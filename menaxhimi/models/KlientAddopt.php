<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class KlientAddopt extends Klient
{
    use MessagesTrait;

    // Page ID
    public $PageID = "addopt";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'klient';

    // Page object name
    public $PageObjName = "KlientAddopt";

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
        $this->klientID->Visible = false;
        $this->klientTipi->setVisibility();
        $this->klientEmertimi->setVisibility();
        $this->klientNIPT->setVisibility();
        $this->klientAdresa->setVisibility();
        $this->klientQyteti->setVisibility();
        $this->klientTel1->setVisibility();
        $this->klientTel2->setVisibility();
        $this->klientEmail->setVisibility();
        $this->klientAutori->setVisibility();
        $this->klientShtuar->setVisibility();
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
        $this->klientTipi->DefaultValue = "Individ";
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'klientTipi' first before field var 'x_klientTipi'
        $val = $CurrentForm->hasValue("klientTipi") ? $CurrentForm->getValue("klientTipi") : $CurrentForm->getValue("x_klientTipi");
        if (!$this->klientTipi->IsDetailKey) {
            $this->klientTipi->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'klientEmertimi' first before field var 'x_klientEmertimi'
        $val = $CurrentForm->hasValue("klientEmertimi") ? $CurrentForm->getValue("klientEmertimi") : $CurrentForm->getValue("x_klientEmertimi");
        if (!$this->klientEmertimi->IsDetailKey) {
            $this->klientEmertimi->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'klientNIPT' first before field var 'x_klientNIPT'
        $val = $CurrentForm->hasValue("klientNIPT") ? $CurrentForm->getValue("klientNIPT") : $CurrentForm->getValue("x_klientNIPT");
        if (!$this->klientNIPT->IsDetailKey) {
            $this->klientNIPT->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'klientAdresa' first before field var 'x_klientAdresa'
        $val = $CurrentForm->hasValue("klientAdresa") ? $CurrentForm->getValue("klientAdresa") : $CurrentForm->getValue("x_klientAdresa");
        if (!$this->klientAdresa->IsDetailKey) {
            $this->klientAdresa->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'klientQyteti' first before field var 'x_klientQyteti'
        $val = $CurrentForm->hasValue("klientQyteti") ? $CurrentForm->getValue("klientQyteti") : $CurrentForm->getValue("x_klientQyteti");
        if (!$this->klientQyteti->IsDetailKey) {
            $this->klientQyteti->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'klientTel1' first before field var 'x_klientTel1'
        $val = $CurrentForm->hasValue("klientTel1") ? $CurrentForm->getValue("klientTel1") : $CurrentForm->getValue("x_klientTel1");
        if (!$this->klientTel1->IsDetailKey) {
            $this->klientTel1->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'klientTel2' first before field var 'x_klientTel2'
        $val = $CurrentForm->hasValue("klientTel2") ? $CurrentForm->getValue("klientTel2") : $CurrentForm->getValue("x_klientTel2");
        if (!$this->klientTel2->IsDetailKey) {
            $this->klientTel2->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'klientEmail' first before field var 'x_klientEmail'
        $val = $CurrentForm->hasValue("klientEmail") ? $CurrentForm->getValue("klientEmail") : $CurrentForm->getValue("x_klientEmail");
        if (!$this->klientEmail->IsDetailKey) {
            $this->klientEmail->setFormValue(ConvertFromUtf8($val), true, $validate);
        }

        // Check field name 'klientAutori' first before field var 'x_klientAutori'
        $val = $CurrentForm->hasValue("klientAutori") ? $CurrentForm->getValue("klientAutori") : $CurrentForm->getValue("x_klientAutori");
        if (!$this->klientAutori->IsDetailKey) {
            $this->klientAutori->setFormValue(ConvertFromUtf8($val));
        }

        // Check field name 'klientShtuar' first before field var 'x_klientShtuar'
        $val = $CurrentForm->hasValue("klientShtuar") ? $CurrentForm->getValue("klientShtuar") : $CurrentForm->getValue("x_klientShtuar");
        if (!$this->klientShtuar->IsDetailKey) {
            $this->klientShtuar->setFormValue(ConvertFromUtf8($val));
            $this->klientShtuar->CurrentValue = UnFormatDateTime($this->klientShtuar->CurrentValue, $this->klientShtuar->formatPattern());
        }

        // Check field name 'klientModifikuar' first before field var 'x_klientModifikuar'
        $val = $CurrentForm->hasValue("klientModifikuar") ? $CurrentForm->getValue("klientModifikuar") : $CurrentForm->getValue("x_klientModifikuar");
        if (!$this->klientModifikuar->IsDetailKey) {
            $this->klientModifikuar->setFormValue(ConvertFromUtf8($val));
            $this->klientModifikuar->CurrentValue = UnFormatDateTime($this->klientModifikuar->CurrentValue, $this->klientModifikuar->formatPattern());
        }

        // Check field name 'klientID' first before field var 'x_klientID'
        $val = $CurrentForm->hasValue("klientID") ? $CurrentForm->getValue("klientID") : $CurrentForm->getValue("x_klientID");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->klientTipi->CurrentValue = ConvertToUtf8($this->klientTipi->FormValue);
        $this->klientEmertimi->CurrentValue = ConvertToUtf8($this->klientEmertimi->FormValue);
        $this->klientNIPT->CurrentValue = ConvertToUtf8($this->klientNIPT->FormValue);
        $this->klientAdresa->CurrentValue = ConvertToUtf8($this->klientAdresa->FormValue);
        $this->klientQyteti->CurrentValue = ConvertToUtf8($this->klientQyteti->FormValue);
        $this->klientTel1->CurrentValue = ConvertToUtf8($this->klientTel1->FormValue);
        $this->klientTel2->CurrentValue = ConvertToUtf8($this->klientTel2->FormValue);
        $this->klientEmail->CurrentValue = ConvertToUtf8($this->klientEmail->FormValue);
        $this->klientAutori->CurrentValue = ConvertToUtf8($this->klientAutori->FormValue);
        $this->klientShtuar->CurrentValue = ConvertToUtf8($this->klientShtuar->FormValue);
        $this->klientShtuar->CurrentValue = UnFormatDateTime($this->klientShtuar->CurrentValue, $this->klientShtuar->formatPattern());
        $this->klientModifikuar->CurrentValue = ConvertToUtf8($this->klientModifikuar->FormValue);
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

            // klientTipi
            $this->klientTipi->LinkCustomAttributes = "";
            $this->klientTipi->HrefValue = "";
            $this->klientTipi->TooltipValue = "";

            // klientEmertimi
            $this->klientEmertimi->LinkCustomAttributes = "";
            $this->klientEmertimi->HrefValue = "";
            $this->klientEmertimi->TooltipValue = "";

            // klientNIPT
            $this->klientNIPT->LinkCustomAttributes = "";
            $this->klientNIPT->HrefValue = "";
            $this->klientNIPT->TooltipValue = "";

            // klientAdresa
            $this->klientAdresa->LinkCustomAttributes = "";
            $this->klientAdresa->HrefValue = "";
            $this->klientAdresa->TooltipValue = "";

            // klientQyteti
            $this->klientQyteti->LinkCustomAttributes = "";
            $this->klientQyteti->HrefValue = "";
            $this->klientQyteti->TooltipValue = "";

            // klientTel1
            $this->klientTel1->LinkCustomAttributes = "";
            $this->klientTel1->HrefValue = "";
            $this->klientTel1->TooltipValue = "";

            // klientTel2
            $this->klientTel2->LinkCustomAttributes = "";
            $this->klientTel2->HrefValue = "";
            $this->klientTel2->TooltipValue = "";

            // klientEmail
            $this->klientEmail->LinkCustomAttributes = "";
            $this->klientEmail->HrefValue = "";
            $this->klientEmail->TooltipValue = "";

            // klientAutori
            $this->klientAutori->LinkCustomAttributes = "";
            $this->klientAutori->HrefValue = "";
            $this->klientAutori->TooltipValue = "";

            // klientShtuar
            $this->klientShtuar->LinkCustomAttributes = "";
            $this->klientShtuar->HrefValue = "";
            $this->klientShtuar->TooltipValue = "";

            // klientModifikuar
            $this->klientModifikuar->LinkCustomAttributes = "";
            $this->klientModifikuar->HrefValue = "";
            $this->klientModifikuar->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
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
            $this->klientAutori->setupEditAttributes();
            $this->klientAutori->EditCustomAttributes = "";
            $this->klientAutori->CurrentValue = FormatNumber(CurrentUserID(), $this->klientAutori->formatPattern());

            // klientShtuar
            $this->klientShtuar->setupEditAttributes();
            $this->klientShtuar->EditCustomAttributes = "";
            $this->klientShtuar->CurrentValue = FormatDateTime(CurrentDateTime(), $this->klientShtuar->formatPattern());

            // klientModifikuar
            $this->klientModifikuar->setupEditAttributes();
            $this->klientModifikuar->EditCustomAttributes = "";
            $this->klientModifikuar->CurrentValue = FormatDateTime(CurrentDateTime(), $this->klientModifikuar->formatPattern());

            // Add refer script

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

            // klientShtuar
            $this->klientShtuar->LinkCustomAttributes = "";
            $this->klientShtuar->HrefValue = "";

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
        if ($this->klientShtuar->Required) {
            if (!$this->klientShtuar->IsDetailKey && EmptyValue($this->klientShtuar->FormValue)) {
                $this->klientShtuar->addErrorMessage(str_replace("%s", $this->klientShtuar->caption(), $this->klientShtuar->RequiredErrorMessage));
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

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("KlientList"), "", $this->TableVar, true);
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
