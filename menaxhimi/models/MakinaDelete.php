<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MakinaDelete extends Makina
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'makina';

    // Page object name
    public $PageObjName = "MakinaDelete";

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

        // Table object (makina)
        if (!isset($GLOBALS["makina"]) || get_class($GLOBALS["makina"]) == PROJECT_NAMESPACE . "makina") {
            $GLOBALS["makina"] = &$this;
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

        // Set up lookup cache
        $this->setupLookupOptions($this->makinaKlienti);
        $this->setupLookupOptions($this->makinaKarburanti);
        $this->setupLookupOptions($this->makinaShiturVOLAL);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("MakinaList"); // Prevent SQL injection, return to list
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
                $this->terminate("MakinaList"); // Return to list
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
            $thisKey .= $row['makinaID'];

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MakinaList"), "", $this->TableVar, true);
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
