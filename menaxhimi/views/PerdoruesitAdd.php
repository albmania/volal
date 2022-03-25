<?php

namespace PHPMaker2022\volalservice;

// Page object
$PerdoruesitAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { perdoruesit: currentTable } });
var currentForm, currentPageID;
var fperdoruesitadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fperdoruesitadd = new ew.Form("fperdoruesitadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fperdoruesitadd;

    // Add fields
    var fields = currentTable.fields;
    fperdoruesitadd.addFields([
        ["perdEmri", [fields.perdEmri.visible && fields.perdEmri.required ? ew.Validators.required(fields.perdEmri.caption) : null], fields.perdEmri.isInvalid],
        ["perdUsername", [fields.perdUsername.visible && fields.perdUsername.required ? ew.Validators.required(fields.perdUsername.caption) : null], fields.perdUsername.isInvalid],
        ["perdFjalekalimi", [fields.perdFjalekalimi.visible && fields.perdFjalekalimi.required ? ew.Validators.required(fields.perdFjalekalimi.caption) : null], fields.perdFjalekalimi.isInvalid],
        ["perdEmail", [fields.perdEmail.visible && fields.perdEmail.required ? ew.Validators.required(fields.perdEmail.caption) : null], fields.perdEmail.isInvalid],
        ["perdNiveliPerdoruesit", [fields.perdNiveliPerdoruesit.visible && fields.perdNiveliPerdoruesit.required ? ew.Validators.required(fields.perdNiveliPerdoruesit.caption) : null], fields.perdNiveliPerdoruesit.isInvalid],
        ["perdDtReg", [fields.perdDtReg.visible && fields.perdDtReg.required ? ew.Validators.required(fields.perdDtReg.caption) : null, ew.Validators.datetime(fields.perdDtReg.clientFormatPattern)], fields.perdDtReg.isInvalid],
        ["perdActivated", [fields.perdActivated.visible && fields.perdActivated.required ? ew.Validators.required(fields.perdActivated.caption) : null], fields.perdActivated.isInvalid],
        ["perdProfileField", [fields.perdProfileField.visible && fields.perdProfileField.required ? ew.Validators.required(fields.perdProfileField.caption) : null], fields.perdProfileField.isInvalid]
    ]);

    // Form_CustomValidate
    fperdoruesitadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fperdoruesitadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fperdoruesitadd.lists.perdNiveliPerdoruesit = <?= $Page->perdNiveliPerdoruesit->toClientList($Page) ?>;
    loadjs.done("fperdoruesitadd");
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
<form name="fperdoruesitadd" id="fperdoruesitadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="perdoruesit">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->perdEmri->Visible) { // perdEmri ?>
    <div id="r_perdEmri"<?= $Page->perdEmri->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdEmri" for="x_perdEmri" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdEmri->caption() ?><?= $Page->perdEmri->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdEmri->cellAttributes() ?>>
<span id="el_perdoruesit_perdEmri">
<input type="<?= $Page->perdEmri->getInputTextType() ?>" name="x_perdEmri" id="x_perdEmri" data-table="perdoruesit" data-field="x_perdEmri" value="<?= $Page->perdEmri->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdEmri->getPlaceHolder()) ?>"<?= $Page->perdEmri->editAttributes() ?> aria-describedby="x_perdEmri_help">
<?= $Page->perdEmri->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdEmri->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdUsername->Visible) { // perdUsername ?>
    <div id="r_perdUsername"<?= $Page->perdUsername->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdUsername" for="x_perdUsername" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdUsername->caption() ?><?= $Page->perdUsername->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdUsername->cellAttributes() ?>>
<span id="el_perdoruesit_perdUsername">
<input type="<?= $Page->perdUsername->getInputTextType() ?>" name="x_perdUsername" id="x_perdUsername" data-table="perdoruesit" data-field="x_perdUsername" value="<?= $Page->perdUsername->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdUsername->getPlaceHolder()) ?>"<?= $Page->perdUsername->editAttributes() ?> aria-describedby="x_perdUsername_help">
<?= $Page->perdUsername->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdUsername->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdFjalekalimi->Visible) { // perdFjalekalimi ?>
    <div id="r_perdFjalekalimi"<?= $Page->perdFjalekalimi->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdFjalekalimi" for="x_perdFjalekalimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdFjalekalimi->caption() ?><?= $Page->perdFjalekalimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdFjalekalimi->cellAttributes() ?>>
<span id="el_perdoruesit_perdFjalekalimi">
<input type="<?= $Page->perdFjalekalimi->getInputTextType() ?>" name="x_perdFjalekalimi" id="x_perdFjalekalimi" data-table="perdoruesit" data-field="x_perdFjalekalimi" value="<?= $Page->perdFjalekalimi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdFjalekalimi->getPlaceHolder()) ?>"<?= $Page->perdFjalekalimi->editAttributes() ?> aria-describedby="x_perdFjalekalimi_help">
<?= $Page->perdFjalekalimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdFjalekalimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdEmail->Visible) { // perdEmail ?>
    <div id="r_perdEmail"<?= $Page->perdEmail->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdEmail" for="x_perdEmail" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdEmail->caption() ?><?= $Page->perdEmail->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdEmail->cellAttributes() ?>>
<span id="el_perdoruesit_perdEmail">
<input type="<?= $Page->perdEmail->getInputTextType() ?>" name="x_perdEmail" id="x_perdEmail" data-table="perdoruesit" data-field="x_perdEmail" value="<?= $Page->perdEmail->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdEmail->getPlaceHolder()) ?>"<?= $Page->perdEmail->editAttributes() ?> aria-describedby="x_perdEmail_help">
<?= $Page->perdEmail->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdEmail->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdNiveliPerdoruesit->Visible) { // perdNiveliPerdoruesit ?>
    <div id="r_perdNiveliPerdoruesit"<?= $Page->perdNiveliPerdoruesit->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdNiveliPerdoruesit" for="x_perdNiveliPerdoruesit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdNiveliPerdoruesit->caption() ?><?= $Page->perdNiveliPerdoruesit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdNiveliPerdoruesit->cellAttributes() ?>>
<?php if (!$Security->isAdmin() && $Security->isLoggedIn()) { // Non system admin ?>
<span id="el_perdoruesit_perdNiveliPerdoruesit">
<span class="form-control-plaintext"><?= $Page->perdNiveliPerdoruesit->getDisplayValue($Page->perdNiveliPerdoruesit->EditValue) ?></span>
</span>
<?php } else { ?>
<span id="el_perdoruesit_perdNiveliPerdoruesit">
    <select
        id="x_perdNiveliPerdoruesit"
        name="x_perdNiveliPerdoruesit"
        class="form-select ew-select<?= $Page->perdNiveliPerdoruesit->isInvalidClass() ?>"
        data-select2-id="fperdoruesitadd_x_perdNiveliPerdoruesit"
        data-table="perdoruesit"
        data-field="x_perdNiveliPerdoruesit"
        data-value-separator="<?= $Page->perdNiveliPerdoruesit->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->perdNiveliPerdoruesit->getPlaceHolder()) ?>"
        <?= $Page->perdNiveliPerdoruesit->editAttributes() ?>>
        <?= $Page->perdNiveliPerdoruesit->selectOptionListHtml("x_perdNiveliPerdoruesit") ?>
    </select>
    <?= $Page->perdNiveliPerdoruesit->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->perdNiveliPerdoruesit->getErrorMessage() ?></div>
<?= $Page->perdNiveliPerdoruesit->Lookup->getParamTag($Page, "p_x_perdNiveliPerdoruesit") ?>
<script>
loadjs.ready("fperdoruesitadd", function() {
    var options = { name: "x_perdNiveliPerdoruesit", selectId: "fperdoruesitadd_x_perdNiveliPerdoruesit" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fperdoruesitadd.lists.perdNiveliPerdoruesit.lookupOptions.length) {
        options.data = { id: "x_perdNiveliPerdoruesit", form: "fperdoruesitadd" };
    } else {
        options.ajax = { id: "x_perdNiveliPerdoruesit", form: "fperdoruesitadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.perdoruesit.fields.perdNiveliPerdoruesit.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdDtReg->Visible) { // perdDtReg ?>
    <div id="r_perdDtReg"<?= $Page->perdDtReg->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdDtReg" for="x_perdDtReg" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdDtReg->caption() ?><?= $Page->perdDtReg->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdDtReg->cellAttributes() ?>>
<span id="el_perdoruesit_perdDtReg">
<input type="<?= $Page->perdDtReg->getInputTextType() ?>" name="x_perdDtReg" id="x_perdDtReg" data-table="perdoruesit" data-field="x_perdDtReg" value="<?= $Page->perdDtReg->EditValue ?>" placeholder="<?= HtmlEncode($Page->perdDtReg->getPlaceHolder()) ?>"<?= $Page->perdDtReg->editAttributes() ?> aria-describedby="x_perdDtReg_help">
<?= $Page->perdDtReg->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdDtReg->getErrorMessage() ?></div>
<?php if (!$Page->perdDtReg->ReadOnly && !$Page->perdDtReg->Disabled && !isset($Page->perdDtReg->EditAttrs["readonly"]) && !isset($Page->perdDtReg->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fperdoruesitadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fperdoruesitadd", "x_perdDtReg", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdActivated->Visible) { // perdActivated ?>
    <div id="r_perdActivated"<?= $Page->perdActivated->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdActivated" for="x_perdActivated" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdActivated->caption() ?><?= $Page->perdActivated->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdActivated->cellAttributes() ?>>
<span id="el_perdoruesit_perdActivated">
<input type="<?= $Page->perdActivated->getInputTextType() ?>" name="x_perdActivated" id="x_perdActivated" data-table="perdoruesit" data-field="x_perdActivated" value="<?= $Page->perdActivated->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdActivated->getPlaceHolder()) ?>"<?= $Page->perdActivated->editAttributes() ?> aria-describedby="x_perdActivated_help">
<?= $Page->perdActivated->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdActivated->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdProfileField->Visible) { // perdProfileField ?>
    <div id="r_perdProfileField"<?= $Page->perdProfileField->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdProfileField" for="x_perdProfileField" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdProfileField->caption() ?><?= $Page->perdProfileField->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdProfileField->cellAttributes() ?>>
<span id="el_perdoruesit_perdProfileField">
<textarea data-table="perdoruesit" data-field="x_perdProfileField" name="x_perdProfileField" id="x_perdProfileField" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->perdProfileField->getPlaceHolder()) ?>"<?= $Page->perdProfileField->editAttributes() ?> aria-describedby="x_perdProfileField_help"><?= $Page->perdProfileField->EditValue ?></textarea>
<?= $Page->perdProfileField->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdProfileField->getErrorMessage() ?></div>
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
    ew.addEventHandlers("perdoruesit");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
