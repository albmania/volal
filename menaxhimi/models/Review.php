<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for review
 */
class Review extends DbTable
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
    public $reviewID;
    public $reviewGjuha;
    public $reviewEmri;
    public $reviewMakine;
    public $reviewPer;
    public $reviewFoto;
    public $reviewTxt;
    public $reviewDate;
    public $reviewAktiv;
    public $reviewRegjistruarNga;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'review';
        $this->TableName = 'review';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`review`";
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

        // reviewID
        $this->reviewID = new DbField(
            'review',
            'review',
            'x_reviewID',
            'reviewID',
            '`reviewID`',
            '`reviewID`',
            3,
            255,
            -1,
            false,
            '`reviewID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->reviewID->InputTextType = "text";
        $this->reviewID->IsAutoIncrement = true; // Autoincrement field
        $this->reviewID->IsPrimaryKey = true; // Primary key field
        $this->reviewID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['reviewID'] = &$this->reviewID;

        // reviewGjuha
        $this->reviewGjuha = new DbField(
            'review',
            'review',
            'x_reviewGjuha',
            'reviewGjuha',
            '`reviewGjuha`',
            '`reviewGjuha`',
            202,
            2,
            -1,
            false,
            '`reviewGjuha`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->reviewGjuha->InputTextType = "text";
        $this->reviewGjuha->Nullable = false; // NOT NULL field
        $this->reviewGjuha->Lookup = new Lookup('reviewGjuha', 'review', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->reviewGjuha->OptionCount = 2;
        $this->Fields['reviewGjuha'] = &$this->reviewGjuha;

        // reviewEmri
        $this->reviewEmri = new DbField(
            'review',
            'review',
            'x_reviewEmri',
            'reviewEmri',
            '`reviewEmri`',
            '`reviewEmri`',
            200,
            100,
            -1,
            false,
            '`reviewEmri`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->reviewEmri->InputTextType = "text";
        $this->reviewEmri->Nullable = false; // NOT NULL field
        $this->reviewEmri->Required = true; // Required field
        $this->Fields['reviewEmri'] = &$this->reviewEmri;

        // reviewMakine
        $this->reviewMakine = new DbField(
            'review',
            'review',
            'x_reviewMakine',
            'reviewMakine',
            '`reviewMakine`',
            '`reviewMakine`',
            200,
            100,
            -1,
            false,
            '`reviewMakine`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->reviewMakine->InputTextType = "text";
        $this->reviewMakine->Nullable = false; // NOT NULL field
        $this->reviewMakine->Required = true; // Required field
        $this->Fields['reviewMakine'] = &$this->reviewMakine;

        // reviewPer
        $this->reviewPer = new DbField(
            'review',
            'review',
            'x_reviewPer',
            'reviewPer',
            '`reviewPer`',
            '`reviewPer`',
            202,
            13,
            -1,
            false,
            '`reviewPer`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->reviewPer->InputTextType = "text";
        $this->reviewPer->Nullable = false; // NOT NULL field
        $this->reviewPer->Required = true; // Required field
        $this->reviewPer->Lookup = new Lookup('reviewPer', 'review', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->reviewPer->OptionCount = 4;
        $this->Fields['reviewPer'] = &$this->reviewPer;

        // reviewFoto
        $this->reviewFoto = new DbField(
            'review',
            'review',
            'x_reviewFoto',
            'reviewFoto',
            '`reviewFoto`',
            '`reviewFoto`',
            200,
            255,
            -1,
            true,
            '`reviewFoto`',
            false,
            false,
            false,
            'IMAGE',
            'FILE'
        );
        $this->reviewFoto->InputTextType = "text";
        $this->reviewFoto->UploadAllowedFileExt = "jpg,jpeg,gif,png";
        $this->reviewFoto->UploadPath = '../ngarkime/review/';
        $this->Fields['reviewFoto'] = &$this->reviewFoto;

        // reviewTxt
        $this->reviewTxt = new DbField(
            'review',
            'review',
            'x_reviewTxt',
            'reviewTxt',
            '`reviewTxt`',
            '`reviewTxt`',
            200,
            250,
            -1,
            false,
            '`reviewTxt`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->reviewTxt->InputTextType = "text";
        $this->reviewTxt->Nullable = false; // NOT NULL field
        $this->reviewTxt->Required = true; // Required field
        $this->Fields['reviewTxt'] = &$this->reviewTxt;

        // reviewDate
        $this->reviewDate = new DbField(
            'review',
            'review',
            'x_reviewDate',
            'reviewDate',
            '`reviewDate`',
            CastDateFieldForLike("`reviewDate`", 7, "DB"),
            133,
            10,
            7,
            false,
            '`reviewDate`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->reviewDate->InputTextType = "text";
        $this->reviewDate->Nullable = false; // NOT NULL field
        $this->reviewDate->Required = true; // Required field
        $this->reviewDate->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->Fields['reviewDate'] = &$this->reviewDate;

        // reviewAktiv
        $this->reviewAktiv = new DbField(
            'review',
            'review',
            'x_reviewAktiv',
            'reviewAktiv',
            '`reviewAktiv`',
            '`reviewAktiv`',
            202,
            2,
            -1,
            false,
            '`reviewAktiv`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->reviewAktiv->InputTextType = "text";
        $this->reviewAktiv->Nullable = false; // NOT NULL field
        $this->reviewAktiv->Lookup = new Lookup('reviewAktiv', 'review', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->reviewAktiv->OptionCount = 2;
        $this->Fields['reviewAktiv'] = &$this->reviewAktiv;

        // reviewRegjistruarNga
        $this->reviewRegjistruarNga = new DbField(
            'review',
            'review',
            'x_reviewRegjistruarNga',
            'reviewRegjistruarNga',
            '`reviewRegjistruarNga`',
            '`reviewRegjistruarNga`',
            201,
            65535,
            -1,
            false,
            '`reviewRegjistruarNga`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->reviewRegjistruarNga->InputTextType = "text";
        $this->Fields['reviewRegjistruarNga'] = &$this->reviewRegjistruarNga;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`review`";
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
            $this->reviewID->setDbValue($conn->lastInsertId());
            $rs['reviewID'] = $this->reviewID->DbValue;
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
            if (array_key_exists('reviewID', $rs)) {
                AddFilter($where, QuotedName('reviewID', $this->Dbid) . '=' . QuotedValue($rs['reviewID'], $this->reviewID->DataType, $this->Dbid));
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
        $this->reviewID->DbValue = $row['reviewID'];
        $this->reviewGjuha->DbValue = $row['reviewGjuha'];
        $this->reviewEmri->DbValue = $row['reviewEmri'];
        $this->reviewMakine->DbValue = $row['reviewMakine'];
        $this->reviewPer->DbValue = $row['reviewPer'];
        $this->reviewFoto->Upload->DbValue = $row['reviewFoto'];
        $this->reviewTxt->DbValue = $row['reviewTxt'];
        $this->reviewDate->DbValue = $row['reviewDate'];
        $this->reviewAktiv->DbValue = $row['reviewAktiv'];
        $this->reviewRegjistruarNga->DbValue = $row['reviewRegjistruarNga'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $this->reviewFoto->OldUploadPath = '../ngarkime/review/';
        $oldFiles = EmptyValue($row['reviewFoto']) ? [] : [$row['reviewFoto']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->reviewFoto->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->reviewFoto->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`reviewID` = @reviewID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->reviewID->CurrentValue : $this->reviewID->OldValue;
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
                $this->reviewID->CurrentValue = $keys[0];
            } else {
                $this->reviewID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('reviewID', $row) ? $row['reviewID'] : null;
        } else {
            $val = $this->reviewID->OldValue !== null ? $this->reviewID->OldValue : $this->reviewID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@reviewID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("ReviewList");
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
        if ($pageName == "ReviewView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ReviewEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ReviewAdd") {
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
                return "ReviewView";
            case Config("API_ADD_ACTION"):
                return "ReviewAdd";
            case Config("API_EDIT_ACTION"):
                return "ReviewEdit";
            case Config("API_DELETE_ACTION"):
                return "ReviewDelete";
            case Config("API_LIST_ACTION"):
                return "ReviewList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ReviewList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ReviewView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ReviewView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ReviewAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ReviewAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ReviewEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("ReviewAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("ReviewDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"reviewID\":" . JsonEncode($this->reviewID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->reviewID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->reviewID->CurrentValue);
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
            if (($keyValue = Param("reviewID") ?? Route("reviewID")) !== null) {
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
                $this->reviewID->CurrentValue = $key;
            } else {
                $this->reviewID->OldValue = $key;
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
        $this->reviewID->setDbValue($row['reviewID']);
        $this->reviewGjuha->setDbValue($row['reviewGjuha']);
        $this->reviewEmri->setDbValue($row['reviewEmri']);
        $this->reviewMakine->setDbValue($row['reviewMakine']);
        $this->reviewPer->setDbValue($row['reviewPer']);
        $this->reviewFoto->Upload->DbValue = $row['reviewFoto'];
        $this->reviewTxt->setDbValue($row['reviewTxt']);
        $this->reviewDate->setDbValue($row['reviewDate']);
        $this->reviewAktiv->setDbValue($row['reviewAktiv']);
        $this->reviewRegjistruarNga->setDbValue($row['reviewRegjistruarNga']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // reviewID

        // reviewGjuha

        // reviewEmri

        // reviewMakine

        // reviewPer

        // reviewFoto

        // reviewTxt

        // reviewDate

        // reviewAktiv

        // reviewRegjistruarNga

        // reviewID
        $this->reviewID->ViewValue = $this->reviewID->CurrentValue;
        $this->reviewID->ViewCustomAttributes = "";

        // reviewGjuha
        if (strval($this->reviewGjuha->CurrentValue) != "") {
            $this->reviewGjuha->ViewValue = $this->reviewGjuha->optionCaption($this->reviewGjuha->CurrentValue);
        } else {
            $this->reviewGjuha->ViewValue = null;
        }
        $this->reviewGjuha->ViewCustomAttributes = "";

        // reviewEmri
        $this->reviewEmri->ViewValue = $this->reviewEmri->CurrentValue;
        $this->reviewEmri->ViewCustomAttributes = "";

        // reviewMakine
        $this->reviewMakine->ViewValue = $this->reviewMakine->CurrentValue;
        $this->reviewMakine->ViewCustomAttributes = "";

        // reviewPer
        if (strval($this->reviewPer->CurrentValue) != "") {
            $this->reviewPer->ViewValue = $this->reviewPer->optionCaption($this->reviewPer->CurrentValue);
        } else {
            $this->reviewPer->ViewValue = null;
        }
        $this->reviewPer->ViewCustomAttributes = "";

        // reviewFoto
        $this->reviewFoto->UploadPath = '../ngarkime/review/';
        if (!EmptyValue($this->reviewFoto->Upload->DbValue)) {
            $this->reviewFoto->ImageWidth = 0;
            $this->reviewFoto->ImageHeight = 50;
            $this->reviewFoto->ImageAlt = $this->reviewFoto->alt();
            $this->reviewFoto->ImageCssClass = "ew-image";
            $this->reviewFoto->ViewValue = $this->reviewFoto->Upload->DbValue;
        } else {
            $this->reviewFoto->ViewValue = "";
        }
        $this->reviewFoto->ViewCustomAttributes = "";

        // reviewTxt
        $this->reviewTxt->ViewValue = $this->reviewTxt->CurrentValue;
        $this->reviewTxt->ViewCustomAttributes = "";

        // reviewDate
        $this->reviewDate->ViewValue = $this->reviewDate->CurrentValue;
        $this->reviewDate->ViewValue = FormatDateTime($this->reviewDate->ViewValue, $this->reviewDate->formatPattern());
        $this->reviewDate->ViewCustomAttributes = "";

        // reviewAktiv
        if (strval($this->reviewAktiv->CurrentValue) != "") {
            $this->reviewAktiv->ViewValue = $this->reviewAktiv->optionCaption($this->reviewAktiv->CurrentValue);
        } else {
            $this->reviewAktiv->ViewValue = null;
        }
        $this->reviewAktiv->ViewCustomAttributes = "";

        // reviewRegjistruarNga
        $this->reviewRegjistruarNga->ViewValue = $this->reviewRegjistruarNga->CurrentValue;
        $this->reviewRegjistruarNga->ViewCustomAttributes = "";

        // reviewID
        $this->reviewID->LinkCustomAttributes = "";
        $this->reviewID->HrefValue = "";
        $this->reviewID->TooltipValue = "";

        // reviewGjuha
        $this->reviewGjuha->LinkCustomAttributes = "";
        $this->reviewGjuha->HrefValue = "";
        $this->reviewGjuha->TooltipValue = "";

        // reviewEmri
        $this->reviewEmri->LinkCustomAttributes = "";
        $this->reviewEmri->HrefValue = "";
        $this->reviewEmri->TooltipValue = "";

        // reviewMakine
        $this->reviewMakine->LinkCustomAttributes = "";
        $this->reviewMakine->HrefValue = "";
        $this->reviewMakine->TooltipValue = "";

        // reviewPer
        $this->reviewPer->LinkCustomAttributes = "";
        $this->reviewPer->HrefValue = "";
        $this->reviewPer->TooltipValue = "";

        // reviewFoto
        $this->reviewFoto->LinkCustomAttributes = "";
        $this->reviewFoto->UploadPath = '../ngarkime/review/';
        if (!EmptyValue($this->reviewFoto->Upload->DbValue)) {
            $this->reviewFoto->HrefValue = GetFileUploadUrl($this->reviewFoto, $this->reviewFoto->htmlDecode($this->reviewFoto->Upload->DbValue)); // Add prefix/suffix
            $this->reviewFoto->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->reviewFoto->HrefValue = FullUrl($this->reviewFoto->HrefValue, "href");
            }
        } else {
            $this->reviewFoto->HrefValue = "";
        }
        $this->reviewFoto->ExportHrefValue = $this->reviewFoto->UploadPath . $this->reviewFoto->Upload->DbValue;
        $this->reviewFoto->TooltipValue = "";
        if ($this->reviewFoto->UseColorbox) {
            if (EmptyValue($this->reviewFoto->TooltipValue)) {
                $this->reviewFoto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->reviewFoto->LinkAttrs["data-rel"] = "review_x_reviewFoto";
            $this->reviewFoto->LinkAttrs->appendClass("ew-lightbox");
        }

        // reviewTxt
        $this->reviewTxt->LinkCustomAttributes = "";
        $this->reviewTxt->HrefValue = "";
        $this->reviewTxt->TooltipValue = "";

        // reviewDate
        $this->reviewDate->LinkCustomAttributes = "";
        $this->reviewDate->HrefValue = "";
        $this->reviewDate->TooltipValue = "";

        // reviewAktiv
        $this->reviewAktiv->LinkCustomAttributes = "";
        $this->reviewAktiv->HrefValue = "";
        $this->reviewAktiv->TooltipValue = "";

        // reviewRegjistruarNga
        $this->reviewRegjistruarNga->LinkCustomAttributes = "";
        $this->reviewRegjistruarNga->HrefValue = "";
        $this->reviewRegjistruarNga->TooltipValue = "";

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

        // reviewID
        $this->reviewID->setupEditAttributes();
        $this->reviewID->EditCustomAttributes = "";
        $this->reviewID->EditValue = $this->reviewID->CurrentValue;
        $this->reviewID->ViewCustomAttributes = "";

        // reviewGjuha
        $this->reviewGjuha->EditCustomAttributes = "";
        $this->reviewGjuha->EditValue = $this->reviewGjuha->options(false);
        $this->reviewGjuha->PlaceHolder = RemoveHtml($this->reviewGjuha->caption());

        // reviewEmri
        $this->reviewEmri->setupEditAttributes();
        $this->reviewEmri->EditCustomAttributes = "";
        if (!$this->reviewEmri->Raw) {
            $this->reviewEmri->CurrentValue = HtmlDecode($this->reviewEmri->CurrentValue);
        }
        $this->reviewEmri->EditValue = $this->reviewEmri->CurrentValue;
        $this->reviewEmri->PlaceHolder = RemoveHtml($this->reviewEmri->caption());

        // reviewMakine
        $this->reviewMakine->setupEditAttributes();
        $this->reviewMakine->EditCustomAttributes = "";
        if (!$this->reviewMakine->Raw) {
            $this->reviewMakine->CurrentValue = HtmlDecode($this->reviewMakine->CurrentValue);
        }
        $this->reviewMakine->EditValue = $this->reviewMakine->CurrentValue;
        $this->reviewMakine->PlaceHolder = RemoveHtml($this->reviewMakine->caption());

        // reviewPer
        $this->reviewPer->EditCustomAttributes = "";
        $this->reviewPer->EditValue = $this->reviewPer->options(false);
        $this->reviewPer->PlaceHolder = RemoveHtml($this->reviewPer->caption());

        // reviewFoto
        $this->reviewFoto->setupEditAttributes();
        $this->reviewFoto->EditCustomAttributes = "";
        $this->reviewFoto->UploadPath = '../ngarkime/review/';
        if (!EmptyValue($this->reviewFoto->Upload->DbValue)) {
            $this->reviewFoto->ImageWidth = 0;
            $this->reviewFoto->ImageHeight = 50;
            $this->reviewFoto->ImageAlt = $this->reviewFoto->alt();
            $this->reviewFoto->ImageCssClass = "ew-image";
            $this->reviewFoto->EditValue = $this->reviewFoto->Upload->DbValue;
        } else {
            $this->reviewFoto->EditValue = "";
        }
        if (!EmptyValue($this->reviewFoto->CurrentValue)) {
            $this->reviewFoto->Upload->FileName = $this->reviewFoto->CurrentValue;
        }

        // reviewTxt
        $this->reviewTxt->setupEditAttributes();
        $this->reviewTxt->EditCustomAttributes = "";
        if (!$this->reviewTxt->Raw) {
            $this->reviewTxt->CurrentValue = HtmlDecode($this->reviewTxt->CurrentValue);
        }
        $this->reviewTxt->EditValue = $this->reviewTxt->CurrentValue;
        $this->reviewTxt->PlaceHolder = RemoveHtml($this->reviewTxt->caption());

        // reviewDate
        $this->reviewDate->setupEditAttributes();
        $this->reviewDate->EditCustomAttributes = "";
        $this->reviewDate->EditValue = FormatDateTime($this->reviewDate->CurrentValue, $this->reviewDate->formatPattern());
        $this->reviewDate->PlaceHolder = RemoveHtml($this->reviewDate->caption());

        // reviewAktiv
        $this->reviewAktiv->EditCustomAttributes = "";
        $this->reviewAktiv->EditValue = $this->reviewAktiv->options(false);
        $this->reviewAktiv->PlaceHolder = RemoveHtml($this->reviewAktiv->caption());

        // reviewRegjistruarNga

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
                    $doc->exportCaption($this->reviewID);
                    $doc->exportCaption($this->reviewGjuha);
                    $doc->exportCaption($this->reviewEmri);
                    $doc->exportCaption($this->reviewMakine);
                    $doc->exportCaption($this->reviewPer);
                    $doc->exportCaption($this->reviewFoto);
                    $doc->exportCaption($this->reviewTxt);
                    $doc->exportCaption($this->reviewDate);
                    $doc->exportCaption($this->reviewAktiv);
                    $doc->exportCaption($this->reviewRegjistruarNga);
                } else {
                    $doc->exportCaption($this->reviewID);
                    $doc->exportCaption($this->reviewGjuha);
                    $doc->exportCaption($this->reviewEmri);
                    $doc->exportCaption($this->reviewMakine);
                    $doc->exportCaption($this->reviewPer);
                    $doc->exportCaption($this->reviewFoto);
                    $doc->exportCaption($this->reviewTxt);
                    $doc->exportCaption($this->reviewDate);
                    $doc->exportCaption($this->reviewAktiv);
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
                        $doc->exportField($this->reviewID);
                        $doc->exportField($this->reviewGjuha);
                        $doc->exportField($this->reviewEmri);
                        $doc->exportField($this->reviewMakine);
                        $doc->exportField($this->reviewPer);
                        $doc->exportField($this->reviewFoto);
                        $doc->exportField($this->reviewTxt);
                        $doc->exportField($this->reviewDate);
                        $doc->exportField($this->reviewAktiv);
                        $doc->exportField($this->reviewRegjistruarNga);
                    } else {
                        $doc->exportField($this->reviewID);
                        $doc->exportField($this->reviewGjuha);
                        $doc->exportField($this->reviewEmri);
                        $doc->exportField($this->reviewMakine);
                        $doc->exportField($this->reviewPer);
                        $doc->exportField($this->reviewFoto);
                        $doc->exportField($this->reviewTxt);
                        $doc->exportField($this->reviewDate);
                        $doc->exportField($this->reviewAktiv);
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
        if ($fldparm == 'reviewFoto') {
            $fldName = "reviewFoto";
            $fileNameFld = "reviewFoto";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->reviewID->CurrentValue = $ar[0];
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
