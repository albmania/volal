<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for makina_importi
 */
class MakinaImporti extends DbTable
{
    protected $SqlFrom = "";
    protected $SqlSelect = null;
    protected $SqlSelectList = null;
    protected $SqlWhere = "";
    protected $SqlGroupBy = "";
    protected $SqlHaving = "";
    protected $SqlOrderBy = "";
    public $UseSessionForListSql = true;

    // Column CSS classes
    public $LeftColumnClass = "col-sm-2 col-form-label ew-label";
    public $RightColumnClass = "col-sm-10";
    public $OffsetColumnClass = "col-sm-10 offset-sm-2";
    public $TableLeftColumnClass = "w-col-2";

    // Export
    public $ExportDoc;

    // Fields
    public $mimpID;
    public $mimpMarka;
    public $mimpModeli;
    public $mimpTipi;
    public $mimpShasia;
    public $mimpViti;
    public $mimpKarburant;
    public $mimpKambio;
    public $mimpNgjyra;
    public $mimpPrejardhja;
    public $mimpInfo;
    public $mimpCmimiBlerjes;
    public $mimpDogana;
    public $mimpTransporti;
    public $mimpTjera;
    public $mimpDtHyrjes;
    public $mimpCmimiShitjes;
    public $mimpGati;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'makina_importi';
        $this->TableName = 'makina_importi';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`makina_importi`";
        $this->Dbid = 'DB';
        $this->ExportAll = true;
        $this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
        $this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
        $this->ExportPageSize = "a4"; // Page size (PDF only)
        $this->ExportExcelPageOrientation = ""; // Page orientation (PhpSpreadsheet only)
        $this->ExportExcelPageSize = ""; // Page size (PhpSpreadsheet only)
        $this->ExportWordVersion = 12; // Word version (PHPWord only)
        $this->ExportWordPageOrientation = "portrait"; // Page orientation (PHPWord only)
        $this->ExportWordPageSize = "A4"; // Page orientation (PHPWord only)
        $this->ExportWordColumnWidth = null; // Cell width (PHPWord only)
        $this->DetailAdd = false; // Allow detail add
        $this->DetailEdit = false; // Allow detail edit
        $this->DetailView = false; // Allow detail view
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // mimpID
        $this->mimpID = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpID',
            'mimpID',
            '`mimpID`',
            '`mimpID`',
            3,
            255,
            -1,
            false,
            '`mimpID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->mimpID->InputTextType = "text";
        $this->mimpID->IsAutoIncrement = true; // Autoincrement field
        $this->mimpID->IsPrimaryKey = true; // Primary key field
        $this->mimpID->IsForeignKey = true; // Foreign key field
        $this->mimpID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['mimpID'] = &$this->mimpID;

        // mimpMarka
        $this->mimpMarka = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpMarka',
            'mimpMarka',
            '`mimpMarka`',
            '`mimpMarka`',
            3,
            255,
            -1,
            false,
            '`mimpMarka`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->mimpMarka->InputTextType = "text";
        $this->mimpMarka->Nullable = false; // NOT NULL field
        $this->mimpMarka->Required = true; // Required field
        $this->mimpMarka->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->mimpMarka->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->mimpMarka->Lookup = new Lookup('mimpMarka', 'makina_marka', false, 'mmarkaID', ["mmarkaMarka","","",""], [], ["x_mimpModeli"], [], [], [], [], '', '', "`mmarkaMarka`");
        $this->mimpMarka->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['mimpMarka'] = &$this->mimpMarka;

        // mimpModeli
        $this->mimpModeli = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpModeli',
            'mimpModeli',
            '`mimpModeli`',
            '`mimpModeli`',
            3,
            255,
            -1,
            false,
            '`mimpModeli`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->mimpModeli->InputTextType = "text";
        $this->mimpModeli->Nullable = false; // NOT NULL field
        $this->mimpModeli->Required = true; // Required field
        $this->mimpModeli->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->mimpModeli->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->mimpModeli->Lookup = new Lookup('mimpModeli', 'makina_modeli', false, 'mmodeliID', ["mmodeliModeli","","",""], ["x_mimpMarka"], ["x_mimpTipi"], ["mmodeliMarka"], ["x_mmodeliMarka"], [], [], '', '', "`mmodeliModeli`");
        $this->mimpModeli->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['mimpModeli'] = &$this->mimpModeli;

        // mimpTipi
        $this->mimpTipi = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpTipi',
            'mimpTipi',
            '`mimpTipi`',
            '`mimpTipi`',
            3,
            255,
            -1,
            false,
            '`mimpTipi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->mimpTipi->InputTextType = "text";
        $this->mimpTipi->Nullable = false; // NOT NULL field
        $this->mimpTipi->Required = true; // Required field
        $this->mimpTipi->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->mimpTipi->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->mimpTipi->Lookup = new Lookup('mimpTipi', 'makina_tipi', false, 'mtipiID', ["mtipiTipi","","",""], ["x_mimpModeli"], [], ["mtipiModeli"], ["x_mtipiModeli"], [], [], '', '', "`mtipiTipi`");
        $this->mimpTipi->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['mimpTipi'] = &$this->mimpTipi;

