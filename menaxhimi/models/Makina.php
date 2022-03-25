<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for makina
 */
class Makina extends DbTable
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
    public $makinaID;
    public $makinaKlienti;
    public $makinaMarka;
    public $makinaModeli;
    public $makinaKarburanti;
    public $makinaMadhesiaMotorrit;
    public $makinaVitiProdhimit;
    public $makinaNgjyra;
    public $makinaInfoShtese;
    public $makinaVitiRegAL;
    public $makinaTarga;
    public $makinaNrShasie;
    public $makinaPrejardhja;
    public $makinaShiturVOLAL;
    public $makinaAutori;
    public $makinaShtuar;
    public $makinaModifikuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'makina';
        $this->TableName = 'makina';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`makina`";
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

        // makinaID
        $this->makinaID = new DbField(
            'makina',
            'makina',
            'x_makinaID',
            'makinaID',
            '`makinaID`',
            '`makinaID`',
            3,
            255,
            -1,
            false,
            '`makinaID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->makinaID->InputTextType = "text";
        $this->makinaID->IsAutoIncrement = true; // Autoincrement field
        $this->makinaID->IsPrimaryKey = true; // Primary key field
        $this->makinaID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['makinaID'] = &$this->makinaID;

        // makinaKlienti
        $this->makinaKlienti = new DbField(
            'makina',
            'makina',
            'x_makinaKlienti',
            'makinaKlienti',
            '`makinaKlienti`',
            '`makinaKlienti`',
            3,
            255,
            -1,
            false,
            '`EV__makinaKlienti`',
            true,
            true,
            true,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->makinaKlienti->InputTextType = "text";
        $this->makinaKlienti->Nullable = false; // NOT NULL field
        $this->makinaKlienti->Required = true; // Required field
        $this->makinaKlienti->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->makinaKlienti->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->makinaKlienti->Lookup = new Lookup('makinaKlienti', 'klient', true, 'klientID', ["klientEmertimi","","",""], [], [], [], [], [], [], '', '', "`klientEmertimi`");
        $this->makinaKlienti->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['makinaKlienti'] = &$this->makinaKlienti;

        // makinaMarka
        $this->makinaMarka = new DbField(
            'makina',
            'makina',
            'x_makinaMarka',
            'makinaMarka',
            '`makinaMarka`',
            '`makinaMarka`',
            200,
            30,
            -1,
            false,
            '`makinaMarka`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaMarka->InputTextType = "text";
        $this->makinaMarka->Nullable = false; // NOT NULL field
        $this->makinaMarka->Required = true; // Required field
        $this->makinaMarka->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaMarka->Param, "CustomMsg");
        $this->Fields['makinaMarka'] = &$this->makinaMarka;

        // makinaModeli
        $this->makinaModeli = new DbField(
            'makina',
            'makina',
            'x_makinaModeli',
            'makinaModeli',
            '`makinaModeli`',
            '`makinaModeli`',
            200,
            50,
            -1,
            false,
            '`makinaModeli`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaModeli->InputTextType = "text";
        $this->makinaModeli->Nullable = false; // NOT NULL field
        $this->makinaModeli->Required = true; // Required field
        $this->makinaModeli->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaModeli->Param, "CustomMsg");
        $this->Fields['makinaModeli'] = &$this->makinaModeli;

        // makinaKarburanti
        $this->makinaKarburanti = new DbField(
            'makina',
            'makina',
            'x_makinaKarburanti',
            'makinaKarburanti',
            '`makinaKarburanti`',
            '`makinaKarburanti`',
            202,
            13,
            -1,
            false,
            '`makinaKarburanti`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->makinaKarburanti->InputTextType = "text";
        $this->makinaKarburanti->Nullable = false; // NOT NULL field
        $this->makinaKarburanti->Required = true; // Required field
        $this->makinaKarburanti->Lookup = new Lookup('makinaKarburanti', 'makina', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->makinaKarburanti->OptionCount = 7;
        $this->Fields['makinaKarburanti'] = &$this->makinaKarburanti;

        // makinaMadhesiaMotorrit
        $this->makinaMadhesiaMotorrit = new DbField(
            'makina',
            'makina',
            'x_makinaMadhesiaMotorrit',
            'makinaMadhesiaMotorrit',
            '`makinaMadhesiaMotorrit`',
            '`makinaMadhesiaMotorrit`',
            200,
            10,
            -1,
            false,
            '`makinaMadhesiaMotorrit`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaMadhesiaMotorrit->InputTextType = "text";
        $this->makinaMadhesiaMotorrit->Nullable = false; // NOT NULL field
        $this->makinaMadhesiaMotorrit->Required = true; // Required field
        $this->makinaMadhesiaMotorrit->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaMadhesiaMotorrit->Param, "CustomMsg");
        $this->Fields['makinaMadhesiaMotorrit'] = &$this->makinaMadhesiaMotorrit;

        // makinaVitiProdhimit
        $this->makinaVitiProdhimit = new DbField(
            'makina',
            'makina',
            'x_makinaVitiProdhimit',
            'makinaVitiProdhimit',
            '`makinaVitiProdhimit`',
            '`makinaVitiProdhimit`',
            3,
            4,
            -1,
            false,
            '`makinaVitiProdhimit`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaVitiProdhimit->InputTextType = "text";
        $this->makinaVitiProdhimit->Nullable = false; // NOT NULL field
        $this->makinaVitiProdhimit->Required = true; // Required field
        $this->makinaVitiProdhimit->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->makinaVitiProdhimit->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaVitiProdhimit->Param, "CustomMsg");
        $this->Fields['makinaVitiProdhimit'] = &$this->makinaVitiProdhimit;

        // makinaNgjyra
        $this->makinaNgjyra = new DbField(
            'makina',
            'makina',
            'x_makinaNgjyra',
            'makinaNgjyra',
            '`makinaNgjyra`',
            '`makinaNgjyra`',
            200,
            20,
            -1,
            false,
            '`makinaNgjyra`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaNgjyra->InputTextType = "text";
        $this->makinaNgjyra->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaNgjyra->Param, "CustomMsg");
        $this->Fields['makinaNgjyra'] = &$this->makinaNgjyra;

        // makinaInfoShtese
        $this->makinaInfoShtese = new DbField(
            'makina',
            'makina',
            'x_makinaInfoShtese',
            'makinaInfoShtese',
            '`makinaInfoShtese`',
            '`makinaInfoShtese`',
            201,
            16777215,
            -1,
            false,
            '`makinaInfoShtese`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->makinaInfoShtese->InputTextType = "text";
        $this->makinaInfoShtese->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaInfoShtese->Param, "CustomMsg");
        $this->Fields['makinaInfoShtese'] = &$this->makinaInfoShtese;

        // makinaVitiRegAL
        $this->makinaVitiRegAL = new DbField(
            'makina',
            'makina',
            'x_makinaVitiRegAL',
            'makinaVitiRegAL',
            '`makinaVitiRegAL`',
            '`makinaVitiRegAL`',
            3,
            4,
            -1,
            false,
            '`makinaVitiRegAL`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaVitiRegAL->InputTextType = "text";
        $this->makinaVitiRegAL->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->makinaVitiRegAL->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaVitiRegAL->Param, "CustomMsg");
        $this->Fields['makinaVitiRegAL'] = &$this->makinaVitiRegAL;

        // makinaTarga
        $this->makinaTarga = new DbField(
            'makina',
            'makina',
            'x_makinaTarga',
            'makinaTarga',
            '`makinaTarga`',
            '`makinaTarga`',
            200,
            10,
            -1,
            false,
            '`makinaTarga`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaTarga->InputTextType = "text";
        $this->makinaTarga->Nullable = false; // NOT NULL field
        $this->makinaTarga->Required = true; // Required field
        $this->makinaTarga->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaTarga->Param, "CustomMsg");
        $this->Fields['makinaTarga'] = &$this->makinaTarga;

        // makinaNrShasie
        $this->makinaNrShasie = new DbField(
            'makina',
            'makina',
            'x_makinaNrShasie',
            'makinaNrShasie',
            '`makinaNrShasie`',
            '`makinaNrShasie`',
            200,
            50,
            -1,
            false,
            '`makinaNrShasie`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaNrShasie->InputTextType = "text";
        $this->makinaNrShasie->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaNrShasie->Param, "CustomMsg");
        $this->Fields['makinaNrShasie'] = &$this->makinaNrShasie;

        // makinaPrejardhja
        $this->makinaPrejardhja = new DbField(
            'makina',
            'makina',
            'x_makinaPrejardhja',
            'makinaPrejardhja',
            '`makinaPrejardhja`',
            '`makinaPrejardhja`',
            200,
            20,
            -1,
            false,
            '`makinaPrejardhja`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaPrejardhja->InputTextType = "text";
        $this->makinaPrejardhja->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->makinaPrejardhja->Param, "CustomMsg");
        $this->Fields['makinaPrejardhja'] = &$this->makinaPrejardhja;

        // makinaShiturVOLAL
        $this->makinaShiturVOLAL = new DbField(
            'makina',
            'makina',
            'x_makinaShiturVOLAL',
            'makinaShiturVOLAL',
            '`makinaShiturVOLAL`',
            '`makinaShiturVOLAL`',
            202,
            2,
            -1,
            false,
            '`makinaShiturVOLAL`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->makinaShiturVOLAL->InputTextType = "text";
        $this->makinaShiturVOLAL->Nullable = false; // NOT NULL field
        $this->makinaShiturVOLAL->Required = true; // Required field
        $this->makinaShiturVOLAL->Lookup = new Lookup('makinaShiturVOLAL', 'makina', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->makinaShiturVOLAL->OptionCount = 2;
        $this->Fields['makinaShiturVOLAL'] = &$this->makinaShiturVOLAL;

        // makinaAutori
        $this->makinaAutori = new DbField(
            'makina',
            'makina',
            'x_makinaAutori',
            'makinaAutori',
            '`makinaAutori`',
            '`makinaAutori`',
            3,
            255,
            -1,
            false,
            '`makinaAutori`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaAutori->InputTextType = "text";
        $this->makinaAutori->Nullable = false; // NOT NULL field
        $this->makinaAutori->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['makinaAutori'] = &$this->makinaAutori;

        // makinaShtuar
        $this->makinaShtuar = new DbField(
            'makina',
            'makina',
            'x_makinaShtuar',
            'makinaShtuar',
            '`makinaShtuar`',
            CastDateFieldForLike("`makinaShtuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`makinaShtuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaShtuar->InputTextType = "text";
        $this->makinaShtuar->Nullable = false; // NOT NULL field
        $this->makinaShtuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['makinaShtuar'] = &$this->makinaShtuar;

        // makinaModifikuar
        $this->makinaModifikuar = new DbField(
            'makina',
            'makina',
            'x_makinaModifikuar',
            'makinaModifikuar',
            '`makinaModifikuar`',
            CastDateFieldForLike("`makinaModifikuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`makinaModifikuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->makinaModifikuar->InputTextType = "text";
        $this->makinaModifikuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['makinaModifikuar'] = &$this->makinaModifikuar;

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
            $sortFieldList = ($fld->VirtualExpression != "") ? $fld->VirtualExpression : $sortField;
            $lastOrderBy = in_array($lastSort, ["ASC", "DESC"]) ? $sortFieldList . " " . $lastSort : "";
            $curOrderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortFieldList . " " . $curSort : "";
            if ($ctrl) {
                $orderByList = $this->getSessionOrderByList();
                $arOrderBy = !empty($orderByList) ? explode(", ", $orderByList) : [];
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
                $orderByList = implode(", ", $arOrderBy);
                $this->setSessionOrderByList($orderByList); // Save to Session
            } else {
                $this->setSessionOrderByList($curOrderBy); // Save to Session
            }
        } else {
            if (!$ctrl) {
                $fld->setSort("");
            }
        }
    }

    // Session ORDER BY for List page
    public function getSessionOrderByList()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST"));
    }

    public function setSessionOrderByList($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_ORDER_BY_LIST")] = $v;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`makina`";
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

    public function getSqlSelectList() // Select for List page
    {
        if ($this->SqlSelectList) {
            return $this->SqlSelectList;
        }
        $from = "(SELECT *, (SELECT DISTINCT `klientEmertimi` FROM `klient` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`klientID` = `makina`.`makinaKlienti` LIMIT 1) AS `EV__makinaKlienti` FROM `makina`)";
        return $from . " `TMP_TABLE`";
    }

    public function sqlSelectList() // For backward compatibility
    {
        return $this->getSqlSelectList();
    }

    public function setSqlSelectList($v)
    {
        $this->SqlSelectList = $v;
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
        if ($this->useVirtualFields()) {
            $select = "*";
            $from = $this->getSqlSelectList();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        } else {
            $select = $this->getSqlSelect();
            $from = $this->getSqlFrom();
            $sort = $this->UseSessionForListSql ? $this->getSessionOrderBy() : "";
        }
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
        $sort = ($this->useVirtualFields()) ? $this->getSessionOrderByList() : $this->getSessionOrderBy();
        if ($orderBy != "" && $sort != "") {
            $orderBy .= ", " . $sort;
        } elseif ($sort != "") {
            $orderBy = $sort;
        }
        return $orderBy;
    }

    // Check if virtual fields is used in SQL
    protected function useVirtualFields()
    {
        $where = $this->UseSessionForListSql ? $this->getSessionWhere() : $this->CurrentFilter;
        $orderBy = $this->UseSessionForListSql ? $this->getSessionOrderByList() : "";
        if ($where != "") {
            $where = " " . str_replace(["(", ")"], ["", ""], $where) . " ";
        }
        if ($orderBy != "") {
            $orderBy = " " . str_replace(["(", ")"], ["", ""], $orderBy) . " ";
        }
        if (
            $this->makinaKlienti->AdvancedSearch->SearchValue != "" ||
            $this->makinaKlienti->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->makinaKlienti->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->makinaKlienti->VirtualExpression . " ")) {
            return true;
        }
        return false;
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
        if ($this->useVirtualFields()) {
            $sql = $this->buildSelectSql("*", $this->getSqlSelectList(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        } else {
            $sql = $this->buildSelectSql($select, $this->getSqlFrom(), $this->getSqlWhere(), $groupBy, $having, "", $filter, "");
        }
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
            $this->makinaID->setDbValue($conn->lastInsertId());
            $rs['makinaID'] = $this->makinaID->DbValue;
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
            if (array_key_exists('makinaID', $rs)) {
                AddFilter($where, QuotedName('makinaID', $this->Dbid) . '=' . QuotedValue($rs['makinaID'], $this->makinaID->DataType, $this->Dbid));
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
        $this->makinaID->DbValue = $row['makinaID'];
        $this->makinaKlienti->DbValue = $row['makinaKlienti'];
        $this->makinaMarka->DbValue = $row['makinaMarka'];
        $this->makinaModeli->DbValue = $row['makinaModeli'];
        $this->makinaKarburanti->DbValue = $row['makinaKarburanti'];
        $this->makinaMadhesiaMotorrit->DbValue = $row['makinaMadhesiaMotorrit'];
        $this->makinaVitiProdhimit->DbValue = $row['makinaVitiProdhimit'];
        $this->makinaNgjyra->DbValue = $row['makinaNgjyra'];
        $this->makinaInfoShtese->DbValue = $row['makinaInfoShtese'];
        $this->makinaVitiRegAL->DbValue = $row['makinaVitiRegAL'];
        $this->makinaTarga->DbValue = $row['makinaTarga'];
        $this->makinaNrShasie->DbValue = $row['makinaNrShasie'];
        $this->makinaPrejardhja->DbValue = $row['makinaPrejardhja'];
        $this->makinaShiturVOLAL->DbValue = $row['makinaShiturVOLAL'];
        $this->makinaAutori->DbValue = $row['makinaAutori'];
        $this->makinaShtuar->DbValue = $row['makinaShtuar'];
        $this->makinaModifikuar->DbValue = $row['makinaModifikuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`makinaID` = @makinaID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->makinaID->CurrentValue : $this->makinaID->OldValue;
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
                $this->makinaID->CurrentValue = $keys[0];
            } else {
                $this->makinaID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('makinaID', $row) ? $row['makinaID'] : null;
        } else {
            $val = $this->makinaID->OldValue !== null ? $this->makinaID->OldValue : $this->makinaID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@makinaID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("MakinaList");
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
        if ($pageName == "MakinaView") {
            return $Language->phrase("View");
        } elseif ($pageName == "MakinaEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "MakinaAdd") {
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
                return "MakinaView";
            case Config("API_ADD_ACTION"):
                return "MakinaAdd";
            case Config("API_EDIT_ACTION"):
                return "MakinaEdit";
            case Config("API_DELETE_ACTION"):
                return "MakinaDelete";
            case Config("API_LIST_ACTION"):
                return "MakinaList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "MakinaList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("MakinaView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("MakinaView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "MakinaAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "MakinaAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("MakinaEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("MakinaAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("MakinaDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"makinaID\":" . JsonEncode($this->makinaID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->makinaID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->makinaID->CurrentValue);
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
            if (($keyValue = Param("makinaID") ?? Route("makinaID")) !== null) {
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
                $this->makinaID->CurrentValue = $key;
            } else {
                $this->makinaID->OldValue = $key;
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
        $this->makinaID->setDbValue($row['makinaID']);
        $this->makinaKlienti->setDbValue($row['makinaKlienti']);
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

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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

        // makinaID
        $this->makinaID->setupEditAttributes();
        $this->makinaID->EditCustomAttributes = "";
        $this->makinaID->EditValue = $this->makinaID->CurrentValue;
        $this->makinaID->ViewCustomAttributes = "";

        // makinaKlienti
        $this->makinaKlienti->setupEditAttributes();
        $this->makinaKlienti->EditCustomAttributes = "";
        $this->makinaKlienti->PlaceHolder = RemoveHtml($this->makinaKlienti->caption());

        // makinaMarka
        $this->makinaMarka->setupEditAttributes();
        $this->makinaMarka->EditCustomAttributes = "";
        if (!$this->makinaMarka->Raw) {
            $this->makinaMarka->CurrentValue = HtmlDecode($this->makinaMarka->CurrentValue);
        }
        $this->makinaMarka->EditValue = $this->makinaMarka->CurrentValue;
        $this->makinaMarka->PlaceHolder = RemoveHtml($this->makinaMarka->caption());

        // makinaModeli
        $this->makinaModeli->setupEditAttributes();
        $this->makinaModeli->EditCustomAttributes = "";
        if (!$this->makinaModeli->Raw) {
            $this->makinaModeli->CurrentValue = HtmlDecode($this->makinaModeli->CurrentValue);
        }
        $this->makinaModeli->EditValue = $this->makinaModeli->CurrentValue;
        $this->makinaModeli->PlaceHolder = RemoveHtml($this->makinaModeli->caption());

        // makinaKarburanti
        $this->makinaKarburanti->EditCustomAttributes = "";
        $this->makinaKarburanti->EditValue = $this->makinaKarburanti->options(false);
        $this->makinaKarburanti->PlaceHolder = RemoveHtml($this->makinaKarburanti->caption());

        // makinaMadhesiaMotorrit
        $this->makinaMadhesiaMotorrit->setupEditAttributes();
        $this->makinaMadhesiaMotorrit->EditCustomAttributes = "";
        if (!$this->makinaMadhesiaMotorrit->Raw) {
            $this->makinaMadhesiaMotorrit->CurrentValue = HtmlDecode($this->makinaMadhesiaMotorrit->CurrentValue);
        }
        $this->makinaMadhesiaMotorrit->EditValue = $this->makinaMadhesiaMotorrit->CurrentValue;
        $this->makinaMadhesiaMotorrit->PlaceHolder = RemoveHtml($this->makinaMadhesiaMotorrit->caption());

        // makinaVitiProdhimit
        $this->makinaVitiProdhimit->setupEditAttributes();
        $this->makinaVitiProdhimit->EditCustomAttributes = "";
        $this->makinaVitiProdhimit->EditValue = $this->makinaVitiProdhimit->CurrentValue;
        $this->makinaVitiProdhimit->PlaceHolder = RemoveHtml($this->makinaVitiProdhimit->caption());
        if (strval($this->makinaVitiProdhimit->EditValue) != "" && is_numeric($this->makinaVitiProdhimit->EditValue)) {
            $this->makinaVitiProdhimit->EditValue = FormatNumber($this->makinaVitiProdhimit->EditValue, null);
        }

        // makinaNgjyra
        $this->makinaNgjyra->setupEditAttributes();
        $this->makinaNgjyra->EditCustomAttributes = "";
        if (!$this->makinaNgjyra->Raw) {
            $this->makinaNgjyra->CurrentValue = HtmlDecode($this->makinaNgjyra->CurrentValue);
        }
        $this->makinaNgjyra->EditValue = $this->makinaNgjyra->CurrentValue;
        $this->makinaNgjyra->PlaceHolder = RemoveHtml($this->makinaNgjyra->caption());

        // makinaInfoShtese
        $this->makinaInfoShtese->setupEditAttributes();
        $this->makinaInfoShtese->EditCustomAttributes = "";
        $this->makinaInfoShtese->EditValue = $this->makinaInfoShtese->CurrentValue;
        $this->makinaInfoShtese->PlaceHolder = RemoveHtml($this->makinaInfoShtese->caption());

        // makinaVitiRegAL
        $this->makinaVitiRegAL->setupEditAttributes();
        $this->makinaVitiRegAL->EditCustomAttributes = "";
        $this->makinaVitiRegAL->EditValue = $this->makinaVitiRegAL->CurrentValue;
        $this->makinaVitiRegAL->PlaceHolder = RemoveHtml($this->makinaVitiRegAL->caption());
        if (strval($this->makinaVitiRegAL->EditValue) != "" && is_numeric($this->makinaVitiRegAL->EditValue)) {
            $this->makinaVitiRegAL->EditValue = FormatNumber($this->makinaVitiRegAL->EditValue, null);
        }

        // makinaTarga
        $this->makinaTarga->setupEditAttributes();
        $this->makinaTarga->EditCustomAttributes = "";
        if (!$this->makinaTarga->Raw) {
            $this->makinaTarga->CurrentValue = HtmlDecode($this->makinaTarga->CurrentValue);
        }
        $this->makinaTarga->EditValue = $this->makinaTarga->CurrentValue;
        $this->makinaTarga->PlaceHolder = RemoveHtml($this->makinaTarga->caption());

        // makinaNrShasie
        $this->makinaNrShasie->setupEditAttributes();
        $this->makinaNrShasie->EditCustomAttributes = "";
        if (!$this->makinaNrShasie->Raw) {
            $this->makinaNrShasie->CurrentValue = HtmlDecode($this->makinaNrShasie->CurrentValue);
        }
        $this->makinaNrShasie->EditValue = $this->makinaNrShasie->CurrentValue;
        $this->makinaNrShasie->PlaceHolder = RemoveHtml($this->makinaNrShasie->caption());

        // makinaPrejardhja
        $this->makinaPrejardhja->setupEditAttributes();
        $this->makinaPrejardhja->EditCustomAttributes = "";
        if (!$this->makinaPrejardhja->Raw) {
            $this->makinaPrejardhja->CurrentValue = HtmlDecode($this->makinaPrejardhja->CurrentValue);
        }
        $this->makinaPrejardhja->EditValue = $this->makinaPrejardhja->CurrentValue;
        $this->makinaPrejardhja->PlaceHolder = RemoveHtml($this->makinaPrejardhja->caption());

        // makinaShiturVOLAL
        $this->makinaShiturVOLAL->EditCustomAttributes = "";
        $this->makinaShiturVOLAL->EditValue = $this->makinaShiturVOLAL->options(false);
        $this->makinaShiturVOLAL->PlaceHolder = RemoveHtml($this->makinaShiturVOLAL->caption());

        // makinaAutori

        // makinaShtuar

        // makinaModifikuar

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
                    $doc->exportCaption($this->makinaID);
                    $doc->exportCaption($this->makinaKlienti);
                    $doc->exportCaption($this->makinaMarka);
                    $doc->exportCaption($this->makinaModeli);
                    $doc->exportCaption($this->makinaKarburanti);
                    $doc->exportCaption($this->makinaMadhesiaMotorrit);
                    $doc->exportCaption($this->makinaVitiProdhimit);
                    $doc->exportCaption($this->makinaNgjyra);
                    $doc->exportCaption($this->makinaInfoShtese);
                    $doc->exportCaption($this->makinaVitiRegAL);
                    $doc->exportCaption($this->makinaTarga);
                    $doc->exportCaption($this->makinaNrShasie);
                    $doc->exportCaption($this->makinaPrejardhja);
                    $doc->exportCaption($this->makinaShiturVOLAL);
                    $doc->exportCaption($this->makinaAutori);
                    $doc->exportCaption($this->makinaShtuar);
                    $doc->exportCaption($this->makinaModifikuar);
                } else {
                    $doc->exportCaption($this->makinaID);
                    $doc->exportCaption($this->makinaKlienti);
                    $doc->exportCaption($this->makinaMarka);
                    $doc->exportCaption($this->makinaModeli);
                    $doc->exportCaption($this->makinaKarburanti);
                    $doc->exportCaption($this->makinaMadhesiaMotorrit);
                    $doc->exportCaption($this->makinaVitiProdhimit);
                    $doc->exportCaption($this->makinaNgjyra);
                    $doc->exportCaption($this->makinaVitiRegAL);
                    $doc->exportCaption($this->makinaTarga);
                    $doc->exportCaption($this->makinaNrShasie);
                    $doc->exportCaption($this->makinaPrejardhja);
                    $doc->exportCaption($this->makinaShiturVOLAL);
                    $doc->exportCaption($this->makinaAutori);
                    $doc->exportCaption($this->makinaShtuar);
                    $doc->exportCaption($this->makinaModifikuar);
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
                        $doc->exportField($this->makinaID);
                        $doc->exportField($this->makinaKlienti);
                        $doc->exportField($this->makinaMarka);
                        $doc->exportField($this->makinaModeli);
                        $doc->exportField($this->makinaKarburanti);
                        $doc->exportField($this->makinaMadhesiaMotorrit);
                        $doc->exportField($this->makinaVitiProdhimit);
                        $doc->exportField($this->makinaNgjyra);
                        $doc->exportField($this->makinaInfoShtese);
                        $doc->exportField($this->makinaVitiRegAL);
                        $doc->exportField($this->makinaTarga);
                        $doc->exportField($this->makinaNrShasie);
                        $doc->exportField($this->makinaPrejardhja);
                        $doc->exportField($this->makinaShiturVOLAL);
                        $doc->exportField($this->makinaAutori);
                        $doc->exportField($this->makinaShtuar);
                        $doc->exportField($this->makinaModifikuar);
                    } else {
                        $doc->exportField($this->makinaID);
                        $doc->exportField($this->makinaKlienti);
                        $doc->exportField($this->makinaMarka);
                        $doc->exportField($this->makinaModeli);
                        $doc->exportField($this->makinaKarburanti);
                        $doc->exportField($this->makinaMadhesiaMotorrit);
                        $doc->exportField($this->makinaVitiProdhimit);
                        $doc->exportField($this->makinaNgjyra);
                        $doc->exportField($this->makinaVitiRegAL);
                        $doc->exportField($this->makinaTarga);
                        $doc->exportField($this->makinaNrShasie);
                        $doc->exportField($this->makinaPrejardhja);
                        $doc->exportField($this->makinaShiturVOLAL);
                        $doc->exportField($this->makinaAutori);
                        $doc->exportField($this->makinaShtuar);
                        $doc->exportField($this->makinaModifikuar);
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
