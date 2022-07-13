<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class EncuestaAdd extends Encuesta
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'encuesta';

    // Page object name
    public $PageObjName = "EncuestaAdd";

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

        // Table object (encuesta)
        if (!isset($GLOBALS["encuesta"]) || get_class($GLOBALS["encuesta"]) == PROJECT_NAMESPACE . "encuesta") {
            $GLOBALS["encuesta"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'encuesta');
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
                $tbl = Container("encuesta");
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
                    if ($pageName == "EncuestaView") {
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
            $key .= @$ar['ID_ENCUESTA'];
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
            $this->ID_ENCUESTA->Visible = false;
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
        $this->ID_ENCUESTA->Visible = false;
        $this->ID_PARTICIPANTE->setVisibility();
        $this->GRUPO->setVisibility();
        $this->EQUIPO->setVisibility();
        $this->POSICION->setVisibility();
        $this->NUMERACION->setVisibility();
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
        $this->setupLookupOptions($this->ID_PARTICIPANTE);
        $this->setupLookupOptions($this->GRUPO);
        $this->setupLookupOptions($this->EQUIPO);
        $this->setupLookupOptions($this->POSICION);

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
            if (($keyValue = Get("ID_ENCUESTA") ?? Route("ID_ENCUESTA")) !== null) {
                $this->ID_ENCUESTA->setQueryStringValue($keyValue);
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
                    $this->terminate("EncuestaList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "EncuestaList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "EncuestaView") {
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

        // Check field name 'ID_PARTICIPANTE' first before field var 'x_ID_PARTICIPANTE'
        $val = $CurrentForm->hasValue("ID_PARTICIPANTE") ? $CurrentForm->getValue("ID_PARTICIPANTE") : $CurrentForm->getValue("x_ID_PARTICIPANTE");
        if (!$this->ID_PARTICIPANTE->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ID_PARTICIPANTE->Visible = false; // Disable update for API request
            } else {
                $this->ID_PARTICIPANTE->setFormValue($val);
            }
        }

        // Check field name 'GRUPO' first before field var 'x_GRUPO'
        $val = $CurrentForm->hasValue("GRUPO") ? $CurrentForm->getValue("GRUPO") : $CurrentForm->getValue("x_GRUPO");
        if (!$this->GRUPO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->GRUPO->Visible = false; // Disable update for API request
            } else {
                $this->GRUPO->setFormValue($val);
            }
        }

        // Check field name 'EQUIPO' first before field var 'x_EQUIPO'
        $val = $CurrentForm->hasValue("EQUIPO") ? $CurrentForm->getValue("EQUIPO") : $CurrentForm->getValue("x_EQUIPO");
        if (!$this->EQUIPO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->EQUIPO->Visible = false; // Disable update for API request
            } else {
                $this->EQUIPO->setFormValue($val);
            }
        }

        // Check field name 'POSICION' first before field var 'x_POSICION'
        $val = $CurrentForm->hasValue("POSICION") ? $CurrentForm->getValue("POSICION") : $CurrentForm->getValue("x_POSICION");
        if (!$this->POSICION->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->POSICION->Visible = false; // Disable update for API request
            } else {
                $this->POSICION->setFormValue($val);
            }
        }

        // Check field name 'NUMERACION' first before field var 'x_NUMERACION'
        $val = $CurrentForm->hasValue("NUMERACION") ? $CurrentForm->getValue("NUMERACION") : $CurrentForm->getValue("x_NUMERACION");
        if (!$this->NUMERACION->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NUMERACION->Visible = false; // Disable update for API request
            } else {
                $this->NUMERACION->setFormValue($val);
            }
        }

        // Check field name 'ID_ENCUESTA' first before field var 'x_ID_ENCUESTA'
        $val = $CurrentForm->hasValue("ID_ENCUESTA") ? $CurrentForm->getValue("ID_ENCUESTA") : $CurrentForm->getValue("x_ID_ENCUESTA");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->ID_PARTICIPANTE->CurrentValue = $this->ID_PARTICIPANTE->FormValue;
        $this->GRUPO->CurrentValue = $this->GRUPO->FormValue;
        $this->EQUIPO->CurrentValue = $this->EQUIPO->FormValue;
        $this->POSICION->CurrentValue = $this->POSICION->FormValue;
        $this->NUMERACION->CurrentValue = $this->NUMERACION->FormValue;
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
        $this->ID_ENCUESTA->setDbValue($row['ID_ENCUESTA']);
        $this->ID_PARTICIPANTE->setDbValue($row['ID_PARTICIPANTE']);
        $this->GRUPO->setDbValue($row['GRUPO']);
        $this->EQUIPO->setDbValue($row['EQUIPO']);
        $this->POSICION->setDbValue($row['POSICION']);
        $this->NUMERACION->setDbValue($row['NUMERACION']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ID_ENCUESTA'] = $this->ID_ENCUESTA->DefaultValue;
        $row['ID_PARTICIPANTE'] = $this->ID_PARTICIPANTE->DefaultValue;
        $row['GRUPO'] = $this->GRUPO->DefaultValue;
        $row['EQUIPO'] = $this->EQUIPO->DefaultValue;
        $row['POSICION'] = $this->POSICION->DefaultValue;
        $row['NUMERACION'] = $this->NUMERACION->DefaultValue;
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

        // ID_ENCUESTA
        $this->ID_ENCUESTA->RowCssClass = "row";

        // ID_PARTICIPANTE
        $this->ID_PARTICIPANTE->RowCssClass = "row";

        // GRUPO
        $this->GRUPO->RowCssClass = "row";

        // EQUIPO
        $this->EQUIPO->RowCssClass = "row";

        // POSICION
        $this->POSICION->RowCssClass = "row";

        // NUMERACION
        $this->NUMERACION->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // ID_ENCUESTA
            $this->ID_ENCUESTA->ViewValue = $this->ID_ENCUESTA->CurrentValue;
            $this->ID_ENCUESTA->ViewCustomAttributes = "";

            // ID_PARTICIPANTE
            $curVal = strval($this->ID_PARTICIPANTE->CurrentValue);
            if ($curVal != "") {
                $this->ID_PARTICIPANTE->ViewValue = $this->ID_PARTICIPANTE->lookupCacheOption($curVal);
                if ($this->ID_PARTICIPANTE->ViewValue === null) { // Lookup from database
                    $filterWrk = "`ID_PARTICIPANTE`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->ID_PARTICIPANTE->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ID_PARTICIPANTE->Lookup->renderViewRow($rswrk[0]);
                        $this->ID_PARTICIPANTE->ViewValue = $this->ID_PARTICIPANTE->displayValue($arwrk);
                    } else {
                        $this->ID_PARTICIPANTE->ViewValue = FormatNumber($this->ID_PARTICIPANTE->CurrentValue, $this->ID_PARTICIPANTE->formatPattern());
                    }
                }
            } else {
                $this->ID_PARTICIPANTE->ViewValue = null;
            }
            $this->ID_PARTICIPANTE->ViewCustomAttributes = "";

            // GRUPO
            if (strval($this->GRUPO->CurrentValue) != "") {
                $this->GRUPO->ViewValue = $this->GRUPO->optionCaption($this->GRUPO->CurrentValue);
            } else {
                $this->GRUPO->ViewValue = null;
            }
            $this->GRUPO->ViewCustomAttributes = "";

            // EQUIPO
            $curVal = strval($this->EQUIPO->CurrentValue);
            if ($curVal != "") {
                $this->EQUIPO->ViewValue = $this->EQUIPO->lookupCacheOption($curVal);
                if ($this->EQUIPO->ViewValue === null) { // Lookup from database
                    $filterWrk = "`NOM_EQUIPO_CORTO`" . SearchString("=", $curVal, DATATYPE_MEMO, "");
                    $sqlWrk = $this->EQUIPO->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->EQUIPO->Lookup->renderViewRow($rswrk[0]);
                        $this->EQUIPO->ViewValue = $this->EQUIPO->displayValue($arwrk);
                    } else {
                        $this->EQUIPO->ViewValue = $this->EQUIPO->CurrentValue;
                    }
                }
            } else {
                $this->EQUIPO->ViewValue = null;
            }
            $this->EQUIPO->ViewCustomAttributes = "";

            // POSICION
            if (strval($this->POSICION->CurrentValue) != "") {
                $this->POSICION->ViewValue = $this->POSICION->optionCaption($this->POSICION->CurrentValue);
            } else {
                $this->POSICION->ViewValue = null;
            }
            $this->POSICION->ViewCustomAttributes = "";

            // NUMERACION
            $this->NUMERACION->ViewValue = $this->NUMERACION->CurrentValue;
            $this->NUMERACION->ViewCustomAttributes = "";

            // ID_PARTICIPANTE
            $this->ID_PARTICIPANTE->LinkCustomAttributes = "";
            $this->ID_PARTICIPANTE->HrefValue = "";

            // GRUPO
            $this->GRUPO->LinkCustomAttributes = "";
            $this->GRUPO->HrefValue = "";

            // EQUIPO
            $this->EQUIPO->LinkCustomAttributes = "";
            $this->EQUIPO->HrefValue = "";

            // POSICION
            $this->POSICION->LinkCustomAttributes = "";
            $this->POSICION->HrefValue = "";

            // NUMERACION
            $this->NUMERACION->LinkCustomAttributes = "";
            $this->NUMERACION->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // ID_PARTICIPANTE
            $this->ID_PARTICIPANTE->setupEditAttributes();
            $this->ID_PARTICIPANTE->EditCustomAttributes = "";
            $curVal = trim(strval($this->ID_PARTICIPANTE->CurrentValue));
            if ($curVal != "") {
                $this->ID_PARTICIPANTE->ViewValue = $this->ID_PARTICIPANTE->lookupCacheOption($curVal);
            } else {
                $this->ID_PARTICIPANTE->ViewValue = $this->ID_PARTICIPANTE->Lookup !== null && is_array($this->ID_PARTICIPANTE->lookupOptions()) ? $curVal : null;
            }
            if ($this->ID_PARTICIPANTE->ViewValue !== null) { // Load from cache
                $this->ID_PARTICIPANTE->EditValue = array_values($this->ID_PARTICIPANTE->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`ID_PARTICIPANTE`" . SearchString("=", $this->ID_PARTICIPANTE->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->ID_PARTICIPANTE->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->ID_PARTICIPANTE->EditValue = $arwrk;
            }
            $this->ID_PARTICIPANTE->PlaceHolder = RemoveHtml($this->ID_PARTICIPANTE->caption());

            // GRUPO
            $this->GRUPO->setupEditAttributes();
            $this->GRUPO->EditCustomAttributes = "";
            $this->GRUPO->EditValue = $this->GRUPO->options(true);
            $this->GRUPO->PlaceHolder = RemoveHtml($this->GRUPO->caption());

            // EQUIPO
            $this->EQUIPO->setupEditAttributes();
            $this->EQUIPO->EditCustomAttributes = "";
            $curVal = trim(strval($this->EQUIPO->CurrentValue));
            if ($curVal != "") {
                $this->EQUIPO->ViewValue = $this->EQUIPO->lookupCacheOption($curVal);
            } else {
                $this->EQUIPO->ViewValue = $this->EQUIPO->Lookup !== null && is_array($this->EQUIPO->lookupOptions()) ? $curVal : null;
            }
            if ($this->EQUIPO->ViewValue !== null) { // Load from cache
                $this->EQUIPO->EditValue = array_values($this->EQUIPO->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`NOM_EQUIPO_CORTO`" . SearchString("=", $this->EQUIPO->CurrentValue, DATATYPE_MEMO, "");
                }
                $sqlWrk = $this->EQUIPO->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->EQUIPO->EditValue = $arwrk;
            }
            $this->EQUIPO->PlaceHolder = RemoveHtml($this->EQUIPO->caption());

            // POSICION
            $this->POSICION->setupEditAttributes();
            $this->POSICION->EditCustomAttributes = "";
            $this->POSICION->EditValue = $this->POSICION->options(true);
            $this->POSICION->PlaceHolder = RemoveHtml($this->POSICION->caption());

            // NUMERACION
            $this->NUMERACION->setupEditAttributes();
            $this->NUMERACION->EditCustomAttributes = "";
            if (!$this->NUMERACION->Raw) {
                $this->NUMERACION->CurrentValue = HtmlDecode($this->NUMERACION->CurrentValue);
            }
            $this->NUMERACION->EditValue = HtmlEncode($this->NUMERACION->CurrentValue);
            $this->NUMERACION->PlaceHolder = RemoveHtml($this->NUMERACION->caption());

            // Add refer script

            // ID_PARTICIPANTE
            $this->ID_PARTICIPANTE->LinkCustomAttributes = "";
            $this->ID_PARTICIPANTE->HrefValue = "";

            // GRUPO
            $this->GRUPO->LinkCustomAttributes = "";
            $this->GRUPO->HrefValue = "";

            // EQUIPO
            $this->EQUIPO->LinkCustomAttributes = "";
            $this->EQUIPO->HrefValue = "";

            // POSICION
            $this->POSICION->LinkCustomAttributes = "";
            $this->POSICION->HrefValue = "";

            // NUMERACION
            $this->NUMERACION->LinkCustomAttributes = "";
            $this->NUMERACION->HrefValue = "";
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
        if ($this->ID_PARTICIPANTE->Required) {
            if (!$this->ID_PARTICIPANTE->IsDetailKey && EmptyValue($this->ID_PARTICIPANTE->FormValue)) {
                $this->ID_PARTICIPANTE->addErrorMessage(str_replace("%s", $this->ID_PARTICIPANTE->caption(), $this->ID_PARTICIPANTE->RequiredErrorMessage));
            }
        }
        if ($this->GRUPO->Required) {
            if (!$this->GRUPO->IsDetailKey && EmptyValue($this->GRUPO->FormValue)) {
                $this->GRUPO->addErrorMessage(str_replace("%s", $this->GRUPO->caption(), $this->GRUPO->RequiredErrorMessage));
            }
        }
        if ($this->EQUIPO->Required) {
            if (!$this->EQUIPO->IsDetailKey && EmptyValue($this->EQUIPO->FormValue)) {
                $this->EQUIPO->addErrorMessage(str_replace("%s", $this->EQUIPO->caption(), $this->EQUIPO->RequiredErrorMessage));
            }
        }
        if ($this->POSICION->Required) {
            if (!$this->POSICION->IsDetailKey && EmptyValue($this->POSICION->FormValue)) {
                $this->POSICION->addErrorMessage(str_replace("%s", $this->POSICION->caption(), $this->POSICION->RequiredErrorMessage));
            }
        }
        if ($this->NUMERACION->Required) {
            if (!$this->NUMERACION->IsDetailKey && EmptyValue($this->NUMERACION->FormValue)) {
                $this->NUMERACION->addErrorMessage(str_replace("%s", $this->NUMERACION->caption(), $this->NUMERACION->RequiredErrorMessage));
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

        // ID_PARTICIPANTE
        $this->ID_PARTICIPANTE->setDbValueDef($rsnew, $this->ID_PARTICIPANTE->CurrentValue, null, false);

        // GRUPO
        $this->GRUPO->setDbValueDef($rsnew, $this->GRUPO->CurrentValue, null, false);

        // EQUIPO
        $this->EQUIPO->setDbValueDef($rsnew, $this->EQUIPO->CurrentValue, null, false);

        // POSICION
        $this->POSICION->setDbValueDef($rsnew, $this->POSICION->CurrentValue, null, false);

        // NUMERACION
        $this->NUMERACION->setDbValueDef($rsnew, $this->NUMERACION->CurrentValue, null, false);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("EncuestaList"), "", $this->TableVar, true);
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
                case "x_ID_PARTICIPANTE":
                    break;
                case "x_GRUPO":
                    break;
                case "x_EQUIPO":
                    break;
                case "x_POSICION":
                    break;
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
