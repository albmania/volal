<?php

namespace PHPMaker2022\volalservice;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for menu_dytesore
 */
class MenuDytesore extends DbTable
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
    public $menudID;
    public $menudGjuha;
    public $menudKryesore;
    public $menudTitulli;
    public $menudUrl;
    public $menudBlank;
    public $menudRadhe;
    public $menudAktiv;
    public $menudAutor;
    public $menudKrijuar;
    public $menudAzhornuar;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'menu_dytesore';
        $this->TableName = 'menu_dytesore';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`menu_dytesore`";
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

        // menudID
        $this->menudID = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudID',
            'menudID',
            '`menudID`',
            '`menudID`',
            3,
            255,
            -1,
            false,
            '`menudID`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->menudID->InputTextType = "text";
        $this->menudID->IsAutoIncrement = true; // Autoincrement field
        $this->menudID->IsPrimaryKey = true; // Primary key field
        $this->menudID->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['menudID'] = &$this->menudID;

        // menudGjuha
        $this->menudGjuha = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudGjuha',
            'menudGjuha',
            '`menudGjuha`',
            '`menudGjuha`',
            202,
            2,
            -1,
            false,
            '`menudGjuha`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->menudGjuha->InputTextType = "text";
        $this->menudGjuha->Nullable = false; // NOT NULL field
        $this->menudGjuha->Required = true; // Required field
        $this->menudGjuha->Lookup = new Lookup('menudGjuha', 'menu_dytesore', false, '', ["","","",""], [], ["x_menudKryesore"], [], [], [], [], '', '', "");
        $this->menudGjuha->OptionCount = 2;
        $this->Fields['menudGjuha'] = &$this->menudGjuha;

        // menudKryesore
        $this->menudKryesore = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudKryesore',
            'menudKryesore',
            '`menudKryesore`',
            '`menudKryesore`',
            3,
            255,
            -1,
            false,
            '`EV__menudKryesore`',
            true,
            true,
            true,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->menudKryesore->InputTextType = "text";
        $this->menudKryesore->Nullable = false; // NOT NULL field
        $this->menudKryesore->Required = true; // Required field
        $this->menudKryesore->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->menudKryesore->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        $this->menudKryesore->Lookup = new Lookup('menudKryesore', 'menu_kryesore', true, 'menukID', ["menukTitull","","",""], ["x_menudGjuha"], [], ["menukGjuha"], ["x_menukGjuha"], [], [], '', '', "`menukTitull`");
        $this->menudKryesore->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['menudKryesore'] = &$this->menudKryesore;

        // menudTitulli
        $this->menudTitulli = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudTitulli',
            'menudTitulli',
            '`menudTitulli`',
            '`menudTitulli`',
            200,
            255,
            -1,
            false,
            '`menudTitulli`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menudTitulli->InputTextType = "text";
        $this->menudTitulli->Nullable = false; // NOT NULL field
        $this->menudTitulli->Required = true; // Required field
        $this->Fields['menudTitulli'] = &$this->menudTitulli;

        // menudUrl
        $this->menudUrl = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudUrl',
            'menudUrl',
            '`menudUrl`',
            '`menudUrl`',
            200,
            255,
            -1,
            false,
            '`menudUrl`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menudUrl->InputTextType = "text";
        $this->menudUrl->Nullable = false; // NOT NULL field
        $this->menudUrl->Required = true; // Required field
        $this->Fields['menudUrl'] = &$this->menudUrl;

        // menudBlank
        $this->menudBlank = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudBlank',
            'menudBlank',
            '`menudBlank`',
            '`menudBlank`',
            202,
            6,
            -1,
            false,
            '`menudBlank`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->menudBlank->InputTextType = "text";
        $this->menudBlank->Nullable = false; // NOT NULL field
        $this->menudBlank->Required = true; // Required field
        $this->menudBlank->Lookup = new Lookup('menudBlank', 'menu_dytesore', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->menudBlank->OptionCount = 2;
        $this->Fields['menudBlank'] = &$this->menudBlank;

        // menudRadhe
        $this->menudRadhe = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudRadhe',
            'menudRadhe',
            '`menudRadhe`',
            '`menudRadhe`',
            3,
            10,
            -1,
            false,
            '`menudRadhe`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menudRadhe->InputTextType = "text";
        $this->menudRadhe->Nullable = false; // NOT NULL field
        $this->menudRadhe->Required = true; // Required field
        $this->menudRadhe->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['menudRadhe'] = &$this->menudRadhe;

        // menudAktiv
        $this->menudAktiv = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudAktiv',
            'menudAktiv',
            '`menudAktiv`',
            '`menudAktiv`',
            202,
            2,
            -1,
            false,
            '`menudAktiv`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'RADIO'
        );
        $this->menudAktiv->InputTextType = "text";
        $this->menudAktiv->Nullable = false; // NOT NULL field
        $this->menudAktiv->Required = true; // Required field
        $this->menudAktiv->Lookup = new Lookup('menudAktiv', 'menu_dytesore', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
        $this->menudAktiv->OptionCount = 2;
        $this->Fields['menudAktiv'] = &$this->menudAktiv;

        // menudAutor
        $this->menudAutor = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudAutor',
            'menudAutor',
            '`menudAutor`',
            '`menudAutor`',
            3,
            255,
            -1,
            false,
            '`menudAutor`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menudAutor->InputTextType = "text";
        $this->menudAutor->Nullable = false; // NOT NULL field
        $this->menudAutor->Lookup = new Lookup('menudAutor', 'perdoruesit', false, 'perdID', ["perdEmri","","",""], [], [], [], [], [], [], '', '', "`perdEmri`");
        $this->menudAutor->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['menudAutor'] = &$this->menudAutor;

        // menudKrijuar
        $this->menudKrijuar = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudKrijuar',
            'menudKrijuar',
            '`menudKrijuar`',
            CastDateFieldForLike("`menudKrijuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`menudKrijuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menudKrijuar->InputTextType = "text";
        $this->menudKrijuar->Nullable = false; // NOT NULL field
        $this->menudKrijuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['menudKrijuar'] = &$this->menudKrijuar;

        // menudAzhornuar
        $this->menudAzhornuar = new DbField(
            'menu_dytesore',
            'menu_dytesore',
            'x_menudAzhornuar',
            'menudAzhornuar',
            '`menudAzhornuar`',
            CastDateFieldForLike("`menudAzhornuar`", 0, "DB"),
            135,
            19,
            0,
            false,
            '`menudAzhornuar`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->menudAzhornuar->InputTextType = "text";
        $this->menudAzhornuar->DefaultErrorMessage = str_replace("%s", $GLOBALS["DATE_FORMAT"], $Language->phrase("IncorrectDate"));
        $this->Fields['menudAzhornuar'] = &$this->menudAzhornuar;

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

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`menu_dytesore`";
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
        $from = "(SELECT *, (SELECT DISTINCT `menukTitull` FROM `menu_kryesore` `TMP_LOOKUPTABLE` WHERE `TMP_LOOKUPTABLE`.`menukID` = `menu_dytesore`.`menudKryesore` LIMIT 1) AS `EV__menudKryesore` FROM `menu_dytesore`)";
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
            $this->menudKryesore->AdvancedSearch->SearchValue != "" ||
            $this->menudKryesore->AdvancedSearch->SearchValue2 != "" ||
            ContainsString($where, " " . $this->menudKryesore->VirtualExpression . " ")
        ) {
            return true;
        }
        if (ContainsString($orderBy, " " . $this->menudKryesore->VirtualExpression . " ")) {
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
            $this->menudID->setDbValue($conn->lastInsertId());
            $rs['menudID'] = $this->menudID->DbValue;
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
            if (array_key_exists('menudID', $rs)) {
                AddFilter($where, QuotedName('menudID', $this->Dbid) . '=' . QuotedValue($rs['menudID'], $this->menudID->DataType, $this->Dbid));
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
        $this->menudID->DbValue = $row['menudID'];
        $this->menudGjuha->DbValue = $row['menudGjuha'];
        $this->menudKryesore->DbValue = $row['menudKryesore'];
        $this->menudTitulli->DbValue = $row['menudTitulli'];
        $this->menudUrl->DbValue = $row['menudUrl'];
        $this->menudBlank->DbValue = $row['menudBlank'];
        $this->menudRadhe->DbValue = $row['menudRadhe'];
        $this->menudAktiv->DbValue = $row['menudAktiv'];
        $this->menudAutor->DbValue = $row['menudAutor'];
        $this->menudKrijuar->DbValue = $row['menudKrijuar'];
        $this->menudAzhornuar->DbValue = $row['menudAzhornuar'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`menudID` = @menudID@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->menudID->CurrentValue : $this->menudID->OldValue;
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
                $this->menudID->CurrentValue = $keys[0];
            } else {
                $this->menudID->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('menudID', $row) ? $row['menudID'] : null;
        } else {
            $val = $this->menudID->OldValue !== null ? $this->menudID->OldValue : $this->menudID->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@menudID@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("MenuDytesoreList");
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
        if ($pageName == "MenuDytesoreView") {
            return $Language->phrase("View");
        } elseif ($pageName == "MenuDytesoreEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "MenuDytesoreAdd") {
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
                return "MenuDytesoreView";
            case Config("API_ADD_ACTION"):
                return "MenuDytesoreAdd";
            case Config("API_EDIT_ACTION"):
                return "MenuDytesoreEdit";
            case Config("API_DELETE_ACTION"):
                return "MenuDytesoreDelete";
            case Config("API_LIST_ACTION"):
                return "MenuDytesoreList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "MenuDytesoreList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("MenuDytesoreView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("MenuDytesoreView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "MenuDytesoreAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "MenuDytesoreAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("MenuDytesoreEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("MenuDytesoreAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("MenuDytesoreDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"menudID\":" . JsonEncode($this->menudID->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->menudID->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->menudID->CurrentValue);
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
            if (($keyValue = Param("menudID") ?? Route("menudID")) !== null) {
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
                $this->menudID->CurrentValue = $key;
            } else {
                $this->menudID->OldValue = $key;
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
        $this->menudID->setDbValue($row['menudID']);
        $this->menudGjuha->setDbValue($row['menudGjuha']);
        $this->menudKryesore->setDbValue($row['menudKryesore']);
        $this->menudTitulli->setDbValue($row['menudTitulli']);
        $this->menudUrl->setDbValue($row['menudUrl']);
        $this->menudBlank->setDbValue($row['menudBlank']);
        $this->menudRadhe->setDbValue($row['menudRadhe']);
        $this->menudAktiv->setDbValue($row['menudAktiv']);
        $this->menudAutor->setDbValue($row['menudAutor']);
        $this->menudKrijuar->setDbValue($row['menudKrijuar']);
        $this->menudAzhornuar->setDbValue($row['menudAzhornuar']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // menudID

        // menudGjuha

        // menudKryesore

        // menudTitulli

        // menudUrl

        // menudBlank

        // menudRadhe

        // menudAktiv

        // menudAutor

        // menudKrijuar

        // menudAzhornuar

        // menudID
        $this->menudID->ViewValue = $this->menudID->CurrentValue;
        $this->menudID->ViewCustomAttributes = "";

        // menudGjuha
        if (strval($this->menudGjuha->CurrentValue) != "") {
            $this->menudGjuha->ViewValue = $this->menudGjuha->optionCaption($this->menudGjuha->CurrentValue);
        } else {
            $this->menudGjuha->ViewValue = null;
        }
        $this->menudGjuha->ViewCustomAttributes = "";

        // menudKryesore
        if ($this->menudKryesore->VirtualValue != "") {
            $this->menudKryesore->ViewValue = $this->menudKryesore->VirtualValue;
        } else {
            $curVal = strval($this->menudKryesore->CurrentValue);
            if ($curVal != "") {
                $this->menudKryesore->ViewValue = $this->menudKryesore->lookupCacheOption($curVal);
                if ($this->menudKryesore->ViewValue === null) { // Lookup from database
                    $filterWrk = "`menukID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->menudKryesore->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->menudKryesore->Lookup->renderViewRow($rswrk[0]);
                        $this->menudKryesore->ViewValue = $this->menudKryesore->displayValue($arwrk);
                    } else {
                        $this->menudKryesore->ViewValue = FormatNumber($this->menudKryesore->CurrentValue, $this->menudKryesore->formatPattern());
                    }
                }
            } else {
                $this->menudKryesore->ViewValue = null;
            }
        }
        $this->menudKryesore->ViewCustomAttributes = "";

        // menudTitulli
        $this->menudTitulli->ViewValue = $this->menudTitulli->CurrentValue;
        $this->menudTitulli->ViewCustomAttributes = "";

        // menudUrl
        $this->menudUrl->ViewValue = $this->menudUrl->CurrentValue;
        $this->menudUrl->ViewCustomAttributes = "";

        // menudBlank
        if (strval($this->menudBlank->CurrentValue) != "") {
            $this->menudBlank->ViewValue = $this->menudBlank->optionCaption($this->menudBlank->CurrentValue);
        } else {
            $this->menudBlank->ViewValue = null;
        }
        $this->menudBlank->ViewCustomAttributes = "";

        // menudRadhe
        $this->menudRadhe->ViewValue = $this->menudRadhe->CurrentValue;
        $this->menudRadhe->ViewValue = FormatNumber($this->menudRadhe->ViewValue, $this->menudRadhe->formatPattern());
        $this->menudRadhe->ViewCustomAttributes = "";

        // menudAktiv
        if (strval($this->menudAktiv->CurrentValue) != "") {
            $this->menudAktiv->ViewValue = $this->menudAktiv->optionCaption($this->menudAktiv->CurrentValue);
        } else {
            $this->menudAktiv->ViewValue = null;
        }
        $this->menudAktiv->ViewCustomAttributes = "";

        // menudAutor
        $this->menudAutor->ViewValue = $this->menudAutor->CurrentValue;
        $curVal = strval($this->menudAutor->CurrentValue);
        if ($curVal != "") {
            $this->menudAutor->ViewValue = $this->menudAutor->lookupCacheOption($curVal);
            if ($this->menudAutor->ViewValue === null) { // Lookup from database
                $filterWrk = "`perdID`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->menudAutor->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->menudAutor->Lookup->renderViewRow($rswrk[0]);
                    $this->menudAutor->ViewValue = $this->menudAutor->displayValue($arwrk);
                } else {
                    $this->menudAutor->ViewValue = FormatNumber($this->menudAutor->CurrentValue, $this->menudAutor->formatPattern());
                }
            }
        } else {
            $this->menudAutor->ViewValue = null;
        }
        $this->menudAutor->ViewCustomAttributes = "";

        // menudKrijuar
        $this->menudKrijuar->ViewValue = $this->menudKrijuar->CurrentValue;
        $this->menudKrijuar->ViewValue = FormatDateTime($this->menudKrijuar->ViewValue, $this->menudKrijuar->formatPattern());
        $this->menudKrijuar->ViewCustomAttributes = "";

        // menudAzhornuar
        $this->menudAzhornuar->ViewValue = $this->menudAzhornuar->CurrentValue;
        $this->menudAzhornuar->ViewValue = FormatDateTime($this->menudAzhornuar->ViewValue, $this->menudAzhornuar->formatPattern());
        $this->menudAzhornuar->ViewCustomAttributes = "";

        // menudID
        $this->menudID->LinkCustomAttributes = "";
        $this->menudID->HrefValue = "";
        $this->menudID->TooltipValue = "";

        // menudGjuha
        $this->menudGjuha->LinkCustomAttributes = "";
        $this->menudGjuha->HrefValue = "";
        $this->menudGjuha->TooltipValue = "";

        // menudKryesore
        $this->menudKryesore->LinkCustomAttributes = "";
        $this->menudKryesore->HrefValue = "";
        $this->menudKryesore->TooltipValue = "";

        // menudTitulli
        $this->menudTitulli->LinkCustomAttributes = "";
        $this->menudTitulli->HrefValue = "";
        $this->menudTitulli->TooltipValue = "";

        // menudUrl
        $this->menudUrl->LinkCustomAttributes = "";
        $this->menudUrl->HrefValue = "";
        $this->menudUrl->TooltipValue = "";

        // menudBlank
        $this->menudBlank->LinkCustomAttributes = "";
        $this->menudBlank->HrefValue = "";
        $this->menudBlank->TooltipValue = "";

        // menudRadhe
        $this->menudRadhe->LinkCustomAttributes = "";
        $this->menudRadhe->HrefValue = "";
        $this->menudRadhe->TooltipValue = "";

        // menudAktiv
        $this->menudAktiv->LinkCustomAttributes = "";
        $this->menudAktiv->HrefValue = "";
        $this->menudAktiv->TooltipValue = "";

        // menudAutor
        $this->menudAutor->LinkCustomAttributes = "";
        $this->menudAutor->HrefValue = "";
        $this->menudAutor->TooltipValue = "";

        // menudKrijuar
        $this->menudKrijuar->LinkCustomAttributes = "";
        $this->menudKrijuar->HrefValue = "";
        $this->menudKrijuar->TooltipValue = "";

        // menudAzhornuar
        $this->menudAzhornuar->LinkCustomAttributes = "";
        $this->menudAzhornuar->HrefValue = "";
        $this->menudAzhornuar->TooltipValue = "";

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

        // menudID
        $this->menudID->setupEditAttributes();
        $this->menudID->EditCustomAttributes = "";
        $this->menudID->EditValue = $this->menudID->CurrentValue;
        $this->menudID->ViewCustomAttributes = "";

        // menudGjuha
        $this->menudGjuha->EditCustomAttributes = "";
        $this->menudGjuha->EditValue = $this->menudGjuha->options(false);
        $this->menudGjuha->PlaceHolder = RemoveHtml($this->menudGjuha->caption());

        // menudKryesore
        $this->menudKryesore->setupEditAttributes();
        $this->menudKryesore->EditCustomAttributes = "";
        $this->menudKryesore->PlaceHolder = RemoveHtml($this->menudKryesore->caption());

        // menudTitulli
        $this->menudTitulli->setupEditAttributes();
        $this->menudTitulli->EditCustomAttributes = "";
        if (!$this->menudTitulli->Raw) {
            $this->menudTitulli->CurrentValue = HtmlDecode($this->menudTitulli->CurrentValue);
        }
        $this->menudTitulli->EditValue = $this->menudTitulli->CurrentValue;
        $this->menudTitulli->PlaceHolder = RemoveHtml($this->menudTitulli->caption());

        // menudUrl
        $this->menudUrl->setupEditAttributes();
        $this->menudUrl->EditCustomAttributes = "";
        if (!$this->menudUrl->Raw) {
            $this->menudUrl->CurrentValue = HtmlDecode($this->menudUrl->CurrentValue);
        }
        $this->menudUrl->EditValue = $this->menudUrl->CurrentValue;
        $this->menudUrl->PlaceHolder = RemoveHtml($this->menudUrl->caption());

        // menudBlank
        $this->menudBlank->EditCustomAttributes = "";
        $this->menudBlank->EditValue = $this->menudBlank->options(false);
        $this->menudBlank->PlaceHolder = RemoveHtml($this->menudBlank->caption());

        // menudRadhe
        $this->menudRadhe->setupEditAttributes();
        $this->menudRadhe->EditCustomAttributes = "";
        $this->menudRadhe->EditValue = $this->menudRadhe->CurrentValue;
        $this->menudRadhe->PlaceHolder = RemoveHtml($this->menudRadhe->caption());
        if (strval($this->menudRadhe->EditValue) != "" && is_numeric($this->menudRadhe->EditValue)) {
            $this->menudRadhe->EditValue = FormatNumber($this->menudRadhe->EditValue, null);
        }

        // menudAktiv
        $this->menudAktiv->EditCustomAttributes = "";
        $this->menudAktiv->EditValue = $this->menudAktiv->options(false);
        $this->menudAktiv->PlaceHolder = RemoveHtml($this->menudAktiv->caption());

        // menudAutor

        // menudKrijuar

        // menudAzhornuar

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
                    $doc->exportCaption($this->menudID);
                    $doc->exportCaption($this->menudGjuha);
                    $doc->exportCaption($this->menudKryesore);
                    $doc->exportCaption($this->menudTitulli);
                    $doc->exportCaption($this->menudUrl);
                    $doc->exportCaption($this->menudBlank);
                    $doc->exportCaption($this->menudRadhe);
                    $doc->exportCaption($this->menudAktiv);
                    $doc->exportCaption($this->menudAutor);
                    $doc->exportCaption($this->menudKrijuar);
                    $doc->exportCaption($this->menudAzhornuar);
                } else {
                    $doc->exportCaption($this->menudID);
                    $doc->exportCaption($this->menudGjuha);
                    $doc->exportCaption($this->menudKryesore);
                    $doc->exportCaption($this->menudTitulli);
                    $doc->exportCaption($this->menudUrl);
                    $doc->exportCaption($this->menudBlank);
                    $doc->exportCaption($this->menudRadhe);
                    $doc->exportCaption($this->menudAktiv);
                    $doc->exportCaption($this->menudAutor);
                    $doc->exportCaption($this->menudKrijuar);
                    $doc->exportCaption($this->menudAzhornuar);
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
                        $doc->exportField($this->menudID);
                        $doc->exportField($this->menudGjuha);
                        $doc->exportField($this->menudKryesore);
                        $doc->exportField($this->menudTitulli);
                        $doc->exportField($this->menudUrl);
                        $doc->exportField($this->menudBlank);
                        $doc->exportField($this->menudRadhe);
                        $doc->exportField($this->menudAktiv);
                        $doc->exportField($this->menudAutor);
                        $doc->exportField($this->menudKrijuar);
                        $doc->exportField($this->menudAzhornuar);
                    } else {
                        $doc->exportField($this->menudID);
                        $doc->exportField($this->menudGjuha);
                        $doc->exportField($this->menudKryesore);
                        $doc->exportField($this->menudTitulli);
                        $doc->exportField($this->menudUrl);
                        $doc->exportField($this->menudBlank);
                        $doc->exportField($this->menudRadhe);
                        $doc->exportField($this->menudAktiv);
                        $doc->exportField($this->menudAutor);
                        $doc->exportField($this->menudKrijuar);
                        $doc->exportField($this->menudAzhornuar);
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
