<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MenuKryesoreAddopt extends MenuKryesore
{
    use MessagesTrait;

    // Page ID
    public $PageID = "addopt";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'menu_kryesore';

    // Page object name
    public $PageObjName = "MenuKryesoreAddopt";

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

        // Table object (menu_kryesore)
        if (!isset($GLOBALS["menu_kryesore"]) || get_class($GLOBALS["menu_kryesore"]) == PROJECT_NAMESPACE . "menu_kryesore") {
            $GLOBALS["menu_kryesore"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'menu_kryesore');
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
                $tbl = Container("menu_kryesore");
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
            SaveDebugMessage();
            Redirect(GetUrl($url));
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
            $key .= @$ar['menukID'];
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
            $this->menukID->Visible = false;
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
    public $IsModal = false;
    public $IsMobileOrModal = true; // Add option page is always modal

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param("layout", true));

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->menukID->Visible = false;
        $this->menukGjuha->setVisibility();
        $this->menukTitull->setVisibility();
        $this->menukUrl->setVisibility();
        $this->menukBlank->setVisibility();
        $this->menukRadhe->setVisibility();
        $this->menukAktiv->setVisibility();
        $this->menukAutor->setVisibility();
        $this->menukKrijuar->setVisibility();
        $this->menukAzhornuar->setVisibility();
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
        $this->setupLookupOptions($this->menukGjuha);
        $this->setupLookupOptions($this->menukBlank);
        $this->setupLookupOptions($this->menukAktiv);
        $this->setupLookupOptions($this->menukAutor);

        // Load default values for add
        $this->loadDefaultValues();

        // Set up Breadcrumb
        //$this->setupBreadcrumb(); // Not used
        $this->loadRowValues(); // Load default values

        // Render row
        $this->RowType = ROWTYPE_ADD; // Render add type
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

    // Load default values
    protected function loadDefaultValues()
    {
        $this->menukGjuha->DefaultValue = "sq";
        $this->menukBlank->DefaultValue = "_self";
        $this->menukRadhe->DefaultValue = 10;
        $this->menukAktiv->DefaultValue = "Po";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'menukGjuha' first before field var 'x_menukGjuha'
        $val = $CurrentForm->hasValue("menukGjuha") ? $CurrentForm->getValue("menukGjuha") : $CurrentForm->getValue("x_menukGjuha");
        if (!$this->menukGjuha->IsDetailKey) {
            $this->menukGjuha->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'menukTitull' first before field var 'x_menukTitull'
        $val = $CurrentForm->hasValue("menukTitull") ? $CurrentForm->getValue("menukTitull") : $CurrentForm->getValue("x_menukTitull");
        if (!$this->menukTitull->IsDetailKey) {
            $this->menukTitull->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'menukUrl' first before field var 'x_menukUrl'
        $val = $CurrentForm->hasValue("menukUrl") ? $CurrentForm->getValue("menukUrl") : $CurrentForm->getValue("x_menukUrl");
        if (!$this->menukUrl->IsDetailKey) {
            $this->menukUrl->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'menukBlank' first before field var 'x_menukBlank'
        $val = $CurrentForm->hasValue("menukBlank") ? $CurrentForm->getValue("menukBlank") : $CurrentForm->getValue("x_menukBlank");
        if (!$this->menukBlank->IsDetailKey) {
            $this->menukBlank->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'menukRadhe' first before field var 'x_menukRadhe'
        $val = $CurrentForm->hasValue("menukRadhe") ? $CurrentForm->getValue("menukRadhe") : $CurrentForm->getValue("x_menukRadhe");
        if (!$this->menukRadhe->IsDetailKey) {
            $this->menukRadhe->setFormValue(ConvertFromUtf8($val), true, $validate);
        }

        // Check field name 'menukAktiv' first before field var 'x_menukAktiv'
        $val = $CurrentForm->hasValue("menukAktiv") ? $CurrentForm->getValue("menukAktiv") : $CurrentForm->getValue("x_menukAktiv");
        if (!$this->menukAktiv->IsDetailKey) {
            $this->menukAktiv->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'menukAutor' first before field var 'x_menukAutor'
        $val = $CurrentForm->hasValue("menukAutor") ? $CurrentForm->getValue("menukAutor") : $CurrentForm->getValue("x_menukAutor");
        if (!$this->menukAutor->IsDetailKey) {
            $this->menukAutor->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'menukKrijuar' first before field var 'x_menukKrijuar'
        $val = $CurrentForm->hasValue("menukKrijuar") ? $CurrentForm->getValue("menukKrijuar") : $CurrentForm->getValue("x_menukKrijuar");
        if (!$this->menukKrijuar->IsDetailKey) {
            $this->menukKrijuar->setFormValue(ConvertFromUtf8($val));
            $this->menukKrijuar->CurrentValue = UnFormatDateTime($this->menukKrijuar->CurrentValue, $this->menukKrijuar->formatPattern());
        }

        // Check field name 'menukAzhornuar' first before field var 'x_menukAzhornuar'
        $val = $CurrentForm->hasValue("menukAzhornuar") ? $CurrentForm->getValue("menukAzhornuar") : $CurrentForm->getValue("x_menukAzhornuar");
        if (!$this->menukAzhornuar->IsDetailKey) {
            $this->menukAzhornuar->setFormValue(ConvertFromUtf8($val));
            $this->menukAzhornuar->CurrentValue = UnFormatDateTime($this->menukAzhornuar->CurrentValue, $this->menukAzhornuar->formatPattern());
        }

        // Check field name 'menukID' first before field var 'x_menukID'
        $val = $CurrentForm->hasValue("menukID") ? $CurrentForm->getValue("menukID") : $CurrentForm->getValue("x_menukID");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->menukGjuha->CurrentValue = ConvertToUtf8($this->menukGjuha->FormValue);
        $this->menukTitull->CurrentValue = ConvertToUtf8($this->menukTitull->FormValue);
        $this->menukUrl->CurrentValue = ConvertToUtf8($this->menukUrl->FormValue);
        $this->menukBlank->CurrentValue = ConvertToUtf8($this->menukBlank->FormValue);
        $this->menukRadhe->CurrentValue = ConvertToUtf8($this->menukRadhe->FormValue);
        $this->menukAktiv->CurrentValue = ConvertToUtf8($this->menukAktiv->FormValue);
        $this->menukAutor->CurrentValue = ConvertToUtf8($this->menukAutor->FormValue);
        $this->menukKrijuar->CurrentValue = ConvertToUtf8($this->menukKrijuar->FormValue);
        $this->menukKrijuar->CurrentValue = UnFormatDateTime($this->menukKrijuar->CurrentValue, $this->menukKrijuar->formatPattern());
        $this->menukAzhornuar->CurrentValue = ConvertToUtf8($this->menukAzhornuar->FormValue);
        $this->menukAzhornuar->CurrentValue = UnFormatDateTime($this->menukAzhornuar->CurrentValue, $this->menukAzhornuar->formatPattern());
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
        $this->menukID->setDbValue($row['menukID']);
        $this->menukGjuha->setDbValue($row['menukGjuha']);
        $this->menukTitull->setDbValue($row['menukTitull']);
        $this->menukUrl->setDbValue($row['menukUrl']);
        $this->menukBlank->setDbValue($row['menukBlank']);
        $this->menukRadhe->setDbValue($row['menukRadhe']);
        $this->menukAktiv->setDbValue($row['menukAktiv']);
        $this->menukAutor->setDbValue($row['menukAutor']);
        $this->menukKrijuar->setDbValue($row['menukKrijuar']);
        $this->menukAzhornuar->setDbValue($row['menukAzhornuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['menukID'] = $this->menukID->DefaultValue;
        $row['menukGjuha'] = $this->menukGjuha->DefaultValue;
        $row['menukTitull'] = $this->menukTitull->DefaultValue;
        $row['menukUrl'] = $this->menukUrl->DefaultValue;
        $row['menukBlank'] = $this->menukBlank->DefaultValue;
        $row['menukRadhe'] = $this->menukRadhe->DefaultValue;
        $row['menukAktiv'] = $this->menukAktiv->DefaultValue;
        $row['menukAutor'] = $this->menukAutor->DefaultValue;
        $row['menukKrijuar'] = $this->menukKrijuar->DefaultValue;
        $row['menukAzhornuar'] = $this->menukAzhornuar->DefaultValue;
        return $row;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // menukID
        $this->menukID->RowCssClass = "row";

        // menukGjuha
        $this->menukGjuha->RowCssClass = "row";

        // menukTitull
        $this->menukTitull->RowCssClass = "row";

        // menukUrl
        $this->menukUrl->RowCssClass = "row";

        // menukBlank
        $this->menukBlank->RowCssClass = "row";

        // menukRadhe
        $this->menukRadhe->RowCssClass = "row";

        // menukAktiv
        $this->menukAktiv->RowCssClass = "row";

        // menukAutor
        $this->menukAutor->RowCssClass = "row";

        // menukKrijuar
        $this->menukKrijuar->RowCssClass = "row";

        // menukAzhornuar
        $this->menukAzhornuar->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // menukID
            $this->menukID->ViewValue = $this->menukID->CurrentValue;
            $this->menukID->ViewCustomAttributes = "";

            // menukGjuha
            if (strval($this->menukGjuha->CurrentValue) != "") {
                $this->menukGjuha->ViewValue = $this->menukGjuha->optionCaption($this->menukGjuha->CurrentValue);
            } else {
                $this->menukGjuha->ViewValue = null;
            }
            $this->menukGjuha->ViewCustomAttributes = "";

            // menukTitull
            $this->menukTitull->ViewValue = $this->menukTitull->CurrentValue;
            $this->menukTitull->ViewCustomAttributes = "";

            // menukUrl
            $this->menukUrl->ViewValue = $this->menukUrl->CurrentValue;
            $this->menukUrl->ViewCustomAttributes = "";

            // menukBlank
            if (strval($this->menukBlank->CurrentValue) != "") {
                $this->menukBlank->ViewValue = $this->menukBlank->optionCaption($this->menukBlank->CurrentValue);
            } else {
                $this->menukBlank->ViewValue = null;
            }
            $this->menukBlank->ViewCustomAttributes = "";

            // menukRadhe
            $this->menukRadhe->ViewValue = $this->menukRadhe->CurrentValue;
            $this->menukRadhe->ViewValue = FormatNumber($this->menukRadhe->ViewValue, $this->menukRadhe->formatPattern());
            $this->menukRadhe->ViewCustomAttributes = "";

            // menukAktiv
            if (strval($this->menukAktiv->CurrentValue) != "") {
                $this->menukAktiv->ViewValue = $this->menukAktiv->optionCaption($this->menukAktiv->CurrentValue);
            } else {
                $this->menukAktiv->ViewValue = null;
            }
            $this->menukAktiv->ViewCustomAttributes = "";

            // menukAutor
            $this->menukAutor->ViewValue = $this->menukAutor->CurrentValue;
            $curVal = strval($this->menukAutor->CurrentValue);
            if ($curVal != "") {
                $this->menukAutor->ViewValue = $this->menukAutor->lookupCacheOption($curVal);
                if ($this->menukAutor->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->menukAutor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->menukAutor->Lookup->renderViewRow($rswrk[0]);
                        $this->menukAutor->ViewValue = $this->menukAutor->displayValue($arwrk);
                    } else {
                        $this->menukAutor->ViewValue = FormatNumber($this->menukAutor->CurrentValue, $this->menukAutor->formatPattern());
                    }
                }
            } else {
                $this->menukAutor->ViewValue = null;
            }
            $this->menukAutor->ViewCustomAttributes = "";

            // menukKrijuar
            $this->menukKrijuar->ViewValue = $this->menukKrijuar->CurrentValue;
            $this->menukKrijuar->ViewValue = FormatDateTime($this->menukKrijuar->ViewValue, $this->menukKrijuar->formatPattern());
            $this->menukKrijuar->ViewCustomAttributes = "";

            // menukAzhornuar
            $this->menukAzhornuar->ViewValue = $this->menukAzhornuar->CurrentValue;
            $this->menukAzhornuar->ViewValue = FormatDateTime($this->menukAzhornuar->ViewValue, $this->menukAzhornuar->formatPattern());
            $this->menukAzhornuar->ViewCustomAttributes = "";

            // menukGjuha
            $this->menukGjuha->LinkCustomAttributes = "";
            $this->menukGjuha->HrefValue = "";
            $this->menukGjuha->TooltipValue = "";

            // menukTitull
            $this->menukTitull->LinkCustomAttributes = "";
            $this->menukTitull->HrefValue = "";
            $this->menukTitull->TooltipValue = "";

            // menukUrl
            $this->menukUrl->LinkCustomAttributes = "";
            $this->menukUrl->HrefValue = "";
            $this->menukUrl->TooltipValue = "";

            // menukBlank
            $this->menukBlank->LinkCustomAttributes = "";
            $this->menukBlank->HrefValue = "";
            $this->menukBlank->TooltipValue = "";

            // menukRadhe
            $this->menukRadhe->LinkCustomAttributes = "";
            $this->menukRadhe->HrefValue = "";
            $this->menukRadhe->TooltipValue = "";

            // menukAktiv
            $this->menukAktiv->LinkCustomAttributes = "";
            $this->menukAktiv->HrefValue = "";
            $this->menukAktiv->TooltipValue = "";

            // menukAutor
            $this->menukAutor->LinkCustomAttributes = "";
            $this->menukAutor->HrefValue = "";
            $this->menukAutor->TooltipValue = "";

            // menukKrijuar
            $this->menukKrijuar->LinkCustomAttributes = "";
            $this->menukKrijuar->HrefValue = "";
            $this->menukKrijuar->TooltipValue = "";

            // menukAzhornuar
            $this->menukAzhornuar->LinkCustomAttributes = "";
            $this->menukAzhornuar->HrefValue = "";
            $this->menukAzhornuar->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // menukGjuha
            $this->menukGjuha->EditCustomAttributes = "";
            $this->menukGjuha->EditValue = $this->menukGjuha->options(false);
            $this->menukGjuha->PlaceHolder = RemoveHtml($this->menukGjuha->caption());

            // menukTitull
            $this->menukTitull->setupEditAttributes();
            $this->menukTitull->EditCustomAttributes = "";
            if (!$this->menukTitull->Raw) {
                $this->menukTitull->CurrentValue = HtmlDecode($this->menukTitull->CurrentValue);
            }
            $this->menukTitull->EditValue = HtmlEncode($this->menukTitull->CurrentValue);
            $this->menukTitull->PlaceHolder = RemoveHtml($this->menukTitull->caption());

            // menukUrl
            $this->menukUrl->setupEditAttributes();
            $this->menukUrl->EditCustomAttributes = "";
            if (!$this->menukUrl->Raw) {
                $this->menukUrl->CurrentValue = HtmlDecode($this->menukUrl->CurrentValue);
            }
            $this->menukUrl->EditValue = HtmlEncode($this->menukUrl->CurrentValue);
            $this->menukUrl->PlaceHolder = RemoveHtml($this->menukUrl->caption());

            // menukBlank
            $this->menukBlank->EditCustomAttributes = "";
            $this->menukBlank->EditValue = $this->menukBlank->options(false);
            $this->menukBlank->PlaceHolder = RemoveHtml($this->menukBlank->caption());

            // menukRadhe
            $this->menukRadhe->setupEditAttributes();
            $this->menukRadhe->EditCustomAttributes = "";
            $this->menukRadhe->EditValue = HtmlEncode($this->menukRadhe->CurrentValue);
            $this->menukRadhe->PlaceHolder = RemoveHtml($this->menukRadhe->caption());
            if (strval($this->menukRadhe->EditValue) != "" && is_numeric($this->menukRadhe->EditValue)) {
                $this->menukRadhe->EditValue = FormatNumber($this->menukRadhe->EditValue, null);
            }

            // menukAktiv
            $this->menukAktiv->EditCustomAttributes = "";
            $this->menukAktiv->EditValue = $this->menukAktiv->options(false);
            $this->menukAktiv->PlaceHolder = RemoveHtml($this->menukAktiv->caption());

            // menukAutor
            $this->menukAutor->setupEditAttributes();
            $this->menukAutor->EditCustomAttributes = "";
            $this->menukAutor->CurrentValue = FormatNumber(CurrentUserID(), $this->menukAutor->formatPattern());

            // menukKrijuar
            $this->menukKrijuar->setupEditAttributes();
            $this->menukKrijuar->EditCustomAttributes = "";
            $this->menukKrijuar->CurrentValue = FormatDateTime(CurrentDateTime(), $this->menukKrijuar->formatPattern());

            // menukAzhornuar
            $this->menukAzhornuar->setupEditAttributes();
            $this->menukAzhornuar->EditCustomAttributes = "";
            $this->menukAzhornuar->CurrentValue = FormatDateTime(CurrentDateTime(), $this->menukAzhornuar->formatPattern());

            // Add refer script

            // menukGjuha
            $this->menukGjuha->LinkCustomAttributes = "";
            $this->menukGjuha->HrefValue = "";

            // menukTitull
            $this->menukTitull->LinkCustomAttributes = "";
            $this->menukTitull->HrefValue = "";

            // menukUrl
            $this->menukUrl->LinkCustomAttributes = "";
            $this->menukUrl->HrefValue = "";

            // menukBlank
            $this->menukBlank->LinkCustomAttributes = "";
            $this->menukBlank->HrefValue = "";

            // menukRadhe
            $this->menukRadhe->LinkCustomAttributes = "";
            $this->menukRadhe->HrefValue = "";

            // menukAktiv
            $this->menukAktiv->LinkCustomAttributes = "";
            $this->menukAktiv->HrefValue = "";

            // menukAutor
            $this->menukAutor->LinkCustomAttributes = "";
            $this->menukAutor->HrefValue = "";

            // menukKrijuar
            $this->menukKrijuar->LinkCustomAttributes = "";
            $this->menukKrijuar->HrefValue = "";

            // menukAzhornuar
            $this->menukAzhornuar->LinkCustomAttributes = "";
            $this->menukAzhornuar->HrefValue = "";
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
        if ($this->menukGjuha->Required) {
            if ($this->menukGjuha->FormValue == "") {
                $this->menukGjuha->addErrorMessage(str_replace("%s", $this->menukGjuha->caption(), $this->menukGjuha->RequiredErrorMessage));
            }
        }
        if ($this->menukTitull->Required) {
            if (!$this->menukTitull->IsDetailKey && EmptyValue($this->menukTitull->FormValue)) {
                $this->menukTitull->addErrorMessage(str_replace("%s", $this->menukTitull->caption(), $this->menukTitull->RequiredErrorMessage));
            }
        }
        if ($this->menukUrl->Required) {
            if (!$this->menukUrl->IsDetailKey && EmptyValue($this->menukUrl->FormValue)) {
                $this->menukUrl->addErrorMessage(str_replace("%s", $this->menukUrl->caption(), $this->menukUrl->RequiredErrorMessage));
            }
        }
        if ($this->menukBlank->Required) {
            if ($this->menukBlank->FormValue == "") {
                $this->menukBlank->addErrorMessage(str_replace("%s", $this->menukBlank->caption(), $this->menukBlank->RequiredErrorMessage));
            }
        }
        if ($this->menukRadhe->Required) {
            if (!$this->menukRadhe->IsDetailKey && EmptyValue($this->menukRadhe->FormValue)) {
                $this->menukRadhe->addErrorMessage(str_replace("%s", $this->menukRadhe->caption(), $this->menukRadhe->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->menukRadhe->FormValue)) {
            $this->menukRadhe->addErrorMessage($this->menukRadhe->getErrorMessage(false));
        }
        if ($this->menukAktiv->Required) {
            if ($this->menukAktiv->FormValue == "") {
                $this->menukAktiv->addErrorMessage(str_replace("%s", $this->menukAktiv->caption(), $this->menukAktiv->RequiredErrorMessage));
            }
        }
        if ($this->menukAutor->Required) {
            if (!$this->menukAutor->IsDetailKey && EmptyValue($this->menukAutor->FormValue)) {
                $this->menukAutor->addErrorMessage(str_replace("%s", $this->menukAutor->caption(), $this->menukAutor->RequiredErrorMessage));
            }
        }
        if ($this->menukKrijuar->Required) {
            if (!$this->menukKrijuar->IsDetailKey && EmptyValue($this->menukKrijuar->FormValue)) {
                $this->menukKrijuar->addErrorMessage(str_replace("%s", $this->menukKrijuar->caption(), $this->menukKrijuar->RequiredErrorMessage));
            }
        }
        if ($this->menukAzhornuar->Required) {
            if (!$this->menukAzhornuar->IsDetailKey && EmptyValue($this->menukAzhornuar->FormValue)) {
                $this->menukAzhornuar->addErrorMessage(str_replace("%s", $this->menukAzhornuar->caption(), $this->menukAzhornuar->RequiredErrorMessage));
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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MenuKryesoreList"), "", $this->TableVar, true);
        $pageId = "addopt";
        $Breadcrumb->add("addopt", $pageId, $url);
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
                case "x_menukGjuha":
                    break;
                case "x_menukBlank":
                    break;
                case "x_menukAktiv":
                    break;
                case "x_menukAutor":
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
}
