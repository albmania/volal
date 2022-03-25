<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina: currentTable } });
var currentForm, currentPageID;
var fmakinaaddopt;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakinaaddopt = new ew.Form("fmakinaaddopt", "addopt");
    currentPageID = ew.PAGE_ID = "addopt";
    currentForm = fmakinaaddopt;

    // Add fields
    var fields = currentTable.fields;
    fmakinaaddopt.addFields([
        ["makinaKlienti", [fields.makinaKlienti.visible && fields.makinaKlienti.required ? ew.Validators.required(fields.makinaKlienti.caption) : null], fields.makinaKlienti.isInvalid],
        ["makinaMarka", [fields.makinaMarka.visible && fields.makinaMarka.required ? ew.Validators.required(fields.makinaMarka.caption) : null], fields.makinaMarka.isInvalid],
        ["makinaModeli", [fields.makinaModeli.visible && fields.makinaModeli.required ? ew.Validators.required(fields.makinaModeli.caption) : null], fields.makinaModeli.isInvalid],
        ["makinaKarburanti", [fields.makinaKarburanti.visible && fields.makinaKarburanti.required ? ew.Validators.required(fields.makinaKarburanti.caption) : null], fields.makinaKarburanti.isInvalid],
        ["makinaMadhesiaMotorrit", [fields.makinaMadhesiaMotorrit.visible && fields.makinaMadhesiaMotorrit.required ? ew.Validators.required(fields.makinaMadhesiaMotorrit.caption) : null], fields.makinaMadhesiaMotorrit.isInvalid],
        ["makinaVitiProdhimit", [fields.makinaVitiProdhimit.visible && fields.makinaVitiProdhimit.required ? ew.Validators.required(fields.makinaVitiProdhimit.caption) : null, ew.Validators.integer], fields.makinaVitiProdhimit.isInvalid],
        ["makinaNgjyra", [fields.makinaNgjyra.visible && fields.makinaNgjyra.required ? ew.Validators.required(fields.makinaNgjyra.caption) : null], fields.makinaNgjyra.isInvalid],
        ["makinaInfoShtese", [fields.makinaInfoShtese.visible && fields.makinaInfoShtese.required ? ew.Validators.required(fields.makinaInfoShtese.caption) : null], fields.makinaInfoShtese.isInvalid],
        ["makinaVitiRegAL", [fields.makinaVitiRegAL.visible && fields.makinaVitiRegAL.required ? ew.Validators.required(fields.makinaVitiRegAL.caption) : null, ew.Validators.integer], fields.makinaVitiRegAL.isInvalid],
        ["makinaTarga", [fields.makinaTarga.visible && fields.makinaTarga.required ? ew.Validators.required(fields.makinaTarga.caption) : null], fields.makinaTarga.isInvalid],
        ["makinaNrShasie", [fields.makinaNrShasie.visible && fields.makinaNrShasie.required ? ew.Validators.required(fields.makinaNrShasie.caption) : null], fields.makinaNrShasie.isInvalid],
        ["makinaPrejardhja", [fields.makinaPrejardhja.visible && fields.makinaPrejardhja.required ? ew.Validators.required(fields.makinaPrejardhja.caption) : null], fields.makinaPrejardhja.isInvalid],
        ["makinaShiturVOLAL", [fields.makinaShiturVOLAL.visible && fields.makinaShiturVOLAL.required ? ew.Validators.required(fields.makinaShiturVOLAL.caption) : null], fields.makinaShiturVOLAL.isInvalid],
        ["makinaAutori", [fields.makinaAutori.visible && fields.makinaAutori.required ? ew.Validators.required(fields.makinaAutori.caption) : null], fields.makinaAutori.isInvalid],
        ["makinaShtuar", [fields.makinaShtuar.visible && fields.makinaShtuar.required ? ew.Validators.required(fields.makinaShtuar.caption) : null], fields.makinaShtuar.isInvalid],
        ["makinaModifikuar", [fields.makinaModifikuar.visible && fields.makinaModifikuar.required ? ew.Validators.required(fields.makinaModifikuar.caption) : null], fields.makinaModifikuar.isInvalid]
    ]);

    // Form_CustomValidate
    fmakinaaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakinaaddopt.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakinaaddopt.lists.makinaKlienti = <?= $Page->makinaKlienti->toClientList($Page) ?>;
    fmakinaaddopt.lists.makinaKarburanti = <?= $Page->makinaKarburanti->toClientList($Page) ?>;
    fmakinaaddopt.lists.makinaShiturVOLAL = <?= $Page->makinaShiturVOLAL->toClientList($Page) ?>;
    loadjs.done("fmakinaaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fmakinaaddopt" id="fmakinaaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="makina">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->makinaKlienti->Visible) { // makinaKlienti ?>
    <div<?= $Page->makinaKlienti->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaKlienti"><?= $Page->makinaKlienti->caption() ?><?= $Page->makinaKlienti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaKlienti->cellAttributes() ?>>
<div class="input-group flex-nowrap">
    <select
        id="x_makinaKlienti"
        name="x_makinaKlienti"
        class="form-select ew-select<?= $Page->makinaKlienti->isInvalidClass() ?>"
        data-select2-id="fmakinaaddopt_x_makinaKlienti"
        data-table="makina"
        data-field="x_makinaKlienti"
        data-value-separator="<?= $Page->makinaKlienti->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->makinaKlienti->getPlaceHolder()) ?>"
        <?= $Page->makinaKlienti->editAttributes() ?>>
        <?= $Page->makinaKlienti->selectOptionListHtml("x_makinaKlienti") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "klient") && !$Page->makinaKlienti->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_makinaKlienti" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->makinaKlienti->caption() ?>" data-title="<?= $Page->makinaKlienti->caption() ?>" data-ew-action="add-option" data-el="x_makinaKlienti" data-url="<?= GetUrl("KlientAddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<div class="invalid-feedback"><?= $Page->makinaKlienti->getErrorMessage() ?></div>
