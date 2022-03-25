<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaEdit extends Makina
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina';

    // Page object name
    public $PageObjName = "MakinaEdit";

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

        // Table object (makina)
        if (!isset($GLOBALS["makina"]) || get_class($GLOBALS["makina"]) == PROJECT_NAMESPACE . "makina") {
            $GLOBALS["makina"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'makina');
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
                $tbl = Container("makina");
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
                    if ($pageName == "MakinaView") {
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
            $key .= @$ar['makinaID'];
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
            $this->makinaID->Visible = false;
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
        $this->makinaID->setVisibility();
        $this->makinaKlienti->setVisibility();
        $this->makinaMarka->setVisibility();
        $this->makinaModeli->setVisibility();
        $this->makinaKarburanti->setVisibility();
        $this->makinaMadhesiaMotorrit->setVisibility();
        $this->makinaVitiProdhimit->setVisibility();
        $this->makinaNgjyra->setVisibility();
        $this->makinaInfoShtese->setVisibility();
        $this->makinaVitiRegAL->setVisibility();
        $this->makinaTarga->setVisibility();
        $this->makinaNrShasie->setVisibility();
        $this->makinaPrejardhja->setVisibility();
        $this->makinaShiturVOLAL->setVisibility();
        $this->makinaAutori->setVisibility();
        $this->makinaShtuar->Visible = false;
        $this->makinaModifikuar->setVisibility();
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
        $this->setupLookupOptions($this->makinaKlienti);
        $this->setupLookupOptions($this->makinaKarburanti);
        $this->setupLookupOptions($this->makinaShiturVOLAL);

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
            if (($keyValue = Get("makinaID") ?? Key(0) ?? Route(2)) !== null) {
                $this->makinaID->setQueryStringValue($keyValue);
                $this->makinaID->setOldValue($this->makinaID->QueryStringValue);
            } elseif (Post("makinaID") !== null) {
                $this->makinaID->setFormValue(Post("makinaID"));
                $this->makinaID->setOldValue($this->makinaID->FormValue);
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
                if (($keyValue = Get("makinaID") ?? Route("makinaID")) !== null) {
                    $this->makinaID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->makinaID->CurrentValue = null;
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
                        $this->terminate("MakinaList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "MakinaList") {
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

        // Check field name 'makinaID' first before field var 'x_makinaID'
        $val = $CurrentForm->hasValue("makinaID") ? $CurrentForm->getValue("makinaID") : $CurrentForm->getValue("x_makinaID");
        if (!$this->makinaID->IsDetailKey) {
            $this->makinaID->setFormValue($val);
        }

        // Check field name 'makinaKlienti' first before field var 'x_makinaKlienti'
        $val = $CurrentForm->hasValue("makinaKlienti") ? $CurrentForm->getValue("makinaKlienti") : $CurrentForm->getValue("x_makinaKlienti");
        if (!$this->makinaKlienti->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaKlienti->Visible = false; // Disable update for API request
            } else {
                $this->makinaKlienti->setFormValue($val);
            }
        }

        // Check field name 'makinaMarka' first before field var 'x_makinaMarka'
        $val = $CurrentForm->hasValue("makinaMarka") ? $CurrentForm->getValue("makinaMarka") : $CurrentForm->getValue("x_makinaMarka");
        if (!$this->makinaMarka->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaMarka->Visible = false; // Disable update for API request
            } else {
                $this->makinaMarka->setFormValue($val);
            }
        }

        // Check field name 'makinaModeli' first before field var 'x_makinaModeli'
        $val = $CurrentForm->hasValue("makinaModeli") ? $CurrentForm->getValue("makinaModeli") : $CurrentForm->getValue("x_makinaModeli");
        if (!$this->makinaModeli->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaModeli->Visible = false; // Disable update for API request
            } else {
                $this->makinaModeli->setFormValue($val);
            }
        }

        // Check field name 'makinaKarburanti' first before field var 'x_makinaKarburanti'
        $val = $CurrentForm->hasValue("makinaKarburanti") ? $CurrentForm->getValue("makinaKarburanti") : $CurrentForm->getValue("x_makinaKarburanti");
        if (!$this->makinaKarburanti->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaKarburanti->Visible = false; // Disable update for API request
            } else {
                $this->makinaKarburanti->setFormValue($val);
            }
        }

        // Check field name 'makinaMadhesiaMotorrit' first before field var 'x_makinaMadhesiaMotorrit'
        $val = $CurrentForm->hasValue("makinaMadhesiaMotorrit") ? $CurrentForm->getValue("makinaMadhesiaMotorrit") : $CurrentForm->getValue("x_makinaMadhesiaMotorrit");
        if (!$this->makinaMadhesiaMotorrit->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaMadhesiaMotorrit->Visible = false; // Disable update for API request
            } else {
                $this->makinaMadhesiaMotorrit->setFormValue($val);
            }
        }

        // Check field name 'makinaVitiProdhimit' first before field var 'x_makinaVitiProdhimit'
        $val = $CurrentForm->hasValue("makinaVitiProdhimit") ? $CurrentForm->getValue("makinaVitiProdhimit") : $CurrentForm->getValue("x_makinaVitiProdhimit");
        if (!$this->makinaVitiProdhimit->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaVitiProdhimit->Visible = false; // Disable update for API request
            } else {
                $this->makinaVitiProdhimit->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'makinaNgjyra' first before field var 'x_makinaNgjyra'
        $val = $CurrentForm->hasValue("makinaNgjyra") ? $CurrentForm->getValue("makinaNgjyra") : $CurrentForm->getValue("x_makinaNgjyra");
        if (!$this->makinaNgjyra->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaNgjyra->Visible = false; // Disable update for API request
            } else {
                $this->makinaNgjyra->setFormValue($val);
            }
        }

        // Check field name 'makinaInfoShtese' first before field var 'x_makinaInfoShtese'
        $val = $CurrentForm->hasValue("makinaInfoShtese") ? $CurrentForm->getValue("makinaInfoShtese") : $CurrentForm->getValue("x_makinaInfoShtese");
        if (!$this->makinaInfoShtese->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaInfoShtese->Visible = false; // Disable update for API request
            } else {
                $this->makinaInfoShtese->setFormValue($val);
            }
        }

        // Check field name 'makinaVitiRegAL' first before field var 'x_makinaVitiRegAL'
        $val = $CurrentForm->hasValue("makinaVitiRegAL") ? $CurrentForm->getValue("makinaVitiRegAL") : $CurrentForm->getValue("x_makinaVitiRegAL");
        if (!$this->makinaVitiRegAL->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaVitiRegAL->Visible = false; // Disable update for API request
            } else {
                $this->makinaVitiRegAL->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'makinaTarga' first before field var 'x_makinaTarga'
        $val = $CurrentForm->hasValue("makinaTarga") ? $CurrentForm->getValue("makinaTarga") : $CurrentForm->getValue("x_makinaTarga");
        if (!$this->makinaTarga->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaTarga->Visible = false; // Disable update for API request
            } else {
                $this->makinaTarga->setFormValue($val);
            }
        }

        // Check field name 'makinaNrShasie' first before field var 'x_makinaNrShasie'
        $val = $CurrentForm->hasValue("makinaNrShasie") ? $CurrentForm->getValue("makinaNrShasie") : $CurrentForm->getValue("x_makinaNrShasie");
        if (!$this->makinaNrShasie->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaNrShasie->Visible = false; // Disable update for API request
            } else {
                $this->makinaNrShasie->setFormValue($val);
            }
        }

        // Check field name 'makinaPrejardhja' first before field var 'x_makinaPrejardhja'
        $val = $CurrentForm->hasValue("makinaPrejardhja") ? $CurrentForm->getValue("makinaPrejardhja") : $CurrentForm->getValue("x_makinaPrejardhja");
        if (!$this->makinaPrejardhja->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaPrejardhja->Visible = false; // Disable update for API request
            } else {
                $this->makinaPrejardhja->setFormValue($val);
            }
        }

        // Check field name 'makinaShiturVOLAL' first before field var 'x_makinaShiturVOLAL'
        $val = $CurrentForm->hasValue("makinaShiturVOLAL") ? $CurrentForm->getValue("makinaShiturVOLAL") : $CurrentForm->getValue("x_makinaShiturVOLAL");
        if (!$this->makinaShiturVOLAL->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaShiturVOLAL->Visible = false; // Disable update for API request
            } else {
                $this->makinaShiturVOLAL->setFormValue($val);
            }
        }

        // Check field name 'makinaAutori' first before field var 'x_makinaAutori'
        $val = $CurrentForm->hasValue("makinaAutori") ? $CurrentForm->getValue("makinaAutori") : $CurrentForm->getValue("x_makinaAutori");
        if (!$this->makinaAutori->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaAutori->Visible = false; // Disable update for API request
            } else {
                $this->makinaAutori->setFormValue($val);
            }
        }

        // Check field name 'makinaModifikuar' first before field var 'x_makinaModifikuar'
        $val = $CurrentForm->hasValue("makinaModifikuar") ? $CurrentForm->getValue("makinaModifikuar") : $CurrentForm->getValue("x_makinaModifikuar");
        if (!$this->makinaModifikuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->makinaModifikuar->Visible = false; // Disable update for API request
            } else {
                $this->makinaModifikuar->setFormValue($val);
            }
            $this->makinaModifikuar->CurrentValue = UnFormatDateTime($this->makinaModifikuar->CurrentValue, $this->makinaModifikuar->formatPattern());
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->makinaID->CurrentValue = $this->makinaID->FormValue;
        $this->makinaKlienti->CurrentValue = $this->makinaKlienti->FormValue;
        $this->makinaMarka->CurrentValue = $this->makinaMarka->FormValue;
        $this->makinaModeli->CurrentValue = $this->makinaModeli->FormValue;
        $this->makinaKarburanti->CurrentValue = $this->makinaKarburanti->FormValue;
        $this->makinaMadhesiaMotorrit->CurrentValue = $this->makinaMadhesiaMotorrit->FormValue;
        $this->makinaVitiProdhimit->CurrentValue = $this->makinaVitiProdhimit->FormValue;
        $this->makinaNgjyra->CurrentValue = $this->makinaNgjyra->FormValue;
        $this->makinaInfoShtese->CurrentValue = $this->makinaInfoShtese->FormValue;
        $this->makinaVitiRegAL->CurrentValue = $this->makinaVitiRegAL->FormValue;
        $this->makinaTarga->CurrentValue = $this->makinaTarga->FormValue;
        $this->makinaNrShasie->CurrentValue = $this->makinaNrShasie->FormValue;
        $this->makinaPrejardhja->CurrentValue = $this->makinaPrejardhja->FormValue;
        $this->makinaShiturVOLAL->CurrentValue = $this->makinaShiturVOLAL->FormValue;
        $this->makinaAutori->CurrentValue = $this->makinaAutori->FormValue;
        $this->makinaModifikuar->CurrentValue = $this->makinaModifikuar->FormValue;
        $this->makinaModifikuar->CurrentValue = UnFormatDateTime($this->makinaModifikuar->CurrentValue, $this->makinaModifikuar->formatPattern());
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
        $this->makinaID->setDbValue($row['makinaID']);
        $this->makinaKlienti->setDbValue($row['makinaKlienti']);
        if (array_key_exists('EV__makinaKlienti', $row)) {
            $this->makinaKlienti->VirtualValue = $row['EV__makinaKlienti']; // Set up virtual field value
        } else {
            $this->makinaKlienti->VirtualValue = ""; // Clear value
        }
        $this->makinaMarka->setDbValue($row['makinaMarka']);
        $this->makinaModeli->setDbValue($row['makinaModeli']);
        $this->makinaKarburanti->setDbValue($row['makinaKarburanti']);
        $this->makinaMadhesiaMotorrit->setDbValue($row['makinaMadhesiaMotorrit']);
        $this->makinaVitiProdhimit->setDbValue($row['makinaVitiProdhimit']);
        $this->makinaNgjyra->setDbValue($row['makinaNgjyra']);
        $this->makinaInfoShtese->setDbValue($row['makinaInfoShtese']);
        $this->makinaVitiRegAL->setDbValue($row['makinaVitiRegAL']);
        $this->makinaTarga->setDbValue($row['makinaTarga']);
        $this->makinaNrShasie->setDbValue($row['makinaNrShasie']);
        $this->makinaPrejardhja->setDbValue($row['makinaPrejardhja']);
        $this->makinaShiturVOLAL->setDbValue($row['makinaShiturVOLAL']);
        $this->makinaAutori->setDbValue($row['makinaAutori']);
        $this->makinaShtuar->setDbValue($row['makinaShtuar']);
        $this->makinaModifikuar->setDbValue($row['makinaModifikuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['makinaID'] = $this->makinaID->DefaultValue;
        $row['makinaKlienti'] = $this->makinaKlienti->DefaultValue;
        $row['makinaMarka'] = $this->makinaMarka->DefaultValue;
        $row['makinaModeli'] = $this->makinaModeli->DefaultValue;
        $row['makinaKarburanti'] = $this->makinaKarburanti->DefaultValue;
        $row['makinaMadhesiaMotorrit'] = $this->makinaMadhesiaMotorrit->DefaultValue;
        $row['makinaVitiProdhimit'] = $this->makinaVitiProdhimit->DefaultValue;
        $row['makinaNgjyra'] = $this->makinaNgjyra->DefaultValue;
        $row['makinaInfoShtese'] = $this->makinaInfoShtese->DefaultValue;
        $row['makinaVitiRegAL'] = $this->makinaVitiRegAL->DefaultValue;
        $row['makinaTarga'] = $this->makinaTarga->DefaultValue;
        $row['makinaNrShasie'] = $this->makinaNrShasie->DefaultValue;
        $row['makinaPrejardhja'] = $this->makinaPrejardhja->DefaultValue;
        $row['makinaShiturVOLAL'] = $this->makinaShiturVOLAL->DefaultValue;
        $row['makinaAutori'] = $this->makinaAutori->DefaultValue;
        $row['makinaShtuar'] = $this->makinaShtuar->DefaultValue;
        $row['makinaModifikuar'] = $this->makinaModifikuar->DefaultValue;
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

        // makinaID
        $this->makinaID->RowCssClass = "row";

        // makinaKlienti
        $this->makinaKlienti->RowCssClass = "row";

        // makinaMarka
        $this->makinaMarka->RowCssClass = "row";

        // makinaModeli
        $this->makinaModeli->RowCssClass = "row";

        // makinaKarburanti
        $this->makinaKarburanti->RowCssClass = "row";

        // makinaMadhesiaMotorrit
        $this->makinaMadhesiaMotorrit->RowCssClass = "row";

        // makinaVitiProdhimit
        $this->makinaVitiProdhimit->RowCssClass = "row";

        // makinaNgjyra
        $this->makinaNgjyra->RowCssClass = "row";

        // makinaInfoShtese
        $this->makinaInfoShtese->RowCssClass = "row";

        // makinaVitiRegAL
        $this->makinaVitiRegAL->RowCssClass = "row";

        // makinaTarga
        $this->makinaTarga->RowCssClass = "row";

        // makinaNrShasie
        $this->makinaNrShasie->RowCssClass = "row";

        // makinaPrejardhja
        $this->makinaPrejardhja->RowCssClass = "row";

        // makinaShiturVOLAL
        $this->makinaShiturVOLAL->RowCssClass = "row";

        // makinaAutori
        $this->makinaAutori->RowCssClass = "row";

        // makinaShtuar
        $this->makinaShtuar->RowCssClass = "row";

        // makinaModifikuar
        $this->makinaModifikuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // makinaID
            $this->makinaID->ViewValue = $this->makinaID->CurrentValue;
            $this->makinaID->ViewCustomAttributes = "";

            // makinaKlienti
            if ($this->makinaKlienti->VirtualValue != "") {
                $this->makinaKlienti->ViewValue = $this->makinaKlienti->VirtualValue;
            } else {
                $curVal = strval($this->makinaKlienti->CurrentValue);
                if ($curVal != "") {
                    $this->makinaKlienti->ViewValue = $this->makinaKlienti->lookupCacheOption($curVal);
                    if ($this->makinaKlienti->ViewValue === null) { // Lookup from database
                        $filterWrk = "`klientID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->makinaKlienti->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->makinaKlienti->Lookup->renderViewRow($rswrk[0]);
                            $this->makinaKlienti->ViewValue = $this->makinaKlienti->displayValue($arwrk);
                        } else {
                            $this->makinaKlienti->ViewValue = FormatNumber($this->makinaKlienti->CurrentValue, $this->makinaKlienti->formatPattern());
                        }
                    }
                } else {
                    $this->makinaKlienti->ViewValue = null;
                }
            }
            $this->makinaKlienti->ViewCustomAttributes = "";

            // makinaMarka
            $this->makinaMarka->ViewValue = $this->makinaMarka->CurrentValue;
            $this->makinaMarka->ViewCustomAttributes = "";

            // makinaModeli
            $this->makinaModeli->ViewValue = $this->makinaModeli->CurrentValue;
            $this->makinaModeli->ViewCustomAttributes = "";

            // makinaKarburanti
            if (strval($this->makinaKarburanti->CurrentValue) != "") {
                $this->makinaKarburanti->ViewValue = $this->makinaKarburanti->optionCaption($this->makinaKarburanti->CurrentValue);
            } else {
                $this->makinaKarburanti->ViewValue = null;
            }
            $this->makinaKarburanti->ViewCustomAttributes = "";

            // makinaMadhesiaMotorrit
            $this->makinaMadhesiaMotorrit->ViewValue = $this->makinaMadhesiaMotorrit->CurrentValue;
            $this->makinaMadhesiaMotorrit->ViewCustomAttributes = "";

            // makinaVitiProdhimit
            $this->makinaVitiProdhimit->ViewValue = $this->makinaVitiProdhimit->CurrentValue;
            $this->makinaVitiProdhimit->ViewValue = FormatNumber($this->makinaVitiProdhimit->ViewValue, $this->makinaVitiProdhimit->formatPattern());
            $this->makinaVitiProdhimit->ViewCustomAttributes = "";

            // makinaNgjyra
            $this->makinaNgjyra->ViewValue = $this->makinaNgjyra->CurrentValue;
            $this->makinaNgjyra->ViewCustomAttributes = "";

            // makinaInfoShtese
            $this->makinaInfoShtese->ViewValue = $this->makinaInfoShtese->CurrentValue;
            $this->makinaInfoShtese->ViewCustomAttributes = "";

            // makinaVitiRegAL
            $this->makinaVitiRegAL->ViewValue = $this->makinaVitiRegAL->CurrentValue;
            $this->makinaVitiRegAL->ViewValue = FormatNumber($this->makinaVitiRegAL->ViewValue, $this->makinaVitiRegAL->formatPattern());
            $this->makinaVitiRegAL->ViewCustomAttributes = "";

            // makinaTarga
            $this->makinaTarga->ViewValue = $this->makinaTarga->CurrentValue;
            $this->makinaTarga->ViewCustomAttributes = "";

            // makinaNrShasie
            $this->makinaNrShasie->ViewValue = $this->makinaNrShasie->CurrentValue;
            $this->makinaNrShasie->ViewCustomAttributes = "";

            // makinaPrejardhja
            $this->makinaPrejardhja->ViewValue = $this->makinaPrejardhja->CurrentValue;
            $this->makinaPrejardhja->ViewCustomAttributes = "";

            // makinaShiturVOLAL
            if (strval($this->makinaShiturVOLAL->CurrentValue) != "") {
                $this->makinaShiturVOLAL->ViewValue = $this->makinaShiturVOLAL->optionCaption($this->makinaShiturVOLAL->CurrentValue);
            } else {
                $this->makinaShiturVOLAL->ViewValue = null;
            }
            $this->makinaShiturVOLAL->ViewCustomAttributes = "";

            // makinaAutori
            $this->makinaAutori->ViewValue = $this->makinaAutori->CurrentValue;
            $this->makinaAutori->ViewValue = FormatNumber($this->makinaAutori->ViewValue, $this->makinaAutori->formatPattern());
            $this->makinaAutori->ViewCustomAttributes = "";

            // makinaShtuar
            $this->makinaShtuar->ViewValue = $this->makinaShtuar->CurrentValue;
            $this->makinaShtuar->ViewValue = FormatDateTime($this->makinaShtuar->ViewValue, $this->makinaShtuar->formatPattern());
            $this->makinaShtuar->ViewCustomAttributes = "";

            // makinaModifikuar
            $this->makinaModifikuar->ViewValue = $this->makinaModifikuar->CurrentValue;
            $this->makinaModifikuar->ViewValue = FormatDateTime($this->makinaModifikuar->ViewValue, $this->makinaModifikuar->formatPattern());
            $this->makinaModifikuar->ViewCustomAttributes = "";

            // makinaID
            $this->makinaID->LinkCustomAttributes = "";
            $this->makinaID->HrefValue = "";

            // makinaKlienti
            $this->makinaKlienti->LinkCustomAttributes = "";
            $this->makinaKlienti->HrefValue = "";

            // makinaMarka
            $this->makinaMarka->LinkCustomAttributes = "";
            $this->makinaMarka->HrefValue = "";

            // makinaModeli
            $this->makinaModeli->LinkCustomAttributes = "";
            $this->makinaModeli->HrefValue = "";

            // makinaKarburanti
            $this->makinaKarburanti->LinkCustomAttributes = "";
            $this->makinaKarburanti->HrefValue = "";

            // makinaMadhesiaMotorrit
            $this->makinaMadhesiaMotorrit->LinkCustomAttributes = "";
            $this->makinaMadhesiaMotorrit->HrefValue = "";

            // makinaVitiProdhimit
            $this->makinaVitiProdhimit->LinkCustomAttributes = "";
            $this->makinaVitiProdhimit->HrefValue = "";

            // makinaNgjyra
            $this->makinaNgjyra->LinkCustomAttributes = "";
            $this->makinaNgjyra->HrefValue = "";

            // makinaInfoShtese
            $this->makinaInfoShtese->LinkCustomAttributes = "";
            $this->makinaInfoShtese->HrefValue = "";

            // makinaVitiRegAL
            $this->makinaVitiRegAL->LinkCustomAttributes = "";
            $this->makinaVitiRegAL->HrefValue = "";

            // makinaTarga
            $this->makinaTarga->LinkCustomAttributes = "";
            $this->makinaTarga->HrefValue = "";

            // makinaNrShasie
            $this->makinaNrShasie->LinkCustomAttributes = "";
            $this->makinaNrShasie->HrefValue = "";

            // makinaPrejardhja
            $this->makinaPrejardhja->LinkCustomAttributes = "";
            $this->makinaPrejardhja->HrefValue = "";

            // makinaShiturVOLAL
            $this->makinaShiturVOLAL->LinkCustomAttributes = "";
            $this->makinaShiturVOLAL->HrefValue = "";

            // makinaAutori
            $this->makinaAutori->LinkCustomAttributes = "";
            $this->makinaAutori->HrefValue = "";

            // makinaModifikuar
            $this->makinaModifikuar->LinkCustomAttributes = "";
            $this->makinaModifikuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // makinaID
            $this->makinaID->setupEditAttributes();
            $this->makinaID->EditCustomAttributes = "";
            $this->makinaID->EditValue = $this->makinaID->CurrentValue;
            $this->makinaID->ViewCustomAttributes = "";

            // makinaKlienti
            $this->makinaKlienti->setupEditAttributes();
            $this->makinaKlienti->EditCustomAttributes = "";
            $curVal = trim(strval($this->makinaKlienti->CurrentValue));
            if ($curVal != "") {
                $this->makinaKlienti->ViewValue = $this->makinaKlienti->lookupCacheOption($curVal);
            } else {
                $this->makinaKlienti->ViewValue = $this->makinaKlienti->Lookup !== null && is_array($this->makinaKlienti->lookupOptions()) ? $curVal : null;
            }
            if ($this->makinaKlienti->ViewValue !== null) { // Load from cache
                $this->makinaKlienti->EditValue = array_values($this->makinaKlienti->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`klientID`" . SearchString("=", $this->makinaKlienti->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->makinaKlienti->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->makinaKlienti->EditValue = $arwrk;
            }
            $this->makinaKlienti->PlaceHolder = RemoveHtml($this->makinaKlienti->caption());

            // makinaMarka
            $this->makinaMarka->setupEditAttributes();
            $this->makinaMarka->EditCustomAttributes = "";
            if (!$this->makinaMarka->Raw) {
                $this->makinaMarka->CurrentValue = HtmlDecode($this->makinaMarka->CurrentValue);
            }
            $this->makinaMarka->EditValue = HtmlEncode($this->makinaMarka->CurrentValue);
            $this->makinaMarka->PlaceHolder = RemoveHtml($this->makinaMarka->caption());

            // makinaModeli
            $this->makinaModeli->setupEditAttributes();
            $this->makinaModeli->EditCustomAttributes = "";
            if (!$this->makinaModeli->Raw) {
                $this->makinaModeli->CurrentValue = HtmlDecode($this->makinaModeli->CurrentValue);
            }
            $this->makinaModeli->EditValue = HtmlEncode($this->makinaModeli->CurrentValue);
            $this->makinaModeli->PlaceHolder = RemoveHtml($this->makinaModeli->caption());

            // makinaKarburanti
            $this->makinaKarburanti->EditCustomAttributes = "";
            $this->makinaKarburanti->EditValue = $this->makinaKarburanti->options(false);
            $this->makinaKarburanti->PlaceHolder = RemoveHtml($this->makinaKarburanti->caption());

            // makinaMadhesiaMotorrit
            $this->makinaMadhesiaMotorrit->setupEditAttributes();
            $this->makinaMadhesiaMotorrit->EditCustomAttributes = "";
            if (!$this->makinaMadhesiaMotorrit->Raw) {
                $this->makinaMadhesiaMotorrit->CurrentValue = HtmlDecode($this->makinaMadhesiaMotorrit->CurrentValue);
            }
            $this->makinaMadhesiaMotorrit->EditValue = HtmlEncode($this->makinaMadhesiaMotorrit->CurrentValue);
            $this->makinaMadhesiaMotorrit->PlaceHolder = RemoveHtml($this->makinaMadhesiaMotorrit->caption());

            // makinaVitiProdhimit
            $this->makinaVitiProdhimit->setupEditAttributes();
            $this->makinaVitiProdhimit->EditCustomAttributes = "";
            $this->makinaVitiProdhimit->EditValue = HtmlEncode($this->makinaVitiProdhimit->CurrentValue);
            $this->makinaVitiProdhimit->PlaceHolder = RemoveHtml($this->makinaVitiProdhimit->caption());
            if (strval($this->makinaVitiProdhimit->EditValue) != "" && is_numeric($this->makinaVitiProdhimit->EditValue)) {
                $this->makinaVitiProdhimit->EditValue = FormatNumber($this->makinaVitiProdhimit->EditValue, null);
            }

            // makinaNgjyra
            $this->makinaNgjyra->setupEditAttributes();
            $this->makinaNgjyra->EditCustomAttributes = "";
            if (!$this->makinaNgjyra->Raw) {
                $this->makinaNgjyra->CurrentValue = HtmlDecode($this->makinaNgjyra->CurrentValue);
            }
            $this->makinaNgjyra->EditValue = HtmlEncode($this->makinaNgjyra->CurrentValue);
            $this->makinaNgjyra->PlaceHolder = RemoveHtml($this->makinaNgjyra->caption());

            // makinaInfoShtese
            $this->makinaInfoShtese->setupEditAttributes();
            $this->makinaInfoShtese->EditCustomAttributes = "";
            $this->makinaInfoShtese->EditValue = HtmlEncode($this->makinaInfoShtese->CurrentValue);
            $this->makinaInfoShtese->PlaceHolder = RemoveHtml($this->makinaInfoShtese->caption());

            // makinaVitiRegAL
            $this->makinaVitiRegAL->setupEditAttributes();
            $this->makinaVitiRegAL->EditCustomAttributes = "";
            $this->makinaVitiRegAL->EditValue = HtmlEncode($this->makinaVitiRegAL->CurrentValue);
            $this->makinaVitiRegAL->PlaceHolder = RemoveHtml($this->makinaVitiRegAL->caption());
            if (strval($this->makinaVitiRegAL->EditValue) != "" && is_numeric($this->makinaVitiRegAL->EditValue)) {
                $this->makinaVitiRegAL->EditValue = FormatNumber($this->makinaVitiRegAL->EditValue, null);
            }

            // makinaTarga
            $this->makinaTarga->setupEditAttributes();
            $this->makinaTarga->EditCustomAttributes = "";
            if (!$this->makinaTarga->Raw) {
                $this->makinaTarga->CurrentValue = HtmlDecode($this->makinaTarga->CurrentValue);
            }
            $this->makinaTarga->EditValue = HtmlEncode($this->makinaTarga->CurrentValue);
            $this->makinaTarga->PlaceHolder = RemoveHtml($this->makinaTarga->caption());

            // makinaNrShasie
            $this->makinaNrShasie->setupEditAttributes();
            $this->makinaNrShasie->EditCustomAttributes = "";
            if (!$this->makinaNrShasie->Raw) {
                $this->makinaNrShasie->CurrentValue = HtmlDecode($this->makinaNrShasie->CurrentValue);
            }
            $this->makinaNrShasie->EditValue = HtmlEncode($this->makinaNrShasie->CurrentValue);
            $this->makinaNrShasie->PlaceHolder = RemoveHtml($this->makinaNrShasie->caption());

            // makinaPrejardhja
            $this->makinaPrejardhja->setupEditAttributes();
            $this->makinaPrejardhja->EditCustomAttributes = "";
            if (!$this->makinaPrejardhja->Raw) {
                $this->makinaPrejardhja->CurrentValue = HtmlDecode($this->makinaPrejardhja->CurrentValue);
            }
            $this->makinaPrejardhja->EditValue = HtmlEncode($this->makinaPrejardhja->CurrentValue);
            $this->makinaPrejardhja->PlaceHolder = RemoveHtml($this->makinaPrejardhja->caption());

            // makinaShiturVOLAL
            $this->makinaShiturVOLAL->EditCustomAttributes = "";
            $this->makinaShiturVOLAL->EditValue = $this->makinaShiturVOLAL->options(false);
            $this->makinaShiturVOLAL->PlaceHolder = RemoveHtml($this->makinaShiturVOLAL->caption());

            // makinaAutori

            // makinaModifikuar

            // Edit refer script

            // makinaID
            $this->makinaID->LinkCustomAttributes = "";
            $this->makinaID->HrefValue = "";

            // makinaKlienti
            $this->makinaKlienti->LinkCustomAttributes = "";
            $this->makinaKlienti->HrefValue = "";

            // makinaMarka
            $this->makinaMarka->LinkCustomAttributes = "";
            $this->makinaMarka->HrefValue = "";

            // makinaModeli
            $this->makinaModeli->LinkCustomAttributes = "";
            $this->makinaModeli->HrefValue = "";

            // makinaKarburanti
            $this->makinaKarburanti->LinkCustomAttributes = "";
            $this->makinaKarburanti->HrefValue = "";

            // makinaMadhesiaMotorrit
            $this->makinaMadhesiaMotorrit->LinkCustomAttributes = "";
            $this->makinaMadhesiaMotorrit->HrefValue = "";

            // makinaVitiProdhimit
            $this->makinaVitiProdhimit->LinkCustomAttributes = "";
            $this->makinaVitiProdhimit->HrefValue = "";

            // makinaNgjyra
            $this->makinaNgjyra->LinkCustomAttributes = "";
            $this->makinaNgjyra->HrefValue = "";

            // makinaInfoShtese
            $this->makinaInfoShtese->LinkCustomAttributes = "";
            $this->makinaInfoShtese->HrefValue = "";

            // makinaVitiRegAL
            $this->makinaVitiRegAL->LinkCustomAttributes = "";
            $this->makinaVitiRegAL->HrefValue = "";

            // makinaTarga
            $this->makinaTarga->LinkCustomAttributes = "";
            $this->makinaTarga->HrefValue = "";

            // makinaNrShasie
            $this->makinaNrShasie->LinkCustomAttributes = "";
            $this->makinaNrShasie->HrefValue = "";

            // makinaPrejardhja
            $this->makinaPrejardhja->LinkCustomAttributes = "";
            $this->makinaPrejardhja->HrefValue = "";

            // makinaShiturVOLAL
            $this->makinaShiturVOLAL->LinkCustomAttributes = "";
            $this->makinaShiturVOLAL->HrefValue = "";

            // makinaAutori
            $this->makinaAutori->LinkCustomAttributes = "";
            $this->makinaAutori->HrefValue = "";

            // makinaModifikuar
            $this->makinaModifikuar->LinkCustomAttributes = "";
            $this->makinaModifikuar->HrefValue = "";
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
        if ($this->makinaID->Required) {
            if (!$this->makinaID->IsDetailKey && EmptyValue($this->makinaID->FormValue)) {
                $this->makinaID->addErrorMessage(str_replace("%s", $this->makinaID->caption(), $this->makinaID->RequiredErrorMessage));
            }
        }
        if ($this->makinaKlienti->Required) {
            if (!$this->makinaKlienti->IsDetailKey && EmptyValue($this->makinaKlienti->FormValue)) {
                $this->makinaKlienti->addErrorMessage(str_replace("%s", $this->makinaKlienti->caption(), $this->makinaKlienti->RequiredErrorMessage));
            }
        }
        if ($this->makinaMarka->Required) {
            if (!$this->makinaMarka->IsDetailKey && EmptyValue($this->makinaMarka->FormValue)) {
                $this->makinaMarka->addErrorMessage(str_replace("%s", $this->makinaMarka->caption(), $this->makinaMarka->RequiredErrorMessage));
            }
        }
        if ($this->makinaModeli->Required) {
            if (!$this->makinaModeli->IsDetailKey && EmptyValue($this->makinaModeli->FormValue)) {
                $this->makinaModeli->addErrorMessage(str_replace("%s", $this->makinaModeli->caption(), $this->makinaModeli->RequiredErrorMessage));
            }
        }
        if ($this->makinaKarburanti->Required) {
            if ($this->makinaKarburanti->FormValue == "") {
                $this->makinaKarburanti->addErrorMessage(str_replace("%s", $this->makinaKarburanti->caption(), $this->makinaKarburanti->RequiredErrorMessage));
            }
        }
        if ($this->makinaMadhesiaMotorrit->Required) {
            if (!$this->makinaMadhesiaMotorrit->IsDetailKey && EmptyValue($this->makinaMadhesiaMotorrit->FormValue)) {
                $this->makinaMadhesiaMotorrit->addErrorMessage(str_replace("%s", $this->makinaMadhesiaMotorrit->caption(), $this->makinaMadhesiaMotorrit->RequiredErrorMessage));
            }
        }
        if ($this->makinaVitiProdhimit->Required) {
            if (!$this->makinaVitiProdhimit->IsDetailKey && EmptyValue($this->makinaVitiProdhimit->FormValue)) {
                $this->makinaVitiProdhimit->addErrorMessage(str_replace("%s", $this->makinaVitiProdhimit->caption(), $this->makinaVitiProdhimit->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->makinaVitiProdhimit->FormValue)) {
            $this->makinaVitiProdhimit->addErrorMessage($this->makinaVitiProdhimit->getErrorMessage(false));
        }
        if ($this->makinaNgjyra->Required) {
            if (!$this->makinaNgjyra->IsDetailKey && EmptyValue($this->makinaNgjyra->FormValue)) {
                $this->makinaNgjyra->addErrorMessage(str_replace("%s", $this->makinaNgjyra->caption(), $this->makinaNgjyra->RequiredErrorMessage));
            }
        }
        if ($this->makinaInfoShtese->Required) {
            if (!$this->makinaInfoShtese->IsDetailKey && EmptyValue($this->makinaInfoShtese->FormValue)) {
                $this->makinaInfoShtese->addErrorMessage(str_replace("%s", $this->makinaInfoShtese->caption(), $this->makinaInfoShtese->RequiredErrorMessage));
            }
        }
        if ($this->makinaVitiRegAL->Required) {
            if (!$this->makinaVitiRegAL->IsDetailKey && EmptyValue($this->makinaVitiRegAL->FormValue)) {
                $this->makinaVitiRegAL->addErrorMessage(str_replace("%s", $this->makinaVitiRegAL->caption(), $this->makinaVitiRegAL->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->makinaVitiRegAL->FormValue)) {
            $this->makinaVitiRegAL->addErrorMessage($this->makinaVitiRegAL->getErrorMessage(false));
        }
        if ($this->makinaTarga->Required) {
            if (!$this->makinaTarga->IsDetailKey && EmptyValue($this->makinaTarga->FormValue)) {
                $this->makinaTarga->addErrorMessage(str_replace("%s", $this->makinaTarga->caption(), $this->makinaTarga->RequiredErrorMessage));
            }
        }
        if ($this->makinaNrShasie->Required) {
            if (!$this->makinaNrShasie->IsDetailKey && EmptyValue($this->makinaNrShasie->FormValue)) {
                $this->makinaNrShasie->addErrorMessage(str_replace("%s", $this->makinaNrShasie->caption(), $this->makinaNrShasie->RequiredErrorMessage));
            }
        }
        if ($this->makinaPrejardhja->Required) {
            if (!$this->makinaPrejardhja->IsDetailKey && EmptyValue($this->makinaPrejardhja->FormValue)) {
                $this->makinaPrejardhja->addErrorMessage(str_replace("%s", $this->makinaPrejardhja->caption(), $this->makinaPrejardhja->RequiredErrorMessage));
            }
        }
        if ($this->makinaShiturVOLAL->Required) {
            if ($this->makinaShiturVOLAL->FormValue == "") {
                $this->makinaShiturVOLAL->addErrorMessage(str_replace("%s", $this->makinaShiturVOLAL->caption(), $this->makinaShiturVOLAL->RequiredErrorMessage));
            }
        }
        if ($this->makinaAutori->Required) {
            if (!$this->makinaAutori->IsDetailKey && EmptyValue($this->makinaAutori->FormValue)) {
                $this->makinaAutori->addErrorMessage(str_replace("%s", $this->makinaAutori->caption(), $this->makinaAutori->RequiredErrorMessage));
            }
        }
        if ($this->makinaModifikuar->Required) {
            if (!$this->makinaModifikuar->IsDetailKey && EmptyValue($this->makinaModifikuar->FormValue)) {
                $this->makinaModifikuar->addErrorMessage(str_replace("%s", $this->makinaModifikuar->caption(), $this->makinaModifikuar->RequiredErrorMessage));
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

        // makinaKlienti
        $this->makinaKlienti->setDbValueDef($rsnew, $this->makinaKlienti->CurrentValue, 0, $this->makinaKlienti->ReadOnly);

        // makinaMarka
        $this->makinaMarka->setDbValueDef($rsnew, $this->makinaMarka->CurrentValue, "", $this->makinaMarka->ReadOnly);

        // makinaModeli
        $this->makinaModeli->setDbValueDef($rsnew, $this->makinaModeli->CurrentValue, "", $this->makinaModeli->ReadOnly);

        // makinaKarburanti
        $this->makinaKarburanti->setDbValueDef($rsnew, $this->makinaKarburanti->CurrentValue, "", $this->makinaKarburanti->ReadOnly);

        // makinaMadhesiaMotorrit
        $this->makinaMadhesiaMotorrit->setDbValueDef($rsnew, $this->makinaMadhesiaMotorrit->CurrentValue, "", $this->makinaMadhesiaMotorrit->ReadOnly);

        // makinaVitiProdhimit
        $this->makinaVitiProdhimit->setDbValueDef($rsnew, $this->makinaVitiProdhimit->CurrentValue, 0, $this->makinaVitiProdhimit->ReadOnly);

        // makinaNgjyra
        $this->makinaNgjyra->setDbValueDef($rsnew, $this->makinaNgjyra->CurrentValue, null, $this->makinaNgjyra->ReadOnly);

        // makinaInfoShtese
        $this->makinaInfoShtese->setDbValueDef($rsnew, $this->makinaInfoShtese->CurrentValue, null, $this->makinaInfoShtese->ReadOnly);

        // makinaVitiRegAL
        $this->makinaVitiRegAL->setDbValueDef($rsnew, $this->makinaVitiRegAL->CurrentValue, null, $this->makinaVitiRegAL->ReadOnly);

        // makinaTarga
        $this->makinaTarga->setDbValueDef($rsnew, $this->makinaTarga->CurrentValue, "", $this->makinaTarga->ReadOnly);

        // makinaNrShasie
        $this->makinaNrShasie->setDbValueDef($rsnew, $this->makinaNrShasie->CurrentValue, null, $this->makinaNrShasie->ReadOnly);

        // makinaPrejardhja
        $this->makinaPrejardhja->setDbValueDef($rsnew, $this->makinaPrejardhja->CurrentValue, null, $this->makinaPrejardhja->ReadOnly);

        // makinaShiturVOLAL
        $this->makinaShiturVOLAL->setDbValueDef($rsnew, $this->makinaShiturVOLAL->CurrentValue, "", $this->makinaShiturVOLAL->ReadOnly);

        // makinaAutori
        $this->makinaAutori->CurrentValue = CurrentUserID();
        $this->makinaAutori->setDbValueDef($rsnew, $this->makinaAutori->CurrentValue, 0);

        // makinaModifikuar
        $this->makinaModifikuar->CurrentValue = CurrentDateTime();
        $this->makinaModifikuar->setDbValueDef($rsnew, $this->makinaModifikuar->CurrentValue, null);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MakinaList"), "", $this->TableVar, true);
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
                case "x_makinaKlienti":
                    break;
                case "x_makinaKarburanti":
                    break;
                case "x_makinaShiturVOLAL":
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
