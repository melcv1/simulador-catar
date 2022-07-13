<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Table class for equipotorneo
 */
class Equipotorneo extends DbTable
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
    public $ID_EQUIPO_TORNEO;
    public $ID_TORNEO;
    public $ID_EQUIPO;
    public $PARTIDOS_JUGADOS;
    public $PARTIDOS_GANADOS;
    public $PARTIDOS_EMPATADOS;
    public $PARTIDOS_PERDIDOS;
    public $GF;
    public $GC;
    public $GD;
    public $GRUPO;
    public $POSICION_EQUIPO_TORENO;

    // Page ID
    public $PageID = ""; // To be overridden by subclass

    // Constructor
    public function __construct()
    {
        global $Language, $CurrentLanguage, $CurrentLocale;
        parent::__construct();

        // Language object
        $Language = Container("language");
        $this->TableVar = 'equipotorneo';
        $this->TableName = 'equipotorneo';
        $this->TableType = 'TABLE';

        // Update Table
        $this->UpdateTable = "`equipotorneo`";
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

        // ID_EQUIPO_TORNEO
        $this->ID_EQUIPO_TORNEO = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_ID_EQUIPO_TORNEO',
            'ID_EQUIPO_TORNEO',
            '`ID_EQUIPO_TORNEO`',
            '`ID_EQUIPO_TORNEO`',
            3,
            11,
            -1,
            false,
            '`ID_EQUIPO_TORNEO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'NO'
        );
        $this->ID_EQUIPO_TORNEO->InputTextType = "text";
        $this->ID_EQUIPO_TORNEO->IsAutoIncrement = true; // Autoincrement field
        $this->ID_EQUIPO_TORNEO->IsPrimaryKey = true; // Primary key field
        $this->ID_EQUIPO_TORNEO->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['ID_EQUIPO_TORNEO'] = &$this->ID_EQUIPO_TORNEO;

        // ID_TORNEO
        $this->ID_TORNEO = new DbField(
            'equipotorneo',
            'equipotorneo',
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
            'SELECT'
        );
        $this->ID_TORNEO->InputTextType = "text";
        $this->ID_TORNEO->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->ID_TORNEO->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en-US":
                $this->ID_TORNEO->Lookup = new Lookup('ID_TORNEO', 'torneo', false, 'ID_TORNEO', ["NOM_TORNEO_CORTO","","",""], [], [], [], [], [], [], '', '', "`NOM_TORNEO_CORTO`");
                break;
            default:
                $this->ID_TORNEO->Lookup = new Lookup('ID_TORNEO', 'torneo', false, 'ID_TORNEO', ["NOM_TORNEO_CORTO","","",""], [], [], [], [], [], [], '', '', "`NOM_TORNEO_CORTO`");
                break;
        }
        $this->ID_TORNEO->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['ID_TORNEO'] = &$this->ID_TORNEO;

        // ID_EQUIPO
        $this->ID_EQUIPO = new DbField(
            'equipotorneo',
            'equipotorneo',
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
            'SELECT'
        );
        $this->ID_EQUIPO->InputTextType = "text";
        $this->ID_EQUIPO->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->ID_EQUIPO->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en-US":
                $this->ID_EQUIPO->Lookup = new Lookup('ID_EQUIPO', 'equipo', false, 'ID_EQUIPO', ["NOM_EQUIPO_LARGO","","",""], [], [], [], [], [], [], '', '', "`NOM_EQUIPO_LARGO`");
                break;
            default:
                $this->ID_EQUIPO->Lookup = new Lookup('ID_EQUIPO', 'equipo', false, 'ID_EQUIPO', ["NOM_EQUIPO_LARGO","","",""], [], [], [], [], [], [], '', '', "`NOM_EQUIPO_LARGO`");
                break;
        }
        $this->ID_EQUIPO->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['ID_EQUIPO'] = &$this->ID_EQUIPO;

        // PARTIDOS_JUGADOS
        $this->PARTIDOS_JUGADOS = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_PARTIDOS_JUGADOS',
            'PARTIDOS_JUGADOS',
            '`PARTIDOS_JUGADOS`',
            '`PARTIDOS_JUGADOS`',
            3,
            11,
            -1,
            false,
            '`PARTIDOS_JUGADOS`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->PARTIDOS_JUGADOS->InputTextType = "text";
        $this->PARTIDOS_JUGADOS->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['PARTIDOS_JUGADOS'] = &$this->PARTIDOS_JUGADOS;

        // PARTIDOS_GANADOS
        $this->PARTIDOS_GANADOS = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_PARTIDOS_GANADOS',
            'PARTIDOS_GANADOS',
            '`PARTIDOS_GANADOS`',
            '`PARTIDOS_GANADOS`',
            3,
            11,
            -1,
            false,
            '`PARTIDOS_GANADOS`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->PARTIDOS_GANADOS->InputTextType = "text";
        $this->PARTIDOS_GANADOS->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['PARTIDOS_GANADOS'] = &$this->PARTIDOS_GANADOS;

        // PARTIDOS_EMPATADOS
        $this->PARTIDOS_EMPATADOS = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_PARTIDOS_EMPATADOS',
            'PARTIDOS_EMPATADOS',
            '`PARTIDOS_EMPATADOS`',
            '`PARTIDOS_EMPATADOS`',
            3,
            11,
            -1,
            false,
            '`PARTIDOS_EMPATADOS`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->PARTIDOS_EMPATADOS->InputTextType = "text";
        $this->PARTIDOS_EMPATADOS->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['PARTIDOS_EMPATADOS'] = &$this->PARTIDOS_EMPATADOS;

        // PARTIDOS_PERDIDOS
        $this->PARTIDOS_PERDIDOS = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_PARTIDOS_PERDIDOS',
            'PARTIDOS_PERDIDOS',
            '`PARTIDOS_PERDIDOS`',
            '`PARTIDOS_PERDIDOS`',
            3,
            11,
            -1,
            false,
            '`PARTIDOS_PERDIDOS`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->PARTIDOS_PERDIDOS->InputTextType = "text";
        $this->PARTIDOS_PERDIDOS->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['PARTIDOS_PERDIDOS'] = &$this->PARTIDOS_PERDIDOS;

        // GF
        $this->GF = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_GF',
            'GF',
            '`GF`',
            '`GF`',
            3,
            11,
            -1,
            false,
            '`GF`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->GF->InputTextType = "text";
        $this->GF->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['GF'] = &$this->GF;

        // GC
        $this->GC = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_GC',
            'GC',
            '`GC`',
            '`GC`',
            3,
            11,
            -1,
            false,
            '`GC`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->GC->InputTextType = "text";
        $this->GC->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['GC'] = &$this->GC;

        // GD
        $this->GD = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_GD',
            'GD',
            '`GD`',
            '`GD`',
            3,
            11,
            -1,
            false,
            '`GD`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXT'
        );
        $this->GD->InputTextType = "text";
        $this->GD->DefaultErrorMessage = $Language->phrase("IncorrectInteger");
        $this->Fields['GD'] = &$this->GD;

        // GRUPO
        $this->GRUPO = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_GRUPO',
            'GRUPO',
            '`GRUPO`',
            '`GRUPO`',
            201,
            256,
            -1,
            false,
            '`GRUPO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'SELECT'
        );
        $this->GRUPO->InputTextType = "text";
        $this->GRUPO->UsePleaseSelect = true; // Use PleaseSelect by default
        $this->GRUPO->PleaseSelectText = $Language->phrase("PleaseSelect"); // "PleaseSelect" text
        switch ($CurrentLanguage) {
            case "en-US":
                $this->GRUPO->Lookup = new Lookup('GRUPO', 'equipotorneo', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
                break;
            default:
                $this->GRUPO->Lookup = new Lookup('GRUPO', 'equipotorneo', false, '', ["","","",""], [], [], [], [], [], [], '', '', "");
                break;
        }
        $this->GRUPO->OptionCount = 8;
        $this->Fields['GRUPO'] = &$this->GRUPO;

        // POSICION_EQUIPO_TORENO
        $this->POSICION_EQUIPO_TORENO = new DbField(
            'equipotorneo',
            'equipotorneo',
            'x_POSICION_EQUIPO_TORENO',
            'POSICION_EQUIPO_TORENO',
            '`POSICION_EQUIPO_TORENO`',
            '`POSICION_EQUIPO_TORENO`',
            201,
            256,
            -1,
            false,
            '`POSICION_EQUIPO_TORENO`',
            false,
            false,
            false,
            'FORMATTED TEXT',
            'TEXTAREA'
        );
        $this->POSICION_EQUIPO_TORENO->InputTextType = "text";
        $this->Fields['POSICION_EQUIPO_TORENO'] = &$this->POSICION_EQUIPO_TORENO;

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
        return ($this->SqlFrom != "") ? $this->SqlFrom : "`equipotorneo`";
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
            $this->ID_EQUIPO_TORNEO->setDbValue($conn->lastInsertId());
            $rs['ID_EQUIPO_TORNEO'] = $this->ID_EQUIPO_TORNEO->DbValue;
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
            if (array_key_exists('ID_EQUIPO_TORNEO', $rs)) {
                AddFilter($where, QuotedName('ID_EQUIPO_TORNEO', $this->Dbid) . '=' . QuotedValue($rs['ID_EQUIPO_TORNEO'], $this->ID_EQUIPO_TORNEO->DataType, $this->Dbid));
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
        $this->ID_EQUIPO_TORNEO->DbValue = $row['ID_EQUIPO_TORNEO'];
        $this->ID_TORNEO->DbValue = $row['ID_TORNEO'];
        $this->ID_EQUIPO->DbValue = $row['ID_EQUIPO'];
        $this->PARTIDOS_JUGADOS->DbValue = $row['PARTIDOS_JUGADOS'];
        $this->PARTIDOS_GANADOS->DbValue = $row['PARTIDOS_GANADOS'];
        $this->PARTIDOS_EMPATADOS->DbValue = $row['PARTIDOS_EMPATADOS'];
        $this->PARTIDOS_PERDIDOS->DbValue = $row['PARTIDOS_PERDIDOS'];
        $this->GF->DbValue = $row['GF'];
        $this->GC->DbValue = $row['GC'];
        $this->GD->DbValue = $row['GD'];
        $this->GRUPO->DbValue = $row['GRUPO'];
        $this->POSICION_EQUIPO_TORENO->DbValue = $row['POSICION_EQUIPO_TORENO'];
    }

    // Delete uploaded files
    public function deleteUploadedFiles($row)
    {
        $this->loadDbValues($row);
    }

    // Record filter WHERE clause
    protected function sqlKeyFilter()
    {
        return "`ID_EQUIPO_TORNEO` = @ID_EQUIPO_TORNEO@";
    }

    // Get Key
    public function getKey($current = false)
    {
        $keys = [];
        $val = $current ? $this->ID_EQUIPO_TORNEO->CurrentValue : $this->ID_EQUIPO_TORNEO->OldValue;
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
                $this->ID_EQUIPO_TORNEO->CurrentValue = $keys[0];
            } else {
                $this->ID_EQUIPO_TORNEO->OldValue = $keys[0];
            }
        }
    }

    // Get record filter
    public function getRecordFilter($row = null)
    {
        $keyFilter = $this->sqlKeyFilter();
        if (is_array($row)) {
            $val = array_key_exists('ID_EQUIPO_TORNEO', $row) ? $row['ID_EQUIPO_TORNEO'] : null;
        } else {
            $val = $this->ID_EQUIPO_TORNEO->OldValue !== null ? $this->ID_EQUIPO_TORNEO->OldValue : $this->ID_EQUIPO_TORNEO->CurrentValue;
        }
        if (!is_numeric($val)) {
            return "0=1"; // Invalid key
        }
        if ($val === null) {
            return "0=1"; // Invalid key
        } else {
            $keyFilter = str_replace("@ID_EQUIPO_TORNEO@", AdjustSql($val, $this->Dbid), $keyFilter); // Replace key value
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
        return $_SESSION[$name] ?? GetUrl("EquipotorneoList");
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
        if ($pageName == "EquipotorneoView") {
            return $Language->phrase("View");
        } elseif ($pageName == "EquipotorneoEdit") {
            return $Language->phrase("Edit");
        } elseif ($pageName == "EquipotorneoAdd") {
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
                return "EquipotorneoView";
            case Config("API_ADD_ACTION"):
                return "EquipotorneoAdd";
            case Config("API_EDIT_ACTION"):
                return "EquipotorneoEdit";
            case Config("API_DELETE_ACTION"):
                return "EquipotorneoDelete";
            case Config("API_LIST_ACTION"):
                return "EquipotorneoList";
            default:
                return "";
        }
    }

    // List URL
    public function getListUrl()
    {
        return "EquipotorneoList";
    }

    // View URL
    public function getViewUrl($parm = "")
    {
        if ($parm != "") {
            $url = $this->keyUrl("EquipotorneoView", $this->getUrlParm($parm));
        } else {
            $url = $this->keyUrl("EquipotorneoView", $this->getUrlParm(Config("TABLE_SHOW_DETAIL") . "="));
        }
        return $this->addMasterUrl($url);
    }

    // Add URL
    public function getAddUrl($parm = "")
    {
        if ($parm != "") {
            $url = "EquipotorneoAdd?" . $this->getUrlParm($parm);
        } else {
            $url = "EquipotorneoAdd";
        }
        return $this->addMasterUrl($url);
    }

    // Edit URL
    public function getEditUrl($parm = "")
    {
        $url = $this->keyUrl("EquipotorneoEdit", $this->getUrlParm($parm));
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
        $url = $this->keyUrl("EquipotorneoAdd", $this->getUrlParm($parm));
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
        return $this->keyUrl("EquipotorneoDelete", $this->getUrlParm());
    }

    // Add master url
    public function addMasterUrl($url)
    {
        return $url;
    }

    public function keyToJson($htmlEncode = false)
    {
        $json = "";
        $json .= "\"ID_EQUIPO_TORNEO\":" . JsonEncode($this->ID_EQUIPO_TORNEO->CurrentValue, "number");
        $json = "{" . $json . "}";
        if ($htmlEncode) {
            $json = HtmlEncode($json);
        }
        return $json;
    }

    // Add key value to URL
    public function keyUrl($url, $parm = "")
    {
        if ($this->ID_EQUIPO_TORNEO->CurrentValue !== null) {
            $url .= "/" . $this->encodeKeyValue($this->ID_EQUIPO_TORNEO->CurrentValue);
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
            if (($keyValue = Param("ID_EQUIPO_TORNEO") ?? Route("ID_EQUIPO_TORNEO")) !== null) {
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
                $this->ID_EQUIPO_TORNEO->CurrentValue = $key;
            } else {
                $this->ID_EQUIPO_TORNEO->OldValue = $key;
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
        $this->ID_EQUIPO_TORNEO->setDbValue($row['ID_EQUIPO_TORNEO']);
        $this->ID_TORNEO->setDbValue($row['ID_TORNEO']);
        $this->ID_EQUIPO->setDbValue($row['ID_EQUIPO']);
        $this->PARTIDOS_JUGADOS->setDbValue($row['PARTIDOS_JUGADOS']);
        $this->PARTIDOS_GANADOS->setDbValue($row['PARTIDOS_GANADOS']);
        $this->PARTIDOS_EMPATADOS->setDbValue($row['PARTIDOS_EMPATADOS']);
        $this->PARTIDOS_PERDIDOS->setDbValue($row['PARTIDOS_PERDIDOS']);
        $this->GF->setDbValue($row['GF']);
        $this->GC->setDbValue($row['GC']);
        $this->GD->setDbValue($row['GD']);
        $this->GRUPO->setDbValue($row['GRUPO']);
        $this->POSICION_EQUIPO_TORENO->setDbValue($row['POSICION_EQUIPO_TORENO']);
    }

    // Render list row values
    public function renderListRow()
    {
        global $Security, $CurrentLanguage, $Language;

        // Call Row Rendering event
        $this->rowRendering();

        // Common render codes

        // ID_EQUIPO_TORNEO

        // ID_TORNEO

        // ID_EQUIPO

        // PARTIDOS_JUGADOS

        // PARTIDOS_GANADOS

        // PARTIDOS_EMPATADOS

        // PARTIDOS_PERDIDOS

        // GF

        // GC

        // GD

        // GRUPO

        // POSICION_EQUIPO_TORENO

        // ID_EQUIPO_TORNEO
        $this->ID_EQUIPO_TORNEO->ViewValue = $this->ID_EQUIPO_TORNEO->CurrentValue;
        $this->ID_EQUIPO_TORNEO->ViewCustomAttributes = "";

        // ID_TORNEO
        $curVal = strval($this->ID_TORNEO->CurrentValue);
        if ($curVal != "") {
            $this->ID_TORNEO->ViewValue = $this->ID_TORNEO->lookupCacheOption($curVal);
            if ($this->ID_TORNEO->ViewValue === null) { // Lookup from database
                $filterWrk = "`ID_TORNEO`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->ID_TORNEO->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->ID_TORNEO->Lookup->renderViewRow($rswrk[0]);
                    $this->ID_TORNEO->ViewValue = $this->ID_TORNEO->displayValue($arwrk);
                } else {
                    $this->ID_TORNEO->ViewValue = FormatNumber($this->ID_TORNEO->CurrentValue, $this->ID_TORNEO->formatPattern());
                }
            }
        } else {
            $this->ID_TORNEO->ViewValue = null;
        }
        $this->ID_TORNEO->ViewCustomAttributes = "";

        // ID_EQUIPO
        $curVal = strval($this->ID_EQUIPO->CurrentValue);
        if ($curVal != "") {
            $this->ID_EQUIPO->ViewValue = $this->ID_EQUIPO->lookupCacheOption($curVal);
            if ($this->ID_EQUIPO->ViewValue === null) { // Lookup from database
                $filterWrk = "`ID_EQUIPO`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                $sqlWrk = $this->ID_EQUIPO->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                if ($ari > 0) { // Lookup values found
                    $arwrk = $this->ID_EQUIPO->Lookup->renderViewRow($rswrk[0]);
                    $this->ID_EQUIPO->ViewValue = $this->ID_EQUIPO->displayValue($arwrk);
                } else {
                    $this->ID_EQUIPO->ViewValue = FormatNumber($this->ID_EQUIPO->CurrentValue, $this->ID_EQUIPO->formatPattern());
                }
            }
        } else {
            $this->ID_EQUIPO->ViewValue = null;
        }
        $this->ID_EQUIPO->ViewCustomAttributes = "";

        // PARTIDOS_JUGADOS
        $this->PARTIDOS_JUGADOS->ViewValue = $this->PARTIDOS_JUGADOS->CurrentValue;
        $this->PARTIDOS_JUGADOS->ViewValue = FormatNumber($this->PARTIDOS_JUGADOS->ViewValue, $this->PARTIDOS_JUGADOS->formatPattern());
        $this->PARTIDOS_JUGADOS->ViewCustomAttributes = "";

        // PARTIDOS_GANADOS
        $this->PARTIDOS_GANADOS->ViewValue = $this->PARTIDOS_GANADOS->CurrentValue;
        $this->PARTIDOS_GANADOS->ViewValue = FormatNumber($this->PARTIDOS_GANADOS->ViewValue, $this->PARTIDOS_GANADOS->formatPattern());
        $this->PARTIDOS_GANADOS->ViewCustomAttributes = "";

        // PARTIDOS_EMPATADOS
        $this->PARTIDOS_EMPATADOS->ViewValue = $this->PARTIDOS_EMPATADOS->CurrentValue;
        $this->PARTIDOS_EMPATADOS->ViewValue = FormatNumber($this->PARTIDOS_EMPATADOS->ViewValue, $this->PARTIDOS_EMPATADOS->formatPattern());
        $this->PARTIDOS_EMPATADOS->ViewCustomAttributes = "";

        // PARTIDOS_PERDIDOS
        $this->PARTIDOS_PERDIDOS->ViewValue = $this->PARTIDOS_PERDIDOS->CurrentValue;
        $this->PARTIDOS_PERDIDOS->ViewValue = FormatNumber($this->PARTIDOS_PERDIDOS->ViewValue, $this->PARTIDOS_PERDIDOS->formatPattern());
        $this->PARTIDOS_PERDIDOS->ViewCustomAttributes = "";

        // GF
        $this->GF->ViewValue = $this->GF->CurrentValue;
        $this->GF->ViewValue = FormatNumber($this->GF->ViewValue, $this->GF->formatPattern());
        $this->GF->ViewCustomAttributes = "";

        // GC
        $this->GC->ViewValue = $this->GC->CurrentValue;
        $this->GC->ViewValue = FormatNumber($this->GC->ViewValue, $this->GC->formatPattern());
        $this->GC->ViewCustomAttributes = "";

        // GD
        $this->GD->ViewValue = $this->GD->CurrentValue;
        $this->GD->ViewValue = FormatNumber($this->GD->ViewValue, $this->GD->formatPattern());
        $this->GD->ViewCustomAttributes = "";

        // GRUPO
        if (strval($this->GRUPO->CurrentValue) != "") {
            $this->GRUPO->ViewValue = $this->GRUPO->optionCaption($this->GRUPO->CurrentValue);
        } else {
            $this->GRUPO->ViewValue = null;
        }
        $this->GRUPO->ViewCustomAttributes = "";

        // POSICION_EQUIPO_TORENO
        $this->POSICION_EQUIPO_TORENO->ViewValue = $this->POSICION_EQUIPO_TORENO->CurrentValue;
        $this->POSICION_EQUIPO_TORENO->ViewCustomAttributes = "";

        // ID_EQUIPO_TORNEO
        $this->ID_EQUIPO_TORNEO->LinkCustomAttributes = "";
        $this->ID_EQUIPO_TORNEO->HrefValue = "";
        $this->ID_EQUIPO_TORNEO->TooltipValue = "";

        // ID_TORNEO
        $this->ID_TORNEO->LinkCustomAttributes = "";
        $this->ID_TORNEO->HrefValue = "";
        $this->ID_TORNEO->TooltipValue = "";

        // ID_EQUIPO
        $this->ID_EQUIPO->LinkCustomAttributes = "";
        $this->ID_EQUIPO->HrefValue = "";
        $this->ID_EQUIPO->TooltipValue = "";

        // PARTIDOS_JUGADOS
        $this->PARTIDOS_JUGADOS->LinkCustomAttributes = "";
        $this->PARTIDOS_JUGADOS->HrefValue = "";
        $this->PARTIDOS_JUGADOS->TooltipValue = "";

        // PARTIDOS_GANADOS
        $this->PARTIDOS_GANADOS->LinkCustomAttributes = "";
        $this->PARTIDOS_GANADOS->HrefValue = "";
        $this->PARTIDOS_GANADOS->TooltipValue = "";

        // PARTIDOS_EMPATADOS
        $this->PARTIDOS_EMPATADOS->LinkCustomAttributes = "";
        $this->PARTIDOS_EMPATADOS->HrefValue = "";
        $this->PARTIDOS_EMPATADOS->TooltipValue = "";

        // PARTIDOS_PERDIDOS
        $this->PARTIDOS_PERDIDOS->LinkCustomAttributes = "";
        $this->PARTIDOS_PERDIDOS->HrefValue = "";
        $this->PARTIDOS_PERDIDOS->TooltipValue = "";

        // GF
        $this->GF->LinkCustomAttributes = "";
        $this->GF->HrefValue = "";
        $this->GF->TooltipValue = "";

        // GC
        $this->GC->LinkCustomAttributes = "";
        $this->GC->HrefValue = "";
        $this->GC->TooltipValue = "";

        // GD
        $this->GD->LinkCustomAttributes = "";
        $this->GD->HrefValue = "";
        $this->GD->TooltipValue = "";

        // GRUPO
        $this->GRUPO->LinkCustomAttributes = "";
        $this->GRUPO->HrefValue = "";
        $this->GRUPO->TooltipValue = "";

        // POSICION_EQUIPO_TORENO
        $this->POSICION_EQUIPO_TORENO->LinkCustomAttributes = "";
        $this->POSICION_EQUIPO_TORENO->HrefValue = "";
        $this->POSICION_EQUIPO_TORENO->TooltipValue = "";

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

        // ID_EQUIPO_TORNEO
        $this->ID_EQUIPO_TORNEO->setupEditAttributes();
        $this->ID_EQUIPO_TORNEO->EditCustomAttributes = "";
        $this->ID_EQUIPO_TORNEO->EditValue = $this->ID_EQUIPO_TORNEO->CurrentValue;
        $this->ID_EQUIPO_TORNEO->ViewCustomAttributes = "";

        // ID_TORNEO
        $this->ID_TORNEO->setupEditAttributes();
        $this->ID_TORNEO->EditCustomAttributes = "";
        $this->ID_TORNEO->PlaceHolder = RemoveHtml($this->ID_TORNEO->caption());

        // ID_EQUIPO
        $this->ID_EQUIPO->setupEditAttributes();
        $this->ID_EQUIPO->EditCustomAttributes = "";
        $this->ID_EQUIPO->PlaceHolder = RemoveHtml($this->ID_EQUIPO->caption());

        // PARTIDOS_JUGADOS
        $this->PARTIDOS_JUGADOS->setupEditAttributes();
        $this->PARTIDOS_JUGADOS->EditCustomAttributes = "";
        $this->PARTIDOS_JUGADOS->EditValue = $this->PARTIDOS_JUGADOS->CurrentValue;
        $this->PARTIDOS_JUGADOS->PlaceHolder = RemoveHtml($this->PARTIDOS_JUGADOS->caption());
        if (strval($this->PARTIDOS_JUGADOS->EditValue) != "" && is_numeric($this->PARTIDOS_JUGADOS->EditValue)) {
            $this->PARTIDOS_JUGADOS->EditValue = FormatNumber($this->PARTIDOS_JUGADOS->EditValue, null);
        }

        // PARTIDOS_GANADOS
        $this->PARTIDOS_GANADOS->setupEditAttributes();
        $this->PARTIDOS_GANADOS->EditCustomAttributes = "";
        $this->PARTIDOS_GANADOS->EditValue = $this->PARTIDOS_GANADOS->CurrentValue;
        $this->PARTIDOS_GANADOS->PlaceHolder = RemoveHtml($this->PARTIDOS_GANADOS->caption());
        if (strval($this->PARTIDOS_GANADOS->EditValue) != "" && is_numeric($this->PARTIDOS_GANADOS->EditValue)) {
            $this->PARTIDOS_GANADOS->EditValue = FormatNumber($this->PARTIDOS_GANADOS->EditValue, null);
        }

        // PARTIDOS_EMPATADOS
        $this->PARTIDOS_EMPATADOS->setupEditAttributes();
        $this->PARTIDOS_EMPATADOS->EditCustomAttributes = "";
        $this->PARTIDOS_EMPATADOS->EditValue = $this->PARTIDOS_EMPATADOS->CurrentValue;
        $this->PARTIDOS_EMPATADOS->PlaceHolder = RemoveHtml($this->PARTIDOS_EMPATADOS->caption());
        if (strval($this->PARTIDOS_EMPATADOS->EditValue) != "" && is_numeric($this->PARTIDOS_EMPATADOS->EditValue)) {
            $this->PARTIDOS_EMPATADOS->EditValue = FormatNumber($this->PARTIDOS_EMPATADOS->EditValue, null);
        }

        // PARTIDOS_PERDIDOS
        $this->PARTIDOS_PERDIDOS->setupEditAttributes();
        $this->PARTIDOS_PERDIDOS->EditCustomAttributes = "";
        $this->PARTIDOS_PERDIDOS->EditValue = $this->PARTIDOS_PERDIDOS->CurrentValue;
        $this->PARTIDOS_PERDIDOS->PlaceHolder = RemoveHtml($this->PARTIDOS_PERDIDOS->caption());
        if (strval($this->PARTIDOS_PERDIDOS->EditValue) != "" && is_numeric($this->PARTIDOS_PERDIDOS->EditValue)) {
            $this->PARTIDOS_PERDIDOS->EditValue = FormatNumber($this->PARTIDOS_PERDIDOS->EditValue, null);
        }

        // GF
        $this->GF->setupEditAttributes();
        $this->GF->EditCustomAttributes = "";
        $this->GF->EditValue = $this->GF->CurrentValue;
        $this->GF->PlaceHolder = RemoveHtml($this->GF->caption());
        if (strval($this->GF->EditValue) != "" && is_numeric($this->GF->EditValue)) {
            $this->GF->EditValue = FormatNumber($this->GF->EditValue, null);
        }

        // GC
        $this->GC->setupEditAttributes();
        $this->GC->EditCustomAttributes = "";
        $this->GC->EditValue = $this->GC->CurrentValue;
        $this->GC->PlaceHolder = RemoveHtml($this->GC->caption());
        if (strval($this->GC->EditValue) != "" && is_numeric($this->GC->EditValue)) {
            $this->GC->EditValue = FormatNumber($this->GC->EditValue, null);
        }

        // GD
        $this->GD->setupEditAttributes();
        $this->GD->EditCustomAttributes = "";
        $this->GD->EditValue = $this->GD->CurrentValue;
        $this->GD->PlaceHolder = RemoveHtml($this->GD->caption());
        if (strval($this->GD->EditValue) != "" && is_numeric($this->GD->EditValue)) {
            $this->GD->EditValue = FormatNumber($this->GD->EditValue, null);
        }

        // GRUPO
        $this->GRUPO->setupEditAttributes();
        $this->GRUPO->EditCustomAttributes = "";
        $this->GRUPO->EditValue = $this->GRUPO->options(true);
        $this->GRUPO->PlaceHolder = RemoveHtml($this->GRUPO->caption());

        // POSICION_EQUIPO_TORENO
        $this->POSICION_EQUIPO_TORENO->setupEditAttributes();
        $this->POSICION_EQUIPO_TORENO->EditCustomAttributes = "";
        $this->POSICION_EQUIPO_TORENO->EditValue = $this->POSICION_EQUIPO_TORENO->CurrentValue;
        $this->POSICION_EQUIPO_TORENO->PlaceHolder = RemoveHtml($this->POSICION_EQUIPO_TORENO->caption());

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
                    $doc->exportCaption($this->ID_EQUIPO_TORNEO);
                    $doc->exportCaption($this->ID_TORNEO);
                    $doc->exportCaption($this->ID_EQUIPO);
                    $doc->exportCaption($this->PARTIDOS_JUGADOS);
                    $doc->exportCaption($this->PARTIDOS_GANADOS);
                    $doc->exportCaption($this->PARTIDOS_EMPATADOS);
                    $doc->exportCaption($this->PARTIDOS_PERDIDOS);
                    $doc->exportCaption($this->GF);
                    $doc->exportCaption($this->GC);
                    $doc->exportCaption($this->GD);
                    $doc->exportCaption($this->GRUPO);
                    $doc->exportCaption($this->POSICION_EQUIPO_TORENO);
                } else {
                    $doc->exportCaption($this->ID_EQUIPO_TORNEO);
                    $doc->exportCaption($this->ID_TORNEO);
                    $doc->exportCaption($this->ID_EQUIPO);
                    $doc->exportCaption($this->PARTIDOS_JUGADOS);
                    $doc->exportCaption($this->PARTIDOS_GANADOS);
                    $doc->exportCaption($this->PARTIDOS_EMPATADOS);
                    $doc->exportCaption($this->PARTIDOS_PERDIDOS);
                    $doc->exportCaption($this->GF);
                    $doc->exportCaption($this->GC);
                    $doc->exportCaption($this->GD);
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
                        $doc->exportField($this->ID_EQUIPO_TORNEO);
                        $doc->exportField($this->ID_TORNEO);
                        $doc->exportField($this->ID_EQUIPO);
                        $doc->exportField($this->PARTIDOS_JUGADOS);
                        $doc->exportField($this->PARTIDOS_GANADOS);
                        $doc->exportField($this->PARTIDOS_EMPATADOS);
                        $doc->exportField($this->PARTIDOS_PERDIDOS);
                        $doc->exportField($this->GF);
                        $doc->exportField($this->GC);
                        $doc->exportField($this->GD);
                        $doc->exportField($this->GRUPO);
                        $doc->exportField($this->POSICION_EQUIPO_TORENO);
                    } else {
                        $doc->exportField($this->ID_EQUIPO_TORNEO);
                        $doc->exportField($this->ID_TORNEO);
                        $doc->exportField($this->ID_EQUIPO);
                        $doc->exportField($this->PARTIDOS_JUGADOS);
                        $doc->exportField($this->PARTIDOS_GANADOS);
                        $doc->exportField($this->PARTIDOS_EMPATADOS);
                        $doc->exportField($this->PARTIDOS_PERDIDOS);
                        $doc->exportField($this->GF);
                        $doc->exportField($this->GC);
                        $doc->exportField($this->GD);
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
