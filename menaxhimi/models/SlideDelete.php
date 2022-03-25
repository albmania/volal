<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class SlideDelete extends Slide
{
    use MessagesTrait;

    // Page ID
    public $PageID = "delete";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'slide';

    // Page object name
    public $PageObjName = "SlideDelete";

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

        // Table object (slide)
        if (!isset($GLOBALS["slide"]) || get_class($GLOBALS["slide"]) == PROJECT_NAMESPACE . "slide") {
            $GLOBALS["slide"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'slide');
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
                $tbl = Container("slide");
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
		        $this->slideFoto->OldUploadPath = '../ngarkime/slide/';
		        $this->slideFoto->UploadPath = $this->slideFoto->OldUploadPath;
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
            $key .= @$ar['slideID'];
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
            $this->slideID->Visible = false;
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
        $this->slideID->setVisibility();
        $this->slideGjuha->setVisibility();
        $this->slideFoto->setVisibility();
        $this->slideTxt1->setVisibility();
        $this->slideTxt2->setVisibility();
        $this->slideTxt3->Visible = false;
        $this->slideButonTxt->setVisibility();
        $this->slideLink->Visible = false;
        $this->slideTarget->Visible = false;
        $this->slideRadha->setVisibility();
        $this->slideAktiv->setVisibility();
        $this->slideAutori->setVisibility();
        $this->slideKrijuar->setVisibility();
        $this->slideAzhornuar->setVisibility();
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
        $this->setupLookupOptions($this->slideGjuha);
        $this->setupLookupOptions($this->slideTarget);
        $this->setupLookupOptions($this->slideAktiv);
        $this->setupLookupOptions($this->slideAutori);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Load key parameters
        $this->RecKeys = $this->getRecordKeys(); // Load record keys
        $filter = $this->getFilterFromRecordKeys();
        if ($filter == "") {
            $this->terminate("SlideList"); // Prevent SQL injection, return to list
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
                $this->terminate("SlideList"); // Return to list
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
        $this->slideID->setDbValue($row['slideID']);
        $this->slideGjuha->setDbValue($row['slideGjuha']);
        $this->slideFoto->Upload->DbValue = $row['slideFoto'];
        $this->slideFoto->setDbValue($this->slideFoto->Upload->DbValue);
        $this->slideTxt1->setDbValue($row['slideTxt1']);
        $this->slideTxt2->setDbValue($row['slideTxt2']);
        $this->slideTxt3->setDbValue($row['slideTxt3']);
        $this->slideButonTxt->setDbValue($row['slideButonTxt']);
        $this->slideLink->setDbValue($row['slideLink']);
        $this->slideTarget->setDbValue($row['slideTarget']);
        $this->slideRadha->setDbValue($row['slideRadha']);
        $this->slideAktiv->setDbValue($row['slideAktiv']);
        $this->slideAutori->setDbValue($row['slideAutori']);
        $this->slideKrijuar->setDbValue($row['slideKrijuar']);
        $this->slideAzhornuar->setDbValue($row['slideAzhornuar']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['slideID'] = $this->slideID->DefaultValue;
        $row['slideGjuha'] = $this->slideGjuha->DefaultValue;
        $row['slideFoto'] = $this->slideFoto->DefaultValue;
        $row['slideTxt1'] = $this->slideTxt1->DefaultValue;
        $row['slideTxt2'] = $this->slideTxt2->DefaultValue;
        $row['slideTxt3'] = $this->slideTxt3->DefaultValue;
        $row['slideButonTxt'] = $this->slideButonTxt->DefaultValue;
        $row['slideLink'] = $this->slideLink->DefaultValue;
        $row['slideTarget'] = $this->slideTarget->DefaultValue;
        $row['slideRadha'] = $this->slideRadha->DefaultValue;
        $row['slideAktiv'] = $this->slideAktiv->DefaultValue;
        $row['slideAutori'] = $this->slideAutori->DefaultValue;
        $row['slideKrijuar'] = $this->slideKrijuar->DefaultValue;
        $row['slideAzhornuar'] = $this->slideAzhornuar->DefaultValue;
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

        // slideID

        // slideGjuha

        // slideFoto

        // slideTxt1

        // slideTxt2

        // slideTxt3

        // slideButonTxt

        // slideLink

        // slideTarget

        // slideRadha

        // slideAktiv

        // slideAutori

        // slideKrijuar

        // slideAzhornuar

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // slideID
            $this->slideID->ViewValue = $this->slideID->CurrentValue;
            $this->slideID->ViewCustomAttributes = "";

            // slideGjuha
            if (strval($this->slideGjuha->CurrentValue) != "") {
                $this->slideGjuha->ViewValue = $this->slideGjuha->optionCaption($this->slideGjuha->CurrentValue);
            } else {
                $this->slideGjuha->ViewValue = null;
            }
            $this->slideGjuha->CssClass = "fw-bold";
            $this->slideGjuha->ViewCustomAttributes = "";

            // slideFoto
            $this->slideFoto->UploadPath = '../ngarkime/slide/';
            if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
                $this->slideFoto->ImageWidth = 100;
                $this->slideFoto->ImageHeight = 0;
                $this->slideFoto->ImageAlt = $this->slideFoto->alt();
                $this->slideFoto->ImageCssClass = "ew-image";
                $this->slideFoto->ViewValue = $this->slideFoto->Upload->DbValue;
            } else {
                $this->slideFoto->ViewValue = "";
            }
            $this->slideFoto->ViewCustomAttributes = "";

            // slideTxt1
            $this->slideTxt1->ViewValue = $this->slideTxt1->CurrentValue;
            $this->slideTxt1->ViewCustomAttributes = "";

            // slideTxt2
            $this->slideTxt2->ViewValue = $this->slideTxt2->CurrentValue;
            $this->slideTxt2->ViewCustomAttributes = "";

            // slideTxt3
            $this->slideTxt3->ViewValue = $this->slideTxt3->CurrentValue;
            $this->slideTxt3->ViewCustomAttributes = "";

            // slideButonTxt
            $this->slideButonTxt->ViewValue = $this->slideButonTxt->CurrentValue;
            $this->slideButonTxt->ViewCustomAttributes = "";

            // slideLink
            $this->slideLink->ViewValue = $this->slideLink->CurrentValue;
            $this->slideLink->ViewCustomAttributes = "";

            // slideTarget
            if (strval($this->slideTarget->CurrentValue) != "") {
                $this->slideTarget->ViewValue = $this->slideTarget->optionCaption($this->slideTarget->CurrentValue);
            } else {
                $this->slideTarget->ViewValue = null;
            }
            $this->slideTarget->ViewCustomAttributes = "";

            // slideRadha
            $this->slideRadha->ViewValue = $this->slideRadha->CurrentValue;
            $this->slideRadha->ViewValue = FormatNumber($this->slideRadha->ViewValue, $this->slideRadha->formatPattern());
            $this->slideRadha->ViewCustomAttributes = "";

            // slideAktiv
            if (strval($this->slideAktiv->CurrentValue) != "") {
                $this->slideAktiv->ViewValue = $this->slideAktiv->optionCaption($this->slideAktiv->CurrentValue);
            } else {
                $this->slideAktiv->ViewValue = null;
            }
            $this->slideAktiv->ViewCustomAttributes = "";

            // slideAutori
            $this->slideAutori->ViewValue = $this->slideAutori->CurrentValue;
            $curVal = strval($this->slideAutori->CurrentValue);
            if ($curVal != "") {
                $this->slideAutori->ViewValue = $this->slideAutori->lookupCacheOption($curVal);
                if ($this->slideAutori->ViewValue === null) { // Lookup from database
                    $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->slideAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->slideAutori->Lookup->renderViewRow($rswrk[0]);
                        $this->slideAutori->ViewValue = $this->slideAutori->displayValue($arwrk);
                    } else {
                        $this->slideAutori->ViewValue = FormatNumber($this->slideAutori->CurrentValue, $this->slideAutori->formatPattern());
                    }
                }
            } else {
                $this->slideAutori->ViewValue = null;
            }
            $this->slideAutori->ViewCustomAttributes = "";

            // slideKrijuar
            $this->slideKrijuar->ViewValue = $this->slideKrijuar->CurrentValue;
            $this->slideKrijuar->ViewValue = FormatDateTime($this->slideKrijuar->ViewValue, $this->slideKrijuar->formatPattern());
            $this->slideKrijuar->ViewCustomAttributes = "";

            // slideAzhornuar
            $this->slideAzhornuar->ViewValue = $this->slideAzhornuar->CurrentValue;
            $this->slideAzhornuar->ViewValue = FormatDateTime($this->slideAzhornuar->ViewValue, $this->slideAzhornuar->formatPattern());
            $this->slideAzhornuar->ViewCustomAttributes = "";

            // slideID
            $this->slideID->LinkCustomAttributes = "";
            $this->slideID->HrefValue = "";
            $this->slideID->TooltipValue = "";

            // slideGjuha
            $this->slideGjuha->LinkCustomAttributes = "";
            $this->slideGjuha->HrefValue = "";
            $this->slideGjuha->TooltipValue = "";

            // slideFoto
            $this->slideFoto->LinkCustomAttributes = "";
            $this->slideFoto->UploadPath = '../ngarkime/slide/';
            if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
                $this->slideFoto->HrefValue = GetFileUploadUrl($this->slideFoto, $this->slideFoto->htmlDecode($this->slideFoto->Upload->DbValue)); // Add prefix/suffix
                $this->slideFoto->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->slideFoto->HrefValue = FullUrl($this->slideFoto->HrefValue, "href");
                }
            } else {
                $this->slideFoto->HrefValue = "";
            }
            $this->slideFoto->ExportHrefValue = $this->slideFoto->UploadPath . $this->slideFoto->Upload->DbValue;
            $this->slideFoto->TooltipValue = "";
            if ($this->slideFoto->UseColorbox) {
                if (EmptyValue($this->slideFoto->TooltipValue)) {
                    $this->slideFoto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
                }
                $this->slideFoto->LinkAttrs["data-rel"] = "slide_x_slideFoto";
                $this->slideFoto->LinkAttrs->appendClass("ew-lightbox");
            }

            // slideTxt1
            $this->slideTxt1->LinkCustomAttributes = "";
            $this->slideTxt1->HrefValue = "";
            $this->slideTxt1->TooltipValue = "";

            // slideTxt2
            $this->slideTxt2->LinkCustomAttributes = "";
            $this->slideTxt2->HrefValue = "";
            $this->slideTxt2->TooltipValue = "";

            // slideButonTxt
            $this->slideButonTxt->LinkCustomAttributes = "";
            if (!EmptyValue($this->slideLink->CurrentValue)) {
                $this->slideButonTxt->HrefValue = (!empty($this->slideLink->ViewValue) && !is_array($this->slideLink->ViewValue) ? RemoveHtml($this->slideLink->ViewValue) : $this->slideLink->CurrentValue); // Add prefix/suffix
                $this->slideButonTxt->LinkAttrs["target"] = "_blank"; // Add target
                if ($this->isExport()) {
                    $this->slideButonTxt->HrefValue = FullUrl($this->slideButonTxt->HrefValue, "href");
                }
            } else {
                $this->slideButonTxt->HrefValue = "";
            }
            $this->slideButonTxt->TooltipValue = "";

            // slideRadha
            $this->slideRadha->LinkCustomAttributes = "";
            $this->slideRadha->HrefValue = "";
            $this->slideRadha->TooltipValue = "";

            // slideAktiv
            $this->slideAktiv->LinkCustomAttributes = "";
            $this->slideAktiv->HrefValue = "";
            $this->slideAktiv->TooltipValue = "";

            // slideAutori
            $this->slideAutori->LinkCustomAttributes = "";
            $this->slideAutori->HrefValue = "";
            $this->slideAutori->TooltipValue = "";

            // slideKrijuar
            $this->slideKrijuar->LinkCustomAttributes = "";
            $this->slideKrijuar->HrefValue = "";
            $this->slideKrijuar->TooltipValue = "";

            // slideAzhornuar
            $this->slideAzhornuar->LinkCustomAttributes = "";
            $this->slideAzhornuar->HrefValue = "";
            $this->slideAzhornuar->TooltipValue = "";
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
            $thisKey .= $row['slideID'];

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("SlideList"), "", $this->TableVar, true);
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
                case "x_slideGjuha":
                    break;
                case "x_slideTarget":
                    break;
                case "x_slideAktiv":
                    break;
                case "x_slideAutori":
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
