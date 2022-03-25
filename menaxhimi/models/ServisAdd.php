<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ServisAdd extends Servis
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'servis';

    // Page object name
    public $PageObjName = "ServisAdd";

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

        // Custom template
        $this->UseCustomTemplate = true;

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (servis)
        if (!isset($GLOBALS["servis"]) || get_class($GLOBALS["servis"]) == PROJECT_NAMESPACE . "servis") {
            $GLOBALS["servis"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'servis');
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
        if (Post("customexport") === null) {
             // Page Unload event
            if (method_exists($this, "pageUnload")) {
                $this->pageUnload();
            }

            // Global Page Unloaded event (in userfn*.php)
            Page_Unloaded();
        }

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            if (is_array(Session(SESSION_TEMP_IMAGES))) { // Restore temp images
                $TempImages = Session(SESSION_TEMP_IMAGES);
            }
            if (Post("data") !== null) {
                $content = Post("data");
            }
            $ExportFileName = Post("filename", "");
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $tbl = Container("servis");
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
        if ($this->CustomExport) { // Save temp images array for custom export
            if (is_array($TempImages)) {
                $_SESSION[SESSION_TEMP_IMAGES] = $TempImages;
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
                    if ($pageName == "ServisView") {
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
            $key .= @$ar['servisID'];
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
    public $DetailPages; // Detail pages object

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
        $this->servisID->Visible = false;
        $this->servisDate->setVisibility();
        $this->servisKlienti->setVisibility();
        $this->servisMakina->setVisibility();
        $this->servisKmMakines->setVisibility();
        $this->servisStafi->setVisibility();
        $this->servisTotaliFatures->setVisibility();
        $this->servisAutori->setVisibility();
        $this->servisShtuar->setVisibility();
        $this->servisModifikuar->Visible = false;
        $this->hideFieldsForAddEdit();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Set up detail page object
        $this->setupDetailPages();

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->servisKlienti);
        $this->setupLookupOptions($this->servisMakina);
        $this->setupLookupOptions($this->servisStafi);
        $this->setupLookupOptions($this->servisAutori);

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
            if (($keyValue = Get("servisID") ?? Route("servisID")) !== null) {
                $this->servisID->setQueryStringValue($keyValue);
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

        // Set up detail parameters
        $this->setupDetailParms();

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
                    $this->terminate("ServisList"); // No matching record, return to list
                    return;
                }

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    if ($this->getCurrentDetailTable() != "") { // Master/detail add
                        $returnUrl = $this->getDetailUrl();
                    } else {
                        $returnUrl = $this->getReturnUrl();
                    }
                    if (GetPageName($returnUrl) == "ServisList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ServisView") {
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

                    // Set up detail parameters
                    $this->setupDetailParms();
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
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->servisTotaliFatures->DefaultValue = 0.00;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'servisDate' first before field var 'x_servisDate'
        $val = $CurrentForm->hasValue("servisDate") ? $CurrentForm->getValue("servisDate") : $CurrentForm->getValue("x_servisDate");
        if (!$this->servisDate->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisDate->Visible = false; // Disable update for API request
            } else {
                $this->servisDate->setFormValue($val, true, $validate);
            }
            $this->servisDate->CurrentValue = UnFormatDateTime($this->servisDate->CurrentValue, $this->servisDate->formatPattern());
        }

        // Check field name 'servisKlienti' first before field var 'x_servisKlienti'
        $val = $CurrentForm->hasValue("servisKlienti") ? $CurrentForm->getValue("servisKlienti") : $CurrentForm->getValue("x_servisKlienti");
        if (!$this->servisKlienti->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisKlienti->Visible = false; // Disable update for API request
            } else {
                $this->servisKlienti->setFormValue($val);
            }
        }

        // Check field name 'servisMakina' first before field var 'x_servisMakina'
        $val = $CurrentForm->hasValue("servisMakina") ? $CurrentForm->getValue("servisMakina") : $CurrentForm->getValue("x_servisMakina");
        if (!$this->servisMakina->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisMakina->Visible = false; // Disable update for API request
            } else {
                $this->servisMakina->setFormValue($val);
            }
        }

        // Check field name 'servisKmMakines' first before field var 'x_servisKmMakines'
        $val = $CurrentForm->hasValue("servisKmMakines") ? $CurrentForm->getValue("servisKmMakines") : $CurrentForm->getValue("x_servisKmMakines");
        if (!$this->servisKmMakines->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisKmMakines->Visible = false; // Disable update for API request
            } else {
                $this->servisKmMakines->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'servisStafi' first before field var 'x_servisStafi'
        $val = $CurrentForm->hasValue("servisStafi") ? $CurrentForm->getValue("servisStafi") : $CurrentForm->getValue("x_servisStafi");
        if (!$this->servisStafi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisStafi->Visible = false; // Disable update for API request
            } else {
                $this->servisStafi->setFormValue($val);
            }
        }

        // Check field name 'servisTotaliFatures' first before field var 'x_servisTotaliFatures'
        $val = $CurrentForm->hasValue("servisTotaliFatures") ? $CurrentForm->getValue("servisTotaliFatures") : $CurrentForm->getValue("x_servisTotaliFatures");
        if (!$this->servisTotaliFatures->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisTotaliFatures->Visible = false; // Disable update for API request
            } else {
                $this->servisTotaliFatures->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'servisAutori' first before field var 'x_servisAutori'
        $val = $CurrentForm->hasValue("servisAutori") ? $CurrentForm->getValue("servisAutori") : $CurrentForm->getValue("x_servisAutori");
        if (!$this->servisAutori->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisAutori->Visible = false; // Disable update for API request
            } else {
                $this->servisAutori->setFormValue($val);
            }
        }

        // Check field name 'servisShtuar' first before field var 'x_servisShtuar'
        $val = $CurrentForm->hasValue("servisShtuar") ? $CurrentForm->getValue("servisShtuar") : $CurrentForm->getValue("x_servisShtuar");
        if (!$this->servisShtuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisShtuar->Visible = false; // Disable update for API request
            } else {
                $this->servisShtuar->setFormValue($val);
            }
            $this->servisShtuar->CurrentValue = UnFormatDateTime($this->servisShtuar->CurrentValue, $this->servisShtuar->formatPattern());
        }

        // Check field name 'servisID' first before field var 'x_servisID'
        $val = $CurrentForm->hasValue("servisID") ? $CurrentForm->getValue("servisID") : $CurrentForm->getValue("x_servisID");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->servisDate->CurrentValue = $this->servisDate->FormValue;
        $this->servisDate->CurrentValue = UnFormatDateTime($this->servisDate->CurrentValue, $this->servisDate->formatPattern());
        $this->servisKlienti->CurrentValue = $this->servisKlienti->FormValue;
        $this->servisMakina->CurrentValue = $this->servisMakina->FormValue;
        $this->servisKmMakines->CurrentValue = $this->servisKmMakines->FormValue;
        $this->servisStafi->CurrentValue = $this->servisStafi->FormValue;
        $this->servisTotaliFatures->CurrentValue = $this->servisTotaliFatures->FormValue;
        $this->servisAutori->CurrentValue = $this->servisAutori->FormValue;
        $this->servisShtuar->CurrentValue = $this->servisShtuar->FormValue;
        $this->servisShtuar->CurrentValue = UnFormatDateTime($this->servisShtuar->CurrentValue, $this->servisShtuar->formatPattern());
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
        $this->servisID->setDbValue($row['servisID']);
        $this->servisDate->setDbValue($row['servisDate']);
        $this->servisKlienti->setDbValue($row['servisKlienti']);
        if (array_key_exists('EV__servisKlienti', $row)) {
            $this->servisKlienti->VirtualValue = $row['EV__servisKlienti']; // Set up virtual field value
        } else {
            $this->servisKlienti->VirtualValue = ""; // Clear value
        }
        $this->servisMakina->setDbValue($row['servisMakina']);
        if (array_key_exists('EV__servisMakina', $row)) {
            $this->servisMakina->VirtualValue = $row['EV__servisMakina']; // Set up virtual field value
        } else {
            $this->servisMakina->VirtualValue = ""; // Clear value
        }
        $this->servisKmMakines->setDbValue($row['servisKmMakines']);
        $this->servisStafi->setDbValue($row['servisStafi']);
        if (array_key_exists('EV__servisStafi', $row)) {
            $this->servisStafi->VirtualValue = $row['EV__servisStafi']; // Set up virtual field value
        } else {
            $this->servisStafi->VirtualValue = ""; // Clear value
        }
        $this->servisTotaliFatures->setDbValue($row['servisTotaliFatures']);
        $this->servisAutori->setDbValue($row['servisAutori']);
        $this->servisShtuar->setDbValue($row['servisShtuar']);
        $this->servisModifikuar->setDbValue($row['servisModifikuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['servisID'] = $this->servisID->DefaultValue;
        $row['servisDate'] = $this->servisDate->DefaultValue;
        $row['servisKlienti'] = $this->servisKlienti->DefaultValue;
        $row['servisMakina'] = $this->servisMakina->DefaultValue;
        $row['servisKmMakines'] = $this->servisKmMakines->DefaultValue;
        $row['servisStafi'] = $this->servisStafi->DefaultValue;
        $row['servisTotaliFatures'] = $this->servisTotaliFatures->DefaultValue;
        $row['servisAutori'] = $this->servisAutori->DefaultValue;
        $row['servisShtuar'] = $this->servisShtuar->DefaultValue;
        $row['servisModifikuar'] = $this->servisModifikuar->DefaultValue;
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

        // servisID
        $this->servisID->RowCssClass = "row";

        // servisDate
        $this->servisDate->RowCssClass = "row";

        // servisKlienti
        $this->servisKlienti->RowCssClass = "row";

        // servisMakina
        $this->servisMakina->RowCssClass = "row";

        // servisKmMakines
        $this->servisKmMakines->RowCssClass = "row";

        // servisStafi
        $this->servisStafi->RowCssClass = "row";

        // servisTotaliFatures
        $this->servisTotaliFatures->RowCssClass = "row";

        // servisAutori
        $this->servisAutori->RowCssClass = "row";

        // servisShtuar
        $this->servisShtuar->RowCssClass = "row";

        // servisModifikuar
        $this->servisModifikuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // servisID
            $this->servisID->ViewValue = $this->servisID->CurrentValue;
            $this->servisID->ViewCustomAttributes = "";

            // servisDate
            $this->servisDate->ViewValue = $this->servisDate->CurrentValue;
            $this->servisDate->ViewValue = FormatDateTime($this->servisDate->ViewValue, $this->servisDate->formatPattern());
            $this->servisDate->ViewCustomAttributes = "";

            // servisKlienti
            if ($this->servisKlienti->VirtualValue != "") {
                $this->servisKlienti->ViewValue = $this->servisKlienti->VirtualValue;
            } else {
                $curVal = strval($this->servisKlienti->CurrentValue);
                if ($curVal != "") {
                    $this->servisKlienti->ViewValue = $this->servisKlienti->lookupCacheOption($curVal);
                    if ($this->servisKlienti->ViewValue === null) { // Lookup from database
                        $filterWrk = "`klientID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->servisKlienti->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->servisKlienti->Lookup->renderViewRow($rswrk[0]);
                            $this->servisKlienti->ViewValue = $this->servisKlienti->displayValue($arwrk);
                        } else {
                            $this->servisKlienti->ViewValue = FormatNumber($this->servisKlienti->CurrentValue, $this->servisKlienti->formatPattern());
                        }
                    }
                } else {
                    $this->servisKlienti->ViewValue = null;
                }
            }
            $this->servisKlienti->ViewCustomAttributes = "";

            // servisMakina
            if ($this->servisMakina->VirtualValue != "") {
                $this->servisMakina->ViewValue = $this->servisMakina->VirtualValue;
            } else {
                $curVal = strval($this->servisMakina->CurrentValue);
                if ($curVal != "") {
                    $this->servisMakina->ViewValue = $this->servisMakina->lookupCacheOption($curVal);
                    if ($this->servisMakina->ViewValue === null) { // Lookup from database
                        $filterWrk = "`makinaID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->servisMakina->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->servisMakina->Lookup->renderViewRow($rswrk[0]);
                            $this->servisMakina->ViewValue = $this->servisMakina->displayValue($arwrk);
                        } else {
                            $this->servisMakina->ViewValue = FormatNumber($this->servisMakina->CurrentValue, $this->servisMakina->formatPattern());
                        }
                    }
                } else {
                    $this->servisMakina->ViewValue = null;
                }
            }
            $this->servisMakina->ViewCustomAttributes = "";

            // servisKmMakines
            $this->servisKmMakines->ViewValue = $this->servisKmMakines->CurrentValue;
            $this->servisKmMakines->ViewValue = FormatNumber($this->servisKmMakines->ViewValue, $this->servisKmMakines->formatPattern());
            $this->servisKmMakines->ViewCustomAttributes = "";

            // servisStafi
            if ($this->servisStafi->VirtualValue != "") {
                $this->servisStafi->ViewValue = $this->servisStafi->VirtualValue;
            } else {
                $curVal = strval($this->servisStafi->CurrentValue);
                if ($curVal != "") {
                    $this->servisStafi->ViewValue = $this->servisStafi->lookupCacheOption($curVal);
                    if ($this->servisStafi->ViewValue === null) { // Lookup from database
                        $filterWrk = "`stafiID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->servisStafi->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->servisStafi->Lookup->renderViewRow($rswrk[0]);
                            $this->servisStafi->ViewValue = $this->servisStafi->displayValue($arwrk);
                        } else {
                            $this->servisStafi->ViewValue = FormatNumber($this->servisStafi->CurrentValue, $this->servisStafi->formatPattern());
                        }
                    }
                } else {
                    $this->servisStafi->ViewValue = null;
                }
            }
            $this->servisStafi->ViewCustomAttributes = "";

            // servisTotaliFatures
            $this->servisTotaliFatures->ViewValue = $this->servisTotaliFatures->CurrentValue;
            $this->servisTotaliFatures->ViewValue = FormatNumber($this->servisTotaliFatures->ViewValue, $this->servisTotaliFatures->formatPattern());
            $this->servisTotaliFatures->ViewCustomAttributes = "";

            // servisAutori
            $this->servisAutori->ViewValue = $this->servisAutori->CurrentValue;
            $curVal = strval($this->servisAutori->CurrentValue);
            if ($curVal != "") {
                $this->servisAutori->ViewValue = $this->servisAutori->lookupCacheOption($curVal);
                if ($this->servisAutori->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->servisAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->servisAutori->Lookup->renderViewRow($rswrk[0]);
                        $this->servisAutori->ViewValue = $this->servisAutori->displayValue($arwrk);
                    } else {
                        $this->servisAutori->ViewValue = FormatNumber($this->servisAutori->CurrentValue, $this->servisAutori->formatPattern());
                    }
                }
            } else {
                $this->servisAutori->ViewValue = null;
            }
            $this->servisAutori->ViewCustomAttributes = "";

            // servisShtuar
            $this->servisShtuar->ViewValue = $this->servisShtuar->CurrentValue;
            $this->servisShtuar->ViewValue = FormatDateTime($this->servisShtuar->ViewValue, $this->servisShtuar->formatPattern());
            $this->servisShtuar->ViewCustomAttributes = "";

            // servisModifikuar
            $this->servisModifikuar->ViewValue = $this->servisModifikuar->CurrentValue;
            $this->servisModifikuar->ViewValue = FormatDateTime($this->servisModifikuar->ViewValue, $this->servisModifikuar->formatPattern());
            $this->servisModifikuar->ViewCustomAttributes = "";

            // servisDate
            $this->servisDate->LinkCustomAttributes = "";
            $this->servisDate->HrefValue = "";

            // servisKlienti
            $this->servisKlienti->LinkCustomAttributes = "";
            $this->servisKlienti->HrefValue = "";

            // servisMakina
            $this->servisMakina->LinkCustomAttributes = "";
            $this->servisMakina->HrefValue = "";

            // servisKmMakines
            $this->servisKmMakines->LinkCustomAttributes = "";
            $this->servisKmMakines->HrefValue = "";

            // servisStafi
            $this->servisStafi->LinkCustomAttributes = "";
            $this->servisStafi->HrefValue = "";

            // servisTotaliFatures
            $this->servisTotaliFatures->LinkCustomAttributes = "";
            $this->servisTotaliFatures->HrefValue = "";

            // servisAutori
            $this->servisAutori->LinkCustomAttributes = "";
            $this->servisAutori->HrefValue = "";

            // servisShtuar
            $this->servisShtuar->LinkCustomAttributes = "";
            $this->servisShtuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // servisDate
            $this->servisDate->setupEditAttributes();
            $this->servisDate->EditCustomAttributes = "";
            $this->servisDate->EditValue = HtmlEncode(FormatDateTime($this->servisDate->CurrentValue, $this->servisDate->formatPattern()));
            $this->servisDate->PlaceHolder = RemoveHtml($this->servisDate->caption());

            // servisKlienti
            $this->servisKlienti->setupEditAttributes();
            $this->servisKlienti->EditCustomAttributes = "";
            $curVal = trim(strval($this->servisKlienti->CurrentValue));
            if ($curVal != "") {
                $this->servisKlienti->ViewValue = $this->servisKlienti->lookupCacheOption($curVal);
            } else {
                $this->servisKlienti->ViewValue = $this->servisKlienti->Lookup !== null && is_array($this->servisKlienti->lookupOptions()) ? $curVal : null;
            }
            if ($this->servisKlienti->ViewValue !== null) { // Load from cache
                $this->servisKlienti->EditValue = array_values($this->servisKlienti->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`klientID`" . SearchString("=", $this->servisKlienti->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->servisKlienti->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->servisKlienti->EditValue = $arwrk;
            }
            $this->servisKlienti->PlaceHolder = RemoveHtml($this->servisKlienti->caption());

            // servisMakina
            $this->servisMakina->setupEditAttributes();
            $this->servisMakina->EditCustomAttributes = "";
            $curVal = trim(strval($this->servisMakina->CurrentValue));
            if ($curVal != "") {
                $this->servisMakina->ViewValue = $this->servisMakina->lookupCacheOption($curVal);
            } else {
                $this->servisMakina->ViewValue = $this->servisMakina->Lookup !== null && is_array($this->servisMakina->lookupOptions()) ? $curVal : null;
            }
            if ($this->servisMakina->ViewValue !== null) { // Load from cache
                $this->servisMakina->EditValue = array_values($this->servisMakina->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`makinaID`" . SearchString("=", $this->servisMakina->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->servisMakina->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->servisMakina->EditValue = $arwrk;
            }
            $this->servisMakina->PlaceHolder = RemoveHtml($this->servisMakina->caption());

            // servisKmMakines
            $this->servisKmMakines->setupEditAttributes();
            $this->servisKmMakines->EditCustomAttributes = "";
            $this->servisKmMakines->EditValue = HtmlEncode($this->servisKmMakines->CurrentValue);
            $this->servisKmMakines->PlaceHolder = RemoveHtml($this->servisKmMakines->caption());
            if (strval($this->servisKmMakines->EditValue) != "" && is_numeric($this->servisKmMakines->EditValue)) {
                $this->servisKmMakines->EditValue = FormatNumber($this->servisKmMakines->EditValue, null);
            }

            // servisStafi
            $this->servisStafi->setupEditAttributes();
            $this->servisStafi->EditCustomAttributes = "";
            $curVal = trim(strval($this->servisStafi->CurrentValue));
            if ($curVal != "") {
                $this->servisStafi->ViewValue = $this->servisStafi->lookupCacheOption($curVal);
            } else {
                $this->servisStafi->ViewValue = $this->servisStafi->Lookup !== null && is_array($this->servisStafi->lookupOptions()) ? $curVal : null;
            }
            if ($this->servisStafi->ViewValue !== null) { // Load from cache
                $this->servisStafi->EditValue = array_values($this->servisStafi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`stafiID`" . SearchString("=", $this->servisStafi->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->servisStafi->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->servisStafi->EditValue = $arwrk;
            }
            $this->servisStafi->PlaceHolder = RemoveHtml($this->servisStafi->caption());

            // servisTotaliFatures
            $this->servisTotaliFatures->setupEditAttributes();
            $this->servisTotaliFatures->EditCustomAttributes = "";
            $this->servisTotaliFatures->EditValue = HtmlEncode($this->servisTotaliFatures->CurrentValue);
            $this->servisTotaliFatures->PlaceHolder = RemoveHtml($this->servisTotaliFatures->caption());
            if (strval($this->servisTotaliFatures->EditValue) != "" && is_numeric($this->servisTotaliFatures->EditValue)) {
                $this->servisTotaliFatures->EditValue = FormatNumber($this->servisTotaliFatures->EditValue, null);
            }

            // servisAutori

            // servisShtuar

            // Add refer script

            // servisDate
            $this->servisDate->LinkCustomAttributes = "";
            $this->servisDate->HrefValue = "";

            // servisKlienti
            $this->servisKlienti->LinkCustomAttributes = "";
            $this->servisKlienti->HrefValue = "";

            // servisMakina
            $this->servisMakina->LinkCustomAttributes = "";
            $this->servisMakina->HrefValue = "";

            // servisKmMakines
            $this->servisKmMakines->LinkCustomAttributes = "";
            $this->servisKmMakines->HrefValue = "";

            // servisStafi
            $this->servisStafi->LinkCustomAttributes = "";
            $this->servisStafi->HrefValue = "";

            // servisTotaliFatures
            $this->servisTotaliFatures->LinkCustomAttributes = "";
            $this->servisTotaliFatures->HrefValue = "";

            // servisAutori
            $this->servisAutori->LinkCustomAttributes = "";
            $this->servisAutori->HrefValue = "";

            // servisShtuar
            $this->servisShtuar->LinkCustomAttributes = "";
            $this->servisShtuar->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }

        // Save data for Custom Template
        if ($this->RowType == ROWTYPE_VIEW || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_ADD) {
            $this->Rows[] = $this->customTemplateFieldValues();
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
        if ($this->servisDate->Required) {
            if (!$this->servisDate->IsDetailKey && EmptyValue($this->servisDate->FormValue)) {
                $this->servisDate->addErrorMessage(str_replace("%s", $this->servisDate->caption(), $this->servisDate->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->servisDate->FormValue, $this->servisDate->formatPattern())) {
            $this->servisDate->addErrorMessage($this->servisDate->getErrorMessage(false));
        }
        if ($this->servisKlienti->Required) {
            if (!$this->servisKlienti->IsDetailKey && EmptyValue($this->servisKlienti->FormValue)) {
                $this->servisKlienti->addErrorMessage(str_replace("%s", $this->servisKlienti->caption(), $this->servisKlienti->RequiredErrorMessage));
            }
        }
        if ($this->servisMakina->Required) {
            if (!$this->servisMakina->IsDetailKey && EmptyValue($this->servisMakina->FormValue)) {
                $this->servisMakina->addErrorMessage(str_replace("%s", $this->servisMakina->caption(), $this->servisMakina->RequiredErrorMessage));
            }
        }
        if ($this->servisKmMakines->Required) {
            if (!$this->servisKmMakines->IsDetailKey && EmptyValue($this->servisKmMakines->FormValue)) {
                $this->servisKmMakines->addErrorMessage(str_replace("%s", $this->servisKmMakines->caption(), $this->servisKmMakines->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->servisKmMakines->FormValue)) {
            $this->servisKmMakines->addErrorMessage($this->servisKmMakines->getErrorMessage(false));
        }
        if ($this->servisStafi->Required) {
            if (!$this->servisStafi->IsDetailKey && EmptyValue($this->servisStafi->FormValue)) {
                $this->servisStafi->addErrorMessage(str_replace("%s", $this->servisStafi->caption(), $this->servisStafi->RequiredErrorMessage));
            }
        }
        if ($this->servisTotaliFatures->Required) {
            if (!$this->servisTotaliFatures->IsDetailKey && EmptyValue($this->servisTotaliFatures->FormValue)) {
                $this->servisTotaliFatures->addErrorMessage(str_replace("%s", $this->servisTotaliFatures->caption(), $this->servisTotaliFatures->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->servisTotaliFatures->FormValue)) {
            $this->servisTotaliFatures->addErrorMessage($this->servisTotaliFatures->getErrorMessage(false));
        }
        if ($this->servisAutori->Required) {
            if (!$this->servisAutori->IsDetailKey && EmptyValue($this->servisAutori->FormValue)) {
                $this->servisAutori->addErrorMessage(str_replace("%s", $this->servisAutori->caption(), $this->servisAutori->RequiredErrorMessage));
            }
        }
        if ($this->servisShtuar->Required) {
            if (!$this->servisShtuar->IsDetailKey && EmptyValue($this->servisShtuar->FormValue)) {
                $this->servisShtuar->addErrorMessage(str_replace("%s", $this->servisShtuar->caption(), $this->servisShtuar->RequiredErrorMessage));
            }
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("ServisPjesetGrid");
        if (in_array("servis_pjeset", $detailTblVar) && $detailPage->DetailAdd) {
            $validateForm = $validateForm && $detailPage->validateGridForm();
        }
        $detailPage = Container("ServisSherbimeGrid");
        if (in_array("servis_sherbime", $detailTblVar) && $detailPage->DetailAdd) {
            $validateForm = $validateForm && $detailPage->validateGridForm();
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

        // servisDate
        $this->servisDate->setDbValueDef($rsnew, UnFormatDateTime($this->servisDate->CurrentValue, $this->servisDate->formatPattern()), CurrentDate(), false);

        // servisKlienti
        $this->servisKlienti->setDbValueDef($rsnew, $this->servisKlienti->CurrentValue, 0, false);

        // servisMakina
        $this->servisMakina->setDbValueDef($rsnew, $this->servisMakina->CurrentValue, 0, false);

        // servisKmMakines
        $this->servisKmMakines->setDbValueDef($rsnew, $this->servisKmMakines->CurrentValue, 0, false);

        // servisStafi
        $this->servisStafi->setDbValueDef($rsnew, $this->servisStafi->CurrentValue, 0, false);

        // servisTotaliFatures
        $this->servisTotaliFatures->setDbValueDef($rsnew, $this->servisTotaliFatures->CurrentValue, null, strval($this->servisTotaliFatures->CurrentValue ?? "") == "");

        // servisAutori
        $this->servisAutori->CurrentValue = CurrentUserID();
        $this->servisAutori->setDbValueDef($rsnew, $this->servisAutori->CurrentValue, 0);

        // servisShtuar
        $this->servisShtuar->CurrentValue = CurrentDateTime();
        $this->servisShtuar->setDbValueDef($rsnew, $this->servisShtuar->CurrentValue, CurrentDate());

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->servisTotaliFatures->CurrentValue != "") { // Check field with unique index
            $filter = "(`servisTotaliFatures` = " . AdjustSql($this->servisTotaliFatures->CurrentValue, $this->Dbid) . ")";
            $rsChk = $this->loadRs($filter)->fetch();
            if ($rsChk !== false) {
                $idxErrMsg = str_replace("%f", $this->servisTotaliFatures->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->servisTotaliFatures->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
            }
        }
        $conn = $this->getConnection();

        // Begin transaction
        if ($this->getCurrentDetailTable() != "" && $this->UseTransaction) {
            $conn->beginTransaction();
        }

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

        // Add detail records
        if ($addRow) {
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            $detailPage = Container("ServisPjesetGrid");
            if (in_array("servis_pjeset", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->servisPjeseServisID->setSessionValue($this->servisID->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "servis_pjeset"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->servisPjeseServisID->setSessionValue(""); // Clear master key if insert failed
                }
            }
            $detailPage = Container("ServisSherbimeGrid");
            if (in_array("servis_sherbime", $detailTblVar) && $detailPage->DetailAdd) {
                $detailPage->servisSherbimServisID->setSessionValue($this->servisID->CurrentValue); // Set master key
                $Security->loadCurrentUserLevel($this->ProjectID . "servis_sherbime"); // Load user level of detail table
                $addRow = $detailPage->gridInsert();
                $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                if (!$addRow) {
                $detailPage->servisSherbimServisID->setSessionValue(""); // Clear master key if insert failed
                }
            }
        }

        // Commit/Rollback transaction
        if ($this->getCurrentDetailTable() != "") {
            if ($addRow) {
                if ($this->UseTransaction) { // Commit transaction
                    $conn->commit();
                }
            } else {
                if ($this->UseTransaction) { // Rollback transaction
                    $conn->rollback();
                }
            }
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
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

    // Set up detail parms based on QueryString
    protected function setupDetailParms()
    {
        // Get the keys for master table
        $detailTblVar = Get(Config("TABLE_SHOW_DETAIL"));
        if ($detailTblVar !== null) {
            $this->setCurrentDetailTable($detailTblVar);
        } else {
            $detailTblVar = $this->getCurrentDetailTable();
        }
        if ($detailTblVar != "") {
            $detailTblVar = explode(",", $detailTblVar);
            if (in_array("servis_pjeset", $detailTblVar)) {
                $detailPageObj = Container("ServisPjesetGrid");
                if ($detailPageObj->DetailAdd) {
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->servisPjeseServisID->IsDetailKey = true;
                    $detailPageObj->servisPjeseServisID->CurrentValue = $this->servisID->CurrentValue;
                    $detailPageObj->servisPjeseServisID->setSessionValue($detailPageObj->servisPjeseServisID->CurrentValue);
                }
            }
            if (in_array("servis_sherbime", $detailTblVar)) {
                $detailPageObj = Container("ServisSherbimeGrid");
                if ($detailPageObj->DetailAdd) {
                    if ($this->CopyRecord) {
                        $detailPageObj->CurrentMode = "copy";
                    } else {
                        $detailPageObj->CurrentMode = "add";
                    }
                    $detailPageObj->CurrentAction = "gridadd";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->servisSherbimServisID->IsDetailKey = true;
                    $detailPageObj->servisSherbimServisID->CurrentValue = $this->servisID->CurrentValue;
                    $detailPageObj->servisSherbimServisID->setSessionValue($detailPageObj->servisSherbimServisID->CurrentValue);
                }
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ServisList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Set up detail pages
    protected function setupDetailPages()
    {
        $pages = new SubPages();
        $pages->Style = "tabs";
        $pages->add('servis_pjeset');
        $pages->add('servis_sherbime');
        $this->DetailPages = $pages;
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
                case "x_servisKlienti":
                    break;
                case "x_servisMakina":
                    break;
                case "x_servisStafi":
                    break;
                case "x_servisAutori":
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
