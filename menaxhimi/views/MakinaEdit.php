<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina: currentTable } });
var currentForm, currentPageID;
var fmakinaedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakinaedit = new ew.Form("fmakinaedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fmakinaedit;

    // Add fields
    var fields = currentTable.fields;
    fmakinaedit.addFields([
        ["makinaID", [fields.makinaID.visible && fields.makinaID.required ? ew.Validators.required(fields.makinaID.caption) : null], fields.makinaID.isInvalid],
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
        ["makinaModifikuar", [fields.makinaModifikuar.visible && fields.makinaModifikuar.required ? ew.Validators.required(fields.makinaModifikuar.caption) : null], fields.makinaModifikuar.isInvalid]
    ]);

    // Form_CustomValidate
    fmakinaedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakinaedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakinaedit.lists.makinaKlienti = <?= $Page->makinaKlienti->toClientList($Page) ?>;
    fmakinaedit.lists.makinaKarburanti = <?= $Page->makinaKarburanti->toClientList($Page) ?>;
    fmakinaedit.lists.makinaShiturVOLAL = <?= $Page->makinaShiturVOLAL->toClientList($Page) ?>;
    loadjs.done("fmakinaedit");
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
<form name="fmakinaedit" id="fmakinaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->makinaID->Visible) { // makinaID ?>
    <div id="r_makinaID"<?= $Page->makinaID->rowAttributes() ?>>
        <label id="elh_makina_makinaID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaID->caption() ?><?= $Page->makinaID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaID->cellAttributes() ?>>
<span id="el_makina_makinaID">
<span<?= $Page->makinaID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->makinaID->getDisplayValue($Page->makinaID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina" data-field="x_makinaID" data-hidden="1" name="x_makinaID" id="x_makinaID" value="<?= HtmlEncode($Page->makinaID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaKlienti->Visible) { // makinaKlienti ?>
    <div id="r_makinaKlienti"<?= $Page->makinaKlienti->rowAttributes() ?>>
        <label id="elh_makina_makinaKlienti" for="x_makinaKlienti" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaKlienti->caption() ?><?= $Page->makinaKlienti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaKlienti->cellAttributes() ?>>
<span id="el_makina_makinaKlienti">
<div class="input-group flex-nowrap">
    <select
        id="x_makinaKlienti"
        name="x_makinaKlienti"
        class="form-select ew-select<?= $Page->makinaKlienti->isInvalidClass() ?>"
        data-select2-id="fmakinaedit_x_makinaKlienti"
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
<?= $Page->makinaKlienti->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaKlienti->getErrorMessage() ?></div>
<?= $Page->makinaKlienti->Lookup->getParamTag($Page, "p_x_makinaKlienti") ?>
<script>
loadjs.ready("fmakinaedit", function() {
    var options = { name: "x_makinaKlienti", selectId: "fmakinaedit_x_makinaKlienti" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakinaedit.lists.makinaKlienti.lookupOptions.length) {
        options.data = { id: "x_makinaKlienti", form: "fmakinaedit" };
    } else {
        options.ajax = { id: "x_makinaKlienti", form: "fmakinaedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina.fields.makinaKlienti.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaMarka->Visible) { // makinaMarka ?>
    <div id="r_makinaMarka"<?= $Page->makinaMarka->rowAttributes() ?>>
        <label id="elh_makina_makinaMarka" for="x_makinaMarka" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaMarka->caption() ?><?= $Page->makinaMarka->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaMarka->cellAttributes() ?>>
<span id="el_makina_makinaMarka">
<input type="<?= $Page->makinaMarka->getInputTextType() ?>" name="x_makinaMarka" id="x_makinaMarka" data-table="makina" data-field="x_makinaMarka" value="<?= $Page->makinaMarka->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->makinaMarka->getPlaceHolder()) ?>"<?= $Page->makinaMarka->editAttributes() ?> aria-describedby="x_makinaMarka_help">
<?= $Page->makinaMarka->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaMarka->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaModeli->Visible) { // makinaModeli ?>
    <div id="r_makinaModeli"<?= $Page->makinaModeli->rowAttributes() ?>>
        <label id="elh_makina_makinaModeli" for="x_makinaModeli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaModeli->caption() ?><?= $Page->makinaModeli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaModeli->cellAttributes() ?>>
<span id="el_makina_makinaModeli">
<input type="<?= $Page->makinaModeli->getInputTextType() ?>" name="x_makinaModeli" id="x_makinaModeli" data-table="makina" data-field="x_makinaModeli" value="<?= $Page->makinaModeli->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->makinaModeli->getPlaceHolder()) ?>"<?= $Page->makinaModeli->editAttributes() ?> aria-describedby="x_makinaModeli_help">
<?= $Page->makinaModeli->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaModeli->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaKarburanti->Visible) { // makinaKarburanti ?>
    <div id="r_makinaKarburanti"<?= $Page->makinaKarburanti->rowAttributes() ?>>
        <label id="elh_makina_makinaKarburanti" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaKarburanti->caption() ?><?= $Page->makinaKarburanti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaKarburanti->cellAttributes() ?>>
<span id="el_makina_makinaKarburanti">
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
<?= $Page->makinaKarburanti->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaKarburanti->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaMadhesiaMotorrit->Visible) { // makinaMadhesiaMotorrit ?>
    <div id="r_makinaMadhesiaMotorrit"<?= $Page->makinaMadhesiaMotorrit->rowAttributes() ?>>
        <label id="elh_makina_makinaMadhesiaMotorrit" for="x_makinaMadhesiaMotorrit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaMadhesiaMotorrit->caption() ?><?= $Page->makinaMadhesiaMotorrit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaMadhesiaMotorrit->cellAttributes() ?>>
<span id="el_makina_makinaMadhesiaMotorrit">
<input type="<?= $Page->makinaMadhesiaMotorrit->getInputTextType() ?>" name="x_makinaMadhesiaMotorrit" id="x_makinaMadhesiaMotorrit" data-table="makina" data-field="x_makinaMadhesiaMotorrit" value="<?= $Page->makinaMadhesiaMotorrit->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->makinaMadhesiaMotorrit->getPlaceHolder()) ?>"<?= $Page->makinaMadhesiaMotorrit->editAttributes() ?> aria-describedby="x_makinaMadhesiaMotorrit_help">
<?= $Page->makinaMadhesiaMotorrit->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaMadhesiaMotorrit->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaVitiProdhimit->Visible) { // makinaVitiProdhimit ?>
    <div id="r_makinaVitiProdhimit"<?= $Page->makinaVitiProdhimit->rowAttributes() ?>>
        <label id="elh_makina_makinaVitiProdhimit" for="x_makinaVitiProdhimit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaVitiProdhimit->caption() ?><?= $Page->makinaVitiProdhimit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaVitiProdhimit->cellAttributes() ?>>
<span id="el_makina_makinaVitiProdhimit">
<input type="<?= $Page->makinaVitiProdhimit->getInputTextType() ?>" name="x_makinaVitiProdhimit" id="x_makinaVitiProdhimit" data-table="makina" data-field="x_makinaVitiProdhimit" value="<?= $Page->makinaVitiProdhimit->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->makinaVitiProdhimit->getPlaceHolder()) ?>"<?= $Page->makinaVitiProdhimit->editAttributes() ?> aria-describedby="x_makinaVitiProdhimit_help">
<?= $Page->makinaVitiProdhimit->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaVitiProdhimit->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaNgjyra->Visible) { // makinaNgjyra ?>
    <div id="r_makinaNgjyra"<?= $Page->makinaNgjyra->rowAttributes() ?>>
        <label id="elh_makina_makinaNgjyra" for="x_makinaNgjyra" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaNgjyra->caption() ?><?= $Page->makinaNgjyra->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaNgjyra->cellAttributes() ?>>
<span id="el_makina_makinaNgjyra">
<input type="<?= $Page->makinaNgjyra->getInputTextType() ?>" name="x_makinaNgjyra" id="x_makinaNgjyra" data-table="makina" data-field="x_makinaNgjyra" value="<?= $Page->makinaNgjyra->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->makinaNgjyra->getPlaceHolder()) ?>"<?= $Page->makinaNgjyra->editAttributes() ?> aria-describedby="x_makinaNgjyra_help">
<?= $Page->makinaNgjyra->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaNgjyra->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaInfoShtese->Visible) { // makinaInfoShtese ?>
    <div id="r_makinaInfoShtese"<?= $Page->makinaInfoShtese->rowAttributes() ?>>
        <label id="elh_makina_makinaInfoShtese" for="x_makinaInfoShtese" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaInfoShtese->caption() ?><?= $Page->makinaInfoShtese->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaInfoShtese->cellAttributes() ?>>
<span id="el_makina_makinaInfoShtese">
<textarea data-table="makina" data-field="x_makinaInfoShtese" name="x_makinaInfoShtese" id="x_makinaInfoShtese" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->makinaInfoShtese->getPlaceHolder()) ?>"<?= $Page->makinaInfoShtese->editAttributes() ?> aria-describedby="x_makinaInfoShtese_help"><?= $Page->makinaInfoShtese->EditValue ?></textarea>
<?= $Page->makinaInfoShtese->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaInfoShtese->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaVitiRegAL->Visible) { // makinaVitiRegAL ?>
    <div id="r_makinaVitiRegAL"<?= $Page->makinaVitiRegAL->rowAttributes() ?>>
        <label id="elh_makina_makinaVitiRegAL" for="x_makinaVitiRegAL" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaVitiRegAL->caption() ?><?= $Page->makinaVitiRegAL->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaVitiRegAL->cellAttributes() ?>>
<span id="el_makina_makinaVitiRegAL">
<input type="<?= $Page->makinaVitiRegAL->getInputTextType() ?>" name="x_makinaVitiRegAL" id="x_makinaVitiRegAL" data-table="makina" data-field="x_makinaVitiRegAL" value="<?= $Page->makinaVitiRegAL->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->makinaVitiRegAL->getPlaceHolder()) ?>"<?= $Page->makinaVitiRegAL->editAttributes() ?> aria-describedby="x_makinaVitiRegAL_help">
<?= $Page->makinaVitiRegAL->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaVitiRegAL->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaTarga->Visible) { // makinaTarga ?>
    <div id="r_makinaTarga"<?= $Page->makinaTarga->rowAttributes() ?>>
        <label id="elh_makina_makinaTarga" for="x_makinaTarga" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaTarga->caption() ?><?= $Page->makinaTarga->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaTarga->cellAttributes() ?>>
<span id="el_makina_makinaTarga">
<input type="<?= $Page->makinaTarga->getInputTextType() ?>" name="x_makinaTarga" id="x_makinaTarga" data-table="makina" data-field="x_makinaTarga" value="<?= $Page->makinaTarga->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->makinaTarga->getPlaceHolder()) ?>"<?= $Page->makinaTarga->editAttributes() ?> aria-describedby="x_makinaTarga_help">
<?= $Page->makinaTarga->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaTarga->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaNrShasie->Visible) { // makinaNrShasie ?>
    <div id="r_makinaNrShasie"<?= $Page->makinaNrShasie->rowAttributes() ?>>
        <label id="elh_makina_makinaNrShasie" for="x_makinaNrShasie" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaNrShasie->caption() ?><?= $Page->makinaNrShasie->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaNrShasie->cellAttributes() ?>>
<span id="el_makina_makinaNrShasie">
<input type="<?= $Page->makinaNrShasie->getInputTextType() ?>" name="x_makinaNrShasie" id="x_makinaNrShasie" data-table="makina" data-field="x_makinaNrShasie" value="<?= $Page->makinaNrShasie->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->makinaNrShasie->getPlaceHolder()) ?>"<?= $Page->makinaNrShasie->editAttributes() ?> aria-describedby="x_makinaNrShasie_help">
<?= $Page->makinaNrShasie->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaNrShasie->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaPrejardhja->Visible) { // makinaPrejardhja ?>
    <div id="r_makinaPrejardhja"<?= $Page->makinaPrejardhja->rowAttributes() ?>>
        <label id="elh_makina_makinaPrejardhja" for="x_makinaPrejardhja" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaPrejardhja->caption() ?><?= $Page->makinaPrejardhja->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaPrejardhja->cellAttributes() ?>>
<span id="el_makina_makinaPrejardhja">
<input type="<?= $Page->makinaPrejardhja->getInputTextType() ?>" name="x_makinaPrejardhja" id="x_makinaPrejardhja" data-table="makina" data-field="x_makinaPrejardhja" value="<?= $Page->makinaPrejardhja->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->makinaPrejardhja->getPlaceHolder()) ?>"<?= $Page->makinaPrejardhja->editAttributes() ?> aria-describedby="x_makinaPrejardhja_help">
<?= $Page->makinaPrejardhja->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaPrejardhja->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->makinaShiturVOLAL->Visible) { // makinaShiturVOLAL ?>
    <div id="r_makinaShiturVOLAL"<?= $Page->makinaShiturVOLAL->rowAttributes() ?>>
        <label id="elh_makina_makinaShiturVOLAL" class="<?= $Page->LeftColumnClass ?>"><?= $Page->makinaShiturVOLAL->caption() ?><?= $Page->makinaShiturVOLAL->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->makinaShiturVOLAL->cellAttributes() ?>>
<span id="el_makina_makinaShiturVOLAL">
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
<?= $Page->makinaShiturVOLAL->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->makinaShiturVOLAL->getErrorMessage() ?></div>
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
    ew.addEventHandlers("makina");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
