<?php

namespace PHPMaker2022\volalservice;

// Page object
$PjeseKembimiEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pjese_kembimi: currentTable } });
var currentForm, currentPageID;
var fpjese_kembimiedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fpjese_kembimiedit = new ew.Form("fpjese_kembimiedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fpjese_kembimiedit;

    // Add fields
    var fields = currentTable.fields;
    fpjese_kembimiedit.addFields([
        ["pjeseID", [fields.pjeseID.visible && fields.pjeseID.required ? ew.Validators.required(fields.pjeseID.caption) : null], fields.pjeseID.isInvalid],
        ["pjeseGjendja", [fields.pjeseGjendja.visible && fields.pjeseGjendja.required ? ew.Validators.required(fields.pjeseGjendja.caption) : null], fields.pjeseGjendja.isInvalid],
        ["pjeseKodiVolvo", [fields.pjeseKodiVolvo.visible && fields.pjeseKodiVolvo.required ? ew.Validators.required(fields.pjeseKodiVolvo.caption) : null], fields.pjeseKodiVolvo.isInvalid],
        ["pjeseKodiProdhuesi", [fields.pjeseKodiProdhuesi.visible && fields.pjeseKodiProdhuesi.required ? ew.Validators.required(fields.pjeseKodiProdhuesi.caption) : null], fields.pjeseKodiProdhuesi.isInvalid],
        ["pjeseProdhuesi", [fields.pjeseProdhuesi.visible && fields.pjeseProdhuesi.required ? ew.Validators.required(fields.pjeseProdhuesi.caption) : null], fields.pjeseProdhuesi.isInvalid],
        ["pjesePerMarke", [fields.pjesePerMarke.visible && fields.pjesePerMarke.required ? ew.Validators.required(fields.pjesePerMarke.caption) : null], fields.pjesePerMarke.isInvalid],
        ["pjesePerModel", [fields.pjesePerModel.visible && fields.pjesePerModel.required ? ew.Validators.required(fields.pjesePerModel.caption) : null], fields.pjesePerModel.isInvalid],
        ["pjesePerVitProdhimi", [fields.pjesePerVitProdhimi.visible && fields.pjesePerVitProdhimi.required ? ew.Validators.required(fields.pjesePerVitProdhimi.caption) : null], fields.pjesePerVitProdhimi.isInvalid],
        ["pjeseCmimBlerje", [fields.pjeseCmimBlerje.visible && fields.pjeseCmimBlerje.required ? ew.Validators.required(fields.pjeseCmimBlerje.caption) : null, ew.Validators.float], fields.pjeseCmimBlerje.isInvalid],
        ["pjeseCmimShitje", [fields.pjeseCmimShitje.visible && fields.pjeseCmimShitje.required ? ew.Validators.required(fields.pjeseCmimShitje.caption) : null, ew.Validators.float], fields.pjeseCmimShitje.isInvalid],
        ["pjeseAutori", [fields.pjeseAutori.visible && fields.pjeseAutori.required ? ew.Validators.required(fields.pjeseAutori.caption) : null], fields.pjeseAutori.isInvalid],
        ["pjeseModifikuar", [fields.pjeseModifikuar.visible && fields.pjeseModifikuar.required ? ew.Validators.required(fields.pjeseModifikuar.caption) : null], fields.pjeseModifikuar.isInvalid]
    ]);

    // Form_CustomValidate
    fpjese_kembimiedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpjese_kembimiedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fpjese_kembimiedit.lists.pjeseGjendja = <?= $Page->pjeseGjendja->toClientList($Page) ?>;
    fpjese_kembimiedit.lists.pjeseAutori = <?= $Page->pjeseAutori->toClientList($Page) ?>;
    loadjs.done("fpjese_kembimiedit");
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
<form name="fpjese_kembimiedit" id="fpjese_kembimiedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pjese_kembimi">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->pjeseID->Visible) { // pjeseID ?>
    <div id="r_pjeseID"<?= $Page->pjeseID->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjeseID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjeseID->caption() ?><?= $Page->pjeseID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjeseID->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseID">
<span<?= $Page->pjeseID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->pjeseID->getDisplayValue($Page->pjeseID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="pjese_kembimi" data-field="x_pjeseID" data-hidden="1" name="x_pjeseID" id="x_pjeseID" value="<?= HtmlEncode($Page->pjeseID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjeseGjendja->Visible) { // pjeseGjendja ?>
    <div id="r_pjeseGjendja"<?= $Page->pjeseGjendja->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjeseGjendja" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjeseGjendja->caption() ?><?= $Page->pjeseGjendja->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjeseGjendja->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseGjendja">
<template id="tp_x_pjeseGjendja">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="pjese_kembimi" data-field="x_pjeseGjendja" name="x_pjeseGjendja" id="x_pjeseGjendja"<?= $Page->pjeseGjendja->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_pjeseGjendja" class="ew-item-list"></div>
<selection-list hidden
    id="x_pjeseGjendja"
    name="x_pjeseGjendja"
    value="<?= HtmlEncode($Page->pjeseGjendja->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_pjeseGjendja"
    data-bs-target="dsl_x_pjeseGjendja"
    data-repeatcolumn="5"
    class="form-control<?= $Page->pjeseGjendja->isInvalidClass() ?>"
    data-table="pjese_kembimi"
    data-field="x_pjeseGjendja"
    data-value-separator="<?= $Page->pjeseGjendja->displayValueSeparatorAttribute() ?>"
    <?= $Page->pjeseGjendja->editAttributes() ?>></selection-list>
<?= $Page->pjeseGjendja->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjeseGjendja->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjeseKodiVolvo->Visible) { // pjeseKodiVolvo ?>
    <div id="r_pjeseKodiVolvo"<?= $Page->pjeseKodiVolvo->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjeseKodiVolvo" for="x_pjeseKodiVolvo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjeseKodiVolvo->caption() ?><?= $Page->pjeseKodiVolvo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjeseKodiVolvo->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseKodiVolvo">
<input type="<?= $Page->pjeseKodiVolvo->getInputTextType() ?>" name="x_pjeseKodiVolvo" id="x_pjeseKodiVolvo" data-table="pjese_kembimi" data-field="x_pjeseKodiVolvo" value="<?= $Page->pjeseKodiVolvo->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->pjeseKodiVolvo->getPlaceHolder()) ?>"<?= $Page->pjeseKodiVolvo->editAttributes() ?> aria-describedby="x_pjeseKodiVolvo_help">
<?= $Page->pjeseKodiVolvo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjeseKodiVolvo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjeseKodiProdhuesi->Visible) { // pjeseKodiProdhuesi ?>
    <div id="r_pjeseKodiProdhuesi"<?= $Page->pjeseKodiProdhuesi->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjeseKodiProdhuesi" for="x_pjeseKodiProdhuesi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjeseKodiProdhuesi->caption() ?><?= $Page->pjeseKodiProdhuesi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjeseKodiProdhuesi->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseKodiProdhuesi">
<input type="<?= $Page->pjeseKodiProdhuesi->getInputTextType() ?>" name="x_pjeseKodiProdhuesi" id="x_pjeseKodiProdhuesi" data-table="pjese_kembimi" data-field="x_pjeseKodiProdhuesi" value="<?= $Page->pjeseKodiProdhuesi->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->pjeseKodiProdhuesi->getPlaceHolder()) ?>"<?= $Page->pjeseKodiProdhuesi->editAttributes() ?> aria-describedby="x_pjeseKodiProdhuesi_help">
<?= $Page->pjeseKodiProdhuesi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjeseKodiProdhuesi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjeseProdhuesi->Visible) { // pjeseProdhuesi ?>
    <div id="r_pjeseProdhuesi"<?= $Page->pjeseProdhuesi->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjeseProdhuesi" for="x_pjeseProdhuesi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjeseProdhuesi->caption() ?><?= $Page->pjeseProdhuesi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjeseProdhuesi->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseProdhuesi">
<input type="<?= $Page->pjeseProdhuesi->getInputTextType() ?>" name="x_pjeseProdhuesi" id="x_pjeseProdhuesi" data-table="pjese_kembimi" data-field="x_pjeseProdhuesi" value="<?= $Page->pjeseProdhuesi->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->pjeseProdhuesi->getPlaceHolder()) ?>"<?= $Page->pjeseProdhuesi->editAttributes() ?> aria-describedby="x_pjeseProdhuesi_help">
<?= $Page->pjeseProdhuesi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjeseProdhuesi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjesePerMarke->Visible) { // pjesePerMarke ?>
    <div id="r_pjesePerMarke"<?= $Page->pjesePerMarke->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjesePerMarke" for="x_pjesePerMarke" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjesePerMarke->caption() ?><?= $Page->pjesePerMarke->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjesePerMarke->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjesePerMarke">
<input type="<?= $Page->pjesePerMarke->getInputTextType() ?>" name="x_pjesePerMarke" id="x_pjesePerMarke" data-table="pjese_kembimi" data-field="x_pjesePerMarke" value="<?= $Page->pjesePerMarke->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->pjesePerMarke->getPlaceHolder()) ?>"<?= $Page->pjesePerMarke->editAttributes() ?> aria-describedby="x_pjesePerMarke_help">
<?= $Page->pjesePerMarke->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjesePerMarke->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjesePerModel->Visible) { // pjesePerModel ?>
    <div id="r_pjesePerModel"<?= $Page->pjesePerModel->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjesePerModel" for="x_pjesePerModel" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjesePerModel->caption() ?><?= $Page->pjesePerModel->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjesePerModel->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjesePerModel">
<input type="<?= $Page->pjesePerModel->getInputTextType() ?>" name="x_pjesePerModel" id="x_pjesePerModel" data-table="pjese_kembimi" data-field="x_pjesePerModel" value="<?= $Page->pjesePerModel->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->pjesePerModel->getPlaceHolder()) ?>"<?= $Page->pjesePerModel->editAttributes() ?> aria-describedby="x_pjesePerModel_help">
<?= $Page->pjesePerModel->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjesePerModel->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjesePerVitProdhimi->Visible) { // pjesePerVitProdhimi ?>
    <div id="r_pjesePerVitProdhimi"<?= $Page->pjesePerVitProdhimi->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjesePerVitProdhimi" for="x_pjesePerVitProdhimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjesePerVitProdhimi->caption() ?><?= $Page->pjesePerVitProdhimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjesePerVitProdhimi->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjesePerVitProdhimi">
<input type="<?= $Page->pjesePerVitProdhimi->getInputTextType() ?>" name="x_pjesePerVitProdhimi" id="x_pjesePerVitProdhimi" data-table="pjese_kembimi" data-field="x_pjesePerVitProdhimi" value="<?= $Page->pjesePerVitProdhimi->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->pjesePerVitProdhimi->getPlaceHolder()) ?>"<?= $Page->pjesePerVitProdhimi->editAttributes() ?> aria-describedby="x_pjesePerVitProdhimi_help">
<?= $Page->pjesePerVitProdhimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjesePerVitProdhimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjeseCmimBlerje->Visible) { // pjeseCmimBlerje ?>
    <div id="r_pjeseCmimBlerje"<?= $Page->pjeseCmimBlerje->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjeseCmimBlerje" for="x_pjeseCmimBlerje" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjeseCmimBlerje->caption() ?><?= $Page->pjeseCmimBlerje->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjeseCmimBlerje->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseCmimBlerje">
<input type="<?= $Page->pjeseCmimBlerje->getInputTextType() ?>" name="x_pjeseCmimBlerje" id="x_pjeseCmimBlerje" data-table="pjese_kembimi" data-field="x_pjeseCmimBlerje" value="<?= $Page->pjeseCmimBlerje->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->pjeseCmimBlerje->getPlaceHolder()) ?>"<?= $Page->pjeseCmimBlerje->editAttributes() ?> aria-describedby="x_pjeseCmimBlerje_help">
<?= $Page->pjeseCmimBlerje->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjeseCmimBlerje->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->pjeseCmimShitje->Visible) { // pjeseCmimShitje ?>
    <div id="r_pjeseCmimShitje"<?= $Page->pjeseCmimShitje->rowAttributes() ?>>
        <label id="elh_pjese_kembimi_pjeseCmimShitje" for="x_pjeseCmimShitje" class="<?= $Page->LeftColumnClass ?>"><?= $Page->pjeseCmimShitje->caption() ?><?= $Page->pjeseCmimShitje->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->pjeseCmimShitje->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseCmimShitje">
<input type="<?= $Page->pjeseCmimShitje->getInputTextType() ?>" name="x_pjeseCmimShitje" id="x_pjeseCmimShitje" data-table="pjese_kembimi" data-field="x_pjeseCmimShitje" value="<?= $Page->pjeseCmimShitje->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->pjeseCmimShitje->getPlaceHolder()) ?>"<?= $Page->pjeseCmimShitje->editAttributes() ?> aria-describedby="x_pjeseCmimShitje_help">
<?= $Page->pjeseCmimShitje->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->pjeseCmimShitje->getErrorMessage() ?></div>
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
    ew.addEventHandlers("pjese_kembimi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
