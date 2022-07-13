<?php

namespace PHPMaker2022\project1;

// Page object
$EquipotorneoList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { equipotorneo: currentTable } });
var currentForm, currentPageID;
var fequipotorneolist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fequipotorneolist = new ew.Form("fequipotorneolist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fequipotorneolist;
    fequipotorneolist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fequipotorneolist");
});
var fequipotorneosrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fequipotorneosrch = new ew.Form("fequipotorneosrch", "list");
    currentSearchForm = fequipotorneosrch;

    // Dynamic selection lists

    // Filters
    fequipotorneosrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fequipotorneosrch");
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
<form name="fequipotorneosrch" id="fequipotorneosrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fequipotorneosrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="equipotorneo">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fequipotorneosrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fequipotorneosrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fequipotorneosrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fequipotorneosrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> equipotorneo">
<form name="fequipotorneolist" id="fequipotorneolist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="equipotorneo">
<div id="gmp_equipotorneo" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_equipotorneolist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->ID_EQUIPO_TORNEO->Visible) { // ID_EQUIPO_TORNEO ?>
        <th data-name="ID_EQUIPO_TORNEO" class="<?= $Page->ID_EQUIPO_TORNEO->headerCellClass() ?>"><div id="elh_equipotorneo_ID_EQUIPO_TORNEO" class="equipotorneo_ID_EQUIPO_TORNEO"><?= $Page->renderFieldHeader($Page->ID_EQUIPO_TORNEO) ?></div></th>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <th data-name="ID_TORNEO" class="<?= $Page->ID_TORNEO->headerCellClass() ?>"><div id="elh_equipotorneo_ID_TORNEO" class="equipotorneo_ID_TORNEO"><?= $Page->renderFieldHeader($Page->ID_TORNEO) ?></div></th>
<?php } ?>
<?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
        <th data-name="ID_EQUIPO" class="<?= $Page->ID_EQUIPO->headerCellClass() ?>"><div id="elh_equipotorneo_ID_EQUIPO" class="equipotorneo_ID_EQUIPO"><?= $Page->renderFieldHeader($Page->ID_EQUIPO) ?></div></th>
