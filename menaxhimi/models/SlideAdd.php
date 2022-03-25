<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class SlideAdd extends Slide
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'slide';

    // Page object name
    public $PageObjName = "SlideAdd";

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

        // Table object (slide)
        if (!isset($GLOBALS["slide"]) || get_class($GLOBALS["slide"]) == PROJECT_NAMESPACE . "slide") {
            $GLOBALS["slide"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'slide');
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
                $tbl = Container("slide");
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
                    if ($pageName == "SlideView") {
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
		        $this->slideFoto->OldUploadPath = '../ngarkime/slide/';
		        $this->slideFoto->UploadPath = $this->slideFoto->OldUploadPath;
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
            $key .= @$ar['slideID'];
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
            $this->slideID->Visible = false;
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
        $this->slideID->Visible = false;
        $this->slideGjuha->setVisibility();
        $this->slideFoto->setVisibility();
        $this->slideTxt1->setVisibility();
        $this->slideTxt2->setVisibility();
        $this->slideTxt3->setVisibility();
        $this->slideButonTxt->setVisibility();
        $this->slideLink->setVisibility();
        $this->slideTarget->setVisibility();
        $this->slideRadha->setVisibility();
        $this->slideAktiv->setVisibility();
        $this->slideAutori->setVisibility();
        $this->slideKrijuar->setVisibility();
        $this->slideAzhornuar->Visible = false;
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
        $this->setupLookupOptions($this->slideGjuha);
        $this->setupLookupOptions($this->slideTarget);
        $this->setupLookupOptions($this->slideAktiv);
        $this->setupLookupOptions($this->slideAutori);

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
            if (($keyValue = Get("slideID") ?? Route("slideID")) !== null) {
                $this->slideID->setQueryStringValue($keyValue);
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
                    $this->terminate("SlideList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "SlideList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "SlideView") {
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
        $this->slideFoto->Upload->Index = $CurrentForm->Index;
        $this->slideFoto->Upload->uploadFile();
        $this->slideFoto->CurrentValue = $this->slideFoto->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->slideGjuha->DefaultValue = "sq";
        $this->slideTarget->DefaultValue = "_self";
        $this->slideRadha->DefaultValue = 1;
        $this->slideAktiv->DefaultValue = "Po";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'slideGjuha' first before field var 'x_slideGjuha'
        $val = $CurrentForm->hasValue("slideGjuha") ? $CurrentForm->getValue("slideGjuha") : $CurrentForm->getValue("x_slideGjuha");
        if (!$this->slideGjuha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideGjuha->Visible = false; // Disable update for API request
            } else {
                $this->slideGjuha->setFormValue($val);
            }
        }

        // Check field name 'slideTxt1' first before field var 'x_slideTxt1'
        $val = $CurrentForm->hasValue("slideTxt1") ? $CurrentForm->getValue("slideTxt1") : $CurrentForm->getValue("x_slideTxt1");
        if (!$this->slideTxt1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideTxt1->Visible = false; // Disable update for API request
            } else {
                $this->slideTxt1->setFormValue($val);
            }
        }

        // Check field name 'slideTxt2' first before field var 'x_slideTxt2'
        $val = $CurrentForm->hasValue("slideTxt2") ? $CurrentForm->getValue("slideTxt2") : $CurrentForm->getValue("x_slideTxt2");
        if (!$this->slideTxt2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideTxt2->Visible = false; // Disable update for API request
            } else {
                $this->slideTxt2->setFormValue($val);
            }
        }

        // Check field name 'slideTxt3' first before field var 'x_slideTxt3'
        $val = $CurrentForm->hasValue("slideTxt3") ? $CurrentForm->getValue("slideTxt3") : $CurrentForm->getValue("x_slideTxt3");
        if (!$this->slideTxt3->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideTxt3->Visible = false; // Disable update for API request
            } else {
                $this->slideTxt3->setFormValue($val);
            }
        }

        // Check field name 'slideButonTxt' first before field var 'x_slideButonTxt'
        $val = $CurrentForm->hasValue("slideButonTxt") ? $CurrentForm->getValue("slideButonTxt") : $CurrentForm->getValue("x_slideButonTxt");
        if (!$this->slideButonTxt->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideButonTxt->Visible = false; // Disable update for API request
            } else {
                $this->slideButonTxt->setFormValue($val);
            }
        }

        // Check field name 'slideLink' first before field var 'x_slideLink'
        $val = $CurrentForm->hasValue("slideLink") ? $CurrentForm->getValue("slideLink") : $CurrentForm->getValue("x_slideLink");
        if (!$this->slideLink->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideLink->Visible = false; // Disable update for API request
            } else {
                $this->slideLink->setFormValue($val);
            }
        }

        // Check field name 'slideTarget' first before field var 'x_slideTarget'
        $val = $CurrentForm->hasValue("slideTarget") ? $CurrentForm->getValue("slideTarget") : $CurrentForm->getValue("x_slideTarget");
        if (!$this->slideTarget->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideTarget->Visible = false; // Disable update for API request
            } else {
                $this->slideTarget->setFormValue($val);
            }
        }

        // Check field name 'slideRadha' first before field var 'x_slideRadha'
        $val = $CurrentForm->hasValue("slideRadha") ? $CurrentForm->getValue("slideRadha") : $CurrentForm->getValue("x_slideRadha");
        if (!$this->slideRadha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideRadha->Visible = false; // Disable update for API request
            } else {
                $this->slideRadha->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'slideAktiv' first before field var 'x_slideAktiv'
        $val = $CurrentForm->hasValue("slideAktiv") ? $CurrentForm->getValue("slideAktiv") : $CurrentForm->getValue("x_slideAktiv");
        if (!$this->slideAktiv->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideAktiv->Visible = false; // Disable update for API request
            } else {
                $this->slideAktiv->setFormValue($val);
            }
        }

        // Check field name 'slideAutori' first before field var 'x_slideAutori'
        $val = $CurrentForm->hasValue("slideAutori") ? $CurrentForm->getValue("slideAutori") : $CurrentForm->getValue("x_slideAutori");
        if (!$this->slideAutori->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideAutori->Visible = false; // Disable update for API request
            } else {
                $this->slideAutori->setFormValue($val);
            }
        }

        // Check field name 'slideKrijuar' first before field var 'x_slideKrijuar'
        $val = $CurrentForm->hasValue("slideKrijuar") ? $CurrentForm->getValue("slideKrijuar") : $CurrentForm->getValue("x_slideKrijuar");
        if (!$this->slideKrijuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->slideKrijuar->Visible = false; // Disable update for API request
            } else {
                $this->slideKrijuar->setFormValue($val);
            }
            $this->slideKrijuar->CurrentValue = UnFormatDateTime($this->slideKrijuar->CurrentValue, $this->slideKrijuar->formatPattern());
        }

        // Check field name 'slideID' first before field var 'x_slideID'
        $val = $CurrentForm->hasValue("slideID") ? $CurrentForm->getValue("slideID") : $CurrentForm->getValue("x_slideID");
		$this->slideFoto->OldUploadPath = '../ngarkime/slide/';
		$this->slideFoto->UploadPath = $this->slideFoto->OldUploadPath;
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->slideGjuha->CurrentValue = $this->slideGjuha->FormValue;
        $this->slideTxt1->CurrentValue = $this->slideTxt1->FormValue;
        $this->slideTxt2->CurrentValue = $this->slideTxt2->FormValue;
        $this->slideTxt3->CurrentValue = $this->slideTxt3->FormValue;
        $this->slideButonTxt->CurrentValue = $this->slideButonTxt->FormValue;
        $this->slideLink->CurrentValue = $this->slideLink->FormValue;
        $this->slideTarget->CurrentValue = $this->slideTarget->FormValue;
        $this->slideRadha->CurrentValue = $this->slideRadha->FormValue;
        $this->slideAktiv->CurrentValue = $this->slideAktiv->FormValue;
        $this->slideAutori->CurrentValue = $this->slideAutori->FormValue;
        $this->slideKrijuar->CurrentValue = $this->slideKrijuar->FormValue;
        $this->slideKrijuar->CurrentValue = UnFormatDateTime($this->slideKrijuar->CurrentValue, $this->slideKrijuar->formatPattern());
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
        $this->slideID->setDbValue($row['slideID']);
        $this->slideGjuha->setDbValue($row['slideGjuha']);
        $this->slideFoto->Upload->DbValue = $row['slideFoto'];
        $this->slideFoto->setDbValue($this->slideFoto->Upload->DbValue);
        $this->slideTxt1->setDbValue($row['slideTxt1']);
        $this->slideTxt2->setDbValue($row['slideTxt2']);
        $this->slideTxt3->setDbValue($row['slideTxt3']);
        $this->slideButonTxt->setDbValue($row['slideButonTxt']);
        $this->slideLink->setDbValue($row['slideLink']);
        $this->slideTarget->setDbValue($row['slideTarget']);
        $this->slideRadha->setDbValue($row['slideRadha']);
        $this->slideAktiv->setDbValue($row['slideAktiv']);
        $this->slideAutori->setDbValue($row['slideAutori']);
        $this->slideKrijuar->setDbValue($row['slideKrijuar']);
        $this->slideAzhornuar->setDbValue($row['slideAzhornuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['slideID'] = $this->slideID->DefaultValue;
        $row['slideGjuha'] = $this->slideGjuha->DefaultValue;
        $row['slideFoto'] = $this->slideFoto->DefaultValue;
        $row['slideTxt1'] = $this->slideTxt1->DefaultValue;
        $row['slideTxt2'] = $this->slideTxt2->DefaultValue;
        $row['slideTxt3'] = $this->slideTxt3->DefaultValue;
        $row['slideButonTxt'] = $this->slideButonTxt->DefaultValue;
        $row['slideLink'] = $this->slideLink->DefaultValue;
        $row['slideTarget'] = $this->slideTarget->DefaultValue;
        $row['slideRadha'] = $this->slideRadha->DefaultValue;
        $row['slideAktiv'] = $this->slideAktiv->DefaultValue;
        $row['slideAutori'] = $this->slideAutori->DefaultValue;
        $row['slideKrijuar'] = $this->slideKrijuar->DefaultValue;
        $row['slideAzhornuar'] = $this->slideAzhornuar->DefaultValue;
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

        // slideID
        $this->slideID->RowCssClass = "row";

        // slideGjuha
        $this->slideGjuha->RowCssClass = "row";

        // slideFoto
        $this->slideFoto->RowCssClass = "row";

        // slideTxt1
        $this->slideTxt1->RowCssClass = "row";

        // slideTxt2
        $this->slideTxt2->RowCssClass = "row";

        // slideTxt3
        $this->slideTxt3->RowCssClass = "row";

        // slideButonTxt
        $this->slideButonTxt->RowCssClass = "row";

        // slideLink
        $this->slideLink->RowCssClass = "row";

        // slideTarget
        $this->slideTarget->RowCssClass = "row";

        // slideRadha
        $this->slideRadha->RowCssClass = "row";

        // slideAktiv
        $this->slideAktiv->RowCssClass = "row";

        // slideAutori
        $this->slideAutori->RowCssClass = "row";

        // slideKrijuar
        $this->slideKrijuar->RowCssClass = "row";

        // slideAzhornuar
        $this->slideAzhornuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // slideID
            $this->slideID->ViewValue = $this->slideID->CurrentValue;
            $this->slideID->ViewCustomAttributes = "";

            // slideGjuha
            if (strval($this->slideGjuha->CurrentValue) != "") {
                $this->slideGjuha->ViewValue = $this->slideGjuha->optionCaption($this->slideGjuha->CurrentValue);
            } else {
                $this->slideGjuha->ViewValue = null;
            }
            $this->slideGjuha->CssClass = "fw-bold";
            $this->slideGjuha->ViewCustomAttributes = "";

            // slideFoto
            $this->slideFoto->UploadPath = '../ngarkime/slide/';
            if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
                $this->slideFoto->ImageWidth = 100;
                $this->slideFoto->ImageHeight = 0;
                $this->slideFoto->ImageAlt = $this->slideFoto->alt();
                $this->slideFoto->ImageCssClass = "ew-image";
                $this->slideFoto->ViewValue = $this->slideFoto->Upload->DbValue;
            } else {
                $this->slideFoto->ViewValue = "";
            }
            $this->slideFoto->ViewCustomAttributes = "";

            // slideTxt1
            $this->slideTxt1->ViewValue = $this->slideTxt1->CurrentValue;
            $this->slideTxt1->ViewCustomAttributes = "";

            // slideTxt2
            $this->slideTxt2->ViewValue = $this->slideTxt2->CurrentValue;
            $this->slideTxt2->ViewCustomAttributes = "";

            // slideTxt3
            $this->slideTxt3->ViewValue = $this->slideTxt3->CurrentValue;
            $this->slideTxt3->ViewCustomAttributes = "";

            // slideButonTxt
            $this->slideButonTxt->ViewValue = $this->slideButonTxt->CurrentValue;
            $this->slideButonTxt->ViewCustomAttributes = "";

            // slideLink
            $this->slideLink->ViewValue = $this->slideLink->CurrentValue;
            $this->slideLink->ViewCustomAttributes = "";

            // slideTarget
            if (strval($this->slideTarget->CurrentValue) != "") {
                $this->slideTarget->ViewValue = $this->slideTarget->optionCaption($this->slideTarget->CurrentValue);
            } else {
                $this->slideTarget->ViewValue = null;
            }
            $this->slideTarget->ViewCustomAttributes = "";

            // slideRadha
            $this->slideRadha->ViewValue = $this->slideRadha->CurrentValue;
            $this->slideRadha->ViewValue = FormatNumber($this->slideRadha->ViewValue, $this->slideRadha->formatPattern());
            $this->slideRadha->ViewCustomAttributes = "";

            // slideAktiv
            if (strval($this->slideAktiv->CurrentValue) != "") {
                $this->slideAktiv->ViewValue = $this->slideAktiv->optionCaption($this->slideAktiv->CurrentValue);
            } else {
                $this->slideAktiv->ViewValue = null;
            }
            $this->slideAktiv->ViewCustomAttributes = "";

            // slideAutori
            $this->slideAutori->ViewValue = $this->slideAutori->CurrentValue;
            $curVal = strval($this->slideAutori->CurrentValue);
            if ($curVal != "") {
                $this->slideAutori->ViewValue = $this->slideAutori->lookupCacheOption($curVal);
                if ($this->slideAutori->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->slideAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->slideAutori->Lookup->renderViewRow($rswrk[0]);
                        $this->slideAutori->ViewValue = $this->slideAutori->displayValue($arwrk);
                    } else {
                        $this->slideAutori->ViewValue = FormatNumber($this->slideAutori->CurrentValue, $this->slideAutori->formatPattern());
                    }
                }
            } else {
                $this->slideAutori->ViewValue = null;
            }
            $this->slideAutori->ViewCustomAttributes = "";

            // slideKrijuar
            $this->slideKrijuar->ViewValue = $this->slideKrijuar->CurrentValue;
            $this->slideKrijuar->ViewValue = FormatDateTime($this->slideKrijuar->ViewValue, $this->slideKrijuar->formatPattern());
            $this->slideKrijuar->ViewCustomAttributes = "";

            // slideAzhornuar
            $this->slideAzhornuar->ViewValue = $this->slideAzhornuar->CurrentValue;
            $this->slideAzhornuar->ViewValue = FormatDateTime($this->slideAzhornuar->ViewValue, $this->slideAzhornuar->formatPattern());
            $this->slideAzhornuar->ViewCustomAttributes = "";

            // slideGjuha
            $this->slideGjuha->LinkCustomAttributes = "";
            $this->slideGjuha->HrefValue = "";

            // slideFoto
            $this->slideFoto->LinkCustomAttributes = "";
            $this->slideFoto->UploadPath = '../ngarkime/slide/';
            if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
                $this->slideFoto->HrefValue = GetFileUploadUrl($this->slideFoto, $this->slideFoto->htmlDecode($this->slideFoto->Upload->DbValue)); // Add prefix/suffix
                $this->slideFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->slideFoto->HrefValue = FullUrl($this->slideFoto->HrefValue, "href");
                }
            } else {
                $this->slideFoto->HrefValue = "";
            }
            $this->slideFoto->ExportHrefValue = $this->slideFoto->UploadPath . $this->slideFoto->Upload->DbValue;

            // slideTxt1
            $this->slideTxt1->LinkCustomAttributes = "";
            $this->slideTxt1->HrefValue = "";

            // slideTxt2
            $this->slideTxt2->LinkCustomAttributes = "";
            $this->slideTxt2->HrefValue = "";

            // slideTxt3
            $this->slideTxt3->LinkCustomAttributes = "";
            $this->slideTxt3->HrefValue = "";

            // slideButonTxt
            $this->slideButonTxt->LinkCustomAttributes = "";
            if (!EmptyValue($this->slideLink->CurrentValue)) {
                $this->slideButonTxt->HrefValue = (!empty($this->slideLink->EditValue) && !is_array($this->slideLink->EditValue) ? RemoveHtml($this->slideLink->EditValue) : $this->slideLink->CurrentValue); // Add prefix/suffix
                $this->slideButonTxt->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->slideButonTxt->HrefValue = FullUrl($this->slideButonTxt->HrefValue, "href");
                }
            } else {
                $this->slideButonTxt->HrefValue = "";
            }

            // slideLink
            $this->slideLink->LinkCustomAttributes = "";
            $this->slideLink->HrefValue = "";

            // slideTarget
            $this->slideTarget->LinkCustomAttributes = "";
            $this->slideTarget->HrefValue = "";

            // slideRadha
            $this->slideRadha->LinkCustomAttributes = "";
            $this->slideRadha->HrefValue = "";

            // slideAktiv
            $this->slideAktiv->LinkCustomAttributes = "";
            $this->slideAktiv->HrefValue = "";

            // slideAutori
            $this->slideAutori->LinkCustomAttributes = "";
            $this->slideAutori->HrefValue = "";

            // slideKrijuar
            $this->slideKrijuar->LinkCustomAttributes = "";
            $this->slideKrijuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // slideGjuha
            $this->slideGjuha->EditCustomAttributes = "";
            $this->slideGjuha->EditValue = $this->slideGjuha->options(false);
            $this->slideGjuha->PlaceHolder = RemoveHtml($this->slideGjuha->caption());

            // slideFoto
            $this->slideFoto->setupEditAttributes();
            $this->slideFoto->EditCustomAttributes = "";
            $this->slideFoto->UploadPath = '../ngarkime/slide/';
            if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
                $this->slideFoto->ImageWidth = 100;
                $this->slideFoto->ImageHeight = 0;
                $this->slideFoto->ImageAlt = $this->slideFoto->alt();
                $this->slideFoto->ImageCssClass = "ew-image";
                $this->slideFoto->EditValue = $this->slideFoto->Upload->DbValue;
            } else {
                $this->slideFoto->EditValue = "";
            }
            if (!EmptyValue($this->slideFoto->CurrentValue)) {
                $this->slideFoto->Upload->FileName = $this->slideFoto->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->slideFoto);
            }

            // slideTxt1
            $this->slideTxt1->setupEditAttributes();
            $this->slideTxt1->EditCustomAttributes = "";
            if (!$this->slideTxt1->Raw) {
                $this->slideTxt1->CurrentValue = HtmlDecode($this->slideTxt1->CurrentValue);
            }
            $this->slideTxt1->EditValue = HtmlEncode($this->slideTxt1->CurrentValue);
            $this->slideTxt1->PlaceHolder = RemoveHtml($this->slideTxt1->caption());

            // slideTxt2
            $this->slideTxt2->setupEditAttributes();
            $this->slideTxt2->EditCustomAttributes = "";
            if (!$this->slideTxt2->Raw) {
                $this->slideTxt2->CurrentValue = HtmlDecode($this->slideTxt2->CurrentValue);
            }
            $this->slideTxt2->EditValue = HtmlEncode($this->slideTxt2->CurrentValue);
            $this->slideTxt2->PlaceHolder = RemoveHtml($this->slideTxt2->caption());

            // slideTxt3
            $this->slideTxt3->setupEditAttributes();
            $this->slideTxt3->EditCustomAttributes = "";
            if (!$this->slideTxt3->Raw) {
                $this->slideTxt3->CurrentValue = HtmlDecode($this->slideTxt3->CurrentValue);
            }
            $this->slideTxt3->EditValue = HtmlEncode($this->slideTxt3->CurrentValue);
            $this->slideTxt3->PlaceHolder = RemoveHtml($this->slideTxt3->caption());

            // slideButonTxt
            $this->slideButonTxt->setupEditAttributes();
            $this->slideButonTxt->EditCustomAttributes = "";
            if (!$this->slideButonTxt->Raw) {
                $this->slideButonTxt->CurrentValue = HtmlDecode($this->slideButonTxt->CurrentValue);
            }
            $this->slideButonTxt->EditValue = HtmlEncode($this->slideButonTxt->CurrentValue);
            $this->slideButonTxt->PlaceHolder = RemoveHtml($this->slideButonTxt->caption());

            // slideLink
            $this->slideLink->setupEditAttributes();
            $this->slideLink->EditCustomAttributes = "";
            if (!$this->slideLink->Raw) {
                $this->slideLink->CurrentValue = HtmlDecode($this->slideLink->CurrentValue);
            }
            $this->slideLink->EditValue = HtmlEncode($this->slideLink->CurrentValue);
            $this->slideLink->PlaceHolder = RemoveHtml($this->slideLink->caption());

            // slideTarget
            $this->slideTarget->EditCustomAttributes = "";
            $this->slideTarget->EditValue = $this->slideTarget->options(false);
            $this->slideTarget->PlaceHolder = RemoveHtml($this->slideTarget->caption());

            // slideRadha
            $this->slideRadha->setupEditAttributes();
            $this->slideRadha->EditCustomAttributes = "";
            $this->slideRadha->EditValue = HtmlEncode($this->slideRadha->CurrentValue);
            $this->slideRadha->PlaceHolder = RemoveHtml($this->slideRadha->caption());
            if (strval($this->slideRadha->EditValue) != "" && is_numeric($this->slideRadha->EditValue)) {
                $this->slideRadha->EditValue = FormatNumber($this->slideRadha->EditValue, null);
            }

            // slideAktiv
            $this->slideAktiv->EditCustomAttributes = "";
            $this->slideAktiv->EditValue = $this->slideAktiv->options(false);
            $this->slideAktiv->PlaceHolder = RemoveHtml($this->slideAktiv->caption());

            // slideAutori

            // slideKrijuar

            // Add refer script

            // slideGjuha
            $this->slideGjuha->LinkCustomAttributes = "";
            $this->slideGjuha->HrefValue = "";

            // slideFoto
            $this->slideFoto->LinkCustomAttributes = "";
            $this->slideFoto->UploadPath = '../ngarkime/slide/';
            if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
                $this->slideFoto->HrefValue = GetFileUploadUrl($this->slideFoto, $this->slideFoto->htmlDecode($this->slideFoto->Upload->DbValue)); // Add prefix/suffix
                $this->slideFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->slideFoto->HrefValue = FullUrl($this->slideFoto->HrefValue, "href");
                }
            } else {
                $this->slideFoto->HrefValue = "";
            }
            $this->slideFoto->ExportHrefValue = $this->slideFoto->UploadPath . $this->slideFoto->Upload->DbValue;

            // slideTxt1
            $this->slideTxt1->LinkCustomAttributes = "";
            $this->slideTxt1->HrefValue = "";

            // slideTxt2
            $this->slideTxt2->LinkCustomAttributes = "";
            $this->slideTxt2->HrefValue = "";

            // slideTxt3
            $this->slideTxt3->LinkCustomAttributes = "";
            $this->slideTxt3->HrefValue = "";

            // slideButonTxt
            $this->slideButonTxt->LinkCustomAttributes = "";
            if (!EmptyValue($this->slideLink->CurrentValue)) {
                $this->slideButonTxt->HrefValue = (!empty($this->slideLink->EditValue) && !is_array($this->slideLink->EditValue) ? RemoveHtml($this->slideLink->EditValue) : $this->slideLink->CurrentValue); // Add prefix/suffix
                $this->slideButonTxt->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->slideButonTxt->HrefValue = FullUrl($this->slideButonTxt->HrefValue, "href");
                }
            } else {
                $this->slideButonTxt->HrefValue = "";
            }

            // slideLink
            $this->slideLink->LinkCustomAttributes = "";
            $this->slideLink->HrefValue = "";

            // slideTarget
            $this->slideTarget->LinkCustomAttributes = "";
            $this->slideTarget->HrefValue = "";

            // slideRadha
            $this->slideRadha->LinkCustomAttributes = "";
            $this->slideRadha->HrefValue = "";

            // slideAktiv
            $this->slideAktiv->LinkCustomAttributes = "";
            $this->slideAktiv->HrefValue = "";

            // slideAutori
            $this->slideAutori->LinkCustomAttributes = "";
            $this->slideAutori->HrefValue = "";

            // slideKrijuar
            $this->slideKrijuar->LinkCustomAttributes = "";
            $this->slideKrijuar->HrefValue = "";
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
        if ($this->slideGjuha->Required) {
            if ($this->slideGjuha->FormValue == "") {
                $this->slideGjuha->addErrorMessage(str_replace("%s", $this->slideGjuha->caption(), $this->slideGjuha->RequiredErrorMessage));
            }
        }
        if ($this->slideFoto->Required) {
            if ($this->slideFoto->Upload->FileName == "" && !$this->slideFoto->Upload->KeepFile) {
                $this->slideFoto->addErrorMessage(str_replace("%s", $this->slideFoto->caption(), $this->slideFoto->RequiredErrorMessage));
            }
        }
        if ($this->slideTxt1->Required) {
            if (!$this->slideTxt1->IsDetailKey && EmptyValue($this->slideTxt1->FormValue)) {
                $this->slideTxt1->addErrorMessage(str_replace("%s", $this->slideTxt1->caption(), $this->slideTxt1->RequiredErrorMessage));
            }
        }
        if ($this->slideTxt2->Required) {
            if (!$this->slideTxt2->IsDetailKey && EmptyValue($this->slideTxt2->FormValue)) {
                $this->slideTxt2->addErrorMessage(str_replace("%s", $this->slideTxt2->caption(), $this->slideTxt2->RequiredErrorMessage));
            }
        }
        if ($this->slideTxt3->Required) {
            if (!$this->slideTxt3->IsDetailKey && EmptyValue($this->slideTxt3->FormValue)) {
                $this->slideTxt3->addErrorMessage(str_replace("%s", $this->slideTxt3->caption(), $this->slideTxt3->RequiredErrorMessage));
            }
        }
        if ($this->slideButonTxt->Required) {
            if (!$this->slideButonTxt->IsDetailKey && EmptyValue($this->slideButonTxt->FormValue)) {
                $this->slideButonTxt->addErrorMessage(str_replace("%s", $this->slideButonTxt->caption(), $this->slideButonTxt->RequiredErrorMessage));
            }
        }
        if ($this->slideLink->Required) {
            if (!$this->slideLink->IsDetailKey && EmptyValue($this->slideLink->FormValue)) {
                $this->slideLink->addErrorMessage(str_replace("%s", $this->slideLink->caption(), $this->slideLink->RequiredErrorMessage));
            }
        }
        if ($this->slideTarget->Required) {
            if ($this->slideTarget->FormValue == "") {
                $this->slideTarget->addErrorMessage(str_replace("%s", $this->slideTarget->caption(), $this->slideTarget->RequiredErrorMessage));
            }
        }
        if ($this->slideRadha->Required) {
            if (!$this->slideRadha->IsDetailKey && EmptyValue($this->slideRadha->FormValue)) {
                $this->slideRadha->addErrorMessage(str_replace("%s", $this->slideRadha->caption(), $this->slideRadha->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->slideRadha->FormValue)) {
            $this->slideRadha->addErrorMessage($this->slideRadha->getErrorMessage(false));
        }
        if ($this->slideAktiv->Required) {
            if ($this->slideAktiv->FormValue == "") {
                $this->slideAktiv->addErrorMessage(str_replace("%s", $this->slideAktiv->caption(), $this->slideAktiv->RequiredErrorMessage));
            }
        }
        if ($this->slideAutori->Required) {
            if (!$this->slideAutori->IsDetailKey && EmptyValue($this->slideAutori->FormValue)) {
                $this->slideAutori->addErrorMessage(str_replace("%s", $this->slideAutori->caption(), $this->slideAutori->RequiredErrorMessage));
            }
        }
        if ($this->slideKrijuar->Required) {
            if (!$this->slideKrijuar->IsDetailKey && EmptyValue($this->slideKrijuar->FormValue)) {
                $this->slideKrijuar->addErrorMessage(str_replace("%s", $this->slideKrijuar->caption(), $this->slideKrijuar->RequiredErrorMessage));
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

        // slideGjuha
        $this->slideGjuha->setDbValueDef($rsnew, $this->slideGjuha->CurrentValue, "", strval($this->slideGjuha->CurrentValue ?? "") == "");

        // slideFoto
        if ($this->slideFoto->Visible && !$this->slideFoto->Upload->KeepFile) {
            $this->slideFoto->Upload->DbValue = ""; // No need to delete old file
            if ($this->slideFoto->Upload->FileName == "") {
                $rsnew['slideFoto'] = null;
            } else {
                $rsnew['slideFoto'] = $this->slideFoto->Upload->FileName;
            }
        }

        // slideTxt1
        $this->slideTxt1->setDbValueDef($rsnew, $this->slideTxt1->CurrentValue, null, false);

        // slideTxt2
        $this->slideTxt2->setDbValueDef($rsnew, $this->slideTxt2->CurrentValue, "", false);

        // slideTxt3
        $this->slideTxt3->setDbValueDef($rsnew, $this->slideTxt3->CurrentValue, null, false);

        // slideButonTxt
        $this->slideButonTxt->setDbValueDef($rsnew, $this->slideButonTxt->CurrentValue, "", false);

        // slideLink
        $this->slideLink->setDbValueDef($rsnew, $this->slideLink->CurrentValue, "", false);

        // slideTarget
        $this->slideTarget->setDbValueDef($rsnew, $this->slideTarget->CurrentValue, "", strval($this->slideTarget->CurrentValue ?? "") == "");

        // slideRadha
        $this->slideRadha->setDbValueDef($rsnew, $this->slideRadha->CurrentValue, 0, strval($this->slideRadha->CurrentValue ?? "") == "");

        // slideAktiv
        $this->slideAktiv->setDbValueDef($rsnew, $this->slideAktiv->CurrentValue, "", strval($this->slideAktiv->CurrentValue ?? "") == "");

        // slideAutori
        $this->slideAutori->CurrentValue = CurrentUserID();
        $this->slideAutori->setDbValueDef($rsnew, $this->slideAutori->CurrentValue, 0);

        // slideKrijuar
        $this->slideKrijuar->CurrentValue = CurrentDateTime();
        $this->slideKrijuar->setDbValueDef($rsnew, $this->slideKrijuar->CurrentValue, CurrentDate());
        if ($this->slideFoto->Visible && !$this->slideFoto->Upload->KeepFile) {
            $this->slideFoto->UploadPath = '../ngarkime/slide/';
            $oldFiles = EmptyValue($this->slideFoto->Upload->DbValue) ? [] : [$this->slideFoto->htmlDecode($this->slideFoto->Upload->DbValue)];
            if (!EmptyValue($this->slideFoto->Upload->FileName)) {
                $newFiles = [$this->slideFoto->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->slideFoto, $this->slideFoto->Upload->Index);
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
                            $file1 = UniqueFilename($this->slideFoto->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->slideFoto->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->slideFoto->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->slideFoto->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->slideFoto->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->slideFoto->setDbValueDef($rsnew, $this->slideFoto->Upload->FileName, "", false);
            }
        }

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
            $this->slideFoto->OldUploadPath = '../ngarkime/slide/';
            $this->slideFoto->UploadPath = $this->slideFoto->OldUploadPath;
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->slideFoto->Visible && !$this->slideFoto->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->slideFoto->Upload->DbValue) ? [] : [$this->slideFoto->htmlDecode($this->slideFoto->Upload->DbValue)];
                    if (!EmptyValue($this->slideFoto->Upload->FileName)) {
                        $newFiles = [$this->slideFoto->Upload->FileName];
                        $newFiles2 = [$this->slideFoto->htmlDecode($rsnew['slideFoto'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->slideFoto, $this->slideFoto->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->slideFoto->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->slideFoto->oldPhysicalUploadPath() . $oldFile);
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
            // slideFoto
            CleanUploadTempPath($this->slideFoto, $this->slideFoto->Upload->Index);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("SlideList"), "", $this->TableVar, true);
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
                case "x_slideGjuha":
                    break;
                case "x_slideTarget":
                    break;
                case "x_slideAktiv":
                    break;
                case "x_slideAutori":
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
