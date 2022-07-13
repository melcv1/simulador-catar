<?php

namespace PHPMaker2022\project1;

// Page object
$UsuarioDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { usuario: currentTable } });
var currentForm, currentPageID;
var fusuariodelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fusuariodelete = new ew.Form("fusuariodelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fusuariodelete;
    loadjs.done("fusuariodelete");
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
<form name="fusuariodelete" id="fusuariodelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="usuario">
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
<?php if ($Page->ID_USUARIO->Visible) { // ID_USUARIO ?>
        <th class="<?= $Page->ID_USUARIO->headerCellClass() ?>"><span id="elh_usuario_ID_USUARIO" class="usuario_ID_USUARIO"><?= $Page->ID_USUARIO->caption() ?></span></th>
<?php } ?>
<?php if ($Page->USER->Visible) { // USER ?>
        <th class="<?= $Page->USER->headerCellClass() ?>"><span id="elh_usuario_USER" class="usuario_USER"><?= $Page->USER->caption() ?></span></th>
<?php } ?>
<?php if ($Page->CONTRASENA->Visible) { // CONTRASENA ?>
        <th class="<?= $Page->CONTRASENA->headerCellClass() ?>"><span id="elh_usuario_CONTRASENA" class="usuario_CONTRASENA"><?= $Page->CONTRASENA->caption() ?></span></th>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <th class="<?= $Page->nombre->headerCellClass() ?>"><span id="elh_usuario_nombre" class="usuario_nombre"><?= $Page->nombre->caption() ?></span></th>
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
<?php if ($Page->ID_USUARIO->Visible) { // ID_USUARIO ?>
        <td<?= $Page->ID_USUARIO->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_ID_USUARIO" class="el_usuario_ID_USUARIO">
<span<?= $Page->ID_USUARIO->viewAttributes() ?>>
<?= $Page->ID_USUARIO->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->USER->Visible) { // USER ?>
        <td<?= $Page->USER->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_USER" class="el_usuario_USER">
<span<?= $Page->USER->viewAttributes() ?>>
<?= $Page->USER->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->CONTRASENA->Visible) { // CONTRASENA ?>
        <td<?= $Page->CONTRASENA->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_CONTRASENA" class="el_usuario_CONTRASENA">
<span<?= $Page->CONTRASENA->viewAttributes() ?>>
<?= $Page->CONTRASENA->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
        <td<?= $Page->nombre->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_usuario_nombre" class="el_usuario_nombre">
<span<?= $Page->nombre->viewAttributes() ?>>
<?= $Page->nombre->getViewValue() ?></span>
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
