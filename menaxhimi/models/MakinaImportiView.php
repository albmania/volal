<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaImportiView extends MakinaImporti
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina_importi';

    // Page object name
    public $PageObjName = "MakinaImportiView";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

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
        $pageUrl = $this->pageUrl();
        if (($keyValue = Get("mimpID") ?? Route("mimpID")) !== null) {
            $this->RecKey["mimpID"] = $keyValue;
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

        // Export options
        $this->ExportOptions = new ListOptions(["TagClassName" => "ew-export-option"]);

        // Other options
        if (!$this->OtherOptions) {
            $this->OtherOptions = new ListOptionsArray();
        }

        // Detail tables
        $this->OtherOptions["detail"] = new ListOptions(["TagClassName" => "ew-detail-option"]);
        // Actions
        $this->OtherOptions["action"] = new ListOptions(["TagClassName" => "ew-action-option"]);
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
    public $ExportOptions; // Export options
    public $OtherOptions; // Other options
    public $DisplayRecords = 1;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecKey = [];
    public $IsModal = false;

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

        // Load current record
        $loadCurrentRecord = false;
        $returnUrl = "";
        $matchRecord = false;
        if ($this->isPageRequest()) { // Validate request
            if (($keyValue = Get("mimpID") ?? Route("mimpID")) !== null) {
                $this->mimpID->setQueryStringValue($keyValue);
                $this->RecKey["mimpID"] = $this->mimpID->QueryStringValue;
            } elseif (Post("mimpID") !== null) {
                $this->mimpID->setFormValue(Post("mimpID"));
                $this->RecKey["mimpID"] = $this->mimpID->FormValue;
            } elseif (IsApi() && ($keyValue = Key(0) ?? Route(2)) !== null) {
                $this->mimpID->setQueryStringValue($keyValue);
                $this->RecKey["mimpID"] = $this->mimpID->QueryStringValue;
            } elseif (!$loadCurrentRecord) {
                $returnUrl = "MakinaImportiList"; // Return to list
            }

            // Get action
            $this->CurrentAction = "show"; // Display
            switch ($this->CurrentAction) {
                case "show": // Get a record to display

                        // Load record based on key
                        if (IsApi()) {
                            $filter = $this->getRecordFilter();
                            $this->CurrentFilter = $filter;
                            $sql = $this->getCurrentSql();
                            $conn = $this->getConnection();
                            $this->Recordset = LoadRecordset($sql, $conn);
                            $res = $this->Recordset && !$this->Recordset->EOF;
                        } else {
                            $res = $this->loadRow();
                        }
                        if (!$res) { // Load record based on key
                            if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "") {
                                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                            }
                            $returnUrl = "MakinaImportiList"; // No matching record, return to list
                        }
                    break;
            }
        } else {
            $returnUrl = "MakinaImportiList"; // Not page request, return to list
        }
        if ($returnUrl != "") {
            $this->terminate($returnUrl);
            return;
        }

        // Set up Breadcrumb
        if (!$this->isExport()) {
            $this->setupBreadcrumb();
        }

        // Render row
        $this->RowType = ROWTYPE_VIEW;
        $this->resetAttributes();
        $this->renderRow();

        // Set up detail parameters
        $this->setupDetailParms();

        // Normal return
        if (IsApi()) {
            $rows = $this->getRecordsFromRecordset($this->Recordset, true); // Get current record only
            $this->Recordset->close();
            WriteJson(["success" => true, $this->TableVar => $rows]);
            $this->terminate(true);
            return;
        }

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

    // Set up other options
    protected function setupOtherOptions()
    {
        global $Language, $Security;
        $options = &$this->OtherOptions;
        $option = $options["action"];

        // Add
        $item = &$option->add("add");
        $addcaption = HtmlTitle($Language->phrase("ViewPageAddLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-add\" title=\"" . $addcaption . "\" data-caption=\"" . $addcaption . "\" href=\"" . HtmlEncode(GetUrl($this->AddUrl)) . "\">" . $Language->phrase("ViewPageAddLink") . "</a>";
        }
        $item->Visible = ($this->AddUrl != "" && $Security->canAdd());

        // Edit
        $item = &$option->add("edit");
        $editcaption = HtmlTitle($Language->phrase("ViewPageEditLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-edit\" title=\"" . $editcaption . "\" data-caption=\"" . $editcaption . "\" href=\"" . HtmlEncode(GetUrl($this->EditUrl)) . "\">" . $Language->phrase("ViewPageEditLink") . "</a>";
        }
        $item->Visible = ($this->EditUrl != "" && $Security->canEdit());

        // Delete
        $item = &$option->add("delete");
        $item->Body = "<a data-ew-action=\"inline-delete\" class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        $item->Visible = ($this->DeleteUrl != "" && $Security->canDelete());
        $option = $options["detail"];
        $detailTableLink = "";
        $detailViewTblVar = "";
        $detailCopyTblVar = "";
        $detailEditTblVar = "";

        // "detail_makina_importi_sherbime"
        $item = &$option->add("detail_makina_importi_sherbime");
        $body = $Language->phrase("ViewPageDetailLink") . $Language->TablePhrase("makina_importi_sherbime", "TblCaption");
        $body = "<a class=\"btn btn-default ew-row-link ew-detail\" data-action=\"list\" href=\"" . HtmlEncode(GetUrl("MakinaImportiSherbimeList?" . Config("TABLE_SHOW_MASTER") . "=makina_importi&" . GetForeignKeyUrl("fk_mimpID", $this->mimpID->CurrentValue) . "")) . "\">" . $body . "</a>";
        $links = "";
        $detailPageObj = Container("MakinaImportiSherbimeGrid");
        if ($detailPageObj->DetailView && $Security->canView() && $Security->allowView(CurrentProjectID() . 'makina_importi')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailViewLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=makina_importi_sherbime"))) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            if ($detailViewTblVar != "") {
                $detailViewTblVar .= ",";
            }
            $detailViewTblVar .= "makina_importi_sherbime";
        }
        if ($detailPageObj->DetailEdit && $Security->canEdit() && $Security->allowEdit(CurrentProjectID() . 'makina_importi')) {
            $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlTitle($Language->phrase("MasterDetailEditLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=makina_importi_sherbime"))) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
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
        $item->Body = $body;
        $item->Visible = $Security->allowList(CurrentProjectID() . 'makina_importi_sherbime');
        if ($item->Visible) {
            if ($detailTableLink != "") {
                $detailTableLink .= ",";
            }
            $detailTableLink .= "makina_importi_sherbime";
        }
        if ($this->ShowMultipleDetails) {
            $item->Visible = false;
        }

        // Multiple details
        if ($this->ShowMultipleDetails) {
            $body = "<div class=\"btn-group btn-group-sm ew-btn-group\">";
            $links = "";
            if ($detailViewTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-view\" data-action=\"view\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailViewLink", true)) . "\" href=\"" . HtmlEncode(GetUrl($this->getViewUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailViewTblVar))) . "\">" . $Language->phrase("MasterDetailViewLink", null) . "</a></li>";
            }
            if ($detailEditTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-edit\" data-action=\"edit\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailEditLink", true)) . "\" href=\"" . HtmlEncode(GetUrl($this->getEditUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailEditTblVar))) . "\">" . $Language->phrase("MasterDetailEditLink", null) . "</a></li>";
            }
            if ($detailCopyTblVar != "") {
                $links .= "<li><a class=\"dropdown-item ew-row-link ew-detail-copy\" data-action=\"add\" data-caption=\"" . HtmlEncode($Language->phrase("MasterDetailCopyLink", true)) . "\" href=\"" . HtmlEncode(GetUrl($this->getCopyUrl(Config("TABLE_SHOW_DETAIL") . "=" . $detailCopyTblVar))) . "\">" . $Language->phrase("MasterDetailCopyLink", null) . "</a></li>";
            }
            if ($links != "") {
                $body .= "<button class=\"dropdown-toggle btn btn-default ew-master-detail\" title=\"" . HtmlEncode($Language->phrase("MultipleMasterDetails", true)) . "\" data-bs-toggle=\"dropdown\">" . $Language->phrase("MultipleMasterDetails") . "</button>";
                $body .= "<ul class=\"dropdown-menu ew-menu\">" . $links . "</ul>";
            }
            $body .= "</div>";
            // Multiple details
            $item = &$option->add("details");
            $item->Body = $body;
        }

        // Set up detail default
        $option = $options["detail"];
        $options["detail"]->DropDownButtonPhrase = $Language->phrase("ButtonDetails");
        $ar = explode(",", $detailTableLink);
        $cnt = count($ar);
        $option->UseDropDownButton = ($cnt > 1);
        $option->UseButtonGroup = true;
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;

        // Set up action default
        $option = $options["action"];
        $option->DropDownButtonPhrase = $Language->phrase("ButtonActions");
        $option->UseDropDownButton = false;
        $option->UseButtonGroup = true;
        $item = &$option->addGroupOption();
        $item->Body = "";
        $item->Visible = false;
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

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs
        $this->AddUrl = $this->getAddUrl();
        $this->EditUrl = $this->getEditUrl();
        $this->CopyUrl = $this->getCopyUrl();
        $this->DeleteUrl = $this->getDeleteUrl();
        $this->ListUrl = $this->getListUrl();
        $this->setupOtherOptions();

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

            // mimpKambio
            $this->mimpKambio->LinkCustomAttributes = "";
            $this->mimpKambio->HrefValue = "";
            $this->mimpKambio->TooltipValue = "";

            // mimpNgjyra
            $this->mimpNgjyra->LinkCustomAttributes = "";
            $this->mimpNgjyra->HrefValue = "";
            $this->mimpNgjyra->TooltipValue = "";

            // mimpPrejardhja
            $this->mimpPrejardhja->LinkCustomAttributes = "";
            $this->mimpPrejardhja->HrefValue = "";
            $this->mimpPrejardhja->TooltipValue = "";

            // mimpInfo
            $this->mimpInfo->LinkCustomAttributes = "";
            $this->mimpInfo->HrefValue = "";
            $this->mimpInfo->TooltipValue = "";

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
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
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
                if ($detailPageObj->DetailView) {
                    $detailPageObj->CurrentMode = "view";

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
        $pageId = "view";
        $Breadcrumb->add("view", $pageId, $url);
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
}
