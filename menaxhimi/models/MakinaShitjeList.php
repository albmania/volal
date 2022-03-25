<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaShitjeList extends MakinaShitje
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina_shitje';

    // Page object name
    public $PageObjName = "MakinaShitjeList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fmakina_shitjelist";
    public $FormActionName = "k_action";
    public $FormBlankRowName = "k_blankrow";
    public $FormKeyCountName = "key_count";

    // Page URLs
    public $AddUrl;
    public $EditUrl;
    public $CopyUrl;
    public $DeleteUrl;
    public $ViewUrl;
    public $ListUrl;

    // Update URLs
    public $InlineAddUrl;
    public $InlineCopyUrl;
    public $InlineEditUrl;
    public $GridAddUrl;
    public $GridEditUrl;
    public $MultiDeleteUrl;
    public $MultiUpdateUrl;

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

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "MakinaShitjeAdd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "MakinaShitjeDelete";
        $this->MultiUpdateUrl = "MakinaShitjeUpdate";

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

        // List options
        $this->ListOptions = new ListOptions(["Tag" => "td", "TableVar" => $this->TableVar]);

        // Export options
        $this->ExportOptions = new ListOptions(["TagClassName" => "ew-export-option"]);

        // Import options
        $this->ImportOptions = new ListOptions(["TagClassName" => "ew-import-option"]);

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }

        // Grid-Add/Edit
        $this->OtherOptions["addedit"] = new ListOptions([
            "TagClassName" => "ew-add-edit-option",
            "UseDropDownButton" => false,
            "DropDownButtonPhrase" => $Language->phrase("ButtonAddEdit"),
            "UseButtonGroup" => true
        ]);

        // Detail tables
        $this->OtherOptions["detail"] = new ListOptions(["TagClassName" => "ew-detail-option"]);
        // Actions
        $this->OtherOptions["action"] = new ListOptions(["TagClassName" => "ew-action-option"]);

        // Column visibility
        $this->OtherOptions["column"] = new ListOptions([
            "TableVar" => $this->TableVar,
            "TagClassName" => "ew-column-option",
            "ButtonGroupClass" => "ew-column-dropdown",
            "UseDropDownButton" => true,
            "DropDownButtonPhrase" => $Language->phrase("Columns"),
            "DropDownAutoClose" => "outside",
            "UseButtonGroup" => false
        ]);

        // Filter options
        $this->FilterOptions = new ListOptions(["TagClassName" => "ew-filter-option"]);

        // List actions
        $this->ListActions = new ListActions();
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
                        if ($fld->DataType == DATATYPE_MEMO && $fld->MemoMaxLength > 0) {
                            $val = TruncateMemo($val, $fld->MemoMaxLength, $fld->TruncateMemoRemoveHtml);
                        }
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
        if ($this->isAddOrEdit()) {
            $this->mshitjeAutori->Visible = false;
        }
        if ($this->isAddOrEdit()) {
            $this->mshitjeKrijuar->Visible = false;
        }
        if ($this->isAddOrEdit()) {
            $this->mshitjeAzhornuar->Visible = false;
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

    // Class variables
    public $ListOptions; // List options
    public $ExportOptions; // Export options
    public $SearchOptions; // Search options
    public $OtherOptions; // Other options
    public $FilterOptions; // Filter options
    public $ImportOptions; // Import options
    public $ListActions; // List actions
    public $SelectedCount = 0;
    public $SelectedIndex = 0;
    public $DisplayRecords = 20;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $PageSizes = "10,20,50,-1"; // Page sizes (comma separated)
    public $DefaultSearchWhere = ""; // Default search WHERE clause
    public $SearchWhere = ""; // Search WHERE clause
    public $SearchPanelClass = "ew-search-panel collapse"; // Search Panel class
    public $SearchColumnCount = 0; // For extended search
    public $SearchFieldsPerRow = 1; // For extended search
    public $RecordCount = 0; // Record count
    public $EditRowCount;
    public $StartRowCount = 1;
    public $RowCount = 0;
    public $Attrs = []; // Row attributes and cell attributes
    public $RowIndex = 0; // Row index
    public $KeyCount = 0; // Key count
    public $MultiColumnGridClass = "row-cols-md";
    public $MultiColumnEditClass = "col-12 w-100";
    public $MultiColumnCardClass = "card h-100 ew-card";
    public $MultiColumnListOptionsPosition = "bottom-start";
    public $DbMasterFilter = ""; // Master filter
    public $DbDetailFilter = ""; // Detail filter
    public $MasterRecordExists;
    public $MultiSelectKey;
    public $Command;
    public $UserAction; // User action
    public $RestoreSearch = false;
    public $HashValue; // Hash value
    public $DetailPages;
    public $OldRecordset;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm;

        // Multi column button position
        $this->MultiColumnListOptionsPosition = Config("MULTI_COLUMN_LIST_OPTIONS_POSITION");

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param("layout", true));

        // Get export parameters
        $custom = "";
        if (Param("export") !== null) {
            $this->Export = Param("export");
            $custom = Param("custom", "");
        } elseif (IsPost()) {
            if (Post("exporttype") !== null) {
                $this->Export = Post("exporttype");
            }
            $custom = Post("custom", "");
        } elseif (Get("cmd") == "json") {
            $this->Export = Get("cmd");
        } else {
            $this->setExportReturnUrl(CurrentUrl());
        }
        $ExportFileName = $this->TableVar; // Get export file, used in header

        // Get custom export parameters
        if ($this->isExport() && $custom != "") {
            $this->CustomExport = $this->Export;
            $this->Export = "print";
        }
        $CustomExportType = $this->CustomExport;
        $ExportType = $this->Export; // Get export parameter, used in header
        $this->CurrentAction = Param("action"); // Set up current action

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();

        // Setup export options
        $this->setupExportOptions();
        $this->mshitjeID->setVisibility();
        $this->mshitjeMarka->setVisibility();
        $this->mshitjeModeli->setVisibility();
        $this->mshitjeTipi->setVisibility();
        $this->mshitjeStruktura->Visible = false;
        $this->mshitjeKapacitetiMotorrit->setVisibility();
        $this->mshitjeVitiProdhimit->setVisibility();
        $this->mshitjeKarburant->setVisibility();
        $this->mshitjeNgjyra->Visible = false;
        $this->mshitjeNrVendeve->setVisibility();
        $this->mshitjeKambio->setVisibility();
        $this->mshitjePrejardhja->Visible = false;
        $this->mshitjeTargaAL->setVisibility();
        $this->mshitjeKilometra->setVisibility();
        $this->mshitjeFotografi->Visible = false;
        $this->mshitjePershkrimi->Visible = false;
        $this->mshitjeCmimi->setVisibility();
        $this->mshitjeIndex->setVisibility();
        $this->mshitjePromo->setVisibility();
        $this->mshitjeAktiv->setVisibility();
        $this->mshitjeShitur->setVisibility();
        $this->mshitjeAutori->setVisibility();
        $this->mshitjeKrijuar->setVisibility();
        $this->mshitjeAzhornuar->setVisibility();
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

        // Setup other options
        $this->setupOtherOptions();

        // Set up custom action (compatible with old version)
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions->add($name, $action);
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

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = "";

        // Get command
        $this->Command = strtolower(Get("cmd", ""));
        if ($this->isPageRequest()) {
            // Process list action first
            if ($this->processListAction()) { // Ajax request
                $this->terminate();
                return;
            }

            // Set up records per page
            $this->setupDisplayRecords();

            // Handle reset command
            $this->resetCmd();

            // Set up Breadcrumb
            if (!$this->isExport()) {
                $this->setupBreadcrumb();
            }

            // Hide list options
            if ($this->isExport()) {
                $this->ListOptions->hideAllOptions(["sequence"]);
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            } elseif ($this->isGridAdd() || $this->isGridEdit()) {
                $this->ListOptions->hideAllOptions();
                $this->ListOptions->UseDropDownButton = false; // Disable drop down button
                $this->ListOptions->UseButtonGroup = false; // Disable button group
            }

            // Hide options
            if ($this->isExport() || $this->CurrentAction) {
                $this->ExportOptions->hideAllOptions();
                $this->FilterOptions->hideAllOptions();
                $this->ImportOptions->hideAllOptions();
            }

            // Hide other options
            if ($this->isExport()) {
                $this->OtherOptions->hideAllOptions();
            }

            // Get default search criteria
            AddFilter($this->DefaultSearchWhere, $this->basicSearchWhere(true));
            AddFilter($this->DefaultSearchWhere, $this->advancedSearchWhere(true));

            // Get basic search values
            $this->loadBasicSearchValues();

            // Get and validate search values for advanced search
            if (EmptyValue($this->UserAction)) { // Skip if user action
                $this->loadSearchValues();
            }

            // Process filter list
            if ($this->processFilterList()) {
                $this->terminate();
                return;
            }
            if (!$this->validateSearch()) {
                // Nothing to do
            }

            // Restore search parms from Session if not searching / reset / export
            if (($this->isExport() || $this->Command != "search" && $this->Command != "reset" && $this->Command != "resetall") && $this->Command != "json" && $this->checkSearchParms()) {
                $this->restoreSearchParms();
            }

            // Call Recordset SearchValidated event
            $this->recordsetSearchValidated();

            // Set up sorting order
            $this->setupSortOrder();

            // Get basic search criteria
            if (!$this->hasInvalidFields()) {
                $srchBasic = $this->basicSearchWhere();
            }

            // Get search criteria for advanced search
            if (!$this->hasInvalidFields()) {
                $srchAdvanced = $this->advancedSearchWhere();
            }
        }

        // Restore display records
        if ($this->Command != "json" && $this->getRecordsPerPage() != "") {
            $this->DisplayRecords = $this->getRecordsPerPage(); // Restore from Session
        } else {
            $this->DisplayRecords = 20; // Load default
            $this->setRecordsPerPage($this->DisplayRecords); // Save default to Session
        }

        // Load Sorting Order
        if ($this->Command != "json") {
            $this->loadSortOrder();
        }

        // Load search default if no existing search criteria
        if (!$this->checkSearchParms()) {
            // Load basic search from default
            $this->BasicSearch->loadDefault();
            if ($this->BasicSearch->Keyword != "") {
                $srchBasic = $this->basicSearchWhere();
            }

            // Load advanced search from default
            if ($this->loadAdvancedSearchDefault()) {
                $srchAdvanced = $this->advancedSearchWhere();
            }
        }

        // Restore search settings from Session
        if (!$this->hasInvalidFields()) {
            $this->loadAdvancedSearch();
        }

        // Build search criteria
        AddFilter($this->SearchWhere, $srchAdvanced);
        AddFilter($this->SearchWhere, $srchBasic);

        // Call Recordset_Searching event
        $this->recordsetSearching($this->SearchWhere);

        // Save search criteria
        if ($this->Command == "search" && !$this->RestoreSearch) {
            $this->setSearchWhere($this->SearchWhere); // Save to Session
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->Command != "json") {
            $this->SearchWhere = $this->getSearchWhere();
        }

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }

        // Export data only
        if (!$this->CustomExport && in_array($this->Export, array_keys(Config("EXPORT_CLASSES")))) {
            $this->exportData();
            $this->terminate();
            return;
        }
        if ($this->isGridAdd()) {
            $this->CurrentFilter = "0=1";
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->GridAddRowCount;
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            if ($this->DisplayRecords <= 0 || ($this->isExport() && $this->ExportAll)) { // Display all records
                $this->DisplayRecords = $this->TotalRecords;
            }
            if (!($this->isExport() && $this->ExportAll)) { // Set up start record position
                $this->setupStartRecord();
            }
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);

            // Set no record found message
            if (!$this->CurrentAction && $this->TotalRecords == 0) {
                if (!$Security->canList()) {
                    $this->setWarningMessage(DeniedMessage());
                }
                if ($this->SearchWhere == "0=101") {
                    $this->setWarningMessage($Language->phrase("EnterSearchCriteria"));
                } else {
                    $this->setWarningMessage($Language->phrase("NoRecord"));
                }
            }
        }

        // Set up list action columns
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Allow) {
                if ($listaction->Select == ACTION_MULTIPLE) { // Show checkbox column if multiple action
                    $this->ListOptions["checkbox"]->Visible = true;
                } elseif ($listaction->Select == ACTION_SINGLE) { // Show list action column
                        $this->ListOptions["listactions"]->Visible = true; // Set visible if any list action is allowed
                }
            }
        }

        // Search options
        $this->setupSearchOptions();

        // Set up search panel class
        if ($this->SearchWhere != "") {
            AppendClass($this->SearchPanelClass, "show");
        }

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset);
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows, "totalRecordCount" => $this->TotalRecords]);
            $this->terminate(true);
            return;
        }

        // Set up pager
        $this->Pager = new PrevNextPager($this->TableVar, $this->StartRecord, $this->getRecordsPerPage(), $this->TotalRecords, $this->PageSizes, $this->RecordRange, $this->AutoHidePager, $this->AutoHidePageSizeSelector);

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

    // Set up number of records displayed per page
    protected function setupDisplayRecords()
    {
        $wrk = Get(Config("TABLE_REC_PER_PAGE"), "");
        if ($wrk != "") {
            if (is_numeric($wrk)) {
                $this->DisplayRecords = (int)$wrk;
            } else {
                if (SameText($wrk, "all")) { // Display all records
                    $this->DisplayRecords = -1;
                } else {
                    $this->DisplayRecords = 20; // Non-numeric, load default
                }
            }
            $this->setRecordsPerPage($this->DisplayRecords); // Save to Session
            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Build filter for all keys
    protected function buildKeyFilter()
    {
        global $CurrentForm;
        $wrkFilter = "";

        // Update row index and get row key
        $rowindex = 1;
        $CurrentForm->Index = $rowindex;
        $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        while ($thisKey != "") {
            $this->setKey($thisKey);
            if ($this->OldKey != "") {
                $filter = $this->getRecordFilter();
                if ($wrkFilter != "") {
                    $wrkFilter .= " OR ";
                }
                $wrkFilter .= $filter;
            } else {
                $wrkFilter = "0=1";
                break;
            }

            // Update row index and get row key
            $rowindex++; // Next row
            $CurrentForm->Index = $rowindex;
            $thisKey = strval($CurrentForm->getValue($this->OldKeyName));
        }
        return $wrkFilter;
    }

    // Get list of filters
    public function getFilterList()
    {
        global $UserProfile;

        // Initialize
        $filterList = "";
        $savedFilterList = "";

        // Load server side filters
        if (Config("SEARCH_FILTER_OPTION") == "Server" && isset($UserProfile)) {
            $savedFilterList = $UserProfile->getSearchFilters(CurrentUserName(), "fmakina_shitjesrch");
        }
        $filterList = Concat($filterList, $this->mshitjeID->AdvancedSearch->toJson(), ","); // Field mshitjeID
        $filterList = Concat($filterList, $this->mshitjeMarka->AdvancedSearch->toJson(), ","); // Field mshitjeMarka
        $filterList = Concat($filterList, $this->mshitjeModeli->AdvancedSearch->toJson(), ","); // Field mshitjeModeli
        $filterList = Concat($filterList, $this->mshitjeTipi->AdvancedSearch->toJson(), ","); // Field mshitjeTipi
        $filterList = Concat($filterList, $this->mshitjeStruktura->AdvancedSearch->toJson(), ","); // Field mshitjeStruktura
        $filterList = Concat($filterList, $this->mshitjeKapacitetiMotorrit->AdvancedSearch->toJson(), ","); // Field mshitjeKapacitetiMotorrit
        $filterList = Concat($filterList, $this->mshitjeVitiProdhimit->AdvancedSearch->toJson(), ","); // Field mshitjeVitiProdhimit
        $filterList = Concat($filterList, $this->mshitjeKarburant->AdvancedSearch->toJson(), ","); // Field mshitjeKarburant
        $filterList = Concat($filterList, $this->mshitjeNgjyra->AdvancedSearch->toJson(), ","); // Field mshitjeNgjyra
        $filterList = Concat($filterList, $this->mshitjeNrVendeve->AdvancedSearch->toJson(), ","); // Field mshitjeNrVendeve
        $filterList = Concat($filterList, $this->mshitjeKambio->AdvancedSearch->toJson(), ","); // Field mshitjeKambio
        $filterList = Concat($filterList, $this->mshitjePrejardhja->AdvancedSearch->toJson(), ","); // Field mshitjePrejardhja
        $filterList = Concat($filterList, $this->mshitjeTargaAL->AdvancedSearch->toJson(), ","); // Field mshitjeTargaAL
        $filterList = Concat($filterList, $this->mshitjeKilometra->AdvancedSearch->toJson(), ","); // Field mshitjeKilometra
        $filterList = Concat($filterList, $this->mshitjeFotografi->AdvancedSearch->toJson(), ","); // Field mshitjeFotografi
        $filterList = Concat($filterList, $this->mshitjePershkrimi->AdvancedSearch->toJson(), ","); // Field mshitjePershkrimi
        $filterList = Concat($filterList, $this->mshitjeCmimi->AdvancedSearch->toJson(), ","); // Field mshitjeCmimi
        $filterList = Concat($filterList, $this->mshitjeIndex->AdvancedSearch->toJson(), ","); // Field mshitjeIndex
        $filterList = Concat($filterList, $this->mshitjePromo->AdvancedSearch->toJson(), ","); // Field mshitjePromo
        $filterList = Concat($filterList, $this->mshitjeAktiv->AdvancedSearch->toJson(), ","); // Field mshitjeAktiv
        $filterList = Concat($filterList, $this->mshitjeShitur->AdvancedSearch->toJson(), ","); // Field mshitjeShitur
        $filterList = Concat($filterList, $this->mshitjeAutori->AdvancedSearch->toJson(), ","); // Field mshitjeAutori
        $filterList = Concat($filterList, $this->mshitjeKrijuar->AdvancedSearch->toJson(), ","); // Field mshitjeKrijuar
        $filterList = Concat($filterList, $this->mshitjeAzhornuar->AdvancedSearch->toJson(), ","); // Field mshitjeAzhornuar
        if ($this->BasicSearch->Keyword != "") {
            $wrk = "\"" . Config("TABLE_BASIC_SEARCH") . "\":\"" . JsEncode($this->BasicSearch->Keyword) . "\",\"" . Config("TABLE_BASIC_SEARCH_TYPE") . "\":\"" . JsEncode($this->BasicSearch->Type) . "\"";
            $filterList = Concat($filterList, $wrk, ",");
        }

        // Return filter list in JSON
        if ($filterList != "") {
            $filterList = "\"data\":{" . $filterList . "}";
        }
        if ($savedFilterList != "") {
            $filterList = Concat($filterList, "\"filters\":" . $savedFilterList, ",");
        }
        return ($filterList != "") ? "{" . $filterList . "}" : "null";
    }

    // Process filter list
    protected function processFilterList()
    {
        global $UserProfile;
        if (Post("ajax") == "savefilters") { // Save filter request (Ajax)
            $filters = Post("filters");
            $UserProfile->setSearchFilters(CurrentUserName(), "fmakina_shitjesrch", $filters);
            WriteJson([["success" => true]]); // Success
            return true;
        } elseif (Post("cmd") == "resetfilter") {
            $this->restoreFilterList();
        }
        return false;
    }

    // Restore list of filters
    protected function restoreFilterList()
    {
        // Return if not reset filter
        if (Post("cmd") !== "resetfilter") {
            return false;
        }
        $filter = json_decode(Post("filter"), true);
        $this->Command = "search";

        // Field mshitjeID
        $this->mshitjeID->AdvancedSearch->SearchValue = @$filter["x_mshitjeID"];
        $this->mshitjeID->AdvancedSearch->SearchOperator = @$filter["z_mshitjeID"];
        $this->mshitjeID->AdvancedSearch->SearchCondition = @$filter["v_mshitjeID"];
        $this->mshitjeID->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeID"];
        $this->mshitjeID->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeID"];
        $this->mshitjeID->AdvancedSearch->save();

        // Field mshitjeMarka
        $this->mshitjeMarka->AdvancedSearch->SearchValue = @$filter["x_mshitjeMarka"];
        $this->mshitjeMarka->AdvancedSearch->SearchOperator = @$filter["z_mshitjeMarka"];
        $this->mshitjeMarka->AdvancedSearch->SearchCondition = @$filter["v_mshitjeMarka"];
        $this->mshitjeMarka->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeMarka"];
        $this->mshitjeMarka->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeMarka"];
        $this->mshitjeMarka->AdvancedSearch->save();

        // Field mshitjeModeli
        $this->mshitjeModeli->AdvancedSearch->SearchValue = @$filter["x_mshitjeModeli"];
        $this->mshitjeModeli->AdvancedSearch->SearchOperator = @$filter["z_mshitjeModeli"];
        $this->mshitjeModeli->AdvancedSearch->SearchCondition = @$filter["v_mshitjeModeli"];
        $this->mshitjeModeli->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeModeli"];
        $this->mshitjeModeli->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeModeli"];
        $this->mshitjeModeli->AdvancedSearch->save();

        // Field mshitjeTipi
        $this->mshitjeTipi->AdvancedSearch->SearchValue = @$filter["x_mshitjeTipi"];
        $this->mshitjeTipi->AdvancedSearch->SearchOperator = @$filter["z_mshitjeTipi"];
        $this->mshitjeTipi->AdvancedSearch->SearchCondition = @$filter["v_mshitjeTipi"];
        $this->mshitjeTipi->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeTipi"];
        $this->mshitjeTipi->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeTipi"];
        $this->mshitjeTipi->AdvancedSearch->save();

        // Field mshitjeStruktura
        $this->mshitjeStruktura->AdvancedSearch->SearchValue = @$filter["x_mshitjeStruktura"];
        $this->mshitjeStruktura->AdvancedSearch->SearchOperator = @$filter["z_mshitjeStruktura"];
        $this->mshitjeStruktura->AdvancedSearch->SearchCondition = @$filter["v_mshitjeStruktura"];
        $this->mshitjeStruktura->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeStruktura"];
        $this->mshitjeStruktura->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeStruktura"];
        $this->mshitjeStruktura->AdvancedSearch->save();

        // Field mshitjeKapacitetiMotorrit
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchValue = @$filter["x_mshitjeKapacitetiMotorrit"];
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchOperator = @$filter["z_mshitjeKapacitetiMotorrit"];
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchCondition = @$filter["v_mshitjeKapacitetiMotorrit"];
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeKapacitetiMotorrit"];
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeKapacitetiMotorrit"];
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->save();

        // Field mshitjeVitiProdhimit
        $this->mshitjeVitiProdhimit->AdvancedSearch->SearchValue = @$filter["x_mshitjeVitiProdhimit"];
        $this->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator = @$filter["z_mshitjeVitiProdhimit"];
        $this->mshitjeVitiProdhimit->AdvancedSearch->SearchCondition = @$filter["v_mshitjeVitiProdhimit"];
        $this->mshitjeVitiProdhimit->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeVitiProdhimit"];
        $this->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeVitiProdhimit"];
        $this->mshitjeVitiProdhimit->AdvancedSearch->save();

        // Field mshitjeKarburant
        $this->mshitjeKarburant->AdvancedSearch->SearchValue = @$filter["x_mshitjeKarburant"];
        $this->mshitjeKarburant->AdvancedSearch->SearchOperator = @$filter["z_mshitjeKarburant"];
        $this->mshitjeKarburant->AdvancedSearch->SearchCondition = @$filter["v_mshitjeKarburant"];
        $this->mshitjeKarburant->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeKarburant"];
        $this->mshitjeKarburant->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeKarburant"];
        $this->mshitjeKarburant->AdvancedSearch->save();

        // Field mshitjeNgjyra
        $this->mshitjeNgjyra->AdvancedSearch->SearchValue = @$filter["x_mshitjeNgjyra"];
        $this->mshitjeNgjyra->AdvancedSearch->SearchOperator = @$filter["z_mshitjeNgjyra"];
        $this->mshitjeNgjyra->AdvancedSearch->SearchCondition = @$filter["v_mshitjeNgjyra"];
        $this->mshitjeNgjyra->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeNgjyra"];
        $this->mshitjeNgjyra->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeNgjyra"];
        $this->mshitjeNgjyra->AdvancedSearch->save();

        // Field mshitjeNrVendeve
        $this->mshitjeNrVendeve->AdvancedSearch->SearchValue = @$filter["x_mshitjeNrVendeve"];
        $this->mshitjeNrVendeve->AdvancedSearch->SearchOperator = @$filter["z_mshitjeNrVendeve"];
        $this->mshitjeNrVendeve->AdvancedSearch->SearchCondition = @$filter["v_mshitjeNrVendeve"];
        $this->mshitjeNrVendeve->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeNrVendeve"];
        $this->mshitjeNrVendeve->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeNrVendeve"];
        $this->mshitjeNrVendeve->AdvancedSearch->save();

        // Field mshitjeKambio
        $this->mshitjeKambio->AdvancedSearch->SearchValue = @$filter["x_mshitjeKambio"];
        $this->mshitjeKambio->AdvancedSearch->SearchOperator = @$filter["z_mshitjeKambio"];
        $this->mshitjeKambio->AdvancedSearch->SearchCondition = @$filter["v_mshitjeKambio"];
        $this->mshitjeKambio->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeKambio"];
        $this->mshitjeKambio->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeKambio"];
        $this->mshitjeKambio->AdvancedSearch->save();

        // Field mshitjePrejardhja
        $this->mshitjePrejardhja->AdvancedSearch->SearchValue = @$filter["x_mshitjePrejardhja"];
        $this->mshitjePrejardhja->AdvancedSearch->SearchOperator = @$filter["z_mshitjePrejardhja"];
        $this->mshitjePrejardhja->AdvancedSearch->SearchCondition = @$filter["v_mshitjePrejardhja"];
        $this->mshitjePrejardhja->AdvancedSearch->SearchValue2 = @$filter["y_mshitjePrejardhja"];
        $this->mshitjePrejardhja->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjePrejardhja"];
        $this->mshitjePrejardhja->AdvancedSearch->save();

        // Field mshitjeTargaAL
        $this->mshitjeTargaAL->AdvancedSearch->SearchValue = @$filter["x_mshitjeTargaAL"];
        $this->mshitjeTargaAL->AdvancedSearch->SearchOperator = @$filter["z_mshitjeTargaAL"];
        $this->mshitjeTargaAL->AdvancedSearch->SearchCondition = @$filter["v_mshitjeTargaAL"];
        $this->mshitjeTargaAL->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeTargaAL"];
        $this->mshitjeTargaAL->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeTargaAL"];
        $this->mshitjeTargaAL->AdvancedSearch->save();

        // Field mshitjeKilometra
        $this->mshitjeKilometra->AdvancedSearch->SearchValue = @$filter["x_mshitjeKilometra"];
        $this->mshitjeKilometra->AdvancedSearch->SearchOperator = @$filter["z_mshitjeKilometra"];
        $this->mshitjeKilometra->AdvancedSearch->SearchCondition = @$filter["v_mshitjeKilometra"];
        $this->mshitjeKilometra->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeKilometra"];
        $this->mshitjeKilometra->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeKilometra"];
        $this->mshitjeKilometra->AdvancedSearch->save();

        // Field mshitjeFotografi
        $this->mshitjeFotografi->AdvancedSearch->SearchValue = @$filter["x_mshitjeFotografi"];
        $this->mshitjeFotografi->AdvancedSearch->SearchOperator = @$filter["z_mshitjeFotografi"];
        $this->mshitjeFotografi->AdvancedSearch->SearchCondition = @$filter["v_mshitjeFotografi"];
        $this->mshitjeFotografi->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeFotografi"];
        $this->mshitjeFotografi->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeFotografi"];
        $this->mshitjeFotografi->AdvancedSearch->save();

        // Field mshitjePershkrimi
        $this->mshitjePershkrimi->AdvancedSearch->SearchValue = @$filter["x_mshitjePershkrimi"];
        $this->mshitjePershkrimi->AdvancedSearch->SearchOperator = @$filter["z_mshitjePershkrimi"];
        $this->mshitjePershkrimi->AdvancedSearch->SearchCondition = @$filter["v_mshitjePershkrimi"];
        $this->mshitjePershkrimi->AdvancedSearch->SearchValue2 = @$filter["y_mshitjePershkrimi"];
        $this->mshitjePershkrimi->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjePershkrimi"];
        $this->mshitjePershkrimi->AdvancedSearch->save();

        // Field mshitjeCmimi
        $this->mshitjeCmimi->AdvancedSearch->SearchValue = @$filter["x_mshitjeCmimi"];
        $this->mshitjeCmimi->AdvancedSearch->SearchOperator = @$filter["z_mshitjeCmimi"];
        $this->mshitjeCmimi->AdvancedSearch->SearchCondition = @$filter["v_mshitjeCmimi"];
        $this->mshitjeCmimi->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeCmimi"];
        $this->mshitjeCmimi->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeCmimi"];
        $this->mshitjeCmimi->AdvancedSearch->save();

        // Field mshitjeIndex
        $this->mshitjeIndex->AdvancedSearch->SearchValue = @$filter["x_mshitjeIndex"];
        $this->mshitjeIndex->AdvancedSearch->SearchOperator = @$filter["z_mshitjeIndex"];
        $this->mshitjeIndex->AdvancedSearch->SearchCondition = @$filter["v_mshitjeIndex"];
        $this->mshitjeIndex->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeIndex"];
        $this->mshitjeIndex->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeIndex"];
        $this->mshitjeIndex->AdvancedSearch->save();

        // Field mshitjePromo
        $this->mshitjePromo->AdvancedSearch->SearchValue = @$filter["x_mshitjePromo"];
        $this->mshitjePromo->AdvancedSearch->SearchOperator = @$filter["z_mshitjePromo"];
        $this->mshitjePromo->AdvancedSearch->SearchCondition = @$filter["v_mshitjePromo"];
        $this->mshitjePromo->AdvancedSearch->SearchValue2 = @$filter["y_mshitjePromo"];
        $this->mshitjePromo->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjePromo"];
        $this->mshitjePromo->AdvancedSearch->save();

        // Field mshitjeAktiv
        $this->mshitjeAktiv->AdvancedSearch->SearchValue = @$filter["x_mshitjeAktiv"];
        $this->mshitjeAktiv->AdvancedSearch->SearchOperator = @$filter["z_mshitjeAktiv"];
        $this->mshitjeAktiv->AdvancedSearch->SearchCondition = @$filter["v_mshitjeAktiv"];
        $this->mshitjeAktiv->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeAktiv"];
        $this->mshitjeAktiv->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeAktiv"];
        $this->mshitjeAktiv->AdvancedSearch->save();

        // Field mshitjeShitur
        $this->mshitjeShitur->AdvancedSearch->SearchValue = @$filter["x_mshitjeShitur"];
        $this->mshitjeShitur->AdvancedSearch->SearchOperator = @$filter["z_mshitjeShitur"];
        $this->mshitjeShitur->AdvancedSearch->SearchCondition = @$filter["v_mshitjeShitur"];
        $this->mshitjeShitur->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeShitur"];
        $this->mshitjeShitur->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeShitur"];
        $this->mshitjeShitur->AdvancedSearch->save();

        // Field mshitjeAutori
        $this->mshitjeAutori->AdvancedSearch->SearchValue = @$filter["x_mshitjeAutori"];
        $this->mshitjeAutori->AdvancedSearch->SearchOperator = @$filter["z_mshitjeAutori"];
        $this->mshitjeAutori->AdvancedSearch->SearchCondition = @$filter["v_mshitjeAutori"];
        $this->mshitjeAutori->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeAutori"];
        $this->mshitjeAutori->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeAutori"];
        $this->mshitjeAutori->AdvancedSearch->save();

        // Field mshitjeKrijuar
        $this->mshitjeKrijuar->AdvancedSearch->SearchValue = @$filter["x_mshitjeKrijuar"];
        $this->mshitjeKrijuar->AdvancedSearch->SearchOperator = @$filter["z_mshitjeKrijuar"];
        $this->mshitjeKrijuar->AdvancedSearch->SearchCondition = @$filter["v_mshitjeKrijuar"];
        $this->mshitjeKrijuar->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeKrijuar"];
        $this->mshitjeKrijuar->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeKrijuar"];
        $this->mshitjeKrijuar->AdvancedSearch->save();

        // Field mshitjeAzhornuar
        $this->mshitjeAzhornuar->AdvancedSearch->SearchValue = @$filter["x_mshitjeAzhornuar"];
        $this->mshitjeAzhornuar->AdvancedSearch->SearchOperator = @$filter["z_mshitjeAzhornuar"];
        $this->mshitjeAzhornuar->AdvancedSearch->SearchCondition = @$filter["v_mshitjeAzhornuar"];
        $this->mshitjeAzhornuar->AdvancedSearch->SearchValue2 = @$filter["y_mshitjeAzhornuar"];
        $this->mshitjeAzhornuar->AdvancedSearch->SearchOperator2 = @$filter["w_mshitjeAzhornuar"];
        $this->mshitjeAzhornuar->AdvancedSearch->save();
        $this->BasicSearch->setKeyword(@$filter[Config("TABLE_BASIC_SEARCH")]);
        $this->BasicSearch->setType(@$filter[Config("TABLE_BASIC_SEARCH_TYPE")]);
    }

    // Advanced search WHERE clause based on QueryString
    protected function advancedSearchWhere($default = false)
    {
        global $Security;
        $where = "";
        if (!$Security->canSearch()) {
            return "";
        }
        $this->buildSearchSql($where, $this->mshitjeID, $default, false); // mshitjeID
        $this->buildSearchSql($where, $this->mshitjeMarka, $default, false); // mshitjeMarka
        $this->buildSearchSql($where, $this->mshitjeModeli, $default, false); // mshitjeModeli
        $this->buildSearchSql($where, $this->mshitjeTipi, $default, false); // mshitjeTipi
        $this->buildSearchSql($where, $this->mshitjeStruktura, $default, false); // mshitjeStruktura
        $this->buildSearchSql($where, $this->mshitjeKapacitetiMotorrit, $default, false); // mshitjeKapacitetiMotorrit
        $this->buildSearchSql($where, $this->mshitjeVitiProdhimit, $default, false); // mshitjeVitiProdhimit
        $this->buildSearchSql($where, $this->mshitjeKarburant, $default, false); // mshitjeKarburant
        $this->buildSearchSql($where, $this->mshitjeNgjyra, $default, false); // mshitjeNgjyra
        $this->buildSearchSql($where, $this->mshitjeNrVendeve, $default, false); // mshitjeNrVendeve
        $this->buildSearchSql($where, $this->mshitjeKambio, $default, false); // mshitjeKambio
        $this->buildSearchSql($where, $this->mshitjePrejardhja, $default, false); // mshitjePrejardhja
        $this->buildSearchSql($where, $this->mshitjeTargaAL, $default, false); // mshitjeTargaAL
        $this->buildSearchSql($where, $this->mshitjeKilometra, $default, false); // mshitjeKilometra
        $this->buildSearchSql($where, $this->mshitjeFotografi, $default, false); // mshitjeFotografi
        $this->buildSearchSql($where, $this->mshitjePershkrimi, $default, false); // mshitjePershkrimi
        $this->buildSearchSql($where, $this->mshitjeCmimi, $default, false); // mshitjeCmimi
        $this->buildSearchSql($where, $this->mshitjeIndex, $default, false); // mshitjeIndex
        $this->buildSearchSql($where, $this->mshitjePromo, $default, false); // mshitjePromo
        $this->buildSearchSql($where, $this->mshitjeAktiv, $default, false); // mshitjeAktiv
        $this->buildSearchSql($where, $this->mshitjeShitur, $default, false); // mshitjeShitur
        $this->buildSearchSql($where, $this->mshitjeAutori, $default, false); // mshitjeAutori
        $this->buildSearchSql($where, $this->mshitjeKrijuar, $default, false); // mshitjeKrijuar
        $this->buildSearchSql($where, $this->mshitjeAzhornuar, $default, false); // mshitjeAzhornuar

        // Set up search parm
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->mshitjeID->AdvancedSearch->save(); // mshitjeID
            $this->mshitjeMarka->AdvancedSearch->save(); // mshitjeMarka
            $this->mshitjeModeli->AdvancedSearch->save(); // mshitjeModeli
            $this->mshitjeTipi->AdvancedSearch->save(); // mshitjeTipi
            $this->mshitjeStruktura->AdvancedSearch->save(); // mshitjeStruktura
            $this->mshitjeKapacitetiMotorrit->AdvancedSearch->save(); // mshitjeKapacitetiMotorrit
            $this->mshitjeVitiProdhimit->AdvancedSearch->save(); // mshitjeVitiProdhimit
            $this->mshitjeKarburant->AdvancedSearch->save(); // mshitjeKarburant
            $this->mshitjeNgjyra->AdvancedSearch->save(); // mshitjeNgjyra
            $this->mshitjeNrVendeve->AdvancedSearch->save(); // mshitjeNrVendeve
            $this->mshitjeKambio->AdvancedSearch->save(); // mshitjeKambio
            $this->mshitjePrejardhja->AdvancedSearch->save(); // mshitjePrejardhja
            $this->mshitjeTargaAL->AdvancedSearch->save(); // mshitjeTargaAL
            $this->mshitjeKilometra->AdvancedSearch->save(); // mshitjeKilometra
            $this->mshitjeFotografi->AdvancedSearch->save(); // mshitjeFotografi
            $this->mshitjePershkrimi->AdvancedSearch->save(); // mshitjePershkrimi
            $this->mshitjeCmimi->AdvancedSearch->save(); // mshitjeCmimi
            $this->mshitjeIndex->AdvancedSearch->save(); // mshitjeIndex
            $this->mshitjePromo->AdvancedSearch->save(); // mshitjePromo
            $this->mshitjeAktiv->AdvancedSearch->save(); // mshitjeAktiv
            $this->mshitjeShitur->AdvancedSearch->save(); // mshitjeShitur
            $this->mshitjeAutori->AdvancedSearch->save(); // mshitjeAutori
            $this->mshitjeKrijuar->AdvancedSearch->save(); // mshitjeKrijuar
            $this->mshitjeAzhornuar->AdvancedSearch->save(); // mshitjeAzhornuar
        }
        return $where;
    }

    // Build search SQL
    protected function buildSearchSql(&$where, &$fld, $default, $multiValue)
    {
        $fldParm = $fld->Param;
        $fldVal = $default ? $fld->AdvancedSearch->SearchValueDefault : $fld->AdvancedSearch->SearchValue;
        $fldOpr = $default ? $fld->AdvancedSearch->SearchOperatorDefault : $fld->AdvancedSearch->SearchOperator;
        $fldCond = $default ? $fld->AdvancedSearch->SearchConditionDefault : $fld->AdvancedSearch->SearchCondition;
        $fldVal2 = $default ? $fld->AdvancedSearch->SearchValue2Default : $fld->AdvancedSearch->SearchValue2;
        $fldOpr2 = $default ? $fld->AdvancedSearch->SearchOperator2Default : $fld->AdvancedSearch->SearchOperator2;
        $wrk = "";
        if (is_array($fldVal)) {
            $fldVal = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal);
        }
        if (is_array($fldVal2)) {
            $fldVal2 = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $fldVal2);
        }
        $fldOpr = strtoupper(trim($fldOpr ?? ""));
        if ($fldOpr == "") {
            $fldOpr = "=";
        }
        $fldOpr2 = strtoupper(trim($fldOpr2 ?? ""));
        if ($fldOpr2 == "") {
            $fldOpr2 = "=";
        }
        if (Config("SEARCH_MULTI_VALUE_OPTION") == 1 && !$fld->UseFilter || !IsMultiSearchOperator($fldOpr)) {
            $multiValue = false;
        }
        if ($multiValue) {
            $wrk = $fldVal != "" ? GetMultiSearchSql($fld, $fldOpr, $fldVal, $this->Dbid) : ""; // Field value 1
            $wrk2 = $fldVal2 != "" ? GetMultiSearchSql($fld, $fldOpr2, $fldVal2, $this->Dbid) : ""; // Field value 2
            AddFilter($wrk, $wrk2, $fldCond);
        } else {
            $fldVal = $this->convertSearchValue($fld, $fldVal);
            $fldVal2 = $this->convertSearchValue($fld, $fldVal2);
            $wrk = GetSearchSql($fld, $fldVal, $fldOpr, $fldCond, $fldVal2, $fldOpr2, $this->Dbid);
        }
        if ($this->SearchOption == "AUTO" && in_array($this->BasicSearch->getType(), ["AND", "OR"])) {
            $cond = $this->BasicSearch->getType();
        } else {
            $cond = SameText($this->SearchOption, "OR") ? "OR" : "AND";
        }
        AddFilter($where, $wrk, $cond);
    }

    // Convert search value
    protected function convertSearchValue(&$fld, $fldVal)
    {
        if ($fldVal == Config("NULL_VALUE") || $fldVal == Config("NOT_NULL_VALUE")) {
            return $fldVal;
        }
        $value = $fldVal;
        if ($fld->isBoolean()) {
            if ($fldVal != "") {
                $value = (SameText($fldVal, "1") || SameText($fldVal, "y") || SameText($fldVal, "t")) ? $fld->TrueValue : $fld->FalseValue;
            }
        } elseif ($fld->DataType == DATATYPE_DATE || $fld->DataType == DATATYPE_TIME) {
            if ($fldVal != "") {
                $value = UnFormatDateTime($fldVal, $fld->formatPattern());
            }
        }
        return $value;
    }

    // Return basic search WHERE clause based on search keyword and type
    protected function basicSearchWhere($default = false)
    {
        global $Security;
        $searchStr = "";
        if (!$Security->canSearch()) {
            return "";
        }

        // Fields to search
        $searchFlds = [];
        $searchFlds[] = &$this->mshitjeKapacitetiMotorrit;
        $searchFlds[] = &$this->mshitjeNgjyra;
        $searchFlds[] = &$this->mshitjeNrVendeve;
        $searchFlds[] = &$this->mshitjePrejardhja;
        $searchFlds[] = &$this->mshitjeFotografi;
        $searchFlds[] = &$this->mshitjePershkrimi;
        $searchFlds[] = &$this->mshitjeCmimi;
        $searchKeyword = $default ? $this->BasicSearch->KeywordDefault : $this->BasicSearch->Keyword;
        $searchType = $default ? $this->BasicSearch->TypeDefault : $this->BasicSearch->Type;

        // Get search SQL
        if ($searchKeyword != "") {
            $ar = $this->BasicSearch->keywordList($default);
            $searchStr = GetQuickSearchFilter($searchFlds, $ar, $searchType, Config("BASIC_SEARCH_ANY_FIELDS"), $this->Dbid);
            if (!$default && in_array($this->Command, ["", "reset", "resetall"])) {
                $this->Command = "search";
            }
        }
        if (!$default && $this->Command == "search") {
            $this->BasicSearch->setKeyword($searchKeyword);
            $this->BasicSearch->setType($searchType);
        }
        return $searchStr;
    }

    // Check if search parm exists
    protected function checkSearchParms()
    {
        // Check basic search
        if ($this->BasicSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeID->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeMarka->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeModeli->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeTipi->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeStruktura->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeKapacitetiMotorrit->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeVitiProdhimit->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeKarburant->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeNgjyra->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeNrVendeve->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeKambio->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjePrejardhja->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeTargaAL->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeKilometra->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeFotografi->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjePershkrimi->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeCmimi->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeIndex->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjePromo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeAktiv->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeShitur->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeAutori->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeKrijuar->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mshitjeAzhornuar->AdvancedSearch->issetSession()) {
            return true;
        }
        return false;
    }

    // Clear all search parameters
    protected function resetSearchParms()
    {
        // Clear search WHERE clause
        $this->SearchWhere = "";
        $this->setSearchWhere($this->SearchWhere);

        // Clear basic search parameters
        $this->resetBasicSearchParms();

        // Clear advanced search parameters
        $this->resetAdvancedSearchParms();
    }

    // Load advanced search default values
    protected function loadAdvancedSearchDefault()
    {
        return false;
    }

    // Clear all basic search parameters
    protected function resetBasicSearchParms()
    {
        $this->BasicSearch->unsetSession();
    }

    // Clear all advanced search parameters
    protected function resetAdvancedSearchParms()
    {
        $this->mshitjeID->AdvancedSearch->unsetSession();
        $this->mshitjeMarka->AdvancedSearch->unsetSession();
        $this->mshitjeModeli->AdvancedSearch->unsetSession();
        $this->mshitjeTipi->AdvancedSearch->unsetSession();
        $this->mshitjeStruktura->AdvancedSearch->unsetSession();
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->unsetSession();
        $this->mshitjeVitiProdhimit->AdvancedSearch->unsetSession();
        $this->mshitjeKarburant->AdvancedSearch->unsetSession();
        $this->mshitjeNgjyra->AdvancedSearch->unsetSession();
        $this->mshitjeNrVendeve->AdvancedSearch->unsetSession();
        $this->mshitjeKambio->AdvancedSearch->unsetSession();
        $this->mshitjePrejardhja->AdvancedSearch->unsetSession();
        $this->mshitjeTargaAL->AdvancedSearch->unsetSession();
        $this->mshitjeKilometra->AdvancedSearch->unsetSession();
        $this->mshitjeFotografi->AdvancedSearch->unsetSession();
        $this->mshitjePershkrimi->AdvancedSearch->unsetSession();
        $this->mshitjeCmimi->AdvancedSearch->unsetSession();
        $this->mshitjeIndex->AdvancedSearch->unsetSession();
        $this->mshitjePromo->AdvancedSearch->unsetSession();
        $this->mshitjeAktiv->AdvancedSearch->unsetSession();
        $this->mshitjeShitur->AdvancedSearch->unsetSession();
        $this->mshitjeAutori->AdvancedSearch->unsetSession();
        $this->mshitjeKrijuar->AdvancedSearch->unsetSession();
        $this->mshitjeAzhornuar->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
        $this->mshitjeID->AdvancedSearch->load();
        $this->mshitjeMarka->AdvancedSearch->load();
        $this->mshitjeModeli->AdvancedSearch->load();
        $this->mshitjeTipi->AdvancedSearch->load();
        $this->mshitjeStruktura->AdvancedSearch->load();
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->load();
        $this->mshitjeVitiProdhimit->AdvancedSearch->load();
        $this->mshitjeKarburant->AdvancedSearch->load();
        $this->mshitjeNgjyra->AdvancedSearch->load();
        $this->mshitjeNrVendeve->AdvancedSearch->load();
        $this->mshitjeKambio->AdvancedSearch->load();
        $this->mshitjePrejardhja->AdvancedSearch->load();
        $this->mshitjeTargaAL->AdvancedSearch->load();
        $this->mshitjeKilometra->AdvancedSearch->load();
        $this->mshitjeFotografi->AdvancedSearch->load();
        $this->mshitjePershkrimi->AdvancedSearch->load();
        $this->mshitjeCmimi->AdvancedSearch->load();
        $this->mshitjeIndex->AdvancedSearch->load();
        $this->mshitjePromo->AdvancedSearch->load();
        $this->mshitjeAktiv->AdvancedSearch->load();
        $this->mshitjeShitur->AdvancedSearch->load();
        $this->mshitjeAutori->AdvancedSearch->load();
        $this->mshitjeKrijuar->AdvancedSearch->load();
        $this->mshitjeAzhornuar->AdvancedSearch->load();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for Ctrl pressed
        $ctrl = Get("ctrl") !== null;

        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
            $this->updateSort($this->mshitjeID, $ctrl); // mshitjeID
            $this->updateSort($this->mshitjeMarka, $ctrl); // mshitjeMarka
            $this->updateSort($this->mshitjeModeli, $ctrl); // mshitjeModeli
            $this->updateSort($this->mshitjeTipi, $ctrl); // mshitjeTipi
            $this->updateSort($this->mshitjeKapacitetiMotorrit, $ctrl); // mshitjeKapacitetiMotorrit
            $this->updateSort($this->mshitjeVitiProdhimit, $ctrl); // mshitjeVitiProdhimit
            $this->updateSort($this->mshitjeKarburant, $ctrl); // mshitjeKarburant
            $this->updateSort($this->mshitjeNrVendeve, $ctrl); // mshitjeNrVendeve
            $this->updateSort($this->mshitjeKambio, $ctrl); // mshitjeKambio
            $this->updateSort($this->mshitjeTargaAL, $ctrl); // mshitjeTargaAL
            $this->updateSort($this->mshitjeKilometra, $ctrl); // mshitjeKilometra
            $this->updateSort($this->mshitjeCmimi, $ctrl); // mshitjeCmimi
            $this->updateSort($this->mshitjeIndex, $ctrl); // mshitjeIndex
            $this->updateSort($this->mshitjePromo, $ctrl); // mshitjePromo
            $this->updateSort($this->mshitjeAktiv, $ctrl); // mshitjeAktiv
            $this->updateSort($this->mshitjeShitur, $ctrl); // mshitjeShitur
            $this->updateSort($this->mshitjeAutori, $ctrl); // mshitjeAutori
            $this->updateSort($this->mshitjeKrijuar, $ctrl); // mshitjeKrijuar
            $this->updateSort($this->mshitjeAzhornuar, $ctrl); // mshitjeAzhornuar
            $this->setStartRecordNumber(1); // Reset start position
        }
    }

    // Load sort order parameters
    protected function loadSortOrder()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        if ($orderBy == "") {
            $this->DefaultSort = "";
            if ($this->getSqlOrderBy() != "") {
                $useDefaultSort = true;
                if ($useDefaultSort) {
                    $orderBy = $this->getSqlOrderBy();
                    $this->setSessionOrderBy($orderBy);
                } else {
                    $this->setSessionOrderBy("");
                }
            }
        }
    }

    // Reset command
    // - cmd=reset (Reset search parameters)
    // - cmd=resetall (Reset search and master/detail parameters)
    // - cmd=resetsort (Reset sort parameters)
    protected function resetCmd()
    {
        // Check if reset command
        if (StartsString("reset", $this->Command)) {
            // Reset search criteria
            if ($this->Command == "reset" || $this->Command == "resetall") {
                $this->resetSearchParms();
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->mshitjeID->setSort("");
                $this->mshitjeMarka->setSort("");
                $this->mshitjeModeli->setSort("");
                $this->mshitjeTipi->setSort("");
                $this->mshitjeStruktura->setSort("");
                $this->mshitjeKapacitetiMotorrit->setSort("");
                $this->mshitjeVitiProdhimit->setSort("");
                $this->mshitjeKarburant->setSort("");
                $this->mshitjeNgjyra->setSort("");
                $this->mshitjeNrVendeve->setSort("");
                $this->mshitjeKambio->setSort("");
                $this->mshitjePrejardhja->setSort("");
                $this->mshitjeTargaAL->setSort("");
                $this->mshitjeKilometra->setSort("");
                $this->mshitjeFotografi->setSort("");
                $this->mshitjePershkrimi->setSort("");
                $this->mshitjeCmimi->setSort("");
                $this->mshitjeIndex->setSort("");
                $this->mshitjePromo->setSort("");
                $this->mshitjeAktiv->setSort("");
                $this->mshitjeShitur->setSort("");
                $this->mshitjeAutori->setSort("");
                $this->mshitjeKrijuar->setSort("");
                $this->mshitjeAzhornuar->setSort("");
            }

            // Reset start position
            $this->StartRecord = 1;
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Set up list options
    protected function setupListOptions()
    {
        global $Security, $Language;

        // Add group option item ("button")
        $item = &$this->ListOptions->addGroupOption();
        $item->Body = "";
        $item->OnLeft = false;
        $item->Visible = false;

        // "view"
        $item = &$this->ListOptions->add("view");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canView();
        $item->OnLeft = false;

        // "edit"
        $item = &$this->ListOptions->add("edit");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canEdit();
        $item->OnLeft = false;

        // "copy"
        $item = &$this->ListOptions->add("copy");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canAdd();
        $item->OnLeft = false;

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = false;

        // List actions
        $item = &$this->ListOptions->add("listactions");
        $item->CssClass = "text-nowrap";
        $item->OnLeft = false;
        $item->Visible = false;
        $item->ShowInButtonGroup = false;
        $item->ShowInDropDown = false;

        // "checkbox"
        $item = &$this->ListOptions->add("checkbox");
        $item->Visible = false;
        $item->OnLeft = false;
        $item->Header = "<div class=\"form-check\"><input type=\"checkbox\" name=\"key\" id=\"key\" class=\"form-check-input\" data-ew-action=\"select-all-keys\"></div>";
        if ($item->OnLeft) {
            $item->moveTo(0);
        }
        $item->ShowInDropDown = false;
        $item->ShowInButtonGroup = false;

        // Drop down button for ListOptions
        $this->ListOptions->UseDropDownButton = false;
        $this->ListOptions->DropDownButtonPhrase = $Language->phrase("ButtonListOptions");
        $this->ListOptions->UseButtonGroup = false;
        if ($this->ListOptions->UseButtonGroup && IsMobile()) {
            $this->ListOptions->UseDropDownButton = true;
        }

        //$this->ListOptions->ButtonClass = ""; // Class for button group

        // Call ListOptions_Load event
        $this->listOptionsLoad();
        $this->setupListOptionsExt();
        $item = $this->ListOptions[$this->ListOptions->GroupOptionName];
        $item->Visible = $this->ListOptions->groupOptionVisible();
    }

    // Set up list options (extensions)
    protected function setupListOptionsExt()
    {
            // Set up list options (to be implemented by extensions)
    }

    // Render list options
    public function renderListOptions()
    {
        global $Security, $Language, $CurrentForm, $UserProfile;
        $this->ListOptions->loadDefault();

        // Call ListOptions_Rendering event
        $this->listOptionsRendering();
        $pageUrl = $this->pageUrl(false);
        if ($this->CurrentMode == "view") {
            // "view"
            $opt = $this->ListOptions["view"];
            $viewcaption = HtmlTitle($Language->phrase("ViewLink"));
            if ($Security->canView()) {
                $opt->Body = "<a class=\"ew-row-link ew-view\" title=\"" . $viewcaption . "\" data-caption=\"" . $viewcaption . "\" href=\"" . HtmlEncode(GetUrl($this->ViewUrl)) . "\">" . $Language->phrase("ViewLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "edit"
            $opt = $this->ListOptions["edit"];
            $editcaption = HtmlTitle($Language->phrase("EditLink"));
            if ($Security->canEdit()) {
                $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("EditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "copy"
            $opt = $this->ListOptions["copy"];
            $copycaption = HtmlTitle($Language->phrase("CopyLink"));
            if ($Security->canAdd()) {
                $opt->Body = "<a class=\"ew-row-link ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("CopyLink") . "</a>";
            } else {
                $opt->Body = "";
            }

            // "delete"
            $opt = $this->ListOptions["delete"];
            if ($Security->canDelete()) {
                $opt->Body = "<a class=\"ew-row-link ew-delete\" data-ew-action=\"inline-delete\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("DeleteLink") . "</a>";
            } else {
                $opt->Body = "";
            }
        } // End View mode

        // Set up list action buttons
        $opt = $this->ListOptions["listactions"];
        if ($opt && !$this->isExport() && !$this->CurrentAction) {
            $body = "";
            $links = [];
            foreach ($this->ListActions->Items as $listaction) {
                $action = $listaction->Action;
                $allowed = $listaction->Allow;
                if ($listaction->Select == ACTION_SINGLE && $allowed) {
                    $caption = $listaction->Caption;
                    $icon = ($listaction->Icon != "") ? "<i class=\"" . HtmlEncode(str_replace(" ew-icon", "", $listaction->Icon)) . "\" data-caption=\"" . HtmlTitle($caption) . "\"></i> " : "";
                    $link = "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fmakina_shitjelist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . $listaction->Caption . "</button></li>";
                    if ($link != "") {
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fmakina_shitjelist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . $listaction->Caption . "</button>";
                        }
                    }
                }
            }
            if (count($links) > 1) { // More than one buttons, use dropdown
                $body = "<button class=\"dropdown-toggle btn btn-default ew-actions\" title=\"" . HtmlTitle($Language->phrase("ListActionButton")) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("ListActionButton") . "</button>";
                $content = "";
                foreach ($links as $link) {
                    $content .= "<li>" . $link . "</li>";
                }
                $body .= "<ul class=\"dropdown-menu" . ($opt->OnLeft ? "" : " dropdown-menu-right") . "\">" . $content . "</ul>";
                $body = "<div class=\"btn-group btn-group-sm\">" . $body . "</div>";
            }
            if (count($links) > 0) {
                $opt->Body = $body;
            }
        }

        // "checkbox"
        $opt = $this->ListOptions["checkbox"];
        $opt->Body = "<div class=\"form-check\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"form-check-input ew-multi-select\" value=\"" . HtmlEncode($this->mshitjeID->CurrentValue) . "\" data-ew-action=\"select-key\"></div>";
        $this->renderListOptionsExt();

        // Call ListOptions_Rendered event
        $this->listOptionsRendered();
    }

    // Render list options (extensions)
    protected function renderListOptionsExt()
    {
        // Render list options (to be implemented by extensions)
        global $Security, $Language;
    }

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["addedit"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("AddLink"));
        $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
        $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        $option = $options["action"];

        // Show column list for column visibility
        if ($this->UseColumnVisibility) {
            $option = $this->OtherOptions["column"];
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = $this->UseColumnVisibility;
            $option->add("mshitjeID", $this->createColumnOption("mshitjeID"));
            $option->add("mshitjeMarka", $this->createColumnOption("mshitjeMarka"));
            $option->add("mshitjeModeli", $this->createColumnOption("mshitjeModeli"));
            $option->add("mshitjeTipi", $this->createColumnOption("mshitjeTipi"));
            $option->add("mshitjeKapacitetiMotorrit", $this->createColumnOption("mshitjeKapacitetiMotorrit"));
            $option->add("mshitjeVitiProdhimit", $this->createColumnOption("mshitjeVitiProdhimit"));
            $option->add("mshitjeKarburant", $this->createColumnOption("mshitjeKarburant"));
            $option->add("mshitjeNrVendeve", $this->createColumnOption("mshitjeNrVendeve"));
            $option->add("mshitjeKambio", $this->createColumnOption("mshitjeKambio"));
            $option->add("mshitjeTargaAL", $this->createColumnOption("mshitjeTargaAL"));
            $option->add("mshitjeKilometra", $this->createColumnOption("mshitjeKilometra"));
            $option->add("mshitjeCmimi", $this->createColumnOption("mshitjeCmimi"));
            $option->add("mshitjeIndex", $this->createColumnOption("mshitjeIndex"));
            $option->add("mshitjePromo", $this->createColumnOption("mshitjePromo"));
            $option->add("mshitjeAktiv", $this->createColumnOption("mshitjeAktiv"));
            $option->add("mshitjeShitur", $this->createColumnOption("mshitjeShitur"));
            $option->add("mshitjeAutori", $this->createColumnOption("mshitjeAutori"));
            $option->add("mshitjeKrijuar", $this->createColumnOption("mshitjeKrijuar"));
            $option->add("mshitjeAzhornuar", $this->createColumnOption("mshitjeAzhornuar"));
        }

        // Set up options default
        foreach ($options as $name => $option) {
            if ($name != "column") { // Always use dropdown for column
                $option->UseDropDownButton = false;
                $option->UseButtonGroup = true;
            }
            //$option->ButtonClass = ""; // Class for button group
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = false;
        }
        $options["addedit"]->DropDownButtonPhrase = $Language->phrase("ButtonAddEdit");
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $options["action"]->DropDownButtonPhrase = $Language->phrase("ButtonActions");

        // Filter button
        $item = &$this->FilterOptions->add("savecurrentfilter");
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fmakina_shitjesrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fmakina_shitjesrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
        $item->Visible = true;
        $this->FilterOptions->UseDropDownButton = true;
        $this->FilterOptions->UseButtonGroup = !$this->FilterOptions->UseDropDownButton;
        $this->FilterOptions->DropDownButtonPhrase = $Language->phrase("Filters");

        // Add group option item
        $item = &$this->FilterOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Create new column option
    public function createColumnOption($name)
    {
        $field = $this->Fields[$name] ?? false;
        if ($field && $field->Visible) {
            $item = new ListOption($field->Name);
            $item->Body = '<button class="dropdown-item">' .
                '<div class="form-check ew-dropdown-checkbox">' .
                '<div class="form-check-input ew-dropdown-check-input" data-field="' . $field->Param . '"></div>' .
                '<label class="form-check-label ew-dropdown-check-label">' . $field->caption() . '</label></div></button>';
            return $item;
        }
        return null;
    }

    // Render other options
    public function renderOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];
        // Set up list action buttons
        foreach ($this->ListActions->Items as $listaction) {
            if ($listaction->Select == ACTION_MULTIPLE) {
                $item = &$option->add("custom_" . $listaction->Action);
                $caption = $listaction->Caption;
                $icon = ($listaction->Icon != "") ? '<i class="' . HtmlEncode($listaction->Icon) . '" data-caption="' . HtmlEncode($caption) . '"></i>' . $caption : $caption;
                $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="fmakina_shitjelist"' . $listaction->toDataAttrs() . '>' . $icon . '</button>';
                $item->Visible = $listaction->Allow;
            }
        }

        // Hide grid edit and other options
        if ($this->TotalRecords <= 0) {
            $option = $options["addedit"];
            $item = $option["gridedit"];
            if ($item) {
                $item->Visible = false;
            }
            $option = $options["action"];
            $option->hideAllOptions();
        }
    }

    // Process list action
    protected function processListAction()
    {
        global $Language, $Security, $Response;
        $userlist = "";
        $user = "";
        $filter = $this->getFilterFromRecordKeys();
        $userAction = Post("useraction", "");
        if ($filter != "" && $userAction != "") {
            // Check permission first
            $actionCaption = $userAction;
            if (array_key_exists($userAction, $this->ListActions->Items)) {
                $actionCaption = $this->ListActions[$userAction]->Caption;
                if (!$this->ListActions[$userAction]->Allow) {
                    $errmsg = str_replace('%s', $actionCaption, $Language->phrase("CustomActionNotAllowed"));
                    if (Post("ajax") == $userAction) { // Ajax
                        echo "<p class=\"text-danger\">" . $errmsg . "</p>";
                        return true;
                    } else {
                        $this->setFailureMessage($errmsg);
                        return false;
                    }
                }
            }
            $this->CurrentFilter = $filter;
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $rs = LoadRecordset($sql, $conn);
            $this->UserAction = $userAction;
            $this->ActionValue = Post("actionvalue");

            // Call row action event
            if ($rs) {
                if ($this->UseTransaction) {
                    $conn->beginTransaction();
                }
                $this->SelectedCount = $rs->recordCount();
                $this->SelectedIndex = 0;
                while (!$rs->EOF) {
                    $this->SelectedIndex++;
                    $row = $rs->fields;
                    $processed = $this->rowCustomAction($userAction, $row);
                    if (!$processed) {
                        break;
                    }
                    $rs->moveNext();
                }
                if ($processed) {
                    if ($this->UseTransaction) { // Commit transaction
                        $conn->commit();
                    }
                    if ($this->getSuccessMessage() == "" && !ob_get_length() && !$Response->getBody()->getSize()) { // No output
                        $this->setSuccessMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionCompleted"))); // Set up success message
                    }
                } else {
                    if ($this->UseTransaction) { // Rollback transaction
                        $conn->rollback();
                    }

                    // Set up error message
                    if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                        // Use the message, do nothing
                    } elseif ($this->CancelMessage != "") {
                        $this->setFailureMessage($this->CancelMessage);
                        $this->CancelMessage = "";
                    } else {
                        $this->setFailureMessage(str_replace('%s', $actionCaption, $Language->phrase("CustomActionFailed")));
                    }
                }
            }
            if ($rs) {
                $rs->close();
            }
            if (Post("ajax") == $userAction) { // Ajax
                if ($this->getSuccessMessage() != "") {
                    echo "<p class=\"text-success\">" . $this->getSuccessMessage() . "</p>";
                    $this->clearSuccessMessage(); // Clear message
                }
                if ($this->getFailureMessage() != "") {
                    echo "<p class=\"text-danger\">" . $this->getFailureMessage() . "</p>";
                    $this->clearFailureMessage(); // Clear message
                }
                return true;
            }
        }
        return false; // Not ajax request
    }

    // Load basic search values
    protected function loadBasicSearchValues()
    {
        $this->BasicSearch->setKeyword(Get(Config("TABLE_BASIC_SEARCH"), ""), false);
        if ($this->BasicSearch->Keyword != "" && $this->Command == "") {
            $this->Command = "search";
        }
        $this->BasicSearch->setType(Get(Config("TABLE_BASIC_SEARCH_TYPE"), ""), false);
    }

    // Load search values for validation
    protected function loadSearchValues()
    {
        // Load search values
        $hasValue = false;

        // mshitjeID
        if ($this->mshitjeID->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeID->AdvancedSearch->SearchValue != "" || $this->mshitjeID->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeMarka
        if ($this->mshitjeMarka->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeMarka->AdvancedSearch->SearchValue != "" || $this->mshitjeMarka->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeModeli
        if ($this->mshitjeModeli->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeModeli->AdvancedSearch->SearchValue != "" || $this->mshitjeModeli->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeTipi
        if ($this->mshitjeTipi->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeTipi->AdvancedSearch->SearchValue != "" || $this->mshitjeTipi->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeStruktura
        if ($this->mshitjeStruktura->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeStruktura->AdvancedSearch->SearchValue != "" || $this->mshitjeStruktura->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeKapacitetiMotorrit
        if ($this->mshitjeKapacitetiMotorrit->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchValue != "" || $this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeVitiProdhimit
        if ($this->mshitjeVitiProdhimit->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeVitiProdhimit->AdvancedSearch->SearchValue != "" || $this->mshitjeVitiProdhimit->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeKarburant
        if ($this->mshitjeKarburant->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeKarburant->AdvancedSearch->SearchValue != "" || $this->mshitjeKarburant->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeNgjyra
        if ($this->mshitjeNgjyra->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeNgjyra->AdvancedSearch->SearchValue != "" || $this->mshitjeNgjyra->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeNrVendeve
        if ($this->mshitjeNrVendeve->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeNrVendeve->AdvancedSearch->SearchValue != "" || $this->mshitjeNrVendeve->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeKambio
        if ($this->mshitjeKambio->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeKambio->AdvancedSearch->SearchValue != "" || $this->mshitjeKambio->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjePrejardhja
        if ($this->mshitjePrejardhja->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjePrejardhja->AdvancedSearch->SearchValue != "" || $this->mshitjePrejardhja->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeTargaAL
        if ($this->mshitjeTargaAL->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeTargaAL->AdvancedSearch->SearchValue != "" || $this->mshitjeTargaAL->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeKilometra
        if ($this->mshitjeKilometra->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeKilometra->AdvancedSearch->SearchValue != "" || $this->mshitjeKilometra->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeFotografi
        if ($this->mshitjeFotografi->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeFotografi->AdvancedSearch->SearchValue != "" || $this->mshitjeFotografi->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjePershkrimi
        if ($this->mshitjePershkrimi->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjePershkrimi->AdvancedSearch->SearchValue != "" || $this->mshitjePershkrimi->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeCmimi
        if ($this->mshitjeCmimi->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeCmimi->AdvancedSearch->SearchValue != "" || $this->mshitjeCmimi->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeIndex
        if ($this->mshitjeIndex->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeIndex->AdvancedSearch->SearchValue != "" || $this->mshitjeIndex->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjePromo
        if ($this->mshitjePromo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjePromo->AdvancedSearch->SearchValue != "" || $this->mshitjePromo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeAktiv
        if ($this->mshitjeAktiv->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeAktiv->AdvancedSearch->SearchValue != "" || $this->mshitjeAktiv->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeShitur
        if ($this->mshitjeShitur->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeShitur->AdvancedSearch->SearchValue != "" || $this->mshitjeShitur->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeAutori
        if ($this->mshitjeAutori->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeAutori->AdvancedSearch->SearchValue != "" || $this->mshitjeAutori->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeKrijuar
        if ($this->mshitjeKrijuar->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeKrijuar->AdvancedSearch->SearchValue != "" || $this->mshitjeKrijuar->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mshitjeAzhornuar
        if ($this->mshitjeAzhornuar->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mshitjeAzhornuar->AdvancedSearch->SearchValue != "" || $this->mshitjeAzhornuar->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }
        return $hasValue;
    }

    // Load recordset
    public function loadRecordset($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        $rs = new Recordset($result, $sql);

        // Call Recordset Selected event
        $this->recordsetSelected($rs);
        return $rs;
    }

    // Load records as associative array
    public function loadRows($offset = -1, $rowcnt = -1)
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load recordset
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $result = $sql->execute();
        return $result->fetchAll(FetchMode::ASSOCIATIVE);
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
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // mshitjeID

        // mshitjeMarka

        // mshitjeModeli

        // mshitjeTipi

        // mshitjeStruktura

        // mshitjeKapacitetiMotorrit

        // mshitjeVitiProdhimit

        // mshitjeKarburant

        // mshitjeNgjyra

        // mshitjeNrVendeve

        // mshitjeKambio

        // mshitjePrejardhja

        // mshitjeTargaAL

        // mshitjeKilometra

        // mshitjeFotografi

        // mshitjePershkrimi

        // mshitjeCmimi

        // mshitjeIndex

        // mshitjePromo

        // mshitjeAktiv

        // mshitjeShitur

        // mshitjeAutori

        // mshitjeKrijuar

        // mshitjeAzhornuar

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

            // mshitjeID
            $this->mshitjeID->LinkCustomAttributes = "";
            $this->mshitjeID->HrefValue = "";
            $this->mshitjeID->TooltipValue = "";

            // mshitjeMarka
            $this->mshitjeMarka->LinkCustomAttributes = "";
            $this->mshitjeMarka->HrefValue = "";
            $this->mshitjeMarka->TooltipValue = "";

            // mshitjeModeli
            $this->mshitjeModeli->LinkCustomAttributes = "";
            $this->mshitjeModeli->HrefValue = "";
            $this->mshitjeModeli->TooltipValue = "";

            // mshitjeTipi
            $this->mshitjeTipi->LinkCustomAttributes = "";
            $this->mshitjeTipi->HrefValue = "";
            $this->mshitjeTipi->TooltipValue = "";

            // mshitjeKapacitetiMotorrit
            $this->mshitjeKapacitetiMotorrit->LinkCustomAttributes = "";
            $this->mshitjeKapacitetiMotorrit->HrefValue = "";
            $this->mshitjeKapacitetiMotorrit->TooltipValue = "";

            // mshitjeVitiProdhimit
            $this->mshitjeVitiProdhimit->LinkCustomAttributes = "";
            $this->mshitjeVitiProdhimit->HrefValue = "";
            $this->mshitjeVitiProdhimit->TooltipValue = "";

            // mshitjeKarburant
            $this->mshitjeKarburant->LinkCustomAttributes = "";
            $this->mshitjeKarburant->HrefValue = "";
            $this->mshitjeKarburant->TooltipValue = "";

            // mshitjeNrVendeve
            $this->mshitjeNrVendeve->LinkCustomAttributes = "";
            $this->mshitjeNrVendeve->HrefValue = "";
            $this->mshitjeNrVendeve->TooltipValue = "";

            // mshitjeKambio
            $this->mshitjeKambio->LinkCustomAttributes = "";
            $this->mshitjeKambio->HrefValue = "";
            $this->mshitjeKambio->TooltipValue = "";

            // mshitjeTargaAL
            $this->mshitjeTargaAL->LinkCustomAttributes = "";
            $this->mshitjeTargaAL->HrefValue = "";
            $this->mshitjeTargaAL->TooltipValue = "";

            // mshitjeKilometra
            $this->mshitjeKilometra->LinkCustomAttributes = "";
            $this->mshitjeKilometra->HrefValue = "";
            $this->mshitjeKilometra->TooltipValue = "";

            // mshitjeCmimi
            $this->mshitjeCmimi->LinkCustomAttributes = "";
            $this->mshitjeCmimi->HrefValue = "";
            $this->mshitjeCmimi->TooltipValue = "";

            // mshitjeIndex
            $this->mshitjeIndex->LinkCustomAttributes = "";
            $this->mshitjeIndex->HrefValue = "";
            $this->mshitjeIndex->TooltipValue = "";

            // mshitjePromo
            $this->mshitjePromo->LinkCustomAttributes = "";
            $this->mshitjePromo->HrefValue = "";
            $this->mshitjePromo->TooltipValue = "";

            // mshitjeAktiv
            $this->mshitjeAktiv->LinkCustomAttributes = "";
            $this->mshitjeAktiv->HrefValue = "";
            $this->mshitjeAktiv->TooltipValue = "";

            // mshitjeShitur
            $this->mshitjeShitur->LinkCustomAttributes = "";
            $this->mshitjeShitur->HrefValue = "";
            $this->mshitjeShitur->TooltipValue = "";

            // mshitjeAutori
            $this->mshitjeAutori->LinkCustomAttributes = "";
            $this->mshitjeAutori->HrefValue = "";
            $this->mshitjeAutori->TooltipValue = "";

            // mshitjeKrijuar
            $this->mshitjeKrijuar->LinkCustomAttributes = "";
            $this->mshitjeKrijuar->HrefValue = "";
            $this->mshitjeKrijuar->TooltipValue = "";

            // mshitjeAzhornuar
            $this->mshitjeAzhornuar->LinkCustomAttributes = "";
            $this->mshitjeAzhornuar->HrefValue = "";
            $this->mshitjeAzhornuar->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // mshitjeID
            $this->mshitjeID->setupEditAttributes();
            $this->mshitjeID->EditCustomAttributes = "";
            $this->mshitjeID->EditValue = HtmlEncode($this->mshitjeID->AdvancedSearch->SearchValue);
            $this->mshitjeID->PlaceHolder = RemoveHtml($this->mshitjeID->caption());

            // mshitjeMarka
            $this->mshitjeMarka->setupEditAttributes();
            $this->mshitjeMarka->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeMarka->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->mshitjeMarka->AdvancedSearch->ViewValue = $this->mshitjeMarka->lookupCacheOption($curVal);
            } else {
                $this->mshitjeMarka->AdvancedSearch->ViewValue = $this->mshitjeMarka->Lookup !== null && is_array($this->mshitjeMarka->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeMarka->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->mshitjeMarka->EditValue = array_values($this->mshitjeMarka->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmarkaID`" . SearchString("=", $this->mshitjeMarka->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
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
            $this->mshitjeMarka->setupEditAttributes();
            $this->mshitjeMarka->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeMarka->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->mshitjeMarka->AdvancedSearch->ViewValue2 = $this->mshitjeMarka->lookupCacheOption($curVal);
            } else {
                $this->mshitjeMarka->AdvancedSearch->ViewValue2 = $this->mshitjeMarka->Lookup !== null && is_array($this->mshitjeMarka->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeMarka->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->mshitjeMarka->EditValue2 = array_values($this->mshitjeMarka->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmarkaID`" . SearchString("=", $this->mshitjeMarka->AdvancedSearch->SearchValue2, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mshitjeMarka->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mshitjeMarka->EditValue2 = $arwrk;
            }
            $this->mshitjeMarka->PlaceHolder = RemoveHtml($this->mshitjeMarka->caption());

            // mshitjeModeli
            $this->mshitjeModeli->setupEditAttributes();
            $this->mshitjeModeli->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeModeli->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->mshitjeModeli->AdvancedSearch->ViewValue = $this->mshitjeModeli->lookupCacheOption($curVal);
            } else {
                $this->mshitjeModeli->AdvancedSearch->ViewValue = $this->mshitjeModeli->Lookup !== null && is_array($this->mshitjeModeli->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeModeli->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->mshitjeModeli->EditValue = array_values($this->mshitjeModeli->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmodeliID`" . SearchString("=", $this->mshitjeModeli->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
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
            $this->mshitjeModeli->setupEditAttributes();
            $this->mshitjeModeli->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeModeli->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->mshitjeModeli->AdvancedSearch->ViewValue2 = $this->mshitjeModeli->lookupCacheOption($curVal);
            } else {
                $this->mshitjeModeli->AdvancedSearch->ViewValue2 = $this->mshitjeModeli->Lookup !== null && is_array($this->mshitjeModeli->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeModeli->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->mshitjeModeli->EditValue2 = array_values($this->mshitjeModeli->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmodeliID`" . SearchString("=", $this->mshitjeModeli->AdvancedSearch->SearchValue2, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mshitjeModeli->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mshitjeModeli->EditValue2 = $arwrk;
            }
            $this->mshitjeModeli->PlaceHolder = RemoveHtml($this->mshitjeModeli->caption());

            // mshitjeTipi
            $this->mshitjeTipi->setupEditAttributes();
            $this->mshitjeTipi->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeTipi->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->mshitjeTipi->AdvancedSearch->ViewValue = $this->mshitjeTipi->lookupCacheOption($curVal);
            } else {
                $this->mshitjeTipi->AdvancedSearch->ViewValue = $this->mshitjeTipi->Lookup !== null && is_array($this->mshitjeTipi->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeTipi->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->mshitjeTipi->EditValue = array_values($this->mshitjeTipi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mtipiID`" . SearchString("=", $this->mshitjeTipi->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
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
            $this->mshitjeTipi->setupEditAttributes();
            $this->mshitjeTipi->EditCustomAttributes = "";
            $curVal = trim(strval($this->mshitjeTipi->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->mshitjeTipi->AdvancedSearch->ViewValue2 = $this->mshitjeTipi->lookupCacheOption($curVal);
            } else {
                $this->mshitjeTipi->AdvancedSearch->ViewValue2 = $this->mshitjeTipi->Lookup !== null && is_array($this->mshitjeTipi->lookupOptions()) ? $curVal : null;
            }
            if ($this->mshitjeTipi->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->mshitjeTipi->EditValue2 = array_values($this->mshitjeTipi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mtipiID`" . SearchString("=", $this->mshitjeTipi->AdvancedSearch->SearchValue2, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mshitjeTipi->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mshitjeTipi->EditValue2 = $arwrk;
            }
            $this->mshitjeTipi->PlaceHolder = RemoveHtml($this->mshitjeTipi->caption());

            // mshitjeKapacitetiMotorrit
            $this->mshitjeKapacitetiMotorrit->setupEditAttributes();
            $this->mshitjeKapacitetiMotorrit->EditCustomAttributes = "";
            if (!$this->mshitjeKapacitetiMotorrit->Raw) {
                $this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchValue = HtmlDecode($this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchValue);
            }
            $this->mshitjeKapacitetiMotorrit->EditValue = HtmlEncode($this->mshitjeKapacitetiMotorrit->AdvancedSearch->SearchValue);
            $this->mshitjeKapacitetiMotorrit->PlaceHolder = RemoveHtml($this->mshitjeKapacitetiMotorrit->caption());

            // mshitjeVitiProdhimit
            $this->mshitjeVitiProdhimit->setupEditAttributes();
            $this->mshitjeVitiProdhimit->EditCustomAttributes = "";
            $this->mshitjeVitiProdhimit->EditValue = HtmlEncode($this->mshitjeVitiProdhimit->AdvancedSearch->SearchValue);
            $this->mshitjeVitiProdhimit->PlaceHolder = RemoveHtml($this->mshitjeVitiProdhimit->caption());
            $this->mshitjeVitiProdhimit->setupEditAttributes();
            $this->mshitjeVitiProdhimit->EditCustomAttributes = "";
            $this->mshitjeVitiProdhimit->EditValue2 = HtmlEncode($this->mshitjeVitiProdhimit->AdvancedSearch->SearchValue2);
            $this->mshitjeVitiProdhimit->PlaceHolder = RemoveHtml($this->mshitjeVitiProdhimit->caption());

            // mshitjeKarburant
            $this->mshitjeKarburant->EditCustomAttributes = "";
            $this->mshitjeKarburant->EditValue = $this->mshitjeKarburant->options(false);
            $this->mshitjeKarburant->PlaceHolder = RemoveHtml($this->mshitjeKarburant->caption());
            $this->mshitjeKarburant->EditCustomAttributes = "";
            $this->mshitjeKarburant->EditValue2 = $this->mshitjeKarburant->options(false);
            $this->mshitjeKarburant->PlaceHolder = RemoveHtml($this->mshitjeKarburant->caption());

            // mshitjeNrVendeve
            $this->mshitjeNrVendeve->setupEditAttributes();
            $this->mshitjeNrVendeve->EditCustomAttributes = "";
            if (!$this->mshitjeNrVendeve->Raw) {
                $this->mshitjeNrVendeve->AdvancedSearch->SearchValue = HtmlDecode($this->mshitjeNrVendeve->AdvancedSearch->SearchValue);
            }
            $this->mshitjeNrVendeve->EditValue = HtmlEncode($this->mshitjeNrVendeve->AdvancedSearch->SearchValue);
            $this->mshitjeNrVendeve->PlaceHolder = RemoveHtml($this->mshitjeNrVendeve->caption());

            // mshitjeKambio
            $this->mshitjeKambio->EditCustomAttributes = "";
            $this->mshitjeKambio->EditValue = $this->mshitjeKambio->options(false);
            $this->mshitjeKambio->PlaceHolder = RemoveHtml($this->mshitjeKambio->caption());
            $this->mshitjeKambio->EditCustomAttributes = "";
            $this->mshitjeKambio->EditValue2 = $this->mshitjeKambio->options(false);
            $this->mshitjeKambio->PlaceHolder = RemoveHtml($this->mshitjeKambio->caption());

            // mshitjeTargaAL
            $this->mshitjeTargaAL->EditCustomAttributes = "";
            $this->mshitjeTargaAL->EditValue = $this->mshitjeTargaAL->options(false);
            $this->mshitjeTargaAL->PlaceHolder = RemoveHtml($this->mshitjeTargaAL->caption());
            $this->mshitjeTargaAL->EditCustomAttributes = "";
            $this->mshitjeTargaAL->EditValue2 = $this->mshitjeTargaAL->options(false);
            $this->mshitjeTargaAL->PlaceHolder = RemoveHtml($this->mshitjeTargaAL->caption());

            // mshitjeKilometra
            $this->mshitjeKilometra->setupEditAttributes();
            $this->mshitjeKilometra->EditCustomAttributes = "";
            $this->mshitjeKilometra->EditValue = HtmlEncode($this->mshitjeKilometra->AdvancedSearch->SearchValue);
            $this->mshitjeKilometra->PlaceHolder = RemoveHtml($this->mshitjeKilometra->caption());

            // mshitjeCmimi
            $this->mshitjeCmimi->setupEditAttributes();
            $this->mshitjeCmimi->EditCustomAttributes = "";
            if (!$this->mshitjeCmimi->Raw) {
                $this->mshitjeCmimi->AdvancedSearch->SearchValue = HtmlDecode($this->mshitjeCmimi->AdvancedSearch->SearchValue);
            }
            $this->mshitjeCmimi->EditValue = HtmlEncode($this->mshitjeCmimi->AdvancedSearch->SearchValue);
            $this->mshitjeCmimi->PlaceHolder = RemoveHtml($this->mshitjeCmimi->caption());

            // mshitjeIndex
            $this->mshitjeIndex->EditCustomAttributes = "";
            $this->mshitjeIndex->EditValue = $this->mshitjeIndex->options(false);
            $this->mshitjeIndex->PlaceHolder = RemoveHtml($this->mshitjeIndex->caption());
            $this->mshitjeIndex->EditCustomAttributes = "";
            $this->mshitjeIndex->EditValue2 = $this->mshitjeIndex->options(false);
            $this->mshitjeIndex->PlaceHolder = RemoveHtml($this->mshitjeIndex->caption());

            // mshitjePromo
            $this->mshitjePromo->EditCustomAttributes = "";
            $this->mshitjePromo->EditValue = $this->mshitjePromo->options(false);
            $this->mshitjePromo->PlaceHolder = RemoveHtml($this->mshitjePromo->caption());
            $this->mshitjePromo->EditCustomAttributes = "";
            $this->mshitjePromo->EditValue2 = $this->mshitjePromo->options(false);
            $this->mshitjePromo->PlaceHolder = RemoveHtml($this->mshitjePromo->caption());

            // mshitjeAktiv
            $this->mshitjeAktiv->EditCustomAttributes = "";
            $this->mshitjeAktiv->EditValue = $this->mshitjeAktiv->options(false);
            $this->mshitjeAktiv->PlaceHolder = RemoveHtml($this->mshitjeAktiv->caption());
            $this->mshitjeAktiv->EditCustomAttributes = "";
            $this->mshitjeAktiv->EditValue2 = $this->mshitjeAktiv->options(false);
            $this->mshitjeAktiv->PlaceHolder = RemoveHtml($this->mshitjeAktiv->caption());

            // mshitjeShitur
            $this->mshitjeShitur->EditCustomAttributes = "";
            $this->mshitjeShitur->EditValue = $this->mshitjeShitur->options(false);
            $this->mshitjeShitur->PlaceHolder = RemoveHtml($this->mshitjeShitur->caption());
            $this->mshitjeShitur->EditCustomAttributes = "";
            $this->mshitjeShitur->EditValue2 = $this->mshitjeShitur->options(false);
            $this->mshitjeShitur->PlaceHolder = RemoveHtml($this->mshitjeShitur->caption());

            // mshitjeAutori
            $this->mshitjeAutori->setupEditAttributes();
            $this->mshitjeAutori->EditCustomAttributes = "";
            $this->mshitjeAutori->EditValue = HtmlEncode($this->mshitjeAutori->AdvancedSearch->SearchValue);
            $this->mshitjeAutori->PlaceHolder = RemoveHtml($this->mshitjeAutori->caption());

            // mshitjeKrijuar
            $this->mshitjeKrijuar->setupEditAttributes();
            $this->mshitjeKrijuar->EditCustomAttributes = "";
            $this->mshitjeKrijuar->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->mshitjeKrijuar->AdvancedSearch->SearchValue, $this->mshitjeKrijuar->formatPattern()), $this->mshitjeKrijuar->formatPattern()));
            $this->mshitjeKrijuar->PlaceHolder = RemoveHtml($this->mshitjeKrijuar->caption());

            // mshitjeAzhornuar
            $this->mshitjeAzhornuar->setupEditAttributes();
            $this->mshitjeAzhornuar->EditCustomAttributes = "";
            $this->mshitjeAzhornuar->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->mshitjeAzhornuar->AdvancedSearch->SearchValue, $this->mshitjeAzhornuar->formatPattern()), $this->mshitjeAzhornuar->formatPattern()));
            $this->mshitjeAzhornuar->PlaceHolder = RemoveHtml($this->mshitjeAzhornuar->caption());
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate search
    protected function validateSearch()
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        if (!CheckInteger($this->mshitjeVitiProdhimit->AdvancedSearch->SearchValue)) {
            $this->mshitjeVitiProdhimit->addErrorMessage($this->mshitjeVitiProdhimit->getErrorMessage(false));
        }
        if (!CheckInteger($this->mshitjeVitiProdhimit->AdvancedSearch->SearchValue2)) {
            $this->mshitjeVitiProdhimit->addErrorMessage($this->mshitjeVitiProdhimit->getErrorMessage(false));
        }

        // Return validate result
        $validateSearch = !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateSearch = $validateSearch && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateSearch;
    }

    // Load advanced search
    public function loadAdvancedSearch()
    {
        $this->mshitjeID->AdvancedSearch->load();
        $this->mshitjeMarka->AdvancedSearch->load();
        $this->mshitjeModeli->AdvancedSearch->load();
        $this->mshitjeTipi->AdvancedSearch->load();
        $this->mshitjeStruktura->AdvancedSearch->load();
        $this->mshitjeKapacitetiMotorrit->AdvancedSearch->load();
        $this->mshitjeVitiProdhimit->AdvancedSearch->load();
        $this->mshitjeKarburant->AdvancedSearch->load();
        $this->mshitjeNgjyra->AdvancedSearch->load();
        $this->mshitjeNrVendeve->AdvancedSearch->load();
        $this->mshitjeKambio->AdvancedSearch->load();
        $this->mshitjePrejardhja->AdvancedSearch->load();
        $this->mshitjeTargaAL->AdvancedSearch->load();
        $this->mshitjeKilometra->AdvancedSearch->load();
        $this->mshitjeFotografi->AdvancedSearch->load();
        $this->mshitjePershkrimi->AdvancedSearch->load();
        $this->mshitjeCmimi->AdvancedSearch->load();
        $this->mshitjeIndex->AdvancedSearch->load();
        $this->mshitjePromo->AdvancedSearch->load();
        $this->mshitjeAktiv->AdvancedSearch->load();
        $this->mshitjeShitur->AdvancedSearch->load();
        $this->mshitjeAutori->AdvancedSearch->load();
        $this->mshitjeKrijuar->AdvancedSearch->load();
        $this->mshitjeAzhornuar->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        $exportUrl = GetUrl($pageUrl . "export=" . $type . ($custom ? "&amp;custom=1" : ""));
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" form=\"fmakina_shitjelist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"excel\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToExcel") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" form=\"fmakina_shitjelist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"word\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToWord") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdfText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdfText")) . "\" form=\"fmakina_shitjelist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"pdf\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToPdf") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdfText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdfText")) . "\">" . $Language->phrase("ExportToPdf") . "</a>";
            }
        } elseif (SameText($type, "html")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-html\" title=\"" . HtmlEncode($Language->phrase("ExportToHtmlText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToHtmlText")) . "\">" . $Language->phrase("ExportToHtml") . "</a>";
        } elseif (SameText($type, "xml")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-xml\" title=\"" . HtmlEncode($Language->phrase("ExportToXmlText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToXmlText")) . "\">" . $Language->phrase("ExportToXml") . "</a>";
        } elseif (SameText($type, "csv")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-csv\" title=\"" . HtmlEncode($Language->phrase("ExportToCsvText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToCsvText")) . "\">" . $Language->phrase("ExportToCsv") . "</a>";
        } elseif (SameText($type, "email")) {
            $url = $custom ? ' data-url="' . $exportUrl . '"' : '';
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . $Language->phrase("ExportToEmailText") . '" data-caption="' . $Language->phrase("ExportToEmailText") . '" form="fmakina_shitjelist" data-ew-action="email" data-hdr="' . $Language->phrase("ExportToEmailText") . '" data-sel="false"' . $url . '>' . $Language->phrase("ExportToEmail") . '</button>';
        } elseif (SameText($type, "print")) {
            return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-print\" title=\"" . HtmlEncode($Language->phrase("ExportToPrintText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPrintText")) . "\">" . $Language->phrase("PrinterFriendly") . "</a>";
        }
    }

    // Set up export options
    protected function setupExportOptions()
    {
        global $Language;

        // Printer friendly
        $item = &$this->ExportOptions->add("print");
        $item->Body = $this->getExportTag("print");
        $item->Visible = true;

        // Export to Excel
        $item = &$this->ExportOptions->add("excel");
        $item->Body = $this->getExportTag("excel");
        $item->Visible = true;

        // Export to Word
        $item = &$this->ExportOptions->add("word");
        $item->Body = $this->getExportTag("word");
        $item->Visible = false;

        // Export to HTML
        $item = &$this->ExportOptions->add("html");
        $item->Body = $this->getExportTag("html");
        $item->Visible = true;

        // Export to XML
        $item = &$this->ExportOptions->add("xml");
        $item->Body = $this->getExportTag("xml");
        $item->Visible = false;

        // Export to CSV
        $item = &$this->ExportOptions->add("csv");
        $item->Body = $this->getExportTag("csv");
        $item->Visible = false;

        // Export to PDF
        $item = &$this->ExportOptions->add("pdf");
        $item->Body = $this->getExportTag("pdf");
        $item->Visible = false;

        // Export to Email
        $item = &$this->ExportOptions->add("email");
        $item->Body = $this->getExportTag("email");
        $item->Visible = true;

        // Drop down button for export
        $this->ExportOptions->UseButtonGroup = true;
        $this->ExportOptions->UseDropDownButton = false;
        if ($this->ExportOptions->UseButtonGroup && IsMobile()) {
            $this->ExportOptions->UseDropDownButton = true;
        }
        $this->ExportOptions->DropDownButtonPhrase = $Language->phrase("ButtonExport");

        // Add group option item
        $item = &$this->ExportOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
    }

    // Set up search options
    protected function setupSearchOptions()
    {
        global $Language, $Security;
        $pageUrl = $this->pageUrl();
        $this->SearchOptions = new ListOptions(["TagClassName" => "ew-search-option"]);

        // Search button
        $item = &$this->SearchOptions->add("searchtoggle");
        $searchToggleClass = ($this->SearchWhere != "") ? " active" : "";
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"fmakina_shitjesrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
        $item->Visible = true;

        // Show all button
        $item = &$this->SearchOptions->add("showall");
        $item->Body = "<a class=\"btn btn-default ew-show-all\" title=\"" . $Language->phrase("ShowAll") . "\" data-caption=\"" . $Language->phrase("ShowAll") . "\" href=\"" . $pageUrl . "cmd=reset\">" . $Language->phrase("ShowAllBtn") . "</a>";
        $item->Visible = ($this->SearchWhere != $this->DefaultSearchWhere && $this->SearchWhere != "0=101");

        // Button group for search
        $this->SearchOptions->UseDropDownButton = false;
        $this->SearchOptions->UseButtonGroup = true;
        $this->SearchOptions->DropDownButtonPhrase = $Language->phrase("ButtonSearch");

        // Add group option item
        $item = &$this->SearchOptions->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Hide search options
        if ($this->isExport() || $this->CurrentAction) {
            $this->SearchOptions->hideAllOptions();
        }
        if (!$Security->canSearch()) {
            $this->SearchOptions->hideAllOptions();
            $this->FilterOptions->hideAllOptions();
        }
    }

    // Check if any search fields
    public function hasSearchFields()
    {
        return true;
    }

    // Render search options
    protected function renderSearchOptions()
    {
        if (!$this->hasSearchFields() && $this->SearchOptions["searchtoggle"]) {
            $this->SearchOptions["searchtoggle"]->Visible = false;
        }
    }

    /**
    * Export data in HTML/CSV/Word/Excel/XML/Email/PDF format
    *
    * @param bool $return Return the data rather than output it
    * @return mixed
    */
    public function exportData($return = false)
    {
        global $Language;
        $utf8 = SameText(Config("PROJECT_CHARSET"), "utf-8");

        // Load recordset
        $this->TotalRecords = $this->listRecordCount();
        $this->StartRecord = 1;

        // Export all
        if ($this->ExportAll) {
            if (Config("EXPORT_ALL_TIME_LIMIT") >= 0) {
                @set_time_limit(Config("EXPORT_ALL_TIME_LIMIT"));
            }
            $this->DisplayRecords = $this->TotalRecords;
            $this->StopRecord = $this->TotalRecords;
        } else { // Export one page only
            $this->setupStartRecord(); // Set up start record position
            // Set the last record to display
            if ($this->DisplayRecords <= 0) {
                $this->StopRecord = $this->TotalRecords;
            } else {
                $this->StopRecord = $this->StartRecord + $this->DisplayRecords - 1;
            }
        }
        $rs = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords);
        $this->ExportDoc = GetExportDocument($this, "h");
        $doc = &$this->ExportDoc;
        if (!$doc) {
            $this->setFailureMessage($Language->phrase("ExportClassNotFound")); // Export class not found
        }
        if (!$rs || !$doc) {
            RemoveHeader("Content-Type"); // Remove header
            RemoveHeader("Content-Disposition");
            $this->showMessage();
            return;
        }
        $this->StartRecord = 1;
        $this->StopRecord = $this->DisplayRecords <= 0 ? $this->TotalRecords : $this->DisplayRecords;

        // Call Page Exporting server event
        $this->ExportDoc->ExportCustom = !$this->pageExporting();
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        $doc->Text .= $header;
        $this->exportDocument($doc, $rs, $this->StartRecord, $this->StopRecord, "");
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        $doc->Text .= $footer;

        // Close recordset
        $rs->close();

        // Call Page Exported server event
        $this->pageExported();

        // Export header and footer
        $doc->exportHeaderAndFooter();

        // Clean output buffer (without destroying output buffer)
        $buffer = ob_get_contents(); // Save the output buffer
        if (!Config("DEBUG") && $buffer) {
            ob_clean();
        }

        // Write debug message if enabled
        if (Config("DEBUG") && !$this->isExport("pdf")) {
            echo GetDebugMessage();
        }

        // Output data
        if ($this->isExport("email")) {
            if ($return) {
                return $doc->Text; // Return email content
            } else {
                echo $this->exportEmail($doc->Text); // Send email
            }
        } else {
            $doc->export();
            if ($return) {
                RemoveHeader("Content-Type"); // Remove header
                RemoveHeader("Content-Disposition");
                $content = ob_get_contents();
                if ($content) {
                    ob_clean();
                }
                if ($buffer) {
                    echo $buffer; // Resume the output buffer
                }
                return $content;
            }
        }
    }

    // Export email
    protected function exportEmail($emailContent)
    {
        global $TempImages, $Language;
        $sender = Post("sender", "");
        $recipient = Post("recipient", "");
        $cc = Post("cc", "");
        $bcc = Post("bcc", "");

        // Subject
        $subject = Post("subject", "");
        $emailSubject = $subject;

        // Message
        $content = Post("message", "");
        $emailMessage = $content;

        // Check sender
        if ($sender == "") {
            return "<p class=\"text-danger\">" . str_replace("%s", $Language->phrase("Sender"), $Language->phrase("EnterRequiredField")) . "</p>";
        }
        if (!CheckEmail($sender)) {
            return "<p class=\"text-danger\">" . $Language->phrase("EnterProperSenderEmail") . "</p>";
        }

        // Check recipient
        if ($recipient == "") {
            return "<p class=\"text-danger\">" . str_replace("%s", $Language->phrase("Recipient"), $Language->phrase("EnterRequiredField")) . "</p>";
        }
        if (!CheckEmails($recipient, Config("MAX_EMAIL_RECIPIENT"))) {
            return "<p class=\"text-danger\">" . $Language->phrase("EnterProperRecipientEmail") . "</p>";
        }

        // Check cc
        if (!CheckEmails($cc, Config("MAX_EMAIL_RECIPIENT"))) {
            return "<p class=\"text-danger\">" . $Language->phrase("EnterProperCcEmail") . "</p>";
        }

        // Check bcc
        if (!CheckEmails($bcc, Config("MAX_EMAIL_RECIPIENT"))) {
            return "<p class=\"text-danger\">" . $Language->phrase("EnterProperBccEmail") . "</p>";
        }

        // Check email sent count
        $_SESSION[Config("EXPORT_EMAIL_COUNTER")] = Session(Config("EXPORT_EMAIL_COUNTER")) ?? 0;
        if ((int)Session(Config("EXPORT_EMAIL_COUNTER")) > Config("MAX_EMAIL_SENT_COUNT")) {
            return "<p class=\"text-danger\">" . $Language->phrase("ExceedMaxEmailExport") . "</p>";
        }

        // Send email
        $email = new Email();
        $email->Sender = $sender; // Sender
        $email->Recipient = $recipient; // Recipient
        $email->Cc = $cc; // Cc
        $email->Bcc = $bcc; // Bcc
        $email->Subject = $emailSubject; // Subject
        $email->Format = "html";
        if ($emailMessage != "") {
            $emailMessage = RemoveXss($emailMessage) . "<br><br>";
        }
        foreach ($TempImages as $tmpImage) {
            $email->addEmbeddedImage($tmpImage);
        }
        $email->Content = $emailMessage . CleanEmailContent($emailContent); // Content
        $eventArgs = [];
        if ($this->Recordset) {
            $eventArgs["rs"] = &$this->Recordset;
        }
        $emailSent = false;
        if ($this->emailSending($email, $eventArgs)) {
            $emailSent = $email->send();
        }

        // Check email sent status
        if ($emailSent) {
            // Update email sent count
            $_SESSION[Config("EXPORT_EMAIL_COUNTER")]++;

            // Sent email success
            return "<p class=\"text-success\">" . $Language->phrase("SendEmailSuccess") . "</p>"; // Set up success message
        } else {
            // Sent email failure
            return "<p class=\"text-danger\">" . $email->SendErrDescription . "</p>";
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $url = preg_replace('/\?cmd=reset(all){0,1}$/i', '', $url); // Remove cmd=reset / cmd=resetall
        $Breadcrumb->add("list", $this->TableVar, $url, "", $this->TableVar, true);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            $pageNo = Get(Config("TABLE_PAGE_NO"));
            if ($pageNo !== null) { // Check for "pageno" parameter first
                $pageNo = ParseInteger($pageNo);
                if (is_numeric($pageNo)) {
                    $this->StartRecord = ($pageNo - 1) * $this->DisplayRecords + 1;
                    if ($this->StartRecord <= 0) {
                        $this->StartRecord = 1;
                    } elseif ($this->StartRecord >= (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1) {
                        $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1;
                    }
                    $this->setStartRecordNumber($this->StartRecord);
                }
            } elseif ($startRec !== null && is_numeric($startRec)) { // Check for "start" parameter
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

    // ListOptions Load event
    public function listOptionsLoad()
    {
        // Example:
        //$opt = &$this->ListOptions->Add("new");
        //$opt->Header = "xxx";
        //$opt->OnLeft = true; // Link on left
        //$opt->MoveTo(0); // Move to first column
    }

    // ListOptions Rendering event
    public function listOptionsRendering()
    {
        //Container("DetailTableGrid")->DetailAdd = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailEdit = (...condition...); // Set to true or false conditionally
        //Container("DetailTableGrid")->DetailView = (...condition...); // Set to true or false conditionally
    }

    // ListOptions Rendered event
    public function listOptionsRendered()
    {
        // Example:
        //$this->ListOptions["new"]->Body = "xxx";
    }

    // Row Custom Action event
    public function rowCustomAction($action, $row)
    {
        // Return false to abort
        return true;
    }

    // Page Exporting event
    // $this->ExportDoc = export document object
    public function pageExporting()
    {
        //$this->ExportDoc->Text = "my header"; // Export header
        //return false; // Return false to skip default export and use Row_Export event
        return true; // Return true to use default export and skip Row_Export event
    }

    // Row Export event
    // $this->ExportDoc = export document object
    public function rowExport($rs)
    {
        //$this->ExportDoc->Text .= "my content"; // Build HTML with field value: $rs["MyField"] or $this->MyField->ViewValue
    }

    // Page Exported event
    // $this->ExportDoc = export document object
    public function pageExported()
    {
        //$this->ExportDoc->Text .= "my footer"; // Export footer
        //Log($this->ExportDoc->Text);
    }

    // Page Importing event
    public function pageImporting($reader, &$options)
    {
        //var_dump($reader); // Import data reader
        //var_dump($options); // Show all options for importing
        //return false; // Return false to skip import
        return true;
    }

    // Row Import event
    public function rowImport(&$row, $cnt)
    {
        //Log($cnt); // Import record count
        //var_dump($row); // Import row
        //return false; // Return false to skip import
        return true;
    }

    // Page Imported event
    public function pageImported($reader, $results)
    {
        //var_dump($reader); // Import data reader
        //var_dump($results); // Import results
    }
}
