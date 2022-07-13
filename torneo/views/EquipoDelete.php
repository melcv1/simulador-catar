<?php

namespace PHPMaker2022\project1;

// Page object
$EquipoDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { equipo: currentTable } });
var currentForm, currentPageID;
var fequipodelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fequipodelete = new ew.Form("fequipodelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fequipodelete;
    loadjs.done("fequipodelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fequipodelete" id="fequipodelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="equipo">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table table-bordered table-hover table-sm ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
        <th class="<?= $Page->ID_EQUIPO->headerCellClass() ?>"><span id="elh_equipo_ID_EQUIPO" class="equipo_ID_EQUIPO"><?= $Page->ID_EQUIPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NOM_EQUIPO_CORTO->Visible) { // NOM_EQUIPO_CORTO ?>
        <th class="<?= $Page->NOM_EQUIPO_CORTO->headerCellClass() ?>"><span id="elh_equipo_NOM_EQUIPO_CORTO" class="equipo_NOM_EQUIPO_CORTO"><?= $Page->NOM_EQUIPO_CORTO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NOM_EQUIPO_LARGO->Visible) { // NOM_EQUIPO_LARGO ?>
        <th class="<?= $Page->NOM_EQUIPO_LARGO->headerCellClass() ?>"><span id="elh_equipo_NOM_EQUIPO_LARGO" class="equipo_NOM_EQUIPO_LARGO"><?= $Page->NOM_EQUIPO_LARGO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PAIS_EQUIPO->Visible) { // PAIS_EQUIPO ?>
        <th class="<?= $Page->PAIS_EQUIPO->headerCellClass() ?>"><span id="elh_equipo_PAIS_EQUIPO" class="equipo_PAIS_EQUIPO"><?= $Page->PAIS_EQUIPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->REGION_EQUIPO->Visible) { // REGION_EQUIPO ?>
        <th class="<?= $Page->REGION_EQUIPO->headerCellClass() ?>"><span id="elh_equipo_REGION_EQUIPO" class="equipo_REGION_EQUIPO"><?= $Page->REGION_EQUIPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->DETALLE_EQUIPO->Visible) { // DETALLE_EQUIPO ?>
        <th class="<?= $Page->DETALLE_EQUIPO->headerCellClass() ?>"><span id="elh_equipo_DETALLE_EQUIPO" class="equipo_DETALLE_EQUIPO"><?= $Page->DETALLE_EQUIPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ESCUDO_EQUIPO->Visible) { // ESCUDO_EQUIPO ?>
        <th class="<?= $Page->ESCUDO_EQUIPO->headerCellClass() ?>"><span id="elh_equipo_ESCUDO_EQUIPO" class="equipo_ESCUDO_EQUIPO"><?= $Page->ESCUDO_EQUIPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NOM_ESTADIO->Visible) { // NOM_ESTADIO ?>
        <th class="<?= $Page->NOM_ESTADIO->headerCellClass() ?>"><span id="elh_equipo_NOM_ESTADIO" class="equipo_NOM_ESTADIO"><?= $Page->NOM_ESTADIO->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
        <td<?= $Page->ID_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_ID_EQUIPO" class="el_equipo_ID_EQUIPO">
<span<?= $Page->ID_EQUIPO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NOM_EQUIPO_CORTO->Visible) { // NOM_EQUIPO_CORTO ?>
        <td<?= $Page->NOM_EQUIPO_CORTO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_NOM_EQUIPO_CORTO" class="el_equipo_NOM_EQUIPO_CORTO">
<span<?= $Page->NOM_EQUIPO_CORTO->viewAttributes() ?>>
<?= $Page->NOM_EQUIPO_CORTO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NOM_EQUIPO_LARGO->Visible) { // NOM_EQUIPO_LARGO ?>
        <td<?= $Page->NOM_EQUIPO_LARGO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_NOM_EQUIPO_LARGO" class="el_equipo_NOM_EQUIPO_LARGO">
<span<?= $Page->NOM_EQUIPO_LARGO->viewAttributes() ?>>
<?= $Page->NOM_EQUIPO_LARGO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PAIS_EQUIPO->Visible) { // PAIS_EQUIPO ?>
        <td<?= $Page->PAIS_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_PAIS_EQUIPO" class="el_equipo_PAIS_EQUIPO">
<span<?= $Page->PAIS_EQUIPO->viewAttributes() ?>>
<?= $Page->PAIS_EQUIPO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->REGION_EQUIPO->Visible) { // REGION_EQUIPO ?>
        <td<?= $Page->REGION_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_REGION_EQUIPO" class="el_equipo_REGION_EQUIPO">
<span<?= $Page->REGION_EQUIPO->viewAttributes() ?>>
<?= $Page->REGION_EQUIPO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->DETALLE_EQUIPO->Visible) { // DETALLE_EQUIPO ?>
        <td<?= $Page->DETALLE_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_DETALLE_EQUIPO" class="el_equipo_DETALLE_EQUIPO">
<span<?= $Page->DETALLE_EQUIPO->viewAttributes() ?>>
<?= $Page->DETALLE_EQUIPO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ESCUDO_EQUIPO->Visible) { // ESCUDO_EQUIPO ?>
        <td<?= $Page->ESCUDO_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_ESCUDO_EQUIPO" class="el_equipo_ESCUDO_EQUIPO">
<span>
<?= GetFileViewTag($Page->ESCUDO_EQUIPO, $Page->ESCUDO_EQUIPO->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->NOM_ESTADIO->Visible) { // NOM_ESTADIO ?>
        <td<?= $Page->NOM_ESTADIO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipo_NOM_ESTADIO" class="el_equipo_NOM_ESTADIO">
<span<?= $Page->NOM_ESTADIO->viewAttributes() ?>>
<?= $Page->NOM_ESTADIO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
