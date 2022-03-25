<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class KlientEdit extends Klient
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'klient';

    // Page object name
    public $PageObjName = "KlientEdit";

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

        // Table object (klient)
        if (!isset($GLOBALS["klient"]) || get_class($GLOBALS["klient"]) == PROJECT_NAMESPACE . "klient") {
            $GLOBALS["klient"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'klient');
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
                $tbl = Container("klient");
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
                    if ($pageName == "KlientView") {
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
            $key .= @$ar['klientID'];
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
            $this->klientID->Visible = false;
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
        $this->klientID->setVisibility();
        $this->klientTipi->setVisibility();
        $this->klientEmertimi->setVisibility();
        $this->klientNIPT->setVisibility();
        $this->klientAdresa->setVisibility();
        $this->klientQyteti->setVisibility();
        $this->klientTel1->setVisibility();
        $this->klientTel2->setVisibility();
        $this->klientEmail->setVisibility();
        $this->klientAutori->setVisibility();
        $this->klientShtuar->Visible = false;
        $this->klientModifikuar->setVisibility();
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
        $this->setupLookupOptions($this->klientTipi);
        $this->setupLookupOptions($this->klientAutori);

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
            if (($keyValue = Get("klientID") ?? Key(0) ?? Route(2)) !== null) {
                $this->klientID->setQueryStringValue($keyValue);
                $this->klientID->setOldValue($this->klientID->QueryStringValue);
            } elseif (Post("klientID") !== null) {
                $this->klientID->setFormValue(Post("klientID"));
                $this->klientID->setOldValue($this->klientID->FormValue);
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
                if (($keyValue = Get("klientID") ?? Route("klientID")) !== null) {
                    $this->klientID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->klientID->CurrentValue = null;
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
                        $this->terminate("KlientList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "KlientList") {
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

        // Check field name 'klientID' first before field var 'x_klientID'
        $val = $CurrentForm->hasValue("klientID") ? $CurrentForm->getValue("klientID") : $CurrentForm->getValue("x_klientID");
        if (!$this->klientID->IsDetailKey) {
            $this->klientID->setFormValue($val);
        }

        // Check field name 'klientTipi' first before field var 'x_klientTipi'
        $val = $CurrentForm->hasValue("klientTipi") ? $CurrentForm->getValue("klientTipi") : $CurrentForm->getValue("x_klientTipi");
        if (!$this->klientTipi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientTipi->Visible = false; // Disable update for API request
            } else {
                $this->klientTipi->setFormValue($val);
            }
        }

        // Check field name 'klientEmertimi' first before field var 'x_klientEmertimi'
        $val = $CurrentForm->hasValue("klientEmertimi") ? $CurrentForm->getValue("klientEmertimi") : $CurrentForm->getValue("x_klientEmertimi");
        if (!$this->klientEmertimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientEmertimi->Visible = false; // Disable update for API request
            } else {
                $this->klientEmertimi->setFormValue($val);
            }
        }

        // Check field name 'klientNIPT' first before field var 'x_klientNIPT'
        $val = $CurrentForm->hasValue("klientNIPT") ? $CurrentForm->getValue("klientNIPT") : $CurrentForm->getValue("x_klientNIPT");
        if (!$this->klientNIPT->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientNIPT->Visible = false; // Disable update for API request
            } else {
                $this->klientNIPT->setFormValue($val);
            }
        }

        // Check field name 'klientAdresa' first before field var 'x_klientAdresa'
        $val = $CurrentForm->hasValue("klientAdresa") ? $CurrentForm->getValue("klientAdresa") : $CurrentForm->getValue("x_klientAdresa");
        if (!$this->klientAdresa->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientAdresa->Visible = false; // Disable update for API request
            } else {
                $this->klientAdresa->setFormValue($val);
            }
        }

        // Check field name 'klientQyteti' first before field var 'x_klientQyteti'
        $val = $CurrentForm->hasValue("klientQyteti") ? $CurrentForm->getValue("klientQyteti") : $CurrentForm->getValue("x_klientQyteti");
        if (!$this->klientQyteti->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientQyteti->Visible = false; // Disable update for API request
            } else {
                $this->klientQyteti->setFormValue($val);
            }
        }

        // Check field name 'klientTel1' first before field var 'x_klientTel1'
        $val = $CurrentForm->hasValue("klientTel1") ? $CurrentForm->getValue("klientTel1") : $CurrentForm->getValue("x_klientTel1");
        if (!$this->klientTel1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientTel1->Visible = false; // Disable update for API request
            } else {
                $this->klientTel1->setFormValue($val);
            }
        }

        // Check field name 'klientTel2' first before field var 'x_klientTel2'
        $val = $CurrentForm->hasValue("klientTel2") ? $CurrentForm->getValue("klientTel2") : $CurrentForm->getValue("x_klientTel2");
        if (!$this->klientTel2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientTel2->Visible = false; // Disable update for API request
            } else {
                $this->klientTel2->setFormValue($val);
            }
        }

        // Check field name 'klientEmail' first before field var 'x_klientEmail'
        $val = $CurrentForm->hasValue("klientEmail") ? $CurrentForm->getValue("klientEmail") : $CurrentForm->getValue("x_klientEmail");
        if (!$this->klientEmail->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientEmail->Visible = false; // Disable update for API request
            } else {
                $this->klientEmail->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'klientAutori' first before field var 'x_klientAutori'
        $val = $CurrentForm->hasValue("klientAutori") ? $CurrentForm->getValue("klientAutori") : $CurrentForm->getValue("x_klientAutori");
        if (!$this->klientAutori->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientAutori->Visible = false; // Disable update for API request
            } else {
                $this->klientAutori->setFormValue($val);
            }
        }

        // Check field name 'klientModifikuar' first before field var 'x_klientModifikuar'
        $val = $CurrentForm->hasValue("klientModifikuar") ? $CurrentForm->getValue("klientModifikuar") : $CurrentForm->getValue("x_klientModifikuar");
        if (!$this->klientModifikuar->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->klientModifikuar->Visible = false; // Disable update for API request
            } else {
                $this->klientModifikuar->setFormValue($val);
            }
            $this->klientModifikuar->CurrentValue = UnFormatDateTime($this->klientModifikuar->CurrentValue, $this->klientModifikuar->formatPattern());
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->klientID->CurrentValue = $this->klientID->FormValue;
        $this->klientTipi->CurrentValue = $this->klientTipi->FormValue;
        $this->klientEmertimi->CurrentValue = $this->klientEmertimi->FormValue;
        $this->klientNIPT->CurrentValue = $this->klientNIPT->FormValue;
        $this->klientAdresa->CurrentValue = $this->klientAdresa->FormValue;
        $this->klientQyteti->CurrentValue = $this->klientQyteti->FormValue;
        $this->klientTel1->CurrentValue = $this->klientTel1->FormValue;
        $this->klientTel2->CurrentValue = $this->klientTel2->FormValue;
        $this->klientEmail->CurrentValue = $this->klientEmail->FormValue;
        $this->klientAutori->CurrentValue = $this->klientAutori->FormValue;
        $this->klientModifikuar->CurrentValue = $this->klientModifikuar->FormValue;
        $this->klientModifikuar->CurrentValue = UnFormatDateTime($this->klientModifikuar->CurrentValue, $this->klientModifikuar->formatPattern());
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
        $this->klientID->setDbValue($row['klientID']);
        $this->klientTipi->setDbValue($row['klientTipi']);
        $this->klientEmertimi->setDbValue($row['klientEmertimi']);
        $this->klientNIPT->setDbValue($row['klientNIPT']);
        $this->klientAdresa->setDbValue($row['klientAdresa']);
        $this->klientQyteti->setDbValue($row['klientQyteti']);
        $this->klientTel1->setDbValue($row['klientTel1']);
        $this->klientTel2->setDbValue($row['klientTel2']);
        $this->klientEmail->setDbValue($row['klientEmail']);
        $this->klientAutori->setDbValue($row['klientAutori']);
        $this->klientShtuar->setDbValue($row['klientShtuar']);
        $this->klientModifikuar->setDbValue($row['klientModifikuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['klientID'] = $this->klientID->DefaultValue;
        $row['klientTipi'] = $this->klientTipi->DefaultValue;
        $row['klientEmertimi'] = $this->klientEmertimi->DefaultValue;
        $row['klientNIPT'] = $this->klientNIPT->DefaultValue;
        $row['klientAdresa'] = $this->klientAdresa->DefaultValue;
        $row['klientQyteti'] = $this->klientQyteti->DefaultValue;
        $row['klientTel1'] = $this->klientTel1->DefaultValue;
        $row['klientTel2'] = $this->klientTel2->DefaultValue;
        $row['klientEmail'] = $this->klientEmail->DefaultValue;
        $row['klientAutori'] = $this->klientAutori->DefaultValue;
        $row['klientShtuar'] = $this->klientShtuar->DefaultValue;
        $row['klientModifikuar'] = $this->klientModifikuar->DefaultValue;
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

        // klientID
        $this->klientID->RowCssClass = "row";

        // klientTipi
        $this->klientTipi->RowCssClass = "row";

        // klientEmertimi
        $this->klientEmertimi->RowCssClass = "row";

        // klientNIPT
        $this->klientNIPT->RowCssClass = "row";

        // klientAdresa
        $this->klientAdresa->RowCssClass = "row";

        // klientQyteti
        $this->klientQyteti->RowCssClass = "row";

        // klientTel1
        $this->klientTel1->RowCssClass = "row";

        // klientTel2
        $this->klientTel2->RowCssClass = "row";

        // klientEmail
        $this->klientEmail->RowCssClass = "row";

        // klientAutori
        $this->klientAutori->RowCssClass = "row";

        // klientShtuar
        $this->klientShtuar->RowCssClass = "row";

        // klientModifikuar
        $this->klientModifikuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // klientID
            $this->klientID->ViewValue = $this->klientID->CurrentValue;
            $this->klientID->ViewCustomAttributes = "";

            // klientTipi
            if (strval($this->klientTipi->CurrentValue) != "") {
                $this->klientTipi->ViewValue = $this->klientTipi->optionCaption($this->klientTipi->CurrentValue);
            } else {
                $this->klientTipi->ViewValue = null;
            }
            $this->klientTipi->ViewCustomAttributes = "";

            // klientEmertimi
            $this->klientEmertimi->ViewValue = $this->klientEmertimi->CurrentValue;
            $this->klientEmertimi->ViewCustomAttributes = "";

            // klientNIPT
            $this->klientNIPT->ViewValue = $this->klientNIPT->CurrentValue;
            $this->klientNIPT->ViewCustomAttributes = "";

            // klientAdresa
            $this->klientAdresa->ViewValue = $this->klientAdresa->CurrentValue;
            $this->klientAdresa->ViewCustomAttributes = "";

            // klientQyteti
            $this->klientQyteti->ViewValue = $this->klientQyteti->CurrentValue;
            $this->klientQyteti->ViewCustomAttributes = "";

            // klientTel1
            $this->klientTel1->ViewValue = $this->klientTel1->CurrentValue;
            $this->klientTel1->ViewCustomAttributes = "";

            // klientTel2
            $this->klientTel2->ViewValue = $this->klientTel2->CurrentValue;
            $this->klientTel2->ViewCustomAttributes = "";

            // klientEmail
            $this->klientEmail->ViewValue = $this->klientEmail->CurrentValue;
            $this->klientEmail->ViewCustomAttributes = "";

            // klientAutori
            $this->klientAutori->ViewValue = $this->klientAutori->CurrentValue;
            $curVal = strval($this->klientAutori->CurrentValue);
            if ($curVal != "") {
                $this->klientAutori->ViewValue = $this->klientAutori->lookupCacheOption($curVal);
                if ($this->klientAutori->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->klientAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->klientAutori->Lookup->renderViewRow($rswrk[0]);
                        $this->klientAutori->ViewValue = $this->klientAutori->displayValue($arwrk);
                    } else {
                        $this->klientAutori->ViewValue = FormatNumber($this->klientAutori->CurrentValue, $this->klientAutori->formatPattern());
                    }
                }
            } else {
                $this->klientAutori->ViewValue = null;
            }
            $this->klientAutori->ViewCustomAttributes = "";

            // klientShtuar
            $this->klientShtuar->ViewValue = $this->klientShtuar->CurrentValue;
            $this->klientShtuar->ViewValue = FormatDateTime($this->klientShtuar->ViewValue, $this->klientShtuar->formatPattern());
            $this->klientShtuar->ViewCustomAttributes = "";

            // klientModifikuar
            $this->klientModifikuar->ViewValue = $this->klientModifikuar->CurrentValue;
            $this->klientModifikuar->ViewValue = FormatDateTime($this->klientModifikuar->ViewValue, $this->klientModifikuar->formatPattern());
            $this->klientModifikuar->ViewCustomAttributes = "";

            // klientID
            $this->klientID->LinkCustomAttributes = "";
            $this->klientID->HrefValue = "";

            // klientTipi
            $this->klientTipi->LinkCustomAttributes = "";
            $this->klientTipi->HrefValue = "";

            // klientEmertimi
            $this->klientEmertimi->LinkCustomAttributes = "";
            $this->klientEmertimi->HrefValue = "";

            // klientNIPT
            $this->klientNIPT->LinkCustomAttributes = "";
            $this->klientNIPT->HrefValue = "";

            // klientAdresa
            $this->klientAdresa->LinkCustomAttributes = "";
            $this->klientAdresa->HrefValue = "";

            // klientQyteti
            $this->klientQyteti->LinkCustomAttributes = "";
            $this->klientQyteti->HrefValue = "";

            // klientTel1
            $this->klientTel1->LinkCustomAttributes = "";
            $this->klientTel1->HrefValue = "";

            // klientTel2
            $this->klientTel2->LinkCustomAttributes = "";
            $this->klientTel2->HrefValue = "";

            // klientEmail
            $this->klientEmail->LinkCustomAttributes = "";
            $this->klientEmail->HrefValue = "";

            // klientAutori
            $this->klientAutori->LinkCustomAttributes = "";
            $this->klientAutori->HrefValue = "";

            // klientModifikuar
            $this->klientModifikuar->LinkCustomAttributes = "";
            $this->klientModifikuar->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // klientID
            $this->klientID->setupEditAttributes();
            $this->klientID->EditCustomAttributes = "";
            $this->klientID->EditValue = $this->klientID->CurrentValue;
            $this->klientID->ViewCustomAttributes = "";

            // klientTipi
            $this->klientTipi->EditCustomAttributes = "";
            $this->klientTipi->EditValue = $this->klientTipi->options(false);
            $this->klientTipi->PlaceHolder = RemoveHtml($this->klientTipi->caption());

            // klientEmertimi
            $this->klientEmertimi->setupEditAttributes();
            $this->klientEmertimi->EditCustomAttributes = "";
            if (!$this->klientEmertimi->Raw) {
                $this->klientEmertimi->CurrentValue = HtmlDecode($this->klientEmertimi->CurrentValue);
            }
            $this->klientEmertimi->EditValue = HtmlEncode($this->klientEmertimi->CurrentValue);
            $this->klientEmertimi->PlaceHolder = RemoveHtml($this->klientEmertimi->caption());

            // klientNIPT
            $this->klientNIPT->setupEditAttributes();
            $this->klientNIPT->EditCustomAttributes = "";
            if (!$this->klientNIPT->Raw) {
                $this->klientNIPT->CurrentValue = HtmlDecode($this->klientNIPT->CurrentValue);
            }
            $this->klientNIPT->EditValue = HtmlEncode($this->klientNIPT->CurrentValue);
            $this->klientNIPT->PlaceHolder = RemoveHtml($this->klientNIPT->caption());

            // klientAdresa
            $this->klientAdresa->setupEditAttributes();
            $this->klientAdresa->EditCustomAttributes = "";
            if (!$this->klientAdresa->Raw) {
                $this->klientAdresa->CurrentValue = HtmlDecode($this->klientAdresa->CurrentValue);
            }
            $this->klientAdresa->EditValue = HtmlEncode($this->klientAdresa->CurrentValue);
            $this->klientAdresa->PlaceHolder = RemoveHtml($this->klientAdresa->caption());

            // klientQyteti
            $this->klientQyteti->setupEditAttributes();
            $this->klientQyteti->EditCustomAttributes = "";
            if (!$this->klientQyteti->Raw) {
                $this->klientQyteti->CurrentValue = HtmlDecode($this->klientQyteti->CurrentValue);
            }
            $this->klientQyteti->EditValue = HtmlEncode($this->klientQyteti->CurrentValue);
            $this->klientQyteti->PlaceHolder = RemoveHtml($this->klientQyteti->caption());

            // klientTel1
            $this->klientTel1->setupEditAttributes();
            $this->klientTel1->EditCustomAttributes = "";
            if (!$this->klientTel1->Raw) {
                $this->klientTel1->CurrentValue = HtmlDecode($this->klientTel1->CurrentValue);
            }
            $this->klientTel1->EditValue = HtmlEncode($this->klientTel1->CurrentValue);
            $this->klientTel1->PlaceHolder = RemoveHtml($this->klientTel1->caption());

            // klientTel2
            $this->klientTel2->setupEditAttributes();
            $this->klientTel2->EditCustomAttributes = "";
            if (!$this->klientTel2->Raw) {
                $this->klientTel2->CurrentValue = HtmlDecode($this->klientTel2->CurrentValue);
            }
            $this->klientTel2->EditValue = HtmlEncode($this->klientTel2->CurrentValue);
            $this->klientTel2->PlaceHolder = RemoveHtml($this->klientTel2->caption());

            // klientEmail
            $this->klientEmail->setupEditAttributes();
            $this->klientEmail->EditCustomAttributes = "";
            if (!$this->klientEmail->Raw) {
                $this->klientEmail->CurrentValue = HtmlDecode($this->klientEmail->CurrentValue);
            }
            $this->klientEmail->EditValue = HtmlEncode($this->klientEmail->CurrentValue);
            $this->klientEmail->PlaceHolder = RemoveHtml($this->klientEmail->caption());

            // klientAutori

            // klientModifikuar

            // Edit refer script

            // klientID
            $this->klientID->LinkCustomAttributes = "";
            $this->klientID->HrefValue = "";

            // klientTipi
            $this->klientTipi->LinkCustomAttributes = "";
            $this->klientTipi->HrefValue = "";

            // klientEmertimi
            $this->klientEmertimi->LinkCustomAttributes = "";
            $this->klientEmertimi->HrefValue = "";

            // klientNIPT
            $this->klientNIPT->LinkCustomAttributes = "";
            $this->klientNIPT->HrefValue = "";

            // klientAdresa
            $this->klientAdresa->LinkCustomAttributes = "";
            $this->klientAdresa->HrefValue = "";

            // klientQyteti
            $this->klientQyteti->LinkCustomAttributes = "";
            $this->klientQyteti->HrefValue = "";

            // klientTel1
            $this->klientTel1->LinkCustomAttributes = "";
            $this->klientTel1->HrefValue = "";

            // klientTel2
            $this->klientTel2->LinkCustomAttributes = "";
            $this->klientTel2->HrefValue = "";

            // klientEmail
            $this->klientEmail->LinkCustomAttributes = "";
            $this->klientEmail->HrefValue = "";

            // klientAutori
            $this->klientAutori->LinkCustomAttributes = "";
            $this->klientAutori->HrefValue = "";

            // klientModifikuar
            $this->klientModifikuar->LinkCustomAttributes = "";
            $this->klientModifikuar->HrefValue = "";
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
        if ($this->klientID->Required) {
            if (!$this->klientID->IsDetailKey && EmptyValue($this->klientID->FormValue)) {
                $this->klientID->addErrorMessage(str_replace("%s", $this->klientID->caption(), $this->klientID->RequiredErrorMessage));
            }
        }
        if ($this->klientTipi->Required) {
            if ($this->klientTipi->FormValue == "") {
                $this->klientTipi->addErrorMessage(str_replace("%s", $this->klientTipi->caption(), $this->klientTipi->RequiredErrorMessage));
            }
        }
        if ($this->klientEmertimi->Required) {
            if (!$this->klientEmertimi->IsDetailKey && EmptyValue($this->klientEmertimi->FormValue)) {
                $this->klientEmertimi->addErrorMessage(str_replace("%s", $this->klientEmertimi->caption(), $this->klientEmertimi->RequiredErrorMessage));
            }
        }
        if ($this->klientNIPT->Required) {
            if (!$this->klientNIPT->IsDetailKey && EmptyValue($this->klientNIPT->FormValue)) {
                $this->klientNIPT->addErrorMessage(str_replace("%s", $this->klientNIPT->caption(), $this->klientNIPT->RequiredErrorMessage));
            }
        }
        if ($this->klientAdresa->Required) {
            if (!$this->klientAdresa->IsDetailKey && EmptyValue($this->klientAdresa->FormValue)) {
                $this->klientAdresa->addErrorMessage(str_replace("%s", $this->klientAdresa->caption(), $this->klientAdresa->RequiredErrorMessage));
            }
        }
        if ($this->klientQyteti->Required) {
            if (!$this->klientQyteti->IsDetailKey && EmptyValue($this->klientQyteti->FormValue)) {
                $this->klientQyteti->addErrorMessage(str_replace("%s", $this->klientQyteti->caption(), $this->klientQyteti->RequiredErrorMessage));
            }
        }
        if ($this->klientTel1->Required) {
            if (!$this->klientTel1->IsDetailKey && EmptyValue($this->klientTel1->FormValue)) {
                $this->klientTel1->addErrorMessage(str_replace("%s", $this->klientTel1->caption(), $this->klientTel1->RequiredErrorMessage));
            }
        }
        if ($this->klientTel2->Required) {
            if (!$this->klientTel2->IsDetailKey && EmptyValue($this->klientTel2->FormValue)) {
                $this->klientTel2->addErrorMessage(str_replace("%s", $this->klientTel2->caption(), $this->klientTel2->RequiredErrorMessage));
            }
        }
        if ($this->klientEmail->Required) {
            if (!$this->klientEmail->IsDetailKey && EmptyValue($this->klientEmail->FormValue)) {
                $this->klientEmail->addErrorMessage(str_replace("%s", $this->klientEmail->caption(), $this->klientEmail->RequiredErrorMessage));
            }
        }
        if (!CheckEmail($this->klientEmail->FormValue)) {
            $this->klientEmail->addErrorMessage($this->klientEmail->getErrorMessage(false));
        }
        if ($this->klientAutori->Required) {
            if (!$this->klientAutori->IsDetailKey && EmptyValue($this->klientAutori->FormValue)) {
                $this->klientAutori->addErrorMessage(str_replace("%s", $this->klientAutori->caption(), $this->klientAutori->RequiredErrorMessage));
            }
        }
        if ($this->klientModifikuar->Required) {
            if (!$this->klientModifikuar->IsDetailKey && EmptyValue($this->klientModifikuar->FormValue)) {
                $this->klientModifikuar->addErrorMessage(str_replace("%s", $this->klientModifikuar->caption(), $this->klientModifikuar->RequiredErrorMessage));
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

        // klientTipi
        $this->klientTipi->setDbValueDef($rsnew, $this->klientTipi->CurrentValue, "", $this->klientTipi->ReadOnly);

        // klientEmertimi
        $this->klientEmertimi->setDbValueDef($rsnew, $this->klientEmertimi->CurrentValue, "", $this->klientEmertimi->ReadOnly);

        // klientNIPT
        $this->klientNIPT->setDbValueDef($rsnew, $this->klientNIPT->CurrentValue, null, $this->klientNIPT->ReadOnly);

        // klientAdresa
        $this->klientAdresa->setDbValueDef($rsnew, $this->klientAdresa->CurrentValue, "", $this->klientAdresa->ReadOnly);

        // klientQyteti
        $this->klientQyteti->setDbValueDef($rsnew, $this->klientQyteti->CurrentValue, "", $this->klientQyteti->ReadOnly);

        // klientTel1
        $this->klientTel1->setDbValueDef($rsnew, $this->klientTel1->CurrentValue, "", $this->klientTel1->ReadOnly);

        // klientTel2
        $this->klientTel2->setDbValueDef($rsnew, $this->klientTel2->CurrentValue, null, $this->klientTel2->ReadOnly);

        // klientEmail
        $this->klientEmail->setDbValueDef($rsnew, $this->klientEmail->CurrentValue, null, $this->klientEmail->ReadOnly);

        // klientAutori
        $this->klientAutori->CurrentValue = CurrentUserID();
        $this->klientAutori->setDbValueDef($rsnew, $this->klientAutori->CurrentValue, 0);

        // klientModifikuar
        $this->klientModifikuar->CurrentValue = CurrentDateTime();
        $this->klientModifikuar->setDbValueDef($rsnew, $this->klientModifikuar->CurrentValue, null);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check field with unique index (klientNIPT)
        if ($this->klientNIPT->CurrentValue != "") {
            $filterChk = "(`klientNIPT` = '" . AdjustSql($this->klientNIPT->CurrentValue, $this->Dbid) . "')";
            $filterChk .= " AND NOT (" . $filter . ")";
            $this->CurrentFilter = $filterChk;
            $sqlChk = $this->getCurrentSql();
            $rsChk = $conn->executeQuery($sqlChk);
            if (!$rsChk) {
                return false;
            }
            if ($rsChk->fetch()) {
                $idxErrMsg = str_replace("%f", $this->klientNIPT->caption(), $Language->phrase("DupIndex"));
                $idxErrMsg = str_replace("%v", $this->klientNIPT->CurrentValue, $idxErrMsg);
                $this->setFailureMessage($idxErrMsg);
                return false;
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("KlientList"), "", $this->TableVar, true);
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
                case "x_klientTipi":
                    break;
                case "x_klientAutori":
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
