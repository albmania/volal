<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaImportiList extends MakinaImporti
{
    use MessagesTrait;

    // Page ID
    public $PageID = "list";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina_importi';

    // Page object name
    public $PageObjName = "MakinaImportiList";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fmakina_importilist";
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

        // Table object (makina_importi)
        if (!isset($GLOBALS["makina_importi"]) || get_class($GLOBALS["makina_importi"]) == PROJECT_NAMESPACE . "makina_importi") {
            $GLOBALS["makina_importi"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl(false);

        // Initialize URLs
        $this->AddUrl = "MakinaImportiAdd?" . Config("TABLE_SHOW_DETAIL") . "=";
        $this->InlineAddUrl = $pageUrl . "action=add";
        $this->GridAddUrl = $pageUrl . "action=gridadd";
        $this->GridEditUrl = $pageUrl . "action=gridedit";
        $this->MultiDeleteUrl = "MakinaImportiDelete";
        $this->MultiUpdateUrl = "MakinaImportiUpdate";

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
        $this->mimpID->setVisibility();
        $this->mimpMarka->setVisibility();
        $this->mimpModeli->setVisibility();
        $this->mimpTipi->setVisibility();
        $this->mimpShasia->setVisibility();
        $this->mimpViti->setVisibility();
        $this->mimpKarburant->setVisibility();
        $this->mimpKambio->Visible = false;
        $this->mimpNgjyra->Visible = false;
        $this->mimpPrejardhja->setVisibility();
        $this->mimpInfo->Visible = false;
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

        // Setup other options
        $this->setupOtherOptions();

        // Set up custom action (compatible with old version)
        foreach ($this->CustomActions as $name => $action) {
            $this->ListActions->add($name, $action);
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->mimpMarka);
        $this->setupLookupOptions($this->mimpModeli);
        $this->setupLookupOptions($this->mimpTipi);
        $this->setupLookupOptions($this->mimpKarburant);
        $this->setupLookupOptions($this->mimpKambio);
        $this->setupLookupOptions($this->mimpGati);

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
            $savedFilterList = $UserProfile->getSearchFilters(CurrentUserName(), "fmakina_importisrch");
        }
        $filterList = Concat($filterList, $this->mimpID->AdvancedSearch->toJson(), ","); // Field mimpID
        $filterList = Concat($filterList, $this->mimpMarka->AdvancedSearch->toJson(), ","); // Field mimpMarka
        $filterList = Concat($filterList, $this->mimpModeli->AdvancedSearch->toJson(), ","); // Field mimpModeli
        $filterList = Concat($filterList, $this->mimpTipi->AdvancedSearch->toJson(), ","); // Field mimpTipi
        $filterList = Concat($filterList, $this->mimpShasia->AdvancedSearch->toJson(), ","); // Field mimpShasia
        $filterList = Concat($filterList, $this->mimpViti->AdvancedSearch->toJson(), ","); // Field mimpViti
        $filterList = Concat($filterList, $this->mimpKarburant->AdvancedSearch->toJson(), ","); // Field mimpKarburant
        $filterList = Concat($filterList, $this->mimpKambio->AdvancedSearch->toJson(), ","); // Field mimpKambio
        $filterList = Concat($filterList, $this->mimpNgjyra->AdvancedSearch->toJson(), ","); // Field mimpNgjyra
        $filterList = Concat($filterList, $this->mimpPrejardhja->AdvancedSearch->toJson(), ","); // Field mimpPrejardhja
        $filterList = Concat($filterList, $this->mimpInfo->AdvancedSearch->toJson(), ","); // Field mimpInfo
        $filterList = Concat($filterList, $this->mimpCmimiBlerjes->AdvancedSearch->toJson(), ","); // Field mimpCmimiBlerjes
        $filterList = Concat($filterList, $this->mimpDogana->AdvancedSearch->toJson(), ","); // Field mimpDogana
        $filterList = Concat($filterList, $this->mimpTransporti->AdvancedSearch->toJson(), ","); // Field mimpTransporti
        $filterList = Concat($filterList, $this->mimpTjera->AdvancedSearch->toJson(), ","); // Field mimpTjera
        $filterList = Concat($filterList, $this->mimpDtHyrjes->AdvancedSearch->toJson(), ","); // Field mimpDtHyrjes
        $filterList = Concat($filterList, $this->mimpCmimiShitjes->AdvancedSearch->toJson(), ","); // Field mimpCmimiShitjes
        $filterList = Concat($filterList, $this->mimpGati->AdvancedSearch->toJson(), ","); // Field mimpGati
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
            $UserProfile->setSearchFilters(CurrentUserName(), "fmakina_importisrch", $filters);
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

        // Field mimpID
        $this->mimpID->AdvancedSearch->SearchValue = @$filter["x_mimpID"];
        $this->mimpID->AdvancedSearch->SearchOperator = @$filter["z_mimpID"];
        $this->mimpID->AdvancedSearch->SearchCondition = @$filter["v_mimpID"];
        $this->mimpID->AdvancedSearch->SearchValue2 = @$filter["y_mimpID"];
        $this->mimpID->AdvancedSearch->SearchOperator2 = @$filter["w_mimpID"];
        $this->mimpID->AdvancedSearch->save();

        // Field mimpMarka
        $this->mimpMarka->AdvancedSearch->SearchValue = @$filter["x_mimpMarka"];
        $this->mimpMarka->AdvancedSearch->SearchOperator = @$filter["z_mimpMarka"];
        $this->mimpMarka->AdvancedSearch->SearchCondition = @$filter["v_mimpMarka"];
        $this->mimpMarka->AdvancedSearch->SearchValue2 = @$filter["y_mimpMarka"];
        $this->mimpMarka->AdvancedSearch->SearchOperator2 = @$filter["w_mimpMarka"];
        $this->mimpMarka->AdvancedSearch->save();

        // Field mimpModeli
        $this->mimpModeli->AdvancedSearch->SearchValue = @$filter["x_mimpModeli"];
        $this->mimpModeli->AdvancedSearch->SearchOperator = @$filter["z_mimpModeli"];
        $this->mimpModeli->AdvancedSearch->SearchCondition = @$filter["v_mimpModeli"];
        $this->mimpModeli->AdvancedSearch->SearchValue2 = @$filter["y_mimpModeli"];
        $this->mimpModeli->AdvancedSearch->SearchOperator2 = @$filter["w_mimpModeli"];
        $this->mimpModeli->AdvancedSearch->save();

        // Field mimpTipi
        $this->mimpTipi->AdvancedSearch->SearchValue = @$filter["x_mimpTipi"];
        $this->mimpTipi->AdvancedSearch->SearchOperator = @$filter["z_mimpTipi"];
        $this->mimpTipi->AdvancedSearch->SearchCondition = @$filter["v_mimpTipi"];
        $this->mimpTipi->AdvancedSearch->SearchValue2 = @$filter["y_mimpTipi"];
        $this->mimpTipi->AdvancedSearch->SearchOperator2 = @$filter["w_mimpTipi"];
        $this->mimpTipi->AdvancedSearch->save();

        // Field mimpShasia
        $this->mimpShasia->AdvancedSearch->SearchValue = @$filter["x_mimpShasia"];
        $this->mimpShasia->AdvancedSearch->SearchOperator = @$filter["z_mimpShasia"];
        $this->mimpShasia->AdvancedSearch->SearchCondition = @$filter["v_mimpShasia"];
        $this->mimpShasia->AdvancedSearch->SearchValue2 = @$filter["y_mimpShasia"];
        $this->mimpShasia->AdvancedSearch->SearchOperator2 = @$filter["w_mimpShasia"];
        $this->mimpShasia->AdvancedSearch->save();

        // Field mimpViti
        $this->mimpViti->AdvancedSearch->SearchValue = @$filter["x_mimpViti"];
        $this->mimpViti->AdvancedSearch->SearchOperator = @$filter["z_mimpViti"];
        $this->mimpViti->AdvancedSearch->SearchCondition = @$filter["v_mimpViti"];
        $this->mimpViti->AdvancedSearch->SearchValue2 = @$filter["y_mimpViti"];
        $this->mimpViti->AdvancedSearch->SearchOperator2 = @$filter["w_mimpViti"];
        $this->mimpViti->AdvancedSearch->save();

        // Field mimpKarburant
        $this->mimpKarburant->AdvancedSearch->SearchValue = @$filter["x_mimpKarburant"];
        $this->mimpKarburant->AdvancedSearch->SearchOperator = @$filter["z_mimpKarburant"];
        $this->mimpKarburant->AdvancedSearch->SearchCondition = @$filter["v_mimpKarburant"];
        $this->mimpKarburant->AdvancedSearch->SearchValue2 = @$filter["y_mimpKarburant"];
        $this->mimpKarburant->AdvancedSearch->SearchOperator2 = @$filter["w_mimpKarburant"];
        $this->mimpKarburant->AdvancedSearch->save();

        // Field mimpKambio
        $this->mimpKambio->AdvancedSearch->SearchValue = @$filter["x_mimpKambio"];
        $this->mimpKambio->AdvancedSearch->SearchOperator = @$filter["z_mimpKambio"];
        $this->mimpKambio->AdvancedSearch->SearchCondition = @$filter["v_mimpKambio"];
        $this->mimpKambio->AdvancedSearch->SearchValue2 = @$filter["y_mimpKambio"];
        $this->mimpKambio->AdvancedSearch->SearchOperator2 = @$filter["w_mimpKambio"];
        $this->mimpKambio->AdvancedSearch->save();

        // Field mimpNgjyra
        $this->mimpNgjyra->AdvancedSearch->SearchValue = @$filter["x_mimpNgjyra"];
        $this->mimpNgjyra->AdvancedSearch->SearchOperator = @$filter["z_mimpNgjyra"];
        $this->mimpNgjyra->AdvancedSearch->SearchCondition = @$filter["v_mimpNgjyra"];
        $this->mimpNgjyra->AdvancedSearch->SearchValue2 = @$filter["y_mimpNgjyra"];
        $this->mimpNgjyra->AdvancedSearch->SearchOperator2 = @$filter["w_mimpNgjyra"];
        $this->mimpNgjyra->AdvancedSearch->save();

        // Field mimpPrejardhja
        $this->mimpPrejardhja->AdvancedSearch->SearchValue = @$filter["x_mimpPrejardhja"];
        $this->mimpPrejardhja->AdvancedSearch->SearchOperator = @$filter["z_mimpPrejardhja"];
        $this->mimpPrejardhja->AdvancedSearch->SearchCondition = @$filter["v_mimpPrejardhja"];
        $this->mimpPrejardhja->AdvancedSearch->SearchValue2 = @$filter["y_mimpPrejardhja"];
        $this->mimpPrejardhja->AdvancedSearch->SearchOperator2 = @$filter["w_mimpPrejardhja"];
        $this->mimpPrejardhja->AdvancedSearch->save();

        // Field mimpInfo
        $this->mimpInfo->AdvancedSearch->SearchValue = @$filter["x_mimpInfo"];
        $this->mimpInfo->AdvancedSearch->SearchOperator = @$filter["z_mimpInfo"];
        $this->mimpInfo->AdvancedSearch->SearchCondition = @$filter["v_mimpInfo"];
        $this->mimpInfo->AdvancedSearch->SearchValue2 = @$filter["y_mimpInfo"];
        $this->mimpInfo->AdvancedSearch->SearchOperator2 = @$filter["w_mimpInfo"];
        $this->mimpInfo->AdvancedSearch->save();

        // Field mimpCmimiBlerjes
        $this->mimpCmimiBlerjes->AdvancedSearch->SearchValue = @$filter["x_mimpCmimiBlerjes"];
        $this->mimpCmimiBlerjes->AdvancedSearch->SearchOperator = @$filter["z_mimpCmimiBlerjes"];
        $this->mimpCmimiBlerjes->AdvancedSearch->SearchCondition = @$filter["v_mimpCmimiBlerjes"];
        $this->mimpCmimiBlerjes->AdvancedSearch->SearchValue2 = @$filter["y_mimpCmimiBlerjes"];
        $this->mimpCmimiBlerjes->AdvancedSearch->SearchOperator2 = @$filter["w_mimpCmimiBlerjes"];
        $this->mimpCmimiBlerjes->AdvancedSearch->save();

        // Field mimpDogana
        $this->mimpDogana->AdvancedSearch->SearchValue = @$filter["x_mimpDogana"];
        $this->mimpDogana->AdvancedSearch->SearchOperator = @$filter["z_mimpDogana"];
        $this->mimpDogana->AdvancedSearch->SearchCondition = @$filter["v_mimpDogana"];
        $this->mimpDogana->AdvancedSearch->SearchValue2 = @$filter["y_mimpDogana"];
        $this->mimpDogana->AdvancedSearch->SearchOperator2 = @$filter["w_mimpDogana"];
        $this->mimpDogana->AdvancedSearch->save();

        // Field mimpTransporti
        $this->mimpTransporti->AdvancedSearch->SearchValue = @$filter["x_mimpTransporti"];
        $this->mimpTransporti->AdvancedSearch->SearchOperator = @$filter["z_mimpTransporti"];
        $this->mimpTransporti->AdvancedSearch->SearchCondition = @$filter["v_mimpTransporti"];
        $this->mimpTransporti->AdvancedSearch->SearchValue2 = @$filter["y_mimpTransporti"];
        $this->mimpTransporti->AdvancedSearch->SearchOperator2 = @$filter["w_mimpTransporti"];
        $this->mimpTransporti->AdvancedSearch->save();

        // Field mimpTjera
        $this->mimpTjera->AdvancedSearch->SearchValue = @$filter["x_mimpTjera"];
        $this->mimpTjera->AdvancedSearch->SearchOperator = @$filter["z_mimpTjera"];
        $this->mimpTjera->AdvancedSearch->SearchCondition = @$filter["v_mimpTjera"];
        $this->mimpTjera->AdvancedSearch->SearchValue2 = @$filter["y_mimpTjera"];
        $this->mimpTjera->AdvancedSearch->SearchOperator2 = @$filter["w_mimpTjera"];
        $this->mimpTjera->AdvancedSearch->save();

        // Field mimpDtHyrjes
        $this->mimpDtHyrjes->AdvancedSearch->SearchValue = @$filter["x_mimpDtHyrjes"];
        $this->mimpDtHyrjes->AdvancedSearch->SearchOperator = @$filter["z_mimpDtHyrjes"];
        $this->mimpDtHyrjes->AdvancedSearch->SearchCondition = @$filter["v_mimpDtHyrjes"];
        $this->mimpDtHyrjes->AdvancedSearch->SearchValue2 = @$filter["y_mimpDtHyrjes"];
        $this->mimpDtHyrjes->AdvancedSearch->SearchOperator2 = @$filter["w_mimpDtHyrjes"];
        $this->mimpDtHyrjes->AdvancedSearch->save();

        // Field mimpCmimiShitjes
        $this->mimpCmimiShitjes->AdvancedSearch->SearchValue = @$filter["x_mimpCmimiShitjes"];
        $this->mimpCmimiShitjes->AdvancedSearch->SearchOperator = @$filter["z_mimpCmimiShitjes"];
        $this->mimpCmimiShitjes->AdvancedSearch->SearchCondition = @$filter["v_mimpCmimiShitjes"];
        $this->mimpCmimiShitjes->AdvancedSearch->SearchValue2 = @$filter["y_mimpCmimiShitjes"];
        $this->mimpCmimiShitjes->AdvancedSearch->SearchOperator2 = @$filter["w_mimpCmimiShitjes"];
        $this->mimpCmimiShitjes->AdvancedSearch->save();

        // Field mimpGati
        $this->mimpGati->AdvancedSearch->SearchValue = @$filter["x_mimpGati"];
        $this->mimpGati->AdvancedSearch->SearchOperator = @$filter["z_mimpGati"];
        $this->mimpGati->AdvancedSearch->SearchCondition = @$filter["v_mimpGati"];
        $this->mimpGati->AdvancedSearch->SearchValue2 = @$filter["y_mimpGati"];
        $this->mimpGati->AdvancedSearch->SearchOperator2 = @$filter["w_mimpGati"];
        $this->mimpGati->AdvancedSearch->save();
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
        $this->buildSearchSql($where, $this->mimpID, $default, false); // mimpID
        $this->buildSearchSql($where, $this->mimpMarka, $default, false); // mimpMarka
        $this->buildSearchSql($where, $this->mimpModeli, $default, false); // mimpModeli
        $this->buildSearchSql($where, $this->mimpTipi, $default, false); // mimpTipi
        $this->buildSearchSql($where, $this->mimpShasia, $default, false); // mimpShasia
        $this->buildSearchSql($where, $this->mimpViti, $default, false); // mimpViti
        $this->buildSearchSql($where, $this->mimpKarburant, $default, false); // mimpKarburant
        $this->buildSearchSql($where, $this->mimpKambio, $default, false); // mimpKambio
        $this->buildSearchSql($where, $this->mimpNgjyra, $default, false); // mimpNgjyra
        $this->buildSearchSql($where, $this->mimpPrejardhja, $default, false); // mimpPrejardhja
        $this->buildSearchSql($where, $this->mimpInfo, $default, false); // mimpInfo
        $this->buildSearchSql($where, $this->mimpCmimiBlerjes, $default, false); // mimpCmimiBlerjes
        $this->buildSearchSql($where, $this->mimpDogana, $default, false); // mimpDogana
        $this->buildSearchSql($where, $this->mimpTransporti, $default, false); // mimpTransporti
        $this->buildSearchSql($where, $this->mimpTjera, $default, false); // mimpTjera
        $this->buildSearchSql($where, $this->mimpDtHyrjes, $default, false); // mimpDtHyrjes
        $this->buildSearchSql($where, $this->mimpCmimiShitjes, $default, false); // mimpCmimiShitjes
        $this->buildSearchSql($where, $this->mimpGati, $default, false); // mimpGati

        // Set up search parm
        if (!$default && $where != "" && in_array($this->Command, ["", "reset", "resetall"])) {
            $this->Command = "search";
        }
        if (!$default && $this->Command == "search") {
            $this->mimpID->AdvancedSearch->save(); // mimpID
            $this->mimpMarka->AdvancedSearch->save(); // mimpMarka
            $this->mimpModeli->AdvancedSearch->save(); // mimpModeli
            $this->mimpTipi->AdvancedSearch->save(); // mimpTipi
            $this->mimpShasia->AdvancedSearch->save(); // mimpShasia
            $this->mimpViti->AdvancedSearch->save(); // mimpViti
            $this->mimpKarburant->AdvancedSearch->save(); // mimpKarburant
            $this->mimpKambio->AdvancedSearch->save(); // mimpKambio
            $this->mimpNgjyra->AdvancedSearch->save(); // mimpNgjyra
            $this->mimpPrejardhja->AdvancedSearch->save(); // mimpPrejardhja
            $this->mimpInfo->AdvancedSearch->save(); // mimpInfo
            $this->mimpCmimiBlerjes->AdvancedSearch->save(); // mimpCmimiBlerjes
            $this->mimpDogana->AdvancedSearch->save(); // mimpDogana
            $this->mimpTransporti->AdvancedSearch->save(); // mimpTransporti
            $this->mimpTjera->AdvancedSearch->save(); // mimpTjera
            $this->mimpDtHyrjes->AdvancedSearch->save(); // mimpDtHyrjes
            $this->mimpCmimiShitjes->AdvancedSearch->save(); // mimpCmimiShitjes
            $this->mimpGati->AdvancedSearch->save(); // mimpGati
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
        $searchFlds[] = &$this->mimpShasia;
        $searchFlds[] = &$this->mimpNgjyra;
        $searchFlds[] = &$this->mimpPrejardhja;
        $searchFlds[] = &$this->mimpInfo;
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
        if ($this->mimpID->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpMarka->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpModeli->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpTipi->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpShasia->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpViti->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpKarburant->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpKambio->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpNgjyra->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpPrejardhja->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpInfo->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpCmimiBlerjes->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpDogana->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpTransporti->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpTjera->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpDtHyrjes->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpCmimiShitjes->AdvancedSearch->issetSession()) {
            return true;
        }
        if ($this->mimpGati->AdvancedSearch->issetSession()) {
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
        $this->mimpID->AdvancedSearch->unsetSession();
        $this->mimpMarka->AdvancedSearch->unsetSession();
        $this->mimpModeli->AdvancedSearch->unsetSession();
        $this->mimpTipi->AdvancedSearch->unsetSession();
        $this->mimpShasia->AdvancedSearch->unsetSession();
        $this->mimpViti->AdvancedSearch->unsetSession();
        $this->mimpKarburant->AdvancedSearch->unsetSession();
        $this->mimpKambio->AdvancedSearch->unsetSession();
        $this->mimpNgjyra->AdvancedSearch->unsetSession();
        $this->mimpPrejardhja->AdvancedSearch->unsetSession();
        $this->mimpInfo->AdvancedSearch->unsetSession();
        $this->mimpCmimiBlerjes->AdvancedSearch->unsetSession();
        $this->mimpDogana->AdvancedSearch->unsetSession();
        $this->mimpTransporti->AdvancedSearch->unsetSession();
        $this->mimpTjera->AdvancedSearch->unsetSession();
        $this->mimpDtHyrjes->AdvancedSearch->unsetSession();
        $this->mimpCmimiShitjes->AdvancedSearch->unsetSession();
        $this->mimpGati->AdvancedSearch->unsetSession();
    }

    // Restore all search parameters
    protected function restoreSearchParms()
    {
        $this->RestoreSearch = true;

        // Restore basic search values
        $this->BasicSearch->load();

        // Restore advanced search values
        $this->mimpID->AdvancedSearch->load();
        $this->mimpMarka->AdvancedSearch->load();
        $this->mimpModeli->AdvancedSearch->load();
        $this->mimpTipi->AdvancedSearch->load();
        $this->mimpShasia->AdvancedSearch->load();
        $this->mimpViti->AdvancedSearch->load();
        $this->mimpKarburant->AdvancedSearch->load();
        $this->mimpKambio->AdvancedSearch->load();
        $this->mimpNgjyra->AdvancedSearch->load();
        $this->mimpPrejardhja->AdvancedSearch->load();
        $this->mimpInfo->AdvancedSearch->load();
        $this->mimpCmimiBlerjes->AdvancedSearch->load();
        $this->mimpDogana->AdvancedSearch->load();
        $this->mimpTransporti->AdvancedSearch->load();
        $this->mimpTjera->AdvancedSearch->load();
        $this->mimpDtHyrjes->AdvancedSearch->load();
        $this->mimpCmimiShitjes->AdvancedSearch->load();
        $this->mimpGati->AdvancedSearch->load();
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
            $this->updateSort($this->mimpID, $ctrl); // mimpID
            $this->updateSort($this->mimpMarka, $ctrl); // mimpMarka
            $this->updateSort($this->mimpModeli, $ctrl); // mimpModeli
            $this->updateSort($this->mimpTipi, $ctrl); // mimpTipi
            $this->updateSort($this->mimpShasia, $ctrl); // mimpShasia
            $this->updateSort($this->mimpViti, $ctrl); // mimpViti
            $this->updateSort($this->mimpKarburant, $ctrl); // mimpKarburant
            $this->updateSort($this->mimpPrejardhja, $ctrl); // mimpPrejardhja
            $this->updateSort($this->mimpCmimiBlerjes, $ctrl); // mimpCmimiBlerjes
            $this->updateSort($this->mimpDogana, $ctrl); // mimpDogana
            $this->updateSort($this->mimpTransporti, $ctrl); // mimpTransporti
            $this->updateSort($this->mimpTjera, $ctrl); // mimpTjera
            $this->updateSort($this->mimpDtHyrjes, $ctrl); // mimpDtHyrjes
            $this->updateSort($this->mimpCmimiShitjes, $ctrl); // mimpCmimiShitjes
            $this->updateSort($this->mimpGati, $ctrl); // mimpGati
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
                $this->mimpID->setSort("");
                $this->mimpMarka->setSort("");
                $this->mimpModeli->setSort("");
                $this->mimpTipi->setSort("");
                $this->mimpShasia->setSort("");
                $this->mimpViti->setSort("");
                $this->mimpKarburant->setSort("");
                $this->mimpKambio->setSort("");
                $this->mimpNgjyra->setSort("");
                $this->mimpPrejardhja->setSort("");
                $this->mimpInfo->setSort("");
                $this->mimpCmimiBlerjes->setSort("");
                $this->mimpDogana->setSort("");
                $this->mimpTransporti->setSort("");
                $this->mimpTjera->setSort("");
                $this->mimpDtHyrjes->setSort("");
                $this->mimpCmimiShitjes->setSort("");
                $this->mimpGati->setSort("");
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

        // "delete"
        $item = &$this->ListOptions->add("delete");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->canDelete();
        $item->OnLeft = false;

        // "detail_makina_importi_sherbime"
        $item = &$this->ListOptions->add("detail_makina_importi_sherbime");
        $item->CssClass = "text-nowrap";
        $item->Visible = $Security->allowList(CurrentProjectID() . 'makina_importi_sherbime');
        $item->OnLeft = false;
        $item->ShowInButtonGroup = false;

        // Multiple details
        if ($this->ShowMultipleDetails) {
            $item = &$this->ListOptions->add("details");
            $item->CssClass = "text-nowrap";
            $item->Visible = $this->ShowMultipleDetails && $this->ListOptions->detailVisible();
            $item->OnLeft = false;
            $item->ShowInButtonGroup = false;
            $this->ListOptions->hideDetailItems();
        }

        // Set up detail pages
        $pages = new SubPages();
        $pages->add("makina_importi_sherbime");
        $this->DetailPages = $pages;

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
                if (IsMobile()) {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("EditLink") . "</a>";
                } else {
                    $opt->Body = "<a class=\"ew-row-link ew-edit\" title=\"" . $editcaption . "\" data-table=\"makina_importi\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\" data-btn=\"SaveBtn\">" . $Language->phrase("EditLink") . "</a>";
                }
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
                    $link = "<li><button type=\"button\" class=\"dropdown-item ew-action ew-list-action\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fmakina_importilist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . $listaction->Caption . "</button></li>";
                    if ($link != "") {
                        $links[] = $link;
                        if ($body == "") { // Setup first button
                            $body = "<button type=\"button\" class=\"btn btn-default ew-action ew-list-action\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" data-ew-action=\"submit\" form=\"fmakina_importilist\" data-key=\"" . $this->keyToJson(true) . "\"" . $listaction->toDataAttrs() . ">" . $icon . $listaction->Caption . "</button>";
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
        $detailViewTblVar = "";
        $detailCopyTblVar = "";
        $detailEditTblVar = "";

        // "detail_makina_importi_sherbime"
        $opt = $this->ListOptions["detail_makina_importi_sherbime"];
        if ($Security->allowList(CurrentProjectID() . 'makina_importi_sherbime')) {
            $body = $Language->phrase("DetailLink") . $Language->TablePhrase("makina_importi_sherbime", "TblCaption");
            $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode("MakinaImportiSherbimeList?" . Config("TABLE_SHOW_MASTER") . "=makina_importi&" . GetForeignKeyUrl("fk_mimpID", $this->mimpID->CurrentValue) . "") . "\">" . $body . "</a>";
            $links = "";
            $detailPage = Container("MakinaImportiSherbimeGrid");
            if ($detailPage->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'makina_importi')) {
                $caption = $Language->phrase("MasterDetailViewLink", null);
                $url = $this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=makina_importi_sherbime");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailViewTblVar != "") {
                    $detailViewTblVar .= ",";
                }
                $detailViewTblVar .= "makina_importi_sherbime";
            }
            if ($detailPage->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'makina_importi')) {
                $caption = $Language->phrase("MasterDetailEditLink", null);
                $url = $this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=makina_importi_sherbime");
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode($url) . "\">" . $caption . "</a></li>";
                if ($detailEditTblVar != "") {
                    $detailEditTblVar .= ",";
                }
                $detailEditTblVar .= "makina_importi_sherbime";
            }
            if ($links != "") {
                $body .= "<button class=\"dropdown-toggle btn btn-default ew-detail\" data-bs-toggle=\"dropdown\"></button>";
                $body .= "<ul class=\"dropdown-menu\">" . $links . "</ul>";
            }
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">" . $body . "</div>";
            $opt->Body = $body;
            if ($this->ShowMultipleDetails) {
                $opt->Visible = false;
            }
        }
        if ($this->ShowMultipleDetails) {
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">";
            $links = "";
            if ($detailViewTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailViewLink", true)) . "\" href=\"" . HtmlEncode($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailViewTblVar)) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            }
            if ($detailEditTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailEditLink", true)) . "\" href=\"" . HtmlEncode($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailEditTblVar)) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
            }
            if ($detailCopyTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailCopyLink", true)) . "\" href=\"" . HtmlEncode($this->GetCopyUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailCopyTblVar)) . "\">" . $Language->phrase("MasterDetailCopyLink", null) . "</a></li>";
            }
            if ($links != "") {
                $body .= "<button class=\"dropdown-toggle btn btn-default ew-master-detail\" title=\"" . HtmlEncode($Language->phrase("MultipleMasterDetails", true)) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("MultipleMasterDetails") . "</button>";
                $body .= "<ul class=\"dropdown-menu ew-menu\">" . $links . "</ul>";
            }
            $body .= "</div>";
            // Multiple details
            $opt = $this->ListOptions["details"];
            $opt->Body = $body;
        }

        // "checkbox"
        $opt = $this->ListOptions["checkbox"];
        $opt->Body = "<div class=\"form-check\"><input type=\"checkbox\" id=\"key_m_" . $this->RowCount . "\" name=\"key_m[]\" class=\"form-check-input ew-multi-select\" value=\"" . HtmlEncode($this->mimpID->CurrentValue) . "\" data-ew-action=\"select-key\"></div>";
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
        $option = $options["detail"];
        $detailTableLink = "";
                $item = &$option->add("detailadd_makina_importi_sherbime");
                $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=makina_importi_sherbime");
                $detailPage = Container("MakinaImportiSherbimeGrid");
                $caption = $Language->phrase("Add") . "&nbsp;" . $this->tableCaption() . "/" . $detailPage->tableCaption();
                $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
                $item->Visible = ($detailPage->DetailAdd && $Security->allowAdd(CurrentProjectID() . 'makina_importi') && $Security->canAdd());
                if ($item->Visible) {
                    if ($detailTableLink != "") {
                        $detailTableLink .= ",";
                    }
                    $detailTableLink .= "makina_importi_sherbime";
                }

        // Add multiple details
        if ($this->ShowMultipleDetails) {
            $item = &$option->add("detailsadd");
            $url = $this->getAddUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailTableLink);
            $caption = $Language->phrase("AddMasterDetailLink");
            $item->Body = "<a class=\"ew-detail-add-group ew-detail-add\" title=\"" . HtmlTitle($caption) . "\" data-caption=\"" . HtmlTitle($caption) . "\" href=\"" . HtmlEncode(GetUrl($url)) . "\">" . $caption . "</a>";
            $item->Visible = $detailTableLink != "" && $Security->canAdd();
            // Hide single master/detail items
            $ar = explode(",", $detailTableLink);
            $cnt = count($ar);
            for ($i = 0; $i < $cnt; $i++) {
                if ($item = $option["detailadd_" . $ar[$i]]) {
                    $item->Visible = false;
                }
            }
        }
        $option = $options["action"];

        // Show column list for column visibility
        if ($this->UseColumnVisibility) {
            $option = $this->OtherOptions["column"];
            $item = &$option->addGroupOption();
            $item->Body = "";
            $item->Visible = $this->UseColumnVisibility;
            $option->add("mimpID", $this->createColumnOption("mimpID"));
            $option->add("mimpMarka", $this->createColumnOption("mimpMarka"));
            $option->add("mimpModeli", $this->createColumnOption("mimpModeli"));
            $option->add("mimpTipi", $this->createColumnOption("mimpTipi"));
            $option->add("mimpShasia", $this->createColumnOption("mimpShasia"));
            $option->add("mimpViti", $this->createColumnOption("mimpViti"));
            $option->add("mimpKarburant", $this->createColumnOption("mimpKarburant"));
            $option->add("mimpPrejardhja", $this->createColumnOption("mimpPrejardhja"));
            $option->add("mimpCmimiBlerjes", $this->createColumnOption("mimpCmimiBlerjes"));
            $option->add("mimpDogana", $this->createColumnOption("mimpDogana"));
            $option->add("mimpTransporti", $this->createColumnOption("mimpTransporti"));
            $option->add("mimpTjera", $this->createColumnOption("mimpTjera"));
            $option->add("mimpDtHyrjes", $this->createColumnOption("mimpDtHyrjes"));
            $option->add("mimpCmimiShitjes", $this->createColumnOption("mimpCmimiShitjes"));
            $option->add("mimpGati", $this->createColumnOption("mimpGati"));
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
        $item->Body = "<a class=\"ew-save-filter\" data-form=\"fmakina_importisrch\" data-ew-action=\"none\">" . $Language->phrase("SaveCurrentFilter") . "</a>";
        $item->Visible = true;
        $item = &$this->FilterOptions->add("deletefilter");
        $item->Body = "<a class=\"ew-delete-filter\" data-form=\"fmakina_importisrch\" data-ew-action=\"none\">" . $Language->phrase("DeleteFilter") . "</a>";
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
                $item->Body = '<button type="button" class="btn btn-default ew-action ew-list-action" title="' . HtmlEncode($caption) . '" data-caption="' . HtmlEncode($caption) . '" data-ew-action="submit" form="fmakina_importilist"' . $listaction->toDataAttrs() . '>' . $icon . '</button>';
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

        // mimpID
        if ($this->mimpID->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpID->AdvancedSearch->SearchValue != "" || $this->mimpID->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpMarka
        if ($this->mimpMarka->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpMarka->AdvancedSearch->SearchValue != "" || $this->mimpMarka->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpModeli
        if ($this->mimpModeli->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpModeli->AdvancedSearch->SearchValue != "" || $this->mimpModeli->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpTipi
        if ($this->mimpTipi->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpTipi->AdvancedSearch->SearchValue != "" || $this->mimpTipi->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpShasia
        if ($this->mimpShasia->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpShasia->AdvancedSearch->SearchValue != "" || $this->mimpShasia->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpViti
        if ($this->mimpViti->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpViti->AdvancedSearch->SearchValue != "" || $this->mimpViti->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpKarburant
        if ($this->mimpKarburant->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpKarburant->AdvancedSearch->SearchValue != "" || $this->mimpKarburant->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpKambio
        if ($this->mimpKambio->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpKambio->AdvancedSearch->SearchValue != "" || $this->mimpKambio->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpNgjyra
        if ($this->mimpNgjyra->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpNgjyra->AdvancedSearch->SearchValue != "" || $this->mimpNgjyra->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpPrejardhja
        if ($this->mimpPrejardhja->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpPrejardhja->AdvancedSearch->SearchValue != "" || $this->mimpPrejardhja->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpInfo
        if ($this->mimpInfo->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpInfo->AdvancedSearch->SearchValue != "" || $this->mimpInfo->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpCmimiBlerjes
        if ($this->mimpCmimiBlerjes->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpCmimiBlerjes->AdvancedSearch->SearchValue != "" || $this->mimpCmimiBlerjes->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpDogana
        if ($this->mimpDogana->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpDogana->AdvancedSearch->SearchValue != "" || $this->mimpDogana->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpTransporti
        if ($this->mimpTransporti->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpTransporti->AdvancedSearch->SearchValue != "" || $this->mimpTransporti->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpTjera
        if ($this->mimpTjera->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpTjera->AdvancedSearch->SearchValue != "" || $this->mimpTjera->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpDtHyrjes
        if ($this->mimpDtHyrjes->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpDtHyrjes->AdvancedSearch->SearchValue != "" || $this->mimpDtHyrjes->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpCmimiShitjes
        if ($this->mimpCmimiShitjes->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpCmimiShitjes->AdvancedSearch->SearchValue != "" || $this->mimpCmimiShitjes->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
                $this->Command = "search";
            }
        }

        // mimpGati
        if ($this->mimpGati->AdvancedSearch->get()) {
            $hasValue = true;
            if (($this->mimpGati->AdvancedSearch->SearchValue != "" || $this->mimpGati->AdvancedSearch->SearchValue2 != "") && $this->Command == "") {
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
        $this->ViewUrl = $this->getViewUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->InlineEditUrl = $this->getInlineEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->InlineCopyUrl = $this->getInlineCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // mimpID

        // mimpMarka

        // mimpModeli

        // mimpTipi

        // mimpShasia

        // mimpViti

        // mimpKarburant

        // mimpKambio

        // mimpNgjyra

        // mimpPrejardhja

        // mimpInfo

        // mimpCmimiBlerjes

        // mimpDogana

        // mimpTransporti

        // mimpTjera

        // mimpDtHyrjes

        // mimpCmimiShitjes

        // mimpGati

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
            $this->mimpID->TooltipValue = "";

            // mimpMarka
            $this->mimpMarka->LinkCustomAttributes = "";
            $this->mimpMarka->HrefValue = "";
            $this->mimpMarka->TooltipValue = "";

            // mimpModeli
            $this->mimpModeli->LinkCustomAttributes = "";
            $this->mimpModeli->HrefValue = "";
            $this->mimpModeli->TooltipValue = "";

            // mimpTipi
            $this->mimpTipi->LinkCustomAttributes = "";
            $this->mimpTipi->HrefValue = "";
            $this->mimpTipi->TooltipValue = "";

            // mimpShasia
            $this->mimpShasia->LinkCustomAttributes = "";
            $this->mimpShasia->HrefValue = "";
            $this->mimpShasia->TooltipValue = "";

            // mimpViti
            $this->mimpViti->LinkCustomAttributes = "";
            $this->mimpViti->HrefValue = "";
            $this->mimpViti->TooltipValue = "";

            // mimpKarburant
            $this->mimpKarburant->LinkCustomAttributes = "";
            $this->mimpKarburant->HrefValue = "";
            $this->mimpKarburant->TooltipValue = "";

            // mimpPrejardhja
            $this->mimpPrejardhja->LinkCustomAttributes = "";
            $this->mimpPrejardhja->HrefValue = "";
            $this->mimpPrejardhja->TooltipValue = "";

            // mimpCmimiBlerjes
            $this->mimpCmimiBlerjes->LinkCustomAttributes = "";
            $this->mimpCmimiBlerjes->HrefValue = "";
            $this->mimpCmimiBlerjes->TooltipValue = "";

            // mimpDogana
            $this->mimpDogana->LinkCustomAttributes = "";
            $this->mimpDogana->HrefValue = "";
            $this->mimpDogana->TooltipValue = "";

            // mimpTransporti
            $this->mimpTransporti->LinkCustomAttributes = "";
            $this->mimpTransporti->HrefValue = "";
            $this->mimpTransporti->TooltipValue = "";

            // mimpTjera
            $this->mimpTjera->LinkCustomAttributes = "";
            $this->mimpTjera->HrefValue = "";
            $this->mimpTjera->TooltipValue = "";

            // mimpDtHyrjes
            $this->mimpDtHyrjes->LinkCustomAttributes = "";
            $this->mimpDtHyrjes->HrefValue = "";
            $this->mimpDtHyrjes->TooltipValue = "";

            // mimpCmimiShitjes
            $this->mimpCmimiShitjes->LinkCustomAttributes = "";
            $this->mimpCmimiShitjes->HrefValue = "";
            $this->mimpCmimiShitjes->TooltipValue = "";

            // mimpGati
            $this->mimpGati->LinkCustomAttributes = "";
            $this->mimpGati->HrefValue = "";
            $this->mimpGati->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_SEARCH) {
            // mimpID
            $this->mimpID->setupEditAttributes();
            $this->mimpID->EditCustomAttributes = "";
            $this->mimpID->EditValue = HtmlEncode($this->mimpID->AdvancedSearch->SearchValue);
            $this->mimpID->PlaceHolder = RemoveHtml($this->mimpID->caption());

            // mimpMarka
            $this->mimpMarka->setupEditAttributes();
            $this->mimpMarka->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpMarka->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->mimpMarka->AdvancedSearch->ViewValue = $this->mimpMarka->lookupCacheOption($curVal);
            } else {
                $this->mimpMarka->AdvancedSearch->ViewValue = $this->mimpMarka->Lookup !== null && is_array($this->mimpMarka->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpMarka->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->mimpMarka->EditValue = array_values($this->mimpMarka->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmarkaID`" . SearchString("=", $this->mimpMarka->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
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
            $this->mimpMarka->setupEditAttributes();
            $this->mimpMarka->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpMarka->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->mimpMarka->AdvancedSearch->ViewValue2 = $this->mimpMarka->lookupCacheOption($curVal);
            } else {
                $this->mimpMarka->AdvancedSearch->ViewValue2 = $this->mimpMarka->Lookup !== null && is_array($this->mimpMarka->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpMarka->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->mimpMarka->EditValue2 = array_values($this->mimpMarka->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmarkaID`" . SearchString("=", $this->mimpMarka->AdvancedSearch->SearchValue2, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mimpMarka->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mimpMarka->EditValue2 = $arwrk;
            }
            $this->mimpMarka->PlaceHolder = RemoveHtml($this->mimpMarka->caption());

            // mimpModeli
            $this->mimpModeli->setupEditAttributes();
            $this->mimpModeli->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpModeli->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->mimpModeli->AdvancedSearch->ViewValue = $this->mimpModeli->lookupCacheOption($curVal);
            } else {
                $this->mimpModeli->AdvancedSearch->ViewValue = $this->mimpModeli->Lookup !== null && is_array($this->mimpModeli->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpModeli->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->mimpModeli->EditValue = array_values($this->mimpModeli->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmodeliID`" . SearchString("=", $this->mimpModeli->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
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
            $this->mimpModeli->setupEditAttributes();
            $this->mimpModeli->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpModeli->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->mimpModeli->AdvancedSearch->ViewValue2 = $this->mimpModeli->lookupCacheOption($curVal);
            } else {
                $this->mimpModeli->AdvancedSearch->ViewValue2 = $this->mimpModeli->Lookup !== null && is_array($this->mimpModeli->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpModeli->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->mimpModeli->EditValue2 = array_values($this->mimpModeli->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mmodeliID`" . SearchString("=", $this->mimpModeli->AdvancedSearch->SearchValue2, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mimpModeli->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mimpModeli->EditValue2 = $arwrk;
            }
            $this->mimpModeli->PlaceHolder = RemoveHtml($this->mimpModeli->caption());

            // mimpTipi
            $this->mimpTipi->setupEditAttributes();
            $this->mimpTipi->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpTipi->AdvancedSearch->SearchValue));
            if ($curVal != "") {
                $this->mimpTipi->AdvancedSearch->ViewValue = $this->mimpTipi->lookupCacheOption($curVal);
            } else {
                $this->mimpTipi->AdvancedSearch->ViewValue = $this->mimpTipi->Lookup !== null && is_array($this->mimpTipi->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpTipi->AdvancedSearch->ViewValue !== null) { // Load from cache
                $this->mimpTipi->EditValue = array_values($this->mimpTipi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mtipiID`" . SearchString("=", $this->mimpTipi->AdvancedSearch->SearchValue, DATATYPE_NUMBER, "");
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
            $this->mimpTipi->setupEditAttributes();
            $this->mimpTipi->EditCustomAttributes = "";
            $curVal = trim(strval($this->mimpTipi->AdvancedSearch->SearchValue2));
            if ($curVal != "") {
                $this->mimpTipi->AdvancedSearch->ViewValue2 = $this->mimpTipi->lookupCacheOption($curVal);
            } else {
                $this->mimpTipi->AdvancedSearch->ViewValue2 = $this->mimpTipi->Lookup !== null && is_array($this->mimpTipi->lookupOptions()) ? $curVal : null;
            }
            if ($this->mimpTipi->AdvancedSearch->ViewValue2 !== null) { // Load from cache
                $this->mimpTipi->EditValue2 = array_values($this->mimpTipi->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`mtipiID`" . SearchString("=", $this->mimpTipi->AdvancedSearch->SearchValue2, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->mimpTipi->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->mimpTipi->EditValue2 = $arwrk;
            }
            $this->mimpTipi->PlaceHolder = RemoveHtml($this->mimpTipi->caption());

            // mimpShasia
            $this->mimpShasia->setupEditAttributes();
            $this->mimpShasia->EditCustomAttributes = "";
            if (!$this->mimpShasia->Raw) {
                $this->mimpShasia->AdvancedSearch->SearchValue = HtmlDecode($this->mimpShasia->AdvancedSearch->SearchValue);
            }
            $this->mimpShasia->EditValue = HtmlEncode($this->mimpShasia->AdvancedSearch->SearchValue);
            $this->mimpShasia->PlaceHolder = RemoveHtml($this->mimpShasia->caption());

            // mimpViti
            $this->mimpViti->setupEditAttributes();
            $this->mimpViti->EditCustomAttributes = "";
            $this->mimpViti->EditValue = HtmlEncode($this->mimpViti->AdvancedSearch->SearchValue);
            $this->mimpViti->PlaceHolder = RemoveHtml($this->mimpViti->caption());

            // mimpKarburant
            $this->mimpKarburant->EditCustomAttributes = "";
            $this->mimpKarburant->EditValue = $this->mimpKarburant->options(false);
            $this->mimpKarburant->PlaceHolder = RemoveHtml($this->mimpKarburant->caption());
            $this->mimpKarburant->EditCustomAttributes = "";
            $this->mimpKarburant->EditValue2 = $this->mimpKarburant->options(false);
            $this->mimpKarburant->PlaceHolder = RemoveHtml($this->mimpKarburant->caption());

            // mimpPrejardhja
            $this->mimpPrejardhja->setupEditAttributes();
            $this->mimpPrejardhja->EditCustomAttributes = "";
            if (!$this->mimpPrejardhja->Raw) {
                $this->mimpPrejardhja->AdvancedSearch->SearchValue = HtmlDecode($this->mimpPrejardhja->AdvancedSearch->SearchValue);
            }
            $this->mimpPrejardhja->EditValue = HtmlEncode($this->mimpPrejardhja->AdvancedSearch->SearchValue);
            $this->mimpPrejardhja->PlaceHolder = RemoveHtml($this->mimpPrejardhja->caption());

            // mimpCmimiBlerjes
            $this->mimpCmimiBlerjes->setupEditAttributes();
            $this->mimpCmimiBlerjes->EditCustomAttributes = "";
            $this->mimpCmimiBlerjes->EditValue = HtmlEncode($this->mimpCmimiBlerjes->AdvancedSearch->SearchValue);
            $this->mimpCmimiBlerjes->PlaceHolder = RemoveHtml($this->mimpCmimiBlerjes->caption());

            // mimpDogana
            $this->mimpDogana->setupEditAttributes();
            $this->mimpDogana->EditCustomAttributes = "";
            $this->mimpDogana->EditValue = HtmlEncode($this->mimpDogana->AdvancedSearch->SearchValue);
            $this->mimpDogana->PlaceHolder = RemoveHtml($this->mimpDogana->caption());

            // mimpTransporti
            $this->mimpTransporti->setupEditAttributes();
            $this->mimpTransporti->EditCustomAttributes = "";
            $this->mimpTransporti->EditValue = HtmlEncode($this->mimpTransporti->AdvancedSearch->SearchValue);
            $this->mimpTransporti->PlaceHolder = RemoveHtml($this->mimpTransporti->caption());

            // mimpTjera
            $this->mimpTjera->setupEditAttributes();
            $this->mimpTjera->EditCustomAttributes = "";
            $this->mimpTjera->EditValue = HtmlEncode($this->mimpTjera->AdvancedSearch->SearchValue);
            $this->mimpTjera->PlaceHolder = RemoveHtml($this->mimpTjera->caption());

            // mimpDtHyrjes
            $this->mimpDtHyrjes->setupEditAttributes();
            $this->mimpDtHyrjes->EditCustomAttributes = "";
            $this->mimpDtHyrjes->EditValue = HtmlEncode(FormatDateTime(UnFormatDateTime($this->mimpDtHyrjes->AdvancedSearch->SearchValue, $this->mimpDtHyrjes->formatPattern()), $this->mimpDtHyrjes->formatPattern()));
            $this->mimpDtHyrjes->PlaceHolder = RemoveHtml($this->mimpDtHyrjes->caption());

            // mimpCmimiShitjes
            $this->mimpCmimiShitjes->setupEditAttributes();
            $this->mimpCmimiShitjes->EditCustomAttributes = "";
            $this->mimpCmimiShitjes->EditValue = HtmlEncode($this->mimpCmimiShitjes->AdvancedSearch->SearchValue);
            $this->mimpCmimiShitjes->PlaceHolder = RemoveHtml($this->mimpCmimiShitjes->caption());

            // mimpGati
            $this->mimpGati->EditCustomAttributes = "";
            $this->mimpGati->EditValue = $this->mimpGati->options(false);
            $this->mimpGati->PlaceHolder = RemoveHtml($this->mimpGati->caption());
            $this->mimpGati->EditCustomAttributes = "";
            $this->mimpGati->EditValue2 = $this->mimpGati->options(false);
            $this->mimpGati->PlaceHolder = RemoveHtml($this->mimpGati->caption());
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
        if (!CheckInteger($this->mimpViti->AdvancedSearch->SearchValue)) {
            $this->mimpViti->addErrorMessage($this->mimpViti->getErrorMessage(false));
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
        $this->mimpID->AdvancedSearch->load();
        $this->mimpMarka->AdvancedSearch->load();
        $this->mimpModeli->AdvancedSearch->load();
        $this->mimpTipi->AdvancedSearch->load();
        $this->mimpShasia->AdvancedSearch->load();
        $this->mimpViti->AdvancedSearch->load();
        $this->mimpKarburant->AdvancedSearch->load();
        $this->mimpKambio->AdvancedSearch->load();
        $this->mimpNgjyra->AdvancedSearch->load();
        $this->mimpPrejardhja->AdvancedSearch->load();
        $this->mimpInfo->AdvancedSearch->load();
        $this->mimpCmimiBlerjes->AdvancedSearch->load();
        $this->mimpDogana->AdvancedSearch->load();
        $this->mimpTransporti->AdvancedSearch->load();
        $this->mimpTjera->AdvancedSearch->load();
        $this->mimpDtHyrjes->AdvancedSearch->load();
        $this->mimpCmimiShitjes->AdvancedSearch->load();
        $this->mimpGati->AdvancedSearch->load();
    }

    // Get export HTML tag
    protected function getExportTag($type, $custom = false)
    {
        global $Language;
        $pageUrl = $this->pageUrl();
        $exportUrl = GetUrl($pageUrl . "export=" . $type . ($custom ? "&amp;custom=1" : ""));
        if (SameText($type, "excel")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" form=\"fmakina_importilist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"excel\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToExcel") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-excel\" title=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToExcelText")) . "\">" . $Language->phrase("ExportToExcel") . "</a>";
            }
        } elseif (SameText($type, "word")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" form=\"fmakina_importilist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"word\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToWord") . "</button>";
            } else {
                return "<a href=\"$exportUrl\" class=\"btn btn-default ew-export-link ew-word\" title=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToWordText")) . "\">" . $Language->phrase("ExportToWord") . "</a>";
            }
        } elseif (SameText($type, "pdf")) {
            if ($custom) {
                return "<button type=\"button\" class=\"btn btn-default ew-export-link ew-pdf\" title=\"" . HtmlEncode($Language->phrase("ExportToPdfText")) . "\" data-caption=\"" . HtmlEncode($Language->phrase("ExportToPdfText")) . "\" form=\"fmakina_importilist\" data-url=\"$exportUrl\" data-ew-action=\"export\" data-export=\"pdf\" data-custom=\"true\" data-export-selected=\"false\">" . $Language->phrase("ExportToPdf") . "</button>";
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
            return '<button type="button" class="btn btn-default ew-export-link ew-email" title="' . $Language->phrase("ExportToEmailText") . '" data-caption="' . $Language->phrase("ExportToEmailText") . '" form="fmakina_importilist" data-ew-action="email" data-hdr="' . $Language->phrase("ExportToEmailText") . '" data-sel="false"' . $url . '>' . $Language->phrase("ExportToEmail") . '</button>';
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
        $item->Body = "<a class=\"btn btn-default ew-search-toggle" . $searchToggleClass . "\" role=\"button\" title=\"" . $Language->phrase("SearchPanel") . "\" data-caption=\"" . $Language->phrase("SearchPanel") . "\" data-ew-action=\"search-toggle\" data-form=\"fmakina_importisrch\" aria-pressed=\"" . ($searchToggleClass == " active" ? "true" : "false") . "\">" . $Language->phrase("SearchLink") . "</a>";
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