<?= $Page->makinaKlienti->Lookup->getParamTag($Page, "p_x_makinaKlienti") ?>
<script>
loadjs.ready("fmakinaaddopt", function() {
    var options = { name: "x_makinaKlienti", selectId: "fmakinaaddopt_x_makinaKlienti" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakinaaddopt.lists.makinaKlienti.lookupOptions.length) {
        options.data = { id: "x_makinaKlienti", form: "fmakinaaddopt" };
    } else {
        options.ajax = { id: "x_makinaKlienti", form: "fmakinaaddopt", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina.fields.makinaKlienti.selectOptions);
    ew.createSelect(options);
});
</script>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaMarka->Visible) { // makinaMarka ?>
    <div<?= $Page->makinaMarka->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaMarka"><?= $Page->makinaMarka->caption() ?><?= $Page->makinaMarka->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaMarka->cellAttributes() ?>>
<input type="<?= $Page->makinaMarka->getInputTextType() ?>" name="x_makinaMarka" id="x_makinaMarka" data-table="makina" data-field="x_makinaMarka" value="<?= $Page->makinaMarka->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->makinaMarka->getPlaceHolder()) ?>"<?= $Page->makinaMarka->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaMarka->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaModeli->Visible) { // makinaModeli ?>
    <div<?= $Page->makinaModeli->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaModeli"><?= $Page->makinaModeli->caption() ?><?= $Page->makinaModeli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaModeli->cellAttributes() ?>>
<input type="<?= $Page->makinaModeli->getInputTextType() ?>" name="x_makinaModeli" id="x_makinaModeli" data-table="makina" data-field="x_makinaModeli" value="<?= $Page->makinaModeli->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->makinaModeli->getPlaceHolder()) ?>"<?= $Page->makinaModeli->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaModeli->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaKarburanti->Visible) { // makinaKarburanti ?>
    <div<?= $Page->makinaKarburanti->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->makinaKarburanti->caption() ?><?= $Page->makinaKarburanti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaKarburanti->cellAttributes() ?>>
<template id="tp_x_makinaKarburanti">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina" data-field="x_makinaKarburanti" name="x_makinaKarburanti" id="x_makinaKarburanti"<?= $Page->makinaKarburanti->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_makinaKarburanti" class="ew-item-list"></div>
<selection-list hidden
    id="x_makinaKarburanti"
    name="x_makinaKarburanti"
    value="<?= HtmlEncode($Page->makinaKarburanti->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_makinaKarburanti"
    data-bs-target="dsl_x_makinaKarburanti"
    data-repeatcolumn="5"
    class="form-control<?= $Page->makinaKarburanti->isInvalidClass() ?>"
    data-table="makina"
    data-field="x_makinaKarburanti"
    data-value-separator="<?= $Page->makinaKarburanti->displayValueSeparatorAttribute() ?>"
    <?= $Page->makinaKarburanti->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->makinaKarburanti->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaMadhesiaMotorrit->Visible) { // makinaMadhesiaMotorrit ?>
    <div<?= $Page->makinaMadhesiaMotorrit->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaMadhesiaMotorrit"><?= $Page->makinaMadhesiaMotorrit->caption() ?><?= $Page->makinaMadhesiaMotorrit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaMadhesiaMotorrit->cellAttributes() ?>>
<input type="<?= $Page->makinaMadhesiaMotorrit->getInputTextType() ?>" name="x_makinaMadhesiaMotorrit" id="x_makinaMadhesiaMotorrit" data-table="makina" data-field="x_makinaMadhesiaMotorrit" value="<?= $Page->makinaMadhesiaMotorrit->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->makinaMadhesiaMotorrit->getPlaceHolder()) ?>"<?= $Page->makinaMadhesiaMotorrit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaMadhesiaMotorrit->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaVitiProdhimit->Visible) { // makinaVitiProdhimit ?>
    <div<?= $Page->makinaVitiProdhimit->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaVitiProdhimit"><?= $Page->makinaVitiProdhimit->caption() ?><?= $Page->makinaVitiProdhimit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaVitiProdhimit->cellAttributes() ?>>
<input type="<?= $Page->makinaVitiProdhimit->getInputTextType() ?>" name="x_makinaVitiProdhimit" id="x_makinaVitiProdhimit" data-table="makina" data-field="x_makinaVitiProdhimit" value="<?= $Page->makinaVitiProdhimit->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->makinaVitiProdhimit->getPlaceHolder()) ?>"<?= $Page->makinaVitiProdhimit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaVitiProdhimit->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaNgjyra->Visible) { // makinaNgjyra ?>
    <div<?= $Page->makinaNgjyra->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaNgjyra"><?= $Page->makinaNgjyra->caption() ?><?= $Page->makinaNgjyra->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaNgjyra->cellAttributes() ?>>
<input type="<?= $Page->makinaNgjyra->getInputTextType() ?>" name="x_makinaNgjyra" id="x_makinaNgjyra" data-table="makina" data-field="x_makinaNgjyra" value="<?= $Page->makinaNgjyra->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->makinaNgjyra->getPlaceHolder()) ?>"<?= $Page->makinaNgjyra->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaNgjyra->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaInfoShtese->Visible) { // makinaInfoShtese ?>
    <div<?= $Page->makinaInfoShtese->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaInfoShtese"><?= $Page->makinaInfoShtese->caption() ?><?= $Page->makinaInfoShtese->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaInfoShtese->cellAttributes() ?>>
<textarea data-table="makina" data-field="x_makinaInfoShtese" name="x_makinaInfoShtese" id="x_makinaInfoShtese" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->makinaInfoShtese->getPlaceHolder()) ?>"<?= $Page->makinaInfoShtese->editAttributes() ?>><?= $Page->makinaInfoShtese->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->makinaInfoShtese->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaVitiRegAL->Visible) { // makinaVitiRegAL ?>
    <div<?= $Page->makinaVitiRegAL->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaVitiRegAL"><?= $Page->makinaVitiRegAL->caption() ?><?= $Page->makinaVitiRegAL->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaVitiRegAL->cellAttributes() ?>>
