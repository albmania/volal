<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaShitjeAdd extends MakinaShitje
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina_shitje';

    // Page object name
    public $PageObjName = "MakinaShitjeAdd";

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

        // Table object (makina_shitje)
        if (!isset($GLOBALS["makina_shitje"]) || get_class($GLOBALS["makina_shitje"]) == PROJECT_NAMESPACE . "makina_shitje") {
            $GLOBALS["makina_shitje"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'makina_shitje');
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
                $tbl = Container("makina_shitje");
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
                    if ($pageName == "MakinaShitjeView") {
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
		        $this->mshitjeFotografi->OldUploadPath = '../ngarkime/makina/';
		        $this->mshitjeFotografi->UploadPath = $this->mshitjeFotografi->OldUploadPath;
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
            $key .= @$ar['mshitjeID'];
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
            $this->mshitjeID->Visible = false;
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
        $this->mshitjeID->Visible = false;
        $this->mshitjeMarka->setVisibility();
        $this->mshitjeModeli->setVisibility();
        $this->mshitjeTipi->setVisibility();
        $this->mshitjeStruktura->setVisibility();
        $this->mshitjeKapacitetiMotorrit->setVisibility();
        $this->mshitjeVitiProdhimit->setVisibility();
        $this->mshitjeKarburant->setVisibility();
        $this->mshitjeNgjyra->setVisibility();
        $this->mshitjeNrVendeve->setVisibility();
        $this->mshitjeKambio->setVisibility();
        $this->mshitjePrejardhja->setVisibility();
        $this->mshitjeTargaAL->setVisibility();
        $this->mshitjeKilometra->setVisibility();
        $this->mshitjeFotografi->setVisibility();
        $this->mshitjePershkrimi->setVisibility();
        $this->mshitjeCmimi->setVisibility();
        $this->mshitjeIndex->setVisibility();
        $this->mshitjePromo->setVisibility();
        $this->mshitjeAktiv->setVisibility();
        $this->mshitjeShitur->setVisibility();
        $this->mshitjeAutori->setVisibility();
        $this->mshitjeKrijuar->setVisibility();
        $this->mshitjeAzhornuar->Visible = false;
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
        $this->setupLookupOptions($this->mshitjeMarka);
        $this->setupLookupOptions($this->mshitjeModeli);
        $this->setupLookupOptions($this->mshitjeTipi);
        $this->setupLookupOptions($this->mshitjeStruktura);
        $this->setupLookupOptions($this->mshitjeKarburant);
        $this->setupLookupOptions($this->mshitjeKambio);
        $this->setupLookupOptions($this->mshitjeTargaAL);
        $this->setupLookupOptions($this->mshitjeIndex);
        $this->setupLookupOptions($this->mshitjePromo);
        $this->setupLookupOptions($this->mshitjeAktiv);
        $this->setupLookupOptions($this->mshitjeShitur);
        $this->setupLookupOptions($this->mshitjeAutori);

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
            if (($keyValue = Get("mshitjeID") ?? Route("mshitjeID")) !== null) {
                $this->mshitjeID->setQueryStringValue($keyValue);
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
                    $this->terminate("MakinaShitjeList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = "MakinaShitjeList";
                    if (GetPageName($returnUrl) == "MakinaShitjeList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "MakinaShitjeView") {
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
        $this->mshitjeFotografi->Upload->Index = $CurrentForm->Index;
        $this->mshitjeFotografi->Upload->uploadFile();
        $this->mshitjeFotografi->CurrentValue = $this->mshitjeFotografi->Upload->FileName;
    }

    // Load default values
    protected function loadDefaultValues()
    {
        $this->mshitjeStruktura->DefaultValue = "Sedan";
        $this->mshitjeKarburant->DefaultValue = "Nafte";
        $this->mshitjeKambio->DefaultValue = "Automatike";
        $this->mshitjeTargaAL->DefaultValue = "Jo";
        $this->mshitjeIndex->DefaultValue = "Po";
        $this->mshitjePromo->DefaultValue = "Jo";
        $this->mshitjeAktiv->DefaultValue = "Po";
        $this->mshitjeShitur->DefaultValue = "Jo";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'mshitjeMarka' first before field var 'x_mshitjeMarka'
        $val = $CurrentForm->hasValue("mshitjeMarka") ? $CurrentForm->getValue("mshitjeMarka") : $CurrentForm->getValue("x_mshitjeMarka");
        if (!$this->mshitjeMarka->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeMarka->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeMarka->setFormValue($val);
            }
        }

        // Check field name 'mshitjeModeli' first before field var 'x_mshitjeModeli'
        $val = $CurrentForm->hasValue("mshitjeModeli") ? $CurrentForm->getValue("mshitjeModeli") : $CurrentForm->getValue("x_mshitjeModeli");
        if (!$this->mshitjeModeli->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeModeli->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeModeli->setFormValue($val);
            }
        }

        // Check field name 'mshitjeTipi' first before field var 'x_mshitjeTipi'
        $val = $CurrentForm->hasValue("mshitjeTipi") ? $CurrentForm->getValue("mshitjeTipi") : $CurrentForm->getValue("x_mshitjeTipi");
        if (!$this->mshitjeTipi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeTipi->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeTipi->setFormValue($val);
            }
        }

        // Check field name 'mshitjeStruktura' first before field var 'x_mshitjeStruktura'
        $val = $CurrentForm->hasValue("mshitjeStruktura") ? $CurrentForm->getValue("mshitjeStruktura") : $CurrentForm->getValue("x_mshitjeStruktura");
        if (!$this->mshitjeStruktura->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeStruktura->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeStruktura->setFormValue($val);
            }
        }

        // Check field name 'mshitjeKapacitetiMotorrit' first before field var 'x_mshitjeKapacitetiMotorrit'
        $val = $CurrentForm->hasValue("mshitjeKapacitetiMotorrit") ? $CurrentForm->getValue("mshitjeKapacitetiMotorrit") : $CurrentForm->getValue("x_mshitjeKapacitetiMotorrit");
        if (!$this->mshitjeKapacitetiMotorrit->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeKapacitetiMotorrit->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeKapacitetiMotorrit->setFormValue($val);
            }
        }

        // Check field name 'mshitjeVitiProdhimit' first before field var 'x_mshitjeVitiProdhimit'
        $val = $CurrentForm->hasValue("mshitjeVitiProdhimit") ? $CurrentForm->getValue("mshitjeVitiProdhimit") : $CurrentForm->getValue("x_mshitjeVitiProdhimit");
        if (!$this->mshitjeVitiProdhimit->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeVitiProdhimit->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeVitiProdhimit->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'mshitjeKarburant' first before field var 'x_mshitjeKarburant'
        $val = $CurrentForm->hasValue("mshitjeKarburant") ? $CurrentForm->getValue("mshitjeKarburant") : $CurrentForm->getValue("x_mshitjeKarburant");
        if (!$this->mshitjeKarburant->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeKarburant->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeKarburant->setFormValue($val);
            }
        }

        // Check field name 'mshitjeNgjyra' first before field var 'x_mshitjeNgjyra'
        $val = $CurrentForm->hasValue("mshitjeNgjyra") ? $CurrentForm->getValue("mshitjeNgjyra") : $CurrentForm->getValue("x_mshitjeNgjyra");
        if (!$this->mshitjeNgjyra->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeNgjyra->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeNgjyra->setFormValue($val);
            }
        }

        // Check field name 'mshitjeNrVendeve' first before field var 'x_mshitjeNrVendeve'
        $val = $CurrentForm->hasValue("mshitjeNrVendeve") ? $CurrentForm->getValue("mshitjeNrVendeve") : $CurrentForm->getValue("x_mshitjeNrVendeve");
        if (!$this->mshitjeNrVendeve->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeNrVendeve->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeNrVendeve->setFormValue($val);
            }
        }

        // Check field name 'mshitjeKambio' first before field var 'x_mshitjeKambio'
        $val = $CurrentForm->hasValue("mshitjeKambio") ? $CurrentForm->getValue("mshitjeKambio") : $CurrentForm->getValue("x_mshitjeKambio");
        if (!$this->mshitjeKambio->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeKambio->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeKambio->setFormValue($val);
            }
        }

        // Check field name 'mshitjePrejardhja' first before field var 'x_mshitjePrejardhja'
        $val = $CurrentForm->hasValue("mshitjePrejardhja") ? $CurrentForm->getValue("mshitjePrejardhja") : $CurrentForm->getValue("x_mshitjePrejardhja");
        if (!$this->mshitjePrejardhja->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjePrejardhja->Visible = false; // Disable update for API request
            } else {
                $this->mshitjePrejardhja->setFormValue($val);
            }
        }

        // Check field name 'mshitjeTargaAL' first before field var 'x_mshitjeTargaAL'
        $val = $CurrentForm->hasValue("mshitjeTargaAL") ? $CurrentForm->getValue("mshitjeTargaAL") : $CurrentForm->getValue("x_mshitjeTargaAL");
        if (!$this->mshitjeTargaAL->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeTargaAL->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeTargaAL->setFormValue($val);
            }
        }

        // Check field name 'mshitjeKilometra' first before field var 'x_mshitjeKilometra'
        $val = $CurrentForm->hasValue("mshitjeKilometra") ? $CurrentForm->getValue("mshitjeKilometra") : $CurrentForm->getValue("x_mshitjeKilometra");
        if (!$this->mshitjeKilometra->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeKilometra->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeKilometra->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'mshitjePershkrimi' first before field var 'x_mshitjePershkrimi'
        $val = $CurrentForm->hasValue("mshitjePershkrimi") ? $CurrentForm->getValue("mshitjePershkrimi") : $CurrentForm->getValue("x_mshitjePershkrimi");
        if (!$this->mshitjePershkrimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjePershkrimi->Visible = false; // Disable update for API request
            } else {
                $this->mshitjePershkrimi->setFormValue($val);
            }
        }

        // Check field name 'mshitjeCmimi' first before field var 'x_mshitjeCmimi'
        $val = $CurrentForm->hasValue("mshitjeCmimi") ? $CurrentForm->getValue("mshitjeCmimi") : $CurrentForm->getValue("x_mshitjeCmimi");
        if (!$this->mshitjeCmimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeCmimi->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeCmimi->setFormValue($val);
            }
        }

        // Check field name 'mshitjeIndex' first before field var 'x_mshitjeIndex'
        $val = $CurrentForm->hasValue("mshitjeIndex") ? $CurrentForm->getValue("mshitjeIndex") : $CurrentForm->getValue("x_mshitjeIndex");
        if (!$this->mshitjeIndex->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeIndex->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeIndex->setFormValue($val);
            }
        }

        // Check field name 'mshitjePromo' first before field var 'x_mshitjePromo'
        $val = $CurrentForm->hasValue("mshitjePromo") ? $CurrentForm->getValue("mshitjePromo") : $CurrentForm->getValue("x_mshitjePromo");
        if (!$this->mshitjePromo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjePromo->Visible = false; // Disable update for API request
            } else {
                $this->mshitjePromo->setFormValue($val);
            }
        }

        // Check field name 'mshitjeAktiv' first before field var 'x_mshitjeAktiv'
        $val = $CurrentForm->hasValue("mshitjeAktiv") ? $CurrentForm->getValue("mshitjeAktiv") : $CurrentForm->getValue("x_mshitjeAktiv");
        if (!$this->mshitjeAktiv->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeAktiv->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeAktiv->setFormValue($val);
            }
        }

        // Check field name 'mshitjeShitur' first before field var 'x_mshitjeShitur'
        $val = $CurrentForm->hasValue("mshitjeShitur") ? $CurrentForm->getValue("mshitjeShitur") : $CurrentForm->getValue("x_mshitjeShitur");
        if (!$this->mshitjeShitur->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeShitur->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeShitur->setFormValue($val);
            }
        }

        // Check field name 'mshitjeAutori' first before field var 'x_mshitjeAutori'
        $val = $CurrentForm->hasValue("mshitjeAutori") ? $CurrentForm->getValue("mshitjeAutori") : $CurrentForm->getValue("x_mshitjeAutori");
        if (!$this->mshitjeAutori->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeAutori->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeAutori->setFormValue($val);
            }
        }

        // Check field name 'mshitjeKrijuar' first before field var 'x_mshitjeKrijuar'
        $val = $CurrentForm->hasValue("mshitjeKrijuar") ? $CurrentForm->getValue("mshitjeKrijuar") : $CurrentForm->getValue("x_mshitjeKrijuar");
        if (!$this->mshitjeKrijuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mshitjeKrijuar->Visible = false; // Disable update for API request
            } else {
                $this->mshitjeKrijuar->setFormValue($val);
            }
            $this->mshitjeKrijuar->CurrentValue = UnFormatDateTime($this->mshitjeKrijuar->CurrentValue, $this->mshitjeKrijuar->formatPattern());
        }

        // Check field name 'mshitjeID' first before field var 'x_mshitjeID'
        $val = $CurrentForm->hasValue("mshitjeID") ? $CurrentForm->getValue("mshitjeID") : $CurrentForm->getValue("x_mshitjeID");
		$this->mshitjeFotografi->OldUploadPath = '../ngarkime/makina/';
		$this->mshitjeFotografi->UploadPath = $this->mshitjeFotografi->OldUploadPath;
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->mshitjeMarka->CurrentValue = $this->mshitjeMarka->FormValue;
        $this->mshitjeModeli->CurrentValue = $this->mshitjeModeli->FormValue;
        $this->mshitjeTipi->CurrentValue = $this->mshitjeTipi->FormValue;
        $this->mshitjeStruktura->CurrentValue = $this->mshitjeStruktura->FormValue;
        $this->mshitjeKapacitetiMotorrit->CurrentValue = $this->mshitjeKapacitetiMotorrit->FormValue;
        $this->mshitjeVitiProdhimit->CurrentValue = $this->mshitjeVitiProdhimit->FormValue;
        $this->mshitjeKarburant->CurrentValue = $this->mshitjeKarburant->FormValue;
        $this->mshitjeNgjyra->CurrentValue = $this->mshitjeNgjyra->FormValue;
        $this->mshitjeNrVendeve->CurrentValue = $this->mshitjeNrVendeve->FormValue;
        $this->mshitjeKambio->CurrentValue = $this->mshitjeKambio->FormValue;
        $this->mshitjePrejardhja->CurrentValue = $this->mshitjePrejardhja->FormValue;
        $this->mshitjeTargaAL->CurrentValue = $this->mshitjeTargaAL->FormValue;
        $this->mshitjeKilometra->CurrentValue = $this->mshitjeKilometra->FormValue;
        $this->mshitjePershkrimi->CurrentValue = $this->mshitjePershkrimi->FormValue;
        $this->mshitjeCmimi->CurrentValue = $this->mshitjeCmimi->FormValue;
        $this->mshitjeIndex->CurrentValue = $this->mshitjeIndex->FormValue;
        $this->mshitjePromo->CurrentValue = $this->mshitjePromo->FormValue;
        $this->mshitjeAktiv->CurrentValue = $this->mshitjeAktiv->FormValue;
        $this->mshitjeShitur->CurrentValue = $this->mshitjeShitur->FormValue;
        $this->mshitjeAutori->CurrentValue = $this->mshitjeAutori->FormValue;
        $this->mshitjeKrijuar->CurrentValue = $this->mshitjeKrijuar->FormValue;
        $this->mshitjeKrijuar->CurrentValue = UnFormatDateTime($this->mshitjeKrijuar->CurrentValue, $this->mshitjeKrijuar->formatPattern());
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
        $this->mshitjeID->setDbValue($row['mshitjeID']);
        $this->mshitjeMarka->setDbValue($row['mshitjeMarka']);
        $this->mshitjeModeli->setDbValue($row['mshitjeModeli']);
        $this->mshitjeTipi->setDbValue($row['mshitjeTipi']);
        $this->mshitjeStruktura->setDbValue($row['mshitjeStruktura']);
        $this->mshitjeKapacitetiMotorrit->setDbValue($row['mshitjeKapacitetiMotorrit']);
        $this->mshitjeVitiProdhimit->setDbValue($row['mshitjeVitiProdhimit']);
        $this->mshitjeKarburant->setDbValue($row['mshitjeKarburant']);
        $this->mshitjeNgjyra->setDbValue($row['mshitjeNgjyra']);
        $this->mshitjeNrVendeve->setDbValue($row['mshitjeNrVendeve']);
        $this->mshitjeKambio->setDbValue($row['mshitjeKambio']);
        $this->mshitjePrejardhja->setDbValue($row['mshitjePrejardhja']);
        $this->mshitjeTargaAL->setDbValue($row['mshitjeTargaAL']);
        $this->mshitjeKilometra->setDbValue($row['mshitjeKilometra']);
        $this->mshitjeFotografi->Upload->DbValue = $row['mshitjeFotografi'];
        $this->mshitjeFotografi->setDbValue($this->mshitjeFotografi->Upload->DbValue);
        $this->mshitjePershkrimi->setDbValue($row['mshitjePershkrimi']);
        $this->mshitjeCmimi->setDbValue($row['mshitjeCmimi']);
        $this->mshitjeIndex->setDbValue($row['mshitjeIndex']);
        $this->mshitjePromo->setDbValue($row['mshitjePromo']);
        $this->mshitjeAktiv->setDbValue($row['mshitjeAktiv']);
        $this->mshitjeShitur->setDbValue($row['mshitjeShitur']);
        $this->mshitjeAutori->setDbValue($row['mshitjeAutori']);
        $this->mshitjeKrijuar->setDbValue($row['mshitjeKrijuar']);
        $this->mshitjeAzhornuar->setDbValue($row['mshitjeAzhornuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['mshitjeID'] = $this->mshitjeID->DefaultValue;
        $row['mshitjeMarka'] = $this->mshitjeMarka->DefaultValue;
        $row['mshitjeModeli'] = $this->mshitjeModeli->DefaultValue;
        $row['mshitjeTipi'] = $this->mshitjeTipi->DefaultValue;
        $row['mshitjeStruktura'] = $this->mshitjeStruktura->DefaultValue;
        $row['mshitjeKapacitetiMotorrit'] = $this->mshitjeKapacitetiMotorrit->DefaultValue;
        $row['mshitjeVitiProdhimit'] = $this->mshitjeVitiProdhimit->DefaultValue;
        $row['mshitjeKarburant'] = $this->mshitjeKarburant->DefaultValue;
        $row['mshitjeNgjyra'] = $this->mshitjeNgjyra->DefaultValue;
        $row['mshitjeNrVendeve'] = $this->mshitjeNrVendeve->DefaultValue;
        $row['mshitjeKambio'] = $this->mshitjeKambio->DefaultValue;
        $row['mshitjePrejardhja'] = $this->mshitjePrejardhja->DefaultValue;
        $row['mshitjeTargaAL'] = $this->mshitjeTargaAL->DefaultValue;
        $row['mshitjeKilometra'] = $this->mshitjeKilometra->DefaultValue;
        $row['mshitjeFotografi'] = $this->mshitjeFotografi->DefaultValue;
        $row['mshitjePershkrimi'] = $this->mshitjePershkrimi->DefaultValue;
        $row['mshitjeCmimi'] = $this->mshitjeCmimi->DefaultValue;
        $row['mshitjeIndex'] = $this->mshitjeIndex->DefaultValue;
        $row['mshitjePromo'] = $this->mshitjePromo->DefaultValue;
        $row['mshitjeAktiv'] = $this->mshitjeAktiv->DefaultValue;
        $row['mshitjeShitur'] = $this->mshitjeShitur->DefaultValue;
        $row['mshitjeAutori'] = $this->mshitjeAutori->DefaultValue;
        $row['mshitjeKrijuar'] = $this->mshitjeKrijuar->DefaultValue;
        $row['mshitjeAzhornuar'] = $this->mshitjeAzhornuar->DefaultValue;
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

        // mshitjeID
        $this->mshitjeID->RowCssClass = "row";

        // mshitjeMarka
        $this->mshitjeMarka->RowCssClass = "row";

        // mshitjeModeli
        $this->mshitjeModeli->RowCssClass = "row";

        // mshitjeTipi
        $this->mshitjeTipi->RowCssClass = "row";

        // mshitjeStruktura
        $this->mshitjeStruktura->RowCssClass = "row";

        // mshitjeKapacitetiMotorrit
        $this->mshitjeKapacitetiMotorrit->RowCssClass = "row";

        // mshitjeVitiProdhimit
        $this->mshitjeVitiProdhimit->RowCssClass = "row";

        // mshitjeKarburant
        $this->mshitjeKarburant->RowCssClass = "row";

        // mshitjeNgjyra
        $this->mshitjeNgjyra->RowCssClass = "row";

        // mshitjeNrVendeve
        $this->mshitjeNrVendeve->RowCssClass = "row";

        // mshitjeKambio
        $this->mshitjeKambio->RowCssClass = "row";

        // mshitjePrejardhja
        $this->mshitjePrejardhja->RowCssClass = "row";

        // mshitjeTargaAL
        $this->mshitjeTargaAL->RowCssClass = "row";

        // mshitjeKilometra
        $this->mshitjeKilometra->RowCssClass = "row";

        // mshitjeFotografi
        $this->mshitjeFotografi->RowCssClass = "row";

        // mshitjePershkrimi
        $this->mshitjePershkrimi->RowCssClass = "row";

        // mshitjeCmimi
        $this->mshitjeCmimi->RowCssClass = "row";

        // mshitjeIndex
        $this->mshitjeIndex->RowCssClass = "row";

        // mshitjePromo
        $this->mshitjePromo->RowCssClass = "row";

        // mshitjeAktiv
        $this->mshitjeAktiv->RowCssClass = "row";

        // mshitjeShitur
        $this->mshitjeShitur->RowCssClass = "row";

        // mshitjeAutori
        $this->mshitjeAutori->RowCssClass = "row";

        // mshitjeKrijuar
        $this->mshitjeKrijuar->RowCssClass = "row";

        // mshitjeAzhornuar
        $this->mshitjeAzhornuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // mshitjeID
            $this->mshitjeID->ViewValue = $this->mshitjeID->CurrentValue;
            $this->mshitjeID->ViewCustomAttributes = "";

            // mshitjeMarka
            $curVal = strval($this->mshitjeMarka->CurrentValue);
            if ($curVal != "") {
                $this->mshitjeMarka->ViewValue = $this->mshitjeMarka->lookupCacheOption($curVal);
                if ($this->mshitjeMarka->ViewValue === null) { // Lookup from database
                    $filterWrk = "`mmarkaID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->mshitjeMarka->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->mshitjeMarka->Lookup->renderViewRow($rswrk[0]);
                        $this->mshitjeMarka->ViewValue = $this->mshitjeMarka->displayValue($arwrk);
                    } else {
                        $this->mshitjeMarka->ViewValue = FormatNumber($this->mshitjeMarka->CurrentValue, $this->mshitjeMarka->formatPattern());
                    }
                }
            } else {
                $this->mshitjeMarka->ViewValue = null;
            }
            $this->mshitjeMarka->CssClass = "fw-bold";
            $this->mshitjeMarka->ViewCustomAttributes = "";

            // mshitjeModeli
            $curVal = strval($this->mshitjeModeli->CurrentValue);
            if ($curVal != "") {
                $this->mshitjeModeli->ViewValue = $this->mshitjeModeli->lookupCacheOption($curVal);
                if ($this->mshitjeModeli->ViewValue === null) { // Lookup from database
                    $filterWrk = "`mmodeliID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->mshitjeModeli->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->mshitjeModeli->Lookup->renderViewRow($rswrk[0]);
                        $this->mshitjeModeli->ViewValue = $this->mshitjeModeli->displayValue($arwrk);
                    } else {
                        $this->mshitjeModeli->ViewValue = FormatNumber($this->mshitjeModeli->CurrentValue, $this->mshitjeModeli->formatPattern());
                    }
                }
            } else {
                $this->mshitjeModeli->ViewValue = null;
            }
            $this->mshitjeModeli->CssClass = "fw-bold";
            $this->mshitjeModeli->ViewCustomAttributes = "";

            // mshitjeTipi
            $curVal = strval($this->mshitjeTipi->CurrentValue);
            if ($curVal != "") {
                $this->mshitjeTipi->ViewValue = $this->mshitjeTipi->lookupCacheOption($curVal);
                if ($this->mshitjeTipi->ViewValue === null) { // Lookup from database
                    $filterWrk = "`mtipiID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->mshitjeTipi->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->mshitjeTipi->Lookup->renderViewRow($rswrk[0]);
                        $this->mshitjeTipi->ViewValue = $this->mshitjeTipi->displayValue($arwrk);
                    } else {
                        $this->mshitjeTipi->ViewValue = FormatNumber($this->mshitjeTipi->CurrentValue, $this->mshitjeTipi->formatPattern());
                    }
                }
            } else {
                $this->mshitjeTipi->ViewValue = null;
            }
            $this->mshitjeTipi->CssClass = "fw-bold";
            $this->mshitjeTipi->ViewCustomAttributes = "";

            // mshitjeStruktura
            if (strval($this->mshitjeStruktura->CurrentValue) != "") {
                $this->mshitjeStruktura->ViewValue = $this->mshitjeStruktura->optionCaption($this->mshitjeStruktura->CurrentValue);
            } else {
                $this->mshitjeStruktura->ViewValue = null;
            }
            $this->mshitjeStruktura->ViewCustomAttributes = "";

            // mshitjeKapacitetiMotorrit
            $this->mshitjeKapacitetiMotorrit->ViewValue = $this->mshitjeKapacitetiMotorrit->CurrentValue;
            $this->mshitjeKapacitetiMotorrit->ViewCustomAttributes = "";

            // mshitjeVitiProdhimit
            $this->mshitjeVitiProdhimit->ViewValue = $this->mshitjeVitiProdhimit->CurrentValue;
            $this->mshitjeVitiProdhimit->ViewValue = FormatNumber($this->mshitjeVitiProdhimit->ViewValue, $this->mshitjeVitiProdhimit->formatPattern());
            $this->mshitjeVitiProdhimit->ViewCustomAttributes = "";

            // mshitjeKarburant
            if (strval($this->mshitjeKarburant->CurrentValue) != "") {
                $this->mshitjeKarburant->ViewValue = $this->mshitjeKarburant->optionCaption($this->mshitjeKarburant->CurrentValue);
            } else {
                $this->mshitjeKarburant->ViewValue = null;
            }
            $this->mshitjeKarburant->ViewCustomAttributes = "";

            // mshitjeNgjyra
            $this->mshitjeNgjyra->ViewValue = $this->mshitjeNgjyra->CurrentValue;
            $this->mshitjeNgjyra->ViewCustomAttributes = "";

            // mshitjeNrVendeve
            $this->mshitjeNrVendeve->ViewValue = $this->mshitjeNrVendeve->CurrentValue;
            $this->mshitjeNrVendeve->ViewCustomAttributes = "";

            // mshitjeKambio
            if (strval($this->mshitjeKambio->CurrentValue) != "") {
                $this->mshitjeKambio->ViewValue = $this->mshitjeKambio->optionCaption($this->mshitjeKambio->CurrentValue);
            } else {
                $this->mshitjeKambio->ViewValue = null;
            }
            $this->mshitjeKambio->ViewCustomAttributes = "";

            // mshitjePrejardhja
            $this->mshitjePrejardhja->ViewValue = $this->mshitjePrejardhja->CurrentValue;
            $this->mshitjePrejardhja->ViewCustomAttributes = "";

            // mshitjeTargaAL
            if (strval($this->mshitjeTargaAL->CurrentValue) != "") {
                $this->mshitjeTargaAL->ViewValue = $this->mshitjeTargaAL->optionCaption($this->mshitjeTargaAL->CurrentValue);
            } else {
                $this->mshitjeTargaAL->ViewValue = null;
            }
            $this->mshitjeTargaAL->ViewCustomAttributes = "";

            // mshitjeKilometra
            $this->mshitjeKilometra->ViewValue = $this->mshitjeKilometra->CurrentValue;
            $this->mshitjeKilometra->ViewValue = FormatNumber($this->mshitjeKilometra->ViewValue, $this->mshitjeKilometra->formatPattern());
            $this->mshitjeKilometra->ViewCustomAttributes = "";

            // mshitjeFotografi
            $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
            if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
                $this->mshitjeFotografi->ImageWidth = 100;
                $this->mshitjeFotografi->ImageHeight = 0;
                $this->mshitjeFotografi->ImageAlt = $this->mshitjeFotografi->alt();
                $this->mshitjeFotografi->ImageCssClass = "ew-image";
                $this->mshitjeFotografi->ViewValue = $this->mshitjeFotografi->Upload->DbValue;
            } else {
                $this->mshitjeFotografi->ViewValue = "";
            }
            $this->mshitjeFotografi->ViewCustomAttributes = "";

            // mshitjePershkrimi
            $this->mshitjePershkrimi->ViewValue = $this->mshitjePershkrimi->CurrentValue;
            $this->mshitjePershkrimi->ViewCustomAttributes = "";

            // mshitjeCmimi
            $this->mshitjeCmimi->ViewValue = $this->mshitjeCmimi->CurrentValue;
            $this->mshitjeCmimi->CssClass = "fw-bold";
            $this->mshitjeCmimi->ViewCustomAttributes = "";

            // mshitjeIndex
            if (strval($this->mshitjeIndex->CurrentValue) != "") {
                $this->mshitjeIndex->ViewValue = $this->mshitjeIndex->optionCaption($this->mshitjeIndex->CurrentValue);
            } else {
                $this->mshitjeIndex->ViewValue = null;
            }
            $this->mshitjeIndex->ViewCustomAttributes = "";

            // mshitjePromo
            if (strval($this->mshitjePromo->CurrentValue) != "") {
                $this->mshitjePromo->ViewValue = $this->mshitjePromo->optionCaption($this->mshitjePromo->CurrentValue);
            } else {
                $this->mshitjePromo->ViewValue = null;
            }
            $this->mshitjePromo->ViewCustomAttributes = "";

            // mshitjeAktiv
            if (strval($this->mshitjeAktiv->CurrentValue) != "") {
                $this->mshitjeAktiv->ViewValue = $this->mshitjeAktiv->optionCaption($this->mshitjeAktiv->CurrentValue);
            } else {
                $this->mshitjeAktiv->ViewValue = null;
            }
            $this->mshitjeAktiv->ViewCustomAttributes = "";

            // mshitjeShitur
            if (strval($this->mshitjeShitur->CurrentValue) != "") {
                $this->mshitjeShitur->ViewValue = $this->mshitjeShitur->optionCaption($this->mshitjeShitur->CurrentValue);
            } else {
                $this->mshitjeShitur->ViewValue = null;
            }
            $this->mshitjeShitur->ViewCustomAttributes = "";

            // mshitjeAutori
            $this->mshitjeAutori->ViewValue = $this->mshitjeAutori->CurrentValue;
            $curVal = strval($this->mshitjeAutori->CurrentValue);
            if ($curVal != "") {
                $this->mshitjeAutori->ViewValue = $this->mshitjeAutori->lookupCacheOption($curVal);
                if ($this->mshitjeAutori->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->mshitjeAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->mshitjeAutori->Lookup->renderViewRow($rswrk[0]);
                        $this->mshitjeAutori->ViewValue = $this->mshitjeAutori->displayValue($arwrk);
                    } else {
                        $this->mshitjeAutori->ViewValue = FormatNumber($this->mshitjeAutori->CurrentValue, $this->mshitjeAutori->formatPattern());
                    }
                }
            } else {
                $this->mshitjeAutori->ViewValue = null;
            }
            $this->mshitjeAutori->ViewCustomAttributes = "";

            // mshitjeKrijuar
            $this->mshitjeKrijuar->ViewValue = $this->mshitjeKrijuar->CurrentValue;
            $this->mshitjeKrijuar->ViewValue = FormatDateTime($this->mshitjeKrijuar->ViewValue, $this->mshitjeKrijuar->formatPattern());
            $this->mshitjeKrijuar->ViewCustomAttributes = "";

            // mshitjeAzhornuar
            $this->mshitjeAzhornuar->ViewValue = $this->mshitjeAzhornuar->CurrentValue;
            $this->mshitjeAzhornuar->ViewValue = FormatDateTime($this->mshitjeAzhornuar->ViewValue, $this->mshitjeAzhornuar->formatPattern());
            $this->mshitjeAzhornuar->ViewCustomAttributes = "";

            // mshitjeMarka
            $this->mshitjeMarka->LinkCustomAttributes = "";
            $this->mshitjeMarka->HrefValue = "";

            // mshitjeModeli
            $this->mshitjeModeli->LinkCustomAttributes = "";
            $this->mshitjeModeli->HrefValue = "";

            // mshitjeTipi
            $this->mshitjeTipi->LinkCustomAttributes = "";
            $this->mshitjeTipi->HrefValue = "";

            // mshitjeStruktura
            $this->mshitjeStruktura->LinkCustomAttributes = "";
            $this->mshitjeStruktura->HrefValue = "";

            // mshitjeKapacitetiMotorrit
            $this->mshitjeKapacitetiMotorrit->LinkCustomAttributes = "";
            $this->mshitjeKapacitetiMotorrit->HrefValue = "";

            // mshitjeVitiProdhimit
            $this->mshitjeVitiProdhimit->LinkCustomAttributes = "";
            $this->mshitjeVitiProdhimit->HrefValue = "";

            // mshitjeKarburant
            $this->mshitjeKarburant->LinkCustomAttributes = "";
            $this->mshitjeKarburant->HrefValue = "";

            // mshitjeNgjyra
            $this->mshitjeNgjyra->LinkCustomAttributes = "";
            $this->mshitjeNgjyra->HrefValue = "";

            // mshitjeNrVendeve
            $this->mshitjeNrVendeve->LinkCustomAttributes = "";
            $this->mshitjeNrVendeve->HrefValue = "";

            // mshitjeKambio
            $this->mshitjeKambio->LinkCustomAttributes = "";
            $this->mshitjeKambio->HrefValue = "";

            // mshitjePrejardhja
            $this->mshitjePrejardhja->LinkCustomAttributes = "";
            $this->mshitjePrejardhja->HrefValue = "";

            // mshitjeTargaAL
            $this->mshitjeTargaAL->LinkCustomAttributes = "";
            $this->mshitjeTargaAL->HrefValue = "";

            // mshitjeKilometra
            $this->mshitjeKilometra->LinkCustomAttributes = "";
            $this->mshitjeKilometra->HrefValue = "";

            // mshitjeFotografi
            $this->mshitjeFotografi->LinkCustomAttributes = "";
            $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
            if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
                $this->mshitjeFotografi->HrefValue = "%u"; // Add prefix/suffix
                $this->mshitjeFotografi->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->mshitjeFotografi->HrefValue = FullUrl($this->mshitjeFotografi->HrefValue, "href");
                }
            } else {
                $this->mshitjeFotografi->HrefValue = "";
            }
            $this->mshitjeFotografi->ExportHrefValue = $this->mshitjeFotografi->UploadPath . $this->mshitjeFotografi->Upload->DbValue;

            // mshitjePershkrimi
            $this->mshitjePershkrimi->LinkCustomAttributes = "";
            $this->mshitjePershkrimi->HrefValue = "";

            // mshitjeCmimi
            $this->mshitjeCmimi->LinkCustomAttributes = "";
            $this->mshitjeCmimi->HrefValue = "";

            // mshitjeIndex
            $this->mshitjeIndex->LinkCustomAttributes = "";
            $this->mshitjeIndex->HrefValue = "";

            // mshitjePromo
            $this->mshitjePromo->LinkCustomAttributes = "";
            $this->mshitjePromo->HrefValue = "";

            // mshitjeAktiv
            $this->mshitjeAktiv->LinkCustomAttributes = "";
            $this->mshitjeAktiv->HrefValue = "";

            // mshitjeShitur
            $this->mshitjeShitur->LinkCustomAttributes = "";
            $this->mshitjeShitur->HrefValue = "";

            // mshitjeAutori
            $this->mshitjeAutori->LinkCustomAttributes = "";
            $this->mshitjeAutori->HrefValue = "";

            // mshitjeKrijuar
            $this->mshitjeKrijuar->LinkCustomAttributes = "";
            $this->mshitjeKrijuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // mshitjeMarka
            $this->mshitjeMarka->setupEditAttributes();
            $this->mshitjeMarka->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeMarka->CurrentValue));
            if ($curVal != "") {
                $this->mshitjeMarka->ViewValue = $this->mshitjeMarka->lookupCacheOption($curVal);
            } else {
                $this->mshitjeMarka->ViewValue = $this->mshitjeMarka->Lookup !== null && is_array($this->mshitjeMarka->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeMarka->ViewValue !== null) { // Load from cache
                $this->mshitjeMarka->EditValue = array_values($this->mshitjeMarka->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmarkaID`" . SearchString("=", $this->mshitjeMarka->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mshitjeMarka->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mshitjeMarka->EditValue = $arwrk;
            }
            $this->mshitjeMarka->PlaceHolder = RemoveHtml($this->mshitjeMarka->caption());

            // mshitjeModeli
            $this->mshitjeModeli->setupEditAttributes();
            $this->mshitjeModeli->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeModeli->CurrentValue));
            if ($curVal != "") {
                $this->mshitjeModeli->ViewValue = $this->mshitjeModeli->lookupCacheOption($curVal);
            } else {
                $this->mshitjeModeli->ViewValue = $this->mshitjeModeli->Lookup !== null && is_array($this->mshitjeModeli->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeModeli->ViewValue !== null) { // Load from cache
                $this->mshitjeModeli->EditValue = array_values($this->mshitjeModeli->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmodeliID`" . SearchString("=", $this->mshitjeModeli->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mshitjeModeli->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mshitjeModeli->EditValue = $arwrk;
            }
            $this->mshitjeModeli->PlaceHolder = RemoveHtml($this->mshitjeModeli->caption());

            // mshitjeTipi
            $this->mshitjeTipi->setupEditAttributes();
            $this->mshitjeTipi->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeTipi->CurrentValue));
            if ($curVal != "") {
                $this->mshitjeTipi->ViewValue = $this->mshitjeTipi->lookupCacheOption($curVal);
            } else {
                $this->mshitjeTipi->ViewValue = $this->mshitjeTipi->Lookup !== null && is_array($this->mshitjeTipi->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeTipi->ViewValue !== null) { // Load from cache
                $this->mshitjeTipi->EditValue = array_values($this->mshitjeTipi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mtipiID`" . SearchString("=", $this->mshitjeTipi->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mshitjeTipi->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mshitjeTipi->EditValue = $arwrk;
            }
            $this->mshitjeTipi->PlaceHolder = RemoveHtml($this->mshitjeTipi->caption());

            // mshitjeStruktura
            $this->mshitjeStruktura->EditCustomAttributes = "";
            $this->mshitjeStruktura->EditValue = $this->mshitjeStruktura->options(false);
            $this->mshitjeStruktura->PlaceHolder = RemoveHtml($this->mshitjeStruktura->caption());

            // mshitjeKapacitetiMotorrit
            $this->mshitjeKapacitetiMotorrit->setupEditAttributes();
            $this->mshitjeKapacitetiMotorrit->EditCustomAttributes = "";
            if (!$this->mshitjeKapacitetiMotorrit->Raw) {
                $this->mshitjeKapacitetiMotorrit->CurrentValue = HtmlDecode($this->mshitjeKapacitetiMotorrit->CurrentValue);
            }
            $this->mshitjeKapacitetiMotorrit->EditValue = HtmlEncode($this->mshitjeKapacitetiMotorrit->CurrentValue);
            $this->mshitjeKapacitetiMotorrit->PlaceHolder = RemoveHtml($this->mshitjeKapacitetiMotorrit->caption());

            // mshitjeVitiProdhimit
            $this->mshitjeVitiProdhimit->setupEditAttributes();
            $this->mshitjeVitiProdhimit->EditCustomAttributes = "";
            $this->mshitjeVitiProdhimit->EditValue = HtmlEncode($this->mshitjeVitiProdhimit->CurrentValue);
            $this->mshitjeVitiProdhimit->PlaceHolder = RemoveHtml($this->mshitjeVitiProdhimit->caption());
            if (strval($this->mshitjeVitiProdhimit->EditValue) != "" && is_numeric($this->mshitjeVitiProdhimit->EditValue)) {
                $this->mshitjeVitiProdhimit->EditValue = FormatNumber($this->mshitjeVitiProdhimit->EditValue, null);
            }

            // mshitjeKarburant
            $this->mshitjeKarburant->EditCustomAttributes = "";
            $this->mshitjeKarburant->EditValue = $this->mshitjeKarburant->options(false);
            $this->mshitjeKarburant->PlaceHolder = RemoveHtml($this->mshitjeKarburant->caption());

            // mshitjeNgjyra
            $this->mshitjeNgjyra->setupEditAttributes();
            $this->mshitjeNgjyra->EditCustomAttributes = "";
            if (!$this->mshitjeNgjyra->Raw) {
                $this->mshitjeNgjyra->CurrentValue = HtmlDecode($this->mshitjeNgjyra->CurrentValue);
            }
            $this->mshitjeNgjyra->EditValue = HtmlEncode($this->mshitjeNgjyra->CurrentValue);
            $this->mshitjeNgjyra->PlaceHolder = RemoveHtml($this->mshitjeNgjyra->caption());

            // mshitjeNrVendeve
            $this->mshitjeNrVendeve->setupEditAttributes();
            $this->mshitjeNrVendeve->EditCustomAttributes = "";
            if (!$this->mshitjeNrVendeve->Raw) {
                $this->mshitjeNrVendeve->CurrentValue = HtmlDecode($this->mshitjeNrVendeve->CurrentValue);
            }
            $this->mshitjeNrVendeve->EditValue = HtmlEncode($this->mshitjeNrVendeve->CurrentValue);
            $this->mshitjeNrVendeve->PlaceHolder = RemoveHtml($this->mshitjeNrVendeve->caption());

            // mshitjeKambio
            $this->mshitjeKambio->EditCustomAttributes = "";
            $this->mshitjeKambio->EditValue = $this->mshitjeKambio->options(false);
            $this->mshitjeKambio->PlaceHolder = RemoveHtml($this->mshitjeKambio->caption());

            // mshitjePrejardhja
            $this->mshitjePrejardhja->setupEditAttributes();
            $this->mshitjePrejardhja->EditCustomAttributes = "";
            if (!$this->mshitjePrejardhja->Raw) {
                $this->mshitjePrejardhja->CurrentValue = HtmlDecode($this->mshitjePrejardhja->CurrentValue);
            }
            $this->mshitjePrejardhja->EditValue = HtmlEncode($this->mshitjePrejardhja->CurrentValue);
            $this->mshitjePrejardhja->PlaceHolder = RemoveHtml($this->mshitjePrejardhja->caption());

            // mshitjeTargaAL
            $this->mshitjeTargaAL->EditCustomAttributes = "";
            $this->mshitjeTargaAL->EditValue = $this->mshitjeTargaAL->options(false);
            $this->mshitjeTargaAL->PlaceHolder = RemoveHtml($this->mshitjeTargaAL->caption());

            // mshitjeKilometra
            $this->mshitjeKilometra->setupEditAttributes();
            $this->mshitjeKilometra->EditCustomAttributes = "";
            $this->mshitjeKilometra->EditValue = HtmlEncode($this->mshitjeKilometra->CurrentValue);
            $this->mshitjeKilometra->PlaceHolder = RemoveHtml($this->mshitjeKilometra->caption());
            if (strval($this->mshitjeKilometra->EditValue) != "" && is_numeric($this->mshitjeKilometra->EditValue)) {
                $this->mshitjeKilometra->EditValue = FormatNumber($this->mshitjeKilometra->EditValue, null);
            }

            // mshitjeFotografi
            $this->mshitjeFotografi->setupEditAttributes();
            $this->mshitjeFotografi->EditCustomAttributes = "";
            $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
            if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
                $this->mshitjeFotografi->ImageWidth = 100;
                $this->mshitjeFotografi->ImageHeight = 0;
                $this->mshitjeFotografi->ImageAlt = $this->mshitjeFotografi->alt();
                $this->mshitjeFotografi->ImageCssClass = "ew-image";
                $this->mshitjeFotografi->EditValue = $this->mshitjeFotografi->Upload->DbValue;
            } else {
                $this->mshitjeFotografi->EditValue = "";
            }
            if (!EmptyValue($this->mshitjeFotografi->CurrentValue)) {
                $this->mshitjeFotografi->Upload->FileName = $this->mshitjeFotografi->CurrentValue;
            }
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->mshitjeFotografi);
            }

            // mshitjePershkrimi
            $this->mshitjePershkrimi->setupEditAttributes();
            $this->mshitjePershkrimi->EditCustomAttributes = "";
            $this->mshitjePershkrimi->EditValue = HtmlEncode($this->mshitjePershkrimi->CurrentValue);
            $this->mshitjePershkrimi->PlaceHolder = RemoveHtml($this->mshitjePershkrimi->caption());

            // mshitjeCmimi
            $this->mshitjeCmimi->setupEditAttributes();
            $this->mshitjeCmimi->EditCustomAttributes = "";
            if (!$this->mshitjeCmimi->Raw) {
                $this->mshitjeCmimi->CurrentValue = HtmlDecode($this->mshitjeCmimi->CurrentValue);
            }
            $this->mshitjeCmimi->EditValue = HtmlEncode($this->mshitjeCmimi->CurrentValue);
            $this->mshitjeCmimi->PlaceHolder = RemoveHtml($this->mshitjeCmimi->caption());

            // mshitjeIndex
            $this->mshitjeIndex->EditCustomAttributes = "";
            $this->mshitjeIndex->EditValue = $this->mshitjeIndex->options(false);
            $this->mshitjeIndex->PlaceHolder = RemoveHtml($this->mshitjeIndex->caption());

            // mshitjePromo
            $this->mshitjePromo->EditCustomAttributes = "";
            $this->mshitjePromo->EditValue = $this->mshitjePromo->options(false);
            $this->mshitjePromo->PlaceHolder = RemoveHtml($this->mshitjePromo->caption());

            // mshitjeAktiv
            $this->mshitjeAktiv->EditCustomAttributes = "";
            $this->mshitjeAktiv->EditValue = $this->mshitjeAktiv->options(false);
            $this->mshitjeAktiv->PlaceHolder = RemoveHtml($this->mshitjeAktiv->caption());

            // mshitjeShitur
            $this->mshitjeShitur->EditCustomAttributes = "";
            $this->mshitjeShitur->EditValue = $this->mshitjeShitur->options(false);
            $this->mshitjeShitur->PlaceHolder = RemoveHtml($this->mshitjeShitur->caption());

            // mshitjeAutori

            // mshitjeKrijuar

            // Add refer script

            // mshitjeMarka
            $this->mshitjeMarka->LinkCustomAttributes = "";
            $this->mshitjeMarka->HrefValue = "";

            // mshitjeModeli
            $this->mshitjeModeli->LinkCustomAttributes = "";
            $this->mshitjeModeli->HrefValue = "";

            // mshitjeTipi
            $this->mshitjeTipi->LinkCustomAttributes = "";
            $this->mshitjeTipi->HrefValue = "";

            // mshitjeStruktura
            $this->mshitjeStruktura->LinkCustomAttributes = "";
            $this->mshitjeStruktura->HrefValue = "";

            // mshitjeKapacitetiMotorrit
            $this->mshitjeKapacitetiMotorrit->LinkCustomAttributes = "";
            $this->mshitjeKapacitetiMotorrit->HrefValue = "";

            // mshitjeVitiProdhimit
            $this->mshitjeVitiProdhimit->LinkCustomAttributes = "";
            $this->mshitjeVitiProdhimit->HrefValue = "";

            // mshitjeKarburant
            $this->mshitjeKarburant->LinkCustomAttributes = "";
            $this->mshitjeKarburant->HrefValue = "";

            // mshitjeNgjyra
            $this->mshitjeNgjyra->LinkCustomAttributes = "";
            $this->mshitjeNgjyra->HrefValue = "";

            // mshitjeNrVendeve
            $this->mshitjeNrVendeve->LinkCustomAttributes = "";
            $this->mshitjeNrVendeve->HrefValue = "";

            // mshitjeKambio
            $this->mshitjeKambio->LinkCustomAttributes = "";
            $this->mshitjeKambio->HrefValue = "";

            // mshitjePrejardhja
            $this->mshitjePrejardhja->LinkCustomAttributes = "";
            $this->mshitjePrejardhja->HrefValue = "";

            // mshitjeTargaAL
            $this->mshitjeTargaAL->LinkCustomAttributes = "";
            $this->mshitjeTargaAL->HrefValue = "";

            // mshitjeKilometra
            $this->mshitjeKilometra->LinkCustomAttributes = "";
            $this->mshitjeKilometra->HrefValue = "";

            // mshitjeFotografi
            $this->mshitjeFotografi->LinkCustomAttributes = "";
            $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
            if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
                $this->mshitjeFotografi->HrefValue = "%u"; // Add prefix/suffix
                $this->mshitjeFotografi->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->mshitjeFotografi->HrefValue = FullUrl($this->mshitjeFotografi->HrefValue, "href");
                }
            } else {
                $this->mshitjeFotografi->HrefValue = "";
            }
            $this->mshitjeFotografi->ExportHrefValue = $this->mshitjeFotografi->UploadPath . $this->mshitjeFotografi->Upload->DbValue;

            // mshitjePershkrimi
            $this->mshitjePershkrimi->LinkCustomAttributes = "";
            $this->mshitjePershkrimi->HrefValue = "";

            // mshitjeCmimi
            $this->mshitjeCmimi->LinkCustomAttributes = "";
            $this->mshitjeCmimi->HrefValue = "";

            // mshitjeIndex
            $this->mshitjeIndex->LinkCustomAttributes = "";
            $this->mshitjeIndex->HrefValue = "";

            // mshitjePromo
            $this->mshitjePromo->LinkCustomAttributes = "";
            $this->mshitjePromo->HrefValue = "";

            // mshitjeAktiv
            $this->mshitjeAktiv->LinkCustomAttributes = "";
            $this->mshitjeAktiv->HrefValue = "";

            // mshitjeShitur
            $this->mshitjeShitur->LinkCustomAttributes = "";
            $this->mshitjeShitur->HrefValue = "";

            // mshitjeAutori
            $this->mshitjeAutori->LinkCustomAttributes = "";
            $this->mshitjeAutori->HrefValue = "";

            // mshitjeKrijuar
            $this->mshitjeKrijuar->LinkCustomAttributes = "";
            $this->mshitjeKrijuar->HrefValue = "";
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
        if ($this->mshitjeMarka->Required) {
            if (!$this->mshitjeMarka->IsDetailKey && EmptyValue($this->mshitjeMarka->FormValue)) {
                $this->mshitjeMarka->addErrorMessage(str_replace("%s", $this->mshitjeMarka->caption(), $this->mshitjeMarka->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeModeli->Required) {
            if (!$this->mshitjeModeli->IsDetailKey && EmptyValue($this->mshitjeModeli->FormValue)) {
                $this->mshitjeModeli->addErrorMessage(str_replace("%s", $this->mshitjeModeli->caption(), $this->mshitjeModeli->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeTipi->Required) {
            if (!$this->mshitjeTipi->IsDetailKey && EmptyValue($this->mshitjeTipi->FormValue)) {
                $this->mshitjeTipi->addErrorMessage(str_replace("%s", $this->mshitjeTipi->caption(), $this->mshitjeTipi->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeStruktura->Required) {
            if ($this->mshitjeStruktura->FormValue == "") {
                $this->mshitjeStruktura->addErrorMessage(str_replace("%s", $this->mshitjeStruktura->caption(), $this->mshitjeStruktura->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeKapacitetiMotorrit->Required) {
            if (!$this->mshitjeKapacitetiMotorrit->IsDetailKey && EmptyValue($this->mshitjeKapacitetiMotorrit->FormValue)) {
                $this->mshitjeKapacitetiMotorrit->addErrorMessage(str_replace("%s", $this->mshitjeKapacitetiMotorrit->caption(), $this->mshitjeKapacitetiMotorrit->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeVitiProdhimit->Required) {
            if (!$this->mshitjeVitiProdhimit->IsDetailKey && EmptyValue($this->mshitjeVitiProdhimit->FormValue)) {
                $this->mshitjeVitiProdhimit->addErrorMessage(str_replace("%s", $this->mshitjeVitiProdhimit->caption(), $this->mshitjeVitiProdhimit->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->mshitjeVitiProdhimit->FormValue)) {
            $this->mshitjeVitiProdhimit->addErrorMessage($this->mshitjeVitiProdhimit->getErrorMessage(false));
        }
        if ($this->mshitjeKarburant->Required) {
            if ($this->mshitjeKarburant->FormValue == "") {
                $this->mshitjeKarburant->addErrorMessage(str_replace("%s", $this->mshitjeKarburant->caption(), $this->mshitjeKarburant->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeNgjyra->Required) {
            if (!$this->mshitjeNgjyra->IsDetailKey && EmptyValue($this->mshitjeNgjyra->FormValue)) {
                $this->mshitjeNgjyra->addErrorMessage(str_replace("%s", $this->mshitjeNgjyra->caption(), $this->mshitjeNgjyra->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeNrVendeve->Required) {
            if (!$this->mshitjeNrVendeve->IsDetailKey && EmptyValue($this->mshitjeNrVendeve->FormValue)) {
                $this->mshitjeNrVendeve->addErrorMessage(str_replace("%s", $this->mshitjeNrVendeve->caption(), $this->mshitjeNrVendeve->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeKambio->Required) {
            if ($this->mshitjeKambio->FormValue == "") {
                $this->mshitjeKambio->addErrorMessage(str_replace("%s", $this->mshitjeKambio->caption(), $this->mshitjeKambio->RequiredErrorMessage));
            }
        }
        if ($this->mshitjePrejardhja->Required) {
            if (!$this->mshitjePrejardhja->IsDetailKey && EmptyValue($this->mshitjePrejardhja->FormValue)) {
                $this->mshitjePrejardhja->addErrorMessage(str_replace("%s", $this->mshitjePrejardhja->caption(), $this->mshitjePrejardhja->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeTargaAL->Required) {
            if ($this->mshitjeTargaAL->FormValue == "") {
                $this->mshitjeTargaAL->addErrorMessage(str_replace("%s", $this->mshitjeTargaAL->caption(), $this->mshitjeTargaAL->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeKilometra->Required) {
            if (!$this->mshitjeKilometra->IsDetailKey && EmptyValue($this->mshitjeKilometra->FormValue)) {
                $this->mshitjeKilometra->addErrorMessage(str_replace("%s", $this->mshitjeKilometra->caption(), $this->mshitjeKilometra->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->mshitjeKilometra->FormValue)) {
            $this->mshitjeKilometra->addErrorMessage($this->mshitjeKilometra->getErrorMessage(false));
        }
        if ($this->mshitjeFotografi->Required) {
            if ($this->mshitjeFotografi->Upload->FileName == "" && !$this->mshitjeFotografi->Upload->KeepFile) {
                $this->mshitjeFotografi->addErrorMessage(str_replace("%s", $this->mshitjeFotografi->caption(), $this->mshitjeFotografi->RequiredErrorMessage));
            }
        }
        if ($this->mshitjePershkrimi->Required) {
            if (!$this->mshitjePershkrimi->IsDetailKey && EmptyValue($this->mshitjePershkrimi->FormValue)) {
                $this->mshitjePershkrimi->addErrorMessage(str_replace("%s", $this->mshitjePershkrimi->caption(), $this->mshitjePershkrimi->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeCmimi->Required) {
            if (!$this->mshitjeCmimi->IsDetailKey && EmptyValue($this->mshitjeCmimi->FormValue)) {
                $this->mshitjeCmimi->addErrorMessage(str_replace("%s", $this->mshitjeCmimi->caption(), $this->mshitjeCmimi->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeIndex->Required) {
            if ($this->mshitjeIndex->FormValue == "") {
                $this->mshitjeIndex->addErrorMessage(str_replace("%s", $this->mshitjeIndex->caption(), $this->mshitjeIndex->RequiredErrorMessage));
            }
        }
        if ($this->mshitjePromo->Required) {
            if ($this->mshitjePromo->FormValue == "") {
                $this->mshitjePromo->addErrorMessage(str_replace("%s", $this->mshitjePromo->caption(), $this->mshitjePromo->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeAktiv->Required) {
            if ($this->mshitjeAktiv->FormValue == "") {
                $this->mshitjeAktiv->addErrorMessage(str_replace("%s", $this->mshitjeAktiv->caption(), $this->mshitjeAktiv->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeShitur->Required) {
            if ($this->mshitjeShitur->FormValue == "") {
                $this->mshitjeShitur->addErrorMessage(str_replace("%s", $this->mshitjeShitur->caption(), $this->mshitjeShitur->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeAutori->Required) {
            if (!$this->mshitjeAutori->IsDetailKey && EmptyValue($this->mshitjeAutori->FormValue)) {
                $this->mshitjeAutori->addErrorMessage(str_replace("%s", $this->mshitjeAutori->caption(), $this->mshitjeAutori->RequiredErrorMessage));
            }
        }
        if ($this->mshitjeKrijuar->Required) {
            if (!$this->mshitjeKrijuar->IsDetailKey && EmptyValue($this->mshitjeKrijuar->FormValue)) {
                $this->mshitjeKrijuar->addErrorMessage(str_replace("%s", $this->mshitjeKrijuar->caption(), $this->mshitjeKrijuar->RequiredErrorMessage));
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

        // mshitjeMarka
        $this->mshitjeMarka->setDbValueDef($rsnew, $this->mshitjeMarka->CurrentValue, 0, false);

        // mshitjeModeli
        $this->mshitjeModeli->setDbValueDef($rsnew, $this->mshitjeModeli->CurrentValue, 0, false);

        // mshitjeTipi
        $this->mshitjeTipi->setDbValueDef($rsnew, $this->mshitjeTipi->CurrentValue, 0, false);

        // mshitjeStruktura
        $this->mshitjeStruktura->setDbValueDef($rsnew, $this->mshitjeStruktura->CurrentValue, "", strval($this->mshitjeStruktura->CurrentValue ?? "") == "");

        // mshitjeKapacitetiMotorrit
        $this->mshitjeKapacitetiMotorrit->setDbValueDef($rsnew, $this->mshitjeKapacitetiMotorrit->CurrentValue, "", false);

        // mshitjeVitiProdhimit
        $this->mshitjeVitiProdhimit->setDbValueDef($rsnew, $this->mshitjeVitiProdhimit->CurrentValue, 0, false);

        // mshitjeKarburant
        $this->mshitjeKarburant->setDbValueDef($rsnew, $this->mshitjeKarburant->CurrentValue, "", strval($this->mshitjeKarburant->CurrentValue ?? "") == "");

        // mshitjeNgjyra
        $this->mshitjeNgjyra->setDbValueDef($rsnew, $this->mshitjeNgjyra->CurrentValue, "", false);

        // mshitjeNrVendeve
        $this->mshitjeNrVendeve->setDbValueDef($rsnew, $this->mshitjeNrVendeve->CurrentValue, "", false);

        // mshitjeKambio
        $this->mshitjeKambio->setDbValueDef($rsnew, $this->mshitjeKambio->CurrentValue, "", strval($this->mshitjeKambio->CurrentValue ?? "") == "");

        // mshitjePrejardhja
        $this->mshitjePrejardhja->setDbValueDef($rsnew, $this->mshitjePrejardhja->CurrentValue, "", false);

        // mshitjeTargaAL
        $this->mshitjeTargaAL->setDbValueDef($rsnew, $this->mshitjeTargaAL->CurrentValue, "", strval($this->mshitjeTargaAL->CurrentValue ?? "") == "");

        // mshitjeKilometra
        $this->mshitjeKilometra->setDbValueDef($rsnew, $this->mshitjeKilometra->CurrentValue, 0, false);

        // mshitjeFotografi
        if ($this->mshitjeFotografi->Visible && !$this->mshitjeFotografi->Upload->KeepFile) {
            $this->mshitjeFotografi->Upload->DbValue = ""; // No need to delete old file
            if ($this->mshitjeFotografi->Upload->FileName == "") {
                $rsnew['mshitjeFotografi'] = null;
            } else {
                $rsnew['mshitjeFotografi'] = $this->mshitjeFotografi->Upload->FileName;
            }
            $this->mshitjeFotografi->ImageWidth = 1000; // Resize width
            $this->mshitjeFotografi->ImageHeight = 671; // Resize height
        }

        // mshitjePershkrimi
        $this->mshitjePershkrimi->setDbValueDef($rsnew, $this->mshitjePershkrimi->CurrentValue, "", false);

        // mshitjeCmimi
        $this->mshitjeCmimi->setDbValueDef($rsnew, $this->mshitjeCmimi->CurrentValue, "", false);

        // mshitjeIndex
        $this->mshitjeIndex->setDbValueDef($rsnew, $this->mshitjeIndex->CurrentValue, "", strval($this->mshitjeIndex->CurrentValue ?? "") == "");

        // mshitjePromo
        $this->mshitjePromo->setDbValueDef($rsnew, $this->mshitjePromo->CurrentValue, "", strval($this->mshitjePromo->CurrentValue ?? "") == "");

        // mshitjeAktiv
        $this->mshitjeAktiv->setDbValueDef($rsnew, $this->mshitjeAktiv->CurrentValue, "", strval($this->mshitjeAktiv->CurrentValue ?? "") == "");

        // mshitjeShitur
        $this->mshitjeShitur->setDbValueDef($rsnew, $this->mshitjeShitur->CurrentValue, "", strval($this->mshitjeShitur->CurrentValue ?? "") == "");

        // mshitjeAutori
        $this->mshitjeAutori->CurrentValue = CurrentUserID();
        $this->mshitjeAutori->setDbValueDef($rsnew, $this->mshitjeAutori->CurrentValue, 0);

        // mshitjeKrijuar
        $this->mshitjeKrijuar->CurrentValue = CurrentDateTime();
        $this->mshitjeKrijuar->setDbValueDef($rsnew, $this->mshitjeKrijuar->CurrentValue, CurrentDate());
        if ($this->mshitjeFotografi->Visible && !$this->mshitjeFotografi->Upload->KeepFile) {
            $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
            $oldFiles = EmptyValue($this->mshitjeFotografi->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->mshitjeFotografi->htmlDecode(strval($this->mshitjeFotografi->Upload->DbValue)));
            if (!EmptyValue($this->mshitjeFotografi->Upload->FileName)) {
                $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), strval($this->mshitjeFotografi->Upload->FileName));
                $NewFileCount = count($newFiles);
                for ($i = 0; $i < $NewFileCount; $i++) {
                    if ($newFiles[$i] != "") {
                        $file = $newFiles[$i];
                        $tempPath = UploadTempPath($this->mshitjeFotografi, $this->mshitjeFotografi->Upload->Index);
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
                            $file1 = UniqueFilename($this->mshitjeFotografi->physicalUploadPath(), $file); // Get new file name
                            if ($file1 != $file) { // Rename temp file
                                while (file_exists($tempPath . $file1) || file_exists($this->mshitjeFotografi->physicalUploadPath() . $file1)) { // Make sure no file name clash
                                    $file1 = UniqueFilename([$this->mshitjeFotografi->physicalUploadPath(), $tempPath], $file1, true); // Use indexed name
                                }
                                rename($tempPath . $file, $tempPath . $file1);
                                $newFiles[$i] = $file1;
                            }
                        }
                    }
                }
                $this->mshitjeFotografi->Upload->DbValue = empty($oldFiles) ? "" : implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $oldFiles);
                $this->mshitjeFotografi->Upload->FileName = implode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $newFiles);
                $this->mshitjeFotografi->setDbValueDef($rsnew, $this->mshitjeFotografi->Upload->FileName, "", false);
            }
        }

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
            $this->mshitjeFotografi->OldUploadPath = '../ngarkime/makina/';
            $this->mshitjeFotografi->UploadPath = $this->mshitjeFotografi->OldUploadPath;
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
                if ($this->mshitjeFotografi->Visible && !$this->mshitjeFotografi->Upload->KeepFile) {
                    $oldFiles = EmptyValue($this->mshitjeFotografi->Upload->DbValue) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->mshitjeFotografi->htmlDecode(strval($this->mshitjeFotografi->Upload->DbValue)));
                    if (!EmptyValue($this->mshitjeFotografi->Upload->FileName)) {
                        $newFiles = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->mshitjeFotografi->Upload->FileName);
                        $newFiles2 = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $this->mshitjeFotografi->htmlDecode($rsnew['mshitjeFotografi']));
                        $newFileCount = count($newFiles);
                        for ($i = 0; $i < $newFileCount; $i++) {
                            if ($newFiles[$i] != "") {
                                $file = UploadTempPath($this->mshitjeFotografi, $this->mshitjeFotografi->Upload->Index) . $newFiles[$i];
                                if (file_exists($file)) {
                                    if (@$newFiles2[$i] != "") { // Use correct file name
                                        $newFiles[$i] = $newFiles2[$i];
                                    }
                                    if (!$this->mshitjeFotografi->Upload->ResizeAndSaveToFile($this->mshitjeFotografi->ImageWidth, $this->mshitjeFotografi->ImageHeight, 100, $newFiles[$i], true, $i)) {
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
                                @unlink($this->mshitjeFotografi->oldPhysicalUploadPath() . $oldFile);
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
            // mshitjeFotografi
            CleanUploadTempPath($this->mshitjeFotografi, $this->mshitjeFotografi->Upload->Index);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MakinaShitjeList"), "", $this->TableVar, true);
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
                case "x_mshitjeMarka":
                    break;
                case "x_mshitjeModeli":
                    break;
                case "x_mshitjeTipi":
                    break;
                case "x_mshitjeStruktura":
                    break;
                case "x_mshitjeKarburant":
                    break;
                case "x_mshitjeKambio":
                    break;
                case "x_mshitjeTargaAL":
                    break;
                case "x_mshitjeIndex":
                    break;
                case "x_mshitjePromo":
                    break;
                case "x_mshitjeAktiv":
                    break;
                case "x_mshitjeShitur":
                    break;
                case "x_mshitjeAutori":
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
