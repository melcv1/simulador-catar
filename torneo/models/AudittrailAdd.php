<?php

namespace PHPMaker2022\project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

/**
 * Page class
 */
class AudittrailAdd extends Audittrail
{
    use MessagesTrait;

    // Page ID
    public $PageID = "add";

    // Project ID
    public $ProjectID = PROJECT_ID;

    // Table name
    public $TableName = 'audittrail';

    // Page object name
    public $PageObjName = "AudittrailAdd";

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

        // Table object (audittrail)
        if (!isset($GLOBALS["audittrail"]) || get_class($GLOBALS["audittrail"]) == PROJECT_NAMESPACE . "audittrail") {
            $GLOBALS["audittrail"] = &$this;
        }

        // Table name (for backward compatibility only)
        if (!defined(PROJECT_NAMESPACE . "TABLE_NAME")) {
            define(PROJECT_NAMESPACE . "TABLE_NAME", 'audittrail');
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
                $tbl = Container("audittrail");
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
                    if ($pageName == "AudittrailView") {
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
            $key .= @$ar['id'];
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
            $this->id->Visible = false;
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
        $this->id->Visible = false;
        $this->datetime->setVisibility();
        $this->script->setVisibility();
        $this->user->setVisibility();
        $this->_action->setVisibility();
        $this->_table->setVisibility();
        $this->field->setVisibility();
        $this->keyvalue->setVisibility();
        $this->oldvalue->setVisibility();
        $this->newvalue->setVisibility();
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
            if (($keyValue = Get("id") ?? Route("id")) !== null) {
                $this->id->setQueryStringValue($keyValue);
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
                    $this->terminate("AudittrailList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "AudittrailList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "AudittrailView") {
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

        // Check field name 'datetime' first before field var 'x_datetime'
        $val = $CurrentForm->hasValue("datetime") ? $CurrentForm->getValue("datetime") : $CurrentForm->getValue("x_datetime");
        if (!$this->datetime->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->datetime->Visible = false; // Disable update for API request
            } else {
                $this->datetime->setFormValue($val, true, $validate);
            }
            $this->datetime->CurrentValue = UnFormatDateTime($this->datetime->CurrentValue, $this->datetime->formatPattern());
        }

        // Check field name 'script' first before field var 'x_script'
        $val = $CurrentForm->hasValue("script") ? $CurrentForm->getValue("script") : $CurrentForm->getValue("x_script");
        if (!$this->script->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->script->Visible = false; // Disable update for API request
            } else {
                $this->script->setFormValue($val);
            }
        }

        // Check field name 'user' first before field var 'x_user'
        $val = $CurrentForm->hasValue("user") ? $CurrentForm->getValue("user") : $CurrentForm->getValue("x_user");
        if (!$this->user->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->user->Visible = false; // Disable update for API request
            } else {
                $this->user->setFormValue($val);
            }
        }

        // Check field name '_action' first before field var 'x__action'
        $val = $CurrentForm->hasValue("_action") ? $CurrentForm->getValue("_action") : $CurrentForm->getValue("x__action");
        if (!$this->_action->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_action->Visible = false; // Disable update for API request
            } else {
                $this->_action->setFormValue($val);
            }
        }

