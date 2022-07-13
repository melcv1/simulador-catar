<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class TorneoAdd extends Torneo
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'torneo';

    // Page object name
    public $PageObjName = "TorneoAdd";

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

        // Table object (torneo)
        if (!isset($GLOBALS["torneo"]) || get_class($GLOBALS["torneo"]) == PROJECT_NAMESPACE . "torneo") {
            $GLOBALS["torneo"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'torneo');
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
                $tbl = Container("torneo");
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
                    if ($pageName == "TorneoView") {
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
            $key .= @$ar['ID_TORNEO'];
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
            $this->ID_TORNEO->Visible = false;
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
        $this->ID_TORNEO->Visible = false;
        $this->NOM_TORNEO_CORTO->setVisibility();
        $this->NOM_TORNEO_LARGO->setVisibility();
        $this->PAIS_TORNEO->setVisibility();
        $this->REGION_TORNEO->setVisibility();
        $this->DETALLE_TORNEO->setVisibility();
        $this->LOGO_TORNEO->setVisibility();
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
            if (($keyValue = Get("ID_TORNEO") ?? Route("ID_TORNEO")) !== null) {
                $this->ID_TORNEO->setQueryStringValue($keyValue);
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
                    $this->terminate("TorneoList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "TorneoList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "TorneoView") {
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
        $this->LOGO_TORNEO->Upload->Index = $CurrentForm->Index;
        $this->LOGO_TORNEO->Upload->uploadFile();
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

        // Check field name 'NOM_TORNEO_CORTO' first before field var 'x_NOM_TORNEO_CORTO'
        $val = $CurrentForm->hasValue("NOM_TORNEO_CORTO") ? $CurrentForm->getValue("NOM_TORNEO_CORTO") : $CurrentForm->getValue("x_NOM_TORNEO_CORTO");
        if (!$this->NOM_TORNEO_CORTO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NOM_TORNEO_CORTO->Visible = false; // Disable update for API request
            } else {
                $this->NOM_TORNEO_CORTO->setFormValue($val);
            }
        }

        // Check field name 'NOM_TORNEO_LARGO' first before field var 'x_NOM_TORNEO_LARGO'
        $val = $CurrentForm->hasValue("NOM_TORNEO_LARGO") ? $CurrentForm->getValue("NOM_TORNEO_LARGO") : $CurrentForm->getValue("x_NOM_TORNEO_LARGO");
        if (!$this->NOM_TORNEO_LARGO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NOM_TORNEO_LARGO->Visible = false; // Disable update for API request
            } else {
                $this->NOM_TORNEO_LARGO->setFormValue($val);
            }
        }

        // Check field name 'PAIS_TORNEO' first before field var 'x_PAIS_TORNEO'
        $val = $CurrentForm->hasValue("PAIS_TORNEO") ? $CurrentForm->getValue("PAIS_TORNEO") : $CurrentForm->getValue("x_PAIS_TORNEO");
        if (!$this->PAIS_TORNEO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PAIS_TORNEO->Visible = false; // Disable update for API request
            } else {
                $this->PAIS_TORNEO->setFormValue($val);
            }
        }

        // Check field name 'REGION_TORNEO' first before field var 'x_REGION_TORNEO'
        $val = $CurrentForm->hasValue("REGION_TORNEO") ? $CurrentForm->getValue("REGION_TORNEO") : $CurrentForm->getValue("x_REGION_TORNEO");
        if (!$this->REGION_TORNEO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->REGION_TORNEO->Visible = false; // Disable update for API request
            } else {
                $this->REGION_TORNEO->setFormValue($val);
            }
        }

        // Check field name 'DETALLE_TORNEO' first before field var 'x_DETALLE_TORNEO'
        $val = $CurrentForm->hasValue("DETALLE_TORNEO") ? $CurrentForm->getValue("DETALLE_TORNEO") : $CurrentForm->getValue("x_DETALLE_TORNEO");
        if (!$this->DETALLE_TORNEO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->DETALLE_TORNEO->Visible = false; // Disable update for API request
            } else {
                $this->DETALLE_TORNEO->setFormValue($val);
            }
        }

        // Check field name 'ID_TORNEO' first before field var 'x_ID_TORNEO'
        $val = $CurrentForm->hasValue("ID_TORNEO") ? $CurrentForm->getValue("ID_TORNEO") : $CurrentForm->getValue("x_ID_TORNEO");
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->NOM_TORNEO_CORTO->CurrentValue = $this->NOM_TORNEO_CORTO->FormValue;
        $this->NOM_TORNEO_LARGO->CurrentValue = $this->NOM_TORNEO_LARGO->FormValue;
        $this->PAIS_TORNEO->CurrentValue = $this->PAIS_TORNEO->FormValue;
        $this->REGION_TORNEO->CurrentValue = $this->REGION_TORNEO->FormValue;
        $this->DETALLE_TORNEO->CurrentValue = $this->DETALLE_TORNEO->FormValue;
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
        $this->ID_TORNEO->setDbValue($row['ID_TORNEO']);
        $this->NOM_TORNEO_CORTO->setDbValue($row['NOM_TORNEO_CORTO']);
        $this->NOM_TORNEO_LARGO->setDbValue($row['NOM_TORNEO_LARGO']);
        $this->PAIS_TORNEO->setDbValue($row['PAIS_TORNEO']);
        $this->REGION_TORNEO->setDbValue($row['REGION_TORNEO']);
        $this->DETALLE_TORNEO->setDbValue($row['DETALLE_TORNEO']);
        $this->LOGO_TORNEO->Upload->DbValue = $row['LOGO_TORNEO'];
        if (is_resource($this->LOGO_TORNEO->Upload->DbValue) && get_resource_type($this->LOGO_TORNEO->Upload->DbValue) == "stream") { // Byte array
            $this->LOGO_TORNEO->Upload->DbValue = stream_get_contents($this->LOGO_TORNEO->Upload->DbValue);
        }
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ID_TORNEO'] = $this->ID_TORNEO->DefaultValue;
        $row['NOM_TORNEO_CORTO'] = $this->NOM_TORNEO_CORTO->DefaultValue;
        $row['NOM_TORNEO_LARGO'] = $this->NOM_TORNEO_LARGO->DefaultValue;
        $row['PAIS_TORNEO'] = $this->PAIS_TORNEO->DefaultValue;
        $row['REGION_TORNEO'] = $this->REGION_TORNEO->DefaultValue;
        $row['DETALLE_TORNEO'] = $this->DETALLE_TORNEO->DefaultValue;
        $row['LOGO_TORNEO'] = $this->LOGO_TORNEO->DefaultValue;
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

        // ID_TORNEO
        $this->ID_TORNEO->RowCssClass = "row";

        // NOM_TORNEO_CORTO
        $this->NOM_TORNEO_CORTO->RowCssClass = "row";

        // NOM_TORNEO_LARGO
        $this->NOM_TORNEO_LARGO->RowCssClass = "row";

        // PAIS_TORNEO
        $this->PAIS_TORNEO->RowCssClass = "row";

        // REGION_TORNEO
        $this->REGION_TORNEO->RowCssClass = "row";

        // DETALLE_TORNEO
        $this->DETALLE_TORNEO->RowCssClass = "row";

        // LOGO_TORNEO
        $this->LOGO_TORNEO->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
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

            // NOM_TORNEO_CORTO
            $this->NOM_TORNEO_CORTO->LinkCustomAttributes = "";
            $this->NOM_TORNEO_CORTO->HrefValue = "";

            // NOM_TORNEO_LARGO
            $this->NOM_TORNEO_LARGO->LinkCustomAttributes = "";
            $this->NOM_TORNEO_LARGO->HrefValue = "";

            // PAIS_TORNEO
            $this->PAIS_TORNEO->LinkCustomAttributes = "";
            $this->PAIS_TORNEO->HrefValue = "";

            // REGION_TORNEO
            $this->REGION_TORNEO->LinkCustomAttributes = "";
            $this->REGION_TORNEO->HrefValue = "";

            // DETALLE_TORNEO
            $this->DETALLE_TORNEO->LinkCustomAttributes = "";
            $this->DETALLE_TORNEO->HrefValue = "";

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
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // NOM_TORNEO_CORTO
            $this->NOM_TORNEO_CORTO->setupEditAttributes();
            $this->NOM_TORNEO_CORTO->EditCustomAttributes = "";
            $this->NOM_TORNEO_CORTO->EditValue = HtmlEncode($this->NOM_TORNEO_CORTO->CurrentValue);
            $this->NOM_TORNEO_CORTO->PlaceHolder = RemoveHtml($this->NOM_TORNEO_CORTO->caption());

            // NOM_TORNEO_LARGO
            $this->NOM_TORNEO_LARGO->setupEditAttributes();
            $this->NOM_TORNEO_LARGO->EditCustomAttributes = "";
            $this->NOM_TORNEO_LARGO->EditValue = HtmlEncode($this->NOM_TORNEO_LARGO->CurrentValue);
            $this->NOM_TORNEO_LARGO->PlaceHolder = RemoveHtml($this->NOM_TORNEO_LARGO->caption());

            // PAIS_TORNEO
            $this->PAIS_TORNEO->setupEditAttributes();
            $this->PAIS_TORNEO->EditCustomAttributes = "";
            $this->PAIS_TORNEO->EditValue = HtmlEncode($this->PAIS_TORNEO->CurrentValue);
            $this->PAIS_TORNEO->PlaceHolder = RemoveHtml($this->PAIS_TORNEO->caption());

            // REGION_TORNEO
            $this->REGION_TORNEO->setupEditAttributes();
            $this->REGION_TORNEO->EditCustomAttributes = "";
            $this->REGION_TORNEO->EditValue = HtmlEncode($this->REGION_TORNEO->CurrentValue);
            $this->REGION_TORNEO->PlaceHolder = RemoveHtml($this->REGION_TORNEO->caption());

            // DETALLE_TORNEO
            $this->DETALLE_TORNEO->setupEditAttributes();
            $this->DETALLE_TORNEO->EditCustomAttributes = "";
            $this->DETALLE_TORNEO->EditValue = HtmlEncode($this->DETALLE_TORNEO->CurrentValue);
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
            if ($this->isShow() || $this->isCopy()) {
                RenderUploadField($this->LOGO_TORNEO);
            }

            // Add refer script

            // NOM_TORNEO_CORTO
            $this->NOM_TORNEO_CORTO->LinkCustomAttributes = "";
            $this->NOM_TORNEO_CORTO->HrefValue = "";

            // NOM_TORNEO_LARGO
            $this->NOM_TORNEO_LARGO->LinkCustomAttributes = "";
            $this->NOM_TORNEO_LARGO->HrefValue = "";

            // PAIS_TORNEO
            $this->PAIS_TORNEO->LinkCustomAttributes = "";
            $this->PAIS_TORNEO->HrefValue = "";

            // REGION_TORNEO
            $this->REGION_TORNEO->LinkCustomAttributes = "";
            $this->REGION_TORNEO->HrefValue = "";

            // DETALLE_TORNEO
            $this->DETALLE_TORNEO->LinkCustomAttributes = "";
            $this->DETALLE_TORNEO->HrefValue = "";

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
        if ($this->NOM_TORNEO_CORTO->Required) {
            if (!$this->NOM_TORNEO_CORTO->IsDetailKey && EmptyValue($this->NOM_TORNEO_CORTO->FormValue)) {
                $this->NOM_TORNEO_CORTO->addErrorMessage(str_replace("%s", $this->NOM_TORNEO_CORTO->caption(), $this->NOM_TORNEO_CORTO->RequiredErrorMessage));
            }
        }
        if ($this->NOM_TORNEO_LARGO->Required) {
            if (!$this->NOM_TORNEO_LARGO->IsDetailKey && EmptyValue($this->NOM_TORNEO_LARGO->FormValue)) {
                $this->NOM_TORNEO_LARGO->addErrorMessage(str_replace("%s", $this->NOM_TORNEO_LARGO->caption(), $this->NOM_TORNEO_LARGO->RequiredErrorMessage));
            }
        }
        if ($this->PAIS_TORNEO->Required) {
            if (!$this->PAIS_TORNEO->IsDetailKey && EmptyValue($this->PAIS_TORNEO->FormValue)) {
                $this->PAIS_TORNEO->addErrorMessage(str_replace("%s", $this->PAIS_TORNEO->caption(), $this->PAIS_TORNEO->RequiredErrorMessage));
            }
        }
        if ($this->REGION_TORNEO->Required) {
            if (!$this->REGION_TORNEO->IsDetailKey && EmptyValue($this->REGION_TORNEO->FormValue)) {
                $this->REGION_TORNEO->addErrorMessage(str_replace("%s", $this->REGION_TORNEO->caption(), $this->REGION_TORNEO->RequiredErrorMessage));
            }
        }
        if ($this->DETALLE_TORNEO->Required) {
            if (!$this->DETALLE_TORNEO->IsDetailKey && EmptyValue($this->DETALLE_TORNEO->FormValue)) {
                $this->DETALLE_TORNEO->addErrorMessage(str_replace("%s", $this->DETALLE_TORNEO->caption(), $this->DETALLE_TORNEO->RequiredErrorMessage));
            }
        }
        if ($this->LOGO_TORNEO->Required) {
            if ($this->LOGO_TORNEO->Upload->FileName == "" && !$this->LOGO_TORNEO->Upload->KeepFile) {
                $this->LOGO_TORNEO->addErrorMessage(str_replace("%s", $this->LOGO_TORNEO->caption(), $this->LOGO_TORNEO->RequiredErrorMessage));
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

        // NOM_TORNEO_CORTO
        $this->NOM_TORNEO_CORTO->setDbValueDef($rsnew, $this->NOM_TORNEO_CORTO->CurrentValue, null, false);

        // NOM_TORNEO_LARGO
        $this->NOM_TORNEO_LARGO->setDbValueDef($rsnew, $this->NOM_TORNEO_LARGO->CurrentValue, null, false);

        // PAIS_TORNEO
        $this->PAIS_TORNEO->setDbValueDef($rsnew, $this->PAIS_TORNEO->CurrentValue, null, false);

        // REGION_TORNEO
        $this->REGION_TORNEO->setDbValueDef($rsnew, $this->REGION_TORNEO->CurrentValue, null, false);

        // DETALLE_TORNEO
        $this->DETALLE_TORNEO->setDbValueDef($rsnew, $this->DETALLE_TORNEO->CurrentValue, null, false);

        // LOGO_TORNEO
        if ($this->LOGO_TORNEO->Visible && !$this->LOGO_TORNEO->Upload->KeepFile) {
            if ($this->LOGO_TORNEO->Upload->Value === null) {
                $rsnew['LOGO_TORNEO'] = null;
            } else {
                $rsnew['LOGO_TORNEO'] = $this->LOGO_TORNEO->Upload->Value;
            }
        }

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
            // LOGO_TORNEO
            CleanUploadTempPath($this->LOGO_TORNEO, $this->LOGO_TORNEO->Upload->Index);
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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("TorneoList"), "", $this->TableVar, true);
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
