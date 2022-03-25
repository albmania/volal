<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class BlogAdd extends Blog
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'blog';

    // Page object name
    public $PageObjName = "BlogAdd";

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

        // Table object (blog)
        if (!isset($GLOBALS["blog"]) || get_class($GLOBALS["blog"]) == PROJECT_NAMESPACE . "blog") {
            $GLOBALS["blog"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'blog');
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
                $tbl = Container("blog");
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
                    if ($pageName == "BlogView") {
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
		        $this->blogFoto->OldUploadPath = '../ngarkime/blog/';
		        $this->blogFoto->UploadPath = $this->blogFoto->OldUploadPath;
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
            $key .= @$ar['blogID'];
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
            $this->blogID->Visible = false;
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
        $this->blogID->Visible = false;
        $this->blogGjuha->setVisibility();
        $this->blogKategoria->setVisibility();
        $this->blogTitulli->setVisibility();
        $this->blogTxt->setVisibility();
        $this->blogFoto->setVisibility();
        $this->blogVideo->setVisibility();
        $this->blogDtPublik->setVisibility();
        $this->blogAutori->setVisibility();
        $this->blogShtuar->setVisibility();
        $this->blogModifikuar->Visible = false;
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
        $this->setupLookupOptions($this->blogGjuha);
        $this->setupLookupOptions($this->blogKategoria);
        $this->setupLookupOptions($this->blogAutori);

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
            if (($keyValue = Get("blogID") ?? Route("blogID")) !== null) {
                $this->blogID->setQueryStringValue($keyValue);
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
                    $this->terminate("BlogList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "BlogList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "BlogView") {
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
        $this->blogFoto->Upload->Index = $CurrentForm->Index;
        $this->blogFoto->Upload->uploadFile();
        $this->blogFoto->CurrentValue = $this->blogFoto->Upload->FileName;
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

        // Check field name 'blogGjuha' first before field var 'x_blogGjuha'
        $val = $CurrentForm->hasValue("blogGjuha") ? $CurrentForm->getValue("blogGjuha") : $CurrentForm->getValue("x_blogGjuha");
        if (!$this->blogGjuha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blogGjuha->Visible = false; // Disable update for API request
            } else {
                $this->blogGjuha->setFormValue($val);
            }
        }

        // Check field name 'blogKategoria' first before field var 'x_blogKategoria'
        $val = $CurrentForm->hasValue("blogKategoria") ? $CurrentForm->getValue("blogKategoria") : $CurrentForm->getValue("x_blogKategoria");
        if (!$this->blogKategoria->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blogKategoria->Visible = false; // Disable update for API request
            } else {
                $this->blogKategoria->setFormValue($val);
            }
        }

        // Check field name 'blogTitulli' first before field var 'x_blogTitulli'
        $val = $CurrentForm->hasValue("blogTitulli") ? $CurrentForm->getValue("blogTitulli") : $CurrentForm->getValue("x_blogTitulli");
        if (!$this->blogTitulli->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blogTitulli->Visible = false; // Disable update for API request
            } else {
                $this->blogTitulli->setFormValue($val);
            }
        }

        // Check field name 'blogTxt' first before field var 'x_blogTxt'
        $val = $CurrentForm->hasValue("blogTxt") ? $CurrentForm->getValue("blogTxt") : $CurrentForm->getValue("x_blogTxt");
        if (!$this->blogTxt->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blogTxt->Visible = false; // Disable update for API request
            } else {
                $this->blogTxt->setFormValue($val);
            }
        }

        // Check field name 'blogVideo' first before field var 'x_blogVideo'
        $val = $CurrentForm->hasValue("blogVideo") ? $CurrentForm->getValue("blogVideo") : $CurrentForm->getValue("x_blogVideo");
        if (!$this->blogVideo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blogVideo->Visible = false; // Disable update for API request
            } else {
                $this->blogVideo->setFormValue($val);
            }
        }

        // Check field name 'blogDtPublik' first before field var 'x_blogDtPublik'
        $val = $CurrentForm->hasValue("blogDtPublik") ? $CurrentForm->getValue("blogDtPublik") : $CurrentForm->getValue("x_blogDtPublik");
        if (!$this->blogDtPublik->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blogDtPublik->Visible = false; // Disable update for API request
            } else {
                $this->blogDtPublik->setFormValue($val, true, $validate);
            }
            $this->blogDtPublik->CurrentValue = UnFormatDateTime($this->blogDtPublik->CurrentValue, $this->blogDtPublik->formatPattern());
        }

        // Check field name 'blogAutori' first before field var 'x_blogAutori'
        $val = $CurrentForm->hasValue("blogAutori") ? $CurrentForm->getValue("blogAutori") : $CurrentForm->getValue("x_blogAutori");
        if (!$this->blogAutori->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blogAutori->Visible = false; // Disable update for API request
            } else {
                $this->blogAutori->setFormValue($val);
            }
        }

        // Check field name 'blogShtuar' first before field var 'x_blogShtuar'
        $val = $CurrentForm->hasValue("blogShtuar") ? $CurrentForm->getValue("blogShtuar") : $CurrentForm->getValue("x_blogShtuar");
        if (!$this->blogShtuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->blogShtuar->Visible = false; // Disable update for API request
            } else {
                $this->blogShtuar->setFormValue($val);
            }
            $this->blogShtuar->CurrentValue = UnFormatDateTime($this->blogShtuar->CurrentValue, $this->blogShtuar->formatPattern());
        }

        // Check field name 'blogID' first before field var 'x_blogID'
        $val = $CurrentForm->hasValue("blogID") ? $CurrentForm->getValue("blogID") : $CurrentForm->getValue("x_blogID");
		$this->blogFoto->OldUploadPath = '../ngarkime/blog/';
		$this->blogFoto->UploadPath = $this->blogFoto->OldUploadPath;
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->blogGjuha->CurrentValue = $this->blogGjuha->FormValue;
        $this->blogKategoria->CurrentValue = $this->blogKategoria->FormValue;
        $this->blogTitulli->CurrentValue = $this->blogTitulli->FormValue;
        $this->blogTxt->CurrentValue = $this->blogTxt->FormValue;
        $this->blogVideo->CurrentValue = $this->blogVideo->FormValue;
        $this->blogDtPublik->CurrentValue = $this->blogDtPublik->FormValue;
        $this->blogDtPublik->CurrentValue = UnFormatDateTime($this->blogDtPublik->CurrentValue, $this->blogDtPublik->formatPattern());
        $this->blogAutori->CurrentValue = $this->blogAutori->FormValue;
        $this->blogShtuar->CurrentValue = $this->blogShtuar->FormValue;
        $this->blogShtuar->CurrentValue = UnFormatDateTime($this->blogShtuar->CurrentValue, $this->blogShtuar->formatPattern());
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
        $this->blogID->setDbValue($row['blogID']);
        $this->blogGjuha->setDbValue($row['blogGjuha']);
        $this->blogKategoria->setDbValue($row['blogKategoria']);
        $this->blogTitulli->setDbValue($row['blogTitulli']);
        $this->blogTxt->setDbValue($row['blogTxt']);
        $this->blogFoto->Upload->DbValue = $row['blogFoto'];
        $this->blogFoto->setDbValue($this->blogFoto->Upload->DbValue);
        $this->blogVideo->setDbValue($row['blogVideo']);
        $this->blogDtPublik->setDbValue($row['blogDtPublik']);
        $this->blogAutori->setDbValue($row['blogAutori']);
        $this->blogShtuar->setDbValue($row['blogShtuar']);
        $this->blogModifikuar->setDbValue($row['blogModifikuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['blogID'] = $this->blogID->DefaultValue;
        $row['blogGjuha'] = $this->blogGjuha->DefaultValue;
        $row['blogKategoria'] = $this->blogKategoria->DefaultValue;
        $row['blogTitulli'] = $this->blogTitulli->DefaultValue;
        $row['blogTxt'] = $this->blogTxt->DefaultValue;
        $row['blogFoto'] = $this->blogFoto->DefaultValue;
        $row['blogVideo'] = $this->blogVideo->DefaultValue;
        $row['blogDtPublik'] = $this->blogDtPublik->DefaultValue;
        $row['blogAutori'] = $this->blogAutori->DefaultValue;
        $row['blogShtuar'] = $this->blogShtuar->DefaultValue;
        $row['blogModifikuar'] = $this->blogModifikuar->DefaultValue;
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

        // blogID
        $this->blogID->RowCssClass = "row";

        // blogGjuha
        $this->blogGjuha->RowCssClass = "row";

        // blogKategoria
        $this->blogKategoria->RowCssClass = "row";

        // blogTitulli
        $this->blogTitulli->RowCssClass = "row";

        // blogTxt
        $this->blogTxt->RowCssClass = "row";

        // blogFoto
        $this->blogFoto->RowCssClass = "row";

        // blogVideo
        $this->blogVideo->RowCssClass = "row";

        // blogDtPublik
        $this->blogDtPublik->RowCssClass = "row";

        // blogAutori
        $this->blogAutori->RowCssClass = "row";

        // blogShtuar
        $this->blogShtuar->RowCssClass = "row";

        // blogModifikuar
        $this->blogModifikuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // blogID
            $this->blogID->ViewValue = $this->blogID->CurrentValue;
            $this->blogID->ViewCustomAttributes = "";

            // blogGjuha
            if (strval($this->blogGjuha->CurrentValue) != "") {
                $this->blogGjuha->ViewValue = $this->blogGjuha->optionCaption($this->blogGjuha->CurrentValue);
            } else {
                $this->blogGjuha->ViewValue = null;
            }
            $this->blogGjuha->ViewCustomAttributes = "";

            // blogKategoria
            $curVal = strval($this->blogKategoria->CurrentValue);
            if ($curVal != "") {
                $this->blogKategoria->ViewValue = $this->blogKategoria->lookupCacheOption($curVal);
                if ($this->blogKategoria->ViewValue === null) { // Lookup from database
                    $filterWrk = "`blogKatID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->blogKategoria->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->blogKategoria->Lookup->renderViewRow($rswrk[0]);
                        $this->blogKategoria->ViewValue = $this->blogKategoria->displayValue($arwrk);
                    } else {
                        $this->blogKategoria->ViewValue = FormatNumber($this->blogKategoria->CurrentValue, $this->blogKategoria->formatPattern());
                    }
                }
            } else {
                $this->blogKategoria->ViewValue = null;
            }
            $this->blogKategoria->ViewCustomAttributes = "";

            // blogTitulli
            $this->blogTitulli->ViewValue = $this->blogTitulli->CurrentValue;
            $this->blogTitulli->ViewCustomAttributes = "";

            // blogTxt
            $this->blogTxt->ViewValue = $this->blogTxt->CurrentValue;
            $this->blogTxt->ViewCustomAttributes = "";

            // blogFoto
            $this->blogFoto->UploadPath = '../ngarkime/blog/';
            if (!EmptyValue($this->blogFoto->Upload->DbValue)) {
                $this->blogFoto->ImageWidth = 100;
                $this->blogFoto->ImageHeight = 0;
                $this->blogFoto->ImageAlt = $this->blogFoto->alt();
                $this->blogFoto->ImageCssClass = "ew-image";
                $this->blogFoto->ViewValue = $this->blogFoto->Upload->DbValue;
            } else {
                $this->blogFoto->ViewValue = "";
            }
            $this->blogFoto->ViewCustomAttributes = "";

            // blogVideo
            $this->blogVideo->ViewValue = $this->blogVideo->CurrentValue;
            $this->blogVideo->ViewCustomAttributes = "";

            // blogDtPublik
            $this->blogDtPublik->ViewValue = $this->blogDtPublik->CurrentValue;
            $this->blogDtPublik->ViewValue = FormatDateTime($this->blogDtPublik->ViewValue, $this->blogDtPublik->formatPattern());
            $this->blogDtPublik->ViewCustomAttributes = "";

            // blogAutori
            $this->blogAutori->ViewValue = $this->blogAutori->CurrentValue;
            $curVal = strval($this->blogAutori->CurrentValue);
            if ($curVal != "") {
                $this->blogAutori->ViewValue = $this->blogAutori->lookupCacheOption($curVal);
                if ($this->blogAutori->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->blogAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->blogAutori->Lookup->renderViewRow($rswrk[0]);
                        $this->blogAutori->ViewValue = $this->blogAutori->displayValue($arwrk);
                    } else {
                        $this->blogAutori->ViewValue = FormatNumber($this->blogAutori->CurrentValue, $this->blogAutori->formatPattern());
                    }
                }
            } else {
                $this->blogAutori->ViewValue = null;
            }
            $this->blogAutori->ViewCustomAttributes = "";

            // blogShtuar
            $this->blogShtuar->ViewValue = $this->blogShtuar->CurrentValue;
            $this->blogShtuar->ViewValue = FormatDateTime($this->blogShtuar->ViewValue, $this->blogShtuar->formatPattern());
            $this->blogShtuar->ViewCustomAttributes = "";

            // blogModifikuar
            $this->blogModifikuar->ViewValue = $this->blogModifikuar->CurrentValue;
            $this->blogModifikuar->ViewValue = FormatDateTime($this->blogModifikuar->ViewValue, $this->blogModifikuar->formatPattern());
            $this->blogModifikuar->ViewCustomAttributes = "";

            // blogGjuha
            $this->blogGjuha->LinkCustomAttributes = "";
            $this->blogGjuha->HrefValue = "";

            // blogKategoria
            $this->blogKategoria->LinkCustomAttributes = "";
            $this->blogKategoria->HrefValue = "";

            // blogTitulli
            $this->blogTitulli->LinkCustomAttributes = "";
            $this->blogTitulli->HrefValue = "";

            // blogTxt
            $this->blogTxt->LinkCustomAttributes = "";
            $this->blogTxt->HrefValue = "";

            // blogFoto
            $this->blogFoto->LinkCustomAttributes = "";
            $this->blogFoto->UploadPath = '../ngarkime/blog/';
            if (!EmptyValue($this->blogFoto->Upload->DbValue)) {
                $this->blogFoto->HrefValue = "%u"; // Add prefix/suffix
                $this->blogFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->blogFoto->HrefValue = FullUrl($this->blogFoto->HrefValue, "href");
                }
            } else {
                $this->blogFoto->HrefValue = "";
            }
            $this->blogFoto->ExportHrefValue = $this->blogFoto->UploadPath . $this->blogFoto->Upload->DbValue;

            // blogVideo
            $this->blogVideo->LinkCustomAttributes = "";
            $this->blogVideo->HrefValue = "";

            // blogDtPublik
            $this->blogDtPublik->LinkCustomAttributes = "";
            $this->blogDtPublik->HrefValue = "";

            // blogAutori
            $this->blogAutori->LinkCustomAttributes = "";
            $this->blogAutori->HrefValue = "";

            // blogShtuar
            $this->blogShtuar->LinkCustomAttributes = "";
            $this->blogShtuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // blogGjuha
            $this->blogGjuha->EditCustomAttributes = "";
            $this->blogGjuha->EditValue = $this->blogGjuha->options(false);
            $this->blogGjuha->PlaceHolder = RemoveHtml($this->blogGjuha->caption());

            // blogKategoria
            $this->blogKategoria->setupEditAttributes();
            $this->blogKategoria->EditCustomAttributes = "";
            $curVal = trim(strval($this->blogKategoria->CurrentValue));
            if ($curVal != "") {
                $this->blogKategoria->ViewValue = $this->blogKategoria->lookupCacheOption($curVal);
            } else {
                $this->blogKategoria->ViewValue = $this->blogKategoria->Lookup !== null && is_array($this->blogKategoria->lookupOptions()) ? $curVal : null;
            }
            if ($this->blogKategoria->ViewValue !== null) { // Load from cache
                $this->blogKategoria->EditValue = array_values($this->blogKategoria->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`blogKatID`" . SearchString("=", $this->blogKategoria->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->blogKategoria->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->blogKategoria->EditValue = $arwrk;
            }
            $this->blogKategoria->PlaceHolder = RemoveHtml($this->blogKategoria->caption());

            // blogTitulli
            $this->blogTitulli->setupEditAttributes();
            $this->blogTitulli->EditCustomAttributes = "";
            if (!$this->blogTitulli->Raw) {
                $this->blogTitulli->CurrentValue = HtmlDecode($this->blogTitulli->CurrentValue);
            }
            $this->blogTitulli->EditValue = HtmlEncode($this->blogTitulli->CurrentValue);
            $this->blogTitulli->PlaceHolder = RemoveHtml($this->blogTitulli->caption());

            // blogTxt
            $this->blogTxt->setupEditAttributes();
            $this->blogTxt->EditCustomAttributes = "";
            $this->blogTxt->EditValue = HtmlEncode($this->blogTxt->CurrentValue);
            $this->blogTxt->PlaceHolder = RemoveHtml($this->blogTxt->caption());

            // blogFoto
            $this->blogFoto->setupEditAttributes();
            $this->blogFoto->EditCustomAttributes = "";
            $this->blogFoto->UploadPath = '../ngarkime/blog/';
            if (!EmptyValue($this->blogFoto->Upload->DbValue)) {
                $this->blogFoto->ImageWidth = 100;
                $this->blogFoto->ImageHeight = 0;
                $this->blogFoto->ImageAlt = $this->blogFoto->alt();
                $this->blogFoto->ImageCssClass = "ew-image";
                $this->blogFoto->EditValue = $this->blogFoto->Upload->DbValue;
            } else {
                $this->blogFoto->EditValue = "";
            }
            if (!EmptyValue($this->blogFoto->CurrentValue)) {
                $this->blogFoto->Upload->FileName = $this->blogFoto->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->blogFoto);
            }

            // blogVideo
            $this->blogVideo->setupEditAttributes();
            $this->blogVideo->EditCustomAttributes = "";
            $this->blogVideo->EditValue = HtmlEncode($this->blogVideo->CurrentValue);
            $this->blogVideo->PlaceHolder = RemoveHtml($this->blogVideo->caption());

            // blogDtPublik
            $this->blogDtPublik->setupEditAttributes();
            $this->blogDtPublik->EditCustomAttributes = "";
            $this->blogDtPublik->EditValue = HtmlEncode(FormatDateTime($this->blogDtPublik->CurrentValue, $this->blogDtPublik->formatPattern()));
            $this->blogDtPublik->PlaceHolder = RemoveHtml($this->blogDtPublik->caption());

            // blogAutori

            // blogShtuar

            // Add refer script

            // blogGjuha
            $this->blogGjuha->LinkCustomAttributes = "";
            $this->blogGjuha->HrefValue = "";

            // blogKategoria
            $this->blogKategoria->LinkCustomAttributes = "";
            $this->blogKategoria->HrefValue = "";

            // blogTitulli
            $this->blogTitulli->LinkCustomAttributes = "";
            $this->blogTitulli->HrefValue = "";

            // blogTxt
            $this->blogTxt->LinkCustomAttributes = "";
            $this->blogTxt->HrefValue = "";

            // blogFoto
            $this->blogFoto->LinkCustomAttributes = "";
            $this->blogFoto->UploadPath = '../ngarkime/blog/';
            if (!EmptyValue($this->blogFoto->Upload->DbValue)) {
                $this->blogFoto->HrefValue = "%u"; // Add prefix/suffix
                $this->blogFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->blogFoto->HrefValue = FullUrl($this->blogFoto->HrefValue, "href");
                }
            } else {
                $this->blogFoto->HrefValue = "";
            }
            $this->blogFoto->ExportHrefValue = $this->blogFoto->UploadPath . $this->blogFoto->Upload->DbValue;

            // blogVideo
            $this->blogVideo->LinkCustomAttributes = "";
            $this->blogVideo->HrefValue = "";

            // blogDtPublik
            $this->blogDtPublik->LinkCustomAttributes = "";
            $this->blogDtPublik->HrefValue = "";

            // blogAutori
            $this->blogAutori->LinkCustomAttributes = "";
            $this->blogAutori->HrefValue = "";

            // blogShtuar
            $this->blogShtuar->LinkCustomAttributes = "";
            $this->blogShtuar->HrefValue = "";
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
        if ($this->blogGjuha->Required) {
            if ($this->blogGjuha->FormValue == "") {
                $this->blogGjuha->addErrorMessage(str_replace("%s", $this->blogGjuha->caption(), $this->blogGjuha->RequiredErrorMessage));
            }
        }
        if ($this->blogKategoria->Required) {
            if (!$this->blogKategoria->IsDetailKey && EmptyValue($this->blogKategoria->FormValue)) {
                $this->blogKategoria->addErrorMessage(str_replace("%s", $this->blogKategoria->caption(), $this->blogKategoria->RequiredErrorMessage));
            }
        }
        if ($this->blogTitulli->Required) {
            if (!$this->blogTitulli->IsDetailKey && EmptyValue($this->blogTitulli->FormValue)) {
                $this->blogTitulli->addErrorMessage(str_replace("%s", $this->blogTitulli->caption(), $this->blogTitulli->RequiredErrorMessage));
            }
        }
        if ($this->blogTxt->Required) {
            if (!$this->blogTxt->IsDetailKey && EmptyValue($this->blogTxt->FormValue)) {
                $this->blogTxt->addErrorMessage(str_replace("%s", $this->blogTxt->caption(), $this->blogTxt->RequiredErrorMessage));
            }
        }
        if ($this->blogFoto->Required) {
            if ($this->blogFoto->Upload->FileName == "" && !$this->blogFoto->Upload->KeepFile) {
                $this->blogFoto->addErrorMessage(str_replace("%s", $this->blogFoto->caption(), $this->blogFoto->RequiredErrorMessage));
            }
        }
        if ($this->blogVideo->Required) {
            if (!$this->blogVideo->IsDetailKey && EmptyValue($this->blogVideo->FormValue)) {
                $this->blogVideo->addErrorMessage(str_replace("%s", $this->blogVideo->caption(), $this->blogVideo->RequiredErrorMessage));
            }
        }
        if ($this->blogDtPublik->Required) {
            if (!$this->blogDtPublik->IsDetailKey && EmptyValue($this->blogDtPublik->FormValue)) {
                $this->blogDtPublik->addErrorMessage(str_replace("%s", $this->blogDtPublik->caption(), $this->blogDtPublik->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->blogDtPublik->FormValue, $this->blogDtPublik->formatPattern())) {
            $this->blogDtPublik->addErrorMessage($this->blogDtPublik->getErrorMessage(false));
        }
        if ($this->blogAutori->Required) {
            if (!$this->blogAutori->IsDetailKey && EmptyValue($this->blogAutori->FormValue)) {
                $this->blogAutori->addErrorMessage(str_replace("%s", $this->blogAutori->caption(), $this->blogAutori->RequiredErrorMessage));
            }
        }
        if ($this->blogShtuar->Required) {
            if (!$this->blogShtuar->IsDetailKey && EmptyValue($this->blogShtuar->FormValue)) {
                $this->blogShtuar->addErrorMessage(str_replace("%s", $this->blogShtuar->caption(), $this->blogShtuar->RequiredErrorMessage));
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

        // blogGjuha
        $this->blogGjuha->setDbValueDef($rsnew, $this->blogGjuha->CurrentValue, "", false);

        // blogKategoria
        $this->blogKategoria->setDbValueDef($rsnew, $this->blogKategoria->CurrentValue, 0, false);

        // blogTitulli
        $this->blogTitulli->setDbValueDef($rsnew, $this->blogTitulli->CurrentValue, "", false);

        // blogTxt
        $this->blogTxt->setDbValueDef($rsnew, $this->blogTxt->CurrentValue, "", false);

        // blogFoto
        if ($this->blogFoto->Visible && !$this->blogFoto->Upload->KeepFile) {
            $this->blogFoto->Upload->DbValue = ""; // No need to delete old file
            if ($this->blogFoto->Upload->FileName == "") {
                $rsnew['blogFoto'] = null;
            } else {
                $rsnew['blogFoto'] = $this->blogFoto->Upload->FileName;
            }
        }

        // blogVideo
        $this->blogVideo->setDbValueDef($rsnew, $this->blogVideo->CurrentValue, null, false);

        // blogDtPublik
        $this->blogDtPublik->setDbValueDef($rsnew, UnFormatDateTime($this->blogDtPublik->CurrentValue, $this->blogDtPublik->formatPattern()), CurrentDate(), false);

        // blogAutori
        $this->blogAutori->CurrentValue = CurrentUserID();
        $this->blogAutori->setDbValueDef($rsnew, $this->blogAutori->CurrentValue, 0);

        // blogShtuar
        $this->blogShtuar->CurrentValue = CurrentDateTime();
        $this->blogShtuar->setDbValueDef($rsnew, $this->blogShtuar->CurrentValue, CurrentDate());
        if ($this->blogFoto->Visible && !$this->blogFoto->Upload->KeepFile) {
            $this->blogFoto->UploadPath = '../ngarkime/blog/';
            $oldFiles = EmptyValue($this->blogFoto->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->blogFoto->htmlDecode(strval($this->blogFoto->Upload->DbValue)));
            if (!EmptyValue($this->blogFoto->Upload->FileName)) {
                $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), strval($this->blogFoto->Upload->FileName));
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->blogFoto, $this->blogFoto->Upload->Index);
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
                            $file1 = UniqueFilename($this->blogFoto->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->blogFoto->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->blogFoto->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->blogFoto->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->blogFoto->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->blogFoto->setDbValueDef($rsnew, $this->blogFoto->Upload->FileName, "", false);
            }
        }

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
            $this->blogFoto->OldUploadPath = '../ngarkime/blog/';
            $this->blogFoto->UploadPath = $this->blogFoto->OldUploadPath;
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->blogFoto->Visible && !$this->blogFoto->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->blogFoto->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->blogFoto->htmlDecode(strval($this->blogFoto->Upload->DbValue)));
                    if (!EmptyValue($this->blogFoto->Upload->FileName)) {
                        $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->blogFoto->Upload->FileName);
                        $newFiles2 = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->blogFoto->htmlDecode($rsnew['blogFoto']));
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->blogFoto, $this->blogFoto->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->blogFoto->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->blogFoto->oldPhysicalUploadPath() . $oldFile);
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
            // blogFoto
            CleanUploadTempPath($this->blogFoto, $this->blogFoto->Upload->Index);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("BlogList"), "", $this->TableVar, true);
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
                case "x_blogGjuha":
                    break;
                case "x_blogKategoria":
                    break;
                case "x_blogAutori":
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
