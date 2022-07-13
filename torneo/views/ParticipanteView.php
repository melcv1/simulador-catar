<?php

namespace PHPMaker2022\project1;

// Page object
$ParticipanteView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { participante: currentTable } });
var currentForm, currentPageID;
var fparticipanteview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fparticipanteview = new ew.Form("fparticipanteview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fparticipanteview;
    loadjs.done("fparticipanteview");
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
<form name="fparticipanteview" id="fparticipanteview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="participante">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-bordered table-hover table-sm ew-view-table">
<?php if ($Page->ID_PARTICIPANTE->Visible) { // ID_PARTICIPANTE ?>
    <tr id="r_ID_PARTICIPANTE"<?= $Page->ID_PARTICIPANTE->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante_ID_PARTICIPANTE"><?= $Page->ID_PARTICIPANTE->caption() ?></span></td>
        <td data-name="ID_PARTICIPANTE"<?= $Page->ID_PARTICIPANTE->cellAttributes() ?>>
<span id="el_participante_ID_PARTICIPANTE">
<span<?= $Page->ID_PARTICIPANTE->viewAttributes() ?>>
<?= $Page->ID_PARTICIPANTE->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->NOMBRE->Visible) { // NOMBRE ?>
    <tr id="r_NOMBRE"<?= $Page->NOMBRE->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante_NOMBRE"><?= $Page->NOMBRE->caption() ?></span></td>
        <td data-name="NOMBRE"<?= $Page->NOMBRE->cellAttributes() ?>>
<span id="el_participante_NOMBRE">
<span<?= $Page->NOMBRE->viewAttributes() ?>>
<?= $Page->NOMBRE->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->APELLIDO->Visible) { // APELLIDO ?>
    <tr id="r_APELLIDO"<?= $Page->APELLIDO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante_APELLIDO"><?= $Page->APELLIDO->caption() ?></span></td>
        <td data-name="APELLIDO"<?= $Page->APELLIDO->cellAttributes() ?>>
<span id="el_participante_APELLIDO">
<span<?= $Page->APELLIDO->viewAttributes() ?>>
<?= $Page->APELLIDO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->FECHA_NACIMIENTO->Visible) { // FECHA_NACIMIENTO ?>
    <tr id="r_FECHA_NACIMIENTO"<?= $Page->FECHA_NACIMIENTO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante_FECHA_NACIMIENTO"><?= $Page->FECHA_NACIMIENTO->caption() ?></span></td>
        <td data-name="FECHA_NACIMIENTO"<?= $Page->FECHA_NACIMIENTO->cellAttributes() ?>>
<span id="el_participante_FECHA_NACIMIENTO">
<span<?= $Page->FECHA_NACIMIENTO->viewAttributes() ?>>
<?= $Page->FECHA_NACIMIENTO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->CEDULA->Visible) { // CEDULA ?>
    <tr id="r_CEDULA"<?= $Page->CEDULA->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante_CEDULA"><?= $Page->CEDULA->caption() ?></span></td>
        <td data-name="CEDULA"<?= $Page->CEDULA->cellAttributes() ?>>
<span id="el_participante_CEDULA">
<span<?= $Page->CEDULA->viewAttributes() ?>>
<?= $Page->CEDULA->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->_EMAIL->Visible) { // EMAIL ?>
    <tr id="r__EMAIL"<?= $Page->_EMAIL->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante__EMAIL"><?= $Page->_EMAIL->caption() ?></span></td>
        <td data-name="_EMAIL"<?= $Page->_EMAIL->cellAttributes() ?>>
<span id="el_participante__EMAIL">
<span<?= $Page->_EMAIL->viewAttributes() ?>>
<?= $Page->_EMAIL->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->TELEFONO->Visible) { // TELEFONO ?>
    <tr id="r_TELEFONO"<?= $Page->TELEFONO->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante_TELEFONO"><?= $Page->TELEFONO->caption() ?></span></td>
        <td data-name="TELEFONO"<?= $Page->TELEFONO->cellAttributes() ?>>
<span id="el_participante_TELEFONO">
<span<?= $Page->TELEFONO->viewAttributes() ?>>
<?= $Page->TELEFONO->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->CREACION->Visible) { // CREACION ?>
    <tr id="r_CREACION"<?= $Page->CREACION->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante_CREACION"><?= $Page->CREACION->caption() ?></span></td>
        <td data-name="CREACION"<?= $Page->CREACION->cellAttributes() ?>>
<span id="el_participante_CREACION">
<span<?= $Page->CREACION->viewAttributes() ?>>
<?= $Page->CREACION->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->ACTUALIZACION->Visible) { // ACTUALIZACION ?>
    <tr id="r_ACTUALIZACION"<?= $Page->ACTUALIZACION->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_participante_ACTUALIZACION"><?= $Page->ACTUALIZACION->caption() ?></span></td>
        <td data-name="ACTUALIZACION"<?= $Page->ACTUALIZACION->cellAttributes() ?>>
<span id="el_participante_ACTUALIZACION">
<span<?= $Page->ACTUALIZACION->viewAttributes() ?>>
<?= $Page->ACTUALIZACION->getViewValue() ?></span>
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
