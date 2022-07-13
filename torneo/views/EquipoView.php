<?php

namespace PHPMaker2022\project1;

// Page object
$EquipoView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { equipo: currentTable } });
var currentForm, currentPageID;
var fequipoview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fequipoview = new ew.Form("fequipoview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fequipoview;
    loadjs.done("fequipoview");
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
<form name="fequipoview" id="fequipoview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="equipo">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->ID_EQUIPO->Visible) { // ID_EQUIPO ?>
    <tr id="r_ID_EQUIPO"<?= $Page->ID_EQUIPO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipo_ID_EQUIPO"><?= $Page->ID_EQUIPO->caption() ?></span></td>
        <td data-name="ID_EQUIPO"<?= $Page->ID_EQUIPO->cellAttributes() ?>>
<span id="el_equipo_ID_EQUIPO">
<span<?= $Page->ID_EQUIPO->viewAttributes() ?>>
<?= $Page->ID_EQUIPO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NOM_EQUIPO_CORTO->Visible) { // NOM_EQUIPO_CORTO ?>
    <tr id="r_NOM_EQUIPO_CORTO"<?= $Page->NOM_EQUIPO_CORTO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipo_NOM_EQUIPO_CORTO"><?= $Page->NOM_EQUIPO_CORTO->caption() ?></span></td>
        <td data-name="NOM_EQUIPO_CORTO"<?= $Page->NOM_EQUIPO_CORTO->cellAttributes() ?>>
<span id="el_equipo_NOM_EQUIPO_CORTO">
<span<?= $Page->NOM_EQUIPO_CORTO->viewAttributes() ?>>
<?= $Page->NOM_EQUIPO_CORTO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NOM_EQUIPO_LARGO->Visible) { // NOM_EQUIPO_LARGO ?>
    <tr id="r_NOM_EQUIPO_LARGO"<?= $Page->NOM_EQUIPO_LARGO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipo_NOM_EQUIPO_LARGO"><?= $Page->NOM_EQUIPO_LARGO->caption() ?></span></td>
        <td data-name="NOM_EQUIPO_LARGO"<?= $Page->NOM_EQUIPO_LARGO->cellAttributes() ?>>
<span id="el_equipo_NOM_EQUIPO_LARGO">
<span<?= $Page->NOM_EQUIPO_LARGO->viewAttributes() ?>>
<?= $Page->NOM_EQUIPO_LARGO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->PAIS_EQUIPO->Visible) { // PAIS_EQUIPO ?>
    <tr id="r_PAIS_EQUIPO"<?= $Page->PAIS_EQUIPO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipo_PAIS_EQUIPO"><?= $Page->PAIS_EQUIPO->caption() ?></span></td>
        <td data-name="PAIS_EQUIPO"<?= $Page->PAIS_EQUIPO->cellAttributes() ?>>
<span id="el_equipo_PAIS_EQUIPO">
<span<?= $Page->PAIS_EQUIPO->viewAttributes() ?>>
<?= $Page->PAIS_EQUIPO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->REGION_EQUIPO->Visible) { // REGION_EQUIPO ?>
    <tr id="r_REGION_EQUIPO"<?= $Page->REGION_EQUIPO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipo_REGION_EQUIPO"><?= $Page->REGION_EQUIPO->caption() ?></span></td>
        <td data-name="REGION_EQUIPO"<?= $Page->REGION_EQUIPO->cellAttributes() ?>>
<span id="el_equipo_REGION_EQUIPO">
<span<?= $Page->REGION_EQUIPO->viewAttributes() ?>>
<?= $Page->REGION_EQUIPO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->DETALLE_EQUIPO->Visible) { // DETALLE_EQUIPO ?>
    <tr id="r_DETALLE_EQUIPO"<?= $Page->DETALLE_EQUIPO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipo_DETALLE_EQUIPO"><?= $Page->DETALLE_EQUIPO->caption() ?></span></td>
        <td data-name="DETALLE_EQUIPO"<?= $Page->DETALLE_EQUIPO->cellAttributes() ?>>
<span id="el_equipo_DETALLE_EQUIPO">
<span<?= $Page->DETALLE_EQUIPO->viewAttributes() ?>>
<?= $Page->DETALLE_EQUIPO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ESCUDO_EQUIPO->Visible) { // ESCUDO_EQUIPO ?>
    <tr id="r_ESCUDO_EQUIPO"<?= $Page->ESCUDO_EQUIPO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipo_ESCUDO_EQUIPO"><?= $Page->ESCUDO_EQUIPO->caption() ?></span></td>
        <td data-name="ESCUDO_EQUIPO"<?= $Page->ESCUDO_EQUIPO->cellAttributes() ?>>
<span id="el_equipo_ESCUDO_EQUIPO">
<span>
<?= GetFileViewTag($Page->ESCUDO_EQUIPO, $Page->ESCUDO_EQUIPO->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NOM_ESTADIO->Visible) { // NOM_ESTADIO ?>
    <tr id="r_NOM_ESTADIO"<?= $Page->NOM_ESTADIO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_equipo_NOM_ESTADIO"><?= $Page->NOM_ESTADIO->caption() ?></span></td>
        <td data-name="NOM_ESTADIO"<?= $Page->NOM_ESTADIO->cellAttributes() ?>>
<span id="el_equipo_NOM_ESTADIO">
<span<?= $Page->NOM_ESTADIO->viewAttributes() ?>>
<?= $Page->NOM_ESTADIO->getViewValue() ?></span>
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
