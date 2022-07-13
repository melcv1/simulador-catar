<?php

namespace PHPMaker2022\project1;

// Page object
$TorneoDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { torneo: currentTable } });
var currentForm, currentPageID;
var ftorneodelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    ftorneodelete = new ew.Form("ftorneodelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = ftorneodelete;
    loadjs.done("ftorneodelete");
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
<form name="ftorneodelete" id="ftorneodelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="torneo">
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
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <th class="<?= $Page->ID_TORNEO->headerCellClass() ?>"><span id="elh_torneo_ID_TORNEO" class="torneo_ID_TORNEO"><?= $Page->ID_TORNEO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NOM_TORNEO_CORTO->Visible) { // NOM_TORNEO_CORTO ?>
        <th class="<?= $Page->NOM_TORNEO_CORTO->headerCellClass() ?>"><span id="elh_torneo_NOM_TORNEO_CORTO" class="torneo_NOM_TORNEO_CORTO"><?= $Page->NOM_TORNEO_CORTO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NOM_TORNEO_LARGO->Visible) { // NOM_TORNEO_LARGO ?>
        <th class="<?= $Page->NOM_TORNEO_LARGO->headerCellClass() ?>"><span id="elh_torneo_NOM_TORNEO_LARGO" class="torneo_NOM_TORNEO_LARGO"><?= $Page->NOM_TORNEO_LARGO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PAIS_TORNEO->Visible) { // PAIS_TORNEO ?>
        <th class="<?= $Page->PAIS_TORNEO->headerCellClass() ?>"><span id="elh_torneo_PAIS_TORNEO" class="torneo_PAIS_TORNEO"><?= $Page->PAIS_TORNEO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->REGION_TORNEO->Visible) { // REGION_TORNEO ?>
        <th class="<?= $Page->REGION_TORNEO->headerCellClass() ?>"><span id="elh_torneo_REGION_TORNEO" class="torneo_REGION_TORNEO"><?= $Page->REGION_TORNEO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->DETALLE_TORNEO->Visible) { // DETALLE_TORNEO ?>
        <th class="<?= $Page->DETALLE_TORNEO->headerCellClass() ?>"><span id="elh_torneo_DETALLE_TORNEO" class="torneo_DETALLE_TORNEO"><?= $Page->DETALLE_TORNEO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->LOGO_TORNEO->Visible) { // LOGO_TORNEO ?>
        <th class="<?= $Page->LOGO_TORNEO->headerCellClass() ?>"><span id="elh_torneo_LOGO_TORNEO" class="torneo_LOGO_TORNEO"><?= $Page->LOGO_TORNEO->caption() ?></span></th>
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
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <td<?= $Page->ID_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_torneo_ID_TORNEO" class="el_torneo_ID_TORNEO">
<span<?= $Page->ID_TORNEO->viewAttributes() ?>>
<?= $Page->ID_TORNEO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NOM_TORNEO_CORTO->Visible) { // NOM_TORNEO_CORTO ?>
        <td<?= $Page->NOM_TORNEO_CORTO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_torneo_NOM_TORNEO_CORTO" class="el_torneo_NOM_TORNEO_CORTO">
<span<?= $Page->NOM_TORNEO_CORTO->viewAttributes() ?>>
<?= $Page->NOM_TORNEO_CORTO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NOM_TORNEO_LARGO->Visible) { // NOM_TORNEO_LARGO ?>
        <td<?= $Page->NOM_TORNEO_LARGO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_torneo_NOM_TORNEO_LARGO" class="el_torneo_NOM_TORNEO_LARGO">
<span<?= $Page->NOM_TORNEO_LARGO->viewAttributes() ?>>
<?= $Page->NOM_TORNEO_LARGO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PAIS_TORNEO->Visible) { // PAIS_TORNEO ?>
        <td<?= $Page->PAIS_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_torneo_PAIS_TORNEO" class="el_torneo_PAIS_TORNEO">
<span<?= $Page->PAIS_TORNEO->viewAttributes() ?>>
<?= $Page->PAIS_TORNEO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->REGION_TORNEO->Visible) { // REGION_TORNEO ?>
        <td<?= $Page->REGION_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_torneo_REGION_TORNEO" class="el_torneo_REGION_TORNEO">
<span<?= $Page->REGION_TORNEO->viewAttributes() ?>>
<?= $Page->REGION_TORNEO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->DETALLE_TORNEO->Visible) { // DETALLE_TORNEO ?>
        <td<?= $Page->DETALLE_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_torneo_DETALLE_TORNEO" class="el_torneo_DETALLE_TORNEO">
<span<?= $Page->DETALLE_TORNEO->viewAttributes() ?>>
<?= $Page->DETALLE_TORNEO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->LOGO_TORNEO->Visible) { // LOGO_TORNEO ?>
        <td<?= $Page->LOGO_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_torneo_LOGO_TORNEO" class="el_torneo_LOGO_TORNEO">
<span>
<?= GetFileViewTag($Page->LOGO_TORNEO, $Page->LOGO_TORNEO->getViewValue(), false) ?>
</span>
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
