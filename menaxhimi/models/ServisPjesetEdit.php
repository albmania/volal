<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ServisPjesetEdit extends ServisPjeset
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'servis_pjeset';

    // Page object name
    public $PageObjName = "ServisPjesetEdit";

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

        // Table object (servis_pjeset)
        if (!isset($GLOBALS["servis_pjeset"]) || get_class($GLOBALS["servis_pjeset"]) == PROJECT_NAMESPACE . "servis_pjeset") {
            $GLOBALS["servis_pjeset"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'servis_pjeset');
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
                $tbl = Container("servis_pjeset");
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
                    if ($pageName == "ServisPjesetView") {
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
            $key .= @$ar['servisPjeseID'];
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
            $this->servisPjeseID->Visible = false;
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
        $this->servisPjeseID->setVisibility();
        $this->servisPjeseServisID->setVisibility();
        $this->servisPjesePjesa->setVisibility();
        $this->servisPjeseSasia->setVisibility();
        $this->servisPjeseCmimi->setVisibility();
        $this->servisPjeseShenim->setVisibility();
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
        $this->setupLookupOptions($this->servisPjesePjesa);

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
            if (($keyValue = Get("servisPjeseID") ?? Key(0) ?? Route(2)) !== null) {
                $this->servisPjeseID->setQueryStringValue($keyValue);
                $this->servisPjeseID->setOldValue($this->servisPjeseID->QueryStringValue);
            } elseif (Post("servisPjeseID") !== null) {
                $this->servisPjeseID->setFormValue(Post("servisPjeseID"));
                $this->servisPjeseID->setOldValue($this->servisPjeseID->FormValue);
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
                if (($keyValue = Get("servisPjeseID") ?? Route("servisPjeseID")) !== null) {
                    $this->servisPjeseID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->servisPjeseID->CurrentValue = null;
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
                        $this->terminate("ServisPjesetList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "ServisPjesetList") {
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

        // Check field name 'servisPjeseID' first before field var 'x_servisPjeseID'
        $val = $CurrentForm->hasValue("servisPjeseID") ? $CurrentForm->getValue("servisPjeseID") : $CurrentForm->getValue("x_servisPjeseID");
        if (!$this->servisPjeseID->IsDetailKey) {
            $this->servisPjeseID->setFormValue($val);
        }

        // Check field name 'servisPjeseServisID' first before field var 'x_servisPjeseServisID'
        $val = $CurrentForm->hasValue("servisPjeseServisID") ? $CurrentForm->getValue("servisPjeseServisID") : $CurrentForm->getValue("x_servisPjeseServisID");
        if (!$this->servisPjeseServisID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjeseServisID->Visible = false; // Disable update for API request
            } else {
                $this->servisPjeseServisID->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'servisPjesePjesa' first before field var 'x_servisPjesePjesa'
        $val = $CurrentForm->hasValue("servisPjesePjesa") ? $CurrentForm->getValue("servisPjesePjesa") : $CurrentForm->getValue("x_servisPjesePjesa");
        if (!$this->servisPjesePjesa->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjesePjesa->Visible = false; // Disable update for API request
            } else {
                $this->servisPjesePjesa->setFormValue($val);
            }
        }

        // Check field name 'servisPjeseSasia' first before field var 'x_servisPjeseSasia'
        $val = $CurrentForm->hasValue("servisPjeseSasia") ? $CurrentForm->getValue("servisPjeseSasia") : $CurrentForm->getValue("x_servisPjeseSasia");
        if (!$this->servisPjeseSasia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjeseSasia->Visible = false; // Disable update for API request
            } else {
                $this->servisPjeseSasia->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'servisPjeseCmimi' first before field var 'x_servisPjeseCmimi'
        $val = $CurrentForm->hasValue("servisPjeseCmimi") ? $CurrentForm->getValue("servisPjeseCmimi") : $CurrentForm->getValue("x_servisPjeseCmimi");
        if (!$this->servisPjeseCmimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjeseCmimi->Visible = false; // Disable update for API request
            } else {
                $this->servisPjeseCmimi->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'servisPjeseShenim' first before field var 'x_servisPjeseShenim'
        $val = $CurrentForm->hasValue("servisPjeseShenim") ? $CurrentForm->getValue("servisPjeseShenim") : $CurrentForm->getValue("x_servisPjeseShenim");
        if (!$this->servisPjeseShenim->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjeseShenim->Visible = false; // Disable update for API request
            } else {
                $this->servisPjeseShenim->setFormValue($val);
            }
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->servisPjeseID->CurrentValue = $this->servisPjeseID->FormValue;
        $this->servisPjeseServisID->CurrentValue = $this->servisPjeseServisID->FormValue;
        $this->servisPjesePjesa->CurrentValue = $this->servisPjesePjesa->FormValue;
        $this->servisPjeseSasia->CurrentValue = $this->servisPjeseSasia->FormValue;
        $this->servisPjeseCmimi->CurrentValue = $this->servisPjeseCmimi->FormValue;
        $this->servisPjeseShenim->CurrentValue = $this->servisPjeseShenim->FormValue;
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
        $this->servisPjeseID->setDbValue($row['servisPjeseID']);
        $this->servisPjeseServisID->setDbValue($row['servisPjeseServisID']);
        $this->servisPjesePjesa->setDbValue($row['servisPjesePjesa']);
        if (array_key_exists('EV__servisPjesePjesa', $row)) {
            $this->servisPjesePjesa->VirtualValue = $row['EV__servisPjesePjesa']; // Set up virtual field value
        } else {
            $this->servisPjesePjesa->VirtualValue = ""; // Clear value
        }
        $this->servisPjeseSasia->setDbValue($row['servisPjeseSasia']);
        $this->servisPjeseCmimi->setDbValue($row['servisPjeseCmimi']);
        $this->servisPjeseShenim->setDbValue($row['servisPjeseShenim']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['servisPjeseID'] = $this->servisPjeseID->DefaultValue;
        $row['servisPjeseServisID'] = $this->servisPjeseServisID->DefaultValue;
        $row['servisPjesePjesa'] = $this->servisPjesePjesa->DefaultValue;
        $row['servisPjeseSasia'] = $this->servisPjeseSasia->DefaultValue;
        $row['servisPjeseCmimi'] = $this->servisPjeseCmimi->DefaultValue;
        $row['servisPjeseShenim'] = $this->servisPjeseShenim->DefaultValue;
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

        // servisPjeseID
        $this->servisPjeseID->RowCssClass = "row";

        // servisPjeseServisID
        $this->servisPjeseServisID->RowCssClass = "row";

        // servisPjesePjesa
        $this->servisPjesePjesa->RowCssClass = "row";

        // servisPjeseSasia
        $this->servisPjeseSasia->RowCssClass = "row";

        // servisPjeseCmimi
        $this->servisPjeseCmimi->RowCssClass = "row";

        // servisPjeseShenim
        $this->servisPjeseShenim->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // servisPjeseID
            $this->servisPjeseID->ViewValue = $this->servisPjeseID->CurrentValue;
            $this->servisPjeseID->ViewCustomAttributes = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->ViewValue = $this->servisPjeseServisID->CurrentValue;
            $this->servisPjeseServisID->ViewValue = FormatNumber($this->servisPjeseServisID->ViewValue, $this->servisPjeseServisID->formatPattern());
            $this->servisPjeseServisID->ViewCustomAttributes = "";

            // servisPjesePjesa
            if ($this->servisPjesePjesa->VirtualValue != "") {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->VirtualValue;
            } else {
                $curVal = strval($this->servisPjesePjesa->CurrentValue);
                if ($curVal != "") {
                    $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->lookupCacheOption($curVal);
                    if ($this->servisPjesePjesa->ViewValue === null) { // Lookup from database
                        $filterWrk = "`pjeseID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->servisPjesePjesa->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->servisPjesePjesa->Lookup->renderViewRow($rswrk[0]);
                            $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->displayValue($arwrk);
                        } else {
                            $this->servisPjesePjesa->ViewValue = FormatNumber($this->servisPjesePjesa->CurrentValue, $this->servisPjesePjesa->formatPattern());
                        }
                    }
                } else {
                    $this->servisPjesePjesa->ViewValue = null;
                }
            }
            $this->servisPjesePjesa->ViewCustomAttributes = "";

            // servisPjeseSasia
            $this->servisPjeseSasia->ViewValue = $this->servisPjeseSasia->CurrentValue;
            $this->servisPjeseSasia->ViewValue = FormatNumber($this->servisPjeseSasia->ViewValue, $this->servisPjeseSasia->formatPattern());
            $this->servisPjeseSasia->ViewCustomAttributes = "";

            // servisPjeseCmimi
            $this->servisPjeseCmimi->ViewValue = $this->servisPjeseCmimi->CurrentValue;
            $this->servisPjeseCmimi->ViewValue = FormatNumber($this->servisPjeseCmimi->ViewValue, $this->servisPjeseCmimi->formatPattern());
            $this->servisPjeseCmimi->ViewCustomAttributes = "";

            // servisPjeseShenim
            $this->servisPjeseShenim->ViewValue = $this->servisPjeseShenim->CurrentValue;
            $this->servisPjeseShenim->ViewCustomAttributes = "";

            // servisPjeseID
            $this->servisPjeseID->LinkCustomAttributes = "";
            $this->servisPjeseID->HrefValue = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->LinkCustomAttributes = "";
            $this->servisPjeseServisID->HrefValue = "";

            // servisPjesePjesa
            $this->servisPjesePjesa->LinkCustomAttributes = "";
            $this->servisPjesePjesa->HrefValue = "";

            // servisPjeseSasia
            $this->servisPjeseSasia->LinkCustomAttributes = "";
            $this->servisPjeseSasia->HrefValue = "";

            // servisPjeseCmimi
            $this->servisPjeseCmimi->LinkCustomAttributes = "";
            $this->servisPjeseCmimi->HrefValue = "";

            // servisPjeseShenim
            $this->servisPjeseShenim->LinkCustomAttributes = "";
            $this->servisPjeseShenim->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // servisPjeseID
            $this->servisPjeseID->setupEditAttributes();
            $this->servisPjeseID->EditCustomAttributes = "";
            $this->servisPjeseID->EditValue = $this->servisPjeseID->CurrentValue;
            $this->servisPjeseID->ViewCustomAttributes = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->setupEditAttributes();
            $this->servisPjeseServisID->EditCustomAttributes = "";
            if ($this->servisPjeseServisID->getSessionValue() != "") {
                $this->servisPjeseServisID->CurrentValue = GetForeignKeyValue($this->servisPjeseServisID->getSessionValue());
                $this->servisPjeseServisID->ViewValue = $this->servisPjeseServisID->CurrentValue;
                $this->servisPjeseServisID->ViewValue = FormatNumber($this->servisPjeseServisID->ViewValue, $this->servisPjeseServisID->formatPattern());
                $this->servisPjeseServisID->ViewCustomAttributes = "";
            } else {
                $this->servisPjeseServisID->EditValue = HtmlEncode($this->servisPjeseServisID->CurrentValue);
                $this->servisPjeseServisID->PlaceHolder = RemoveHtml($this->servisPjeseServisID->caption());
                if (strval($this->servisPjeseServisID->EditValue) != "" && is_numeric($this->servisPjeseServisID->EditValue)) {
                    $this->servisPjeseServisID->EditValue = FormatNumber($this->servisPjeseServisID->EditValue, null);
                }
            }

            // servisPjesePjesa
            $this->servisPjesePjesa->setupEditAttributes();
            $this->servisPjesePjesa->EditCustomAttributes = "";
            $curVal = trim(strval($this->servisPjesePjesa->CurrentValue));
            if ($curVal != "") {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->lookupCacheOption($curVal);
            } else {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->Lookup !== null && is_array($this->servisPjesePjesa->lookupOptions()) ? $curVal : null;
            }
            if ($this->servisPjesePjesa->ViewValue !== null) { // Load from cache
                $this->servisPjesePjesa->EditValue = array_values($this->servisPjesePjesa->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`pjeseID`" . SearchString("=", $this->servisPjesePjesa->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->servisPjesePjesa->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->servisPjesePjesa->EditValue = $arwrk;
            }
            $this->servisPjesePjesa->PlaceHolder = RemoveHtml($this->servisPjesePjesa->caption());

            // servisPjeseSasia
            $this->servisPjeseSasia->setupEditAttributes();
            $this->servisPjeseSasia->EditCustomAttributes = "";
            $this->servisPjeseSasia->EditValue = HtmlEncode($this->servisPjeseSasia->CurrentValue);
            $this->servisPjeseSasia->PlaceHolder = RemoveHtml($this->servisPjeseSasia->caption());
            if (strval($this->servisPjeseSasia->EditValue) != "" && is_numeric($this->servisPjeseSasia->EditValue)) {
                $this->servisPjeseSasia->EditValue = FormatNumber($this->servisPjeseSasia->EditValue, null);
            }

            // servisPjeseCmimi
            $this->servisPjeseCmimi->setupEditAttributes();
            $this->servisPjeseCmimi->EditCustomAttributes = "";
            $this->servisPjeseCmimi->EditValue = HtmlEncode($this->servisPjeseCmimi->CurrentValue);
            $this->servisPjeseCmimi->PlaceHolder = RemoveHtml($this->servisPjeseCmimi->caption());
            if (strval($this->servisPjeseCmimi->EditValue) != "" && is_numeric($this->servisPjeseCmimi->EditValue)) {
                $this->servisPjeseCmimi->EditValue = FormatNumber($this->servisPjeseCmimi->EditValue, null);
            }

            // servisPjeseShenim
            $this->servisPjeseShenim->setupEditAttributes();
            $this->servisPjeseShenim->EditCustomAttributes = "";
            if (!$this->servisPjeseShenim->Raw) {
                $this->servisPjeseShenim->CurrentValue = HtmlDecode($this->servisPjeseShenim->CurrentValue);
            }
            $this->servisPjeseShenim->EditValue = HtmlEncode($this->servisPjeseShenim->CurrentValue);
            $this->servisPjeseShenim->PlaceHolder = RemoveHtml($this->servisPjeseShenim->caption());

            // Edit refer script

            // servisPjeseID
            $this->servisPjeseID->LinkCustomAttributes = "";
            $this->servisPjeseID->HrefValue = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->LinkCustomAttributes = "";
            $this->servisPjeseServisID->HrefValue = "";

            // servisPjesePjesa
            $this->servisPjesePjesa->LinkCustomAttributes = "";
            $this->servisPjesePjesa->HrefValue = "";

            // servisPjeseSasia
            $this->servisPjeseSasia->LinkCustomAttributes = "";
            $this->servisPjeseSasia->HrefValue = "";

            // servisPjeseCmimi
            $this->servisPjeseCmimi->LinkCustomAttributes = "";
            $this->servisPjeseCmimi->HrefValue = "";

            // servisPjeseShenim
            $this->servisPjeseShenim->LinkCustomAttributes = "";
            $this->servisPjeseShenim->HrefValue = "";
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
        if ($this->servisPjeseID->Required) {
            if (!$this->servisPjeseID->IsDetailKey && EmptyValue($this->servisPjeseID->FormValue)) {
                $this->servisPjeseID->addErrorMessage(str_replace("%s", $this->servisPjeseID->caption(), $this->servisPjeseID->RequiredErrorMessage));
            }
        }
        if ($this->servisPjeseServisID->Required) {
            if (!$this->servisPjeseServisID->IsDetailKey && EmptyValue($this->servisPjeseServisID->FormValue)) {
                $this->servisPjeseServisID->addErrorMessage(str_replace("%s", $this->servisPjeseServisID->caption(), $this->servisPjeseServisID->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->servisPjeseServisID->FormValue)) {
            $this->servisPjeseServisID->addErrorMessage($this->servisPjeseServisID->getErrorMessage(false));
        }
        if ($this->servisPjesePjesa->Required) {
            if (!$this->servisPjesePjesa->IsDetailKey && EmptyValue($this->servisPjesePjesa->FormValue)) {
                $this->servisPjesePjesa->addErrorMessage(str_replace("%s", $this->servisPjesePjesa->caption(), $this->servisPjesePjesa->RequiredErrorMessage));
            }
        }
        if ($this->servisPjeseSasia->Required) {
            if (!$this->servisPjeseSasia->IsDetailKey && EmptyValue($this->servisPjeseSasia->FormValue)) {
                $this->servisPjeseSasia->addErrorMessage(str_replace("%s", $this->servisPjeseSasia->caption(), $this->servisPjeseSasia->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->servisPjeseSasia->FormValue)) {
            $this->servisPjeseSasia->addErrorMessage($this->servisPjeseSasia->getErrorMessage(false));
        }
        if ($this->servisPjeseCmimi->Required) {
            if (!$this->servisPjeseCmimi->IsDetailKey && EmptyValue($this->servisPjeseCmimi->FormValue)) {
                $this->servisPjeseCmimi->addErrorMessage(str_replace("%s", $this->servisPjeseCmimi->caption(), $this->servisPjeseCmimi->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->servisPjeseCmimi->FormValue)) {
            $this->servisPjeseCmimi->addErrorMessage($this->servisPjeseCmimi->getErrorMessage(false));
        }
        if ($this->servisPjeseShenim->Required) {
            if (!$this->servisPjeseShenim->IsDetailKey && EmptyValue($this->servisPjeseShenim->FormValue)) {
                $this->servisPjeseShenim->addErrorMessage(str_replace("%s", $this->servisPjeseShenim->caption(), $this->servisPjeseShenim->RequiredErrorMessage));
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

        // servisPjeseServisID
        if ($this->servisPjeseServisID->getSessionValue() != "") {
            $this->servisPjeseServisID->ReadOnly = true;
        }
        $this->servisPjeseServisID->setDbValueDef($rsnew, $this->servisPjeseServisID->CurrentValue, 0, $this->servisPjeseServisID->ReadOnly);

        // servisPjesePjesa
        $this->servisPjesePjesa->setDbValueDef($rsnew, $this->servisPjesePjesa->CurrentValue, 0, $this->servisPjesePjesa->ReadOnly);

        // servisPjeseSasia
        $this->servisPjeseSasia->setDbValueDef($rsnew, $this->servisPjeseSasia->CurrentValue, 0, $this->servisPjeseSasia->ReadOnly);

        // servisPjeseCmimi
        $this->servisPjeseCmimi->setDbValueDef($rsnew, $this->servisPjeseCmimi->CurrentValue, 0, $this->servisPjeseCmimi->ReadOnly);

        // servisPjeseShenim
        $this->servisPjeseShenim->setDbValueDef($rsnew, $this->servisPjeseShenim->CurrentValue, null, $this->servisPjeseShenim->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check referential integrity for master table 'servis'
        $detailKeys = [];
        $keyValue = $rsnew['servisPjeseServisID'] ?? $rsold['servisPjeseServisID'];
        $detailKeys['servisPjeseServisID'] = $keyValue;
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
                if (($parm = Get("fk_servisID", Get("servisPjeseServisID"))) !== null) {
                    $masterTbl->servisID->setQueryStringValue($parm);
                    $this->servisPjeseServisID->setQueryStringValue($masterTbl->servisID->QueryStringValue);
                    $this->servisPjeseServisID->setSessionValue($this->servisPjeseServisID->QueryStringValue);
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
                if (($parm = Post("fk_servisID", Post("servisPjeseServisID"))) !== null) {
                    $masterTbl->servisID->setFormValue($parm);
                    $this->servisPjeseServisID->setFormValue($masterTbl->servisID->FormValue);
                    $this->servisPjeseServisID->setSessionValue($this->servisPjeseServisID->FormValue);
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
                if ($this->servisPjeseServisID->CurrentValue == "") {
                    $this->servisPjeseServisID->setSessionValue("");
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ServisPjesetList"), "", $this->TableVar, true);
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
                case "x_servisPjesePjesa":
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
