<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class PartidosAdd extends Partidos
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'partidos';

    // Page object name
    public $PageObjName = "PartidosAdd";

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

        // Table object (partidos)
        if (!isset($GLOBALS["partidos"]) || get_class($GLOBALS["partidos"]) == PROJECT_NAMESPACE . "partidos") {
            $GLOBALS["partidos"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'partidos');
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
                $tbl = Container("partidos");
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
                    if ($pageName == "PartidosView") {
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
            $key .= @$ar['ID_PARTIDO'];
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
            $this->ID_PARTIDO->Visible = false;
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
        $this->ID_EQUIPO2->setVisibility();
        $this->ID_EQUIPO1->setVisibility();
        $this->ID_PARTIDO->Visible = false;
        $this->ID_TORNEO->setVisibility();
        $this->FECHA_PARTIDO->setVisibility();
        $this->HORA_PARTIDO->setVisibility();
        $this->DIA_PARTIDO->setVisibility();
        $this->ESTADIO->setVisibility();
        $this->CIUDAD_PARTIDO->setVisibility();
        $this->PAIS_PARTIDO->setVisibility();
        $this->GOLES_EQUIPO1->setVisibility();
        $this->GOLES_EQUIPO2->setVisibility();
        $this->GOLES_EXTRA_EQUIPO1->setVisibility();
        $this->GOLES_EXTRA_EQUIPO2->setVisibility();
        $this->NOTA_PARTIDO->setVisibility();
        $this->RESUMEN_PARTIDO->setVisibility();
        $this->ESTADO_PARTIDO->setVisibility();
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
        $this->setupLookupOptions($this->ID_EQUIPO2);
        $this->setupLookupOptions($this->ID_EQUIPO1);
        $this->setupLookupOptions($this->ID_TORNEO);

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
            if (($keyValue = Get("ID_PARTIDO") ?? Route("ID_PARTIDO")) !== null) {
                $this->ID_PARTIDO->setQueryStringValue($keyValue);
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
                    $this->terminate("PartidosList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "PartidosList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "PartidosView") {
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

        // Check field name 'ID_EQUIPO2' first before field var 'x_ID_EQUIPO2'
        $val = $CurrentForm->hasValue("ID_EQUIPO2") ? $CurrentForm->getValue("ID_EQUIPO2") : $CurrentForm->getValue("x_ID_EQUIPO2");
        if (!$this->ID_EQUIPO2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ID_EQUIPO2->Visible = false; // Disable update for API request
            } else {
                $this->ID_EQUIPO2->setFormValue($val);
            }
        }

        // Check field name 'ID_EQUIPO1' first before field var 'x_ID_EQUIPO1'
        $val = $CurrentForm->hasValue("ID_EQUIPO1") ? $CurrentForm->getValue("ID_EQUIPO1") : $CurrentForm->getValue("x_ID_EQUIPO1");
        if (!$this->ID_EQUIPO1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ID_EQUIPO1->Visible = false; // Disable update for API request
            } else {
                $this->ID_EQUIPO1->setFormValue($val);
            }
        }

        // Check field name 'ID_TORNEO' first before field var 'x_ID_TORNEO'
        $val = $CurrentForm->hasValue("ID_TORNEO") ? $CurrentForm->getValue("ID_TORNEO") : $CurrentForm->getValue("x_ID_TORNEO");
        if (!$this->ID_TORNEO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ID_TORNEO->Visible = false; // Disable update for API request
            } else {
                $this->ID_TORNEO->setFormValue($val);
            }
        }

        // Check field name 'FECHA_PARTIDO' first before field var 'x_FECHA_PARTIDO'
        $val = $CurrentForm->hasValue("FECHA_PARTIDO") ? $CurrentForm->getValue("FECHA_PARTIDO") : $CurrentForm->getValue("x_FECHA_PARTIDO");
        if (!$this->FECHA_PARTIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->FECHA_PARTIDO->Visible = false; // Disable update for API request
            } else {
                $this->FECHA_PARTIDO->setFormValue($val, true, $validate);
            }
            $this->FECHA_PARTIDO->CurrentValue = UnFormatDateTime($this->FECHA_PARTIDO->CurrentValue, $this->FECHA_PARTIDO->formatPattern());
        }

        // Check field name 'HORA_PARTIDO' first before field var 'x_HORA_PARTIDO'
        $val = $CurrentForm->hasValue("HORA_PARTIDO") ? $CurrentForm->getValue("HORA_PARTIDO") : $CurrentForm->getValue("x_HORA_PARTIDO");
        if (!$this->HORA_PARTIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->HORA_PARTIDO->Visible = false; // Disable update for API request
            } else {
                $this->HORA_PARTIDO->setFormValue($val, true, $validate);
            }
            $this->HORA_PARTIDO->CurrentValue = UnFormatDateTime($this->HORA_PARTIDO->CurrentValue, $this->HORA_PARTIDO->formatPattern());
        }

        // Check field name 'DIA_PARTIDO' first before field var 'x_DIA_PARTIDO'
        $val = $CurrentForm->hasValue("DIA_PARTIDO") ? $CurrentForm->getValue("DIA_PARTIDO") : $CurrentForm->getValue("x_DIA_PARTIDO");
        if (!$this->DIA_PARTIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->DIA_PARTIDO->Visible = false; // Disable update for API request
            } else {
                $this->DIA_PARTIDO->setFormValue($val);
            }
        }

        // Check field name 'ESTADIO' first before field var 'x_ESTADIO'
        $val = $CurrentForm->hasValue("ESTADIO") ? $CurrentForm->getValue("ESTADIO") : $CurrentForm->getValue("x_ESTADIO");
        if (!$this->ESTADIO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ESTADIO->Visible = false; // Disable update for API request
            } else {
                $this->ESTADIO->setFormValue($val);
            }
        }

        // Check field name 'CIUDAD_PARTIDO' first before field var 'x_CIUDAD_PARTIDO'
        $val = $CurrentForm->hasValue("CIUDAD_PARTIDO") ? $CurrentForm->getValue("CIUDAD_PARTIDO") : $CurrentForm->getValue("x_CIUDAD_PARTIDO");
        if (!$this->CIUDAD_PARTIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->CIUDAD_PARTIDO->Visible = false; // Disable update for API request
            } else {
                $this->CIUDAD_PARTIDO->setFormValue($val);
            }
        }

        // Check field name 'PAIS_PARTIDO' first before field var 'x_PAIS_PARTIDO'
        $val = $CurrentForm->hasValue("PAIS_PARTIDO") ? $CurrentForm->getValue("PAIS_PARTIDO") : $CurrentForm->getValue("x_PAIS_PARTIDO");
        if (!$this->PAIS_PARTIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PAIS_PARTIDO->Visible = false; // Disable update for API request
            } else {
                $this->PAIS_PARTIDO->setFormValue($val);
            }
        }

        // Check field name 'GOLES_EQUIPO1' first before field var 'x_GOLES_EQUIPO1'
        $val = $CurrentForm->hasValue("GOLES_EQUIPO1") ? $CurrentForm->getValue("GOLES_EQUIPO1") : $CurrentForm->getValue("x_GOLES_EQUIPO1");
        if (!$this->GOLES_EQUIPO1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->GOLES_EQUIPO1->Visible = false; // Disable update for API request
            } else {
                $this->GOLES_EQUIPO1->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'GOLES_EQUIPO2' first before field var 'x_GOLES_EQUIPO2'
        $val = $CurrentForm->hasValue("GOLES_EQUIPO2") ? $CurrentForm->getValue("GOLES_EQUIPO2") : $CurrentForm->getValue("x_GOLES_EQUIPO2");
        if (!$this->GOLES_EQUIPO2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->GOLES_EQUIPO2->Visible = false; // Disable update for API request
            } else {
                $this->GOLES_EQUIPO2->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'GOLES_EXTRA_EQUIPO1' first before field var 'x_GOLES_EXTRA_EQUIPO1'
        $val = $CurrentForm->hasValue("GOLES_EXTRA_EQUIPO1") ? $CurrentForm->getValue("GOLES_EXTRA_EQUIPO1") : $CurrentForm->getValue("x_GOLES_EXTRA_EQUIPO1");
        if (!$this->GOLES_EXTRA_EQUIPO1->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->GOLES_EXTRA_EQUIPO1->Visible = false; // Disable update for API request
            } else {
                $this->GOLES_EXTRA_EQUIPO1->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'GOLES_EXTRA_EQUIPO2' first before field var 'x_GOLES_EXTRA_EQUIPO2'
        $val = $CurrentForm->hasValue("GOLES_EXTRA_EQUIPO2") ? $CurrentForm->getValue("GOLES_EXTRA_EQUIPO2") : $CurrentForm->getValue("x_GOLES_EXTRA_EQUIPO2");
        if (!$this->GOLES_EXTRA_EQUIPO2->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->GOLES_EXTRA_EQUIPO2->Visible = false; // Disable update for API request
            } else {
                $this->GOLES_EXTRA_EQUIPO2->setFormValue($val, true, $validate);
            }
        }

        // Check field name 'NOTA_PARTIDO' first before field var 'x_NOTA_PARTIDO'
        $val = $CurrentForm->hasValue("NOTA_PARTIDO") ? $CurrentForm->getValue("NOTA_PARTIDO") : $CurrentForm->getValue("x_NOTA_PARTIDO");
        if (!$this->NOTA_PARTIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NOTA_PARTIDO->Visible = false; // Disable update for API request
            } else {
                $this->NOTA_PARTIDO->setFormValue($val);
            }
        }

        // Check field name 'RESUMEN_PARTIDO' first before field var 'x_RESUMEN_PARTIDO'
        $val = $CurrentForm->hasValue("RESUMEN_PARTIDO") ? $CurrentForm->getValue("RESUMEN_PARTIDO") : $CurrentForm->getValue("x_RESUMEN_PARTIDO");
        if (!$this->RESUMEN_PARTIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->RESUMEN_PARTIDO->Visible = false; // Disable update for API request
            } else {
                $this->RESUMEN_PARTIDO->setFormValue($val);
            }
        }

        // Check field name 'ESTADO_PARTIDO' first before field var 'x_ESTADO_PARTIDO'
        $val = $CurrentForm->hasValue("ESTADO_PARTIDO") ? $CurrentForm->getValue("ESTADO_PARTIDO") : $CurrentForm->getValue("x_ESTADO_PARTIDO");
        if (!$this->ESTADO_PARTIDO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->ESTADO_PARTIDO->Visible = false; // Disable update for API request
            } else {
                $this->ESTADO_PARTIDO->setFormValue($val);
            }
        }

        // Check field name 'ID_PARTIDO' first before field var 'x_ID_PARTIDO'
        $val = $CurrentForm->hasValue("ID_PARTIDO") ? $CurrentForm->getValue("ID_PARTIDO") : $CurrentForm->getValue("x_ID_PARTIDO");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->ID_EQUIPO2->CurrentValue = $this->ID_EQUIPO2->FormValue;
        $this->ID_EQUIPO1->CurrentValue = $this->ID_EQUIPO1->FormValue;
        $this->ID_TORNEO->CurrentValue = $this->ID_TORNEO->FormValue;
        $this->FECHA_PARTIDO->CurrentValue = $this->FECHA_PARTIDO->FormValue;
        $this->FECHA_PARTIDO->CurrentValue = UnFormatDateTime($this->FECHA_PARTIDO->CurrentValue, $this->FECHA_PARTIDO->formatPattern());
        $this->HORA_PARTIDO->CurrentValue = $this->HORA_PARTIDO->FormValue;
        $this->HORA_PARTIDO->CurrentValue = UnFormatDateTime($this->HORA_PARTIDO->CurrentValue, $this->HORA_PARTIDO->formatPattern());
        $this->DIA_PARTIDO->CurrentValue = $this->DIA_PARTIDO->FormValue;
        $this->ESTADIO->CurrentValue = $this->ESTADIO->FormValue;
        $this->CIUDAD_PARTIDO->CurrentValue = $this->CIUDAD_PARTIDO->FormValue;
        $this->PAIS_PARTIDO->CurrentValue = $this->PAIS_PARTIDO->FormValue;
        $this->GOLES_EQUIPO1->CurrentValue = $this->GOLES_EQUIPO1->FormValue;
        $this->GOLES_EQUIPO2->CurrentValue = $this->GOLES_EQUIPO2->FormValue;
        $this->GOLES_EXTRA_EQUIPO1->CurrentValue = $this->GOLES_EXTRA_EQUIPO1->FormValue;
        $this->GOLES_EXTRA_EQUIPO2->CurrentValue = $this->GOLES_EXTRA_EQUIPO2->FormValue;
        $this->NOTA_PARTIDO->CurrentValue = $this->NOTA_PARTIDO->FormValue;
        $this->RESUMEN_PARTIDO->CurrentValue = $this->RESUMEN_PARTIDO->FormValue;
        $this->ESTADO_PARTIDO->CurrentValue = $this->ESTADO_PARTIDO->FormValue;
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
        $this->ID_EQUIPO2->setDbValue($row['ID_EQUIPO2']);
        $this->ID_EQUIPO1->setDbValue($row['ID_EQUIPO1']);
        $this->ID_PARTIDO->setDbValue($row['ID_PARTIDO']);
        $this->ID_TORNEO->setDbValue($row['ID_TORNEO']);
        $this->FECHA_PARTIDO->setDbValue($row['FECHA_PARTIDO']);
        $this->HORA_PARTIDO->setDbValue($row['HORA_PARTIDO']);
        $this->DIA_PARTIDO->setDbValue($row['DIA_PARTIDO']);
        $this->ESTADIO->setDbValue($row['ESTADIO']);
        $this->CIUDAD_PARTIDO->setDbValue($row['CIUDAD_PARTIDO']);
        $this->PAIS_PARTIDO->setDbValue($row['PAIS_PARTIDO']);
        $this->GOLES_EQUIPO1->setDbValue($row['GOLES_EQUIPO1']);
        $this->GOLES_EQUIPO2->setDbValue($row['GOLES_EQUIPO2']);
        $this->GOLES_EXTRA_EQUIPO1->setDbValue($row['GOLES_EXTRA_EQUIPO1']);
        $this->GOLES_EXTRA_EQUIPO2->setDbValue($row['GOLES_EXTRA_EQUIPO2']);
        $this->NOTA_PARTIDO->setDbValue($row['NOTA_PARTIDO']);
        $this->RESUMEN_PARTIDO->setDbValue($row['RESUMEN_PARTIDO']);
        $this->ESTADO_PARTIDO->setDbValue($row['ESTADO_PARTIDO']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ID_EQUIPO2'] = $this->ID_EQUIPO2->DefaultValue;
        $row['ID_EQUIPO1'] = $this->ID_EQUIPO1->DefaultValue;
        $row['ID_PARTIDO'] = $this->ID_PARTIDO->DefaultValue;
        $row['ID_TORNEO'] = $this->ID_TORNEO->DefaultValue;
        $row['FECHA_PARTIDO'] = $this->FECHA_PARTIDO->DefaultValue;
        $row['HORA_PARTIDO'] = $this->HORA_PARTIDO->DefaultValue;
        $row['DIA_PARTIDO'] = $this->DIA_PARTIDO->DefaultValue;
        $row['ESTADIO'] = $this->ESTADIO->DefaultValue;
        $row['CIUDAD_PARTIDO'] = $this->CIUDAD_PARTIDO->DefaultValue;
        $row['PAIS_PARTIDO'] = $this->PAIS_PARTIDO->DefaultValue;
        $row['GOLES_EQUIPO1'] = $this->GOLES_EQUIPO1->DefaultValue;
        $row['GOLES_EQUIPO2'] = $this->GOLES_EQUIPO2->DefaultValue;
        $row['GOLES_EXTRA_EQUIPO1'] = $this->GOLES_EXTRA_EQUIPO1->DefaultValue;
        $row['GOLES_EXTRA_EQUIPO2'] = $this->GOLES_EXTRA_EQUIPO2->DefaultValue;
        $row['NOTA_PARTIDO'] = $this->NOTA_PARTIDO->DefaultValue;
        $row['RESUMEN_PARTIDO'] = $this->RESUMEN_PARTIDO->DefaultValue;
        $row['ESTADO_PARTIDO'] = $this->ESTADO_PARTIDO->DefaultValue;
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

        // ID_EQUIPO2
        $this->ID_EQUIPO2->RowCssClass = "row";

        // ID_EQUIPO1
        $this->ID_EQUIPO1->RowCssClass = "row";

        // ID_PARTIDO
        $this->ID_PARTIDO->RowCssClass = "row";

        // ID_TORNEO
        $this->ID_TORNEO->RowCssClass = "row";

        // FECHA_PARTIDO
        $this->FECHA_PARTIDO->RowCssClass = "row";

        // HORA_PARTIDO
        $this->HORA_PARTIDO->RowCssClass = "row";

        // DIA_PARTIDO
        $this->DIA_PARTIDO->RowCssClass = "row";

        // ESTADIO
        $this->ESTADIO->RowCssClass = "row";

        // CIUDAD_PARTIDO
        $this->CIUDAD_PARTIDO->RowCssClass = "row";

        // PAIS_PARTIDO
        $this->PAIS_PARTIDO->RowCssClass = "row";

        // GOLES_EQUIPO1
        $this->GOLES_EQUIPO1->RowCssClass = "row";

        // GOLES_EQUIPO2
        $this->GOLES_EQUIPO2->RowCssClass = "row";

        // GOLES_EXTRA_EQUIPO1
        $this->GOLES_EXTRA_EQUIPO1->RowCssClass = "row";

        // GOLES_EXTRA_EQUIPO2
        $this->GOLES_EXTRA_EQUIPO2->RowCssClass = "row";

        // NOTA_PARTIDO
        $this->NOTA_PARTIDO->RowCssClass = "row";

        // RESUMEN_PARTIDO
        $this->RESUMEN_PARTIDO->RowCssClass = "row";

        // ESTADO_PARTIDO
        $this->ESTADO_PARTIDO->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // ID_EQUIPO2
            $curVal = strval($this->ID_EQUIPO2->CurrentValue);
            if ($curVal != "") {
                $this->ID_EQUIPO2->ViewValue = $this->ID_EQUIPO2->lookupCacheOption($curVal);
                if ($this->ID_EQUIPO2->ViewValue === null) { // Lookup from database
                    $filterWrk = "`ID_EQUIPO`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->ID_EQUIPO2->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ID_EQUIPO2->Lookup->renderViewRow($rswrk[0]);
                        $this->ID_EQUIPO2->ViewValue = $this->ID_EQUIPO2->displayValue($arwrk);
                    } else {
                        $this->ID_EQUIPO2->ViewValue = FormatNumber($this->ID_EQUIPO2->CurrentValue, $this->ID_EQUIPO2->formatPattern());
                    }
                }
            } else {
                $this->ID_EQUIPO2->ViewValue = null;
            }
            $this->ID_EQUIPO2->ViewCustomAttributes = "";

            // ID_EQUIPO1
            $curVal = strval($this->ID_EQUIPO1->CurrentValue);
            if ($curVal != "") {
                $this->ID_EQUIPO1->ViewValue = $this->ID_EQUIPO1->lookupCacheOption($curVal);
                if ($this->ID_EQUIPO1->ViewValue === null) { // Lookup from database
                    $filterWrk = "`ID_EQUIPO`" . SearchString("=", $curVal, DATATYPE_NUMBER, "");
                    $sqlWrk = $this->ID_EQUIPO1->Lookup->getSql(false, $filterWrk, '', $this, true, true);
                    $conn = Conn();
                    $config = $conn->getConfiguration();
                    $config->setResultCacheImpl($this->Cache);
                    $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                    $ari = count($rswrk);
                    if ($ari > 0) { // Lookup values found
                        $arwrk = $this->ID_EQUIPO1->Lookup->renderViewRow($rswrk[0]);
                        $this->ID_EQUIPO1->ViewValue = $this->ID_EQUIPO1->displayValue($arwrk);
                    } else {
                        $this->ID_EQUIPO1->ViewValue = FormatNumber($this->ID_EQUIPO1->CurrentValue, $this->ID_EQUIPO1->formatPattern());
                    }
                }
            } else {
                $this->ID_EQUIPO1->ViewValue = null;
            }
            $this->ID_EQUIPO1->ViewCustomAttributes = "";

            // ID_PARTIDO
            $this->ID_PARTIDO->ViewValue = $this->ID_PARTIDO->CurrentValue;
            $this->ID_PARTIDO->ViewCustomAttributes = "";

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

            // FECHA_PARTIDO
            $this->FECHA_PARTIDO->ViewValue = $this->FECHA_PARTIDO->CurrentValue;
            $this->FECHA_PARTIDO->ViewValue = FormatDateTime($this->FECHA_PARTIDO->ViewValue, $this->FECHA_PARTIDO->formatPattern());
            $this->FECHA_PARTIDO->ViewCustomAttributes = "";

            // HORA_PARTIDO
            $this->HORA_PARTIDO->ViewValue = $this->HORA_PARTIDO->CurrentValue;
            $this->HORA_PARTIDO->ViewValue = FormatDateTime($this->HORA_PARTIDO->ViewValue, $this->HORA_PARTIDO->formatPattern());
            $this->HORA_PARTIDO->ViewCustomAttributes = "";

            // DIA_PARTIDO
            $this->DIA_PARTIDO->ViewValue = $this->DIA_PARTIDO->CurrentValue;
            $this->DIA_PARTIDO->ViewCustomAttributes = "";

            // ESTADIO
            $this->ESTADIO->ViewValue = $this->ESTADIO->CurrentValue;
            $this->ESTADIO->ViewCustomAttributes = "";

            // CIUDAD_PARTIDO
            $this->CIUDAD_PARTIDO->ViewValue = $this->CIUDAD_PARTIDO->CurrentValue;
            $this->CIUDAD_PARTIDO->ViewCustomAttributes = "";

            // PAIS_PARTIDO
            $this->PAIS_PARTIDO->ViewValue = $this->PAIS_PARTIDO->CurrentValue;
            $this->PAIS_PARTIDO->ViewCustomAttributes = "";

            // GOLES_EQUIPO1
            $this->GOLES_EQUIPO1->ViewValue = $this->GOLES_EQUIPO1->CurrentValue;
            $this->GOLES_EQUIPO1->ViewValue = FormatNumber($this->GOLES_EQUIPO1->ViewValue, $this->GOLES_EQUIPO1->formatPattern());
            $this->GOLES_EQUIPO1->ViewCustomAttributes = "";

            // GOLES_EQUIPO2
            $this->GOLES_EQUIPO2->ViewValue = $this->GOLES_EQUIPO2->CurrentValue;
            $this->GOLES_EQUIPO2->ViewValue = FormatNumber($this->GOLES_EQUIPO2->ViewValue, $this->GOLES_EQUIPO2->formatPattern());
            $this->GOLES_EQUIPO2->ViewCustomAttributes = "";

            // GOLES_EXTRA_EQUIPO1
            $this->GOLES_EXTRA_EQUIPO1->ViewValue = $this->GOLES_EXTRA_EQUIPO1->CurrentValue;
            $this->GOLES_EXTRA_EQUIPO1->ViewValue = FormatNumber($this->GOLES_EXTRA_EQUIPO1->ViewValue, $this->GOLES_EXTRA_EQUIPO1->formatPattern());
            $this->GOLES_EXTRA_EQUIPO1->ViewCustomAttributes = "";

            // GOLES_EXTRA_EQUIPO2
            $this->GOLES_EXTRA_EQUIPO2->ViewValue = $this->GOLES_EXTRA_EQUIPO2->CurrentValue;
            $this->GOLES_EXTRA_EQUIPO2->ViewValue = FormatNumber($this->GOLES_EXTRA_EQUIPO2->ViewValue, $this->GOLES_EXTRA_EQUIPO2->formatPattern());
            $this->GOLES_EXTRA_EQUIPO2->ViewCustomAttributes = "";

            // NOTA_PARTIDO
            $this->NOTA_PARTIDO->ViewValue = $this->NOTA_PARTIDO->CurrentValue;
            $this->NOTA_PARTIDO->ViewCustomAttributes = "";

            // RESUMEN_PARTIDO
            $this->RESUMEN_PARTIDO->ViewValue = $this->RESUMEN_PARTIDO->CurrentValue;
            $this->RESUMEN_PARTIDO->ViewCustomAttributes = "";

            // ESTADO_PARTIDO
            $this->ESTADO_PARTIDO->ViewValue = $this->ESTADO_PARTIDO->CurrentValue;
            $this->ESTADO_PARTIDO->ViewCustomAttributes = "";

            // ID_EQUIPO2
            $this->ID_EQUIPO2->LinkCustomAttributes = "";
            $this->ID_EQUIPO2->HrefValue = "";

            // ID_EQUIPO1
            $this->ID_EQUIPO1->LinkCustomAttributes = "";
            $this->ID_EQUIPO1->HrefValue = "";

            // ID_TORNEO
            $this->ID_TORNEO->LinkCustomAttributes = "";
            $this->ID_TORNEO->HrefValue = "";

            // FECHA_PARTIDO
            $this->FECHA_PARTIDO->LinkCustomAttributes = "";
            $this->FECHA_PARTIDO->HrefValue = "";

            // HORA_PARTIDO
            $this->HORA_PARTIDO->LinkCustomAttributes = "";
            $this->HORA_PARTIDO->HrefValue = "";

            // DIA_PARTIDO
            $this->DIA_PARTIDO->LinkCustomAttributes = "";
            $this->DIA_PARTIDO->HrefValue = "";

            // ESTADIO
            $this->ESTADIO->LinkCustomAttributes = "";
            $this->ESTADIO->HrefValue = "";

            // CIUDAD_PARTIDO
            $this->CIUDAD_PARTIDO->LinkCustomAttributes = "";
            $this->CIUDAD_PARTIDO->HrefValue = "";

            // PAIS_PARTIDO
            $this->PAIS_PARTIDO->LinkCustomAttributes = "";
            $this->PAIS_PARTIDO->HrefValue = "";

            // GOLES_EQUIPO1
            $this->GOLES_EQUIPO1->LinkCustomAttributes = "";
            $this->GOLES_EQUIPO1->HrefValue = "";

            // GOLES_EQUIPO2
            $this->GOLES_EQUIPO2->LinkCustomAttributes = "";
            $this->GOLES_EQUIPO2->HrefValue = "";

            // GOLES_EXTRA_EQUIPO1
            $this->GOLES_EXTRA_EQUIPO1->LinkCustomAttributes = "";
            $this->GOLES_EXTRA_EQUIPO1->HrefValue = "";

            // GOLES_EXTRA_EQUIPO2
            $this->GOLES_EXTRA_EQUIPO2->LinkCustomAttributes = "";
            $this->GOLES_EXTRA_EQUIPO2->HrefValue = "";

            // NOTA_PARTIDO
            $this->NOTA_PARTIDO->LinkCustomAttributes = "";
            $this->NOTA_PARTIDO->HrefValue = "";

            // RESUMEN_PARTIDO
            $this->RESUMEN_PARTIDO->LinkCustomAttributes = "";
            $this->RESUMEN_PARTIDO->HrefValue = "";

            // ESTADO_PARTIDO
            $this->ESTADO_PARTIDO->LinkCustomAttributes = "";
            $this->ESTADO_PARTIDO->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // ID_EQUIPO2
            $this->ID_EQUIPO2->setupEditAttributes();
            $this->ID_EQUIPO2->EditCustomAttributes = "";
            $curVal = trim(strval($this->ID_EQUIPO2->CurrentValue));
            if ($curVal != "") {
                $this->ID_EQUIPO2->ViewValue = $this->ID_EQUIPO2->lookupCacheOption($curVal);
            } else {
                $this->ID_EQUIPO2->ViewValue = $this->ID_EQUIPO2->Lookup !== null && is_array($this->ID_EQUIPO2->lookupOptions()) ? $curVal : null;
            }
            if ($this->ID_EQUIPO2->ViewValue !== null) { // Load from cache
                $this->ID_EQUIPO2->EditValue = array_values($this->ID_EQUIPO2->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`ID_EQUIPO`" . SearchString("=", $this->ID_EQUIPO2->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->ID_EQUIPO2->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->ID_EQUIPO2->EditValue = $arwrk;
            }
            $this->ID_EQUIPO2->PlaceHolder = RemoveHtml($this->ID_EQUIPO2->caption());

            // ID_EQUIPO1
            $this->ID_EQUIPO1->setupEditAttributes();
            $this->ID_EQUIPO1->EditCustomAttributes = "";
            $curVal = trim(strval($this->ID_EQUIPO1->CurrentValue));
            if ($curVal != "") {
                $this->ID_EQUIPO1->ViewValue = $this->ID_EQUIPO1->lookupCacheOption($curVal);
            } else {
                $this->ID_EQUIPO1->ViewValue = $this->ID_EQUIPO1->Lookup !== null && is_array($this->ID_EQUIPO1->lookupOptions()) ? $curVal : null;
            }
            if ($this->ID_EQUIPO1->ViewValue !== null) { // Load from cache
                $this->ID_EQUIPO1->EditValue = array_values($this->ID_EQUIPO1->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`ID_EQUIPO`" . SearchString("=", $this->ID_EQUIPO1->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->ID_EQUIPO1->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->ID_EQUIPO1->EditValue = $arwrk;
            }
            $this->ID_EQUIPO1->PlaceHolder = RemoveHtml($this->ID_EQUIPO1->caption());

            // ID_TORNEO
            $this->ID_TORNEO->setupEditAttributes();
            $this->ID_TORNEO->EditCustomAttributes = "";
            $curVal = trim(strval($this->ID_TORNEO->CurrentValue));
            if ($curVal != "") {
                $this->ID_TORNEO->ViewValue = $this->ID_TORNEO->lookupCacheOption($curVal);
            } else {
                $this->ID_TORNEO->ViewValue = $this->ID_TORNEO->Lookup !== null && is_array($this->ID_TORNEO->lookupOptions()) ? $curVal : null;
            }
            if ($this->ID_TORNEO->ViewValue !== null) { // Load from cache
                $this->ID_TORNEO->EditValue = array_values($this->ID_TORNEO->lookupOptions());
            } else { // Lookup from database
                if ($curVal == "") {
                    $filterWrk = "0=1";
                } else {
                    $filterWrk = "`ID_TORNEO`" . SearchString("=", $this->ID_TORNEO->CurrentValue, DATATYPE_NUMBER, "");
                }
                $sqlWrk = $this->ID_TORNEO->Lookup->getSql(true, $filterWrk, '', $this, false, true);
                $conn = Conn();
                $config = $conn->getConfiguration();
                $config->setResultCacheImpl($this->Cache);
                $rswrk = $conn->executeCacheQuery($sqlWrk, [], [], $this->CacheProfile)->fetchAll();
                $ari = count($rswrk);
                $arwrk = $rswrk;
                $this->ID_TORNEO->EditValue = $arwrk;
            }
            $this->ID_TORNEO->PlaceHolder = RemoveHtml($this->ID_TORNEO->caption());

            // FECHA_PARTIDO
            $this->FECHA_PARTIDO->setupEditAttributes();
            $this->FECHA_PARTIDO->EditCustomAttributes = "";
            $this->FECHA_PARTIDO->EditValue = HtmlEncode(FormatDateTime($this->FECHA_PARTIDO->CurrentValue, $this->FECHA_PARTIDO->formatPattern()));
            $this->FECHA_PARTIDO->PlaceHolder = RemoveHtml($this->FECHA_PARTIDO->caption());

            // HORA_PARTIDO
            $this->HORA_PARTIDO->setupEditAttributes();
            $this->HORA_PARTIDO->EditCustomAttributes = "";
            $this->HORA_PARTIDO->EditValue = HtmlEncode(FormatDateTime($this->HORA_PARTIDO->CurrentValue, $this->HORA_PARTIDO->formatPattern()));
            $this->HORA_PARTIDO->PlaceHolder = RemoveHtml($this->HORA_PARTIDO->caption());

            // DIA_PARTIDO
            $this->DIA_PARTIDO->setupEditAttributes();
            $this->DIA_PARTIDO->EditCustomAttributes = "";
            $this->DIA_PARTIDO->EditValue = HtmlEncode($this->DIA_PARTIDO->CurrentValue);
            $this->DIA_PARTIDO->PlaceHolder = RemoveHtml($this->DIA_PARTIDO->caption());

            // ESTADIO
            $this->ESTADIO->setupEditAttributes();
            $this->ESTADIO->EditCustomAttributes = "";
            $this->ESTADIO->EditValue = HtmlEncode($this->ESTADIO->CurrentValue);
            $this->ESTADIO->PlaceHolder = RemoveHtml($this->ESTADIO->caption());

            // CIUDAD_PARTIDO
            $this->CIUDAD_PARTIDO->setupEditAttributes();
            $this->CIUDAD_PARTIDO->EditCustomAttributes = "";
            $this->CIUDAD_PARTIDO->EditValue = HtmlEncode($this->CIUDAD_PARTIDO->CurrentValue);
            $this->CIUDAD_PARTIDO->PlaceHolder = RemoveHtml($this->CIUDAD_PARTIDO->caption());

            // PAIS_PARTIDO
            $this->PAIS_PARTIDO->setupEditAttributes();
            $this->PAIS_PARTIDO->EditCustomAttributes = "";
            $this->PAIS_PARTIDO->EditValue = HtmlEncode($this->PAIS_PARTIDO->CurrentValue);
            $this->PAIS_PARTIDO->PlaceHolder = RemoveHtml($this->PAIS_PARTIDO->caption());

            // GOLES_EQUIPO1
            $this->GOLES_EQUIPO1->setupEditAttributes();
            $this->GOLES_EQUIPO1->EditCustomAttributes = "";
            $this->GOLES_EQUIPO1->EditValue = HtmlEncode($this->GOLES_EQUIPO1->CurrentValue);
            $this->GOLES_EQUIPO1->PlaceHolder = RemoveHtml($this->GOLES_EQUIPO1->caption());
            if (strval($this->GOLES_EQUIPO1->EditValue) != "" && is_numeric($this->GOLES_EQUIPO1->EditValue)) {
                $this->GOLES_EQUIPO1->EditValue = FormatNumber($this->GOLES_EQUIPO1->EditValue, null);
            }

            // GOLES_EQUIPO2
            $this->GOLES_EQUIPO2->setupEditAttributes();
            $this->GOLES_EQUIPO2->EditCustomAttributes = "";
            $this->GOLES_EQUIPO2->EditValue = HtmlEncode($this->GOLES_EQUIPO2->CurrentValue);
            $this->GOLES_EQUIPO2->PlaceHolder = RemoveHtml($this->GOLES_EQUIPO2->caption());
            if (strval($this->GOLES_EQUIPO2->EditValue) != "" && is_numeric($this->GOLES_EQUIPO2->EditValue)) {
                $this->GOLES_EQUIPO2->EditValue = FormatNumber($this->GOLES_EQUIPO2->EditValue, null);
            }

            // GOLES_EXTRA_EQUIPO1
            $this->GOLES_EXTRA_EQUIPO1->setupEditAttributes();
            $this->GOLES_EXTRA_EQUIPO1->EditCustomAttributes = "";
            $this->GOLES_EXTRA_EQUIPO1->EditValue = HtmlEncode($this->GOLES_EXTRA_EQUIPO1->CurrentValue);
            $this->GOLES_EXTRA_EQUIPO1->PlaceHolder = RemoveHtml($this->GOLES_EXTRA_EQUIPO1->caption());
            if (strval($this->GOLES_EXTRA_EQUIPO1->EditValue) != "" && is_numeric($this->GOLES_EXTRA_EQUIPO1->EditValue)) {
                $this->GOLES_EXTRA_EQUIPO1->EditValue = FormatNumber($this->GOLES_EXTRA_EQUIPO1->EditValue, null);
            }

            // GOLES_EXTRA_EQUIPO2
            $this->GOLES_EXTRA_EQUIPO2->setupEditAttributes();
            $this->GOLES_EXTRA_EQUIPO2->EditCustomAttributes = "";
            $this->GOLES_EXTRA_EQUIPO2->EditValue = HtmlEncode($this->GOLES_EXTRA_EQUIPO2->CurrentValue);
            $this->GOLES_EXTRA_EQUIPO2->PlaceHolder = RemoveHtml($this->GOLES_EXTRA_EQUIPO2->caption());
            if (strval($this->GOLES_EXTRA_EQUIPO2->EditValue) != "" && is_numeric($this->GOLES_EXTRA_EQUIPO2->EditValue)) {
                $this->GOLES_EXTRA_EQUIPO2->EditValue = FormatNumber($this->GOLES_EXTRA_EQUIPO2->EditValue, null);
            }

            // NOTA_PARTIDO
            $this->NOTA_PARTIDO->setupEditAttributes();
            $this->NOTA_PARTIDO->EditCustomAttributes = "";
            $this->NOTA_PARTIDO->EditValue = HtmlEncode($this->NOTA_PARTIDO->CurrentValue);
            $this->NOTA_PARTIDO->PlaceHolder = RemoveHtml($this->NOTA_PARTIDO->caption());

            // RESUMEN_PARTIDO
            $this->RESUMEN_PARTIDO->setupEditAttributes();
            $this->RESUMEN_PARTIDO->EditCustomAttributes = "";
            $this->RESUMEN_PARTIDO->EditValue = HtmlEncode($this->RESUMEN_PARTIDO->CurrentValue);
            $this->RESUMEN_PARTIDO->PlaceHolder = RemoveHtml($this->RESUMEN_PARTIDO->caption());

            // ESTADO_PARTIDO
            $this->ESTADO_PARTIDO->setupEditAttributes();
            $this->ESTADO_PARTIDO->EditCustomAttributes = "";
            $this->ESTADO_PARTIDO->EditValue = HtmlEncode($this->ESTADO_PARTIDO->CurrentValue);
            $this->ESTADO_PARTIDO->PlaceHolder = RemoveHtml($this->ESTADO_PARTIDO->caption());

            // Add refer script

            // ID_EQUIPO2
            $this->ID_EQUIPO2->LinkCustomAttributes = "";
            $this->ID_EQUIPO2->HrefValue = "";

            // ID_EQUIPO1
            $this->ID_EQUIPO1->LinkCustomAttributes = "";
            $this->ID_EQUIPO1->HrefValue = "";

            // ID_TORNEO
            $this->ID_TORNEO->LinkCustomAttributes = "";
            $this->ID_TORNEO->HrefValue = "";

            // FECHA_PARTIDO
            $this->FECHA_PARTIDO->LinkCustomAttributes = "";
            $this->FECHA_PARTIDO->HrefValue = "";

            // HORA_PARTIDO
            $this->HORA_PARTIDO->LinkCustomAttributes = "";
            $this->HORA_PARTIDO->HrefValue = "";

            // DIA_PARTIDO
            $this->DIA_PARTIDO->LinkCustomAttributes = "";
            $this->DIA_PARTIDO->HrefValue = "";

            // ESTADIO
            $this->ESTADIO->LinkCustomAttributes = "";
            $this->ESTADIO->HrefValue = "";

            // CIUDAD_PARTIDO
            $this->CIUDAD_PARTIDO->LinkCustomAttributes = "";
            $this->CIUDAD_PARTIDO->HrefValue = "";

            // PAIS_PARTIDO
            $this->PAIS_PARTIDO->LinkCustomAttributes = "";
            $this->PAIS_PARTIDO->HrefValue = "";

            // GOLES_EQUIPO1
            $this->GOLES_EQUIPO1->LinkCustomAttributes = "";
            $this->GOLES_EQUIPO1->HrefValue = "";

            // GOLES_EQUIPO2
            $this->GOLES_EQUIPO2->LinkCustomAttributes = "";
            $this->GOLES_EQUIPO2->HrefValue = "";

            // GOLES_EXTRA_EQUIPO1
            $this->GOLES_EXTRA_EQUIPO1->LinkCustomAttributes = "";
            $this->GOLES_EXTRA_EQUIPO1->HrefValue = "";

            // GOLES_EXTRA_EQUIPO2
            $this->GOLES_EXTRA_EQUIPO2->LinkCustomAttributes = "";
            $this->GOLES_EXTRA_EQUIPO2->HrefValue = "";

            // NOTA_PARTIDO
            $this->NOTA_PARTIDO->LinkCustomAttributes = "";
            $this->NOTA_PARTIDO->HrefValue = "";

            // RESUMEN_PARTIDO
            $this->RESUMEN_PARTIDO->LinkCustomAttributes = "";
            $this->RESUMEN_PARTIDO->HrefValue = "";

            // ESTADO_PARTIDO
            $this->ESTADO_PARTIDO->LinkCustomAttributes = "";
            $this->ESTADO_PARTIDO->HrefValue = "";
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
        if ($this->ID_EQUIPO2->Required) {
            if (!$this->ID_EQUIPO2->IsDetailKey && EmptyValue($this->ID_EQUIPO2->FormValue)) {
                $this->ID_EQUIPO2->addErrorMessage(str_replace("%s", $this->ID_EQUIPO2->caption(), $this->ID_EQUIPO2->RequiredErrorMessage));
            }
        }
        if ($this->ID_EQUIPO1->Required) {
            if (!$this->ID_EQUIPO1->IsDetailKey && EmptyValue($this->ID_EQUIPO1->FormValue)) {
                $this->ID_EQUIPO1->addErrorMessage(str_replace("%s", $this->ID_EQUIPO1->caption(), $this->ID_EQUIPO1->RequiredErrorMessage));
            }
        }
        if ($this->ID_TORNEO->Required) {
            if (!$this->ID_TORNEO->IsDetailKey && EmptyValue($this->ID_TORNEO->FormValue)) {
                $this->ID_TORNEO->addErrorMessage(str_replace("%s", $this->ID_TORNEO->caption(), $this->ID_TORNEO->RequiredErrorMessage));
            }
        }
        if ($this->FECHA_PARTIDO->Required) {
            if (!$this->FECHA_PARTIDO->IsDetailKey && EmptyValue($this->FECHA_PARTIDO->FormValue)) {
                $this->FECHA_PARTIDO->addErrorMessage(str_replace("%s", $this->FECHA_PARTIDO->caption(), $this->FECHA_PARTIDO->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->FECHA_PARTIDO->FormValue, $this->FECHA_PARTIDO->formatPattern())) {
            $this->FECHA_PARTIDO->addErrorMessage($this->FECHA_PARTIDO->getErrorMessage(false));
        }
        if ($this->HORA_PARTIDO->Required) {
            if (!$this->HORA_PARTIDO->IsDetailKey && EmptyValue($this->HORA_PARTIDO->FormValue)) {
                $this->HORA_PARTIDO->addErrorMessage(str_replace("%s", $this->HORA_PARTIDO->caption(), $this->HORA_PARTIDO->RequiredErrorMessage));
            }
        }
        if (!CheckTime($this->HORA_PARTIDO->FormValue, $this->HORA_PARTIDO->formatPattern())) {
            $this->HORA_PARTIDO->addErrorMessage($this->HORA_PARTIDO->getErrorMessage(false));
        }
        if ($this->DIA_PARTIDO->Required) {
            if (!$this->DIA_PARTIDO->IsDetailKey && EmptyValue($this->DIA_PARTIDO->FormValue)) {
                $this->DIA_PARTIDO->addErrorMessage(str_replace("%s", $this->DIA_PARTIDO->caption(), $this->DIA_PARTIDO->RequiredErrorMessage));
            }
        }
        if ($this->ESTADIO->Required) {
            if (!$this->ESTADIO->IsDetailKey && EmptyValue($this->ESTADIO->FormValue)) {
                $this->ESTADIO->addErrorMessage(str_replace("%s", $this->ESTADIO->caption(), $this->ESTADIO->RequiredErrorMessage));
            }
        }
        if ($this->CIUDAD_PARTIDO->Required) {
            if (!$this->CIUDAD_PARTIDO->IsDetailKey && EmptyValue($this->CIUDAD_PARTIDO->FormValue)) {
                $this->CIUDAD_PARTIDO->addErrorMessage(str_replace("%s", $this->CIUDAD_PARTIDO->caption(), $this->CIUDAD_PARTIDO->RequiredErrorMessage));
            }
        }
        if ($this->PAIS_PARTIDO->Required) {
            if (!$this->PAIS_PARTIDO->IsDetailKey && EmptyValue($this->PAIS_PARTIDO->FormValue)) {
                $this->PAIS_PARTIDO->addErrorMessage(str_replace("%s", $this->PAIS_PARTIDO->caption(), $this->PAIS_PARTIDO->RequiredErrorMessage));
            }
        }
        if ($this->GOLES_EQUIPO1->Required) {
            if (!$this->GOLES_EQUIPO1->IsDetailKey && EmptyValue($this->GOLES_EQUIPO1->FormValue)) {
                $this->GOLES_EQUIPO1->addErrorMessage(str_replace("%s", $this->GOLES_EQUIPO1->caption(), $this->GOLES_EQUIPO1->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->GOLES_EQUIPO1->FormValue)) {
            $this->GOLES_EQUIPO1->addErrorMessage($this->GOLES_EQUIPO1->getErrorMessage(false));
        }
        if ($this->GOLES_EQUIPO2->Required) {
            if (!$this->GOLES_EQUIPO2->IsDetailKey && EmptyValue($this->GOLES_EQUIPO2->FormValue)) {
                $this->GOLES_EQUIPO2->addErrorMessage(str_replace("%s", $this->GOLES_EQUIPO2->caption(), $this->GOLES_EQUIPO2->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->GOLES_EQUIPO2->FormValue)) {
            $this->GOLES_EQUIPO2->addErrorMessage($this->GOLES_EQUIPO2->getErrorMessage(false));
        }
        if ($this->GOLES_EXTRA_EQUIPO1->Required) {
            if (!$this->GOLES_EXTRA_EQUIPO1->IsDetailKey && EmptyValue($this->GOLES_EXTRA_EQUIPO1->FormValue)) {
                $this->GOLES_EXTRA_EQUIPO1->addErrorMessage(str_replace("%s", $this->GOLES_EXTRA_EQUIPO1->caption(), $this->GOLES_EXTRA_EQUIPO1->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->GOLES_EXTRA_EQUIPO1->FormValue)) {
            $this->GOLES_EXTRA_EQUIPO1->addErrorMessage($this->GOLES_EXTRA_EQUIPO1->getErrorMessage(false));
        }
        if ($this->GOLES_EXTRA_EQUIPO2->Required) {
            if (!$this->GOLES_EXTRA_EQUIPO2->IsDetailKey && EmptyValue($this->GOLES_EXTRA_EQUIPO2->FormValue)) {
                $this->GOLES_EXTRA_EQUIPO2->addErrorMessage(str_replace("%s", $this->GOLES_EXTRA_EQUIPO2->caption(), $this->GOLES_EXTRA_EQUIPO2->RequiredErrorMessage));
            }
        }
        if (!CheckInteger($this->GOLES_EXTRA_EQUIPO2->FormValue)) {
            $this->GOLES_EXTRA_EQUIPO2->addErrorMessage($this->GOLES_EXTRA_EQUIPO2->getErrorMessage(false));
        }
        if ($this->NOTA_PARTIDO->Required) {
            if (!$this->NOTA_PARTIDO->IsDetailKey && EmptyValue($this->NOTA_PARTIDO->FormValue)) {
                $this->NOTA_PARTIDO->addErrorMessage(str_replace("%s", $this->NOTA_PARTIDO->caption(), $this->NOTA_PARTIDO->RequiredErrorMessage));
            }
        }
        if ($this->RESUMEN_PARTIDO->Required) {
            if (!$this->RESUMEN_PARTIDO->IsDetailKey && EmptyValue($this->RESUMEN_PARTIDO->FormValue)) {
                $this->RESUMEN_PARTIDO->addErrorMessage(str_replace("%s", $this->RESUMEN_PARTIDO->caption(), $this->RESUMEN_PARTIDO->RequiredErrorMessage));
            }
        }
        if ($this->ESTADO_PARTIDO->Required) {
            if (!$this->ESTADO_PARTIDO->IsDetailKey && EmptyValue($this->ESTADO_PARTIDO->FormValue)) {
                $this->ESTADO_PARTIDO->addErrorMessage(str_replace("%s", $this->ESTADO_PARTIDO->caption(), $this->ESTADO_PARTIDO->RequiredErrorMessage));
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

        // ID_EQUIPO2
        $this->ID_EQUIPO2->setDbValueDef($rsnew, $this->ID_EQUIPO2->CurrentValue, null, false);

        // ID_EQUIPO1
        $this->ID_EQUIPO1->setDbValueDef($rsnew, $this->ID_EQUIPO1->CurrentValue, null, false);

        // ID_TORNEO
        $this->ID_TORNEO->setDbValueDef($rsnew, $this->ID_TORNEO->CurrentValue, null, false);

        // FECHA_PARTIDO
        $this->FECHA_PARTIDO->setDbValueDef($rsnew, UnFormatDateTime($this->FECHA_PARTIDO->CurrentValue, $this->FECHA_PARTIDO->formatPattern()), null, false);

        // HORA_PARTIDO
        $this->HORA_PARTIDO->setDbValueDef($rsnew, UnFormatDateTime($this->HORA_PARTIDO->CurrentValue, $this->HORA_PARTIDO->formatPattern()), null, false);

        // DIA_PARTIDO
        $this->DIA_PARTIDO->setDbValueDef($rsnew, $this->DIA_PARTIDO->CurrentValue, null, false);

        // ESTADIO
        $this->ESTADIO->setDbValueDef($rsnew, $this->ESTADIO->CurrentValue, null, false);

        // CIUDAD_PARTIDO
        $this->CIUDAD_PARTIDO->setDbValueDef($rsnew, $this->CIUDAD_PARTIDO->CurrentValue, null, false);

        // PAIS_PARTIDO
        $this->PAIS_PARTIDO->setDbValueDef($rsnew, $this->PAIS_PARTIDO->CurrentValue, null, false);

        // GOLES_EQUIPO1
        $this->GOLES_EQUIPO1->setDbValueDef($rsnew, $this->GOLES_EQUIPO1->CurrentValue, null, false);

        // GOLES_EQUIPO2
        $this->GOLES_EQUIPO2->setDbValueDef($rsnew, $this->GOLES_EQUIPO2->CurrentValue, null, false);

        // GOLES_EXTRA_EQUIPO1
        $this->GOLES_EXTRA_EQUIPO1->setDbValueDef($rsnew, $this->GOLES_EXTRA_EQUIPO1->CurrentValue, null, false);

        // GOLES_EXTRA_EQUIPO2
        $this->GOLES_EXTRA_EQUIPO2->setDbValueDef($rsnew, $this->GOLES_EXTRA_EQUIPO2->CurrentValue, null, false);

        // NOTA_PARTIDO
        $this->NOTA_PARTIDO->setDbValueDef($rsnew, $this->NOTA_PARTIDO->CurrentValue, null, false);

        // RESUMEN_PARTIDO
        $this->RESUMEN_PARTIDO->setDbValueDef($rsnew, $this->RESUMEN_PARTIDO->CurrentValue, null, false);

        // ESTADO_PARTIDO
        $this->ESTADO_PARTIDO->setDbValueDef($rsnew, $this->ESTADO_PARTIDO->CurrentValue, null, false);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("PartidosList"), "", $this->TableVar, true);
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
                case "x_ID_EQUIPO2":
                    break;
                case "x_ID_EQUIPO1":
                    break;
                case "x_ID_TORNEO":
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
