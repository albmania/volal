<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for slide
 */
class Slide extends DbTable
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
    public $slideID;
    public $slideGjuha;
    public $slideFoto;
    public $slideTxt1;
    public $slideTxt2;
    public $slideTxt3;
    public $slideButonTxt;
    public $slideLink;
    public $slideTarget;
    public $slideRadha;
    public $slideAktiv;
    public $slideAutori;
    public $slideKrijuar;
    public $slideAzhornuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'slide';
        $this->TableName = 'slide';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`slide`";
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

        // slideID
        $this->slideID = new DbField(
            'slide',
            'slide',
            'x_slideID',
            'slideID',
            '`slideID`',
            '`slideID`',
            3,
            11,
            -1,
            false,
            '`slideID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->slideID->InputTextType = "text";
        $this->slideID->IsAutoIncrement = true; // Autoincrement field
        $this->slideID->IsPrimaryKey = true; // Primary key field
        $this->slideID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['slideID'] = &$this->slideID;

        // slideGjuha
        $this->slideGjuha = new DbField(
            'slide',
            'slide',
            'x_slideGjuha',
            'slideGjuha',
            '`slideGjuha`',
            '`slideGjuha`',
            202,
            2,
            -1,
            false,
            '`slideGjuha`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->slideGjuha->InputTextType = "text";
        $this->slideGjuha->Nullable = false; // NOT NULL field
        $this->slideGjuha->Lookup = new Lookup('slideGjuha', 'slide', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->slideGjuha->OptionCount = 2;
        $this->Fields['slideGjuha'] = &$this->slideGjuha;

        // slideFoto
        $this->slideFoto = new DbField(
            'slide',
            'slide',
            'x_slideFoto',
            'slideFoto',
            '`slideFoto`',
            '`slideFoto`',
            200,
            255,
            -1,
            true,
            '`slideFoto`',
            false,
            false,
            false,
            'IMAGE',
            'FILE'
        );
        $this->slideFoto->InputTextType = "text";
        $this->slideFoto->Nullable = false; // NOT NULL field
        $this->slideFoto->Required = true; // Required field
        $this->slideFoto->UploadAllowedFileExt = "jpg,png,gif,webp";
        $this->slideFoto->UploadMaxFileSize = 2097152;
        $this->slideFoto->UploadPath = '../ngarkime/slide/';
        $this->slideFoto->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->slideFoto->Param, "CustomMsg");
        $this->Fields['slideFoto'] = &$this->slideFoto;

        // slideTxt1
        $this->slideTxt1 = new DbField(
            'slide',
            'slide',
            'x_slideTxt1',
            'slideTxt1',
            '`slideTxt1`',
            '`slideTxt1`',
            200,
            100,
            -1,
            false,
            '`slideTxt1`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideTxt1->InputTextType = "text";
        $this->slideTxt1->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->slideTxt1->Param, "CustomMsg");
        $this->Fields['slideTxt1'] = &$this->slideTxt1;

        // slideTxt2
        $this->slideTxt2 = new DbField(
            'slide',
            'slide',
            'x_slideTxt2',
            'slideTxt2',
            '`slideTxt2`',
            '`slideTxt2`',
            200,
            100,
            -1,
            false,
            '`slideTxt2`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideTxt2->InputTextType = "text";
        $this->slideTxt2->Nullable = false; // NOT NULL field
        $this->slideTxt2->Required = true; // Required field
        $this->slideTxt2->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->slideTxt2->Param, "CustomMsg");
        $this->Fields['slideTxt2'] = &$this->slideTxt2;

        // slideTxt3
        $this->slideTxt3 = new DbField(
            'slide',
            'slide',
            'x_slideTxt3',
            'slideTxt3',
            '`slideTxt3`',
            '`slideTxt3`',
            200,
            255,
            -1,
            false,
            '`slideTxt3`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideTxt3->InputTextType = "text";
        $this->slideTxt3->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->slideTxt3->Param, "CustomMsg");
        $this->Fields['slideTxt3'] = &$this->slideTxt3;

        // slideButonTxt
        $this->slideButonTxt = new DbField(
            'slide',
            'slide',
            'x_slideButonTxt',
            'slideButonTxt',
            '`slideButonTxt`',
            '`slideButonTxt`',
            200,
            50,
            -1,
            false,
            '`slideButonTxt`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideButonTxt->InputTextType = "text";
        $this->slideButonTxt->Nullable = false; // NOT NULL field
        $this->slideButonTxt->Required = true; // Required field
        $this->slideButonTxt->CustomMsg = $Language->FieldPhrase($this->TableVar, $this->slideButonTxt->Param, "CustomMsg");
        $this->Fields['slideButonTxt'] = &$this->slideButonTxt;

        // slideLink
        $this->slideLink = new DbField(
            'slide',
            'slide',
            'x_slideLink',
            'slideLink',
            '`slideLink`',
            '`slideLink`',
            200,
            255,
            -1,
            false,
            '`slideLink`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideLink->InputTextType = "text";
        $this->slideLink->Nullable = false; // NOT NULL field
        $this->slideLink->Required = true; // Required field
        $this->Fields['slideLink'] = &$this->slideLink;

        // slideTarget
        $this->slideTarget = new DbField(
            'slide',
            'slide',
            'x_slideTarget',
            'slideTarget',
            '`slideTarget`',
            '`slideTarget`',
            202,
            6,
            -1,
            false,
            '`slideTarget`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->slideTarget->InputTextType = "text";
        $this->slideTarget->Nullable = false; // NOT NULL field
        $this->slideTarget->Required = true; // Required field
        $this->slideTarget->Lookup = new Lookup('slideTarget', 'slide', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->slideTarget->OptionCount = 2;
        $this->Fields['slideTarget'] = &$this->slideTarget;

        // slideRadha
        $this->slideRadha = new DbField(
            'slide',
            'slide',
            'x_slideRadha',
            'slideRadha',
            '`slideRadha`',
            '`slideRadha`',
            3,
            10,
            -1,
            false,
            '`slideRadha`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideRadha->InputTextType = "text";
        $this->slideRadha->Nullable = false; // NOT NULL field
        $this->slideRadha->Required = true; // Required field
        $this->slideRadha->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['slideRadha'] = &$this->slideRadha;

        // slideAktiv
        $this->slideAktiv = new DbField(
            'slide',
            'slide',
            'x_slideAktiv',
            'slideAktiv',
            '`slideAktiv`',
            '`slideAktiv`',
            202,
            2,
            -1,
            false,
            '`slideAktiv`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->slideAktiv->InputTextType = "text";
        $this->slideAktiv->Nullable = false; // NOT NULL field
        $this->slideAktiv->Required = true; // Required field
        $this->slideAktiv->Lookup = new Lookup('slideAktiv', 'slide', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->slideAktiv->OptionCount = 2;
        $this->Fields['slideAktiv'] = &$this->slideAktiv;

        // slideAutori
        $this->slideAutori = new DbField(
            'slide',
            'slide',
            'x_slideAutori',
            'slideAutori',
            '`slideAutori`',
            '`slideAutori`',
            3,
            255,
            -1,
            false,
            '`slideAutori`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideAutori->InputTextType = "text";
        $this->slideAutori->Nullable = false; // NOT NULL field
        $this->slideAutori->Lookup = new Lookup('slideAutori', 'perdoruesit', false, 'perdID', ["perdEmri","","",""], [], [], [], [], [], [], '', '', "`perdEmri`");
        $this->slideAutori->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['slideAutori'] = &$this->slideAutori;

        // slideKrijuar
        $this->slideKrijuar = new DbField(
            'slide',
            'slide',
            'x_slideKrijuar',
            'slideKrijuar',
            '`slideKrijuar`',
            CastDateFieldForLike("`slideKrijuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`slideKrijuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideKrijuar->InputTextType = "text";
        $this->slideKrijuar->Nullable = false; // NOT NULL field
        $this->slideKrijuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['slideKrijuar'] = &$this->slideKrijuar;

        // slideAzhornuar
        $this->slideAzhornuar = new DbField(
            'slide',
            'slide',
            'x_slideAzhornuar',
            'slideAzhornuar',
            '`slideAzhornuar`',
            CastDateFieldForLike("`slideAzhornuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`slideAzhornuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->slideAzhornuar->InputTextType = "text";
        $this->slideAzhornuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['slideAzhornuar'] = &$this->slideAzhornuar;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`slide`";
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
            $this->slideID->setDbValue($conn->lastInsertId());
            $rs['slideID'] = $this->slideID->DbValue;
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
            if (array_key_exists('slideID', $rs)) {
                AddFilter($where, QuotedName('slideID', $this->Dbid) . '=' . QuotedValue($rs['slideID'], $this->slideID->DataType, $this->Dbid));
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
        $this->slideID->DbValue = $row['slideID'];
        $this->slideGjuha->DbValue = $row['slideGjuha'];
        $this->slideFoto->Upload->DbValue = $row['slideFoto'];
        $this->slideTxt1->DbValue = $row['slideTxt1'];
        $this->slideTxt2->DbValue = $row['slideTxt2'];
        $this->slideTxt3->DbValue = $row['slideTxt3'];
        $this->slideButonTxt->DbValue = $row['slideButonTxt'];
        $this->slideLink->DbValue = $row['slideLink'];
        $this->slideTarget->DbValue = $row['slideTarget'];
        $this->slideRadha->DbValue = $row['slideRadha'];
        $this->slideAktiv->DbValue = $row['slideAktiv'];
        $this->slideAutori->DbValue = $row['slideAutori'];
        $this->slideKrijuar->DbValue = $row['slideKrijuar'];
        $this->slideAzhornuar->DbValue = $row['slideAzhornuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
        $this->slideFoto->OldUploadPath = '../ngarkime/slide/';
        $oldFiles = EmptyValue($row['slideFoto']) ? [] : [$row['slideFoto']];
        foreach ($oldFiles as $oldFile) {
            if (file_exists($this->slideFoto->oldPhysicalUploadPath() . $oldFile)) {
                @unlink($this->slideFoto->oldPhysicalUploadPath() . $oldFile);
            }
        }
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`slideID` = @slideID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->slideID->CurrentValue : $this->slideID->OldValue;
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
                $this->slideID->CurrentValue = $keys[0];
            } else {
                $this->slideID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('slideID', $row) ? $row['slideID'] : null;
        } else {
            $val = $this->slideID->OldValue !== null ? $this->slideID->OldValue : $this->slideID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@slideID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("SlideList");
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
        if ($pageName == "SlideView") {
            return $Language->phrase("View");
        } elseif ($pageName == "SlideEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "SlideAdd") {
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
                return "SlideView";
            case Config("API_ADD_ACTION"):
                return "SlideAdd";
            case Config("API_EDIT_ACTION"):
                return "SlideEdit";
            case Config("API_DELETE_ACTION"):
                return "SlideDelete";
            case Config("API_LIST_ACTION"):
                return "SlideList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "SlideList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("SlideView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("SlideView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "SlideAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "SlideAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("SlideEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("SlideAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("SlideDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"slideID\":" . JsonEncode($this->slideID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->slideID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->slideID->CurrentValue);
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
            if (($keyValue = Param("slideID") ?? Route("slideID")) !== null) {
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
                $this->slideID->CurrentValue = $key;
            } else {
                $this->slideID->OldValue = $key;
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
        $this->slideID->setDbValue($row['slideID']);
        $this->slideGjuha->setDbValue($row['slideGjuha']);
        $this->slideFoto->Upload->DbValue = $row['slideFoto'];
        $this->slideTxt1->setDbValue($row['slideTxt1']);
        $this->slideTxt2->setDbValue($row['slideTxt2']);
        $this->slideTxt3->setDbValue($row['slideTxt3']);
        $this->slideButonTxt->setDbValue($row['slideButonTxt']);
        $this->slideLink->setDbValue($row['slideLink']);
        $this->slideTarget->setDbValue($row['slideTarget']);
        $this->slideRadha->setDbValue($row['slideRadha']);
        $this->slideAktiv->setDbValue($row['slideAktiv']);
        $this->slideAutori->setDbValue($row['slideAutori']);
        $this->slideKrijuar->setDbValue($row['slideKrijuar']);
        $this->slideAzhornuar->setDbValue($row['slideAzhornuar']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // slideID

        // slideGjuha

        // slideFoto

        // slideTxt1

        // slideTxt2

        // slideTxt3

        // slideButonTxt

        // slideLink

        // slideTarget

        // slideRadha

        // slideAktiv

        // slideAutori

        // slideKrijuar

        // slideAzhornuar

        // slideID
        $this->slideID->ViewValue = $this->slideID->CurrentValue;
        $this->slideID->ViewCustomAttributes = "";

        // slideGjuha
        if (strval($this->slideGjuha->CurrentValue) != "") {
            $this->slideGjuha->ViewValue = $this->slideGjuha->optionCaption($this->slideGjuha->CurrentValue);
        } else {
            $this->slideGjuha->ViewValue = null;
        }
        $this->slideGjuha->CssClass = "fw-bold";
        $this->slideGjuha->ViewCustomAttributes = "";

        // slideFoto
        $this->slideFoto->UploadPath = '../ngarkime/slide/';
        if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
            $this->slideFoto->ImageWidth = 100;
            $this->slideFoto->ImageHeight = 0;
            $this->slideFoto->ImageAlt = $this->slideFoto->alt();
            $this->slideFoto->ImageCssClass = "ew-image";
            $this->slideFoto->ViewValue = $this->slideFoto->Upload->DbValue;
        } else {
            $this->slideFoto->ViewValue = "";
        }
        $this->slideFoto->ViewCustomAttributes = "";

        // slideTxt1
        $this->slideTxt1->ViewValue = $this->slideTxt1->CurrentValue;
        $this->slideTxt1->ViewCustomAttributes = "";

        // slideTxt2
        $this->slideTxt2->ViewValue = $this->slideTxt2->CurrentValue;
        $this->slideTxt2->ViewCustomAttributes = "";

        // slideTxt3
        $this->slideTxt3->ViewValue = $this->slideTxt3->CurrentValue;
        $this->slideTxt3->ViewCustomAttributes = "";

        // slideButonTxt
        $this->slideButonTxt->ViewValue = $this->slideButonTxt->CurrentValue;
        $this->slideButonTxt->ViewCustomAttributes = "";

        // slideLink
        $this->slideLink->ViewValue = $this->slideLink->CurrentValue;
        $this->slideLink->ViewCustomAttributes = "";

        // slideTarget
        if (strval($this->slideTarget->CurrentValue) != "") {
            $this->slideTarget->ViewValue = $this->slideTarget->optionCaption($this->slideTarget->CurrentValue);
        } else {
            $this->slideTarget->ViewValue = null;
        }
        $this->slideTarget->ViewCustomAttributes = "";

        // slideRadha
        $this->slideRadha->ViewValue = $this->slideRadha->CurrentValue;
        $this->slideRadha->ViewValue = FormatNumber($this->slideRadha->ViewValue, $this->slideRadha->formatPattern());
        $this->slideRadha->ViewCustomAttributes = "";

        // slideAktiv
        if (strval($this->slideAktiv->CurrentValue) != "") {
            $this->slideAktiv->ViewValue = $this->slideAktiv->optionCaption($this->slideAktiv->CurrentValue);
        } else {
            $this->slideAktiv->ViewValue = null;
        }
        $this->slideAktiv->ViewCustomAttributes = "";

        // slideAutori
        $this->slideAutori->ViewValue = $this->slideAutori->CurrentValue;
        $curVal = strval($this->slideAutori->CurrentValue);
        if ($curVal != "") {
            $this->slideAutori->ViewValue = $this->slideAutori->lookupCacheOption($curVal);
            if ($this->slideAutori->ViewValue === null) { // Lookup from database
                $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->slideAutori->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->slideAutori->Lookup->renderViewRow($rswrk[0]);
                    $this->slideAutori->ViewValue = $this->slideAutori->displayValue($arwrk);
                } else {
                    $this->slideAutori->ViewValue = FormatNumber($this->slideAutori->CurrentValue, $this->slideAutori->formatPattern());
                }
            }
        } else {
            $this->slideAutori->ViewValue = null;
        }
        $this->slideAutori->ViewCustomAttributes = "";

        // slideKrijuar
        $this->slideKrijuar->ViewValue = $this->slideKrijuar->CurrentValue;
        $this->slideKrijuar->ViewValue = FormatDateTime($this->slideKrijuar->ViewValue, $this->slideKrijuar->formatPattern());
        $this->slideKrijuar->ViewCustomAttributes = "";

        // slideAzhornuar
        $this->slideAzhornuar->ViewValue = $this->slideAzhornuar->CurrentValue;
        $this->slideAzhornuar->ViewValue = FormatDateTime($this->slideAzhornuar->ViewValue, $this->slideAzhornuar->formatPattern());
        $this->slideAzhornuar->ViewCustomAttributes = "";

        // slideID
        $this->slideID->LinkCustomAttributes = "";
        $this->slideID->HrefValue = "";
        $this->slideID->TooltipValue = "";

        // slideGjuha
        $this->slideGjuha->LinkCustomAttributes = "";
        $this->slideGjuha->HrefValue = "";
        $this->slideGjuha->TooltipValue = "";

        // slideFoto
        $this->slideFoto->LinkCustomAttributes = "";
        $this->slideFoto->UploadPath = '../ngarkime/slide/';
        if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
            $this->slideFoto->HrefValue = GetFileUploadUrl($this->slideFoto, $this->slideFoto->htmlDecode($this->slideFoto->Upload->DbValue)); // Add prefix/suffix
            $this->slideFoto->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->slideFoto->HrefValue = FullUrl($this->slideFoto->HrefValue, "href");
            }
        } else {
            $this->slideFoto->HrefValue = "";
        }
        $this->slideFoto->ExportHrefValue = $this->slideFoto->UploadPath . $this->slideFoto->Upload->DbValue;
        $this->slideFoto->TooltipValue = "";
        if ($this->slideFoto->UseColorbox) {
            if (EmptyValue($this->slideFoto->TooltipValue)) {
                $this->slideFoto->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->slideFoto->LinkAttrs["data-rel"] = "slide_x_slideFoto";
            $this->slideFoto->LinkAttrs->appendClass("ew-lightbox");
        }

        // slideTxt1
        $this->slideTxt1->LinkCustomAttributes = "";
        $this->slideTxt1->HrefValue = "";
        $this->slideTxt1->TooltipValue = "";

        // slideTxt2
        $this->slideTxt2->LinkCustomAttributes = "";
        $this->slideTxt2->HrefValue = "";
        $this->slideTxt2->TooltipValue = "";

        // slideTxt3
        $this->slideTxt3->LinkCustomAttributes = "";
        $this->slideTxt3->HrefValue = "";
        $this->slideTxt3->TooltipValue = "";

        // slideButonTxt
        $this->slideButonTxt->LinkCustomAttributes = "";
        if (!EmptyValue($this->slideLink->CurrentValue)) {
            $this->slideButonTxt->HrefValue = (!empty($this->slideLink->ViewValue) && !is_array($this->slideLink->ViewValue) ? RemoveHtml($this->slideLink->ViewValue) : $this->slideLink->CurrentValue); // Add prefix/suffix
            $this->slideButonTxt->LinkAttrs["target"] = "_blank"; // Add target
            if ($this->isExport()) {
                $this->slideButonTxt->HrefValue = FullUrl($this->slideButonTxt->HrefValue, "href");
            }
        } else {
            $this->slideButonTxt->HrefValue = "";
        }
        $this->slideButonTxt->TooltipValue = "";

        // slideLink
        $this->slideLink->LinkCustomAttributes = "";
        $this->slideLink->HrefValue = "";
        $this->slideLink->TooltipValue = "";

        // slideTarget
        $this->slideTarget->LinkCustomAttributes = "";
        $this->slideTarget->HrefValue = "";
        $this->slideTarget->TooltipValue = "";

        // slideRadha
        $this->slideRadha->LinkCustomAttributes = "";
        $this->slideRadha->HrefValue = "";
        $this->slideRadha->TooltipValue = "";

        // slideAktiv
        $this->slideAktiv->LinkCustomAttributes = "";
        $this->slideAktiv->HrefValue = "";
        $this->slideAktiv->TooltipValue = "";

        // slideAutori
        $this->slideAutori->LinkCustomAttributes = "";
        $this->slideAutori->HrefValue = "";
        $this->slideAutori->TooltipValue = "";

        // slideKrijuar
        $this->slideKrijuar->LinkCustomAttributes = "";
        $this->slideKrijuar->HrefValue = "";
        $this->slideKrijuar->TooltipValue = "";

        // slideAzhornuar
        $this->slideAzhornuar->LinkCustomAttributes = "";
        $this->slideAzhornuar->HrefValue = "";
        $this->slideAzhornuar->TooltipValue = "";

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

        // slideID
        $this->slideID->setupEditAttributes();
        $this->slideID->EditCustomAttributes = "";
        $this->slideID->EditValue = $this->slideID->CurrentValue;
        $this->slideID->ViewCustomAttributes = "";

        // slideGjuha
        $this->slideGjuha->EditCustomAttributes = "";
        $this->slideGjuha->EditValue = $this->slideGjuha->options(false);
        $this->slideGjuha->PlaceHolder = RemoveHtml($this->slideGjuha->caption());

        // slideFoto
        $this->slideFoto->setupEditAttributes();
        $this->slideFoto->EditCustomAttributes = "";
        $this->slideFoto->UploadPath = '../ngarkime/slide/';
        if (!EmptyValue($this->slideFoto->Upload->DbValue)) {
            $this->slideFoto->ImageWidth = 100;
            $this->slideFoto->ImageHeight = 0;
            $this->slideFoto->ImageAlt = $this->slideFoto->alt();
            $this->slideFoto->ImageCssClass = "ew-image";
            $this->slideFoto->EditValue = $this->slideFoto->Upload->DbValue;
        } else {
            $this->slideFoto->EditValue = "";
        }
        if (!EmptyValue($this->slideFoto->CurrentValue)) {
            $this->slideFoto->Upload->FileName = $this->slideFoto->CurrentValue;
        }

        // slideTxt1
        $this->slideTxt1->setupEditAttributes();
        $this->slideTxt1->EditCustomAttributes = "";
        if (!$this->slideTxt1->Raw) {
            $this->slideTxt1->CurrentValue = HtmlDecode($this->slideTxt1->CurrentValue);
        }
        $this->slideTxt1->EditValue = $this->slideTxt1->CurrentValue;
        $this->slideTxt1->PlaceHolder = RemoveHtml($this->slideTxt1->caption());

        // slideTxt2
        $this->slideTxt2->setupEditAttributes();
        $this->slideTxt2->EditCustomAttributes = "";
        if (!$this->slideTxt2->Raw) {
            $this->slideTxt2->CurrentValue = HtmlDecode($this->slideTxt2->CurrentValue);
        }
        $this->slideTxt2->EditValue = $this->slideTxt2->CurrentValue;
        $this->slideTxt2->PlaceHolder = RemoveHtml($this->slideTxt2->caption());

        // slideTxt3
        $this->slideTxt3->setupEditAttributes();
        $this->slideTxt3->EditCustomAttributes = "";
        if (!$this->slideTxt3->Raw) {
            $this->slideTxt3->CurrentValue = HtmlDecode($this->slideTxt3->CurrentValue);
        }
        $this->slideTxt3->EditValue = $this->slideTxt3->CurrentValue;
        $this->slideTxt3->PlaceHolder = RemoveHtml($this->slideTxt3->caption());

        // slideButonTxt
        $this->slideButonTxt->setupEditAttributes();
        $this->slideButonTxt->EditCustomAttributes = "";
        if (!$this->slideButonTxt->Raw) {
            $this->slideButonTxt->CurrentValue = HtmlDecode($this->slideButonTxt->CurrentValue);
        }
        $this->slideButonTxt->EditValue = $this->slideButonTxt->CurrentValue;
        $this->slideButonTxt->PlaceHolder = RemoveHtml($this->slideButonTxt->caption());

        // slideLink
        $this->slideLink->setupEditAttributes();
        $this->slideLink->EditCustomAttributes = "";
        if (!$this->slideLink->Raw) {
            $this->slideLink->CurrentValue = HtmlDecode($this->slideLink->CurrentValue);
        }
        $this->slideLink->EditValue = $this->slideLink->CurrentValue;
        $this->slideLink->PlaceHolder = RemoveHtml($this->slideLink->caption());

        // slideTarget
        $this->slideTarget->EditCustomAttributes = "";
        $this->slideTarget->EditValue = $this->slideTarget->options(false);
        $this->slideTarget->PlaceHolder = RemoveHtml($this->slideTarget->caption());

        // slideRadha
        $this->slideRadha->setupEditAttributes();
        $this->slideRadha->EditCustomAttributes = "";
        $this->slideRadha->EditValue = $this->slideRadha->CurrentValue;
        $this->slideRadha->PlaceHolder = RemoveHtml($this->slideRadha->caption());
        if (strval($this->slideRadha->EditValue) != "" && is_numeric($this->slideRadha->EditValue)) {
            $this->slideRadha->EditValue = FormatNumber($this->slideRadha->EditValue, null);
        }

        // slideAktiv
        $this->slideAktiv->EditCustomAttributes = "";
        $this->slideAktiv->EditValue = $this->slideAktiv->options(false);
        $this->slideAktiv->PlaceHolder = RemoveHtml($this->slideAktiv->caption());

        // slideAutori

        // slideKrijuar

        // slideAzhornuar

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
                    $doc->exportCaption($this->slideID);
                    $doc->exportCaption($this->slideGjuha);
                    $doc->exportCaption($this->slideFoto);
                    $doc->exportCaption($this->slideTxt1);
                    $doc->exportCaption($this->slideTxt2);
                    $doc->exportCaption($this->slideTxt3);
                    $doc->exportCaption($this->slideButonTxt);
                    $doc->exportCaption($this->slideLink);
                    $doc->exportCaption($this->slideTarget);
                    $doc->exportCaption($this->slideRadha);
                    $doc->exportCaption($this->slideAktiv);
                    $doc->exportCaption($this->slideAutori);
                    $doc->exportCaption($this->slideKrijuar);
                    $doc->exportCaption($this->slideAzhornuar);
                } else {
                    $doc->exportCaption($this->slideID);
                    $doc->exportCaption($this->slideGjuha);
                    $doc->exportCaption($this->slideFoto);
                    $doc->exportCaption($this->slideTxt1);
                    $doc->exportCaption($this->slideTxt2);
                    $doc->exportCaption($this->slideTxt3);
                    $doc->exportCaption($this->slideButonTxt);
                    $doc->exportCaption($this->slideLink);
                    $doc->exportCaption($this->slideTarget);
                    $doc->exportCaption($this->slideRadha);
                    $doc->exportCaption($this->slideAktiv);
                    $doc->exportCaption($this->slideAutori);
                    $doc->exportCaption($this->slideKrijuar);
                    $doc->exportCaption($this->slideAzhornuar);
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
                        $doc->exportField($this->slideID);
                        $doc->exportField($this->slideGjuha);
                        $doc->exportField($this->slideFoto);
                        $doc->exportField($this->slideTxt1);
                        $doc->exportField($this->slideTxt2);
                        $doc->exportField($this->slideTxt3);
                        $doc->exportField($this->slideButonTxt);
                        $doc->exportField($this->slideLink);
                        $doc->exportField($this->slideTarget);
                        $doc->exportField($this->slideRadha);
                        $doc->exportField($this->slideAktiv);
                        $doc->exportField($this->slideAutori);
                        $doc->exportField($this->slideKrijuar);
                        $doc->exportField($this->slideAzhornuar);
                    } else {
                        $doc->exportField($this->slideID);
                        $doc->exportField($this->slideGjuha);
                        $doc->exportField($this->slideFoto);
                        $doc->exportField($this->slideTxt1);
                        $doc->exportField($this->slideTxt2);
                        $doc->exportField($this->slideTxt3);
                        $doc->exportField($this->slideButonTxt);
                        $doc->exportField($this->slideLink);
                        $doc->exportField($this->slideTarget);
                        $doc->exportField($this->slideRadha);
                        $doc->exportField($this->slideAktiv);
                        $doc->exportField($this->slideAutori);
                        $doc->exportField($this->slideKrijuar);
                        $doc->exportField($this->slideAzhornuar);
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
        if ($fldparm == 'slideFoto') {
            $fldName = "slideFoto";
            $fileNameFld = "slideFoto";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->slideID->CurrentValue = $ar[0];
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
