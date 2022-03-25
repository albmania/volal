<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaList extends Makina
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina';

    // Page object name
    public $PageObjName = "MakinaList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fmakinalist";
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

        // Table object (makina)
        if (!isset($GLOBALS["makina"]) || get_class($GLOBALS["makina"]) == PROJECT_NAMESPACE . "makina") {
            $GLOBALS["makina"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "MakinaAdd";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "MakinaDelete";
        $this->MultiUpdateUrl = "MakinaUpdate";

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
        if ($this->isAddOrEdit()) {
            $this->makinaAutori->Visible = false;
        }
        if ($this->isAddOrEdit()) {
            $this->makinaShtuar->Visible = false;
        }
        if ($this->isAddOrEdit()) {
            $this->makinaModifikuar->Visible = false;
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
        $this->makinaID->setVisibility();
        $this->makinaKlienti->setVisibility();
        $this->makinaMarka->setVisibility();
        $this->makinaModeli->setVisibility();
        $this->makinaKarburanti->setVisibility();
        $this->makinaMadhesiaMotorrit->setVisibility();
        $this->makinaVitiProdhimit->setVisibility();
        $this->makinaNgjyra->Visible = false;
        $this->makinaInfoShtese->Visible = false;
        $this->makinaVitiRegAL->Visible = false;
        $this->makinaTarga->setVisibility();
        $this->makinaNrShasie->Visible = false;
        $this->makinaPrejardhja->Visible = false;
        $this->makinaShiturVOLAL->setVisibility();
        $this->makinaAutori->setVisibility();
        $this->makinaShtuar->setVisibility();
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

        // Setup other options
        $this->setupOtherOptions();

        // Set up custom action (compatible with old version)
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions->add($name, $action);
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->makinaKlienti);
        $this->setupLookupOptions($this->makinaKarburanti);
        $this->setupLookupOptions($this->makinaShiturVOLAL);

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
            $savedFilterList = $UserProfile->getSearchFilters(CurrentUserName(), "fmakinasrch");
        }
        $filterList = Concat($filterList, $this->makinaID->AdvancedSearch->toJson(), ","); // Field makinaID
        $filterList = Concat($filterList, $this->makinaKlienti->AdvancedSearch->toJson(), ","); // Field makinaKlienti
        $filterList = Concat($filterList, $this->makinaMarka->AdvancedSearch->toJson(), ","); // Field makinaMarka
        $filterList = Concat($filterList, $this->makinaModeli->AdvancedSearch->toJson(), ","); // Field makinaModeli
        $filterList = Concat($filterList, $this->makinaKarburanti->AdvancedSearch->toJson(), ","); // Field makinaKarburanti
        $filterList = Concat($filterList, $this->makinaMadhesiaMotorrit->AdvancedSearch->toJson(), ","); // Field makinaMadhesiaMotorrit
        $filterList = Concat($filterList, $this->makinaVitiProdhimit->AdvancedSearch->toJson(), ","); // Field makinaVitiProdhimit
        $filterList = Concat($filterList, $this->makinaNgjyra->AdvancedSearch->toJson(), ","); // Field makinaNgjyra
        $filterList = Concat($filterList, $this->makinaInfoShtese->AdvancedSearch->toJson(), ","); // Field makinaInfoShtese
        $filterList = Concat($filterList, $this->makinaVitiRegAL->AdvancedSearch->toJson(), ","); // Field makinaVitiRegAL
        $filterList = Concat($filterList, $this->makinaTarga->AdvancedSearch->toJson(), ","); // Field makinaTarga
        $filterList = Concat($filterList, $this->makinaNrShasie->AdvancedSearch->toJson(), ","); // Field makinaNrShasie
        $filterList = Concat($filterList, $this->makinaPrejardhja->AdvancedSearch->toJson(), ","); // Field makinaPrejardhja
        $filterList = Concat($filterList, $this->makinaShiturVOLAL->AdvancedSearch->toJson(), ","); // Field makinaShiturVOLAL
        $filterList = Concat($filterList, $this->makinaAutori->AdvancedSearch->toJson(), ","); // Field makinaAutori
        $filterList = Concat($filterList, $this->makinaShtuar->AdvancedSearch->toJson(), ","); // Field makinaShtuar
        $filterList = Concat($filterList, $this->makinaModifikuar->AdvancedSearch->toJson(), ","); // Field makinaModifikuar
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
            $UserProfile->setSearchFilters(CurrentUserName(), "fmakinasrch", $filters);
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

        // Field makinaID
        $this->makinaID->AdvancedSearch->SearchValue = @$filter["x_makinaID"];
        $this->makinaID->AdvancedSearch->SearchOperator = @$filter["z_makinaID"];
        $this->makinaID->AdvancedSearch->SearchCondition = @$filter["v_makinaID"];
        $this->makinaID->AdvancedSearch->SearchValue2 = @$filter["y_makinaID"];
        $this->makinaID->AdvancedSearch->SearchOperator2 = @$filter["w_makinaID"];
        $this->makinaID->AdvancedSearch->save();

        // Field makinaKlienti
        $this->makinaKlienti->AdvancedSearch->SearchValue = @$filter["x_makinaKlienti"];
        $this->makinaKlienti->AdvancedSearch->SearchOperator = @$filter["z_makinaKlienti"];
        $this->makinaKlienti->AdvancedSearch->SearchCondition = @$filter["v_makinaKlienti"];
        $this->makinaKlienti->AdvancedSearch->SearchValue2 = @$filter["y_makinaKlienti"];
        $this->makinaKlienti->AdvancedSearch->SearchOperator2 = @$filter["w_makinaKlienti"];
        $this->makinaKlienti->AdvancedSearch->save();

        // Field makinaMarka
        $this->makinaMarka->AdvancedSearch->SearchValue = @$filter["x_makinaMarka"];
        $this->makinaMarka->AdvancedSearch->SearchOperator = @$filter["z_makinaMarka"];
        $this->makinaMarka->AdvancedSearch->SearchCondition = @$filter["v_makinaMarka"];
        $this->makinaMarka->AdvancedSearch->SearchValue2 = @$filter["y_makinaMarka"];
        $this->makinaMarka->AdvancedSearch->SearchOperator2 = @$filter["w_makinaMarka"];
        $this->makinaMarka->AdvancedSearch->save();

        // Field makinaModeli
        $this->makinaModeli->AdvancedSearch->SearchValue = @$filter["x_makinaModeli"];
        $this->makinaModeli->AdvancedSearch->SearchOperator = @$filter["z_makinaModeli"];
        $this->makinaModeli->AdvancedSearch->SearchCondition = @$filter["v_makinaModeli"];
        $this->makinaModeli->AdvancedSearch->SearchValue2 = @$filter["y_makinaModeli"];
        $this->makinaModeli->AdvancedSearch->SearchOperator2 = @$filter["w_makinaModeli"];
        $this->makinaModeli->AdvancedSearch->save();

        // Field makinaKarburanti
        $this->makinaKarburanti->AdvancedSearch->SearchValue = @$filter["x_makinaKarburanti"];
        $this->makinaKarburanti->AdvancedSearch->SearchOperator = @$filter["z_makinaKarburanti"];
        $this->makinaKarburanti->AdvancedSearch->SearchCondition = @$filter["v_makinaKarburanti"];
        $this->makinaKarburanti->AdvancedSearch->SearchValue2 = @$filter["y_makinaKarburanti"];
        $this->makinaKarburanti->AdvancedSearch->SearchOperator2 = @$filter["w_makinaKarburanti"];
        $this->makinaKarburanti->AdvancedSearch->save();

        // Field makinaMadhesiaMotorrit
        $this->makinaMadhesiaMotorrit->AdvancedSearch->SearchValue = @$filter["x_makinaMadhesiaMotorrit"];
        $this->makinaMadhesiaMotorrit->AdvancedSearch->SearchOperator = @$filter["z_makinaMadhesiaMotorrit"];
        $this->makinaMadhesiaMotorrit->AdvancedSearch->SearchCondition = @$filter["v_makinaMadhesiaMotorrit"];
        $this->makinaMadhesiaMotorrit->AdvancedSearch->SearchValue2 = @$filter["y_makinaMadhesiaMotorrit"];
        $this->makinaMadhesiaMotorrit->AdvancedSearch->SearchOperator2 = @$filter["w_makinaMadhesiaMotorrit"];
        $this->makinaMadhesiaMotorrit->AdvancedSearch->save();

        // Field makinaVitiProdhimit
        $this->makinaVitiProdhimit->AdvancedSearch->SearchValue = @$filter["x_makinaVitiProdhimit"];
        $this->makinaVitiProdhimit->AdvancedSearch->SearchOperator = @$filter["z_makinaVitiProdhimit"];
        $this->makinaVitiProdhimit->AdvancedSearch->SearchCondition = @$filter["v_makinaVitiProdhimit"];
        $this->makinaVitiProdhimit->AdvancedSearch->SearchValue2 = @$filter["y_makinaVitiProdhimit"];
        $this->makinaVitiProdhimit->AdvancedSearch->SearchOperator2 = @$filter["w_makinaVitiProdhimit"];
        $this->makinaVitiProdhimit->AdvancedSearch->save();

        // Field makinaNgjyra
        $this->makinaNgjyra->AdvancedSearch->SearchValue = @$filter["x_makinaNgjyra"];
        $this->makinaNgjyra->AdvancedSearch->SearchOperator = @$filter["z_makinaNgjyra"];
        $this->makinaNgjyra->AdvancedSearch->SearchCondition = @$filter["v_makinaNgjyra"];
        $this->makinaNgjyra->AdvancedSearch->SearchValue2 = @$filter["y_makinaNgjyra"];
        $this->makinaNgjyra->AdvancedSearch->SearchOperator2 = @$filter["w_makinaNgjyra"];
        $this->makinaNgjyra->AdvancedSearch->save();

        // Field makinaInfoShtese
        $this->makinaInfoShtese->AdvancedSearch->SearchValue = @$filter["x_makinaInfoShtese"];
        $this->makinaInfoShtese->AdvancedSearch->SearchOperator = @$filter["z_makinaInfoShtese"];
        $this->makinaInfoShtese->AdvancedSearch->SearchCondition = @$filter["v_makinaInfoShtese"];
        $this->makinaInfoShtese->AdvancedSearch->SearchValue2 = @$filter["y_makinaInfoShtese"];
        $this->makinaInfoShtese->AdvancedSearch->SearchOperator2 = @$filter["w_makinaInfoShtese"];
        $this->makinaInfoShtese->AdvancedSearch->save();

        // Field makinaVitiRegAL
        $this->makinaVitiRegAL->AdvancedSearch->SearchValue = @$filter["x_makinaVitiRegAL"];
        $this->makinaVitiRegAL->AdvancedSearch->SearchOperator = @$filter["z_makinaVitiRegAL"];
        $this->makinaVitiRegAL->AdvancedSearch->SearchCondition = @$filter["v_makinaVitiRegAL"];
        $this->makinaVitiRegAL->AdvancedSearch->SearchValue2 = @$filter["y_makinaVitiRegAL"];
        $this->makinaVitiRegAL->AdvancedSearch->SearchOperator2 = @$filter["w_makinaVitiRegAL"];
        $this->makinaVitiRegAL->AdvancedSearch->save();

        // Field makinaTarga
        $this->makinaTarga->AdvancedSearch->SearchValue = @$filter["x_makinaTarga"];
        $this->makinaTarga->AdvancedSearch->SearchOperator = @$filter["z_makinaTarga"];
        $this->makinaTarga->AdvancedSearch->SearchCondition = @$filter["v_makinaTarga"];
        $this->makinaTarga->AdvancedSearch->SearchValue2 = @$filter["y_makinaTarga"];
        $this->makinaTarga->AdvancedSearch->SearchOperator2 = @$filter["w_makinaTarga"];
        $this->makinaTarga->AdvancedSearch->save();

        // Field makinaNrShasie
        $this->makinaNrShasie->AdvancedSearch->SearchValue = @$filter["x_makinaNrShasie"];
        $this->makinaNrShasie->AdvancedSearch->SearchOperator = @$filter["z_makinaNrShasie"];
        $this->makinaNrShasie->AdvancedSearch->SearchCondition = @$filter["v_makinaNrShasie"];
        $this->makinaNrShasie->AdvancedSearch->SearchValue2 = @$filter["y_makinaNrShasie"];
        $this->makinaNrShasie->AdvancedSearch->SearchOperator2 = @$filter["w_makinaNrShasie"];
        $this->makinaNrShasie->AdvancedSearch->save();

        // Field makinaPrejardhja
        $this->makinaPrejardhja->AdvancedSearch->SearchValue = @$filter["x_makinaPrejardhja"];
        $this->makinaPrejardhja->AdvancedSearch->SearchOperator = @$filter["z_makinaPrejardhja"];
        $this->makinaPrejardhja->AdvancedSearch->SearchCondition = @$filter["v_makinaPrejardhja"];
        $this->makinaPrejardhja->AdvancedSearch->SearchValue2 = @$filter["y_makinaPrejardhja"];
        $this->makinaPrejardhja->AdvancedSearch->SearchOperator2 = @$filter["w_makinaPrejardhja"];
        $this->makinaPrejardhja->AdvancedSearch->save();

        // Field makinaShiturVOLAL
        $this->makinaShiturVOLAL->AdvancedSearch->SearchValue = @$filter["x_makinaShiturVOLAL"];
        $this->makinaShiturVOLAL->AdvancedSearch->SearchOperator = @$filter["z_makinaShiturVOLAL"];
        $this->makinaShiturVOLAL->AdvancedSearch->SearchCondition = @$filter["v_makinaShiturVOLAL"];
        $this->makinaShiturVOLAL->AdvancedSearch->SearchValue2 = @$filter["y_makinaShiturVOLAL"];
        $this->makinaShiturVOLAL->AdvancedSearch->SearchOperator2 = @$filter["w_makinaShiturVOLAL"];
        $this->makinaShiturVOLAL->AdvancedSearch->save();

        // Field makinaAutori
        $this->makinaAutori->AdvancedSearch->SearchValue = @$filter["x_makinaAutori"];
        $this->makinaAutori->AdvancedSearch->SearchOperator = @$filter["z_makinaAutori"];
        $this->makinaAutori->AdvancedSearch->SearchCondition = @$filter["v_makinaAutori"];
        $this->makinaAutori->AdvancedSearch->SearchValue2 = @$filter["y_makinaAutori"];
        $this->makinaAutori->AdvancedSearch->SearchOperator2 = @$filter["w_makinaAutori"];
        $this->makinaAutori->AdvancedSearch->save();

        // Field makinaShtuar
        $this->makinaShtuar->AdvancedSearch->SearchValue = @$filter["x_makinaShtuar"];
        $this->makinaShtuar->AdvancedSearch->SearchOperator = @$filter["z_makinaShtuar"];
        $this->makinaShtuar->AdvancedSearch->SearchCondition = @$filter["v_makinaShtuar"];
        $this->makinaShtuar->AdvancedSearch->SearchValue2 = @$filter["y_makinaShtuar"];
        $this->makinaShtuar->AdvancedSearch->SearchOperator2 = @$filter["w_makinaShtuar"];
        $this->makinaShtuar->AdvancedSearch->save();

        // Field makinaModifikuar
        $this->makinaModifikuar->AdvancedSearch->SearchValue = @$filter["x_makinaModifikuar"];
        $this->makinaModifikuar->AdvancedSearch->SearchOperator = @$filter["z_makinaModifikuar"];
        $this->makinaModifikuar->AdvancedSearch->SearchCondition = @$filter["v_makinaModifikuar"];
        $this->makinaModifikuar->AdvancedSearch->SearchValue2 = @$filter["y_makinaModifikuar"];
        $this->makinaModifikuar->AdvancedSearch->SearchOperator2 = @$filter["w_makinaModifikuar"];
        $this->makinaModifikuar->AdvancedSearch->save();
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
        $this->buildSearchSql($where, $this->makinaID, $default, false); // makinaID
        $this->buildSearchSql($where, $this->makinaKlienti, $default, false); // makinaKlienti
        $this->buildSearchSql($where, $this->makinaMarka, $default, false); // makinaMarka
        $this->buildSearchSql($where, $this->makinaModeli, $default, false); // makinaModeli
        $this->buildSearchSql($where, $this->makinaKarburanti, $default, false); // makinaKarburanti
        $this->buildSearchSql($where, $this->makinaMadhesiaMotorrit, $default, false); // makinaMadhesiaMotorrit
        $this->buildSearchSql($where, $this->makinaVitiProdhimit, $default, false); // makinaVitiProdhimit
        $this->buildSearchSql($where, $this->makinaNgjyra, $default, false); // makinaNgjyra
        $this->buildSearchSql($where, $this->makinaInfoShtese, $default, false); // makinaInfoShtese
        $this->buildSearchSql($where, $this->makinaVitiRegAL, $default, false); // makinaVitiRegAL
        $this->buildSearchSql($where, $this->makinaTarga, $default, false); // makinaTarga
        $this->buildSearchSql($where, $this->makinaNrShasie, $default, false); // makinaNrShasie
        $this->buildSearchSql($where, $this->makinaPrejardhja, $default, false); // makinaPrejardhja
        $this->buildSearchSql($where, $this->makinaShiturVOLAL, $default, false); // makinaShiturVOLAL
        $this->buildSearchSql($where, $this->makinaAutori, $default, false); // makinaAutori
        $this->buildSearchSql($where, $this->makinaShtuar, $default, false); // makinaShtuar
        $this->buildSearchSql($where, $this->makinaModifikuar, $default, false); // makinaModifikuar

        // Set up search parm
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->makinaID->AdvancedSearch->save(); // makinaID
            $this->makinaKlienti->AdvancedSearch->save(); // makinaKlienti
            $this->makinaMarka->AdvancedSearch->save(); // makinaMarka
            $this->makinaModeli->AdvancedSearch->save(); // makinaModeli
            $this->makinaKarburanti->AdvancedSearch->save(); // makinaKarburanti
            $this->makinaMadhesiaMotorrit->AdvancedSearch->save(); // makinaMadhesiaMotorrit
            $this->makinaVitiProdhimit->AdvancedSearch->save(); // makinaVitiProdhimit
            $this->makinaNgjyra->AdvancedSearch->save(); // makinaNgjyra
            $this->makinaInfoShtese->AdvancedSearch->save(); // makinaInfoShtese
            $this->makinaVitiRegAL->AdvancedSearch->save(); // makinaVitiRegAL
            $this->makinaTarga->AdvancedSearch->save(); // makinaTarga
            $this->makinaNrShasie->AdvancedSearch->save(); // makinaNrShasie
            $this->makinaPrejardhja->AdvancedSearch->save(); // makinaPrejardhja
            $this->makinaShiturVOLAL->AdvancedSearch->save(); // makinaShiturVOLAL
            $this->makinaAutori->AdvancedSearch->save(); // makinaAutori
            $this->makinaShtuar->AdvancedSearch->save(); // makinaShtuar
            $this->makinaModifikuar->AdvancedSearch->save(); // makinaModifikuar
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
        $searchFlds[] = &$this->makinaMarka;
        $searchFlds[] = &$this->makinaModeli;
        $searchFlds[] = &$this->makinaMadhesiaMotorrit;
        $searchFlds[] = &$this->makinaNgjyra;
        $searchFlds[] = &$this->makinaInfoShtese;
        $searchFlds[] = &$this->makinaTarga;
        $searchFlds[] = &$this->makinaNrShasie;
        $searchFlds[] = &$this->makinaPrejardhja;
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
        if ($this->makinaID->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaKlienti->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaMarka->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaModeli->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaKarburanti->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaMadhesiaMotorrit->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaVitiProdhimit->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaNgjyra->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaInfoShtese->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaVitiRegAL->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaTarga->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaNrShasie->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaPrejardhja->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaShiturVOLAL->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaAutori->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaShtuar->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->makinaModifikuar->AdvancedSearch->issetSession()) {
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
        $this->makinaID->AdvancedSearch->unsetSession();
        $this->makinaKlienti->AdvancedSearch->unsetSession();
        $this->makinaMarka->AdvancedSearch->unsetSession();
        $this->makinaModeli->AdvancedSearch->unsetSession();
        $this->makinaKarburanti->AdvancedSearch->unsetSession();
        $this->makinaMadhesiaMotorrit->AdvancedSearch->unsetSession();
        $this->makinaVitiProdhimit->AdvancedSearch->unsetSession();
        $this->makinaNgjyra->AdvancedSearch->unsetSession();
        $this->makinaInfoShtese->AdvancedSearch->unsetSession();
        $this->makinaVitiRegAL->AdvancedSearch->unsetSession();
        $this->makinaTarga->AdvancedSearch->unsetSession();
        $this->makinaNrShasie->AdvancedSearch->unsetSession();
        $this->makinaPrejardhja->AdvancedSearch->unsetSession();
        $this->makinaShiturVOLAL->AdvancedSearch->unsetSession();
        $this->makinaAutori->AdvancedSearch->unsetSession();
        $this->makinaShtuar->AdvancedSearch->unsetSession();
        $this->makinaModifikuar->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
        $this->makinaID->AdvancedSearch->load();
        $this->makinaKlienti->AdvancedSearch->load();
        $this->makinaMarka->AdvancedSearch->load();
        $this->makinaModeli->AdvancedSearch->load();
        $this->makinaKarburanti->AdvancedSearch->load();
        $this->makinaMadhesiaMotorrit->AdvancedSearch->load();
        $this->makinaVitiProdhimit->AdvancedSearch->load();
        $this->makinaNgjyra->AdvancedSearch->load();
        $this->makinaInfoShtese->AdvancedSearch->load();
        $this->makinaVitiRegAL->AdvancedSearch->load();
        $this->makinaTarga->AdvancedSearch->load();
        $this->makinaNrShasie->AdvancedSearch->load();
        $this->makinaPrejardhja->AdvancedSearch->load();
        $this->makinaShiturVOLAL->AdvancedSearch->load();
        $this->makinaAutori->AdvancedSearch->load();
        $this->makinaShtuar->AdvancedSearch->load();
        $this->makinaModifikuar->AdvancedSearch->load();
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
            $this->updateSort($this->makinaID, $ctrl); // makinaID
            $this->updateSort($this->makinaKlienti, $ctrl); // makinaKlienti
            $this->updateSort($this->makinaMarka, $ctrl); // makinaMarka
            $this->updateSort($this->makinaModeli, $ctrl); // makinaModeli
            $this->updateSort($this->makinaKarburanti, $ctrl); // makinaKarburanti
            $this->updateSort($this->makinaMadhesiaMotorrit, $ctrl); // makinaMadhesiaMotorrit
            $this->updateSort($this->makinaVitiProdhimit, $ctrl); // makinaVitiProdhimit
            $this->updateSort($this->makinaTarga, $ctrl); // makinaTarga
            $this->updateSort($this->makinaShiturVOLAL, $ctrl); // makinaShiturVOLAL
            $this->updateSort($this->makinaAutori, $ctrl); // makinaAutori
            $this->updateSort($this->makinaShtuar, $ctrl); // makinaShtuar
            $this->updateSort($this->makinaModifikuar, $ctrl); // makinaModifikuar
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
                $this->setSessionOrderByList($orderBy);
                $this->makinaID->setSort("");
                $this->makinaKlienti->setSort("");
                $this->makinaMarka->setSort("");
                $this->makinaModeli->setSort("");
                $this->makinaKarburanti->setSort("");
                $this->makinaMadhesiaMotorrit->setSort("");
                $this->makinaVitiProdhimit->setSort("");
                $this->makinaNgjyra->setSort("");
                $this->makinaInfoShtese->setSort("");
                $this->makinaVitiRegAL->setSort("");
                $this->makinaTarga->setSort("");
                $this->makinaNrShasie->setSort("");
                $this->makinaPrejardhja->setSort("");
                $this->makinaShiturVOLAL->setSort("");
                $this->makinaAutori->setSort("");
                $this->makinaShtuar->setSort("");
                $this->makinaModifikuar->setSort("");
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
                $opt->Body = "<a class=\"ew-row-link ew-delete\" data-ew-action=\"\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("DeleteLink") . "</a>";
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
                    $link = "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fmakinalist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . $listaction->Caption . "</button></li>";
                    if ($link != "") {
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fmakinalist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . $listaction->Caption . "</button>";
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
        $opt->Body = "<div class=\"form-check\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"form-check-input ew-multi-select\" value=\"" . HtmlEncode($this->makinaID->CurrentValue) . "\" data-ew-action=\"select-key\"></div>";
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
            $option->add("makinaID", $this->createColumnOption("makinaID"));
            $option->add("makinaKlienti", $this->createColumnOption("makinaKlienti"));
            $option->add("makinaMarka", $this->createColumnOption("makinaMarka"));
            $option->add("makinaModeli", $this->createColumnOption("makinaModeli"));
            $option->add("makinaKarburanti", $this->createColumnOption("makinaKarburanti"));
            $option->add("makinaMadhesiaMotorrit", $this->createColumnOption("makinaMadhesiaMotorrit"));
            $option->add("makinaVitiProdhimit", $this->createColumnOption("makinaVitiProdhimit"));
            $option->add("makinaTarga", $this->createColumnOption("makinaTarga"));
            $option->add("makinaShiturVOLAL", $this->createColumnOption("makinaShiturVOLAL"));
            $option->add("makinaAutori", $this->createColumnOption("makinaAutori"));
            $option->add("makinaShtuar", $this->createColumnOption("makinaShtuar"));
            $option->add("makinaModifikuar", $this->createColumnOption("makinaModifikuar"));
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fmakinasrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fmakinasrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="fmakinalist"' . $listaction->toDataAttrs() . '>' . $icon . '</button>';
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

        // makinaID
        if ($this->makinaID->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaID->AdvancedSearch->SearchValue != "" || $this->makinaID->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaKlienti
        if ($this->makinaKlienti->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaKlienti->AdvancedSearch->SearchValue != "" || $this->makinaKlienti->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaMarka
        if ($this->makinaMarka->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaMarka->AdvancedSearch->SearchValue != "" || $this->makinaMarka->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaModeli
        if ($this->makinaModeli->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaModeli->AdvancedSearch->SearchValue != "" || $this->makinaModeli->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaKarburanti
        if ($this->makinaKarburanti->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaKarburanti->AdvancedSearch->SearchValue != "" || $this->makinaKarburanti->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaMadhesiaMotorrit
        if ($this->makinaMadhesiaMotorrit->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaMadhesiaMotorrit->AdvancedSearch->SearchValue != "" || $this->makinaMadhesiaMotorrit->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaVitiProdhimit
        if ($this->makinaVitiProdhimit->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaVitiProdhimit->AdvancedSearch->SearchValue != "" || $this->makinaVitiProdhimit->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaNgjyra
        if ($this->makinaNgjyra->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaNgjyra->AdvancedSearch->SearchValue != "" || $this->makinaNgjyra->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaInfoShtese
        if ($this->makinaInfoShtese->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaInfoShtese->AdvancedSearch->SearchValue != "" || $this->makinaInfoShtese->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaVitiRegAL
        if ($this->makinaVitiRegAL->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaVitiRegAL->AdvancedSearch->SearchValue != "" || $this->makinaVitiRegAL->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaTarga
        if ($this->makinaTarga->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaTarga->AdvancedSearch->SearchValue != "" || $this->makinaTarga->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaNrShasie
        if ($this->makinaNrShasie->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaNrShasie->AdvancedSearch->SearchValue != "" || $this->makinaNrShasie->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaPrejardhja
        if ($this->makinaPrejardhja->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaPrejardhja->AdvancedSearch->SearchValue != "" || $this->makinaPrejardhja->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaShiturVOLAL
        if ($this->makinaShiturVOLAL->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaShiturVOLAL->AdvancedSearch->SearchValue != "" || $this->makinaShiturVOLAL->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaAutori
        if ($this->makinaAutori->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaAutori->AdvancedSearch->SearchValue != "" || $this->makinaAutori->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaShtuar
        if ($this->makinaShtuar->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaShtuar->AdvancedSearch->SearchValue != "" || $this->makinaShtuar->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // makinaModifikuar
        if ($this->makinaModifikuar->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->makinaModifikuar->AdvancedSearch->SearchValue != "" || $this->makinaModifikuar->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // makinaID

        // makinaKlienti

        // makinaMarka

        // makinaModeli

        // makinaKarburanti

        // makinaMadhesiaMotorrit

        // makinaVitiProdhimit

        // makinaNgjyra

        // makinaInfoShtese

        // makinaVitiRegAL

        // makinaTarga

        // makinaNrShasie

        // makinaPrejardhja

        // makinaShiturVOLAL

        // makinaAutori

        // makinaShtuar

        // makinaModifikuar

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
            $this->makinaID->TooltipValue = "";

            // makinaKlienti
            $this->makinaKlienti->LinkCustomAttributes = "";
            $this->makinaKlienti->HrefValue = "";
            $this->makinaKlienti->TooltipValue = "";

            // makinaMarka
            $this->makinaMarka->LinkCustomAttributes = "";
            $this->makinaMarka->HrefValue = "";
            $this->makinaMarka->TooltipValue = "";

            // makinaModeli
            $this->makinaModeli->LinkCustomAttributes = "";
            $this->makinaModeli->HrefValue = "";
            $this->makinaModeli->TooltipValue = "";

            // makinaKarburanti
            $this->makinaKarburanti->LinkCustomAttributes = "";
            $this->makinaKarburanti->HrefValue = "";
            $this->makinaKarburanti->TooltipValue = "";

            // makinaMadhesiaMotorrit
            $this->makinaMadhesiaMotorrit->LinkCustomAttributes = "";
            $this->makinaMadhesiaMotorrit->HrefValue = "";
            $this->makinaMadhesiaMotorrit->TooltipValue = "";

            // makinaVitiProdhimit
            $this->makinaVitiProdhimit->LinkCustomAttributes = "";
            $this->makinaVitiProdhimit->HrefValue = "";
            $this->makinaVitiProdhimit->TooltipValue = "";

            // makinaTarga
            $this->makinaTarga->LinkCustomAttributes = "";
            $this->makinaTarga->HrefValue = "";
            $this->makinaTarga->TooltipValue = "";

            // makinaShiturVOLAL
            $this->makinaShiturVOLAL->LinkCustomAttributes = "";
            $this->makinaShiturVOLAL->HrefValue = "";
            $this->makinaShiturVOLAL->TooltipValue = "";

            // makinaAutori
            $this->makinaAutori->LinkCustomAttributes = "";
            $this->makinaAutori->HrefValue = "";
            $this->makinaAutori->TooltipValue = "";

            // makinaShtuar
            $this->makinaShtuar->LinkCustomAttributes = "";
            $this->makinaShtuar->HrefValue = "";
            $this->makinaShtuar->TooltipValue = "";

            // makinaModifikuar
            $this->makinaModifikuar->LinkCustomAttributes = "";
            $this->makinaModifikuar->HrefValue = "";
            $this->makinaModifikuar->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // makinaID
            $this->makinaID->setupEditAttributes();
            $this->makinaID->EditCustomAttributes = "";
            $this->makinaID->EditValue = HtmlEncode($this->makinaID->AdvancedSearch->SearchValue);
            $this->makinaID->PlaceHolder = RemoveHtml($this->makinaID->caption());

            // makinaKlienti
            $this->makinaKlienti->setupEditAttributes();
            $this->makinaKlienti->EditCustomAttributes = "";
            $this->makinaKlienti->EditValue = HtmlEncode($this->makinaKlienti->AdvancedSearch->SearchValue);
            $this->makinaKlienti->PlaceHolder = RemoveHtml($this->makinaKlienti->caption());
            $this->makinaKlienti->setupEditAttributes();
            $this->makinaKlienti->EditCustomAttributes = "";
            $this->makinaKlienti->EditValue2 = HtmlEncode($this->makinaKlienti->AdvancedSearch->SearchValue2);
            $this->makinaKlienti->PlaceHolder = RemoveHtml($this->makinaKlienti->caption());

            // makinaMarka
            $this->makinaMarka->setupEditAttributes();
            $this->makinaMarka->EditCustomAttributes = "";
            if (!$this->makinaMarka->Raw) {
                $this->makinaMarka->AdvancedSearch->SearchValue = HtmlDecode($this->makinaMarka->AdvancedSearch->SearchValue);
            }
            $this->makinaMarka->EditValue = HtmlEncode($this->makinaMarka->AdvancedSearch->SearchValue);
            $this->makinaMarka->PlaceHolder = RemoveHtml($this->makinaMarka->caption());

            // makinaModeli
            $this->makinaModeli->setupEditAttributes();
            $this->makinaModeli->EditCustomAttributes = "";
            if (!$this->makinaModeli->Raw) {
                $this->makinaModeli->AdvancedSearch->SearchValue = HtmlDecode($this->makinaModeli->AdvancedSearch->SearchValue);
            }
            $this->makinaModeli->EditValue = HtmlEncode($this->makinaModeli->AdvancedSearch->SearchValue);
            $this->makinaModeli->PlaceHolder = RemoveHtml($this->makinaModeli->caption());
            $this->makinaModeli->setupEditAttributes();
            $this->makinaModeli->EditCustomAttributes = "";
            if (!$this->makinaModeli->Raw) {
                $this->makinaModeli->AdvancedSearch->SearchValue2 = HtmlDecode($this->makinaModeli->AdvancedSearch->SearchValue2);
            }
            $this->makinaModeli->EditValue2 = HtmlEncode($this->makinaModeli->AdvancedSearch->SearchValue2);
            $this->makinaModeli->PlaceHolder = RemoveHtml($this->makinaModeli->caption());

            // makinaKarburanti
            $this->makinaKarburanti->EditCustomAttributes = "";
            $this->makinaKarburanti->EditValue = $this->makinaKarburanti->options(false);
            $this->makinaKarburanti->PlaceHolder = RemoveHtml($this->makinaKarburanti->caption());

            // makinaMadhesiaMotorrit
            $this->makinaMadhesiaMotorrit->setupEditAttributes();
            $this->makinaMadhesiaMotorrit->EditCustomAttributes = "";
            if (!$this->makinaMadhesiaMotorrit->Raw) {
                $this->makinaMadhesiaMotorrit->AdvancedSearch->SearchValue = HtmlDecode($this->makinaMadhesiaMotorrit->AdvancedSearch->SearchValue);
            }
            $this->makinaMadhesiaMotorrit->EditValue = HtmlEncode($this->makinaMadhesiaMotorrit->AdvancedSearch->SearchValue);
            $this->makinaMadhesiaMotorrit->PlaceHolder = RemoveHtml($this->makinaMadhesiaMotorrit->caption());

            // makinaVitiProdhimit
            $this->makinaVitiProdhimit->setupEditAttributes();
            $this->makinaVitiProdhimit->EditCustomAttributes = "";
            $this->makinaVitiProdhimit->EditValue = HtmlEncode($this->makinaVitiProdhimit->AdvancedSearch->SearchValue);
            $this->makinaVitiProdhimit->PlaceHolder = RemoveHtml($this->makinaVitiProdhimit->caption());
            $this->makinaVitiProdhimit->setupEditAttributes();
            $this->makinaVitiProdhimit->EditCustomAttributes = "";
            $this->makinaVitiProdhimit->EditValue2 = HtmlEncode($this->makinaVitiProdhimit->AdvancedSearch->SearchValue2);
            $this->makinaVitiProdhimit->PlaceHolder = RemoveHtml($this->makinaVitiProdhimit->caption());

            // makinaTarga
            $this->makinaTarga->setupEditAttributes();
            $this->makinaTarga->EditCustomAttributes = "";
            if (!$this->makinaTarga->Raw) {
                $this->makinaTarga->AdvancedSearch->SearchValue = HtmlDecode($this->makinaTarga->AdvancedSearch->SearchValue);
            }
            $this->makinaTarga->EditValue = HtmlEncode($this->makinaTarga->AdvancedSearch->SearchValue);
            $this->makinaTarga->PlaceHolder = RemoveHtml($this->makinaTarga->caption());

            // makinaShiturVOLAL
            $this->makinaShiturVOLAL->EditCustomAttributes = "";
            $this->makinaShiturVOLAL->EditValue = $this->makinaShiturVOLAL->options(false);
            $this->makinaShiturVOLAL->PlaceHolder = RemoveHtml($this->makinaShiturVOLAL->caption());

            // makinaAutori
            $this->makinaAutori->setupEditAttributes();
            $this->makinaAutori->EditCustomAttributes = "";
            $this->makinaAutori->EditValue = HtmlEncode($this->makinaAutori->AdvancedSearch->SearchValue);
            $this->makinaAutori->PlaceHolder = RemoveHtml($this->makinaAutori->caption());

            // makinaShtuar
            $this->makinaShtuar->setupEditAttributes();
            $this->makinaShtuar->EditCustomAttributes = "";
            $this->makinaShtuar->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->makinaShtuar->AdvancedSearch->SearchValue, $this->makinaShtuar->formatPattern()), $this->makinaShtuar->formatPattern()));
            $this->makinaShtuar->PlaceHolder = RemoveHtml($this->makinaShtuar->caption());

            // makinaModifikuar
            $this->makinaModifikuar->setupEditAttributes();
            $this->makinaModifikuar->EditCustomAttributes = "";
            $this->makinaModifikuar->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->makinaModifikuar->AdvancedSearch->SearchValue, $this->makinaModifikuar->formatPattern()), $this->makinaModifikuar->formatPattern()));
            $this->makinaModifikuar->PlaceHolder = RemoveHtml($this->makinaModifikuar->caption());
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
        if (!CheckInteger($this->makinaVitiProdhimit->AdvancedSearch->SearchValue)) {
            $this->makinaVitiProdhimit->addErrorMessage($this->makinaVitiProdhimit->getErrorMessage(false));
        }
        if (!CheckInteger($this->makinaVitiProdhimit->AdvancedSearch->SearchValue2)) {
            $this->makinaVitiProdhimit->addErrorMessage($this->makinaVitiProdhimit->getErrorMessage(false));
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
        $this->makinaID->AdvancedSearch->load();
        $this->makinaKlienti->AdvancedSearch->load();
        $this->makinaMarka->AdvancedSearch->load();
        $this->makinaModeli->AdvancedSearch->load();
        $this->makinaKarburanti->AdvancedSearch->load();
        $this->makinaMadhesiaMotorrit->AdvancedSearch->load();
        $this->makinaVitiProdhimit->AdvancedSearch->load();
        $this->makinaNgjyra->AdvancedSearch->load();
        $this->makinaInfoShtese->AdvancedSearch->load();
        $this->makinaVitiRegAL->AdvancedSearch->load();
        $this->makinaTarga->AdvancedSearch->load();
        $this->makinaNrShasie->AdvancedSearch->load();
        $this->makinaPrejardhja->AdvancedSearch->load();
        $this->makinaShiturVOLAL->AdvancedSearch->load();
        $this->makinaAutori->AdvancedSearch->load();
        $this->makinaShtuar->AdvancedSearch->load();
        $this->makinaModifikuar->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        $exportUrl = GetUrl($pageUrl . "export=" . $type . ($custom ? "&amp;custom=1" : ""));
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" form=\"fmakinalist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"excel\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToExcel") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" form=\"fmakinalist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"word\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToWord") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdfText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdfText")) . "\" form=\"fmakinalist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"pdf\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToPdf") . "</button>";
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
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . $Language->phrase("ExportToEmailText") . '" data-caption="' . $Language->phrase("ExportToEmailText") . '" form="fmakinalist" data-ew-action="email" data-hdr="' . $Language->phrase("ExportToEmailText") . '" data-sel="false"' . $url . '>' . $Language->phrase("ExportToEmail") . '</button>';
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"fmakinasrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
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
