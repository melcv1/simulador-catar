<?php

namespace PHPMaker2022\project1;

// Page object
$Register = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { usuario: currentTable } });
var currentForm, currentPageID;
var fregister;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fregister = new ew.Form("fregister", "register");
    currentPageID = ew.PAGE_ID = "register";
    currentForm = fregister;

    // Add fields
    var fields = currentTable.fields;
    fregister.addFields([
        ["USER", [fields.USER.visible && fields.USER.required ? ew.Validators.required(fields.USER.caption) : null], fields.USER.isInvalid],
        ["c_CONTRASENA", [ew.Validators.required(ew.language.phrase("ConfirmPassword")), ew.Validators.mismatchPassword], fields.CONTRASENA.isInvalid],
        ["CONTRASENA", [fields.CONTRASENA.visible && fields.CONTRASENA.required ? ew.Validators.required(fields.CONTRASENA.caption) : null], fields.CONTRASENA.isInvalid]
    ]);

    // Form_CustomValidate
    fregister.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fregister.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fregister");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fregister" id="fregister" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="t" value="usuario">
<?php if ($Page->isConfirm()) { // Confirm page ?>
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="confirm" id="confirm" value="confirm">
<?php } else { ?>
<input type="hidden" name="action" id="action" value="confirm">
<?php } ?>
<div class="ew-register-div"><!-- page* -->
<?php if ($Page->USER->Visible) { // USER ?>
    <div id="r_USER"<?= $Page->USER->rowAttributes() ?>>
        <label id="elh_usuario_USER" for="x_USER" class="<?= $Page->LeftColumnClass ?>"><?= $Page->USER->caption() ?><?= $Page->USER->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->USER->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_usuario_USER">
<textarea data-table="usuario" data-field="x_USER" name="x_USER" id="x_USER" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->USER->getPlaceHolder()) ?>"<?= $Page->USER->editAttributes() ?> aria-describedby="x_USER_help"><?= $Page->USER->EditValue ?></textarea>
<?= $Page->USER->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->USER->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_usuario_USER">
<span<?= $Page->USER->viewAttributes() ?>>
<?= $Page->USER->ViewValue ?></span>
</span>
<input type="hidden" data-table="usuario" data-field="x_USER" data-hidden="1" name="x_USER" id="x_USER" value="<?= HtmlEncode($Page->USER->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CONTRASENA->Visible) { // CONTRASENA ?>
    <div id="r_CONTRASENA"<?= $Page->CONTRASENA->rowAttributes() ?>>
        <label id="elh_usuario_CONTRASENA" for="x_CONTRASENA" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CONTRASENA->caption() ?><?= $Page->CONTRASENA->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CONTRASENA->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_usuario_CONTRASENA">
<textarea data-table="usuario" data-field="x_CONTRASENA" name="x_CONTRASENA" id="x_CONTRASENA" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->CONTRASENA->getPlaceHolder()) ?>"<?= $Page->CONTRASENA->editAttributes() ?> aria-describedby="x_CONTRASENA_help"><?= $Page->CONTRASENA->EditValue ?></textarea>
<?= $Page->CONTRASENA->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CONTRASENA->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_usuario_CONTRASENA">
<span<?= $Page->CONTRASENA->viewAttributes() ?>>
<?= $Page->CONTRASENA->ViewValue ?></span>
</span>
<input type="hidden" data-table="usuario" data-field="x_CONTRASENA" data-hidden="1" name="x_CONTRASENA" id="x_CONTRASENA" value="<?= HtmlEncode($Page->CONTRASENA->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CONTRASENA->Visible) { // CONTRASENA ?>
    <div id="r_c_CONTRASENA" class="row">
        <label id="elh_c_usuario_CONTRASENA" for="c_CONTRASENA" class="<?= $Page->LeftColumnClass ?>"><?= $Language->phrase("Confirm") ?> <?= $Page->CONTRASENA->caption() ?><?= $Page->CONTRASENA->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CONTRASENA->cellAttributes() ?>>
<?php if (!$Page->isConfirm()) { ?>
<span id="el_c_usuario_CONTRASENA">
<textarea data-table="usuario" data-field="x_CONTRASENA" name="c_CONTRASENA" id="c_CONTRASENA" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->CONTRASENA->getPlaceHolder()) ?>"<?= $Page->CONTRASENA->editAttributes() ?> aria-describedby="x_CONTRASENA_help"><?= $Page->CONTRASENA->EditValue ?></textarea>
<?= $Page->CONTRASENA->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CONTRASENA->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el_c_usuario_CONTRASENA">
<span<?= $Page->CONTRASENA->viewAttributes() ?>>
<?= $Page->CONTRASENA->ViewValue ?></span>
</span>
<input type="hidden" data-table="usuario" data-field="x_CONTRASENA" data-hidden="1" name="c_CONTRASENA" id="c_CONTRASENA" value="<?= HtmlEncode($Page->CONTRASENA->FormValue) ?>">
<?php } ?>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="row"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<?php if (!$Page->isConfirm()) { // Confirm page ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit" data-ew-action="set-action" data-value="confirm"><?= $Language->phrase("RegisterBtn") ?></button>
<?php } else { ?>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("ConfirmBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="submit" data-ew-action="set-action" data-value="cancel"><?= $Language->phrase("CancelBtn") ?></button>
<?php } ?>
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
    // Write your startup script here, no need to add script tags.
});
</script>
