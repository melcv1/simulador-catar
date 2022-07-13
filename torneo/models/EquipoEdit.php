<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class EquipoEdit extends Equipo
{
    use MessagesTrait;

    // Page ID
    public $PageID = "edit";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'equipo';

    // Page object name
    public $PageObjName = "EquipoEdit";

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

        // Table object (equipo)
        if (!isset($GLOBALS["equipo"]) || get_class($GLOBALS["equipo"]) == PROJECT_NAMESPACE . "equipo") {
            $GLOBALS["equipo"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'equipo');
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
                $tbl = Container("equipo");
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
                    if ($pageName == "EquipoView") {
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
            $key .= @$ar['ID_EQUIPO'];
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
            $this->ID_EQUIPO->Visible = false;
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

    // Properties
    public $FormClassName = "ew-form ew-edit-form";
    public $IsModal = false;
    public $IsMobileOrModal = false;
    public $DbMasterFilter;
    public $DbDetailFilter;
    public $HashValue; // Hash Value
    public $DisplayRecords = 1;
    public $StartRecord;
    public $StopRecord;
    public $TotalRecords = 0;
    public $RecordRange = 10;
    public $RecordCount;

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
        $this->ID_EQUIPO->setVisibility();
        $this->NOM_EQUIPO_CORTO->setVisibility();
        $this->NOM_EQUIPO_LARGO->setVisibility();
        $this->PAIS_EQUIPO->setVisibility();
        $this->REGION_EQUIPO->setVisibility();
        $this->DETALLE_EQUIPO->setVisibility();
        $this->ESCUDO_EQUIPO->setVisibility();
        $this->NOM_ESTADIO->setVisibility();
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

        // Check modal
        if ($this->IsModal) {
            $SkipHeaderFooter = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $this->FormClassName = "ew-form ew-edit-form";
        $loaded = false;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load key values
            $loaded = true;
            if (($keyValue = Get("ID_EQUIPO") ?? Key(0) ?? Route(2)) !== null) {
                $this->ID_EQUIPO->setQueryStringValue($keyValue);
                $this->ID_EQUIPO->setOldValue($this->ID_EQUIPO->QueryStringValue);
            } elseif (Post("ID_EQUIPO") !== null) {
                $this->ID_EQUIPO->setFormValue(Post("ID_EQUIPO"));
                $this->ID_EQUIPO->setOldValue($this->ID_EQUIPO->FormValue);
            } else {
                $loaded = false; // Unable to load key
            }

            // Load record
            if ($loaded) {
                $loaded = $this->loadRow();
            }
            if (!$loaded) {
                $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if (Post("action") !== null) {
                $this->CurrentAction = Post("action"); // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey(Post($this->OldKeyName), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from QueryString
                $loadByQuery = false;
                if (($keyValue = Get("ID_EQUIPO") ?? Route("ID_EQUIPO")) !== null) {
                    $this->ID_EQUIPO->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->ID_EQUIPO->CurrentValue = null;
                }
            }

            // Load recordset
            if ($this->isShow()) {
                    // Load current record
                    $loaded = $this->loadRow();
                $this->OldKey = $loaded ? $this->getKey(true) : ""; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                $this->restoreFormValues();
                if (IsApi()) {
                    $this->terminate();
                    return;
                } else {
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                    if (!$loaded) { // Load record based on key
                        if ($this->getFailureMessage() == "") {
                            $this->setFailureMessage($Language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("EquipoList"); // No matching record, return to list
                        return;
                    }
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "EquipoList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                $this->SendEmail = true; // Send email on update success
                if ($this->editRow()) { // Update record based on key
                    if ($this->getSuccessMessage() == "") {
                        $this->setSuccessMessage($Language->phrase("UpdateSuccess")); // Update success
                    }
                    if (IsApi()) {
                        $this->terminate(true);
                        return;
                    } else {
                        $this->terminate($returnUrl); // Return to caller
                        return;
                    }
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->getFailureMessage() == $Language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render the record
        $this->RowType = ROWTYPE_EDIT; // Render as Edit
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
        $this->ESCUDO_EQUIPO->Upload->Index = $CurrentForm->Index;
        $this->ESCUDO_EQUIPO->Upload->uploadFile();
    }

    // Load form values
    protected function loadFormValues()
    {
        // Load from form
        global $CurrentForm;
        $validate = !Config("SERVER_VALIDATE");

        // Check field name 'ID_EQUIPO' first before field var 'x_ID_EQUIPO'
        $val = $CurrentForm->hasValue("ID_EQUIPO") ? $CurrentForm->getValue("ID_EQUIPO") : $CurrentForm->getValue("x_ID_EQUIPO");
        if (!$this->ID_EQUIPO->IsDetailKey) {
            $this->ID_EQUIPO->setFormValue($val);
        }

        // Check field name 'NOM_EQUIPO_CORTO' first before field var 'x_NOM_EQUIPO_CORTO'
        $val = $CurrentForm->hasValue("NOM_EQUIPO_CORTO") ? $CurrentForm->getValue("NOM_EQUIPO_CORTO") : $CurrentForm->getValue("x_NOM_EQUIPO_CORTO");
        if (!$this->NOM_EQUIPO_CORTO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NOM_EQUIPO_CORTO->Visible = false; // Disable update for API request
            } else {
                $this->NOM_EQUIPO_CORTO->setFormValue($val);
            }
        }

        // Check field name 'NOM_EQUIPO_LARGO' first before field var 'x_NOM_EQUIPO_LARGO'
        $val = $CurrentForm->hasValue("NOM_EQUIPO_LARGO") ? $CurrentForm->getValue("NOM_EQUIPO_LARGO") : $CurrentForm->getValue("x_NOM_EQUIPO_LARGO");
        if (!$this->NOM_EQUIPO_LARGO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NOM_EQUIPO_LARGO->Visible = false; // Disable update for API request
            } else {
                $this->NOM_EQUIPO_LARGO->setFormValue($val);
            }
        }

        // Check field name 'PAIS_EQUIPO' first before field var 'x_PAIS_EQUIPO'
        $val = $CurrentForm->hasValue("PAIS_EQUIPO") ? $CurrentForm->getValue("PAIS_EQUIPO") : $CurrentForm->getValue("x_PAIS_EQUIPO");
        if (!$this->PAIS_EQUIPO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->PAIS_EQUIPO->Visible = false; // Disable update for API request
            } else {
                $this->PAIS_EQUIPO->setFormValue($val);
            }
        }

        // Check field name 'REGION_EQUIPO' first before field var 'x_REGION_EQUIPO'
        $val = $CurrentForm->hasValue("REGION_EQUIPO") ? $CurrentForm->getValue("REGION_EQUIPO") : $CurrentForm->getValue("x_REGION_EQUIPO");
        if (!$this->REGION_EQUIPO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->REGION_EQUIPO->Visible = false; // Disable update for API request
            } else {
                $this->REGION_EQUIPO->setFormValue($val);
            }
        }

        // Check field name 'DETALLE_EQUIPO' first before field var 'x_DETALLE_EQUIPO'
        $val = $CurrentForm->hasValue("DETALLE_EQUIPO") ? $CurrentForm->getValue("DETALLE_EQUIPO") : $CurrentForm->getValue("x_DETALLE_EQUIPO");
        if (!$this->DETALLE_EQUIPO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->DETALLE_EQUIPO->Visible = false; // Disable update for API request
            } else {
                $this->DETALLE_EQUIPO->setFormValue($val);
            }
        }

        // Check field name 'NOM_ESTADIO' first before field var 'x_NOM_ESTADIO'
        $val = $CurrentForm->hasValue("NOM_ESTADIO") ? $CurrentForm->getValue("NOM_ESTADIO") : $CurrentForm->getValue("x_NOM_ESTADIO");
        if (!$this->NOM_ESTADIO->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->NOM_ESTADIO->Visible = false; // Disable update for API request
            } else {
                $this->NOM_ESTADIO->setFormValue($val);
            }
        }
        $this->getUploadFiles(); // Get upload files
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->ID_EQUIPO->CurrentValue = $this->ID_EQUIPO->FormValue;
        $this->NOM_EQUIPO_CORTO->CurrentValue = $this->NOM_EQUIPO_CORTO->FormValue;
        $this->NOM_EQUIPO_LARGO->CurrentValue = $this->NOM_EQUIPO_LARGO->FormValue;
        $this->PAIS_EQUIPO->CurrentValue = $this->PAIS_EQUIPO->FormValue;
        $this->REGION_EQUIPO->CurrentValue = $this->REGION_EQUIPO->FormValue;
        $this->DETALLE_EQUIPO->CurrentValue = $this->DETALLE_EQUIPO->FormValue;
        $this->NOM_ESTADIO->CurrentValue = $this->NOM_ESTADIO->FormValue;
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
        $this->ID_EQUIPO->setDbValue($row['ID_EQUIPO']);
        $this->NOM_EQUIPO_CORTO->setDbValue($row['NOM_EQUIPO_CORTO']);
        $this->NOM_EQUIPO_LARGO->setDbValue($row['NOM_EQUIPO_LARGO']);
        $this->PAIS_EQUIPO->setDbValue($row['PAIS_EQUIPO']);
        $this->REGION_EQUIPO->setDbValue($row['REGION_EQUIPO']);
        $this->DETALLE_EQUIPO->setDbValue($row['DETALLE_EQUIPO']);
        $this->ESCUDO_EQUIPO->Upload->DbValue = $row['ESCUDO_EQUIPO'];
        if (is_resource($this->ESCUDO_EQUIPO->Upload->DbValue) && get_resource_type($this->ESCUDO_EQUIPO->Upload->DbValue) == "stream") { // Byte array
            $this->ESCUDO_EQUIPO->Upload->DbValue = stream_get_contents($this->ESCUDO_EQUIPO->Upload->DbValue);
        }
        $this->NOM_ESTADIO->setDbValue($row['NOM_ESTADIO']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['ID_EQUIPO'] = $this->ID_EQUIPO->DefaultValue;
        $row['NOM_EQUIPO_CORTO'] = $this->NOM_EQUIPO_CORTO->DefaultValue;
        $row['NOM_EQUIPO_LARGO'] = $this->NOM_EQUIPO_LARGO->DefaultValue;
        $row['PAIS_EQUIPO'] = $this->PAIS_EQUIPO->DefaultValue;
        $row['REGION_EQUIPO'] = $this->REGION_EQUIPO->DefaultValue;
        $row['DETALLE_EQUIPO'] = $this->DETALLE_EQUIPO->DefaultValue;
        $row['ESCUDO_EQUIPO'] = $this->ESCUDO_EQUIPO->DefaultValue;
        $row['NOM_ESTADIO'] = $this->NOM_ESTADIO->DefaultValue;
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

        // ID_EQUIPO
        $this->ID_EQUIPO->RowCssClass = "row";

        // NOM_EQUIPO_CORTO
        $this->NOM_EQUIPO_CORTO->RowCssClass = "row";

        // NOM_EQUIPO_LARGO
        $this->NOM_EQUIPO_LARGO->RowCssClass = "row";

        // PAIS_EQUIPO
        $this->PAIS_EQUIPO->RowCssClass = "row";

        // REGION_EQUIPO
        $this->REGION_EQUIPO->RowCssClass = "row";

        // DETALLE_EQUIPO
        $this->DETALLE_EQUIPO->RowCssClass = "row";

        // ESCUDO_EQUIPO
        $this->ESCUDO_EQUIPO->RowCssClass = "row";

        // NOM_ESTADIO
        $this->NOM_ESTADIO->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
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

            // NOM_EQUIPO_CORTO
            $this->NOM_EQUIPO_CORTO->LinkCustomAttributes = "";
            $this->NOM_EQUIPO_CORTO->HrefValue = "";

            // NOM_EQUIPO_LARGO
            $this->NOM_EQUIPO_LARGO->LinkCustomAttributes = "";
            $this->NOM_EQUIPO_LARGO->HrefValue = "";

            // PAIS_EQUIPO
            $this->PAIS_EQUIPO->LinkCustomAttributes = "";
            $this->PAIS_EQUIPO->HrefValue = "";

            // REGION_EQUIPO
            $this->REGION_EQUIPO->LinkCustomAttributes = "";
            $this->REGION_EQUIPO->HrefValue = "";

            // DETALLE_EQUIPO
            $this->DETALLE_EQUIPO->LinkCustomAttributes = "";
            $this->DETALLE_EQUIPO->HrefValue = "";

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

            // NOM_ESTADIO
            $this->NOM_ESTADIO->LinkCustomAttributes = "";
            $this->NOM_ESTADIO->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_EDIT) {
            // ID_EQUIPO
            $this->ID_EQUIPO->setupEditAttributes();
            $this->ID_EQUIPO->EditCustomAttributes = "";
            $this->ID_EQUIPO->EditValue = $this->ID_EQUIPO->CurrentValue;
            $this->ID_EQUIPO->ViewCustomAttributes = "";

            // NOM_EQUIPO_CORTO
            $this->NOM_EQUIPO_CORTO->setupEditAttributes();
            $this->NOM_EQUIPO_CORTO->EditCustomAttributes = "";
            $this->NOM_EQUIPO_CORTO->EditValue = HtmlEncode($this->NOM_EQUIPO_CORTO->CurrentValue);
            $this->NOM_EQUIPO_CORTO->PlaceHolder = RemoveHtml($this->NOM_EQUIPO_CORTO->caption());

            // NOM_EQUIPO_LARGO
            $this->NOM_EQUIPO_LARGO->setupEditAttributes();
            $this->NOM_EQUIPO_LARGO->EditCustomAttributes = "";
            $this->NOM_EQUIPO_LARGO->EditValue = HtmlEncode($this->NOM_EQUIPO_LARGO->CurrentValue);
            $this->NOM_EQUIPO_LARGO->PlaceHolder = RemoveHtml($this->NOM_EQUIPO_LARGO->caption());

            // PAIS_EQUIPO
            $this->PAIS_EQUIPO->setupEditAttributes();
            $this->PAIS_EQUIPO->EditCustomAttributes = "";
            $this->PAIS_EQUIPO->EditValue = HtmlEncode($this->PAIS_EQUIPO->CurrentValue);
            $this->PAIS_EQUIPO->PlaceHolder = RemoveHtml($this->PAIS_EQUIPO->caption());

            // REGION_EQUIPO
            $this->REGION_EQUIPO->setupEditAttributes();
            $this->REGION_EQUIPO->EditCustomAttributes = "";
            $this->REGION_EQUIPO->EditValue = HtmlEncode($this->REGION_EQUIPO->CurrentValue);
            $this->REGION_EQUIPO->PlaceHolder = RemoveHtml($this->REGION_EQUIPO->caption());

            // DETALLE_EQUIPO
            $this->DETALLE_EQUIPO->setupEditAttributes();
            $this->DETALLE_EQUIPO->EditCustomAttributes = "";
            $this->DETALLE_EQUIPO->EditValue = HtmlEncode($this->DETALLE_EQUIPO->CurrentValue);
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
            if ($this->isShow()) {
                RenderUploadField($this->ESCUDO_EQUIPO);
            }

            // NOM_ESTADIO
            $this->NOM_ESTADIO->setupEditAttributes();
            $this->NOM_ESTADIO->EditCustomAttributes = "";
            $this->NOM_ESTADIO->EditValue = HtmlEncode($this->NOM_ESTADIO->CurrentValue);
            $this->NOM_ESTADIO->PlaceHolder = RemoveHtml($this->NOM_ESTADIO->caption());

            // Edit refer script

            // ID_EQUIPO
            $this->ID_EQUIPO->LinkCustomAttributes = "";
            $this->ID_EQUIPO->HrefValue = "";

            // NOM_EQUIPO_CORTO
            $this->NOM_EQUIPO_CORTO->LinkCustomAttributes = "";
            $this->NOM_EQUIPO_CORTO->HrefValue = "";

            // NOM_EQUIPO_LARGO
            $this->NOM_EQUIPO_LARGO->LinkCustomAttributes = "";
            $this->NOM_EQUIPO_LARGO->HrefValue = "";

            // PAIS_EQUIPO
            $this->PAIS_EQUIPO->LinkCustomAttributes = "";
            $this->PAIS_EQUIPO->HrefValue = "";

            // REGION_EQUIPO
            $this->REGION_EQUIPO->LinkCustomAttributes = "";
            $this->REGION_EQUIPO->HrefValue = "";

            // DETALLE_EQUIPO
            $this->DETALLE_EQUIPO->LinkCustomAttributes = "";
            $this->DETALLE_EQUIPO->HrefValue = "";

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

            // NOM_ESTADIO
            $this->NOM_ESTADIO->LinkCustomAttributes = "";
            $this->NOM_ESTADIO->HrefValue = "";
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
        if ($this->ID_EQUIPO->Required) {
            if (!$this->ID_EQUIPO->IsDetailKey && EmptyValue($this->ID_EQUIPO->FormValue)) {
                $this->ID_EQUIPO->addErrorMessage(str_replace("%s", $this->ID_EQUIPO->caption(), $this->ID_EQUIPO->RequiredErrorMessage));
            }
        }
        if ($this->NOM_EQUIPO_CORTO->Required) {
            if (!$this->NOM_EQUIPO_CORTO->IsDetailKey && EmptyValue($this->NOM_EQUIPO_CORTO->FormValue)) {
                $this->NOM_EQUIPO_CORTO->addErrorMessage(str_replace("%s", $this->NOM_EQUIPO_CORTO->caption(), $this->NOM_EQUIPO_CORTO->RequiredErrorMessage));
            }
        }
        if ($this->NOM_EQUIPO_LARGO->Required) {
            if (!$this->NOM_EQUIPO_LARGO->IsDetailKey && EmptyValue($this->NOM_EQUIPO_LARGO->FormValue)) {
                $this->NOM_EQUIPO_LARGO->addErrorMessage(str_replace("%s", $this->NOM_EQUIPO_LARGO->caption(), $this->NOM_EQUIPO_LARGO->RequiredErrorMessage));
            }
        }
        if ($this->PAIS_EQUIPO->Required) {
            if (!$this->PAIS_EQUIPO->IsDetailKey && EmptyValue($this->PAIS_EQUIPO->FormValue)) {
                $this->PAIS_EQUIPO->addErrorMessage(str_replace("%s", $this->PAIS_EQUIPO->caption(), $this->PAIS_EQUIPO->RequiredErrorMessage));
            }
        }
        if ($this->REGION_EQUIPO->Required) {
            if (!$this->REGION_EQUIPO->IsDetailKey && EmptyValue($this->REGION_EQUIPO->FormValue)) {
                $this->REGION_EQUIPO->addErrorMessage(str_replace("%s", $this->REGION_EQUIPO->caption(), $this->REGION_EQUIPO->RequiredErrorMessage));
            }
        }
        if ($this->DETALLE_EQUIPO->Required) {
            if (!$this->DETALLE_EQUIPO->IsDetailKey && EmptyValue($this->DETALLE_EQUIPO->FormValue)) {
                $this->DETALLE_EQUIPO->addErrorMessage(str_replace("%s", $this->DETALLE_EQUIPO->caption(), $this->DETALLE_EQUIPO->RequiredErrorMessage));
            }
        }
        if ($this->ESCUDO_EQUIPO->Required) {
            if ($this->ESCUDO_EQUIPO->Upload->FileName == "" && !$this->ESCUDO_EQUIPO->Upload->KeepFile) {
                $this->ESCUDO_EQUIPO->addErrorMessage(str_replace("%s", $this->ESCUDO_EQUIPO->caption(), $this->ESCUDO_EQUIPO->RequiredErrorMessage));
            }
        }
        if ($this->NOM_ESTADIO->Required) {
            if (!$this->NOM_ESTADIO->IsDetailKey && EmptyValue($this->NOM_ESTADIO->FormValue)) {
                $this->NOM_ESTADIO->addErrorMessage(str_replace("%s", $this->NOM_ESTADIO->caption(), $this->NOM_ESTADIO->RequiredErrorMessage));
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

    // Update record based on key values
    protected function editRow()
    {
        global $Security, $Language;
        $oldKeyFilter = $this->getRecordFilter();
        $filter = $this->applyUserIDFilters($oldKeyFilter);
        $conn = $this->getConnection();

        // Load old row
        $this->CurrentFilter = $filter;
        $sql = $this->getCurrentSql();
        $rsold = $conn->fetchAssociative($sql);
        if (!$rsold) {
            $this->setFailureMessage($Language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        } else {
            // Save old values
            $this->loadDbValues($rsold);
        }

        // Set new row
        $rsnew = [];

        // NOM_EQUIPO_CORTO
        $this->NOM_EQUIPO_CORTO->setDbValueDef($rsnew, $this->NOM_EQUIPO_CORTO->CurrentValue, null, $this->NOM_EQUIPO_CORTO->ReadOnly);

        // NOM_EQUIPO_LARGO
        $this->NOM_EQUIPO_LARGO->setDbValueDef($rsnew, $this->NOM_EQUIPO_LARGO->CurrentValue, null, $this->NOM_EQUIPO_LARGO->ReadOnly);

        // PAIS_EQUIPO
        $this->PAIS_EQUIPO->setDbValueDef($rsnew, $this->PAIS_EQUIPO->CurrentValue, null, $this->PAIS_EQUIPO->ReadOnly);

        // REGION_EQUIPO
        $this->REGION_EQUIPO->setDbValueDef($rsnew, $this->REGION_EQUIPO->CurrentValue, null, $this->REGION_EQUIPO->ReadOnly);

        // DETALLE_EQUIPO
        $this->DETALLE_EQUIPO->setDbValueDef($rsnew, $this->DETALLE_EQUIPO->CurrentValue, null, $this->DETALLE_EQUIPO->ReadOnly);

        // ESCUDO_EQUIPO
        if ($this->ESCUDO_EQUIPO->Visible && !$this->ESCUDO_EQUIPO->ReadOnly && !$this->ESCUDO_EQUIPO->Upload->KeepFile) {
            if ($this->ESCUDO_EQUIPO->Upload->Value === null) {
                $rsnew['ESCUDO_EQUIPO'] = null;
            } else {
                $rsnew['ESCUDO_EQUIPO'] = $this->ESCUDO_EQUIPO->Upload->Value;
            }
        }

        // NOM_ESTADIO
        $this->NOM_ESTADIO->setDbValueDef($rsnew, $this->NOM_ESTADIO->CurrentValue, null, $this->NOM_ESTADIO->ReadOnly);

        // Update current values
        $this->setCurrentValues($rsnew);

        // Call Row Updating event
        $updateRow = $this->rowUpdating($rsold, $rsnew);
        if ($updateRow) {
            if (count($rsnew) > 0) {
                $this->CurrentFilter = $filter; // Set up current filter
                $editRow = $this->update($rsnew, "", $rsold);
            } else {
                $editRow = true; // No field to update
            }
            if ($editRow) {
            }
        } else {
            if ($this->getSuccessMessage() != "" || $this->getFailureMessage() != "") {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($Language->phrase("UpdateCancelled"));
            }
            $editRow = false;
        }

        // Call Row_Updated event
        if ($editRow) {
            $this->rowUpdated($rsold, $rsnew);
        }

        // Clean upload path if any
        if ($editRow) {
            // ESCUDO_EQUIPO
            CleanUploadTempPath($this->ESCUDO_EQUIPO, $this->ESCUDO_EQUIPO->Upload->Index);
        }

        // Write JSON for API request
        if (IsApi() && $editRow) {
            $row = $this->getRecordsFromRecordset([$rsnew], true);
            WriteJson(["success" => true, $this->TableVar => $row]);
        }
        return $editRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb()
    {
        global $Breadcrumb, $Language;
        $Breadcrumb = new Breadcrumb("index");
        $url = CurrentUrl();
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("EquipoList"), "", $this->TableVar, true);
        $pageId = "edit";
        $Breadcrumb->add("edit", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord()
    {
        if ($this->DisplayRecords == 0) {
            return;
        }
        if ($this->isPageRequest()) { // Validate request
            $startRec = Get(Config("TABLE_START_REC"));
            if ($startRec !== null && is_numeric($startRec)) { // Check for "start" parameter
                $this->StartRecord = $startRec;
                $this->setStartRecordNumber($this->StartRecord);
            }
        }
        $this->StartRecord = $this->getStartRecordNumber();

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || $this->StartRecord == "") { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
            $this->setStartRecordNumber($this->StartRecord);
        } elseif ($this->StartRecord > $this->TotalRecords) { // Avoid starting record > total records
            $this->StartRecord = (int)(($this->TotalRecords - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to last page first record
            $this->setStartRecordNumber($this->StartRecord);
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
            $this->setStartRecordNumber($this->StartRecord);
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
