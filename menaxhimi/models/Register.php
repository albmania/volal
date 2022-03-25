<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class Register extends Perdoruesit
{
    use MessagesTrait;

    // Page ID
    public $PageID = "register";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Page object name
    public $PageObjName = "Register";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = $route->getArguments();
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        $url = rtrim(UrlFor($route->getName(), $args), "/") . "?";
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (perdoruesit)
        if (!isset($GLOBALS["perdoruesit"]) || get_class($GLOBALS["perdoruesit"]) == PROJECT_NAMESPACE . "perdoruesit") {
            $GLOBALS["perdoruesit"] = &$this;
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => $url];
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['perdID'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->perdID->Visible = false;
        }
    }

    // Lookup data
    public function lookup($ar = null)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $ar["field"] ?? Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = $ar["ajax"] ?? Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $ar["q"] ?? Param("q") ?? $ar["sv"] ?? Post("sv", "");
            $pageSize = $ar["n"] ?? Param("n") ?? $ar["recperpage"] ?? Post("recperpage", 10);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $ar["q"] ?? Param("q", "");
            $pageSize = $ar["n"] ?? Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $ar["start"] ?? Param("start", -1);
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $ar["page"] ?? Param("page", -1);
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($ar["s"] ?? Post("s", ""));
        $userFilter = Decrypt($ar["f"] ?? Post("f", ""));
        $userOrderBy = Decrypt($ar["o"] ?? Post("o", ""));
        $keys = $ar["keys"] ?? Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $ar["v0"] ?? $ar["lookupValue"] ?? Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $ar["v" . $i] ?? Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, !is_array($ar)); // Use settings from current page
    }
    public $FormClassName = "ew-form ew-register-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $UserTable, $CurrentLanguage, $Breadcrumb, $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param("layout", true));

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-register-form";

        // Set up Breadcrumb
        $Breadcrumb = new Breadcrumb("index");
        $Breadcrumb->add("register", "RegisterPage", CurrentUrl(), "", "", true);
        $this->Heading = $Language->phrase("RegisterPage");
        $userExists = false;
        $this->loadRowValues(); // Load default values

        // Get action
        $action = "";
        if (IsApi()) {
            $action = "insert";
        } elseif (Post("action") != "") {
            $action = Post("action");
        }

        // Check action
        if ($action != "") {
            // Get action
            $this->CurrentAction = $action;
            $this->loadFormValues(); // Get form values

            // Validate form
            if (!$this->validateForm()) {
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        } else {
            $this->CurrentAction = "show"; // Display blank record
        }

        // Insert record
        if ($this->isInsert()) {
            // Check for duplicate User ID
            $filter = GetUserFilter(Config("LOGIN_USERNAME_FIELD_NAME"), $this->perdUsername->CurrentValue);
            // Set up filter (WHERE Clause)
            $this->CurrentFilter = $filter;
            $userSql = $this->getCurrentSql();
            $rs = Conn($UserTable->Dbid)->executeQuery($userSql);
            if ($rs->fetch()) {
                $userExists = true;
                $this->restoreFormValues(); // Restore form values
                $this->setFailureMessage($Language->phrase("UserExists")); // Set user exist message
            }
            if (!$userExists) {
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow()) { // Add record
                    $email = $this->prepareRegisterEmail();
                    // Get new record
                    $this->CurrentFilter = $this->getRecordFilter();
                    $sql = $this->getCurrentSql();
                    $row = Conn($UserTable->Dbid)->fetchAssociative($sql);
                    $args = [];
                    $args["rs"] = $row;
                    $emailSent = false;
                    if ($this->emailSending($email, $args)) {
                        $emailSent = $email->send();
                    }

                    // Send email failed
                    if (!$emailSent) {
                        $this->setFailureMessage($email->SendErrDescription);
                    }
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("RegisterSuccess")); // Register success
                    }

                    // Auto login user
                    if ($Security->validateUser($this->perdUsername->CurrentValue, $this->perdFjalekalimi->FormValue, true)) {
                        // Nothing to do
                    } else {
                        $this->setFailureMessage($Language->phrase("AutoLoginFailed")); // Set auto login failed message
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        if (Config("USE_TWO_FACTOR_AUTHENTICATION") && Config("FORCE_TWO_FACTOR_AUTHENTICATION")) { // Add two factor authentication
                            $_SESSION[SESSION_STATUS] = "loggingin2fa";
                            $_SESSION[SESSION_USER_PROFILE_USER_NAME] = $this->perdUsername->CurrentValue;
                            $_SESSION[SESSION_USER_PROFILE_PASSWORD] = $this->perdFjalekalimi->FormValue;
                            $this->terminate("login2fa"); // Add two factor authentication
                        } else {
                            $this->terminate("index"); // Return
                        }
                        return;
                    }
                } else {
                    $this->restoreFormValues(); // Restore form values
                }
            }
        }

        // API request, return
        if (IsApi()) {
            $this->terminate();
            return;
        }

        // Render row
        $this->RowType = ROWTYPE_ADD; // Render add
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->perdUsername->DefaultValue = "u1";
        $this->perdNiveliPerdoruesit->DefaultValue = 0;
        $this->perdActivated->DefaultValue = "N";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'perdEmri' first before field var 'x_perdEmri'
        $val = $CurrentForm->hasValue("perdEmri") ? $CurrentForm->getValue("perdEmri") : $CurrentForm->getValue("x_perdEmri");
        if (!$this->perdEmri->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->perdEmri->Visible = false; // Disable update for API request
            } else {
                $this->perdEmri->setFormValue($val);
            }
        }

        // Check field name 'perdUsername' first before field var 'x_perdUsername'
        $val = $CurrentForm->hasValue("perdUsername") ? $CurrentForm->getValue("perdUsername") : $CurrentForm->getValue("x_perdUsername");
        if (!$this->perdUsername->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->perdUsername->Visible = false; // Disable update for API request
            } else {
                $this->perdUsername->setFormValue($val);
            }
        }

        // Check field name 'perdFjalekalimi' first before field var 'x_perdFjalekalimi'
        $val = $CurrentForm->hasValue("perdFjalekalimi") ? $CurrentForm->getValue("perdFjalekalimi") : $CurrentForm->getValue("x_perdFjalekalimi");
        if (!$this->perdFjalekalimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->perdFjalekalimi->Visible = false; // Disable update for API request
            } else {
                $this->perdFjalekalimi->setFormValue($val);
            }
        }

        // Note: ConfirmValue will be compared with FormValue
        if (Config("ENCRYPTED_PASSWORD")) { // Encrypted password, use raw value
            $this->perdFjalekalimi->ConfirmValue = $CurrentForm->getValue("c_perdFjalekalimi");
        } else {
            $this->perdFjalekalimi->ConfirmValue = RemoveXss($CurrentForm->getValue("c_perdFjalekalimi"));
        }

        // Check field name 'perdEmail' first before field var 'x_perdEmail'
        $val = $CurrentForm->hasValue("perdEmail") ? $CurrentForm->getValue("perdEmail") : $CurrentForm->getValue("x_perdEmail");
        if (!$this->perdEmail->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->perdEmail->Visible = false; // Disable update for API request
            } else {
                $this->perdEmail->setFormValue($val);
            }
        }

        // Check field name 'perdID' first before field var 'x_perdID'
        $val = $CurrentForm->hasValue("perdID") ? $CurrentForm->getValue("perdID") : $CurrentForm->getValue("x_perdID");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->perdEmri->CurrentValue = $this->perdEmri->FormValue;
        $this->perdUsername->CurrentValue = $this->perdUsername->FormValue;
        $this->perdFjalekalimi->CurrentValue = $this->perdFjalekalimi->FormValue;
        $this->perdEmail->CurrentValue = $this->perdEmail->FormValue;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }
        if (!$row) {
            return;
        }

        // Call Row Selected event
        $this->rowSelected($row);
        $this->perdID->setDbValue($row['perdID']);
        $this->perdEmri->setDbValue($row['perdEmri']);
        $this->perdUsername->setDbValue($row['perdUsername']);
        $this->perdFjalekalimi->setDbValue($row['perdFjalekalimi']);
        $this->perdEmail->setDbValue($row['perdEmail']);
        $this->perdNiveliPerdoruesit->setDbValue($row['perdNiveliPerdoruesit']);
        $this->perdDtReg->setDbValue($row['perdDtReg']);
        $this->perdActivated->setDbValue($row['perdActivated']);
        $this->perdProfileField->setDbValue($row['perdProfileField']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['perdID'] = $this->perdID->DefaultValue;
        $row['perdEmri'] = $this->perdEmri->DefaultValue;
        $row['perdUsername'] = $this->perdUsername->DefaultValue;
        $row['perdFjalekalimi'] = $this->perdFjalekalimi->DefaultValue;
        $row['perdEmail'] = $this->perdEmail->DefaultValue;
        $row['perdNiveliPerdoruesit'] = $this->perdNiveliPerdoruesit->DefaultValue;
        $row['perdDtReg'] = $this->perdDtReg->DefaultValue;
        $row['perdActivated'] = $this->perdActivated->DefaultValue;
        $row['perdProfileField'] = $this->perdProfileField->DefaultValue;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // perdID
        $this->perdID->RowCssClass = "row";

        // perdEmri
        $this->perdEmri->RowCssClass = "row";

        // perdUsername
        $this->perdUsername->RowCssClass = "row";

        // perdFjalekalimi
        $this->perdFjalekalimi->RowCssClass = "row";

        // perdEmail
        $this->perdEmail->RowCssClass = "row";

        // perdNiveliPerdoruesit
        $this->perdNiveliPerdoruesit->RowCssClass = "row";

        // perdDtReg
        $this->perdDtReg->RowCssClass = "row";

        // perdActivated
        $this->perdActivated->RowCssClass = "row";

        // perdProfileField
        $this->perdProfileField->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // perdID
            $this->perdID->ViewValue = $this->perdID->CurrentValue;
            $this->perdID->ViewCustomAttributes = "";

            // perdEmri
            $this->perdEmri->ViewValue = $this->perdEmri->CurrentValue;
            $this->perdEmri->ViewCustomAttributes = "";

            // perdUsername
            $this->perdUsername->ViewValue = $this->perdUsername->CurrentValue;
            $this->perdUsername->ViewCustomAttributes = "";

            // perdFjalekalimi
            $this->perdFjalekalimi->ViewValue = $this->perdFjalekalimi->CurrentValue;
            $this->perdFjalekalimi->ViewCustomAttributes = "";

            // perdEmail
            $this->perdEmail->ViewValue = $this->perdEmail->CurrentValue;
            $this->perdEmail->ViewCustomAttributes = "";

            // perdNiveliPerdoruesit
            if ($Security->canAdmin()) { // System admin
                $curVal = strval($this->perdNiveliPerdoruesit->CurrentValue);
                if ($curVal != "") {
                    $this->perdNiveliPerdoruesit->ViewValue = $this->perdNiveliPerdoruesit->lookupCacheOption($curVal);
                    if ($this->perdNiveliPerdoruesit->ViewValue === null) { // Lookup from database
                        $filterWrk = "`userlevelid`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->perdNiveliPerdoruesit->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->perdNiveliPerdoruesit->Lookup->renderViewRow($rswrk[0]);
                            $this->perdNiveliPerdoruesit->ViewValue = $this->perdNiveliPerdoruesit->displayValue($arwrk);
                        } else {
                            $this->perdNiveliPerdoruesit->ViewValue = FormatNumber($this->perdNiveliPerdoruesit->CurrentValue, $this->perdNiveliPerdoruesit->formatPattern());
                        }
                    }
                } else {
                    $this->perdNiveliPerdoruesit->ViewValue = null;
                }
            } else {
                $this->perdNiveliPerdoruesit->ViewValue = $Language->phrase("PasswordMask");
            }
            $this->perdNiveliPerdoruesit->ViewCustomAttributes = "";

            // perdDtReg
            $this->perdDtReg->ViewValue = $this->perdDtReg->CurrentValue;
            $this->perdDtReg->ViewValue = FormatDateTime($this->perdDtReg->ViewValue, $this->perdDtReg->formatPattern());
            $this->perdDtReg->ViewCustomAttributes = "";

            // perdActivated
            $this->perdActivated->ViewValue = $this->perdActivated->CurrentValue;
            $this->perdActivated->ViewCustomAttributes = "";

            // perdEmri
            $this->perdEmri->LinkCustomAttributes = "";
            $this->perdEmri->HrefValue = "";
            $this->perdEmri->TooltipValue = "";

            // perdUsername
            $this->perdUsername->LinkCustomAttributes = "";
            $this->perdUsername->HrefValue = "";
            $this->perdUsername->TooltipValue = "";

            // perdFjalekalimi
            $this->perdFjalekalimi->LinkCustomAttributes = "";
            $this->perdFjalekalimi->HrefValue = "";
            $this->perdFjalekalimi->TooltipValue = "";

            // perdEmail
            $this->perdEmail->LinkCustomAttributes = "";
            $this->perdEmail->HrefValue = "";
            $this->perdEmail->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // perdEmri
            $this->perdEmri->setupEditAttributes();
            $this->perdEmri->EditCustomAttributes = "";
            if (!$this->perdEmri->Raw) {
                $this->perdEmri->CurrentValue = HtmlDecode($this->perdEmri->CurrentValue);
            }
            $this->perdEmri->EditValue = HtmlEncode($this->perdEmri->CurrentValue);
            $this->perdEmri->PlaceHolder = RemoveHtml($this->perdEmri->caption());

            // perdUsername
            $this->perdUsername->setupEditAttributes();
            $this->perdUsername->EditCustomAttributes = "";
            if (!$this->perdUsername->Raw) {
                $this->perdUsername->CurrentValue = HtmlDecode($this->perdUsername->CurrentValue);
            }
            $this->perdUsername->EditValue = HtmlEncode($this->perdUsername->CurrentValue);
            $this->perdUsername->PlaceHolder = RemoveHtml($this->perdUsername->caption());

            // perdFjalekalimi
            $this->perdFjalekalimi->setupEditAttributes();
            $this->perdFjalekalimi->EditCustomAttributes = "";
            if (!$this->perdFjalekalimi->Raw) {
                $this->perdFjalekalimi->CurrentValue = HtmlDecode($this->perdFjalekalimi->CurrentValue);
            }
            $this->perdFjalekalimi->EditValue = HtmlEncode($this->perdFjalekalimi->CurrentValue);
            $this->perdFjalekalimi->PlaceHolder = RemoveHtml($this->perdFjalekalimi->caption());

            // perdEmail
            $this->perdEmail->setupEditAttributes();
            $this->perdEmail->EditCustomAttributes = "";
            if (!$this->perdEmail->Raw) {
                $this->perdEmail->CurrentValue = HtmlDecode($this->perdEmail->CurrentValue);
            }
            $this->perdEmail->EditValue = HtmlEncode($this->perdEmail->CurrentValue);
            $this->perdEmail->PlaceHolder = RemoveHtml($this->perdEmail->caption());

            // Add refer script

            // perdEmri
            $this->perdEmri->LinkCustomAttributes = "";
            $this->perdEmri->HrefValue = "";

            // perdUsername
            $this->perdUsername->LinkCustomAttributes = "";
            $this->perdUsername->HrefValue = "";

            // perdFjalekalimi
            $this->perdFjalekalimi->LinkCustomAttributes = "";
            $this->perdFjalekalimi->HrefValue = "";

            // perdEmail
            $this->perdEmail->LinkCustomAttributes = "";
            $this->perdEmail->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->perdEmri->Required) {
            if (!$this->perdEmri->IsDetailKey && EmptyValue($this->perdEmri->FormValue)) {
                $this->perdEmri->addErrorMessage(str_replace("%s", $this->perdEmri->caption(), $this->perdEmri->RequiredErrorMessage));
            }
        }
        if ($this->perdUsername->Required) {
            if (!$this->perdUsername->IsDetailKey && EmptyValue($this->perdUsername->FormValue)) {
                $this->perdUsername->addErrorMessage($Language->phrase("EnterUserName"));
            }
        }
        if (!$this->perdUsername->Raw && Config("REMOVE_XSS") && CheckUsername($this->perdUsername->FormValue)) {
            $this->perdUsername->addErrorMessage($Language->phrase("InvalidUsernameChars"));
        }
        if ($this->perdFjalekalimi->Required) {
            if (!$this->perdFjalekalimi->IsDetailKey && EmptyValue($this->perdFjalekalimi->FormValue)) {
                $this->perdFjalekalimi->addErrorMessage($Language->phrase("EnterPassword"));
            }
        }
        if (!$this->perdFjalekalimi->Raw && Config("REMOVE_XSS") && CheckPassword($this->perdFjalekalimi->FormValue)) {
            $this->perdFjalekalimi->addErrorMessage($Language->phrase("InvalidPasswordChars"));
        }
        if ($this->perdEmail->Required) {
            if (!$this->perdEmail->IsDetailKey && EmptyValue($this->perdEmail->FormValue)) {
                $this->perdEmail->addErrorMessage(str_replace("%s", $this->perdEmail->caption(), $this->perdEmail->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set new row
        $rsnew = [];

        // perdEmri
        $this->perdEmri->setDbValueDef($rsnew, $this->perdEmri->CurrentValue, null, false);

        // perdUsername
        $this->perdUsername->setDbValueDef($rsnew, $this->perdUsername->CurrentValue, null, strval($this->perdUsername->CurrentValue ?? "") == "");

        // perdFjalekalimi
        $this->perdFjalekalimi->setDbValueDef($rsnew, $this->perdFjalekalimi->CurrentValue, "", false);

        // perdEmail
        $this->perdEmail->setDbValueDef($rsnew, $this->perdEmail->CurrentValue, "", false);

        // perdID

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check if valid User ID
        $validUser = false;
        if ($Security->currentUserID() != "" && !EmptyValue($this->perdID->CurrentValue) && !$Security->isAdmin()) { // Non system admin
            $validUser = $Security->isValidUserID($this->perdID->CurrentValue);
            if (!$validUser) {
                $userIdMsg = str_replace("%c", CurrentUserID(), $Language->phrase("UnAuthorizedUserID"));
                $userIdMsg = str_replace("%u", $this->perdID->CurrentValue, $userIdMsg);
                $this->setFailureMessage($userIdMsg);
                return false;
            }
        }
        if ($this->perdUsername->CurrentValue != "") { // Check field with unique index
            $filter = "(`perdUsername` = '" . AdjustSql($this->perdUsername->CurrentValue, $this->Dbid) . "')";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->perdUsername->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->perdUsername->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);

            // Call User Registered event
            $this->userRegistered($rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_perdNiveliPerdoruesit":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $ar[strval($row["lf"])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'
    public function messageShowing(&$msg, $type)
    {
        // Example:
        //if ($type == 'success') $msg = "your success message";
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }

    // User Registered event
    public function userRegistered(&$rs)
    {
        //Log("User_Registered");
    }

    // User Activated event
    public function userActivated(&$rs)
    {
        //Log("User_Activated");
    }
}
