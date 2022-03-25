<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for index_psene
 */
class IndexPsene extends DbTable
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
    public $iPseNeID;
    public $iPseNeGjuha;
    public $iPseNeB1Titull;
    public $iPseNeB1Txt;
    public $iPseNeB1Ikona;
    public $iPseNeB2Titull;
    public $iPseNeB2Txt;
    public $iPseNeB2Ikona;
    public $iPseNeB3Titull;
    public $iPseNeB3Txt;
    public $iPseNeB3Ikona;
    public $iPseNeB4Titull;
    public $iPseNeB4Txt;
    public $iPseNeB4Ikona;
    public $iPseNeFoto;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'index_psene';
        $this->TableName = 'index_psene';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`index_psene`";
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

        // iPseNeID
        $this->iPseNeID = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeID',
            'iPseNeID',
            '`iPseNeID`',
            '`iPseNeID`',
            3,
            11,
            -1,
            false,
            '`iPseNeID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->iPseNeID->InputTextType = "text";
        $this->iPseNeID->IsAutoIncrement = true; // Autoincrement field
        $this->iPseNeID->IsPrimaryKey = true; // Primary key field
        $this->iPseNeID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['iPseNeID'] = &$this->iPseNeID;

        // iPseNeGjuha
        $this->iPseNeGjuha = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeGjuha',
            'iPseNeGjuha',
            '`iPseNeGjuha`',
            '`iPseNeGjuha`',
            202,
            2,
            -1,
            false,
            '`iPseNeGjuha`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->iPseNeGjuha->InputTextType = "text";
        $this->iPseNeGjuha->Nullable = false; // NOT NULL field
        $this->iPseNeGjuha->Lookup = new Lookup('iPseNeGjuha', 'index_psene', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->iPseNeGjuha->OptionCount = 2;
        $this->Fields['iPseNeGjuha'] = &$this->iPseNeGjuha;

        // iPseNeB1Titull
        $this->iPseNeB1Titull = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB1Titull',
            'iPseNeB1Titull',
            '`iPseNeB1Titull`',
            '`iPseNeB1Titull`',
            200,
            50,
            -1,
            false,
            '`iPseNeB1Titull`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB1Titull->InputTextType = "text";
        $this->iPseNeB1Titull->Nullable = false; // NOT NULL field
        $this->iPseNeB1Titull->Required = true; // Required field
        $this->Fields['iPseNeB1Titull'] = &$this->iPseNeB1Titull;

        // iPseNeB1Txt
        $this->iPseNeB1Txt = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB1Txt',
            'iPseNeB1Txt',
            '`iPseNeB1Txt`',
            '`iPseNeB1Txt`',
            200,
            200,
            -1,
            false,
            '`iPseNeB1Txt`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB1Txt->InputTextType = "text";
        $this->iPseNeB1Txt->Nullable = false; // NOT NULL field
        $this->iPseNeB1Txt->Required = true; // Required field
        $this->Fields['iPseNeB1Txt'] = &$this->iPseNeB1Txt;

        // iPseNeB1Ikona
        $this->iPseNeB1Ikona = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB1Ikona',
            'iPseNeB1Ikona',
            '`iPseNeB1Ikona`',
            '`iPseNeB1Ikona`',
            200,
            250,
            -1,
            false,
            '`iPseNeB1Ikona`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB1Ikona->InputTextType = "text";
        $this->iPseNeB1Ikona->Nullable = false; // NOT NULL field
        $this->iPseNeB1Ikona->Required = true; // Required field
        $this->iPseNeB1Ikona->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->iPseNeB1Ikona->Param, "CustomMsg");
        $this->Fields['iPseNeB1Ikona'] = &$this->iPseNeB1Ikona;

        // iPseNeB2Titull
        $this->iPseNeB2Titull = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB2Titull',
            'iPseNeB2Titull',
            '`iPseNeB2Titull`',
            '`iPseNeB2Titull`',
            200,
            50,
            -1,
            false,
            '`iPseNeB2Titull`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB2Titull->InputTextType = "text";
        $this->iPseNeB2Titull->Nullable = false; // NOT NULL field
        $this->iPseNeB2Titull->Required = true; // Required field
        $this->Fields['iPseNeB2Titull'] = &$this->iPseNeB2Titull;

        // iPseNeB2Txt
        $this->iPseNeB2Txt = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB2Txt',
            'iPseNeB2Txt',
            '`iPseNeB2Txt`',
            '`iPseNeB2Txt`',
            200,
            200,
            -1,
            false,
            '`iPseNeB2Txt`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB2Txt->InputTextType = "text";
        $this->iPseNeB2Txt->Nullable = false; // NOT NULL field
        $this->iPseNeB2Txt->Required = true; // Required field
        $this->Fields['iPseNeB2Txt'] = &$this->iPseNeB2Txt;

        // iPseNeB2Ikona
        $this->iPseNeB2Ikona = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB2Ikona',
            'iPseNeB2Ikona',
            '`iPseNeB2Ikona`',
            '`iPseNeB2Ikona`',
            200,
            250,
            -1,
            false,
            '`iPseNeB2Ikona`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB2Ikona->InputTextType = "text";
        $this->iPseNeB2Ikona->Nullable = false; // NOT NULL field
        $this->iPseNeB2Ikona->Required = true; // Required field
        $this->iPseNeB2Ikona->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->iPseNeB2Ikona->Param, "CustomMsg");
        $this->Fields['iPseNeB2Ikona'] = &$this->iPseNeB2Ikona;

        // iPseNeB3Titull
        $this->iPseNeB3Titull = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB3Titull',
            'iPseNeB3Titull',
            '`iPseNeB3Titull`',
            '`iPseNeB3Titull`',
            200,
            50,
            -1,
            false,
            '`iPseNeB3Titull`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB3Titull->InputTextType = "text";
        $this->iPseNeB3Titull->Nullable = false; // NOT NULL field
        $this->iPseNeB3Titull->Required = true; // Required field
        $this->Fields['iPseNeB3Titull'] = &$this->iPseNeB3Titull;

        // iPseNeB3Txt
        $this->iPseNeB3Txt = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB3Txt',
            'iPseNeB3Txt',
            '`iPseNeB3Txt`',
            '`iPseNeB3Txt`',
            200,
            200,
            -1,
            false,
            '`iPseNeB3Txt`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB3Txt->InputTextType = "text";
        $this->iPseNeB3Txt->Nullable = false; // NOT NULL field
        $this->iPseNeB3Txt->Required = true; // Required field
        $this->Fields['iPseNeB3Txt'] = &$this->iPseNeB3Txt;

        // iPseNeB3Ikona
        $this->iPseNeB3Ikona = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB3Ikona',
            'iPseNeB3Ikona',
            '`iPseNeB3Ikona`',
            '`iPseNeB3Ikona`',
            200,
            250,
            -1,
            false,
            '`iPseNeB3Ikona`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB3Ikona->InputTextType = "text";
        $this->iPseNeB3Ikona->Nullable = false; // NOT NULL field
        $this->iPseNeB3Ikona->Required = true; // Required field
        $this->iPseNeB3Ikona->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->iPseNeB3Ikona->Param, "CustomMsg");
        $this->Fields['iPseNeB3Ikona'] = &$this->iPseNeB3Ikona;

        // iPseNeB4Titull
        $this->iPseNeB4Titull = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB4Titull',
            'iPseNeB4Titull',
            '`iPseNeB4Titull`',
            '`iPseNeB4Titull`',
            200,
            50,
            -1,
            false,
            '`iPseNeB4Titull`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB4Titull->InputTextType = "text";
        $this->iPseNeB4Titull->Nullable = false; // NOT NULL field
        $this->iPseNeB4Titull->Required = true; // Required field
        $this->Fields['iPseNeB4Titull'] = &$this->iPseNeB4Titull;

        // iPseNeB4Txt
        $this->iPseNeB4Txt = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB4Txt',
            'iPseNeB4Txt',
            '`iPseNeB4Txt`',
            '`iPseNeB4Txt`',
            200,
            200,
            -1,
            false,
            '`iPseNeB4Txt`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB4Txt->InputTextType = "text";
        $this->iPseNeB4Txt->Nullable = false; // NOT NULL field
        $this->iPseNeB4Txt->Required = true; // Required field
        $this->Fields['iPseNeB4Txt'] = &$this->iPseNeB4Txt;

        // iPseNeB4Ikona
        $this->iPseNeB4Ikona = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeB4Ikona',
            'iPseNeB4Ikona',
            '`iPseNeB4Ikona`',
            '`iPseNeB4Ikona`',
            200,
            250,
            -1,
            false,
            '`iPseNeB4Ikona`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->iPseNeB4Ikona->InputTextType = "text";
        $this->iPseNeB4Ikona->Nullable = false; // NOT NULL field
        $this->iPseNeB4Ikona->Required = true; // Required field
        $this->iPseNeB4Ikona->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->iPseNeB4Ikona->Param, "CustomMsg");
        $this->Fields['iPseNeB4Ikona'] = &$this->iPseNeB4Ikona;

        // iPseNeFoto
        $this->iPseNeFoto = new DbField(
            'index_psene',
            'index_psene',
            'x_iPseNeFoto',
            'iPseNeFoto',
            '`iPseNeFoto`',
            '`iPseNeFoto`',
            200,
            255,
            -1,
            true,
            '`iPseNeFoto`',
            false,
            false,
            false,
            'IMAGE',
            'FILE'
        );
        $this->iPseNeFoto->InputTextType = "text";
        $this->iPseNeFoto->Nullable = false; // NOT NULL field
        $this->iPseNeFoto->Required = true; // Required field
        $this->iPseNeFoto->UploadAllowedFileExt = "jpg,jpeg,gif,png,webo";
        $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
        $this->iPseNeFoto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->iPseNeFoto->Param, "CustomMsg");
        $this->Fields['iPseNeFoto'] = &$this->iPseNeFoto;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`index_psene`";
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
            $this->iPseNeID->setDbValue($conn->lastInsertId());
            $rs['iPseNeID'] = $this->iPseNeID->DbValue;
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
            if (array_key_exists('iPseNeID', $rs)) {
                AddFilter($where, QuotedName('iPseNeID', $this->Dbid) . '=' . QuotedValue($rs['iPseNeID'], $this->iPseNeID->DataType, $this->Dbid));
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
        $this->iPseNeID->DbValue = $row['iPseNeID'];
        $this->iPseNeGjuha->DbValue = $row['iPseNeGjuha'];
        $this->iPseNeB1Titull->DbValue = $row['iPseNeB1Titull'];
        $this->iPseNeB1Txt->DbValue = $row['iPseNeB1Txt'];
        $this->iPseNeB1Ikona->DbValue = $row['iPseNeB1Ikona'];
        $this->iPseNeB2Titull->DbValue = $row['iPseNeB2Titull'];
        $this->iPseNeB2Txt->DbValue = $row['iPseNeB2Txt'];
        $this->iPseNeB2Ikona->DbValue = $row['iPseNeB2Ikona'];
        $this->iPseNeB3Titull->DbValue = $row['iPseNeB3Titull'];
        $this->iPseNeB3Txt->DbValue = $row['iPseNeB3Txt'];
        $this->iPseNeB3Ikona->DbValue = $row['iPseNeB3Ikona'];
        $this->iPseNeB4Titull->DbValue = $row['iPseNeB4Titull'];
        $this->iPseNeB4Txt->DbValue = $row['iPseNeB4Txt'];
        $this->iPseNeB4Ikona->DbValue = $row['iPseNeB4Ikona'];
        $this->iPseNeFoto->Upload->DbValue = $row['iPseNeFoto'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $this->iPseNeFoto->OldUploadPath = '../ngarkime/index/psene/';
        $oldFiles = EmptyValue($row['iPseNeFoto']) ? [] : [$row['iPseNeFoto']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->iPseNeFoto->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->iPseNeFoto->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`iPseNeID` = @iPseNeID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->iPseNeID->CurrentValue : $this->iPseNeID->OldValue;
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
                $this->iPseNeID->CurrentValue = $keys[0];
            } else {
                $this->iPseNeID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('iPseNeID', $row) ? $row['iPseNeID'] : null;
        } else {
            $val = $this->iPseNeID->OldValue !== null ? $this->iPseNeID->OldValue : $this->iPseNeID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@iPseNeID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("IndexPseneList");
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
        if ($pageName == "IndexPseneView") {
            return $Language->phrase("View");
        } elseif ($pageName == "IndexPseneEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "IndexPseneAdd") {
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
                return "IndexPseneView";
            case Config("API_ADD_ACTION"):
                return "IndexPseneAdd";
            case Config("API_EDIT_ACTION"):
                return "IndexPseneEdit";
            case Config("API_DELETE_ACTION"):
                return "IndexPseneDelete";
            case Config("API_LIST_ACTION"):
                return "IndexPseneList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "IndexPseneList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("IndexPseneView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("IndexPseneView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "IndexPseneAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "IndexPseneAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("IndexPseneEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("IndexPseneAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("IndexPseneDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"iPseNeID\":" . JsonEncode($this->iPseNeID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->iPseNeID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->iPseNeID->CurrentValue);
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
            if (($keyValue = Param("iPseNeID") ?? Route("iPseNeID")) !== null) {
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
                $this->iPseNeID->CurrentValue = $key;
            } else {
                $this->iPseNeID->OldValue = $key;
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
        $this->iPseNeID->setDbValue($row['iPseNeID']);
        $this->iPseNeGjuha->setDbValue($row['iPseNeGjuha']);
        $this->iPseNeB1Titull->setDbValue($row['iPseNeB1Titull']);
        $this->iPseNeB1Txt->setDbValue($row['iPseNeB1Txt']);
        $this->iPseNeB1Ikona->setDbValue($row['iPseNeB1Ikona']);
        $this->iPseNeB2Titull->setDbValue($row['iPseNeB2Titull']);
        $this->iPseNeB2Txt->setDbValue($row['iPseNeB2Txt']);
        $this->iPseNeB2Ikona->setDbValue($row['iPseNeB2Ikona']);
        $this->iPseNeB3Titull->setDbValue($row['iPseNeB3Titull']);
        $this->iPseNeB3Txt->setDbValue($row['iPseNeB3Txt']);
        $this->iPseNeB3Ikona->setDbValue($row['iPseNeB3Ikona']);
        $this->iPseNeB4Titull->setDbValue($row['iPseNeB4Titull']);
        $this->iPseNeB4Txt->setDbValue($row['iPseNeB4Txt']);
        $this->iPseNeB4Ikona->setDbValue($row['iPseNeB4Ikona']);
        $this->iPseNeFoto->Upload->DbValue = $row['iPseNeFoto'];
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // iPseNeID
        $this->iPseNeID->CellCssStyle = "white-space: nowrap;";

        // iPseNeGjuha

        // iPseNeB1Titull

        // iPseNeB1Txt

        // iPseNeB1Ikona

        // iPseNeB2Titull

        // iPseNeB2Txt

        // iPseNeB2Ikona

        // iPseNeB3Titull

        // iPseNeB3Txt

        // iPseNeB3Ikona

        // iPseNeB4Titull

        // iPseNeB4Txt

        // iPseNeB4Ikona

        // iPseNeFoto

        // iPseNeID
        $this->iPseNeID->ViewValue = $this->iPseNeID->CurrentValue;
        $this->iPseNeID->ViewCustomAttributes = "";

        // iPseNeGjuha
        if (strval($this->iPseNeGjuha->CurrentValue) != "") {
            $this->iPseNeGjuha->ViewValue = $this->iPseNeGjuha->optionCaption($this->iPseNeGjuha->CurrentValue);
        } else {
            $this->iPseNeGjuha->ViewValue = null;
        }
        $this->iPseNeGjuha->CssClass = "fw-bold";
        $this->iPseNeGjuha->ViewCustomAttributes = "";

        // iPseNeB1Titull
        $this->iPseNeB1Titull->ViewValue = $this->iPseNeB1Titull->CurrentValue;
        $this->iPseNeB1Titull->ViewCustomAttributes = "";

        // iPseNeB1Txt
        $this->iPseNeB1Txt->ViewValue = $this->iPseNeB1Txt->CurrentValue;
        $this->iPseNeB1Txt->ViewCustomAttributes = "";

        // iPseNeB1Ikona
        $this->iPseNeB1Ikona->ViewValue = $this->iPseNeB1Ikona->CurrentValue;
        $this->iPseNeB1Ikona->ViewCustomAttributes = "";

        // iPseNeB2Titull
        $this->iPseNeB2Titull->ViewValue = $this->iPseNeB2Titull->CurrentValue;
        $this->iPseNeB2Titull->ViewCustomAttributes = "";

        // iPseNeB2Txt
        $this->iPseNeB2Txt->ViewValue = $this->iPseNeB2Txt->CurrentValue;
        $this->iPseNeB2Txt->ViewCustomAttributes = "";

        // iPseNeB2Ikona
        $this->iPseNeB2Ikona->ViewValue = $this->iPseNeB2Ikona->CurrentValue;
        $this->iPseNeB2Ikona->ViewCustomAttributes = "";

        // iPseNeB3Titull
        $this->iPseNeB3Titull->ViewValue = $this->iPseNeB3Titull->CurrentValue;
        $this->iPseNeB3Titull->ViewCustomAttributes = "";

        // iPseNeB3Txt
        $this->iPseNeB3Txt->ViewValue = $this->iPseNeB3Txt->CurrentValue;
        $this->iPseNeB3Txt->ViewCustomAttributes = "";

        // iPseNeB3Ikona
        $this->iPseNeB3Ikona->ViewValue = $this->iPseNeB3Ikona->CurrentValue;
        $this->iPseNeB3Ikona->ViewCustomAttributes = "";

        // iPseNeB4Titull
        $this->iPseNeB4Titull->ViewValue = $this->iPseNeB4Titull->CurrentValue;
        $this->iPseNeB4Titull->ViewCustomAttributes = "";

        // iPseNeB4Txt
        $this->iPseNeB4Txt->ViewValue = $this->iPseNeB4Txt->CurrentValue;
        $this->iPseNeB4Txt->ViewCustomAttributes = "";

        // iPseNeB4Ikona
        $this->iPseNeB4Ikona->ViewValue = $this->iPseNeB4Ikona->CurrentValue;
        $this->iPseNeB4Ikona->ViewCustomAttributes = "";

        // iPseNeFoto
        $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
        if (!EmptyValue($this->iPseNeFoto->Upload->DbValue)) {
            $this->iPseNeFoto->ImageWidth = 100;
            $this->iPseNeFoto->ImageHeight = 0;
            $this->iPseNeFoto->ImageAlt = $this->iPseNeFoto->alt();
            $this->iPseNeFoto->ImageCssClass = "ew-image";
            $this->iPseNeFoto->ViewValue = $this->iPseNeFoto->Upload->DbValue;
        } else {
            $this->iPseNeFoto->ViewValue = "";
        }
        $this->iPseNeFoto->ViewCustomAttributes = "";

        // iPseNeID
        $this->iPseNeID->LinkCustomAttributes = "";
        $this->iPseNeID->HrefValue = "";
        $this->iPseNeID->TooltipValue = "";

        // iPseNeGjuha
        $this->iPseNeGjuha->LinkCustomAttributes = "";
        $this->iPseNeGjuha->HrefValue = "";
        $this->iPseNeGjuha->TooltipValue = "";

        // iPseNeB1Titull
        $this->iPseNeB1Titull->LinkCustomAttributes = "";
        $this->iPseNeB1Titull->HrefValue = "";
        $this->iPseNeB1Titull->TooltipValue = "";

        // iPseNeB1Txt
        $this->iPseNeB1Txt->LinkCustomAttributes = "";
        $this->iPseNeB1Txt->HrefValue = "";
        $this->iPseNeB1Txt->TooltipValue = "";

        // iPseNeB1Ikona
        $this->iPseNeB1Ikona->LinkCustomAttributes = "";
        $this->iPseNeB1Ikona->HrefValue = "";
        $this->iPseNeB1Ikona->TooltipValue = "";

        // iPseNeB2Titull
        $this->iPseNeB2Titull->LinkCustomAttributes = "";
        $this->iPseNeB2Titull->HrefValue = "";
        $this->iPseNeB2Titull->TooltipValue = "";

        // iPseNeB2Txt
        $this->iPseNeB2Txt->LinkCustomAttributes = "";
        $this->iPseNeB2Txt->HrefValue = "";
        $this->iPseNeB2Txt->TooltipValue = "";

        // iPseNeB2Ikona
        $this->iPseNeB2Ikona->LinkCustomAttributes = "";
        $this->iPseNeB2Ikona->HrefValue = "";
        $this->iPseNeB2Ikona->TooltipValue = "";

        // iPseNeB3Titull
        $this->iPseNeB3Titull->LinkCustomAttributes = "";
        $this->iPseNeB3Titull->HrefValue = "";
        $this->iPseNeB3Titull->TooltipValue = "";

        // iPseNeB3Txt
        $this->iPseNeB3Txt->LinkCustomAttributes = "";
        $this->iPseNeB3Txt->HrefValue = "";
        $this->iPseNeB3Txt->TooltipValue = "";

        // iPseNeB3Ikona
        $this->iPseNeB3Ikona->LinkCustomAttributes = "";
        $this->iPseNeB3Ikona->HrefValue = "";
        $this->iPseNeB3Ikona->TooltipValue = "";

        // iPseNeB4Titull
        $this->iPseNeB4Titull->LinkCustomAttributes = "";
        $this->iPseNeB4Titull->HrefValue = "";
        $this->iPseNeB4Titull->TooltipValue = "";

        // iPseNeB4Txt
        $this->iPseNeB4Txt->LinkCustomAttributes = "";
        $this->iPseNeB4Txt->HrefValue = "";
        $this->iPseNeB4Txt->TooltipValue = "";

        // iPseNeB4Ikona
        $this->iPseNeB4Ikona->LinkCustomAttributes = "";
        $this->iPseNeB4Ikona->HrefValue = "";
        $this->iPseNeB4Ikona->TooltipValue = "";

        // iPseNeFoto
        $this->iPseNeFoto->LinkCustomAttributes = "";
        $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
        if (!EmptyValue($this->iPseNeFoto->Upload->DbValue)) {
            $this->iPseNeFoto->HrefValue = GetFileUploadUrl($this->iPseNeFoto, $this->iPseNeFoto->htmlDecode($this->iPseNeFoto->Upload->DbValue)); // Add prefix/suffix
            $this->iPseNeFoto->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->iPseNeFoto->HrefValue = FullUrl($this->iPseNeFoto->HrefValue, "href");
            }
        } else {
            $this->iPseNeFoto->HrefValue = "";
        }
        $this->iPseNeFoto->ExportHrefValue = $this->iPseNeFoto->UploadPath . $this->iPseNeFoto->Upload->DbValue;
        $this->iPseNeFoto->TooltipValue = "";
        if ($this->iPseNeFoto->UseColorbox) {
            if (EmptyValue($this->iPseNeFoto->TooltipValue)) {
                $this->iPseNeFoto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->iPseNeFoto->LinkAttrs["data-rel"] = "index_psene_x_iPseNeFoto";
            $this->iPseNeFoto->LinkAttrs->appendClass("ew-lightbox");
        }

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

        // iPseNeID
        $this->iPseNeID->setupEditAttributes();
        $this->iPseNeID->EditCustomAttributes = "";
        $this->iPseNeID->EditValue = $this->iPseNeID->CurrentValue;
        $this->iPseNeID->ViewCustomAttributes = "";

        // iPseNeGjuha
        $this->iPseNeGjuha->EditCustomAttributes = "";
        $this->iPseNeGjuha->EditValue = $this->iPseNeGjuha->options(false);
        $this->iPseNeGjuha->PlaceHolder = RemoveHtml($this->iPseNeGjuha->caption());

        // iPseNeB1Titull
        $this->iPseNeB1Titull->setupEditAttributes();
        $this->iPseNeB1Titull->EditCustomAttributes = "";
        if (!$this->iPseNeB1Titull->Raw) {
            $this->iPseNeB1Titull->CurrentValue = HtmlDecode($this->iPseNeB1Titull->CurrentValue);
        }
        $this->iPseNeB1Titull->EditValue = $this->iPseNeB1Titull->CurrentValue;
        $this->iPseNeB1Titull->PlaceHolder = RemoveHtml($this->iPseNeB1Titull->caption());

        // iPseNeB1Txt
        $this->iPseNeB1Txt->setupEditAttributes();
        $this->iPseNeB1Txt->EditCustomAttributes = "";
        if (!$this->iPseNeB1Txt->Raw) {
            $this->iPseNeB1Txt->CurrentValue = HtmlDecode($this->iPseNeB1Txt->CurrentValue);
        }
        $this->iPseNeB1Txt->EditValue = $this->iPseNeB1Txt->CurrentValue;
        $this->iPseNeB1Txt->PlaceHolder = RemoveHtml($this->iPseNeB1Txt->caption());

        // iPseNeB1Ikona
        $this->iPseNeB1Ikona->setupEditAttributes();
        $this->iPseNeB1Ikona->EditCustomAttributes = "";
        if (!$this->iPseNeB1Ikona->Raw) {
            $this->iPseNeB1Ikona->CurrentValue = HtmlDecode($this->iPseNeB1Ikona->CurrentValue);
        }
        $this->iPseNeB1Ikona->EditValue = $this->iPseNeB1Ikona->CurrentValue;
        $this->iPseNeB1Ikona->PlaceHolder = RemoveHtml($this->iPseNeB1Ikona->caption());

        // iPseNeB2Titull
        $this->iPseNeB2Titull->setupEditAttributes();
        $this->iPseNeB2Titull->EditCustomAttributes = "";
        if (!$this->iPseNeB2Titull->Raw) {
            $this->iPseNeB2Titull->CurrentValue = HtmlDecode($this->iPseNeB2Titull->CurrentValue);
        }
        $this->iPseNeB2Titull->EditValue = $this->iPseNeB2Titull->CurrentValue;
        $this->iPseNeB2Titull->PlaceHolder = RemoveHtml($this->iPseNeB2Titull->caption());

        // iPseNeB2Txt
        $this->iPseNeB2Txt->setupEditAttributes();
        $this->iPseNeB2Txt->EditCustomAttributes = "";
        if (!$this->iPseNeB2Txt->Raw) {
            $this->iPseNeB2Txt->CurrentValue = HtmlDecode($this->iPseNeB2Txt->CurrentValue);
        }
        $this->iPseNeB2Txt->EditValue = $this->iPseNeB2Txt->CurrentValue;
        $this->iPseNeB2Txt->PlaceHolder = RemoveHtml($this->iPseNeB2Txt->caption());

        // iPseNeB2Ikona
        $this->iPseNeB2Ikona->setupEditAttributes();
        $this->iPseNeB2Ikona->EditCustomAttributes = "";
        if (!$this->iPseNeB2Ikona->Raw) {
            $this->iPseNeB2Ikona->CurrentValue = HtmlDecode($this->iPseNeB2Ikona->CurrentValue);
        }
        $this->iPseNeB2Ikona->EditValue = $this->iPseNeB2Ikona->CurrentValue;
        $this->iPseNeB2Ikona->PlaceHolder = RemoveHtml($this->iPseNeB2Ikona->caption());

        // iPseNeB3Titull
        $this->iPseNeB3Titull->setupEditAttributes();
        $this->iPseNeB3Titull->EditCustomAttributes = "";
        if (!$this->iPseNeB3Titull->Raw) {
            $this->iPseNeB3Titull->CurrentValue = HtmlDecode($this->iPseNeB3Titull->CurrentValue);
        }
        $this->iPseNeB3Titull->EditValue = $this->iPseNeB3Titull->CurrentValue;
        $this->iPseNeB3Titull->PlaceHolder = RemoveHtml($this->iPseNeB3Titull->caption());

        // iPseNeB3Txt
        $this->iPseNeB3Txt->setupEditAttributes();
        $this->iPseNeB3Txt->EditCustomAttributes = "";
        if (!$this->iPseNeB3Txt->Raw) {
            $this->iPseNeB3Txt->CurrentValue = HtmlDecode($this->iPseNeB3Txt->CurrentValue);
        }
        $this->iPseNeB3Txt->EditValue = $this->iPseNeB3Txt->CurrentValue;
        $this->iPseNeB3Txt->PlaceHolder = RemoveHtml($this->iPseNeB3Txt->caption());

        // iPseNeB3Ikona
        $this->iPseNeB3Ikona->setupEditAttributes();
        $this->iPseNeB3Ikona->EditCustomAttributes = "";
        if (!$this->iPseNeB3Ikona->Raw) {
            $this->iPseNeB3Ikona->CurrentValue = HtmlDecode($this->iPseNeB3Ikona->CurrentValue);
        }
        $this->iPseNeB3Ikona->EditValue = $this->iPseNeB3Ikona->CurrentValue;
        $this->iPseNeB3Ikona->PlaceHolder = RemoveHtml($this->iPseNeB3Ikona->caption());

        // iPseNeB4Titull
        $this->iPseNeB4Titull->setupEditAttributes();
        $this->iPseNeB4Titull->EditCustomAttributes = "";
        if (!$this->iPseNeB4Titull->Raw) {
            $this->iPseNeB4Titull->CurrentValue = HtmlDecode($this->iPseNeB4Titull->CurrentValue);
        }
        $this->iPseNeB4Titull->EditValue = $this->iPseNeB4Titull->CurrentValue;
        $this->iPseNeB4Titull->PlaceHolder = RemoveHtml($this->iPseNeB4Titull->caption());

        // iPseNeB4Txt
        $this->iPseNeB4Txt->setupEditAttributes();
        $this->iPseNeB4Txt->EditCustomAttributes = "";
        if (!$this->iPseNeB4Txt->Raw) {
            $this->iPseNeB4Txt->CurrentValue = HtmlDecode($this->iPseNeB4Txt->CurrentValue);
        }
        $this->iPseNeB4Txt->EditValue = $this->iPseNeB4Txt->CurrentValue;
        $this->iPseNeB4Txt->PlaceHolder = RemoveHtml($this->iPseNeB4Txt->caption());

        // iPseNeB4Ikona
        $this->iPseNeB4Ikona->setupEditAttributes();
        $this->iPseNeB4Ikona->EditCustomAttributes = "";
        if (!$this->iPseNeB4Ikona->Raw) {
            $this->iPseNeB4Ikona->CurrentValue = HtmlDecode($this->iPseNeB4Ikona->CurrentValue);
        }
        $this->iPseNeB4Ikona->EditValue = $this->iPseNeB4Ikona->CurrentValue;
        $this->iPseNeB4Ikona->PlaceHolder = RemoveHtml($this->iPseNeB4Ikona->caption());

        // iPseNeFoto
        $this->iPseNeFoto->setupEditAttributes();
        $this->iPseNeFoto->EditCustomAttributes = "";
        $this->iPseNeFoto->UploadPath = '../ngarkime/index/psene/';
        if (!EmptyValue($this->iPseNeFoto->Upload->DbValue)) {
            $this->iPseNeFoto->ImageWidth = 100;
            $this->iPseNeFoto->ImageHeight = 0;
            $this->iPseNeFoto->ImageAlt = $this->iPseNeFoto->alt();
            $this->iPseNeFoto->ImageCssClass = "ew-image";
            $this->iPseNeFoto->EditValue = $this->iPseNeFoto->Upload->DbValue;
        } else {
            $this->iPseNeFoto->EditValue = "";
        }
        if (!EmptyValue($this->iPseNeFoto->CurrentValue)) {
            $this->iPseNeFoto->Upload->FileName = $this->iPseNeFoto->CurrentValue;
        }

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
                    $doc->exportCaption($this->iPseNeGjuha);
                    $doc->exportCaption($this->iPseNeB1Titull);
                    $doc->exportCaption($this->iPseNeB1Txt);
                    $doc->exportCaption($this->iPseNeB1Ikona);
                    $doc->exportCaption($this->iPseNeB2Titull);
                    $doc->exportCaption($this->iPseNeB2Txt);
                    $doc->exportCaption($this->iPseNeB2Ikona);
                    $doc->exportCaption($this->iPseNeB3Titull);
                    $doc->exportCaption($this->iPseNeB3Txt);
                    $doc->exportCaption($this->iPseNeB3Ikona);
                    $doc->exportCaption($this->iPseNeB4Titull);
                    $doc->exportCaption($this->iPseNeB4Txt);
                    $doc->exportCaption($this->iPseNeB4Ikona);
                    $doc->exportCaption($this->iPseNeFoto);
                } else {
                    $doc->exportCaption($this->iPseNeGjuha);
                    $doc->exportCaption($this->iPseNeB1Titull);
                    $doc->exportCaption($this->iPseNeB1Txt);
                    $doc->exportCaption($this->iPseNeB1Ikona);
                    $doc->exportCaption($this->iPseNeB2Titull);
                    $doc->exportCaption($this->iPseNeB2Txt);
                    $doc->exportCaption($this->iPseNeB2Ikona);
                    $doc->exportCaption($this->iPseNeB3Titull);
                    $doc->exportCaption($this->iPseNeB3Txt);
                    $doc->exportCaption($this->iPseNeB3Ikona);
                    $doc->exportCaption($this->iPseNeB4Titull);
                    $doc->exportCaption($this->iPseNeB4Txt);
                    $doc->exportCaption($this->iPseNeB4Ikona);
                    $doc->exportCaption($this->iPseNeFoto);
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
                        $doc->exportField($this->iPseNeGjuha);
                        $doc->exportField($this->iPseNeB1Titull);
                        $doc->exportField($this->iPseNeB1Txt);
                        $doc->exportField($this->iPseNeB1Ikona);
                        $doc->exportField($this->iPseNeB2Titull);
                        $doc->exportField($this->iPseNeB2Txt);
                        $doc->exportField($this->iPseNeB2Ikona);
                        $doc->exportField($this->iPseNeB3Titull);
                        $doc->exportField($this->iPseNeB3Txt);
                        $doc->exportField($this->iPseNeB3Ikona);
                        $doc->exportField($this->iPseNeB4Titull);
                        $doc->exportField($this->iPseNeB4Txt);
                        $doc->exportField($this->iPseNeB4Ikona);
                        $doc->exportField($this->iPseNeFoto);
                    } else {
                        $doc->exportField($this->iPseNeGjuha);
                        $doc->exportField($this->iPseNeB1Titull);
                        $doc->exportField($this->iPseNeB1Txt);
                        $doc->exportField($this->iPseNeB1Ikona);
                        $doc->exportField($this->iPseNeB2Titull);
                        $doc->exportField($this->iPseNeB2Txt);
                        $doc->exportField($this->iPseNeB2Ikona);
                        $doc->exportField($this->iPseNeB3Titull);
                        $doc->exportField($this->iPseNeB3Txt);
                        $doc->exportField($this->iPseNeB3Ikona);
                        $doc->exportField($this->iPseNeB4Titull);
                        $doc->exportField($this->iPseNeB4Txt);
                        $doc->exportField($this->iPseNeB4Ikona);
                        $doc->exportField($this->iPseNeFoto);
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
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'iPseNeFoto') {
            $fldName = "iPseNeFoto";
            $fileNameFld = "iPseNeFoto";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->iPseNeID->CurrentValue = $ar[0];
        } else {
            return false; // Incorrect key
        }

        // Set up filter (WHERE Clause)
        $filter = $this->getRecordFilter();
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $dbtype = GetConnectionType($this->Dbid);
        if ($row = $conn->fetchAssociative($sql)) {
            $val = $row[$fldName];
            if (!EmptyValue($val)) {
                $fld = $this->Fields[$fldName];

                // Binary data
                if ($fld->DataType == DATATYPE_BLOB) {
                    if ($dbtype != "MYSQL") {
                        if (is_resource($val) && get_resource_type($val) == "stream") { // Byte array
                            $val = stream_get_contents($val);
                        }
                    }
                    if ($resize) {
                        ResizeBinary($val, $width, $height, $plugins);
                    }

                    // Write file type
                    if ($fileTypeFld != "" && !EmptyValue($row[$fileTypeFld])) {
                        AddHeader("Content-type", $row[$fileTypeFld]);
                    } else {
                        AddHeader("Content-type", ContentType($val));
                    }

                    // Write file name
                    $downloadPdf = !Config("EMBED_PDF") && Config("DOWNLOAD_PDF_FILE");
                    if ($fileNameFld != "" && !EmptyValue($row[$fileNameFld])) {
                        $fileName = $row[$fileNameFld];
                        $pathinfo = pathinfo($fileName);
                        $ext = strtolower(@$pathinfo["extension"]);
                        $isPdf = SameText($ext, "pdf");
                        if ($downloadPdf || !$isPdf) { // Skip header if not download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    } else {
                        $ext = ContentExtension($val);
                        $isPdf = SameText($ext, ".pdf");
                        if ($isPdf && $downloadPdf) { // Add header if download PDF
                            AddHeader("Content-Disposition", "attachment; filename=\"" . $fileName . "\"");
                        }
                    }

                    // Write file data
                    if (
                        StartsString("PK", $val) &&
                        ContainsString($val, "[Content_Types].xml") &&
                        ContainsString($val, "_rels") &&
                        ContainsString($val, "docProps")
                    ) { // Fix Office 2007 documents
                        if (!EndsString("\0\0\0", $val)) { // Not ends with 3 or 4 \0
                            $val .= "\0\0\0\0";
                        }
                    }

                    // Clear any debug message
                    if (ob_get_length()) {
                        ob_end_clean();
                    }

                    // Write binary data
                    Write($val);

                // Upload to folder
                } else {
                    if ($fld->UploadMultiple) {
                        $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                    } else {
                        $files = [$val];
                    }
                    $data = [];
                    $ar = [];
                    foreach ($files as $file) {
                        if (!EmptyValue($file)) {
                            if (Config("ENCRYPT_FILE_PATH")) {
                                $ar[$file] = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $this->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                            } else {
                                $ar[$file] = FullUrl($fld->hrefPath() . $file);
                            }
                        }
                    }
                    $data[$fld->Param] = $ar;
                    WriteJson($data);
                }
            }
            return true;
        }
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
