<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class MenuDytesoreDelete extends MenuDytesore
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'menu_dytesore';

    // Page object name
    public $PageObjName = "MenuDytesoreDelete";

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

        // Table object (menu_dytesore)
        if (!isset($GLOBALS["menu_dytesore"]) || get_class($GLOBALS["menu_dytesore"]) == PROJECT_NAMESPACE . "menu_dytesore") {
            $GLOBALS["menu_dytesore"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'menu_dytesore');
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
                $tbl = Container("menu_dytesore");
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
            $key .= @$ar['menudID'];
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
            $this->menudID->Visible = false;
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
        $this->menudID->setVisibility();
        $this->menudGjuha->setVisibility();
        $this->menudKryesore->setVisibility();
        $this->menudTitulli->setVisibility();
        $this->menudUrl->setVisibility();
        $this->menudBlank->setVisibility();
        $this->menudRadhe->setVisibility();
        $this->menudAktiv->setVisibility();
        $this->menudAutor->setVisibility();
        $this->menudKrijuar->setVisibility();
        $this->menudAzhornuar->setVisibility();
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
        $this->setupLookupOptions($this->menudGjuha);
        $this->setupLookupOptions($this->menudKryesore);
        $this->setupLookupOptions($this->menudBlank);
        $this->setupLookupOptions($this->menudAktiv);
        $this->setupLookupOptions($this->menudAutor);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("MenuDytesoreList"); // Prevent SQL injection, return to list
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
                $this->terminate("MenuDytesoreList"); // Return to list
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
        $this->menudID->setDbValue($row['menudID']);
        $this->menudGjuha->setDbValue($row['menudGjuha']);
        $this->menudKryesore->setDbValue($row['menudKryesore']);
        if (array_key_exists('EV__menudKryesore', $row)) {
            $this->menudKryesore->VirtualValue = $row['EV__menudKryesore']; // Set up virtual field value
        } else {
            $this->menudKryesore->VirtualValue = ""; // Clear value
        }
        $this->menudTitulli->setDbValue($row['menudTitulli']);
        $this->menudUrl->setDbValue($row['menudUrl']);
        $this->menudBlank->setDbValue($row['menudBlank']);
        $this->menudRadhe->setDbValue($row['menudRadhe']);
        $this->menudAktiv->setDbValue($row['menudAktiv']);
        $this->menudAutor->setDbValue($row['menudAutor']);
        $this->menudKrijuar->setDbValue($row['menudKrijuar']);
        $this->menudAzhornuar->setDbValue($row['menudAzhornuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['menudID'] = $this->menudID->DefaultValue;
        $row['menudGjuha'] = $this->menudGjuha->DefaultValue;
        $row['menudKryesore'] = $this->menudKryesore->DefaultValue;
        $row['menudTitulli'] = $this->menudTitulli->DefaultValue;
        $row['menudUrl'] = $this->menudUrl->DefaultValue;
        $row['menudBlank'] = $this->menudBlank->DefaultValue;
        $row['menudRadhe'] = $this->menudRadhe->DefaultValue;
        $row['menudAktiv'] = $this->menudAktiv->DefaultValue;
        $row['menudAutor'] = $this->menudAutor->DefaultValue;
        $row['menudKrijuar'] = $this->menudKrijuar->DefaultValue;
        $row['menudAzhornuar'] = $this->menudAzhornuar->DefaultValue;
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

        // menudID

        // menudGjuha

        // menudKryesore

        // menudTitulli

        // menudUrl

        // menudBlank

        // menudRadhe

        // menudAktiv

        // menudAutor

        // menudKrijuar

        // menudAzhornuar

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // menudID
            $this->menudID->ViewValue = $this->menudID->CurrentValue;
            $this->menudID->ViewCustomAttributes = "";

            // menudGjuha
            if (strval($this->menudGjuha->CurrentValue) != "") {
                $this->menudGjuha->ViewValue = $this->menudGjuha->optionCaption($this->menudGjuha->CurrentValue);
            } else {
                $this->menudGjuha->ViewValue = null;
            }
            $this->menudGjuha->ViewCustomAttributes = "";

            // menudKryesore
            if ($this->menudKryesore->VirtualValue != "") {
                $this->menudKryesore->ViewValue = $this->menudKryesore->VirtualValue;
            } else {
                $curVal = strval($this->menudKryesore->CurrentValue);
                if ($curVal != "") {
                    $this->menudKryesore->ViewValue = $this->menudKryesore->lookupCacheOption($curVal);
                    if ($this->menudKryesore->ViewValue === null) { // Lookup from database
                        $filterWrk = "`menukID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                        $sqlWrk = $this->menudKryesore->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                        $conn = Conn();
                        $config = $conn->getConfiguration();
                        $config->setResultCacheImpl($this->Cache);
                        $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                        $ari = count($rswrk);
                        if ($ari > 0) { // Lookup values found
                            $arwrk = $this->menudKryesore->Lookup->renderViewRow($rswrk[0]);
                            $this->menudKryesore->ViewValue = $this->menudKryesore->displayValue($arwrk);
                        } else {
                            $this->menudKryesore->ViewValue = FormatNumber($this->menudKryesore->CurrentValue, $this->menudKryesore->formatPattern());
                        }
                    }
                } else {
                    $this->menudKryesore->ViewValue = null;
                }
            }
            $this->menudKryesore->ViewCustomAttributes = "";

            // menudTitulli
            $this->menudTitulli->ViewValue = $this->menudTitulli->CurrentValue;
            $this->menudTitulli->ViewCustomAttributes = "";

            // menudUrl
            $this->menudUrl->ViewValue = $this->menudUrl->CurrentValue;
            $this->menudUrl->ViewCustomAttributes = "";

            // menudBlank
            if (strval($this->menudBlank->CurrentValue) != "") {
                $this->menudBlank->ViewValue = $this->menudBlank->optionCaption($this->menudBlank->CurrentValue);
            } else {
                $this->menudBlank->ViewValue = null;
            }
            $this->menudBlank->ViewCustomAttributes = "";

            // menudRadhe
            $this->menudRadhe->ViewValue = $this->menudRadhe->CurrentValue;
            $this->menudRadhe->ViewValue = FormatNumber($this->menudRadhe->ViewValue, $this->menudRadhe->formatPattern());
            $this->menudRadhe->ViewCustomAttributes = "";

            // menudAktiv
            if (strval($this->menudAktiv->CurrentValue) != "") {
                $this->menudAktiv->ViewValue = $this->menudAktiv->optionCaption($this->menudAktiv->CurrentValue);
            } else {
                $this->menudAktiv->ViewValue = null;
            }
            $this->menudAktiv->ViewCustomAttributes = "";

            // menudAutor
            $this->menudAutor->ViewValue = $this->menudAutor->CurrentValue;
            $curVal = strval($this->menudAutor->CurrentValue);
            if ($curVal != "") {
                $this->menudAutor->ViewValue = $this->menudAutor->lookupCacheOption($curVal);
                if ($this->menudAutor->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->menudAutor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->menudAutor->Lookup->renderViewRow($rswrk[0]);
                        $this->menudAutor->ViewValue = $this->menudAutor->displayValue($arwrk);
                    } else {
                        $this->menudAutor->ViewValue = FormatNumber($this->menudAutor->CurrentValue, $this->menudAutor->formatPattern());
                    }
                }
            } else {
                $this->menudAutor->ViewValue = null;
            }
            $this->menudAutor->ViewCustomAttributes = "";

            // menudKrijuar
            $this->menudKrijuar->ViewValue = $this->menudKrijuar->CurrentValue;
            $this->menudKrijuar->ViewValue = FormatDateTime($this->menudKrijuar->ViewValue, $this->menudKrijuar->formatPattern());
            $this->menudKrijuar->ViewCustomAttributes = "";

            // menudAzhornuar
            $this->menudAzhornuar->ViewValue = $this->menudAzhornuar->CurrentValue;
            $this->menudAzhornuar->ViewValue = FormatDateTime($this->menudAzhornuar->ViewValue, $this->menudAzhornuar->formatPattern());
            $this->menudAzhornuar->ViewCustomAttributes = "";

            // menudID
            $this->menudID->LinkCustomAttributes = "";
            $this->menudID->HrefValue = "";
            $this->menudID->TooltipValue = "";

            // menudGjuha
            $this->menudGjuha->LinkCustomAttributes = "";
            $this->menudGjuha->HrefValue = "";
            $this->menudGjuha->TooltipValue = "";

            // menudKryesore
            $this->menudKryesore->LinkCustomAttributes = "";
            $this->menudKryesore->HrefValue = "";
            $this->menudKryesore->TooltipValue = "";

            // menudTitulli
            $this->menudTitulli->LinkCustomAttributes = "";
            $this->menudTitulli->HrefValue = "";
            $this->menudTitulli->TooltipValue = "";

            // menudUrl
            $this->menudUrl->LinkCustomAttributes = "";
            $this->menudUrl->HrefValue = "";
            $this->menudUrl->TooltipValue = "";

            // menudBlank
            $this->menudBlank->LinkCustomAttributes = "";
            $this->menudBlank->HrefValue = "";
            $this->menudBlank->TooltipValue = "";

            // menudRadhe
            $this->menudRadhe->LinkCustomAttributes = "";
            $this->menudRadhe->HrefValue = "";
            $this->menudRadhe->TooltipValue = "";

            // menudAktiv
            $this->menudAktiv->LinkCustomAttributes = "";
            $this->menudAktiv->HrefValue = "";
            $this->menudAktiv->TooltipValue = "";

            // menudAutor
            $this->menudAutor->LinkCustomAttributes = "";
            $this->menudAutor->HrefValue = "";
            $this->menudAutor->TooltipValue = "";

            // menudKrijuar
            $this->menudKrijuar->LinkCustomAttributes = "";
            $this->menudKrijuar->HrefValue = "";
            $this->menudKrijuar->TooltipValue = "";

            // menudAzhornuar
            $this->menudAzhornuar->LinkCustomAttributes = "";
            $this->menudAzhornuar->HrefValue = "";
            $this->menudAzhornuar->TooltipValue = "";
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
            $thisKey .= $row['menudID'];

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("MenuDytesoreList"), "", $this->TableVar, true);
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
                case "x_menudGjuha":
                    break;
                case "x_menudKryesore":
                    break;
                case "x_menudBlank":
                    break;
                case "x_menudAktiv":
                    break;
                case "x_menudAutor":
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
