<?php

namespace PHPMaker2022\project1;

// Page object
$PartidosList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { partidos: currentTable } });
var currentForm, currentPageID;
var fpartidoslist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fpartidoslist = new ew.Form("fpartidoslist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fpartidoslist;
    fpartidoslist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fpartidoslist");
});
var fpartidossrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fpartidossrch = new ew.Form("fpartidossrch", "list");
    currentSearchForm = fpartidossrch;

    // Dynamic selection lists

    // Filters
    fpartidossrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fpartidossrch");
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
<form name="fpartidossrch" id="fpartidossrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fpartidossrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="partidos">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fpartidossrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fpartidossrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fpartidossrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fpartidossrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> partidos">
<form name="fpartidoslist" id="fpartidoslist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="partidos">
<div id="gmp_partidos" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_partidoslist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->ID_EQUIPO2->Visible) { // ID_EQUIPO2 ?>
        <th data-name="ID_EQUIPO2" class="<?= $Page->ID_EQUIPO2->headerCellClass() ?>"><div id="elh_partidos_ID_EQUIPO2" class="partidos_ID_EQUIPO2"><?= $Page->renderFieldHeader($Page->ID_EQUIPO2) ?></div></th>
<?php } ?>
<?php if ($Page->ID_EQUIPO1->Visible) { // ID_EQUIPO1 ?>
        <th data-name="ID_EQUIPO1" class="<?= $Page->ID_EQUIPO1->headerCellClass() ?>"><div id="elh_partidos_ID_EQUIPO1" class="partidos_ID_EQUIPO1"><?= $Page->renderFieldHeader($Page->ID_EQUIPO1) ?></div></th>
<?php } ?>
<?php if ($Page->ID_PARTIDO->Visible) { // ID_PARTIDO ?>
        <th data-name="ID_PARTIDO" class="<?= $Page->ID_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_ID_PARTIDO" class="partidos_ID_PARTIDO"><?= $Page->renderFieldHeader($Page->ID_PARTIDO) ?></div></th>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <th data-name="ID_TORNEO" class="<?= $Page->ID_TORNEO->headerCellClass() ?>"><div id="elh_partidos_ID_TORNEO" class="partidos_ID_TORNEO"><?= $Page->renderFieldHeader($Page->ID_TORNEO) ?></div></th>
<?php } ?>
<?php if ($Page->FECHA_PARTIDO->Visible) { // FECHA_PARTIDO ?>
        <th data-name="FECHA_PARTIDO" class="<?= $Page->FECHA_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_FECHA_PARTIDO" class="partidos_FECHA_PARTIDO"><?= $Page->renderFieldHeader($Page->FECHA_PARTIDO) ?></div></th>
<?php } ?>
<?php if ($Page->HORA_PARTIDO->Visible) { // HORA_PARTIDO ?>
        <th data-name="HORA_PARTIDO" class="<?= $Page->HORA_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_HORA_PARTIDO" class="partidos_HORA_PARTIDO"><?= $Page->renderFieldHeader($Page->HORA_PARTIDO) ?></div></th>
<?php } ?>
<?php if ($Page->DIA_PARTIDO->Visible) { // DIA_PARTIDO ?>
        <th data-name="DIA_PARTIDO" class="<?= $Page->DIA_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_DIA_PARTIDO" class="partidos_DIA_PARTIDO"><?= $Page->renderFieldHeader($Page->DIA_PARTIDO) ?></div></th>
<?php } ?>
<?php if ($Page->ESTADIO->Visible) { // ESTADIO ?>
        <th data-name="ESTADIO" class="<?= $Page->ESTADIO->headerCellClass() ?>"><div id="elh_partidos_ESTADIO" class="partidos_ESTADIO"><?= $Page->renderFieldHeader($Page->ESTADIO) ?></div></th>
<?php } ?>
<?php if ($Page->CIUDAD_PARTIDO->Visible) { // CIUDAD_PARTIDO ?>
        <th data-name="CIUDAD_PARTIDO" class="<?= $Page->CIUDAD_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_CIUDAD_PARTIDO" class="partidos_CIUDAD_PARTIDO"><?= $Page->renderFieldHeader($Page->CIUDAD_PARTIDO) ?></div></th>
<?php } ?>
<?php if ($Page->PAIS_PARTIDO->Visible) { // PAIS_PARTIDO ?>
        <th data-name="PAIS_PARTIDO" class="<?= $Page->PAIS_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_PAIS_PARTIDO" class="partidos_PAIS_PARTIDO"><?= $Page->renderFieldHeader($Page->PAIS_PARTIDO) ?></div></th>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO1->Visible) { // GOLES_EQUIPO1 ?>
        <th data-name="GOLES_EQUIPO1" class="<?= $Page->GOLES_EQUIPO1->headerCellClass() ?>"><div id="elh_partidos_GOLES_EQUIPO1" class="partidos_GOLES_EQUIPO1"><?= $Page->renderFieldHeader($Page->GOLES_EQUIPO1) ?></div></th>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO2->Visible) { // GOLES_EQUIPO2 ?>
        <th data-name="GOLES_EQUIPO2" class="<?= $Page->GOLES_EQUIPO2->headerCellClass() ?>"><div id="elh_partidos_GOLES_EQUIPO2" class="partidos_GOLES_EQUIPO2"><?= $Page->renderFieldHeader($Page->GOLES_EQUIPO2) ?></div></th>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO1->Visible) { // GOLES_EXTRA_EQUIPO1 ?>
        <th data-name="GOLES_EXTRA_EQUIPO1" class="<?= $Page->GOLES_EXTRA_EQUIPO1->headerCellClass() ?>"><div id="elh_partidos_GOLES_EXTRA_EQUIPO1" class="partidos_GOLES_EXTRA_EQUIPO1"><?= $Page->renderFieldHeader($Page->GOLES_EXTRA_EQUIPO1) ?></div></th>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO2->Visible) { // GOLES_EXTRA_EQUIPO2 ?>
        <th data-name="GOLES_EXTRA_EQUIPO2" class="<?= $Page->GOLES_EXTRA_EQUIPO2->headerCellClass() ?>"><div id="elh_partidos_GOLES_EXTRA_EQUIPO2" class="partidos_GOLES_EXTRA_EQUIPO2"><?= $Page->renderFieldHeader($Page->GOLES_EXTRA_EQUIPO2) ?></div></th>
<?php } ?>
<?php if ($Page->NOTA_PARTIDO->Visible) { // NOTA_PARTIDO ?>
        <th data-name="NOTA_PARTIDO" class="<?= $Page->NOTA_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_NOTA_PARTIDO" class="partidos_NOTA_PARTIDO"><?= $Page->renderFieldHeader($Page->NOTA_PARTIDO) ?></div></th>
<?php } ?>
<?php if ($Page->RESUMEN_PARTIDO->Visible) { // RESUMEN_PARTIDO ?>
        <th data-name="RESUMEN_PARTIDO" class="<?= $Page->RESUMEN_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_RESUMEN_PARTIDO" class="partidos_RESUMEN_PARTIDO"><?= $Page->renderFieldHeader($Page->RESUMEN_PARTIDO) ?></div></th>
<?php } ?>
<?php if ($Page->ESTADO_PARTIDO->Visible) { // ESTADO_PARTIDO ?>
        <th data-name="ESTADO_PARTIDO" class="<?= $Page->ESTADO_PARTIDO->headerCellClass() ?>"><div id="elh_partidos_ESTADO_PARTIDO" class="partidos_ESTADO_PARTIDO"><?= $Page->renderFieldHeader($Page->ESTADO_PARTIDO) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_partidos",
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
    <?php if ($Page->ID_EQUIPO2->Visible) { // ID_EQUIPO2 ?>
        <td data-name="ID_EQUIPO2"<?= $Page->ID_EQUIPO2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ID_EQUIPO2" class="el_partidos_ID_EQUIPO2">
<span<?= $Page->ID_EQUIPO2->viewAttributes() ?>>
<?= $Page->ID_EQUIPO2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ID_EQUIPO1->Visible) { // ID_EQUIPO1 ?>
        <td data-name="ID_EQUIPO1"<?= $Page->ID_EQUIPO1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ID_EQUIPO1" class="el_partidos_ID_EQUIPO1">
<span<?= $Page->ID_EQUIPO1->viewAttributes() ?>>
<?= $Page->ID_EQUIPO1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ID_PARTIDO->Visible) { // ID_PARTIDO ?>
        <td data-name="ID_PARTIDO"<?= $Page->ID_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ID_PARTIDO" class="el_partidos_ID_PARTIDO">
<span<?= $Page->ID_PARTIDO->viewAttributes() ?>>
<?= $Page->ID_PARTIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <td data-name="ID_TORNEO"<?= $Page->ID_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ID_TORNEO" class="el_partidos_ID_TORNEO">
<span<?= $Page->ID_TORNEO->viewAttributes() ?>>
<?= $Page->ID_TORNEO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->FECHA_PARTIDO->Visible) { // FECHA_PARTIDO ?>
        <td data-name="FECHA_PARTIDO"<?= $Page->FECHA_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_FECHA_PARTIDO" class="el_partidos_FECHA_PARTIDO">
<span<?= $Page->FECHA_PARTIDO->viewAttributes() ?>>
<?= $Page->FECHA_PARTIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->HORA_PARTIDO->Visible) { // HORA_PARTIDO ?>
        <td data-name="HORA_PARTIDO"<?= $Page->HORA_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_HORA_PARTIDO" class="el_partidos_HORA_PARTIDO">
<span<?= $Page->HORA_PARTIDO->viewAttributes() ?>>
<?= $Page->HORA_PARTIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->DIA_PARTIDO->Visible) { // DIA_PARTIDO ?>
        <td data-name="DIA_PARTIDO"<?= $Page->DIA_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_DIA_PARTIDO" class="el_partidos_DIA_PARTIDO">
<span<?= $Page->DIA_PARTIDO->viewAttributes() ?>>
<?= $Page->DIA_PARTIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ESTADIO->Visible) { // ESTADIO ?>
        <td data-name="ESTADIO"<?= $Page->ESTADIO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ESTADIO" class="el_partidos_ESTADIO">
<span<?= $Page->ESTADIO->viewAttributes() ?>>
<?= $Page->ESTADIO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->CIUDAD_PARTIDO->Visible) { // CIUDAD_PARTIDO ?>
        <td data-name="CIUDAD_PARTIDO"<?= $Page->CIUDAD_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_CIUDAD_PARTIDO" class="el_partidos_CIUDAD_PARTIDO">
<span<?= $Page->CIUDAD_PARTIDO->viewAttributes() ?>>
<?= $Page->CIUDAD_PARTIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PAIS_PARTIDO->Visible) { // PAIS_PARTIDO ?>
        <td data-name="PAIS_PARTIDO"<?= $Page->PAIS_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_PAIS_PARTIDO" class="el_partidos_PAIS_PARTIDO">
<span<?= $Page->PAIS_PARTIDO->viewAttributes() ?>>
<?= $Page->PAIS_PARTIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GOLES_EQUIPO1->Visible) { // GOLES_EQUIPO1 ?>
        <td data-name="GOLES_EQUIPO1"<?= $Page->GOLES_EQUIPO1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_GOLES_EQUIPO1" class="el_partidos_GOLES_EQUIPO1">
<span<?= $Page->GOLES_EQUIPO1->viewAttributes() ?>>
<?= $Page->GOLES_EQUIPO1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GOLES_EQUIPO2->Visible) { // GOLES_EQUIPO2 ?>
        <td data-name="GOLES_EQUIPO2"<?= $Page->GOLES_EQUIPO2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_GOLES_EQUIPO2" class="el_partidos_GOLES_EQUIPO2">
<span<?= $Page->GOLES_EQUIPO2->viewAttributes() ?>>
<?= $Page->GOLES_EQUIPO2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GOLES_EXTRA_EQUIPO1->Visible) { // GOLES_EXTRA_EQUIPO1 ?>
        <td data-name="GOLES_EXTRA_EQUIPO1"<?= $Page->GOLES_EXTRA_EQUIPO1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_GOLES_EXTRA_EQUIPO1" class="el_partidos_GOLES_EXTRA_EQUIPO1">
<span<?= $Page->GOLES_EXTRA_EQUIPO1->viewAttributes() ?>>
<?= $Page->GOLES_EXTRA_EQUIPO1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GOLES_EXTRA_EQUIPO2->Visible) { // GOLES_EXTRA_EQUIPO2 ?>
        <td data-name="GOLES_EXTRA_EQUIPO2"<?= $Page->GOLES_EXTRA_EQUIPO2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_GOLES_EXTRA_EQUIPO2" class="el_partidos_GOLES_EXTRA_EQUIPO2">
<span<?= $Page->GOLES_EXTRA_EQUIPO2->viewAttributes() ?>>
<?= $Page->GOLES_EXTRA_EQUIPO2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NOTA_PARTIDO->Visible) { // NOTA_PARTIDO ?>
        <td data-name="NOTA_PARTIDO"<?= $Page->NOTA_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_NOTA_PARTIDO" class="el_partidos_NOTA_PARTIDO">
<span<?= $Page->NOTA_PARTIDO->viewAttributes() ?>>
<?= $Page->NOTA_PARTIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->RESUMEN_PARTIDO->Visible) { // RESUMEN_PARTIDO ?>
        <td data-name="RESUMEN_PARTIDO"<?= $Page->RESUMEN_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_RESUMEN_PARTIDO" class="el_partidos_RESUMEN_PARTIDO">
<span<?= $Page->RESUMEN_PARTIDO->viewAttributes() ?>>
<?= $Page->RESUMEN_PARTIDO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ESTADO_PARTIDO->Visible) { // ESTADO_PARTIDO ?>
        <td data-name="ESTADO_PARTIDO"<?= $Page->ESTADO_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ESTADO_PARTIDO" class="el_partidos_ESTADO_PARTIDO">
<span<?= $Page->ESTADO_PARTIDO->viewAttributes() ?>>
<?= $Page->ESTADO_PARTIDO->getViewValue() ?></span>
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
    ew.addEventHandlers("partidos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
