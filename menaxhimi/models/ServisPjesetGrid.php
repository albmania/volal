<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ServisPjesetGrid extends ServisPjeset
{
    use MessagesTrait;

    // Page ID
    public $PageID = "grid";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'servis_pjeset';

    // Page object name
    public $PageObjName = "ServisPjesetGrid";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Grid form hidden field names
    public $FormName = "fservis_pjesetgrid";
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
        $this->FormActionName .= "_" . $this->FormName;
        $this->OldKeyName .= "_" . $this->FormName;
        $this->FormBlankRowName .= "_" . $this->FormName;
        $this->FormKeyCountName .= "_" . $this->FormName;
        $GLOBALS["Grid"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (servis_pjeset)
        if (!isset($GLOBALS["servis_pjeset"]) || get_class($GLOBALS["servis_pjeset"]) == PROJECT_NAMESPACE . "servis_pjeset") {
            $GLOBALS["servis_pjeset"] = &$this;
        }
        $this->AddUrl = "ServisPjesetAdd";

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'servis_pjeset');
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

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $tbl = Container("servis_pjeset");
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
        unset($GLOBALS["Grid"]);
        if ($url === "") {
            return;
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

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
            $key .= @$ar['servisPjeseID'];
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
            $this->servisPjeseID->Visible = false;
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
    public $ShowOtherOptions = false;
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

        // Get grid add count
        $gridaddcnt = Get(Config("TABLE_GRID_ADD_ROW_COUNT"), "");
        if (is_numeric($gridaddcnt) && $gridaddcnt > 0) {
            $this->GridAddRowCount = $gridaddcnt;
        }

        // Set up list options
        $this->setupListOptions();
        $this->servisPjeseID->setVisibility();
        $this->servisPjeseServisID->setVisibility();
        $this->servisPjesePjesa->setVisibility();
        $this->servisPjeseSasia->setVisibility();
        $this->servisPjeseCmimi->setVisibility();
        $this->servisPjeseShenim->setVisibility();
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

        // Set up master detail parameters
        $this->setupMasterParms();

        // Setup other options
        $this->setupOtherOptions();

        // Set up lookup cache
        $this->setupLookupOptions($this->servisPjesePjesa);

        // Load default values for add
        $this->loadDefaultValues();

        // Search filters
        $srchAdvanced = ""; // Advanced search filter
        $srchBasic = ""; // Basic search filter
        $filter = "";

        // Get command
        $this->Command = strtolower(Get("cmd", ""));
        if ($this->isPageRequest()) {
            // Set up records per page
            $this->setupDisplayRecords();

            // Handle reset command
            $this->resetCmd();

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

            // Show grid delete link for grid add / grid edit
            if ($this->AllowAddDeleteRow) {
                if ($this->isGridAdd() || $this->isGridEdit()) {
                    $item = $this->ListOptions["griddelete"];
                    if ($item) {
                        $item->Visible = true;
                    }
                }
            }

            // Set up sorting order
            $this->setupSortOrder();
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

        // Build filter
        $filter = "";
        if (!$Security->canList()) {
            $filter = "(0=1)"; // Filter all records
        }

        // Restore master/detail filter from session
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Restore master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Restore detail filter from session
        AddFilter($filter, $this->DbDetailFilter);
        AddFilter($filter, $this->SearchWhere);

        // Load master record
        if ($this->CurrentMode != "add" && $this->DbMasterFilter != "" && $this->getCurrentMasterTable() == "servis") {
            $masterTbl = Container("servis");
            $rsmaster = $masterTbl->loadRs($this->DbMasterFilter)->fetchAssociative();
            $this->MasterRecordExists = $rsmaster !== false;
            if (!$this->MasterRecordExists) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record found
                $this->terminate("ServisList"); // Return to master page
                return;
            } else {
                $masterTbl->loadListRowValues($rsmaster);
                $masterTbl->RowType = ROWTYPE_MASTER; // Master row
                $masterTbl->renderListRow();
            }
        }

        // Set up filter
        if ($this->Command == "json") {
            $this->UseSessionForListSql = false; // Do not use session for ListSQL
            $this->CurrentFilter = $filter;
        } else {
            $this->setSessionWhere($filter);
            $this->CurrentFilter = "";
        }
        if ($this->isGridAdd()) {
            if ($this->CurrentMode == "copy") {
                $this->TotalRecords = $this->listRecordCount();
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->TotalRecords;
                $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
            } else {
                $this->CurrentFilter = "0=1";
                $this->StartRecord = 1;
                $this->DisplayRecords = $this->GridAddRowCount;
            }
            $this->TotalRecords = $this->DisplayRecords;
            $this->StopRecord = $this->DisplayRecords;
        } else {
            $this->TotalRecords = $this->listRecordCount();
            $this->StartRecord = 1;
            $this->DisplayRecords = $this->TotalRecords; // Display all records
            $this->Recordset = $this->loadRecordset($this->StartRecord - 1, $this->DisplayRecords);
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

    // Exit inline mode
    protected function clearInlineMode()
    {
        $this->servisPjeseCmimi->FormValue = ""; // Clear form value
        $this->LastAction = $this->CurrentAction; // Save last action
        $this->CurrentAction = ""; // Clear action
        $_SESSION[SESSION_INLINE_MODE] = ""; // Clear inline mode
    }

    // Switch to Grid Add mode
    protected function gridAddMode()
    {
        global $Security, $Language;
        if (!$Security->canAdd()) { // No add permission
            $this->CurrentAction = "";
            $this->setFailureMessage($Language->phrase("NoAddPermission"));
            return false;
        }
        $this->CurrentAction = "gridadd";
        $_SESSION[SESSION_INLINE_MODE] = "gridadd";
        $this->hideFieldsForAddEdit();
    }

    // Switch to Grid Edit mode
    protected function gridEditMode()
    {
        global $Security, $Language;
        if (!$Security->canEdit()) { // No edit permission
            $this->CurrentAction = "";
            $this->setFailureMessage($Language->phrase("NoEditPermission"));
            return false;
        }
        $this->CurrentAction = "gridedit";
        $_SESSION[SESSION_INLINE_MODE] = "gridedit";
        $this->hideFieldsForAddEdit();
    }

    // Perform update to grid
    public function gridUpdate()
    {
        global $Language, $CurrentForm;
        $gridUpdate = true;

        // Get old recordset
        $this->CurrentFilter = $this->buildKeyFilter();
        if ($this->CurrentFilter == "") {
            $this->CurrentFilter = "0=1";
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        if ($rs = $conn->executeQuery($sql)) {
            $rsold = $rs->fetchAllAssociative();
        }

        // Call Grid Updating event
        if (!$this->gridUpdating($rsold)) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridEditCancelled")); // Set grid edit cancelled message
            }
            return false;
        }
        $key = "";

        // Update row index and get row key
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Update all rows based on key
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            $CurrentForm->Index = $rowindex;
            $this->setKey($CurrentForm->getValue($this->OldKeyName));
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));

            // Load all values and keys
            if ($rowaction != "insertdelete") { // Skip insert then deleted rows
                $this->loadFormValues(); // Get form values
                if ($rowaction == "" || $rowaction == "edit" || $rowaction == "delete") {
                    $gridUpdate = $this->OldKey != ""; // Key must not be empty
                } else {
                    $gridUpdate = true;
                }

                // Skip empty row
                if ($rowaction == "insert" && $this->emptyRow()) {
                // Validate form and insert/update/delete record
                } elseif ($gridUpdate) {
                    if ($rowaction == "delete") {
                        $this->CurrentFilter = $this->getRecordFilter();
                        $gridUpdate = $this->deleteRows(); // Delete this row
                    //} elseif (!$this->validateForm()) { // Already done in validateGridForm
                    //    $gridUpdate = false; // Form error, reset action
                    } else {
                        if ($rowaction == "insert") {
                            $gridUpdate = $this->addRow(); // Insert this row
                        } else {
                            if ($this->OldKey != "") {
                                $this->SendEmail = false; // Do not send email on update success
                                $gridUpdate = $this->editRow(); // Update this row
                            }
                        } // End update
                    }
                }
                if ($gridUpdate) {
                    if ($key != "") {
                        $key .= ", ";
                    }
                    $key .= $this->OldKey;
                } else {
                    break;
                }
            }
        }
        if ($gridUpdate) {
            // Get new records
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Updated event
            $this->gridUpdated($rsold, $rsnew);
            $this->clearInlineMode(); // Clear inline edit mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("UpdateFailed")); // Set update failed message
            }
        }
        return $gridUpdate;
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

    // Perform Grid Add
    public function gridInsert()
    {
        global $Language, $CurrentForm;
        $rowindex = 1;
        $gridInsert = false;
        $conn = $this->getConnection();

        // Call Grid Inserting event
        if (!$this->gridInserting()) {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("GridAddCancelled")); // Set grid add cancelled message
            }
            return false;
        }

        // Init key filter
        $wrkfilter = "";
        $addcnt = 0;
        $key = "";

        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Insert all rows
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "" && $rowaction != "insert") {
                continue; // Skip
            }
            if ($rowaction == "insert") {
                $this->OldKey = strval($CurrentForm->getValue($this->OldKeyName));
                $this->loadOldRecord(); // Load old record
            }
            $this->loadFormValues(); // Get form values
            if (!$this->emptyRow()) {
                $addcnt++;
                $this->SendEmail = false; // Do not send email on insert success

                // Validate form // Already done in validateGridForm
                //if (!$this->validateForm()) {
                //    $gridInsert = false; // Form error, reset action
                //} else {
                    $gridInsert = $this->addRow($this->OldRecordset); // Insert this row
                //}
                if ($gridInsert) {
                    if ($key != "") {
                        $key .= Config("COMPOSITE_KEY_SEPARATOR");
                    }
                    $key .= $this->servisPjeseID->CurrentValue;

                    // Add filter for this record
                    $filter = $this->getRecordFilter();
                    if ($wrkfilter != "") {
                        $wrkfilter .= " OR ";
                    }
                    $wrkfilter .= $filter;
                } else {
                    break;
                }
            }
        }
        if ($addcnt == 0) { // No record inserted
            $this->clearInlineMode(); // Clear grid add mode and return
            return true;
        }
        if ($gridInsert) {
            // Get new records
            $this->CurrentFilter = $wrkfilter;
            $sql = $this->getCurrentSql();
            $rsnew = $conn->fetchAllAssociative($sql);

            // Call Grid_Inserted event
            $this->gridInserted($rsnew);
            $this->clearInlineMode(); // Clear grid add mode
        } else {
            if ($this->getFailureMessage() == "") {
                $this->setFailureMessage($Language->phrase("InsertFailed")); // Set insert failed message
            }
        }
        return $gridInsert;
    }

    // Check if empty row
    public function emptyRow()
    {
        global $CurrentForm;
        if ($CurrentForm->hasValue("x_servisPjeseServisID") && $CurrentForm->hasValue("o_servisPjeseServisID") && $this->servisPjeseServisID->CurrentValue != $this->servisPjeseServisID->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_servisPjesePjesa") && $CurrentForm->hasValue("o_servisPjesePjesa") && $this->servisPjesePjesa->CurrentValue != $this->servisPjesePjesa->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_servisPjeseSasia") && $CurrentForm->hasValue("o_servisPjeseSasia") && $this->servisPjeseSasia->CurrentValue != $this->servisPjeseSasia->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_servisPjeseCmimi") && $CurrentForm->hasValue("o_servisPjeseCmimi") && $this->servisPjeseCmimi->CurrentValue != $this->servisPjeseCmimi->DefaultValue) {
            return false;
        }
        if ($CurrentForm->hasValue("x_servisPjeseShenim") && $CurrentForm->hasValue("o_servisPjeseShenim") && $this->servisPjeseShenim->CurrentValue != $this->servisPjeseShenim->DefaultValue) {
            return false;
        }
        return true;
    }

    // Validate grid form
    public function validateGridForm()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }

        // Validate all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } elseif (!$this->validateForm()) {
                    $this->EventCancelled = true;
                    return false;
                }
            }
        }
        return true;
    }

    // Get all form values of the grid
    public function getGridFormValues()
    {
        global $CurrentForm;
        // Get row count
        $CurrentForm->Index = -1;
        $rowcnt = strval($CurrentForm->getValue($this->FormKeyCountName));
        if ($rowcnt == "" || !is_numeric($rowcnt)) {
            $rowcnt = 0;
        }
        $rows = [];

        // Loop through all records
        for ($rowindex = 1; $rowindex <= $rowcnt; $rowindex++) {
            // Load current row values
            $CurrentForm->Index = $rowindex;
            $rowaction = strval($CurrentForm->getValue($this->FormActionName));
            if ($rowaction != "delete" && $rowaction != "insertdelete") {
                $this->loadFormValues(); // Get form values
                if ($rowaction == "insert" && $this->emptyRow()) {
                    // Ignore
                } else {
                    $rows[] = $this->getFieldValues("FormValue"); // Return row as array
                }
            }
        }
        return $rows; // Return as array of array
    }

    // Restore form values for current row
    public function restoreCurrentRowFormValues($idx)
    {
        global $CurrentForm;

        // Get row based on current index
        $CurrentForm->Index = $idx;
        $rowaction = strval($CurrentForm->getValue($this->FormActionName));
        $this->loadFormValues(); // Load form values
        // Set up invalid status correctly
        $this->resetFormError();
        if ($rowaction == "insert" && $this->emptyRow()) {
            // Ignore
        } else {
            $this->validateForm();
        }
    }

    // Reset form status
    public function resetFormError()
    {
        $this->servisPjeseID->clearErrorMessage();
        $this->servisPjeseServisID->clearErrorMessage();
        $this->servisPjesePjesa->clearErrorMessage();
        $this->servisPjeseSasia->clearErrorMessage();
        $this->servisPjeseCmimi->clearErrorMessage();
        $this->servisPjeseShenim->clearErrorMessage();
    }

    // Set up sort parameters
    protected function setupSortOrder()
    {
        // Check for "order" parameter
        if (Get("order") !== null) {
            $this->CurrentOrder = Get("order");
            $this->CurrentOrderType = Get("ordertype", "");
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
            // Reset master/detail keys
            if ($this->Command == "resetall") {
                $this->setCurrentMasterTable(""); // Clear master table
                $this->DbMasterFilter = "";
                $this->DbDetailFilter = "";
                        $this->servisPjeseServisID->setSessionValue("");
            }

            // Reset (clear) sorting order
            if ($this->Command == "resetsort") {
                $orderBy = "";
                $this->setSessionOrderBy($orderBy);
                $this->setSessionOrderByList($orderBy);
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

        // "griddelete"
        if ($this->AllowAddDeleteRow) {
            $item = &$this->ListOptions->add("griddelete");
            $item->CssClass = "text-nowrap";
            $item->OnLeft = false;
            $item->Visible = false; // Default hidden
        }

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

        // Set up row action and key
        if ($CurrentForm && is_numeric($this->RowIndex) && $this->RowType != "view") {
            $CurrentForm->Index = $this->RowIndex;
            $actionName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormActionName);
            $oldKeyName = str_replace("k_", "k" . $this->RowIndex . "_", $this->OldKeyName);
            $blankRowName = str_replace("k_", "k" . $this->RowIndex . "_", $this->FormBlankRowName);
            if ($this->RowAction != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $actionName . "\" id=\"" . $actionName . "\" value=\"" . $this->RowAction . "\">";
            }
            $oldKey = $this->getKey(false); // Get from OldValue
            if ($oldKeyName != "" && $oldKey != "") {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $oldKeyName . "\" id=\"" . $oldKeyName . "\" value=\"" . HtmlEncode($oldKey) . "\">";
            }
            if ($this->RowAction == "insert" && $this->isConfirm() && $this->emptyRow()) {
                $this->MultiSelectKey .= "<input type=\"hidden\" name=\"" . $blankRowName . "\" id=\"" . $blankRowName . "\" value=\"1\">";
            }
        }

        // "delete"
        if ($this->AllowAddDeleteRow) {
            if ($this->CurrentMode == "add" || $this->CurrentMode == "copy" || $this->CurrentMode == "edit") {
                $options = &$this->ListOptions;
                $options->UseButtonGroup = true; // Use button group for grid delete button
                $opt = $options["griddelete"];
                if (!$Security->canDelete() && is_numeric($this->RowIndex) && ($this->RowAction == "" || $this->RowAction == "edit")) { // Do not allow delete existing record
                    $opt->Body = "&nbsp;";
                } else {
                    $opt->Body = "<a class=\"ew-grid-link ew-grid-delete\" title=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("DeleteLink")) . "\" data-ew-action=\"delete-grid-row\" data-rowindex=\"" . $this->RowIndex . "\">" . $Language->phrase("DeleteLink") . "</a>";
                }
            }
        }
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
        $option = $this->OtherOptions["addedit"];
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Add
        if ($this->CurrentMode == "view") { // Check view mode
            $item = &$option->add("add");
            $addcaption = HtmlTitle($Language->phrase("AddLink"));
            $this->AddUrl = $this->getAddUrl();
            $item->Body = "<a class=\"ew-add-edit ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("AddLink") . "</a>";
            $item->Visible = $this->AddUrl != "" && $Security->canAdd();
        }
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
            if (in_array($this->CurrentMode, ["add", "copy", "edit"]) && !$this->isConfirm()) { // Check add/copy/edit mode
                if ($this->AllowAddDeleteRow) {
                    $option = $options["addedit"];
                    $option->UseDropDownButton = false;
                    $item = &$option->add("addblankrow");
                    $item->Body = "<a class=\"ew-add-edit ew-add-blank-row\" title=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("AddBlankRow")) . "\" data-ew-action=\"add-grid-row\">" . $Language->phrase("AddBlankRow") . "</a>";
                    $item->Visible = $Security->canAdd();
                    $this->ShowOtherOptions = $item->Visible;
                }
            }
            if ($this->CurrentMode == "view") { // Check view mode
                $option = $options["addedit"];
                $item = $option["add"];
                $this->ShowOtherOptions = $item && $item->Visible;
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
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $CurrentForm->FormName = $this->FormName;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'servisPjeseID' first before field var 'x_servisPjeseID'
        $val = $CurrentForm->hasValue("servisPjeseID") ? $CurrentForm->getValue("servisPjeseID") : $CurrentForm->getValue("x_servisPjeseID");
        if (!$this->servisPjeseID->IsDetailKey && !$this->isGridAdd() && !$this->isAdd()) {
            $this->servisPjeseID->setFormValue($val);
        }

        // Check field name 'servisPjeseServisID' first before field var 'x_servisPjeseServisID'
        $val = $CurrentForm->hasValue("servisPjeseServisID") ? $CurrentForm->getValue("servisPjeseServisID") : $CurrentForm->getValue("x_servisPjeseServisID");
        if (!$this->servisPjeseServisID->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjeseServisID->Visible = false; // Disable update for API request
            } else {
                $this->servisPjeseServisID->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_servisPjeseServisID")) {
            $this->servisPjeseServisID->setOldValue($CurrentForm->getValue("o_servisPjeseServisID"));
        }

        // Check field name 'servisPjesePjesa' first before field var 'x_servisPjesePjesa'
        $val = $CurrentForm->hasValue("servisPjesePjesa") ? $CurrentForm->getValue("servisPjesePjesa") : $CurrentForm->getValue("x_servisPjesePjesa");
        if (!$this->servisPjesePjesa->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjesePjesa->Visible = false; // Disable update for API request
            } else {
                $this->servisPjesePjesa->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_servisPjesePjesa")) {
            $this->servisPjesePjesa->setOldValue($CurrentForm->getValue("o_servisPjesePjesa"));
        }

        // Check field name 'servisPjeseSasia' first before field var 'x_servisPjeseSasia'
        $val = $CurrentForm->hasValue("servisPjeseSasia") ? $CurrentForm->getValue("servisPjeseSasia") : $CurrentForm->getValue("x_servisPjeseSasia");
        if (!$this->servisPjeseSasia->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjeseSasia->Visible = false; // Disable update for API request
            } else {
                $this->servisPjeseSasia->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_servisPjeseSasia")) {
            $this->servisPjeseSasia->setOldValue($CurrentForm->getValue("o_servisPjeseSasia"));
        }

        // Check field name 'servisPjeseCmimi' first before field var 'x_servisPjeseCmimi'
        $val = $CurrentForm->hasValue("servisPjeseCmimi") ? $CurrentForm->getValue("servisPjeseCmimi") : $CurrentForm->getValue("x_servisPjeseCmimi");
        if (!$this->servisPjeseCmimi->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjeseCmimi->Visible = false; // Disable update for API request
            } else {
                $this->servisPjeseCmimi->setFormValue($val, true, $validate);
            }
        }
        if ($CurrentForm->hasValue("o_servisPjeseCmimi")) {
            $this->servisPjeseCmimi->setOldValue($CurrentForm->getValue("o_servisPjeseCmimi"));
        }

        // Check field name 'servisPjeseShenim' first before field var 'x_servisPjeseShenim'
        $val = $CurrentForm->hasValue("servisPjeseShenim") ? $CurrentForm->getValue("servisPjeseShenim") : $CurrentForm->getValue("x_servisPjeseShenim");
        if (!$this->servisPjeseShenim->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->servisPjeseShenim->Visible = false; // Disable update for API request
            } else {
                $this->servisPjeseShenim->setFormValue($val);
            }
        }
        if ($CurrentForm->hasValue("o_servisPjeseShenim")) {
            $this->servisPjeseShenim->setOldValue($CurrentForm->getValue("o_servisPjeseShenim"));
        }
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        if (!$this->isGridAdd() && !$this->isAdd()) {
            $this->servisPjeseID->CurrentValue = $this->servisPjeseID->FormValue;
        }
        $this->servisPjeseServisID->CurrentValue = $this->servisPjeseServisID->FormValue;
        $this->servisPjesePjesa->CurrentValue = $this->servisPjesePjesa->FormValue;
        $this->servisPjeseSasia->CurrentValue = $this->servisPjeseSasia->FormValue;
        $this->servisPjeseCmimi->CurrentValue = $this->servisPjeseCmimi->FormValue;
        $this->servisPjeseShenim->CurrentValue = $this->servisPjeseShenim->FormValue;
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
        $this->servisPjeseID->setDbValue($row['servisPjeseID']);
        $this->servisPjeseServisID->setDbValue($row['servisPjeseServisID']);
        $this->servisPjesePjesa->setDbValue($row['servisPjesePjesa']);
        if (array_key_exists('EV__servisPjesePjesa', $row)) {
            $this->servisPjesePjesa->VirtualValue = $row['EV__servisPjesePjesa']; // Set up virtual field value
        } else {
            $this->servisPjesePjesa->VirtualValue = ""; // Clear value
        }
        $this->servisPjeseSasia->setDbValue($row['servisPjeseSasia']);
        $this->servisPjeseCmimi->setDbValue($row['servisPjeseCmimi']);
        $this->servisPjeseShenim->setDbValue($row['servisPjeseShenim']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['servisPjeseID'] = $this->servisPjeseID->DefaultValue;
        $row['servisPjeseServisID'] = $this->servisPjeseServisID->DefaultValue;
        $row['servisPjesePjesa'] = $this->servisPjesePjesa->DefaultValue;
        $row['servisPjeseSasia'] = $this->servisPjeseSasia->DefaultValue;
        $row['servisPjeseCmimi'] = $this->servisPjeseCmimi->DefaultValue;
        $row['servisPjeseShenim'] = $this->servisPjeseShenim->DefaultValue;
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
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // servisPjeseID

        // servisPjeseServisID

        // servisPjesePjesa

        // servisPjeseSasia

        // servisPjeseCmimi

        // servisPjeseShenim

        // Accumulate aggregate value
        if ($this->RowType != ROWTYPE_AGGREGATEINIT && $this->RowType != ROWTYPE_AGGREGATE && $this->RowType != ROWTYPE_PREVIEW_FIELD) {
            if (is_numeric($this->servisPjeseCmimi->CurrentValue)) {
                $this->servisPjeseCmimi->Total += $this->servisPjeseCmimi->CurrentValue; // Accumulate total
            }
        }

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // servisPjeseID
            $this->servisPjeseID->ViewValue = $this->servisPjeseID->CurrentValue;
            $this->servisPjeseID->ViewCustomAttributes = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->ViewValue = $this->servisPjeseServisID->CurrentValue;
            $this->servisPjeseServisID->ViewValue = FormatNumber($this->servisPjeseServisID->ViewValue, $this->servisPjeseServisID->formatPattern());
            $this->servisPjeseServisID->ViewCustomAttributes = "";

            // servisPjesePjesa
            if ($this->servisPjesePjesa->VirtualValue != "") {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->VirtualValue;
            } else {
                $curVal = strval($this->servisPjesePjesa->CurrentValue);
                if ($curVal != "") {
                    $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->lookupCacheOption($curVal);
                    if ($this->servisPjesePjesa->ViewValue === null) { // Lookup from database
                        $filterWrk = "`pjeseID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->servisPjesePjesa->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->servisPjesePjesa->Lookup->renderViewRow($rswrk[0]);
                            $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->displayValue($arwrk);
                        } else {
                            $this->servisPjesePjesa->ViewValue = FormatNumber($this->servisPjesePjesa->CurrentValue, $this->servisPjesePjesa->formatPattern());
                        }
                    }
                } else {
                    $this->servisPjesePjesa->ViewValue = null;
                }
            }
            $this->servisPjesePjesa->ViewCustomAttributes = "";

            // servisPjeseSasia
            $this->servisPjeseSasia->ViewValue = $this->servisPjeseSasia->CurrentValue;
            $this->servisPjeseSasia->ViewValue = FormatNumber($this->servisPjeseSasia->ViewValue, $this->servisPjeseSasia->formatPattern());
            $this->servisPjeseSasia->ViewCustomAttributes = "";

            // servisPjeseCmimi
            $this->servisPjeseCmimi->ViewValue = $this->servisPjeseCmimi->CurrentValue;
            $this->servisPjeseCmimi->ViewValue = FormatNumber($this->servisPjeseCmimi->ViewValue, $this->servisPjeseCmimi->formatPattern());
            $this->servisPjeseCmimi->ViewCustomAttributes = "";

            // servisPjeseShenim
            $this->servisPjeseShenim->ViewValue = $this->servisPjeseShenim->CurrentValue;
            $this->servisPjeseShenim->ViewCustomAttributes = "";

            // servisPjeseID
            $this->servisPjeseID->LinkCustomAttributes = "";
            $this->servisPjeseID->HrefValue = "";
            $this->servisPjeseID->TooltipValue = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->LinkCustomAttributes = "";
            $this->servisPjeseServisID->HrefValue = "";
            $this->servisPjeseServisID->TooltipValue = "";

            // servisPjesePjesa
            $this->servisPjesePjesa->LinkCustomAttributes = "";
            $this->servisPjesePjesa->HrefValue = "";
            $this->servisPjesePjesa->TooltipValue = "";

            // servisPjeseSasia
            $this->servisPjeseSasia->LinkCustomAttributes = "";
            $this->servisPjeseSasia->HrefValue = "";
            $this->servisPjeseSasia->TooltipValue = "";

            // servisPjeseCmimi
            $this->servisPjeseCmimi->LinkCustomAttributes = "";
            $this->servisPjeseCmimi->HrefValue = "";
            $this->servisPjeseCmimi->TooltipValue = "";

            // servisPjeseShenim
            $this->servisPjeseShenim->LinkCustomAttributes = "";
            $this->servisPjeseShenim->HrefValue = "";
            $this->servisPjeseShenim->TooltipValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // servisPjeseID

            // servisPjeseServisID
            $this->servisPjeseServisID->setupEditAttributes();
            $this->servisPjeseServisID->EditCustomAttributes = "";
            if ($this->servisPjeseServisID->getSessionValue() != "") {
                $this->servisPjeseServisID->CurrentValue = GetForeignKeyValue($this->servisPjeseServisID->getSessionValue());
                $this->servisPjeseServisID->OldValue = $this->servisPjeseServisID->CurrentValue;
                $this->servisPjeseServisID->ViewValue = $this->servisPjeseServisID->CurrentValue;
                $this->servisPjeseServisID->ViewValue = FormatNumber($this->servisPjeseServisID->ViewValue, $this->servisPjeseServisID->formatPattern());
                $this->servisPjeseServisID->ViewCustomAttributes = "";
            } else {
                $this->servisPjeseServisID->EditValue = HtmlEncode($this->servisPjeseServisID->CurrentValue);
                $this->servisPjeseServisID->PlaceHolder = RemoveHtml($this->servisPjeseServisID->caption());
                if (strval($this->servisPjeseServisID->EditValue) != "" && is_numeric($this->servisPjeseServisID->EditValue)) {
                    $this->servisPjeseServisID->EditValue = FormatNumber($this->servisPjeseServisID->EditValue, null);
                    $this->servisPjeseServisID->OldValue = $this->servisPjeseServisID->EditValue;
                }
            }

            // servisPjesePjesa
            $this->servisPjesePjesa->setupEditAttributes();
            $this->servisPjesePjesa->EditCustomAttributes = "";
            $curVal = trim(strval($this->servisPjesePjesa->CurrentValue));
            if ($curVal != "") {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->lookupCacheOption($curVal);
            } else {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->Lookup !== null && is_array($this->servisPjesePjesa->lookupOptions()) ? $curVal : null;
            }
            if ($this->servisPjesePjesa->ViewValue !== null) { // Load from cache
                $this->servisPjesePjesa->EditValue = array_values($this->servisPjesePjesa->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`pjeseID`" . SearchString("=", $this->servisPjesePjesa->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->servisPjesePjesa->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->servisPjesePjesa->EditValue = $arwrk;
            }
            $this->servisPjesePjesa->PlaceHolder = RemoveHtml($this->servisPjesePjesa->caption());

            // servisPjeseSasia
            $this->servisPjeseSasia->setupEditAttributes();
            $this->servisPjeseSasia->EditCustomAttributes = "";
            $this->servisPjeseSasia->EditValue = HtmlEncode($this->servisPjeseSasia->CurrentValue);
            $this->servisPjeseSasia->PlaceHolder = RemoveHtml($this->servisPjeseSasia->caption());
            if (strval($this->servisPjeseSasia->EditValue) != "" && is_numeric($this->servisPjeseSasia->EditValue)) {
                $this->servisPjeseSasia->EditValue = FormatNumber($this->servisPjeseSasia->EditValue, null);
                $this->servisPjeseSasia->OldValue = $this->servisPjeseSasia->EditValue;
            }

            // servisPjeseCmimi
            $this->servisPjeseCmimi->setupEditAttributes();
            $this->servisPjeseCmimi->EditCustomAttributes = "";
            $this->servisPjeseCmimi->EditValue = HtmlEncode($this->servisPjeseCmimi->CurrentValue);
            $this->servisPjeseCmimi->PlaceHolder = RemoveHtml($this->servisPjeseCmimi->caption());
            if (strval($this->servisPjeseCmimi->EditValue) != "" && is_numeric($this->servisPjeseCmimi->EditValue)) {
                $this->servisPjeseCmimi->EditValue = FormatNumber($this->servisPjeseCmimi->EditValue, null);
                $this->servisPjeseCmimi->OldValue = $this->servisPjeseCmimi->EditValue;
            }

            // servisPjeseShenim
            $this->servisPjeseShenim->setupEditAttributes();
            $this->servisPjeseShenim->EditCustomAttributes = "";
            if (!$this->servisPjeseShenim->Raw) {
                $this->servisPjeseShenim->CurrentValue = HtmlDecode($this->servisPjeseShenim->CurrentValue);
            }
            $this->servisPjeseShenim->EditValue = HtmlEncode($this->servisPjeseShenim->CurrentValue);
            $this->servisPjeseShenim->PlaceHolder = RemoveHtml($this->servisPjeseShenim->caption());

            // Add refer script

            // servisPjeseID
            $this->servisPjeseID->LinkCustomAttributes = "";
            $this->servisPjeseID->HrefValue = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->LinkCustomAttributes = "";
            $this->servisPjeseServisID->HrefValue = "";

            // servisPjesePjesa
            $this->servisPjesePjesa->LinkCustomAttributes = "";
            $this->servisPjesePjesa->HrefValue = "";

            // servisPjeseSasia
            $this->servisPjeseSasia->LinkCustomAttributes = "";
            $this->servisPjeseSasia->HrefValue = "";

            // servisPjeseCmimi
            $this->servisPjeseCmimi->LinkCustomAttributes = "";
            $this->servisPjeseCmimi->HrefValue = "";

            // servisPjeseShenim
            $this->servisPjeseShenim->LinkCustomAttributes = "";
            $this->servisPjeseShenim->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // servisPjeseID
            $this->servisPjeseID->setupEditAttributes();
            $this->servisPjeseID->EditCustomAttributes = "";
            $this->servisPjeseID->EditValue = $this->servisPjeseID->CurrentValue;
            $this->servisPjeseID->ViewCustomAttributes = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->setupEditAttributes();
            $this->servisPjeseServisID->EditCustomAttributes = "";
            if ($this->servisPjeseServisID->getSessionValue() != "") {
                $this->servisPjeseServisID->CurrentValue = GetForeignKeyValue($this->servisPjeseServisID->getSessionValue());
                $this->servisPjeseServisID->OldValue = $this->servisPjeseServisID->CurrentValue;
                $this->servisPjeseServisID->ViewValue = $this->servisPjeseServisID->CurrentValue;
                $this->servisPjeseServisID->ViewValue = FormatNumber($this->servisPjeseServisID->ViewValue, $this->servisPjeseServisID->formatPattern());
                $this->servisPjeseServisID->ViewCustomAttributes = "";
            } else {
                $this->servisPjeseServisID->EditValue = HtmlEncode($this->servisPjeseServisID->CurrentValue);
                $this->servisPjeseServisID->PlaceHolder = RemoveHtml($this->servisPjeseServisID->caption());
                if (strval($this->servisPjeseServisID->EditValue) != "" && is_numeric($this->servisPjeseServisID->EditValue)) {
                    $this->servisPjeseServisID->EditValue = FormatNumber($this->servisPjeseServisID->EditValue, null);
                    $this->servisPjeseServisID->OldValue = $this->servisPjeseServisID->EditValue;
                }
            }

            // servisPjesePjesa
            $this->servisPjesePjesa->setupEditAttributes();
            $this->servisPjesePjesa->EditCustomAttributes = "";
            $curVal = trim(strval($this->servisPjesePjesa->CurrentValue));
            if ($curVal != "") {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->lookupCacheOption($curVal);
            } else {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->Lookup !== null && is_array($this->servisPjesePjesa->lookupOptions()) ? $curVal : null;
            }
            if ($this->servisPjesePjesa->ViewValue !== null) { // Load from cache
                $this->servisPjesePjesa->EditValue = array_values($this->servisPjesePjesa->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`pjeseID`" . SearchString("=", $this->servisPjesePjesa->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->servisPjesePjesa->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->servisPjesePjesa->EditValue = $arwrk;
            }
            $this->servisPjesePjesa->PlaceHolder = RemoveHtml($this->servisPjesePjesa->caption());

            // servisPjeseSasia
            $this->servisPjeseSasia->setupEditAttributes();
            $this->servisPjeseSasia->EditCustomAttributes = "";
            $this->servisPjeseSasia->EditValue = HtmlEncode($this->servisPjeseSasia->CurrentValue);
            $this->servisPjeseSasia->PlaceHolder = RemoveHtml($this->servisPjeseSasia->caption());
            if (strval($this->servisPjeseSasia->EditValue) != "" && is_numeric($this->servisPjeseSasia->EditValue)) {
                $this->servisPjeseSasia->EditValue = FormatNumber($this->servisPjeseSasia->EditValue, null);
                $this->servisPjeseSasia->OldValue = $this->servisPjeseSasia->EditValue;
            }

            // servisPjeseCmimi
            $this->servisPjeseCmimi->setupEditAttributes();
            $this->servisPjeseCmimi->EditCustomAttributes = "";
            $this->servisPjeseCmimi->EditValue = HtmlEncode($this->servisPjeseCmimi->CurrentValue);
            $this->servisPjeseCmimi->PlaceHolder = RemoveHtml($this->servisPjeseCmimi->caption());
            if (strval($this->servisPjeseCmimi->EditValue) != "" && is_numeric($this->servisPjeseCmimi->EditValue)) {
                $this->servisPjeseCmimi->EditValue = FormatNumber($this->servisPjeseCmimi->EditValue, null);
                $this->servisPjeseCmimi->OldValue = $this->servisPjeseCmimi->EditValue;
            }

            // servisPjeseShenim
            $this->servisPjeseShenim->setupEditAttributes();
            $this->servisPjeseShenim->EditCustomAttributes = "";
            if (!$this->servisPjeseShenim->Raw) {
                $this->servisPjeseShenim->CurrentValue = HtmlDecode($this->servisPjeseShenim->CurrentValue);
            }
            $this->servisPjeseShenim->EditValue = HtmlEncode($this->servisPjeseShenim->CurrentValue);
            $this->servisPjeseShenim->PlaceHolder = RemoveHtml($this->servisPjeseShenim->caption());

            // Edit refer script

            // servisPjeseID
            $this->servisPjeseID->LinkCustomAttributes = "";
            $this->servisPjeseID->HrefValue = "";

            // servisPjeseServisID
            $this->servisPjeseServisID->LinkCustomAttributes = "";
            $this->servisPjeseServisID->HrefValue = "";

            // servisPjesePjesa
            $this->servisPjesePjesa->LinkCustomAttributes = "";
            $this->servisPjesePjesa->HrefValue = "";

            // servisPjeseSasia
            $this->servisPjeseSasia->LinkCustomAttributes = "";
            $this->servisPjeseSasia->HrefValue = "";

            // servisPjeseCmimi
            $this->servisPjeseCmimi->LinkCustomAttributes = "";
            $this->servisPjeseCmimi->HrefValue = "";

            // servisPjeseShenim
            $this->servisPjeseShenim->LinkCustomAttributes = "";
            $this->servisPjeseShenim->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_AGGREGATEINIT) { // Initialize aggregate row
                    $this->servisPjeseCmimi->Total = 0; // Initialize total
        } elseif ($this->RowType == ROWTYPE_AGGREGATE) { // Aggregate row
            $this->servisPjeseCmimi->CurrentValue = $this->servisPjeseCmimi->Total;
            $this->servisPjeseCmimi->ViewValue = $this->servisPjeseCmimi->CurrentValue;
            $this->servisPjeseCmimi->ViewValue = FormatNumber($this->servisPjeseCmimi->ViewValue, $this->servisPjeseCmimi->formatPattern());
            $this->servisPjeseCmimi->ViewCustomAttributes = "";
            $this->servisPjeseCmimi->HrefValue = ""; // Clear href value
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
        if ($this->servisPjeseID->Required) {
            if (!$this->servisPjeseID->IsDetailKey && EmptyValue($this->servisPjeseID->FormValue)) {
                $this->servisPjeseID->addErrorMessage(str_replace("%s", $this->servisPjeseID->caption(), $this->servisPjeseID->RequiredErrorMessage));
            }
        }
        if ($this->servisPjeseServisID->Required) {
            if (!$this->servisPjeseServisID->IsDetailKey && EmptyValue($this->servisPjeseServisID->FormValue)) {
                $this->servisPjeseServisID->addErrorMessage(str_replace("%s", $this->servisPjeseServisID->caption(), $this->servisPjeseServisID->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->servisPjeseServisID->FormValue)) {
            $this->servisPjeseServisID->addErrorMessage($this->servisPjeseServisID->getErrorMessage(false));
        }
        if ($this->servisPjesePjesa->Required) {
            if (!$this->servisPjesePjesa->IsDetailKey && EmptyValue($this->servisPjesePjesa->FormValue)) {
                $this->servisPjesePjesa->addErrorMessage(str_replace("%s", $this->servisPjesePjesa->caption(), $this->servisPjesePjesa->RequiredErrorMessage));
            }
        }
        if ($this->servisPjeseSasia->Required) {
            if (!$this->servisPjeseSasia->IsDetailKey && EmptyValue($this->servisPjeseSasia->FormValue)) {
                $this->servisPjeseSasia->addErrorMessage(str_replace("%s", $this->servisPjeseSasia->caption(), $this->servisPjeseSasia->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->servisPjeseSasia->FormValue)) {
            $this->servisPjeseSasia->addErrorMessage($this->servisPjeseSasia->getErrorMessage(false));
        }
        if ($this->servisPjeseCmimi->Required) {
            if (!$this->servisPjeseCmimi->IsDetailKey && EmptyValue($this->servisPjeseCmimi->FormValue)) {
                $this->servisPjeseCmimi->addErrorMessage(str_replace("%s", $this->servisPjeseCmimi->caption(), $this->servisPjeseCmimi->RequiredErrorMessage));
            }
        }
        if (!CheckNumber($this->servisPjeseCmimi->FormValue)) {
            $this->servisPjeseCmimi->addErrorMessage($this->servisPjeseCmimi->getErrorMessage(false));
        }
        if ($this->servisPjeseShenim->Required) {
            if (!$this->servisPjeseShenim->IsDetailKey && EmptyValue($this->servisPjeseShenim->FormValue)) {
                $this->servisPjeseShenim->addErrorMessage(str_replace("%s", $this->servisPjeseShenim->caption(), $this->servisPjeseShenim->RequiredErrorMessage));
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

    // Delete records based on current filter
    protected function deleteRows()
    {
        global $Language, $Security;
        if (!$Security->canDelete()) {
            $this->setFailureMessage($Language->phrase("NoDeletePermission")); // No delete permission
            return false;
        }
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $rows = $conn->fetchAllAssociative($sql);
        if (count($rows) == 0) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
            return false;
        }

        // Clone old rows
        $rsold = $rows;
        $successKeys = [];
        $failKeys = [];
        foreach ($rsold as $row) {
            $thisKey = "";
            if ($thisKey != "") {
                $thisKey .= Config("COMPOSITE_KEY_SEPARATOR");
            }
            $thisKey .= $row['servisPjeseID'];

            // Call row deleting event
            $deleteRow = $this->rowDeleting($row);
            if ($deleteRow) { // Delete
                $deleteRow = $this->delete($row);
            }
            if ($deleteRow === false) {
                if ($this->UseTransaction) {
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $thisKey;
            } else {
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($row);
                }

                // Call Row Deleted event
                $this->rowDeleted($row);
                $successKeys[] = $thisKey;
            }
        }

        // Any records deleted
        $deleteRows = count($successKeys) > 0;
        if (!$deleteRows) {
            // Set up error message
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("DeleteCancelled"));
            }
        }

        // Write JSON for API request
        if (IsApi() && $deleteRows) {
            $row = $this->getRecordsFromRecordset($rsold);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $deleteRows;
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

        // servisPjeseServisID
        if ($this->servisPjeseServisID->getSessionValue() != "") {
            $this->servisPjeseServisID->ReadOnly = true;
        }
        $this->servisPjeseServisID->setDbValueDef($rsnew, $this->servisPjeseServisID->CurrentValue, 0, $this->servisPjeseServisID->ReadOnly);

        // servisPjesePjesa
        $this->servisPjesePjesa->setDbValueDef($rsnew, $this->servisPjesePjesa->CurrentValue, 0, $this->servisPjesePjesa->ReadOnly);

        // servisPjeseSasia
        $this->servisPjeseSasia->setDbValueDef($rsnew, $this->servisPjeseSasia->CurrentValue, 0, $this->servisPjeseSasia->ReadOnly);

        // servisPjeseCmimi
        $this->servisPjeseCmimi->setDbValueDef($rsnew, $this->servisPjeseCmimi->CurrentValue, 0, $this->servisPjeseCmimi->ReadOnly);

        // servisPjeseShenim
        $this->servisPjeseShenim->setDbValueDef($rsnew, $this->servisPjeseShenim->CurrentValue, null, $this->servisPjeseShenim->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check referential integrity for master table 'servis'
        $detailKeys = [];
        $keyValue = $rsnew['servisPjeseServisID'] ?? $rsold['servisPjeseServisID'];
        $detailKeys['servisPjeseServisID'] = $keyValue;
        $masterTable = Container("servis");
        $masterFilter = $this->getMasterFilter($masterTable, $detailKeys);
        if (!EmptyValue($masterFilter)) {
            $rsmaster = $masterTable->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        } else { // Allow null value if not required field
            $validMasterRecord = $masterFilter === null;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "servis", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
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

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set up foreign key field value from Session
        if ($this->getCurrentMasterTable() == "servis") {
            $this->servisPjeseServisID->CurrentValue = $this->servisPjeseServisID->getSessionValue();
        }

        // Set new row
        $rsnew = [];

        // servisPjeseServisID
        $this->servisPjeseServisID->setDbValueDef($rsnew, $this->servisPjeseServisID->CurrentValue, 0, false);

        // servisPjesePjesa
        $this->servisPjesePjesa->setDbValueDef($rsnew, $this->servisPjesePjesa->CurrentValue, 0, false);

        // servisPjeseSasia
        $this->servisPjeseSasia->setDbValueDef($rsnew, $this->servisPjeseSasia->CurrentValue, 0, false);

        // servisPjeseCmimi
        $this->servisPjeseCmimi->setDbValueDef($rsnew, $this->servisPjeseCmimi->CurrentValue, 0, false);

        // servisPjeseShenim
        $this->servisPjeseShenim->setDbValueDef($rsnew, $this->servisPjeseShenim->CurrentValue, null, false);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Check referential integrity for master table 'servis_pjeset'
        $validMasterRecord = true;
        $detailKeys = [];
        $detailKeys["servisPjeseServisID"] = $this->servisPjeseServisID->CurrentValue;
        $masterTable = Container("servis");
        $masterFilter = $this->getMasterFilter($masterTable, $detailKeys);
        if (!EmptyValue($masterFilter)) {
            $rsmaster = $masterTable->loadRs($masterFilter)->fetch();
            $validMasterRecord = $rsmaster !== false;
        } else { // Allow null value if not required field
            $validMasterRecord = $masterFilter === null;
        }
        if (!$validMasterRecord) {
            $relatedRecordMsg = str_replace("%t", "servis", $Language->phrase("RelatedRecordRequired"));
            $this->setFailureMessage($relatedRecordMsg);
            return false;
        }
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
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
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Set up master/detail based on QueryString
    protected function setupMasterParms()
    {
        // Hide foreign keys
        $masterTblVar = $this->getCurrentMasterTable();
        if ($masterTblVar == "servis") {
            $masterTbl = Container("servis");
            $this->servisPjeseServisID->Visible = false;
            if ($masterTbl->EventCancelled) {
                $this->EventCancelled = true;
            }
        }
        $this->DbMasterFilter = $this->getMasterFilterFromSession(); // Get master filter from session
        $this->DbDetailFilter = $this->getDetailFilterFromSession(); // Get detail filter from session
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
                case "x_servisPjesePjesa":
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
}