<input type="<?= $Page->makinaVitiRegAL->getInputTextType() ?>" name="x_makinaVitiRegAL" id="x_makinaVitiRegAL" data-table="makina" data-field="x_makinaVitiRegAL" value="<?= $Page->makinaVitiRegAL->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->makinaVitiRegAL->getPlaceHolder()) ?>"<?= $Page->makinaVitiRegAL->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaVitiRegAL->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaTarga->Visible) { // makinaTarga ?>
    <div<?= $Page->makinaTarga->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaTarga"><?= $Page->makinaTarga->caption() ?><?= $Page->makinaTarga->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaTarga->cellAttributes() ?>>
<input type="<?= $Page->makinaTarga->getInputTextType() ?>" name="x_makinaTarga" id="x_makinaTarga" data-table="makina" data-field="x_makinaTarga" value="<?= $Page->makinaTarga->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->makinaTarga->getPlaceHolder()) ?>"<?= $Page->makinaTarga->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaTarga->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaNrShasie->Visible) { // makinaNrShasie ?>
    <div<?= $Page->makinaNrShasie->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaNrShasie"><?= $Page->makinaNrShasie->caption() ?><?= $Page->makinaNrShasie->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaNrShasie->cellAttributes() ?>>
<input type="<?= $Page->makinaNrShasie->getInputTextType() ?>" name="x_makinaNrShasie" id="x_makinaNrShasie" data-table="makina" data-field="x_makinaNrShasie" value="<?= $Page->makinaNrShasie->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->makinaNrShasie->getPlaceHolder()) ?>"<?= $Page->makinaNrShasie->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaNrShasie->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaPrejardhja->Visible) { // makinaPrejardhja ?>
    <div<?= $Page->makinaPrejardhja->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_makinaPrejardhja"><?= $Page->makinaPrejardhja->caption() ?><?= $Page->makinaPrejardhja->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaPrejardhja->cellAttributes() ?>>
<input type="<?= $Page->makinaPrejardhja->getInputTextType() ?>" name="x_makinaPrejardhja" id="x_makinaPrejardhja" data-table="makina" data-field="x_makinaPrejardhja" value="<?= $Page->makinaPrejardhja->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->makinaPrejardhja->getPlaceHolder()) ?>"<?= $Page->makinaPrejardhja->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaPrejardhja->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaShiturVOLAL->Visible) { // makinaShiturVOLAL ?>
    <div<?= $Page->makinaShiturVOLAL->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->makinaShiturVOLAL->caption() ?><?= $Page->makinaShiturVOLAL->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->makinaShiturVOLAL->cellAttributes() ?>>
<template id="tp_x_makinaShiturVOLAL">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina" data-field="x_makinaShiturVOLAL" name="x_makinaShiturVOLAL" id="x_makinaShiturVOLAL"<?= $Page->makinaShiturVOLAL->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_makinaShiturVOLAL" class="ew-item-list"></div>
<selection-list hidden
    id="x_makinaShiturVOLAL"
    name="x_makinaShiturVOLAL"
    value="<?= HtmlEncode($Page->makinaShiturVOLAL->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_makinaShiturVOLAL"
    data-bs-target="dsl_x_makinaShiturVOLAL"
    data-repeatcolumn="5"
    class="form-control<?= $Page->makinaShiturVOLAL->isInvalidClass() ?>"
    data-table="makina"
    data-field="x_makinaShiturVOLAL"
    data-value-separator="<?= $Page->makinaShiturVOLAL->displayValueSeparatorAttribute() ?>"
    <?= $Page->makinaShiturVOLAL->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->makinaShiturVOLAL->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaAutori->Visible) { // makinaAutori ?>
    <input type="hidden" data-table="makina" data-field="x_makinaAutori" data-hidden="1" name="x_makinaAutori" id="x_makinaAutori" value="<?= HtmlEncode($Page->makinaAutori->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->makinaShtuar->Visible) { // makinaShtuar ?>
    <input type="hidden" data-table="makina" data-field="x_makinaShtuar" data-hidden="1" name="x_makinaShtuar" id="x_makinaShtuar" value="<?= HtmlEncode($Page->makinaShtuar->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->makinaModifikuar->Visible) { // makinaModifikuar ?>
    <input type="hidden" data-table="makina" data-field="x_makinaModifikuar" data-hidden="1" name="x_makinaModifikuar" id="x_makinaModifikuar" value="<?= HtmlEncode($Page->makinaModifikuar->CurrentValue) ?>">
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("makina");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
