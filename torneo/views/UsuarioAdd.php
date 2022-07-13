<?php

namespace PHPMaker2022\project1;

// Page object
$UsuarioAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { usuario: currentTable } });
var currentForm, currentPageID;
var fusuarioadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fusuarioadd = new ew.Form("fusuarioadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fusuarioadd;

    // Add fields
    var fields = currentTable.fields;
    fusuarioadd.addFields([
        ["USER", [fields.USER.visible && fields.USER.required ? ew.Validators.required(fields.USER.caption) : null], fields.USER.isInvalid],
        ["CONTRASENA", [fields.CONTRASENA.visible && fields.CONTRASENA.required ? ew.Validators.required(fields.CONTRASENA.caption) : null], fields.CONTRASENA.isInvalid],
        ["nombre", [fields.nombre.visible && fields.nombre.required ? ew.Validators.required(fields.nombre.caption) : null], fields.nombre.isInvalid]
    ]);

    // Form_CustomValidate
    fusuarioadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fusuarioadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fusuarioadd");
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
<form name="fusuarioadd" id="fusuarioadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="usuario">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->USER->Visible) { // USER ?>
    <div id="r_USER"<?= $Page->USER->rowAttributes() ?>>
        <label id="elh_usuario_USER" for="x_USER" class="<?= $Page->LeftColumnClass ?>"><?= $Page->USER->caption() ?><?= $Page->USER->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->USER->cellAttributes() ?>>
<span id="el_usuario_USER">
<textarea data-table="usuario" data-field="x_USER" name="x_USER" id="x_USER" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->USER->getPlaceHolder()) ?>"<?= $Page->USER->editAttributes() ?> aria-describedby="x_USER_help"><?= $Page->USER->EditValue ?></textarea>
<?= $Page->USER->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->USER->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CONTRASENA->Visible) { // CONTRASENA ?>
    <div id="r_CONTRASENA"<?= $Page->CONTRASENA->rowAttributes() ?>>
        <label id="elh_usuario_CONTRASENA" for="x_CONTRASENA" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CONTRASENA->caption() ?><?= $Page->CONTRASENA->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CONTRASENA->cellAttributes() ?>>
<span id="el_usuario_CONTRASENA">
<textarea data-table="usuario" data-field="x_CONTRASENA" name="x_CONTRASENA" id="x_CONTRASENA" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->CONTRASENA->getPlaceHolder()) ?>"<?= $Page->CONTRASENA->editAttributes() ?> aria-describedby="x_CONTRASENA_help"><?= $Page->CONTRASENA->EditValue ?></textarea>
<?= $Page->CONTRASENA->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CONTRASENA->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->nombre->Visible) { // nombre ?>
    <div id="r_nombre"<?= $Page->nombre->rowAttributes() ?>>
        <label id="elh_usuario_nombre" for="x_nombre" class="<?= $Page->LeftColumnClass ?>"><?= $Page->nombre->caption() ?><?= $Page->nombre->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->nombre->cellAttributes() ?>>
<span id="el_usuario_nombre">
<textarea data-table="usuario" data-field="x_nombre" name="x_nombre" id="x_nombre" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->nombre->getPlaceHolder()) ?>"<?= $Page->nombre->editAttributes() ?> aria-describedby="x_nombre_help"><?= $Page->nombre->EditValue ?></textarea>
<?= $Page->nombre->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->nombre->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="row"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .row -->
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("usuario");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
