<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for equipo
 */
class Equipo extends DbTable
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
    public $ID_EQUIPO;
    public $NOM_EQUIPO_CORTO;
    public $NOM_EQUIPO_LARGO;
    public $PAIS_EQUIPO;
    public $REGION_EQUIPO;
    public $DETALLE_EQUIPO;
    public $ESCUDO_EQUIPO;
    public $NOM_ESTADIO;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'equipo';
        $this->TableName = 'equipo';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`equipo`";
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

        // ID_EQUIPO
        $this->ID_EQUIPO = new DbField(
            'equipo',
            'equipo',
            'x_ID_EQUIPO',
            'ID_EQUIPO',
            '`ID_EQUIPO`',
            '`ID_EQUIPO`',
            3,
            11,
            -1,
            false,
            '`ID_EQUIPO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->ID_EQUIPO->InputTextType = "text";
        $this->ID_EQUIPO->IsAutoIncrement = true; // Autoincrement field
        $this->ID_EQUIPO->IsPrimaryKey = true; // Primary key field
        $this->ID_EQUIPO->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['ID_EQUIPO'] = &$this->ID_EQUIPO;

        // NOM_EQUIPO_CORTO
        $this->NOM_EQUIPO_CORTO = new DbField(
            'equipo',
            'equipo',
            'x_NOM_EQUIPO_CORTO',
            'NOM_EQUIPO_CORTO',
            '`NOM_EQUIPO_CORTO`',
            '`NOM_EQUIPO_CORTO`',
            201,
            256,
            -1,
            false,
            '`NOM_EQUIPO_CORTO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->NOM_EQUIPO_CORTO->InputTextType = "text";
        $this->Fields['NOM_EQUIPO_CORTO'] = &$this->NOM_EQUIPO_CORTO;

        // NOM_EQUIPO_LARGO
        $this->NOM_EQUIPO_LARGO = new DbField(
            'equipo',
            'equipo',
            'x_NOM_EQUIPO_LARGO',
            'NOM_EQUIPO_LARGO',
            '`NOM_EQUIPO_LARGO`',
            '`NOM_EQUIPO_LARGO`',
            201,
            256,
            -1,
            false,
            '`NOM_EQUIPO_LARGO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->NOM_EQUIPO_LARGO->InputTextType = "text";
        $this->Fields['NOM_EQUIPO_LARGO'] = &$this->NOM_EQUIPO_LARGO;

        // PAIS_EQUIPO
        $this->PAIS_EQUIPO = new DbField(
            'equipo',
            'equipo',
            'x_PAIS_EQUIPO',
            'PAIS_EQUIPO',
            '`PAIS_EQUIPO`',
            '`PAIS_EQUIPO`',
            201,
            256,
            -1,
            false,
            '`PAIS_EQUIPO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->PAIS_EQUIPO->InputTextType = "text";
        $this->Fields['PAIS_EQUIPO'] = &$this->PAIS_EQUIPO;

        // REGION_EQUIPO
        $this->REGION_EQUIPO = new DbField(
            'equipo',
            'equipo',
            'x_REGION_EQUIPO',
            'REGION_EQUIPO',
            '`REGION_EQUIPO`',
            '`REGION_EQUIPO`',
            201,
            256,
            -1,
            false,
            '`REGION_EQUIPO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->REGION_EQUIPO->InputTextType = "text";
        $this->Fields['REGION_EQUIPO'] = &$this->REGION_EQUIPO;

        // DETALLE_EQUIPO
        $this->DETALLE_EQUIPO = new DbField(
            'equipo',
            'equipo',
            'x_DETALLE_EQUIPO',
            'DETALLE_EQUIPO',
            '`DETALLE_EQUIPO`',
            '`DETALLE_EQUIPO`',
            201,
            65535,
            -1,
            false,
            '`DETALLE_EQUIPO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->DETALLE_EQUIPO->InputTextType = "text";
        $this->Fields['DETALLE_EQUIPO'] = &$this->DETALLE_EQUIPO;

        // ESCUDO_EQUIPO
        $this->ESCUDO_EQUIPO = new DbField(
            'equipo',
            'equipo',
            'x_ESCUDO_EQUIPO',
            'ESCUDO_EQUIPO',
            '`ESCUDO_EQUIPO`',
            '`ESCUDO_EQUIPO`',
            205,
            0,
            -1,
            true,
            '`ESCUDO_EQUIPO`',
            false,
            false,
            false,
            'IMAGE',
            'FILE'
        );
        $this->ESCUDO_EQUIPO->InputTextType = "text";
        $this->ESCUDO_EQUIPO->Sortable = false; // Allow sort
        $this->Fields['ESCUDO_EQUIPO'] = &$this->ESCUDO_EQUIPO;

        // NOM_ESTADIO
        $this->NOM_ESTADIO = new DbField(
            'equipo',
            'equipo',
            'x_NOM_ESTADIO',
            'NOM_ESTADIO',
            '`NOM_ESTADIO`',
            '`NOM_ESTADIO`',
            201,
            256,
            -1,
            false,
            '`NOM_ESTADIO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->NOM_ESTADIO->InputTextType = "text";
        $this->Fields['NOM_ESTADIO'] = &$this->NOM_ESTADIO;

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

    // Single column sort
    public function updateSort(&$fld)
    {
        if ($this->CurrentOrder == $fld->Name) {
            $sortField = $fld->Expression;
            $lastSort = $fld->getSort();
            if (in_array($this->CurrentOrderType, ["ASC", "DESC", "NO"])) {
                $curSort = $this->CurrentOrderType;
            } else {
                $curSort = $lastSort;
            }
            $orderBy = in_array($curSort, ["ASC", "DESC"]) ? $sortField . " " . $curSort : "";
            $this->setSessionOrderBy($orderBy); // Save to Session
        }
    }

    // Update field sort
    public function updateFieldSort()
    {
        $orderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
        $flds = GetSortFields($orderBy);
        foreach ($this->Fields as $field) {
            $fldSort = "";
            foreach ($flds as $fld) {
                if ($fld[0] == $field->Expression || $fld[0] == $field->VirtualExpression) {
                    $fldSort = $fld[1];
                }
            }
            $field->setSort($fldSort);
        }
    }

    // Table level SQL
    public function getSqlFrom() // From
    {
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`equipo`";
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
        return ($this->SqlOrderBy != "") ? $this->SqlOrderBy : "";
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
            $this->ID_EQUIPO->setDbValue($conn->lastInsertId());
            $rs['ID_EQUIPO'] = $this->ID_EQUIPO->DbValue;
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
            if (array_key_exists('ID_EQUIPO', $rs)) {
                AddFilter($where, QuotedName('ID_EQUIPO', $this->Dbid) . '=' . QuotedValue($rs['ID_EQUIPO'], $this->ID_EQUIPO->DataType, $this->Dbid));
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
        $this->ID_EQUIPO->DbValue = $row['ID_EQUIPO'];
        $this->NOM_EQUIPO_CORTO->DbValue = $row['NOM_EQUIPO_CORTO'];
        $this->NOM_EQUIPO_LARGO->DbValue = $row['NOM_EQUIPO_LARGO'];
        $this->PAIS_EQUIPO->DbValue = $row['PAIS_EQUIPO'];
        $this->REGION_EQUIPO->DbValue = $row['REGION_EQUIPO'];
        $this->DETALLE_EQUIPO->DbValue = $row['DETALLE_EQUIPO'];
        $this->ESCUDO_EQUIPO->Upload->DbValue = $row['ESCUDO_EQUIPO'];
        $this->NOM_ESTADIO->DbValue = $row['NOM_ESTADIO'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`ID_EQUIPO` = @ID_EQUIPO@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->ID_EQUIPO->CurrentValue : $this->ID_EQUIPO->OldValue;
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
                $this->ID_EQUIPO->CurrentValue = $keys[0];
            } else {
                $this->ID_EQUIPO->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('ID_EQUIPO', $row) ? $row['ID_EQUIPO'] : null;
        } else {
            $val = $this->ID_EQUIPO->OldValue !== null ? $this->ID_EQUIPO->OldValue : $this->ID_EQUIPO->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@ID_EQUIPO@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("EquipoList");
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
        if ($pageName == "EquipoView") {
            return $Language->phrase("View");
        } elseif ($pageName == "EquipoEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "EquipoAdd") {
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
                return "EquipoView";
            case Config("API_ADD_ACTION"):
                return "EquipoAdd";
            case Config("API_EDIT_ACTION"):
                return "EquipoEdit";
            case Config("API_DELETE_ACTION"):
                return "EquipoDelete";
            case Config("API_LIST_ACTION"):
                return "EquipoList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "EquipoList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("EquipoView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("EquipoView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "EquipoAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "EquipoAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("EquipoEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("EquipoAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("EquipoDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"ID_EQUIPO\":" . JsonEncode($this->ID_EQUIPO->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->ID_EQUIPO->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->ID_EQUIPO->CurrentValue);
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
            $attrs = ' role="button" data-sort-url="' . $sortUrl . '" data-sort-type="1"';
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
            if (($keyValue = Param("ID_EQUIPO") ?? Route("ID_EQUIPO")) !== null) {
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
                $this->ID_EQUIPO->CurrentValue = $key;
            } else {
                $this->ID_EQUIPO->OldValue = $key;
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
        $this->ID_EQUIPO->setDbValue($row['ID_EQUIPO']);
        $this->NOM_EQUIPO_CORTO->setDbValue($row['NOM_EQUIPO_CORTO']);
        $this->NOM_EQUIPO_LARGO->setDbValue($row['NOM_EQUIPO_LARGO']);
        $this->PAIS_EQUIPO->setDbValue($row['PAIS_EQUIPO']);
        $this->REGION_EQUIPO->setDbValue($row['REGION_EQUIPO']);
        $this->DETALLE_EQUIPO->setDbValue($row['DETALLE_EQUIPO']);
        $this->ESCUDO_EQUIPO->Upload->DbValue = $row['ESCUDO_EQUIPO'];
        $this->NOM_ESTADIO->setDbValue($row['NOM_ESTADIO']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // ID_EQUIPO

        // NOM_EQUIPO_CORTO

        // NOM_EQUIPO_LARGO

        // PAIS_EQUIPO

        // REGION_EQUIPO

        // DETALLE_EQUIPO

        // ESCUDO_EQUIPO

        // NOM_ESTADIO

        // ID_EQUIPO
        $this->ID_EQUIPO->ViewValue = $this->ID_EQUIPO->CurrentValue;
        $this->ID_EQUIPO->ViewCustomAttributes = "";

        // NOM_EQUIPO_CORTO
        $this->NOM_EQUIPO_CORTO->ViewValue = $this->NOM_EQUIPO_CORTO->CurrentValue;
        $this->NOM_EQUIPO_CORTO->ViewCustomAttributes = "";

        // NOM_EQUIPO_LARGO
        $this->NOM_EQUIPO_LARGO->ViewValue = $this->NOM_EQUIPO_LARGO->CurrentValue;
        $this->NOM_EQUIPO_LARGO->ViewCustomAttributes = "";

        // PAIS_EQUIPO
        $this->PAIS_EQUIPO->ViewValue = $this->PAIS_EQUIPO->CurrentValue;
        $this->PAIS_EQUIPO->ViewCustomAttributes = "";

        // REGION_EQUIPO
        $this->REGION_EQUIPO->ViewValue = $this->REGION_EQUIPO->CurrentValue;
        $this->REGION_EQUIPO->ViewCustomAttributes = "";

        // DETALLE_EQUIPO
        $this->DETALLE_EQUIPO->ViewValue = $this->DETALLE_EQUIPO->CurrentValue;
        $this->DETALLE_EQUIPO->ViewCustomAttributes = "";

        // ESCUDO_EQUIPO
        if (!EmptyValue($this->ESCUDO_EQUIPO->Upload->DbValue)) {
            $this->ESCUDO_EQUIPO->ImageAlt = $this->ESCUDO_EQUIPO->alt();
            $this->ESCUDO_EQUIPO->ImageCssClass = "ew-image";
            $this->ESCUDO_EQUIPO->ViewValue = $this->ID_EQUIPO->CurrentValue;
            $this->ESCUDO_EQUIPO->IsBlobImage = IsImageFile(ContentExtension($this->ESCUDO_EQUIPO->Upload->DbValue));
        } else {
            $this->ESCUDO_EQUIPO->ViewValue = "";
        }
        $this->ESCUDO_EQUIPO->ViewCustomAttributes = "";

        // NOM_ESTADIO
        $this->NOM_ESTADIO->ViewValue = $this->NOM_ESTADIO->CurrentValue;
        $this->NOM_ESTADIO->ViewCustomAttributes = "";

        // ID_EQUIPO
        $this->ID_EQUIPO->LinkCustomAttributes = "";
        $this->ID_EQUIPO->HrefValue = "";
        $this->ID_EQUIPO->TooltipValue = "";

        // NOM_EQUIPO_CORTO
        $this->NOM_EQUIPO_CORTO->LinkCustomAttributes = "";
        $this->NOM_EQUIPO_CORTO->HrefValue = "";
        $this->NOM_EQUIPO_CORTO->TooltipValue = "";

        // NOM_EQUIPO_LARGO
        $this->NOM_EQUIPO_LARGO->LinkCustomAttributes = "";
        $this->NOM_EQUIPO_LARGO->HrefValue = "";
        $this->NOM_EQUIPO_LARGO->TooltipValue = "";

        // PAIS_EQUIPO
        $this->PAIS_EQUIPO->LinkCustomAttributes = "";
        $this->PAIS_EQUIPO->HrefValue = "";
        $this->PAIS_EQUIPO->TooltipValue = "";

        // REGION_EQUIPO
        $this->REGION_EQUIPO->LinkCustomAttributes = "";
        $this->REGION_EQUIPO->HrefValue = "";
        $this->REGION_EQUIPO->TooltipValue = "";

        // DETALLE_EQUIPO
        $this->DETALLE_EQUIPO->LinkCustomAttributes = "";
        $this->DETALLE_EQUIPO->HrefValue = "";
        $this->DETALLE_EQUIPO->TooltipValue = "";

        // ESCUDO_EQUIPO
        $this->ESCUDO_EQUIPO->LinkCustomAttributes = "";
        if (!empty($this->ESCUDO_EQUIPO->Upload->DbValue)) {
            $this->ESCUDO_EQUIPO->HrefValue = GetFileUploadUrl($this->ESCUDO_EQUIPO, $this->ID_EQUIPO->CurrentValue);
            $this->ESCUDO_EQUIPO->LinkAttrs["target"] = "";
            if ($this->ESCUDO_EQUIPO->IsBlobImage && empty($this->ESCUDO_EQUIPO->LinkAttrs["target"])) {
                $this->ESCUDO_EQUIPO->LinkAttrs["target"] = "_blank";
            }
            if ($this->isExport()) {
                $this->ESCUDO_EQUIPO->HrefValue = FullUrl($this->ESCUDO_EQUIPO->HrefValue, "href");
            }
        } else {
            $this->ESCUDO_EQUIPO->HrefValue = "";
        }
        $this->ESCUDO_EQUIPO->ExportHrefValue = GetFileUploadUrl($this->ESCUDO_EQUIPO, $this->ID_EQUIPO->CurrentValue);
        $this->ESCUDO_EQUIPO->TooltipValue = "";
        if ($this->ESCUDO_EQUIPO->UseColorbox) {
            if (EmptyValue($this->ESCUDO_EQUIPO->TooltipValue)) {
                $this->ESCUDO_EQUIPO->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->ESCUDO_EQUIPO->LinkAttrs["data-rel"] = "equipo_x_ESCUDO_EQUIPO";
            $this->ESCUDO_EQUIPO->LinkAttrs->appendClass("ew-lightbox");
        }

        // NOM_ESTADIO
        $this->NOM_ESTADIO->LinkCustomAttributes = "";
        $this->NOM_ESTADIO->HrefValue = "";
        $this->NOM_ESTADIO->TooltipValue = "";

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

        // ID_EQUIPO
        $this->ID_EQUIPO->setupEditAttributes();
        $this->ID_EQUIPO->EditCustomAttributes = "";
        $this->ID_EQUIPO->EditValue = $this->ID_EQUIPO->CurrentValue;
        $this->ID_EQUIPO->ViewCustomAttributes = "";

        // NOM_EQUIPO_CORTO
        $this->NOM_EQUIPO_CORTO->setupEditAttributes();
        $this->NOM_EQUIPO_CORTO->EditCustomAttributes = "";
        $this->NOM_EQUIPO_CORTO->EditValue = $this->NOM_EQUIPO_CORTO->CurrentValue;
        $this->NOM_EQUIPO_CORTO->PlaceHolder = RemoveHtml($this->NOM_EQUIPO_CORTO->caption());

        // NOM_EQUIPO_LARGO
        $this->NOM_EQUIPO_LARGO->setupEditAttributes();
        $this->NOM_EQUIPO_LARGO->EditCustomAttributes = "";
        $this->NOM_EQUIPO_LARGO->EditValue = $this->NOM_EQUIPO_LARGO->CurrentValue;
        $this->NOM_EQUIPO_LARGO->PlaceHolder = RemoveHtml($this->NOM_EQUIPO_LARGO->caption());

        // PAIS_EQUIPO
        $this->PAIS_EQUIPO->setupEditAttributes();
        $this->PAIS_EQUIPO->EditCustomAttributes = "";
        $this->PAIS_EQUIPO->EditValue = $this->PAIS_EQUIPO->CurrentValue;
        $this->PAIS_EQUIPO->PlaceHolder = RemoveHtml($this->PAIS_EQUIPO->caption());

        // REGION_EQUIPO
        $this->REGION_EQUIPO->setupEditAttributes();
        $this->REGION_EQUIPO->EditCustomAttributes = "";
        $this->REGION_EQUIPO->EditValue = $this->REGION_EQUIPO->CurrentValue;
        $this->REGION_EQUIPO->PlaceHolder = RemoveHtml($this->REGION_EQUIPO->caption());

        // DETALLE_EQUIPO
        $this->DETALLE_EQUIPO->setupEditAttributes();
        $this->DETALLE_EQUIPO->EditCustomAttributes = "";
        $this->DETALLE_EQUIPO->EditValue = $this->DETALLE_EQUIPO->CurrentValue;
        $this->DETALLE_EQUIPO->PlaceHolder = RemoveHtml($this->DETALLE_EQUIPO->caption());

        // ESCUDO_EQUIPO
        $this->ESCUDO_EQUIPO->setupEditAttributes();
        $this->ESCUDO_EQUIPO->EditCustomAttributes = "";
        if (!EmptyValue($this->ESCUDO_EQUIPO->Upload->DbValue)) {
            $this->ESCUDO_EQUIPO->ImageAlt = $this->ESCUDO_EQUIPO->alt();
            $this->ESCUDO_EQUIPO->ImageCssClass = "ew-image";
            $this->ESCUDO_EQUIPO->EditValue = $this->ID_EQUIPO->CurrentValue;
            $this->ESCUDO_EQUIPO->IsBlobImage = IsImageFile(ContentExtension($this->ESCUDO_EQUIPO->Upload->DbValue));
        } else {
            $this->ESCUDO_EQUIPO->EditValue = "";
        }

        // NOM_ESTADIO
        $this->NOM_ESTADIO->setupEditAttributes();
        $this->NOM_ESTADIO->EditCustomAttributes = "";
        $this->NOM_ESTADIO->EditValue = $this->NOM_ESTADIO->CurrentValue;
        $this->NOM_ESTADIO->PlaceHolder = RemoveHtml($this->NOM_ESTADIO->caption());

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
                    $doc->exportCaption($this->ID_EQUIPO);
                    $doc->exportCaption($this->NOM_EQUIPO_CORTO);
                    $doc->exportCaption($this->NOM_EQUIPO_LARGO);
                    $doc->exportCaption($this->PAIS_EQUIPO);
                    $doc->exportCaption($this->REGION_EQUIPO);
                    $doc->exportCaption($this->DETALLE_EQUIPO);
                    $doc->exportCaption($this->ESCUDO_EQUIPO);
                    $doc->exportCaption($this->NOM_ESTADIO);
                } else {
                    $doc->exportCaption($this->ID_EQUIPO);
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
                        $doc->exportField($this->ID_EQUIPO);
                        $doc->exportField($this->NOM_EQUIPO_CORTO);
                        $doc->exportField($this->NOM_EQUIPO_LARGO);
                        $doc->exportField($this->PAIS_EQUIPO);
                        $doc->exportField($this->REGION_EQUIPO);
                        $doc->exportField($this->DETALLE_EQUIPO);
                        $doc->exportField($this->ESCUDO_EQUIPO);
                        $doc->exportField($this->NOM_ESTADIO);
                    } else {
                        $doc->exportField($this->ID_EQUIPO);
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
        global $DownloadFileName;
        $width = ($width > 0) ? $width : Config("THUMBNAIL_DEFAULT_WIDTH");
        $height = ($height > 0) ? $height : Config("THUMBNAIL_DEFAULT_HEIGHT");

        // Set up field name / file name field / file type field
        $fldName = "";
        $fileNameFld = "";
        $fileTypeFld = "";
        if ($fldparm == 'ESCUDO_EQUIPO') {
            $fldName = "ESCUDO_EQUIPO";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->ID_EQUIPO->CurrentValue = $ar[0];
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
                            AddHeader("Content-Disposition", "attachment" . ($DownloadFileName ? "; filename=\"" . $DownloadFileName . "\"" : ""));
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