        // Check field name 'table' first before field var 'x__table'
        $val = $CurrentForm->hasValue("table") ? $CurrentForm->getValue("table") : $CurrentForm->getValue("x__table");
        if (!$this->_table->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->_table->Visible = false; // Disable update for API request
            } else {
                $this->_table->setFormValue($val);
            }
        }

        // Check field name 'field' first before field var 'x_field'
        $val = $CurrentForm->hasValue("field") ? $CurrentForm->getValue("field") : $CurrentForm->getValue("x_field");
        if (!$this->field->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->field->Visible = false; // Disable update for API request
            } else {
                $this->field->setFormValue($val);
            }
        }

        // Check field name 'keyvalue' first before field var 'x_keyvalue'
        $val = $CurrentForm->hasValue("keyvalue") ? $CurrentForm->getValue("keyvalue") : $CurrentForm->getValue("x_keyvalue");
        if (!$this->keyvalue->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->keyvalue->Visible = false; // Disable update for API request
            } else {
                $this->keyvalue->setFormValue($val);
            }
        }

        // Check field name 'oldvalue' first before field var 'x_oldvalue'
        $val = $CurrentForm->hasValue("oldvalue") ? $CurrentForm->getValue("oldvalue") : $CurrentForm->getValue("x_oldvalue");
        if (!$this->oldvalue->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->oldvalue->Visible = false; // Disable update for API request
            } else {
                $this->oldvalue->setFormValue($val);
            }
        }

        // Check field name 'newvalue' first before field var 'x_newvalue'
        $val = $CurrentForm->hasValue("newvalue") ? $CurrentForm->getValue("newvalue") : $CurrentForm->getValue("x_newvalue");
        if (!$this->newvalue->IsDetailKey) {
            if (IsApi() && $val === null) {
                $this->newvalue->Visible = false; // Disable update for API request
            } else {
                $this->newvalue->setFormValue($val);
            }
        }

        // Check field name 'id' first before field var 'x_id'
        $val = $CurrentForm->hasValue("id") ? $CurrentForm->getValue("id") : $CurrentForm->getValue("x_id");
    }

    // Restore form values
    public function restoreFormValues()
    {
        global $CurrentForm;
        $this->datetime->CurrentValue = $this->datetime->FormValue;
        $this->datetime->CurrentValue = UnFormatDateTime($this->datetime->CurrentValue, $this->datetime->formatPattern());
        $this->script->CurrentValue = $this->script->FormValue;
        $this->user->CurrentValue = $this->user->FormValue;
        $this->_action->CurrentValue = $this->_action->FormValue;
        $this->_table->CurrentValue = $this->_table->FormValue;
        $this->field->CurrentValue = $this->field->FormValue;
        $this->keyvalue->CurrentValue = $this->keyvalue->FormValue;
        $this->oldvalue->CurrentValue = $this->oldvalue->FormValue;
        $this->newvalue->CurrentValue = $this->newvalue->FormValue;
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
        $this->id->setDbValue($row['id']);
        $this->datetime->setDbValue($row['datetime']);
        $this->script->setDbValue($row['script']);
        $this->user->setDbValue($row['user']);
        $this->_action->setDbValue($row['action']);
        $this->_table->setDbValue($row['table']);
        $this->field->setDbValue($row['field']);
        $this->keyvalue->setDbValue($row['keyvalue']);
        $this->oldvalue->setDbValue($row['oldvalue']);
        $this->newvalue->setDbValue($row['newvalue']);
    }

    // Return a row with default values
    protected function newRow()
    {
        $row = [];
        $row['id'] = $this->id->DefaultValue;
        $row['datetime'] = $this->datetime->DefaultValue;
        $row['script'] = $this->script->DefaultValue;
        $row['user'] = $this->user->DefaultValue;
        $row['action'] = $this->_action->DefaultValue;
        $row['table'] = $this->_table->DefaultValue;
        $row['field'] = $this->field->DefaultValue;
        $row['keyvalue'] = $this->keyvalue->DefaultValue;
        $row['oldvalue'] = $this->oldvalue->DefaultValue;
        $row['newvalue'] = $this->newvalue->DefaultValue;
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

        // id
        $this->id->RowCssClass = "row";

        // datetime
        $this->datetime->RowCssClass = "row";

        // script
        $this->script->RowCssClass = "row";

        // user
        $this->user->RowCssClass = "row";

        // action
        $this->_action->RowCssClass = "row";

        // table
        $this->_table->RowCssClass = "row";

        // field
        $this->field->RowCssClass = "row";

        // keyvalue
        $this->keyvalue->RowCssClass = "row";

        // oldvalue
        $this->oldvalue->RowCssClass = "row";

        // newvalue
        $this->newvalue->RowCssClass = "row";

        // View row
        if ($this->RowType == ROWTYPE_VIEW) {
            // id
            $this->id->ViewValue = $this->id->CurrentValue;
            $this->id->ViewCustomAttributes = "";

            // datetime
            $this->datetime->ViewValue = $this->datetime->CurrentValue;
            $this->datetime->ViewValue = FormatDateTime($this->datetime->ViewValue, $this->datetime->formatPattern());
            $this->datetime->ViewCustomAttributes = "";

            // script
            $this->script->ViewValue = $this->script->CurrentValue;
            $this->script->ViewCustomAttributes = "";

            // user
            $this->user->ViewValue = $this->user->CurrentValue;
            $this->user->ViewCustomAttributes = "";

            // action
            $this->_action->ViewValue = $this->_action->CurrentValue;
            $this->_action->ViewCustomAttributes = "";

            // table
            $this->_table->ViewValue = $this->_table->CurrentValue;
            $this->_table->ViewCustomAttributes = "";

            // field
            $this->field->ViewValue = $this->field->CurrentValue;
            $this->field->ViewCustomAttributes = "";

            // keyvalue
            $this->keyvalue->ViewValue = $this->keyvalue->CurrentValue;
            $this->keyvalue->ViewCustomAttributes = "";

            // oldvalue
            $this->oldvalue->ViewValue = $this->oldvalue->CurrentValue;
            $this->oldvalue->ViewCustomAttributes = "";

            // newvalue
            $this->newvalue->ViewValue = $this->newvalue->CurrentValue;
            $this->newvalue->ViewCustomAttributes = "";

            // datetime
            $this->datetime->LinkCustomAttributes = "";
            $this->datetime->HrefValue = "";

            // script
            $this->script->LinkCustomAttributes = "";
            $this->script->HrefValue = "";

            // user
            $this->user->LinkCustomAttributes = "";
            $this->user->HrefValue = "";

            // action
            $this->_action->LinkCustomAttributes = "";
            $this->_action->HrefValue = "";

            // table
            $this->_table->LinkCustomAttributes = "";
            $this->_table->HrefValue = "";

            // field
            $this->field->LinkCustomAttributes = "";
            $this->field->HrefValue = "";

            // keyvalue
            $this->keyvalue->LinkCustomAttributes = "";
            $this->keyvalue->HrefValue = "";

            // oldvalue
            $this->oldvalue->LinkCustomAttributes = "";
            $this->oldvalue->HrefValue = "";

            // newvalue
            $this->newvalue->LinkCustomAttributes = "";
            $this->newvalue->HrefValue = "";
        } elseif ($this->RowType == ROWTYPE_ADD) {
            // datetime
            $this->datetime->setupEditAttributes();
            $this->datetime->EditCustomAttributes = "";
            $this->datetime->EditValue = HtmlEncode(FormatDateTime($this->datetime->CurrentValue, $this->datetime->formatPattern()));
            $this->datetime->PlaceHolder = RemoveHtml($this->datetime->caption());

            // script
            $this->script->setupEditAttributes();
            $this->script->EditCustomAttributes = "";
            if (!$this->script->Raw) {
                $this->script->CurrentValue = HtmlDecode($this->script->CurrentValue);
            }
            $this->script->EditValue = HtmlEncode($this->script->CurrentValue);
            $this->script->PlaceHolder = RemoveHtml($this->script->caption());

            // user
            $this->user->setupEditAttributes();
            $this->user->EditCustomAttributes = "";
            if (!$this->user->Raw) {
                $this->user->CurrentValue = HtmlDecode($this->user->CurrentValue);
            }
            $this->user->EditValue = HtmlEncode($this->user->CurrentValue);
            $this->user->PlaceHolder = RemoveHtml($this->user->caption());

            // action
            $this->_action->setupEditAttributes();
            $this->_action->EditCustomAttributes = "";
            if (!$this->_action->Raw) {
                $this->_action->CurrentValue = HtmlDecode($this->_action->CurrentValue);
            }
            $this->_action->EditValue = HtmlEncode($this->_action->CurrentValue);
            $this->_action->PlaceHolder = RemoveHtml($this->_action->caption());

            // table
            $this->_table->setupEditAttributes();
            $this->_table->EditCustomAttributes = "";
            if (!$this->_table->Raw) {
                $this->_table->CurrentValue = HtmlDecode($this->_table->CurrentValue);
            }
            $this->_table->EditValue = HtmlEncode($this->_table->CurrentValue);
            $this->_table->PlaceHolder = RemoveHtml($this->_table->caption());

            // field
            $this->field->setupEditAttributes();
            $this->field->EditCustomAttributes = "";
            if (!$this->field->Raw) {
                $this->field->CurrentValue = HtmlDecode($this->field->CurrentValue);
            }
            $this->field->EditValue = HtmlEncode($this->field->CurrentValue);
            $this->field->PlaceHolder = RemoveHtml($this->field->caption());

            // keyvalue
            $this->keyvalue->setupEditAttributes();
            $this->keyvalue->EditCustomAttributes = "";
            $this->keyvalue->EditValue = HtmlEncode($this->keyvalue->CurrentValue);
            $this->keyvalue->PlaceHolder = RemoveHtml($this->keyvalue->caption());

            // oldvalue
            $this->oldvalue->setupEditAttributes();
            $this->oldvalue->EditCustomAttributes = "";
            $this->oldvalue->EditValue = HtmlEncode($this->oldvalue->CurrentValue);
            $this->oldvalue->PlaceHolder = RemoveHtml($this->oldvalue->caption());

            // newvalue
            $this->newvalue->setupEditAttributes();
            $this->newvalue->EditCustomAttributes = "";
            $this->newvalue->EditValue = HtmlEncode($this->newvalue->CurrentValue);
            $this->newvalue->PlaceHolder = RemoveHtml($this->newvalue->caption());

            // Add refer script

            // datetime
            $this->datetime->LinkCustomAttributes = "";
            $this->datetime->HrefValue = "";

            // script
            $this->script->LinkCustomAttributes = "";
            $this->script->HrefValue = "";

            // user
            $this->user->LinkCustomAttributes = "";
            $this->user->HrefValue = "";

            // action
            $this->_action->LinkCustomAttributes = "";
            $this->_action->HrefValue = "";

            // table
            $this->_table->LinkCustomAttributes = "";
            $this->_table->HrefValue = "";

            // field
            $this->field->LinkCustomAttributes = "";
            $this->field->HrefValue = "";

            // keyvalue
            $this->keyvalue->LinkCustomAttributes = "";
            $this->keyvalue->HrefValue = "";

            // oldvalue
            $this->oldvalue->LinkCustomAttributes = "";
            $this->oldvalue->HrefValue = "";

            // newvalue
            $this->newvalue->LinkCustomAttributes = "";
            $this->newvalue->HrefValue = "";
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
        if ($this->datetime->Required) {
            if (!$this->datetime->IsDetailKey && EmptyValue($this->datetime->FormValue)) {
                $this->datetime->addErrorMessage(str_replace("%s", $this->datetime->caption(), $this->datetime->RequiredErrorMessage));
            }
        }
        if (!CheckDate($this->datetime->FormValue, $this->datetime->formatPattern())) {
            $this->datetime->addErrorMessage($this->datetime->getErrorMessage(false));
        }
        if ($this->script->Required) {
            if (!$this->script->IsDetailKey && EmptyValue($this->script->FormValue)) {
                $this->script->addErrorMessage(str_replace("%s", $this->script->caption(), $this->script->RequiredErrorMessage));
            }
        }
        if ($this->user->Required) {
            if (!$this->user->IsDetailKey && EmptyValue($this->user->FormValue)) {
                $this->user->addErrorMessage(str_replace("%s", $this->user->caption(), $this->user->RequiredErrorMessage));
            }
        }
        if ($this->_action->Required) {
            if (!$this->_action->IsDetailKey && EmptyValue($this->_action->FormValue)) {
                $this->_action->addErrorMessage(str_replace("%s", $this->_action->caption(), $this->_action->RequiredErrorMessage));
            }
        }
        if ($this->_table->Required) {
            if (!$this->_table->IsDetailKey && EmptyValue($this->_table->FormValue)) {
                $this->_table->addErrorMessage(str_replace("%s", $this->_table->caption(), $this->_table->RequiredErrorMessage));
            }
        }
        if ($this->field->Required) {
            if (!$this->field->IsDetailKey && EmptyValue($this->field->FormValue)) {
                $this->field->addErrorMessage(str_replace("%s", $this->field->caption(), $this->field->RequiredErrorMessage));
            }
        }
        if ($this->keyvalue->Required) {
            if (!$this->keyvalue->IsDetailKey && EmptyValue($this->keyvalue->FormValue)) {
                $this->keyvalue->addErrorMessage(str_replace("%s", $this->keyvalue->caption(), $this->keyvalue->RequiredErrorMessage));
            }
        }
        if ($this->oldvalue->Required) {
            if (!$this->oldvalue->IsDetailKey && EmptyValue($this->oldvalue->FormValue)) {
                $this->oldvalue->addErrorMessage(str_replace("%s", $this->oldvalue->caption(), $this->oldvalue->RequiredErrorMessage));
            }
        }
        if ($this->newvalue->Required) {
            if (!$this->newvalue->IsDetailKey && EmptyValue($this->newvalue->FormValue)) {
                $this->newvalue->addErrorMessage(str_replace("%s", $this->newvalue->caption(), $this->newvalue->RequiredErrorMessage));
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

        // datetime
        $this->datetime->setDbValueDef($rsnew, UnFormatDateTime($this->datetime->CurrentValue, $this->datetime->formatPattern()), CurrentDate(), false);

        // script
        $this->script->setDbValueDef($rsnew, $this->script->CurrentValue, null, false);

        // user
        $this->user->setDbValueDef($rsnew, $this->user->CurrentValue, null, false);

        // action
        $this->_action->setDbValueDef($rsnew, $this->_action->CurrentValue, null, false);

        // table
        $this->_table->setDbValueDef($rsnew, $this->_table->CurrentValue, null, false);

        // field
        $this->field->setDbValueDef($rsnew, $this->field->CurrentValue, null, false);

        // keyvalue
        $this->keyvalue->setDbValueDef($rsnew, $this->keyvalue->CurrentValue, null, false);

        // oldvalue
        $this->oldvalue->setDbValueDef($rsnew, $this->oldvalue->CurrentValue, null, false);

        // newvalue
        $this->newvalue->setDbValueDef($rsnew, $this->newvalue->CurrentValue, null, false);

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
        $Breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("AudittrailList"), "", $this->TableVar, true);
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
