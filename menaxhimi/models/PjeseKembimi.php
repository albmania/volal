<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for pjese_kembimi
 */
class PjeseKembimi extends DbTable
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
    public $pjeseID;
    public $pjeseGjendja;
    public $pjeseKodiVolvo;
    public $pjeseKodiProdhuesi;
    public $pjeseProdhuesi;
    public $pjesePerMarke;
    public $pjesePerModel;
    public $pjesePerVitProdhimi;
    public $pjeseCmimBlerje;
    public $pjeseCmimShitje;
    public $pjeseAutori;
    public $pjeseShtuar;
    public $pjeseModifikuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'pjese_kembimi';
        $this->TableName = 'pjese_kembimi';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`pjese_kembimi`";
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

        // pjeseID
        $this->pjeseID = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseID',
            'pjeseID',
            '`pjeseID`',
            '`pjeseID`',
            3,
            255,
            -1,
            false,
            '`pjeseID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->pjeseID->InputTextType = "text";
        $this->pjeseID->IsAutoIncrement = true; // Autoincrement field
        $this->pjeseID->IsPrimaryKey = true; // Primary key field
        $this->pjeseID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['pjeseID'] = &$this->pjeseID;

        // pjeseGjendja
        $this->pjeseGjendja = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseGjendja',
            'pjeseGjendja',
            '`pjeseGjendja`',
            '`pjeseGjendja`',
            202,
            10,
            -1,
            false,
            '`pjeseGjendja`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->pjeseGjendja->InputTextType = "text";
        $this->pjeseGjendja->Nullable = false; // NOT NULL field
        $this->pjeseGjendja->Required = true; // Required field
        $this->pjeseGjendja->Lookup = new Lookup('pjeseGjendja', 'pjese_kembimi', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->pjeseGjendja->OptionCount = 2;
        $this->Fields['pjeseGjendja'] = &$this->pjeseGjendja;

        // pjeseKodiVolvo
        $this->pjeseKodiVolvo = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseKodiVolvo',
            'pjeseKodiVolvo',
            '`pjeseKodiVolvo`',
            '`pjeseKodiVolvo`',
            200,
            50,
            -1,
            false,
            '`pjeseKodiVolvo`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjeseKodiVolvo->InputTextType = "text";
        $this->pjeseKodiVolvo->Nullable = false; // NOT NULL field
        $this->pjeseKodiVolvo->Required = true; // Required field
        $this->pjeseKodiVolvo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pjeseKodiVolvo->Param, "CustomMsg");
        $this->Fields['pjeseKodiVolvo'] = &$this->pjeseKodiVolvo;

        // pjeseKodiProdhuesi
        $this->pjeseKodiProdhuesi = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseKodiProdhuesi',
            'pjeseKodiProdhuesi',
            '`pjeseKodiProdhuesi`',
            '`pjeseKodiProdhuesi`',
            200,
            50,
            -1,
            false,
            '`pjeseKodiProdhuesi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjeseKodiProdhuesi->InputTextType = "text";
        $this->pjeseKodiProdhuesi->Nullable = false; // NOT NULL field
        $this->pjeseKodiProdhuesi->Required = true; // Required field
        $this->pjeseKodiProdhuesi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pjeseKodiProdhuesi->Param, "CustomMsg");
        $this->Fields['pjeseKodiProdhuesi'] = &$this->pjeseKodiProdhuesi;

        // pjeseProdhuesi
        $this->pjeseProdhuesi = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseProdhuesi',
            'pjeseProdhuesi',
            '`pjeseProdhuesi`',
            '`pjeseProdhuesi`',
            200,
            100,
            -1,
            false,
            '`pjeseProdhuesi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjeseProdhuesi->InputTextType = "text";
        $this->pjeseProdhuesi->Nullable = false; // NOT NULL field
        $this->pjeseProdhuesi->Required = true; // Required field
        $this->pjeseProdhuesi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pjeseProdhuesi->Param, "CustomMsg");
        $this->Fields['pjeseProdhuesi'] = &$this->pjeseProdhuesi;

        // pjesePerMarke
        $this->pjesePerMarke = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjesePerMarke',
            'pjesePerMarke',
            '`pjesePerMarke`',
            '`pjesePerMarke`',
            200,
            50,
            -1,
            false,
            '`pjesePerMarke`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjesePerMarke->InputTextType = "text";
        $this->pjesePerMarke->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pjesePerMarke->Param, "CustomMsg");
        $this->Fields['pjesePerMarke'] = &$this->pjesePerMarke;

        // pjesePerModel
        $this->pjesePerModel = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjesePerModel',
            'pjesePerModel',
            '`pjesePerModel`',
            '`pjesePerModel`',
            200,
            250,
            -1,
            false,
            '`pjesePerModel`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjesePerModel->InputTextType = "text";
        $this->pjesePerModel->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pjesePerModel->Param, "CustomMsg");
        $this->Fields['pjesePerModel'] = &$this->pjesePerModel;

        // pjesePerVitProdhimi
        $this->pjesePerVitProdhimi = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjesePerVitProdhimi',
            'pjesePerVitProdhimi',
            '`pjesePerVitProdhimi`',
            '`pjesePerVitProdhimi`',
            200,
            50,
            -1,
            false,
            '`pjesePerVitProdhimi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjesePerVitProdhimi->InputTextType = "text";
        $this->pjesePerVitProdhimi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pjesePerVitProdhimi->Param, "CustomMsg");
        $this->Fields['pjesePerVitProdhimi'] = &$this->pjesePerVitProdhimi;

        // pjeseCmimBlerje
        $this->pjeseCmimBlerje = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseCmimBlerje',
            'pjeseCmimBlerje',
            '`pjeseCmimBlerje`',
            '`pjeseCmimBlerje`',
            5,
            10,
            -1,
            false,
            '`pjeseCmimBlerje`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjeseCmimBlerje->InputTextType = "text";
        $this->pjeseCmimBlerje->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->pjeseCmimBlerje->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pjeseCmimBlerje->Param, "CustomMsg");
        $this->Fields['pjeseCmimBlerje'] = &$this->pjeseCmimBlerje;

        // pjeseCmimShitje
        $this->pjeseCmimShitje = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseCmimShitje',
            'pjeseCmimShitje',
            '`pjeseCmimShitje`',
            '`pjeseCmimShitje`',
            5,
            10,
            -1,
            false,
            '`pjeseCmimShitje`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjeseCmimShitje->InputTextType = "text";
        $this->pjeseCmimShitje->Nullable = false; // NOT NULL field
        $this->pjeseCmimShitje->Required = true; // Required field
        $this->pjeseCmimShitje->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->pjeseCmimShitje->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->pjeseCmimShitje->Param, "CustomMsg");
        $this->Fields['pjeseCmimShitje'] = &$this->pjeseCmimShitje;

        // pjeseAutori
        $this->pjeseAutori = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseAutori',
            'pjeseAutori',
            '`pjeseAutori`',
            '`pjeseAutori`',
            3,
            255,
            -1,
            false,
            '`pjeseAutori`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjeseAutori->InputTextType = "text";
        $this->pjeseAutori->Nullable = false; // NOT NULL field
        $this->pjeseAutori->Lookup = new Lookup('pjeseAutori', 'perdoruesit', false, 'perdID', ["perdEmri","","",""], [], [], [], [], [], [], '', '', "`perdEmri`");
        $this->pjeseAutori->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['pjeseAutori'] = &$this->pjeseAutori;

        // pjeseShtuar
        $this->pjeseShtuar = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseShtuar',
            'pjeseShtuar',
            '`pjeseShtuar`',
            CastDateFieldForLike("`pjeseShtuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`pjeseShtuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjeseShtuar->InputTextType = "text";
        $this->pjeseShtuar->Nullable = false; // NOT NULL field
        $this->pjeseShtuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['pjeseShtuar'] = &$this->pjeseShtuar;

        // pjeseModifikuar
        $this->pjeseModifikuar = new DbField(
            'pjese_kembimi',
            'pjese_kembimi',
            'x_pjeseModifikuar',
            'pjeseModifikuar',
            '`pjeseModifikuar`',
            CastDateFieldForLike("`pjeseModifikuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`pjeseModifikuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->pjeseModifikuar->InputTextType = "text";
        $this->pjeseModifikuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['pjeseModifikuar'] = &$this->pjeseModifikuar;

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

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`pjese_kembimi`";
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
            $this->pjeseID->setDbValue($conn->lastInsertId());
            $rs['pjeseID'] = $this->pjeseID->DbValue;
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
            if (array_key_exists('pjeseID', $rs)) {
                AddFilter($where, QuotedName('pjeseID', $this->Dbid) . '=' . QuotedValue($rs['pjeseID'], $this->pjeseID->DataType, $this->Dbid));
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
        $this->pjeseID->DbValue = $row['pjeseID'];
        $this->pjeseGjendja->DbValue = $row['pjeseGjendja'];
        $this->pjeseKodiVolvo->DbValue = $row['pjeseKodiVolvo'];
        $this->pjeseKodiProdhuesi->DbValue = $row['pjeseKodiProdhuesi'];
        $this->pjeseProdhuesi->DbValue = $row['pjeseProdhuesi'];
        $this->pjesePerMarke->DbValue = $row['pjesePerMarke'];
        $this->pjesePerModel->DbValue = $row['pjesePerModel'];
        $this->pjesePerVitProdhimi->DbValue = $row['pjesePerVitProdhimi'];
        $this->pjeseCmimBlerje->DbValue = $row['pjeseCmimBlerje'];
        $this->pjeseCmimShitje->DbValue = $row['pjeseCmimShitje'];
        $this->pjeseAutori->DbValue = $row['pjeseAutori'];
        $this->pjeseShtuar->DbValue = $row['pjeseShtuar'];
        $this->pjeseModifikuar->DbValue = $row['pjeseModifikuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`pjeseID` = @pjeseID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->pjeseID->CurrentValue : $this->pjeseID->OldValue;
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
                $this->pjeseID->CurrentValue = $keys[0];
            } else {
                $this->pjeseID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('pjeseID', $row) ? $row['pjeseID'] : null;
        } else {
            $val = $this->pjeseID->OldValue !== null ? $this->pjeseID->OldValue : $this->pjeseID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@pjeseID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("PjeseKembimiList");
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
        if ($pageName == "PjeseKembimiView") {
            return $Language->phrase("View");
        } elseif ($pageName == "PjeseKembimiEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "PjeseKembimiAdd") {
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
                return "PjeseKembimiView";
            case Config("API_ADD_ACTION"):
                return "PjeseKembimiAdd";
            case Config("API_EDIT_ACTION"):
                return "PjeseKembimiEdit";
            case Config("API_DELETE_ACTION"):
                return "PjeseKembimiDelete";
            case Config("API_LIST_ACTION"):
                return "PjeseKembimiList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "PjeseKembimiList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PjeseKembimiView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("PjeseKembimiView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PjeseKembimiAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "PjeseKembimiAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("PjeseKembimiEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("PjeseKembimiAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("PjeseKembimiDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"pjeseID\":" . JsonEncode($this->pjeseID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->pjeseID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->pjeseID->CurrentValue);
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
            if (($keyValue = Param("pjeseID") ?? Route("pjeseID")) !== null) {
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
                $this->pjeseID->CurrentValue = $key;
            } else {
                $this->pjeseID->OldValue = $key;
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

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

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

        // pjeseID
        $this->pjeseID->setupEditAttributes();
        $this->pjeseID->EditCustomAttributes = "";
        $this->pjeseID->EditValue = $this->pjeseID->CurrentValue;
        $this->pjeseID->ViewCustomAttributes = "";

        // pjeseGjendja
        $this->pjeseGjendja->EditCustomAttributes = "";
        $this->pjeseGjendja->EditValue = $this->pjeseGjendja->options(false);
        $this->pjeseGjendja->PlaceHolder = RemoveHtml($this->pjeseGjendja->caption());

        // pjeseKodiVolvo
        $this->pjeseKodiVolvo->setupEditAttributes();
        $this->pjeseKodiVolvo->EditCustomAttributes = "";
        if (!$this->pjeseKodiVolvo->Raw) {
            $this->pjeseKodiVolvo->CurrentValue = HtmlDecode($this->pjeseKodiVolvo->CurrentValue);
        }
        $this->pjeseKodiVolvo->EditValue = $this->pjeseKodiVolvo->CurrentValue;
        $this->pjeseKodiVolvo->PlaceHolder = RemoveHtml($this->pjeseKodiVolvo->caption());

        // pjeseKodiProdhuesi
        $this->pjeseKodiProdhuesi->setupEditAttributes();
        $this->pjeseKodiProdhuesi->EditCustomAttributes = "";
        if (!$this->pjeseKodiProdhuesi->Raw) {
            $this->pjeseKodiProdhuesi->CurrentValue = HtmlDecode($this->pjeseKodiProdhuesi->CurrentValue);
        }
        $this->pjeseKodiProdhuesi->EditValue = $this->pjeseKodiProdhuesi->CurrentValue;
        $this->pjeseKodiProdhuesi->PlaceHolder = RemoveHtml($this->pjeseKodiProdhuesi->caption());

        // pjeseProdhuesi
        $this->pjeseProdhuesi->setupEditAttributes();
        $this->pjeseProdhuesi->EditCustomAttributes = "";
        if (!$this->pjeseProdhuesi->Raw) {
            $this->pjeseProdhuesi->CurrentValue = HtmlDecode($this->pjeseProdhuesi->CurrentValue);
        }
        $this->pjeseProdhuesi->EditValue = $this->pjeseProdhuesi->CurrentValue;
        $this->pjeseProdhuesi->PlaceHolder = RemoveHtml($this->pjeseProdhuesi->caption());

        // pjesePerMarke
        $this->pjesePerMarke->setupEditAttributes();
        $this->pjesePerMarke->EditCustomAttributes = "";
        if (!$this->pjesePerMarke->Raw) {
            $this->pjesePerMarke->CurrentValue = HtmlDecode($this->pjesePerMarke->CurrentValue);
        }
        $this->pjesePerMarke->EditValue = $this->pjesePerMarke->CurrentValue;
        $this->pjesePerMarke->PlaceHolder = RemoveHtml($this->pjesePerMarke->caption());

        // pjesePerModel
        $this->pjesePerModel->setupEditAttributes();
        $this->pjesePerModel->EditCustomAttributes = "";
        if (!$this->pjesePerModel->Raw) {
            $this->pjesePerModel->CurrentValue = HtmlDecode($this->pjesePerModel->CurrentValue);
        }
        $this->pjesePerModel->EditValue = $this->pjesePerModel->CurrentValue;
        $this->pjesePerModel->PlaceHolder = RemoveHtml($this->pjesePerModel->caption());

        // pjesePerVitProdhimi
        $this->pjesePerVitProdhimi->setupEditAttributes();
        $this->pjesePerVitProdhimi->EditCustomAttributes = "";
        if (!$this->pjesePerVitProdhimi->Raw) {
            $this->pjesePerVitProdhimi->CurrentValue = HtmlDecode($this->pjesePerVitProdhimi->CurrentValue);
        }
        $this->pjesePerVitProdhimi->EditValue = $this->pjesePerVitProdhimi->CurrentValue;
        $this->pjesePerVitProdhimi->PlaceHolder = RemoveHtml($this->pjesePerVitProdhimi->caption());

        // pjeseCmimBlerje
        $this->pjeseCmimBlerje->setupEditAttributes();
        $this->pjeseCmimBlerje->EditCustomAttributes = "";
        $this->pjeseCmimBlerje->EditValue = $this->pjeseCmimBlerje->CurrentValue;
        $this->pjeseCmimBlerje->PlaceHolder = RemoveHtml($this->pjeseCmimBlerje->caption());
        if (strval($this->pjeseCmimBlerje->EditValue) != "" && is_numeric($this->pjeseCmimBlerje->EditValue)) {
            $this->pjeseCmimBlerje->EditValue = FormatNumber($this->pjeseCmimBlerje->EditValue, null);
        }

        // pjeseCmimShitje
        $this->pjeseCmimShitje->setupEditAttributes();
        $this->pjeseCmimShitje->EditCustomAttributes = "";
        $this->pjeseCmimShitje->EditValue = $this->pjeseCmimShitje->CurrentValue;
        $this->pjeseCmimShitje->PlaceHolder = RemoveHtml($this->pjeseCmimShitje->caption());
        if (strval($this->pjeseCmimShitje->EditValue) != "" && is_numeric($this->pjeseCmimShitje->EditValue)) {
            $this->pjeseCmimShitje->EditValue = FormatNumber($this->pjeseCmimShitje->EditValue, null);
        }

        // pjeseAutori

        // pjeseShtuar

        // pjeseModifikuar

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
                    $doc->exportCaption($this->pjeseID);
                    $doc->exportCaption($this->pjeseGjendja);
                    $doc->exportCaption($this->pjeseKodiVolvo);
                    $doc->exportCaption($this->pjeseKodiProdhuesi);
                    $doc->exportCaption($this->pjeseProdhuesi);
                    $doc->exportCaption($this->pjesePerMarke);
                    $doc->exportCaption($this->pjesePerModel);
                    $doc->exportCaption($this->pjesePerVitProdhimi);
                    $doc->exportCaption($this->pjeseCmimBlerje);
                    $doc->exportCaption($this->pjeseCmimShitje);
                    $doc->exportCaption($this->pjeseAutori);
                    $doc->exportCaption($this->pjeseShtuar);
                    $doc->exportCaption($this->pjeseModifikuar);
                } else {
                    $doc->exportCaption($this->pjeseID);
                    $doc->exportCaption($this->pjeseGjendja);
                    $doc->exportCaption($this->pjeseKodiVolvo);
                    $doc->exportCaption($this->pjeseKodiProdhuesi);
                    $doc->exportCaption($this->pjeseProdhuesi);
                    $doc->exportCaption($this->pjesePerMarke);
                    $doc->exportCaption($this->pjesePerModel);
                    $doc->exportCaption($this->pjesePerVitProdhimi);
                    $doc->exportCaption($this->pjeseCmimBlerje);
                    $doc->exportCaption($this->pjeseCmimShitje);
                    $doc->exportCaption($this->pjeseAutori);
                    $doc->exportCaption($this->pjeseShtuar);
                    $doc->exportCaption($this->pjeseModifikuar);
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
                        $doc->exportField($this->pjeseID);
                        $doc->exportField($this->pjeseGjendja);
                        $doc->exportField($this->pjeseKodiVolvo);
                        $doc->exportField($this->pjeseKodiProdhuesi);
                        $doc->exportField($this->pjeseProdhuesi);
                        $doc->exportField($this->pjesePerMarke);
                        $doc->exportField($this->pjesePerModel);
                        $doc->exportField($this->pjesePerVitProdhimi);
                        $doc->exportField($this->pjeseCmimBlerje);
                        $doc->exportField($this->pjeseCmimShitje);
                        $doc->exportField($this->pjeseAutori);
                        $doc->exportField($this->pjeseShtuar);
                        $doc->exportField($this->pjeseModifikuar);
                    } else {
                        $doc->exportField($this->pjeseID);
                        $doc->exportField($this->pjeseGjendja);
                        $doc->exportField($this->pjeseKodiVolvo);
                        $doc->exportField($this->pjeseKodiProdhuesi);
                        $doc->exportField($this->pjeseProdhuesi);
                        $doc->exportField($this->pjesePerMarke);
                        $doc->exportField($this->pjesePerModel);
                        $doc->exportField($this->pjesePerVitProdhimi);
                        $doc->exportField($this->pjeseCmimBlerje);
                        $doc->exportField($this->pjeseCmimShitje);
                        $doc->exportField($this->pjeseAutori);
                        $doc->exportField($this->pjeseShtuar);
                        $doc->exportField($this->pjeseModifikuar);
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
