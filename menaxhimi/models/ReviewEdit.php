<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ReviewEdit extends Review
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'review';

    // Page object name
    public $PageObjName = "ReviewEdit";

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

        // Table object (review)
        if (!isset($GLOBALS["review"]) || get_class($GLOBALS["review"]) == PROJECT_NAMESPACE . "review") {
            $GLOBALS["review"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'review');
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
                $tbl = Container("review");
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
                    if ($pageName == "ReviewView") {
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
		        $this->reviewFoto->OldUploadPath = '../ngarkime/review/';
		        $this->reviewFoto->UploadPath = $this->reviewFoto->OldUploadPath;
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
            $key .= @$ar['reviewID'];
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
            $this->reviewID->Visible = false;
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
        $this->reviewID->setVisibility();
        $this->reviewGjuha->setVisibility();
        $this->reviewEmri->setVisibility();
        $this->reviewMakine->setVisibility();
        $this->reviewPer->setVisibility();
        $this->reviewFoto->setVisibility();
        $this->reviewTxt->setVisibility();
        $this->reviewDate->setVisibility();
        $this->reviewAktiv->setVisibility();
        $this->reviewRegjistruarNga->setVisibility();
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
        $this->setupLookupOptions($this->reviewGjuha);
        $this->setupLookupOptions($this->reviewPer);
        $this->setupLookupOptions($this->reviewAktiv);

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
            if (($keyValue = Get("reviewID") ?? Key(0) ?? Route(2)) !== null) {
                $this->reviewID->setQueryStringValue($keyValue);
                $this->reviewID->setOldValue($this->reviewID->QueryStringValue);
            } elseif (Post("reviewID") !== null) {
                $this->reviewID->setFormValue(Post("reviewID"));
                $this->reviewID->setOldValue($this->reviewID->FormValue);
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
                if (($keyValue = Get("reviewID") ?? Route("reviewID")) !== null) {
                    $this->reviewID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->reviewID->CurrentValue = null;
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
                        $this->terminate("ReviewList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "ReviewList") {
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
        $this->reviewFoto->Upload->Index = $CurrentForm->Index;
        $this->reviewFoto->Upload->uploadFile();
        $this->reviewFoto->CurrentValue = $this->reviewFoto->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'reviewID' first before field var 'x_reviewID'
        $val = $CurrentForm->hasValue("reviewID") ? $CurrentForm->getValue("reviewID") : $CurrentForm->getValue("x_reviewID");
        if (!$this->reviewID->IsDetailKey) {
            $this->reviewID->setFormValue($val);
        }

        // Check field name 'reviewGjuha' first before field var 'x_reviewGjuha'
        $val = $CurrentForm->hasValue("reviewGjuha") ? $CurrentForm->getValue("reviewGjuha") : $CurrentForm->getValue("x_reviewGjuha");
        if (!$this->reviewGjuha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reviewGjuha->Visible = false; // Disable update for API request
            } else {
                $this->reviewGjuha->setFormValue($val);
            }
        }

        // Check field name 'reviewEmri' first before field var 'x_reviewEmri'
        $val = $CurrentForm->hasValue("reviewEmri") ? $CurrentForm->getValue("reviewEmri") : $CurrentForm->getValue("x_reviewEmri");
        if (!$this->reviewEmri->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reviewEmri->Visible = false; // Disable update for API request
            } else {
                $this->reviewEmri->setFormValue($val);
            }
        }

        // Check field name 'reviewMakine' first before field var 'x_reviewMakine'
        $val = $CurrentForm->hasValue("reviewMakine") ? $CurrentForm->getValue("reviewMakine") : $CurrentForm->getValue("x_reviewMakine");
        if (!$this->reviewMakine->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reviewMakine->Visible = false; // Disable update for API request
            } else {
                $this->reviewMakine->setFormValue($val);
            }
        }

        // Check field name 'reviewPer' first before field var 'x_reviewPer'
        $val = $CurrentForm->hasValue("reviewPer") ? $CurrentForm->getValue("reviewPer") : $CurrentForm->getValue("x_reviewPer");
        if (!$this->reviewPer->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reviewPer->Visible = false; // Disable update for API request
            } else {
                $this->reviewPer->setFormValue($val);
            }
        }

        // Check field name 'reviewTxt' first before field var 'x_reviewTxt'
        $val = $CurrentForm->hasValue("reviewTxt") ? $CurrentForm->getValue("reviewTxt") : $CurrentForm->getValue("x_reviewTxt");
        if (!$this->reviewTxt->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reviewTxt->Visible = false; // Disable update for API request
            } else {
                $this->reviewTxt->setFormValue($val);
            }
        }

        // Check field name 'reviewDate' first before field var 'x_reviewDate'
        $val = $CurrentForm->hasValue("reviewDate") ? $CurrentForm->getValue("reviewDate") : $CurrentForm->getValue("x_reviewDate");
        if (!$this->reviewDate->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reviewDate->Visible = false; // Disable update for API request
            } else {
                $this->reviewDate->setFormValue($val, true, $validate);
            }
            $this->reviewDate->CurrentValue = UnFormatDateTime($this->reviewDate->CurrentValue, $this->reviewDate->formatPattern());
        }

        // Check field name 'reviewAktiv' first before field var 'x_reviewAktiv'
        $val = $CurrentForm->hasValue("reviewAktiv") ? $CurrentForm->getValue("reviewAktiv") : $CurrentForm->getValue("x_reviewAktiv");
        if (!$this->reviewAktiv->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reviewAktiv->Visible = false; // Disable update for API request
            } else {
                $this->reviewAktiv->setFormValue($val);
            }
        }

        // Check field name 'reviewRegjistruarNga' first before field var 'x_reviewRegjistruarNga'
        $val = $CurrentForm->hasValue("reviewRegjistruarNga") ? $CurrentForm->getValue("reviewRegjistruarNga") : $CurrentForm->getValue("x_reviewRegjistruarNga");
        if (!$this->reviewRegjistruarNga->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->reviewRegjistruarNga->Visible = false; // Disable update for API request
            } else {
                $this->reviewRegjistruarNga->setFormValue($val);
            }
        }
		$this->reviewFoto->OldUploadPath = '../ngarkime/review/';
		$this->reviewFoto->UploadPath = $this->reviewFoto->OldUploadPath;
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->reviewID->CurrentValue = $this->reviewID->FormValue;
        $this->reviewGjuha->CurrentValue = $this->reviewGjuha->FormValue;
        $this->reviewEmri->CurrentValue = $this->reviewEmri->FormValue;
        $this->reviewMakine->CurrentValue = $this->reviewMakine->FormValue;
        $this->reviewPer->CurrentValue = $this->reviewPer->FormValue;
        $this->reviewTxt->CurrentValue = $this->reviewTxt->FormValue;
        $this->reviewDate->CurrentValue = $this->reviewDate->FormValue;
        $this->reviewDate->CurrentValue = UnFormatDateTime($this->reviewDate->CurrentValue, $this->reviewDate->formatPattern());
        $this->reviewAktiv->CurrentValue = $this->reviewAktiv->FormValue;
        $this->reviewRegjistruarNga->CurrentValue = $this->reviewRegjistruarNga->FormValue;
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
        $this->reviewID->setDbValue($row['reviewID']);
        $this->reviewGjuha->setDbValue($row['reviewGjuha']);
        $this->reviewEmri->setDbValue($row['reviewEmri']);
        $this->reviewMakine->setDbValue($row['reviewMakine']);
        $this->reviewPer->setDbValue($row['reviewPer']);
        $this->reviewFoto->Upload->DbValue = $row['reviewFoto'];
        $this->reviewFoto->setDbValue($this->reviewFoto->Upload->DbValue);
        $this->reviewTxt->setDbValue($row['reviewTxt']);
        $this->reviewDate->setDbValue($row['reviewDate']);
        $this->reviewAktiv->setDbValue($row['reviewAktiv']);
        $this->reviewRegjistruarNga->setDbValue($row['reviewRegjistruarNga']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['reviewID'] = $this->reviewID->DefaultValue;
        $row['reviewGjuha'] = $this->reviewGjuha->DefaultValue;
        $row['reviewEmri'] = $this->reviewEmri->DefaultValue;
        $row['reviewMakine'] = $this->reviewMakine->DefaultValue;
        $row['reviewPer'] = $this->reviewPer->DefaultValue;
        $row['reviewFoto'] = $this->reviewFoto->DefaultValue;
        $row['reviewTxt'] = $this->reviewTxt->DefaultValue;
        $row['reviewDate'] = $this->reviewDate->DefaultValue;
        $row['reviewAktiv'] = $this->reviewAktiv->DefaultValue;
        $row['reviewRegjistruarNga'] = $this->reviewRegjistruarNga->DefaultValue;
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

        // reviewID
        $this->reviewID->RowCssClass = "row";

        // reviewGjuha
        $this->reviewGjuha->RowCssClass = "row";

        // reviewEmri
        $this->reviewEmri->RowCssClass = "row";

        // reviewMakine
        $this->reviewMakine->RowCssClass = "row";

        // reviewPer
        $this->reviewPer->RowCssClass = "row";

        // reviewFoto
        $this->reviewFoto->RowCssClass = "row";

        // reviewTxt
        $this->reviewTxt->RowCssClass = "row";

        // reviewDate
        $this->reviewDate->RowCssClass = "row";

        // reviewAktiv
        $this->reviewAktiv->RowCssClass = "row";

        // reviewRegjistruarNga
        $this->reviewRegjistruarNga->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // reviewID
            $this->reviewID->ViewValue = $this->reviewID->CurrentValue;
            $this->reviewID->ViewCustomAttributes = "";

            // reviewGjuha
            if (strval($this->reviewGjuha->CurrentValue) != "") {
                $this->reviewGjuha->ViewValue = $this->reviewGjuha->optionCaption($this->reviewGjuha->CurrentValue);
            } else {
                $this->reviewGjuha->ViewValue = null;
            }
            $this->reviewGjuha->ViewCustomAttributes = "";

            // reviewEmri
            $this->reviewEmri->ViewValue = $this->reviewEmri->CurrentValue;
            $this->reviewEmri->ViewCustomAttributes = "";

            // reviewMakine
            $this->reviewMakine->ViewValue = $this->reviewMakine->CurrentValue;
            $this->reviewMakine->ViewCustomAttributes = "";

            // reviewPer
            if (strval($this->reviewPer->CurrentValue) != "") {
                $this->reviewPer->ViewValue = $this->reviewPer->optionCaption($this->reviewPer->CurrentValue);
            } else {
                $this->reviewPer->ViewValue = null;
            }
            $this->reviewPer->ViewCustomAttributes = "";

            // reviewFoto
            $this->reviewFoto->UploadPath = '../ngarkime/review/';
            if (!EmptyValue($this->reviewFoto->Upload->DbValue)) {
                $this->reviewFoto->ImageWidth = 0;
                $this->reviewFoto->ImageHeight = 50;
                $this->reviewFoto->ImageAlt = $this->reviewFoto->alt();
                $this->reviewFoto->ImageCssClass = "ew-image";
                $this->reviewFoto->ViewValue = $this->reviewFoto->Upload->DbValue;
            } else {
                $this->reviewFoto->ViewValue = "";
            }
            $this->reviewFoto->ViewCustomAttributes = "";

            // reviewTxt
            $this->reviewTxt->ViewValue = $this->reviewTxt->CurrentValue;
            $this->reviewTxt->ViewCustomAttributes = "";

            // reviewDate
            $this->reviewDate->ViewValue = $this->reviewDate->CurrentValue;
            $this->reviewDate->ViewValue = FormatDateTime($this->reviewDate->ViewValue, $this->reviewDate->formatPattern());
            $this->reviewDate->ViewCustomAttributes = "";

            // reviewAktiv
            if (strval($this->reviewAktiv->CurrentValue) != "") {
                $this->reviewAktiv->ViewValue = $this->reviewAktiv->optionCaption($this->reviewAktiv->CurrentValue);
            } else {
                $this->reviewAktiv->ViewValue = null;
            }
            $this->reviewAktiv->ViewCustomAttributes = "";

            // reviewRegjistruarNga
            $this->reviewRegjistruarNga->ViewValue = $this->reviewRegjistruarNga->CurrentValue;
            $this->reviewRegjistruarNga->ViewCustomAttributes = "";

            // reviewID
            $this->reviewID->LinkCustomAttributes = "";
            $this->reviewID->HrefValue = "";

            // reviewGjuha
            $this->reviewGjuha->LinkCustomAttributes = "";
            $this->reviewGjuha->HrefValue = "";

            // reviewEmri
            $this->reviewEmri->LinkCustomAttributes = "";
            $this->reviewEmri->HrefValue = "";

            // reviewMakine
            $this->reviewMakine->LinkCustomAttributes = "";
            $this->reviewMakine->HrefValue = "";

            // reviewPer
            $this->reviewPer->LinkCustomAttributes = "";
            $this->reviewPer->HrefValue = "";

            // reviewFoto
            $this->reviewFoto->LinkCustomAttributes = "";
            $this->reviewFoto->UploadPath = '../ngarkime/review/';
            if (!EmptyValue($this->reviewFoto->Upload->DbValue)) {
                $this->reviewFoto->HrefValue = GetFileUploadUrl($this->reviewFoto, $this->reviewFoto->htmlDecode($this->reviewFoto->Upload->DbValue)); // Add prefix/suffix
                $this->reviewFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->reviewFoto->HrefValue = FullUrl($this->reviewFoto->HrefValue, "href");
                }
            } else {
                $this->reviewFoto->HrefValue = "";
            }
            $this->reviewFoto->ExportHrefValue = $this->reviewFoto->UploadPath . $this->reviewFoto->Upload->DbValue;

            // reviewTxt
            $this->reviewTxt->LinkCustomAttributes = "";
            $this->reviewTxt->HrefValue = "";

            // reviewDate
            $this->reviewDate->LinkCustomAttributes = "";
            $this->reviewDate->HrefValue = "";

            // reviewAktiv
            $this->reviewAktiv->LinkCustomAttributes = "";
            $this->reviewAktiv->HrefValue = "";

            // reviewRegjistruarNga
            $this->reviewRegjistruarNga->LinkCustomAttributes = "";
            $this->reviewRegjistruarNga->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // reviewID
            $this->reviewID->setupEditAttributes();
            $this->reviewID->EditCustomAttributes = "";
            $this->reviewID->EditValue = $this->reviewID->CurrentValue;
            $this->reviewID->ViewCustomAttributes = "";

            // reviewGjuha
            $this->reviewGjuha->EditCustomAttributes = "";
            $this->reviewGjuha->EditValue = $this->reviewGjuha->options(false);
            $this->reviewGjuha->PlaceHolder = RemoveHtml($this->reviewGjuha->caption());

            // reviewEmri
            $this->reviewEmri->setupEditAttributes();
            $this->reviewEmri->EditCustomAttributes = "";
            if (!$this->reviewEmri->Raw) {
                $this->reviewEmri->CurrentValue = HtmlDecode($this->reviewEmri->CurrentValue);
            }
            $this->reviewEmri->EditValue = HtmlEncode($this->reviewEmri->CurrentValue);
            $this->reviewEmri->PlaceHolder = RemoveHtml($this->reviewEmri->caption());

            // reviewMakine
            $this->reviewMakine->setupEditAttributes();
            $this->reviewMakine->EditCustomAttributes = "";
            if (!$this->reviewMakine->Raw) {
                $this->reviewMakine->CurrentValue = HtmlDecode($this->reviewMakine->CurrentValue);
            }
            $this->reviewMakine->EditValue = HtmlEncode($this->reviewMakine->CurrentValue);
            $this->reviewMakine->PlaceHolder = RemoveHtml($this->reviewMakine->caption());

            // reviewPer
            $this->reviewPer->EditCustomAttributes = "";
            $this->reviewPer->EditValue = $this->reviewPer->options(false);
            $this->reviewPer->PlaceHolder = RemoveHtml($this->reviewPer->caption());

            // reviewFoto
            $this->reviewFoto->setupEditAttributes();
            $this->reviewFoto->EditCustomAttributes = "";
            $this->reviewFoto->UploadPath = '../ngarkime/review/';
            if (!EmptyValue($this->reviewFoto->Upload->DbValue)) {
                $this->reviewFoto->ImageWidth = 0;
                $this->reviewFoto->ImageHeight = 50;
                $this->reviewFoto->ImageAlt = $this->reviewFoto->alt();
                $this->reviewFoto->ImageCssClass = "ew-image";
                $this->reviewFoto->EditValue = $this->reviewFoto->Upload->DbValue;
            } else {
                $this->reviewFoto->EditValue = "";
            }
            if (!EmptyValue($this->reviewFoto->CurrentValue)) {
                $this->reviewFoto->Upload->FileName = $this->reviewFoto->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->reviewFoto);
            }

            // reviewTxt
            $this->reviewTxt->setupEditAttributes();
            $this->reviewTxt->EditCustomAttributes = "";
            if (!$this->reviewTxt->Raw) {
                $this->reviewTxt->CurrentValue = HtmlDecode($this->reviewTxt->CurrentValue);
            }
            $this->reviewTxt->EditValue = HtmlEncode($this->reviewTxt->CurrentValue);
            $this->reviewTxt->PlaceHolder = RemoveHtml($this->reviewTxt->caption());

            // reviewDate
            $this->reviewDate->setupEditAttributes();
            $this->reviewDate->EditCustomAttributes = "";
            $this->reviewDate->EditValue = HtmlEncode(FormatDateTime($this->reviewDate->CurrentValue, $this->reviewDate->formatPattern()));
            $this->reviewDate->PlaceHolder = RemoveHtml($this->reviewDate->caption());

            // reviewAktiv
            $this->reviewAktiv->EditCustomAttributes = "";
            $this->reviewAktiv->EditValue = $this->reviewAktiv->options(false);
            $this->reviewAktiv->PlaceHolder = RemoveHtml($this->reviewAktiv->caption());

            // reviewRegjistruarNga

            // Edit refer script

            // reviewID
            $this->reviewID->LinkCustomAttributes = "";
            $this->reviewID->HrefValue = "";

            // reviewGjuha
            $this->reviewGjuha->LinkCustomAttributes = "";
            $this->reviewGjuha->HrefValue = "";

            // reviewEmri
            $this->reviewEmri->LinkCustomAttributes = "";
            $this->reviewEmri->HrefValue = "";

            // reviewMakine
            $this->reviewMakine->LinkCustomAttributes = "";
            $this->reviewMakine->HrefValue = "";

            // reviewPer
            $this->reviewPer->LinkCustomAttributes = "";
            $this->reviewPer->HrefValue = "";

            // reviewFoto
            $this->reviewFoto->LinkCustomAttributes = "";
            $this->reviewFoto->UploadPath = '../ngarkime/review/';
            if (!EmptyValue($this->reviewFoto->Upload->DbValue)) {
                $this->reviewFoto->HrefValue = GetFileUploadUrl($this->reviewFoto, $this->reviewFoto->htmlDecode($this->reviewFoto->Upload->DbValue)); // Add prefix/suffix
                $this->reviewFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->reviewFoto->HrefValue = FullUrl($this->reviewFoto->HrefValue, "href");
                }
            } else {
                $this->reviewFoto->HrefValue = "";
            }
            $this->reviewFoto->ExportHrefValue = $this->reviewFoto->UploadPath . $this->reviewFoto->Upload->DbValue;

            // reviewTxt
            $this->reviewTxt->LinkCustomAttributes = "";
            $this->reviewTxt->HrefValue = "";

            // reviewDate
            $this->reviewDate->LinkCustomAttributes = "";
            $this->reviewDate->HrefValue = "";

            // reviewAktiv
            $this->reviewAktiv->LinkCustomAttributes = "";
            $this->reviewAktiv->HrefValue = "";

            // reviewRegjistruarNga
            $this->reviewRegjistruarNga->LinkCustomAttributes = "";
            $this->reviewRegjistruarNga->HrefValue = "";
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
        if ($this->reviewID->Required) {
            if (!$this->reviewID->IsDetailKey && EmptyValue($this->reviewID->FormValue)) {
                $this->reviewID->addErrorMessage(str_replace("%s", $this->reviewID->caption(), $this->reviewID->RequiredErrorMessage));
            }
        }
        if ($this->reviewGjuha->Required) {
            if ($this->reviewGjuha->FormValue == "") {
                $this->reviewGjuha->addErrorMessage(str_replace("%s", $this->reviewGjuha->caption(), $this->reviewGjuha->RequiredErrorMessage));
            }
        }
        if ($this->reviewEmri->Required) {
            if (!$this->reviewEmri->IsDetailKey && EmptyValue($this->reviewEmri->FormValue)) {
                $this->reviewEmri->addErrorMessage(str_replace("%s", $this->reviewEmri->caption(), $this->reviewEmri->RequiredErrorMessage));
            }
        }
        if ($this->reviewMakine->Required) {
            if (!$this->reviewMakine->IsDetailKey && EmptyValue($this->reviewMakine->FormValue)) {
                $this->reviewMakine->addErrorMessage(str_replace("%s", $this->reviewMakine->caption(), $this->reviewMakine->RequiredErrorMessage));
            }
        }
        if ($this->reviewPer->Required) {
            if ($this->reviewPer->FormValue == "") {
                $this->reviewPer->addErrorMessage(str_replace("%s", $this->reviewPer->caption(), $this->reviewPer->RequiredErrorMessage));
            }
        }
        if ($this->reviewFoto->Required) {
            if ($this->reviewFoto->Upload->FileName == "" && !$this->reviewFoto->Upload->KeepFile) {
                $this->reviewFoto->addErrorMessage(str_replace("%s", $this->reviewFoto->caption(), $this->reviewFoto->RequiredErrorMessage));
            }
        }
        if ($this->reviewTxt->Required) {
            if (!$this->reviewTxt->IsDetailKey && EmptyValue($this->reviewTxt->FormValue)) {
                $this->reviewTxt->addErrorMessage(str_replace("%s", $this->reviewTxt->caption(), $this->reviewTxt->RequiredErrorMessage));
            }
        }
        if ($this->reviewDate->Required) {
            if (!$this->reviewDate->IsDetailKey && EmptyValue($this->reviewDate->FormValue)) {
                $this->reviewDate->addErrorMessage(str_replace("%s", $this->reviewDate->caption(), $this->reviewDate->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->reviewDate->FormValue, $this->reviewDate->formatPattern())) {
            $this->reviewDate->addErrorMessage($this->reviewDate->getErrorMessage(false));
        }
        if ($this->reviewAktiv->Required) {
            if ($this->reviewAktiv->FormValue == "") {
                $this->reviewAktiv->addErrorMessage(str_replace("%s", $this->reviewAktiv->caption(), $this->reviewAktiv->RequiredErrorMessage));
            }
        }
        if ($this->reviewRegjistruarNga->Required) {
            if (!$this->reviewRegjistruarNga->IsDetailKey && EmptyValue($this->reviewRegjistruarNga->FormValue)) {
                $this->reviewRegjistruarNga->addErrorMessage(str_replace("%s", $this->reviewRegjistruarNga->caption(), $this->reviewRegjistruarNga->RequiredErrorMessage));
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
            $this->reviewFoto->OldUploadPath = '../ngarkime/review/';
            $this->reviewFoto->UploadPath = $this->reviewFoto->OldUploadPath;
        }

        // Set new row
        $rsnew = [];

        // reviewGjuha
        $this->reviewGjuha->setDbValueDef($rsnew, $this->reviewGjuha->CurrentValue, "", $this->reviewGjuha->ReadOnly);

        // reviewEmri
        $this->reviewEmri->setDbValueDef($rsnew, $this->reviewEmri->CurrentValue, "", $this->reviewEmri->ReadOnly);

        // reviewMakine
        $this->reviewMakine->setDbValueDef($rsnew, $this->reviewMakine->CurrentValue, "", $this->reviewMakine->ReadOnly);

        // reviewPer
        $this->reviewPer->setDbValueDef($rsnew, $this->reviewPer->CurrentValue, "", $this->reviewPer->ReadOnly);

        // reviewFoto
        if ($this->reviewFoto->Visible && !$this->reviewFoto->ReadOnly && !$this->reviewFoto->Upload->KeepFile) {
            $this->reviewFoto->Upload->DbValue = $rsold['reviewFoto']; // Get original value
            if ($this->reviewFoto->Upload->FileName == "") {
                $rsnew['reviewFoto'] = null;
            } else {
                $rsnew['reviewFoto'] = $this->reviewFoto->Upload->FileName;
            }
        }

        // reviewTxt
        $this->reviewTxt->setDbValueDef($rsnew, $this->reviewTxt->CurrentValue, "", $this->reviewTxt->ReadOnly);

        // reviewDate
        $this->reviewDate->setDbValueDef($rsnew, UnFormatDateTime($this->reviewDate->CurrentValue, $this->reviewDate->formatPattern()), CurrentDate(), $this->reviewDate->ReadOnly);

        // reviewAktiv
        $this->reviewAktiv->setDbValueDef($rsnew, $this->reviewAktiv->CurrentValue, "", $this->reviewAktiv->ReadOnly);

        // reviewRegjistruarNga
        $this->reviewRegjistruarNga->CurrentValue = 'Nga:'. CurrentUserName().' <br>IP: '.CurrentUserIP().' <br> Me: '.CurrentDateTime();
        $this->reviewRegjistruarNga->setDbValueDef($rsnew, $this->reviewRegjistruarNga->CurrentValue, null);

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->reviewFoto->Visible && !$this->reviewFoto->Upload->KeepFile) {
            $this->reviewFoto->UploadPath = '../ngarkime/review/';
            $oldFiles = EmptyValue($this->reviewFoto->Upload->DbValue) ? [] : [$this->reviewFoto->htmlDecode($this->reviewFoto->Upload->DbValue)];
            if (!EmptyValue($this->reviewFoto->Upload->FileName)) {
                $newFiles = [$this->reviewFoto->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->reviewFoto, $this->reviewFoto->Upload->Index);
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
                            $file1 = UniqueFilename($this->reviewFoto->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->reviewFoto->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->reviewFoto->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->reviewFoto->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->reviewFoto->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->reviewFoto->setDbValueDef($rsnew, $this->reviewFoto->Upload->FileName, null, $this->reviewFoto->ReadOnly);
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
                if ($this->reviewFoto->Visible && !$this->reviewFoto->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->reviewFoto->Upload->DbValue) ? [] : [$this->reviewFoto->htmlDecode($this->reviewFoto->Upload->DbValue)];
                    if (!EmptyValue($this->reviewFoto->Upload->FileName)) {
                        $newFiles = [$this->reviewFoto->Upload->FileName];
                        $newFiles2 = [$this->reviewFoto->htmlDecode($rsnew['reviewFoto'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->reviewFoto, $this->reviewFoto->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->reviewFoto->Upload->SaveToFile($newFiles[$i], true, $i)) { // Just replace
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
                                @unlink($this->reviewFoto->oldPhysicalUploadPath() . $oldFile);
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
            // reviewFoto
            CleanUploadTempPath($this->reviewFoto, $this->reviewFoto->Upload->Index);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ReviewList"), "", $this->TableVar, true);
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
                case "x_reviewGjuha":
                    break;
                case "x_reviewPer":
                    break;
                case "x_reviewAktiv":
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
