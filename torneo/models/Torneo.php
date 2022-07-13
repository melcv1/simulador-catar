<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for torneo
 */
class Torneo extends DbTable
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
    public $ID_TORNEO;
    public $NOM_TORNEO_CORTO;
    public $NOM_TORNEO_LARGO;
    public $PAIS_TORNEO;
    public $REGION_TORNEO;
    public $DETALLE_TORNEO;
    public $LOGO_TORNEO;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'torneo';
        $this->TableName = 'torneo';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`torneo`";
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

        // ID_TORNEO
        $this->ID_TORNEO = new DbField(
            'torneo',
            'torneo',
            'x_ID_TORNEO',
            'ID_TORNEO',
            '`ID_TORNEO`',
            '`ID_TORNEO`',
            3,
            11,
            -1,
            false,
            '`ID_TORNEO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->ID_TORNEO->InputTextType = "text";
        $this->ID_TORNEO->IsAutoIncrement = true; // Autoincrement field
        $this->ID_TORNEO->IsPrimaryKey = true; // Primary key field
        $this->ID_TORNEO->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['ID_TORNEO'] = &$this->ID_TORNEO;

        // NOM_TORNEO_CORTO
        $this->NOM_TORNEO_CORTO = new DbField(
            'torneo',
            'torneo',
            'x_NOM_TORNEO_CORTO',
            'NOM_TORNEO_CORTO',
            '`NOM_TORNEO_CORTO`',
            '`NOM_TORNEO_CORTO`',
            201,
            256,
            -1,
            false,
            '`NOM_TORNEO_CORTO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->NOM_TORNEO_CORTO->InputTextType = "text";
        $this->Fields['NOM_TORNEO_CORTO'] = &$this->NOM_TORNEO_CORTO;

        // NOM_TORNEO_LARGO
        $this->NOM_TORNEO_LARGO = new DbField(
            'torneo',
            'torneo',
            'x_NOM_TORNEO_LARGO',
            'NOM_TORNEO_LARGO',
            '`NOM_TORNEO_LARGO`',
            '`NOM_TORNEO_LARGO`',
            201,
            256,
            -1,
            false,
            '`NOM_TORNEO_LARGO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->NOM_TORNEO_LARGO->InputTextType = "text";
        $this->Fields['NOM_TORNEO_LARGO'] = &$this->NOM_TORNEO_LARGO;

        // PAIS_TORNEO
        $this->PAIS_TORNEO = new DbField(
            'torneo',
            'torneo',
            'x_PAIS_TORNEO',
            'PAIS_TORNEO',
            '`PAIS_TORNEO`',
            '`PAIS_TORNEO`',
            201,
            256,
            -1,
            false,
            '`PAIS_TORNEO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->PAIS_TORNEO->InputTextType = "text";
        $this->Fields['PAIS_TORNEO'] = &$this->PAIS_TORNEO;

        // REGION_TORNEO
        $this->REGION_TORNEO = new DbField(
            'torneo',
            'torneo',
            'x_REGION_TORNEO',
            'REGION_TORNEO',
            '`REGION_TORNEO`',
            '`REGION_TORNEO`',
            201,
            256,
            -1,
            false,
            '`REGION_TORNEO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->REGION_TORNEO->InputTextType = "text";
        $this->Fields['REGION_TORNEO'] = &$this->REGION_TORNEO;

        // DETALLE_TORNEO
        $this->DETALLE_TORNEO = new DbField(
            'torneo',
            'torneo',
            'x_DETALLE_TORNEO',
            'DETALLE_TORNEO',
            '`DETALLE_TORNEO`',
            '`DETALLE_TORNEO`',
            201,
            65535,
            -1,
            false,
            '`DETALLE_TORNEO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->DETALLE_TORNEO->InputTextType = "text";
        $this->Fields['DETALLE_TORNEO'] = &$this->DETALLE_TORNEO;

        // LOGO_TORNEO
        $this->LOGO_TORNEO = new DbField(
            'torneo',
            'torneo',
            'x_LOGO_TORNEO',
            'LOGO_TORNEO',
            '`LOGO_TORNEO`',
            '`LOGO_TORNEO`',
            205,
            0,
            -1,
            true,
            '`LOGO_TORNEO`',
            false,
            false,
            false,
            'IMAGE',
            'FILE'
        );
        $this->LOGO_TORNEO->InputTextType = "text";
        $this->LOGO_TORNEO->Sortable = false; // Allow sort
        $this->Fields['LOGO_TORNEO'] = &$this->LOGO_TORNEO;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`torneo`";
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
            $this->ID_TORNEO->setDbValue($conn->lastInsertId());
            $rs['ID_TORNEO'] = $this->ID_TORNEO->DbValue;
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
            if (array_key_exists('ID_TORNEO', $rs)) {
                AddFilter($where, QuotedName('ID_TORNEO', $this->Dbid) . '=' . QuotedValue($rs['ID_TORNEO'], $this->ID_TORNEO->DataType, $this->Dbid));
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
        $this->ID_TORNEO->DbValue = $row['ID_TORNEO'];
        $this->NOM_TORNEO_CORTO->DbValue = $row['NOM_TORNEO_CORTO'];
        $this->NOM_TORNEO_LARGO->DbValue = $row['NOM_TORNEO_LARGO'];
        $this->PAIS_TORNEO->DbValue = $row['PAIS_TORNEO'];
        $this->REGION_TORNEO->DbValue = $row['REGION_TORNEO'];
        $this->DETALLE_TORNEO->DbValue = $row['DETALLE_TORNEO'];
        $this->LOGO_TORNEO->Upload->DbValue = $row['LOGO_TORNEO'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`ID_TORNEO` = @ID_TORNEO@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->ID_TORNEO->CurrentValue : $this->ID_TORNEO->OldValue;
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
                $this->ID_TORNEO->CurrentValue = $keys[0];
            } else {
                $this->ID_TORNEO->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('ID_TORNEO', $row) ? $row['ID_TORNEO'] : null;
        } else {
            $val = $this->ID_TORNEO->OldValue !== null ? $this->ID_TORNEO->OldValue : $this->ID_TORNEO->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@ID_TORNEO@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("TorneoList");
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
        if ($pageName == "TorneoView") {
            return $Language->phrase("View");
        } elseif ($pageName == "TorneoEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "TorneoAdd") {
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
                return "TorneoView";
            case Config("API_ADD_ACTION"):
                return "TorneoAdd";
            case Config("API_EDIT_ACTION"):
                return "TorneoEdit";
            case Config("API_DELETE_ACTION"):
                return "TorneoDelete";
            case Config("API_LIST_ACTION"):
                return "TorneoList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "TorneoList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("TorneoView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("TorneoView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "TorneoAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "TorneoAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("TorneoEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("TorneoAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("TorneoDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"ID_TORNEO\":" . JsonEncode($this->ID_TORNEO->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->ID_TORNEO->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->ID_TORNEO->CurrentValue);
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
            if (($keyValue = Param("ID_TORNEO") ?? Route("ID_TORNEO")) !== null) {
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
                $this->ID_TORNEO->CurrentValue = $key;
            } else {
                $this->ID_TORNEO->OldValue = $key;
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
        $this->ID_TORNEO->setDbValue($row['ID_TORNEO']);
        $this->NOM_TORNEO_CORTO->setDbValue($row['NOM_TORNEO_CORTO']);
        $this->NOM_TORNEO_LARGO->setDbValue($row['NOM_TORNEO_LARGO']);
        $this->PAIS_TORNEO->setDbValue($row['PAIS_TORNEO']);
        $this->REGION_TORNEO->setDbValue($row['REGION_TORNEO']);
        $this->DETALLE_TORNEO->setDbValue($row['DETALLE_TORNEO']);
        $this->LOGO_TORNEO->Upload->DbValue = $row['LOGO_TORNEO'];
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // ID_TORNEO

        // NOM_TORNEO_CORTO

        // NOM_TORNEO_LARGO

        // PAIS_TORNEO

        // REGION_TORNEO

        // DETALLE_TORNEO

        // LOGO_TORNEO

        // ID_TORNEO
        $this->ID_TORNEO->ViewValue = $this->ID_TORNEO->CurrentValue;
        $this->ID_TORNEO->ViewCustomAttributes = "";

        // NOM_TORNEO_CORTO
        $this->NOM_TORNEO_CORTO->ViewValue = $this->NOM_TORNEO_CORTO->CurrentValue;
        $this->NOM_TORNEO_CORTO->ViewCustomAttributes = "";

        // NOM_TORNEO_LARGO
        $this->NOM_TORNEO_LARGO->ViewValue = $this->NOM_TORNEO_LARGO->CurrentValue;
        $this->NOM_TORNEO_LARGO->ViewCustomAttributes = "";

        // PAIS_TORNEO
        $this->PAIS_TORNEO->ViewValue = $this->PAIS_TORNEO->CurrentValue;
        $this->PAIS_TORNEO->ViewCustomAttributes = "";

        // REGION_TORNEO
        $this->REGION_TORNEO->ViewValue = $this->REGION_TORNEO->CurrentValue;
        $this->REGION_TORNEO->ViewCustomAttributes = "";

        // DETALLE_TORNEO
        $this->DETALLE_TORNEO->ViewValue = $this->DETALLE_TORNEO->CurrentValue;
        $this->DETALLE_TORNEO->ViewCustomAttributes = "";

        // LOGO_TORNEO
        if (!EmptyValue($this->LOGO_TORNEO->Upload->DbValue)) {
            $this->LOGO_TORNEO->ImageAlt = $this->LOGO_TORNEO->alt();
            $this->LOGO_TORNEO->ImageCssClass = "ew-image";
            $this->LOGO_TORNEO->ViewValue = $this->ID_TORNEO->CurrentValue;
            $this->LOGO_TORNEO->IsBlobImage = IsImageFile(ContentExtension($this->LOGO_TORNEO->Upload->DbValue));
        } else {
            $this->LOGO_TORNEO->ViewValue = "";
        }
        $this->LOGO_TORNEO->ViewCustomAttributes = "";

        // ID_TORNEO
        $this->ID_TORNEO->LinkCustomAttributes = "";
        $this->ID_TORNEO->HrefValue = "";
        $this->ID_TORNEO->TooltipValue = "";

        // NOM_TORNEO_CORTO
        $this->NOM_TORNEO_CORTO->LinkCustomAttributes = "";
        $this->NOM_TORNEO_CORTO->HrefValue = "";
        $this->NOM_TORNEO_CORTO->TooltipValue = "";

        // NOM_TORNEO_LARGO
        $this->NOM_TORNEO_LARGO->LinkCustomAttributes = "";
        $this->NOM_TORNEO_LARGO->HrefValue = "";
        $this->NOM_TORNEO_LARGO->TooltipValue = "";

        // PAIS_TORNEO
        $this->PAIS_TORNEO->LinkCustomAttributes = "";
        $this->PAIS_TORNEO->HrefValue = "";
        $this->PAIS_TORNEO->TooltipValue = "";

        // REGION_TORNEO
        $this->REGION_TORNEO->LinkCustomAttributes = "";
        $this->REGION_TORNEO->HrefValue = "";
        $this->REGION_TORNEO->TooltipValue = "";

        // DETALLE_TORNEO
        $this->DETALLE_TORNEO->LinkCustomAttributes = "";
        $this->DETALLE_TORNEO->HrefValue = "";
        $this->DETALLE_TORNEO->TooltipValue = "";

        // LOGO_TORNEO
        $this->LOGO_TORNEO->LinkCustomAttributes = "";
        if (!empty($this->LOGO_TORNEO->Upload->DbValue)) {
            $this->LOGO_TORNEO->HrefValue = GetFileUploadUrl($this->LOGO_TORNEO, $this->ID_TORNEO->CurrentValue);
            $this->LOGO_TORNEO->LinkAttrs["target"] = "";
            if ($this->LOGO_TORNEO->IsBlobImage && empty($this->LOGO_TORNEO->LinkAttrs["target"])) {
                $this->LOGO_TORNEO->LinkAttrs["target"] = "_blank";
            }
            if ($this->isExport()) {
                $this->LOGO_TORNEO->HrefValue = FullUrl($this->LOGO_TORNEO->HrefValue, "href");
            }
        } else {
            $this->LOGO_TORNEO->HrefValue = "";
        }
        $this->LOGO_TORNEO->ExportHrefValue = GetFileUploadUrl($this->LOGO_TORNEO, $this->ID_TORNEO->CurrentValue);
        $this->LOGO_TORNEO->TooltipValue = "";
        if ($this->LOGO_TORNEO->UseColorbox) {
            if (EmptyValue($this->LOGO_TORNEO->TooltipValue)) {
                $this->LOGO_TORNEO->LinkAttrs["title"] = $Language->phrase("ViewImageGallery");
            }
            $this->LOGO_TORNEO->LinkAttrs["data-rel"] = "torneo_x_LOGO_TORNEO";
            $this->LOGO_TORNEO->LinkAttrs->appendClass("ew-lightbox");
        }

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

        // ID_TORNEO
        $this->ID_TORNEO->setupEditAttributes();
        $this->ID_TORNEO->EditCustomAttributes = "";
        $this->ID_TORNEO->EditValue = $this->ID_TORNEO->CurrentValue;
        $this->ID_TORNEO->ViewCustomAttributes = "";

        // NOM_TORNEO_CORTO
        $this->NOM_TORNEO_CORTO->setupEditAttributes();
        $this->NOM_TORNEO_CORTO->EditCustomAttributes = "";
        $this->NOM_TORNEO_CORTO->EditValue = $this->NOM_TORNEO_CORTO->CurrentValue;
        $this->NOM_TORNEO_CORTO->PlaceHolder = RemoveHtml($this->NOM_TORNEO_CORTO->caption());

        // NOM_TORNEO_LARGO
        $this->NOM_TORNEO_LARGO->setupEditAttributes();
        $this->NOM_TORNEO_LARGO->EditCustomAttributes = "";
        $this->NOM_TORNEO_LARGO->EditValue = $this->NOM_TORNEO_LARGO->CurrentValue;
        $this->NOM_TORNEO_LARGO->PlaceHolder = RemoveHtml($this->NOM_TORNEO_LARGO->caption());

        // PAIS_TORNEO
        $this->PAIS_TORNEO->setupEditAttributes();
        $this->PAIS_TORNEO->EditCustomAttributes = "";
        $this->PAIS_TORNEO->EditValue = $this->PAIS_TORNEO->CurrentValue;
        $this->PAIS_TORNEO->PlaceHolder = RemoveHtml($this->PAIS_TORNEO->caption());

        // REGION_TORNEO
        $this->REGION_TORNEO->setupEditAttributes();
        $this->REGION_TORNEO->EditCustomAttributes = "";
        $this->REGION_TORNEO->EditValue = $this->REGION_TORNEO->CurrentValue;
        $this->REGION_TORNEO->PlaceHolder = RemoveHtml($this->REGION_TORNEO->caption());

        // DETALLE_TORNEO
        $this->DETALLE_TORNEO->setupEditAttributes();
        $this->DETALLE_TORNEO->EditCustomAttributes = "";
        $this->DETALLE_TORNEO->EditValue = $this->DETALLE_TORNEO->CurrentValue;
        $this->DETALLE_TORNEO->PlaceHolder = RemoveHtml($this->DETALLE_TORNEO->caption());

        // LOGO_TORNEO
        $this->LOGO_TORNEO->setupEditAttributes();
        $this->LOGO_TORNEO->EditCustomAttributes = "";
        if (!EmptyValue($this->LOGO_TORNEO->Upload->DbValue)) {
            $this->LOGO_TORNEO->ImageAlt = $this->LOGO_TORNEO->alt();
            $this->LOGO_TORNEO->ImageCssClass = "ew-image";
            $this->LOGO_TORNEO->EditValue = $this->ID_TORNEO->CurrentValue;
            $this->LOGO_TORNEO->IsBlobImage = IsImageFile(ContentExtension($this->LOGO_TORNEO->Upload->DbValue));
        } else {
            $this->LOGO_TORNEO->EditValue = "";
        }

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
                    $doc->exportCaption($this->ID_TORNEO);
                    $doc->exportCaption($this->NOM_TORNEO_CORTO);
                    $doc->exportCaption($this->NOM_TORNEO_LARGO);
                    $doc->exportCaption($this->PAIS_TORNEO);
                    $doc->exportCaption($this->REGION_TORNEO);
                    $doc->exportCaption($this->DETALLE_TORNEO);
                    $doc->exportCaption($this->LOGO_TORNEO);
                } else {
                    $doc->exportCaption($this->ID_TORNEO);
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
                        $doc->exportField($this->ID_TORNEO);
                        $doc->exportField($this->NOM_TORNEO_CORTO);
                        $doc->exportField($this->NOM_TORNEO_LARGO);
                        $doc->exportField($this->PAIS_TORNEO);
                        $doc->exportField($this->REGION_TORNEO);
                        $doc->exportField($this->DETALLE_TORNEO);
                        $doc->exportField($this->LOGO_TORNEO);
                    } else {
                        $doc->exportField($this->ID_TORNEO);
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
        if ($fldparm == 'LOGO_TORNEO') {
            $fldName = "LOGO_TORNEO";
        } else {
            return false; // Incorrect field
        }

        // Set up key values
        $ar = explode(Config("COMPOSITE_KEY_SEPARATOR"), $key);
        if (count($ar) == 1) {
            $this->ID_TORNEO->CurrentValue = $ar[0];
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
