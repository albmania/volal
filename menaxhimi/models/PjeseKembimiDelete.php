<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class PjeseKembimiDelete extends PjeseKembimi
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'pjese_kembimi';

    // Page object name
    public $PageObjName = "PjeseKembimiDelete";

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

        // Table object (pjese_kembimi)
        if (!isset($GLOBALS["pjese_kembimi"]) || get_class($GLOBALS["pjese_kembimi"]) == PROJECT_NAMESPACE . "pjese_kembimi") {
            $GLOBALS["pjese_kembimi"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'pjese_kembimi');
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
                $tbl = Container("pjese_kembimi");
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
            $key .= @$ar['pjeseID'];
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
            $this->pjeseID->Visible = false;
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
        $this->pjeseID->setVisibility();
        $this->pjeseGjendja->setVisibility();
        $this->pjeseKodiVolvo->setVisibility();
        $this->pjeseKodiProdhuesi->setVisibility();
        $this->pjeseProdhuesi->setVisibility();
        $this->pjesePerMarke->setVisibility();
        $this->pjesePerModel->setVisibility();
        $this->pjesePerVitProdhimi->setVisibility();
        $this->pjeseCmimBlerje->setVisibility();
        $this->pjeseCmimShitje->setVisibility();
        $this->pjeseAutori->setVisibility();
        $this->pjeseShtuar->setVisibility();
        $this->pjeseModifikuar->setVisibility();
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
        $this->setupLookupOptions($this->pjeseGjendja);
        $this->setupLookupOptions($this->pjeseAutori);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("PjeseKembimiList"); // Prevent SQL injection, return to list
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
            $this->CurrentAction = "show"; // Display record
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
                $this->CurrentAction = "show"; // Display record
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
                $this->terminate("PjeseKembimiList"); // Return to list
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
        $this->pjeseID->setDbValue($row['pjeseID']);
        $this->pjeseGjendja->setDbValue($row['pjeseGjendja']);
        $this->pjeseKodiVolvo->setDbValue($row['pjeseKodiVolvo']);
        $this->pjeseKodiProdhuesi->setDbValue($row['pjeseKodiProdhuesi']);
        $this->pjeseProdhuesi->setDbValue($row['pjeseProdhuesi']);
        $this->pjesePerMarke->setDbValue($row['pjesePerMarke']);
        $this->pjesePerModel->setDbValue($row['pjesePerModel']);
        $this->pjesePerVitProdhimi->setDbValue($row['pjesePerVitProdhimi']);
        $this->pjeseCmimBlerje->setDbValue($row['pjeseCmimBlerje']);
        $this->pjeseCmimShitje->setDbValue($row['pjeseCmimShitje']);
        $this->pjeseAutori->setDbValue($row['pjeseAutori']);
        $this->pjeseShtuar->setDbValue($row['pjeseShtuar']);
        $this->pjeseModifikuar->setDbValue($row['pjeseModifikuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['pjeseID'] = $this->pjeseID->DefaultValue;
        $row['pjeseGjendja'] = $this->pjeseGjendja->DefaultValue;
        $row['pjeseKodiVolvo'] = $this->pjeseKodiVolvo->DefaultValue;
        $row['pjeseKodiProdhuesi'] = $this->pjeseKodiProdhuesi->DefaultValue;
        $row['pjeseProdhuesi'] = $this->pjeseProdhuesi->DefaultValue;
        $row['pjesePerMarke'] = $this->pjesePerMarke->DefaultValue;
        $row['pjesePerModel'] = $this->pjesePerModel->DefaultValue;
        $row['pjesePerVitProdhimi'] = $this->pjesePerVitProdhimi->DefaultValue;
        $row['pjeseCmimBlerje'] = $this->pjeseCmimBlerje->DefaultValue;
        $row['pjeseCmimShitje'] = $this->pjeseCmimShitje->DefaultValue;
        $row['pjeseAutori'] = $this->pjeseAutori->DefaultValue;
        $row['pjeseShtuar'] = $this->pjeseShtuar->DefaultValue;
        $row['pjeseModifikuar'] = $this->pjeseModifikuar->DefaultValue;
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

        // pjeseID

        // pjeseGjendja

        // pjeseKodiVolvo

        // pjeseKodiProdhuesi

        // pjeseProdhuesi

        // pjesePerMarke

        // pjesePerModel

        // pjesePerVitProdhimi

        // pjeseCmimBlerje

        // pjeseCmimShitje

        // pjeseAutori

        // pjeseShtuar

        // pjeseModifikuar

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // pjeseID
            $this->pjeseID->ViewValue = $this->pjeseID->CurrentValue;
            $this->pjeseID->ViewCustomAttributes = "";

            // pjeseGjendja
            if (strval($this->pjeseGjendja->CurrentValue) != "") {
                $this->pjeseGjendja->ViewValue = $this->pjeseGjendja->optionCaption($this->pjeseGjendja->CurrentValue);
            } else {
                $this->pjeseGjendja->ViewValue = null;
            }
            $this->pjeseGjendja->ViewCustomAttributes = "";

            // pjeseKodiVolvo
            $this->pjeseKodiVolvo->ViewValue = $this->pjeseKodiVolvo->CurrentValue;
            $this->pjeseKodiVolvo->ViewCustomAttributes = "";

            // pjeseKodiProdhuesi
            $this->pjeseKodiProdhuesi->ViewValue = $this->pjeseKodiProdhuesi->CurrentValue;
            $this->pjeseKodiProdhuesi->ViewCustomAttributes = "";

            // pjeseProdhuesi
            $this->pjeseProdhuesi->ViewValue = $this->pjeseProdhuesi->CurrentValue;
            $this->pjeseProdhuesi->ViewCustomAttributes = "";

            // pjesePerMarke
            $this->pjesePerMarke->ViewValue = $this->pjesePerMarke->CurrentValue;
            $this->pjesePerMarke->ViewCustomAttributes = "";

            // pjesePerModel
            $this->pjesePerModel->ViewValue = $this->pjesePerModel->CurrentValue;
            $this->pjesePerModel->ViewCustomAttributes = "";

            // pjesePerVitProdhimi
            $this->pjesePerVitProdhimi->ViewValue = $this->pjesePerVitProdhimi->CurrentValue;
            $this->pjesePerVitProdhimi->ViewCustomAttributes = "";

            // pjeseCmimBlerje
            $this->pjeseCmimBlerje->ViewValue = $this->pjeseCmimBlerje->CurrentValue;
            $this->pjeseCmimBlerje->ViewValue = FormatNumber($this->pjeseCmimBlerje->ViewValue, $this->pjeseCmimBlerje->formatPattern());
            $this->pjeseCmimBlerje->ViewCustomAttributes = "";

            // pjeseCmimShitje
            $this->pjeseCmimShitje->ViewValue = $this->pjeseCmimShitje->CurrentValue;
            $this->pjeseCmimShitje->ViewValue = FormatNumber($this->pjeseCmimShitje->ViewValue, $this->pjeseCmimShitje->formatPattern());
            $this->pjeseCmimShitje->ViewCustomAttributes = "";

            // pjeseAutori
            $this->pjeseAutori->ViewValue = $this->pjeseAutori->CurrentValue;
            $curVal = strval($this->pjeseAutori->CurrentValue);
            if ($curVal != "") {
                $this->pjeseAutori->ViewValue = $this->pjeseAutori->lookupCacheOption($curVal);
                if ($this->pjeseAutori->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->pjeseAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->pjeseAutori->Lookup->renderViewRow($rswrk[0]);
                        $this->pjeseAutori->ViewValue = $this->pjeseAutori->displayValue($arwrk);
                    } else {
                        $this->pjeseAutori->ViewValue = FormatNumber($this->pjeseAutori->CurrentValue, $this->pjeseAutori->formatPattern());
                    }
                }
            } else {
                $this->pjeseAutori->ViewValue = null;
            }
            $this->pjeseAutori->ViewCustomAttributes = "";

            // pjeseShtuar
            $this->pjeseShtuar->ViewValue = $this->pjeseShtuar->CurrentValue;
            $this->pjeseShtuar->ViewValue = FormatDateTime($this->pjeseShtuar->ViewValue, $this->pjeseShtuar->formatPattern());
            $this->pjeseShtuar->ViewCustomAttributes = "";

            // pjeseModifikuar
            $this->pjeseModifikuar->ViewValue = $this->pjeseModifikuar->CurrentValue;
            $this->pjeseModifikuar->ViewValue = FormatDateTime($this->pjeseModifikuar->ViewValue, $this->pjeseModifikuar->formatPattern());
            $this->pjeseModifikuar->ViewCustomAttributes = "";

            // pjeseID
            $this->pjeseID->LinkCustomAttributes = "";
            $this->pjeseID->HrefValue = "";
            $this->pjeseID->TooltipValue = "";

            // pjeseGjendja
            $this->pjeseGjendja->LinkCustomAttributes = "";
            $this->pjeseGjendja->HrefValue = "";
            $this->pjeseGjendja->TooltipValue = "";

            // pjeseKodiVolvo
            $this->pjeseKodiVolvo->LinkCustomAttributes = "";
            $this->pjeseKodiVolvo->HrefValue = "";
            $this->pjeseKodiVolvo->TooltipValue = "";

            // pjeseKodiProdhuesi
            $this->pjeseKodiProdhuesi->LinkCustomAttributes = "";
            $this->pjeseKodiProdhuesi->HrefValue = "";
            $this->pjeseKodiProdhuesi->TooltipValue = "";

            // pjeseProdhuesi
            $this->pjeseProdhuesi->LinkCustomAttributes = "";
            $this->pjeseProdhuesi->HrefValue = "";
            $this->pjeseProdhuesi->TooltipValue = "";

            // pjesePerMarke
            $this->pjesePerMarke->LinkCustomAttributes = "";
            $this->pjesePerMarke->HrefValue = "";
            $this->pjesePerMarke->TooltipValue = "";

            // pjesePerModel
            $this->pjesePerModel->LinkCustomAttributes = "";
            $this->pjesePerModel->HrefValue = "";
            $this->pjesePerModel->TooltipValue = "";

            // pjesePerVitProdhimi
            $this->pjesePerVitProdhimi->LinkCustomAttributes = "";
            $this->pjesePerVitProdhimi->HrefValue = "";
            $this->pjesePerVitProdhimi->TooltipValue = "";

            // pjeseCmimBlerje
            $this->pjeseCmimBlerje->LinkCustomAttributes = "";
            $this->pjeseCmimBlerje->HrefValue = "";
            $this->pjeseCmimBlerje->TooltipValue = "";

            // pjeseCmimShitje
            $this->pjeseCmimShitje->LinkCustomAttributes = "";
            $this->pjeseCmimShitje->HrefValue = "";
            $this->pjeseCmimShitje->TooltipValue = "";

            // pjeseAutori
            $this->pjeseAutori->LinkCustomAttributes = "";
            $this->pjeseAutori->HrefValue = "";
            $this->pjeseAutori->TooltipValue = "";

            // pjeseShtuar
            $this->pjeseShtuar->LinkCustomAttributes = "";
            $this->pjeseShtuar->HrefValue = "";
            $this->pjeseShtuar->TooltipValue = "";

            // pjeseModifikuar
            $this->pjeseModifikuar->LinkCustomAttributes = "";
            $this->pjeseModifikuar->HrefValue = "";
            $this->pjeseModifikuar->TooltipValue = "";
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
            $thisKey .= $row['pjeseID'];

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PjeseKembimiList"), "", $this->TableVar, true);
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
                case "x_pjeseGjendja":
                    break;
                case "x_pjeseAutori":
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
