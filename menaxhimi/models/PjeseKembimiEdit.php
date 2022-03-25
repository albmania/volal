<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class PjeseKembimiEdit extends PjeseKembimi
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'pjese_kembimi';

    // Page object name
    public $PageObjName = "PjeseKembimiEdit";

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

        // Table object (pjese_kembimi)
        if (!isset($GLOBALS["pjese_kembimi"]) || get_class($GLOBALS["pjese_kembimi"]) == PROJECT_NAMESPACE . "pjese_kembimi") {
            $GLOBALS["pjese_kembimi"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'pjese_kembimi');
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
                $tbl = Container("pjese_kembimi");
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
                    if ($pageName == "PjeseKembimiView") {
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
            $key .= @$ar['pjeseID'];
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
            $this->pjeseID->Visible = false;
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
        $this->pjeseID->setVisibility();
        $this->pjeseGjendja->setVisibility();
        $this->pjeseKodiVolvo->setVisibility();
        $this->pjeseKodiProdhuesi->setVisibility();
        $this->pjeseProdhuesi->setVisibility();
        $this->pjesePerMarke->setVisibility();
        $this->pjesePerModel->setVisibility();
        $this->pjesePerVitProdhimi->setVisibility();
        $this->pjeseCmimBlerje->setVisibility();
        $this->pjeseCmimShitje->setVisibility();
        $this->pjeseAutori->setVisibility();
        $this->pjeseShtuar->Visible = false;
        $this->pjeseModifikuar->setVisibility();
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
        $this->setupLookupOptions($this->pjeseGjendja);
        $this->setupLookupOptions($this->pjeseAutori);

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
            if (($keyValue = Get("pjeseID") ?? Key(0) ?? Route(2)) !== null) {
                $this->pjeseID->setQueryStringValue($keyValue);
                $this->pjeseID->setOldValue($this->pjeseID->QueryStringValue);
            } elseif (Post("pjeseID") !== null) {
                $this->pjeseID->setFormValue(Post("pjeseID"));
                $this->pjeseID->setOldValue($this->pjeseID->FormValue);
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
                if (($keyValue = Get("pjeseID") ?? Route("pjeseID")) !== null) {
                    $this->pjeseID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->pjeseID->CurrentValue = null;
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
                        $this->terminate("PjeseKembimiList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "PjeseKembimiList") {
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

        // Check field name 'pjeseID' first before field var 'x_pjeseID'
        $val = $CurrentForm->hasValue("pjeseID") ? $CurrentForm->getValue("pjeseID") : $CurrentForm->getValue("x_pjeseID");
        if (!$this->pjeseID->IsDetailKey) {
            $this->pjeseID->setFormValue($val);
        }

        // Check field name 'pjeseGjendja' first before field var 'x_pjeseGjendja'
        $val = $CurrentForm->hasValue("pjeseGjendja") ? $CurrentForm->getValue("pjeseGjendja") : $CurrentForm->getValue("x_pjeseGjendja");
        if (!$this->pjeseGjendja->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjeseGjendja->Visible = false; // Disable update for API request
            } else {
                $this->pjeseGjendja->setFormValue($val);
            }
        }

        // Check field name 'pjeseKodiVolvo' first before field var 'x_pjeseKodiVolvo'
        $val = $CurrentForm->hasValue("pjeseKodiVolvo") ? $CurrentForm->getValue("pjeseKodiVolvo") : $CurrentForm->getValue("x_pjeseKodiVolvo");
        if (!$this->pjeseKodiVolvo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjeseKodiVolvo->Visible = false; // Disable update for API request
            } else {
                $this->pjeseKodiVolvo->setFormValue($val);
            }
        }

        // Check field name 'pjeseKodiProdhuesi' first before field var 'x_pjeseKodiProdhuesi'
        $val = $CurrentForm->hasValue("pjeseKodiProdhuesi") ? $CurrentForm->getValue("pjeseKodiProdhuesi") : $CurrentForm->getValue("x_pjeseKodiProdhuesi");
        if (!$this->pjeseKodiProdhuesi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjeseKodiProdhuesi->Visible = false; // Disable update for API request
            } else {
                $this->pjeseKodiProdhuesi->setFormValue($val);
            }
        }

        // Check field name 'pjeseProdhuesi' first before field var 'x_pjeseProdhuesi'
        $val = $CurrentForm->hasValue("pjeseProdhuesi") ? $CurrentForm->getValue("pjeseProdhuesi") : $CurrentForm->getValue("x_pjeseProdhuesi");
        if (!$this->pjeseProdhuesi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjeseProdhuesi->Visible = false; // Disable update for API request
            } else {
                $this->pjeseProdhuesi->setFormValue($val);
            }
        }

        // Check field name 'pjesePerMarke' first before field var 'x_pjesePerMarke'
        $val = $CurrentForm->hasValue("pjesePerMarke") ? $CurrentForm->getValue("pjesePerMarke") : $CurrentForm->getValue("x_pjesePerMarke");
        if (!$this->pjesePerMarke->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjesePerMarke->Visible = false; // Disable update for API request
            } else {
                $this->pjesePerMarke->setFormValue($val);
            }
        }

        // Check field name 'pjesePerModel' first before field var 'x_pjesePerModel'
        $val = $CurrentForm->hasValue("pjesePerModel") ? $CurrentForm->getValue("pjesePerModel") : $CurrentForm->getValue("x_pjesePerModel");
        if (!$this->pjesePerModel->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjesePerModel->Visible = false; // Disable update for API request
            } else {
                $this->pjesePerModel->setFormValue($val);
            }
        }

        // Check field name 'pjesePerVitProdhimi' first before field var 'x_pjesePerVitProdhimi'
        $val = $CurrentForm->hasValue("pjesePerVitProdhimi") ? $CurrentForm->getValue("pjesePerVitProdhimi") : $CurrentForm->getValue("x_pjesePerVitProdhimi");
        if (!$this->pjesePerVitProdhimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjesePerVitProdhimi->Visible = false; // Disable update for API request
            } else {
                $this->pjesePerVitProdhimi->setFormValue($val);
            }
        }

        // Check field name 'pjeseCmimBlerje' first before field var 'x_pjeseCmimBlerje'
        $val = $CurrentForm->hasValue("pjeseCmimBlerje") ? $CurrentForm->getValue("pjeseCmimBlerje") : $CurrentForm->getValue("x_pjeseCmimBlerje");
        if (!$this->pjeseCmimBlerje->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjeseCmimBlerje->Visible = false; // Disable update for API request
            } else {
                $this->pjeseCmimBlerje->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'pjeseCmimShitje' first before field var 'x_pjeseCmimShitje'
        $val = $CurrentForm->hasValue("pjeseCmimShitje") ? $CurrentForm->getValue("pjeseCmimShitje") : $CurrentForm->getValue("x_pjeseCmimShitje");
        if (!$this->pjeseCmimShitje->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjeseCmimShitje->Visible = false; // Disable update for API request
            } else {
                $this->pjeseCmimShitje->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'pjeseAutori' first before field var 'x_pjeseAutori'
        $val = $CurrentForm->hasValue("pjeseAutori") ? $CurrentForm->getValue("pjeseAutori") : $CurrentForm->getValue("x_pjeseAutori");
        if (!$this->pjeseAutori->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjeseAutori->Visible = false; // Disable update for API request
            } else {
                $this->pjeseAutori->setFormValue($val);
            }
        }

        // Check field name 'pjeseModifikuar' first before field var 'x_pjeseModifikuar'
        $val = $CurrentForm->hasValue("pjeseModifikuar") ? $CurrentForm->getValue("pjeseModifikuar") : $CurrentForm->getValue("x_pjeseModifikuar");
        if (!$this->pjeseModifikuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->pjeseModifikuar->Visible = false; // Disable update for API request
            } else {
                $this->pjeseModifikuar->setFormValue($val);
            }
            $this->pjeseModifikuar->CurrentValue = UnFormatDateTime($this->pjeseModifikuar->CurrentValue, $this->pjeseModifikuar->formatPattern());
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->pjeseID->CurrentValue = $this->pjeseID->FormValue;
        $this->pjeseGjendja->CurrentValue = $this->pjeseGjendja->FormValue;
        $this->pjeseKodiVolvo->CurrentValue = $this->pjeseKodiVolvo->FormValue;
        $this->pjeseKodiProdhuesi->CurrentValue = $this->pjeseKodiProdhuesi->FormValue;
        $this->pjeseProdhuesi->CurrentValue = $this->pjeseProdhuesi->FormValue;
        $this->pjesePerMarke->CurrentValue = $this->pjesePerMarke->FormValue;
        $this->pjesePerModel->CurrentValue = $this->pjesePerModel->FormValue;
        $this->pjesePerVitProdhimi->CurrentValue = $this->pjesePerVitProdhimi->FormValue;
        $this->pjeseCmimBlerje->CurrentValue = $this->pjeseCmimBlerje->FormValue;
        $this->pjeseCmimShitje->CurrentValue = $this->pjeseCmimShitje->FormValue;
        $this->pjeseAutori->CurrentValue = $this->pjeseAutori->FormValue;
        $this->pjeseModifikuar->CurrentValue = $this->pjeseModifikuar->FormValue;
        $this->pjeseModifikuar->CurrentValue = UnFormatDateTime($this->pjeseModifikuar->CurrentValue, $this->pjeseModifikuar->formatPattern());
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
        $this->pjeseID->setDbValue($row['pjeseID']);
        $this->pjeseGjendja->setDbValue($row['pjeseGjendja']);
        $this->pjeseKodiVolvo->setDbValue($row['pjeseKodiVolvo']);
        $this->pjeseKodiProdhuesi->setDbValue($row['pjeseKodiProdhuesi']);
        $this->pjeseProdhuesi->setDbValue($row['pjeseProdhuesi']);
        $this->pjesePerMarke->setDbValue($row['pjesePerMarke']);
        $this->pjesePerModel->setDbValue($row['pjesePerModel']);
        $this->pjesePerVitProdhimi->setDbValue($row['pjesePerVitProdhimi']);
        $this->pjeseCmimBlerje->setDbValue($row['pjeseCmimBlerje']);
        $this->pjeseCmimShitje->setDbValue($row['pjeseCmimShitje']);
        $this->pjeseAutori->setDbValue($row['pjeseAutori']);
        $this->pjeseShtuar->setDbValue($row['pjeseShtuar']);
        $this->pjeseModifikuar->setDbValue($row['pjeseModifikuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['pjeseID'] = $this->pjeseID->DefaultValue;
        $row['pjeseGjendja'] = $this->pjeseGjendja->DefaultValue;
        $row['pjeseKodiVolvo'] = $this->pjeseKodiVolvo->DefaultValue;
        $row['pjeseKodiProdhuesi'] = $this->pjeseKodiProdhuesi->DefaultValue;
        $row['pjeseProdhuesi'] = $this->pjeseProdhuesi->DefaultValue;
        $row['pjesePerMarke'] = $this->pjesePerMarke->DefaultValue;
        $row['pjesePerModel'] = $this->pjesePerModel->DefaultValue;
        $row['pjesePerVitProdhimi'] = $this->pjesePerVitProdhimi->DefaultValue;
        $row['pjeseCmimBlerje'] = $this->pjeseCmimBlerje->DefaultValue;
        $row['pjeseCmimShitje'] = $this->pjeseCmimShitje->DefaultValue;
        $row['pjeseAutori'] = $this->pjeseAutori->DefaultValue;
        $row['pjeseShtuar'] = $this->pjeseShtuar->DefaultValue;
        $row['pjeseModifikuar'] = $this->pjeseModifikuar->DefaultValue;
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

        // pjeseID
        $this->pjeseID->RowCssClass = "row";

        // pjeseGjendja
        $this->pjeseGjendja->RowCssClass = "row";

        // pjeseKodiVolvo
        $this->pjeseKodiVolvo->RowCssClass = "row";

        // pjeseKodiProdhuesi
        $this->pjeseKodiProdhuesi->RowCssClass = "row";

        // pjeseProdhuesi
        $this->pjeseProdhuesi->RowCssClass = "row";

        // pjesePerMarke
        $this->pjesePerMarke->RowCssClass = "row";

        // pjesePerModel
        $this->pjesePerModel->RowCssClass = "row";

        // pjesePerVitProdhimi
        $this->pjesePerVitProdhimi->RowCssClass = "row";

        // pjeseCmimBlerje
        $this->pjeseCmimBlerje->RowCssClass = "row";

        // pjeseCmimShitje
        $this->pjeseCmimShitje->RowCssClass = "row";

        // pjeseAutori
        $this->pjeseAutori->RowCssClass = "row";

        // pjeseShtuar
        $this->pjeseShtuar->RowCssClass = "row";

        // pjeseModifikuar
        $this->pjeseModifikuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // pjeseID
            $this->pjeseID->ViewValue = $this->pjeseID->CurrentValue;
            $this->pjeseID->ViewCustomAttributes = "";

            // pjeseGjendja
            if (strval($this->pjeseGjendja->CurrentValue) != "") {
                $this->pjeseGjendja->ViewValue = $this->pjeseGjendja->optionCaption($this->pjeseGjendja->CurrentValue);
            } else {
                $this->pjeseGjendja->ViewValue = null;
            }
            $this->pjeseGjendja->ViewCustomAttributes = "";

            // pjeseKodiVolvo
            $this->pjeseKodiVolvo->ViewValue = $this->pjeseKodiVolvo->CurrentValue;
            $this->pjeseKodiVolvo->ViewCustomAttributes = "";

            // pjeseKodiProdhuesi
            $this->pjeseKodiProdhuesi->ViewValue = $this->pjeseKodiProdhuesi->CurrentValue;
            $this->pjeseKodiProdhuesi->ViewCustomAttributes = "";

            // pjeseProdhuesi
            $this->pjeseProdhuesi->ViewValue = $this->pjeseProdhuesi->CurrentValue;
            $this->pjeseProdhuesi->ViewCustomAttributes = "";

            // pjesePerMarke
            $this->pjesePerMarke->ViewValue = $this->pjesePerMarke->CurrentValue;
            $this->pjesePerMarke->ViewCustomAttributes = "";

            // pjesePerModel
            $this->pjesePerModel->ViewValue = $this->pjesePerModel->CurrentValue;
            $this->pjesePerModel->ViewCustomAttributes = "";

            // pjesePerVitProdhimi
            $this->pjesePerVitProdhimi->ViewValue = $this->pjesePerVitProdhimi->CurrentValue;
            $this->pjesePerVitProdhimi->ViewCustomAttributes = "";

            // pjeseCmimBlerje
            $this->pjeseCmimBlerje->ViewValue = $this->pjeseCmimBlerje->CurrentValue;
            $this->pjeseCmimBlerje->ViewValue = FormatNumber($this->pjeseCmimBlerje->ViewValue, $this->pjeseCmimBlerje->formatPattern());
            $this->pjeseCmimBlerje->ViewCustomAttributes = "";

            // pjeseCmimShitje
            $this->pjeseCmimShitje->ViewValue = $this->pjeseCmimShitje->CurrentValue;
            $this->pjeseCmimShitje->ViewValue = FormatNumber($this->pjeseCmimShitje->ViewValue, $this->pjeseCmimShitje->formatPattern());
            $this->pjeseCmimShitje->ViewCustomAttributes = "";

            // pjeseAutori
            $this->pjeseAutori->ViewValue = $this->pjeseAutori->CurrentValue;
            $curVal = strval($this->pjeseAutori->CurrentValue);
            if ($curVal != "") {
                $this->pjeseAutori->ViewValue = $this->pjeseAutori->lookupCacheOption($curVal);
                if ($this->pjeseAutori->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->pjeseAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->pjeseAutori->Lookup->renderViewRow($rswrk[0]);
                        $this->pjeseAutori->ViewValue = $this->pjeseAutori->displayValue($arwrk);
                    } else {
                        $this->pjeseAutori->ViewValue = FormatNumber($this->pjeseAutori->CurrentValue, $this->pjeseAutori->formatPattern());
                    }
                }
            } else {
                $this->pjeseAutori->ViewValue = null;
            }
            $this->pjeseAutori->ViewCustomAttributes = "";

            // pjeseShtuar
            $this->pjeseShtuar->ViewValue = $this->pjeseShtuar->CurrentValue;
            $this->pjeseShtuar->ViewValue = FormatDateTime($this->pjeseShtuar->ViewValue, $this->pjeseShtuar->formatPattern());
            $this->pjeseShtuar->ViewCustomAttributes = "";

            // pjeseModifikuar
            $this->pjeseModifikuar->ViewValue = $this->pjeseModifikuar->CurrentValue;
            $this->pjeseModifikuar->ViewValue = FormatDateTime($this->pjeseModifikuar->ViewValue, $this->pjeseModifikuar->formatPattern());
            $this->pjeseModifikuar->ViewCustomAttributes = "";

            // pjeseID
            $this->pjeseID->LinkCustomAttributes = "";
            $this->pjeseID->HrefValue = "";

            // pjeseGjendja
            $this->pjeseGjendja->LinkCustomAttributes = "";
            $this->pjeseGjendja->HrefValue = "";

            // pjeseKodiVolvo
            $this->pjeseKodiVolvo->LinkCustomAttributes = "";
            $this->pjeseKodiVolvo->HrefValue = "";

            // pjeseKodiProdhuesi
            $this->pjeseKodiProdhuesi->LinkCustomAttributes = "";
            $this->pjeseKodiProdhuesi->HrefValue = "";

            // pjeseProdhuesi
            $this->pjeseProdhuesi->LinkCustomAttributes = "";
            $this->pjeseProdhuesi->HrefValue = "";

            // pjesePerMarke
            $this->pjesePerMarke->LinkCustomAttributes = "";
            $this->pjesePerMarke->HrefValue = "";

            // pjesePerModel
            $this->pjesePerModel->LinkCustomAttributes = "";
            $this->pjesePerModel->HrefValue = "";

            // pjesePerVitProdhimi
            $this->pjesePerVitProdhimi->LinkCustomAttributes = "";
            $this->pjesePerVitProdhimi->HrefValue = "";

            // pjeseCmimBlerje
            $this->pjeseCmimBlerje->LinkCustomAttributes = "";
            $this->pjeseCmimBlerje->HrefValue = "";

            // pjeseCmimShitje
            $this->pjeseCmimShitje->LinkCustomAttributes = "";
            $this->pjeseCmimShitje->HrefValue = "";

            // pjeseAutori
            $this->pjeseAutori->LinkCustomAttributes = "";
            $this->pjeseAutori->HrefValue = "";

            // pjeseModifikuar
            $this->pjeseModifikuar->LinkCustomAttributes = "";
            $this->pjeseModifikuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // pjeseID
            $this->pjeseID->setupEditAttributes();
            $this->pjeseID->EditCustomAttributes = "";
            $this->pjeseID->EditValue = $this->pjeseID->CurrentValue;
            $this->pjeseID->ViewCustomAttributes = "";

            // pjeseGjendja
            $this->pjeseGjendja->EditCustomAttributes = "";
            $this->pjeseGjendja->EditValue = $this->pjeseGjendja->options(false);
            $this->pjeseGjendja->PlaceHolder = RemoveHtml($this->pjeseGjendja->caption());

            // pjeseKodiVolvo
            $this->pjeseKodiVolvo->setupEditAttributes();
            $this->pjeseKodiVolvo->EditCustomAttributes = "";
            if (!$this->pjeseKodiVolvo->Raw) {
                $this->pjeseKodiVolvo->CurrentValue = HtmlDecode($this->pjeseKodiVolvo->CurrentValue);
            }
            $this->pjeseKodiVolvo->EditValue = HtmlEncode($this->pjeseKodiVolvo->CurrentValue);
            $this->pjeseKodiVolvo->PlaceHolder = RemoveHtml($this->pjeseKodiVolvo->caption());

            // pjeseKodiProdhuesi
            $this->pjeseKodiProdhuesi->setupEditAttributes();
            $this->pjeseKodiProdhuesi->EditCustomAttributes = "";
            if (!$this->pjeseKodiProdhuesi->Raw) {
                $this->pjeseKodiProdhuesi->CurrentValue = HtmlDecode($this->pjeseKodiProdhuesi->CurrentValue);
            }
            $this->pjeseKodiProdhuesi->EditValue = HtmlEncode($this->pjeseKodiProdhuesi->CurrentValue);
            $this->pjeseKodiProdhuesi->PlaceHolder = RemoveHtml($this->pjeseKodiProdhuesi->caption());

            // pjeseProdhuesi
            $this->pjeseProdhuesi->setupEditAttributes();
            $this->pjeseProdhuesi->EditCustomAttributes = "";
            if (!$this->pjeseProdhuesi->Raw) {
                $this->pjeseProdhuesi->CurrentValue = HtmlDecode($this->pjeseProdhuesi->CurrentValue);
            }
            $this->pjeseProdhuesi->EditValue = HtmlEncode($this->pjeseProdhuesi->CurrentValue);
            $this->pjeseProdhuesi->PlaceHolder = RemoveHtml($this->pjeseProdhuesi->caption());

            // pjesePerMarke
            $this->pjesePerMarke->setupEditAttributes();
            $this->pjesePerMarke->EditCustomAttributes = "";
            if (!$this->pjesePerMarke->Raw) {
                $this->pjesePerMarke->CurrentValue = HtmlDecode($this->pjesePerMarke->CurrentValue);
            }
            $this->pjesePerMarke->EditValue = HtmlEncode($this->pjesePerMarke->CurrentValue);
            $this->pjesePerMarke->PlaceHolder = RemoveHtml($this->pjesePerMarke->caption());

            // pjesePerModel
            $this->pjesePerModel->setupEditAttributes();
            $this->pjesePerModel->EditCustomAttributes = "";
            if (!$this->pjesePerModel->Raw) {
                $this->pjesePerModel->CurrentValue = HtmlDecode($this->pjesePerModel->CurrentValue);
            }
            $this->pjesePerModel->EditValue = HtmlEncode($this->pjesePerModel->CurrentValue);
            $this->pjesePerModel->PlaceHolder = RemoveHtml($this->pjesePerModel->caption());

            // pjesePerVitProdhimi
            $this->pjesePerVitProdhimi->setupEditAttributes();
            $this->pjesePerVitProdhimi->EditCustomAttributes = "";
            if (!$this->pjesePerVitProdhimi->Raw) {
                $this->pjesePerVitProdhimi->CurrentValue = HtmlDecode($this->pjesePerVitProdhimi->CurrentValue);
            }
            $this->pjesePerVitProdhimi->EditValue = HtmlEncode($this->pjesePerVitProdhimi->CurrentValue);
            $this->pjesePerVitProdhimi->PlaceHolder = RemoveHtml($this->pjesePerVitProdhimi->caption());

            // pjeseCmimBlerje
            $this->pjeseCmimBlerje->setupEditAttributes();
            $this->pjeseCmimBlerje->EditCustomAttributes = "";
            $this->pjeseCmimBlerje->EditValue = HtmlEncode($this->pjeseCmimBlerje->CurrentValue);
            $this->pjeseCmimBlerje->PlaceHolder = RemoveHtml($this->pjeseCmimBlerje->caption());
            if (strval($this->pjeseCmimBlerje->EditValue) != "" && is_numeric($this->pjeseCmimBlerje->EditValue)) {
                $this->pjeseCmimBlerje->EditValue = FormatNumber($this->pjeseCmimBlerje->EditValue, null);
            }

            // pjeseCmimShitje
            $this->pjeseCmimShitje->setupEditAttributes();
            $this->pjeseCmimShitje->EditCustomAttributes = "";
            $this->pjeseCmimShitje->EditValue = HtmlEncode($this->pjeseCmimShitje->CurrentValue);
            $this->pjeseCmimShitje->PlaceHolder = RemoveHtml($this->pjeseCmimShitje->caption());
            if (strval($this->pjeseCmimShitje->EditValue) != "" && is_numeric($this->pjeseCmimShitje->EditValue)) {
                $this->pjeseCmimShitje->EditValue = FormatNumber($this->pjeseCmimShitje->EditValue, null);
            }

            // pjeseAutori

            // pjeseModifikuar

            // Edit refer script

            // pjeseID
            $this->pjeseID->LinkCustomAttributes = "";
            $this->pjeseID->HrefValue = "";

            // pjeseGjendja
            $this->pjeseGjendja->LinkCustomAttributes = "";
            $this->pjeseGjendja->HrefValue = "";

            // pjeseKodiVolvo
            $this->pjeseKodiVolvo->LinkCustomAttributes = "";
            $this->pjeseKodiVolvo->HrefValue = "";

            // pjeseKodiProdhuesi
            $this->pjeseKodiProdhuesi->LinkCustomAttributes = "";
            $this->pjeseKodiProdhuesi->HrefValue = "";

            // pjeseProdhuesi
            $this->pjeseProdhuesi->LinkCustomAttributes = "";
            $this->pjeseProdhuesi->HrefValue = "";

            // pjesePerMarke
            $this->pjesePerMarke->LinkCustomAttributes = "";
            $this->pjesePerMarke->HrefValue = "";

            // pjesePerModel
            $this->pjesePerModel->LinkCustomAttributes = "";
            $this->pjesePerModel->HrefValue = "";

            // pjesePerVitProdhimi
            $this->pjesePerVitProdhimi->LinkCustomAttributes = "";
            $this->pjesePerVitProdhimi->HrefValue = "";

            // pjeseCmimBlerje
            $this->pjeseCmimBlerje->LinkCustomAttributes = "";
            $this->pjeseCmimBlerje->HrefValue = "";

            // pjeseCmimShitje
            $this->pjeseCmimShitje->LinkCustomAttributes = "";
            $this->pjeseCmimShitje->HrefValue = "";

            // pjeseAutori
            $this->pjeseAutori->LinkCustomAttributes = "";
            $this->pjeseAutori->HrefValue = "";

            // pjeseModifikuar
            $this->pjeseModifikuar->LinkCustomAttributes = "";
            $this->pjeseModifikuar->HrefValue = "";
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
        if ($this->pjeseID->Required) {
            if (!$this->pjeseID->IsDetailKey && EmptyValue($this->pjeseID->FormValue)) {
                $this->pjeseID->addErrorMessage(str_replace("%s", $this->pjeseID->caption(), $this->pjeseID->RequiredErrorMessage));
            }
        }
        if ($this->pjeseGjendja->Required) {
            if ($this->pjeseGjendja->FormValue == "") {
                $this->pjeseGjendja->addErrorMessage(str_replace("%s", $this->pjeseGjendja->caption(), $this->pjeseGjendja->RequiredErrorMessage));
            }
        }
        if ($this->pjeseKodiVolvo->Required) {
            if (!$this->pjeseKodiVolvo->IsDetailKey && EmptyValue($this->pjeseKodiVolvo->FormValue)) {
                $this->pjeseKodiVolvo->addErrorMessage(str_replace("%s", $this->pjeseKodiVolvo->caption(), $this->pjeseKodiVolvo->RequiredErrorMessage));
            }
        }
        if ($this->pjeseKodiProdhuesi->Required) {
            if (!$this->pjeseKodiProdhuesi->IsDetailKey && EmptyValue($this->pjeseKodiProdhuesi->FormValue)) {
                $this->pjeseKodiProdhuesi->addErrorMessage(str_replace("%s", $this->pjeseKodiProdhuesi->caption(), $this->pjeseKodiProdhuesi->RequiredErrorMessage));
            }
        }
        if ($this->pjeseProdhuesi->Required) {
            if (!$this->pjeseProdhuesi->IsDetailKey && EmptyValue($this->pjeseProdhuesi->FormValue)) {
                $this->pjeseProdhuesi->addErrorMessage(str_replace("%s", $this->pjeseProdhuesi->caption(), $this->pjeseProdhuesi->RequiredErrorMessage));
            }
        }
        if ($this->pjesePerMarke->Required) {
            if (!$this->pjesePerMarke->IsDetailKey && EmptyValue($this->pjesePerMarke->FormValue)) {
                $this->pjesePerMarke->addErrorMessage(str_replace("%s", $this->pjesePerMarke->caption(), $this->pjesePerMarke->RequiredErrorMessage));
            }
        }
        if ($this->pjesePerModel->Required) {
            if (!$this->pjesePerModel->IsDetailKey && EmptyValue($this->pjesePerModel->FormValue)) {
                $this->pjesePerModel->addErrorMessage(str_replace("%s", $this->pjesePerModel->caption(), $this->pjesePerModel->RequiredErrorMessage));
            }
        }
        if ($this->pjesePerVitProdhimi->Required) {
            if (!$this->pjesePerVitProdhimi->IsDetailKey && EmptyValue($this->pjesePerVitProdhimi->FormValue)) {
                $this->pjesePerVitProdhimi->addErrorMessage(str_replace("%s", $this->pjesePerVitProdhimi->caption(), $this->pjesePerVitProdhimi->RequiredErrorMessage));
            }
        }
        if ($this->pjeseCmimBlerje->Required) {
            if (!$this->pjeseCmimBlerje->IsDetailKey && EmptyValue($this->pjeseCmimBlerje->FormValue)) {
                $this->pjeseCmimBlerje->addErrorMessage(str_replace("%s", $this->pjeseCmimBlerje->caption(), $this->pjeseCmimBlerje->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->pjeseCmimBlerje->FormValue)) {
            $this->pjeseCmimBlerje->addErrorMessage($this->pjeseCmimBlerje->getErrorMessage(false));
        }
        if ($this->pjeseCmimShitje->Required) {
            if (!$this->pjeseCmimShitje->IsDetailKey && EmptyValue($this->pjeseCmimShitje->FormValue)) {
                $this->pjeseCmimShitje->addErrorMessage(str_replace("%s", $this->pjeseCmimShitje->caption(), $this->pjeseCmimShitje->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->pjeseCmimShitje->FormValue)) {
            $this->pjeseCmimShitje->addErrorMessage($this->pjeseCmimShitje->getErrorMessage(false));
        }
        if ($this->pjeseAutori->Required) {
            if (!$this->pjeseAutori->IsDetailKey && EmptyValue($this->pjeseAutori->FormValue)) {
                $this->pjeseAutori->addErrorMessage(str_replace("%s", $this->pjeseAutori->caption(), $this->pjeseAutori->RequiredErrorMessage));
            }
        }
        if ($this->pjeseModifikuar->Required) {
            if (!$this->pjeseModifikuar->IsDetailKey && EmptyValue($this->pjeseModifikuar->FormValue)) {
                $this->pjeseModifikuar->addErrorMessage(str_replace("%s", $this->pjeseModifikuar->caption(), $this->pjeseModifikuar->RequiredErrorMessage));
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

        // pjeseGjendja
        $this->pjeseGjendja->setDbValueDef($rsnew, $this->pjeseGjendja->CurrentValue, "", $this->pjeseGjendja->ReadOnly);

        // pjeseKodiVolvo
        $this->pjeseKodiVolvo->setDbValueDef($rsnew, $this->pjeseKodiVolvo->CurrentValue, "", $this->pjeseKodiVolvo->ReadOnly);

        // pjeseKodiProdhuesi
        $this->pjeseKodiProdhuesi->setDbValueDef($rsnew, $this->pjeseKodiProdhuesi->CurrentValue, "", $this->pjeseKodiProdhuesi->ReadOnly);

        // pjeseProdhuesi
        $this->pjeseProdhuesi->setDbValueDef($rsnew, $this->pjeseProdhuesi->CurrentValue, "", $this->pjeseProdhuesi->ReadOnly);

        // pjesePerMarke
        $this->pjesePerMarke->setDbValueDef($rsnew, $this->pjesePerMarke->CurrentValue, null, $this->pjesePerMarke->ReadOnly);

        // pjesePerModel
        $this->pjesePerModel->setDbValueDef($rsnew, $this->pjesePerModel->CurrentValue, null, $this->pjesePerModel->ReadOnly);

        // pjesePerVitProdhimi
        $this->pjesePerVitProdhimi->setDbValueDef($rsnew, $this->pjesePerVitProdhimi->CurrentValue, null, $this->pjesePerVitProdhimi->ReadOnly);

        // pjeseCmimBlerje
        $this->pjeseCmimBlerje->setDbValueDef($rsnew, $this->pjeseCmimBlerje->CurrentValue, null, $this->pjeseCmimBlerje->ReadOnly);

        // pjeseCmimShitje
        $this->pjeseCmimShitje->setDbValueDef($rsnew, $this->pjeseCmimShitje->CurrentValue, 0, $this->pjeseCmimShitje->ReadOnly);

        // pjeseAutori
        $this->pjeseAutori->CurrentValue = CurrentUserID();
        $this->pjeseAutori->setDbValueDef($rsnew, $this->pjeseAutori->CurrentValue, 0);

        // pjeseModifikuar
        $this->pjeseModifikuar->CurrentValue = CurrentDateTime();
        $this->pjeseModifikuar->setDbValueDef($rsnew, $this->pjeseModifikuar->CurrentValue, null);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PjeseKembimiList"), "", $this->TableVar, true);
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
                case "x_pjeseGjendja":
                    break;
                case "x_pjeseAutori":
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
