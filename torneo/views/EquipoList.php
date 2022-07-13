<?php

namespace PHPMaker2022\project1;

// Page object
$EquipoList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { equipo: currentTable } });
var currentForm, currentPageID;
var fequipolist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fequipolist = new ew.Form("fequipolist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fequipolist;
    fequipolist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fequipolist");
});
var fequiposrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fequiposrch = new ew.Form("fequiposrch", "list");
    currentSearchForm = fequiposrch;

    // Dynamic selection lists

    // Filters
    fequiposrch.filterList = <?= $Page->getFilterList() ?>;
    loadjs.done("fequiposrch");
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
<form name="fequiposrch" id="fequiposrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fequiposrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="equipo">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fequiposrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fequiposrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fequiposrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fequiposrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> equipo">
<form name="fequipolist" id="fequipolist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="equipo">
<div id="gmp_equipo" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_equipolist" class="table table-bordered table-hover table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
        <th data-name="ID_EQUIPO" class="<?= $Page->ID_EQUIPO->headerCellClass() ?>"><div id="elh_equipo_ID_EQUIPO" class="equipo_ID_EQUIPO"><?= $Page->renderFieldHeader($Page->ID_EQUIPO) ?></div></th>
<?php } ?>
<?php if ($Page->NOM_EQUIPO_CORTO->Visible) { // NOM_EQUIPO_CORTO ?>
        <th data-name="NOM_EQUIPO_CORTO" class="<?= $Page->NOM_EQUIPO_CORTO->headerCellClass() ?>"><div id="elh_equipo_NOM_EQUIPO_CORTO" class="equipo_NOM_EQUIPO_CORTO"><?= $Page->renderFieldHeader($Page->NOM_EQUIPO_CORTO) ?></div></th>
<?php } ?>
<?php if ($Page->NOM_EQUIPO_LARGO->Visible) { // NOM_EQUIPO_LARGO ?>
        <th data-name="NOM_EQUIPO_LARGO" class="<?= $Page->NOM_EQUIPO_LARGO->headerCellClass() ?>"><div id="elh_equipo_NOM_EQUIPO_LARGO" class="equipo_NOM_EQUIPO_LARGO"><?= $Page->renderFieldHeader($Page->NOM_EQUIPO_LARGO) ?></div></th>
<?php } ?>
<?php if ($Page->PAIS_EQUIPO->Visible) { // PAIS_EQUIPO ?>
        <th data-name="PAIS_EQUIPO" class="<?= $Page->PAIS_EQUIPO->headerCellClass() ?>"><div id="elh_equipo_PAIS_EQUIPO" class="equipo_PAIS_EQUIPO"><?= $Page->renderFieldHeader($Page->PAIS_EQUIPO) ?></div></th>
<?php } ?>
<?php if ($Page->REGION_EQUIPO->Visible) { // REGION_EQUIPO ?>
        <th data-name="REGION_EQUIPO" class="<?= $Page->REGION_EQUIPO->headerCellClass() ?>"><div id="elh_equipo_REGION_EQUIPO" class="equipo_REGION_EQUIPO"><?= $Page->renderFieldHeader($Page->REGION_EQUIPO) ?></div></th>
<?php } ?>
<?php if ($Page->DETALLE_EQUIPO->Visible) { // DETALLE_EQUIPO ?>
        <th data-name="DETALLE_EQUIPO" class="<?= $Page->DETALLE_EQUIPO->headerCellClass() ?>"><div id="elh_equipo_DETALLE_EQUIPO" class="equipo_DETALLE_EQUIPO"><?= $Page->renderFieldHeader($Page->DETALLE_EQUIPO) ?></div></th>
<?php } ?>
<?php if ($Page->ESCUDO_EQUIPO->Visible) { // ESCUDO_EQUIPO ?>
        <th data-name="ESCUDO_EQUIPO" class="<?= $Page->ESCUDO_EQUIPO->headerCellClass() ?>"><div id="elh_equipo_ESCUDO_EQUIPO" class="equipo_ESCUDO_EQUIPO"><?= $Page->renderFieldHeader($Page->ESCUDO_EQUIPO) ?></div></th>
<?php } ?>
<?php if ($Page->NOM_ESTADIO->Visible) { // NOM_ESTADIO ?>
        <th data-name="NOM_ESTADIO" class="<?= $Page->NOM_ESTADIO->headerCellClass() ?>"><div id="elh_equipo_NOM_ESTADIO" class="equipo_NOM_ESTADIO"><?= $Page->renderFieldHeader($Page->NOM_ESTADIO) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_equipo",
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
    <?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
        <td data-name="ID_EQUIPO"<?= $Page->ID_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_ID_EQUIPO" class="el_equipo_ID_EQUIPO">
<span<?= $Page->ID_EQUIPO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NOM_EQUIPO_CORTO->Visible) { // NOM_EQUIPO_CORTO ?>
        <td data-name="NOM_EQUIPO_CORTO"<?= $Page->NOM_EQUIPO_CORTO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_NOM_EQUIPO_CORTO" class="el_equipo_NOM_EQUIPO_CORTO">
<span<?= $Page->NOM_EQUIPO_CORTO->viewAttributes() ?>>
<?= $Page->NOM_EQUIPO_CORTO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NOM_EQUIPO_LARGO->Visible) { // NOM_EQUIPO_LARGO ?>
        <td data-name="NOM_EQUIPO_LARGO"<?= $Page->NOM_EQUIPO_LARGO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_NOM_EQUIPO_LARGO" class="el_equipo_NOM_EQUIPO_LARGO">
<span<?= $Page->NOM_EQUIPO_LARGO->viewAttributes() ?>>
<?= $Page->NOM_EQUIPO_LARGO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->PAIS_EQUIPO->Visible) { // PAIS_EQUIPO ?>
        <td data-name="PAIS_EQUIPO"<?= $Page->PAIS_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_PAIS_EQUIPO" class="el_equipo_PAIS_EQUIPO">
<span<?= $Page->PAIS_EQUIPO->viewAttributes() ?>>
<?= $Page->PAIS_EQUIPO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->REGION_EQUIPO->Visible) { // REGION_EQUIPO ?>
        <td data-name="REGION_EQUIPO"<?= $Page->REGION_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_REGION_EQUIPO" class="el_equipo_REGION_EQUIPO">
<span<?= $Page->REGION_EQUIPO->viewAttributes() ?>>
<?= $Page->REGION_EQUIPO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->DETALLE_EQUIPO->Visible) { // DETALLE_EQUIPO ?>
        <td data-name="DETALLE_EQUIPO"<?= $Page->DETALLE_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_DETALLE_EQUIPO" class="el_equipo_DETALLE_EQUIPO">
<span<?= $Page->DETALLE_EQUIPO->viewAttributes() ?>>
<?= $Page->DETALLE_EQUIPO->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->ESCUDO_EQUIPO->Visible) { // ESCUDO_EQUIPO ?>
        <td data-name="ESCUDO_EQUIPO"<?= $Page->ESCUDO_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_ESCUDO_EQUIPO" class="el_equipo_ESCUDO_EQUIPO">
<span>
<?= GetFileViewTag($Page->ESCUDO_EQUIPO, $Page->ESCUDO_EQUIPO->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->NOM_ESTADIO->Visible) { // NOM_ESTADIO ?>
        <td data-name="NOM_ESTADIO"<?= $Page->NOM_ESTADIO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_NOM_ESTADIO" class="el_equipo_NOM_ESTADIO">
<span<?= $Page->NOM_ESTADIO->viewAttributes() ?>>
<?= $Page->NOM_ESTADIO->getViewValue() ?></span>
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
    ew.addEventHandlers("equipo");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
