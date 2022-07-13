<?php

namespace PHPMaker2022\project1;

// Page object
$EquipotorneoView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { equipotorneo: currentTable } });
var currentForm, currentPageID;
var fequipotorneoview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fequipotorneoview = new ew.Form("fequipotorneoview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fequipotorneoview;
    loadjs.done("fequipotorneoview");
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
<form name="fequipotorneoview" id="fequipotorneoview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="equipotorneo">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->ID_EQUIPO_TORNEO->Visible) { // ID_EQUIPO_TORNEO ?>
    <tr id="r_ID_EQUIPO_TORNEO"<?= $Page->ID_EQUIPO_TORNEO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_ID_EQUIPO_TORNEO"><?= $Page->ID_EQUIPO_TORNEO->caption() ?></span></td>
        <td data-name="ID_EQUIPO_TORNEO"<?= $Page->ID_EQUIPO_TORNEO->cellAttributes() ?>>
<span id="el_equipotorneo_ID_EQUIPO_TORNEO">
<span<?= $Page->ID_EQUIPO_TORNEO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO_TORNEO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
    <tr id="r_ID_TORNEO"<?= $Page->ID_TORNEO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_ID_TORNEO"><?= $Page->ID_TORNEO->caption() ?></span></td>
        <td data-name="ID_TORNEO"<?= $Page->ID_TORNEO->cellAttributes() ?>>
<span id="el_equipotorneo_ID_TORNEO">
<span<?= $Page->ID_TORNEO->viewAttributes() ?>>
<?= $Page->ID_TORNEO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
    <tr id="r_ID_EQUIPO"<?= $Page->ID_EQUIPO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_ID_EQUIPO"><?= $Page->ID_EQUIPO->caption() ?></span></td>
        <td data-name="ID_EQUIPO"<?= $Page->ID_EQUIPO->cellAttributes() ?>>
<span id="el_equipotorneo_ID_EQUIPO">
<span<?= $Page->ID_EQUIPO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PARTIDOS_JUGADOS->Visible) { // PARTIDOS_JUGADOS ?>
    <tr id="r_PARTIDOS_JUGADOS"<?= $Page->PARTIDOS_JUGADOS->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_PARTIDOS_JUGADOS"><?= $Page->PARTIDOS_JUGADOS->caption() ?></span></td>
        <td data-name="PARTIDOS_JUGADOS"<?= $Page->PARTIDOS_JUGADOS->cellAttributes() ?>>
<span id="el_equipotorneo_PARTIDOS_JUGADOS">
<span<?= $Page->PARTIDOS_JUGADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_JUGADOS->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PARTIDOS_GANADOS->Visible) { // PARTIDOS_GANADOS ?>
    <tr id="r_PARTIDOS_GANADOS"<?= $Page->PARTIDOS_GANADOS->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_PARTIDOS_GANADOS"><?= $Page->PARTIDOS_GANADOS->caption() ?></span></td>
        <td data-name="PARTIDOS_GANADOS"<?= $Page->PARTIDOS_GANADOS->cellAttributes() ?>>
<span id="el_equipotorneo_PARTIDOS_GANADOS">
<span<?= $Page->PARTIDOS_GANADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_GANADOS->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PARTIDOS_EMPATADOS->Visible) { // PARTIDOS_EMPATADOS ?>
    <tr id="r_PARTIDOS_EMPATADOS"<?= $Page->PARTIDOS_EMPATADOS->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_PARTIDOS_EMPATADOS"><?= $Page->PARTIDOS_EMPATADOS->caption() ?></span></td>
        <td data-name="PARTIDOS_EMPATADOS"<?= $Page->PARTIDOS_EMPATADOS->cellAttributes() ?>>
<span id="el_equipotorneo_PARTIDOS_EMPATADOS">
<span<?= $Page->PARTIDOS_EMPATADOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_EMPATADOS->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PARTIDOS_PERDIDOS->Visible) { // PARTIDOS_PERDIDOS ?>
    <tr id="r_PARTIDOS_PERDIDOS"<?= $Page->PARTIDOS_PERDIDOS->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_PARTIDOS_PERDIDOS"><?= $Page->PARTIDOS_PERDIDOS->caption() ?></span></td>
        <td data-name="PARTIDOS_PERDIDOS"<?= $Page->PARTIDOS_PERDIDOS->cellAttributes() ?>>
<span id="el_equipotorneo_PARTIDOS_PERDIDOS">
<span<?= $Page->PARTIDOS_PERDIDOS->viewAttributes() ?>>
<?= $Page->PARTIDOS_PERDIDOS->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GF->Visible) { // GF ?>
    <tr id="r_GF"<?= $Page->GF->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_GF"><?= $Page->GF->caption() ?></span></td>
        <td data-name="GF"<?= $Page->GF->cellAttributes() ?>>
<span id="el_equipotorneo_GF">
<span<?= $Page->GF->viewAttributes() ?>>
<?= $Page->GF->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GC->Visible) { // GC ?>
    <tr id="r_GC"<?= $Page->GC->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_GC"><?= $Page->GC->caption() ?></span></td>
        <td data-name="GC"<?= $Page->GC->cellAttributes() ?>>
<span id="el_equipotorneo_GC">
<span<?= $Page->GC->viewAttributes() ?>>
<?= $Page->GC->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GD->Visible) { // GD ?>
    <tr id="r_GD"<?= $Page->GD->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_GD"><?= $Page->GD->caption() ?></span></td>
        <td data-name="GD"<?= $Page->GD->cellAttributes() ?>>
<span id="el_equipotorneo_GD">
<span<?= $Page->GD->viewAttributes() ?>>
<?= $Page->GD->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->GRUPO->Visible) { // GRUPO ?>
    <tr id="r_GRUPO"<?= $Page->GRUPO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_GRUPO"><?= $Page->GRUPO->caption() ?></span></td>
        <td data-name="GRUPO"<?= $Page->GRUPO->cellAttributes() ?>>
<span id="el_equipotorneo_GRUPO">
<span<?= $Page->GRUPO->viewAttributes() ?>>
<?= $Page->GRUPO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->POSICION_EQUIPO_TORENO->Visible) { // POSICION_EQUIPO_TORENO ?>
    <tr id="r_POSICION_EQUIPO_TORENO"<?= $Page->POSICION_EQUIPO_TORENO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipotorneo_POSICION_EQUIPO_TORENO"><?= $Page->POSICION_EQUIPO_TORENO->caption() ?></span></td>
        <td data-name="POSICION_EQUIPO_TORENO"<?= $Page->POSICION_EQUIPO_TORENO->cellAttributes() ?>>
<span id="el_equipotorneo_POSICION_EQUIPO_TORENO">
<span<?= $Page->POSICION_EQUIPO_TORENO->viewAttributes() ?>>
<?= $Page->POSICION_EQUIPO_TORENO->getViewValue() ?></span>
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
