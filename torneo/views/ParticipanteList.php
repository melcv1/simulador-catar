<?php

namespace PHPMaker2022\project1;

// Page object
$ParticipanteList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { participante: currentTable } });
var currentForm, currentPageID;
var fparticipantelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fparticipantelist = new ew.Form("fparticipantelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fparticipantelist;
    fparticipantelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fparticipantelist");
});
var fparticipantesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fparticipantesrch = new ew.Form("fparticipantesrch", "list");
    currentSearchForm = fparticipantesrch;

    // Dynamic selection lists

    // Filters
    fparticipantesrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fparticipantesrch");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fparticipantesrch" id="fparticipantesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fparticipantesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="participante">
<div class="ew-extended-search container-fluid">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fparticipantesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fparticipantesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fparticipantesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fparticipantesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> participante">
<form name="fparticipantelist" id="fparticipantelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="participante">
<div id="gmp_participante" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_participantelist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->ID_PARTICIPANTE->Visible) { // ID_PARTICIPANTE ?>
        <th data-name="ID_PARTICIPANTE" class="<?= $Page->ID_PARTICIPANTE->headerCellClass() ?>"><div id="elh_participante_ID_PARTICIPANTE" class="participante_ID_PARTICIPANTE"><?= $Page->renderFieldHeader($Page->ID_PARTICIPANTE) ?></div></th>
<?php } ?>
<?php if ($Page->NOMBRE->Visible) { // NOMBRE ?>
        <th data-name="NOMBRE" class="<?= $Page->NOMBRE->headerCellClass() ?>"><div id="elh_participante_NOMBRE" class="participante_NOMBRE"><?= $Page->renderFieldHeader($Page->NOMBRE) ?></div></th>
<?php } ?>
<?php if ($Page->APELLIDO->Visible) { // APELLIDO ?>
        <th data-name="APELLIDO" class="<?= $Page->APELLIDO->headerCellClass() ?>"><div id="elh_participante_APELLIDO" class="participante_APELLIDO"><?= $Page->renderFieldHeader($Page->APELLIDO) ?></div></th>
<?php } ?>
<?php if ($Page->FECHA_NACIMIENTO->Visible) { // FECHA_NACIMIENTO ?>
        <th data-name="FECHA_NACIMIENTO" class="<?= $Page->FECHA_NACIMIENTO->headerCellClass() ?>"><div id="elh_participante_FECHA_NACIMIENTO" class="participante_FECHA_NACIMIENTO"><?= $Page->renderFieldHeader($Page->FECHA_NACIMIENTO) ?></div></th>
<?php } ?>
<?php if ($Page->CEDULA->Visible) { // CEDULA ?>
        <th data-name="CEDULA" class="<?= $Page->CEDULA->headerCellClass() ?>"><div id="elh_participante_CEDULA" class="participante_CEDULA"><?= $Page->renderFieldHeader($Page->CEDULA) ?></div></th>
<?php } ?>
<?php if ($Page->_EMAIL->Visible) { // EMAIL ?>
        <th data-name="_EMAIL" class="<?= $Page->_EMAIL->headerCellClass() ?>"><div id="elh_participante__EMAIL" class="participante__EMAIL"><?= $Page->renderFieldHeader($Page->_EMAIL) ?></div></th>
<?php } ?>
<?php if ($Page->TELEFONO->Visible) { // TELEFONO ?>
        <th data-name="TELEFONO" class="<?= $Page->TELEFONO->headerCellClass() ?>"><div id="elh_participante_TELEFONO" class="participante_TELEFONO"><?= $Page->renderFieldHeader($Page->TELEFONO) ?></div></th>
<?php } ?>
<?php if ($Page->CREACION->Visible) { // CREACION ?>
        <th data-name="CREACION" class="<?= $Page->CREACION->headerCellClass() ?>"><div id="elh_participante_CREACION" class="participante_CREACION"><?= $Page->renderFieldHeader($Page->CREACION) ?></div></th>
<?php } ?>
<?php if ($Page->ACTUALIZACION->Visible) { // ACTUALIZACION ?>
        <th data-name="ACTUALIZACION" class="<?= $Page->ACTUALIZACION->headerCellClass() ?>"><div id="elh_participante_ACTUALIZACION" class="participante_ACTUALIZACION"><?= $Page->renderFieldHeader($Page->ACTUALIZACION) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif ($Page->isGridAdd() && !$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row attributes
        $Page->RowAttrs->merge([
            "data-rowindex" => $Page->RowCount,
            "id" => "r" . $Page->RowCount . "_participante",
            "data-rowtype" => $Page->RowType,
            "class" => ($Page->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($Page->isAdd() && $Page->RowType == ROWTYPE_ADD || $Page->isEdit() && $Page->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $Page->RowAttrs->appendClass("table-active");
        }

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->ID_PARTICIPANTE->Visible) { // ID_PARTICIPANTE ?>
        <td data-name="ID_PARTICIPANTE"<?= $Page->ID_PARTICIPANTE->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante_ID_PARTICIPANTE" class="el_participante_ID_PARTICIPANTE">
<span<?= $Page->ID_PARTICIPANTE->viewAttributes() ?>>
<?= $Page->ID_PARTICIPANTE->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NOMBRE->Visible) { // NOMBRE ?>
        <td data-name="NOMBRE"<?= $Page->NOMBRE->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante_NOMBRE" class="el_participante_NOMBRE">
<span<?= $Page->NOMBRE->viewAttributes() ?>>
<?= $Page->NOMBRE->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->APELLIDO->Visible) { // APELLIDO ?>
        <td data-name="APELLIDO"<?= $Page->APELLIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante_APELLIDO" class="el_participante_APELLIDO">
<span<?= $Page->APELLIDO->viewAttributes() ?>>
<?= $Page->APELLIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->FECHA_NACIMIENTO->Visible) { // FECHA_NACIMIENTO ?>
        <td data-name="FECHA_NACIMIENTO"<?= $Page->FECHA_NACIMIENTO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante_FECHA_NACIMIENTO" class="el_participante_FECHA_NACIMIENTO">
<span<?= $Page->FECHA_NACIMIENTO->viewAttributes() ?>>
<?= $Page->FECHA_NACIMIENTO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->CEDULA->Visible) { // CEDULA ?>
        <td data-name="CEDULA"<?= $Page->CEDULA->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante_CEDULA" class="el_participante_CEDULA">
<span<?= $Page->CEDULA->viewAttributes() ?>>
<?= $Page->CEDULA->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->_EMAIL->Visible) { // EMAIL ?>
        <td data-name="_EMAIL"<?= $Page->_EMAIL->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante__EMAIL" class="el_participante__EMAIL">
<span<?= $Page->_EMAIL->viewAttributes() ?>>
<?= $Page->_EMAIL->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->TELEFONO->Visible) { // TELEFONO ?>
        <td data-name="TELEFONO"<?= $Page->TELEFONO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante_TELEFONO" class="el_participante_TELEFONO">
<span<?= $Page->TELEFONO->viewAttributes() ?>>
<?= $Page->TELEFONO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->CREACION->Visible) { // CREACION ?>
        <td data-name="CREACION"<?= $Page->CREACION->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante_CREACION" class="el_participante_CREACION">
<span<?= $Page->CREACION->viewAttributes() ?>>
<?= $Page->CREACION->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ACTUALIZACION->Visible) { // ACTUALIZACION ?>
        <td data-name="ACTUALIZACION"<?= $Page->ACTUALIZACION->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_participante_ACTUALIZACION" class="el_participante_ACTUALIZACION">
<span<?= $Page->ACTUALIZACION->viewAttributes() ?>>
<?= $Page->ACTUALIZACION->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("participante");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
