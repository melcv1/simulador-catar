<?php

namespace PHPMaker2022\project1;

// Page object
$EquipotorneoDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { equipotorneo: currentTable } });
var currentForm, currentPageID;
var fequipotorneodelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fequipotorneodelete = new ew.Form("fequipotorneodelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fequipotorneodelete;
    loadjs.done("fequipotorneodelete");
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
<form name="fequipotorneodelete" id="fequipotorneodelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="equipotorneo">
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
<?php if ($Page->ID_EQUIPO_TORNEO->Visible) { // ID_EQUIPO_TORNEO ?>
        <th class="<?= $Page->ID_EQUIPO_TORNEO->headerCellClass() ?>"><span id="elh_equipotorneo_ID_EQUIPO_TORNEO" class="equipotorneo_ID_EQUIPO_TORNEO"><?= $Page->ID_EQUIPO_TORNEO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <th class="<?= $Page->ID_TORNEO->headerCellClass() ?>"><span id="elh_equipotorneo_ID_TORNEO" class="equipotorneo_ID_TORNEO"><?= $Page->ID_TORNEO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
        <th class="<?= $Page->ID_EQUIPO->headerCellClass() ?>"><span id="elh_equipotorneo_ID_EQUIPO" class="equipotorneo_ID_EQUIPO"><?= $Page->ID_EQUIPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PARTIDOS_JUGADOS->Visible) { // PARTIDOS_JUGADOS ?>
        <th class="<?= $Page->PARTIDOS_JUGADOS->headerCellClass() ?>"><span id="elh_equipotorneo_PARTIDOS_JUGADOS" class="equipotorneo_PARTIDOS_JUGADOS"><?= $Page->PARTIDOS_JUGADOS->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PARTIDOS_GANADOS->Visible) { // PARTIDOS_GANADOS ?>
        <th class="<?= $Page->PARTIDOS_GANADOS->headerCellClass() ?>"><span id="elh_equipotorneo_PARTIDOS_GANADOS" class="equipotorneo_PARTIDOS_GANADOS"><?= $Page->PARTIDOS_GANADOS->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PARTIDOS_EMPATADOS->Visible) { // PARTIDOS_EMPATADOS ?>
        <th class="<?= $Page->PARTIDOS_EMPATADOS->headerCellClass() ?>"><span id="elh_equipotorneo_PARTIDOS_EMPATADOS" class="equipotorneo_PARTIDOS_EMPATADOS"><?= $Page->PARTIDOS_EMPATADOS->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PARTIDOS_PERDIDOS->Visible) { // PARTIDOS_PERDIDOS ?>
        <th class="<?= $Page->PARTIDOS_PERDIDOS->headerCellClass() ?>"><span id="elh_equipotorneo_PARTIDOS_PERDIDOS" class="equipotorneo_PARTIDOS_PERDIDOS"><?= $Page->PARTIDOS_PERDIDOS->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GF->Visible) { // GF ?>
        <th class="<?= $Page->GF->headerCellClass() ?>"><span id="elh_equipotorneo_GF" class="equipotorneo_GF"><?= $Page->GF->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GC->Visible) { // GC ?>
        <th class="<?= $Page->GC->headerCellClass() ?>"><span id="elh_equipotorneo_GC" class="equipotorneo_GC"><?= $Page->GC->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GD->Visible) { // GD ?>
        <th class="<?= $Page->GD->headerCellClass() ?>"><span id="elh_equipotorneo_GD" class="equipotorneo_GD"><?= $Page->GD->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GRUPO->Visible) { // GRUPO ?>
        <th class="<?= $Page->GRUPO->headerCellClass() ?>"><span id="elh_equipotorneo_GRUPO" class="equipotorneo_GRUPO"><?= $Page->GRUPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->POSICION_EQUIPO_TORENO->Visible) { // POSICION_EQUIPO_TORENO ?>
        <th class="<?= $Page->POSICION_EQUIPO_TORENO->headerCellClass() ?>"><span id="elh_equipotorneo_POSICION_EQUIPO_TORENO" class="equipotorneo_POSICION_EQUIPO_TORENO"><?= $Page->POSICION_EQUIPO_TORENO->caption() ?></span></th>
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
<?php if ($Page->ID_EQUIPO_TORNEO->Visible) { // ID_EQUIPO_TORNEO ?>
        <td<?= $Page->ID_EQUIPO_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_ID_EQUIPO_TORNEO" class="el_equipotorneo_ID_EQUIPO_TORNEO">
<span<?= $Page->ID_EQUIPO_TORNEO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO_TORNEO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <td<?= $Page->ID_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_ID_TORNEO" class="el_equipotorneo_ID_TORNEO">
<span<?= $Page->ID_TORNEO->viewAttributes() ?>>
<?= $Page->ID_TORNEO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
        <td<?= $Page->ID_EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_ID_EQUIPO" class="el_equipotorneo_ID_EQUIPO">
<span<?= $Page->ID_EQUIPO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PARTIDOS_JUGADOS->Visible) { // PARTIDOS_JUGADOS ?>
        <td<?= $Page->PARTIDOS_JUGADOS->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_PARTIDOS_JUGADOS" class="el_equipotorneo_PARTIDOS_JUGADOS">
<span<?= $Page->PARTIDOS_JUGADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_JUGADOS->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PARTIDOS_GANADOS->Visible) { // PARTIDOS_GANADOS ?>
        <td<?= $Page->PARTIDOS_GANADOS->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_PARTIDOS_GANADOS" class="el_equipotorneo_PARTIDOS_GANADOS">
<span<?= $Page->PARTIDOS_GANADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_GANADOS->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PARTIDOS_EMPATADOS->Visible) { // PARTIDOS_EMPATADOS ?>
        <td<?= $Page->PARTIDOS_EMPATADOS->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_PARTIDOS_EMPATADOS" class="el_equipotorneo_PARTIDOS_EMPATADOS">
<span<?= $Page->PARTIDOS_EMPATADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_EMPATADOS->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PARTIDOS_PERDIDOS->Visible) { // PARTIDOS_PERDIDOS ?>
        <td<?= $Page->PARTIDOS_PERDIDOS->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_PARTIDOS_PERDIDOS" class="el_equipotorneo_PARTIDOS_PERDIDOS">
<span<?= $Page->PARTIDOS_PERDIDOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_PERDIDOS->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GF->Visible) { // GF ?>
        <td<?= $Page->GF->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_GF" class="el_equipotorneo_GF">
<span<?= $Page->GF->viewAttributes() ?>>
<?= $Page->GF->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GC->Visible) { // GC ?>
        <td<?= $Page->GC->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_GC" class="el_equipotorneo_GC">
<span<?= $Page->GC->viewAttributes() ?>>
<?= $Page->GC->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GD->Visible) { // GD ?>
        <td<?= $Page->GD->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_GD" class="el_equipotorneo_GD">
<span<?= $Page->GD->viewAttributes() ?>>
<?= $Page->GD->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GRUPO->Visible) { // GRUPO ?>
        <td<?= $Page->GRUPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_GRUPO" class="el_equipotorneo_GRUPO">
<span<?= $Page->GRUPO->viewAttributes() ?>>
<?= $Page->GRUPO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->POSICION_EQUIPO_TORENO->Visible) { // POSICION_EQUIPO_TORENO ?>
        <td<?= $Page->POSICION_EQUIPO_TORENO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_equipotorneo_POSICION_EQUIPO_TORENO" class="el_equipotorneo_POSICION_EQUIPO_TORENO">
<span<?= $Page->POSICION_EQUIPO_TORENO->viewAttributes() ?>>
<?= $Page->POSICION_EQUIPO_TORENO->getViewValue() ?></span>
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