        // mimpShasia
        $this->mimpShasia = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpShasia',
            'mimpShasia',
            '`mimpShasia`',
            '`mimpShasia`',
            200,
            50,
            -1,
            false,
            '`mimpShasia`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpShasia->InputTextType = "text";
        $this->mimpShasia->Nullable = false; // NOT NULL field
        $this->mimpShasia->Required = true; // Required field
        $this->mimpShasia->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpShasia->Param, "CustomMsg");
        $this->Fields['mimpShasia'] = &$this->mimpShasia;

        // mimpViti
        $this->mimpViti = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpViti',
            'mimpViti',
            '`mimpViti`',
            '`mimpViti`',
            3,
            4,
            -1,
            false,
            '`mimpViti`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpViti->InputTextType = "text";
        $this->mimpViti->Nullable = false; // NOT NULL field
        $this->mimpViti->Required = true; // Required field
        $this->mimpViti->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->mimpViti->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpViti->Param, "CustomMsg");
        $this->Fields['mimpViti'] = &$this->mimpViti;

        // mimpKarburant
        $this->mimpKarburant = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpKarburant',
            'mimpKarburant',
            '`mimpKarburant`',
            '`mimpKarburant`',
            202,
            11,
            -1,
            false,
            '`mimpKarburant`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mimpKarburant->InputTextType = "text";
        $this->mimpKarburant->Nullable = false; // NOT NULL field
        $this->mimpKarburant->Required = true; // Required field
        $this->mimpKarburant->Lookup = new Lookup('mimpKarburant', 'makina_importi', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mimpKarburant->OptionCount = 6;
        $this->Fields['mimpKarburant'] = &$this->mimpKarburant;

        // mimpKambio
        $this->mimpKambio = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpKambio',
            'mimpKambio',
            '`mimpKambio`',
            '`mimpKambio`',
            202,
            10,
            -1,
            false,
            '`mimpKambio`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mimpKambio->InputTextType = "text";
        $this->mimpKambio->Nullable = false; // NOT NULL field
        $this->mimpKambio->Required = true; // Required field
        $this->mimpKambio->Lookup = new Lookup('mimpKambio', 'makina_importi', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mimpKambio->OptionCount = 3;
        $this->Fields['mimpKambio'] = &$this->mimpKambio;

        // mimpNgjyra
        $this->mimpNgjyra = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpNgjyra',
            'mimpNgjyra',
            '`mimpNgjyra`',
            '`mimpNgjyra`',
            200,
            50,
            -1,
            false,
            '`mimpNgjyra`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpNgjyra->InputTextType = "text";
        $this->mimpNgjyra->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpNgjyra->Param, "CustomMsg");
        $this->Fields['mimpNgjyra'] = &$this->mimpNgjyra;

        // mimpPrejardhja
        $this->mimpPrejardhja = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpPrejardhja',
            'mimpPrejardhja',
            '`mimpPrejardhja`',
            '`mimpPrejardhja`',
            200,
            25,
            -1,
            false,
            '`mimpPrejardhja`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpPrejardhja->InputTextType = "text";
        $this->mimpPrejardhja->Nullable = false; // NOT NULL field
        $this->mimpPrejardhja->Required = true; // Required field
        $this->mimpPrejardhja->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpPrejardhja->Param, "CustomMsg");
        $this->Fields['mimpPrejardhja'] = &$this->mimpPrejardhja;

        // mimpInfo
        $this->mimpInfo = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpInfo',
            'mimpInfo',
            '`mimpInfo`',
            '`mimpInfo`',
            201,
            65535,
            -1,
            false,
            '`mimpInfo`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->mimpInfo->InputTextType = "text";
        $this->Fields['mimpInfo'] = &$this->mimpInfo;

        // mimpCmimiBlerjes
        $this->mimpCmimiBlerjes = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpCmimiBlerjes',
            'mimpCmimiBlerjes',
            '`mimpCmimiBlerjes`',
            '`mimpCmimiBlerjes`',
            5,
            22,
            -1,
            false,
            '`mimpCmimiBlerjes`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpCmimiBlerjes->InputTextType = "text";
        $this->mimpCmimiBlerjes->Nullable = false; // NOT NULL field
        $this->mimpCmimiBlerjes->Required = true; // Required field
        $this->mimpCmimiBlerjes->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->mimpCmimiBlerjes->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpCmimiBlerjes->Param, "CustomMsg");
        $this->Fields['mimpCmimiBlerjes'] = &$this->mimpCmimiBlerjes;

        // mimpDogana
        $this->mimpDogana = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpDogana',
            'mimpDogana',
            '`mimpDogana`',
            '`mimpDogana`',
            5,
            22,
            -1,
            false,
            '`mimpDogana`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpDogana->InputTextType = "text";
        $this->mimpDogana->Nullable = false; // NOT NULL field
        $this->mimpDogana->Required = true; // Required field
        $this->mimpDogana->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->mimpDogana->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpDogana->Param, "CustomMsg");
        $this->Fields['mimpDogana'] = &$this->mimpDogana;

        // mimpTransporti
        $this->mimpTransporti = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpTransporti',
            'mimpTransporti',
            '`mimpTransporti`',
            '`mimpTransporti`',
            5,
            22,
            -1,
            false,
            '`mimpTransporti`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpTransporti->InputTextType = "text";
        $this->mimpTransporti->Nullable = false; // NOT NULL field
        $this->mimpTransporti->Required = true; // Required field
        $this->mimpTransporti->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->mimpTransporti->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpTransporti->Param, "CustomMsg");
        $this->Fields['mimpTransporti'] = &$this->mimpTransporti;

        // mimpTjera
        $this->mimpTjera = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpTjera',
            'mimpTjera',
            '`mimpTjera`',
            '`mimpTjera`',
            5,
            22,
            -1,
            false,
            '`mimpTjera`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpTjera->InputTextType = "text";
        $this->mimpTjera->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->mimpTjera->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpTjera->Param, "CustomMsg");
        $this->Fields['mimpTjera'] = &$this->mimpTjera;

        // mimpDtHyrjes
        $this->mimpDtHyrjes = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpDtHyrjes',
            'mimpDtHyrjes',
            '`mimpDtHyrjes`',
            CastDateFieldForLike("`mimpDtHyrjes`", 7, "DB"),
            133,
            10,
            7,
            false,
            '`mimpDtHyrjes`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpDtHyrjes->InputTextType = "text";
        $this->mimpDtHyrjes->Required = true; // Required field
        $this->mimpDtHyrjes->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->mimpDtHyrjes->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpDtHyrjes->Param, "CustomMsg");
        $this->Fields['mimpDtHyrjes'] = &$this->mimpDtHyrjes;

        // mimpCmimiShitjes
        $this->mimpCmimiShitjes = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpCmimiShitjes',
            'mimpCmimiShitjes',
            '`mimpCmimiShitjes`',
            '`mimpCmimiShitjes`',
            5,
            22,
            -1,
            false,
            '`mimpCmimiShitjes`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->mimpCmimiShitjes->InputTextType = "text";
        $this->mimpCmimiShitjes->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->mimpCmimiShitjes->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpCmimiShitjes->Param, "CustomMsg");
        $this->Fields['mimpCmimiShitjes'] = &$this->mimpCmimiShitjes;

        // mimpGati
        $this->mimpGati = new DbField(
            'makina_importi',
            'makina_importi',
            'x_mimpGati',
            'mimpGati',
            '`mimpGati`',
            '`mimpGati`',
            202,
            2,
            -1,
            false,
            '`mimpGati`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->mimpGati->InputTextType = "text";
        $this->mimpGati->Nullable = false; // NOT NULL field
        $this->mimpGati->Required = true; // Required field
        $this->mimpGati->Lookup = new Lookup('mimpGati', 'makina_importi', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->mimpGati->OptionCount = 2;
        $this->mimpGati->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->mimpGati->Param, "CustomMsg");
        $this->Fields['mimpGati'] = &$this->mimpGati;

        // Add Doctrine Cache
        $this->Cache = new ArrayCache();
        $this->CacheProfile = new \Doctrine\DBAL\Cache\QueryCacheProfile(0, $this->TableVar);
    }

    // Field Visibility
    public function getFieldVisibility($fldParm)
    {
        global $Security;
        return $this->$fldParm->Visible; // Returns original value
    }

    // Set left column class (must be predefined col-*-* classes of Bootstrap grid system)
    public function setLeftColumnClass($class)
    {
        if (preg_match('/^col\-(\w+)\-(\d+)$/', $class, $match)) {
            $this->LeftColumnClass = $class . " col-form-label ew-label";
            $this->RightColumnClass = "col-" . $match[1] . "-" . strval(12 - (int)$match[2]);
            $this->OffsetColumnClass = $this->RightColumnClass . " " . str_replace("col-", "offset-", $class);
            $this->TableLeftColumnClass = preg_replace('/^col-\w+-(\d+)$/', "w-col-$1", $class); // Change to w-col-*
        }
    }

    // Multiple column sort
    public function updateSort(&$fld, $ctrl)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $fld->setSort($curSort);
            $lastOrderBy = in_array($lastSort, ["ASC", "DESC"]) ? $sortField . " " . $lastSort : "";
            $curOrderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            if ($ctrl) {
                $orderBy = $this->getSessionOrderBy();
                $arOrderBy = !empty($orderBy) ? explode(", ", $orderBy) : [];
                if ($lastOrderBy != "" && in_array($lastOrderBy, $arOrderBy)) {
                    foreach ($arOrderBy as $key => $val) {
                        if ($val == $lastOrderBy) {
                            if ($curOrderBy == "") {
                                unset($arOrderBy[$key]);
                            } else {
                                $arOrderBy[$key] = $curOrderBy;
                            }
                        }
                    }
                } elseif ($curOrderBy != "") {
                    $arOrderBy[] = $curOrderBy;
                }
                $orderBy = implode(", ", $arOrderBy);
                $this->setSessionOrderBy($orderBy); // Save to Session
            } else {
                $this->setSessionOrderBy($curOrderBy); // Save to Session
            }
        } else {
            if (!$ctrl) {
                $fld->setSort("");
            }
        }
    }

    // Current detail table name
    public function getCurrentDetailTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE"));
    }

    public function setCurrentDetailTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_DETAIL_TABLE")] = $v;
    }

    // Get detail url
    public function getDetailUrl()
    {
        // Detail url
        $detailUrl = "";
        if ($this->getCurrentDetailTable() == "makina_importi_sherbime") {
            $detailUrl = Container("makina_importi_sherbime")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_mimpID", $this->mimpID->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "MakinaImportiList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`makina_importi`";
    }

    public function sqlFrom() // For backward compatibility
    {
        return $this->getSqlFrom();
    }

    public function setSqlFrom($v)
    {
        $this->SqlFrom = $v;
    }

    public function getSqlSelect() // Select
    {
        return $this->SqlSelect ?? $this->getQueryBuilder()->select("*");
    }

    public function sqlSelect() // For backward compatibility
    {
        return $this->getSqlSelect();
    }

    public function setSqlSelect($v)
    {
        $this->SqlSelect = $v;
    }

    public function getSqlWhere() // Where
    {
        $where = ($this->SqlWhere != "") ? $this->SqlWhere : "";
        $this->DefaultFilter = "";
        AddFilter($where, $this->DefaultFilter);
        return $where;
    }

    public function sqlWhere() // For backward compatibility
    {
        return $this->getSqlWhere();
    }

    public function setSqlWhere($v)
    {
        $this->SqlWhere = $v;
    }

    public function getSqlGroupBy() // Group By
    {
        return ($this->SqlGroupBy != "") ? $this->SqlGroupBy : "";
    }

    public function sqlGroupBy() // For backward compatibility
    {
        return $this->getSqlGroupBy();
    }

    public function setSqlGroupBy($v)
    {
        $this->SqlGroupBy = $v;
    }

    public function getSqlHaving() // Having
    {
        return ($this->SqlHaving != "") ? $this->SqlHaving : "";
    }

    public function sqlHaving() // For backward compatibility
    {
        return $this->getSqlHaving();
    }

    public function setSqlHaving($v)
    {
        $this->SqlHaving = $v;
    }

    public function getSqlOrderBy() // Order By
    {
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : $this->DefaultSort;
    }

    public function sqlOrderBy() // For backward compatibility
    {
        return $this->getSqlOrderBy();
    }

    public function setSqlOrderBy($v)
    {
        $this->SqlOrderBy = $v;
    }

    // Apply User ID filters
    public function applyUserIDFilters($filter, $id = "")
    {
        return $filter;
    }

    // Check if User ID security allows view all
    public function userIDAllow($id = "")
    {
        $allow = $this->UserIDAllowSecurity;
        switch ($id) {
            case "add":
            case "copy":
            case "gridadd":
            case "register":
            case "addopt":
                return (($allow & 1) == 1);
            case "edit":
            case "gridedit":
            case "update":
            case "changepassword":
            case "resetpassword":
                return (($allow & 4) == 4);
            case "delete":
                return (($allow & 2) == 2);
            case "view":
                return (($allow & 32) == 32);
            case "search":
                return (($allow & 64) == 64);
            case "lookup":
                return (($allow & 256) == 256);
            default:
                return (($allow & 8) == 8);
        }
    }

    /**
     * Get record count
     *
     * @param string|QueryBuilder $sql SQL or QueryBuilder
     * @param mixed $c Connection
     * @return int
     */
    public function getRecordCount($sql, $c = null)
    {
        $cnt = -1;
        $rs = null;
        if ($sql instanceof QueryBuilder) { // Query builder
            $sqlwrk = clone $sql;
            $sqlwrk = $sqlwrk->resetQueryPart("orderBy")->getSQL();
        } else {
            $sqlwrk = $sql;
        }
        $pattern = '/^SELECT\s([\s\S]+)\sFROM\s/i';
        // Skip Custom View / SubQuery / SELECT DISTINCT / ORDER BY
        if (
            ($this->TableType == 'TABLE' || $this->TableType == 'VIEW' || $this->TableType == 'LINKTABLE') &&
            preg_match($pattern, $sqlwrk) && !preg_match('/\(\s*(SELECT[^)]+)\)/i', $sqlwrk) &&
            !preg_match('/^\s*select\s+distinct\s+/i', $sqlwrk) && !preg_match('/\s+order\s+by\s+/i', $sqlwrk)
        ) {
            $sqlwrk = "SELECT COUNT(*) FROM " . preg_replace($pattern, "", $sqlwrk);
        } else {
            $sqlwrk = "SELECT COUNT(*) FROM (" . $sqlwrk . ") COUNT_TABLE";
        }
        $conn = $c ?? $this->getConnection();
        $cnt = $conn->fetchOne($sqlwrk);
        if ($cnt !== false) {
            return (int)$cnt;
        }

        // Unable to get count by SELECT COUNT(*), execute the SQL to get record count directly
        return ExecuteRecordCount($sql, $conn);
    }

    // Get SQL
    public function getSql($where, $orderBy = "")
    {
        return $this->buildSelectSql(
            $this->getSqlSelect(),
            $this->getSqlFrom(),
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $where,
            $orderBy
        )->getSQL();
    }

    // Table SQL
    public function getCurrentSql()
    {
        $filter = $this->CurrentFilter;
        $filter = $this->applyUserIDFilters($filter);
        $sort = $this->getSessionOrderBy();
        return $this->getSql($filter, $sort);
    }

    /**
     * Table SQL with List page filter
     *
     * @return QueryBuilder
     */
    public function getListSql()
    {
        $filter = $this->UseSessionForListSql ? $this->getSessionWhere() : "";
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->getSqlSelect();
        $from = $this->getSqlFrom();
        $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        $this->Sort = $sort;
        return $this->buildSelectSql(
            $select,
            $from,
            $this->getSqlWhere(),
            $this->getSqlGroupBy(),
            $this->getSqlHaving(),
            $this->getSqlOrderBy(),
            $filter,
            $sort
        );
    }

    // Get ORDER BY clause
    public function getOrderBy()
    {
        $orderBy = $this->getSqlOrderBy();
        $sort = $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Get record count based on filter (for detail record count in master table pages)
    public function loadRecordCount($filter)
    {
        $origFilter = $this->CurrentFilter;
        $this->CurrentFilter = $filter;
        $this->recordsetSelecting($this->CurrentFilter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $this->CurrentFilter, "");
        $cnt = $this->getRecordCount($sql);
        $this->CurrentFilter = $origFilter;
        return $cnt;
    }

    // Get record count (for current List page)
    public function listRecordCount()
    {
        $filter = $this->getSessionWhere();
        AddFilter($filter, $this->CurrentFilter);
        $filter = $this->applyUserIDFilters($filter);
        $this->recordsetSelecting($filter);
        $select = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlSelect() : $this->getQueryBuilder()->select("*");
        $groupBy = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlGroupBy() : "";
        $having = $this->TableType == 'CUSTOMVIEW' ? $this->getSqlHaving() : "";
        $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        $cnt = $this->getRecordCount($sql);
        return $cnt;
    }

    /**
     * INSERT statement
     *
     * @param mixed $rs
     * @return QueryBuilder
     */
    public function insertSql(&$rs)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->insert($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->setValue($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        return $queryBuilder;
    }

    // Insert
    public function insert(&$rs)
    {
        $conn = $this->getConnection();
        $success = $this->insertSql($rs)->execute();
        if ($success) {
            // Get insert id if necessary
            $this->mimpID->setDbValue($conn->lastInsertId());
            $rs['mimpID'] = $this->mimpID->DbValue;
        }
        return $success;
    }

    /**
     * UPDATE statement
     *
     * @param array $rs Data to be updated
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function updateSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->update($this->UpdateTable);
        foreach ($rs as $name => $value) {
            if (!isset($this->Fields[$name]) || $this->Fields[$name]->IsCustom || $this->Fields[$name]->IsAutoIncrement) {
                continue;
            }
            $type = GetParameterType($this->Fields[$name], $value, $this->Dbid);
            $queryBuilder->set($this->Fields[$name]->Expression, $queryBuilder->createPositionalParameter($value, $type));
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        AddFilter($filter, $where);
        if ($filter != "") {
            $queryBuilder->where($filter);
        }
        return $queryBuilder;
    }

    // Update
    public function update(&$rs, $where = "", $rsold = null, $curfilter = true)
    {
        // If no field is updated, execute may return 0. Treat as success
        $success = $this->updateSql($rs, $where, $curfilter)->execute();
        $success = ($success > 0) ? $success : true;
        return $success;
    }

    /**
     * DELETE statement
     *
     * @param array $rs Key values
     * @param string|array $where WHERE clause
     * @param string $curfilter Filter
     * @return QueryBuilder
     */
    public function deleteSql(&$rs, $where = "", $curfilter = true)
    {
        $queryBuilder = $this->getQueryBuilder();
        $queryBuilder->delete($this->UpdateTable);
        if (is_array($where)) {
            $where = $this->arrayToFilter($where);
        }
        if ($rs) {
            if (array_key_exists('mimpID', $rs)) {
                AddFilter($where, QuotedName('mimpID', $this->Dbid) . '=' . QuotedValue($rs['mimpID'], $this->mimpID->DataType, $this->Dbid));
            }
        }
        $filter = ($curfilter) ? $this->CurrentFilter : "";
        AddFilter($filter, $where);
        return $queryBuilder->where($filter != "" ? $filter : "0=1");
    }

    // Delete
    public function delete(&$rs, $where = "", $curfilter = false)
    {
        $success = true;
        if ($success) {
            $success = $this->deleteSql($rs, $where, $curfilter)->execute();
        }
        return $success;
    }

    // Load DbValue from recordset or array
    protected function loadDbValues($row)
    {
        if (!is_array($row)) {
            return;
        }
        $this->mimpID->DbValue = $row['mimpID'];
        $this->mimpMarka->DbValue = $row['mimpMarka'];
        $this->mimpModeli->DbValue = $row['mimpModeli'];
        $this->mimpTipi->DbValue = $row['mimpTipi'];
        $this->mimpShasia->DbValue = $row['mimpShasia'];
        $this->mimpViti->DbValue = $row['mimpViti'];
        $this->mimpKarburant->DbValue = $row['mimpKarburant'];
        $this->mimpKambio->DbValue = $row['mimpKambio'];
        $this->mimpNgjyra->DbValue = $row['mimpNgjyra'];
        $this->mimpPrejardhja->DbValue = $row['mimpPrejardhja'];
        $this->mimpInfo->DbValue = $row['mimpInfo'];
        $this->mimpCmimiBlerjes->DbValue = $row['mimpCmimiBlerjes'];
        $this->mimpDogana->DbValue = $row['mimpDogana'];
        $this->mimpTransporti->DbValue = $row['mimpTransporti'];
        $this->mimpTjera->DbValue = $row['mimpTjera'];
        $this->mimpDtHyrjes->DbValue = $row['mimpDtHyrjes'];
        $this->mimpCmimiShitjes->DbValue = $row['mimpCmimiShitjes'];
        $this->mimpGati->DbValue = $row['mimpGati'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`mimpID` = @mimpID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->mimpID->CurrentValue : $this->mimpID->OldValue;
        if (EmptyValue($val)) {
            return "";
        } else {
            $keys[] = $val;
        }
        return implode(Config("COMPOSITE_KEY_SEPARATOR"), $keys);
    }

    // Set Key
    public function setKey($key, $current = false)
    {
        $this->OldKey = strval($key);
        $keys = explode(Config("COMPOSITE_KEY_SEPARATOR"), $this->OldKey);
        if (count($keys) == 1) {
            if ($current) {
                $this->mimpID->CurrentValue = $keys[0];
            } else {
                $this->mimpID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('mimpID', $row) ? $row['mimpID'] : null;
        } else {
            $val = $this->mimpID->OldValue !== null ? $this->mimpID->OldValue : $this->mimpID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@mimpID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
        }
        return $keyFilter;
    }

    // Return page URL
    public function getReturnUrl()
    {
        $referUrl = ReferUrl();
        $referPageName = ReferPageName();
        $name = PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL");
        // Get referer URL automatically
        if ($referUrl != "" && $referPageName != CurrentPageName() && $referPageName != "login") { // Referer not same page or login page
            $_SESSION[$name] = $referUrl; // Save to Session
        }
        return $_SESSION[$name] ?? GetUrl("MakinaImportiList");
    }

    // Set return page URL
    public function setReturnUrl($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_RETURN_URL")] = $v;
    }

    // Get modal caption
    public function getModalCaption($pageName)
    {
        global $Language;
        if ($pageName == "MakinaImportiView") {
            return $Language->phrase("View");
        } elseif ($pageName == "MakinaImportiEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "MakinaImportiAdd") {
            return $Language->phrase("Add");
        } else {
            return "";
        }
    }

    // API page name
    public function getApiPageName($action)
    {
        switch (strtolower($action)) {
            case Config("API_VIEW_ACTION"):
                return "MakinaImportiView";
            case Config("API_ADD_ACTION"):
                return "MakinaImportiAdd";
            case Config("API_EDIT_ACTION"):
                return "MakinaImportiEdit";
            case Config("API_DELETE_ACTION"):
                return "MakinaImportiDelete";
            case Config("API_LIST_ACTION"):
                return "MakinaImportiList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "MakinaImportiList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("MakinaImportiView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("MakinaImportiView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "MakinaImportiAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "MakinaImportiAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("MakinaImportiEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("MakinaImportiEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Inline edit URL
    public function getInlineEditUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=edit"));
        return $this->addMasterUrl($url);
    }

    // Copy URL
    public function getCopyUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("MakinaImportiAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("MakinaImportiAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Inline copy URL
    public function getInlineCopyUrl()
    {
        $url = $this->keyUrl(CurrentPageName(), $this->getUrlParm("action=copy"));
        return $this->addMasterUrl($url);
    }

    // Delete URL
    public function getDeleteUrl()
    {
        return $this->keyUrl("MakinaImportiDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"mimpID\":" . JsonEncode($this->mimpID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->mimpID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->mimpID->CurrentValue);
        } else {
            return "javascript:ew.alert(ew.language.phrase('InvalidRecord'));";
        }
        if ($parm != "") {
            $url .= "?" . $parm;
        }
        return $url;
    }

    // Render sort
    public function renderFieldHeader($fld)
    {
        global $Security, $Language;
        $sortUrl = "";
        $attrs = "";
        if ($fld->Sortable) {
            $sortUrl = $this->sortUrl($fld);
            $attrs = ' role="button" data-sort-url="' . $sortUrl . '" data-sort-type="2"';
        }
        $html = '<div class="ew-table-header-caption"' . $attrs . '>' . $fld->caption() . '</div>';
        if ($sortUrl) {
            $html .= '<div class="ew-table-header-sort">' . $fld->getSortIcon() . '</div>';
        }
        if ($fld->UseFilter && $Security->canSearch()) {
            $html .= '<div class="ew-filter-dropdown-btn" data-ew-action="filter" data-table="' . $fld->TableVar . '" data-field="' . $fld->FieldVar .
                '"><div class="ew-table-header-filter" role="button" aria-haspopup="true">' . $Language->phrase("Filter") . '</div></div>';
        }
        $html = '<div class="ew-table-header-btn">' . $html . '</div>';
        if ($this->UseCustomTemplate) {
            $scriptId = str_replace("{id}", $fld->TableVar . "_" . $fld->Param, "tpc_{id}");
            $html = '<template id="' . $scriptId . '">' . $html . '</template>';
        }
        return $html;
    }

    // Sort URL
    public function sortUrl($fld)
    {
        if (
            $this->CurrentAction || $this->isExport() ||
            in_array($fld->Type, [128, 204, 205])
        ) { // Unsortable data type
                return "";
        } elseif ($fld->Sortable) {
            $urlParm = $this->getUrlParm("order=" . urlencode($fld->Name) . "&amp;ordertype=" . $fld->getNextSort());
            return $this->addMasterUrl(CurrentPageName() . "?" . $urlParm);
        } else {
            return "";
        }
    }

    // Get record keys from Post/Get/Session
    public function getRecordKeys()
    {
        $arKeys = [];
        $arKey = [];
        if (Param("key_m") !== null) {
            $arKeys = Param("key_m");
            $cnt = count($arKeys);
        } else {
            if (($keyValue = Param("mimpID") ?? Route("mimpID")) !== null) {
                $arKeys[] = $keyValue;
            } elseif (IsApi() && (($keyValue = Key(0) ?? Route(2)) !== null)) {
                $arKeys[] = $keyValue;
            } else {
                $arKeys = null; // Do not setup
            }

            //return $arKeys; // Do not return yet, so the values will also be checked by the following code
        }
        // Check keys
        $ar = [];
        if (is_array($arKeys)) {
            foreach ($arKeys as $key) {
                if (!is_numeric($key)) {
                    continue;
                }
                $ar[] = $key;
            }
        }
        return $ar;
    }

    // Get filter from record keys
    public function getFilterFromRecordKeys($setCurrent = true)
    {
        $arKeys = $this->getRecordKeys();
        $keyFilter = "";
        foreach ($arKeys as $key) {
            if ($keyFilter != "") {
                $keyFilter .= " OR ";
            }
            if ($setCurrent) {
                $this->mimpID->CurrentValue = $key;
            } else {
                $this->mimpID->OldValue = $key;
            }
            $keyFilter .= "(" . $this->getRecordFilter() . ")";
        }
        return $keyFilter;
    }

    // Load recordset based on filter
    public function loadRs($filter)
    {
        $sql = $this->getSql($filter); // Set up filter (WHERE Clause)
        $conn = $this->getConnection();
        return $conn->executeQuery($sql);
    }

    // Load row values from record
    public function loadListRowValues(&$rs)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            return;
        }
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

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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

        // Call Row Rendered event
        $this->rowRendered();

        // Save data for Custom Template
        $this->Rows[] = $this->customTemplateFieldValues();
    }

    // Render edit row values
    public function renderEditRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // mimpID
        $this->mimpID->setupEditAttributes();
        $this->mimpID->EditCustomAttributes = "";
        $this->mimpID->EditValue = $this->mimpID->CurrentValue;
        $this->mimpID->ViewCustomAttributes = "";

        // mimpMarka
        $this->mimpMarka->setupEditAttributes();
        $this->mimpMarka->EditCustomAttributes = "";
        $this->mimpMarka->PlaceHolder = RemoveHtml($this->mimpMarka->caption());

        // mimpModeli
        $this->mimpModeli->setupEditAttributes();
        $this->mimpModeli->EditCustomAttributes = "";
        $this->mimpModeli->PlaceHolder = RemoveHtml($this->mimpModeli->caption());

        // mimpTipi
        $this->mimpTipi->setupEditAttributes();
        $this->mimpTipi->EditCustomAttributes = "";
        $this->mimpTipi->PlaceHolder = RemoveHtml($this->mimpTipi->caption());

        // mimpShasia
        $this->mimpShasia->setupEditAttributes();
        $this->mimpShasia->EditCustomAttributes = "";
        if (!$this->mimpShasia->Raw) {
            $this->mimpShasia->CurrentValue = HtmlDecode($this->mimpShasia->CurrentValue);
        }
        $this->mimpShasia->EditValue = $this->mimpShasia->CurrentValue;
        $this->mimpShasia->PlaceHolder = RemoveHtml($this->mimpShasia->caption());

        // mimpViti
        $this->mimpViti->setupEditAttributes();
        $this->mimpViti->EditCustomAttributes = "";
        $this->mimpViti->EditValue = $this->mimpViti->CurrentValue;
        $this->mimpViti->PlaceHolder = RemoveHtml($this->mimpViti->caption());
        if (strval($this->mimpViti->EditValue) != "" && is_numeric($this->mimpViti->EditValue)) {
            $this->mimpViti->EditValue = FormatNumber($this->mimpViti->EditValue, null);
        }

        // mimpKarburant
        $this->mimpKarburant->EditCustomAttributes = "";
        $this->mimpKarburant->EditValue = $this->mimpKarburant->options(false);
        $this->mimpKarburant->PlaceHolder = RemoveHtml($this->mimpKarburant->caption());

        // mimpKambio
        $this->mimpKambio->EditCustomAttributes = "";
        $this->mimpKambio->EditValue = $this->mimpKambio->options(false);
        $this->mimpKambio->PlaceHolder = RemoveHtml($this->mimpKambio->caption());

        // mimpNgjyra
        $this->mimpNgjyra->setupEditAttributes();
        $this->mimpNgjyra->EditCustomAttributes = "";
        if (!$this->mimpNgjyra->Raw) {
            $this->mimpNgjyra->CurrentValue = HtmlDecode($this->mimpNgjyra->CurrentValue);
        }
        $this->mimpNgjyra->EditValue = $this->mimpNgjyra->CurrentValue;
        $this->mimpNgjyra->PlaceHolder = RemoveHtml($this->mimpNgjyra->caption());

        // mimpPrejardhja
        $this->mimpPrejardhja->setupEditAttributes();
        $this->mimpPrejardhja->EditCustomAttributes = "";
        if (!$this->mimpPrejardhja->Raw) {
            $this->mimpPrejardhja->CurrentValue = HtmlDecode($this->mimpPrejardhja->CurrentValue);
        }
        $this->mimpPrejardhja->EditValue = $this->mimpPrejardhja->CurrentValue;
        $this->mimpPrejardhja->PlaceHolder = RemoveHtml($this->mimpPrejardhja->caption());

        // mimpInfo
        $this->mimpInfo->setupEditAttributes();
        $this->mimpInfo->EditCustomAttributes = "";
        $this->mimpInfo->EditValue = $this->mimpInfo->CurrentValue;
        $this->mimpInfo->PlaceHolder = RemoveHtml($this->mimpInfo->caption());

        // mimpCmimiBlerjes
        $this->mimpCmimiBlerjes->setupEditAttributes();
        $this->mimpCmimiBlerjes->EditCustomAttributes = "";
        $this->mimpCmimiBlerjes->EditValue = $this->mimpCmimiBlerjes->CurrentValue;
        $this->mimpCmimiBlerjes->PlaceHolder = RemoveHtml($this->mimpCmimiBlerjes->caption());
        if (strval($this->mimpCmimiBlerjes->EditValue) != "" && is_numeric($this->mimpCmimiBlerjes->EditValue)) {
            $this->mimpCmimiBlerjes->EditValue = FormatNumber($this->mimpCmimiBlerjes->EditValue, null);
        }

        // mimpDogana
        $this->mimpDogana->setupEditAttributes();
        $this->mimpDogana->EditCustomAttributes = "";
        $this->mimpDogana->EditValue = $this->mimpDogana->CurrentValue;
        $this->mimpDogana->PlaceHolder = RemoveHtml($this->mimpDogana->caption());
        if (strval($this->mimpDogana->EditValue) != "" && is_numeric($this->mimpDogana->EditValue)) {
            $this->mimpDogana->EditValue = FormatNumber($this->mimpDogana->EditValue, null);
        }

        // mimpTransporti
        $this->mimpTransporti->setupEditAttributes();
        $this->mimpTransporti->EditCustomAttributes = "";
        $this->mimpTransporti->EditValue = $this->mimpTransporti->CurrentValue;
        $this->mimpTransporti->PlaceHolder = RemoveHtml($this->mimpTransporti->caption());
        if (strval($this->mimpTransporti->EditValue) != "" && is_numeric($this->mimpTransporti->EditValue)) {
            $this->mimpTransporti->EditValue = FormatNumber($this->mimpTransporti->EditValue, null);
        }

        // mimpTjera
        $this->mimpTjera->setupEditAttributes();
        $this->mimpTjera->EditCustomAttributes = "";
        $this->mimpTjera->EditValue = $this->mimpTjera->CurrentValue;
        $this->mimpTjera->PlaceHolder = RemoveHtml($this->mimpTjera->caption());
        if (strval($this->mimpTjera->EditValue) != "" && is_numeric($this->mimpTjera->EditValue)) {
            $this->mimpTjera->EditValue = FormatNumber($this->mimpTjera->EditValue, null);
        }

        // mimpDtHyrjes
        $this->mimpDtHyrjes->setupEditAttributes();
        $this->mimpDtHyrjes->EditCustomAttributes = "";
        $this->mimpDtHyrjes->EditValue = FormatDateTime($this->mimpDtHyrjes->CurrentValue, $this->mimpDtHyrjes->formatPattern());
        $this->mimpDtHyrjes->PlaceHolder = RemoveHtml($this->mimpDtHyrjes->caption());

        // mimpCmimiShitjes
        $this->mimpCmimiShitjes->setupEditAttributes();
        $this->mimpCmimiShitjes->EditCustomAttributes = "";
        $this->mimpCmimiShitjes->EditValue = $this->mimpCmimiShitjes->CurrentValue;
        $this->mimpCmimiShitjes->PlaceHolder = RemoveHtml($this->mimpCmimiShitjes->caption());
        if (strval($this->mimpCmimiShitjes->EditValue) != "" && is_numeric($this->mimpCmimiShitjes->EditValue)) {
            $this->mimpCmimiShitjes->EditValue = FormatNumber($this->mimpCmimiShitjes->EditValue, null);
        }

        // mimpGati
        $this->mimpGati->EditCustomAttributes = "";
        $this->mimpGati->EditValue = $this->mimpGati->options(false);
        $this->mimpGati->PlaceHolder = RemoveHtml($this->mimpGati->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
        // Call Row Rendered event
        $this->rowRendered();
    }

    // Export data in HTML/CSV/Word/Excel/Email/PDF format
    public function exportDocument($doc, $recordset, $startRec = 1, $stopRec = 1, $exportPageType = "")
    {
        if (!$recordset || !$doc) {
            return;
        }
        if (!$doc->ExportCustom) {
            // Write header
            $doc->exportTableHeader();
            if ($doc->Horizontal) { // Horizontal format, write header
                $doc->beginExportRow();
                if ($exportPageType == "view") {
                    $doc->exportCaption($this->mimpID);
                    $doc->exportCaption($this->mimpMarka);
                    $doc->exportCaption($this->mimpModeli);
                    $doc->exportCaption($this->mimpTipi);
                    $doc->exportCaption($this->mimpShasia);
                    $doc->exportCaption($this->mimpViti);
                    $doc->exportCaption($this->mimpKarburant);
                    $doc->exportCaption($this->mimpKambio);
                    $doc->exportCaption($this->mimpNgjyra);
                    $doc->exportCaption($this->mimpPrejardhja);
                    $doc->exportCaption($this->mimpInfo);
                    $doc->exportCaption($this->mimpCmimiBlerjes);
                    $doc->exportCaption($this->mimpDogana);
                    $doc->exportCaption($this->mimpTransporti);
                    $doc->exportCaption($this->mimpTjera);
                    $doc->exportCaption($this->mimpDtHyrjes);
                    $doc->exportCaption($this->mimpCmimiShitjes);
                    $doc->exportCaption($this->mimpGati);
                } else {
                    $doc->exportCaption($this->mimpID);
                    $doc->exportCaption($this->mimpMarka);
                    $doc->exportCaption($this->mimpModeli);
                    $doc->exportCaption($this->mimpTipi);
                    $doc->exportCaption($this->mimpShasia);
                    $doc->exportCaption($this->mimpViti);
                    $doc->exportCaption($this->mimpKarburant);
                    $doc->exportCaption($this->mimpKambio);
                    $doc->exportCaption($this->mimpNgjyra);
                    $doc->exportCaption($this->mimpPrejardhja);
                    $doc->exportCaption($this->mimpDtHyrjes);
                    $doc->exportCaption($this->mimpGati);
                }
                $doc->endExportRow();
            }
        }

        // Move to first record
        $recCnt = $startRec - 1;
        $stopRec = ($stopRec > 0) ? $stopRec : PHP_INT_MAX;
        while (!$recordset->EOF && $recCnt < $stopRec) {
            $row = $recordset->fields;
            $recCnt++;
            if ($recCnt >= $startRec) {
                $rowCnt = $recCnt - $startRec + 1;

                // Page break
                if ($this->ExportPageBreakCount > 0) {
                    if ($rowCnt > 1 && ($rowCnt - 1) % $this->ExportPageBreakCount == 0) {
                        $doc->exportPageBreak();
                    }
                }
                $this->loadListRowValues($row);

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->mimpID);
                        $doc->exportField($this->mimpMarka);
                        $doc->exportField($this->mimpModeli);
                        $doc->exportField($this->mimpTipi);
                        $doc->exportField($this->mimpShasia);
                        $doc->exportField($this->mimpViti);
                        $doc->exportField($this->mimpKarburant);
                        $doc->exportField($this->mimpKambio);
                        $doc->exportField($this->mimpNgjyra);
                        $doc->exportField($this->mimpPrejardhja);
                        $doc->exportField($this->mimpInfo);
                        $doc->exportField($this->mimpCmimiBlerjes);
                        $doc->exportField($this->mimpDogana);
                        $doc->exportField($this->mimpTransporti);
                        $doc->exportField($this->mimpTjera);
                        $doc->exportField($this->mimpDtHyrjes);
                        $doc->exportField($this->mimpCmimiShitjes);
                        $doc->exportField($this->mimpGati);
                    } else {
                        $doc->exportField($this->mimpID);
                        $doc->exportField($this->mimpMarka);
                        $doc->exportField($this->mimpModeli);
                        $doc->exportField($this->mimpTipi);
                        $doc->exportField($this->mimpShasia);
                        $doc->exportField($this->mimpViti);
                        $doc->exportField($this->mimpKarburant);
                        $doc->exportField($this->mimpKambio);
                        $doc->exportField($this->mimpNgjyra);
                        $doc->exportField($this->mimpPrejardhja);
                        $doc->exportField($this->mimpDtHyrjes);
                        $doc->exportField($this->mimpGati);
                    }
                    $doc->endExportRow($rowCnt);
                }
            }

            // Call Row Export server event
            if ($doc->ExportCustom) {
                $this->rowExport($row);
            }
            $recordset->moveNext();
        }
        if (!$doc->ExportCustom) {
            $doc->exportTableFooter();
        }
    }

    // Get file data
    public function getFileData($fldparm, $key, $resize, $width = 0, $height = 0, $plugins = [])
    {
        // No binary fields
        return false;
    }

    // Table level events

    // Recordset Selecting event
    public function recordsetSelecting(&$filter)
    {
        // Enter your code here
    }

    // Recordset Selected event
    public function recordsetSelected(&$rs)
    {
        //Log("Recordset Selected");
    }

    // Recordset Search Validated event
    public function recordsetSearchValidated()
    {
        // Example:
        //$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value
    }

    // Recordset Searching event
    public function recordsetSearching(&$filter)
    {
        // Enter your code here
    }

    // Row_Selecting event
    public function rowSelecting(&$filter)
    {
        // Enter your code here
    }

    // Row Selected event
    public function rowSelected(&$rs)
    {
        //Log("Row Selected");
    }

    // Row Inserting event
    public function rowInserting($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Inserted event
    public function rowInserted($rsold, &$rsnew)
    {
        //Log("Row Inserted");
    }

    // Row Updating event
    public function rowUpdating($rsold, &$rsnew)
    {
        // Enter your code here
        // To cancel, set return value to false
        return true;
    }

    // Row Updated event
    public function rowUpdated($rsold, &$rsnew)
    {
        //Log("Row Updated");
    }

    // Row Update Conflict event
    public function rowUpdateConflict($rsold, &$rsnew)
    {
        // Enter your code here
        // To ignore conflict, set return value to false
        return true;
    }

    // Grid Inserting event
    public function gridInserting()
    {
        // Enter your code here
        // To reject grid insert, set return value to false
        return true;
    }

    // Grid Inserted event
    public function gridInserted($rsnew)
    {
        //Log("Grid Inserted");
    }

    // Grid Updating event
    public function gridUpdating($rsold)
    {
        // Enter your code here
        // To reject grid update, set return value to false
        return true;
    }

    // Grid Updated event
    public function gridUpdated($rsold, $rsnew)
    {
        //Log("Grid Updated");
    }

    // Row Deleting event
    public function rowDeleting(&$rs)
    {
        // Enter your code here
        // To cancel, set return value to False
        return true;
    }

    // Row Deleted event
    public function rowDeleted(&$rs)
    {
        //Log("Row Deleted");
    }

    // Email Sending event
    public function emailSending($email, &$args)
    {
        //var_dump($email, $args); exit();
        return true;
    }

    // Lookup Selecting event
    public function lookupSelecting($fld, &$filter)
    {
        //var_dump($fld->Name, $fld->Lookup, $filter); // Uncomment to view the filter
        // Enter your code here
    }

    // Row Rendering event
    public function rowRendering()
    {
        // Enter your code here
    }

    // Row Rendered event
    public function rowRendered()
    {
        // To view properties of field class, use:
        //var_dump($this-><FieldName>);
    }

    // User ID Filtering event
    public function userIdFiltering(&$filter)
    {
        // Enter your code here
    }
}
