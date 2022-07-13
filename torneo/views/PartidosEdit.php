<?php

namespace PHPMaker2022\project1;

// Page object
$PartidosEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { partidos: currentTable } });
var currentForm, currentPageID;
var fpartidosedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fpartidosedit = new ew.Form("fpartidosedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fpartidosedit;

    // Add fields
    var fields = currentTable.fields;
    fpartidosedit.addFields([
        ["ID_EQUIPO2", [fields.ID_EQUIPO2.visible && fields.ID_EQUIPO2.required ? ew.Validators.required(fields.ID_EQUIPO2.caption) : null], fields.ID_EQUIPO2.isInvalid],
        ["ID_EQUIPO1", [fields.ID_EQUIPO1.visible && fields.ID_EQUIPO1.required ? ew.Validators.required(fields.ID_EQUIPO1.caption) : null], fields.ID_EQUIPO1.isInvalid],
        ["ID_PARTIDO", [fields.ID_PARTIDO.visible && fields.ID_PARTIDO.required ? ew.Validators.required(fields.ID_PARTIDO.caption) : null], fields.ID_PARTIDO.isInvalid],
        ["ID_TORNEO", [fields.ID_TORNEO.visible && fields.ID_TORNEO.required ? ew.Validators.required(fields.ID_TORNEO.caption) : null], fields.ID_TORNEO.isInvalid],
        ["FECHA_PARTIDO", [fields.FECHA_PARTIDO.visible && fields.FECHA_PARTIDO.required ? ew.Validators.required(fields.FECHA_PARTIDO.caption) : null, ew.Validators.datetime(fields.FECHA_PARTIDO.clientFormatPattern)], fields.FECHA_PARTIDO.isInvalid],
        ["HORA_PARTIDO", [fields.HORA_PARTIDO.visible && fields.HORA_PARTIDO.required ? ew.Validators.required(fields.HORA_PARTIDO.caption) : null, ew.Validators.time(fields.HORA_PARTIDO.clientFormatPattern)], fields.HORA_PARTIDO.isInvalid],
        ["DIA_PARTIDO", [fields.DIA_PARTIDO.visible && fields.DIA_PARTIDO.required ? ew.Validators.required(fields.DIA_PARTIDO.caption) : null], fields.DIA_PARTIDO.isInvalid],
        ["ESTADIO", [fields.ESTADIO.visible && fields.ESTADIO.required ? ew.Validators.required(fields.ESTADIO.caption) : null], fields.ESTADIO.isInvalid],
        ["CIUDAD_PARTIDO", [fields.CIUDAD_PARTIDO.visible && fields.CIUDAD_PARTIDO.required ? ew.Validators.required(fields.CIUDAD_PARTIDO.caption) : null], fields.CIUDAD_PARTIDO.isInvalid],
        ["PAIS_PARTIDO", [fields.PAIS_PARTIDO.visible && fields.PAIS_PARTIDO.required ? ew.Validators.required(fields.PAIS_PARTIDO.caption) : null], fields.PAIS_PARTIDO.isInvalid],
        ["GOLES_EQUIPO1", [fields.GOLES_EQUIPO1.visible && fields.GOLES_EQUIPO1.required ? ew.Validators.required(fields.GOLES_EQUIPO1.caption) : null, ew.Validators.integer], fields.GOLES_EQUIPO1.isInvalid],
        ["GOLES_EQUIPO2", [fields.GOLES_EQUIPO2.visible && fields.GOLES_EQUIPO2.required ? ew.Validators.required(fields.GOLES_EQUIPO2.caption) : null, ew.Validators.integer], fields.GOLES_EQUIPO2.isInvalid],
        ["GOLES_EXTRA_EQUIPO1", [fields.GOLES_EXTRA_EQUIPO1.visible && fields.GOLES_EXTRA_EQUIPO1.required ? ew.Validators.required(fields.GOLES_EXTRA_EQUIPO1.caption) : null, ew.Validators.integer], fields.GOLES_EXTRA_EQUIPO1.isInvalid],
        ["GOLES_EXTRA_EQUIPO2", [fields.GOLES_EXTRA_EQUIPO2.visible && fields.GOLES_EXTRA_EQUIPO2.required ? ew.Validators.required(fields.GOLES_EXTRA_EQUIPO2.caption) : null, ew.Validators.integer], fields.GOLES_EXTRA_EQUIPO2.isInvalid],
        ["NOTA_PARTIDO", [fields.NOTA_PARTIDO.visible && fields.NOTA_PARTIDO.required ? ew.Validators.required(fields.NOTA_PARTIDO.caption) : null], fields.NOTA_PARTIDO.isInvalid],
        ["RESUMEN_PARTIDO", [fields.RESUMEN_PARTIDO.visible && fields.RESUMEN_PARTIDO.required ? ew.Validators.required(fields.RESUMEN_PARTIDO.caption) : null], fields.RESUMEN_PARTIDO.isInvalid],
        ["ESTADO_PARTIDO", [fields.ESTADO_PARTIDO.visible && fields.ESTADO_PARTIDO.required ? ew.Validators.required(fields.ESTADO_PARTIDO.caption) : null], fields.ESTADO_PARTIDO.isInvalid]
    ]);

    // Form_CustomValidate
    fpartidosedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpartidosedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fpartidosedit.lists.ID_EQUIPO2 = <?= $Page->ID_EQUIPO2->toClientList($Page) ?>;
    fpartidosedit.lists.ID_EQUIPO1 = <?= $Page->ID_EQUIPO1->toClientList($Page) ?>;
    fpartidosedit.lists.ID_TORNEO = <?= $Page->ID_TORNEO->toClientList($Page) ?>;
    loadjs.done("fpartidosedit");
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
<form name="fpartidosedit" id="fpartidosedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="partidos">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->ID_EQUIPO2->Visible) { // ID_EQUIPO2 ?>
    <div id="r_ID_EQUIPO2"<?= $Page->ID_EQUIPO2->rowAttributes() ?>>
        <label id="elh_partidos_ID_EQUIPO2" for="x_ID_EQUIPO2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ID_EQUIPO2->caption() ?><?= $Page->ID_EQUIPO2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ID_EQUIPO2->cellAttributes() ?>>
<span id="el_partidos_ID_EQUIPO2">
    <select
        id="x_ID_EQUIPO2"
        name="x_ID_EQUIPO2"
        class="form-select ew-select<?= $Page->ID_EQUIPO2->isInvalidClass() ?>"
        data-select2-id="fpartidosedit_x_ID_EQUIPO2"
        data-table="partidos"
        data-field="x_ID_EQUIPO2"
        data-value-separator="<?= $Page->ID_EQUIPO2->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ID_EQUIPO2->getPlaceHolder()) ?>"
        <?= $Page->ID_EQUIPO2->editAttributes() ?>>
        <?= $Page->ID_EQUIPO2->selectOptionListHtml("x_ID_EQUIPO2") ?>
    </select>
    <?= $Page->ID_EQUIPO2->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->ID_EQUIPO2->getErrorMessage() ?></div>
<?= $Page->ID_EQUIPO2->Lookup->getParamTag($Page, "p_x_ID_EQUIPO2") ?>
<script>
loadjs.ready("fpartidosedit", function() {
    var options = { name: "x_ID_EQUIPO2", selectId: "fpartidosedit_x_ID_EQUIPO2" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpartidosedit.lists.ID_EQUIPO2.lookupOptions.length) {
        options.data = { id: "x_ID_EQUIPO2", form: "fpartidosedit" };
    } else {
        options.ajax = { id: "x_ID_EQUIPO2", form: "fpartidosedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.partidos.fields.ID_EQUIPO2.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ID_EQUIPO1->Visible) { // ID_EQUIPO1 ?>
    <div id="r_ID_EQUIPO1"<?= $Page->ID_EQUIPO1->rowAttributes() ?>>
        <label id="elh_partidos_ID_EQUIPO1" for="x_ID_EQUIPO1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ID_EQUIPO1->caption() ?><?= $Page->ID_EQUIPO1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ID_EQUIPO1->cellAttributes() ?>>
<span id="el_partidos_ID_EQUIPO1">
    <select
        id="x_ID_EQUIPO1"
        name="x_ID_EQUIPO1"
        class="form-select ew-select<?= $Page->ID_EQUIPO1->isInvalidClass() ?>"
        data-select2-id="fpartidosedit_x_ID_EQUIPO1"
        data-table="partidos"
        data-field="x_ID_EQUIPO1"
        data-value-separator="<?= $Page->ID_EQUIPO1->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ID_EQUIPO1->getPlaceHolder()) ?>"
        <?= $Page->ID_EQUIPO1->editAttributes() ?>>
        <?= $Page->ID_EQUIPO1->selectOptionListHtml("x_ID_EQUIPO1") ?>
    </select>
    <?= $Page->ID_EQUIPO1->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->ID_EQUIPO1->getErrorMessage() ?></div>
<?= $Page->ID_EQUIPO1->Lookup->getParamTag($Page, "p_x_ID_EQUIPO1") ?>
<script>
loadjs.ready("fpartidosedit", function() {
    var options = { name: "x_ID_EQUIPO1", selectId: "fpartidosedit_x_ID_EQUIPO1" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpartidosedit.lists.ID_EQUIPO1.lookupOptions.length) {
        options.data = { id: "x_ID_EQUIPO1", form: "fpartidosedit" };
    } else {
        options.ajax = { id: "x_ID_EQUIPO1", form: "fpartidosedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.partidos.fields.ID_EQUIPO1.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ID_PARTIDO->Visible) { // ID_PARTIDO ?>
    <div id="r_ID_PARTIDO"<?= $Page->ID_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_ID_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ID_PARTIDO->caption() ?><?= $Page->ID_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ID_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_ID_PARTIDO">
<span<?= $Page->ID_PARTIDO->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->ID_PARTIDO->getDisplayValue($Page->ID_PARTIDO->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="partidos" data-field="x_ID_PARTIDO" data-hidden="1" name="x_ID_PARTIDO" id="x_ID_PARTIDO" value="<?= HtmlEncode($Page->ID_PARTIDO->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ID_TORNEO->Visible) { // ID_TORNEO ?>
    <div id="r_ID_TORNEO"<?= $Page->ID_TORNEO->rowAttributes() ?>>
        <label id="elh_partidos_ID_TORNEO" for="x_ID_TORNEO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ID_TORNEO->caption() ?><?= $Page->ID_TORNEO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ID_TORNEO->cellAttributes() ?>>
<span id="el_partidos_ID_TORNEO">
    <select
        id="x_ID_TORNEO"
        name="x_ID_TORNEO"
        class="form-select ew-select<?= $Page->ID_TORNEO->isInvalidClass() ?>"
        data-select2-id="fpartidosedit_x_ID_TORNEO"
        data-table="partidos"
        data-field="x_ID_TORNEO"
        data-value-separator="<?= $Page->ID_TORNEO->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->ID_TORNEO->getPlaceHolder()) ?>"
        <?= $Page->ID_TORNEO->editAttributes() ?>>
        <?= $Page->ID_TORNEO->selectOptionListHtml("x_ID_TORNEO") ?>
    </select>
    <?= $Page->ID_TORNEO->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->ID_TORNEO->getErrorMessage() ?></div>
<?= $Page->ID_TORNEO->Lookup->getParamTag($Page, "p_x_ID_TORNEO") ?>
<script>
loadjs.ready("fpartidosedit", function() {
    var options = { name: "x_ID_TORNEO", selectId: "fpartidosedit_x_ID_TORNEO" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fpartidosedit.lists.ID_TORNEO.lookupOptions.length) {
        options.data = { id: "x_ID_TORNEO", form: "fpartidosedit" };
    } else {
        options.ajax = { id: "x_ID_TORNEO", form: "fpartidosedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.partidos.fields.ID_TORNEO.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->FECHA_PARTIDO->Visible) { // FECHA_PARTIDO ?>
    <div id="r_FECHA_PARTIDO"<?= $Page->FECHA_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_FECHA_PARTIDO" for="x_FECHA_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->FECHA_PARTIDO->caption() ?><?= $Page->FECHA_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->FECHA_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_FECHA_PARTIDO">
<input type="<?= $Page->FECHA_PARTIDO->getInputTextType() ?>" name="x_FECHA_PARTIDO" id="x_FECHA_PARTIDO" data-table="partidos" data-field="x_FECHA_PARTIDO" value="<?= $Page->FECHA_PARTIDO->EditValue ?>" placeholder="<?= HtmlEncode($Page->FECHA_PARTIDO->getPlaceHolder()) ?>"<?= $Page->FECHA_PARTIDO->editAttributes() ?> aria-describedby="x_FECHA_PARTIDO_help">
<?= $Page->FECHA_PARTIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->FECHA_PARTIDO->getErrorMessage() ?></div>
<?php if (!$Page->FECHA_PARTIDO->ReadOnly && !$Page->FECHA_PARTIDO->Disabled && !isset($Page->FECHA_PARTIDO->EditAttrs["readonly"]) && !isset($Page->FECHA_PARTIDO->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fpartidosedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fpartidosedit", "x_FECHA_PARTIDO", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->HORA_PARTIDO->Visible) { // HORA_PARTIDO ?>
    <div id="r_HORA_PARTIDO"<?= $Page->HORA_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_HORA_PARTIDO" for="x_HORA_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->HORA_PARTIDO->caption() ?><?= $Page->HORA_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->HORA_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_HORA_PARTIDO">
<input type="<?= $Page->HORA_PARTIDO->getInputTextType() ?>" name="x_HORA_PARTIDO" id="x_HORA_PARTIDO" data-table="partidos" data-field="x_HORA_PARTIDO" value="<?= $Page->HORA_PARTIDO->EditValue ?>" placeholder="<?= HtmlEncode($Page->HORA_PARTIDO->getPlaceHolder()) ?>"<?= $Page->HORA_PARTIDO->editAttributes() ?> aria-describedby="x_HORA_PARTIDO_help">
<?= $Page->HORA_PARTIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->HORA_PARTIDO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->DIA_PARTIDO->Visible) { // DIA_PARTIDO ?>
    <div id="r_DIA_PARTIDO"<?= $Page->DIA_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_DIA_PARTIDO" for="x_DIA_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->DIA_PARTIDO->caption() ?><?= $Page->DIA_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->DIA_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_DIA_PARTIDO">
<textarea data-table="partidos" data-field="x_DIA_PARTIDO" name="x_DIA_PARTIDO" id="x_DIA_PARTIDO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->DIA_PARTIDO->getPlaceHolder()) ?>"<?= $Page->DIA_PARTIDO->editAttributes() ?> aria-describedby="x_DIA_PARTIDO_help"><?= $Page->DIA_PARTIDO->EditValue ?></textarea>
<?= $Page->DIA_PARTIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->DIA_PARTIDO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ESTADIO->Visible) { // ESTADIO ?>
    <div id="r_ESTADIO"<?= $Page->ESTADIO->rowAttributes() ?>>
        <label id="elh_partidos_ESTADIO" for="x_ESTADIO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ESTADIO->caption() ?><?= $Page->ESTADIO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ESTADIO->cellAttributes() ?>>
<span id="el_partidos_ESTADIO">
<textarea data-table="partidos" data-field="x_ESTADIO" name="x_ESTADIO" id="x_ESTADIO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->ESTADIO->getPlaceHolder()) ?>"<?= $Page->ESTADIO->editAttributes() ?> aria-describedby="x_ESTADIO_help"><?= $Page->ESTADIO->EditValue ?></textarea>
<?= $Page->ESTADIO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ESTADIO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->CIUDAD_PARTIDO->Visible) { // CIUDAD_PARTIDO ?>
    <div id="r_CIUDAD_PARTIDO"<?= $Page->CIUDAD_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_CIUDAD_PARTIDO" for="x_CIUDAD_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->CIUDAD_PARTIDO->caption() ?><?= $Page->CIUDAD_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->CIUDAD_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_CIUDAD_PARTIDO">
<textarea data-table="partidos" data-field="x_CIUDAD_PARTIDO" name="x_CIUDAD_PARTIDO" id="x_CIUDAD_PARTIDO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->CIUDAD_PARTIDO->getPlaceHolder()) ?>"<?= $Page->CIUDAD_PARTIDO->editAttributes() ?> aria-describedby="x_CIUDAD_PARTIDO_help"><?= $Page->CIUDAD_PARTIDO->EditValue ?></textarea>
<?= $Page->CIUDAD_PARTIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->CIUDAD_PARTIDO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->PAIS_PARTIDO->Visible) { // PAIS_PARTIDO ?>
    <div id="r_PAIS_PARTIDO"<?= $Page->PAIS_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_PAIS_PARTIDO" for="x_PAIS_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->PAIS_PARTIDO->caption() ?><?= $Page->PAIS_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->PAIS_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_PAIS_PARTIDO">
<textarea data-table="partidos" data-field="x_PAIS_PARTIDO" name="x_PAIS_PARTIDO" id="x_PAIS_PARTIDO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->PAIS_PARTIDO->getPlaceHolder()) ?>"<?= $Page->PAIS_PARTIDO->editAttributes() ?> aria-describedby="x_PAIS_PARTIDO_help"><?= $Page->PAIS_PARTIDO->EditValue ?></textarea>
<?= $Page->PAIS_PARTIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->PAIS_PARTIDO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO1->Visible) { // GOLES_EQUIPO1 ?>
    <div id="r_GOLES_EQUIPO1"<?= $Page->GOLES_EQUIPO1->rowAttributes() ?>>
        <label id="elh_partidos_GOLES_EQUIPO1" for="x_GOLES_EQUIPO1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->GOLES_EQUIPO1->caption() ?><?= $Page->GOLES_EQUIPO1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->GOLES_EQUIPO1->cellAttributes() ?>>
<span id="el_partidos_GOLES_EQUIPO1">
<input type="<?= $Page->GOLES_EQUIPO1->getInputTextType() ?>" name="x_GOLES_EQUIPO1" id="x_GOLES_EQUIPO1" data-table="partidos" data-field="x_GOLES_EQUIPO1" value="<?= $Page->GOLES_EQUIPO1->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->GOLES_EQUIPO1->getPlaceHolder()) ?>"<?= $Page->GOLES_EQUIPO1->editAttributes() ?> aria-describedby="x_GOLES_EQUIPO1_help">
<?= $Page->GOLES_EQUIPO1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->GOLES_EQUIPO1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->GOLES_EQUIPO2->Visible) { // GOLES_EQUIPO2 ?>
    <div id="r_GOLES_EQUIPO2"<?= $Page->GOLES_EQUIPO2->rowAttributes() ?>>
        <label id="elh_partidos_GOLES_EQUIPO2" for="x_GOLES_EQUIPO2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->GOLES_EQUIPO2->caption() ?><?= $Page->GOLES_EQUIPO2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->GOLES_EQUIPO2->cellAttributes() ?>>
<span id="el_partidos_GOLES_EQUIPO2">
<input type="<?= $Page->GOLES_EQUIPO2->getInputTextType() ?>" name="x_GOLES_EQUIPO2" id="x_GOLES_EQUIPO2" data-table="partidos" data-field="x_GOLES_EQUIPO2" value="<?= $Page->GOLES_EQUIPO2->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->GOLES_EQUIPO2->getPlaceHolder()) ?>"<?= $Page->GOLES_EQUIPO2->editAttributes() ?> aria-describedby="x_GOLES_EQUIPO2_help">
<?= $Page->GOLES_EQUIPO2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->GOLES_EQUIPO2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO1->Visible) { // GOLES_EXTRA_EQUIPO1 ?>
    <div id="r_GOLES_EXTRA_EQUIPO1"<?= $Page->GOLES_EXTRA_EQUIPO1->rowAttributes() ?>>
        <label id="elh_partidos_GOLES_EXTRA_EQUIPO1" for="x_GOLES_EXTRA_EQUIPO1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->GOLES_EXTRA_EQUIPO1->caption() ?><?= $Page->GOLES_EXTRA_EQUIPO1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->GOLES_EXTRA_EQUIPO1->cellAttributes() ?>>
<span id="el_partidos_GOLES_EXTRA_EQUIPO1">
<input type="<?= $Page->GOLES_EXTRA_EQUIPO1->getInputTextType() ?>" name="x_GOLES_EXTRA_EQUIPO1" id="x_GOLES_EXTRA_EQUIPO1" data-table="partidos" data-field="x_GOLES_EXTRA_EQUIPO1" value="<?= $Page->GOLES_EXTRA_EQUIPO1->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->GOLES_EXTRA_EQUIPO1->getPlaceHolder()) ?>"<?= $Page->GOLES_EXTRA_EQUIPO1->editAttributes() ?> aria-describedby="x_GOLES_EXTRA_EQUIPO1_help">
<?= $Page->GOLES_EXTRA_EQUIPO1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->GOLES_EXTRA_EQUIPO1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->GOLES_EXTRA_EQUIPO2->Visible) { // GOLES_EXTRA_EQUIPO2 ?>
    <div id="r_GOLES_EXTRA_EQUIPO2"<?= $Page->GOLES_EXTRA_EQUIPO2->rowAttributes() ?>>
        <label id="elh_partidos_GOLES_EXTRA_EQUIPO2" for="x_GOLES_EXTRA_EQUIPO2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->GOLES_EXTRA_EQUIPO2->caption() ?><?= $Page->GOLES_EXTRA_EQUIPO2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->GOLES_EXTRA_EQUIPO2->cellAttributes() ?>>
<span id="el_partidos_GOLES_EXTRA_EQUIPO2">
<input type="<?= $Page->GOLES_EXTRA_EQUIPO2->getInputTextType() ?>" name="x_GOLES_EXTRA_EQUIPO2" id="x_GOLES_EXTRA_EQUIPO2" data-table="partidos" data-field="x_GOLES_EXTRA_EQUIPO2" value="<?= $Page->GOLES_EXTRA_EQUIPO2->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->GOLES_EXTRA_EQUIPO2->getPlaceHolder()) ?>"<?= $Page->GOLES_EXTRA_EQUIPO2->editAttributes() ?> aria-describedby="x_GOLES_EXTRA_EQUIPO2_help">
<?= $Page->GOLES_EXTRA_EQUIPO2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->GOLES_EXTRA_EQUIPO2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->NOTA_PARTIDO->Visible) { // NOTA_PARTIDO ?>
    <div id="r_NOTA_PARTIDO"<?= $Page->NOTA_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_NOTA_PARTIDO" for="x_NOTA_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->NOTA_PARTIDO->caption() ?><?= $Page->NOTA_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->NOTA_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_NOTA_PARTIDO">
<textarea data-table="partidos" data-field="x_NOTA_PARTIDO" name="x_NOTA_PARTIDO" id="x_NOTA_PARTIDO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->NOTA_PARTIDO->getPlaceHolder()) ?>"<?= $Page->NOTA_PARTIDO->editAttributes() ?> aria-describedby="x_NOTA_PARTIDO_help"><?= $Page->NOTA_PARTIDO->EditValue ?></textarea>
<?= $Page->NOTA_PARTIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->NOTA_PARTIDO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->RESUMEN_PARTIDO->Visible) { // RESUMEN_PARTIDO ?>
    <div id="r_RESUMEN_PARTIDO"<?= $Page->RESUMEN_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_RESUMEN_PARTIDO" for="x_RESUMEN_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->RESUMEN_PARTIDO->caption() ?><?= $Page->RESUMEN_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->RESUMEN_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_RESUMEN_PARTIDO">
<textarea data-table="partidos" data-field="x_RESUMEN_PARTIDO" name="x_RESUMEN_PARTIDO" id="x_RESUMEN_PARTIDO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->RESUMEN_PARTIDO->getPlaceHolder()) ?>"<?= $Page->RESUMEN_PARTIDO->editAttributes() ?> aria-describedby="x_RESUMEN_PARTIDO_help"><?= $Page->RESUMEN_PARTIDO->EditValue ?></textarea>
<?= $Page->RESUMEN_PARTIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->RESUMEN_PARTIDO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->ESTADO_PARTIDO->Visible) { // ESTADO_PARTIDO ?>
    <div id="r_ESTADO_PARTIDO"<?= $Page->ESTADO_PARTIDO->rowAttributes() ?>>
        <label id="elh_partidos_ESTADO_PARTIDO" for="x_ESTADO_PARTIDO" class="<?= $Page->LeftColumnClass ?>"><?= $Page->ESTADO_PARTIDO->caption() ?><?= $Page->ESTADO_PARTIDO->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->ESTADO_PARTIDO->cellAttributes() ?>>
<span id="el_partidos_ESTADO_PARTIDO">
<textarea data-table="partidos" data-field="x_ESTADO_PARTIDO" name="x_ESTADO_PARTIDO" id="x_ESTADO_PARTIDO" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->ESTADO_PARTIDO->getPlaceHolder()) ?>"<?= $Page->ESTADO_PARTIDO->editAttributes() ?> aria-describedby="x_ESTADO_PARTIDO_help"><?= $Page->ESTADO_PARTIDO->EditValue ?></textarea>
<?= $Page->ESTADO_PARTIDO->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->ESTADO_PARTIDO->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="row"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("SaveBtn") ?></button>
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
    ew.addEventHandlers("partidos");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
