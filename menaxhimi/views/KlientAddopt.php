<?php

namespace PHPMaker2022\volalservice;

// Page object
$KlientAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { klient: currentTable } });
var currentForm, currentPageID;
var fklientaddopt;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fklientaddopt = new ew.Form("fklientaddopt", "addopt");
    currentPageID = ew.PAGE_ID = "addopt";
    currentForm = fklientaddopt;

    // Add fields
    var fields = currentTable.fields;
    fklientaddopt.addFields([
        ["klientTipi", [fields.klientTipi.visible && fields.klientTipi.required ? ew.Validators.required(fields.klientTipi.caption) : null], fields.klientTipi.isInvalid],
        ["klientEmertimi", [fields.klientEmertimi.visible && fields.klientEmertimi.required ? ew.Validators.required(fields.klientEmertimi.caption) : null], fields.klientEmertimi.isInvalid],
        ["klientNIPT", [fields.klientNIPT.visible && fields.klientNIPT.required ? ew.Validators.required(fields.klientNIPT.caption) : null], fields.klientNIPT.isInvalid],
        ["klientAdresa", [fields.klientAdresa.visible && fields.klientAdresa.required ? ew.Validators.required(fields.klientAdresa.caption) : null], fields.klientAdresa.isInvalid],
        ["klientQyteti", [fields.klientQyteti.visible && fields.klientQyteti.required ? ew.Validators.required(fields.klientQyteti.caption) : null], fields.klientQyteti.isInvalid],
        ["klientTel1", [fields.klientTel1.visible && fields.klientTel1.required ? ew.Validators.required(fields.klientTel1.caption) : null], fields.klientTel1.isInvalid],
        ["klientTel2", [fields.klientTel2.visible && fields.klientTel2.required ? ew.Validators.required(fields.klientTel2.caption) : null], fields.klientTel2.isInvalid],
        ["klientEmail", [fields.klientEmail.visible && fields.klientEmail.required ? ew.Validators.required(fields.klientEmail.caption) : null, ew.Validators.email], fields.klientEmail.isInvalid],
        ["klientAutori", [fields.klientAutori.visible && fields.klientAutori.required ? ew.Validators.required(fields.klientAutori.caption) : null], fields.klientAutori.isInvalid],
        ["klientShtuar", [fields.klientShtuar.visible && fields.klientShtuar.required ? ew.Validators.required(fields.klientShtuar.caption) : null], fields.klientShtuar.isInvalid],
        ["klientModifikuar", [fields.klientModifikuar.visible && fields.klientModifikuar.required ? ew.Validators.required(fields.klientModifikuar.caption) : null], fields.klientModifikuar.isInvalid]
    ]);

    // Form_CustomValidate
    fklientaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fklientaddopt.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fklientaddopt.lists.klientTipi = <?= $Page->klientTipi->toClientList($Page) ?>;
    fklientaddopt.lists.klientAutori = <?= $Page->klientAutori->toClientList($Page) ?>;
    loadjs.done("fklientaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fklientaddopt" id="fklientaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="klient">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->klientTipi->Visible) { // klientTipi ?>
    <div<?= $Page->klientTipi->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->klientTipi->caption() ?><?= $Page->klientTipi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->klientTipi->cellAttributes() ?>>
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
<div class="invalid-feedback"><?= $Page->klientTipi->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientEmertimi->Visible) { // klientEmertimi ?>
    <div<?= $Page->klientEmertimi->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_klientEmertimi"><?= $Page->klientEmertimi->caption() ?><?= $Page->klientEmertimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->klientEmertimi->cellAttributes() ?>>
<input type="<?= $Page->klientEmertimi->getInputTextType() ?>" name="x_klientEmertimi" id="x_klientEmertimi" data-table="klient" data-field="x_klientEmertimi" value="<?= $Page->klientEmertimi->EditValue ?>" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->klientEmertimi->getPlaceHolder()) ?>"<?= $Page->klientEmertimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->klientEmertimi->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientNIPT->Visible) { // klientNIPT ?>
    <div<?= $Page->klientNIPT->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_klientNIPT"><?= $Page->klientNIPT->caption() ?><?= $Page->klientNIPT->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->klientNIPT->cellAttributes() ?>>
