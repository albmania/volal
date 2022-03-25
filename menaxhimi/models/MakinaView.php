<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaView extends Makina
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina';

    // Page object name
    public $PageObjName = "MakinaView";

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

        // Table object (makina)
        if (!isset($GLOBALS["makina"]) || get_class($GLOBALS["makina"]) == PROJECT_NAMESPACE . "makina") {
            $GLOBALS["makina"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        if (($keyValue = Get("makinaID") ?? Route("makinaID")) !== null) {
            $this->RecKey["makinaID"] = $keyValue;
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

        // Set up lookup cache
        $this->setupLookupOptions($this->makinaKlienti);
        $this->setupLookupOptions($this->makinaKarburanti);
        $this->setupLookupOptions($this->makinaShiturVOLAL);

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }

        // Load current record
        $loadCurrentRecord = false;
        $returnUrl = "";
        $matchRecord = false;
        if ($this->isPageRequest()) { // Validate request
            if (($keyValue = Get("makinaID") ?? Route("makinaID")) !== null) {
                $this->makinaID->setQueryStringValue($keyValue);
                $this->RecKey["makinaID"] = $this->makinaID->QueryStringValue;
            } elseif (Post("makinaID") !== null) {
                $this->makinaID->setFormValue(Post("makinaID"));
                $this->RecKey["makinaID"] = $this->makinaID->FormValue;
            } elseif (IsApi() && ($keyValue = Key(0) ?? Route(2)) !== null) {
                $this->makinaID->setQueryStringValue($keyValue);
                $this->RecKey["makinaID"] = $this->makinaID->QueryStringValue;
            } elseif (!$loadCurrentRecord) {
                $returnUrl = "MakinaList"; // Return to list
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
                            $returnUrl = "MakinaList"; // No matching record, return to list
                        }
                    break;
            }
        } else {
            $returnUrl = "MakinaList"; // Not page request, return to list
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

        // Copy
        $item = &$option->add("copy");
        $copycaption = HtmlTitle($Language->phrase("ViewPageCopyLink"));
        if ($this->IsModal) {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" data-ew-action=\"modal\" data-url=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\" data-btn=\"AddBtn\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-copy\" title=\"" . $copycaption . "\" data-caption=\"" . $copycaption . "\" href=\"" . HtmlEncode(GetUrl($this->CopyUrl)) . "\">" . $Language->phrase("ViewPageCopyLink") . "</a>";
        }
        $item->Visible = ($this->CopyUrl != "" && $Security->canAdd());

        // Delete
        $item = &$option->add("delete");
        if ($this->IsModal) { // Handle as inline delete
            $item->Body = "<a data-ew-action=\"inline-delete\" class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(UrlAddQuery(GetUrl($this->DeleteUrl), "action=1")) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        } else {
            $item->Body = "<a class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
        }
        $item->Visible = ($this->DeleteUrl != "" && $Security->canDelete());

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

            // makinaNgjyra
            $this->makinaNgjyra->LinkCustomAttributes = "";
            $this->makinaNgjyra->HrefValue = "";
            $this->makinaNgjyra->TooltipValue = "";

            // makinaInfoShtese
            $this->makinaInfoShtese->LinkCustomAttributes = "";
            $this->makinaInfoShtese->HrefValue = "";
            $this->makinaInfoShtese->TooltipValue = "";

            // makinaVitiRegAL
            $this->makinaVitiRegAL->LinkCustomAttributes = "";
            $this->makinaVitiRegAL->HrefValue = "";
            $this->makinaVitiRegAL->TooltipValue = "";

            // makinaTarga
            $this->makinaTarga->LinkCustomAttributes = "";
            $this->makinaTarga->HrefValue = "";
            $this->makinaTarga->TooltipValue = "";

            // makinaNrShasie
            $this->makinaNrShasie->LinkCustomAttributes = "";
            $this->makinaNrShasie->HrefValue = "";
            $this->makinaNrShasie->TooltipValue = "";

            // makinaPrejardhja
            $this->makinaPrejardhja->LinkCustomAttributes = "";
            $this->makinaPrejardhja->HrefValue = "";
            $this->makinaPrejardhja->TooltipValue = "";

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
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MakinaList"), "", $this->TableVar, true);
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
