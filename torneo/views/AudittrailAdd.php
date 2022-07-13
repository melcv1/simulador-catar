<?php

namespace PHPMaker2022\project1;

// Page object
$AudittrailAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { audittrail: currentTable } });
var currentForm, currentPageID;
var faudittrailadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    faudittrailadd = new ew.Form("faudittrailadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = faudittrailadd;

    // Add fields
    var fields = currentTable.fields;
    faudittrailadd.addFields([
        ["datetime", [fields.datetime.visible && fields.datetime.required ? ew.Validators.required(fields.datetime.caption) : null, ew.Validators.datetime(fields.datetime.clientFormatPattern)], fields.datetime.isInvalid],
        ["script", [fields.script.visible && fields.script.required ? ew.Validators.required(fields.script.caption) : null], fields.script.isInvalid],
        ["user", [fields.user.visible && fields.user.required ? ew.Validators.required(fields.user.caption) : null], fields.user.isInvalid],
        ["_action", [fields._action.visible && fields._action.required ? ew.Validators.required(fields._action.caption) : null], fields._action.isInvalid],
        ["_table", [fields._table.visible && fields._table.required ? ew.Validators.required(fields._table.caption) : null], fields._table.isInvalid],
        ["field", [fields.field.visible && fields.field.required ? ew.Validators.required(fields.field.caption) : null], fields.field.isInvalid],
        ["keyvalue", [fields.keyvalue.visible && fields.keyvalue.required ? ew.Validators.required(fields.keyvalue.caption) : null], fields.keyvalue.isInvalid],
        ["oldvalue", [fields.oldvalue.visible && fields.oldvalue.required ? ew.Validators.required(fields.oldvalue.caption) : null], fields.oldvalue.isInvalid],
        ["newvalue", [fields.newvalue.visible && fields.newvalue.required ? ew.Validators.required(fields.newvalue.caption) : null], fields.newvalue.isInvalid]
    ]);

    // Form_CustomValidate
    faudittrailadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    faudittrailadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("faudittrailadd");
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
<form name="faudittrailadd" id="faudittrailadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="audittrail">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->datetime->Visible) { // datetime ?>
    <div id="r_datetime"<?= $Page->datetime->rowAttributes() ?>>
        <label id="elh_audittrail_datetime" for="x_datetime" class="<?= $Page->LeftColumnClass ?>"><?= $Page->datetime->caption() ?><?= $Page->datetime->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->datetime->cellAttributes() ?>>
<span id="el_audittrail_datetime">
<input type="<?= $Page->datetime->getInputTextType() ?>" name="x_datetime" id="x_datetime" data-table="audittrail" data-field="x_datetime" value="<?= $Page->datetime->EditValue ?>" placeholder="<?= HtmlEncode($Page->datetime->getPlaceHolder()) ?>"<?= $Page->datetime->editAttributes() ?> aria-describedby="x_datetime_help">
<?= $Page->datetime->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->datetime->getErrorMessage() ?></div>
<?php if (!$Page->datetime->ReadOnly && !$Page->datetime->Disabled && !isset($Page->datetime->EditAttrs["readonly"]) && !isset($Page->datetime->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["faudittrailadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem()
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                },
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i),
                    useTwentyfourHour: !!format.match(/H/)
                }
            },
            meta: {
                format
            }
        };
    ew.createDateTimePicker("faudittrailadd", "x_datetime", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->script->Visible) { // script ?>
    <div id="r_script"<?= $Page->script->rowAttributes() ?>>
        <label id="elh_audittrail_script" for="x_script" class="<?= $Page->LeftColumnClass ?>"><?= $Page->script->caption() ?><?= $Page->script->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->script->cellAttributes() ?>>
<span id="el_audittrail_script">
<input type="<?= $Page->script->getInputTextType() ?>" name="x_script" id="x_script" data-table="audittrail" data-field="x_script" value="<?= $Page->script->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->script->getPlaceHolder()) ?>"<?= $Page->script->editAttributes() ?> aria-describedby="x_script_help">
<?= $Page->script->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->script->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user->Visible) { // user ?>
    <div id="r_user"<?= $Page->user->rowAttributes() ?>>
        <label id="elh_audittrail_user" for="x_user" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user->caption() ?><?= $Page->user->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user->cellAttributes() ?>>
<span id="el_audittrail_user">
<input type="<?= $Page->user->getInputTextType() ?>" name="x_user" id="x_user" data-table="audittrail" data-field="x_user" value="<?= $Page->user->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->user->getPlaceHolder()) ?>"<?= $Page->user->editAttributes() ?> aria-describedby="x_user_help">
<?= $Page->user->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_action->Visible) { // action ?>
    <div id="r__action"<?= $Page->_action->rowAttributes() ?>>
        <label id="elh_audittrail__action" for="x__action" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_action->caption() ?><?= $Page->_action->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_action->cellAttributes() ?>>
<span id="el_audittrail__action">
<input type="<?= $Page->_action->getInputTextType() ?>" name="x__action" id="x__action" data-table="audittrail" data-field="x__action" value="<?= $Page->_action->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_action->getPlaceHolder()) ?>"<?= $Page->_action->editAttributes() ?> aria-describedby="x__action_help">
<?= $Page->_action->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_action->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_table->Visible) { // table ?>
    <div id="r__table"<?= $Page->_table->rowAttributes() ?>>
        <label id="elh_audittrail__table" for="x__table" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_table->caption() ?><?= $Page->_table->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_table->cellAttributes() ?>>
<span id="el_audittrail__table">
<input type="<?= $Page->_table->getInputTextType() ?>" name="x__table" id="x__table" data-table="audittrail" data-field="x__table" value="<?= $Page->_table->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->_table->getPlaceHolder()) ?>"<?= $Page->_table->editAttributes() ?> aria-describedby="x__table_help">
<?= $Page->_table->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_table->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->field->Visible) { // field ?>
    <div id="r_field"<?= $Page->field->rowAttributes() ?>>
        <label id="elh_audittrail_field" for="x_field" class="<?= $Page->LeftColumnClass ?>"><?= $Page->field->caption() ?><?= $Page->field->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->field->cellAttributes() ?>>
<span id="el_audittrail_field">
<input type="<?= $Page->field->getInputTextType() ?>" name="x_field" id="x_field" data-table="audittrail" data-field="x_field" value="<?= $Page->field->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->field->getPlaceHolder()) ?>"<?= $Page->field->editAttributes() ?> aria-describedby="x_field_help">
<?= $Page->field->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->field->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->keyvalue->Visible) { // keyvalue ?>
    <div id="r_keyvalue"<?= $Page->keyvalue->rowAttributes() ?>>
        <label id="elh_audittrail_keyvalue" for="x_keyvalue" class="<?= $Page->LeftColumnClass ?>"><?= $Page->keyvalue->caption() ?><?= $Page->keyvalue->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->keyvalue->cellAttributes() ?>>
<span id="el_audittrail_keyvalue">
<textarea data-table="audittrail" data-field="x_keyvalue" name="x_keyvalue" id="x_keyvalue" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->keyvalue->getPlaceHolder()) ?>"<?= $Page->keyvalue->editAttributes() ?> aria-describedby="x_keyvalue_help"><?= $Page->keyvalue->EditValue ?></textarea>
<?= $Page->keyvalue->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->keyvalue->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->oldvalue->Visible) { // oldvalue ?>
    <div id="r_oldvalue"<?= $Page->oldvalue->rowAttributes() ?>>
        <label id="elh_audittrail_oldvalue" for="x_oldvalue" class="<?= $Page->LeftColumnClass ?>"><?= $Page->oldvalue->caption() ?><?= $Page->oldvalue->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->oldvalue->cellAttributes() ?>>
<span id="el_audittrail_oldvalue">
<textarea data-table="audittrail" data-field="x_oldvalue" name="x_oldvalue" id="x_oldvalue" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->oldvalue->getPlaceHolder()) ?>"<?= $Page->oldvalue->editAttributes() ?> aria-describedby="x_oldvalue_help"><?= $Page->oldvalue->EditValue ?></textarea>
<?= $Page->oldvalue->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->oldvalue->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->newvalue->Visible) { // newvalue ?>
    <div id="r_newvalue"<?= $Page->newvalue->rowAttributes() ?>>
        <label id="elh_audittrail_newvalue" for="x_newvalue" class="<?= $Page->LeftColumnClass ?>"><?= $Page->newvalue->caption() ?><?= $Page->newvalue->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->newvalue->cellAttributes() ?>>
<span id="el_audittrail_newvalue">
<textarea data-table="audittrail" data-field="x_newvalue" name="x_newvalue" id="x_newvalue" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->newvalue->getPlaceHolder()) ?>"<?= $Page->newvalue->editAttributes() ?> aria-describedby="x_newvalue_help"><?= $Page->newvalue->EditValue ?></textarea>
<?= $Page->newvalue->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->newvalue->getErrorMessage() ?></div>
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
    ew.addEventHandlers("audittrail");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
