<?php

namespace PHPMaker2022\project1;

// Page object
$PartidosView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { partidos: currentTable } });
var currentForm, currentPageID;
var fpartidosview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fpartidosview = new ew.Form("fpartidosview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fpartidosview;
    loadjs.done("fpartidosview");
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
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fpartidosview" id="fpartidosview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="partidos">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->ID_EQUIPO2->Visible) { // ID_EQUIPO2 ?>
    <tr id="r_ID_EQUIPO2"<?= $Page->ID_EQUIPO2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_ID_EQUIPO2"><?= $Page->ID_EQUIPO2->caption() ?></span></td>
        <td data-name="ID_EQUIPO2"<?= $Page->ID_EQUIPO2->cellAttributes() ?>>
<span id="el_partidos_ID_EQUIPO2">
<span<?= $Page->ID_EQUIPO2->viewAttributes() ?>>
<?= $Page->ID_EQUIPO2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ID_EQUIPO1->Visible) { // ID_EQUIPO1 ?>
    <tr id="r_ID_EQUIPO1"<?= $Page->ID_EQUIPO1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_ID_EQUIPO1"><?= $Page->ID_EQUIPO1->caption() ?></span></td>
        <td data-name="ID_EQUIPO1"<?= $Page->ID_EQUIPO1->cellAttributes() ?>>
<span id="el_partidos_ID_EQUIPO1">
<span<?= $Page->ID_EQUIPO1->viewAttributes() ?>>
<?= $Page->ID_EQUIPO1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ID_PARTIDO->Visible) { // ID_PARTIDO ?>
    <tr id="r_ID_PARTIDO"<?= $Page->ID_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_ID_PARTIDO"><?= $Page->ID_PARTIDO->caption() ?></span></td>
        <td data-name="ID_PARTIDO"<?= $Page->ID_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_ID_PARTIDO">
<span<?= $Page->ID_PARTIDO->viewAttributes() ?>>
<?= $Page->ID_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
    <tr id="r_ID_TORNEO"<?= $Page->ID_TORNEO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_ID_TORNEO"><?= $Page->ID_TORNEO->caption() ?></span></td>
        <td data-name="ID_TORNEO"<?= $Page->ID_TORNEO->cellAttributes() ?>>
<span id="el_partidos_ID_TORNEO">
<span<?= $Page->ID_TORNEO->viewAttributes() ?>>
<?= $Page->ID_TORNEO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->FECHA_PARTIDO->Visible) { // FECHA_PARTIDO ?>
    <tr id="r_FECHA_PARTIDO"<?= $Page->FECHA_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_FECHA_PARTIDO"><?= $Page->FECHA_PARTIDO->caption() ?></span></td>
        <td data-name="FECHA_PARTIDO"<?= $Page->FECHA_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_FECHA_PARTIDO">
<span<?= $Page->FECHA_PARTIDO->viewAttributes() ?>>
<?= $Page->FECHA_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->HORA_PARTIDO->Visible) { // HORA_PARTIDO ?>
    <tr id="r_HORA_PARTIDO"<?= $Page->HORA_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_HORA_PARTIDO"><?= $Page->HORA_PARTIDO->caption() ?></span></td>
        <td data-name="HORA_PARTIDO"<?= $Page->HORA_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_HORA_PARTIDO">
<span<?= $Page->HORA_PARTIDO->viewAttributes() ?>>
<?= $Page->HORA_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->DIA_PARTIDO->Visible) { // DIA_PARTIDO ?>
    <tr id="r_DIA_PARTIDO"<?= $Page->DIA_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_DIA_PARTIDO"><?= $Page->DIA_PARTIDO->caption() ?></span></td>
        <td data-name="DIA_PARTIDO"<?= $Page->DIA_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_DIA_PARTIDO">
<span<?= $Page->DIA_PARTIDO->viewAttributes() ?>>
<?= $Page->DIA_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ESTADIO->Visible) { // ESTADIO ?>
    <tr id="r_ESTADIO"<?= $Page->ESTADIO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_ESTADIO"><?= $Page->ESTADIO->caption() ?></span></td>
        <td data-name="ESTADIO"<?= $Page->ESTADIO->cellAttributes() ?>>
<span id="el_partidos_ESTADIO">
<span<?= $Page->ESTADIO->viewAttributes() ?>>
<?= $Page->ESTADIO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->CIUDAD_PARTIDO->Visible) { // CIUDAD_PARTIDO ?>
    <tr id="r_CIUDAD_PARTIDO"<?= $Page->CIUDAD_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_CIUDAD_PARTIDO"><?= $Page->CIUDAD_PARTIDO->caption() ?></span></td>
        <td data-name="CIUDAD_PARTIDO"<?= $Page->CIUDAD_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_CIUDAD_PARTIDO">
<span<?= $Page->CIUDAD_PARTIDO->viewAttributes() ?>>
<?= $Page->CIUDAD_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PAIS_PARTIDO->Visible) { // PAIS_PARTIDO ?>
    <tr id="r_PAIS_PARTIDO"<?= $Page->PAIS_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_PAIS_PARTIDO"><?= $Page->PAIS_PARTIDO->caption() ?></span></td>
        <td data-name="PAIS_PARTIDO"<?= $Page->PAIS_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_PAIS_PARTIDO">
<span<?= $Page->PAIS_PARTIDO->viewAttributes() ?>>
<?= $Page->PAIS_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO1->Visible) { // GOLES_EQUIPO1 ?>
    <tr id="r_GOLES_EQUIPO1"<?= $Page->GOLES_EQUIPO1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_GOLES_EQUIPO1"><?= $Page->GOLES_EQUIPO1->caption() ?></span></td>
        <td data-name="GOLES_EQUIPO1"<?= $Page->GOLES_EQUIPO1->cellAttributes() ?>>
<span id="el_partidos_GOLES_EQUIPO1">
<span<?= $Page->GOLES_EQUIPO1->viewAttributes() ?>>
<?= $Page->GOLES_EQUIPO1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO2->Visible) { // GOLES_EQUIPO2 ?>
    <tr id="r_GOLES_EQUIPO2"<?= $Page->GOLES_EQUIPO2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_GOLES_EQUIPO2"><?= $Page->GOLES_EQUIPO2->caption() ?></span></td>
        <td data-name="GOLES_EQUIPO2"<?= $Page->GOLES_EQUIPO2->cellAttributes() ?>>
<span id="el_partidos_GOLES_EQUIPO2">
<span<?= $Page->GOLES_EQUIPO2->viewAttributes() ?>>
<?= $Page->GOLES_EQUIPO2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO1->Visible) { // GOLES_EXTRA_EQUIPO1 ?>
    <tr id="r_GOLES_EXTRA_EQUIPO1"<?= $Page->GOLES_EXTRA_EQUIPO1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_GOLES_EXTRA_EQUIPO1"><?= $Page->GOLES_EXTRA_EQUIPO1->caption() ?></span></td>
        <td data-name="GOLES_EXTRA_EQUIPO1"<?= $Page->GOLES_EXTRA_EQUIPO1->cellAttributes() ?>>
<span id="el_partidos_GOLES_EXTRA_EQUIPO1">
<span<?= $Page->GOLES_EXTRA_EQUIPO1->viewAttributes() ?>>
<?= $Page->GOLES_EXTRA_EQUIPO1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO2->Visible) { // GOLES_EXTRA_EQUIPO2 ?>
    <tr id="r_GOLES_EXTRA_EQUIPO2"<?= $Page->GOLES_EXTRA_EQUIPO2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_GOLES_EXTRA_EQUIPO2"><?= $Page->GOLES_EXTRA_EQUIPO2->caption() ?></span></td>
        <td data-name="GOLES_EXTRA_EQUIPO2"<?= $Page->GOLES_EXTRA_EQUIPO2->cellAttributes() ?>>
<span id="el_partidos_GOLES_EXTRA_EQUIPO2">
<span<?= $Page->GOLES_EXTRA_EQUIPO2->viewAttributes() ?>>
<?= $Page->GOLES_EXTRA_EQUIPO2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NOTA_PARTIDO->Visible) { // NOTA_PARTIDO ?>
    <tr id="r_NOTA_PARTIDO"<?= $Page->NOTA_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_NOTA_PARTIDO"><?= $Page->NOTA_PARTIDO->caption() ?></span></td>
        <td data-name="NOTA_PARTIDO"<?= $Page->NOTA_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_NOTA_PARTIDO">
<span<?= $Page->NOTA_PARTIDO->viewAttributes() ?>>
<?= $Page->NOTA_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->RESUMEN_PARTIDO->Visible) { // RESUMEN_PARTIDO ?>
    <tr id="r_RESUMEN_PARTIDO"<?= $Page->RESUMEN_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_RESUMEN_PARTIDO"><?= $Page->RESUMEN_PARTIDO->caption() ?></span></td>
        <td data-name="RESUMEN_PARTIDO"<?= $Page->RESUMEN_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_RESUMEN_PARTIDO">
<span<?= $Page->RESUMEN_PARTIDO->viewAttributes() ?>>
<?= $Page->RESUMEN_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ESTADO_PARTIDO->Visible) { // ESTADO_PARTIDO ?>
    <tr id="r_ESTADO_PARTIDO"<?= $Page->ESTADO_PARTIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_partidos_ESTADO_PARTIDO"><?= $Page->ESTADO_PARTIDO->caption() ?></span></td>
        <td data-name="ESTADO_PARTIDO"<?= $Page->ESTADO_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_ESTADO_PARTIDO">
<span<?= $Page->ESTADO_PARTIDO->viewAttributes() ?>>
<?= $Page->ESTADO_PARTIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
