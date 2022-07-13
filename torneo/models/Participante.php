<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for participante
 */
class Participante extends DbTable
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
    public $ID_PARTICIPANTE;
    public $NOMBRE;
    public $APELLIDO;
    public $FECHA_NACIMIENTO;
    public $CEDULA;
    public $_EMAIL;
    public $TELEFONO;
    public $CREACION;
    public $ACTUALIZACION;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'participante';
        $this->TableName = 'participante';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`participante`";
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

        // ID_PARTICIPANTE
        $this->ID_PARTICIPANTE = new DbField(
            'participante',
            'participante',
            'x_ID_PARTICIPANTE',
            'ID_PARTICIPANTE',
            '`ID_PARTICIPANTE`',
            '`ID_PARTICIPANTE`',
            3,
            11,
            -1,
            false,
            '`ID_PARTICIPANTE`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->ID_PARTICIPANTE->InputTextType = "text";
        $this->ID_PARTICIPANTE->IsAutoIncrement = true; // Autoincrement field
        $this->ID_PARTICIPANTE->IsPrimaryKey = true; // Primary key field
        $this->ID_PARTICIPANTE->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['ID_PARTICIPANTE'] = &$this->ID_PARTICIPANTE;

        // NOMBRE
        $this->NOMBRE = new DbField(
            'participante',
            'participante',
            'x_NOMBRE',
            'NOMBRE',
            '`NOMBRE`',
            '`NOMBRE`',
            201,
            256,
            -1,
            false,
            '`NOMBRE`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->NOMBRE->InputTextType = "text";
        $this->Fields['NOMBRE'] = &$this->NOMBRE;

        // APELLIDO
        $this->APELLIDO = new DbField(
            'participante',
            'participante',
            'x_APELLIDO',
            'APELLIDO',
            '`APELLIDO`',
            '`APELLIDO`',
            201,
            256,
            -1,
            false,
            '`APELLIDO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->APELLIDO->InputTextType = "text";
        $this->Fields['APELLIDO'] = &$this->APELLIDO;

        // FECHA_NACIMIENTO
        $this->FECHA_NACIMIENTO = new DbField(
            'participante',
            'participante',
            'x_FECHA_NACIMIENTO',
            'FECHA_NACIMIENTO',
            '`FECHA_NACIMIENTO`',
            '`FECHA_NACIMIENTO`',
            201,
            256,
            -1,
            false,
            '`FECHA_NACIMIENTO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->FECHA_NACIMIENTO->InputTextType = "text";
        $this->Fields['FECHA_NACIMIENTO'] = &$this->FECHA_NACIMIENTO;

        // CEDULA
        $this->CEDULA = new DbField(
            'participante',
            'participante',
            'x_CEDULA',
            'CEDULA',
            '`CEDULA`',
            '`CEDULA`',
            200,
            10,
            -1,
            false,
            '`CEDULA`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->CEDULA->InputTextType = "text";
        $this->Fields['CEDULA'] = &$this->CEDULA;

        // EMAIL
        $this->_EMAIL = new DbField(
            'participante',
            'participante',
            'x__EMAIL',
            'EMAIL',
            '`EMAIL`',
            '`EMAIL`',
            201,
            256,
            -1,
            false,
            '`EMAIL`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->_EMAIL->InputTextType = "text";
        $this->Fields['EMAIL'] = &$this->_EMAIL;

        // TELEFONO
        $this->TELEFONO = new DbField(
            'participante',
            'participante',
            'x_TELEFONO',
            'TELEFONO',
            '`TELEFONO`',
            '`TELEFONO`',
            200,
            10,
            -1,
            false,
            '`TELEFONO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->TELEFONO->InputTextType = "text";
        $this->Fields['TELEFONO'] = &$this->TELEFONO;

        // CREACION
        $this->CREACION = new DbField(
            'participante',
            'participante',
            'x_CREACION',
            'CREACION',
            '`CREACION`',
            '`CREACION`',
            201,
            256,
            -1,
            false,
            '`CREACION`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->CREACION->InputTextType = "text";
        $this->Fields['CREACION'] = &$this->CREACION;

        // ACTUALIZACION
        $this->ACTUALIZACION = new DbField(
            'participante',
            'participante',
            'x_ACTUALIZACION',
            'ACTUALIZACION',
            '`ACTUALIZACION`',
            '`ACTUALIZACION`',
            201,
            256,
            -1,
            false,
            '`ACTUALIZACION`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->ACTUALIZACION->InputTextType = "text";
        $this->Fields['ACTUALIZACION'] = &$this->ACTUALIZACION;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`participante`";
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
            $this->ID_PARTICIPANTE->setDbValue($conn->lastInsertId());
            $rs['ID_PARTICIPANTE'] = $this->ID_PARTICIPANTE->DbValue;
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
            if (array_key_exists('ID_PARTICIPANTE', $rs)) {
                AddFilter($where, QuotedName('ID_PARTICIPANTE', $this->Dbid) . '=' . QuotedValue($rs['ID_PARTICIPANTE'], $this->ID_PARTICIPANTE->DataType, $this->Dbid));
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
        $this->ID_PARTICIPANTE->DbValue = $row['ID_PARTICIPANTE'];
        $this->NOMBRE->DbValue = $row['NOMBRE'];
        $this->APELLIDO->DbValue = $row['APELLIDO'];
        $this->FECHA_NACIMIENTO->DbValue = $row['FECHA_NACIMIENTO'];
        $this->CEDULA->DbValue = $row['CEDULA'];
        $this->_EMAIL->DbValue = $row['EMAIL'];
        $this->TELEFONO->DbValue = $row['TELEFONO'];
        $this->CREACION->DbValue = $row['CREACION'];
        $this->ACTUALIZACION->DbValue = $row['ACTUALIZACION'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`ID_PARTICIPANTE` = @ID_PARTICIPANTE@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->ID_PARTICIPANTE->CurrentValue : $this->ID_PARTICIPANTE->OldValue;
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
                $this->ID_PARTICIPANTE->CurrentValue = $keys[0];
            } else {
                $this->ID_PARTICIPANTE->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('ID_PARTICIPANTE', $row) ? $row['ID_PARTICIPANTE'] : null;
        } else {
            $val = $this->ID_PARTICIPANTE->OldValue !== null ? $this->ID_PARTICIPANTE->OldValue : $this->ID_PARTICIPANTE->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@ID_PARTICIPANTE@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("ParticipanteList");
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
        if ($pageName == "ParticipanteView") {
            return $Language->phrase("View");
        } elseif ($pageName == "ParticipanteEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "ParticipanteAdd") {
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
                return "ParticipanteView";
            case Config("API_ADD_ACTION"):
                return "ParticipanteAdd";
            case Config("API_EDIT_ACTION"):
                return "ParticipanteEdit";
            case Config("API_DELETE_ACTION"):
                return "ParticipanteDelete";
            case Config("API_LIST_ACTION"):
                return "ParticipanteList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "ParticipanteList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("ParticipanteView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("ParticipanteView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "ParticipanteAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "ParticipanteAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("ParticipanteEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("ParticipanteAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("ParticipanteDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"ID_PARTICIPANTE\":" . JsonEncode($this->ID_PARTICIPANTE->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->ID_PARTICIPANTE->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->ID_PARTICIPANTE->CurrentValue);
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
            if (($keyValue = Param("ID_PARTICIPANTE") ?? Route("ID_PARTICIPANTE")) !== null) {
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
                $this->ID_PARTICIPANTE->CurrentValue = $key;
            } else {
                $this->ID_PARTICIPANTE->OldValue = $key;
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
        $this->ID_PARTICIPANTE->setDbValue($row['ID_PARTICIPANTE']);
        $this->NOMBRE->setDbValue($row['NOMBRE']);
        $this->APELLIDO->setDbValue($row['APELLIDO']);
        $this->FECHA_NACIMIENTO->setDbValue($row['FECHA_NACIMIENTO']);
        $this->CEDULA->setDbValue($row['CEDULA']);
        $this->_EMAIL->setDbValue($row['EMAIL']);
        $this->TELEFONO->setDbValue($row['TELEFONO']);
        $this->CREACION->setDbValue($row['CREACION']);
        $this->ACTUALIZACION->setDbValue($row['ACTUALIZACION']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // ID_PARTICIPANTE

        // NOMBRE

        // APELLIDO

        // FECHA_NACIMIENTO

        // CEDULA

        // EMAIL

        // TELEFONO

        // CREACION

        // ACTUALIZACION

        // ID_PARTICIPANTE
        $this->ID_PARTICIPANTE->ViewValue = $this->ID_PARTICIPANTE->CurrentValue;
        $this->ID_PARTICIPANTE->ViewCustomAttributes = "";

        // NOMBRE
        $this->NOMBRE->ViewValue = $this->NOMBRE->CurrentValue;
        $this->NOMBRE->ViewCustomAttributes = "";

        // APELLIDO
        $this->APELLIDO->ViewValue = $this->APELLIDO->CurrentValue;
        $this->APELLIDO->ViewCustomAttributes = "";

        // FECHA_NACIMIENTO
        $this->FECHA_NACIMIENTO->ViewValue = $this->FECHA_NACIMIENTO->CurrentValue;
        $this->FECHA_NACIMIENTO->ViewCustomAttributes = "";

        // CEDULA
        $this->CEDULA->ViewValue = $this->CEDULA->CurrentValue;
        $this->CEDULA->ViewCustomAttributes = "";

        // EMAIL
        $this->_EMAIL->ViewValue = $this->_EMAIL->CurrentValue;
        $this->_EMAIL->ViewCustomAttributes = "";

        // TELEFONO
        $this->TELEFONO->ViewValue = $this->TELEFONO->CurrentValue;
        $this->TELEFONO->ViewCustomAttributes = "";

        // CREACION
        $this->CREACION->ViewValue = $this->CREACION->CurrentValue;
        $this->CREACION->ViewCustomAttributes = "";

        // ACTUALIZACION
        $this->ACTUALIZACION->ViewValue = $this->ACTUALIZACION->CurrentValue;
        $this->ACTUALIZACION->ViewCustomAttributes = "";

        // ID_PARTICIPANTE
        $this->ID_PARTICIPANTE->LinkCustomAttributes = "";
        $this->ID_PARTICIPANTE->HrefValue = "";
        $this->ID_PARTICIPANTE->TooltipValue = "";

        // NOMBRE
        $this->NOMBRE->LinkCustomAttributes = "";
        $this->NOMBRE->HrefValue = "";
        $this->NOMBRE->TooltipValue = "";

        // APELLIDO
        $this->APELLIDO->LinkCustomAttributes = "";
        $this->APELLIDO->HrefValue = "";
        $this->APELLIDO->TooltipValue = "";

        // FECHA_NACIMIENTO
        $this->FECHA_NACIMIENTO->LinkCustomAttributes = "";
        $this->FECHA_NACIMIENTO->HrefValue = "";
        $this->FECHA_NACIMIENTO->TooltipValue = "";

        // CEDULA
        $this->CEDULA->LinkCustomAttributes = "";
        $this->CEDULA->HrefValue = "";
        $this->CEDULA->TooltipValue = "";

        // EMAIL
        $this->_EMAIL->LinkCustomAttributes = "";
        $this->_EMAIL->HrefValue = "";
        $this->_EMAIL->TooltipValue = "";

        // TELEFONO
        $this->TELEFONO->LinkCustomAttributes = "";
        $this->TELEFONO->HrefValue = "";
        $this->TELEFONO->TooltipValue = "";

        // CREACION
        $this->CREACION->LinkCustomAttributes = "";
        $this->CREACION->HrefValue = "";
        $this->CREACION->TooltipValue = "";

        // ACTUALIZACION
        $this->ACTUALIZACION->LinkCustomAttributes = "";
        $this->ACTUALIZACION->HrefValue = "";
        $this->ACTUALIZACION->TooltipValue = "";

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

        // ID_PARTICIPANTE
        $this->ID_PARTICIPANTE->setupEditAttributes();
        $this->ID_PARTICIPANTE->EditCustomAttributes = "";
        $this->ID_PARTICIPANTE->EditValue = $this->ID_PARTICIPANTE->CurrentValue;
        $this->ID_PARTICIPANTE->ViewCustomAttributes = "";

        // NOMBRE
        $this->NOMBRE->setupEditAttributes();
        $this->NOMBRE->EditCustomAttributes = "";
        $this->NOMBRE->EditValue = $this->NOMBRE->CurrentValue;
        $this->NOMBRE->PlaceHolder = RemoveHtml($this->NOMBRE->caption());

        // APELLIDO
        $this->APELLIDO->setupEditAttributes();
        $this->APELLIDO->EditCustomAttributes = "";
        $this->APELLIDO->EditValue = $this->APELLIDO->CurrentValue;
        $this->APELLIDO->PlaceHolder = RemoveHtml($this->APELLIDO->caption());

        // FECHA_NACIMIENTO
        $this->FECHA_NACIMIENTO->setupEditAttributes();
        $this->FECHA_NACIMIENTO->EditCustomAttributes = "";
        $this->FECHA_NACIMIENTO->EditValue = $this->FECHA_NACIMIENTO->CurrentValue;
        $this->FECHA_NACIMIENTO->PlaceHolder = RemoveHtml($this->FECHA_NACIMIENTO->caption());

        // CEDULA
        $this->CEDULA->setupEditAttributes();
        $this->CEDULA->EditCustomAttributes = "";
        if (!$this->CEDULA->Raw) {
            $this->CEDULA->CurrentValue = HtmlDecode($this->CEDULA->CurrentValue);
        }
        $this->CEDULA->EditValue = $this->CEDULA->CurrentValue;
        $this->CEDULA->PlaceHolder = RemoveHtml($this->CEDULA->caption());

        // EMAIL
        $this->_EMAIL->setupEditAttributes();
        $this->_EMAIL->EditCustomAttributes = "";
        $this->_EMAIL->EditValue = $this->_EMAIL->CurrentValue;
        $this->_EMAIL->PlaceHolder = RemoveHtml($this->_EMAIL->caption());

        // TELEFONO
        $this->TELEFONO->setupEditAttributes();
        $this->TELEFONO->EditCustomAttributes = "";
        if (!$this->TELEFONO->Raw) {
            $this->TELEFONO->CurrentValue = HtmlDecode($this->TELEFONO->CurrentValue);
        }
        $this->TELEFONO->EditValue = $this->TELEFONO->CurrentValue;
        $this->TELEFONO->PlaceHolder = RemoveHtml($this->TELEFONO->caption());

        // CREACION
        $this->CREACION->setupEditAttributes();
        $this->CREACION->EditCustomAttributes = "";
        $this->CREACION->EditValue = $this->CREACION->CurrentValue;
        $this->CREACION->PlaceHolder = RemoveHtml($this->CREACION->caption());

        // ACTUALIZACION
        $this->ACTUALIZACION->setupEditAttributes();
        $this->ACTUALIZACION->EditCustomAttributes = "";
        $this->ACTUALIZACION->EditValue = $this->ACTUALIZACION->CurrentValue;
        $this->ACTUALIZACION->PlaceHolder = RemoveHtml($this->ACTUALIZACION->caption());

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
                    $doc->exportCaption($this->ID_PARTICIPANTE);
                    $doc->exportCaption($this->NOMBRE);
                    $doc->exportCaption($this->APELLIDO);
                    $doc->exportCaption($this->FECHA_NACIMIENTO);
                    $doc->exportCaption($this->CEDULA);
                    $doc->exportCaption($this->_EMAIL);
                    $doc->exportCaption($this->TELEFONO);
                    $doc->exportCaption($this->CREACION);
                    $doc->exportCaption($this->ACTUALIZACION);
                } else {
                    $doc->exportCaption($this->ID_PARTICIPANTE);
                    $doc->exportCaption($this->CEDULA);
                    $doc->exportCaption($this->TELEFONO);
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
                        $doc->exportField($this->ID_PARTICIPANTE);
                        $doc->exportField($this->NOMBRE);
                        $doc->exportField($this->APELLIDO);
                        $doc->exportField($this->FECHA_NACIMIENTO);
                        $doc->exportField($this->CEDULA);
                        $doc->exportField($this->_EMAIL);
                        $doc->exportField($this->TELEFONO);
                        $doc->exportField($this->CREACION);
                        $doc->exportField($this->ACTUALIZACION);
                    } else {
                        $doc->exportField($this->ID_PARTICIPANTE);
                        $doc->exportField($this->CEDULA);
                        $doc->exportField($this->TELEFONO);
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
