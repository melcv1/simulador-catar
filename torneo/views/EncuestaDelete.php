<?php

namespace PHPMaker2022\project1;

// Page object
$EncuestaDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { encuesta: currentTable } });
var currentForm, currentPageID;
var fencuestadelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fencuestadelete = new ew.Form("fencuestadelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fencuestadelete;
    loadjs.done("fencuestadelete");
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
<form name="fencuestadelete" id="fencuestadelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="encuesta">
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
<?php if ($Page->ID_ENCUESTA->Visible) { // ID_ENCUESTA ?>
        <th class="<?= $Page->ID_ENCUESTA->headerCellClass() ?>"><span id="elh_encuesta_ID_ENCUESTA" class="encuesta_ID_ENCUESTA"><?= $Page->ID_ENCUESTA->caption() ?></span></th>
<?php } ?>
<?php if ($Page->ID_PARTICIPANTE->Visible) { // ID_PARTICIPANTE ?>
        <th class="<?= $Page->ID_PARTICIPANTE->headerCellClass() ?>"><span id="elh_encuesta_ID_PARTICIPANTE" class="encuesta_ID_PARTICIPANTE"><?= $Page->ID_PARTICIPANTE->caption() ?></span></th>
<?php } ?>
<?php if ($Page->GRUPO->Visible) { // GRUPO ?>
        <th class="<?= $Page->GRUPO->headerCellClass() ?>"><span id="elh_encuesta_GRUPO" class="encuesta_GRUPO"><?= $Page->GRUPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->EQUIPO->Visible) { // EQUIPO ?>
        <th class="<?= $Page->EQUIPO->headerCellClass() ?>"><span id="elh_encuesta_EQUIPO" class="encuesta_EQUIPO"><?= $Page->EQUIPO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->POSICION->Visible) { // POSICION ?>
        <th class="<?= $Page->POSICION->headerCellClass() ?>"><span id="elh_encuesta_POSICION" class="encuesta_POSICION"><?= $Page->POSICION->caption() ?></span></th>
<?php } ?>
<?php if ($Page->NUMERACION->Visible) { // NUMERACION ?>
        <th class="<?= $Page->NUMERACION->headerCellClass() ?>"><span id="elh_encuesta_NUMERACION" class="encuesta_NUMERACION"><?= $Page->NUMERACION->caption() ?></span></th>
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
<?php if ($Page->ID_ENCUESTA->Visible) { // ID_ENCUESTA ?>
        <td<?= $Page->ID_ENCUESTA->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_ID_ENCUESTA" class="el_encuesta_ID_ENCUESTA">
<span<?= $Page->ID_ENCUESTA->viewAttributes() ?>>
<?= $Page->ID_ENCUESTA->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->ID_PARTICIPANTE->Visible) { // ID_PARTICIPANTE ?>
        <td<?= $Page->ID_PARTICIPANTE->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_ID_PARTICIPANTE" class="el_encuesta_ID_PARTICIPANTE">
<span<?= $Page->ID_PARTICIPANTE->viewAttributes() ?>>
<?= $Page->ID_PARTICIPANTE->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->GRUPO->Visible) { // GRUPO ?>
        <td<?= $Page->GRUPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_GRUPO" class="el_encuesta_GRUPO">
<span<?= $Page->GRUPO->viewAttributes() ?>>
<?= $Page->GRUPO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->EQUIPO->Visible) { // EQUIPO ?>
        <td<?= $Page->EQUIPO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_EQUIPO" class="el_encuesta_EQUIPO">
<span<?= $Page->EQUIPO->viewAttributes() ?>>
<?= $Page->EQUIPO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->POSICION->Visible) { // POSICION ?>
        <td<?= $Page->POSICION->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_POSICION" class="el_encuesta_POSICION">
<span<?= $Page->POSICION->viewAttributes() ?>>
<?= $Page->POSICION->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->NUMERACION->Visible) { // NUMERACION ?>
        <td<?= $Page->NUMERACION->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_encuesta_NUMERACION" class="el_encuesta_NUMERACION">
<span<?= $Page->NUMERACION->viewAttributes() ?>>
<?= $Page->NUMERACION->getViewValue() ?></span>
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