<?php } ?>
<?php if ($Page->PARTIDOS_JUGADOS->Visible) { // PARTIDOS_JUGADOS ?>
        <th data-name="PARTIDOS_JUGADOS" class="<?= $Page->PARTIDOS_JUGADOS->headerCellClass() ?>"><div id="elh_equipotorneo_PARTIDOS_JUGADOS" class="equipotorneo_PARTIDOS_JUGADOS"><?= $Page->renderFieldHeader($Page->PARTIDOS_JUGADOS) ?></div></th>
<?php } ?>
<?php if ($Page->PARTIDOS_GANADOS->Visible) { // PARTIDOS_GANADOS ?>
        <th data-name="PARTIDOS_GANADOS" class="<?= $Page->PARTIDOS_GANADOS->headerCellClass() ?>"><div id="elh_equipotorneo_PARTIDOS_GANADOS" class="equipotorneo_PARTIDOS_GANADOS"><?= $Page->renderFieldHeader($Page->PARTIDOS_GANADOS) ?></div></th>
<?php } ?>
<?php if ($Page->PARTIDOS_EMPATADOS->Visible) { // PARTIDOS_EMPATADOS ?>
        <th data-name="PARTIDOS_EMPATADOS" class="<?= $Page->PARTIDOS_EMPATADOS->headerCellClass() ?>"><div id="elh_equipotorneo_PARTIDOS_EMPATADOS" class="equipotorneo_PARTIDOS_EMPATADOS"><?= $Page->renderFieldHeader($Page->PARTIDOS_EMPATADOS) ?></div></th>
<?php } ?>
<?php if ($Page->PARTIDOS_PERDIDOS->Visible) { // PARTIDOS_PERDIDOS ?>
        <th data-name="PARTIDOS_PERDIDOS" class="<?= $Page->PARTIDOS_PERDIDOS->headerCellClass() ?>"><div id="elh_equipotorneo_PARTIDOS_PERDIDOS" class="equipotorneo_PARTIDOS_PERDIDOS"><?= $Page->renderFieldHeader($Page->PARTIDOS_PERDIDOS) ?></div></th>
<?php } ?>
<?php if ($Page->GF->Visible) { // GF ?>
        <th data-name="GF" class="<?= $Page->GF->headerCellClass() ?>"><div id="elh_equipotorneo_GF" class="equipotorneo_GF"><?= $Page->renderFieldHeader($Page->GF) ?></div></th>
<?php } ?>
<?php if ($Page->GC->Visible) { // GC ?>
        <th data-name="GC" class="<?= $Page->GC->headerCellClass() ?>"><div id="elh_equipotorneo_GC" class="equipotorneo_GC"><?= $Page->renderFieldHeader($Page->GC) ?></div></th>
<?php } ?>
<?php if ($Page->GD->Visible) { // GD ?>
        <th data-name="GD" class="<?= $Page->GD->headerCellClass() ?>"><div id="elh_equipotorneo_GD" class="equipotorneo_GD"><?= $Page->renderFieldHeader($Page->GD) ?></div></th>
<?php } ?>
<?php if ($Page->GRUPO->Visible) { // GRUPO ?>
        <th data-name="GRUPO" class="<?= $Page->GRUPO->headerCellClass() ?>"><div id="elh_equipotorneo_GRUPO" class="equipotorneo_GRUPO"><?= $Page->renderFieldHeader($Page->GRUPO) ?></div></th>
<?php } ?>
<?php if ($Page->POSICION_EQUIPO_TORENO->Visible) { // POSICION_EQUIPO_TORENO ?>
        <th data-name="POSICION_EQUIPO_TORENO" class="<?= $Page->POSICION_EQUIPO_TORENO->headerCellClass() ?>"><div id="elh_equipotorneo_POSICION_EQUIPO_TORENO" class="equipotorneo_POSICION_EQUIPO_TORENO"><?= $Page->renderFieldHeader($Page->POSICION_EQUIPO_TORENO) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_equipotorneo",
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
    <?php if ($Page->ID_EQUIPO_TORNEO->Visible) { // ID_EQUIPO_TORNEO ?>
        <td data-name="ID_EQUIPO_TORNEO"<?= $Page->ID_EQUIPO_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_ID_EQUIPO_TORNEO" class="el_equipotorneo_ID_EQUIPO_TORNEO">
<span<?= $Page->ID_EQUIPO_TORNEO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO_TORNEO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <td data-name="ID_TORNEO"<?= $Page->ID_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_ID_TORNEO" class="el_equipotorneo_ID_TORNEO">
<span<?= $Page->ID_TORNEO->viewAttributes() ?>>
<?= $Page->ID_TORNEO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
        <td data-name="ID_EQUIPO"<?= $Page->ID_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_ID_EQUIPO" class="el_equipotorneo_ID_EQUIPO">
<span<?= $Page->ID_EQUIPO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PARTIDOS_JUGADOS->Visible) { // PARTIDOS_JUGADOS ?>
        <td data-name="PARTIDOS_JUGADOS"<?= $Page->PARTIDOS_JUGADOS->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_PARTIDOS_JUGADOS" class="el_equipotorneo_PARTIDOS_JUGADOS">
<span<?= $Page->PARTIDOS_JUGADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_JUGADOS->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PARTIDOS_GANADOS->Visible) { // PARTIDOS_GANADOS ?>
        <td data-name="PARTIDOS_GANADOS"<?= $Page->PARTIDOS_GANADOS->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_PARTIDOS_GANADOS" class="el_equipotorneo_PARTIDOS_GANADOS">
<span<?= $Page->PARTIDOS_GANADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_GANADOS->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PARTIDOS_EMPATADOS->Visible) { // PARTIDOS_EMPATADOS ?>
        <td data-name="PARTIDOS_EMPATADOS"<?= $Page->PARTIDOS_EMPATADOS->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_PARTIDOS_EMPATADOS" class="el_equipotorneo_PARTIDOS_EMPATADOS">
<span<?= $Page->PARTIDOS_EMPATADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_EMPATADOS->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PARTIDOS_PERDIDOS->Visible) { // PARTIDOS_PERDIDOS ?>
        <td data-name="PARTIDOS_PERDIDOS"<?= $Page->PARTIDOS_PERDIDOS->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_PARTIDOS_PERDIDOS" class="el_equipotorneo_PARTIDOS_PERDIDOS">
<span<?= $Page->PARTIDOS_PERDIDOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_PERDIDOS->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GF->Visible) { // GF ?>
        <td data-name="GF"<?= $Page->GF->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_GF" class="el_equipotorneo_GF">
<span<?= $Page->GF->viewAttributes() ?>>
<?= $Page->GF->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GC->Visible) { // GC ?>
        <td data-name="GC"<?= $Page->GC->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_GC" class="el_equipotorneo_GC">
<span<?= $Page->GC->viewAttributes() ?>>
<?= $Page->GC->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GD->Visible) { // GD ?>
        <td data-name="GD"<?= $Page->GD->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_GD" class="el_equipotorneo_GD">
<span<?= $Page->GD->viewAttributes() ?>>
<?= $Page->GD->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->GRUPO->Visible) { // GRUPO ?>
        <td data-name="GRUPO"<?= $Page->GRUPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_GRUPO" class="el_equipotorneo_GRUPO">
<span<?= $Page->GRUPO->viewAttributes() ?>>
<?= $Page->GRUPO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->POSICION_EQUIPO_TORENO->Visible) { // POSICION_EQUIPO_TORENO ?>
        <td data-name="POSICION_EQUIPO_TORENO"<?= $Page->POSICION_EQUIPO_TORENO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_POSICION_EQUIPO_TORENO" class="el_equipotorneo_POSICION_EQUIPO_TORENO">
<span<?= $Page->POSICION_EQUIPO_TORENO->viewAttributes() ?>>
<?= $Page->POSICION_EQUIPO_TORENO->getViewValue() ?></span>
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
    ew.addEventHandlers("equipotorneo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
