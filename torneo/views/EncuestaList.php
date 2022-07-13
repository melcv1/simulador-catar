<?php

namespace PHPMaker2022\project1;

// Page object
$EncuestaList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { encuesta: currentTable } });
var currentForm, currentPageID;
var fencuestalist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fencuestalist = new ew.Form("fencuestalist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fencuestalist;
    fencuestalist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fencuestalist");
});
var fencuestasrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fencuestasrch = new ew.Form("fencuestasrch", "list");
    currentSearchForm = fencuestasrch;

    // Dynamic selection lists

    // Filters
    fencuestasrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fencuestasrch");
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
<form name="fencuestasrch" id="fencuestasrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fencuestasrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="encuesta">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fencuestasrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fencuestasrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fencuestasrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fencuestasrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> encuesta">
<form name="fencuestalist" id="fencuestalist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="encuesta">
<div id="gmp_encuesta" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_encuestalist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->ID_ENCUESTA->Visible) { // ID_ENCUESTA ?>
        <th data-name="ID_ENCUESTA" class="<?= $Page->ID_ENCUESTA->headerCellClass() ?>"><div id="elh_encuesta_ID_ENCUESTA" class="encuesta_ID_ENCUESTA"><?= $Page->renderFieldHeader($Page->ID_ENCUESTA) ?></div></th>
<?php } ?>
<?php if ($Page->ID_PARTICIPANTE->Visible) { // ID_PARTICIPANTE ?>
        <th data-name="ID_PARTICIPANTE" class="<?= $Page->ID_PARTICIPANTE->headerCellClass() ?>"><div id="elh_encuesta_ID_PARTICIPANTE" class="encuesta_ID_PARTICIPANTE"><?= $Page->renderFieldHeader($Page->ID_PARTICIPANTE) ?></div></th>
<?php } ?>
<?php if ($Page->GRUPO->Visible) { // GRUPO ?>
        <th data-name="GRUPO" class="<?= $Page->GRUPO->headerCellClass() ?>"><div id="elh_encuesta_GRUPO" class="encuesta_GRUPO"><?= $Page->renderFieldHeader($Page->GRUPO) ?></div></th>
<?php } ?>
<?php if ($Page->EQUIPO->Visible) { // EQUIPO ?>
        <th data-name="EQUIPO" class="<?= $Page->EQUIPO->headerCellClass() ?>"><div id="elh_encuesta_EQUIPO" class="encuesta_EQUIPO"><?= $Page->renderFieldHeader($Page->EQUIPO) ?></div></th>
<?php } ?>
<?php if ($Page->POSICION->Visible) { // POSICION ?>
        <th data-name="POSICION" class="<?= $Page->POSICION->headerCellClass() ?>"><div id="elh_encuesta_POSICION" class="encuesta_POSICION"><?= $Page->renderFieldHeader($Page->POSICION) ?></div></th>
<?php } ?>
<?php if ($Page->NUMERACION->Visible) { // NUMERACION ?>
        <th data-name="NUMERACION" class="<?= $Page->NUMERACION->headerCellClass() ?>"><div id="elh_encuesta_NUMERACION" class="encuesta_NUMERACION"><?= $Page->renderFieldHeader($Page->NUMERACION) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_encuesta",
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
    <?php if ($Page->ID_ENCUESTA->Visible) { // ID_ENCUESTA ?>
        <td data-name="ID_ENCUESTA"<?= $Page->ID_ENCUESTA->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_ID_ENCUESTA" class="el_encuesta_ID_ENCUESTA">
<span<?= $Page->ID_ENCUESTA->viewAttributes() ?>>
<?= $Page->ID_ENCUESTA->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ID_PARTICIPANTE->Visible) { // ID_PARTICIPANTE ?>
        <td data-name="ID_PARTICIPANTE"<?= $Page->ID_PARTICIPANTE->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_ID_PARTICIPANTE" class="el_encuesta_ID_PARTICIPANTE">
<span<?= $Page->ID_PARTICIPANTE->viewAttributes() ?>>
<?= $Page->ID_PARTICIPANTE->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GRUPO->Visible) { // GRUPO ?>
        <td data-name="GRUPO"<?= $Page->GRUPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_GRUPO" class="el_encuesta_GRUPO">
<span<?= $Page->GRUPO->viewAttributes() ?>>
<?= $Page->GRUPO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->EQUIPO->Visible) { // EQUIPO ?>
        <td data-name="EQUIPO"<?= $Page->EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_EQUIPO" class="el_encuesta_EQUIPO">
<span<?= $Page->EQUIPO->viewAttributes() ?>>
<?= $Page->EQUIPO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->POSICION->Visible) { // POSICION ?>
        <td data-name="POSICION"<?= $Page->POSICION->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_POSICION" class="el_encuesta_POSICION">
<span<?= $Page->POSICION->viewAttributes() ?>>
<?= $Page->POSICION->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NUMERACION->Visible) { // NUMERACION ?>
        <td data-name="NUMERACION"<?= $Page->NUMERACION->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_NUMERACION" class="el_encuesta_NUMERACION">
<span<?= $Page->NUMERACION->viewAttributes() ?>>
<?= $Page->NUMERACION->getViewValue() ?></span>
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
    ew.addEventHandlers("encuesta");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
