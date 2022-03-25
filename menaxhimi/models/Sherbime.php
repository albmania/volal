<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for sherbime
 */
class Sherbime extends DbTable
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
    public $sherbimeID;
    public $sherbimeEmertimi_sq;
    public $sherbimeTxt_sq;
    public $sherbimeCmimi;
    public $sherbimeEmertimi_en;
    public $sherbimeTxt_en;
    public $sherbimeFoto;
    public $sherbimeIkona;
    public $sherbimeIndex;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'sherbime';
        $this->TableName = 'sherbime';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`sherbime`";
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

        // sherbimeID
        $this->sherbimeID = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeID',
            'sherbimeID',
            '`sherbimeID`',
            '`sherbimeID`',
            3,
            255,
            -1,
            false,
            '`sherbimeID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->sherbimeID->InputTextType = "text";
        $this->sherbimeID->IsAutoIncrement = true; // Autoincrement field
        $this->sherbimeID->IsPrimaryKey = true; // Primary key field
        $this->sherbimeID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['sherbimeID'] = &$this->sherbimeID;

        // sherbimeEmertimi_sq
        $this->sherbimeEmertimi_sq = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeEmertimi_sq',
            'sherbimeEmertimi_sq',
            '`sherbimeEmertimi_sq`',
            '`sherbimeEmertimi_sq`',
            200,
            250,
            -1,
            false,
            '`sherbimeEmertimi_sq`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->sherbimeEmertimi_sq->InputTextType = "text";
        $this->sherbimeEmertimi_sq->Nullable = false; // NOT NULL field
        $this->sherbimeEmertimi_sq->Required = true; // Required field
        $this->sherbimeEmertimi_sq->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sherbimeEmertimi_sq->Param, "CustomMsg");
        $this->Fields['sherbimeEmertimi_sq'] = &$this->sherbimeEmertimi_sq;

        // sherbimeTxt_sq
        $this->sherbimeTxt_sq = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeTxt_sq',
            'sherbimeTxt_sq',
            '`sherbimeTxt_sq`',
            '`sherbimeTxt_sq`',
            200,
            250,
            -1,
            false,
            '`sherbimeTxt_sq`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->sherbimeTxt_sq->InputTextType = "text";
        $this->sherbimeTxt_sq->Nullable = false; // NOT NULL field
        $this->sherbimeTxt_sq->Required = true; // Required field
        $this->sherbimeTxt_sq->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sherbimeTxt_sq->Param, "CustomMsg");
        $this->Fields['sherbimeTxt_sq'] = &$this->sherbimeTxt_sq;

        // sherbimeCmimi
        $this->sherbimeCmimi = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeCmimi',
            'sherbimeCmimi',
            '`sherbimeCmimi`',
            '`sherbimeCmimi`',
            5,
            10,
            -1,
            false,
            '`sherbimeCmimi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->sherbimeCmimi->InputTextType = "text";
        $this->sherbimeCmimi->Nullable = false; // NOT NULL field
        $this->sherbimeCmimi->Required = true; // Required field
        $this->sherbimeCmimi->DefaultErrorMessage = $Language->phrase("IncorrectFloat");
        $this->sherbimeCmimi->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sherbimeCmimi->Param, "CustomMsg");
        $this->Fields['sherbimeCmimi'] = &$this->sherbimeCmimi;

        // sherbimeEmertimi_en
        $this->sherbimeEmertimi_en = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeEmertimi_en',
            'sherbimeEmertimi_en',
            '`sherbimeEmertimi_en`',
            '`sherbimeEmertimi_en`',
            200,
            250,
            -1,
            false,
            '`sherbimeEmertimi_en`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->sherbimeEmertimi_en->InputTextType = "text";
        $this->Fields['sherbimeEmertimi_en'] = &$this->sherbimeEmertimi_en;

        // sherbimeTxt_en
        $this->sherbimeTxt_en = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeTxt_en',
            'sherbimeTxt_en',
            '`sherbimeTxt_en`',
            '`sherbimeTxt_en`',
            200,
            250,
            -1,
            false,
            '`sherbimeTxt_en`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->sherbimeTxt_en->InputTextType = "text";
        $this->sherbimeTxt_en->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sherbimeTxt_en->Param, "CustomMsg");
        $this->Fields['sherbimeTxt_en'] = &$this->sherbimeTxt_en;

        // sherbimeFoto
        $this->sherbimeFoto = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeFoto',
            'sherbimeFoto',
            '`sherbimeFoto`',
            '`sherbimeFoto`',
            200,
            255,
            -1,
            true,
            '`sherbimeFoto`',
            false,
            false,
            false,
            'IMAGE',
            'FILE'
        );
        $this->sherbimeFoto->InputTextType = "text";
        $this->sherbimeFoto->Nullable = false; // NOT NULL field
        $this->sherbimeFoto->Required = true; // Required field
        $this->sherbimeFoto->UploadAllowedFileExt = "jpg,jpeg,gif,png,webp";
        $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
        $this->sherbimeFoto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sherbimeFoto->Param, "CustomMsg");
        $this->Fields['sherbimeFoto'] = &$this->sherbimeFoto;

        // sherbimeIkona
        $this->sherbimeIkona = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeIkona',
            'sherbimeIkona',
            '`sherbimeIkona`',
            '`sherbimeIkona`',
            200,
            100,
            -1,
            false,
            '`sherbimeIkona`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->sherbimeIkona->InputTextType = "text";
        $this->sherbimeIkona->Nullable = false; // NOT NULL field
        $this->sherbimeIkona->Required = true; // Required field
        $this->sherbimeIkona->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->sherbimeIkona->Param, "CustomMsg");
        $this->Fields['sherbimeIkona'] = &$this->sherbimeIkona;

        // sherbimeIndex
        $this->sherbimeIndex = new DbField(
            'sherbime',
            'sherbime',
            'x_sherbimeIndex',
            'sherbimeIndex',
            '`sherbimeIndex`',
            '`sherbimeIndex`',
            202,
            2,
            -1,
            false,
            '`sherbimeIndex`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->sherbimeIndex->InputTextType = "text";
        $this->sherbimeIndex->Nullable = false; // NOT NULL field
        $this->sherbimeIndex->Required = true; // Required field
        $this->sherbimeIndex->Lookup = new Lookup('sherbimeIndex', 'sherbime', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->sherbimeIndex->OptionCount = 2;
        $this->Fields['sherbimeIndex'] = &$this->sherbimeIndex;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`sherbime`";
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
            $this->sherbimeID->setDbValue($conn->lastInsertId());
            $rs['sherbimeID'] = $this->sherbimeID->DbValue;
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
            if (array_key_exists('sherbimeID', $rs)) {
                AddFilter($where, QuotedName('sherbimeID', $this->Dbid) . '=' . QuotedValue($rs['sherbimeID'], $this->sherbimeID->DataType, $this->Dbid));
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
        $this->sherbimeID->DbValue = $row['sherbimeID'];
        $this->sherbimeEmertimi_sq->DbValue = $row['sherbimeEmertimi_sq'];
        $this->sherbimeTxt_sq->DbValue = $row['sherbimeTxt_sq'];
        $this->sherbimeCmimi->DbValue = $row['sherbimeCmimi'];
        $this->sherbimeEmertimi_en->DbValue = $row['sherbimeEmertimi_en'];
        $this->sherbimeTxt_en->DbValue = $row['sherbimeTxt_en'];
        $this->sherbimeFoto->Upload->DbValue = $row['sherbimeFoto'];
        $this->sherbimeIkona->DbValue = $row['sherbimeIkona'];
        $this->sherbimeIndex->DbValue = $row['sherbimeIndex'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $this->sherbimeFoto->OldUploadPath = '../ngarkime/sherbime/';
        $oldFiles = EmptyValue($row['sherbimeFoto']) ? [] : [$row['sherbimeFoto']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->sherbimeFoto->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->sherbimeFoto->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`sherbimeID` = @sherbimeID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->sherbimeID->CurrentValue : $this->sherbimeID->OldValue;
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
                $this->sherbimeID->CurrentValue = $keys[0];
            } else {
                $this->sherbimeID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('sherbimeID', $row) ? $row['sherbimeID'] : null;
        } else {
            $val = $this->sherbimeID->OldValue !== null ? $this->sherbimeID->OldValue : $this->sherbimeID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@sherbimeID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("SherbimeList");
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
        if ($pageName == "SherbimeView") {
            return $Language->phrase("View");
        } elseif ($pageName == "SherbimeEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "SherbimeAdd") {
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
                return "SherbimeView";
            case Config("API_ADD_ACTION"):
                return "SherbimeAdd";
            case Config("API_EDIT_ACTION"):
                return "SherbimeEdit";
            case Config("API_DELETE_ACTION"):
                return "SherbimeDelete";
            case Config("API_LIST_ACTION"):
                return "SherbimeList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "SherbimeList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("SherbimeView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("SherbimeView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "SherbimeAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "SherbimeAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("SherbimeEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("SherbimeAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("SherbimeDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"sherbimeID\":" . JsonEncode($this->sherbimeID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->sherbimeID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->sherbimeID->CurrentValue);
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
            if (($keyValue = Param("sherbimeID") ?? Route("sherbimeID")) !== null) {
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
                $this->sherbimeID->CurrentValue = $key;
            } else {
                $this->sherbimeID->OldValue = $key;
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
        $this->sherbimeID->setDbValue($row['sherbimeID']);
        $this->sherbimeEmertimi_sq->setDbValue($row['sherbimeEmertimi_sq']);
        $this->sherbimeTxt_sq->setDbValue($row['sherbimeTxt_sq']);
        $this->sherbimeCmimi->setDbValue($row['sherbimeCmimi']);
        $this->sherbimeEmertimi_en->setDbValue($row['sherbimeEmertimi_en']);
        $this->sherbimeTxt_en->setDbValue($row['sherbimeTxt_en']);
        $this->sherbimeFoto->Upload->DbValue = $row['sherbimeFoto'];
        $this->sherbimeIkona->setDbValue($row['sherbimeIkona']);
        $this->sherbimeIndex->setDbValue($row['sherbimeIndex']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // sherbimeID

        // sherbimeEmertimi_sq

        // sherbimeTxt_sq

        // sherbimeCmimi

        // sherbimeEmertimi_en

        // sherbimeTxt_en

        // sherbimeFoto

        // sherbimeIkona

        // sherbimeIndex

        // sherbimeID
        $this->sherbimeID->ViewValue = $this->sherbimeID->CurrentValue;
        $this->sherbimeID->ViewCustomAttributes = "";

        // sherbimeEmertimi_sq
        $this->sherbimeEmertimi_sq->ViewValue = $this->sherbimeEmertimi_sq->CurrentValue;
        $this->sherbimeEmertimi_sq->ViewCustomAttributes = "";

        // sherbimeTxt_sq
        $this->sherbimeTxt_sq->ViewValue = $this->sherbimeTxt_sq->CurrentValue;
        $this->sherbimeTxt_sq->ViewCustomAttributes = "";

        // sherbimeCmimi
        $this->sherbimeCmimi->ViewValue = $this->sherbimeCmimi->CurrentValue;
        $this->sherbimeCmimi->ViewValue = FormatNumber($this->sherbimeCmimi->ViewValue, $this->sherbimeCmimi->formatPattern());
        $this->sherbimeCmimi->ViewCustomAttributes = "";

        // sherbimeEmertimi_en
        $this->sherbimeEmertimi_en->ViewValue = $this->sherbimeEmertimi_en->CurrentValue;
        $this->sherbimeEmertimi_en->ViewCustomAttributes = "";

        // sherbimeTxt_en
        $this->sherbimeTxt_en->ViewValue = $this->sherbimeTxt_en->CurrentValue;
        $this->sherbimeTxt_en->ViewCustomAttributes = "";

        // sherbimeFoto
        $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
        if (!EmptyValue($this->sherbimeFoto->Upload->DbValue)) {
            $this->sherbimeFoto->ImageWidth = 100;
            $this->sherbimeFoto->ImageHeight = 0;
            $this->sherbimeFoto->ImageAlt = $this->sherbimeFoto->alt();
            $this->sherbimeFoto->ImageCssClass = "ew-image";
            $this->sherbimeFoto->ViewValue = $this->sherbimeFoto->Upload->DbValue;
        } else {
            $this->sherbimeFoto->ViewValue = "";
        }
        $this->sherbimeFoto->ViewCustomAttributes = "";

        // sherbimeIkona
        $this->sherbimeIkona->ViewValue = $this->sherbimeIkona->CurrentValue;
        $this->sherbimeIkona->ViewCustomAttributes = "";

        // sherbimeIndex
        if (strval($this->sherbimeIndex->CurrentValue) != "") {
            $this->sherbimeIndex->ViewValue = $this->sherbimeIndex->optionCaption($this->sherbimeIndex->CurrentValue);
        } else {
            $this->sherbimeIndex->ViewValue = null;
        }
        $this->sherbimeIndex->ViewCustomAttributes = "";

        // sherbimeID
        $this->sherbimeID->LinkCustomAttributes = "";
        $this->sherbimeID->HrefValue = "";
        $this->sherbimeID->TooltipValue = "";

        // sherbimeEmertimi_sq
        $this->sherbimeEmertimi_sq->LinkCustomAttributes = "";
        $this->sherbimeEmertimi_sq->HrefValue = "";
        $this->sherbimeEmertimi_sq->TooltipValue = "";

        // sherbimeTxt_sq
        $this->sherbimeTxt_sq->LinkCustomAttributes = "";
        $this->sherbimeTxt_sq->HrefValue = "";
        $this->sherbimeTxt_sq->TooltipValue = "";

        // sherbimeCmimi
        $this->sherbimeCmimi->LinkCustomAttributes = "";
        $this->sherbimeCmimi->HrefValue = "";
        $this->sherbimeCmimi->TooltipValue = "";

        // sherbimeEmertimi_en
        $this->sherbimeEmertimi_en->LinkCustomAttributes = "";
        $this->sherbimeEmertimi_en->HrefValue = "";
        $this->sherbimeEmertimi_en->TooltipValue = "";

        // sherbimeTxt_en
        $this->sherbimeTxt_en->LinkCustomAttributes = "";
        $this->sherbimeTxt_en->HrefValue = "";
        $this->sherbimeTxt_en->TooltipValue = "";

        // sherbimeFoto
        $this->sherbimeFoto->LinkCustomAttributes = "";
        $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
        if (!EmptyValue($this->sherbimeFoto->Upload->DbValue)) {
            $this->sherbimeFoto->HrefValue = GetFileUploadUrl($this->sherbimeFoto, $this->sherbimeFoto->htmlDecode($this->sherbimeFoto->Upload->DbValue)); // Add prefix/suffix
            $this->sherbimeFoto->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->sherbimeFoto->HrefValue = FullUrl($this->sherbimeFoto->HrefValue, "href");
            }
        } else {
            $this->sherbimeFoto->HrefValue = "";
        }
        $this->sherbimeFoto->ExportHrefValue = $this->sherbimeFoto->UploadPath . $this->sherbimeFoto->Upload->DbValue;
        $this->sherbimeFoto->TooltipValue = "";
        if ($this->sherbimeFoto->UseColorbox) {
            if (EmptyValue($this->sherbimeFoto->TooltipValue)) {
                $this->sherbimeFoto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->sherbimeFoto->LinkAttrs["data-rel"] = "sherbime_x_sherbimeFoto";
            $this->sherbimeFoto->LinkAttrs->appendClass("ew-lightbox");
        }

        // sherbimeIkona
        $this->sherbimeIkona->LinkCustomAttributes = "";
        $this->sherbimeIkona->HrefValue = "";
        $this->sherbimeIkona->TooltipValue = "";

        // sherbimeIndex
        $this->sherbimeIndex->LinkCustomAttributes = "";
        $this->sherbimeIndex->HrefValue = "";
        $this->sherbimeIndex->TooltipValue = "";

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

        // sherbimeID
        $this->sherbimeID->setupEditAttributes();
        $this->sherbimeID->EditCustomAttributes = "";
        $this->sherbimeID->EditValue = $this->sherbimeID->CurrentValue;
        $this->sherbimeID->ViewCustomAttributes = "";

        // sherbimeEmertimi_sq
        $this->sherbimeEmertimi_sq->setupEditAttributes();
        $this->sherbimeEmertimi_sq->EditCustomAttributes = "";
        if (!$this->sherbimeEmertimi_sq->Raw) {
            $this->sherbimeEmertimi_sq->CurrentValue = HtmlDecode($this->sherbimeEmertimi_sq->CurrentValue);
        }
        $this->sherbimeEmertimi_sq->EditValue = $this->sherbimeEmertimi_sq->CurrentValue;
        $this->sherbimeEmertimi_sq->PlaceHolder = RemoveHtml($this->sherbimeEmertimi_sq->caption());

        // sherbimeTxt_sq
        $this->sherbimeTxt_sq->setupEditAttributes();
        $this->sherbimeTxt_sq->EditCustomAttributes = "";
        if (!$this->sherbimeTxt_sq->Raw) {
            $this->sherbimeTxt_sq->CurrentValue = HtmlDecode($this->sherbimeTxt_sq->CurrentValue);
        }
        $this->sherbimeTxt_sq->EditValue = $this->sherbimeTxt_sq->CurrentValue;
        $this->sherbimeTxt_sq->PlaceHolder = RemoveHtml($this->sherbimeTxt_sq->caption());

        // sherbimeCmimi
        $this->sherbimeCmimi->setupEditAttributes();
        $this->sherbimeCmimi->EditCustomAttributes = "";
        $this->sherbimeCmimi->EditValue = $this->sherbimeCmimi->CurrentValue;
        $this->sherbimeCmimi->PlaceHolder = RemoveHtml($this->sherbimeCmimi->caption());
        if (strval($this->sherbimeCmimi->EditValue) != "" && is_numeric($this->sherbimeCmimi->EditValue)) {
            $this->sherbimeCmimi->EditValue = FormatNumber($this->sherbimeCmimi->EditValue, null);
        }

        // sherbimeEmertimi_en
        $this->sherbimeEmertimi_en->setupEditAttributes();
        $this->sherbimeEmertimi_en->EditCustomAttributes = "";
        if (!$this->sherbimeEmertimi_en->Raw) {
            $this->sherbimeEmertimi_en->CurrentValue = HtmlDecode($this->sherbimeEmertimi_en->CurrentValue);
        }
        $this->sherbimeEmertimi_en->EditValue = $this->sherbimeEmertimi_en->CurrentValue;
        $this->sherbimeEmertimi_en->PlaceHolder = RemoveHtml($this->sherbimeEmertimi_en->caption());

        // sherbimeTxt_en
        $this->sherbimeTxt_en->setupEditAttributes();
        $this->sherbimeTxt_en->EditCustomAttributes = "";
        if (!$this->sherbimeTxt_en->Raw) {
            $this->sherbimeTxt_en->CurrentValue = HtmlDecode($this->sherbimeTxt_en->CurrentValue);
        }
        $this->sherbimeTxt_en->EditValue = $this->sherbimeTxt_en->CurrentValue;
        $this->sherbimeTxt_en->PlaceHolder = RemoveHtml($this->sherbimeTxt_en->caption());

        // sherbimeFoto
        $this->sherbimeFoto->setupEditAttributes();
        $this->sherbimeFoto->EditCustomAttributes = "";
        $this->sherbimeFoto->UploadPath = '../ngarkime/sherbime/';
        if (!EmptyValue($this->sherbimeFoto->Upload->DbValue)) {
            $this->sherbimeFoto->ImageWidth = 100;
            $this->sherbimeFoto->ImageHeight = 0;
            $this->sherbimeFoto->ImageAlt = $this->sherbimeFoto->alt();
            $this->sherbimeFoto->ImageCssClass = "ew-image";
            $this->sherbimeFoto->EditValue = $this->sherbimeFoto->Upload->DbValue;
        } else {
            $this->sherbimeFoto->EditValue = "";
        }
        if (!EmptyValue($this->sherbimeFoto->CurrentValue)) {
            $this->sherbimeFoto->Upload->FileName = $this->sherbimeFoto->CurrentValue;
        }

        // sherbimeIkona
        $this->sherbimeIkona->setupEditAttributes();
        $this->sherbimeIkona->EditCustomAttributes = "";
        if (!$this->sherbimeIkona->Raw) {
            $this->sherbimeIkona->CurrentValue = HtmlDecode($this->sherbimeIkona->CurrentValue);
        }
        $this->sherbimeIkona->EditValue = $this->sherbimeIkona->CurrentValue;
        $this->sherbimeIkona->PlaceHolder = RemoveHtml($this->sherbimeIkona->caption());

        // sherbimeIndex
        $this->sherbimeIndex->EditCustomAttributes = "";
        $this->sherbimeIndex->EditValue = $this->sherbimeIndex->options(false);
        $this->sherbimeIndex->PlaceHolder = RemoveHtml($this->sherbimeIndex->caption());

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
                    $doc->exportCaption($this->sherbimeID);
                    $doc->exportCaption($this->sherbimeEmertimi_sq);
                    $doc->exportCaption($this->sherbimeTxt_sq);
                    $doc->exportCaption($this->sherbimeCmimi);
                    $doc->exportCaption($this->sherbimeEmertimi_en);
                    $doc->exportCaption($this->sherbimeTxt_en);
                    $doc->exportCaption($this->sherbimeFoto);
                    $doc->exportCaption($this->sherbimeIkona);
                    $doc->exportCaption($this->sherbimeIndex);
                } else {
                    $doc->exportCaption($this->sherbimeID);
                    $doc->exportCaption($this->sherbimeEmertimi_sq);
                    $doc->exportCaption($this->sherbimeTxt_sq);
                    $doc->exportCaption($this->sherbimeCmimi);
                    $doc->exportCaption($this->sherbimeEmertimi_en);
                    $doc->exportCaption($this->sherbimeTxt_en);
                    $doc->exportCaption($this->sherbimeFoto);
                    $doc->exportCaption($this->sherbimeIkona);
                    $doc->exportCaption($this->sherbimeIndex);
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
                        $doc->exportField($this->sherbimeID);
                        $doc->exportField($this->sherbimeEmertimi_sq);
                        $doc->exportField($this->sherbimeTxt_sq);
                        $doc->exportField($this->sherbimeCmimi);
                        $doc->exportField($this->sherbimeEmertimi_en);
                        $doc->exportField($this->sherbimeTxt_en);
                        $doc->exportField($this->sherbimeFoto);
                        $doc->exportField($this->sherbimeIkona);
                        $doc->exportField($this->sherbimeIndex);
                    } else {
                        $doc->exportField($this->sherbimeID);
                        $doc->exportField($this->sherbimeEmertimi_sq);
                        $doc->exportField($this->sherbimeTxt_sq);
                        $doc->exportField($this->sherbimeCmimi);
                        $doc->exportField($this->sherbimeEmertimi_en);
                        $doc->exportField($this->sherbimeTxt_en);
                        $doc->exportField($this->sherbimeFoto);
                        $doc->exportField($this->sherbimeIkona);
                        $doc->exportField($this->sherbimeIndex);
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
        if ($fldparm == 'sherbimeFoto') {
            $fldName = "sherbimeFoto";
            $fileNameFld = "sherbimeFoto";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->sherbimeID->CurrentValue = $ar[0];
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
