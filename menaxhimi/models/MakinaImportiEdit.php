<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaImportiEdit extends MakinaImporti
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina_importi';

    // Page object name
    public $PageObjName = "MakinaImportiEdit";

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

        // Table object (makina_importi)
        if (!isset($GLOBALS["makina_importi"]) || get_class($GLOBALS["makina_importi"]) == PROJECT_NAMESPACE . "makina_importi") {
            $GLOBALS["makina_importi"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'makina_importi');
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
                $tbl = Container("makina_importi");
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
                    if ($pageName == "MakinaImportiView") {
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
            $key .= @$ar['mimpID'];
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
            $this->mimpID->Visible = false;
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
        $this->mimpID->setVisibility();
        $this->mimpMarka->setVisibility();
        $this->mimpModeli->setVisibility();
        $this->mimpTipi->setVisibility();
        $this->mimpShasia->setVisibility();
        $this->mimpViti->setVisibility();
        $this->mimpKarburant->setVisibility();
        $this->mimpKambio->setVisibility();
        $this->mimpNgjyra->setVisibility();
        $this->mimpPrejardhja->setVisibility();
        $this->mimpInfo->setVisibility();
        $this->mimpCmimiBlerjes->setVisibility();
        $this->mimpDogana->setVisibility();
        $this->mimpTransporti->setVisibility();
        $this->mimpTjera->setVisibility();
        $this->mimpDtHyrjes->setVisibility();
        $this->mimpCmimiShitjes->setVisibility();
        $this->mimpGati->setVisibility();
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
        $this->setupLookupOptions($this->mimpMarka);
        $this->setupLookupOptions($this->mimpModeli);
        $this->setupLookupOptions($this->mimpTipi);
        $this->setupLookupOptions($this->mimpKarburant);
        $this->setupLookupOptions($this->mimpKambio);
        $this->setupLookupOptions($this->mimpGati);

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
            if (($keyValue = Get("mimpID") ?? Key(0) ?? Route(2)) !== null) {
                $this->mimpID->setQueryStringValue($keyValue);
                $this->mimpID->setOldValue($this->mimpID->QueryStringValue);
            } elseif (Post("mimpID") !== null) {
                $this->mimpID->setFormValue(Post("mimpID"));
                $this->mimpID->setOldValue($this->mimpID->FormValue);
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
                if (($keyValue = Get("mimpID") ?? Route("mimpID")) !== null) {
                    $this->mimpID->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->mimpID->CurrentValue = null;
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

            // Overwrite record, reload hash value
            if ($this->isOverwrite()) {
                $this->loadRowHash();
                $this->CurrentAction = "update";
            }

            // Set up detail parameters
            $this->setupDetailParms();
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
                        $this->terminate("MakinaImportiList"); // No matching record, return to list
                        return;
                    }

                // Set up detail parameters
                $this->setupDetailParms();
                break;
            case "update": // Update
                if ($this->getCurrentDetailTable() != "") { // Master/detail edit
                    $returnUrl = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $this->getCurrentDetailTable()); // Master/Detail view page
                } else {
                    $returnUrl = $this->getReturnUrl();
                }
                if (GetPageName($returnUrl) == "MakinaImportiList") {
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

                    // Set up detail parameters
                    $this->setupDetailParms();
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

        // Check field name 'mimpID' first before field var 'x_mimpID'
        $val = $CurrentForm->hasValue("mimpID") ? $CurrentForm->getValue("mimpID") : $CurrentForm->getValue("x_mimpID");
        if (!$this->mimpID->IsDetailKey) {
            $this->mimpID->setFormValue($val);
        }

        // Check field name 'mimpMarka' first before field var 'x_mimpMarka'
        $val = $CurrentForm->hasValue("mimpMarka") ? $CurrentForm->getValue("mimpMarka") : $CurrentForm->getValue("x_mimpMarka");
        if (!$this->mimpMarka->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpMarka->Visible = false; // Disable update for API request
            } else {
                $this->mimpMarka->setFormValue($val);
            }
        }

        // Check field name 'mimpModeli' first before field var 'x_mimpModeli'
        $val = $CurrentForm->hasValue("mimpModeli") ? $CurrentForm->getValue("mimpModeli") : $CurrentForm->getValue("x_mimpModeli");
        if (!$this->mimpModeli->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpModeli->Visible = false; // Disable update for API request
            } else {
                $this->mimpModeli->setFormValue($val);
            }
        }

        // Check field name 'mimpTipi' first before field var 'x_mimpTipi'
        $val = $CurrentForm->hasValue("mimpTipi") ? $CurrentForm->getValue("mimpTipi") : $CurrentForm->getValue("x_mimpTipi");
        if (!$this->mimpTipi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpTipi->Visible = false; // Disable update for API request
            } else {
                $this->mimpTipi->setFormValue($val);
            }
        }

        // Check field name 'mimpShasia' first before field var 'x_mimpShasia'
        $val = $CurrentForm->hasValue("mimpShasia") ? $CurrentForm->getValue("mimpShasia") : $CurrentForm->getValue("x_mimpShasia");
        if (!$this->mimpShasia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpShasia->Visible = false; // Disable update for API request
            } else {
                $this->mimpShasia->setFormValue($val);
            }
        }

        // Check field name 'mimpViti' first before field var 'x_mimpViti'
        $val = $CurrentForm->hasValue("mimpViti") ? $CurrentForm->getValue("mimpViti") : $CurrentForm->getValue("x_mimpViti");
        if (!$this->mimpViti->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpViti->Visible = false; // Disable update for API request
            } else {
                $this->mimpViti->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'mimpKarburant' first before field var 'x_mimpKarburant'
        $val = $CurrentForm->hasValue("mimpKarburant") ? $CurrentForm->getValue("mimpKarburant") : $CurrentForm->getValue("x_mimpKarburant");
        if (!$this->mimpKarburant->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpKarburant->Visible = false; // Disable update for API request
            } else {
                $this->mimpKarburant->setFormValue($val);
            }
        }

        // Check field name 'mimpKambio' first before field var 'x_mimpKambio'
        $val = $CurrentForm->hasValue("mimpKambio") ? $CurrentForm->getValue("mimpKambio") : $CurrentForm->getValue("x_mimpKambio");
        if (!$this->mimpKambio->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpKambio->Visible = false; // Disable update for API request
            } else {
                $this->mimpKambio->setFormValue($val);
            }
        }

        // Check field name 'mimpNgjyra' first before field var 'x_mimpNgjyra'
        $val = $CurrentForm->hasValue("mimpNgjyra") ? $CurrentForm->getValue("mimpNgjyra") : $CurrentForm->getValue("x_mimpNgjyra");
        if (!$this->mimpNgjyra->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpNgjyra->Visible = false; // Disable update for API request
            } else {
                $this->mimpNgjyra->setFormValue($val);
            }
        }

        // Check field name 'mimpPrejardhja' first before field var 'x_mimpPrejardhja'
        $val = $CurrentForm->hasValue("mimpPrejardhja") ? $CurrentForm->getValue("mimpPrejardhja") : $CurrentForm->getValue("x_mimpPrejardhja");
        if (!$this->mimpPrejardhja->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpPrejardhja->Visible = false; // Disable update for API request
            } else {
                $this->mimpPrejardhja->setFormValue($val);
            }
        }

        // Check field name 'mimpInfo' first before field var 'x_mimpInfo'
        $val = $CurrentForm->hasValue("mimpInfo") ? $CurrentForm->getValue("mimpInfo") : $CurrentForm->getValue("x_mimpInfo");
        if (!$this->mimpInfo->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpInfo->Visible = false; // Disable update for API request
            } else {
                $this->mimpInfo->setFormValue($val);
            }
        }

        // Check field name 'mimpCmimiBlerjes' first before field var 'x_mimpCmimiBlerjes'
        $val = $CurrentForm->hasValue("mimpCmimiBlerjes") ? $CurrentForm->getValue("mimpCmimiBlerjes") : $CurrentForm->getValue("x_mimpCmimiBlerjes");
        if (!$this->mimpCmimiBlerjes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpCmimiBlerjes->Visible = false; // Disable update for API request
            } else {
                $this->mimpCmimiBlerjes->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'mimpDogana' first before field var 'x_mimpDogana'
        $val = $CurrentForm->hasValue("mimpDogana") ? $CurrentForm->getValue("mimpDogana") : $CurrentForm->getValue("x_mimpDogana");
        if (!$this->mimpDogana->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpDogana->Visible = false; // Disable update for API request
            } else {
                $this->mimpDogana->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'mimpTransporti' first before field var 'x_mimpTransporti'
        $val = $CurrentForm->hasValue("mimpTransporti") ? $CurrentForm->getValue("mimpTransporti") : $CurrentForm->getValue("x_mimpTransporti");
        if (!$this->mimpTransporti->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpTransporti->Visible = false; // Disable update for API request
            } else {
                $this->mimpTransporti->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'mimpTjera' first before field var 'x_mimpTjera'
        $val = $CurrentForm->hasValue("mimpTjera") ? $CurrentForm->getValue("mimpTjera") : $CurrentForm->getValue("x_mimpTjera");
        if (!$this->mimpTjera->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpTjera->Visible = false; // Disable update for API request
            } else {
                $this->mimpTjera->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'mimpDtHyrjes' first before field var 'x_mimpDtHyrjes'
        $val = $CurrentForm->hasValue("mimpDtHyrjes") ? $CurrentForm->getValue("mimpDtHyrjes") : $CurrentForm->getValue("x_mimpDtHyrjes");
        if (!$this->mimpDtHyrjes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpDtHyrjes->Visible = false; // Disable update for API request
            } else {
                $this->mimpDtHyrjes->setFormValue($val, true, $validate);
            }
            $this->mimpDtHyrjes->CurrentValue = UnFormatDateTime($this->mimpDtHyrjes->CurrentValue, $this->mimpDtHyrjes->formatPattern());
        }

        // Check field name 'mimpCmimiShitjes' first before field var 'x_mimpCmimiShitjes'
        $val = $CurrentForm->hasValue("mimpCmimiShitjes") ? $CurrentForm->getValue("mimpCmimiShitjes") : $CurrentForm->getValue("x_mimpCmimiShitjes");
        if (!$this->mimpCmimiShitjes->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpCmimiShitjes->Visible = false; // Disable update for API request
            } else {
                $this->mimpCmimiShitjes->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'mimpGati' first before field var 'x_mimpGati'
        $val = $CurrentForm->hasValue("mimpGati") ? $CurrentForm->getValue("mimpGati") : $CurrentForm->getValue("x_mimpGati");
        if (!$this->mimpGati->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->mimpGati->Visible = false; // Disable update for API request
            } else {
                $this->mimpGati->setFormValue($val);
            }
        }
        if (!$this->isOverwrite()) {
            $this->HashValue = $CurrentForm->getValue("k_hash");
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->mimpID->CurrentValue = $this->mimpID->FormValue;
        $this->mimpMarka->CurrentValue = $this->mimpMarka->FormValue;
        $this->mimpModeli->CurrentValue = $this->mimpModeli->FormValue;
        $this->mimpTipi->CurrentValue = $this->mimpTipi->FormValue;
        $this->mimpShasia->CurrentValue = $this->mimpShasia->FormValue;
        $this->mimpViti->CurrentValue = $this->mimpViti->FormValue;
        $this->mimpKarburant->CurrentValue = $this->mimpKarburant->FormValue;
        $this->mimpKambio->CurrentValue = $this->mimpKambio->FormValue;
        $this->mimpNgjyra->CurrentValue = $this->mimpNgjyra->FormValue;
        $this->mimpPrejardhja->CurrentValue = $this->mimpPrejardhja->FormValue;
        $this->mimpInfo->CurrentValue = $this->mimpInfo->FormValue;
        $this->mimpCmimiBlerjes->CurrentValue = $this->mimpCmimiBlerjes->FormValue;
        $this->mimpDogana->CurrentValue = $this->mimpDogana->FormValue;
        $this->mimpTransporti->CurrentValue = $this->mimpTransporti->FormValue;
        $this->mimpTjera->CurrentValue = $this->mimpTjera->FormValue;
        $this->mimpDtHyrjes->CurrentValue = $this->mimpDtHyrjes->FormValue;
        $this->mimpDtHyrjes->CurrentValue = UnFormatDateTime($this->mimpDtHyrjes->CurrentValue, $this->mimpDtHyrjes->formatPattern());
        $this->mimpCmimiShitjes->CurrentValue = $this->mimpCmimiShitjes->FormValue;
        $this->mimpGati->CurrentValue = $this->mimpGati->FormValue;
        if (!$this->isOverwrite()) {
            $this->HashValue = $CurrentForm->getValue("k_hash");
        }
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
            if (!$this->EventCancelled) {
                $this->HashValue = $this->getRowHash($row); // Get hash value for record
            }
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
        $this->mimpID->setDbValue($row['mimpID']);
        $this->mimpMarka->setDbValue($row['mimpMarka']);
        $this->mimpModeli->setDbValue($row['mimpModeli']);
        $this->mimpTipi->setDbValue($row['mimpTipi']);
        $this->mimpShasia->setDbValue($row['mimpShasia']);
        $this->mimpViti->setDbValue($row['mimpViti']);
        $this->mimpKarburant->setDbValue($row['mimpKarburant']);
        $this->mimpKambio->setDbValue($row['mimpKambio']);
        $this->mimpNgjyra->setDbValue($row['mimpNgjyra']);
        $this->mimpPrejardhja->setDbValue($row['mimpPrejardhja']);
        $this->mimpInfo->setDbValue($row['mimpInfo']);
        $this->mimpCmimiBlerjes->setDbValue($row['mimpCmimiBlerjes']);
        $this->mimpDogana->setDbValue($row['mimpDogana']);
        $this->mimpTransporti->setDbValue($row['mimpTransporti']);
        $this->mimpTjera->setDbValue($row['mimpTjera']);
        $this->mimpDtHyrjes->setDbValue($row['mimpDtHyrjes']);
        $this->mimpCmimiShitjes->setDbValue($row['mimpCmimiShitjes']);
        $this->mimpGati->setDbValue($row['mimpGati']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['mimpID'] = $this->mimpID->DefaultValue;
        $row['mimpMarka'] = $this->mimpMarka->DefaultValue;
        $row['mimpModeli'] = $this->mimpModeli->DefaultValue;
        $row['mimpTipi'] = $this->mimpTipi->DefaultValue;
        $row['mimpShasia'] = $this->mimpShasia->DefaultValue;
        $row['mimpViti'] = $this->mimpViti->DefaultValue;
        $row['mimpKarburant'] = $this->mimpKarburant->DefaultValue;
        $row['mimpKambio'] = $this->mimpKambio->DefaultValue;
        $row['mimpNgjyra'] = $this->mimpNgjyra->DefaultValue;
        $row['mimpPrejardhja'] = $this->mimpPrejardhja->DefaultValue;
        $row['mimpInfo'] = $this->mimpInfo->DefaultValue;
        $row['mimpCmimiBlerjes'] = $this->mimpCmimiBlerjes->DefaultValue;
        $row['mimpDogana'] = $this->mimpDogana->DefaultValue;
        $row['mimpTransporti'] = $this->mimpTransporti->DefaultValue;
        $row['mimpTjera'] = $this->mimpTjera->DefaultValue;
        $row['mimpDtHyrjes'] = $this->mimpDtHyrjes->DefaultValue;
        $row['mimpCmimiShitjes'] = $this->mimpCmimiShitjes->DefaultValue;
        $row['mimpGati'] = $this->mimpGati->DefaultValue;
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

        // mimpID
        $this->mimpID->RowCssClass = "row";

        // mimpMarka
        $this->mimpMarka->RowCssClass = "row";

        // mimpModeli
        $this->mimpModeli->RowCssClass = "row";

        // mimpTipi
        $this->mimpTipi->RowCssClass = "row";

        // mimpShasia
        $this->mimpShasia->RowCssClass = "row";

        // mimpViti
        $this->mimpViti->RowCssClass = "row";

        // mimpKarburant
        $this->mimpKarburant->RowCssClass = "row";

        // mimpKambio
        $this->mimpKambio->RowCssClass = "row";

        // mimpNgjyra
        $this->mimpNgjyra->RowCssClass = "row";

        // mimpPrejardhja
        $this->mimpPrejardhja->RowCssClass = "row";

        // mimpInfo
        $this->mimpInfo->RowCssClass = "row";

        // mimpCmimiBlerjes
        $this->mimpCmimiBlerjes->RowCssClass = "row";

        // mimpDogana
        $this->mimpDogana->RowCssClass = "row";

        // mimpTransporti
        $this->mimpTransporti->RowCssClass = "row";

        // mimpTjera
        $this->mimpTjera->RowCssClass = "row";

        // mimpDtHyrjes
        $this->mimpDtHyrjes->RowCssClass = "row";

        // mimpCmimiShitjes
        $this->mimpCmimiShitjes->RowCssClass = "row";

        // mimpGati
        $this->mimpGati->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // mimpID
            $this->mimpID->ViewValue = $this->mimpID->CurrentValue;
            $this->mimpID->ViewCustomAttributes = "";

            // mimpMarka
            $curVal = strval($this->mimpMarka->CurrentValue);
            if ($curVal != "") {
                $this->mimpMarka->ViewValue = $this->mimpMarka->lookupCacheOption($curVal);
                if ($this->mimpMarka->ViewValue === null) { // Lookup from database
                    $filterWrk = "`mmarkaID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->mimpMarka->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->mimpMarka->Lookup->renderViewRow($rswrk[0]);
                        $this->mimpMarka->ViewValue = $this->mimpMarka->displayValue($arwrk);
                    } else {
                        $this->mimpMarka->ViewValue = FormatNumber($this->mimpMarka->CurrentValue, $this->mimpMarka->formatPattern());
                    }
                }
            } else {
                $this->mimpMarka->ViewValue = null;
            }
            $this->mimpMarka->ViewCustomAttributes = "";

            // mimpModeli
            $curVal = strval($this->mimpModeli->CurrentValue);
            if ($curVal != "") {
                $this->mimpModeli->ViewValue = $this->mimpModeli->lookupCacheOption($curVal);
                if ($this->mimpModeli->ViewValue === null) { // Lookup from database
                    $filterWrk = "`mmodeliID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->mimpModeli->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->mimpModeli->Lookup->renderViewRow($rswrk[0]);
                        $this->mimpModeli->ViewValue = $this->mimpModeli->displayValue($arwrk);
                    } else {
                        $this->mimpModeli->ViewValue = FormatNumber($this->mimpModeli->CurrentValue, $this->mimpModeli->formatPattern());
                    }
                }
            } else {
                $this->mimpModeli->ViewValue = null;
            }
            $this->mimpModeli->ViewCustomAttributes = "";

            // mimpTipi
            $curVal = strval($this->mimpTipi->CurrentValue);
            if ($curVal != "") {
                $this->mimpTipi->ViewValue = $this->mimpTipi->lookupCacheOption($curVal);
                if ($this->mimpTipi->ViewValue === null) { // Lookup from database
                    $filterWrk = "`mtipiID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->mimpTipi->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->mimpTipi->Lookup->renderViewRow($rswrk[0]);
                        $this->mimpTipi->ViewValue = $this->mimpTipi->displayValue($arwrk);
                    } else {
                        $this->mimpTipi->ViewValue = FormatNumber($this->mimpTipi->CurrentValue, $this->mimpTipi->formatPattern());
                    }
                }
            } else {
                $this->mimpTipi->ViewValue = null;
            }
            $this->mimpTipi->ViewCustomAttributes = "";

            // mimpShasia
            $this->mimpShasia->ViewValue = $this->mimpShasia->CurrentValue;
            $this->mimpShasia->ViewCustomAttributes = "";

            // mimpViti
            $this->mimpViti->ViewValue = $this->mimpViti->CurrentValue;
            $this->mimpViti->ViewValue = FormatNumber($this->mimpViti->ViewValue, $this->mimpViti->formatPattern());
            $this->mimpViti->ViewCustomAttributes = "";

            // mimpKarburant
            if (strval($this->mimpKarburant->CurrentValue) != "") {
                $this->mimpKarburant->ViewValue = $this->mimpKarburant->optionCaption($this->mimpKarburant->CurrentValue);
            } else {
                $this->mimpKarburant->ViewValue = null;
            }
            $this->mimpKarburant->ViewCustomAttributes = "";

            // mimpKambio
            if (strval($this->mimpKambio->CurrentValue) != "") {
                $this->mimpKambio->ViewValue = $this->mimpKambio->optionCaption($this->mimpKambio->CurrentValue);
            } else {
                $this->mimpKambio->ViewValue = null;
            }
            $this->mimpKambio->ViewCustomAttributes = "";

            // mimpNgjyra
            $this->mimpNgjyra->ViewValue = $this->mimpNgjyra->CurrentValue;
            $this->mimpNgjyra->ViewCustomAttributes = "";

            // mimpPrejardhja
            $this->mimpPrejardhja->ViewValue = $this->mimpPrejardhja->CurrentValue;
            $this->mimpPrejardhja->ViewCustomAttributes = "";

            // mimpInfo
            $this->mimpInfo->ViewValue = $this->mimpInfo->CurrentValue;
            $this->mimpInfo->ViewCustomAttributes = "";

            // mimpCmimiBlerjes
            $this->mimpCmimiBlerjes->ViewValue = $this->mimpCmimiBlerjes->CurrentValue;
            $this->mimpCmimiBlerjes->ViewValue = FormatNumber($this->mimpCmimiBlerjes->ViewValue, $this->mimpCmimiBlerjes->formatPattern());
            $this->mimpCmimiBlerjes->ViewCustomAttributes = "";

            // mimpDogana
            $this->mimpDogana->ViewValue = $this->mimpDogana->CurrentValue;
            $this->mimpDogana->ViewValue = FormatNumber($this->mimpDogana->ViewValue, $this->mimpDogana->formatPattern());
            $this->mimpDogana->ViewCustomAttributes = "";

            // mimpTransporti
            $this->mimpTransporti->ViewValue = $this->mimpTransporti->CurrentValue;
            $this->mimpTransporti->ViewValue = FormatNumber($this->mimpTransporti->ViewValue, $this->mimpTransporti->formatPattern());
            $this->mimpTransporti->ViewCustomAttributes = "";

            // mimpTjera
            $this->mimpTjera->ViewValue = $this->mimpTjera->CurrentValue;
            $this->mimpTjera->ViewValue = FormatNumber($this->mimpTjera->ViewValue, $this->mimpTjera->formatPattern());
            $this->mimpTjera->ViewCustomAttributes = "";

            // mimpDtHyrjes
            $this->mimpDtHyrjes->ViewValue = $this->mimpDtHyrjes->CurrentValue;
            $this->mimpDtHyrjes->ViewValue = FormatDateTime($this->mimpDtHyrjes->ViewValue, $this->mimpDtHyrjes->formatPattern());
            $this->mimpDtHyrjes->ViewCustomAttributes = "";

            // mimpCmimiShitjes
            $this->mimpCmimiShitjes->ViewValue = $this->mimpCmimiShitjes->CurrentValue;
            $this->mimpCmimiShitjes->ViewValue = FormatNumber($this->mimpCmimiShitjes->ViewValue, $this->mimpCmimiShitjes->formatPattern());
            $this->mimpCmimiShitjes->ViewCustomAttributes = "";

            // mimpGati
            if (strval($this->mimpGati->CurrentValue) != "") {
                $this->mimpGati->ViewValue = $this->mimpGati->optionCaption($this->mimpGati->CurrentValue);
            } else {
                $this->mimpGati->ViewValue = null;
            }
            $this->mimpGati->ViewCustomAttributes = "";

            // mimpID
            $this->mimpID->LinkCustomAttributes = "";
            $this->mimpID->HrefValue = "";

            // mimpMarka
            $this->mimpMarka->LinkCustomAttributes = "";
            $this->mimpMarka->HrefValue = "";

            // mimpModeli
            $this->mimpModeli->LinkCustomAttributes = "";
            $this->mimpModeli->HrefValue = "";

            // mimpTipi
            $this->mimpTipi->LinkCustomAttributes = "";
            $this->mimpTipi->HrefValue = "";

            // mimpShasia
            $this->mimpShasia->LinkCustomAttributes = "";
            $this->mimpShasia->HrefValue = "";

            // mimpViti
            $this->mimpViti->LinkCustomAttributes = "";
            $this->mimpViti->HrefValue = "";

            // mimpKarburant
            $this->mimpKarburant->LinkCustomAttributes = "";
            $this->mimpKarburant->HrefValue = "";

            // mimpKambio
            $this->mimpKambio->LinkCustomAttributes = "";
            $this->mimpKambio->HrefValue = "";

            // mimpNgjyra
            $this->mimpNgjyra->LinkCustomAttributes = "";
            $this->mimpNgjyra->HrefValue = "";

            // mimpPrejardhja
            $this->mimpPrejardhja->LinkCustomAttributes = "";
            $this->mimpPrejardhja->HrefValue = "";

            // mimpInfo
            $this->mimpInfo->LinkCustomAttributes = "";
            $this->mimpInfo->HrefValue = "";

            // mimpCmimiBlerjes
            $this->mimpCmimiBlerjes->LinkCustomAttributes = "";
            $this->mimpCmimiBlerjes->HrefValue = "";

            // mimpDogana
            $this->mimpDogana->LinkCustomAttributes = "";
            $this->mimpDogana->HrefValue = "";

            // mimpTransporti
            $this->mimpTransporti->LinkCustomAttributes = "";
            $this->mimpTransporti->HrefValue = "";

            // mimpTjera
            $this->mimpTjera->LinkCustomAttributes = "";
            $this->mimpTjera->HrefValue = "";

            // mimpDtHyrjes
            $this->mimpDtHyrjes->LinkCustomAttributes = "";
            $this->mimpDtHyrjes->HrefValue = "";

            // mimpCmimiShitjes
            $this->mimpCmimiShitjes->LinkCustomAttributes = "";
            $this->mimpCmimiShitjes->HrefValue = "";

            // mimpGati
            $this->mimpGati->LinkCustomAttributes = "";
            $this->mimpGati->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // mimpID
            $this->mimpID->setupEditAttributes();
            $this->mimpID->EditCustomAttributes = "";
            $this->mimpID->EditValue = $this->mimpID->CurrentValue;
            $this->mimpID->ViewCustomAttributes = "";

            // mimpMarka
            $this->mimpMarka->setupEditAttributes();
            $this->mimpMarka->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpMarka->CurrentValue));
            if ($curVal != "") {
                $this->mimpMarka->ViewValue = $this->mimpMarka->lookupCacheOption($curVal);
            } else {
                $this->mimpMarka->ViewValue = $this->mimpMarka->Lookup !== null && is_array($this->mimpMarka->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpMarka->ViewValue !== null) { // Load from cache
                $this->mimpMarka->EditValue = array_values($this->mimpMarka->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmarkaID`" . SearchString("=", $this->mimpMarka->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mimpMarka->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mimpMarka->EditValue = $arwrk;
            }
            $this->mimpMarka->PlaceHolder = RemoveHtml($this->mimpMarka->caption());

            // mimpModeli
            $this->mimpModeli->setupEditAttributes();
            $this->mimpModeli->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpModeli->CurrentValue));
            if ($curVal != "") {
                $this->mimpModeli->ViewValue = $this->mimpModeli->lookupCacheOption($curVal);
            } else {
                $this->mimpModeli->ViewValue = $this->mimpModeli->Lookup !== null && is_array($this->mimpModeli->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpModeli->ViewValue !== null) { // Load from cache
                $this->mimpModeli->EditValue = array_values($this->mimpModeli->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmodeliID`" . SearchString("=", $this->mimpModeli->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mimpModeli->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mimpModeli->EditValue = $arwrk;
            }
            $this->mimpModeli->PlaceHolder = RemoveHtml($this->mimpModeli->caption());

            // mimpTipi
            $this->mimpTipi->setupEditAttributes();
            $this->mimpTipi->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpTipi->CurrentValue));
            if ($curVal != "") {
                $this->mimpTipi->ViewValue = $this->mimpTipi->lookupCacheOption($curVal);
            } else {
                $this->mimpTipi->ViewValue = $this->mimpTipi->Lookup !== null && is_array($this->mimpTipi->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpTipi->ViewValue !== null) { // Load from cache
                $this->mimpTipi->EditValue = array_values($this->mimpTipi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mtipiID`" . SearchString("=", $this->mimpTipi->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mimpTipi->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mimpTipi->EditValue = $arwrk;
            }
            $this->mimpTipi->PlaceHolder = RemoveHtml($this->mimpTipi->caption());

            // mimpShasia
            $this->mimpShasia->setupEditAttributes();
            $this->mimpShasia->EditCustomAttributes = "";
            if (!$this->mimpShasia->Raw) {
                $this->mimpShasia->CurrentValue = HtmlDecode($this->mimpShasia->CurrentValue);
            }
            $this->mimpShasia->EditValue = HtmlEncode($this->mimpShasia->CurrentValue);
            $this->mimpShasia->PlaceHolder = RemoveHtml($this->mimpShasia->caption());

            // mimpViti
            $this->mimpViti->setupEditAttributes();
            $this->mimpViti->EditCustomAttributes = "";
            $this->mimpViti->EditValue = HtmlEncode($this->mimpViti->CurrentValue);
            $this->mimpViti->PlaceHolder = RemoveHtml($this->mimpViti->caption());
            if (strval($this->mimpViti->EditValue) != "" && is_numeric($this->mimpViti->EditValue)) {
                $this->mimpViti->EditValue = FormatNumber($this->mimpViti->EditValue, null);
            }

            // mimpKarburant
            $this->mimpKarburant->EditCustomAttributes = "";
            $this->mimpKarburant->EditValue = $this->mimpKarburant->options(false);
            $this->mimpKarburant->PlaceHolder = RemoveHtml($this->mimpKarburant->caption());

            // mimpKambio
            $this->mimpKambio->EditCustomAttributes = "";
            $this->mimpKambio->EditValue = $this->mimpKambio->options(false);
            $this->mimpKambio->PlaceHolder = RemoveHtml($this->mimpKambio->caption());

            // mimpNgjyra
            $this->mimpNgjyra->setupEditAttributes();
            $this->mimpNgjyra->EditCustomAttributes = "";
            if (!$this->mimpNgjyra->Raw) {
                $this->mimpNgjyra->CurrentValue = HtmlDecode($this->mimpNgjyra->CurrentValue);
            }
            $this->mimpNgjyra->EditValue = HtmlEncode($this->mimpNgjyra->CurrentValue);
            $this->mimpNgjyra->PlaceHolder = RemoveHtml($this->mimpNgjyra->caption());

            // mimpPrejardhja
            $this->mimpPrejardhja->setupEditAttributes();
            $this->mimpPrejardhja->EditCustomAttributes = "";
            if (!$this->mimpPrejardhja->Raw) {
                $this->mimpPrejardhja->CurrentValue = HtmlDecode($this->mimpPrejardhja->CurrentValue);
            }
            $this->mimpPrejardhja->EditValue = HtmlEncode($this->mimpPrejardhja->CurrentValue);
            $this->mimpPrejardhja->PlaceHolder = RemoveHtml($this->mimpPrejardhja->caption());

            // mimpInfo
            $this->mimpInfo->setupEditAttributes();
            $this->mimpInfo->EditCustomAttributes = "";
            $this->mimpInfo->EditValue = HtmlEncode($this->mimpInfo->CurrentValue);
            $this->mimpInfo->PlaceHolder = RemoveHtml($this->mimpInfo->caption());

            // mimpCmimiBlerjes
            $this->mimpCmimiBlerjes->setupEditAttributes();
            $this->mimpCmimiBlerjes->EditCustomAttributes = "";
            $this->mimpCmimiBlerjes->EditValue = HtmlEncode($this->mimpCmimiBlerjes->CurrentValue);
            $this->mimpCmimiBlerjes->PlaceHolder = RemoveHtml($this->mimpCmimiBlerjes->caption());
            if (strval($this->mimpCmimiBlerjes->EditValue) != "" && is_numeric($this->mimpCmimiBlerjes->EditValue)) {
                $this->mimpCmimiBlerjes->EditValue = FormatNumber($this->mimpCmimiBlerjes->EditValue, null);
            }

            // mimpDogana
            $this->mimpDogana->setupEditAttributes();
            $this->mimpDogana->EditCustomAttributes = "";
            $this->mimpDogana->EditValue = HtmlEncode($this->mimpDogana->CurrentValue);
            $this->mimpDogana->PlaceHolder = RemoveHtml($this->mimpDogana->caption());
            if (strval($this->mimpDogana->EditValue) != "" && is_numeric($this->mimpDogana->EditValue)) {
                $this->mimpDogana->EditValue = FormatNumber($this->mimpDogana->EditValue, null);
            }

            // mimpTransporti
            $this->mimpTransporti->setupEditAttributes();
            $this->mimpTransporti->EditCustomAttributes = "";
            $this->mimpTransporti->EditValue = HtmlEncode($this->mimpTransporti->CurrentValue);
            $this->mimpTransporti->PlaceHolder = RemoveHtml($this->mimpTransporti->caption());
            if (strval($this->mimpTransporti->EditValue) != "" && is_numeric($this->mimpTransporti->EditValue)) {
                $this->mimpTransporti->EditValue = FormatNumber($this->mimpTransporti->EditValue, null);
            }

            // mimpTjera
            $this->mimpTjera->setupEditAttributes();
            $this->mimpTjera->EditCustomAttributes = "";
            $this->mimpTjera->EditValue = HtmlEncode($this->mimpTjera->CurrentValue);
            $this->mimpTjera->PlaceHolder = RemoveHtml($this->mimpTjera->caption());
            if (strval($this->mimpTjera->EditValue) != "" && is_numeric($this->mimpTjera->EditValue)) {
                $this->mimpTjera->EditValue = FormatNumber($this->mimpTjera->EditValue, null);
            }

            // mimpDtHyrjes
            $this->mimpDtHyrjes->setupEditAttributes();
            $this->mimpDtHyrjes->EditCustomAttributes = "";
            $this->mimpDtHyrjes->EditValue = HtmlEncode(FormatDateTime($this->mimpDtHyrjes->CurrentValue, $this->mimpDtHyrjes->formatPattern()));
            $this->mimpDtHyrjes->PlaceHolder = RemoveHtml($this->mimpDtHyrjes->caption());

            // mimpCmimiShitjes
            $this->mimpCmimiShitjes->setupEditAttributes();
            $this->mimpCmimiShitjes->EditCustomAttributes = "";
            $this->mimpCmimiShitjes->EditValue = HtmlEncode($this->mimpCmimiShitjes->CurrentValue);
            $this->mimpCmimiShitjes->PlaceHolder = RemoveHtml($this->mimpCmimiShitjes->caption());
            if (strval($this->mimpCmimiShitjes->EditValue) != "" && is_numeric($this->mimpCmimiShitjes->EditValue)) {
                $this->mimpCmimiShitjes->EditValue = FormatNumber($this->mimpCmimiShitjes->EditValue, null);
            }

            // mimpGati
            $this->mimpGati->EditCustomAttributes = "";
            $this->mimpGati->EditValue = $this->mimpGati->options(false);
            $this->mimpGati->PlaceHolder = RemoveHtml($this->mimpGati->caption());

            // Edit refer script

            // mimpID
            $this->mimpID->LinkCustomAttributes = "";
            $this->mimpID->HrefValue = "";

            // mimpMarka
            $this->mimpMarka->LinkCustomAttributes = "";
            $this->mimpMarka->HrefValue = "";

            // mimpModeli
            $this->mimpModeli->LinkCustomAttributes = "";
            $this->mimpModeli->HrefValue = "";

            // mimpTipi
            $this->mimpTipi->LinkCustomAttributes = "";
            $this->mimpTipi->HrefValue = "";

            // mimpShasia
            $this->mimpShasia->LinkCustomAttributes = "";
            $this->mimpShasia->HrefValue = "";

            // mimpViti
            $this->mimpViti->LinkCustomAttributes = "";
            $this->mimpViti->HrefValue = "";

            // mimpKarburant
            $this->mimpKarburant->LinkCustomAttributes = "";
            $this->mimpKarburant->HrefValue = "";

            // mimpKambio
            $this->mimpKambio->LinkCustomAttributes = "";
            $this->mimpKambio->HrefValue = "";

            // mimpNgjyra
            $this->mimpNgjyra->LinkCustomAttributes = "";
            $this->mimpNgjyra->HrefValue = "";

            // mimpPrejardhja
            $this->mimpPrejardhja->LinkCustomAttributes = "";
            $this->mimpPrejardhja->HrefValue = "";

            // mimpInfo
            $this->mimpInfo->LinkCustomAttributes = "";
            $this->mimpInfo->HrefValue = "";

            // mimpCmimiBlerjes
            $this->mimpCmimiBlerjes->LinkCustomAttributes = "";
            $this->mimpCmimiBlerjes->HrefValue = "";

            // mimpDogana
            $this->mimpDogana->LinkCustomAttributes = "";
            $this->mimpDogana->HrefValue = "";

            // mimpTransporti
            $this->mimpTransporti->LinkCustomAttributes = "";
            $this->mimpTransporti->HrefValue = "";

            // mimpTjera
            $this->mimpTjera->LinkCustomAttributes = "";
            $this->mimpTjera->HrefValue = "";

            // mimpDtHyrjes
            $this->mimpDtHyrjes->LinkCustomAttributes = "";
            $this->mimpDtHyrjes->HrefValue = "";

            // mimpCmimiShitjes
            $this->mimpCmimiShitjes->LinkCustomAttributes = "";
            $this->mimpCmimiShitjes->HrefValue = "";

            // mimpGati
            $this->mimpGati->LinkCustomAttributes = "";
            $this->mimpGati->HrefValue = "";
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
        if ($this->mimpID->Required) {
            if (!$this->mimpID->IsDetailKey && EmptyValue($this->mimpID->FormValue)) {
                $this->mimpID->addErrorMessage(str_replace("%s", $this->mimpID->caption(), $this->mimpID->RequiredErrorMessage));
            }
        }
        if ($this->mimpMarka->Required) {
            if (!$this->mimpMarka->IsDetailKey && EmptyValue($this->mimpMarka->FormValue)) {
                $this->mimpMarka->addErrorMessage(str_replace("%s", $this->mimpMarka->caption(), $this->mimpMarka->RequiredErrorMessage));
            }
        }
        if ($this->mimpModeli->Required) {
            if (!$this->mimpModeli->IsDetailKey && EmptyValue($this->mimpModeli->FormValue)) {
                $this->mimpModeli->addErrorMessage(str_replace("%s", $this->mimpModeli->caption(), $this->mimpModeli->RequiredErrorMessage));
            }
        }
        if ($this->mimpTipi->Required) {
            if (!$this->mimpTipi->IsDetailKey && EmptyValue($this->mimpTipi->FormValue)) {
                $this->mimpTipi->addErrorMessage(str_replace("%s", $this->mimpTipi->caption(), $this->mimpTipi->RequiredErrorMessage));
            }
        }
        if ($this->mimpShasia->Required) {
            if (!$this->mimpShasia->IsDetailKey && EmptyValue($this->mimpShasia->FormValue)) {
                $this->mimpShasia->addErrorMessage(str_replace("%s", $this->mimpShasia->caption(), $this->mimpShasia->RequiredErrorMessage));
            }
        }
        if ($this->mimpViti->Required) {
            if (!$this->mimpViti->IsDetailKey && EmptyValue($this->mimpViti->FormValue)) {
                $this->mimpViti->addErrorMessage(str_replace("%s", $this->mimpViti->caption(), $this->mimpViti->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->mimpViti->FormValue)) {
            $this->mimpViti->addErrorMessage($this->mimpViti->getErrorMessage(false));
        }
        if ($this->mimpKarburant->Required) {
            if ($this->mimpKarburant->FormValue == "") {
                $this->mimpKarburant->addErrorMessage(str_replace("%s", $this->mimpKarburant->caption(), $this->mimpKarburant->RequiredErrorMessage));
            }
        }
        if ($this->mimpKambio->Required) {
            if ($this->mimpKambio->FormValue == "") {
                $this->mimpKambio->addErrorMessage(str_replace("%s", $this->mimpKambio->caption(), $this->mimpKambio->RequiredErrorMessage));
            }
        }
        if ($this->mimpNgjyra->Required) {
            if (!$this->mimpNgjyra->IsDetailKey && EmptyValue($this->mimpNgjyra->FormValue)) {
                $this->mimpNgjyra->addErrorMessage(str_replace("%s", $this->mimpNgjyra->caption(), $this->mimpNgjyra->RequiredErrorMessage));
            }
        }
        if ($this->mimpPrejardhja->Required) {
            if (!$this->mimpPrejardhja->IsDetailKey && EmptyValue($this->mimpPrejardhja->FormValue)) {
                $this->mimpPrejardhja->addErrorMessage(str_replace("%s", $this->mimpPrejardhja->caption(), $this->mimpPrejardhja->RequiredErrorMessage));
            }
        }
        if ($this->mimpInfo->Required) {
            if (!$this->mimpInfo->IsDetailKey && EmptyValue($this->mimpInfo->FormValue)) {
                $this->mimpInfo->addErrorMessage(str_replace("%s", $this->mimpInfo->caption(), $this->mimpInfo->RequiredErrorMessage));
            }
        }
        if ($this->mimpCmimiBlerjes->Required) {
            if (!$this->mimpCmimiBlerjes->IsDetailKey && EmptyValue($this->mimpCmimiBlerjes->FormValue)) {
                $this->mimpCmimiBlerjes->addErrorMessage(str_replace("%s", $this->mimpCmimiBlerjes->caption(), $this->mimpCmimiBlerjes->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->mimpCmimiBlerjes->FormValue)) {
            $this->mimpCmimiBlerjes->addErrorMessage($this->mimpCmimiBlerjes->getErrorMessage(false));
        }
        if ($this->mimpDogana->Required) {
            if (!$this->mimpDogana->IsDetailKey && EmptyValue($this->mimpDogana->FormValue)) {
                $this->mimpDogana->addErrorMessage(str_replace("%s", $this->mimpDogana->caption(), $this->mimpDogana->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->mimpDogana->FormValue)) {
            $this->mimpDogana->addErrorMessage($this->mimpDogana->getErrorMessage(false));
        }
        if ($this->mimpTransporti->Required) {
            if (!$this->mimpTransporti->IsDetailKey && EmptyValue($this->mimpTransporti->FormValue)) {
                $this->mimpTransporti->addErrorMessage(str_replace("%s", $this->mimpTransporti->caption(), $this->mimpTransporti->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->mimpTransporti->FormValue)) {
            $this->mimpTransporti->addErrorMessage($this->mimpTransporti->getErrorMessage(false));
        }
        if ($this->mimpTjera->Required) {
            if (!$this->mimpTjera->IsDetailKey && EmptyValue($this->mimpTjera->FormValue)) {
                $this->mimpTjera->addErrorMessage(str_replace("%s", $this->mimpTjera->caption(), $this->mimpTjera->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->mimpTjera->FormValue)) {
            $this->mimpTjera->addErrorMessage($this->mimpTjera->getErrorMessage(false));
        }
        if ($this->mimpDtHyrjes->Required) {
            if (!$this->mimpDtHyrjes->IsDetailKey && EmptyValue($this->mimpDtHyrjes->FormValue)) {
                $this->mimpDtHyrjes->addErrorMessage(str_replace("%s", $this->mimpDtHyrjes->caption(), $this->mimpDtHyrjes->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->mimpDtHyrjes->FormValue, $this->mimpDtHyrjes->formatPattern())) {
            $this->mimpDtHyrjes->addErrorMessage($this->mimpDtHyrjes->getErrorMessage(false));
        }
        if ($this->mimpCmimiShitjes->Required) {
            if (!$this->mimpCmimiShitjes->IsDetailKey && EmptyValue($this->mimpCmimiShitjes->FormValue)) {
                $this->mimpCmimiShitjes->addErrorMessage(str_replace("%s", $this->mimpCmimiShitjes->caption(), $this->mimpCmimiShitjes->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->mimpCmimiShitjes->FormValue)) {
            $this->mimpCmimiShitjes->addErrorMessage($this->mimpCmimiShitjes->getErrorMessage(false));
        }
        if ($this->mimpGati->Required) {
            if ($this->mimpGati->FormValue == "") {
                $this->mimpGati->addErrorMessage(str_replace("%s", $this->mimpGati->caption(), $this->mimpGati->RequiredErrorMessage));
            }
        }

        // Validate detail grid
        $detailTblVar = explode(",", $this->getCurrentDetailTable());
        $detailPage = Container("MakinaImportiSherbimeGrid");
        if (in_array("makina_importi_sherbime", $detailTblVar) && $detailPage->DetailEdit) {
            $validateForm = $validateForm && $detailPage->validateGridForm();
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

        // mimpMarka
        $this->mimpMarka->setDbValueDef($rsnew, $this->mimpMarka->CurrentValue, 0, $this->mimpMarka->ReadOnly);

        // mimpModeli
        $this->mimpModeli->setDbValueDef($rsnew, $this->mimpModeli->CurrentValue, 0, $this->mimpModeli->ReadOnly);

        // mimpTipi
        $this->mimpTipi->setDbValueDef($rsnew, $this->mimpTipi->CurrentValue, 0, $this->mimpTipi->ReadOnly);

        // mimpShasia
        $this->mimpShasia->setDbValueDef($rsnew, $this->mimpShasia->CurrentValue, "", $this->mimpShasia->ReadOnly);

        // mimpViti
        $this->mimpViti->setDbValueDef($rsnew, $this->mimpViti->CurrentValue, 0, $this->mimpViti->ReadOnly);

        // mimpKarburant
        $this->mimpKarburant->setDbValueDef($rsnew, $this->mimpKarburant->CurrentValue, "", $this->mimpKarburant->ReadOnly);

        // mimpKambio
        $this->mimpKambio->setDbValueDef($rsnew, $this->mimpKambio->CurrentValue, "", $this->mimpKambio->ReadOnly);

        // mimpNgjyra
        $this->mimpNgjyra->setDbValueDef($rsnew, $this->mimpNgjyra->CurrentValue, null, $this->mimpNgjyra->ReadOnly);

        // mimpPrejardhja
        $this->mimpPrejardhja->setDbValueDef($rsnew, $this->mimpPrejardhja->CurrentValue, "", $this->mimpPrejardhja->ReadOnly);

        // mimpInfo
        $this->mimpInfo->setDbValueDef($rsnew, $this->mimpInfo->CurrentValue, null, $this->mimpInfo->ReadOnly);

        // mimpCmimiBlerjes
        $this->mimpCmimiBlerjes->setDbValueDef($rsnew, $this->mimpCmimiBlerjes->CurrentValue, 0, $this->mimpCmimiBlerjes->ReadOnly);

        // mimpDogana
        $this->mimpDogana->setDbValueDef($rsnew, $this->mimpDogana->CurrentValue, 0, $this->mimpDogana->ReadOnly);

        // mimpTransporti
        $this->mimpTransporti->setDbValueDef($rsnew, $this->mimpTransporti->CurrentValue, 0, $this->mimpTransporti->ReadOnly);

        // mimpTjera
        $this->mimpTjera->setDbValueDef($rsnew, $this->mimpTjera->CurrentValue, null, $this->mimpTjera->ReadOnly);

        // mimpDtHyrjes
        $this->mimpDtHyrjes->setDbValueDef($rsnew, UnFormatDateTime($this->mimpDtHyrjes->CurrentValue, $this->mimpDtHyrjes->formatPattern()), null, $this->mimpDtHyrjes->ReadOnly);

        // mimpCmimiShitjes
        $this->mimpCmimiShitjes->setDbValueDef($rsnew, $this->mimpCmimiShitjes->CurrentValue, null, $this->mimpCmimiShitjes->ReadOnly);

        // mimpGati
        $this->mimpGati->setDbValueDef($rsnew, $this->mimpGati->CurrentValue, "", $this->mimpGati->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Begin transaction
        if ($this->getCurrentDetailTable() != "" && $this->UseTransaction) {
            $conn->beginTransaction();
        }

            // Check hash value
            $rowHasConflict = (!IsApi() && $this->getRowHash($rsold) != $this->HashValue);

            // Call Row Update Conflict event
            if ($rowHasConflict) {
                $rowHasConflict = $this->rowUpdateConflict($rsold, $rsnew);
            }
            if ($rowHasConflict) {
                $this->setFailureMessage($Language->phrase("RecordChangedByOtherUser"));
                $this->UpdateConflict = "U";
                return false; // Update Failed
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

            // Update detail records
            $detailTblVar = explode(",", $this->getCurrentDetailTable());
            if ($editRow) {
                $detailPage = Container("MakinaImportiSherbimeGrid");
                if (in_array("makina_importi_sherbime", $detailTblVar) && $detailPage->DetailEdit) {
                    $Security->loadCurrentUserLevel($this->ProjectID . "makina_importi_sherbime"); // Load user level of detail table
                    $editRow = $detailPage->gridUpdate();
                    $Security->loadCurrentUserLevel($this->ProjectID . $this->TableName); // Restore user level of master table
                }
            }

            // Commit/Rollback transaction
            if ($this->getCurrentDetailTable() != "") {
                if ($editRow) {
                    if ($this->UseTransaction) { // Commit transaction
                        $conn->commit();
                    }
                } else {
                    if ($this->UseTransaction) { // Rollback transaction
                        $conn->rollback();
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
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Load row hash
    protected function loadRowHash()
    {
        $filter = $this->getRecordFilter();

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $row = $conn->fetchAssociative($sql);
        $this->HashValue = $row ? $this->getRowHash($row) : ""; // Get hash value for record
    }

    // Get Row Hash
    public function getRowHash(&$rs)
    {
        if (!$rs) {
            return "";
        }
        $row = ($rs instanceof Recordset) ? $rs->fields : $rs;
        $hash = "";
        $hash .= GetFieldHash($row['mimpMarka']); // mimpMarka
        $hash .= GetFieldHash($row['mimpModeli']); // mimpModeli
        $hash .= GetFieldHash($row['mimpTipi']); // mimpTipi
        $hash .= GetFieldHash($row['mimpShasia']); // mimpShasia
        $hash .= GetFieldHash($row['mimpViti']); // mimpViti
        $hash .= GetFieldHash($row['mimpKarburant']); // mimpKarburant
        $hash .= GetFieldHash($row['mimpKambio']); // mimpKambio
        $hash .= GetFieldHash($row['mimpNgjyra']); // mimpNgjyra
        $hash .= GetFieldHash($row['mimpPrejardhja']); // mimpPrejardhja
        $hash .= GetFieldHash($row['mimpInfo']); // mimpInfo
        $hash .= GetFieldHash($row['mimpCmimiBlerjes']); // mimpCmimiBlerjes
        $hash .= GetFieldHash($row['mimpDogana']); // mimpDogana
        $hash .= GetFieldHash($row['mimpTransporti']); // mimpTransporti
        $hash .= GetFieldHash($row['mimpTjera']); // mimpTjera
        $hash .= GetFieldHash($row['mimpDtHyrjes']); // mimpDtHyrjes
        $hash .= GetFieldHash($row['mimpCmimiShitjes']); // mimpCmimiShitjes
        $hash .= GetFieldHash($row['mimpGati']); // mimpGati
        return md5($hash);
    }

    // Set up detail parms based on QueryString
    protected function setupDetailParms()
    {
        // Get the keys for master table
        $detailTblVar = Get(Config("TABLE_SHOW_DETAIL"));
        if ($detailTblVar !== null) {
            $this->setCurrentDetailTable($detailTblVar);
        } else {
            $detailTblVar = $this->getCurrentDetailTable();
        }
        if ($detailTblVar != "") {
            $detailTblVar = explode(",", $detailTblVar);
            if (in_array("makina_importi_sherbime", $detailTblVar)) {
                $detailPageObj = Container("MakinaImportiSherbimeGrid");
                if ($detailPageObj->DetailEdit) {
                    $detailPageObj->CurrentMode = "edit";
                    $detailPageObj->CurrentAction = "gridedit";

                    // Save current master table to detail table
                    $detailPageObj->setCurrentMasterTable($this->TableVar);
                    $detailPageObj->setStartRecordNumber(1);
                    $detailPageObj->mishMakinaImporti->IsDetailKey = true;
                    $detailPageObj->mishMakinaImporti->CurrentValue = $this->mimpID->CurrentValue;
                    $detailPageObj->mishMakinaImporti->setSessionValue($detailPageObj->mishMakinaImporti->CurrentValue);
                }
            }
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MakinaImportiList"), "", $this->TableVar, true);
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
                case "x_mimpMarka":
                    break;
                case "x_mimpModeli":
                    break;
                case "x_mimpTipi":
                    break;
                case "x_mimpKarburant":
                    break;
                case "x_mimpKambio":
                    break;
                case "x_mimpGati":
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
