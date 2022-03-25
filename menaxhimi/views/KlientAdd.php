<?php

namespace PHPMaker2022\volalservice;

// Page object
$KlientAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { klient: currentTable } });
var currentForm, currentPageID;
var fklientadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fklientadd = new ew.Form("fklientadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fklientadd;

    // Add fields
    var fields = currentTable.fields;
    fklientadd.addFields([
        ["klientTipi", [fields.klientTipi.visible && fields.klientTipi.required ? ew.Validators.required(fields.klientTipi.caption) : null], fields.klientTipi.isInvalid],
        ["klientEmertimi", [fields.klientEmertimi.visible && fields.klientEmertimi.required ? ew.Validators.required(fields.klientEmertimi.caption) : null], fields.klientEmertimi.isInvalid],
        ["klientNIPT", [fields.klientNIPT.visible && fields.klientNIPT.required ? ew.Validators.required(fields.klientNIPT.caption) : null], fields.klientNIPT.isInvalid],
        ["klientAdresa", [fields.klientAdresa.visible && fields.klientAdresa.required ? ew.Validators.required(fields.klientAdresa.caption) : null], fields.klientAdresa.isInvalid],
        ["klientQyteti", [fields.klientQyteti.visible && fields.klientQyteti.required ? ew.Validators.required(fields.klientQyteti.caption) : null], fields.klientQyteti.isInvalid],
        ["klientTel1", [fields.klientTel1.visible && fields.klientTel1.required ? ew.Validators.required(fields.klientTel1.caption) : null], fields.klientTel1.isInvalid],
        ["klientTel2", [fields.klientTel2.visible && fields.klientTel2.required ? ew.Validators.required(fields.klientTel2.caption) : null], fields.klientTel2.isInvalid],
        ["klientEmail", [fields.klientEmail.visible && fields.klientEmail.required ? ew.Validators.required(fields.klientEmail.caption) : null, ew.Validators.email], fields.klientEmail.isInvalid],
        ["klientAutori", [fields.klientAutori.visible && fields.klientAutori.required ? ew.Validators.required(fields.klientAutori.caption) : null], fields.klientAutori.isInvalid],
        ["klientShtuar", [fields.klientShtuar.visible && fields.klientShtuar.required ? ew.Validators.required(fields.klientShtuar.caption) : null], fields.klientShtuar.isInvalid]
    ]);

    // Form_CustomValidate
    fklientadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fklientadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fklientadd.lists.klientTipi = <?= $Page->klientTipi->toClientList($Page) ?>;
    fklientadd.lists.klientAutori = <?= $Page->klientAutori->toClientList($Page) ?>;
    loadjs.done("fklientadd");
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
<form name="fklientadd" id="fklientadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="klient">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->klientTipi->Visible) { // klientTipi ?>
    <div id="r_klientTipi"<?= $Page->klientTipi->rowAttributes() ?>>
        <label id="elh_klient_klientTipi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->klientTipi->caption() ?><?= $Page->klientTipi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->klientTipi->cellAttributes() ?>>
<span id="el_klient_klientTipi">
<template id="tp_x_klientTipi">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="klient" data-field="x_klientTipi" name="x_klientTipi" id="x_klientTipi"<?= $Page->klientTipi->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_klientTipi" class="ew-item-list"></div>
<selection-list hidden
    id="x_klientTipi"
    name="x_klientTipi"
    value="<?= HtmlEncode($Page->klientTipi->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_klientTipi"
    data-bs-target="dsl_x_klientTipi"
    data-repeatcolumn="5"
    class="form-control<?= $Page->klientTipi->isInvalidClass() ?>"
    data-table="klient"
    data-field="x_klientTipi"
    data-value-separator="<?= $Page->klientTipi->displayValueSeparatorAttribute() ?>"
    <?= $Page->klientTipi->editAttributes() ?>></selection-list>
<?= $Page->klientTipi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->klientTipi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientEmertimi->Visible) { // klientEmertimi ?>
    <div id="r_klientEmertimi"<?= $Page->klientEmertimi->rowAttributes() ?>>
        <label id="elh_klient_klientEmertimi" for="x_klientEmertimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->klientEmertimi->caption() ?><?= $Page->klientEmertimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->klientEmertimi->cellAttributes() ?>>
<span id="el_klient_klientEmertimi">
<input type="<?= $Page->klientEmertimi->getInputTextType() ?>" name="x_klientEmertimi" id="x_klientEmertimi" data-table="klient" data-field="x_klientEmertimi" value="<?= $Page->klientEmertimi->EditValue ?>" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->klientEmertimi->getPlaceHolder()) ?>"<?= $Page->klientEmertimi->editAttributes() ?> aria-describedby="x_klientEmertimi_help">
<?= $Page->klientEmertimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->klientEmertimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientNIPT->Visible) { // klientNIPT ?>
    <div id="r_klientNIPT"<?= $Page->klientNIPT->rowAttributes() ?>>
        <label id="elh_klient_klientNIPT" for="x_klientNIPT" class="<?= $Page->LeftColumnClass ?>"><?= $Page->klientNIPT->caption() ?><?= $Page->klientNIPT->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->klientNIPT->cellAttributes() ?>>
<span id="el_klient_klientNIPT">
<input type="<?= $Page->klientNIPT->getInputTextType() ?>" name="x_klientNIPT" id="x_klientNIPT" data-table="klient" data-field="x_klientNIPT" value="<?= $Page->klientNIPT->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->klientNIPT->getPlaceHolder()) ?>"<?= $Page->klientNIPT->editAttributes() ?> aria-describedby="x_klientNIPT_help">
<?= $Page->klientNIPT->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->klientNIPT->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientAdresa->Visible) { // klientAdresa ?>
    <div id="r_klientAdresa"<?= $Page->klientAdresa->rowAttributes() ?>>
        <label id="elh_klient_klientAdresa" for="x_klientAdresa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->klientAdresa->caption() ?><?= $Page->klientAdresa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->klientAdresa->cellAttributes() ?>>
<span id="el_klient_klientAdresa">
<input type="<?= $Page->klientAdresa->getInputTextType() ?>" name="x_klientAdresa" id="x_klientAdresa" data-table="klient" data-field="x_klientAdresa" value="<?= $Page->klientAdresa->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->klientAdresa->getPlaceHolder()) ?>"<?= $Page->klientAdresa->editAttributes() ?> aria-describedby="x_klientAdresa_help">
<?= $Page->klientAdresa->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->klientAdresa->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientQyteti->Visible) { // klientQyteti ?>
    <div id="r_klientQyteti"<?= $Page->klientQyteti->rowAttributes() ?>>
        <label id="elh_klient_klientQyteti" for="x_klientQyteti" class="<?= $Page->LeftColumnClass ?>"><?= $Page->klientQyteti->caption() ?><?= $Page->klientQyteti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->klientQyteti->cellAttributes() ?>>
<span id="el_klient_klientQyteti">
<input type="<?= $Page->klientQyteti->getInputTextType() ?>" name="x_klientQyteti" id="x_klientQyteti" data-table="klient" data-field="x_klientQyteti" value="<?= $Page->klientQyteti->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->klientQyteti->getPlaceHolder()) ?>"<?= $Page->klientQyteti->editAttributes() ?> aria-describedby="x_klientQyteti_help">
<?= $Page->klientQyteti->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->klientQyteti->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientTel1->Visible) { // klientTel1 ?>
    <div id="r_klientTel1"<?= $Page->klientTel1->rowAttributes() ?>>
        <label id="elh_klient_klientTel1" for="x_klientTel1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->klientTel1->caption() ?><?= $Page->klientTel1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->klientTel1->cellAttributes() ?>>
<span id="el_klient_klientTel1">
<input type="<?= $Page->klientTel1->getInputTextType() ?>" name="x_klientTel1" id="x_klientTel1" data-table="klient" data-field="x_klientTel1" value="<?= $Page->klientTel1->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->klientTel1->getPlaceHolder()) ?>"<?= $Page->klientTel1->editAttributes() ?> aria-describedby="x_klientTel1_help">
<?= $Page->klientTel1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->klientTel1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientTel2->Visible) { // klientTel2 ?>
    <div id="r_klientTel2"<?= $Page->klientTel2->rowAttributes() ?>>
        <label id="elh_klient_klientTel2" for="x_klientTel2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->klientTel2->caption() ?><?= $Page->klientTel2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->klientTel2->cellAttributes() ?>>
<span id="el_klient_klientTel2">
<input type="<?= $Page->klientTel2->getInputTextType() ?>" name="x_klientTel2" id="x_klientTel2" data-table="klient" data-field="x_klientTel2" value="<?= $Page->klientTel2->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->klientTel2->getPlaceHolder()) ?>"<?= $Page->klientTel2->editAttributes() ?> aria-describedby="x_klientTel2_help">
<?= $Page->klientTel2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->klientTel2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientEmail->Visible) { // klientEmail ?>
    <div id="r_klientEmail"<?= $Page->klientEmail->rowAttributes() ?>>
        <label id="elh_klient_klientEmail" for="x_klientEmail" class="<?= $Page->LeftColumnClass ?>"><?= $Page->klientEmail->caption() ?><?= $Page->klientEmail->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->klientEmail->cellAttributes() ?>>
<span id="el_klient_klientEmail">
<input type="<?= $Page->klientEmail->getInputTextType() ?>" name="x_klientEmail" id="x_klientEmail" data-table="klient" data-field="x_klientEmail" value="<?= $Page->klientEmail->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->klientEmail->getPlaceHolder()) ?>"<?= $Page->klientEmail->editAttributes() ?> aria-describedby="x_klientEmail_help">
<?= $Page->klientEmail->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->klientEmail->getErrorMessage() ?></div>
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
    ew.addEventHandlers("klient");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
