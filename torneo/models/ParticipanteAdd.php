<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class ParticipanteAdd extends Participante
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'participante';

    // Page object name
    public $PageObjName = "ParticipanteAdd";

    // View file path
    public $View = null;

    // Title
    public $Title = null; // Title for <title> tag

    // Rendering View
    public $RenderingView = false;

    // Page headings
    public $Heading = "";
    public $Subheading = "";
    public $PageHeader;
    public $PageFooter;

    // Page layout
    public $UseLayout = true;

    // Page terminated
    private $terminated = false;

    // Page heading
    public function pageHeading()
    {
        global $Language;
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading()
    {
        global $Language;
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return $Language->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName()
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl($withArgs = true)
    {
        $route = GetRoute();
        $args = $route->getArguments();
        if (!$withArgs) {
            foreach ($args as $key => &$val) {
                $val = "";
            }
            unset($val);
        }
        $url = rtrim(UrlFor($route->getName(), $args), "/") . "?";
        if ($this->UseTokenInUrl) {
            $url .= "t=" . $this->TableVar . "&"; // Add page token
        }
        return $url;
    }

    // Show Page Header
    public function showPageHeader()
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            echo '<p id="ew-page-header">' . $header . '</p>';
        }
    }

    // Show Page Footer
    public function showPageFooter()
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            echo '<p id="ew-page-footer">' . $footer . '</p>';
        }
    }

    // Validate page request
    protected function isPageRequest()
    {
        global $CurrentForm;
        if ($this->UseTokenInUrl) {
            if ($CurrentForm) {
                return $this->TableVar == $CurrentForm->getValue("t");
            }
            if (Get("t") !== null) {
                return $this->TableVar == Get("t");
            }
        }
        return true;
    }

    // Constructor
    public function __construct()
    {
        global $Language, $DashboardReport, $DebugTimer;
        global $UserTable;

        // Initialize
        $GLOBALS["Page"] = &$this;

        // Language object
        $Language = Container("language");

        // Parent constuctor
        parent::__construct();

        // Table object (participante)
        if (!isset($GLOBALS["participante"]) || get_class($GLOBALS["participante"]) == PROJECT_NAMESPACE . "participante") {
            $GLOBALS["participante"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'participante');
        }

        // Start timer
        $DebugTimer = Container("timer");

        // Debug message
        LoadDebugMessage();

        // Open connection
        $GLOBALS["Conn"] = $GLOBALS["Conn"] ?? $this->getConnection();

        // User table object
        $UserTable = Container("usertable");
    }

    // Get content from stream
    public function getContents($stream = null): string
    {
        global $Response;
        return is_object($Response) ? $Response->getBody() : ob_get_clean();
    }

    // Is lookup
    public function isLookup()
    {
        return SameText(Route(0), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest()
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup()
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated()
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param string $url URL for direction
     * @return void
     */
    public function terminate($url = "")
    {
        if ($this->terminated) {
            return;
        }
        global $ExportFileName, $TempImages, $DashboardReport, $Response;

        // Page is terminated
        $this->terminated = true;

         // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }

        // Global Page Unloaded event (in userfn*.php)
        Page_Unloaded();

        // Export
        if ($this->CustomExport && $this->CustomExport == $this->Export && array_key_exists($this->CustomExport, Config("EXPORT_CLASSES"))) {
            $content = $this->getContents();
            if ($ExportFileName == "") {
                $ExportFileName = $this->TableVar;
            }
            $class = PROJECT_NAMESPACE . Config("EXPORT_CLASSES." . $this->CustomExport);
            if (class_exists($class)) {
                $tbl = Container("participante");
                $doc = new $class($tbl);
                $doc->Text = @$content;
                if ($this->isExport("email")) {
                    echo $this->exportEmail($doc->Text);
                } else {
                    $doc->export();
                }
                DeleteTempImages(); // Delete temp images
                return;
            }
        }
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Close connection
        CloseConnections();

        // Return for API
        if (IsApi()) {
            $res = $url === true;
            if (!$res) { // Show error
                WriteJson(array_merge(["success" => false], $this->getMessages()));
            }
            return;
        } else { // Check if response is JSON
            if (StartsString("application/json", $Response->getHeaderLine("Content-type")) && $Response->getBody()->getSize()) { // With JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url != "") {
            if (!Config("DEBUG") && ob_get_length()) {
                ob_end_clean();
            }

            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $row = ["url" => GetUrl($url), "modal" => "1"];
                $pageName = GetPageName($url);
                if ($pageName != $this->getListUrl()) { // Not List page
                    $row["caption"] = $this->getModalCaption($pageName);
                    if ($pageName == "ParticipanteView") {
                        $row["view"] = "1";
                    }
                } else { // List page should not be shown as modal => error
                    $row["error"] = $this->getFailureMessage();
                    $this->clearFailureMessage();
                }
                WriteJson($row);
            } else {
                SaveDebugMessage();
                Redirect(GetUrl($url));
            }
        }
        return; // Return to controller
    }

    // Get records from recordset
    protected function getRecordsFromRecordset($rs, $current = false)
    {
        $rows = [];
        if (is_object($rs)) { // Recordset
            while ($rs && !$rs->EOF) {
                $this->loadRowValues($rs); // Set up DbValue/CurrentValue
                $row = $this->getRecordFromArray($rs->fields);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
                $rs->moveNext();
            }
        } elseif (is_array($rs)) {
            foreach ($rs as $ar) {
                $row = $this->getRecordFromArray($ar);
                if ($current) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get record from array
    protected function getRecordFromArray($ar)
    {
        $row = [];
        if (is_array($ar)) {
            foreach ($ar as $fldname => $val) {
                if (array_key_exists($fldname, $this->Fields) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                    $fld = &$this->Fields[$fldname];
                    if ($fld->HtmlTag == "FILE") { // Upload field
                        if (EmptyValue($val)) {
                            $row[$fldname] = null;
                        } else {
                            if ($fld->DataType == DATATYPE_BLOB) {
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . $fld->Param . "/" . rawurlencode($this->getRecordKeyValue($ar))));
                                $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                            } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                                $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                    "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $val)));
                                $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                            } else { // Multiple files
                                $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                                $ar = [];
                                foreach ($files as $file) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->physicalUploadPath() . $file)));
                                    if (!EmptyValue($file)) {
                                        $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
                                    }
                                }
                                $row[$fldname] = $ar;
                            }
                        }
                    } else {
                        $row[$fldname] = $val;
                    }
                }
            }
        }
        return $row;
    }

    // Get record key value from array
    protected function getRecordKeyValue($ar)
    {
        $key = "";
        if (is_array($ar)) {
            $key .= @$ar['ID_PARTICIPANTE'];
        }
        return $key;
    }

    /**
     * Hide fields for add/edit
     *
     * @return void
     */
    protected function hideFieldsForAddEdit()
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->ID_PARTICIPANTE->Visible = false;
        }
    }

    // Lookup data
    public function lookup($ar = null)
    {
        global $Language, $Security;

        // Get lookup object
        $fieldName = $ar["field"] ?? Post("field");
        $lookup = $this->Fields[$fieldName]->Lookup;

        // Get lookup parameters
        $lookupType = $ar["ajax"] ?? Post("ajax", "unknown");
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $ar["q"] ?? Param("q") ?? $ar["sv"] ?? Post("sv", "");
            $pageSize = $ar["n"] ?? Param("n") ?? $ar["recperpage"] ?? Post("recperpage", 10);
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $ar["q"] ?? Param("q", "");
            $pageSize = $ar["n"] ?? Param("n", -1);
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $ar["start"] ?? Param("start", -1);
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $ar["page"] ?? Param("page", -1);
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($ar["s"] ?? Post("s", ""));
        $userFilter = Decrypt($ar["f"] ?? Post("f", ""));
        $userOrderBy = Decrypt($ar["o"] ?? Post("o", ""));
        $keys = $ar["keys"] ?? Post("keys");
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $ar["v0"] ?? $ar["lookupValue"] ?? Post("v0", Post("lookupValue", ""));
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $ar["v" . $i] ?? Post("v" . $i, "");
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this, !is_array($ar)); // Use settings from current page
    }
    public $FormClassName = "ew-form ew-add-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter = "";
    public $DbDetailFilter = "";
    public $StartRecord;
    public $Priv = 0;
    public $OldRecordset;
    public $CopyRecord;

    /**
     * Page run
     *
     * @return void
     */
    public function run()
    {
        global $ExportType, $CustomExportType, $ExportFileName, $UserProfile, $Language, $Security, $CurrentForm,
            $SkipHeaderFooter;

        // Is modal
        $this->IsModal = Param("modal") == "1";
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

        // Use layout
        $this->UseLayout = $this->UseLayout && ConvertToBool(Param("layout", true));

        // Create form object
        $CurrentForm = new HttpForm();
        $this->CurrentAction = Param("action"); // Set up current action
        $this->ID_PARTICIPANTE->Visible = false;
        $this->NOMBRE->setVisibility();
        $this->APELLIDO->setVisibility();
        $this->FECHA_NACIMIENTO->setVisibility();
        $this->CEDULA->setVisibility();
        $this->_EMAIL->setVisibility();
        $this->TELEFONO->setVisibility();
        $this->CREACION->setVisibility();
        $this->ACTUALIZACION->setVisibility();
        $this->hideFieldsForAddEdit();

        // Set lookup cache
        if (!in_array($this->PageID, Config("LOOKUP_CACHE_PAGE_IDS"))) {
            $this->setUseLookupCache(false);
        }

        // Global Page Loading event (in userfn*.php)
        Page_Loading();

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Set up lookup cache

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-add-form";
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action") !== null) {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey(Post($this->OldKeyName));
            $postBack = true;
        } else {
            // Load key values from QueryString
            if (($keyValue = Get("ID_PARTICIPANTE") ?? Route("ID_PARTICIPANTE")) !== null) {
                $this->ID_PARTICIPANTE->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $this->CopyRecord = !EmptyValue($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record / default values
        $loaded = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues(); // Restore form values
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$loaded) { // Record not loaded
                    if ($this->getFailureMessage() == "") {
                        $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("ParticipanteList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                $this->SendEmail = true; // Send email on add success
                if ($this->addRow($this->OldRecordset)) { // Add successful
                    if ($this->getSuccessMessage() == "" && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($Language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "ParticipanteList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "ParticipanteView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }
                    if (IsApi()) { // Return to caller
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl);
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row based on row type
        $this->RowType = ROWTYPE_ADD; // Render add type

        // Render row
        $this->resetAttributes();
        $this->renderRow();

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Setup login status
            SetupLoginStatus();

            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            Page_Rendering();

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    // Get upload files
    protected function getUploadFiles()
    {
        global $CurrentForm, $Language;
    }

    // Load default values
    protected function loadDefaultValues()
    {
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'NOMBRE' first before field var 'x_NOMBRE'
        $val = $CurrentForm->hasValue("NOMBRE") ? $CurrentForm->getValue("NOMBRE") : $CurrentForm->getValue("x_NOMBRE");
        if (!$this->NOMBRE->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NOMBRE->Visible = false; // Disable update for API request
            } else {
                $this->NOMBRE->setFormValue($val);
            }
        }

        // Check field name 'APELLIDO' first before field var 'x_APELLIDO'
        $val = $CurrentForm->hasValue("APELLIDO") ? $CurrentForm->getValue("APELLIDO") : $CurrentForm->getValue("x_APELLIDO");
        if (!$this->APELLIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->APELLIDO->Visible = false; // Disable update for API request
            } else {
                $this->APELLIDO->setFormValue($val);
            }
        }

        // Check field name 'FECHA_NACIMIENTO' first before field var 'x_FECHA_NACIMIENTO'
        $val = $CurrentForm->hasValue("FECHA_NACIMIENTO") ? $CurrentForm->getValue("FECHA_NACIMIENTO") : $CurrentForm->getValue("x_FECHA_NACIMIENTO");
        if (!$this->FECHA_NACIMIENTO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->FECHA_NACIMIENTO->Visible = false; // Disable update for API request
            } else {
                $this->FECHA_NACIMIENTO->setFormValue($val);
            }
        }

        // Check field name 'CEDULA' first before field var 'x_CEDULA'
        $val = $CurrentForm->hasValue("CEDULA") ? $CurrentForm->getValue("CEDULA") : $CurrentForm->getValue("x_CEDULA");
        if (!$this->CEDULA->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CEDULA->Visible = false; // Disable update for API request
            } else {
                $this->CEDULA->setFormValue($val);
            }
        }

        // Check field name 'EMAIL' first before field var 'x__EMAIL'
        $val = $CurrentForm->hasValue("EMAIL") ? $CurrentForm->getValue("EMAIL") : $CurrentForm->getValue("x__EMAIL");
        if (!$this->_EMAIL->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_EMAIL->Visible = false; // Disable update for API request
            } else {
                $this->_EMAIL->setFormValue($val);
            }
        }

        // Check field name 'TELEFONO' first before field var 'x_TELEFONO'
        $val = $CurrentForm->hasValue("TELEFONO") ? $CurrentForm->getValue("TELEFONO") : $CurrentForm->getValue("x_TELEFONO");
        if (!$this->TELEFONO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->TELEFONO->Visible = false; // Disable update for API request
            } else {
                $this->TELEFONO->setFormValue($val);
            }
        }

        // Check field name 'CREACION' first before field var 'x_CREACION'
        $val = $CurrentForm->hasValue("CREACION") ? $CurrentForm->getValue("CREACION") : $CurrentForm->getValue("x_CREACION");
        if (!$this->CREACION->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CREACION->Visible = false; // Disable update for API request
            } else {
                $this->CREACION->setFormValue($val);
            }
        }

        // Check field name 'ACTUALIZACION' first before field var 'x_ACTUALIZACION'
        $val = $CurrentForm->hasValue("ACTUALIZACION") ? $CurrentForm->getValue("ACTUALIZACION") : $CurrentForm->getValue("x_ACTUALIZACION");
        if (!$this->ACTUALIZACION->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ACTUALIZACION->Visible = false; // Disable update for API request
            } else {
                $this->ACTUALIZACION->setFormValue($val);
            }
        }

        // Check field name 'ID_PARTICIPANTE' first before field var 'x_ID_PARTICIPANTE'
        $val = $CurrentForm->hasValue("ID_PARTICIPANTE") ? $CurrentForm->getValue("ID_PARTICIPANTE") : $CurrentForm->getValue("x_ID_PARTICIPANTE");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->NOMBRE->CurrentValue = $this->NOMBRE->FormValue;
        $this->APELLIDO->CurrentValue = $this->APELLIDO->FormValue;
        $this->FECHA_NACIMIENTO->CurrentValue = $this->FECHA_NACIMIENTO->FormValue;
        $this->CEDULA->CurrentValue = $this->CEDULA->FormValue;
        $this->_EMAIL->CurrentValue = $this->_EMAIL->FormValue;
        $this->TELEFONO->CurrentValue = $this->TELEFONO->FormValue;
        $this->CREACION->CurrentValue = $this->CREACION->FormValue;
        $this->ACTUALIZACION->CurrentValue = $this->ACTUALIZACION->FormValue;
    }

    /**
     * Load row based on key values
     *
     * @return void
     */
    public function loadRow()
    {
        global $Security, $Language;
        $filter = $this->getRecordFilter();

        // Call Row Selecting event
        $this->rowSelecting($filter);

        // Load SQL based on filter
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $conn = $this->getConnection();
        $res = false;
        $row = $conn->fetchAssociative($sql);
        if ($row) {
            $res = true;
            $this->loadRowValues($row); // Load row values
        }
        return $res;
    }

    /**
     * Load row values from recordset or record
     *
     * @param Recordset|array $rs Record
     * @return void
     */
    public function loadRowValues($rs = null)
    {
        if (is_array($rs)) {
            $row = $rs;
        } elseif ($rs && property_exists($rs, "fields")) { // Recordset
            $row = $rs->fields;
        } else {
            $row = $this->newRow();
        }
        if (!$row) {
            return;
        }

        // Call Row Selected event
        $this->rowSelected($row);
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

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ID_PARTICIPANTE'] = $this->ID_PARTICIPANTE->DefaultValue;
        $row['NOMBRE'] = $this->NOMBRE->DefaultValue;
        $row['APELLIDO'] = $this->APELLIDO->DefaultValue;
        $row['FECHA_NACIMIENTO'] = $this->FECHA_NACIMIENTO->DefaultValue;
        $row['CEDULA'] = $this->CEDULA->DefaultValue;
        $row['EMAIL'] = $this->_EMAIL->DefaultValue;
        $row['TELEFONO'] = $this->TELEFONO->DefaultValue;
        $row['CREACION'] = $this->CREACION->DefaultValue;
        $row['ACTUALIZACION'] = $this->ACTUALIZACION->DefaultValue;
        return $row;
    }

    // Load old record
    protected function loadOldRecord()
    {
        // Load old record
        $this->OldRecordset = null;
        $validKey = $this->OldKey != "";
        if ($validKey) {
            $this->CurrentFilter = $this->getRecordFilter();
            $sql = $this->getCurrentSql();
            $conn = $this->getConnection();
            $this->OldRecordset = LoadRecordset($sql, $conn);
        }
        $this->loadRowValues($this->OldRecordset); // Load row values
        return $validKey;
    }

    // Render row values based on field settings
    public function renderRow()
    {
        global $Security, $Language, $CurrentLanguage;

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // ID_PARTICIPANTE
        $this->ID_PARTICIPANTE->RowCssClass = "row";

        // NOMBRE
        $this->NOMBRE->RowCssClass = "row";

        // APELLIDO
        $this->APELLIDO->RowCssClass = "row";

        // FECHA_NACIMIENTO
        $this->FECHA_NACIMIENTO->RowCssClass = "row";

        // CEDULA
        $this->CEDULA->RowCssClass = "row";

        // EMAIL
        $this->_EMAIL->RowCssClass = "row";

        // TELEFONO
        $this->TELEFONO->RowCssClass = "row";

        // CREACION
        $this->CREACION->RowCssClass = "row";

        // ACTUALIZACION
        $this->ACTUALIZACION->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
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

            // NOMBRE
            $this->NOMBRE->LinkCustomAttributes = "";
            $this->NOMBRE->HrefValue = "";

            // APELLIDO
            $this->APELLIDO->LinkCustomAttributes = "";
            $this->APELLIDO->HrefValue = "";

            // FECHA_NACIMIENTO
            $this->FECHA_NACIMIENTO->LinkCustomAttributes = "";
            $this->FECHA_NACIMIENTO->HrefValue = "";

            // CEDULA
            $this->CEDULA->LinkCustomAttributes = "";
            $this->CEDULA->HrefValue = "";

            // EMAIL
            $this->_EMAIL->LinkCustomAttributes = "";
            $this->_EMAIL->HrefValue = "";

            // TELEFONO
            $this->TELEFONO->LinkCustomAttributes = "";
            $this->TELEFONO->HrefValue = "";

            // CREACION
            $this->CREACION->LinkCustomAttributes = "";
            $this->CREACION->HrefValue = "";

            // ACTUALIZACION
            $this->ACTUALIZACION->LinkCustomAttributes = "";
            $this->ACTUALIZACION->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // NOMBRE
            $this->NOMBRE->setupEditAttributes();
            $this->NOMBRE->EditCustomAttributes = "";
            $this->NOMBRE->EditValue = HtmlEncode($this->NOMBRE->CurrentValue);
            $this->NOMBRE->PlaceHolder = RemoveHtml($this->NOMBRE->caption());

            // APELLIDO
            $this->APELLIDO->setupEditAttributes();
            $this->APELLIDO->EditCustomAttributes = "";
            $this->APELLIDO->EditValue = HtmlEncode($this->APELLIDO->CurrentValue);
            $this->APELLIDO->PlaceHolder = RemoveHtml($this->APELLIDO->caption());

            // FECHA_NACIMIENTO
            $this->FECHA_NACIMIENTO->setupEditAttributes();
            $this->FECHA_NACIMIENTO->EditCustomAttributes = "";
            $this->FECHA_NACIMIENTO->EditValue = HtmlEncode($this->FECHA_NACIMIENTO->CurrentValue);
            $this->FECHA_NACIMIENTO->PlaceHolder = RemoveHtml($this->FECHA_NACIMIENTO->caption());

            // CEDULA
            $this->CEDULA->setupEditAttributes();
            $this->CEDULA->EditCustomAttributes = "";
            if (!$this->CEDULA->Raw) {
                $this->CEDULA->CurrentValue = HtmlDecode($this->CEDULA->CurrentValue);
            }
            $this->CEDULA->EditValue = HtmlEncode($this->CEDULA->CurrentValue);
            $this->CEDULA->PlaceHolder = RemoveHtml($this->CEDULA->caption());

            // EMAIL
            $this->_EMAIL->setupEditAttributes();
            $this->_EMAIL->EditCustomAttributes = "";
            $this->_EMAIL->EditValue = HtmlEncode($this->_EMAIL->CurrentValue);
            $this->_EMAIL->PlaceHolder = RemoveHtml($this->_EMAIL->caption());

            // TELEFONO
            $this->TELEFONO->setupEditAttributes();
            $this->TELEFONO->EditCustomAttributes = "";
            if (!$this->TELEFONO->Raw) {
                $this->TELEFONO->CurrentValue = HtmlDecode($this->TELEFONO->CurrentValue);
            }
            $this->TELEFONO->EditValue = HtmlEncode($this->TELEFONO->CurrentValue);
            $this->TELEFONO->PlaceHolder = RemoveHtml($this->TELEFONO->caption());

            // CREACION
            $this->CREACION->setupEditAttributes();
            $this->CREACION->EditCustomAttributes = "";
            $this->CREACION->EditValue = HtmlEncode($this->CREACION->CurrentValue);
            $this->CREACION->PlaceHolder = RemoveHtml($this->CREACION->caption());

            // ACTUALIZACION
            $this->ACTUALIZACION->setupEditAttributes();
            $this->ACTUALIZACION->EditCustomAttributes = "";
            $this->ACTUALIZACION->EditValue = HtmlEncode($this->ACTUALIZACION->CurrentValue);
            $this->ACTUALIZACION->PlaceHolder = RemoveHtml($this->ACTUALIZACION->caption());

            // Add refer script

            // NOMBRE
            $this->NOMBRE->LinkCustomAttributes = "";
            $this->NOMBRE->HrefValue = "";

            // APELLIDO
            $this->APELLIDO->LinkCustomAttributes = "";
            $this->APELLIDO->HrefValue = "";

            // FECHA_NACIMIENTO
            $this->FECHA_NACIMIENTO->LinkCustomAttributes = "";
            $this->FECHA_NACIMIENTO->HrefValue = "";

            // CEDULA
            $this->CEDULA->LinkCustomAttributes = "";
            $this->CEDULA->HrefValue = "";

            // EMAIL
            $this->_EMAIL->LinkCustomAttributes = "";
            $this->_EMAIL->HrefValue = "";

            // TELEFONO
            $this->TELEFONO->LinkCustomAttributes = "";
            $this->TELEFONO->HrefValue = "";

            // CREACION
            $this->CREACION->LinkCustomAttributes = "";
            $this->CREACION->HrefValue = "";

            // ACTUALIZACION
            $this->ACTUALIZACION->LinkCustomAttributes = "";
            $this->ACTUALIZACION->HrefValue = "";
        }
        if ($this->RowType == ROWTYPE_ADD || $this->RowType == ROWTYPE_EDIT || $this->RowType == ROWTYPE_SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != ROWTYPE_AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm()
    {
        global $Language;

        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->NOMBRE->Required) {
            if (!$this->NOMBRE->IsDetailKey && EmptyValue($this->NOMBRE->FormValue)) {
                $this->NOMBRE->addErrorMessage(str_replace("%s", $this->NOMBRE->caption(), $this->NOMBRE->RequiredErrorMessage));
            }
        }
        if ($this->APELLIDO->Required) {
            if (!$this->APELLIDO->IsDetailKey && EmptyValue($this->APELLIDO->FormValue)) {
                $this->APELLIDO->addErrorMessage(str_replace("%s", $this->APELLIDO->caption(), $this->APELLIDO->RequiredErrorMessage));
            }
        }
        if ($this->FECHA_NACIMIENTO->Required) {
            if (!$this->FECHA_NACIMIENTO->IsDetailKey && EmptyValue($this->FECHA_NACIMIENTO->FormValue)) {
                $this->FECHA_NACIMIENTO->addErrorMessage(str_replace("%s", $this->FECHA_NACIMIENTO->caption(), $this->FECHA_NACIMIENTO->RequiredErrorMessage));
            }
        }
        if ($this->CEDULA->Required) {
            if (!$this->CEDULA->IsDetailKey && EmptyValue($this->CEDULA->FormValue)) {
                $this->CEDULA->addErrorMessage(str_replace("%s", $this->CEDULA->caption(), $this->CEDULA->RequiredErrorMessage));
            }
        }
        if ($this->_EMAIL->Required) {
            if (!$this->_EMAIL->IsDetailKey && EmptyValue($this->_EMAIL->FormValue)) {
                $this->_EMAIL->addErrorMessage(str_replace("%s", $this->_EMAIL->caption(), $this->_EMAIL->RequiredErrorMessage));
            }
        }
        if ($this->TELEFONO->Required) {
            if (!$this->TELEFONO->IsDetailKey && EmptyValue($this->TELEFONO->FormValue)) {
                $this->TELEFONO->addErrorMessage(str_replace("%s", $this->TELEFONO->caption(), $this->TELEFONO->RequiredErrorMessage));
            }
        }
        if ($this->CREACION->Required) {
            if (!$this->CREACION->IsDetailKey && EmptyValue($this->CREACION->FormValue)) {
                $this->CREACION->addErrorMessage(str_replace("%s", $this->CREACION->caption(), $this->CREACION->RequiredErrorMessage));
            }
        }
        if ($this->ACTUALIZACION->Required) {
            if (!$this->ACTUALIZACION->IsDetailKey && EmptyValue($this->ACTUALIZACION->FormValue)) {
                $this->ACTUALIZACION->addErrorMessage(str_replace("%s", $this->ACTUALIZACION->caption(), $this->ACTUALIZACION->RequiredErrorMessage));
            }
        }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Add record
    protected function addRow($rsold = null)
    {
        global $Language, $Security;

        // Set new row
        $rsnew = [];

        // NOMBRE
        $this->NOMBRE->setDbValueDef($rsnew, $this->NOMBRE->CurrentValue, null, false);

        // APELLIDO
        $this->APELLIDO->setDbValueDef($rsnew, $this->APELLIDO->CurrentValue, null, false);

        // FECHA_NACIMIENTO
        $this->FECHA_NACIMIENTO->setDbValueDef($rsnew, $this->FECHA_NACIMIENTO->CurrentValue, null, false);

        // CEDULA
        $this->CEDULA->setDbValueDef($rsnew, $this->CEDULA->CurrentValue, null, false);

        // EMAIL
        $this->_EMAIL->setDbValueDef($rsnew, $this->_EMAIL->CurrentValue, null, false);

        // TELEFONO
        $this->TELEFONO->setDbValueDef($rsnew, $this->TELEFONO->CurrentValue, null, false);

        // CREACION
        $this->CREACION->setDbValueDef($rsnew, $this->CREACION->CurrentValue, null, false);

        // ACTUALIZACION
        $this->ACTUALIZACION->setDbValueDef($rsnew, $this->ACTUALIZACION->CurrentValue, null, false);

        // Update current values
        $this->setCurrentValues($rsnew);
        $conn = $this->getConnection();

        // Load db values from old row
        $this->loadDbValues($rsold);
        if ($rsold) {
        }

        // Call Row Inserting event
        $insertRow = $this->rowInserting($rsold, $rsnew);
        if ($insertRow) {
            $addRow = $this->insert($rsnew);
            if ($addRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("InsertCancelled"));
            }
            $addRow = false;
        }
        if ($addRow) {
            // Call Row Inserted event
            $this->rowInserted($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($addRow) {
        }

        // Write JSON for API request
        if (IsApi() && $addRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $addRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("ParticipanteList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $Breadcrumb->add("add", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions($fld)
    {
        if ($fld->Lookup !== null && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $sql = $fld->Lookup->getSql(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $sql != "" && count($fld->Lookup->Options) == 0) {
                $totalCnt = $this->getRecordCount($sql, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }
                $rows = $conn->executeQuery($sql)->fetchAll();
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row, Container($fld->Lookup->LinkTable));
                    $ar[strval($row["lf"])] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad()
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload()
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(&$url)
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'failure'|'warning'
    public function messageShowing(&$msg, $type)
    {
        if ($type == 'success') {
            //$msg = "your success message";
        } elseif ($type == 'failure') {
            //$msg = "your failure message";
        } elseif ($type == 'warning') {
            //$msg = "your warning message";
        } else {
            //$msg = "your message";
        }
    }

    // Page Render event
    public function pageRender()
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(&$header)
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(&$footer)
    {
        // Example:
        //$footer = "your footer";
    }

    // Form Custom Validate event
    public function formCustomValidate(&$customError)
    {
        // Return error message in $customError
        return true;
    }
}
