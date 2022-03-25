<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaShitjeView extends MakinaShitje
{
    use MessagesTrait;

    // Page ID
    public $PageID = "view";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina_shitje';

    // Page object name
    public $PageObjName = "MakinaShitjeView";

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

        // Table object (makina_shitje)
        if (!isset($GLOBALS["makina_shitje"]) || get_class($GLOBALS["makina_shitje"]) == PROJECT_NAMESPACE . "makina_shitje") {
            $GLOBALS["makina_shitje"] = &$this;
        }

        // Page URL
        $pageUrl = $this->pageUrl();
        if (($keyValue = Get("mshitjeID") ?? Route("mshitjeID")) !== null) {
            $this->RecKey["mshitjeID"] = $keyValue;
        }

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

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "MakinaShitjeView") {
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
        $this->mshitjeID->setVisibility();
        $this->mshitjeMarka->setVisibility();
        $this->mshitjeModeli->setVisibility();
        $this->mshitjeTipi->setVisibility();
        $this->mshitjeStruktura->setVisibility();
        $this->mshitjeKapacitetiMotorrit->setVisibility();
        $this->mshitjeVitiProdhimit->setVisibility();
        $this->mshitjeKarburant->setVisibility();
        $this->mshitjeNgjyra->setVisibility();
        $this->mshitjeNrVendeve->setVisibility();
        $this->mshitjeKambio->setVisibility();
        $this->mshitjePrejardhja->setVisibility();
        $this->mshitjeTargaAL->setVisibility();
        $this->mshitjeKilometra->setVisibility();
        $this->mshitjeFotografi->setVisibility();
        $this->mshitjePershkrimi->setVisibility();
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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }

        // Load current record
        $loadCurrentRecord = false;
        $returnUrl = "";
        $matchRecord = false;
        if ($this->isPageRequest()) { // Validate request
            if (($keyValue = Get("mshitjeID") ?? Route("mshitjeID")) !== null) {
                $this->mshitjeID->setQueryStringValue($keyValue);
                $this->RecKey["mshitjeID"] = $this->mshitjeID->QueryStringValue;
            } elseif (Post("mshitjeID") !== null) {
                $this->mshitjeID->setFormValue(Post("mshitjeID"));
                $this->RecKey["mshitjeID"] = $this->mshitjeID->FormValue;
            } elseif (IsApi() && ($keyValue = Key(0) ?? Route(2)) !== null) {
                $this->mshitjeID->setQueryStringValue($keyValue);
                $this->RecKey["mshitjeID"] = $this->mshitjeID->QueryStringValue;
            } elseif (!$loadCurrentRecord) {
                $returnUrl = "MakinaShitjeList"; // Return to list
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
                            $returnUrl = "MakinaShitjeList"; // No matching record, return to list
                        }
                    break;
            }
        } else {
            $returnUrl = "MakinaShitjeList"; // Not page request, return to list
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
        $item->Body = "<a data-ew-action=\"inline-delete\" class=\"ew-action ew-delete\" title=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" data-caption=\"" . HtmlTitle($Language->phrase("ViewPageDeleteLink")) . "\" href=\"" . HtmlEncode(GetUrl($this->DeleteUrl)) . "\">" . $Language->phrase("ViewPageDeleteLink") . "</a>";
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

            // mshitjeFotografi
            $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
            if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
                $this->mshitjeFotografi->ImageWidth = 100;
                $this->mshitjeFotografi->ImageHeight = 0;
                $this->mshitjeFotografi->ImageAlt = $this->mshitjeFotografi->alt();
                $this->mshitjeFotografi->ImageCssClass = "ew-image";
                $this->mshitjeFotografi->ViewValue = $this->mshitjeFotografi->Upload->DbValue;
            } else {
                $this->mshitjeFotografi->ViewValue = "";
            }
            $this->mshitjeFotografi->ViewCustomAttributes = "";

            // mshitjePershkrimi
            $this->mshitjePershkrimi->ViewValue = $this->mshitjePershkrimi->CurrentValue;
            $this->mshitjePershkrimi->ViewCustomAttributes = "";

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

            // mshitjeStruktura
            $this->mshitjeStruktura->LinkCustomAttributes = "";
            $this->mshitjeStruktura->HrefValue = "";
            $this->mshitjeStruktura->TooltipValue = "";

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

            // mshitjeNgjyra
            $this->mshitjeNgjyra->LinkCustomAttributes = "";
            $this->mshitjeNgjyra->HrefValue = "";
            $this->mshitjeNgjyra->TooltipValue = "";

            // mshitjeNrVendeve
            $this->mshitjeNrVendeve->LinkCustomAttributes = "";
            $this->mshitjeNrVendeve->HrefValue = "";
            $this->mshitjeNrVendeve->TooltipValue = "";

            // mshitjeKambio
            $this->mshitjeKambio->LinkCustomAttributes = "";
            $this->mshitjeKambio->HrefValue = "";
            $this->mshitjeKambio->TooltipValue = "";

            // mshitjePrejardhja
            $this->mshitjePrejardhja->LinkCustomAttributes = "";
            $this->mshitjePrejardhja->HrefValue = "";
            $this->mshitjePrejardhja->TooltipValue = "";

            // mshitjeTargaAL
            $this->mshitjeTargaAL->LinkCustomAttributes = "";
            $this->mshitjeTargaAL->HrefValue = "";
            $this->mshitjeTargaAL->TooltipValue = "";

            // mshitjeKilometra
            $this->mshitjeKilometra->LinkCustomAttributes = "";
            $this->mshitjeKilometra->HrefValue = "";
            $this->mshitjeKilometra->TooltipValue = "";

            // mshitjeFotografi
            $this->mshitjeFotografi->LinkCustomAttributes = "";
            $this->mshitjeFotografi->UploadPath = '../ngarkime/makina/';
            if (!EmptyValue($this->mshitjeFotografi->Upload->DbValue)) {
                $this->mshitjeFotografi->HrefValue = "%u"; // Add prefix/suffix
                $this->mshitjeFotografi->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->mshitjeFotografi->HrefValue = FullUrl($this->mshitjeFotografi->HrefValue, "href");
                }
            } else {
                $this->mshitjeFotografi->HrefValue = "";
            }
            $this->mshitjeFotografi->ExportHrefValue = $this->mshitjeFotografi->UploadPath . $this->mshitjeFotografi->Upload->DbValue;
            $this->mshitjeFotografi->TooltipValue = "";
            if ($this->mshitjeFotografi->UseColorbox) {
                if (EmptyValue($this->mshitjeFotografi->TooltipValue)) {
                    $this->mshitjeFotografi->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->mshitjeFotografi->LinkAttrs["data-rel"] = "makina_shitje_x_mshitjeFotografi";
                $this->mshitjeFotografi->LinkAttrs->appendClass("ew-lightbox");
            }

            // mshitjePershkrimi
            $this->mshitjePershkrimi->LinkCustomAttributes = "";
            $this->mshitjePershkrimi->HrefValue = "";
            $this->mshitjePershkrimi->TooltipValue = "";

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MakinaShitjeList"), "", $this->TableVar, true);
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
