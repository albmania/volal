<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for perdoruesit
 */
class Perdoruesit extends DbTable
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
    public $perdID;
    public $perdEmri;
    public $perdUsername;
    public $perdFjalekalimi;
    public $perdEmail;
    public $perdNiveliPerdoruesit;
    public $perdDtReg;
    public $perdActivated;
    public $perdProfileField;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'perdoruesit';
        $this->TableName = 'perdoruesit';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`perdoruesit`";
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
        $this->BasicSearch = new BasicSearch($this->TableVar);

        // perdID
        $this->perdID = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdID',
            'perdID',
            '`perdID`',
            '`perdID`',
            3,
            11,
            -1,
            false,
            '`perdID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->perdID->InputTextType = "text";
        $this->perdID->IsAutoIncrement = true; // Autoincrement field
        $this->perdID->IsPrimaryKey = true; // Primary key field
        $this->perdID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['perdID'] = &$this->perdID;

        // perdEmri
        $this->perdEmri = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdEmri',
            'perdEmri',
            '`perdEmri`',
            '`perdEmri`',
            200,
            255,
            -1,
            false,
            '`perdEmri`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->perdEmri->InputTextType = "text";
        $this->Fields['perdEmri'] = &$this->perdEmri;

        // perdUsername
        $this->perdUsername = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdUsername',
            'perdUsername',
            '`perdUsername`',
            '`perdUsername`',
            200,
            255,
            -1,
            false,
            '`perdUsername`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->perdUsername->InputTextType = "text";
        $this->perdUsername->Required = true; // Required field
        $this->Fields['perdUsername'] = &$this->perdUsername;

        // perdFjalekalimi
        $this->perdFjalekalimi = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdFjalekalimi',
            'perdFjalekalimi',
            '`perdFjalekalimi`',
            '`perdFjalekalimi`',
            200,
            255,
            -1,
            false,
            '`perdFjalekalimi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->perdFjalekalimi->InputTextType = "text";
        if (Config("ENCRYPTED_PASSWORD")) {
            $this->perdFjalekalimi->Raw = true;
        }
        $this->perdFjalekalimi->Nullable = false; // NOT NULL field
        $this->perdFjalekalimi->Required = true; // Required field
        $this->Fields['perdFjalekalimi'] = &$this->perdFjalekalimi;

        // perdEmail
        $this->perdEmail = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdEmail',
            'perdEmail',
            '`perdEmail`',
            '`perdEmail`',
            200,
            255,
            -1,
            false,
            '`perdEmail`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->perdEmail->InputTextType = "text";
        $this->perdEmail->Nullable = false; // NOT NULL field
        $this->perdEmail->Required = true; // Required field
        $this->Fields['perdEmail'] = &$this->perdEmail;

        // perdNiveliPerdoruesit
        $this->perdNiveliPerdoruesit = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdNiveliPerdoruesit',
            'perdNiveliPerdoruesit',
            '`perdNiveliPerdoruesit`',
            '`perdNiveliPerdoruesit`',
            3,
            11,
            -1,
            false,
            '`perdNiveliPerdoruesit`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->perdNiveliPerdoruesit->InputTextType = "text";
        $this->perdNiveliPerdoruesit->Nullable = false; // NOT NULL field
        $this->perdNiveliPerdoruesit->Required = true; // Required field
        $this->perdNiveliPerdoruesit->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->perdNiveliPerdoruesit->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->perdNiveliPerdoruesit->Lookup = new Lookup('perdNiveliPerdoruesit', 'userlevels', false, 'userlevelid', ["userlevelname","","",""], [], [], [], [], [], [], '', '', "`userlevelname`");
        $this->perdNiveliPerdoruesit->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['perdNiveliPerdoruesit'] = &$this->perdNiveliPerdoruesit;

        // perdDtReg
        $this->perdDtReg = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdDtReg',
            'perdDtReg',
            '`perdDtReg`',
            CastDateFieldForLike("`perdDtReg`", 0, "DB"),
            133,
            10,
            0,
            false,
            '`perdDtReg`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->perdDtReg->InputTextType = "text";
        $this->perdDtReg->Nullable = false; // NOT NULL field
        $this->perdDtReg->Required = true; // Required field
        $this->perdDtReg->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['perdDtReg'] = &$this->perdDtReg;

        // perdActivated
        $this->perdActivated = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdActivated',
            'perdActivated',
            '`perdActivated`',
            '`perdActivated`',
            200,
            255,
            -1,
            false,
            '`perdActivated`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->perdActivated->InputTextType = "text";
        $this->perdActivated->Nullable = false; // NOT NULL field
        $this->perdActivated->Required = true; // Required field
        $this->Fields['perdActivated'] = &$this->perdActivated;

        // perdProfileField
        $this->perdProfileField = new DbField(
            'perdoruesit',
            'perdoruesit',
            'x_perdProfileField',
            'perdProfileField',
            '`perdProfileField`',
            '`perdProfileField`',
            201,
            65535,
            -1,
            false,
            '`perdProfileField`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->perdProfileField->InputTextType = "text";
        $this->perdProfileField->Nullable = false; // NOT NULL field
        $this->perdProfileField->Required = true; // Required field
        $this->Fields['perdProfileField'] = &$this->perdProfileField;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`perdoruesit`";
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
        global $Security;
        // Add User ID filter
        if ($Security->currentUserID() != "" && !$Security->isAdmin()) { // Non system admin
            $filter = $this->addUserIDFilter($filter, $id);
        }
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
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
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
            $this->perdID->setDbValue($conn->lastInsertId());
            $rs['perdID'] = $this->perdID->DbValue;
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
            if (Config("ENCRYPTED_PASSWORD") && $name == Config("LOGIN_PASSWORD_FIELD_NAME")) {
                if ($value == $this->Fields[$name]->OldValue) { // No need to update hashed password if not changed
                    continue;
                }
                $value = Config("CASE_SENSITIVE_PASSWORD") ? EncryptPassword($value) : EncryptPassword(strtolower($value));
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
            if (array_key_exists('perdID', $rs)) {
                AddFilter($where, QuotedName('perdID', $this->Dbid) . '=' . QuotedValue($rs['perdID'], $this->perdID->DataType, $this->Dbid));
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
        $this->perdID->DbValue = $row['perdID'];
        $this->perdEmri->DbValue = $row['perdEmri'];
        $this->perdUsername->DbValue = $row['perdUsername'];
        $this->perdFjalekalimi->DbValue = $row['perdFjalekalimi'];
        $this->perdEmail->DbValue = $row['perdEmail'];
        $this->perdNiveliPerdoruesit->DbValue = $row['perdNiveliPerdoruesit'];
        $this->perdDtReg->DbValue = $row['perdDtReg'];
        $this->perdActivated->DbValue = $row['perdActivated'];
        $this->perdProfileField->DbValue = $row['perdProfileField'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`perdID` = @perdID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->perdID->CurrentValue : $this->perdID->OldValue;
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
                $this->perdID->CurrentValue = $keys[0];
            } else {
                $this->perdID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('perdID', $row) ? $row['perdID'] : null;
        } else {
            $val = $this->perdID->OldValue !== null ? $this->perdID->OldValue : $this->perdID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@perdID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("PerdoruesitList");
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
        if ($pageName == "PerdoruesitView") {
            return $Language->phrase("View");
        } elseif ($pageName == "PerdoruesitEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "PerdoruesitAdd") {
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
                return "PerdoruesitView";
            case Config("API_ADD_ACTION"):
                return "PerdoruesitAdd";
            case Config("API_EDIT_ACTION"):
                return "PerdoruesitEdit";
            case Config("API_DELETE_ACTION"):
                return "PerdoruesitDelete";
            case Config("API_LIST_ACTION"):
                return "PerdoruesitList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "PerdoruesitList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("PerdoruesitView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("PerdoruesitView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "PerdoruesitAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "PerdoruesitAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("PerdoruesitEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("PerdoruesitAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("PerdoruesitDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"perdID\":" . JsonEncode($this->perdID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->perdID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->perdID->CurrentValue);
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
            if (($keyValue = Param("perdID") ?? Route("perdID")) !== null) {
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
                $this->perdID->CurrentValue = $key;
            } else {
                $this->perdID->OldValue = $key;
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
        $this->perdID->setDbValue($row['perdID']);
        $this->perdEmri->setDbValue($row['perdEmri']);
        $this->perdUsername->setDbValue($row['perdUsername']);
        $this->perdFjalekalimi->setDbValue($row['perdFjalekalimi']);
        $this->perdEmail->setDbValue($row['perdEmail']);
        $this->perdNiveliPerdoruesit->setDbValue($row['perdNiveliPerdoruesit']);
        $this->perdDtReg->setDbValue($row['perdDtReg']);
        $this->perdActivated->setDbValue($row['perdActivated']);
        $this->perdProfileField->setDbValue($row['perdProfileField']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // perdID

        // perdEmri

        // perdUsername

        // perdFjalekalimi

        // perdEmail

        // perdNiveliPerdoruesit

        // perdDtReg

        // perdActivated

        // perdProfileField

        // perdID
        $this->perdID->ViewValue = $this->perdID->CurrentValue;
        $this->perdID->ViewCustomAttributes = "";

        // perdEmri
        $this->perdEmri->ViewValue = $this->perdEmri->CurrentValue;
        $this->perdEmri->ViewCustomAttributes = "";

        // perdUsername
        $this->perdUsername->ViewValue = $this->perdUsername->CurrentValue;
        $this->perdUsername->ViewCustomAttributes = "";

        // perdFjalekalimi
        $this->perdFjalekalimi->ViewValue = $this->perdFjalekalimi->CurrentValue;
        $this->perdFjalekalimi->ViewCustomAttributes = "";

        // perdEmail
        $this->perdEmail->ViewValue = $this->perdEmail->CurrentValue;
        $this->perdEmail->ViewCustomAttributes = "";

        // perdNiveliPerdoruesit
        if ($Security->canAdmin()) { // System admin
            $curVal = strval($this->perdNiveliPerdoruesit->CurrentValue);
            if ($curVal != "") {
                $this->perdNiveliPerdoruesit->ViewValue = $this->perdNiveliPerdoruesit->lookupCacheOption($curVal);
                if ($this->perdNiveliPerdoruesit->ViewValue === null) { // Lookup from database
                    $filterWrk = "`userlevelid`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->perdNiveliPerdoruesit->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->perdNiveliPerdoruesit->Lookup->renderViewRow($rswrk[0]);
                        $this->perdNiveliPerdoruesit->ViewValue = $this->perdNiveliPerdoruesit->displayValue($arwrk);
                    } else {
                        $this->perdNiveliPerdoruesit->ViewValue = FormatNumber($this->perdNiveliPerdoruesit->CurrentValue, $this->perdNiveliPerdoruesit->formatPattern());
                    }
                }
            } else {
                $this->perdNiveliPerdoruesit->ViewValue = null;
            }
        } else {
            $this->perdNiveliPerdoruesit->ViewValue = $Language->phrase("PasswordMask");
        }
        $this->perdNiveliPerdoruesit->ViewCustomAttributes = "";

        // perdDtReg
        $this->perdDtReg->ViewValue = $this->perdDtReg->CurrentValue;
        $this->perdDtReg->ViewValue = FormatDateTime($this->perdDtReg->ViewValue, $this->perdDtReg->formatPattern());
        $this->perdDtReg->ViewCustomAttributes = "";

        // perdActivated
        $this->perdActivated->ViewValue = $this->perdActivated->CurrentValue;
        $this->perdActivated->ViewCustomAttributes = "";

        // perdProfileField
        $this->perdProfileField->ViewValue = $this->perdProfileField->CurrentValue;
        $this->perdProfileField->ViewCustomAttributes = "";

        // perdID
        $this->perdID->LinkCustomAttributes = "";
        $this->perdID->HrefValue = "";
        $this->perdID->TooltipValue = "";

        // perdEmri
        $this->perdEmri->LinkCustomAttributes = "";
        $this->perdEmri->HrefValue = "";
        $this->perdEmri->TooltipValue = "";

        // perdUsername
        $this->perdUsername->LinkCustomAttributes = "";
        $this->perdUsername->HrefValue = "";
        $this->perdUsername->TooltipValue = "";

        // perdFjalekalimi
        $this->perdFjalekalimi->LinkCustomAttributes = "";
        $this->perdFjalekalimi->HrefValue = "";
        $this->perdFjalekalimi->TooltipValue = "";

        // perdEmail
        $this->perdEmail->LinkCustomAttributes = "";
        $this->perdEmail->HrefValue = "";
        $this->perdEmail->TooltipValue = "";

        // perdNiveliPerdoruesit
        $this->perdNiveliPerdoruesit->LinkCustomAttributes = "";
        $this->perdNiveliPerdoruesit->HrefValue = "";
        $this->perdNiveliPerdoruesit->TooltipValue = "";

        // perdDtReg
        $this->perdDtReg->LinkCustomAttributes = "";
        $this->perdDtReg->HrefValue = "";
        $this->perdDtReg->TooltipValue = "";

        // perdActivated
        $this->perdActivated->LinkCustomAttributes = "";
        $this->perdActivated->HrefValue = "";
        $this->perdActivated->TooltipValue = "";

        // perdProfileField
        $this->perdProfileField->LinkCustomAttributes = "";
        $this->perdProfileField->HrefValue = "";
        $this->perdProfileField->TooltipValue = "";

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

        // perdID
        $this->perdID->setupEditAttributes();
        $this->perdID->EditCustomAttributes = "";
        $this->perdID->EditValue = $this->perdID->CurrentValue;
        $this->perdID->ViewCustomAttributes = "";

        // perdEmri
        $this->perdEmri->setupEditAttributes();
        $this->perdEmri->EditCustomAttributes = "";
        if (!$this->perdEmri->Raw) {
            $this->perdEmri->CurrentValue = HtmlDecode($this->perdEmri->CurrentValue);
        }
        $this->perdEmri->EditValue = $this->perdEmri->CurrentValue;
        $this->perdEmri->PlaceHolder = RemoveHtml($this->perdEmri->caption());

        // perdUsername
        $this->perdUsername->setupEditAttributes();
        $this->perdUsername->EditCustomAttributes = "";
        if (!$this->perdUsername->Raw) {
            $this->perdUsername->CurrentValue = HtmlDecode($this->perdUsername->CurrentValue);
        }
        $this->perdUsername->EditValue = $this->perdUsername->CurrentValue;
        $this->perdUsername->PlaceHolder = RemoveHtml($this->perdUsername->caption());

        // perdFjalekalimi
        $this->perdFjalekalimi->setupEditAttributes();
        $this->perdFjalekalimi->EditCustomAttributes = "";
        if (!$this->perdFjalekalimi->Raw) {
            $this->perdFjalekalimi->CurrentValue = HtmlDecode($this->perdFjalekalimi->CurrentValue);
        }
        $this->perdFjalekalimi->EditValue = $this->perdFjalekalimi->CurrentValue;
        $this->perdFjalekalimi->PlaceHolder = RemoveHtml($this->perdFjalekalimi->caption());

        // perdEmail
        $this->perdEmail->setupEditAttributes();
        $this->perdEmail->EditCustomAttributes = "";
        if (!$this->perdEmail->Raw) {
            $this->perdEmail->CurrentValue = HtmlDecode($this->perdEmail->CurrentValue);
        }
        $this->perdEmail->EditValue = $this->perdEmail->CurrentValue;
        $this->perdEmail->PlaceHolder = RemoveHtml($this->perdEmail->caption());

        // perdNiveliPerdoruesit
        $this->perdNiveliPerdoruesit->setupEditAttributes();
        $this->perdNiveliPerdoruesit->EditCustomAttributes = "";
        if (!$Security->canAdmin()) { // System admin
            $this->perdNiveliPerdoruesit->EditValue = $Language->phrase("PasswordMask");
        } else {
            $this->perdNiveliPerdoruesit->PlaceHolder = RemoveHtml($this->perdNiveliPerdoruesit->caption());
        }

        // perdDtReg
        $this->perdDtReg->setupEditAttributes();
        $this->perdDtReg->EditCustomAttributes = "";
        $this->perdDtReg->EditValue = FormatDateTime($this->perdDtReg->CurrentValue, $this->perdDtReg->formatPattern());
        $this->perdDtReg->PlaceHolder = RemoveHtml($this->perdDtReg->caption());

        // perdActivated
        $this->perdActivated->setupEditAttributes();
        $this->perdActivated->EditCustomAttributes = "";
        if (!$this->perdActivated->Raw) {
            $this->perdActivated->CurrentValue = HtmlDecode($this->perdActivated->CurrentValue);
        }
        $this->perdActivated->EditValue = $this->perdActivated->CurrentValue;
        $this->perdActivated->PlaceHolder = RemoveHtml($this->perdActivated->caption());

        // perdProfileField
        $this->perdProfileField->setupEditAttributes();
        $this->perdProfileField->EditCustomAttributes = "";
        $this->perdProfileField->EditValue = $this->perdProfileField->CurrentValue;
        $this->perdProfileField->PlaceHolder = RemoveHtml($this->perdProfileField->caption());

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
                    $doc->exportCaption($this->perdID);
                    $doc->exportCaption($this->perdEmri);
                    $doc->exportCaption($this->perdUsername);
                    $doc->exportCaption($this->perdFjalekalimi);
                    $doc->exportCaption($this->perdEmail);
                    $doc->exportCaption($this->perdNiveliPerdoruesit);
                    $doc->exportCaption($this->perdDtReg);
                    $doc->exportCaption($this->perdActivated);
                    $doc->exportCaption($this->perdProfileField);
                } else {
                    $doc->exportCaption($this->perdID);
                    $doc->exportCaption($this->perdEmri);
                    $doc->exportCaption($this->perdUsername);
                    $doc->exportCaption($this->perdFjalekalimi);
                    $doc->exportCaption($this->perdEmail);
                    $doc->exportCaption($this->perdNiveliPerdoruesit);
                    $doc->exportCaption($this->perdDtReg);
                    $doc->exportCaption($this->perdActivated);
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
                        $doc->exportField($this->perdID);
                        $doc->exportField($this->perdEmri);
                        $doc->exportField($this->perdUsername);
                        $doc->exportField($this->perdFjalekalimi);
                        $doc->exportField($this->perdEmail);
                        $doc->exportField($this->perdNiveliPerdoruesit);
                        $doc->exportField($this->perdDtReg);
                        $doc->exportField($this->perdActivated);
                        $doc->exportField($this->perdProfileField);
                    } else {
                        $doc->exportField($this->perdID);
                        $doc->exportField($this->perdEmri);
                        $doc->exportField($this->perdUsername);
                        $doc->exportField($this->perdFjalekalimi);
                        $doc->exportField($this->perdEmail);
                        $doc->exportField($this->perdNiveliPerdoruesit);
                        $doc->exportField($this->perdDtReg);
                        $doc->exportField($this->perdActivated);
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

    // User ID filter
    public function getUserIDFilter($userId)
    {
        global $Security;
        $userIdFilter = '`perdID` = ' . QuotedValue($userId, DATATYPE_NUMBER, Config("USER_TABLE_DBID"));
        return $userIdFilter;
    }

    // Add User ID filter
    public function addUserIDFilter($filter = "", $id = "")
    {
        global $Security;
        $filterWrk = "";
        if ($id == "")
            $id = (CurrentPageID() == "list") ? $this->CurrentAction : CurrentPageID();
        if (!$this->userIDAllow($id) && !$Security->isAdmin()) {
            $filterWrk = $Security->userIdList();
            if ($filterWrk != "") {
                $filterWrk = '`perdID` IN (' . $filterWrk . ')';
            }
        }

        // Call User ID Filtering event
        $this->userIdFiltering($filterWrk);
        AddFilter($filter, $filterWrk);
        return $filter;
    }

    // User ID subquery
    public function getUserIDSubquery(&$fld, &$masterfld)
    {
        global $UserTable;
        $wrk = "";
        $sql = "SELECT " . $masterfld->Expression . " FROM `perdoruesit`";
        $filter = $this->addUserIDFilter("");
        if ($filter != "") {
            $sql .= " WHERE " . $filter;
        }

        // List all values
        $conn = Conn($UserTable->Dbid);
        $config = $conn->getConfiguration();
        $config->setResultCacheImpl($this->Cache);
        if ($rs = $conn->executeCacheQuery($sql, [], [], $this->CacheProfile)->fetchAllNumeric()) {
            foreach ($rs as $row) {
                if ($wrk != "") {
                    $wrk .= ",";
                }
                $wrk .= QuotedValue($row[0], $masterfld->DataType, Config("USER_TABLE_DBID"));
            }
        }
        if ($wrk != "") {
            $wrk = $fld->Expression . " IN (" . $wrk . ")";
        } else { // No User ID value found
            $wrk = "0=1";
        }
        return $wrk;
    }

    // Send register email
    public function sendRegisterEmail($row)
    {
        $email = $this->prepareRegisterEmail($row);
        $args = [];
        $args["rs"] = $row;
        $emailSent = false;
        if ($this->emailSending($email, $args)) { // Use Email_Sending server event of user table
            $emailSent = $email->send();
        }
        return $emailSent;
    }

    // Get activate link
    public function getActivateLink($username, $password, $email)
    {
        return FullUrl("register", "activate") . "?action=confirm&user=" . urlencode($username) . "&activatetoken=" . Encrypt($email) . "," . Encrypt($username) . "," . Encrypt($password);
    }

    // Prepare register email
    public function prepareRegisterEmail($row = null, $langId = "")
    {
        global $CurrentForm;
        $email = new Email();
        $email->load(Config("EMAIL_REGISTER_TEMPLATE"), $langId);
        $email->replaceSender(Config("SENDER_EMAIL")); // Replace Sender
        $emailAddress = $row === null ? $this->perdEmail->CurrentValue : GetUserInfo(Config("USER_EMAIL_FIELD_NAME"), $row);
        $emailAddress = $emailAddress ?: Config("RECIPIENT_EMAIL"); // Send to recipient directly if no email address
        $email->replaceRecipient($emailAddress); // Replace Recipient
        if (!SameText($emailAddress, Config("RECIPIENT_EMAIL"))) { // Add Bcc
            $email->addBcc(Config("RECIPIENT_EMAIL"));
        }
        $email->replaceContent('<!--FieldCaption_perdEmri-->', $this->perdEmri->caption());
        $email->replaceContent('<!--perdEmri-->', $row === null ? strval($this->perdEmri->FormValue) : GetUserInfo('perdEmri', $row));
        $email->replaceContent('<!--FieldCaption_perdUsername-->', $this->perdUsername->caption());
        $email->replaceContent('<!--perdUsername-->', $row === null ? strval($this->perdUsername->FormValue) : GetUserInfo('perdUsername', $row));
        $email->replaceContent('<!--FieldCaption_perdFjalekalimi-->', $this->perdFjalekalimi->caption());
        $email->replaceContent('<!--perdFjalekalimi-->', $row === null ? strval($this->perdFjalekalimi->FormValue) : GetUserInfo('perdFjalekalimi', $row));
        $email->replaceContent('<!--FieldCaption_perdEmail-->', $this->perdEmail->caption());
        $email->replaceContent('<!--perdEmail-->', $row === null ? strval($this->perdEmail->FormValue) : GetUserInfo('perdEmail', $row));
        return $email;
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