<input type="<?= $Page->klientNIPT->getInputTextType() ?>" name="x_klientNIPT" id="x_klientNIPT" data-table="klient" data-field="x_klientNIPT" value="<?= $Page->klientNIPT->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->klientNIPT->getPlaceHolder()) ?>"<?= $Page->klientNIPT->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->klientNIPT->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientAdresa->Visible) { // klientAdresa ?>
    <div<?= $Page->klientAdresa->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_klientAdresa"><?= $Page->klientAdresa->caption() ?><?= $Page->klientAdresa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->klientAdresa->cellAttributes() ?>>
<input type="<?= $Page->klientAdresa->getInputTextType() ?>" name="x_klientAdresa" id="x_klientAdresa" data-table="klient" data-field="x_klientAdresa" value="<?= $Page->klientAdresa->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->klientAdresa->getPlaceHolder()) ?>"<?= $Page->klientAdresa->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->klientAdresa->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientQyteti->Visible) { // klientQyteti ?>
    <div<?= $Page->klientQyteti->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_klientQyteti"><?= $Page->klientQyteti->caption() ?><?= $Page->klientQyteti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->klientQyteti->cellAttributes() ?>>
<input type="<?= $Page->klientQyteti->getInputTextType() ?>" name="x_klientQyteti" id="x_klientQyteti" data-table="klient" data-field="x_klientQyteti" value="<?= $Page->klientQyteti->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->klientQyteti->getPlaceHolder()) ?>"<?= $Page->klientQyteti->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->klientQyteti->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientTel1->Visible) { // klientTel1 ?>
    <div<?= $Page->klientTel1->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_klientTel1"><?= $Page->klientTel1->caption() ?><?= $Page->klientTel1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->klientTel1->cellAttributes() ?>>
<input type="<?= $Page->klientTel1->getInputTextType() ?>" name="x_klientTel1" id="x_klientTel1" data-table="klient" data-field="x_klientTel1" value="<?= $Page->klientTel1->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->klientTel1->getPlaceHolder()) ?>"<?= $Page->klientTel1->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->klientTel1->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientTel2->Visible) { // klientTel2 ?>
    <div<?= $Page->klientTel2->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_klientTel2"><?= $Page->klientTel2->caption() ?><?= $Page->klientTel2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->klientTel2->cellAttributes() ?>>
<input type="<?= $Page->klientTel2->getInputTextType() ?>" name="x_klientTel2" id="x_klientTel2" data-table="klient" data-field="x_klientTel2" value="<?= $Page->klientTel2->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->klientTel2->getPlaceHolder()) ?>"<?= $Page->klientTel2->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->klientTel2->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientEmail->Visible) { // klientEmail ?>
    <div<?= $Page->klientEmail->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_klientEmail"><?= $Page->klientEmail->caption() ?><?= $Page->klientEmail->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->klientEmail->cellAttributes() ?>>
<input type="<?= $Page->klientEmail->getInputTextType() ?>" name="x_klientEmail" id="x_klientEmail" data-table="klient" data-field="x_klientEmail" value="<?= $Page->klientEmail->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->klientEmail->getPlaceHolder()) ?>"<?= $Page->klientEmail->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->klientEmail->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->klientAutori->Visible) { // klientAutori ?>
    <input type="hidden" data-table="klient" data-field="x_klientAutori" data-hidden="1" name="x_klientAutori" id="x_klientAutori" value="<?= HtmlEncode($Page->klientAutori->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->klientShtuar->Visible) { // klientShtuar ?>
    <input type="hidden" data-table="klient" data-field="x_klientShtuar" data-hidden="1" name="x_klientShtuar" id="x_klientShtuar" value="<?= HtmlEncode($Page->klientShtuar->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->klientModifikuar->Visible) { // klientModifikuar ?>
    <input type="hidden" data-table="klient" data-field="x_klientModifikuar" data-hidden="1" name="x_klientModifikuar" id="x_klientModifikuar" value="<?= HtmlEncode($Page->klientModifikuar->CurrentValue) ?>">
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
