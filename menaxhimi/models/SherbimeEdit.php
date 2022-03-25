<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class SherbimeEdit extends Sherbime
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'sherbime';

    // Page object name
    public $PageObjName = "SherbimeEdit";

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

        // Table object (sherbime)
        if (!isset($GLOBALS["sherbime"]) || get_class($GLOBALS["sherbime"]) == PROJECT_NAMESPACE . "sherbime") {
            $GLOBALS["sherbime"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'sherbime');
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
                $tbl = Container("sherbime");
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
                    if ($pageName == "SherbimeView") {
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
		        $this->sherbimeFoto->OldUploadPath = '../ngarkime/sherbime/';
		        $this->sherbimeFoto->UploadPath = $this->sherbimeFoto->OldUploadPath;
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
            $key .= @$ar['sherbimeID'];
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
            $this->sherbimeID->Visible = false;
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
        $this->sherbimeID->setVisibility();
        $this->sherbimeEmertimi_sq->setVisibility();
        $this->sherbimeTxt_sq->setVisibility();
        $this->sherbimeCmimi->setVisibility();
        $this->sherbimeEmertimi_en->setVisibility();
        $this->sherbimeTxt_en->setVisibility();
        $this->sherbimeFoto->setVisibility();
        $this->sherbimeIkona->setVisibility();
        $this->sherbimeIndex->setVisibility();
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
        $this->setupLookupOptions($this->sherbimeIndex);

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
            if (($keyValue = Get("sherbimeID") ?? Key(0) ?? Route(2)) !== null) {
                $this->sherbimeID->setQueryStringValue($keyValue);
                $this->sherbimeID->setOldValue($this->sherbimeID->QueryStringValue);
            } elseif (Post("sherbimeID") !== null) {
                $this->sherbimeID->setFormValue(Post("sherbimeID"));
                $this->sherbimeID->setOldValue($this->sherbimeID->FormValue);
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
                if (($keyValue = Get("sherbimeID") ?? Route("sherbimeID")) !== null) {
                    $this->sherbimeID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->sherbimeID->CurrentValue = null;
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
                        $this->terminate("SherbimeList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "SherbimeList") {
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
        $this->sherbimeFoto->Upload->Index = $CurrentForm->Index;
        $this->sherbimeFoto->Upload->uploadFile();
        $this->sherbimeFoto->CurrentValue = $this->sherbimeFoto->Upload->FileName;
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'sherbimeID' first before field var 'x_sherbimeID'
        $val = $CurrentForm->hasValue("sherbimeID") ? $CurrentForm->getValue("sherbimeID") : $CurrentForm->getValue("x_sherbimeID");
        if (!$this->sherbimeID->IsDetailKey) {
            $this->sherbimeID->setFormValue($val);
        }

        // Check field name 'sherbimeEmertimi_sq' first before field var 'x_sherbimeEmertimi_sq'
        $val = $CurrentForm->hasValue("sherbimeEmertimi_sq") ? $CurrentForm->getValue("sherbimeEmertimi_sq") : $CurrentForm->getValue("x_sherbimeEmertimi_sq");
        if (!$this->sherbimeEmertimi_sq->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sherbimeEmertimi_sq->Visible = false; // Disable update for API request
            } else {
                $this->sherbimeEmertimi_sq->setFormValue($val);
            }
        }

        // Check field name 'sherbimeTxt_sq' first before field var 'x_sherbimeTxt_sq'
        $val = $CurrentForm->hasValue("sherbimeTxt_sq") ? $CurrentForm->getValue("sherbimeTxt_sq") : $CurrentForm->getValue("x_sherbimeTxt_sq");
        if (!$this->sherbimeTxt_sq->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sherbimeTxt_sq->Visible = false; // Disable update for API request
            } else {
                $this->sherbimeTxt_sq->setFormValue($val);
            }
        }

        // Check field name 'sherbimeCmimi' first before field var 'x_sherbimeCmimi'
        $val = $CurrentForm->hasValue("sherbimeCmimi") ? $CurrentForm->getValue("sherbimeCmimi") : $CurrentForm->getValue("x_sherbimeCmimi");
        if (!$this->sherbimeCmimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sherbimeCmimi->Visible = false; // Disable update for API request
            } else {
                $this->sherbimeCmimi->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'sherbimeEmertimi_en' first before field var 'x_sherbimeEmertimi_en'
        $val = $CurrentForm->hasValue("sherbimeEmertimi_en") ? $CurrentForm->getValue("sherbimeEmertimi_en") : $CurrentForm->getValue("x_sherbimeEmertimi_en");
        if (!$this->sherbimeEmertimi_en->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sherbimeEmertimi_en->Visible = false; // Disable update for API request
            } else {
                $this->sherbimeEmertimi_en->setFormValue($val);
            }
        }

        // Check field name 'sherbimeTxt_en' first before field var 'x_sherbimeTxt_en'
        $val = $CurrentForm->hasValue("sherbimeTxt_en") ? $CurrentForm->getValue("sherbimeTxt_en") : $CurrentForm->getValue("x_sherbimeTxt_en");
        if (!$this->sherbimeTxt_en->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sherbimeTxt_en->Visible = false; // Disable update for API request
            } else {
                $this->sherbimeTxt_en->setFormValue($val);
            }
        }

        // Check field name 'sherbimeIkona' first before field var 'x_sherbimeIkona'
        $val = $CurrentForm->hasValue("sherbimeIkona") ? $CurrentForm->getValue("sherbimeIkona") : $CurrentForm->getValue("x_sherbimeIkona");
        if (!$this->sherbimeIkona->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sherbimeIkona->Visible = false; // Disable update for API request
            } else {
                $this->sherbimeIkona->setFormValue($val);
            }
        }

        // Check field name 'sherbimeIndex' first before field var 'x_sherbimeIndex'
        $val = $CurrentForm->hasValue("sherbimeIndex") ? $CurrentForm->getValue("sherbimeIndex") : $CurrentForm->getValue("x_sherbimeIndex");
        if (!$this->sherbimeIndex->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->sherbimeIndex->Visible = false; // Disable update for API request
            } else {
                $this->sherbimeIndex->setFormValue($val);
            }
        }
		$this->sherbimeFoto->OldUploadPath = '../ngarkime/sherbime/';
		$this->sherbimeFoto->UploadPath = $this->sherbimeFoto->OldUploadPath;
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->sherbimeID->CurrentValue = $this->sherbimeID->FormValue;
        $this->sherbimeEmertimi_sq->CurrentValue = $this->sherbimeEmertimi_sq->FormValue;
        $this->sherbimeTxt_sq->CurrentValue = $this->sherbimeTxt_sq->FormValue;
        $this->sherbimeCmimi->CurrentValue = $this->sherbimeCmimi->FormValue;
        $this->sherbimeEmertimi_en->CurrentValue = $this->sherbimeEmertimi_en->FormValue;
        $this->sherbimeTxt_en->CurrentValue = $this->sherbimeTxt_en->FormValue;
        $this->sherbimeIkona->CurrentValue = $this->sherbimeIkona->FormValue;
        $this->sherbimeIndex->CurrentValue = $this->sherbimeIndex->FormValue;
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
        $this->sherbimeID->setDbValue($row['sherbimeID']);
        $this->sherbimeEmertimi_sq->setDbValue($row['sherbimeEmertimi_sq']);
        $this->sherbimeTxt_sq->setDbValue($row['sherbimeTxt_sq']);
        $this->sherbimeCmimi->setDbValue($row['sherbimeCmimi']);
        $this->sherbimeEmertimi_en->setDbValue($row['sherbimeEmertimi_en']);
        $this->sherbimeTxt_en->setDbValue($row['sherbimeTxt_en']);
        $this->sherbimeFoto->Upload->DbValue = $row['sherbimeFoto'];
        $this->sherbimeFoto->setDbValue($this->sherbimeFoto->Upload->DbValue);
        $this->sherbimeIkona->setDbValue($row['sherbimeIkona']);
        $this->sherbimeIndex->setDbValue($row['sherbimeIndex']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['sherbimeID'] = $this->sherbimeID->DefaultValue;
        $row['sherbimeEmertimi_sq'] = $this->sherbimeEmertimi_sq->DefaultValue;
        $row['sherbimeTxt_sq'] = $this->sherbimeTxt_sq->DefaultValue;
        $row['sherbimeCmimi'] = $this->sherbimeCmimi->DefaultValue;
        $row['sherbimeEmertimi_en'] = $this->sherbimeEmertimi_en->DefaultValue;
        $row['sherbimeTxt_en'] = $this->sherbimeTxt_en->DefaultValue;
        $row['sherbimeFoto'] = $this->sherbimeFoto->DefaultValue;
        $row['sherbimeIkona'] = $this->sherbimeIkona->DefaultValue;
        $row['sherbimeIndex'] = $this->sherbimeIndex->DefaultValue;
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

        // sherbimeID
        $this->sherbimeID->RowCssClass = "row";

        // sherbimeEmertimi_sq
        $this->sherbimeEmertimi_sq->RowCssClass = "row";

        // sherbimeTxt_sq
        $this->sherbimeTxt_sq->RowCssClass = "row";

        // sherbimeCmimi
        $this->sherbimeCmimi->RowCssClass = "row";

        // sherbimeEmertimi_en
        $this->sherbimeEmertimi_en->RowCssClass = "row";

        // sherbimeTxt_en
        $this->sherbimeTxt_en->RowCssClass = "row";

        // sherbimeFoto
        $this->sherbimeFoto->RowCssClass = "row";

        // sherbimeIkona
        $this->sherbimeIkona->RowCssClass = "row";

        // sherbimeIndex
        $this->sherbimeIndex->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // sherbimeID
            $this->sherbimeID->ViewValue = $this->sherbimeID->CurrentValue;
            $this->sherbimeID->ViewCustomAttributes = "";

            // sherbimeEmertimi_sq
            $this->sherbimeEmertimi_sq->ViewValue = $this->sherbimeEmertimi_sq->CurrentValue;
            $this->sherbimeEmertimi_sq->ViewCustomAttributes = "";

            // sherbimeTxt_sq
            $this->sherbimeTxt_sq->ViewValue = $this->sherbimeTxt_sq->CurrentValue;
            $this->sherbimeTxt_sq->ViewCustomAttributes = "";

            // sherbimeCmimi
            $this->sherbimeCmimi->ViewValue = $this->sherbimeCmimi->CurrentValue;
            $this->sherbimeCmimi->ViewValue = FormatNumber($this->sherbimeCmimi->ViewValue, $this->sherbimeCmimi->formatPattern());
            $this->sherbimeCmimi->ViewCustomAttributes = "";

            // sherbimeEmertimi_en
            $this->sherbimeEmertimi_en->ViewValue = $this->sherbimeEmertimi_en->CurrentValue;
            $this->sherbimeEmertimi_en->ViewCustomAttributes = "";

            // sherbimeTxt_en
            $this->sherbimeTxt_en->ViewValue = $this->sherbimeTxt_en->CurrentValue;
            $this->sherbimeTxt_en->ViewCustomAttributes = "";

            // sherbimeFoto
            $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
            if (!EmptyValue($this->sherbimeFoto->Upload->DbValue)) {
                $this->sherbimeFoto->ImageWidth = 100;
                $this->sherbimeFoto->ImageHeight = 0;
                $this->sherbimeFoto->ImageAlt = $this->sherbimeFoto->alt();
                $this->sherbimeFoto->ImageCssClass = "ew-image";
                $this->sherbimeFoto->ViewValue = $this->sherbimeFoto->Upload->DbValue;
            } else {
                $this->sherbimeFoto->ViewValue = "";
            }
            $this->sherbimeFoto->ViewCustomAttributes = "";

            // sherbimeIkona
            $this->sherbimeIkona->ViewValue = $this->sherbimeIkona->CurrentValue;
            $this->sherbimeIkona->ViewCustomAttributes = "";

            // sherbimeIndex
            if (strval($this->sherbimeIndex->CurrentValue) != "") {
                $this->sherbimeIndex->ViewValue = $this->sherbimeIndex->optionCaption($this->sherbimeIndex->CurrentValue);
            } else {
                $this->sherbimeIndex->ViewValue = null;
            }
            $this->sherbimeIndex->ViewCustomAttributes = "";

            // sherbimeID
            $this->sherbimeID->LinkCustomAttributes = "";
            $this->sherbimeID->HrefValue = "";

            // sherbimeEmertimi_sq
            $this->sherbimeEmertimi_sq->LinkCustomAttributes = "";
            $this->sherbimeEmertimi_sq->HrefValue = "";

            // sherbimeTxt_sq
            $this->sherbimeTxt_sq->LinkCustomAttributes = "";
            $this->sherbimeTxt_sq->HrefValue = "";

            // sherbimeCmimi
            $this->sherbimeCmimi->LinkCustomAttributes = "";
            $this->sherbimeCmimi->HrefValue = "";

            // sherbimeEmertimi_en
            $this->sherbimeEmertimi_en->LinkCustomAttributes = "";
            $this->sherbimeEmertimi_en->HrefValue = "";

            // sherbimeTxt_en
            $this->sherbimeTxt_en->LinkCustomAttributes = "";
            $this->sherbimeTxt_en->HrefValue = "";

            // sherbimeFoto
            $this->sherbimeFoto->LinkCustomAttributes = "";
            $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
            if (!EmptyValue($this->sherbimeFoto->Upload->DbValue)) {
                $this->sherbimeFoto->HrefValue = GetFileUploadUrl($this->sherbimeFoto, $this->sherbimeFoto->htmlDecode($this->sherbimeFoto->Upload->DbValue)); // Add prefix/suffix
                $this->sherbimeFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->sherbimeFoto->HrefValue = FullUrl($this->sherbimeFoto->HrefValue, "href");
                }
            } else {
                $this->sherbimeFoto->HrefValue = "";
            }
            $this->sherbimeFoto->ExportHrefValue = $this->sherbimeFoto->UploadPath . $this->sherbimeFoto->Upload->DbValue;

            // sherbimeIkona
            $this->sherbimeIkona->LinkCustomAttributes = "";
            $this->sherbimeIkona->HrefValue = "";

            // sherbimeIndex
            $this->sherbimeIndex->LinkCustomAttributes = "";
            $this->sherbimeIndex->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // sherbimeID
            $this->sherbimeID->setupEditAttributes();
            $this->sherbimeID->EditCustomAttributes = "";
            $this->sherbimeID->EditValue = $this->sherbimeID->CurrentValue;
            $this->sherbimeID->ViewCustomAttributes = "";

            // sherbimeEmertimi_sq
            $this->sherbimeEmertimi_sq->setupEditAttributes();
            $this->sherbimeEmertimi_sq->EditCustomAttributes = "";
            if (!$this->sherbimeEmertimi_sq->Raw) {
                $this->sherbimeEmertimi_sq->CurrentValue = HtmlDecode($this->sherbimeEmertimi_sq->CurrentValue);
            }
            $this->sherbimeEmertimi_sq->EditValue = HtmlEncode($this->sherbimeEmertimi_sq->CurrentValue);
            $this->sherbimeEmertimi_sq->PlaceHolder = RemoveHtml($this->sherbimeEmertimi_sq->caption());

            // sherbimeTxt_sq
            $this->sherbimeTxt_sq->setupEditAttributes();
            $this->sherbimeTxt_sq->EditCustomAttributes = "";
            if (!$this->sherbimeTxt_sq->Raw) {
                $this->sherbimeTxt_sq->CurrentValue = HtmlDecode($this->sherbimeTxt_sq->CurrentValue);
            }
            $this->sherbimeTxt_sq->EditValue = HtmlEncode($this->sherbimeTxt_sq->CurrentValue);
            $this->sherbimeTxt_sq->PlaceHolder = RemoveHtml($this->sherbimeTxt_sq->caption());

            // sherbimeCmimi
            $this->sherbimeCmimi->setupEditAttributes();
            $this->sherbimeCmimi->EditCustomAttributes = "";
            $this->sherbimeCmimi->EditValue = HtmlEncode($this->sherbimeCmimi->CurrentValue);
            $this->sherbimeCmimi->PlaceHolder = RemoveHtml($this->sherbimeCmimi->caption());
            if (strval($this->sherbimeCmimi->EditValue) != "" && is_numeric($this->sherbimeCmimi->EditValue)) {
                $this->sherbimeCmimi->EditValue = FormatNumber($this->sherbimeCmimi->EditValue, null);
            }

            // sherbimeEmertimi_en
            $this->sherbimeEmertimi_en->setupEditAttributes();
            $this->sherbimeEmertimi_en->EditCustomAttributes = "";
            if (!$this->sherbimeEmertimi_en->Raw) {
                $this->sherbimeEmertimi_en->CurrentValue = HtmlDecode($this->sherbimeEmertimi_en->CurrentValue);
            }
            $this->sherbimeEmertimi_en->EditValue = HtmlEncode($this->sherbimeEmertimi_en->CurrentValue);
            $this->sherbimeEmertimi_en->PlaceHolder = RemoveHtml($this->sherbimeEmertimi_en->caption());

            // sherbimeTxt_en
            $this->sherbimeTxt_en->setupEditAttributes();
            $this->sherbimeTxt_en->EditCustomAttributes = "";
            if (!$this->sherbimeTxt_en->Raw) {
                $this->sherbimeTxt_en->CurrentValue = HtmlDecode($this->sherbimeTxt_en->CurrentValue);
            }
            $this->sherbimeTxt_en->EditValue = HtmlEncode($this->sherbimeTxt_en->CurrentValue);
            $this->sherbimeTxt_en->PlaceHolder = RemoveHtml($this->sherbimeTxt_en->caption());

            // sherbimeFoto
            $this->sherbimeFoto->setupEditAttributes();
            $this->sherbimeFoto->EditCustomAttributes = "";
            $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
            if (!EmptyValue($this->sherbimeFoto->Upload->DbValue)) {
                $this->sherbimeFoto->ImageWidth = 100;
                $this->sherbimeFoto->ImageHeight = 0;
                $this->sherbimeFoto->ImageAlt = $this->sherbimeFoto->alt();
                $this->sherbimeFoto->ImageCssClass = "ew-image";
                $this->sherbimeFoto->EditValue = $this->sherbimeFoto->Upload->DbValue;
            } else {
                $this->sherbimeFoto->EditValue = "";
            }
            if (!EmptyValue($this->sherbimeFoto->CurrentValue)) {
                $this->sherbimeFoto->Upload->FileName = $this->sherbimeFoto->CurrentValue;
            }
            if ($this->isShow()) {
                RenderUploadField($this->sherbimeFoto);
            }

            // sherbimeIkona
            $this->sherbimeIkona->setupEditAttributes();
            $this->sherbimeIkona->EditCustomAttributes = "";
            if (!$this->sherbimeIkona->Raw) {
                $this->sherbimeIkona->CurrentValue = HtmlDecode($this->sherbimeIkona->CurrentValue);
            }
            $this->sherbimeIkona->EditValue = HtmlEncode($this->sherbimeIkona->CurrentValue);
            $this->sherbimeIkona->PlaceHolder = RemoveHtml($this->sherbimeIkona->caption());

            // sherbimeIndex
            $this->sherbimeIndex->EditCustomAttributes = "";
            $this->sherbimeIndex->EditValue = $this->sherbimeIndex->options(false);
            $this->sherbimeIndex->PlaceHolder = RemoveHtml($this->sherbimeIndex->caption());

            // Edit refer script

            // sherbimeID
            $this->sherbimeID->LinkCustomAttributes = "";
            $this->sherbimeID->HrefValue = "";

            // sherbimeEmertimi_sq
            $this->sherbimeEmertimi_sq->LinkCustomAttributes = "";
            $this->sherbimeEmertimi_sq->HrefValue = "";

            // sherbimeTxt_sq
            $this->sherbimeTxt_sq->LinkCustomAttributes = "";
            $this->sherbimeTxt_sq->HrefValue = "";

            // sherbimeCmimi
            $this->sherbimeCmimi->LinkCustomAttributes = "";
            $this->sherbimeCmimi->HrefValue = "";

            // sherbimeEmertimi_en
            $this->sherbimeEmertimi_en->LinkCustomAttributes = "";
            $this->sherbimeEmertimi_en->HrefValue = "";

            // sherbimeTxt_en
            $this->sherbimeTxt_en->LinkCustomAttributes = "";
            $this->sherbimeTxt_en->HrefValue = "";

            // sherbimeFoto
            $this->sherbimeFoto->LinkCustomAttributes = "";
            $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
            if (!EmptyValue($this->sherbimeFoto->Upload->DbValue)) {
                $this->sherbimeFoto->HrefValue = GetFileUploadUrl($this->sherbimeFoto, $this->sherbimeFoto->htmlDecode($this->sherbimeFoto->Upload->DbValue)); // Add prefix/suffix
                $this->sherbimeFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->sherbimeFoto->HrefValue = FullUrl($this->sherbimeFoto->HrefValue, "href");
                }
            } else {
                $this->sherbimeFoto->HrefValue = "";
            }
            $this->sherbimeFoto->ExportHrefValue = $this->sherbimeFoto->UploadPath . $this->sherbimeFoto->Upload->DbValue;

            // sherbimeIkona
            $this->sherbimeIkona->LinkCustomAttributes = "";
            $this->sherbimeIkona->HrefValue = "";

            // sherbimeIndex
            $this->sherbimeIndex->LinkCustomAttributes = "";
            $this->sherbimeIndex->HrefValue = "";
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
        if ($this->sherbimeID->Required) {
            if (!$this->sherbimeID->IsDetailKey && EmptyValue($this->sherbimeID->FormValue)) {
                $this->sherbimeID->addErrorMessage(str_replace("%s", $this->sherbimeID->caption(), $this->sherbimeID->RequiredErrorMessage));
            }
        }
        if ($this->sherbimeEmertimi_sq->Required) {
            if (!$this->sherbimeEmertimi_sq->IsDetailKey && EmptyValue($this->sherbimeEmertimi_sq->FormValue)) {
                $this->sherbimeEmertimi_sq->addErrorMessage(str_replace("%s", $this->sherbimeEmertimi_sq->caption(), $this->sherbimeEmertimi_sq->RequiredErrorMessage));
            }
        }
        if ($this->sherbimeTxt_sq->Required) {
            if (!$this->sherbimeTxt_sq->IsDetailKey && EmptyValue($this->sherbimeTxt_sq->FormValue)) {
                $this->sherbimeTxt_sq->addErrorMessage(str_replace("%s", $this->sherbimeTxt_sq->caption(), $this->sherbimeTxt_sq->RequiredErrorMessage));
            }
        }
        if ($this->sherbimeCmimi->Required) {
            if (!$this->sherbimeCmimi->IsDetailKey && EmptyValue($this->sherbimeCmimi->FormValue)) {
                $this->sherbimeCmimi->addErrorMessage(str_replace("%s", $this->sherbimeCmimi->caption(), $this->sherbimeCmimi->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->sherbimeCmimi->FormValue)) {
            $this->sherbimeCmimi->addErrorMessage($this->sherbimeCmimi->getErrorMessage(false));
        }
        if ($this->sherbimeEmertimi_en->Required) {
            if (!$this->sherbimeEmertimi_en->IsDetailKey && EmptyValue($this->sherbimeEmertimi_en->FormValue)) {
                $this->sherbimeEmertimi_en->addErrorMessage(str_replace("%s", $this->sherbimeEmertimi_en->caption(), $this->sherbimeEmertimi_en->RequiredErrorMessage));
            }
        }
        if ($this->sherbimeTxt_en->Required) {
            if (!$this->sherbimeTxt_en->IsDetailKey && EmptyValue($this->sherbimeTxt_en->FormValue)) {
                $this->sherbimeTxt_en->addErrorMessage(str_replace("%s", $this->sherbimeTxt_en->caption(), $this->sherbimeTxt_en->RequiredErrorMessage));
            }
        }
        if ($this->sherbimeFoto->Required) {
            if ($this->sherbimeFoto->Upload->FileName == "" && !$this->sherbimeFoto->Upload->KeepFile) {
                $this->sherbimeFoto->addErrorMessage(str_replace("%s", $this->sherbimeFoto->caption(), $this->sherbimeFoto->RequiredErrorMessage));
            }
        }
        if ($this->sherbimeIkona->Required) {
            if (!$this->sherbimeIkona->IsDetailKey && EmptyValue($this->sherbimeIkona->FormValue)) {
                $this->sherbimeIkona->addErrorMessage(str_replace("%s", $this->sherbimeIkona->caption(), $this->sherbimeIkona->RequiredErrorMessage));
            }
        }
        if ($this->sherbimeIndex->Required) {
            if ($this->sherbimeIndex->FormValue == "") {
                $this->sherbimeIndex->addErrorMessage(str_replace("%s", $this->sherbimeIndex->caption(), $this->sherbimeIndex->RequiredErrorMessage));
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
            $this->sherbimeFoto->OldUploadPath = '../ngarkime/sherbime/';
            $this->sherbimeFoto->UploadPath = $this->sherbimeFoto->OldUploadPath;
        }

        // Set new row
        $rsnew = [];

        // sherbimeEmertimi_sq
        $this->sherbimeEmertimi_sq->setDbValueDef($rsnew, $this->sherbimeEmertimi_sq->CurrentValue, "", $this->sherbimeEmertimi_sq->ReadOnly);

        // sherbimeTxt_sq
        $this->sherbimeTxt_sq->setDbValueDef($rsnew, $this->sherbimeTxt_sq->CurrentValue, "", $this->sherbimeTxt_sq->ReadOnly);

        // sherbimeCmimi
        $this->sherbimeCmimi->setDbValueDef($rsnew, $this->sherbimeCmimi->CurrentValue, 0, $this->sherbimeCmimi->ReadOnly);

        // sherbimeEmertimi_en
        $this->sherbimeEmertimi_en->setDbValueDef($rsnew, $this->sherbimeEmertimi_en->CurrentValue, null, $this->sherbimeEmertimi_en->ReadOnly);

        // sherbimeTxt_en
        $this->sherbimeTxt_en->setDbValueDef($rsnew, $this->sherbimeTxt_en->CurrentValue, null, $this->sherbimeTxt_en->ReadOnly);

        // sherbimeFoto
        if ($this->sherbimeFoto->Visible && !$this->sherbimeFoto->ReadOnly && !$this->sherbimeFoto->Upload->KeepFile) {
            $this->sherbimeFoto->Upload->DbValue = $rsold['sherbimeFoto']; // Get original value
            if ($this->sherbimeFoto->Upload->FileName == "") {
                $rsnew['sherbimeFoto'] = null;
            } else {
                $rsnew['sherbimeFoto'] = $this->sherbimeFoto->Upload->FileName;
            }
            $this->sherbimeFoto->ImageWidth = 350; // Resize width
            $this->sherbimeFoto->ImageHeight = 235; // Resize height
        }

        // sherbimeIkona
        $this->sherbimeIkona->setDbValueDef($rsnew, $this->sherbimeIkona->CurrentValue, "", $this->sherbimeIkona->ReadOnly);

        // sherbimeIndex
        $this->sherbimeIndex->setDbValueDef($rsnew, $this->sherbimeIndex->CurrentValue, "", $this->sherbimeIndex->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);
        if ($this->sherbimeFoto->Visible && !$this->sherbimeFoto->Upload->KeepFile) {
            $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
            $oldFiles = EmptyValue($this->sherbimeFoto->Upload->DbValue) ? [] : [$this->sherbimeFoto->htmlDecode($this->sherbimeFoto->Upload->DbValue)];
            if (!EmptyValue($this->sherbimeFoto->Upload->FileName)) {
                $newFiles = [$this->sherbimeFoto->Upload->FileName];
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->sherbimeFoto, $this->sherbimeFoto->Upload->Index);
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
                            $file1 = UniqueFilename($this->sherbimeFoto->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->sherbimeFoto->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->sherbimeFoto->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->sherbimeFoto->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->sherbimeFoto->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->sherbimeFoto->setDbValueDef($rsnew, $this->sherbimeFoto->Upload->FileName, "", $this->sherbimeFoto->ReadOnly);
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
                if ($this->sherbimeFoto->Visible && !$this->sherbimeFoto->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->sherbimeFoto->Upload->DbValue) ? [] : [$this->sherbimeFoto->htmlDecode($this->sherbimeFoto->Upload->DbValue)];
                    if (!EmptyValue($this->sherbimeFoto->Upload->FileName)) {
                        $newFiles = [$this->sherbimeFoto->Upload->FileName];
                        $newFiles2 = [$this->sherbimeFoto->htmlDecode($rsnew['sherbimeFoto'])];
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->sherbimeFoto, $this->sherbimeFoto->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->sherbimeFoto->Upload->ResizeAndSaveToFile($this->sherbimeFoto->ImageWidth, $this->sherbimeFoto->ImageHeight, 100, $newFiles[$i], true, $i)) {
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
                                @unlink($this->sherbimeFoto->oldPhysicalUploadPath() . $oldFile);
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
            // sherbimeFoto
            CleanUploadTempPath($this->sherbimeFoto, $this->sherbimeFoto->Upload->Index);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("SherbimeList"), "", $this->TableVar, true);
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
                case "x_sherbimeIndex":
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
