<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for blog
 */
class Blog extends DbTable
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
    public $blogID;
    public $blogGjuha;
    public $blogKategoria;
    public $blogTitulli;
    public $blogTxt;
    public $blogFoto;
    public $blogVideo;
    public $blogDtPublik;
    public $blogAutori;
    public $blogShtuar;
    public $blogModifikuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'blog';
        $this->TableName = 'blog';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`blog`";
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

        // blogID
        $this->blogID = new DbField(
            'blog',
            'blog',
            'x_blogID',
            'blogID',
            '`blogID`',
            '`blogID`',
            3,
            255,
            -1,
            false,
            '`blogID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->blogID->InputTextType = "text";
        $this->blogID->IsAutoIncrement = true; // Autoincrement field
        $this->blogID->IsPrimaryKey = true; // Primary key field
        $this->blogID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['blogID'] = &$this->blogID;

        // blogGjuha
        $this->blogGjuha = new DbField(
            'blog',
            'blog',
            'x_blogGjuha',
            'blogGjuha',
            '`blogGjuha`',
            '`blogGjuha`',
            202,
            2,
            -1,
            false,
            '`blogGjuha`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->blogGjuha->InputTextType = "text";
        $this->blogGjuha->Nullable = false; // NOT NULL field
        $this->blogGjuha->Required = true; // Required field
        $this->blogGjuha->Lookup = new Lookup('blogGjuha', 'blog', false, '', ["","","",""], [], ["x_blogKategoria"], [], [], [], [], '', '', "");
        $this->blogGjuha->OptionCount = 2;
        $this->Fields['blogGjuha'] = &$this->blogGjuha;

        // blogKategoria
        $this->blogKategoria = new DbField(
            'blog',
            'blog',
            'x_blogKategoria',
            'blogKategoria',
            '`blogKategoria`',
            '`blogKategoria`',
            3,
            255,
            -1,
            false,
            '`blogKategoria`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->blogKategoria->InputTextType = "text";
        $this->blogKategoria->Nullable = false; // NOT NULL field
        $this->blogKategoria->Required = true; // Required field
        $this->blogKategoria->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->blogKategoria->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->blogKategoria->Lookup = new Lookup('blogKategoria', 'blog_kategori', false, 'blogKatID', ["blogKatEmertimi","","",""], ["x_blogGjuha"], [], ["blogKatGjuha"], ["x_blogKatGjuha"], [], [], '', '', "`blogKatEmertimi`");
        $this->blogKategoria->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['blogKategoria'] = &$this->blogKategoria;

        // blogTitulli
        $this->blogTitulli = new DbField(
            'blog',
            'blog',
            'x_blogTitulli',
            'blogTitulli',
            '`blogTitulli`',
            '`blogTitulli`',
            200,
            255,
            -1,
            false,
            '`blogTitulli`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->blogTitulli->InputTextType = "text";
        $this->blogTitulli->Nullable = false; // NOT NULL field
        $this->blogTitulli->Required = true; // Required field
        $this->Fields['blogTitulli'] = &$this->blogTitulli;

        // blogTxt
        $this->blogTxt = new DbField(
            'blog',
            'blog',
            'x_blogTxt',
            'blogTxt',
            '`blogTxt`',
            '`blogTxt`',
            201,
            65535,
            -1,
            false,
            '`blogTxt`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->blogTxt->InputTextType = "text";
        $this->blogTxt->Nullable = false; // NOT NULL field
        $this->blogTxt->Required = true; // Required field
        $this->Fields['blogTxt'] = &$this->blogTxt;

        // blogFoto
        $this->blogFoto = new DbField(
            'blog',
            'blog',
            'x_blogFoto',
            'blogFoto',
            '`blogFoto`',
            '`blogFoto`',
            201,
            65535,
            -1,
            true,
            '`blogFoto`',
            false,
            false,
            false,
            'IMAGE',
            'FILE'
        );
        $this->blogFoto->InputTextType = "text";
        $this->blogFoto->Nullable = false; // NOT NULL field
        $this->blogFoto->Required = true; // Required field
        $this->blogFoto->UploadAllowedFileExt = "jpg,jpeg,png,gif,webp";
        $this->blogFoto->UploadMultiple = true;
        $this->blogFoto->Upload->UploadMultiple = true;
        $this->blogFoto->UploadMaxFileCount = 0;
        $this->blogFoto->UploadPath = '../ngarkime/blog/';
        $this->Fields['blogFoto'] = &$this->blogFoto;

        // blogVideo
        $this->blogVideo = new DbField(
            'blog',
            'blog',
            'x_blogVideo',
            'blogVideo',
            '`blogVideo`',
            '`blogVideo`',
            201,
            65535,
            -1,
            false,
            '`blogVideo`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->blogVideo->InputTextType = "text";
        $this->blogVideo->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->blogVideo->Param, "CustomMsg");
        $this->Fields['blogVideo'] = &$this->blogVideo;

        // blogDtPublik
        $this->blogDtPublik = new DbField(
            'blog',
            'blog',
            'x_blogDtPublik',
            'blogDtPublik',
            '`blogDtPublik`',
            CastDateFieldForLike("`blogDtPublik`", 7, "DB"),
            133,
            10,
            7,
            false,
            '`blogDtPublik`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->blogDtPublik->InputTextType = "text";
        $this->blogDtPublik->Nullable = false; // NOT NULL field
        $this->blogDtPublik->Required = true; // Required field
        $this->blogDtPublik->DefaultErrorMessage = str_replace("%s", DateFormat(7), $Language->phrase("IncorrectDate"));
        $this->Fields['blogDtPublik'] = &$this->blogDtPublik;

        // blogAutori
        $this->blogAutori = new DbField(
            'blog',
            'blog',
            'x_blogAutori',
            'blogAutori',
            '`blogAutori`',
            '`blogAutori`',
            3,
            255,
            -1,
            false,
            '`blogAutori`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->blogAutori->InputTextType = "text";
        $this->blogAutori->Nullable = false; // NOT NULL field
        $this->blogAutori->Lookup = new Lookup('blogAutori', 'perdoruesit', false, 'perdID', ["perdEmri","","",""], [], [], [], [], [], [], '', '', "`perdEmri`");
        $this->blogAutori->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['blogAutori'] = &$this->blogAutori;

        // blogShtuar
        $this->blogShtuar = new DbField(
            'blog',
            'blog',
            'x_blogShtuar',
            'blogShtuar',
            '`blogShtuar`',
            CastDateFieldForLike("`blogShtuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`blogShtuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->blogShtuar->InputTextType = "text";
        $this->blogShtuar->Nullable = false; // NOT NULL field
        $this->blogShtuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['blogShtuar'] = &$this->blogShtuar;

        // blogModifikuar
        $this->blogModifikuar = new DbField(
            'blog',
            'blog',
            'x_blogModifikuar',
            'blogModifikuar',
            '`blogModifikuar`',
            CastDateFieldForLike("`blogModifikuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`blogModifikuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->blogModifikuar->InputTextType = "text";
        $this->blogModifikuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['blogModifikuar'] = &$this->blogModifikuar;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`blog`";
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
            $this->blogID->setDbValue($conn->lastInsertId());
            $rs['blogID'] = $this->blogID->DbValue;
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
            if (array_key_exists('blogID', $rs)) {
                AddFilter($where, QuotedName('blogID', $this->Dbid) . '=' . QuotedValue($rs['blogID'], $this->blogID->DataType, $this->Dbid));
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
        $this->blogID->DbValue = $row['blogID'];
        $this->blogGjuha->DbValue = $row['blogGjuha'];
        $this->blogKategoria->DbValue = $row['blogKategoria'];
        $this->blogTitulli->DbValue = $row['blogTitulli'];
        $this->blogTxt->DbValue = $row['blogTxt'];
        $this->blogFoto->Upload->DbValue = $row['blogFoto'];
        $this->blogVideo->DbValue = $row['blogVideo'];
        $this->blogDtPublik->DbValue = $row['blogDtPublik'];
        $this->blogAutori->DbValue = $row['blogAutori'];
        $this->blogShtuar->DbValue = $row['blogShtuar'];
        $this->blogModifikuar->DbValue = $row['blogModifikuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $this->blogFoto->OldUploadPath = '../ngarkime/blog/';
        $oldFiles = EmptyValue($row['blogFoto']) ? [] : explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $row['blogFoto']);
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->blogFoto->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->blogFoto->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`blogID` = @blogID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->blogID->CurrentValue : $this->blogID->OldValue;
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
                $this->blogID->CurrentValue = $keys[0];
            } else {
                $this->blogID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('blogID', $row) ? $row['blogID'] : null;
        } else {
            $val = $this->blogID->OldValue !== null ? $this->blogID->OldValue : $this->blogID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@blogID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("BlogList");
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
        if ($pageName == "BlogView") {
            return $Language->phrase("View");
        } elseif ($pageName == "BlogEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "BlogAdd") {
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
                return "BlogView";
            case Config("API_ADD_ACTION"):
                return "BlogAdd";
            case Config("API_EDIT_ACTION"):
                return "BlogEdit";
            case Config("API_DELETE_ACTION"):
                return "BlogDelete";
            case Config("API_LIST_ACTION"):
                return "BlogList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "BlogList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("BlogView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("BlogView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "BlogAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "BlogAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("BlogEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("BlogAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("BlogDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"blogID\":" . JsonEncode($this->blogID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->blogID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->blogID->CurrentValue);
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
            if (($keyValue = Param("blogID") ?? Route("blogID")) !== null) {
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
                $this->blogID->CurrentValue = $key;
            } else {
                $this->blogID->OldValue = $key;
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
        $this->blogID->setDbValue($row['blogID']);
        $this->blogGjuha->setDbValue($row['blogGjuha']);
        $this->blogKategoria->setDbValue($row['blogKategoria']);
        $this->blogTitulli->setDbValue($row['blogTitulli']);
        $this->blogTxt->setDbValue($row['blogTxt']);
        $this->blogFoto->Upload->DbValue = $row['blogFoto'];
        $this->blogVideo->setDbValue($row['blogVideo']);
        $this->blogDtPublik->setDbValue($row['blogDtPublik']);
        $this->blogAutori->setDbValue($row['blogAutori']);
        $this->blogShtuar->setDbValue($row['blogShtuar']);
        $this->blogModifikuar->setDbValue($row['blogModifikuar']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // blogID

        // blogGjuha

        // blogKategoria

        // blogTitulli

        // blogTxt

        // blogFoto

        // blogVideo

        // blogDtPublik

        // blogAutori

        // blogShtuar

        // blogModifikuar

        // blogID
        $this->blogID->ViewValue = $this->blogID->CurrentValue;
        $this->blogID->ViewCustomAttributes = "";

        // blogGjuha
        if (strval($this->blogGjuha->CurrentValue) != "") {
            $this->blogGjuha->ViewValue = $this->blogGjuha->optionCaption($this->blogGjuha->CurrentValue);
        } else {
            $this->blogGjuha->ViewValue = null;
        }
        $this->blogGjuha->ViewCustomAttributes = "";

        // blogKategoria
        $curVal = strval($this->blogKategoria->CurrentValue);
        if ($curVal != "") {
            $this->blogKategoria->ViewValue = $this->blogKategoria->lookupCacheOption($curVal);
            if ($this->blogKategoria->ViewValue === null) { // Lookup from database
                $filterWrk = "`blogKatID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->blogKategoria->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->blogKategoria->Lookup->renderViewRow($rswrk[0]);
                    $this->blogKategoria->ViewValue = $this->blogKategoria->displayValue($arwrk);
                } else {
                    $this->blogKategoria->ViewValue = FormatNumber($this->blogKategoria->CurrentValue, $this->blogKategoria->formatPattern());
                }
            }
        } else {
            $this->blogKategoria->ViewValue = null;
        }
        $this->blogKategoria->ViewCustomAttributes = "";

        // blogTitulli
        $this->blogTitulli->ViewValue = $this->blogTitulli->CurrentValue;
        $this->blogTitulli->ViewCustomAttributes = "";

        // blogTxt
        $this->blogTxt->ViewValue = $this->blogTxt->CurrentValue;
        $this->blogTxt->ViewCustomAttributes = "";

        // blogFoto
        $this->blogFoto->UploadPath = '../ngarkime/blog/';
        if (!EmptyValue($this->blogFoto->Upload->DbValue)) {
            $this->blogFoto->ImageWidth = 100;
            $this->blogFoto->ImageHeight = 0;
            $this->blogFoto->ImageAlt = $this->blogFoto->alt();
            $this->blogFoto->ImageCssClass = "ew-image";
            $this->blogFoto->ViewValue = $this->blogFoto->Upload->DbValue;
        } else {
            $this->blogFoto->ViewValue = "";
        }
        $this->blogFoto->ViewCustomAttributes = "";

        // blogVideo
        $this->blogVideo->ViewValue = $this->blogVideo->CurrentValue;
        $this->blogVideo->ViewCustomAttributes = "";

        // blogDtPublik
        $this->blogDtPublik->ViewValue = $this->blogDtPublik->CurrentValue;
        $this->blogDtPublik->ViewValue = FormatDateTime($this->blogDtPublik->ViewValue, $this->blogDtPublik->formatPattern());
        $this->blogDtPublik->ViewCustomAttributes = "";

        // blogAutori
        $this->blogAutori->ViewValue = $this->blogAutori->CurrentValue;
        $curVal = strval($this->blogAutori->CurrentValue);
        if ($curVal != "") {
            $this->blogAutori->ViewValue = $this->blogAutori->lookupCacheOption($curVal);
            if ($this->blogAutori->ViewValue === null) { // Lookup from database
                $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->blogAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->blogAutori->Lookup->renderViewRow($rswrk[0]);
                    $this->blogAutori->ViewValue = $this->blogAutori->displayValue($arwrk);
                } else {
                    $this->blogAutori->ViewValue = FormatNumber($this->blogAutori->CurrentValue, $this->blogAutori->formatPattern());
                }
            }
        } else {
            $this->blogAutori->ViewValue = null;
        }
        $this->blogAutori->ViewCustomAttributes = "";

        // blogShtuar
        $this->blogShtuar->ViewValue = $this->blogShtuar->CurrentValue;
        $this->blogShtuar->ViewValue = FormatDateTime($this->blogShtuar->ViewValue, $this->blogShtuar->formatPattern());
        $this->blogShtuar->ViewCustomAttributes = "";

        // blogModifikuar
        $this->blogModifikuar->ViewValue = $this->blogModifikuar->CurrentValue;
        $this->blogModifikuar->ViewValue = FormatDateTime($this->blogModifikuar->ViewValue, $this->blogModifikuar->formatPattern());
        $this->blogModifikuar->ViewCustomAttributes = "";

        // blogID
        $this->blogID->LinkCustomAttributes = "";
        $this->blogID->HrefValue = "";
        $this->blogID->TooltipValue = "";

        // blogGjuha
        $this->blogGjuha->LinkCustomAttributes = "";
        $this->blogGjuha->HrefValue = "";
        $this->blogGjuha->TooltipValue = "";

        // blogKategoria
        $this->blogKategoria->LinkCustomAttributes = "";
        $this->blogKategoria->HrefValue = "";
        $this->blogKategoria->TooltipValue = "";

        // blogTitulli
        $this->blogTitulli->LinkCustomAttributes = "";
        $this->blogTitulli->HrefValue = "";
        $this->blogTitulli->TooltipValue = "";

        // blogTxt
        $this->blogTxt->LinkCustomAttributes = "";
        $this->blogTxt->HrefValue = "";
        $this->blogTxt->TooltipValue = "";

        // blogFoto
        $this->blogFoto->LinkCustomAttributes = "";
        $this->blogFoto->UploadPath = '../ngarkime/blog/';
        if (!EmptyValue($this->blogFoto->Upload->DbValue)) {
            $this->blogFoto->HrefValue = "%u"; // Add prefix/suffix
            $this->blogFoto->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->blogFoto->HrefValue = FullUrl($this->blogFoto->HrefValue, "href");
            }
        } else {
            $this->blogFoto->HrefValue = "";
        }
        $this->blogFoto->ExportHrefValue = $this->blogFoto->UploadPath . $this->blogFoto->Upload->DbValue;
        $this->blogFoto->TooltipValue = "";
        if ($this->blogFoto->UseColorbox) {
            if (EmptyValue($this->blogFoto->TooltipValue)) {
                $this->blogFoto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->blogFoto->LinkAttrs["data-rel"] = "blog_x_blogFoto";
            $this->blogFoto->LinkAttrs->appendClass("ew-lightbox");
        }

        // blogVideo
        $this->blogVideo->LinkCustomAttributes = "";
        $this->blogVideo->HrefValue = "";
        $this->blogVideo->TooltipValue = "";

        // blogDtPublik
        $this->blogDtPublik->LinkCustomAttributes = "";
        $this->blogDtPublik->HrefValue = "";
        $this->blogDtPublik->TooltipValue = "";

        // blogAutori
        $this->blogAutori->LinkCustomAttributes = "";
        $this->blogAutori->HrefValue = "";
        $this->blogAutori->TooltipValue = "";

        // blogShtuar
        $this->blogShtuar->LinkCustomAttributes = "";
        $this->blogShtuar->HrefValue = "";
        $this->blogShtuar->TooltipValue = "";

        // blogModifikuar
        $this->blogModifikuar->LinkCustomAttributes = "";
        $this->blogModifikuar->HrefValue = "";
        $this->blogModifikuar->TooltipValue = "";

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

        // blogID
        $this->blogID->setupEditAttributes();
        $this->blogID->EditCustomAttributes = "";
        $this->blogID->EditValue = $this->blogID->CurrentValue;
        $this->blogID->ViewCustomAttributes = "";

        // blogGjuha
        $this->blogGjuha->EditCustomAttributes = "";
        $this->blogGjuha->EditValue = $this->blogGjuha->options(false);
        $this->blogGjuha->PlaceHolder = RemoveHtml($this->blogGjuha->caption());

        // blogKategoria
        $this->blogKategoria->setupEditAttributes();
        $this->blogKategoria->EditCustomAttributes = "";
        $this->blogKategoria->PlaceHolder = RemoveHtml($this->blogKategoria->caption());

        // blogTitulli
        $this->blogTitulli->setupEditAttributes();
        $this->blogTitulli->EditCustomAttributes = "";
        if (!$this->blogTitulli->Raw) {
            $this->blogTitulli->CurrentValue = HtmlDecode($this->blogTitulli->CurrentValue);
        }
        $this->blogTitulli->EditValue = $this->blogTitulli->CurrentValue;
        $this->blogTitulli->PlaceHolder = RemoveHtml($this->blogTitulli->caption());

        // blogTxt
        $this->blogTxt->setupEditAttributes();
        $this->blogTxt->EditCustomAttributes = "";
        $this->blogTxt->EditValue = $this->blogTxt->CurrentValue;
        $this->blogTxt->PlaceHolder = RemoveHtml($this->blogTxt->caption());

        // blogFoto
        $this->blogFoto->setupEditAttributes();
        $this->blogFoto->EditCustomAttributes = "";
        $this->blogFoto->UploadPath = '../ngarkime/blog/';
        if (!EmptyValue($this->blogFoto->Upload->DbValue)) {
            $this->blogFoto->ImageWidth = 100;
            $this->blogFoto->ImageHeight = 0;
            $this->blogFoto->ImageAlt = $this->blogFoto->alt();
            $this->blogFoto->ImageCssClass = "ew-image";
            $this->blogFoto->EditValue = $this->blogFoto->Upload->DbValue;
        } else {
            $this->blogFoto->EditValue = "";
        }
        if (!EmptyValue($this->blogFoto->CurrentValue)) {
            $this->blogFoto->Upload->FileName = $this->blogFoto->CurrentValue;
        }

        // blogVideo
        $this->blogVideo->setupEditAttributes();
        $this->blogVideo->EditCustomAttributes = "";
        $this->blogVideo->EditValue = $this->blogVideo->CurrentValue;
        $this->blogVideo->PlaceHolder = RemoveHtml($this->blogVideo->caption());

        // blogDtPublik
        $this->blogDtPublik->setupEditAttributes();
        $this->blogDtPublik->EditCustomAttributes = "";
        $this->blogDtPublik->EditValue = FormatDateTime($this->blogDtPublik->CurrentValue, $this->blogDtPublik->formatPattern());
        $this->blogDtPublik->PlaceHolder = RemoveHtml($this->blogDtPublik->caption());

        // blogAutori

        // blogShtuar

        // blogModifikuar

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
                    $doc->exportCaption($this->blogID);
                    $doc->exportCaption($this->blogGjuha);
                    $doc->exportCaption($this->blogKategoria);
                    $doc->exportCaption($this->blogTitulli);
                    $doc->exportCaption($this->blogTxt);
                    $doc->exportCaption($this->blogFoto);
                    $doc->exportCaption($this->blogVideo);
                    $doc->exportCaption($this->blogDtPublik);
                    $doc->exportCaption($this->blogAutori);
                    $doc->exportCaption($this->blogShtuar);
                    $doc->exportCaption($this->blogModifikuar);
                } else {
                    $doc->exportCaption($this->blogID);
                    $doc->exportCaption($this->blogGjuha);
                    $doc->exportCaption($this->blogKategoria);
                    $doc->exportCaption($this->blogTitulli);
                    $doc->exportCaption($this->blogDtPublik);
                    $doc->exportCaption($this->blogAutori);
                    $doc->exportCaption($this->blogShtuar);
                    $doc->exportCaption($this->blogModifikuar);
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
                        $doc->exportField($this->blogID);
                        $doc->exportField($this->blogGjuha);
                        $doc->exportField($this->blogKategoria);
                        $doc->exportField($this->blogTitulli);
                        $doc->exportField($this->blogTxt);
                        $doc->exportField($this->blogFoto);
                        $doc->exportField($this->blogVideo);
                        $doc->exportField($this->blogDtPublik);
                        $doc->exportField($this->blogAutori);
                        $doc->exportField($this->blogShtuar);
                        $doc->exportField($this->blogModifikuar);
                    } else {
                        $doc->exportField($this->blogID);
                        $doc->exportField($this->blogGjuha);
                        $doc->exportField($this->blogKategoria);
                        $doc->exportField($this->blogTitulli);
                        $doc->exportField($this->blogDtPublik);
                        $doc->exportField($this->blogAutori);
                        $doc->exportField($this->blogShtuar);
                        $doc->exportField($this->blogModifikuar);
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
        if ($fldparm == 'blogFoto') {
            $fldName = "blogFoto";
            $fileNameFld = "blogFoto";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->blogID->CurrentValue = $ar[0];
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
