<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for servis_pjeset
 */
class ServisPjeset extends DbTable
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
    public $servisPjeseID;
    public $servisPjeseServisID;
    public $servisPjesePjesa;
    public $servisPjeseSasia;
    public $servisPjeseCmimi;
    public $servisPjeseShenim;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'servis_pjeset';
        $this->TableName = 'servis_pjeset';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`servis_pjeset`";
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
        $this->ShowMultipleDetails = false; // Show multiple details
        $this->GridAddRowCount = 5;
        $this->AllowAddDeleteRow = true; // Allow add/delete row
        $this->UserIDAllowSecurity = Config("DEFAULT_USER_ID_ALLOW_SECURITY"); // Default User ID allowed permissions
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // servisPjeseID
        $this->servisPjeseID = new DbField(
            'servis_pjeset',
            'servis_pjeset',
            'x_servisPjeseID',
            'servisPjeseID',
            '`servisPjeseID`',
            '`servisPjeseID`',
            3,
            255,
            -1,
            false,
            '`servisPjeseID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->servisPjeseID->InputTextType = "text";
        $this->servisPjeseID->IsAutoIncrement = true; // Autoincrement field
        $this->servisPjeseID->IsPrimaryKey = true; // Primary key field
        $this->servisPjeseID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['servisPjeseID'] = &$this->servisPjeseID;

        // servisPjeseServisID
        $this->servisPjeseServisID = new DbField(
            'servis_pjeset',
            'servis_pjeset',
            'x_servisPjeseServisID',
            'servisPjeseServisID',
            '`servisPjeseServisID`',
            '`servisPjeseServisID`',
            3,
            255,
            -1,
            false,
            '`servisPjeseServisID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisPjeseServisID->InputTextType = "text";
        $this->servisPjeseServisID->IsForeignKey = true; // Foreign key field
        $this->servisPjeseServisID->Nullable = false; // NOT NULL field
        $this->servisPjeseServisID->Required = true; // Required field
        $this->servisPjeseServisID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['servisPjeseServisID'] = &$this->servisPjeseServisID;

        // servisPjesePjesa
        $this->servisPjesePjesa = new DbField(
            'servis_pjeset',
            'servis_pjeset',
            'x_servisPjesePjesa',
            'servisPjesePjesa',
            '`servisPjesePjesa`',
            '`servisPjesePjesa`',
            3,
            255,
            -1,
            false,
            '`EV__servisPjesePjesa`',
            true,
            true,
            true,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->servisPjesePjesa->InputTextType = "text";
        $this->servisPjesePjesa->Nullable = false; // NOT NULL field
        $this->servisPjesePjesa->Required = true; // Required field
        $this->servisPjesePjesa->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->servisPjesePjesa->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->servisPjesePjesa->Lookup = new Lookup('servisPjesePjesa', 'pjese_kembimi', true, 'pjeseID', ["pjeseKodiVolvo","","",""], [], [], [], [], [], [], '', '', "`pjeseKodiVolvo`");
        $this->servisPjesePjesa->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['servisPjesePjesa'] = &$this->servisPjesePjesa;

        // servisPjeseSasia
        $this->servisPjeseSasia = new DbField(
            'servis_pjeset',
            'servis_pjeset',
            'x_servisPjeseSasia',
            'servisPjeseSasia',
            '`servisPjeseSasia`',
            '`servisPjeseSasia`',
            3,
            5,
            -1,
            false,
            '`servisPjeseSasia`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisPjeseSasia->InputTextType = "text";
        $this->servisPjeseSasia->Nullable = false; // NOT NULL field
        $this->servisPjeseSasia->Required = true; // Required field
        $this->servisPjeseSasia->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['servisPjeseSasia'] = &$this->servisPjeseSasia;

        // servisPjeseCmimi
        $this->servisPjeseCmimi = new DbField(
            'servis_pjeset',
            'servis_pjeset',
            'x_servisPjeseCmimi',
            'servisPjeseCmimi',
            '`servisPjeseCmimi`',
            '`servisPjeseCmimi`',
            5,
            10,
            -1,
            false,
            '`servisPjeseCmimi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisPjeseCmimi->InputTextType = "text";
        $this->servisPjeseCmimi->Nullable = false; // NOT NULL field
        $this->servisPjeseCmimi->Required = true; // Required field
        $this->servisPjeseCmimi->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->Fields['servisPjeseCmimi'] = &$this->servisPjeseCmimi;

        // servisPjeseShenim
        $this->servisPjeseShenim = new DbField(
            'servis_pjeset',
            'servis_pjeset',
            'x_servisPjeseShenim',
            'servisPjeseShenim',
            '`servisPjeseShenim`',
            '`servisPjeseShenim`',
            200,
            250,
            -1,
            false,
            '`servisPjeseShenim`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->servisPjeseShenim->InputTextType = "text";
        $this->Fields['servisPjeseShenim'] = &$this->servisPjeseShenim;

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

    // Current master table name
    public function getCurrentMasterTable()
    {
        return Session(PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE"));
    }

    public function setCurrentMasterTable($v)
    {
        $_SESSION[PROJECT_NAME . "_" . $this->TableVar . "_" . Config("TABLE_MASTER_TABLE")] = $v;
    }

    // Get master WHERE clause from session values
    public function getMasterFilterFromSession()
    {
        // Master filter
        $masterFilter = "";
        if ($this->getCurrentMasterTable() == "servis") {
            if ($this->servisPjeseServisID->getSessionValue() != "") {
                $masterFilter .= "" . GetForeignKeySql("`servisID`", $this->servisPjeseServisID->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $masterFilter;
    }

    // Get detail WHERE clause from session values
    public function getDetailFilterFromSession()
    {
        // Detail filter
        $detailFilter = "";
        if ($this->getCurrentMasterTable() == "servis") {
            if ($this->servisPjeseServisID->getSessionValue() != "") {
                $detailFilter .= "" . GetForeignKeySql("`servisPjeseServisID`", $this->servisPjeseServisID->getSessionValue(), DATATYPE_NUMBER, "DB");
            } else {
                return "";
            }
        }
        return $detailFilter;
    }

    /**
     * Get master filter
     *
     * @param object $masterTable Master Table
     * @param array $keys Detail Keys
     * @return mixed NULL is returned if all keys are empty, Empty string is returned if some keys are empty and is required
     */
    public function getMasterFilter($masterTable, $keys)
    {
        $validKeys = true;
        switch ($masterTable->TableVar) {
            case "servis":
                $key = $keys["servisPjeseServisID"] ?? "";
                if (EmptyValue($key)) {
                    if ($masterTable->servisID->Required) { // Required field and empty value
                        return ""; // Return empty filter
                    }
                    $validKeys = false;
                } elseif (!$validKeys) { // Already has empty key
                    return ""; // Return empty filter
                }
                if ($validKeys) {
                    return "`servisID`=" . QuotedValue($keys["servisPjeseServisID"], $masterTable->servisID->DataType, $masterTable->Dbid);
                }
                break;
        }
        return null; // All null values and no required fields
    }

    // Get detail filter
    public function getDetailFilter($masterTable)
    {
        switch ($masterTable->TableVar) {
            case "servis":
                return "`servisPjeseServisID`=" . QuotedValue($masterTable->servisID->DbValue, $this->servisPjeseServisID->DataType, $this->Dbid);
        }
        return "";
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`servis_pjeset`";
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
        $from = "(SELECT *, (SELECT DISTINCT `pjeseKodiVolvo` FROM `pjese_kembimi` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`pjeseID` = `servis_pjeset`.`servisPjesePjesa` LIMIT 1) AS `EV__servisPjesePjesa` FROM `servis_pjeset`)";
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
            $this->servisPjesePjesa->AdvancedSearch->SearchValue != "" ||
            $this->servisPjesePjesa->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->servisPjesePjesa->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->servisPjesePjesa->VirtualExpression . " ")) {
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
            $this->servisPjeseID->setDbValue($conn->lastInsertId());
            $rs['servisPjeseID'] = $this->servisPjeseID->DbValue;
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
            if (array_key_exists('servisPjeseID', $rs)) {
                AddFilter($where, QuotedName('servisPjeseID', $this->Dbid) . '=' . QuotedValue($rs['servisPjeseID'], $this->servisPjeseID->DataType, $this->Dbid));
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
        $this->servisPjeseID->DbValue = $row['servisPjeseID'];
        $this->servisPjeseServisID->DbValue = $row['servisPjeseServisID'];
        $this->servisPjesePjesa->DbValue = $row['servisPjesePjesa'];
        $this->servisPjeseSasia->DbValue = $row['servisPjeseSasia'];
        $this->servisPjeseCmimi->DbValue = $row['servisPjeseCmimi'];
        $this->servisPjeseShenim->DbValue = $row['servisPjeseShenim'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`servisPjeseID` = @servisPjeseID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->servisPjeseID->CurrentValue : $this->servisPjeseID->OldValue;
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
                $this->servisPjeseID->CurrentValue = $keys[0];
            } else {
                $this->servisPjeseID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('servisPjeseID', $row) ? $row['servisPjeseID'] : null;
        } else {
            $val = $this->servisPjeseID->OldValue !== null ? $this->servisPjeseID->OldValue : $this->servisPjeseID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@servisPjeseID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("ServisPjesetList");
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
        if ($pageName == "ServisPjesetView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ServisPjesetEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ServisPjesetAdd") {
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
                return "ServisPjesetView";
            case Config("API_ADD_ACTION"):
                return "ServisPjesetAdd";
            case Config("API_EDIT_ACTION"):
                return "ServisPjesetEdit";
            case Config("API_DELETE_ACTION"):
                return "ServisPjesetDelete";
            case Config("API_LIST_ACTION"):
                return "ServisPjesetList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ServisPjesetList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ServisPjesetView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ServisPjesetView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ServisPjesetAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ServisPjesetAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ServisPjesetEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("ServisPjesetAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("ServisPjesetDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        if ($this->getCurrentMasterTable() == "servis" && !ContainsString($url, Config("TABLE_SHOW_MASTER") . "=")) {
            $url .= (ContainsString($url, "?") ? "&" : "?") . Config("TABLE_SHOW_MASTER") . "=" . $this->getCurrentMasterTable();
            $url .= "&" . GetForeignKeyUrl("fk_servisID", $this->servisPjeseServisID->CurrentValue);
        }
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"servisPjeseID\":" . JsonEncode($this->servisPjeseID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->servisPjeseID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->servisPjeseID->CurrentValue);
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
            if (($keyValue = Param("servisPjeseID") ?? Route("servisPjeseID")) !== null) {
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
                $this->servisPjeseID->CurrentValue = $key;
            } else {
                $this->servisPjeseID->OldValue = $key;
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
        $this->servisPjeseID->setDbValue($row['servisPjeseID']);
        $this->servisPjeseServisID->setDbValue($row['servisPjeseServisID']);
        $this->servisPjesePjesa->setDbValue($row['servisPjesePjesa']);
        $this->servisPjeseSasia->setDbValue($row['servisPjeseSasia']);
        $this->servisPjeseCmimi->setDbValue($row['servisPjeseCmimi']);
        $this->servisPjeseShenim->setDbValue($row['servisPjeseShenim']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // servisPjeseID

        // servisPjeseServisID

        // servisPjesePjesa

        // servisPjeseSasia

        // servisPjeseCmimi

        // servisPjeseShenim

        // servisPjeseID
        $this->servisPjeseID->ViewValue = $this->servisPjeseID->CurrentValue;
        $this->servisPjeseID->ViewCustomAttributes = "";

        // servisPjeseServisID
        $this->servisPjeseServisID->ViewValue = $this->servisPjeseServisID->CurrentValue;
        $this->servisPjeseServisID->ViewValue = FormatNumber($this->servisPjeseServisID->ViewValue, $this->servisPjeseServisID->formatPattern());
        $this->servisPjeseServisID->ViewCustomAttributes = "";

        // servisPjesePjesa
        if ($this->servisPjesePjesa->VirtualValue != "") {
            $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->VirtualValue;
        } else {
            $curVal = strval($this->servisPjesePjesa->CurrentValue);
            if ($curVal != "") {
                $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->lookupCacheOption($curVal);
                if ($this->servisPjesePjesa->ViewValue === null) { // Lookup from database
                    $filterWrk = "`pjeseID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->servisPjesePjesa->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->servisPjesePjesa->Lookup->renderViewRow($rswrk[0]);
                        $this->servisPjesePjesa->ViewValue = $this->servisPjesePjesa->displayValue($arwrk);
                    } else {
                        $this->servisPjesePjesa->ViewValue = FormatNumber($this->servisPjesePjesa->CurrentValue, $this->servisPjesePjesa->formatPattern());
                    }
                }
            } else {
                $this->servisPjesePjesa->ViewValue = null;
            }
        }
        $this->servisPjesePjesa->ViewCustomAttributes = "";

        // servisPjeseSasia
        $this->servisPjeseSasia->ViewValue = $this->servisPjeseSasia->CurrentValue;
        $this->servisPjeseSasia->ViewValue = FormatNumber($this->servisPjeseSasia->ViewValue, $this->servisPjeseSasia->formatPattern());
        $this->servisPjeseSasia->ViewCustomAttributes = "";

        // servisPjeseCmimi
        $this->servisPjeseCmimi->ViewValue = $this->servisPjeseCmimi->CurrentValue;
        $this->servisPjeseCmimi->ViewValue = FormatNumber($this->servisPjeseCmimi->ViewValue, $this->servisPjeseCmimi->formatPattern());
        $this->servisPjeseCmimi->ViewCustomAttributes = "";

        // servisPjeseShenim
        $this->servisPjeseShenim->ViewValue = $this->servisPjeseShenim->CurrentValue;
        $this->servisPjeseShenim->ViewCustomAttributes = "";

        // servisPjeseID
        $this->servisPjeseID->LinkCustomAttributes = "";
        $this->servisPjeseID->HrefValue = "";
        $this->servisPjeseID->TooltipValue = "";

        // servisPjeseServisID
        $this->servisPjeseServisID->LinkCustomAttributes = "";
        $this->servisPjeseServisID->HrefValue = "";
        $this->servisPjeseServisID->TooltipValue = "";

        // servisPjesePjesa
        $this->servisPjesePjesa->LinkCustomAttributes = "";
        $this->servisPjesePjesa->HrefValue = "";
        $this->servisPjesePjesa->TooltipValue = "";

        // servisPjeseSasia
        $this->servisPjeseSasia->LinkCustomAttributes = "";
        $this->servisPjeseSasia->HrefValue = "";
        $this->servisPjeseSasia->TooltipValue = "";

        // servisPjeseCmimi
        $this->servisPjeseCmimi->LinkCustomAttributes = "";
        $this->servisPjeseCmimi->HrefValue = "";
        $this->servisPjeseCmimi->TooltipValue = "";

        // servisPjeseShenim
        $this->servisPjeseShenim->LinkCustomAttributes = "";
        $this->servisPjeseShenim->HrefValue = "";
        $this->servisPjeseShenim->TooltipValue = "";

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

        // servisPjeseID
        $this->servisPjeseID->setupEditAttributes();
        $this->servisPjeseID->EditCustomAttributes = "";
        $this->servisPjeseID->EditValue = $this->servisPjeseID->CurrentValue;
        $this->servisPjeseID->ViewCustomAttributes = "";

        // servisPjeseServisID
        $this->servisPjeseServisID->setupEditAttributes();
        $this->servisPjeseServisID->EditCustomAttributes = "";
        if ($this->servisPjeseServisID->getSessionValue() != "") {
            $this->servisPjeseServisID->CurrentValue = GetForeignKeyValue($this->servisPjeseServisID->getSessionValue());
            $this->servisPjeseServisID->ViewValue = $this->servisPjeseServisID->CurrentValue;
            $this->servisPjeseServisID->ViewValue = FormatNumber($this->servisPjeseServisID->ViewValue, $this->servisPjeseServisID->formatPattern());
            $this->servisPjeseServisID->ViewCustomAttributes = "";
        } else {
            $this->servisPjeseServisID->EditValue = $this->servisPjeseServisID->CurrentValue;
            $this->servisPjeseServisID->PlaceHolder = RemoveHtml($this->servisPjeseServisID->caption());
            if (strval($this->servisPjeseServisID->EditValue) != "" && is_numeric($this->servisPjeseServisID->EditValue)) {
                $this->servisPjeseServisID->EditValue = FormatNumber($this->servisPjeseServisID->EditValue, null);
            }
        }

        // servisPjesePjesa
        $this->servisPjesePjesa->setupEditAttributes();
        $this->servisPjesePjesa->EditCustomAttributes = "";
        $this->servisPjesePjesa->PlaceHolder = RemoveHtml($this->servisPjesePjesa->caption());

        // servisPjeseSasia
        $this->servisPjeseSasia->setupEditAttributes();
        $this->servisPjeseSasia->EditCustomAttributes = "";
        $this->servisPjeseSasia->EditValue = $this->servisPjeseSasia->CurrentValue;
        $this->servisPjeseSasia->PlaceHolder = RemoveHtml($this->servisPjeseSasia->caption());
        if (strval($this->servisPjeseSasia->EditValue) != "" && is_numeric($this->servisPjeseSasia->EditValue)) {
            $this->servisPjeseSasia->EditValue = FormatNumber($this->servisPjeseSasia->EditValue, null);
        }

        // servisPjeseCmimi
        $this->servisPjeseCmimi->setupEditAttributes();
        $this->servisPjeseCmimi->EditCustomAttributes = "";
        $this->servisPjeseCmimi->EditValue = $this->servisPjeseCmimi->CurrentValue;
        $this->servisPjeseCmimi->PlaceHolder = RemoveHtml($this->servisPjeseCmimi->caption());
        if (strval($this->servisPjeseCmimi->EditValue) != "" && is_numeric($this->servisPjeseCmimi->EditValue)) {
            $this->servisPjeseCmimi->EditValue = FormatNumber($this->servisPjeseCmimi->EditValue, null);
        }

        // servisPjeseShenim
        $this->servisPjeseShenim->setupEditAttributes();
        $this->servisPjeseShenim->EditCustomAttributes = "";
        if (!$this->servisPjeseShenim->Raw) {
            $this->servisPjeseShenim->CurrentValue = HtmlDecode($this->servisPjeseShenim->CurrentValue);
        }
        $this->servisPjeseShenim->EditValue = $this->servisPjeseShenim->CurrentValue;
        $this->servisPjeseShenim->PlaceHolder = RemoveHtml($this->servisPjeseShenim->caption());

        // Call Row Rendered event
        $this->rowRendered();
    }

    // Aggregate list row values
    public function aggregateListRowValues()
    {
            if (is_numeric($this->servisPjeseCmimi->CurrentValue)) {
                $this->servisPjeseCmimi->Total += $this->servisPjeseCmimi->CurrentValue; // Accumulate total
            }
    }

    // Aggregate list row (for rendering)
    public function aggregateListRow()
    {
            $this->servisPjeseCmimi->CurrentValue = $this->servisPjeseCmimi->Total;
            $this->servisPjeseCmimi->ViewValue = $this->servisPjeseCmimi->CurrentValue;
            $this->servisPjeseCmimi->ViewValue = FormatNumber($this->servisPjeseCmimi->ViewValue, $this->servisPjeseCmimi->formatPattern());
            $this->servisPjeseCmimi->ViewCustomAttributes = "";
            $this->servisPjeseCmimi->HrefValue = ""; // Clear href value

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
                    $doc->exportCaption($this->servisPjeseID);
                    $doc->exportCaption($this->servisPjeseServisID);
                    $doc->exportCaption($this->servisPjesePjesa);
                    $doc->exportCaption($this->servisPjeseSasia);
                    $doc->exportCaption($this->servisPjeseCmimi);
                    $doc->exportCaption($this->servisPjeseShenim);
                } else {
                    $doc->exportCaption($this->servisPjeseID);
                    $doc->exportCaption($this->servisPjeseServisID);
                    $doc->exportCaption($this->servisPjesePjesa);
                    $doc->exportCaption($this->servisPjeseSasia);
                    $doc->exportCaption($this->servisPjeseCmimi);
                    $doc->exportCaption($this->servisPjeseShenim);
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
                        $doc->exportField($this->servisPjeseID);
                        $doc->exportField($this->servisPjeseServisID);
                        $doc->exportField($this->servisPjesePjesa);
                        $doc->exportField($this->servisPjeseSasia);
                        $doc->exportField($this->servisPjeseCmimi);
                        $doc->exportField($this->servisPjeseShenim);
                    } else {
                        $doc->exportField($this->servisPjeseID);
                        $doc->exportField($this->servisPjeseServisID);
                        $doc->exportField($this->servisPjesePjesa);
                        $doc->exportField($this->servisPjeseSasia);
                        $doc->exportField($this->servisPjeseCmimi);
                        $doc->exportField($this->servisPjeseShenim);
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
                $doc->exportAggregate($this->servisPjeseID, '');
                $doc->exportAggregate($this->servisPjeseServisID, '');
                $doc->exportAggregate($this->servisPjesePjesa, '');
                $doc->exportAggregate($this->servisPjeseSasia, '');
                $doc->exportAggregate($this->servisPjeseCmimi, 'TOTAL');
                $doc->exportAggregate($this->servisPjeseShenim, '');
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
