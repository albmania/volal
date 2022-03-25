<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MenuKryesoreEdit extends MenuKryesore
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'menu_kryesore';

    // Page object name
    public $PageObjName = "MenuKryesoreEdit";

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
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
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
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
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
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return $this->TableVar == $CurrentForm->getValue("t");
            }
            if (Get("t") !== null) {
                return $this->TableVar == Get("t");
            }
        }
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

        // Table object (menu_kryesore)
        if (!isset($GLOBALS["menu_kryesore"]) || get_class($GLOBALS["menu_kryesore"]) == PROJECT_NAMESPACE . "menu_kryesore") {
            $GLOBALS["menu_kryesore"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'menu_kryesore');
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
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $tbl = Container("menu_kryesore");
                $doc = new $class($tbl);
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
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
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "MenuKryesoreView") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
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
            $key .= @$ar['menukID'];
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
            $this->menukID->Visible = false;
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

    // Properties
    public $FormClassName = "ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param("layout", true));

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->menukID->setVisibility();
        $this->menukGjuha->setVisibility();
        $this->menukTitull->setVisibility();
        $this->menukUrl->setVisibility();
        $this->menukBlank->setVisibility();
        $this->menukRadhe->setVisibility();
        $this->menukAktiv->setVisibility();
        $this->menukAutor->setVisibility();
        $this->menukKrijuar->Visible = false;
        $this->menukAzhornuar->setVisibility();
        $this->hideFieldsForAddEdit();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->menukGjuha);
        $this->setupLookupOptions($this->menukBlank);
        $this->setupLookupOptions($this->menukAktiv);
        $this->setupLookupOptions($this->menukAutor);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form";
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("menukID") ?? Key(0) ?? Route(2)) !== null) {
                $this->menukID->setQueryStringValue($keyValue);
                $this->menukID->setOldValue($this->menukID->QueryStringValue);
            } elseif (Post("menukID") !== null) {
                $this->menukID->setFormValue(Post("menukID"));
                $this->menukID->setOldValue($this->menukID->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("menukID") ?? Route("menukID")) !== null) {
                    $this->menukID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->menukID->CurrentValue = null;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("MenuKryesoreList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "MenuKryesoreList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
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

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'menukID' first before field var 'x_menukID'
        $val = $CurrentForm->hasValue("menukID") ? $CurrentForm->getValue("menukID") : $CurrentForm->getValue("x_menukID");
        if (!$this->menukID->IsDetailKey) {
            $this->menukID->setFormValue($val);
        }

        // Check field name 'menukGjuha' first before field var 'x_menukGjuha'
        $val = $CurrentForm->hasValue("menukGjuha") ? $CurrentForm->getValue("menukGjuha") : $CurrentForm->getValue("x_menukGjuha");
        if (!$this->menukGjuha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menukGjuha->Visible = false; // Disable update for API request
            } else {
                $this->menukGjuha->setFormValue($val);
            }
        }

        // Check field name 'menukTitull' first before field var 'x_menukTitull'
        $val = $CurrentForm->hasValue("menukTitull") ? $CurrentForm->getValue("menukTitull") : $CurrentForm->getValue("x_menukTitull");
        if (!$this->menukTitull->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menukTitull->Visible = false; // Disable update for API request
            } else {
                $this->menukTitull->setFormValue($val);
            }
        }

        // Check field name 'menukUrl' first before field var 'x_menukUrl'
        $val = $CurrentForm->hasValue("menukUrl") ? $CurrentForm->getValue("menukUrl") : $CurrentForm->getValue("x_menukUrl");
        if (!$this->menukUrl->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menukUrl->Visible = false; // Disable update for API request
            } else {
                $this->menukUrl->setFormValue($val);
            }
        }

        // Check field name 'menukBlank' first before field var 'x_menukBlank'
        $val = $CurrentForm->hasValue("menukBlank") ? $CurrentForm->getValue("menukBlank") : $CurrentForm->getValue("x_menukBlank");
        if (!$this->menukBlank->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menukBlank->Visible = false; // Disable update for API request
            } else {
                $this->menukBlank->setFormValue($val);
            }
        }

        // Check field name 'menukRadhe' first before field var 'x_menukRadhe'
        $val = $CurrentForm->hasValue("menukRadhe") ? $CurrentForm->getValue("menukRadhe") : $CurrentForm->getValue("x_menukRadhe");
        if (!$this->menukRadhe->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menukRadhe->Visible = false; // Disable update for API request
            } else {
                $this->menukRadhe->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'menukAktiv' first before field var 'x_menukAktiv'
        $val = $CurrentForm->hasValue("menukAktiv") ? $CurrentForm->getValue("menukAktiv") : $CurrentForm->getValue("x_menukAktiv");
        if (!$this->menukAktiv->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menukAktiv->Visible = false; // Disable update for API request
            } else {
                $this->menukAktiv->setFormValue($val);
            }
        }

        // Check field name 'menukAutor' first before field var 'x_menukAutor'
        $val = $CurrentForm->hasValue("menukAutor") ? $CurrentForm->getValue("menukAutor") : $CurrentForm->getValue("x_menukAutor");
        if (!$this->menukAutor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menukAutor->Visible = false; // Disable update for API request
            } else {
                $this->menukAutor->setFormValue($val);
            }
        }

        // Check field name 'menukAzhornuar' first before field var 'x_menukAzhornuar'
        $val = $CurrentForm->hasValue("menukAzhornuar") ? $CurrentForm->getValue("menukAzhornuar") : $CurrentForm->getValue("x_menukAzhornuar");
        if (!$this->menukAzhornuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menukAzhornuar->Visible = false; // Disable update for API request
            } else {
                $this->menukAzhornuar->setFormValue($val);
            }
            $this->menukAzhornuar->CurrentValue = UnFormatDateTime($this->menukAzhornuar->CurrentValue, $this->menukAzhornuar->formatPattern());
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->menukID->CurrentValue = $this->menukID->FormValue;
        $this->menukGjuha->CurrentValue = $this->menukGjuha->FormValue;
        $this->menukTitull->CurrentValue = $this->menukTitull->FormValue;
        $this->menukUrl->CurrentValue = $this->menukUrl->FormValue;
        $this->menukBlank->CurrentValue = $this->menukBlank->FormValue;
        $this->menukRadhe->CurrentValue = $this->menukRadhe->FormValue;
        $this->menukAktiv->CurrentValue = $this->menukAktiv->FormValue;
        $this->menukAutor->CurrentValue = $this->menukAutor->FormValue;
        $this->menukAzhornuar->CurrentValue = $this->menukAzhornuar->FormValue;
        $this->menukAzhornuar->CurrentValue = UnFormatDateTime($this->menukAzhornuar->CurrentValue, $this->menukAzhornuar->formatPattern());
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
        $this->menukID->setDbValue($row['menukID']);
        $this->menukGjuha->setDbValue($row['menukGjuha']);
        $this->menukTitull->setDbValue($row['menukTitull']);
        $this->menukUrl->setDbValue($row['menukUrl']);
        $this->menukBlank->setDbValue($row['menukBlank']);
        $this->menukRadhe->setDbValue($row['menukRadhe']);
        $this->menukAktiv->setDbValue($row['menukAktiv']);
        $this->menukAutor->setDbValue($row['menukAutor']);
        $this->menukKrijuar->setDbValue($row['menukKrijuar']);
        $this->menukAzhornuar->setDbValue($row['menukAzhornuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['menukID'] = $this->menukID->DefaultValue;
        $row['menukGjuha'] = $this->menukGjuha->DefaultValue;
        $row['menukTitull'] = $this->menukTitull->DefaultValue;
        $row['menukUrl'] = $this->menukUrl->DefaultValue;
        $row['menukBlank'] = $this->menukBlank->DefaultValue;
        $row['menukRadhe'] = $this->menukRadhe->DefaultValue;
        $row['menukAktiv'] = $this->menukAktiv->DefaultValue;
        $row['menukAutor'] = $this->menukAutor->DefaultValue;
        $row['menukKrijuar'] = $this->menukKrijuar->DefaultValue;
        $row['menukAzhornuar'] = $this->menukAzhornuar->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // menukID
        $this->menukID->RowCssClass = "row";

        // menukGjuha
        $this->menukGjuha->RowCssClass = "row";

        // menukTitull
        $this->menukTitull->RowCssClass = "row";

        // menukUrl
        $this->menukUrl->RowCssClass = "row";

        // menukBlank
        $this->menukBlank->RowCssClass = "row";

        // menukRadhe
        $this->menukRadhe->RowCssClass = "row";

        // menukAktiv
        $this->menukAktiv->RowCssClass = "row";

        // menukAutor
        $this->menukAutor->RowCssClass = "row";

        // menukKrijuar
        $this->menukKrijuar->RowCssClass = "row";

        // menukAzhornuar
        $this->menukAzhornuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // menukID
            $this->menukID->ViewValue = $this->menukID->CurrentValue;
            $this->menukID->ViewCustomAttributes = "";

            // menukGjuha
            if (strval($this->menukGjuha->CurrentValue) != "") {
                $this->menukGjuha->ViewValue = $this->menukGjuha->optionCaption($this->menukGjuha->CurrentValue);
            } else {
                $this->menukGjuha->ViewValue = null;
            }
            $this->menukGjuha->ViewCustomAttributes = "";

            // menukTitull
            $this->menukTitull->ViewValue = $this->menukTitull->CurrentValue;
            $this->menukTitull->ViewCustomAttributes = "";

            // menukUrl
            $this->menukUrl->ViewValue = $this->menukUrl->CurrentValue;
            $this->menukUrl->ViewCustomAttributes = "";

            // menukBlank
            if (strval($this->menukBlank->CurrentValue) != "") {
                $this->menukBlank->ViewValue = $this->menukBlank->optionCaption($this->menukBlank->CurrentValue);
            } else {
                $this->menukBlank->ViewValue = null;
            }
            $this->menukBlank->ViewCustomAttributes = "";

            // menukRadhe
            $this->menukRadhe->ViewValue = $this->menukRadhe->CurrentValue;
            $this->menukRadhe->ViewValue = FormatNumber($this->menukRadhe->ViewValue, $this->menukRadhe->formatPattern());
            $this->menukRadhe->ViewCustomAttributes = "";

            // menukAktiv
            if (strval($this->menukAktiv->CurrentValue) != "") {
                $this->menukAktiv->ViewValue = $this->menukAktiv->optionCaption($this->menukAktiv->CurrentValue);
            } else {
                $this->menukAktiv->ViewValue = null;
            }
            $this->menukAktiv->ViewCustomAttributes = "";

            // menukAutor
            $this->menukAutor->ViewValue = $this->menukAutor->CurrentValue;
            $curVal = strval($this->menukAutor->CurrentValue);
            if ($curVal != "") {
                $this->menukAutor->ViewValue = $this->menukAutor->lookupCacheOption($curVal);
                if ($this->menukAutor->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->menukAutor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->menukAutor->Lookup->renderViewRow($rswrk[0]);
                        $this->menukAutor->ViewValue = $this->menukAutor->displayValue($arwrk);
                    } else {
                        $this->menukAutor->ViewValue = FormatNumber($this->menukAutor->CurrentValue, $this->menukAutor->formatPattern());
                    }
                }
            } else {
                $this->menukAutor->ViewValue = null;
            }
            $this->menukAutor->ViewCustomAttributes = "";

            // menukKrijuar
            $this->menukKrijuar->ViewValue = $this->menukKrijuar->CurrentValue;
            $this->menukKrijuar->ViewValue = FormatDateTime($this->menukKrijuar->ViewValue, $this->menukKrijuar->formatPattern());
            $this->menukKrijuar->ViewCustomAttributes = "";

            // menukAzhornuar
            $this->menukAzhornuar->ViewValue = $this->menukAzhornuar->CurrentValue;
            $this->menukAzhornuar->ViewValue = FormatDateTime($this->menukAzhornuar->ViewValue, $this->menukAzhornuar->formatPattern());
            $this->menukAzhornuar->ViewCustomAttributes = "";

            // menukID
            $this->menukID->LinkCustomAttributes = "";
            $this->menukID->HrefValue = "";

            // menukGjuha
            $this->menukGjuha->LinkCustomAttributes = "";
            $this->menukGjuha->HrefValue = "";

            // menukTitull
            $this->menukTitull->LinkCustomAttributes = "";
            $this->menukTitull->HrefValue = "";

            // menukUrl
            $this->menukUrl->LinkCustomAttributes = "";
            $this->menukUrl->HrefValue = "";

            // menukBlank
            $this->menukBlank->LinkCustomAttributes = "";
            $this->menukBlank->HrefValue = "";

            // menukRadhe
            $this->menukRadhe->LinkCustomAttributes = "";
            $this->menukRadhe->HrefValue = "";

            // menukAktiv
            $this->menukAktiv->LinkCustomAttributes = "";
            $this->menukAktiv->HrefValue = "";

            // menukAutor
            $this->menukAutor->LinkCustomAttributes = "";
            $this->menukAutor->HrefValue = "";

            // menukAzhornuar
            $this->menukAzhornuar->LinkCustomAttributes = "";
            $this->menukAzhornuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // menukID
            $this->menukID->setupEditAttributes();
            $this->menukID->EditCustomAttributes = "";
            $this->menukID->EditValue = $this->menukID->CurrentValue;
            $this->menukID->ViewCustomAttributes = "";

            // menukGjuha
            $this->menukGjuha->EditCustomAttributes = "";
            $this->menukGjuha->EditValue = $this->menukGjuha->options(false);
            $this->menukGjuha->PlaceHolder = RemoveHtml($this->menukGjuha->caption());

            // menukTitull
            $this->menukTitull->setupEditAttributes();
            $this->menukTitull->EditCustomAttributes = "";
            if (!$this->menukTitull->Raw) {
                $this->menukTitull->CurrentValue = HtmlDecode($this->menukTitull->CurrentValue);
            }
            $this->menukTitull->EditValue = HtmlEncode($this->menukTitull->CurrentValue);
            $this->menukTitull->PlaceHolder = RemoveHtml($this->menukTitull->caption());

            // menukUrl
            $this->menukUrl->setupEditAttributes();
            $this->menukUrl->EditCustomAttributes = "";
            if (!$this->menukUrl->Raw) {
                $this->menukUrl->CurrentValue = HtmlDecode($this->menukUrl->CurrentValue);
            }
            $this->menukUrl->EditValue = HtmlEncode($this->menukUrl->CurrentValue);
            $this->menukUrl->PlaceHolder = RemoveHtml($this->menukUrl->caption());

            // menukBlank
            $this->menukBlank->EditCustomAttributes = "";
            $this->menukBlank->EditValue = $this->menukBlank->options(false);
            $this->menukBlank->PlaceHolder = RemoveHtml($this->menukBlank->caption());

            // menukRadhe
            $this->menukRadhe->setupEditAttributes();
            $this->menukRadhe->EditCustomAttributes = "";
            $this->menukRadhe->EditValue = HtmlEncode($this->menukRadhe->CurrentValue);
            $this->menukRadhe->PlaceHolder = RemoveHtml($this->menukRadhe->caption());
            if (strval($this->menukRadhe->EditValue) != "" && is_numeric($this->menukRadhe->EditValue)) {
                $this->menukRadhe->EditValue = FormatNumber($this->menukRadhe->EditValue, null);
            }

            // menukAktiv
            $this->menukAktiv->EditCustomAttributes = "";
            $this->menukAktiv->EditValue = $this->menukAktiv->options(false);
            $this->menukAktiv->PlaceHolder = RemoveHtml($this->menukAktiv->caption());

            // menukAutor

            // menukAzhornuar

            // Edit refer script

            // menukID
            $this->menukID->LinkCustomAttributes = "";
            $this->menukID->HrefValue = "";

            // menukGjuha
            $this->menukGjuha->LinkCustomAttributes = "";
            $this->menukGjuha->HrefValue = "";

            // menukTitull
            $this->menukTitull->LinkCustomAttributes = "";
            $this->menukTitull->HrefValue = "";

            // menukUrl
            $this->menukUrl->LinkCustomAttributes = "";
            $this->menukUrl->HrefValue = "";

            // menukBlank
            $this->menukBlank->LinkCustomAttributes = "";
            $this->menukBlank->HrefValue = "";

            // menukRadhe
            $this->menukRadhe->LinkCustomAttributes = "";
            $this->menukRadhe->HrefValue = "";

            // menukAktiv
            $this->menukAktiv->LinkCustomAttributes = "";
            $this->menukAktiv->HrefValue = "";

            // menukAutor
            $this->menukAutor->LinkCustomAttributes = "";
            $this->menukAutor->HrefValue = "";

            // menukAzhornuar
            $this->menukAzhornuar->LinkCustomAttributes = "";
            $this->menukAzhornuar->HrefValue = "";
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
        if ($this->menukID->Required) {
            if (!$this->menukID->IsDetailKey && EmptyValue($this->menukID->FormValue)) {
                $this->menukID->addErrorMessage(str_replace("%s", $this->menukID->caption(), $this->menukID->RequiredErrorMessage));
            }
        }
        if ($this->menukGjuha->Required) {
            if ($this->menukGjuha->FormValue == "") {
                $this->menukGjuha->addErrorMessage(str_replace("%s", $this->menukGjuha->caption(), $this->menukGjuha->RequiredErrorMessage));
            }
        }
        if ($this->menukTitull->Required) {
            if (!$this->menukTitull->IsDetailKey && EmptyValue($this->menukTitull->FormValue)) {
                $this->menukTitull->addErrorMessage(str_replace("%s", $this->menukTitull->caption(), $this->menukTitull->RequiredErrorMessage));
            }
        }
        if ($this->menukUrl->Required) {
            if (!$this->menukUrl->IsDetailKey && EmptyValue($this->menukUrl->FormValue)) {
                $this->menukUrl->addErrorMessage(str_replace("%s", $this->menukUrl->caption(), $this->menukUrl->RequiredErrorMessage));
            }
        }
        if ($this->menukBlank->Required) {
            if ($this->menukBlank->FormValue == "") {
                $this->menukBlank->addErrorMessage(str_replace("%s", $this->menukBlank->caption(), $this->menukBlank->RequiredErrorMessage));
            }
        }
        if ($this->menukRadhe->Required) {
            if (!$this->menukRadhe->IsDetailKey && EmptyValue($this->menukRadhe->FormValue)) {
                $this->menukRadhe->addErrorMessage(str_replace("%s", $this->menukRadhe->caption(), $this->menukRadhe->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->menukRadhe->FormValue)) {
            $this->menukRadhe->addErrorMessage($this->menukRadhe->getErrorMessage(false));
        }
        if ($this->menukAktiv->Required) {
            if ($this->menukAktiv->FormValue == "") {
                $this->menukAktiv->addErrorMessage(str_replace("%s", $this->menukAktiv->caption(), $this->menukAktiv->RequiredErrorMessage));
            }
        }
        if ($this->menukAutor->Required) {
            if (!$this->menukAutor->IsDetailKey && EmptyValue($this->menukAutor->FormValue)) {
                $this->menukAutor->addErrorMessage(str_replace("%s", $this->menukAutor->caption(), $this->menukAutor->RequiredErrorMessage));
            }
        }
        if ($this->menukAzhornuar->Required) {
            if (!$this->menukAzhornuar->IsDetailKey && EmptyValue($this->menukAzhornuar->FormValue)) {
                $this->menukAzhornuar->addErrorMessage(str_replace("%s", $this->menukAzhornuar->caption(), $this->menukAzhornuar->RequiredErrorMessage));
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
        }

        // Set new row
        $rsnew = [];

        // menukGjuha
        $this->menukGjuha->setDbValueDef($rsnew, $this->menukGjuha->CurrentValue, "", $this->menukGjuha->ReadOnly);

        // menukTitull
        $this->menukTitull->setDbValueDef($rsnew, $this->menukTitull->CurrentValue, "", $this->menukTitull->ReadOnly);

        // menukUrl
        $this->menukUrl->setDbValueDef($rsnew, $this->menukUrl->CurrentValue, "", $this->menukUrl->ReadOnly);

        // menukBlank
        $this->menukBlank->setDbValueDef($rsnew, $this->menukBlank->CurrentValue, "", $this->menukBlank->ReadOnly);

        // menukRadhe
        $this->menukRadhe->setDbValueDef($rsnew, $this->menukRadhe->CurrentValue, 0, $this->menukRadhe->ReadOnly);

        // menukAktiv
        $this->menukAktiv->setDbValueDef($rsnew, $this->menukAktiv->CurrentValue, null, $this->menukAktiv->ReadOnly);

        // menukAutor
        $this->menukAutor->CurrentValue = CurrentUserID();
        $this->menukAutor->setDbValueDef($rsnew, $this->menukAutor->CurrentValue, 0);

        // menukAzhornuar
        $this->menukAzhornuar->CurrentValue = CurrentDateTime();
        $this->menukAzhornuar->setDbValueDef($rsnew, $this->menukAzhornuar->CurrentValue, null);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MenuKryesoreList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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
                case "x_menukGjuha":
                    break;
                case "x_menukBlank":
                    break;
                case "x_menukAktiv":
                    break;
                case "x_menukAutor":
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            if ($startRec !== null && is_numeric($startRec)) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
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
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
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

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
