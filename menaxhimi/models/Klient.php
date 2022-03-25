<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for klient
 */
class Klient extends DbTable
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
    public $klientID;
    public $klientTipi;
    public $klientEmertimi;
    public $klientNIPT;
    public $klientAdresa;
    public $klientQyteti;
    public $klientTel1;
    public $klientTel2;
    public $klientEmail;
    public $klientAutori;
    public $klientShtuar;
    public $klientModifikuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'klient';
        $this->TableName = 'klient';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`klient`";
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

        // klientID
        $this->klientID = new DbField(
            'klient',
            'klient',
            'x_klientID',
            'klientID',
            '`klientID`',
            '`klientID`',
            3,
            255,
            -1,
            false,
            '`klientID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->klientID->InputTextType = "text";
        $this->klientID->IsAutoIncrement = true; // Autoincrement field
        $this->klientID->IsPrimaryKey = true; // Primary key field
        $this->klientID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['klientID'] = &$this->klientID;

        // klientTipi
        $this->klientTipi = new DbField(
            'klient',
            'klient',
            'x_klientTipi',
            'klientTipi',
            '`klientTipi`',
            '`klientTipi`',
            202,
            7,
            -1,
            false,
            '`klientTipi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->klientTipi->InputTextType = "text";
        $this->klientTipi->Nullable = false; // NOT NULL field
        $this->klientTipi->Required = true; // Required field
        $this->klientTipi->Lookup = new Lookup('klientTipi', 'klient', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->klientTipi->OptionCount = 5;
        $this->Fields['klientTipi'] = &$this->klientTipi;

        // klientEmertimi
        $this->klientEmertimi = new DbField(
            'klient',
            'klient',
            'x_klientEmertimi',
            'klientEmertimi',
            '`klientEmertimi`',
            '`klientEmertimi`',
            200,
            200,
            -1,
            false,
            '`klientEmertimi`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientEmertimi->InputTextType = "text";
        $this->klientEmertimi->Nullable = false; // NOT NULL field
        $this->klientEmertimi->Required = true; // Required field
        $this->Fields['klientEmertimi'] = &$this->klientEmertimi;

        // klientNIPT
        $this->klientNIPT = new DbField(
            'klient',
            'klient',
            'x_klientNIPT',
            'klientNIPT',
            '`klientNIPT`',
            '`klientNIPT`',
            200,
            25,
            -1,
            false,
            '`klientNIPT`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientNIPT->InputTextType = "text";
        $this->Fields['klientNIPT'] = &$this->klientNIPT;

        // klientAdresa
        $this->klientAdresa = new DbField(
            'klient',
            'klient',
            'x_klientAdresa',
            'klientAdresa',
            '`klientAdresa`',
            '`klientAdresa`',
            200,
            250,
            -1,
            false,
            '`klientAdresa`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientAdresa->InputTextType = "text";
        $this->klientAdresa->Nullable = false; // NOT NULL field
        $this->klientAdresa->Required = true; // Required field
        $this->Fields['klientAdresa'] = &$this->klientAdresa;

        // klientQyteti
        $this->klientQyteti = new DbField(
            'klient',
            'klient',
            'x_klientQyteti',
            'klientQyteti',
            '`klientQyteti`',
            '`klientQyteti`',
            200,
            20,
            -1,
            false,
            '`klientQyteti`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientQyteti->InputTextType = "text";
        $this->klientQyteti->Nullable = false; // NOT NULL field
        $this->klientQyteti->Required = true; // Required field
        $this->Fields['klientQyteti'] = &$this->klientQyteti;

        // klientTel1
        $this->klientTel1 = new DbField(
            'klient',
            'klient',
            'x_klientTel1',
            'klientTel1',
            '`klientTel1`',
            '`klientTel1`',
            200,
            20,
            -1,
            false,
            '`klientTel1`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientTel1->InputTextType = "text";
        $this->klientTel1->Nullable = false; // NOT NULL field
        $this->klientTel1->Required = true; // Required field
        $this->Fields['klientTel1'] = &$this->klientTel1;

        // klientTel2
        $this->klientTel2 = new DbField(
            'klient',
            'klient',
            'x_klientTel2',
            'klientTel2',
            '`klientTel2`',
            '`klientTel2`',
            200,
            20,
            -1,
            false,
            '`klientTel2`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientTel2->InputTextType = "text";
        $this->Fields['klientTel2'] = &$this->klientTel2;

        // klientEmail
        $this->klientEmail = new DbField(
            'klient',
            'klient',
            'x_klientEmail',
            'klientEmail',
            '`klientEmail`',
            '`klientEmail`',
            200,
            100,
            -1,
            false,
            '`klientEmail`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientEmail->InputTextType = "text";
        $this->klientEmail->DefaultErrorMessage = $Language->phrase("IncorrectEmail");
        $this->Fields['klientEmail'] = &$this->klientEmail;

        // klientAutori
        $this->klientAutori = new DbField(
            'klient',
            'klient',
            'x_klientAutori',
            'klientAutori',
            '`klientAutori`',
            '`klientAutori`',
            3,
            255,
            -1,
            false,
            '`klientAutori`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientAutori->InputTextType = "text";
        $this->klientAutori->Nullable = false; // NOT NULL field
        $this->klientAutori->Lookup = new Lookup('klientAutori', 'perdoruesit', false, 'perdID', ["perdEmri","","",""], [], [], [], [], [], [], '', '', "`perdEmri`");
        $this->klientAutori->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['klientAutori'] = &$this->klientAutori;

        // klientShtuar
        $this->klientShtuar = new DbField(
            'klient',
            'klient',
            'x_klientShtuar',
            'klientShtuar',
            '`klientShtuar`',
            CastDateFieldForLike("`klientShtuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`klientShtuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientShtuar->InputTextType = "text";
        $this->klientShtuar->Nullable = false; // NOT NULL field
        $this->klientShtuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['klientShtuar'] = &$this->klientShtuar;

        // klientModifikuar
        $this->klientModifikuar = new DbField(
            'klient',
            'klient',
            'x_klientModifikuar',
            'klientModifikuar',
            '`klientModifikuar`',
            CastDateFieldForLike("`klientModifikuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`klientModifikuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->klientModifikuar->InputTextType = "text";
        $this->klientModifikuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['klientModifikuar'] = &$this->klientModifikuar;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`klient`";
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
            $this->klientID->setDbValue($conn->lastInsertId());
            $rs['klientID'] = $this->klientID->DbValue;
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
            if (array_key_exists('klientID', $rs)) {
                AddFilter($where, QuotedName('klientID', $this->Dbid) . '=' . QuotedValue($rs['klientID'], $this->klientID->DataType, $this->Dbid));
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
        $this->klientID->DbValue = $row['klientID'];
        $this->klientTipi->DbValue = $row['klientTipi'];
        $this->klientEmertimi->DbValue = $row['klientEmertimi'];
        $this->klientNIPT->DbValue = $row['klientNIPT'];
        $this->klientAdresa->DbValue = $row['klientAdresa'];
        $this->klientQyteti->DbValue = $row['klientQyteti'];
        $this->klientTel1->DbValue = $row['klientTel1'];
        $this->klientTel2->DbValue = $row['klientTel2'];
        $this->klientEmail->DbValue = $row['klientEmail'];
        $this->klientAutori->DbValue = $row['klientAutori'];
        $this->klientShtuar->DbValue = $row['klientShtuar'];
        $this->klientModifikuar->DbValue = $row['klientModifikuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`klientID` = @klientID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->klientID->CurrentValue : $this->klientID->OldValue;
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
                $this->klientID->CurrentValue = $keys[0];
            } else {
                $this->klientID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('klientID', $row) ? $row['klientID'] : null;
        } else {
            $val = $this->klientID->OldValue !== null ? $this->klientID->OldValue : $this->klientID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@klientID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("KlientList");
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
        if ($pageName == "KlientView") {
            return $Language->phrase("View");
        } elseif ($pageName == "KlientEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "KlientAdd") {
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
                return "KlientView";
            case Config("API_ADD_ACTION"):
                return "KlientAdd";
            case Config("API_EDIT_ACTION"):
                return "KlientEdit";
            case Config("API_DELETE_ACTION"):
                return "KlientDelete";
            case Config("API_LIST_ACTION"):
                return "KlientList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "KlientList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("KlientView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("KlientView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "KlientAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "KlientAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("KlientEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("KlientAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("KlientDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"klientID\":" . JsonEncode($this->klientID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->klientID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->klientID->CurrentValue);
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
            if (($keyValue = Param("klientID") ?? Route("klientID")) !== null) {
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
                $this->klientID->CurrentValue = $key;
            } else {
                $this->klientID->OldValue = $key;
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
        $this->klientID->setDbValue($row['klientID']);
        $this->klientTipi->setDbValue($row['klientTipi']);
        $this->klientEmertimi->setDbValue($row['klientEmertimi']);
        $this->klientNIPT->setDbValue($row['klientNIPT']);
        $this->klientAdresa->setDbValue($row['klientAdresa']);
        $this->klientQyteti->setDbValue($row['klientQyteti']);
        $this->klientTel1->setDbValue($row['klientTel1']);
        $this->klientTel2->setDbValue($row['klientTel2']);
        $this->klientEmail->setDbValue($row['klientEmail']);
        $this->klientAutori->setDbValue($row['klientAutori']);
        $this->klientShtuar->setDbValue($row['klientShtuar']);
        $this->klientModifikuar->setDbValue($row['klientModifikuar']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // klientID

        // klientTipi

        // klientEmertimi

        // klientNIPT

        // klientAdresa

        // klientQyteti

        // klientTel1

        // klientTel2

        // klientEmail

        // klientAutori

        // klientShtuar

        // klientModifikuar

        // klientID
        $this->klientID->ViewValue = $this->klientID->CurrentValue;
        $this->klientID->ViewCustomAttributes = "";

        // klientTipi
        if (strval($this->klientTipi->CurrentValue) != "") {
            $this->klientTipi->ViewValue = $this->klientTipi->optionCaption($this->klientTipi->CurrentValue);
        } else {
            $this->klientTipi->ViewValue = null;
        }
        $this->klientTipi->ViewCustomAttributes = "";

        // klientEmertimi
        $this->klientEmertimi->ViewValue = $this->klientEmertimi->CurrentValue;
        $this->klientEmertimi->ViewCustomAttributes = "";

        // klientNIPT
        $this->klientNIPT->ViewValue = $this->klientNIPT->CurrentValue;
        $this->klientNIPT->ViewCustomAttributes = "";

        // klientAdresa
        $this->klientAdresa->ViewValue = $this->klientAdresa->CurrentValue;
        $this->klientAdresa->ViewCustomAttributes = "";

        // klientQyteti
        $this->klientQyteti->ViewValue = $this->klientQyteti->CurrentValue;
        $this->klientQyteti->ViewCustomAttributes = "";

        // klientTel1
        $this->klientTel1->ViewValue = $this->klientTel1->CurrentValue;
        $this->klientTel1->ViewCustomAttributes = "";

        // klientTel2
        $this->klientTel2->ViewValue = $this->klientTel2->CurrentValue;
        $this->klientTel2->ViewCustomAttributes = "";

        // klientEmail
        $this->klientEmail->ViewValue = $this->klientEmail->CurrentValue;
        $this->klientEmail->ViewCustomAttributes = "";

        // klientAutori
        $this->klientAutori->ViewValue = $this->klientAutori->CurrentValue;
        $curVal = strval($this->klientAutori->CurrentValue);
        if ($curVal != "") {
            $this->klientAutori->ViewValue = $this->klientAutori->lookupCacheOption($curVal);
            if ($this->klientAutori->ViewValue === null) { // Lookup from database
                $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->klientAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->klientAutori->Lookup->renderViewRow($rswrk[0]);
                    $this->klientAutori->ViewValue = $this->klientAutori->displayValue($arwrk);
                } else {
                    $this->klientAutori->ViewValue = FormatNumber($this->klientAutori->CurrentValue, $this->klientAutori->formatPattern());
                }
            }
        } else {
            $this->klientAutori->ViewValue = null;
        }
        $this->klientAutori->ViewCustomAttributes = "";

        // klientShtuar
        $this->klientShtuar->ViewValue = $this->klientShtuar->CurrentValue;
        $this->klientShtuar->ViewValue = FormatDateTime($this->klientShtuar->ViewValue, $this->klientShtuar->formatPattern());
        $this->klientShtuar->ViewCustomAttributes = "";

        // klientModifikuar
        $this->klientModifikuar->ViewValue = $this->klientModifikuar->CurrentValue;
        $this->klientModifikuar->ViewValue = FormatDateTime($this->klientModifikuar->ViewValue, $this->klientModifikuar->formatPattern());
        $this->klientModifikuar->ViewCustomAttributes = "";

        // klientID
        $this->klientID->LinkCustomAttributes = "";
        $this->klientID->HrefValue = "";
        $this->klientID->TooltipValue = "";

        // klientTipi
        $this->klientTipi->LinkCustomAttributes = "";
        $this->klientTipi->HrefValue = "";
        $this->klientTipi->TooltipValue = "";

        // klientEmertimi
        $this->klientEmertimi->LinkCustomAttributes = "";
        $this->klientEmertimi->HrefValue = "";
        $this->klientEmertimi->TooltipValue = "";

        // klientNIPT
        $this->klientNIPT->LinkCustomAttributes = "";
        $this->klientNIPT->HrefValue = "";
        $this->klientNIPT->TooltipValue = "";

        // klientAdresa
        $this->klientAdresa->LinkCustomAttributes = "";
        $this->klientAdresa->HrefValue = "";
        $this->klientAdresa->TooltipValue = "";

        // klientQyteti
        $this->klientQyteti->LinkCustomAttributes = "";
        $this->klientQyteti->HrefValue = "";
        $this->klientQyteti->TooltipValue = "";

        // klientTel1
        $this->klientTel1->LinkCustomAttributes = "";
        $this->klientTel1->HrefValue = "";
        $this->klientTel1->TooltipValue = "";

        // klientTel2
        $this->klientTel2->LinkCustomAttributes = "";
        $this->klientTel2->HrefValue = "";
        $this->klientTel2->TooltipValue = "";

        // klientEmail
        $this->klientEmail->LinkCustomAttributes = "";
        $this->klientEmail->HrefValue = "";
        $this->klientEmail->TooltipValue = "";

        // klientAutori
        $this->klientAutori->LinkCustomAttributes = "";
        $this->klientAutori->HrefValue = "";
        $this->klientAutori->TooltipValue = "";

        // klientShtuar
        $this->klientShtuar->LinkCustomAttributes = "";
        $this->klientShtuar->HrefValue = "";
        $this->klientShtuar->TooltipValue = "";

        // klientModifikuar
        $this->klientModifikuar->LinkCustomAttributes = "";
        $this->klientModifikuar->HrefValue = "";
        $this->klientModifikuar->TooltipValue = "";

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

        // klientID
        $this->klientID->setupEditAttributes();
        $this->klientID->EditCustomAttributes = "";
        $this->klientID->EditValue = $this->klientID->CurrentValue;
        $this->klientID->ViewCustomAttributes = "";

        // klientTipi
        $this->klientTipi->EditCustomAttributes = "";
        $this->klientTipi->EditValue = $this->klientTipi->options(false);
        $this->klientTipi->PlaceHolder = RemoveHtml($this->klientTipi->caption());

        // klientEmertimi
        $this->klientEmertimi->setupEditAttributes();
        $this->klientEmertimi->EditCustomAttributes = "";
        if (!$this->klientEmertimi->Raw) {
            $this->klientEmertimi->CurrentValue = HtmlDecode($this->klientEmertimi->CurrentValue);
        }
        $this->klientEmertimi->EditValue = $this->klientEmertimi->CurrentValue;
        $this->klientEmertimi->PlaceHolder = RemoveHtml($this->klientEmertimi->caption());

        // klientNIPT
        $this->klientNIPT->setupEditAttributes();
        $this->klientNIPT->EditCustomAttributes = "";
        if (!$this->klientNIPT->Raw) {
            $this->klientNIPT->CurrentValue = HtmlDecode($this->klientNIPT->CurrentValue);
        }
        $this->klientNIPT->EditValue = $this->klientNIPT->CurrentValue;
        $this->klientNIPT->PlaceHolder = RemoveHtml($this->klientNIPT->caption());

        // klientAdresa
        $this->klientAdresa->setupEditAttributes();
        $this->klientAdresa->EditCustomAttributes = "";
        if (!$this->klientAdresa->Raw) {
            $this->klientAdresa->CurrentValue = HtmlDecode($this->klientAdresa->CurrentValue);
        }
        $this->klientAdresa->EditValue = $this->klientAdresa->CurrentValue;
        $this->klientAdresa->PlaceHolder = RemoveHtml($this->klientAdresa->caption());

        // klientQyteti
        $this->klientQyteti->setupEditAttributes();
        $this->klientQyteti->EditCustomAttributes = "";
        if (!$this->klientQyteti->Raw) {
            $this->klientQyteti->CurrentValue = HtmlDecode($this->klientQyteti->CurrentValue);
        }
        $this->klientQyteti->EditValue = $this->klientQyteti->CurrentValue;
        $this->klientQyteti->PlaceHolder = RemoveHtml($this->klientQyteti->caption());

        // klientTel1
        $this->klientTel1->setupEditAttributes();
        $this->klientTel1->EditCustomAttributes = "";
        if (!$this->klientTel1->Raw) {
            $this->klientTel1->CurrentValue = HtmlDecode($this->klientTel1->CurrentValue);
        }
        $this->klientTel1->EditValue = $this->klientTel1->CurrentValue;
        $this->klientTel1->PlaceHolder = RemoveHtml($this->klientTel1->caption());

        // klientTel2
        $this->klientTel2->setupEditAttributes();
        $this->klientTel2->EditCustomAttributes = "";
        if (!$this->klientTel2->Raw) {
            $this->klientTel2->CurrentValue = HtmlDecode($this->klientTel2->CurrentValue);
        }
        $this->klientTel2->EditValue = $this->klientTel2->CurrentValue;
        $this->klientTel2->PlaceHolder = RemoveHtml($this->klientTel2->caption());

        // klientEmail
        $this->klientEmail->setupEditAttributes();
        $this->klientEmail->EditCustomAttributes = "";
        if (!$this->klientEmail->Raw) {
            $this->klientEmail->CurrentValue = HtmlDecode($this->klientEmail->CurrentValue);
        }
        $this->klientEmail->EditValue = $this->klientEmail->CurrentValue;
        $this->klientEmail->PlaceHolder = RemoveHtml($this->klientEmail->caption());

        // klientAutori

        // klientShtuar

        // klientModifikuar

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
                    $doc->exportCaption($this->klientID);
                    $doc->exportCaption($this->klientTipi);
                    $doc->exportCaption($this->klientEmertimi);
                    $doc->exportCaption($this->klientNIPT);
                    $doc->exportCaption($this->klientAdresa);
                    $doc->exportCaption($this->klientQyteti);
                    $doc->exportCaption($this->klientTel1);
                    $doc->exportCaption($this->klientTel2);
                    $doc->exportCaption($this->klientEmail);
                    $doc->exportCaption($this->klientAutori);
                    $doc->exportCaption($this->klientShtuar);
                    $doc->exportCaption($this->klientModifikuar);
                } else {
                    $doc->exportCaption($this->klientID);
                    $doc->exportCaption($this->klientTipi);
                    $doc->exportCaption($this->klientEmertimi);
                    $doc->exportCaption($this->klientNIPT);
                    $doc->exportCaption($this->klientAdresa);
                    $doc->exportCaption($this->klientQyteti);
                    $doc->exportCaption($this->klientTel1);
                    $doc->exportCaption($this->klientTel2);
                    $doc->exportCaption($this->klientEmail);
                    $doc->exportCaption($this->klientAutori);
                    $doc->exportCaption($this->klientShtuar);
                    $doc->exportCaption($this->klientModifikuar);
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
                        $doc->exportField($this->klientID);
                        $doc->exportField($this->klientTipi);
                        $doc->exportField($this->klientEmertimi);
                        $doc->exportField($this->klientNIPT);
                        $doc->exportField($this->klientAdresa);
                        $doc->exportField($this->klientQyteti);
                        $doc->exportField($this->klientTel1);
                        $doc->exportField($this->klientTel2);
                        $doc->exportField($this->klientEmail);
                        $doc->exportField($this->klientAutori);
                        $doc->exportField($this->klientShtuar);
                        $doc->exportField($this->klientModifikuar);
                    } else {
                        $doc->exportField($this->klientID);
                        $doc->exportField($this->klientTipi);
                        $doc->exportField($this->klientEmertimi);
                        $doc->exportField($this->klientNIPT);
                        $doc->exportField($this->klientAdresa);
                        $doc->exportField($this->klientQyteti);
                        $doc->exportField($this->klientTel1);
                        $doc->exportField($this->klientTel2);
                        $doc->exportField($this->klientEmail);
                        $doc->exportField($this->klientAutori);
                        $doc->exportField($this->klientShtuar);
                        $doc->exportField($this->klientModifikuar);
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
