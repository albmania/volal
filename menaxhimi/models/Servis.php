<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for servis
 */
class Servis extends DbTable
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
    public $servisID;
    public $servisDate;
    public $servisKlienti;
    public $servisMakina;
    public $servisKmMakines;
    public $servisStafi;
    public $servisTotaliFatures;
    public $servisAutori;
    public $servisShtuar;
    public $servisModifikuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'servis';
        $this->TableName = 'servis';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`servis`";
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
        $this->DetailAdd = true; // Allow detail add
        $this->DetailEdit = true; // Allow detail edit
        $this->DetailView = true; // Allow detail view
        $this->ShowMultipleDetails = true; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // servisID
        $this->servisID = new DbField(
            'servis',
            'servis',
            'x_servisID',
            'servisID',
            '`servisID`',
            '`servisID`',
            3,
            11,
            -1,
            false,
            '`servisID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->servisID->InputTextType = "text";
        $this->servisID->IsAutoIncrement = true; // Autoincrement field
        $this->servisID->IsPrimaryKey = true; // Primary key field
        $this->servisID->IsForeignKey = true; // Foreign key field
        $this->servisID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['servisID'] = &$this->servisID;

        // servisDate
        $this->servisDate = new DbField(
            'servis',
            'servis',
            'x_servisDate',
            'servisDate',
            '`servisDate`',
            CastDateFieldForLike("`servisDate`", 7, "DB"),
            133,
            10,
            7,
            false,
            '`servisDate`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisDate->InputTextType = "text";
        $this->servisDate->Nullable = false; // NOT NULL field
        $this->servisDate->Required = true; // Required field
        $this->servisDate->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->servisDate->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->servisDate->Param, "CustomMsg");
        $this->Fields['servisDate'] = &$this->servisDate;

        // servisKlienti
        $this->servisKlienti = new DbField(
            'servis',
            'servis',
            'x_servisKlienti',
            'servisKlienti',
            '`servisKlienti`',
            '`servisKlienti`',
            3,
            255,
            -1,
            false,
            '`EV__servisKlienti`',
            true,
            true,
            true,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->servisKlienti->InputTextType = "text";
        $this->servisKlienti->Nullable = false; // NOT NULL field
        $this->servisKlienti->Required = true; // Required field
        $this->servisKlienti->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->servisKlienti->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->servisKlienti->Lookup = new Lookup('servisKlienti', 'klient', true, 'klientID', ["klientEmertimi","","",""], [], ["x_servisMakina"], [], [], [], [], '', '', "`klientEmertimi`");
        $this->servisKlienti->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->servisKlienti->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->servisKlienti->Param, "CustomMsg");
        $this->Fields['servisKlienti'] = &$this->servisKlienti;

        // servisMakina
        $this->servisMakina = new DbField(
            'servis',
            'servis',
            'x_servisMakina',
            'servisMakina',
            '`servisMakina`',
            '`servisMakina`',
            3,
            255,
            -1,
            false,
            '`EV__servisMakina`',
            true,
            true,
            true,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->servisMakina->InputTextType = "text";
        $this->servisMakina->Nullable = false; // NOT NULL field
        $this->servisMakina->Required = true; // Required field
        $this->servisMakina->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->servisMakina->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->servisMakina->Lookup = new Lookup('servisMakina', 'makina', true, 'makinaID', ["makinaTarga","","",""], ["x_servisKlienti"], [], ["makinaKlienti"], ["x_makinaKlienti"], [], [], '', '', "`makinaTarga`");
        $this->servisMakina->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->servisMakina->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->servisMakina->Param, "CustomMsg");
        $this->Fields['servisMakina'] = &$this->servisMakina;

        // servisKmMakines
        $this->servisKmMakines = new DbField(
            'servis',
            'servis',
            'x_servisKmMakines',
            'servisKmMakines',
            '`servisKmMakines`',
            '`servisKmMakines`',
            3,
            7,
            -1,
            false,
            '`servisKmMakines`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisKmMakines->InputTextType = "text";
        $this->servisKmMakines->Nullable = false; // NOT NULL field
        $this->servisKmMakines->Required = true; // Required field
        $this->servisKmMakines->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->servisKmMakines->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->servisKmMakines->Param, "CustomMsg");
        $this->Fields['servisKmMakines'] = &$this->servisKmMakines;

        // servisStafi
        $this->servisStafi = new DbField(
            'servis',
            'servis',
            'x_servisStafi',
            'servisStafi',
            '`servisStafi`',
            '`servisStafi`',
            3,
            255,
            -1,
            false,
            '`EV__servisStafi`',
            true,
            true,
            true,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->servisStafi->InputTextType = "text";
        $this->servisStafi->Nullable = false; // NOT NULL field
        $this->servisStafi->Required = true; // Required field
        $this->servisStafi->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->servisStafi->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->servisStafi->Lookup = new Lookup('servisStafi', 'stafi', true, 'stafiID', ["stafiEmri","","",""], [], [], [], [], [], [], '', '', "`stafiEmri`");
        $this->servisStafi->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->servisStafi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->servisStafi->Param, "CustomMsg");
        $this->Fields['servisStafi'] = &$this->servisStafi;

        // servisTotaliFatures
        $this->servisTotaliFatures = new DbField(
            'servis',
            'servis',
            'x_servisTotaliFatures',
            'servisTotaliFatures',
            '`servisTotaliFatures`',
            '`servisTotaliFatures`',
            5,
            10,
            -1,
            false,
            '`servisTotaliFatures`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisTotaliFatures->InputTextType = "text";
        $this->servisTotaliFatures->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->servisTotaliFatures->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->servisTotaliFatures->Param, "CustomMsg");
        $this->Fields['servisTotaliFatures'] = &$this->servisTotaliFatures;

        // servisAutori
        $this->servisAutori = new DbField(
            'servis',
            'servis',
            'x_servisAutori',
            'servisAutori',
            '`servisAutori`',
            '`servisAutori`',
            3,
            255,
            -1,
            false,
            '`servisAutori`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisAutori->InputTextType = "text";
        $this->servisAutori->Nullable = false; // NOT NULL field
        $this->servisAutori->Lookup = new Lookup('servisAutori', 'perdoruesit', false, 'perdID', ["perdEmri","","",""], [], [], [], [], [], [], '', '', "`perdEmri`");
        $this->servisAutori->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['servisAutori'] = &$this->servisAutori;

        // servisShtuar
        $this->servisShtuar = new DbField(
            'servis',
            'servis',
            'x_servisShtuar',
            'servisShtuar',
            '`servisShtuar`',
            CastDateFieldForLike("`servisShtuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`servisShtuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisShtuar->InputTextType = "text";
        $this->servisShtuar->Nullable = false; // NOT NULL field
        $this->servisShtuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['servisShtuar'] = &$this->servisShtuar;

        // servisModifikuar
        $this->servisModifikuar = new DbField(
            'servis',
            'servis',
            'x_servisModifikuar',
            'servisModifikuar',
            '`servisModifikuar`',
            CastDateFieldForLike("`servisModifikuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`servisModifikuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisModifikuar->InputTextType = "text";
        $this->servisModifikuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['servisModifikuar'] = &$this->servisModifikuar;

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
        if ($this->getCurrentDetailTable() == "servis_pjeset") {
            $detailUrl = Container("servis_pjeset")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_servisID", $this->servisID->CurrentValue);
        }
        if ($this->getCurrentDetailTable() == "servis_sherbime") {
            $detailUrl = Container("servis_sherbime")->getListUrl() . "?" . Config("TABLE_SHOW_MASTER") . "=" . $this->TableVar;
            $detailUrl .= "&" . GetForeignKeyUrl("fk_servisID", $this->servisID->CurrentValue);
        }
        if ($detailUrl == "") {
            $detailUrl = "ServisList";
        }
        return $detailUrl;
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`servis`";
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
        $from = "(SELECT *, (SELECT DISTINCT `klientEmertimi` FROM `klient` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`klientID` = `servis`.`servisKlienti` LIMIT 1) AS `EV__servisKlienti`, (SELECT DISTINCT `makinaTarga` FROM `makina` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`makinaID` = `servis`.`servisMakina` LIMIT 1) AS `EV__servisMakina`, (SELECT DISTINCT `stafiEmri` FROM `stafi` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`stafiID` = `servis`.`servisStafi` LIMIT 1) AS `EV__servisStafi` FROM `servis`)";
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
            $this->servisKlienti->AdvancedSearch->SearchValue != "" ||
            $this->servisKlienti->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->servisKlienti->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->servisKlienti->VirtualExpression . " ")) {
            return true;
        }
        if (
            $this->servisMakina->AdvancedSearch->SearchValue != "" ||
            $this->servisMakina->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->servisMakina->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->servisMakina->VirtualExpression . " ")) {
            return true;
        }
        if (
            $this->servisStafi->AdvancedSearch->SearchValue != "" ||
            $this->servisStafi->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->servisStafi->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->servisStafi->VirtualExpression . " ")) {
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
            $this->servisID->setDbValue($conn->lastInsertId());
            $rs['servisID'] = $this->servisID->DbValue;
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
        // Cascade Update detail table 'servis_pjeset'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['servisID']) && $rsold['servisID'] != $rs['servisID'])) { // Update detail field 'servisPjeseServisID'
            $cascadeUpdate = true;
            $rscascade['servisPjeseServisID'] = $rs['servisID'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("servis_pjeset")->loadRs("`servisPjeseServisID` = " . QuotedValue($rsold['servisID'], DATATYPE_NUMBER, 'DB'))->fetchAllAssociative();
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'servisPjeseID';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("servis_pjeset")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("servis_pjeset")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("servis_pjeset")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

        // Cascade Update detail table 'servis_sherbime'
        $cascadeUpdate = false;
        $rscascade = [];
        if ($rsold && (isset($rs['servisID']) && $rsold['servisID'] != $rs['servisID'])) { // Update detail field 'servisSherbimServisID'
            $cascadeUpdate = true;
            $rscascade['servisSherbimServisID'] = $rs['servisID'];
        }
        if ($cascadeUpdate) {
            $rswrk = Container("servis_sherbime")->loadRs("`servisSherbimServisID` = " . QuotedValue($rsold['servisID'], DATATYPE_NUMBER, 'DB'))->fetchAllAssociative();
            foreach ($rswrk as $rsdtlold) {
                $rskey = [];
                $fldname = 'servisSherbimID';
                $rskey[$fldname] = $rsdtlold[$fldname];
                $rsdtlnew = array_merge($rsdtlold, $rscascade);
                // Call Row_Updating event
                $success = Container("servis_sherbime")->rowUpdating($rsdtlold, $rsdtlnew);
                if ($success) {
                    $success = Container("servis_sherbime")->update($rscascade, $rskey, $rsdtlold);
                }
                if (!$success) {
                    return false;
                }
                // Call Row_Updated event
                Container("servis_sherbime")->rowUpdated($rsdtlold, $rsdtlnew);
            }
        }

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
            if (array_key_exists('servisID', $rs)) {
                AddFilter($where, QuotedName('servisID', $this->Dbid) . '=' . QuotedValue($rs['servisID'], $this->servisID->DataType, $this->Dbid));
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

        // Cascade delete detail table 'servis_pjeset'
        $dtlrows = Container("servis_pjeset")->loadRs("`servisPjeseServisID` = " . QuotedValue($rs['servisID'], DATATYPE_NUMBER, "DB"))->fetchAllAssociative();
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("servis_pjeset")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("servis_pjeset")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("servis_pjeset")->rowDeleted($dtlrow);
            }
        }

        // Cascade delete detail table 'servis_sherbime'
        $dtlrows = Container("servis_sherbime")->loadRs("`servisSherbimServisID` = " . QuotedValue($rs['servisID'], DATATYPE_NUMBER, "DB"))->fetchAllAssociative();
        // Call Row Deleting event
        foreach ($dtlrows as $dtlrow) {
            $success = Container("servis_sherbime")->rowDeleting($dtlrow);
            if (!$success) {
                break;
            }
        }
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                $success = Container("servis_sherbime")->delete($dtlrow); // Delete
                if (!$success) {
                    break;
                }
            }
        }
        // Call Row Deleted event
        if ($success) {
            foreach ($dtlrows as $dtlrow) {
                Container("servis_sherbime")->rowDeleted($dtlrow);
            }
        }
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
        $this->servisID->DbValue = $row['servisID'];
        $this->servisDate->DbValue = $row['servisDate'];
        $this->servisKlienti->DbValue = $row['servisKlienti'];
        $this->servisMakina->DbValue = $row['servisMakina'];
        $this->servisKmMakines->DbValue = $row['servisKmMakines'];
        $this->servisStafi->DbValue = $row['servisStafi'];
        $this->servisTotaliFatures->DbValue = $row['servisTotaliFatures'];
        $this->servisAutori->DbValue = $row['servisAutori'];
        $this->servisShtuar->DbValue = $row['servisShtuar'];
        $this->servisModifikuar->DbValue = $row['servisModifikuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`servisID` = @servisID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->servisID->CurrentValue : $this->servisID->OldValue;
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
                $this->servisID->CurrentValue = $keys[0];
            } else {
                $this->servisID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('servisID', $row) ? $row['servisID'] : null;
        } else {
            $val = $this->servisID->OldValue !== null ? $this->servisID->OldValue : $this->servisID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@servisID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("ServisList");
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
        if ($pageName == "ServisView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ServisEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ServisAdd") {
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
                return "ServisView";
            case Config("API_ADD_ACTION"):
                return "ServisAdd";
            case Config("API_EDIT_ACTION"):
                return "ServisEdit";
            case Config("API_DELETE_ACTION"):
                return "ServisDelete";
            case Config("API_LIST_ACTION"):
                return "ServisList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ServisList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ServisView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ServisView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ServisAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ServisAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ServisEdit", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ServisEdit", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
            $url = $this->keyUrl("ServisAdd", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ServisAdd", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
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
        return $this->keyUrl("ServisDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"servisID\":" . JsonEncode($this->servisID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->servisID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->servisID->CurrentValue);
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
            if (($keyValue = Param("servisID") ?? Route("servisID")) !== null) {
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
                $this->servisID->CurrentValue = $key;
            } else {
                $this->servisID->OldValue = $key;
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
        $this->servisID->setDbValue($row['servisID']);
        $this->servisDate->setDbValue($row['servisDate']);
        $this->servisKlienti->setDbValue($row['servisKlienti']);
        $this->servisMakina->setDbValue($row['servisMakina']);
        $this->servisKmMakines->setDbValue($row['servisKmMakines']);
        $this->servisStafi->setDbValue($row['servisStafi']);
        $this->servisTotaliFatures->setDbValue($row['servisTotaliFatures']);
        $this->servisAutori->setDbValue($row['servisAutori']);
        $this->servisShtuar->setDbValue($row['servisShtuar']);
        $this->servisModifikuar->setDbValue($row['servisModifikuar']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // servisID

        // servisDate

        // servisKlienti

        // servisMakina

        // servisKmMakines

        // servisStafi

        // servisTotaliFatures

        // servisAutori

        // servisShtuar

        // servisModifikuar

        // servisID
        $this->servisID->ViewValue = $this->servisID->CurrentValue;
        $this->servisID->ViewCustomAttributes = "";

        // servisDate
        $this->servisDate->ViewValue = $this->servisDate->CurrentValue;
        $this->servisDate->ViewValue = FormatDateTime($this->servisDate->ViewValue, $this->servisDate->formatPattern());
        $this->servisDate->ViewCustomAttributes = "";

        // servisKlienti
        if ($this->servisKlienti->VirtualValue != "") {
            $this->servisKlienti->ViewValue = $this->servisKlienti->VirtualValue;
        } else {
            $curVal = strval($this->servisKlienti->CurrentValue);
            if ($curVal != "") {
                $this->servisKlienti->ViewValue = $this->servisKlienti->lookupCacheOption($curVal);
                if ($this->servisKlienti->ViewValue === null) { // Lookup from database
                    $filterWrk = "`klientID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->servisKlienti->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->servisKlienti->Lookup->renderViewRow($rswrk[0]);
                        $this->servisKlienti->ViewValue = $this->servisKlienti->displayValue($arwrk);
                    } else {
                        $this->servisKlienti->ViewValue = FormatNumber($this->servisKlienti->CurrentValue, $this->servisKlienti->formatPattern());
                    }
                }
            } else {
                $this->servisKlienti->ViewValue = null;
            }
        }
        $this->servisKlienti->ViewCustomAttributes = "";

        // servisMakina
        if ($this->servisMakina->VirtualValue != "") {
            $this->servisMakina->ViewValue = $this->servisMakina->VirtualValue;
        } else {
            $curVal = strval($this->servisMakina->CurrentValue);
            if ($curVal != "") {
                $this->servisMakina->ViewValue = $this->servisMakina->lookupCacheOption($curVal);
                if ($this->servisMakina->ViewValue === null) { // Lookup from database
                    $filterWrk = "`makinaID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->servisMakina->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->servisMakina->Lookup->renderViewRow($rswrk[0]);
                        $this->servisMakina->ViewValue = $this->servisMakina->displayValue($arwrk);
                    } else {
                        $this->servisMakina->ViewValue = FormatNumber($this->servisMakina->CurrentValue, $this->servisMakina->formatPattern());
                    }
                }
            } else {
                $this->servisMakina->ViewValue = null;
            }
        }
        $this->servisMakina->ViewCustomAttributes = "";

        // servisKmMakines
        $this->servisKmMakines->ViewValue = $this->servisKmMakines->CurrentValue;
        $this->servisKmMakines->ViewValue = FormatNumber($this->servisKmMakines->ViewValue, $this->servisKmMakines->formatPattern());
        $this->servisKmMakines->ViewCustomAttributes = "";

        // servisStafi
        if ($this->servisStafi->VirtualValue != "") {
            $this->servisStafi->ViewValue = $this->servisStafi->VirtualValue;
        } else {
            $curVal = strval($this->servisStafi->CurrentValue);
            if ($curVal != "") {
                $this->servisStafi->ViewValue = $this->servisStafi->lookupCacheOption($curVal);
                if ($this->servisStafi->ViewValue === null) { // Lookup from database
                    $filterWrk = "`stafiID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->servisStafi->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->servisStafi->Lookup->renderViewRow($rswrk[0]);
                        $this->servisStafi->ViewValue = $this->servisStafi->displayValue($arwrk);
                    } else {
                        $this->servisStafi->ViewValue = FormatNumber($this->servisStafi->CurrentValue, $this->servisStafi->formatPattern());
                    }
                }
            } else {
                $this->servisStafi->ViewValue = null;
            }
        }
        $this->servisStafi->ViewCustomAttributes = "";

        // servisTotaliFatures
        $this->servisTotaliFatures->ViewValue = $this->servisTotaliFatures->CurrentValue;
        $this->servisTotaliFatures->ViewValue = FormatNumber($this->servisTotaliFatures->ViewValue, $this->servisTotaliFatures->formatPattern());
        $this->servisTotaliFatures->ViewCustomAttributes = "";

        // servisAutori
        $this->servisAutori->ViewValue = $this->servisAutori->CurrentValue;
        $curVal = strval($this->servisAutori->CurrentValue);
        if ($curVal != "") {
            $this->servisAutori->ViewValue = $this->servisAutori->lookupCacheOption($curVal);
            if ($this->servisAutori->ViewValue === null) { // Lookup from database
                $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->servisAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->servisAutori->Lookup->renderViewRow($rswrk[0]);
                    $this->servisAutori->ViewValue = $this->servisAutori->displayValue($arwrk);
                } else {
                    $this->servisAutori->ViewValue = FormatNumber($this->servisAutori->CurrentValue, $this->servisAutori->formatPattern());
                }
            }
        } else {
            $this->servisAutori->ViewValue = null;
        }
        $this->servisAutori->ViewCustomAttributes = "";

        // servisShtuar
        $this->servisShtuar->ViewValue = $this->servisShtuar->CurrentValue;
        $this->servisShtuar->ViewValue = FormatDateTime($this->servisShtuar->ViewValue, $this->servisShtuar->formatPattern());
        $this->servisShtuar->ViewCustomAttributes = "";

        // servisModifikuar
        $this->servisModifikuar->ViewValue = $this->servisModifikuar->CurrentValue;
        $this->servisModifikuar->ViewValue = FormatDateTime($this->servisModifikuar->ViewValue, $this->servisModifikuar->formatPattern());
        $this->servisModifikuar->ViewCustomAttributes = "";

        // servisID
        $this->servisID->LinkCustomAttributes = "";
        $this->servisID->HrefValue = "";
        $this->servisID->TooltipValue = "";

        // servisDate
        $this->servisDate->LinkCustomAttributes = "";
        $this->servisDate->HrefValue = "";
        $this->servisDate->TooltipValue = "";

        // servisKlienti
        $this->servisKlienti->LinkCustomAttributes = "";
        $this->servisKlienti->HrefValue = "";
        $this->servisKlienti->TooltipValue = "";

        // servisMakina
        $this->servisMakina->LinkCustomAttributes = "";
        $this->servisMakina->HrefValue = "";
        $this->servisMakina->TooltipValue = "";

        // servisKmMakines
        $this->servisKmMakines->LinkCustomAttributes = "";
        $this->servisKmMakines->HrefValue = "";
        $this->servisKmMakines->TooltipValue = "";

        // servisStafi
        $this->servisStafi->LinkCustomAttributes = "";
        $this->servisStafi->HrefValue = "";
        $this->servisStafi->TooltipValue = "";

        // servisTotaliFatures
        $this->servisTotaliFatures->LinkCustomAttributes = "";
        $this->servisTotaliFatures->HrefValue = "";
        $this->servisTotaliFatures->TooltipValue = "";

        // servisAutori
        $this->servisAutori->LinkCustomAttributes = "";
        $this->servisAutori->HrefValue = "";
        $this->servisAutori->TooltipValue = "";

        // servisShtuar
        $this->servisShtuar->LinkCustomAttributes = "";
        $this->servisShtuar->HrefValue = "";
        $this->servisShtuar->TooltipValue = "";

        // servisModifikuar
        $this->servisModifikuar->LinkCustomAttributes = "";
        $this->servisModifikuar->HrefValue = "";
        $this->servisModifikuar->TooltipValue = "";

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

        // servisID
        $this->servisID->setupEditAttributes();
        $this->servisID->EditCustomAttributes = "";
        $this->servisID->EditValue = $this->servisID->CurrentValue;
        $this->servisID->ViewCustomAttributes = "";

        // servisDate
        $this->servisDate->setupEditAttributes();
        $this->servisDate->EditCustomAttributes = "";
        $this->servisDate->EditValue = FormatDateTime($this->servisDate->CurrentValue, $this->servisDate->formatPattern());
        $this->servisDate->PlaceHolder = RemoveHtml($this->servisDate->caption());

        // servisKlienti
        $this->servisKlienti->setupEditAttributes();
        $this->servisKlienti->EditCustomAttributes = "";
        $this->servisKlienti->PlaceHolder = RemoveHtml($this->servisKlienti->caption());

        // servisMakina
        $this->servisMakina->setupEditAttributes();
        $this->servisMakina->EditCustomAttributes = "";
        $this->servisMakina->PlaceHolder = RemoveHtml($this->servisMakina->caption());

        // servisKmMakines
        $this->servisKmMakines->setupEditAttributes();
        $this->servisKmMakines->EditCustomAttributes = "";
        $this->servisKmMakines->EditValue = $this->servisKmMakines->CurrentValue;
        $this->servisKmMakines->PlaceHolder = RemoveHtml($this->servisKmMakines->caption());
        if (strval($this->servisKmMakines->EditValue) != "" && is_numeric($this->servisKmMakines->EditValue)) {
            $this->servisKmMakines->EditValue = FormatNumber($this->servisKmMakines->EditValue, null);
        }

        // servisStafi
        $this->servisStafi->setupEditAttributes();
        $this->servisStafi->EditCustomAttributes = "";
        $this->servisStafi->PlaceHolder = RemoveHtml($this->servisStafi->caption());

        // servisTotaliFatures
        $this->servisTotaliFatures->setupEditAttributes();
        $this->servisTotaliFatures->EditCustomAttributes = "";
        $this->servisTotaliFatures->EditValue = $this->servisTotaliFatures->CurrentValue;
        $this->servisTotaliFatures->PlaceHolder = RemoveHtml($this->servisTotaliFatures->caption());
        if (strval($this->servisTotaliFatures->EditValue) != "" && is_numeric($this->servisTotaliFatures->EditValue)) {
            $this->servisTotaliFatures->EditValue = FormatNumber($this->servisTotaliFatures->EditValue, null);
        }

        // servisAutori

        // servisShtuar

        // servisModifikuar

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
            if (is_numeric($this->servisTotaliFatures->CurrentValue)) {
                $this->servisTotaliFatures->Total += $this->servisTotaliFatures->CurrentValue; // Accumulate total
            }
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
            $this->servisTotaliFatures->CurrentValue = $this->servisTotaliFatures->Total;
            $this->servisTotaliFatures->ViewValue = $this->servisTotaliFatures->CurrentValue;
            $this->servisTotaliFatures->ViewValue = FormatNumber($this->servisTotaliFatures->ViewValue, $this->servisTotaliFatures->formatPattern());
            $this->servisTotaliFatures->ViewCustomAttributes = "";
            $this->servisTotaliFatures->HrefValue = ""; // Clear href value

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
                    $doc->exportCaption($this->servisID);
                    $doc->exportCaption($this->servisDate);
                    $doc->exportCaption($this->servisKlienti);
                    $doc->exportCaption($this->servisMakina);
                    $doc->exportCaption($this->servisKmMakines);
                    $doc->exportCaption($this->servisStafi);
                    $doc->exportCaption($this->servisTotaliFatures);
                    $doc->exportCaption($this->servisAutori);
                    $doc->exportCaption($this->servisShtuar);
                    $doc->exportCaption($this->servisModifikuar);
                } else {
                    $doc->exportCaption($this->servisID);
                    $doc->exportCaption($this->servisDate);
                    $doc->exportCaption($this->servisKlienti);
                    $doc->exportCaption($this->servisMakina);
                    $doc->exportCaption($this->servisKmMakines);
                    $doc->exportCaption($this->servisStafi);
                    $doc->exportCaption($this->servisTotaliFatures);
                    $doc->exportCaption($this->servisAutori);
                    $doc->exportCaption($this->servisShtuar);
                    $doc->exportCaption($this->servisModifikuar);
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
                $this->aggregateListRowValues(); // Aggregate row values

                // Render row
                $this->RowType = ROWTYPE_VIEW; // Render view
                $this->resetAttributes();
                $this->renderListRow();
                if (!$doc->ExportCustom) {
                    $doc->beginExportRow($rowCnt); // Allow CSS styles if enabled
                    if ($exportPageType == "view") {
                        $doc->exportField($this->servisID);
                        $doc->exportField($this->servisDate);
                        $doc->exportField($this->servisKlienti);
                        $doc->exportField($this->servisMakina);
                        $doc->exportField($this->servisKmMakines);
                        $doc->exportField($this->servisStafi);
                        $doc->exportField($this->servisTotaliFatures);
                        $doc->exportField($this->servisAutori);
                        $doc->exportField($this->servisShtuar);
                        $doc->exportField($this->servisModifikuar);
                    } else {
                        $doc->exportField($this->servisID);
                        $doc->exportField($this->servisDate);
                        $doc->exportField($this->servisKlienti);
                        $doc->exportField($this->servisMakina);
                        $doc->exportField($this->servisKmMakines);
                        $doc->exportField($this->servisStafi);
                        $doc->exportField($this->servisTotaliFatures);
                        $doc->exportField($this->servisAutori);
                        $doc->exportField($this->servisShtuar);
                        $doc->exportField($this->servisModifikuar);
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

        // Export aggregates (horizontal format only)
        if ($doc->Horizontal) {
            $this->RowType = ROWTYPE_AGGREGATE;
            $this->resetAttributes();
            $this->aggregateListRow();
            if (!$doc->ExportCustom) {
                $doc->beginExportRow(-1);
                $doc->exportAggregate($this->servisID, '');
                $doc->exportAggregate($this->servisDate, '');
                $doc->exportAggregate($this->servisKlienti, '');
                $doc->exportAggregate($this->servisMakina, '');
                $doc->exportAggregate($this->servisKmMakines, '');
                $doc->exportAggregate($this->servisStafi, '');
                $doc->exportAggregate($this->servisTotaliFatures, 'TOTAL');
                $doc->exportAggregate($this->servisAutori, '');
                $doc->exportAggregate($this->servisShtuar, '');
                $doc->exportAggregate($this->servisModifikuar, '');
                $doc->endExportRow();
            }
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
        // Oldi

    	// Shtuar nga une
    	//*******************************************//

    	//Nese eshte ne faqen e shtimit te faturave
    	if (CurrentPageID() == "add") {
    	//Per te marre daten e sotme
    	 $this->servisDate->CurrentValue = date('d/m/Y', strtotime('now'));
    	 }
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
