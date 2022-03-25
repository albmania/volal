<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MenuDytesoreEdit extends MenuDytesore
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'menu_dytesore';

    // Page object name
    public $PageObjName = "MenuDytesoreEdit";

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

        // Table object (menu_dytesore)
        if (!isset($GLOBALS["menu_dytesore"]) || get_class($GLOBALS["menu_dytesore"]) == PROJECT_NAMESPACE . "menu_dytesore") {
            $GLOBALS["menu_dytesore"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'menu_dytesore');
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
                $tbl = Container("menu_dytesore");
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
                    if ($pageName == "MenuDytesoreView") {
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
            $key .= @$ar['menudID'];
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
            $this->menudID->Visible = false;
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
        $this->menudID->setVisibility();
        $this->menudGjuha->setVisibility();
        $this->menudKryesore->setVisibility();
        $this->menudTitulli->setVisibility();
        $this->menudUrl->setVisibility();
        $this->menudBlank->setVisibility();
        $this->menudRadhe->setVisibility();
        $this->menudAktiv->setVisibility();
        $this->menudAutor->setVisibility();
        $this->menudKrijuar->Visible = false;
        $this->menudAzhornuar->setVisibility();
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
        $this->setupLookupOptions($this->menudGjuha);
        $this->setupLookupOptions($this->menudKryesore);
        $this->setupLookupOptions($this->menudBlank);
        $this->setupLookupOptions($this->menudAktiv);
        $this->setupLookupOptions($this->menudAutor);

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
            if (($keyValue = Get("menudID") ?? Key(0) ?? Route(2)) !== null) {
                $this->menudID->setQueryStringValue($keyValue);
                $this->menudID->setOldValue($this->menudID->QueryStringValue);
            } elseif (Post("menudID") !== null) {
                $this->menudID->setFormValue(Post("menudID"));
                $this->menudID->setOldValue($this->menudID->FormValue);
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
                if (($keyValue = Get("menudID") ?? Route("menudID")) !== null) {
                    $this->menudID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->menudID->CurrentValue = null;
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
                        $this->terminate("MenuDytesoreList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "MenuDytesoreList") {
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

        // Check field name 'menudID' first before field var 'x_menudID'
        $val = $CurrentForm->hasValue("menudID") ? $CurrentForm->getValue("menudID") : $CurrentForm->getValue("x_menudID");
        if (!$this->menudID->IsDetailKey) {
            $this->menudID->setFormValue($val);
        }

        // Check field name 'menudGjuha' first before field var 'x_menudGjuha'
        $val = $CurrentForm->hasValue("menudGjuha") ? $CurrentForm->getValue("menudGjuha") : $CurrentForm->getValue("x_menudGjuha");
        if (!$this->menudGjuha->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudGjuha->Visible = false; // Disable update for API request
            } else {
                $this->menudGjuha->setFormValue($val);
            }
        }

        // Check field name 'menudKryesore' first before field var 'x_menudKryesore'
        $val = $CurrentForm->hasValue("menudKryesore") ? $CurrentForm->getValue("menudKryesore") : $CurrentForm->getValue("x_menudKryesore");
        if (!$this->menudKryesore->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudKryesore->Visible = false; // Disable update for API request
            } else {
                $this->menudKryesore->setFormValue($val);
            }
        }

        // Check field name 'menudTitulli' first before field var 'x_menudTitulli'
        $val = $CurrentForm->hasValue("menudTitulli") ? $CurrentForm->getValue("menudTitulli") : $CurrentForm->getValue("x_menudTitulli");
        if (!$this->menudTitulli->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudTitulli->Visible = false; // Disable update for API request
            } else {
                $this->menudTitulli->setFormValue($val);
            }
        }

        // Check field name 'menudUrl' first before field var 'x_menudUrl'
        $val = $CurrentForm->hasValue("menudUrl") ? $CurrentForm->getValue("menudUrl") : $CurrentForm->getValue("x_menudUrl");
        if (!$this->menudUrl->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudUrl->Visible = false; // Disable update for API request
            } else {
                $this->menudUrl->setFormValue($val);
            }
        }

        // Check field name 'menudBlank' first before field var 'x_menudBlank'
        $val = $CurrentForm->hasValue("menudBlank") ? $CurrentForm->getValue("menudBlank") : $CurrentForm->getValue("x_menudBlank");
        if (!$this->menudBlank->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudBlank->Visible = false; // Disable update for API request
            } else {
                $this->menudBlank->setFormValue($val);
            }
        }

        // Check field name 'menudRadhe' first before field var 'x_menudRadhe'
        $val = $CurrentForm->hasValue("menudRadhe") ? $CurrentForm->getValue("menudRadhe") : $CurrentForm->getValue("x_menudRadhe");
        if (!$this->menudRadhe->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudRadhe->Visible = false; // Disable update for API request
            } else {
                $this->menudRadhe->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'menudAktiv' first before field var 'x_menudAktiv'
        $val = $CurrentForm->hasValue("menudAktiv") ? $CurrentForm->getValue("menudAktiv") : $CurrentForm->getValue("x_menudAktiv");
        if (!$this->menudAktiv->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudAktiv->Visible = false; // Disable update for API request
            } else {
                $this->menudAktiv->setFormValue($val);
            }
        }

        // Check field name 'menudAutor' first before field var 'x_menudAutor'
        $val = $CurrentForm->hasValue("menudAutor") ? $CurrentForm->getValue("menudAutor") : $CurrentForm->getValue("x_menudAutor");
        if (!$this->menudAutor->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudAutor->Visible = false; // Disable update for API request
            } else {
                $this->menudAutor->setFormValue($val);
            }
        }

        // Check field name 'menudAzhornuar' first before field var 'x_menudAzhornuar'
        $val = $CurrentForm->hasValue("menudAzhornuar") ? $CurrentForm->getValue("menudAzhornuar") : $CurrentForm->getValue("x_menudAzhornuar");
        if (!$this->menudAzhornuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->menudAzhornuar->Visible = false; // Disable update for API request
            } else {
                $this->menudAzhornuar->setFormValue($val);
            }
            $this->menudAzhornuar->CurrentValue = UnFormatDateTime($this->menudAzhornuar->CurrentValue, $this->menudAzhornuar->formatPattern());
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->menudID->CurrentValue = $this->menudID->FormValue;
        $this->menudGjuha->CurrentValue = $this->menudGjuha->FormValue;
        $this->menudKryesore->CurrentValue = $this->menudKryesore->FormValue;
        $this->menudTitulli->CurrentValue = $this->menudTitulli->FormValue;
        $this->menudUrl->CurrentValue = $this->menudUrl->FormValue;
        $this->menudBlank->CurrentValue = $this->menudBlank->FormValue;
        $this->menudRadhe->CurrentValue = $this->menudRadhe->FormValue;
        $this->menudAktiv->CurrentValue = $this->menudAktiv->FormValue;
        $this->menudAutor->CurrentValue = $this->menudAutor->FormValue;
        $this->menudAzhornuar->CurrentValue = $this->menudAzhornuar->FormValue;
        $this->menudAzhornuar->CurrentValue = UnFormatDateTime($this->menudAzhornuar->CurrentValue, $this->menudAzhornuar->formatPattern());
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
        $this->menudID->setDbValue($row['menudID']);
        $this->menudGjuha->setDbValue($row['menudGjuha']);
        $this->menudKryesore->setDbValue($row['menudKryesore']);
        if (array_key_exists('EV__menudKryesore', $row)) {
            $this->menudKryesore->VirtualValue = $row['EV__menudKryesore']; // Set up virtual field value
        } else {
            $this->menudKryesore->VirtualValue = ""; // Clear value
        }
        $this->menudTitulli->setDbValue($row['menudTitulli']);
        $this->menudUrl->setDbValue($row['menudUrl']);
        $this->menudBlank->setDbValue($row['menudBlank']);
        $this->menudRadhe->setDbValue($row['menudRadhe']);
        $this->menudAktiv->setDbValue($row['menudAktiv']);
        $this->menudAutor->setDbValue($row['menudAutor']);
        $this->menudKrijuar->setDbValue($row['menudKrijuar']);
        $this->menudAzhornuar->setDbValue($row['menudAzhornuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['menudID'] = $this->menudID->DefaultValue;
        $row['menudGjuha'] = $this->menudGjuha->DefaultValue;
        $row['menudKryesore'] = $this->menudKryesore->DefaultValue;
        $row['menudTitulli'] = $this->menudTitulli->DefaultValue;
        $row['menudUrl'] = $this->menudUrl->DefaultValue;
        $row['menudBlank'] = $this->menudBlank->DefaultValue;
        $row['menudRadhe'] = $this->menudRadhe->DefaultValue;
        $row['menudAktiv'] = $this->menudAktiv->DefaultValue;
        $row['menudAutor'] = $this->menudAutor->DefaultValue;
        $row['menudKrijuar'] = $this->menudKrijuar->DefaultValue;
        $row['menudAzhornuar'] = $this->menudAzhornuar->DefaultValue;
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

        // menudID
        $this->menudID->RowCssClass = "row";

        // menudGjuha
        $this->menudGjuha->RowCssClass = "row";

        // menudKryesore
        $this->menudKryesore->RowCssClass = "row";

        // menudTitulli
        $this->menudTitulli->RowCssClass = "row";

        // menudUrl
        $this->menudUrl->RowCssClass = "row";

        // menudBlank
        $this->menudBlank->RowCssClass = "row";

        // menudRadhe
        $this->menudRadhe->RowCssClass = "row";

        // menudAktiv
        $this->menudAktiv->RowCssClass = "row";

        // menudAutor
        $this->menudAutor->RowCssClass = "row";

        // menudKrijuar
        $this->menudKrijuar->RowCssClass = "row";

        // menudAzhornuar
        $this->menudAzhornuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // menudID
            $this->menudID->ViewValue = $this->menudID->CurrentValue;
            $this->menudID->ViewCustomAttributes = "";

            // menudGjuha
            if (strval($this->menudGjuha->CurrentValue) != "") {
                $this->menudGjuha->ViewValue = $this->menudGjuha->optionCaption($this->menudGjuha->CurrentValue);
            } else {
                $this->menudGjuha->ViewValue = null;
            }
            $this->menudGjuha->ViewCustomAttributes = "";

            // menudKryesore
            if ($this->menudKryesore->VirtualValue != "") {
                $this->menudKryesore->ViewValue = $this->menudKryesore->VirtualValue;
            } else {
                $curVal = strval($this->menudKryesore->CurrentValue);
                if ($curVal != "") {
                    $this->menudKryesore->ViewValue = $this->menudKryesore->lookupCacheOption($curVal);
                    if ($this->menudKryesore->ViewValue === null) { // Lookup from database
                        $filterWrk = "`menukID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->menudKryesore->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->menudKryesore->Lookup->renderViewRow($rswrk[0]);
                            $this->menudKryesore->ViewValue = $this->menudKryesore->displayValue($arwrk);
                        } else {
                            $this->menudKryesore->ViewValue = FormatNumber($this->menudKryesore->CurrentValue, $this->menudKryesore->formatPattern());
                        }
                    }
                } else {
                    $this->menudKryesore->ViewValue = null;
                }
            }
            $this->menudKryesore->ViewCustomAttributes = "";

            // menudTitulli
            $this->menudTitulli->ViewValue = $this->menudTitulli->CurrentValue;
            $this->menudTitulli->ViewCustomAttributes = "";

            // menudUrl
            $this->menudUrl->ViewValue = $this->menudUrl->CurrentValue;
            $this->menudUrl->ViewCustomAttributes = "";

            // menudBlank
            if (strval($this->menudBlank->CurrentValue) != "") {
                $this->menudBlank->ViewValue = $this->menudBlank->optionCaption($this->menudBlank->CurrentValue);
            } else {
                $this->menudBlank->ViewValue = null;
            }
            $this->menudBlank->ViewCustomAttributes = "";

            // menudRadhe
            $this->menudRadhe->ViewValue = $this->menudRadhe->CurrentValue;
            $this->menudRadhe->ViewValue = FormatNumber($this->menudRadhe->ViewValue, $this->menudRadhe->formatPattern());
            $this->menudRadhe->ViewCustomAttributes = "";

            // menudAktiv
            if (strval($this->menudAktiv->CurrentValue) != "") {
                $this->menudAktiv->ViewValue = $this->menudAktiv->optionCaption($this->menudAktiv->CurrentValue);
            } else {
                $this->menudAktiv->ViewValue = null;
            }
            $this->menudAktiv->ViewCustomAttributes = "";

            // menudAutor
            $this->menudAutor->ViewValue = $this->menudAutor->CurrentValue;
            $curVal = strval($this->menudAutor->CurrentValue);
            if ($curVal != "") {
                $this->menudAutor->ViewValue = $this->menudAutor->lookupCacheOption($curVal);
                if ($this->menudAutor->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->menudAutor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->menudAutor->Lookup->renderViewRow($rswrk[0]);
                        $this->menudAutor->ViewValue = $this->menudAutor->displayValue($arwrk);
                    } else {
                        $this->menudAutor->ViewValue = FormatNumber($this->menudAutor->CurrentValue, $this->menudAutor->formatPattern());
                    }
                }
            } else {
                $this->menudAutor->ViewValue = null;
            }
            $this->menudAutor->ViewCustomAttributes = "";

            // menudKrijuar
            $this->menudKrijuar->ViewValue = $this->menudKrijuar->CurrentValue;
            $this->menudKrijuar->ViewValue = FormatDateTime($this->menudKrijuar->ViewValue, $this->menudKrijuar->formatPattern());
            $this->menudKrijuar->ViewCustomAttributes = "";

            // menudAzhornuar
            $this->menudAzhornuar->ViewValue = $this->menudAzhornuar->CurrentValue;
            $this->menudAzhornuar->ViewValue = FormatDateTime($this->menudAzhornuar->ViewValue, $this->menudAzhornuar->formatPattern());
            $this->menudAzhornuar->ViewCustomAttributes = "";

            // menudID
            $this->menudID->LinkCustomAttributes = "";
            $this->menudID->HrefValue = "";

            // menudGjuha
            $this->menudGjuha->LinkCustomAttributes = "";
            $this->menudGjuha->HrefValue = "";

            // menudKryesore
            $this->menudKryesore->LinkCustomAttributes = "";
            $this->menudKryesore->HrefValue = "";

            // menudTitulli
            $this->menudTitulli->LinkCustomAttributes = "";
            $this->menudTitulli->HrefValue = "";

            // menudUrl
            $this->menudUrl->LinkCustomAttributes = "";
            $this->menudUrl->HrefValue = "";

            // menudBlank
            $this->menudBlank->LinkCustomAttributes = "";
            $this->menudBlank->HrefValue = "";

            // menudRadhe
            $this->menudRadhe->LinkCustomAttributes = "";
            $this->menudRadhe->HrefValue = "";

            // menudAktiv
            $this->menudAktiv->LinkCustomAttributes = "";
            $this->menudAktiv->HrefValue = "";

            // menudAutor
            $this->menudAutor->LinkCustomAttributes = "";
            $this->menudAutor->HrefValue = "";

            // menudAzhornuar
            $this->menudAzhornuar->LinkCustomAttributes = "";
            $this->menudAzhornuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // menudID
            $this->menudID->setupEditAttributes();
            $this->menudID->EditCustomAttributes = "";
            $this->menudID->EditValue = $this->menudID->CurrentValue;
            $this->menudID->ViewCustomAttributes = "";

            // menudGjuha
            $this->menudGjuha->EditCustomAttributes = "";
            $this->menudGjuha->EditValue = $this->menudGjuha->options(false);
            $this->menudGjuha->PlaceHolder = RemoveHtml($this->menudGjuha->caption());

            // menudKryesore
            $this->menudKryesore->setupEditAttributes();
            $this->menudKryesore->EditCustomAttributes = "";
            $curVal = trim(strval($this->menudKryesore->CurrentValue));
            if ($curVal != "") {
                $this->menudKryesore->ViewValue = $this->menudKryesore->lookupCacheOption($curVal);
            } else {
                $this->menudKryesore->ViewValue = $this->menudKryesore->Lookup !== null && is_array($this->menudKryesore->lookupOptions()) ? $curVal : null;
            }
            if ($this->menudKryesore->ViewValue !== null) { // Load from cache
                $this->menudKryesore->EditValue = array_values($this->menudKryesore->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`menukID`" . SearchString("=", $this->menudKryesore->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->menudKryesore->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->menudKryesore->EditValue = $arwrk;
            }
            $this->menudKryesore->PlaceHolder = RemoveHtml($this->menudKryesore->caption());

            // menudTitulli
            $this->menudTitulli->setupEditAttributes();
            $this->menudTitulli->EditCustomAttributes = "";
            if (!$this->menudTitulli->Raw) {
                $this->menudTitulli->CurrentValue = HtmlDecode($this->menudTitulli->CurrentValue);
            }
            $this->menudTitulli->EditValue = HtmlEncode($this->menudTitulli->CurrentValue);
            $this->menudTitulli->PlaceHolder = RemoveHtml($this->menudTitulli->caption());

            // menudUrl
            $this->menudUrl->setupEditAttributes();
            $this->menudUrl->EditCustomAttributes = "";
            if (!$this->menudUrl->Raw) {
                $this->menudUrl->CurrentValue = HtmlDecode($this->menudUrl->CurrentValue);
            }
            $this->menudUrl->EditValue = HtmlEncode($this->menudUrl->CurrentValue);
            $this->menudUrl->PlaceHolder = RemoveHtml($this->menudUrl->caption());

            // menudBlank
            $this->menudBlank->EditCustomAttributes = "";
            $this->menudBlank->EditValue = $this->menudBlank->options(false);
            $this->menudBlank->PlaceHolder = RemoveHtml($this->menudBlank->caption());

            // menudRadhe
            $this->menudRadhe->setupEditAttributes();
            $this->menudRadhe->EditCustomAttributes = "";
            $this->menudRadhe->EditValue = HtmlEncode($this->menudRadhe->CurrentValue);
            $this->menudRadhe->PlaceHolder = RemoveHtml($this->menudRadhe->caption());
            if (strval($this->menudRadhe->EditValue) != "" && is_numeric($this->menudRadhe->EditValue)) {
                $this->menudRadhe->EditValue = FormatNumber($this->menudRadhe->EditValue, null);
            }

            // menudAktiv
            $this->menudAktiv->EditCustomAttributes = "";
            $this->menudAktiv->EditValue = $this->menudAktiv->options(false);
            $this->menudAktiv->PlaceHolder = RemoveHtml($this->menudAktiv->caption());

            // menudAutor

            // menudAzhornuar

            // Edit refer script

            // menudID
            $this->menudID->LinkCustomAttributes = "";
            $this->menudID->HrefValue = "";

            // menudGjuha
            $this->menudGjuha->LinkCustomAttributes = "";
            $this->menudGjuha->HrefValue = "";

            // menudKryesore
            $this->menudKryesore->LinkCustomAttributes = "";
            $this->menudKryesore->HrefValue = "";

            // menudTitulli
            $this->menudTitulli->LinkCustomAttributes = "";
            $this->menudTitulli->HrefValue = "";

            // menudUrl
            $this->menudUrl->LinkCustomAttributes = "";
            $this->menudUrl->HrefValue = "";

            // menudBlank
            $this->menudBlank->LinkCustomAttributes = "";
            $this->menudBlank->HrefValue = "";

            // menudRadhe
            $this->menudRadhe->LinkCustomAttributes = "";
            $this->menudRadhe->HrefValue = "";

            // menudAktiv
            $this->menudAktiv->LinkCustomAttributes = "";
            $this->menudAktiv->HrefValue = "";

            // menudAutor
            $this->menudAutor->LinkCustomAttributes = "";
            $this->menudAutor->HrefValue = "";

            // menudAzhornuar
            $this->menudAzhornuar->LinkCustomAttributes = "";
            $this->menudAzhornuar->HrefValue = "";
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
        if ($this->menudID->Required) {
            if (!$this->menudID->IsDetailKey && EmptyValue($this->menudID->FormValue)) {
                $this->menudID->addErrorMessage(str_replace("%s", $this->menudID->caption(), $this->menudID->RequiredErrorMessage));
            }
        }
        if ($this->menudGjuha->Required) {
            if ($this->menudGjuha->FormValue == "") {
                $this->menudGjuha->addErrorMessage(str_replace("%s", $this->menudGjuha->caption(), $this->menudGjuha->RequiredErrorMessage));
            }
        }
        if ($this->menudKryesore->Required) {
            if (!$this->menudKryesore->IsDetailKey && EmptyValue($this->menudKryesore->FormValue)) {
                $this->menudKryesore->addErrorMessage(str_replace("%s", $this->menudKryesore->caption(), $this->menudKryesore->RequiredErrorMessage));
            }
        }
        if ($this->menudTitulli->Required) {
            if (!$this->menudTitulli->IsDetailKey && EmptyValue($this->menudTitulli->FormValue)) {
                $this->menudTitulli->addErrorMessage(str_replace("%s", $this->menudTitulli->caption(), $this->menudTitulli->RequiredErrorMessage));
            }
        }
        if ($this->menudUrl->Required) {
            if (!$this->menudUrl->IsDetailKey && EmptyValue($this->menudUrl->FormValue)) {
                $this->menudUrl->addErrorMessage(str_replace("%s", $this->menudUrl->caption(), $this->menudUrl->RequiredErrorMessage));
            }
        }
        if ($this->menudBlank->Required) {
            if ($this->menudBlank->FormValue == "") {
                $this->menudBlank->addErrorMessage(str_replace("%s", $this->menudBlank->caption(), $this->menudBlank->RequiredErrorMessage));
            }
        }
        if ($this->menudRadhe->Required) {
            if (!$this->menudRadhe->IsDetailKey && EmptyValue($this->menudRadhe->FormValue)) {
                $this->menudRadhe->addErrorMessage(str_replace("%s", $this->menudRadhe->caption(), $this->menudRadhe->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->menudRadhe->FormValue)) {
            $this->menudRadhe->addErrorMessage($this->menudRadhe->getErrorMessage(false));
        }
        if ($this->menudAktiv->Required) {
            if ($this->menudAktiv->FormValue == "") {
                $this->menudAktiv->addErrorMessage(str_replace("%s", $this->menudAktiv->caption(), $this->menudAktiv->RequiredErrorMessage));
            }
        }
        if ($this->menudAutor->Required) {
            if (!$this->menudAutor->IsDetailKey && EmptyValue($this->menudAutor->FormValue)) {
                $this->menudAutor->addErrorMessage(str_replace("%s", $this->menudAutor->caption(), $this->menudAutor->RequiredErrorMessage));
            }
        }
        if ($this->menudAzhornuar->Required) {
            if (!$this->menudAzhornuar->IsDetailKey && EmptyValue($this->menudAzhornuar->FormValue)) {
                $this->menudAzhornuar->addErrorMessage(str_replace("%s", $this->menudAzhornuar->caption(), $this->menudAzhornuar->RequiredErrorMessage));
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

        // menudGjuha
        $this->menudGjuha->setDbValueDef($rsnew, $this->menudGjuha->CurrentValue, "", $this->menudGjuha->ReadOnly);

        // menudKryesore
        $this->menudKryesore->setDbValueDef($rsnew, $this->menudKryesore->CurrentValue, 0, $this->menudKryesore->ReadOnly);

        // menudTitulli
        $this->menudTitulli->setDbValueDef($rsnew, $this->menudTitulli->CurrentValue, "", $this->menudTitulli->ReadOnly);

        // menudUrl
        $this->menudUrl->setDbValueDef($rsnew, $this->menudUrl->CurrentValue, "", $this->menudUrl->ReadOnly);

        // menudBlank
        $this->menudBlank->setDbValueDef($rsnew, $this->menudBlank->CurrentValue, "", $this->menudBlank->ReadOnly);

        // menudRadhe
        $this->menudRadhe->setDbValueDef($rsnew, $this->menudRadhe->CurrentValue, 0, $this->menudRadhe->ReadOnly);

        // menudAktiv
        $this->menudAktiv->setDbValueDef($rsnew, $this->menudAktiv->CurrentValue, "", $this->menudAktiv->ReadOnly);

        // menudAutor
        $this->menudAutor->CurrentValue = CurrentUserID();
        $this->menudAutor->setDbValueDef($rsnew, $this->menudAutor->CurrentValue, 0);

        // menudAzhornuar
        $this->menudAzhornuar->CurrentValue = CurrentDateTime();
        $this->menudAzhornuar->setDbValueDef($rsnew, $this->menudAzhornuar->CurrentValue, null);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MenuDytesoreList"), "", $this->TableVar, true);
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
                case "x_menudGjuha":
                    break;
                case "x_menudKryesore":
                    break;
                case "x_menudBlank":
                    break;
                case "x_menudAktiv":
                    break;
                case "x_menudAutor":
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
