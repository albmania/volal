<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for menu_kryesore
 */
class MenuKryesore extends DbTable
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
    public $menukID;
    public $menukGjuha;
    public $menukTitull;
    public $menukUrl;
    public $menukBlank;
    public $menukRadhe;
    public $menukAktiv;
    public $menukAutor;
    public $menukKrijuar;
    public $menukAzhornuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'menu_kryesore';
        $this->TableName = 'menu_kryesore';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`menu_kryesore`";
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

        // menukID
        $this->menukID = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukID',
            'menukID',
            '`menukID`',
            '`menukID`',
            3,
            255,
            -1,
            false,
            '`menukID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->menukID->InputTextType = "text";
        $this->menukID->IsAutoIncrement = true; // Autoincrement field
        $this->menukID->IsPrimaryKey = true; // Primary key field
        $this->menukID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['menukID'] = &$this->menukID;

        // menukGjuha
        $this->menukGjuha = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukGjuha',
            'menukGjuha',
            '`menukGjuha`',
            '`menukGjuha`',
            202,
            2,
            -1,
            false,
            '`menukGjuha`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->menukGjuha->InputTextType = "text";
        $this->menukGjuha->Nullable = false; // NOT NULL field
        $this->menukGjuha->Required = true; // Required field
        $this->menukGjuha->Lookup = new Lookup('menukGjuha', 'menu_kryesore', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->menukGjuha->OptionCount = 2;
        $this->Fields['menukGjuha'] = &$this->menukGjuha;

        // menukTitull
        $this->menukTitull = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukTitull',
            'menukTitull',
            '`menukTitull`',
            '`menukTitull`',
            200,
            255,
            -1,
            false,
            '`menukTitull`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menukTitull->InputTextType = "text";
        $this->menukTitull->Nullable = false; // NOT NULL field
        $this->menukTitull->Required = true; // Required field
        $this->Fields['menukTitull'] = &$this->menukTitull;

        // menukUrl
        $this->menukUrl = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukUrl',
            'menukUrl',
            '`menukUrl`',
            '`menukUrl`',
            200,
            255,
            -1,
            false,
            '`menukUrl`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menukUrl->InputTextType = "text";
        $this->menukUrl->Nullable = false; // NOT NULL field
        $this->menukUrl->Required = true; // Required field
        $this->Fields['menukUrl'] = &$this->menukUrl;

        // menukBlank
        $this->menukBlank = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukBlank',
            'menukBlank',
            '`menukBlank`',
            '`menukBlank`',
            202,
            6,
            -1,
            false,
            '`menukBlank`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->menukBlank->InputTextType = "text";
        $this->menukBlank->Nullable = false; // NOT NULL field
        $this->menukBlank->Required = true; // Required field
        $this->menukBlank->Lookup = new Lookup('menukBlank', 'menu_kryesore', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->menukBlank->OptionCount = 2;
        $this->Fields['menukBlank'] = &$this->menukBlank;

        // menukRadhe
        $this->menukRadhe = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukRadhe',
            'menukRadhe',
            '`menukRadhe`',
            '`menukRadhe`',
            3,
            10,
            -1,
            false,
            '`menukRadhe`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menukRadhe->InputTextType = "text";
        $this->menukRadhe->Nullable = false; // NOT NULL field
        $this->menukRadhe->Required = true; // Required field
        $this->menukRadhe->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['menukRadhe'] = &$this->menukRadhe;

        // menukAktiv
        $this->menukAktiv = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukAktiv',
            'menukAktiv',
            '`menukAktiv`',
            '`menukAktiv`',
            202,
            2,
            -1,
            false,
            '`menukAktiv`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->menukAktiv->InputTextType = "text";
        $this->menukAktiv->Required = true; // Required field
        $this->menukAktiv->Lookup = new Lookup('menukAktiv', 'menu_kryesore', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->menukAktiv->OptionCount = 2;
        $this->Fields['menukAktiv'] = &$this->menukAktiv;

        // menukAutor
        $this->menukAutor = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukAutor',
            'menukAutor',
            '`menukAutor`',
            '`menukAutor`',
            3,
            255,
            -1,
            false,
            '`menukAutor`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menukAutor->InputTextType = "text";
        $this->menukAutor->Nullable = false; // NOT NULL field
        $this->menukAutor->Lookup = new Lookup('menukAutor', 'perdoruesit', false, 'perdID', ["perdEmri","","",""], [], [], [], [], [], [], '', '', "`perdEmri`");
        $this->menukAutor->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['menukAutor'] = &$this->menukAutor;

        // menukKrijuar
        $this->menukKrijuar = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukKrijuar',
            'menukKrijuar',
            '`menukKrijuar`',
            CastDateFieldForLike("`menukKrijuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`menukKrijuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menukKrijuar->InputTextType = "text";
        $this->menukKrijuar->Nullable = false; // NOT NULL field
        $this->menukKrijuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['menukKrijuar'] = &$this->menukKrijuar;

        // menukAzhornuar
        $this->menukAzhornuar = new DbField(
            'menu_kryesore',
            'menu_kryesore',
            'x_menukAzhornuar',
            'menukAzhornuar',
            '`menukAzhornuar`',
            CastDateFieldForLike("`menukAzhornuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`menukAzhornuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menukAzhornuar->InputTextType = "text";
        $this->menukAzhornuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['menukAzhornuar'] = &$this->menukAzhornuar;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`menu_kryesore`";
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
            $this->menukID->setDbValue($conn->lastInsertId());
            $rs['menukID'] = $this->menukID->DbValue;
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
            if (array_key_exists('menukID', $rs)) {
                AddFilter($where, QuotedName('menukID', $this->Dbid) . '=' . QuotedValue($rs['menukID'], $this->menukID->DataType, $this->Dbid));
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
        $this->menukID->DbValue = $row['menukID'];
        $this->menukGjuha->DbValue = $row['menukGjuha'];
        $this->menukTitull->DbValue = $row['menukTitull'];
        $this->menukUrl->DbValue = $row['menukUrl'];
        $this->menukBlank->DbValue = $row['menukBlank'];
        $this->menukRadhe->DbValue = $row['menukRadhe'];
        $this->menukAktiv->DbValue = $row['menukAktiv'];
        $this->menukAutor->DbValue = $row['menukAutor'];
        $this->menukKrijuar->DbValue = $row['menukKrijuar'];
        $this->menukAzhornuar->DbValue = $row['menukAzhornuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`menukID` = @menukID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->menukID->CurrentValue : $this->menukID->OldValue;
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
                $this->menukID->CurrentValue = $keys[0];
            } else {
                $this->menukID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('menukID', $row) ? $row['menukID'] : null;
        } else {
            $val = $this->menukID->OldValue !== null ? $this->menukID->OldValue : $this->menukID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@menukID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("MenuKryesoreList");
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
        if ($pageName == "MenuKryesoreView") {
            return $Language->phrase("View");
        } elseif ($pageName == "MenuKryesoreEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "MenuKryesoreAdd") {
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
                return "MenuKryesoreView";
            case Config("API_ADD_ACTION"):
                return "MenuKryesoreAdd";
            case Config("API_EDIT_ACTION"):
                return "MenuKryesoreEdit";
            case Config("API_DELETE_ACTION"):
                return "MenuKryesoreDelete";
            case Config("API_LIST_ACTION"):
                return "MenuKryesoreList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "MenuKryesoreList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("MenuKryesoreView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("MenuKryesoreView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "MenuKryesoreAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "MenuKryesoreAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("MenuKryesoreEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("MenuKryesoreAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("MenuKryesoreDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"menukID\":" . JsonEncode($this->menukID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->menukID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->menukID->CurrentValue);
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
            if (($keyValue = Param("menukID") ?? Route("menukID")) !== null) {
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
                $this->menukID->CurrentValue = $key;
            } else {
                $this->menukID->OldValue = $key;
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
        $this->menukID->setDbValue($row['menukID']);
        $this->menukGjuha->setDbValue($row['menukGjuha']);
        $this->menukTitull->setDbValue($row['menukTitull']);
        $this->menukUrl->setDbValue($row['menukUrl']);
        $this->menukBlank->setDbValue($row['menukBlank']);
        $this->menukRadhe->setDbValue($row['menukRadhe']);
        $this->menukAktiv->setDbValue($row['menukAktiv']);
        $this->menukAutor->setDbValue($row['menukAutor']);
        $this->menukKrijuar->setDbValue($row['menukKrijuar']);
        $this->menukAzhornuar->setDbValue($row['menukAzhornuar']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // menukID

        // menukGjuha

        // menukTitull

        // menukUrl

        // menukBlank

        // menukRadhe

        // menukAktiv

        // menukAutor

        // menukKrijuar

        // menukAzhornuar

        // menukID
        $this->menukID->ViewValue = $this->menukID->CurrentValue;
        $this->menukID->ViewCustomAttributes = "";

        // menukGjuha
        if (strval($this->menukGjuha->CurrentValue) != "") {
            $this->menukGjuha->ViewValue = $this->menukGjuha->optionCaption($this->menukGjuha->CurrentValue);
        } else {
            $this->menukGjuha->ViewValue = null;
        }
        $this->menukGjuha->ViewCustomAttributes = "";

        // menukTitull
        $this->menukTitull->ViewValue = $this->menukTitull->CurrentValue;
        $this->menukTitull->ViewCustomAttributes = "";

        // menukUrl
        $this->menukUrl->ViewValue = $this->menukUrl->CurrentValue;
        $this->menukUrl->ViewCustomAttributes = "";

        // menukBlank
        if (strval($this->menukBlank->CurrentValue) != "") {
            $this->menukBlank->ViewValue = $this->menukBlank->optionCaption($this->menukBlank->CurrentValue);
        } else {
            $this->menukBlank->ViewValue = null;
        }
        $this->menukBlank->ViewCustomAttributes = "";

        // menukRadhe
        $this->menukRadhe->ViewValue = $this->menukRadhe->CurrentValue;
        $this->menukRadhe->ViewValue = FormatNumber($this->menukRadhe->ViewValue, $this->menukRadhe->formatPattern());
        $this->menukRadhe->ViewCustomAttributes = "";

        // menukAktiv
        if (strval($this->menukAktiv->CurrentValue) != "") {
            $this->menukAktiv->ViewValue = $this->menukAktiv->optionCaption($this->menukAktiv->CurrentValue);
        } else {
            $this->menukAktiv->ViewValue = null;
        }
        $this->menukAktiv->ViewCustomAttributes = "";

        // menukAutor
        $this->menukAutor->ViewValue = $this->menukAutor->CurrentValue;
        $curVal = strval($this->menukAutor->CurrentValue);
        if ($curVal != "") {
            $this->menukAutor->ViewValue = $this->menukAutor->lookupCacheOption($curVal);
            if ($this->menukAutor->ViewValue === null) { // Lookup from database
                $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->menukAutor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->menukAutor->Lookup->renderViewRow($rswrk[0]);
                    $this->menukAutor->ViewValue = $this->menukAutor->displayValue($arwrk);
                } else {
                    $this->menukAutor->ViewValue = FormatNumber($this->menukAutor->CurrentValue, $this->menukAutor->formatPattern());
                }
            }
        } else {
            $this->menukAutor->ViewValue = null;
        }
        $this->menukAutor->ViewCustomAttributes = "";

        // menukKrijuar
        $this->menukKrijuar->ViewValue = $this->menukKrijuar->CurrentValue;
        $this->menukKrijuar->ViewValue = FormatDateTime($this->menukKrijuar->ViewValue, $this->menukKrijuar->formatPattern());
        $this->menukKrijuar->ViewCustomAttributes = "";

        // menukAzhornuar
        $this->menukAzhornuar->ViewValue = $this->menukAzhornuar->CurrentValue;
        $this->menukAzhornuar->ViewValue = FormatDateTime($this->menukAzhornuar->ViewValue, $this->menukAzhornuar->formatPattern());
        $this->menukAzhornuar->ViewCustomAttributes = "";

        // menukID
        $this->menukID->LinkCustomAttributes = "";
        $this->menukID->HrefValue = "";
        $this->menukID->TooltipValue = "";

        // menukGjuha
        $this->menukGjuha->LinkCustomAttributes = "";
        $this->menukGjuha->HrefValue = "";
        $this->menukGjuha->TooltipValue = "";

        // menukTitull
        $this->menukTitull->LinkCustomAttributes = "";
        $this->menukTitull->HrefValue = "";
        $this->menukTitull->TooltipValue = "";

        // menukUrl
        $this->menukUrl->LinkCustomAttributes = "";
        $this->menukUrl->HrefValue = "";
        $this->menukUrl->TooltipValue = "";

        // menukBlank
        $this->menukBlank->LinkCustomAttributes = "";
        $this->menukBlank->HrefValue = "";
        $this->menukBlank->TooltipValue = "";

        // menukRadhe
        $this->menukRadhe->LinkCustomAttributes = "";
        $this->menukRadhe->HrefValue = "";
        $this->menukRadhe->TooltipValue = "";

        // menukAktiv
        $this->menukAktiv->LinkCustomAttributes = "";
        $this->menukAktiv->HrefValue = "";
        $this->menukAktiv->TooltipValue = "";

        // menukAutor
        $this->menukAutor->LinkCustomAttributes = "";
        $this->menukAutor->HrefValue = "";
        $this->menukAutor->TooltipValue = "";

        // menukKrijuar
        $this->menukKrijuar->LinkCustomAttributes = "";
        $this->menukKrijuar->HrefValue = "";
        $this->menukKrijuar->TooltipValue = "";

        // menukAzhornuar
        $this->menukAzhornuar->LinkCustomAttributes = "";
        $this->menukAzhornuar->HrefValue = "";
        $this->menukAzhornuar->TooltipValue = "";

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

        // menukID
        $this->menukID->setupEditAttributes();
        $this->menukID->EditCustomAttributes = "";
        $this->menukID->EditValue = $this->menukID->CurrentValue;
        $this->menukID->ViewCustomAttributes = "";

        // menukGjuha
        $this->menukGjuha->EditCustomAttributes = "";
        $this->menukGjuha->EditValue = $this->menukGjuha->options(false);
        $this->menukGjuha->PlaceHolder = RemoveHtml($this->menukGjuha->caption());

        // menukTitull
        $this->menukTitull->setupEditAttributes();
        $this->menukTitull->EditCustomAttributes = "";
        if (!$this->menukTitull->Raw) {
            $this->menukTitull->CurrentValue = HtmlDecode($this->menukTitull->CurrentValue);
        }
        $this->menukTitull->EditValue = $this->menukTitull->CurrentValue;
        $this->menukTitull->PlaceHolder = RemoveHtml($this->menukTitull->caption());

        // menukUrl
        $this->menukUrl->setupEditAttributes();
        $this->menukUrl->EditCustomAttributes = "";
        if (!$this->menukUrl->Raw) {
            $this->menukUrl->CurrentValue = HtmlDecode($this->menukUrl->CurrentValue);
        }
        $this->menukUrl->EditValue = $this->menukUrl->CurrentValue;
        $this->menukUrl->PlaceHolder = RemoveHtml($this->menukUrl->caption());

        // menukBlank
        $this->menukBlank->EditCustomAttributes = "";
        $this->menukBlank->EditValue = $this->menukBlank->options(false);
        $this->menukBlank->PlaceHolder = RemoveHtml($this->menukBlank->caption());

        // menukRadhe
        $this->menukRadhe->setupEditAttributes();
        $this->menukRadhe->EditCustomAttributes = "";
        $this->menukRadhe->EditValue = $this->menukRadhe->CurrentValue;
        $this->menukRadhe->PlaceHolder = RemoveHtml($this->menukRadhe->caption());
        if (strval($this->menukRadhe->EditValue) != "" && is_numeric($this->menukRadhe->EditValue)) {
            $this->menukRadhe->EditValue = FormatNumber($this->menukRadhe->EditValue, null);
        }

        // menukAktiv
        $this->menukAktiv->EditCustomAttributes = "";
        $this->menukAktiv->EditValue = $this->menukAktiv->options(false);
        $this->menukAktiv->PlaceHolder = RemoveHtml($this->menukAktiv->caption());

        // menukAutor

        // menukKrijuar

        // menukAzhornuar

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
                    $doc->exportCaption($this->menukID);
                    $doc->exportCaption($this->menukGjuha);
                    $doc->exportCaption($this->menukTitull);
                    $doc->exportCaption($this->menukUrl);
                    $doc->exportCaption($this->menukBlank);
                    $doc->exportCaption($this->menukRadhe);
                    $doc->exportCaption($this->menukAktiv);
                    $doc->exportCaption($this->menukAutor);
                    $doc->exportCaption($this->menukKrijuar);
                    $doc->exportCaption($this->menukAzhornuar);
                } else {
                    $doc->exportCaption($this->menukID);
                    $doc->exportCaption($this->menukGjuha);
                    $doc->exportCaption($this->menukTitull);
                    $doc->exportCaption($this->menukUrl);
                    $doc->exportCaption($this->menukBlank);
                    $doc->exportCaption($this->menukRadhe);
                    $doc->exportCaption($this->menukAktiv);
                    $doc->exportCaption($this->menukAutor);
                    $doc->exportCaption($this->menukKrijuar);
                    $doc->exportCaption($this->menukAzhornuar);
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
                        $doc->exportField($this->menukID);
                        $doc->exportField($this->menukGjuha);
                        $doc->exportField($this->menukTitull);
                        $doc->exportField($this->menukUrl);
                        $doc->exportField($this->menukBlank);
                        $doc->exportField($this->menukRadhe);
                        $doc->exportField($this->menukAktiv);
                        $doc->exportField($this->menukAutor);
                        $doc->exportField($this->menukKrijuar);
                        $doc->exportField($this->menukAzhornuar);
                    } else {
                        $doc->exportField($this->menukID);
                        $doc->exportField($this->menukGjuha);
                        $doc->exportField($this->menukTitull);
                        $doc->exportField($this->menukUrl);
                        $doc->exportField($this->menukBlank);
                        $doc->exportField($this->menukRadhe);
                        $doc->exportField($this->menukAktiv);
                        $doc->exportField($this->menukAutor);
                        $doc->exportField($this->menukKrijuar);
                        $doc->exportField($this->menukAzhornuar);
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
