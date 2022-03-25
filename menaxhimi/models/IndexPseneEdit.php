<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class IndexPseneEdit extends IndexPsene
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'index_psene';

    // Page object name
    public $PageObjName = "IndexPseneEdit";

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

        // Table object (index_psene)
        if (!isset($GLOBALS["index_psene"]) || get_class($GLOBALS["index_psene"]) == PROJECT_NAMESPACE . "index_psene") {
            $GLOBALS["index_psene"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'index_psene');
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
                $tbl = Container("index_psene");
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
                    if ($pageName == "IndexPseneView") {
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
		        $this->iPseNeFoto->OldUploadPath = '../ngarkime/index/psene/';
		        $this->iPseNeFoto->UploadPath = $this->iPseNeFoto->OldUploadPath;
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
            $key .= @$ar['iPseNeID'];
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
            $this->iPseNeID->Visible = false;
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
        $this->iPseNeID->Visible = false;
        $this->iPseNeGjuha->setVisibility();
        $this->iPseNeB1Titull->setVisibility();
        $this->iPseNeB1Txt->setVisibility();
        $this->iPseNeB1Ikona->setVisibility();
        $this->iPseNeB2Titull->setVisibility();
        $this->iPseNeB2Txt->setVisibility();
        $this->iPseNeB2Ikona->setVisibility();
        $this->iPseNeB3Titull->setVisibility();
        $this->iPseNeB3Txt->setVisibility();
        $this->iPseNeB3Ikona->setVisibility();
        $this->iPseNeB4Titull->setVisibility();
        $this->iPseNeB4Txt->setVisibility();
        $this->iPseNeB4Ikona->setVisibility();
        $this->iPseNeFoto->setVisibility();
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
        $this->setupLookupOptions($this->iPseNeGjuha);

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
            if (($keyValue = Get("iPseNeID") ?? Key(0) ?? Route(2)) !== null) {
                $this->iPseNeID->setQueryStringValue($keyValue);
                $this->iPseNeID->setOldValue($this->iPseNeID->QueryStringValue);
            } elseif (Post("iPseNeID") !== null) {
                $this->iPseNeID->setFormValue(Post("iPseNeID"));
                $this->iPseNeID->setOldValue($this->iPseNeID->FormValue);
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
                if (($keyValue = Get("iPseNeID") ?? Route("iPseNeID")) !== null) {
                    $this->iPseNeID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->iPseNeID->CurrentValue = null;
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
                        $this->terminate("IndexPseneList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "IndexPseneList") {
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
        $this->iPseNeFoto->Upload->Index = $CurrentForm->Index;
        $this->iPseNeFoto->Upload->uploadFile();
        $this->iPseNeFoto->CurrentValue = $this->iPseNeFoto->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'iPseNeGjuha' first before field var 'x_iPseNeGjuha'
        $val = $CurrentForm->hasValue("iPseNeGjuha") ? $CurrentForm->getValue("iPseNeGjuha") : $CurrentForm->getValue("x_iPseNeGjuha");
        if (!$this->iPseNeGjuha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeGjuha->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeGjuha->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB1Titull' first before field var 'x_iPseNeB1Titull'
        $val = $CurrentForm->hasValue("iPseNeB1Titull") ? $CurrentForm->getValue("iPseNeB1Titull") : $CurrentForm->getValue("x_iPseNeB1Titull");
        if (!$this->iPseNeB1Titull->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB1Titull->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB1Titull->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB1Txt' first before field var 'x_iPseNeB1Txt'
        $val = $CurrentForm->hasValue("iPseNeB1Txt") ? $CurrentForm->getValue("iPseNeB1Txt") : $CurrentForm->getValue("x_iPseNeB1Txt");
        if (!$this->iPseNeB1Txt->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB1Txt->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB1Txt->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB1Ikona' first before field var 'x_iPseNeB1Ikona'
        $val = $CurrentForm->hasValue("iPseNeB1Ikona") ? $CurrentForm->getValue("iPseNeB1Ikona") : $CurrentForm->getValue("x_iPseNeB1Ikona");
        if (!$this->iPseNeB1Ikona->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB1Ikona->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB1Ikona->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB2Titull' first before field var 'x_iPseNeB2Titull'
        $val = $CurrentForm->hasValue("iPseNeB2Titull") ? $CurrentForm->getValue("iPseNeB2Titull") : $CurrentForm->getValue("x_iPseNeB2Titull");
        if (!$this->iPseNeB2Titull->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB2Titull->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB2Titull->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB2Txt' first before field var 'x_iPseNeB2Txt'
        $val = $CurrentForm->hasValue("iPseNeB2Txt") ? $CurrentForm->getValue("iPseNeB2Txt") : $CurrentForm->getValue("x_iPseNeB2Txt");
        if (!$this->iPseNeB2Txt->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB2Txt->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB2Txt->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB2Ikona' first before field var 'x_iPseNeB2Ikona'
        $val = $CurrentForm->hasValue("iPseNeB2Ikona") ? $CurrentForm->getValue("iPseNeB2Ikona") : $CurrentForm->getValue("x_iPseNeB2Ikona");
        if (!$this->iPseNeB2Ikona->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB2Ikona->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB2Ikona->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB3Titull' first before field var 'x_iPseNeB3Titull'
        $val = $CurrentForm->hasValue("iPseNeB3Titull") ? $CurrentForm->getValue("iPseNeB3Titull") : $CurrentForm->getValue("x_iPseNeB3Titull");
        if (!$this->iPseNeB3Titull->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB3Titull->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB3Titull->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB3Txt' first before field var 'x_iPseNeB3Txt'
        $val = $CurrentForm->hasValue("iPseNeB3Txt") ? $CurrentForm->getValue("iPseNeB3Txt") : $CurrentForm->getValue("x_iPseNeB3Txt");
        if (!$this->iPseNeB3Txt->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB3Txt->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB3Txt->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB3Ikona' first before field var 'x_iPseNeB3Ikona'
        $val = $CurrentForm->hasValue("iPseNeB3Ikona") ? $CurrentForm->getValue("iPseNeB3Ikona") : $CurrentForm->getValue("x_iPseNeB3Ikona");
        if (!$this->iPseNeB3Ikona->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB3Ikona->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB3Ikona->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB4Titull' first before field var 'x_iPseNeB4Titull'
        $val = $CurrentForm->hasValue("iPseNeB4Titull") ? $CurrentForm->getValue("iPseNeB4Titull") : $CurrentForm->getValue("x_iPseNeB4Titull");
        if (!$this->iPseNeB4Titull->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB4Titull->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB4Titull->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB4Txt' first before field var 'x_iPseNeB4Txt'
        $val = $CurrentForm->hasValue("iPseNeB4Txt") ? $CurrentForm->getValue("iPseNeB4Txt") : $CurrentForm->getValue("x_iPseNeB4Txt");
        if (!$this->iPseNeB4Txt->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB4Txt->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB4Txt->setFormValue($val);
            }
        }

        // Check field name 'iPseNeB4Ikona' first before field var 'x_iPseNeB4Ikona'
        $val = $CurrentForm->hasValue("iPseNeB4Ikona") ? $CurrentForm->getValue("iPseNeB4Ikona") : $CurrentForm->getValue("x_iPseNeB4Ikona");
        if (!$this->iPseNeB4Ikona->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->iPseNeB4Ikona->Visible = false; // Disable update for API request
            } else {
                $this->iPseNeB4Ikona->setFormValue($val);
            }
        }

        // Check field name 'iPseNeID' first before field var 'x_iPseNeID'
        $val = $CurrentForm->hasValue("iPseNeID") ? $CurrentForm->getValue("iPseNeID") : $CurrentForm->getValue("x_iPseNeID");
        if (!$this->iPseNeID->IsDetailKey) {
            $this->iPseNeID->setFormValue($val);
        }
		$this->iPseNeFoto->OldUploadPath = '../ngarkime/index/psene/';
		$this->iPseNeFoto->UploadPath = $this->iPseNeFoto->OldUploadPath;
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->iPseNeID->CurrentValue = $this->iPseNeID->FormValue;
        $this->iPseNeGjuha->CurrentValue = $this->iPseNeGjuha->FormValue;
        $this->iPseNeB1Titull->CurrentValue = $this->iPseNeB1Titull->FormValue;
        $this->iPseNeB1Txt->CurrentValue = $this->iPseNeB1Txt->FormValue;
        $this->iPseNeB1Ikona->CurrentValue = $this->iPseNeB1Ikona->FormValue;
        $this->iPseNeB2Titull->CurrentValue = $this->iPseNeB2Titull->FormValue;
        $this->iPseNeB2Txt->CurrentValue = $this->iPseNeB2Txt->FormValue;
        $this->iPseNeB2Ikona->CurrentValue = $this->iPseNeB2Ikona->FormValue;
        $this->iPseNeB3Titull->CurrentValue = $this->iPseNeB3Titull->FormValue;
        $this->iPseNeB3Txt->CurrentValue = $this->iPseNeB3Txt->FormValue;
        $this->iPseNeB3Ikona->CurrentValue = $this->iPseNeB3Ikona->FormValue;
        $this->iPseNeB4Titull->CurrentValue = $this->iPseNeB4Titull->FormValue;
        $this->iPseNeB4Txt->CurrentValue = $this->iPseNeB4Txt->FormValue;
        $this->iPseNeB4Ikona->CurrentValue = $this->iPseNeB4Ikona->FormValue;
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
        $this->iPseNeID->setDbValue($row['iPseNeID']);
        $this->iPseNeGjuha->setDbValue($row['iPseNeGjuha']);
        $this->iPseNeB1Titull->setDbValue($row['iPseNeB1Titull']);
        $this->iPseNeB1Txt->setDbValue($row['iPseNeB1Txt']);
        $this->iPseNeB1Ikona->setDbValue($row['iPseNeB1Ikona']);
        $this->iPseNeB2Titull->setDbValue($row['iPseNeB2Titull']);
        $this->iPseNeB2Txt->setDbValue($row['iPseNeB2Txt']);
        $this->iPseNeB2Ikona->setDbValue($row['iPseNeB2Ikona']);
        $this->iPseNeB3Titull->setDbValue($row['iPseNeB3Titull']);
        $this->iPseNeB3Txt->setDbValue($row['iPseNeB3Txt']);
        $this->iPseNeB3Ikona->setDbValue($row['iPseNeB3Ikona']);
        $this->iPseNeB4Titull->setDbValue($row['iPseNeB4Titull']);
        $this->iPseNeB4Txt->setDbValue($row['iPseNeB4Txt']);
        $this->iPseNeB4Ikona->setDbValue($row['iPseNeB4Ikona']);
        $this->iPseNeFoto->Upload->DbValue = $row['iPseNeFoto'];
        $this->iPseNeFoto->setDbValue($this->iPseNeFoto->Upload->DbValue);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['iPseNeID'] = $this->iPseNeID->DefaultValue;
        $row['iPseNeGjuha'] = $this->iPseNeGjuha->DefaultValue;
        $row['iPseNeB1Titull'] = $this->iPseNeB1Titull->DefaultValue;
        $row['iPseNeB1Txt'] = $this->iPseNeB1Txt->DefaultValue;
        $row['iPseNeB1Ikona'] = $this->iPseNeB1Ikona->DefaultValue;
        $row['iPseNeB2Titull'] = $this->iPseNeB2Titull->DefaultValue;
        $row['iPseNeB2Txt'] = $this->iPseNeB2Txt->DefaultValue;
        $row['iPseNeB2Ikona'] = $this->iPseNeB2Ikona->DefaultValue;
        $row['iPseNeB3Titull'] = $this->iPseNeB3Titull->DefaultValue;
        $row['iPseNeB3Txt'] = $this->iPseNeB3Txt->DefaultValue;
        $row['iPseNeB3Ikona'] = $this->iPseNeB3Ikona->DefaultValue;
        $row['iPseNeB4Titull'] = $this->iPseNeB4Titull->DefaultValue;
        $row['iPseNeB4Txt'] = $this->iPseNeB4Txt->DefaultValue;
        $row['iPseNeB4Ikona'] = $this->iPseNeB4Ikona->DefaultValue;
        $row['iPseNeFoto'] = $this->iPseNeFoto->DefaultValue;
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

        // iPseNeID
        $this->iPseNeID->RowCssClass = "row";

        // iPseNeGjuha
        $this->iPseNeGjuha->RowCssClass = "row";

        // iPseNeB1Titull
        $this->iPseNeB1Titull->RowCssClass = "row";

        // iPseNeB1Txt
        $this->iPseNeB1Txt->RowCssClass = "row";

        // iPseNeB1Ikona
        $this->iPseNeB1Ikona->RowCssClass = "row";

        // iPseNeB2Titull
        $this->iPseNeB2Titull->RowCssClass = "row";

        // iPseNeB2Txt
        $this->iPseNeB2Txt->RowCssClass = "row";

        // iPseNeB2Ikona
        $this->iPseNeB2Ikona->RowCssClass = "row";

        // iPseNeB3Titull
        $this->iPseNeB3Titull->RowCssClass = "row";

        // iPseNeB3Txt
        $this->iPseNeB3Txt->RowCssClass = "row";

        // iPseNeB3Ikona
        $this->iPseNeB3Ikona->RowCssClass = "row";

        // iPseNeB4Titull
        $this->iPseNeB4Titull->RowCssClass = "row";

        // iPseNeB4Txt
        $this->iPseNeB4Txt->RowCssClass = "row";

        // iPseNeB4Ikona
        $this->iPseNeB4Ikona->RowCssClass = "row";

        // iPseNeFoto
        $this->iPseNeFoto->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // iPseNeGjuha
            if (strval($this->iPseNeGjuha->CurrentValue) != "") {
                $this->iPseNeGjuha->ViewValue = $this->iPseNeGjuha->optionCaption($this->iPseNeGjuha->CurrentValue);
            } else {
                $this->iPseNeGjuha->ViewValue = null;
            }
            $this->iPseNeGjuha->CssClass = "fw-bold";
            $this->iPseNeGjuha->ViewCustomAttributes = "";

            // iPseNeB1Titull
            $this->iPseNeB1Titull->ViewValue = $this->iPseNeB1Titull->CurrentValue;
            $this->iPseNeB1Titull->ViewCustomAttributes = "";

            // iPseNeB1Txt
            $this->iPseNeB1Txt->ViewValue = $this->iPseNeB1Txt->CurrentValue;
            $this->iPseNeB1Txt->ViewCustomAttributes = "";

            // iPseNeB1Ikona
            $this->iPseNeB1Ikona->ViewValue = $this->iPseNeB1Ikona->CurrentValue;
            $this->iPseNeB1Ikona->ViewCustomAttributes = "";

            // iPseNeB2Titull
            $this->iPseNeB2Titull->ViewValue = $this->iPseNeB2Titull->CurrentValue;
            $this->iPseNeB2Titull->ViewCustomAttributes = "";

            // iPseNeB2Txt
            $this->iPseNeB2Txt->ViewValue = $this->iPseNeB2Txt->CurrentValue;
            $this->iPseNeB2Txt->ViewCustomAttributes = "";

            // iPseNeB2Ikona
            $this->iPseNeB2Ikona->ViewValue = $this->iPseNeB2Ikona->CurrentValue;
            $this->iPseNeB2Ikona->ViewCustomAttributes = "";

            // iPseNeB3Titull
            $this->iPseNeB3Titull->ViewValue = $this->iPseNeB3Titull->CurrentValue;
            $this->iPseNeB3Titull->ViewCustomAttributes = "";

            // iPseNeB3Txt
            $this->iPseNeB3Txt->ViewValue = $this->iPseNeB3Txt->CurrentValue;
            $this->iPseNeB3Txt->ViewCustomAttributes = "";

            // iPseNeB3Ikona
            $this->iPseNeB3Ikona->ViewValue = $this->iPseNeB3Ikona->CurrentValue;
            $this->iPseNeB3Ikona->ViewCustomAttributes = "";

            // iPseNeB4Titull
            $this->iPseNeB4Titull->ViewValue = $this->iPseNeB4Titull->CurrentValue;
            $this->iPseNeB4Titull->ViewCustomAttributes = "";

            // iPseNeB4Txt
            $this->iPseNeB4Txt->ViewValue = $this->iPseNeB4Txt->CurrentValue;
            $this->iPseNeB4Txt->ViewCustomAttributes = "";

            // iPseNeB4Ikona
            $this->iPseNeB4Ikona->ViewValue = $this->iPseNeB4Ikona->CurrentValue;
            $this->iPseNeB4Ikona->ViewCustomAttributes = "";

            // iPseNeFoto
            $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
            if (!EmptyValue($this->iPseNeFoto->Upload->DbValue)) {
                $this->iPseNeFoto->ImageWidth = 100;
                $this->iPseNeFoto->ImageHeight = 0;
                $this->iPseNeFoto->ImageAlt = $this->iPseNeFoto->alt();
                $this->iPseNeFoto->ImageCssClass = "ew-image";
                $this->iPseNeFoto->ViewValue = $this->iPseNeFoto->Upload->DbValue;
            } else {
                $this->iPseNeFoto->ViewValue = "";
            }
            $this->iPseNeFoto->ViewCustomAttributes = "";

            // iPseNeGjuha
            $this->iPseNeGjuha->LinkCustomAttributes = "";
            $this->iPseNeGjuha->HrefValue = "";

            // iPseNeB1Titull
            $this->iPseNeB1Titull->LinkCustomAttributes = "";
            $this->iPseNeB1Titull->HrefValue = "";

            // iPseNeB1Txt
            $this->iPseNeB1Txt->LinkCustomAttributes = "";
            $this->iPseNeB1Txt->HrefValue = "";

            // iPseNeB1Ikona
            $this->iPseNeB1Ikona->LinkCustomAttributes = "";
            $this->iPseNeB1Ikona->HrefValue = "";

            // iPseNeB2Titull
            $this->iPseNeB2Titull->LinkCustomAttributes = "";
            $this->iPseNeB2Titull->HrefValue = "";

            // iPseNeB2Txt
            $this->iPseNeB2Txt->LinkCustomAttributes = "";
            $this->iPseNeB2Txt->HrefValue = "";

            // iPseNeB2Ikona
            $this->iPseNeB2Ikona->LinkCustomAttributes = "";
            $this->iPseNeB2Ikona->HrefValue = "";

            // iPseNeB3Titull
            $this->iPseNeB3Titull->LinkCustomAttributes = "";
            $this->iPseNeB3Titull->HrefValue = "";

            // iPseNeB3Txt
            $this->iPseNeB3Txt->LinkCustomAttributes = "";
            $this->iPseNeB3Txt->HrefValue = "";

            // iPseNeB3Ikona
            $this->iPseNeB3Ikona->LinkCustomAttributes = "";
            $this->iPseNeB3Ikona->HrefValue = "";

            // iPseNeB4Titull
            $this->iPseNeB4Titull->LinkCustomAttributes = "";
            $this->iPseNeB4Titull->HrefValue = "";

            // iPseNeB4Txt
            $this->iPseNeB4Txt->LinkCustomAttributes = "";
            $this->iPseNeB4Txt->HrefValue = "";

            // iPseNeB4Ikona
            $this->iPseNeB4Ikona->LinkCustomAttributes = "";
            $this->iPseNeB4Ikona->HrefValue = "";

            // iPseNeFoto
            $this->iPseNeFoto->LinkCustomAttributes = "";
            $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
            if (!EmptyValue($this->iPseNeFoto->Upload->DbValue)) {
                $this->iPseNeFoto->HrefValue = GetFileUploadUrl($this->iPseNeFoto, $this->iPseNeFoto->htmlDecode($this->iPseNeFoto->Upload->DbValue)); // Add prefix/suffix
                $this->iPseNeFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->iPseNeFoto->HrefValue = FullUrl($this->iPseNeFoto->HrefValue, "href");
                }
            } else {
                $this->iPseNeFoto->HrefValue = "";
            }
            $this->iPseNeFoto->ExportHrefValue = $this->iPseNeFoto->UploadPath . $this->iPseNeFoto->Upload->DbValue;
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // iPseNeGjuha
            $this->iPseNeGjuha->EditCustomAttributes = "";
            $this->iPseNeGjuha->EditValue = $this->iPseNeGjuha->options(false);
            $this->iPseNeGjuha->PlaceHolder = RemoveHtml($this->iPseNeGjuha->caption());

            // iPseNeB1Titull
            $this->iPseNeB1Titull->setupEditAttributes();
            $this->iPseNeB1Titull->EditCustomAttributes = "";
            if (!$this->iPseNeB1Titull->Raw) {
                $this->iPseNeB1Titull->CurrentValue = HtmlDecode($this->iPseNeB1Titull->CurrentValue);
            }
            $this->iPseNeB1Titull->EditValue = HtmlEncode($this->iPseNeB1Titull->CurrentValue);
            $this->iPseNeB1Titull->PlaceHolder = RemoveHtml($this->iPseNeB1Titull->caption());

            // iPseNeB1Txt
            $this->iPseNeB1Txt->setupEditAttributes();
            $this->iPseNeB1Txt->EditCustomAttributes = "";
            if (!$this->iPseNeB1Txt->Raw) {
                $this->iPseNeB1Txt->CurrentValue = HtmlDecode($this->iPseNeB1Txt->CurrentValue);
            }
            $this->iPseNeB1Txt->EditValue = HtmlEncode($this->iPseNeB1Txt->CurrentValue);
            $this->iPseNeB1Txt->PlaceHolder = RemoveHtml($this->iPseNeB1Txt->caption());

            // iPseNeB1Ikona
            $this->iPseNeB1Ikona->setupEditAttributes();
            $this->iPseNeB1Ikona->EditCustomAttributes = "";
            if (!$this->iPseNeB1Ikona->Raw) {
                $this->iPseNeB1Ikona->CurrentValue = HtmlDecode($this->iPseNeB1Ikona->CurrentValue);
            }
            $this->iPseNeB1Ikona->EditValue = HtmlEncode($this->iPseNeB1Ikona->CurrentValue);
            $this->iPseNeB1Ikona->PlaceHolder = RemoveHtml($this->iPseNeB1Ikona->caption());

            // iPseNeB2Titull
            $this->iPseNeB2Titull->setupEditAttributes();
            $this->iPseNeB2Titull->EditCustomAttributes = "";
            if (!$this->iPseNeB2Titull->Raw) {
                $this->iPseNeB2Titull->CurrentValue = HtmlDecode($this->iPseNeB2Titull->CurrentValue);
            }
            $this->iPseNeB2Titull->EditValue = HtmlEncode($this->iPseNeB2Titull->CurrentValue);
            $this->iPseNeB2Titull->PlaceHolder = RemoveHtml($this->iPseNeB2Titull->caption());

            // iPseNeB2Txt
            $this->iPseNeB2Txt->setupEditAttributes();
            $this->iPseNeB2Txt->EditCustomAttributes = "";
            if (!$this->iPseNeB2Txt->Raw) {
                $this->iPseNeB2Txt->CurrentValue = HtmlDecode($this->iPseNeB2Txt->CurrentValue);
            }
            $this->iPseNeB2Txt->EditValue = HtmlEncode($this->iPseNeB2Txt->CurrentValue);
            $this->iPseNeB2Txt->PlaceHolder = RemoveHtml($this->iPseNeB2Txt->caption());

            // iPseNeB2Ikona
            $this->iPseNeB2Ikona->setupEditAttributes();
            $this->iPseNeB2Ikona->EditCustomAttributes = "";
            if (!$this->iPseNeB2Ikona->Raw) {
                $this->iPseNeB2Ikona->CurrentValue = HtmlDecode($this->iPseNeB2Ikona->CurrentValue);
            }
            $this->iPseNeB2Ikona->EditValue = HtmlEncode($this->iPseNeB2Ikona->CurrentValue);
            $this->iPseNeB2Ikona->PlaceHolder = RemoveHtml($this->iPseNeB2Ikona->caption());

            // iPseNeB3Titull
            $this->iPseNeB3Titull->setupEditAttributes();
            $this->iPseNeB3Titull->EditCustomAttributes = "";
            if (!$this->iPseNeB3Titull->Raw) {
                $this->iPseNeB3Titull->CurrentValue = HtmlDecode($this->iPseNeB3Titull->CurrentValue);
            }
            $this->iPseNeB3Titull->EditValue = HtmlEncode($this->iPseNeB3Titull->CurrentValue);
            $this->iPseNeB3Titull->PlaceHolder = RemoveHtml($this->iPseNeB3Titull->caption());

            // iPseNeB3Txt
            $this->iPseNeB3Txt->setupEditAttributes();
            $this->iPseNeB3Txt->EditCustomAttributes = "";
            if (!$this->iPseNeB3Txt->Raw) {
                $this->iPseNeB3Txt->CurrentValue = HtmlDecode($this->iPseNeB3Txt->CurrentValue);
            }
            $this->iPseNeB3Txt->EditValue = HtmlEncode($this->iPseNeB3Txt->CurrentValue);
            $this->iPseNeB3Txt->PlaceHolder = RemoveHtml($this->iPseNeB3Txt->caption());

            // iPseNeB3Ikona
            $this->iPseNeB3Ikona->setupEditAttributes();
            $this->iPseNeB3Ikona->EditCustomAttributes = "";
            if (!$this->iPseNeB3Ikona->Raw) {
                $this->iPseNeB3Ikona->CurrentValue = HtmlDecode($this->iPseNeB3Ikona->CurrentValue);
            }
            $this->iPseNeB3Ikona->EditValue = HtmlEncode($this->iPseNeB3Ikona->CurrentValue);
            $this->iPseNeB3Ikona->PlaceHolder = RemoveHtml($this->iPseNeB3Ikona->caption());

            // iPseNeB4Titull
            $this->iPseNeB4Titull->setupEditAttributes();
            $this->iPseNeB4Titull->EditCustomAttributes = "";
            if (!$this->iPseNeB4Titull->Raw) {
                $this->iPseNeB4Titull->CurrentValue = HtmlDecode($this->iPseNeB4Titull->CurrentValue);
            }
            $this->iPseNeB4Titull->EditValue = HtmlEncode($this->iPseNeB4Titull->CurrentValue);
            $this->iPseNeB4Titull->PlaceHolder = RemoveHtml($this->iPseNeB4Titull->caption());

            // iPseNeB4Txt
            $this->iPseNeB4Txt->setupEditAttributes();
            $this->iPseNeB4Txt->EditCustomAttributes = "";
            if (!$this->iPseNeB4Txt->Raw) {
                $this->iPseNeB4Txt->CurrentValue = HtmlDecode($this->iPseNeB4Txt->CurrentValue);
            }
            $this->iPseNeB4Txt->EditValue = HtmlEncode($this->iPseNeB4Txt->CurrentValue);
            $this->iPseNeB4Txt->PlaceHolder = RemoveHtml($this->iPseNeB4Txt->caption());

            // iPseNeB4Ikona
            $this->iPseNeB4Ikona->setupEditAttributes();
            $this->iPseNeB4Ikona->EditCustomAttributes = "";
            if (!$this->iPseNeB4Ikona->Raw) {
                $this->iPseNeB4Ikona->CurrentValue = HtmlDecode($this->iPseNeB4Ikona->CurrentValue);
            }
            $this->iPseNeB4Ikona->EditValue = HtmlEncode($this->iPseNeB4Ikona->CurrentValue);
            $this->iPseNeB4Ikona->PlaceHolder = RemoveHtml($this->iPseNeB4Ikona->caption());

            // iPseNeFoto
            $this->iPseNeFoto->setupEditAttributes();
            $this->iPseNeFoto->EditCustomAttributes = "";
            $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
            if (!EmptyValue($this->iPseNeFoto->Upload->DbValue)) {
                $this->iPseNeFoto->ImageWidth = 100;
                $this->iPseNeFoto->ImageHeight = 0;
                $this->iPseNeFoto->ImageAlt = $this->iPseNeFoto->alt();
                $this->iPseNeFoto->ImageCssClass = "ew-image";
                $this->iPseNeFoto->EditValue = $this->iPseNeFoto->Upload->DbValue;
            } else {
                $this->iPseNeFoto->EditValue = "";
            }
            if (!EmptyValue($this->iPseNeFoto->CurrentValue)) {
                $this->iPseNeFoto->Upload->FileName = $this->iPseNeFoto->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->iPseNeFoto);
            }

            // Edit refer script

            // iPseNeGjuha
            $this->iPseNeGjuha->LinkCustomAttributes = "";
            $this->iPseNeGjuha->HrefValue = "";

            // iPseNeB1Titull
            $this->iPseNeB1Titull->LinkCustomAttributes = "";
            $this->iPseNeB1Titull->HrefValue = "";

            // iPseNeB1Txt
            $this->iPseNeB1Txt->LinkCustomAttributes = "";
            $this->iPseNeB1Txt->HrefValue = "";

            // iPseNeB1Ikona
            $this->iPseNeB1Ikona->LinkCustomAttributes = "";
            $this->iPseNeB1Ikona->HrefValue = "";

            // iPseNeB2Titull
            $this->iPseNeB2Titull->LinkCustomAttributes = "";
            $this->iPseNeB2Titull->HrefValue = "";

            // iPseNeB2Txt
            $this->iPseNeB2Txt->LinkCustomAttributes = "";
            $this->iPseNeB2Txt->HrefValue = "";

            // iPseNeB2Ikona
            $this->iPseNeB2Ikona->LinkCustomAttributes = "";
            $this->iPseNeB2Ikona->HrefValue = "";

            // iPseNeB3Titull
            $this->iPseNeB3Titull->LinkCustomAttributes = "";
            $this->iPseNeB3Titull->HrefValue = "";

            // iPseNeB3Txt
            $this->iPseNeB3Txt->LinkCustomAttributes = "";
            $this->iPseNeB3Txt->HrefValue = "";

            // iPseNeB3Ikona
            $this->iPseNeB3Ikona->LinkCustomAttributes = "";
            $this->iPseNeB3Ikona->HrefValue = "";

            // iPseNeB4Titull
            $this->iPseNeB4Titull->LinkCustomAttributes = "";
            $this->iPseNeB4Titull->HrefValue = "";

            // iPseNeB4Txt
            $this->iPseNeB4Txt->LinkCustomAttributes = "";
            $this->iPseNeB4Txt->HrefValue = "";

            // iPseNeB4Ikona
            $this->iPseNeB4Ikona->LinkCustomAttributes = "";
            $this->iPseNeB4Ikona->HrefValue = "";

            // iPseNeFoto
            $this->iPseNeFoto->LinkCustomAttributes = "";
            $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
            if (!EmptyValue($this->iPseNeFoto->Upload->DbValue)) {
                $this->iPseNeFoto->HrefValue = GetFileUploadUrl($this->iPseNeFoto, $this->iPseNeFoto->htmlDecode($this->iPseNeFoto->Upload->DbValue)); // Add prefix/suffix
                $this->iPseNeFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->iPseNeFoto->HrefValue = FullUrl($this->iPseNeFoto->HrefValue, "href");
                }
            } else {
                $this->iPseNeFoto->HrefValue = "";
            }
            $this->iPseNeFoto->ExportHrefValue = $this->iPseNeFoto->UploadPath . $this->iPseNeFoto->Upload->DbValue;
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
        if ($this->iPseNeGjuha->Required) {
            if ($this->iPseNeGjuha->FormValue == "") {
                $this->iPseNeGjuha->addErrorMessage(str_replace("%s", $this->iPseNeGjuha->caption(), $this->iPseNeGjuha->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB1Titull->Required) {
            if (!$this->iPseNeB1Titull->IsDetailKey && EmptyValue($this->iPseNeB1Titull->FormValue)) {
                $this->iPseNeB1Titull->addErrorMessage(str_replace("%s", $this->iPseNeB1Titull->caption(), $this->iPseNeB1Titull->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB1Txt->Required) {
            if (!$this->iPseNeB1Txt->IsDetailKey && EmptyValue($this->iPseNeB1Txt->FormValue)) {
                $this->iPseNeB1Txt->addErrorMessage(str_replace("%s", $this->iPseNeB1Txt->caption(), $this->iPseNeB1Txt->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB1Ikona->Required) {
            if (!$this->iPseNeB1Ikona->IsDetailKey && EmptyValue($this->iPseNeB1Ikona->FormValue)) {
                $this->iPseNeB1Ikona->addErrorMessage(str_replace("%s", $this->iPseNeB1Ikona->caption(), $this->iPseNeB1Ikona->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB2Titull->Required) {
            if (!$this->iPseNeB2Titull->IsDetailKey && EmptyValue($this->iPseNeB2Titull->FormValue)) {
                $this->iPseNeB2Titull->addErrorMessage(str_replace("%s", $this->iPseNeB2Titull->caption(), $this->iPseNeB2Titull->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB2Txt->Required) {
            if (!$this->iPseNeB2Txt->IsDetailKey && EmptyValue($this->iPseNeB2Txt->FormValue)) {
                $this->iPseNeB2Txt->addErrorMessage(str_replace("%s", $this->iPseNeB2Txt->caption(), $this->iPseNeB2Txt->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB2Ikona->Required) {
            if (!$this->iPseNeB2Ikona->IsDetailKey && EmptyValue($this->iPseNeB2Ikona->FormValue)) {
                $this->iPseNeB2Ikona->addErrorMessage(str_replace("%s", $this->iPseNeB2Ikona->caption(), $this->iPseNeB2Ikona->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB3Titull->Required) {
            if (!$this->iPseNeB3Titull->IsDetailKey && EmptyValue($this->iPseNeB3Titull->FormValue)) {
                $this->iPseNeB3Titull->addErrorMessage(str_replace("%s", $this->iPseNeB3Titull->caption(), $this->iPseNeB3Titull->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB3Txt->Required) {
            if (!$this->iPseNeB3Txt->IsDetailKey && EmptyValue($this->iPseNeB3Txt->FormValue)) {
                $this->iPseNeB3Txt->addErrorMessage(str_replace("%s", $this->iPseNeB3Txt->caption(), $this->iPseNeB3Txt->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB3Ikona->Required) {
            if (!$this->iPseNeB3Ikona->IsDetailKey && EmptyValue($this->iPseNeB3Ikona->FormValue)) {
                $this->iPseNeB3Ikona->addErrorMessage(str_replace("%s", $this->iPseNeB3Ikona->caption(), $this->iPseNeB3Ikona->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB4Titull->Required) {
            if (!$this->iPseNeB4Titull->IsDetailKey && EmptyValue($this->iPseNeB4Titull->FormValue)) {
                $this->iPseNeB4Titull->addErrorMessage(str_replace("%s", $this->iPseNeB4Titull->caption(), $this->iPseNeB4Titull->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB4Txt->Required) {
            if (!$this->iPseNeB4Txt->IsDetailKey && EmptyValue($this->iPseNeB4Txt->FormValue)) {
                $this->iPseNeB4Txt->addErrorMessage(str_replace("%s", $this->iPseNeB4Txt->caption(), $this->iPseNeB4Txt->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeB4Ikona->Required) {
            if (!$this->iPseNeB4Ikona->IsDetailKey && EmptyValue($this->iPseNeB4Ikona->FormValue)) {
                $this->iPseNeB4Ikona->addErrorMessage(str_replace("%s", $this->iPseNeB4Ikona->caption(), $this->iPseNeB4Ikona->RequiredErrorMessage));
            }
        }
        if ($this->iPseNeFoto->Required) {
            if ($this->iPseNeFoto->Upload->FileName == "" && !$this->iPseNeFoto->Upload->KeepFile) {
                $this->iPseNeFoto->addErrorMessage(str_replace("%s", $this->iPseNeFoto->caption(), $this->iPseNeFoto->RequiredErrorMessage));
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
            $this->iPseNeFoto->OldUploadPath = '../ngarkime/index/psene/';
            $this->iPseNeFoto->UploadPath = $this->iPseNeFoto->OldUploadPath;
        }

        // Set new row
        $rsnew = [];

        // iPseNeGjuha
        $this->iPseNeGjuha->setDbValueDef($rsnew, $this->iPseNeGjuha->CurrentValue, "", $this->iPseNeGjuha->ReadOnly);

        // iPseNeB1Titull
        $this->iPseNeB1Titull->setDbValueDef($rsnew, $this->iPseNeB1Titull->CurrentValue, "", $this->iPseNeB1Titull->ReadOnly);

        // iPseNeB1Txt
        $this->iPseNeB1Txt->setDbValueDef($rsnew, $this->iPseNeB1Txt->CurrentValue, "", $this->iPseNeB1Txt->ReadOnly);

        // iPseNeB1Ikona
        $this->iPseNeB1Ikona->setDbValueDef($rsnew, $this->iPseNeB1Ikona->CurrentValue, "", $this->iPseNeB1Ikona->ReadOnly);

        // iPseNeB2Titull
        $this->iPseNeB2Titull->setDbValueDef($rsnew, $this->iPseNeB2Titull->CurrentValue, "", $this->iPseNeB2Titull->ReadOnly);

        // iPseNeB2Txt
        $this->iPseNeB2Txt->setDbValueDef($rsnew, $this->iPseNeB2Txt->CurrentValue, "", $this->iPseNeB2Txt->ReadOnly);

        // iPseNeB2Ikona
        $this->iPseNeB2Ikona->setDbValueDef($rsnew, $this->iPseNeB2Ikona->CurrentValue, "", $this->iPseNeB2Ikona->ReadOnly);

        // iPseNeB3Titull
        $this->iPseNeB3Titull->setDbValueDef($rsnew, $this->iPseNeB3Titull->CurrentValue, "", $this->iPseNeB3Titull->ReadOnly);

        // iPseNeB3Txt
        $this->iPseNeB3Txt->setDbValueDef($rsnew, $this->iPseNeB3Txt->CurrentValue, "", $this->iPseNeB3Txt->ReadOnly);

        // iPseNeB3Ikona
        $this->iPseNeB3Ikona->setDbValueDef($rsnew, $this->iPseNeB3Ikona->CurrentValue, "", $this->iPseNeB3Ikona->ReadOnly);

        // iPseNeB4Titull
        $this->iPseNeB4Titull->setDbValueDef($rsnew, $this->iPseNeB4Titull->CurrentValue, "", $this->iPseNeB4Titull->ReadOnly);

        // iPseNeB4Txt
        $this->iPseNeB4Txt->setDbValueDef($rsnew, $this->iPseNeB4Txt->CurrentValue, "", $this->iPseNeB4Txt->ReadOnly);

        // iPseNeB4Ikona
        $this->iPseNeB4Ikona->setDbValueDef($rsnew, $this->iPseNeB4Ikona->CurrentValue, "", $this->iPseNeB4Ikona->ReadOnly);

        // iPseNeFoto
        if ($this->iPseNeFoto->Visible && !$this->iPseNeFoto->ReadOnly && !$this->iPseNeFoto->Upload->KeepFile) {
            $this->iPseNeFoto->Upload->DbValue = $rsold['iPseNeFoto']; // Get original value
            if ($this->iPseNeFoto->Upload->FileName == "") {
                $rsnew['iPseNeFoto'] = null;
            } else {
                $rsnew['iPseNeFoto'] = $this->iPseNeFoto->Upload->FileName;
            }
        }

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->iPseNeFoto->Visible && !$this->iPseNeFoto->Upload->KeepFile) {
            $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
            $oldFiles = EmptyValue($this->iPseNeFoto->Upload->DbValue) ? [] : [$this->iPseNeFoto->htmlDecode($this->iPseNeFoto->Upload->DbValue)];
            if (!EmptyValue($this->iPseNeFoto->Upload->FileName)) {
                $newFiles = [$this->iPseNeFoto->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->iPseNeFoto, $this->iPseNeFoto->Upload->Index);
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
                            $file1 = UniqueFilename($this->iPseNeFoto->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->iPseNeFoto->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->iPseNeFoto->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->iPseNeFoto->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->iPseNeFoto->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->iPseNeFoto->setDbValueDef($rsnew, $this->iPseNeFoto->Upload->FileName, "", $this->iPseNeFoto->ReadOnly);
            }
        }

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
                if ($this->iPseNeFoto->Visible && !$this->iPseNeFoto->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->iPseNeFoto->Upload->DbValue) ? [] : [$this->iPseNeFoto->htmlDecode($this->iPseNeFoto->Upload->DbValue)];
                    if (!EmptyValue($this->iPseNeFoto->Upload->FileName)) {
                        $newFiles = [$this->iPseNeFoto->Upload->FileName];
                        $newFiles2 = [$this->iPseNeFoto->htmlDecode($rsnew['iPseNeFoto'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->iPseNeFoto, $this->iPseNeFoto->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->iPseNeFoto->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->iPseNeFoto->oldPhysicalUploadPath() . $oldFile);
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
            // iPseNeFoto
            CleanUploadTempPath($this->iPseNeFoto, $this->iPseNeFoto->Upload->Index);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("IndexPseneList"), "", $this->TableVar, true);
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
                case "x_iPseNeGjuha":
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
