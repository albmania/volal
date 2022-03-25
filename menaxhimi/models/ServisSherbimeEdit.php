<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ServisSherbimeEdit extends ServisSherbime
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'servis_sherbime';

    // Page object name
    public $PageObjName = "ServisSherbimeEdit";

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

        // Table object (servis_sherbime)
        if (!isset($GLOBALS["servis_sherbime"]) || get_class($GLOBALS["servis_sherbime"]) == PROJECT_NAMESPACE . "servis_sherbime") {
            $GLOBALS["servis_sherbime"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'servis_sherbime');
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
                $tbl = Container("servis_sherbime");
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
                    if ($pageName == "ServisSherbimeView") {
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
            $key .= @$ar['servisSherbimID'];
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
            $this->servisSherbimID->Visible = false;
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
        $this->servisSherbimID->setVisibility();
        $this->servisSherbimServisID->setVisibility();
        $this->servisSherbimSherbimi->setVisibility();
        $this->servisSherbimCmimi->setVisibility();
        $this->servisSherbimShenim->setVisibility();
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
        $this->setupLookupOptions($this->servisSherbimSherbimi);

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
            if (($keyValue = Get("servisSherbimID") ?? Key(0) ?? Route(2)) !== null) {
                $this->servisSherbimID->setQueryStringValue($keyValue);
                $this->servisSherbimID->setOldValue($this->servisSherbimID->QueryStringValue);
            } elseif (Post("servisSherbimID") !== null) {
                $this->servisSherbimID->setFormValue(Post("servisSherbimID"));
                $this->servisSherbimID->setOldValue($this->servisSherbimID->FormValue);
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
                if (($keyValue = Get("servisSherbimID") ?? Route("servisSherbimID")) !== null) {
                    $this->servisSherbimID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->servisSherbimID->CurrentValue = null;
                }
            }

            // Set up master detail parameters
            $this->setupMasterParms();

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
                        $this->terminate("ServisSherbimeList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "ServisSherbimeList") {
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

        // Check field name 'servisSherbimID' first before field var 'x_servisSherbimID'
        $val = $CurrentForm->hasValue("servisSherbimID") ? $CurrentForm->getValue("servisSherbimID") : $CurrentForm->getValue("x_servisSherbimID");
        if (!$this->servisSherbimID->IsDetailKey) {
            $this->servisSherbimID->setFormValue($val);
        }

        // Check field name 'servisSherbimServisID' first before field var 'x_servisSherbimServisID'
        $val = $CurrentForm->hasValue("servisSherbimServisID") ? $CurrentForm->getValue("servisSherbimServisID") : $CurrentForm->getValue("x_servisSherbimServisID");
        if (!$this->servisSherbimServisID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisSherbimServisID->Visible = false; // Disable update for API request
            } else {
                $this->servisSherbimServisID->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'servisSherbimSherbimi' first before field var 'x_servisSherbimSherbimi'
        $val = $CurrentForm->hasValue("servisSherbimSherbimi") ? $CurrentForm->getValue("servisSherbimSherbimi") : $CurrentForm->getValue("x_servisSherbimSherbimi");
        if (!$this->servisSherbimSherbimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisSherbimSherbimi->Visible = false; // Disable update for API request
            } else {
                $this->servisSherbimSherbimi->setFormValue($val);
            }
        }

        // Check field name 'servisSherbimCmimi' first before field var 'x_servisSherbimCmimi'
        $val = $CurrentForm->hasValue("servisSherbimCmimi") ? $CurrentForm->getValue("servisSherbimCmimi") : $CurrentForm->getValue("x_servisSherbimCmimi");
        if (!$this->servisSherbimCmimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisSherbimCmimi->Visible = false; // Disable update for API request
            } else {
                $this->servisSherbimCmimi->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'servisSherbimShenim' first before field var 'x_servisSherbimShenim'
        $val = $CurrentForm->hasValue("servisSherbimShenim") ? $CurrentForm->getValue("servisSherbimShenim") : $CurrentForm->getValue("x_servisSherbimShenim");
        if (!$this->servisSherbimShenim->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisSherbimShenim->Visible = false; // Disable update for API request
            } else {
                $this->servisSherbimShenim->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->servisSherbimID->CurrentValue = $this->servisSherbimID->FormValue;
        $this->servisSherbimServisID->CurrentValue = $this->servisSherbimServisID->FormValue;
        $this->servisSherbimSherbimi->CurrentValue = $this->servisSherbimSherbimi->FormValue;
        $this->servisSherbimCmimi->CurrentValue = $this->servisSherbimCmimi->FormValue;
        $this->servisSherbimShenim->CurrentValue = $this->servisSherbimShenim->FormValue;
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
        $this->servisSherbimID->setDbValue($row['servisSherbimID']);
        $this->servisSherbimServisID->setDbValue($row['servisSherbimServisID']);
        $this->servisSherbimSherbimi->setDbValue($row['servisSherbimSherbimi']);
        if (array_key_exists('EV__servisSherbimSherbimi', $row)) {
            $this->servisSherbimSherbimi->VirtualValue = $row['EV__servisSherbimSherbimi']; // Set up virtual field value
        } else {
            $this->servisSherbimSherbimi->VirtualValue = ""; // Clear value
        }
        $this->servisSherbimCmimi->setDbValue($row['servisSherbimCmimi']);
        $this->servisSherbimShenim->setDbValue($row['servisSherbimShenim']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['servisSherbimID'] = $this->servisSherbimID->DefaultValue;
        $row['servisSherbimServisID'] = $this->servisSherbimServisID->DefaultValue;
        $row['servisSherbimSherbimi'] = $this->servisSherbimSherbimi->DefaultValue;
        $row['servisSherbimCmimi'] = $this->servisSherbimCmimi->DefaultValue;
        $row['servisSherbimShenim'] = $this->servisSherbimShenim->DefaultValue;
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

        // servisSherbimID
        $this->servisSherbimID->RowCssClass = "row";

        // servisSherbimServisID
        $this->servisSherbimServisID->RowCssClass = "row";

        // servisSherbimSherbimi
        $this->servisSherbimSherbimi->RowCssClass = "row";

        // servisSherbimCmimi
        $this->servisSherbimCmimi->RowCssClass = "row";

        // servisSherbimShenim
        $this->servisSherbimShenim->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // servisSherbimID
            $this->servisSherbimID->ViewValue = $this->servisSherbimID->CurrentValue;
            $this->servisSherbimID->ViewCustomAttributes = "";

            // servisSherbimServisID
            $this->servisSherbimServisID->ViewValue = $this->servisSherbimServisID->CurrentValue;
            $this->servisSherbimServisID->ViewValue = FormatNumber($this->servisSherbimServisID->ViewValue, $this->servisSherbimServisID->formatPattern());
            $this->servisSherbimServisID->ViewCustomAttributes = "";

            // servisSherbimSherbimi
            if ($this->servisSherbimSherbimi->VirtualValue != "") {
                $this->servisSherbimSherbimi->ViewValue = $this->servisSherbimSherbimi->VirtualValue;
            } else {
                $curVal = strval($this->servisSherbimSherbimi->CurrentValue);
                if ($curVal != "") {
                    $this->servisSherbimSherbimi->ViewValue = $this->servisSherbimSherbimi->lookupCacheOption($curVal);
                    if ($this->servisSherbimSherbimi->ViewValue === null) { // Lookup from database
                        $filterWrk = "`sherbimeID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->servisSherbimSherbimi->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->servisSherbimSherbimi->Lookup->renderViewRow($rswrk[0]);
                            $this->servisSherbimSherbimi->ViewValue = $this->servisSherbimSherbimi->displayValue($arwrk);
                        } else {
                            $this->servisSherbimSherbimi->ViewValue = FormatNumber($this->servisSherbimSherbimi->CurrentValue, $this->servisSherbimSherbimi->formatPattern());
                        }
                    }
                } else {
                    $this->servisSherbimSherbimi->ViewValue = null;
                }
            }
            $this->servisSherbimSherbimi->ViewCustomAttributes = "";

            // servisSherbimCmimi
            $this->servisSherbimCmimi->ViewValue = $this->servisSherbimCmimi->CurrentValue;
            $this->servisSherbimCmimi->ViewValue = FormatNumber($this->servisSherbimCmimi->ViewValue, $this->servisSherbimCmimi->formatPattern());
            $this->servisSherbimCmimi->ViewCustomAttributes = "";

            // servisSherbimShenim
            $this->servisSherbimShenim->ViewValue = $this->servisSherbimShenim->CurrentValue;
            $this->servisSherbimShenim->ViewCustomAttributes = "";

            // servisSherbimID
            $this->servisSherbimID->LinkCustomAttributes = "";
            $this->servisSherbimID->HrefValue = "";

            // servisSherbimServisID
            $this->servisSherbimServisID->LinkCustomAttributes = "";
            $this->servisSherbimServisID->HrefValue = "";

            // servisSherbimSherbimi
            $this->servisSherbimSherbimi->LinkCustomAttributes = "";
            $this->servisSherbimSherbimi->HrefValue = "";

            // servisSherbimCmimi
            $this->servisSherbimCmimi->LinkCustomAttributes = "";
            $this->servisSherbimCmimi->HrefValue = "";

            // servisSherbimShenim
            $this->servisSherbimShenim->LinkCustomAttributes = "";
            $this->servisSherbimShenim->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // servisSherbimID
            $this->servisSherbimID->setupEditAttributes();
            $this->servisSherbimID->EditCustomAttributes = "";
            $this->servisSherbimID->EditValue = $this->servisSherbimID->CurrentValue;
            $this->servisSherbimID->ViewCustomAttributes = "";

            // servisSherbimServisID
            $this->servisSherbimServisID->setupEditAttributes();
            $this->servisSherbimServisID->EditCustomAttributes = "";
            if ($this->servisSherbimServisID->getSessionValue() != "") {
                $this->servisSherbimServisID->CurrentValue = GetForeignKeyValue($this->servisSherbimServisID->getSessionValue());
                $this->servisSherbimServisID->ViewValue = $this->servisSherbimServisID->CurrentValue;
                $this->servisSherbimServisID->ViewValue = FormatNumber($this->servisSherbimServisID->ViewValue, $this->servisSherbimServisID->formatPattern());
                $this->servisSherbimServisID->ViewCustomAttributes = "";
            } else {
                $this->servisSherbimServisID->EditValue = HtmlEncode($this->servisSherbimServisID->CurrentValue);
                $this->servisSherbimServisID->PlaceHolder = RemoveHtml($this->servisSherbimServisID->caption());
                if (strval($this->servisSherbimServisID->EditValue) != "" && is_numeric($this->servisSherbimServisID->EditValue)) {
                    $this->servisSherbimServisID->EditValue = FormatNumber($this->servisSherbimServisID->EditValue, null);
                }
            }

            // servisSherbimSherbimi
            $this->servisSherbimSherbimi->setupEditAttributes();
            $this->servisSherbimSherbimi->EditCustomAttributes = "";
            $curVal = trim(strval($this->servisSherbimSherbimi->CurrentValue));
            if ($curVal != "") {
                $this->servisSherbimSherbimi->ViewValue = $this->servisSherbimSherbimi->lookupCacheOption($curVal);
            } else {
                $this->servisSherbimSherbimi->ViewValue = $this->servisSherbimSherbimi->Lookup !== null && is_array($this->servisSherbimSherbimi->lookupOptions()) ? $curVal : null;
            }
            if ($this->servisSherbimSherbimi->ViewValue !== null) { // Load from cache
                $this->servisSherbimSherbimi->EditValue = array_values($this->servisSherbimSherbimi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`sherbimeID`" . SearchString("=", $this->servisSherbimSherbimi->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->servisSherbimSherbimi->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->servisSherbimSherbimi->EditValue = $arwrk;
            }
            $this->servisSherbimSherbimi->PlaceHolder = RemoveHtml($this->servisSherbimSherbimi->caption());

            // servisSherbimCmimi
            $this->servisSherbimCmimi->setupEditAttributes();
            $this->servisSherbimCmimi->EditCustomAttributes = "";
            $this->servisSherbimCmimi->EditValue = HtmlEncode($this->servisSherbimCmimi->CurrentValue);
            $this->servisSherbimCmimi->PlaceHolder = RemoveHtml($this->servisSherbimCmimi->caption());
            if (strval($this->servisSherbimCmimi->EditValue) != "" && is_numeric($this->servisSherbimCmimi->EditValue)) {
                $this->servisSherbimCmimi->EditValue = FormatNumber($this->servisSherbimCmimi->EditValue, null);
            }

            // servisSherbimShenim
            $this->servisSherbimShenim->setupEditAttributes();
            $this->servisSherbimShenim->EditCustomAttributes = "";
            if (!$this->servisSherbimShenim->Raw) {
                $this->servisSherbimShenim->CurrentValue = HtmlDecode($this->servisSherbimShenim->CurrentValue);
            }
            $this->servisSherbimShenim->EditValue = HtmlEncode($this->servisSherbimShenim->CurrentValue);
            $this->servisSherbimShenim->PlaceHolder = RemoveHtml($this->servisSherbimShenim->caption());

            // Edit refer script

            // servisSherbimID
            $this->servisSherbimID->LinkCustomAttributes = "";
            $this->servisSherbimID->HrefValue = "";

            // servisSherbimServisID
            $this->servisSherbimServisID->LinkCustomAttributes = "";
            $this->servisSherbimServisID->HrefValue = "";

            // servisSherbimSherbimi
            $this->servisSherbimSherbimi->LinkCustomAttributes = "";
            $this->servisSherbimSherbimi->HrefValue = "";

            // servisSherbimCmimi
            $this->servisSherbimCmimi->LinkCustomAttributes = "";
            $this->servisSherbimCmimi->HrefValue = "";

            // servisSherbimShenim
            $this->servisSherbimShenim->LinkCustomAttributes = "";
            $this->servisSherbimShenim->HrefValue = "";
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
        if ($this->servisSherbimID->Required) {
            if (!$this->servisSherbimID->IsDetailKey && EmptyValue($this->servisSherbimID->FormValue)) {
                $this->servisSherbimID->addErrorMessage(str_replace("%s", $this->servisSherbimID->caption(), $this->servisSherbimID->RequiredErrorMessage));
            }
        }
        if ($this->servisSherbimServisID->Required) {
            if (!$this->servisSherbimServisID->IsDetailKey && EmptyValue($this->servisSherbimServisID->FormValue)) {
                $this->servisSherbimServisID->addErrorMessage(str_replace("%s", $this->servisSherbimServisID->caption(), $this->servisSherbimServisID->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->servisSherbimServisID->FormValue)) {
            $this->servisSherbimServisID->addErrorMessage($this->servisSherbimServisID->getErrorMessage(false));
        }
        if ($this->servisSherbimSherbimi->Required) {
            if (!$this->servisSherbimSherbimi->IsDetailKey && EmptyValue($this->servisSherbimSherbimi->FormValue)) {
                $this->servisSherbimSherbimi->addErrorMessage(str_replace("%s", $this->servisSherbimSherbimi->caption(), $this->servisSherbimSherbimi->RequiredErrorMessage));
            }
        }
        if ($this->servisSherbimCmimi->Required) {
            if (!$this->servisSherbimCmimi->IsDetailKey && EmptyValue($this->servisSherbimCmimi->FormValue)) {
                $this->servisSherbimCmimi->addErrorMessage(str_replace("%s", $this->servisSherbimCmimi->caption(), $this->servisSherbimCmimi->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->servisSherbimCmimi->FormValue)) {
            $this->servisSherbimCmimi->addErrorMessage($this->servisSherbimCmimi->getErrorMessage(false));
        }
        if ($this->servisSherbimShenim->Required) {
            if (!$this->servisSherbimShenim->IsDetailKey && EmptyValue($this->servisSherbimShenim->FormValue)) {
                $this->servisSherbimShenim->addErrorMessage(str_replace("%s", $this->servisSherbimShenim->caption(), $this->servisSherbimShenim->RequiredErrorMessage));
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

        // servisSherbimServisID
        if ($this->servisSherbimServisID->getSessionValue() != "") {
            $this->servisSherbimServisID->ReadOnly = true;
        }
        $this->servisSherbimServisID->setDbValueDef($rsnew, $this->servisSherbimServisID->CurrentValue, 0, $this->servisSherbimServisID->ReadOnly);

        // servisSherbimSherbimi
        $this->servisSherbimSherbimi->setDbValueDef($rsnew, $this->servisSherbimSherbimi->CurrentValue, 0, $this->servisSherbimSherbimi->ReadOnly);

        // servisSherbimCmimi
        $this->servisSherbimCmimi->setDbValueDef($rsnew, $this->servisSherbimCmimi->CurrentValue, 0, $this->servisSherbimCmimi->ReadOnly);

        // servisSherbimShenim
        $this->servisSherbimShenim->setDbValueDef($rsnew, $this->servisSherbimShenim->CurrentValue, null, $this->servisSherbimShenim->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check referential integrity for master table 'servis'
        $detailKeys = [];
        $keyValue = $rsnew['servisSherbimServisID'] ?? $rsold['servisSherbimServisID'];
        $detailKeys['servisSherbimServisID'] = $keyValue;
        $masterTable = Container("servis");
        $masterFilter = $this->getMasterFilter($masterTable, $detailKeys);
        if (!EmptyValue($masterFilter)) {
            $rsmaster = $masterTable->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        } else { // Allow null value if not required field
            $validMasterRecord = $masterFilter === null;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "servis", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
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

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        $validMaster = false;
        // Get the keys for master table
        if (($master = Get(Config("TABLE_SHOW_MASTER"), Get(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                $validMaster = true;
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "servis") {
                $validMaster = true;
                $masterTbl = Container("servis");
                if (($parm = Get("fk_servisID", Get("servisSherbimServisID"))) !== null) {
                    $masterTbl->servisID->setQueryStringValue($parm);
                    $this->servisSherbimServisID->setQueryStringValue($masterTbl->servisID->QueryStringValue);
                    $this->servisSherbimServisID->setSessionValue($this->servisSherbimServisID->QueryStringValue);
                    if (!is_numeric($masterTbl->servisID->QueryStringValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        } elseif (($master = Post(Config("TABLE_SHOW_MASTER"), Post(Config("TABLE_MASTER")))) !== null) {
            $masterTblVar = $master;
            if ($masterTblVar == "") {
                    $validMaster = true;
                    $this->DbMasterFilter = "";
                    $this->DbDetailFilter = "";
            }
            if ($masterTblVar == "servis") {
                $validMaster = true;
                $masterTbl = Container("servis");
                if (($parm = Post("fk_servisID", Post("servisSherbimServisID"))) !== null) {
                    $masterTbl->servisID->setFormValue($parm);
                    $this->servisSherbimServisID->setFormValue($masterTbl->servisID->FormValue);
                    $this->servisSherbimServisID->setSessionValue($this->servisSherbimServisID->FormValue);
                    if (!is_numeric($masterTbl->servisID->FormValue)) {
                        $validMaster = false;
                    }
                } else {
                    $validMaster = false;
                }
            }
        }
        if ($validMaster) {
            // Save current master table
            $this->setCurrentMasterTable($masterTblVar);
            $this->setSessionWhere($this->getDetailFilterFromSession());

            // Reset start record counter (new master key)
            if (!$this->isAddOrEdit()) {
                $this->StartRecord = 1;
                $this->setStartRecordNumber($this->StartRecord);
            }

            // Clear previous master key from Session
            if ($masterTblVar != "servis") {
                if ($this->servisSherbimServisID->CurrentValue == "") {
                    $this->servisSherbimServisID->setSessionValue("");
                }
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ServisSherbimeList"), "", $this->TableVar, true);
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
                case "x_servisSherbimSherbimi":
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
