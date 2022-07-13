<?php

namespace PHPMaker2022\project1;

// Page object
$PartidosDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { partidos: currentTable } });
var currentForm, currentPageID;
var fpartidosdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fpartidosdelete = new ew.Form("fpartidosdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fpartidosdelete;
    loadjs.done("fpartidosdelete");
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
<form name="fpartidosdelete" id="fpartidosdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="partidos">
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
<?php if ($Page->ID_EQUIPO2->Visible) { // ID_EQUIPO2 ?>
        <th class="<?= $Page->ID_EQUIPO2->headerCellClass() ?>"><span id="elh_partidos_ID_EQUIPO2" class="partidos_ID_EQUIPO2"><?= $Page->ID_EQUIPO2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ID_EQUIPO1->Visible) { // ID_EQUIPO1 ?>
        <th class="<?= $Page->ID_EQUIPO1->headerCellClass() ?>"><span id="elh_partidos_ID_EQUIPO1" class="partidos_ID_EQUIPO1"><?= $Page->ID_EQUIPO1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ID_PARTIDO->Visible) { // ID_PARTIDO ?>
        <th class="<?= $Page->ID_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_ID_PARTIDO" class="partidos_ID_PARTIDO"><?= $Page->ID_PARTIDO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <th class="<?= $Page->ID_TORNEO->headerCellClass() ?>"><span id="elh_partidos_ID_TORNEO" class="partidos_ID_TORNEO"><?= $Page->ID_TORNEO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->FECHA_PARTIDO->Visible) { // FECHA_PARTIDO ?>
        <th class="<?= $Page->FECHA_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_FECHA_PARTIDO" class="partidos_FECHA_PARTIDO"><?= $Page->FECHA_PARTIDO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->HORA_PARTIDO->Visible) { // HORA_PARTIDO ?>
        <th class="<?= $Page->HORA_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_HORA_PARTIDO" class="partidos_HORA_PARTIDO"><?= $Page->HORA_PARTIDO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->DIA_PARTIDO->Visible) { // DIA_PARTIDO ?>
        <th class="<?= $Page->DIA_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_DIA_PARTIDO" class="partidos_DIA_PARTIDO"><?= $Page->DIA_PARTIDO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ESTADIO->Visible) { // ESTADIO ?>
        <th class="<?= $Page->ESTADIO->headerCellClass() ?>"><span id="elh_partidos_ESTADIO" class="partidos_ESTADIO"><?= $Page->ESTADIO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->CIUDAD_PARTIDO->Visible) { // CIUDAD_PARTIDO ?>
        <th class="<?= $Page->CIUDAD_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_CIUDAD_PARTIDO" class="partidos_CIUDAD_PARTIDO"><?= $Page->CIUDAD_PARTIDO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->PAIS_PARTIDO->Visible) { // PAIS_PARTIDO ?>
        <th class="<?= $Page->PAIS_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_PAIS_PARTIDO" class="partidos_PAIS_PARTIDO"><?= $Page->PAIS_PARTIDO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO1->Visible) { // GOLES_EQUIPO1 ?>
        <th class="<?= $Page->GOLES_EQUIPO1->headerCellClass() ?>"><span id="elh_partidos_GOLES_EQUIPO1" class="partidos_GOLES_EQUIPO1"><?= $Page->GOLES_EQUIPO1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO2->Visible) { // GOLES_EQUIPO2 ?>
        <th class="<?= $Page->GOLES_EQUIPO2->headerCellClass() ?>"><span id="elh_partidos_GOLES_EQUIPO2" class="partidos_GOLES_EQUIPO2"><?= $Page->GOLES_EQUIPO2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO1->Visible) { // GOLES_EXTRA_EQUIPO1 ?>
        <th class="<?= $Page->GOLES_EXTRA_EQUIPO1->headerCellClass() ?>"><span id="elh_partidos_GOLES_EXTRA_EQUIPO1" class="partidos_GOLES_EXTRA_EQUIPO1"><?= $Page->GOLES_EXTRA_EQUIPO1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO2->Visible) { // GOLES_EXTRA_EQUIPO2 ?>
        <th class="<?= $Page->GOLES_EXTRA_EQUIPO2->headerCellClass() ?>"><span id="elh_partidos_GOLES_EXTRA_EQUIPO2" class="partidos_GOLES_EXTRA_EQUIPO2"><?= $Page->GOLES_EXTRA_EQUIPO2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NOTA_PARTIDO->Visible) { // NOTA_PARTIDO ?>
        <th class="<?= $Page->NOTA_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_NOTA_PARTIDO" class="partidos_NOTA_PARTIDO"><?= $Page->NOTA_PARTIDO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->RESUMEN_PARTIDO->Visible) { // RESUMEN_PARTIDO ?>
        <th class="<?= $Page->RESUMEN_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_RESUMEN_PARTIDO" class="partidos_RESUMEN_PARTIDO"><?= $Page->RESUMEN_PARTIDO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ESTADO_PARTIDO->Visible) { // ESTADO_PARTIDO ?>
        <th class="<?= $Page->ESTADO_PARTIDO->headerCellClass() ?>"><span id="elh_partidos_ESTADO_PARTIDO" class="partidos_ESTADO_PARTIDO"><?= $Page->ESTADO_PARTIDO->caption() ?></span></th>
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
<?php if ($Page->ID_EQUIPO2->Visible) { // ID_EQUIPO2 ?>
        <td<?= $Page->ID_EQUIPO2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ID_EQUIPO2" class="el_partidos_ID_EQUIPO2">
<span<?= $Page->ID_EQUIPO2->viewAttributes() ?>>
<?= $Page->ID_EQUIPO2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ID_EQUIPO1->Visible) { // ID_EQUIPO1 ?>
        <td<?= $Page->ID_EQUIPO1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ID_EQUIPO1" class="el_partidos_ID_EQUIPO1">
<span<?= $Page->ID_EQUIPO1->viewAttributes() ?>>
<?= $Page->ID_EQUIPO1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ID_PARTIDO->Visible) { // ID_PARTIDO ?>
        <td<?= $Page->ID_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ID_PARTIDO" class="el_partidos_ID_PARTIDO">
<span<?= $Page->ID_PARTIDO->viewAttributes() ?>>
<?= $Page->ID_PARTIDO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
        <td<?= $Page->ID_TORNEO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ID_TORNEO" class="el_partidos_ID_TORNEO">
<span<?= $Page->ID_TORNEO->viewAttributes() ?>>
<?= $Page->ID_TORNEO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->FECHA_PARTIDO->Visible) { // FECHA_PARTIDO ?>
        <td<?= $Page->FECHA_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_FECHA_PARTIDO" class="el_partidos_FECHA_PARTIDO">
<span<?= $Page->FECHA_PARTIDO->viewAttributes() ?>>
<?= $Page->FECHA_PARTIDO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->HORA_PARTIDO->Visible) { // HORA_PARTIDO ?>
        <td<?= $Page->HORA_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_HORA_PARTIDO" class="el_partidos_HORA_PARTIDO">
<span<?= $Page->HORA_PARTIDO->viewAttributes() ?>>
<?= $Page->HORA_PARTIDO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->DIA_PARTIDO->Visible) { // DIA_PARTIDO ?>
        <td<?= $Page->DIA_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_DIA_PARTIDO" class="el_partidos_DIA_PARTIDO">
<span<?= $Page->DIA_PARTIDO->viewAttributes() ?>>
<?= $Page->DIA_PARTIDO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ESTADIO->Visible) { // ESTADIO ?>
        <td<?= $Page->ESTADIO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ESTADIO" class="el_partidos_ESTADIO">
<span<?= $Page->ESTADIO->viewAttributes() ?>>
<?= $Page->ESTADIO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->CIUDAD_PARTIDO->Visible) { // CIUDAD_PARTIDO ?>
        <td<?= $Page->CIUDAD_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_CIUDAD_PARTIDO" class="el_partidos_CIUDAD_PARTIDO">
<span<?= $Page->CIUDAD_PARTIDO->viewAttributes() ?>>
<?= $Page->CIUDAD_PARTIDO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->PAIS_PARTIDO->Visible) { // PAIS_PARTIDO ?>
        <td<?= $Page->PAIS_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_PAIS_PARTIDO" class="el_partidos_PAIS_PARTIDO">
<span<?= $Page->PAIS_PARTIDO->viewAttributes() ?>>
<?= $Page->PAIS_PARTIDO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO1->Visible) { // GOLES_EQUIPO1 ?>
        <td<?= $Page->GOLES_EQUIPO1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_GOLES_EQUIPO1" class="el_partidos_GOLES_EQUIPO1">
<span<?= $Page->GOLES_EQUIPO1->viewAttributes() ?>>
<?= $Page->GOLES_EQUIPO1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO2->Visible) { // GOLES_EQUIPO2 ?>
        <td<?= $Page->GOLES_EQUIPO2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_GOLES_EQUIPO2" class="el_partidos_GOLES_EQUIPO2">
<span<?= $Page->GOLES_EQUIPO2->viewAttributes() ?>>
<?= $Page->GOLES_EQUIPO2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO1->Visible) { // GOLES_EXTRA_EQUIPO1 ?>
        <td<?= $Page->GOLES_EXTRA_EQUIPO1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_GOLES_EXTRA_EQUIPO1" class="el_partidos_GOLES_EXTRA_EQUIPO1">
<span<?= $Page->GOLES_EXTRA_EQUIPO1->viewAttributes() ?>>
<?= $Page->GOLES_EXTRA_EQUIPO1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO2->Visible) { // GOLES_EXTRA_EQUIPO2 ?>
        <td<?= $Page->GOLES_EXTRA_EQUIPO2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_GOLES_EXTRA_EQUIPO2" class="el_partidos_GOLES_EXTRA_EQUIPO2">
<span<?= $Page->GOLES_EXTRA_EQUIPO2->viewAttributes() ?>>
<?= $Page->GOLES_EXTRA_EQUIPO2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NOTA_PARTIDO->Visible) { // NOTA_PARTIDO ?>
        <td<?= $Page->NOTA_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_NOTA_PARTIDO" class="el_partidos_NOTA_PARTIDO">
<span<?= $Page->NOTA_PARTIDO->viewAttributes() ?>>
<?= $Page->NOTA_PARTIDO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->RESUMEN_PARTIDO->Visible) { // RESUMEN_PARTIDO ?>
        <td<?= $Page->RESUMEN_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_RESUMEN_PARTIDO" class="el_partidos_RESUMEN_PARTIDO">
<span<?= $Page->RESUMEN_PARTIDO->viewAttributes() ?>>
<?= $Page->RESUMEN_PARTIDO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ESTADO_PARTIDO->Visible) { // ESTADO_PARTIDO ?>
        <td<?= $Page->ESTADO_PARTIDO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_partidos_ESTADO_PARTIDO" class="el_partidos_ESTADO_PARTIDO">
<span<?= $Page->ESTADO_PARTIDO->viewAttributes() ?>>
<?= $Page->ESTADO_PARTIDO->getViewValue() ?></span>
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
