<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaImportiDelete extends MakinaImporti
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina_importi';

    // Page object name
    public $PageObjName = "MakinaImportiDelete";

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
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $TotalRecords = 0;
    public $RecordCount;
    public $RecKeys = [];
    public $StartRowCount = 1;
    public $RowCount = 0;

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
        $this->CurrentAction = Param("action"); // Set up current action
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

        // Set up lookup cache
        $this->setupLookupOptions($this->mimpMarka);
        $this->setupLookupOptions($this->mimpModeli);
        $this->setupLookupOptions($this->mimpTipi);
        $this->setupLookupOptions($this->mimpKarburant);
        $this->setupLookupOptions($this->mimpKambio);
        $this->setupLookupOptions($this->mimpGati);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("MakinaImportiList"); // Prevent SQL injection, return to list
            return;
        }

        // Set up filter (WHERE Clause)
        $this->CurrentFilter = $filter;

        // Get action
        if (IsApi()) {
            $this->CurrentAction = "delete"; // Delete record directly
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action");
        } elseif (Get("action") == "1") {
            $this->CurrentAction = "delete"; // Delete record directly
        } else {
            $this->CurrentAction = "delete"; // Delete record directly
        }
        if ($this->isDelete()) {
            $this->SendEmail = true; // Send email on delete success
            if ($this->deleteRows()) { // Delete rows
                if ($this->getSuccessMessage() == "") {
                    $this->setSuccessMessage($Language->phrase("DeleteSuccess")); // Set up success message
                }
                if (IsApi()) {
                    $this->terminate(true);
                    return;
                } else {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                }
            } else { // Delete failed
                if (IsApi()) {
                    $this->terminate();
                    return;
                }
                $this->terminate($this->getReturnUrl()); // Return to caller
                return;
            }
        }
        if ($this->isShow()) { // Load records for display
            if ($this->Recordset = $this->loadRecordset()) {
                $this->TotalRecords = $this->Recordset->recordCount(); // Get record count
            }
            if ($this->TotalRecords <= 0) { // No record found, exit
                if ($this->Recordset) {
                    $this->Recordset->close();
                }
                $this->terminate("MakinaImportiList"); // Return to list
                return;
            }
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

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

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
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
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
        if ($this->UseTransaction) {
            $conn->beginTransaction();
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
            $thisKey .= $row['mimpID'];

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
        if ($deleteRows) {
            if ($this->UseTransaction) { // Commit transaction
                $conn->commit();
            }

            // Set warning message if delete some records failed
            if (count($failKeys) > 0) {
                $this->setWarningMessage(str_replace("%k", explode(", ", $failKeys), $Language->phrase("DeleteSomeRecordsFailed")));
            }
        } else {
            if ($this->UseTransaction) { // Rollback transaction
                $conn->rollback();
            }
        }

        // Write JSON for API request
        if (IsApi() && $deleteRows) {
            $row = $this->getRecordsFromRecordset($rsold);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $deleteRows;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MakinaImportiList"), "", $this->TableVar, true);
        $pageId = "delete";
        $Breadcrumb->add("delete", $pageId, $url);
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
