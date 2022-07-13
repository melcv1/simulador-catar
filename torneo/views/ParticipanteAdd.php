<?php

namespace PHPMaker2022\project1;

// Page object
$ParticipanteAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { participante: currentTable } });
var currentForm, currentPageID;
var fparticipanteadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fparticipanteadd = new ew.Form("fparticipanteadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fparticipanteadd;

    // Add fields
    var fields = currentTable.fields;
    fparticipanteadd.addFields([
        ["NOMBRE", [fields.NOMBRE.visible && fields.NOMBRE.required ? ew.Validators.required(fields.NOMBRE.caption) : null], fields.NOMBRE.isInvalid],
        ["APELLIDO", [fields.APELLIDO.visible && fields.APELLIDO.required ? ew.Validators.required(fields.APELLIDO.caption) : null], fields.APELLIDO.isInvalid],
        ["FECHA_NACIMIENTO", [fields.FECHA_NACIMIENTO.visible && fields.FECHA_NACIMIENTO.required ? ew.Validators.required(fields.FECHA_NACIMIENTO.caption) : null], fields.FECHA_NACIMIENTO.isInvalid],
        ["CEDULA", [fields.CEDULA.visible && fields.CEDULA.required ? ew.Validators.required(fields.CEDULA.caption) : null], fields.CEDULA.isInvalid],
        ["_EMAIL", [fields._EMAIL.visible && fields._EMAIL.required ? ew.Validators.required(fields._EMAIL.caption) : null], fields._EMAIL.isInvalid],
        ["TELEFONO", [fields.TELEFONO.visible && fields.TELEFONO.required ? ew.Validators.required(fields.TELEFONO.caption) : null], fields.TELEFONO.isInvalid],
        ["CREACION", [fields.CREACION.visible && fields.CREACION.required ? ew.Validators.required(fields.CREACION.caption) : null], fields.CREACION.isInvalid],
        ["ACTUALIZACION", [fields.ACTUALIZACION.visible && fields.ACTUALIZACION.required ? ew.Validators.required(fields.ACTUALIZACION.caption) : null], fields.ACTUALIZACION.isInvalid]
    ]);

    // Form_CustomValidate
    fparticipanteadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fparticipanteadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fparticipanteadd");
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
<form name="fparticipanteadd" id="fparticipanteadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="participante">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->NOMBRE->Visible) { // NOMBRE ?>
    <div id="r_NOMBRE"<?= $Page->NOMBRE->rowAttributes() ?>>
        <label id="elh_participante_NOMBRE" for="x_NOMBRE" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NOMBRE->caption() ?><?= $Page->NOMBRE->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NOMBRE->cellAttributes() ?>>
<span id="el_participante_NOMBRE">
<textarea data-table="participante" data-field="x_NOMBRE" name="x_NOMBRE" id="x_NOMBRE" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->NOMBRE->getPlaceHolder()) ?>"<?= $Page->NOMBRE->editAttributes() ?> aria-describedby="x_NOMBRE_help"><?= $Page->NOMBRE->EditValue ?></textarea>
<?= $Page->NOMBRE->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NOMBRE->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->APELLIDO->Visible) { // APELLIDO ?>
    <div id="r_APELLIDO"<?= $Page->APELLIDO->rowAttributes() ?>>
        <label id="elh_participante_APELLIDO" for="x_APELLIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->APELLIDO->caption() ?><?= $Page->APELLIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->APELLIDO->cellAttributes() ?>>
<span id="el_participante_APELLIDO">
<textarea data-table="participante" data-field="x_APELLIDO" name="x_APELLIDO" id="x_APELLIDO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->APELLIDO->getPlaceHolder()) ?>"<?= $Page->APELLIDO->editAttributes() ?> aria-describedby="x_APELLIDO_help"><?= $Page->APELLIDO->EditValue ?></textarea>
<?= $Page->APELLIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->APELLIDO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->FECHA_NACIMIENTO->Visible) { // FECHA_NACIMIENTO ?>
    <div id="r_FECHA_NACIMIENTO"<?= $Page->FECHA_NACIMIENTO->rowAttributes() ?>>
        <label id="elh_participante_FECHA_NACIMIENTO" for="x_FECHA_NACIMIENTO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->FECHA_NACIMIENTO->caption() ?><?= $Page->FECHA_NACIMIENTO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->FECHA_NACIMIENTO->cellAttributes() ?>>
<span id="el_participante_FECHA_NACIMIENTO">
<textarea data-table="participante" data-field="x_FECHA_NACIMIENTO" name="x_FECHA_NACIMIENTO" id="x_FECHA_NACIMIENTO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->FECHA_NACIMIENTO->getPlaceHolder()) ?>"<?= $Page->FECHA_NACIMIENTO->editAttributes() ?> aria-describedby="x_FECHA_NACIMIENTO_help"><?= $Page->FECHA_NACIMIENTO->EditValue ?></textarea>
<?= $Page->FECHA_NACIMIENTO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->FECHA_NACIMIENTO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CEDULA->Visible) { // CEDULA ?>
    <div id="r_CEDULA"<?= $Page->CEDULA->rowAttributes() ?>>
        <label id="elh_participante_CEDULA" for="x_CEDULA" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CEDULA->caption() ?><?= $Page->CEDULA->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CEDULA->cellAttributes() ?>>
<span id="el_participante_CEDULA">
<input type="<?= $Page->CEDULA->getInputTextType() ?>" name="x_CEDULA" id="x_CEDULA" data-table="participante" data-field="x_CEDULA" value="<?= $Page->CEDULA->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->CEDULA->getPlaceHolder()) ?>"<?= $Page->CEDULA->editAttributes() ?> aria-describedby="x_CEDULA_help">
<?= $Page->CEDULA->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CEDULA->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_EMAIL->Visible) { // EMAIL ?>
    <div id="r__EMAIL"<?= $Page->_EMAIL->rowAttributes() ?>>
        <label id="elh_participante__EMAIL" for="x__EMAIL" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_EMAIL->caption() ?><?= $Page->_EMAIL->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_EMAIL->cellAttributes() ?>>
<span id="el_participante__EMAIL">
<textarea data-table="participante" data-field="x__EMAIL" name="x__EMAIL" id="x__EMAIL" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->_EMAIL->getPlaceHolder()) ?>"<?= $Page->_EMAIL->editAttributes() ?> aria-describedby="x__EMAIL_help"><?= $Page->_EMAIL->EditValue ?></textarea>
<?= $Page->_EMAIL->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_EMAIL->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->TELEFONO->Visible) { // TELEFONO ?>
    <div id="r_TELEFONO"<?= $Page->TELEFONO->rowAttributes() ?>>
        <label id="elh_participante_TELEFONO" for="x_TELEFONO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->TELEFONO->caption() ?><?= $Page->TELEFONO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->TELEFONO->cellAttributes() ?>>
<span id="el_participante_TELEFONO">
<input type="<?= $Page->TELEFONO->getInputTextType() ?>" name="x_TELEFONO" id="x_TELEFONO" data-table="participante" data-field="x_TELEFONO" value="<?= $Page->TELEFONO->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->TELEFONO->getPlaceHolder()) ?>"<?= $Page->TELEFONO->editAttributes() ?> aria-describedby="x_TELEFONO_help">
<?= $Page->TELEFONO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->TELEFONO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CREACION->Visible) { // CREACION ?>
    <div id="r_CREACION"<?= $Page->CREACION->rowAttributes() ?>>
        <label id="elh_participante_CREACION" for="x_CREACION" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CREACION->caption() ?><?= $Page->CREACION->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CREACION->cellAttributes() ?>>
<span id="el_participante_CREACION">
<textarea data-table="participante" data-field="x_CREACION" name="x_CREACION" id="x_CREACION" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->CREACION->getPlaceHolder()) ?>"<?= $Page->CREACION->editAttributes() ?> aria-describedby="x_CREACION_help"><?= $Page->CREACION->EditValue ?></textarea>
<?= $Page->CREACION->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CREACION->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ACTUALIZACION->Visible) { // ACTUALIZACION ?>
    <div id="r_ACTUALIZACION"<?= $Page->ACTUALIZACION->rowAttributes() ?>>
        <label id="elh_participante_ACTUALIZACION" for="x_ACTUALIZACION" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ACTUALIZACION->caption() ?><?= $Page->ACTUALIZACION->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ACTUALIZACION->cellAttributes() ?>>
<span id="el_participante_ACTUALIZACION">
<textarea data-table="participante" data-field="x_ACTUALIZACION" name="x_ACTUALIZACION" id="x_ACTUALIZACION" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->ACTUALIZACION->getPlaceHolder()) ?>"<?= $Page->ACTUALIZACION->editAttributes() ?> aria-describedby="x_ACTUALIZACION_help"><?= $Page->ACTUALIZACION->EditValue ?></textarea>
<?= $Page->ACTUALIZACION->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ACTUALIZACION->getErrorMessage() ?></div>
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
    ew.addEventHandlers("participante");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
