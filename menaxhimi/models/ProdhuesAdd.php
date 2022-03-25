<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ProdhuesAdd extends Prodhues
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'prodhues';

    // Page object name
    public $PageObjName = "ProdhuesAdd";

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

        // Table object (prodhues)
        if (!isset($GLOBALS["prodhues"]) || get_class($GLOBALS["prodhues"]) == PROJECT_NAMESPACE . "prodhues") {
            $GLOBALS["prodhues"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'prodhues');
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
                $tbl = Container("prodhues");
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
                    if ($pageName == "ProdhuesView") {
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
		        $this->prodhuesLogo->OldUploadPath = '../ngarkime/prodhues/';
		        $this->prodhuesLogo->UploadPath = $this->prodhuesLogo->OldUploadPath;
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
            $key .= @$ar['prodhuesID'];
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
            $this->prodhuesID->Visible = false;
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
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;

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
        $this->prodhuesID->Visible = false;
        $this->prodhuesEmri->setVisibility();
        $this->prodhuesLogo->setVisibility();
        $this->prodhuesPershkrim->setVisibility();
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

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-add-form";
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("prodhuesID") ?? Route("prodhuesID")) !== null) {
                $this->prodhuesID->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record / default values
        $loaded = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$loaded) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("ProdhuesList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "ProdhuesList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ProdhuesView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
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
        $this->prodhuesLogo->Upload->Index = $CurrentForm->Index;
        $this->prodhuesLogo->Upload->uploadFile();
        $this->prodhuesLogo->CurrentValue = $this->prodhuesLogo->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'prodhuesEmri' first before field var 'x_prodhuesEmri'
        $val = $CurrentForm->hasValue("prodhuesEmri") ? $CurrentForm->getValue("prodhuesEmri") : $CurrentForm->getValue("x_prodhuesEmri");
        if (!$this->prodhuesEmri->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->prodhuesEmri->Visible = false; // Disable update for API request
            } else {
                $this->prodhuesEmri->setFormValue($val);
            }
        }

        // Check field name 'prodhuesPershkrim' first before field var 'x_prodhuesPershkrim'
        $val = $CurrentForm->hasValue("prodhuesPershkrim") ? $CurrentForm->getValue("prodhuesPershkrim") : $CurrentForm->getValue("x_prodhuesPershkrim");
        if (!$this->prodhuesPershkrim->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->prodhuesPershkrim->Visible = false; // Disable update for API request
            } else {
                $this->prodhuesPershkrim->setFormValue($val);
            }
        }

        // Check field name 'prodhuesID' first before field var 'x_prodhuesID'
        $val = $CurrentForm->hasValue("prodhuesID") ? $CurrentForm->getValue("prodhuesID") : $CurrentForm->getValue("x_prodhuesID");
		$this->prodhuesLogo->OldUploadPath = '../ngarkime/prodhues/';
		$this->prodhuesLogo->UploadPath = $this->prodhuesLogo->OldUploadPath;
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->prodhuesEmri->CurrentValue = $this->prodhuesEmri->FormValue;
        $this->prodhuesPershkrim->CurrentValue = $this->prodhuesPershkrim->FormValue;
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
        $this->prodhuesID->setDbValue($row['prodhuesID']);
        $this->prodhuesEmri->setDbValue($row['prodhuesEmri']);
        $this->prodhuesLogo->Upload->DbValue = $row['prodhuesLogo'];
        $this->prodhuesLogo->setDbValue($this->prodhuesLogo->Upload->DbValue);
        $this->prodhuesPershkrim->setDbValue($row['prodhuesPershkrim']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['prodhuesID'] = $this->prodhuesID->DefaultValue;
        $row['prodhuesEmri'] = $this->prodhuesEmri->DefaultValue;
        $row['prodhuesLogo'] = $this->prodhuesLogo->DefaultValue;
        $row['prodhuesPershkrim'] = $this->prodhuesPershkrim->DefaultValue;
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

        // prodhuesID
        $this->prodhuesID->RowCssClass = "row";

        // prodhuesEmri
        $this->prodhuesEmri->RowCssClass = "row";

        // prodhuesLogo
        $this->prodhuesLogo->RowCssClass = "row";

        // prodhuesPershkrim
        $this->prodhuesPershkrim->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // prodhuesID
            $this->prodhuesID->ViewValue = $this->prodhuesID->CurrentValue;
            $this->prodhuesID->ViewCustomAttributes = "";

            // prodhuesEmri
            $this->prodhuesEmri->ViewValue = $this->prodhuesEmri->CurrentValue;
            $this->prodhuesEmri->ViewCustomAttributes = "";

            // prodhuesLogo
            $this->prodhuesLogo->UploadPath = '../ngarkime/prodhues/';
            if (!EmptyValue($this->prodhuesLogo->Upload->DbValue)) {
                $this->prodhuesLogo->ImageWidth = 0;
                $this->prodhuesLogo->ImageHeight = 20;
                $this->prodhuesLogo->ImageAlt = $this->prodhuesLogo->alt();
                $this->prodhuesLogo->ImageCssClass = "ew-image";
                $this->prodhuesLogo->ViewValue = $this->prodhuesLogo->Upload->DbValue;
            } else {
                $this->prodhuesLogo->ViewValue = "";
            }
            $this->prodhuesLogo->ViewCustomAttributes = "";

            // prodhuesPershkrim
            $this->prodhuesPershkrim->ViewValue = $this->prodhuesPershkrim->CurrentValue;
            $this->prodhuesPershkrim->ViewCustomAttributes = "";

            // prodhuesEmri
            $this->prodhuesEmri->LinkCustomAttributes = "";
            $this->prodhuesEmri->HrefValue = "";

            // prodhuesLogo
            $this->prodhuesLogo->LinkCustomAttributes = "";
            $this->prodhuesLogo->UploadPath = '../ngarkime/prodhues/';
            if (!EmptyValue($this->prodhuesLogo->Upload->DbValue)) {
                $this->prodhuesLogo->HrefValue = GetFileUploadUrl($this->prodhuesLogo, $this->prodhuesLogo->htmlDecode($this->prodhuesLogo->Upload->DbValue)); // Add prefix/suffix
                $this->prodhuesLogo->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->prodhuesLogo->HrefValue = FullUrl($this->prodhuesLogo->HrefValue, "href");
                }
            } else {
                $this->prodhuesLogo->HrefValue = "";
            }
            $this->prodhuesLogo->ExportHrefValue = $this->prodhuesLogo->UploadPath . $this->prodhuesLogo->Upload->DbValue;

            // prodhuesPershkrim
            $this->prodhuesPershkrim->LinkCustomAttributes = "";
            $this->prodhuesPershkrim->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // prodhuesEmri
            $this->prodhuesEmri->setupEditAttributes();
            $this->prodhuesEmri->EditCustomAttributes = "";
            if (!$this->prodhuesEmri->Raw) {
                $this->prodhuesEmri->CurrentValue = HtmlDecode($this->prodhuesEmri->CurrentValue);
            }
            $this->prodhuesEmri->EditValue = HtmlEncode($this->prodhuesEmri->CurrentValue);
            $this->prodhuesEmri->PlaceHolder = RemoveHtml($this->prodhuesEmri->caption());

            // prodhuesLogo
            $this->prodhuesLogo->setupEditAttributes();
            $this->prodhuesLogo->EditCustomAttributes = "";
            $this->prodhuesLogo->UploadPath = '../ngarkime/prodhues/';
            if (!EmptyValue($this->prodhuesLogo->Upload->DbValue)) {
                $this->prodhuesLogo->ImageWidth = 0;
                $this->prodhuesLogo->ImageHeight = 20;
                $this->prodhuesLogo->ImageAlt = $this->prodhuesLogo->alt();
                $this->prodhuesLogo->ImageCssClass = "ew-image";
                $this->prodhuesLogo->EditValue = $this->prodhuesLogo->Upload->DbValue;
            } else {
                $this->prodhuesLogo->EditValue = "";
            }
            if (!EmptyValue($this->prodhuesLogo->CurrentValue)) {
                $this->prodhuesLogo->Upload->FileName = $this->prodhuesLogo->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->prodhuesLogo);
            }

            // prodhuesPershkrim
            $this->prodhuesPershkrim->setupEditAttributes();
            $this->prodhuesPershkrim->EditCustomAttributes = "";
            $this->prodhuesPershkrim->EditValue = HtmlEncode($this->prodhuesPershkrim->CurrentValue);
            $this->prodhuesPershkrim->PlaceHolder = RemoveHtml($this->prodhuesPershkrim->caption());

            // Add refer script

            // prodhuesEmri
            $this->prodhuesEmri->LinkCustomAttributes = "";
            $this->prodhuesEmri->HrefValue = "";

            // prodhuesLogo
            $this->prodhuesLogo->LinkCustomAttributes = "";
            $this->prodhuesLogo->UploadPath = '../ngarkime/prodhues/';
            if (!EmptyValue($this->prodhuesLogo->Upload->DbValue)) {
                $this->prodhuesLogo->HrefValue = GetFileUploadUrl($this->prodhuesLogo, $this->prodhuesLogo->htmlDecode($this->prodhuesLogo->Upload->DbValue)); // Add prefix/suffix
                $this->prodhuesLogo->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->prodhuesLogo->HrefValue = FullUrl($this->prodhuesLogo->HrefValue, "href");
                }
            } else {
                $this->prodhuesLogo->HrefValue = "";
            }
            $this->prodhuesLogo->ExportHrefValue = $this->prodhuesLogo->UploadPath . $this->prodhuesLogo->Upload->DbValue;

            // prodhuesPershkrim
            $this->prodhuesPershkrim->LinkCustomAttributes = "";
            $this->prodhuesPershkrim->HrefValue = "";
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
        if ($this->prodhuesEmri->Required) {
            if (!$this->prodhuesEmri->IsDetailKey && EmptyValue($this->prodhuesEmri->FormValue)) {
                $this->prodhuesEmri->addErrorMessage(str_replace("%s", $this->prodhuesEmri->caption(), $this->prodhuesEmri->RequiredErrorMessage));
            }
        }
        if ($this->prodhuesLogo->Required) {
            if ($this->prodhuesLogo->Upload->FileName == "" && !$this->prodhuesLogo->Upload->KeepFile) {
                $this->prodhuesLogo->addErrorMessage(str_replace("%s", $this->prodhuesLogo->caption(), $this->prodhuesLogo->RequiredErrorMessage));
            }
        }
        if ($this->prodhuesPershkrim->Required) {
            if (!$this->prodhuesPershkrim->IsDetailKey && EmptyValue($this->prodhuesPershkrim->FormValue)) {
                $this->prodhuesPershkrim->addErrorMessage(str_replace("%s", $this->prodhuesPershkrim->caption(), $this->prodhuesPershkrim->RequiredErrorMessage));
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

        // prodhuesEmri
        $this->prodhuesEmri->setDbValueDef($rsnew, $this->prodhuesEmri->CurrentValue, "", false);

        // prodhuesLogo
        if ($this->prodhuesLogo->Visible && !$this->prodhuesLogo->Upload->KeepFile) {
            $this->prodhuesLogo->Upload->DbValue = ""; // No need to delete old file
            if ($this->prodhuesLogo->Upload->FileName == "") {
                $rsnew['prodhuesLogo'] = null;
            } else {
                $rsnew['prodhuesLogo'] = $this->prodhuesLogo->Upload->FileName;
            }
            $this->prodhuesLogo->ImageWidth = 0; // Resize width
            $this->prodhuesLogo->ImageHeight = 40; // Resize height
        }

        // prodhuesPershkrim
        $this->prodhuesPershkrim->setDbValueDef($rsnew, $this->prodhuesPershkrim->CurrentValue, null, false);
        if ($this->prodhuesLogo->Visible && !$this->prodhuesLogo->Upload->KeepFile) {
            $this->prodhuesLogo->UploadPath = '../ngarkime/prodhues/';
            $oldFiles = EmptyValue($this->prodhuesLogo->Upload->DbValue) ? [] : [$this->prodhuesLogo->htmlDecode($this->prodhuesLogo->Upload->DbValue)];
            if (!EmptyValue($this->prodhuesLogo->Upload->FileName)) {
                $newFiles = [$this->prodhuesLogo->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->prodhuesLogo, $this->prodhuesLogo->Upload->Index);
                        if (file_exists($tempPath . $file)) {
                            if (Config("DELETE_UPLOADED_FILES")) {
                                $oldFileFound = false;
                                $oldFileCount = count($oldFiles);
                                for ($j = 0; $j < $oldFileCount; $j++) {
                                    $oldFile = $oldFiles[$j];
                                    if ($oldFile == $file) { // Old file found, no need to delete anymore
                                        array_splice($oldFiles, $j, 1);
                                        $oldFileFound = true;
                                        break;
                                    }
                                }
                                if ($oldFileFound) { // No need to check if file exists further
                                    continue;
                                }
                            }
                            $file1 = UniqueFilename($this->prodhuesLogo->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->prodhuesLogo->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->prodhuesLogo->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->prodhuesLogo->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->prodhuesLogo->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->prodhuesLogo->setDbValueDef($rsnew, $this->prodhuesLogo->Upload->FileName, "", false);
            }
        }

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
            $this->prodhuesLogo->OldUploadPath = '../ngarkime/prodhues/';
            $this->prodhuesLogo->UploadPath = $this->prodhuesLogo->OldUploadPath;
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->prodhuesLogo->Visible && !$this->prodhuesLogo->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->prodhuesLogo->Upload->DbValue) ? [] : [$this->prodhuesLogo->htmlDecode($this->prodhuesLogo->Upload->DbValue)];
                    if (!EmptyValue($this->prodhuesLogo->Upload->FileName)) {
                        $newFiles = [$this->prodhuesLogo->Upload->FileName];
                        $newFiles2 = [$this->prodhuesLogo->htmlDecode($rsnew['prodhuesLogo'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->prodhuesLogo, $this->prodhuesLogo->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->prodhuesLogo->Upload->ResizeAndSaveToFile($this->prodhuesLogo->ImageWidth, $this->prodhuesLogo->ImageHeight, 100, $newFiles[$i], true, $i)) {
                                        $this->setFailureMessage($Language->phrase("UploadErrMsg7"));
                                        return false;
                                    }
                                }
                            }
                        }
                    } else {
                        $newFiles = [];
                    }
                    if (Config("DELETE_UPLOADED_FILES")) {
                        foreach ($oldFiles as $oldFile) {
                            if ($oldFile != "" && !in_array($oldFile, $newFiles)) {
                                @unlink($this->prodhuesLogo->oldPhysicalUploadPath() . $oldFile);
                            }
                        }
                    }
                }
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
        }

        // Clean upload path if any
        if ($addRow) {
            // prodhuesLogo
            CleanUploadTempPath($this->prodhuesLogo, $this->prodhuesLogo->Upload->Index);
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
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ProdhuesList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
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
