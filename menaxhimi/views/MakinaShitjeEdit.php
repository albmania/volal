<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaShitjeEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_shitje: currentTable } });
var currentForm, currentPageID;
var fmakina_shitjeedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_shitjeedit = new ew.Form("fmakina_shitjeedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fmakina_shitjeedit;

    // Add fields
    var fields = currentTable.fields;
    fmakina_shitjeedit.addFields([
        ["mshitjeID", [fields.mshitjeID.visible && fields.mshitjeID.required ? ew.Validators.required(fields.mshitjeID.caption) : null], fields.mshitjeID.isInvalid],
        ["mshitjeMarka", [fields.mshitjeMarka.visible && fields.mshitjeMarka.required ? ew.Validators.required(fields.mshitjeMarka.caption) : null], fields.mshitjeMarka.isInvalid],
        ["mshitjeModeli", [fields.mshitjeModeli.visible && fields.mshitjeModeli.required ? ew.Validators.required(fields.mshitjeModeli.caption) : null], fields.mshitjeModeli.isInvalid],
        ["mshitjeTipi", [fields.mshitjeTipi.visible && fields.mshitjeTipi.required ? ew.Validators.required(fields.mshitjeTipi.caption) : null], fields.mshitjeTipi.isInvalid],
        ["mshitjeStruktura", [fields.mshitjeStruktura.visible && fields.mshitjeStruktura.required ? ew.Validators.required(fields.mshitjeStruktura.caption) : null], fields.mshitjeStruktura.isInvalid],
        ["mshitjeKapacitetiMotorrit", [fields.mshitjeKapacitetiMotorrit.visible && fields.mshitjeKapacitetiMotorrit.required ? ew.Validators.required(fields.mshitjeKapacitetiMotorrit.caption) : null], fields.mshitjeKapacitetiMotorrit.isInvalid],
        ["mshitjeVitiProdhimit", [fields.mshitjeVitiProdhimit.visible && fields.mshitjeVitiProdhimit.required ? ew.Validators.required(fields.mshitjeVitiProdhimit.caption) : null, ew.Validators.integer], fields.mshitjeVitiProdhimit.isInvalid],
        ["mshitjeKarburant", [fields.mshitjeKarburant.visible && fields.mshitjeKarburant.required ? ew.Validators.required(fields.mshitjeKarburant.caption) : null], fields.mshitjeKarburant.isInvalid],
        ["mshitjeNgjyra", [fields.mshitjeNgjyra.visible && fields.mshitjeNgjyra.required ? ew.Validators.required(fields.mshitjeNgjyra.caption) : null], fields.mshitjeNgjyra.isInvalid],
        ["mshitjeNrVendeve", [fields.mshitjeNrVendeve.visible && fields.mshitjeNrVendeve.required ? ew.Validators.required(fields.mshitjeNrVendeve.caption) : null], fields.mshitjeNrVendeve.isInvalid],
        ["mshitjeKambio", [fields.mshitjeKambio.visible && fields.mshitjeKambio.required ? ew.Validators.required(fields.mshitjeKambio.caption) : null], fields.mshitjeKambio.isInvalid],
        ["mshitjePrejardhja", [fields.mshitjePrejardhja.visible && fields.mshitjePrejardhja.required ? ew.Validators.required(fields.mshitjePrejardhja.caption) : null], fields.mshitjePrejardhja.isInvalid],
        ["mshitjeTargaAL", [fields.mshitjeTargaAL.visible && fields.mshitjeTargaAL.required ? ew.Validators.required(fields.mshitjeTargaAL.caption) : null], fields.mshitjeTargaAL.isInvalid],
        ["mshitjeKilometra", [fields.mshitjeKilometra.visible && fields.mshitjeKilometra.required ? ew.Validators.required(fields.mshitjeKilometra.caption) : null, ew.Validators.integer], fields.mshitjeKilometra.isInvalid],
        ["mshitjeFotografi", [fields.mshitjeFotografi.visible && fields.mshitjeFotografi.required ? ew.Validators.fileRequired(fields.mshitjeFotografi.caption) : null], fields.mshitjeFotografi.isInvalid],
        ["mshitjePershkrimi", [fields.mshitjePershkrimi.visible && fields.mshitjePershkrimi.required ? ew.Validators.required(fields.mshitjePershkrimi.caption) : null], fields.mshitjePershkrimi.isInvalid],
        ["mshitjeCmimi", [fields.mshitjeCmimi.visible && fields.mshitjeCmimi.required ? ew.Validators.required(fields.mshitjeCmimi.caption) : null], fields.mshitjeCmimi.isInvalid],
        ["mshitjeIndex", [fields.mshitjeIndex.visible && fields.mshitjeIndex.required ? ew.Validators.required(fields.mshitjeIndex.caption) : null], fields.mshitjeIndex.isInvalid],
        ["mshitjePromo", [fields.mshitjePromo.visible && fields.mshitjePromo.required ? ew.Validators.required(fields.mshitjePromo.caption) : null], fields.mshitjePromo.isInvalid],
        ["mshitjeAktiv", [fields.mshitjeAktiv.visible && fields.mshitjeAktiv.required ? ew.Validators.required(fields.mshitjeAktiv.caption) : null], fields.mshitjeAktiv.isInvalid],
        ["mshitjeShitur", [fields.mshitjeShitur.visible && fields.mshitjeShitur.required ? ew.Validators.required(fields.mshitjeShitur.caption) : null], fields.mshitjeShitur.isInvalid],
        ["mshitjeAutori", [fields.mshitjeAutori.visible && fields.mshitjeAutori.required ? ew.Validators.required(fields.mshitjeAutori.caption) : null], fields.mshitjeAutori.isInvalid],
        ["mshitjeAzhornuar", [fields.mshitjeAzhornuar.visible && fields.mshitjeAzhornuar.required ? ew.Validators.required(fields.mshitjeAzhornuar.caption) : null], fields.mshitjeAzhornuar.isInvalid]
    ]);

    // Form_CustomValidate
    fmakina_shitjeedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_shitjeedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakina_shitjeedit.lists.mshitjeMarka = <?= $Page->mshitjeMarka->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeModeli = <?= $Page->mshitjeModeli->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeTipi = <?= $Page->mshitjeTipi->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeStruktura = <?= $Page->mshitjeStruktura->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeKarburant = <?= $Page->mshitjeKarburant->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeKambio = <?= $Page->mshitjeKambio->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeTargaAL = <?= $Page->mshitjeTargaAL->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeIndex = <?= $Page->mshitjeIndex->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjePromo = <?= $Page->mshitjePromo->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeAktiv = <?= $Page->mshitjeAktiv->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeShitur = <?= $Page->mshitjeShitur->toClientList($Page) ?>;
    fmakina_shitjeedit.lists.mshitjeAutori = <?= $Page->mshitjeAutori->toClientList($Page) ?>;
    loadjs.done("fmakina_shitjeedit");
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
<form name="fmakina_shitjeedit" id="fmakina_shitjeedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_shitje">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->mshitjeID->Visible) { // mshitjeID ?>
    <div id="r_mshitjeID"<?= $Page->mshitjeID->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeID->caption() ?><?= $Page->mshitjeID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeID->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeID">
<span<?= $Page->mshitjeID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->mshitjeID->getDisplayValue($Page->mshitjeID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_shitje" data-field="x_mshitjeID" data-hidden="1" name="x_mshitjeID" id="x_mshitjeID" value="<?= HtmlEncode($Page->mshitjeID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeMarka->Visible) { // mshitjeMarka ?>
    <div id="r_mshitjeMarka"<?= $Page->mshitjeMarka->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeMarka" for="x_mshitjeMarka" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeMarka->caption() ?><?= $Page->mshitjeMarka->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeMarka->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeMarka">
<?php $Page->mshitjeMarka->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mshitjeMarka"
        name="x_mshitjeMarka"
        class="form-select ew-select<?= $Page->mshitjeMarka->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjeedit_x_mshitjeMarka"
        data-table="makina_shitje"
        data-field="x_mshitjeMarka"
        data-value-separator="<?= $Page->mshitjeMarka->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeMarka->getPlaceHolder()) ?>"
        <?= $Page->mshitjeMarka->editAttributes() ?>>
        <?= $Page->mshitjeMarka->selectOptionListHtml("x_mshitjeMarka") ?>
    </select>
    <?= $Page->mshitjeMarka->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mshitjeMarka->getErrorMessage() ?></div>
<?= $Page->mshitjeMarka->Lookup->getParamTag($Page, "p_x_mshitjeMarka") ?>
<script>
loadjs.ready("fmakina_shitjeedit", function() {
    var options = { name: "x_mshitjeMarka", selectId: "fmakina_shitjeedit_x_mshitjeMarka" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjeedit.lists.mshitjeMarka.lookupOptions.length) {
        options.data = { id: "x_mshitjeMarka", form: "fmakina_shitjeedit" };
    } else {
        options.ajax = { id: "x_mshitjeMarka", form: "fmakina_shitjeedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeMarka.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeModeli->Visible) { // mshitjeModeli ?>
    <div id="r_mshitjeModeli"<?= $Page->mshitjeModeli->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeModeli" for="x_mshitjeModeli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeModeli->caption() ?><?= $Page->mshitjeModeli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeModeli->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeModeli">
<?php $Page->mshitjeModeli->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mshitjeModeli"
        name="x_mshitjeModeli"
        class="form-select ew-select<?= $Page->mshitjeModeli->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjeedit_x_mshitjeModeli"
        data-table="makina_shitje"
        data-field="x_mshitjeModeli"
        data-value-separator="<?= $Page->mshitjeModeli->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeModeli->getPlaceHolder()) ?>"
        <?= $Page->mshitjeModeli->editAttributes() ?>>
        <?= $Page->mshitjeModeli->selectOptionListHtml("x_mshitjeModeli") ?>
    </select>
    <?= $Page->mshitjeModeli->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mshitjeModeli->getErrorMessage() ?></div>
<?= $Page->mshitjeModeli->Lookup->getParamTag($Page, "p_x_mshitjeModeli") ?>
<script>
loadjs.ready("fmakina_shitjeedit", function() {
    var options = { name: "x_mshitjeModeli", selectId: "fmakina_shitjeedit_x_mshitjeModeli" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjeedit.lists.mshitjeModeli.lookupOptions.length) {
        options.data = { id: "x_mshitjeModeli", form: "fmakina_shitjeedit" };
    } else {
        options.ajax = { id: "x_mshitjeModeli", form: "fmakina_shitjeedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeModeli.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeTipi->Visible) { // mshitjeTipi ?>
    <div id="r_mshitjeTipi"<?= $Page->mshitjeTipi->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeTipi" for="x_mshitjeTipi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeTipi->caption() ?><?= $Page->mshitjeTipi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeTipi->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeTipi">
    <select
        id="x_mshitjeTipi"
        name="x_mshitjeTipi"
        class="form-select ew-select<?= $Page->mshitjeTipi->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjeedit_x_mshitjeTipi"
        data-table="makina_shitje"
        data-field="x_mshitjeTipi"
        data-value-separator="<?= $Page->mshitjeTipi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeTipi->getPlaceHolder()) ?>"
        <?= $Page->mshitjeTipi->editAttributes() ?>>
        <?= $Page->mshitjeTipi->selectOptionListHtml("x_mshitjeTipi") ?>
    </select>
    <?= $Page->mshitjeTipi->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mshitjeTipi->getErrorMessage() ?></div>
<?= $Page->mshitjeTipi->Lookup->getParamTag($Page, "p_x_mshitjeTipi") ?>
<script>
loadjs.ready("fmakina_shitjeedit", function() {
    var options = { name: "x_mshitjeTipi", selectId: "fmakina_shitjeedit_x_mshitjeTipi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjeedit.lists.mshitjeTipi.lookupOptions.length) {
        options.data = { id: "x_mshitjeTipi", form: "fmakina_shitjeedit" };
    } else {
        options.ajax = { id: "x_mshitjeTipi", form: "fmakina_shitjeedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeTipi.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeStruktura->Visible) { // mshitjeStruktura ?>
    <div id="r_mshitjeStruktura"<?= $Page->mshitjeStruktura->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeStruktura" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeStruktura->caption() ?><?= $Page->mshitjeStruktura->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeStruktura->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeStruktura">
<template id="tp_x_mshitjeStruktura">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeStruktura" name="x_mshitjeStruktura" id="x_mshitjeStruktura"<?= $Page->mshitjeStruktura->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeStruktura" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeStruktura"
    name="x_mshitjeStruktura"
    value="<?= HtmlEncode($Page->mshitjeStruktura->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeStruktura"
    data-bs-target="dsl_x_mshitjeStruktura"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeStruktura->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeStruktura"
    data-value-separator="<?= $Page->mshitjeStruktura->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeStruktura->editAttributes() ?>></selection-list>
<?= $Page->mshitjeStruktura->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeStruktura->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeKapacitetiMotorrit->Visible) { // mshitjeKapacitetiMotorrit ?>
    <div id="r_mshitjeKapacitetiMotorrit"<?= $Page->mshitjeKapacitetiMotorrit->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeKapacitetiMotorrit" for="x_mshitjeKapacitetiMotorrit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeKapacitetiMotorrit->caption() ?><?= $Page->mshitjeKapacitetiMotorrit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeKapacitetiMotorrit->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKapacitetiMotorrit">
<input type="<?= $Page->mshitjeKapacitetiMotorrit->getInputTextType() ?>" name="x_mshitjeKapacitetiMotorrit" id="x_mshitjeKapacitetiMotorrit" data-table="makina_shitje" data-field="x_mshitjeKapacitetiMotorrit" value="<?= $Page->mshitjeKapacitetiMotorrit->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->mshitjeKapacitetiMotorrit->getPlaceHolder()) ?>"<?= $Page->mshitjeKapacitetiMotorrit->editAttributes() ?> aria-describedby="x_mshitjeKapacitetiMotorrit_help">
<?= $Page->mshitjeKapacitetiMotorrit->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeKapacitetiMotorrit->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeVitiProdhimit->Visible) { // mshitjeVitiProdhimit ?>
    <div id="r_mshitjeVitiProdhimit"<?= $Page->mshitjeVitiProdhimit->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeVitiProdhimit" for="x_mshitjeVitiProdhimit" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeVitiProdhimit->caption() ?><?= $Page->mshitjeVitiProdhimit->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeVitiProdhimit->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeVitiProdhimit">
<input type="<?= $Page->mshitjeVitiProdhimit->getInputTextType() ?>" name="x_mshitjeVitiProdhimit" id="x_mshitjeVitiProdhimit" data-table="makina_shitje" data-field="x_mshitjeVitiProdhimit" value="<?= $Page->mshitjeVitiProdhimit->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mshitjeVitiProdhimit->getPlaceHolder()) ?>"<?= $Page->mshitjeVitiProdhimit->editAttributes() ?> aria-describedby="x_mshitjeVitiProdhimit_help">
<?= $Page->mshitjeVitiProdhimit->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeVitiProdhimit->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeKarburant->Visible) { // mshitjeKarburant ?>
    <div id="r_mshitjeKarburant"<?= $Page->mshitjeKarburant->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeKarburant" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeKarburant->caption() ?><?= $Page->mshitjeKarburant->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeKarburant->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKarburant">
<template id="tp_x_mshitjeKarburant">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeKarburant" name="x_mshitjeKarburant" id="x_mshitjeKarburant"<?= $Page->mshitjeKarburant->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeKarburant" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeKarburant"
    name="x_mshitjeKarburant"
    value="<?= HtmlEncode($Page->mshitjeKarburant->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeKarburant"
    data-bs-target="dsl_x_mshitjeKarburant"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeKarburant->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeKarburant"
    data-value-separator="<?= $Page->mshitjeKarburant->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeKarburant->editAttributes() ?>></selection-list>
<?= $Page->mshitjeKarburant->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeKarburant->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeNgjyra->Visible) { // mshitjeNgjyra ?>
    <div id="r_mshitjeNgjyra"<?= $Page->mshitjeNgjyra->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeNgjyra" for="x_mshitjeNgjyra" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeNgjyra->caption() ?><?= $Page->mshitjeNgjyra->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeNgjyra->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeNgjyra">
<input type="<?= $Page->mshitjeNgjyra->getInputTextType() ?>" name="x_mshitjeNgjyra" id="x_mshitjeNgjyra" data-table="makina_shitje" data-field="x_mshitjeNgjyra" value="<?= $Page->mshitjeNgjyra->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->mshitjeNgjyra->getPlaceHolder()) ?>"<?= $Page->mshitjeNgjyra->editAttributes() ?> aria-describedby="x_mshitjeNgjyra_help">
<?= $Page->mshitjeNgjyra->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeNgjyra->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeNrVendeve->Visible) { // mshitjeNrVendeve ?>
    <div id="r_mshitjeNrVendeve"<?= $Page->mshitjeNrVendeve->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeNrVendeve" for="x_mshitjeNrVendeve" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeNrVendeve->caption() ?><?= $Page->mshitjeNrVendeve->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeNrVendeve->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeNrVendeve">
<input type="<?= $Page->mshitjeNrVendeve->getInputTextType() ?>" name="x_mshitjeNrVendeve" id="x_mshitjeNrVendeve" data-table="makina_shitje" data-field="x_mshitjeNrVendeve" value="<?= $Page->mshitjeNrVendeve->EditValue ?>" size="30" maxlength="10" placeholder="<?= HtmlEncode($Page->mshitjeNrVendeve->getPlaceHolder()) ?>"<?= $Page->mshitjeNrVendeve->editAttributes() ?> aria-describedby="x_mshitjeNrVendeve_help">
<?= $Page->mshitjeNrVendeve->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeNrVendeve->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeKambio->Visible) { // mshitjeKambio ?>
    <div id="r_mshitjeKambio"<?= $Page->mshitjeKambio->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeKambio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeKambio->caption() ?><?= $Page->mshitjeKambio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeKambio->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKambio">
<template id="tp_x_mshitjeKambio">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeKambio" name="x_mshitjeKambio" id="x_mshitjeKambio"<?= $Page->mshitjeKambio->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeKambio" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeKambio"
    name="x_mshitjeKambio"
    value="<?= HtmlEncode($Page->mshitjeKambio->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeKambio"
    data-bs-target="dsl_x_mshitjeKambio"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeKambio->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeKambio"
    data-value-separator="<?= $Page->mshitjeKambio->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeKambio->editAttributes() ?>></selection-list>
<?= $Page->mshitjeKambio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeKambio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjePrejardhja->Visible) { // mshitjePrejardhja ?>
    <div id="r_mshitjePrejardhja"<?= $Page->mshitjePrejardhja->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjePrejardhja" for="x_mshitjePrejardhja" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjePrejardhja->caption() ?><?= $Page->mshitjePrejardhja->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjePrejardhja->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjePrejardhja">
<input type="<?= $Page->mshitjePrejardhja->getInputTextType() ?>" name="x_mshitjePrejardhja" id="x_mshitjePrejardhja" data-table="makina_shitje" data-field="x_mshitjePrejardhja" value="<?= $Page->mshitjePrejardhja->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->mshitjePrejardhja->getPlaceHolder()) ?>"<?= $Page->mshitjePrejardhja->editAttributes() ?> aria-describedby="x_mshitjePrejardhja_help">
<?= $Page->mshitjePrejardhja->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjePrejardhja->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeTargaAL->Visible) { // mshitjeTargaAL ?>
    <div id="r_mshitjeTargaAL"<?= $Page->mshitjeTargaAL->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeTargaAL" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeTargaAL->caption() ?><?= $Page->mshitjeTargaAL->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeTargaAL->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeTargaAL">
<template id="tp_x_mshitjeTargaAL">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeTargaAL" name="x_mshitjeTargaAL" id="x_mshitjeTargaAL"<?= $Page->mshitjeTargaAL->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeTargaAL" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeTargaAL"
    name="x_mshitjeTargaAL"
    value="<?= HtmlEncode($Page->mshitjeTargaAL->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeTargaAL"
    data-bs-target="dsl_x_mshitjeTargaAL"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeTargaAL->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeTargaAL"
    data-value-separator="<?= $Page->mshitjeTargaAL->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeTargaAL->editAttributes() ?>></selection-list>
<?= $Page->mshitjeTargaAL->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeTargaAL->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeKilometra->Visible) { // mshitjeKilometra ?>
    <div id="r_mshitjeKilometra"<?= $Page->mshitjeKilometra->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeKilometra" for="x_mshitjeKilometra" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeKilometra->caption() ?><?= $Page->mshitjeKilometra->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeKilometra->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKilometra">
<input type="<?= $Page->mshitjeKilometra->getInputTextType() ?>" name="x_mshitjeKilometra" id="x_mshitjeKilometra" data-table="makina_shitje" data-field="x_mshitjeKilometra" value="<?= $Page->mshitjeKilometra->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mshitjeKilometra->getPlaceHolder()) ?>"<?= $Page->mshitjeKilometra->editAttributes() ?> aria-describedby="x_mshitjeKilometra_help">
<?= $Page->mshitjeKilometra->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeKilometra->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeFotografi->Visible) { // mshitjeFotografi ?>
    <div id="r_mshitjeFotografi"<?= $Page->mshitjeFotografi->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeFotografi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeFotografi->caption() ?><?= $Page->mshitjeFotografi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeFotografi->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeFotografi">
<div id="fd_x_mshitjeFotografi" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->mshitjeFotografi->title() ?>" data-table="makina_shitje" data-field="x_mshitjeFotografi" name="x_mshitjeFotografi" id="x_mshitjeFotografi" lang="<?= CurrentLanguageID() ?>" multiple<?= $Page->mshitjeFotografi->editAttributes() ?> aria-describedby="x_mshitjeFotografi_help"<?= ($Page->mshitjeFotografi->ReadOnly || $Page->mshitjeFotografi->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
</div>
<?= $Page->mshitjeFotografi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeFotografi->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_mshitjeFotografi" id= "fn_x_mshitjeFotografi" value="<?= $Page->mshitjeFotografi->Upload->FileName ?>">
<input type="hidden" name="fa_x_mshitjeFotografi" id= "fa_x_mshitjeFotografi" value="<?= (Post("fa_x_mshitjeFotografi") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_mshitjeFotografi" id= "fs_x_mshitjeFotografi" value="65535">
<input type="hidden" name="fx_x_mshitjeFotografi" id= "fx_x_mshitjeFotografi" value="<?= $Page->mshitjeFotografi->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_mshitjeFotografi" id= "fm_x_mshitjeFotografi" value="<?= $Page->mshitjeFotografi->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_mshitjeFotografi" id= "fc_x_mshitjeFotografi" value="<?= $Page->mshitjeFotografi->UploadMaxFileCount ?>">
<table id="ft_x_mshitjeFotografi" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjePershkrimi->Visible) { // mshitjePershkrimi ?>
    <div id="r_mshitjePershkrimi"<?= $Page->mshitjePershkrimi->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjePershkrimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjePershkrimi->caption() ?><?= $Page->mshitjePershkrimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjePershkrimi->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjePershkrimi">
<?php $Page->mshitjePershkrimi->EditAttrs->appendClass("editor"); ?>
<textarea data-table="makina_shitje" data-field="x_mshitjePershkrimi" name="x_mshitjePershkrimi" id="x_mshitjePershkrimi" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->mshitjePershkrimi->getPlaceHolder()) ?>"<?= $Page->mshitjePershkrimi->editAttributes() ?> aria-describedby="x_mshitjePershkrimi_help"><?= $Page->mshitjePershkrimi->EditValue ?></textarea>
<?= $Page->mshitjePershkrimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjePershkrimi->getErrorMessage() ?></div>
<script>
loadjs.ready(["fmakina_shitjeedit", "editor"], function() {
    ew.createEditor("fmakina_shitjeedit", "x_mshitjePershkrimi", 35, 4, <?= $Page->mshitjePershkrimi->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeCmimi->Visible) { // mshitjeCmimi ?>
    <div id="r_mshitjeCmimi"<?= $Page->mshitjeCmimi->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeCmimi" for="x_mshitjeCmimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeCmimi->caption() ?><?= $Page->mshitjeCmimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeCmimi->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeCmimi">
<input type="<?= $Page->mshitjeCmimi->getInputTextType() ?>" name="x_mshitjeCmimi" id="x_mshitjeCmimi" data-table="makina_shitje" data-field="x_mshitjeCmimi" value="<?= $Page->mshitjeCmimi->EditValue ?>" size="30" maxlength="20" placeholder="<?= HtmlEncode($Page->mshitjeCmimi->getPlaceHolder()) ?>"<?= $Page->mshitjeCmimi->editAttributes() ?> aria-describedby="x_mshitjeCmimi_help">
<?= $Page->mshitjeCmimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeCmimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeIndex->Visible) { // mshitjeIndex ?>
    <div id="r_mshitjeIndex"<?= $Page->mshitjeIndex->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeIndex" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeIndex->caption() ?><?= $Page->mshitjeIndex->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeIndex->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeIndex">
<template id="tp_x_mshitjeIndex">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeIndex" name="x_mshitjeIndex" id="x_mshitjeIndex"<?= $Page->mshitjeIndex->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeIndex" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeIndex"
    name="x_mshitjeIndex"
    value="<?= HtmlEncode($Page->mshitjeIndex->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeIndex"
    data-bs-target="dsl_x_mshitjeIndex"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeIndex->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeIndex"
    data-value-separator="<?= $Page->mshitjeIndex->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeIndex->editAttributes() ?>></selection-list>
<?= $Page->mshitjeIndex->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeIndex->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjePromo->Visible) { // mshitjePromo ?>
    <div id="r_mshitjePromo"<?= $Page->mshitjePromo->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjePromo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjePromo->caption() ?><?= $Page->mshitjePromo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjePromo->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjePromo">
<template id="tp_x_mshitjePromo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjePromo" name="x_mshitjePromo" id="x_mshitjePromo"<?= $Page->mshitjePromo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjePromo" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjePromo"
    name="x_mshitjePromo"
    value="<?= HtmlEncode($Page->mshitjePromo->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjePromo"
    data-bs-target="dsl_x_mshitjePromo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjePromo->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjePromo"
    data-value-separator="<?= $Page->mshitjePromo->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjePromo->editAttributes() ?>></selection-list>
<?= $Page->mshitjePromo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjePromo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeAktiv->Visible) { // mshitjeAktiv ?>
    <div id="r_mshitjeAktiv"<?= $Page->mshitjeAktiv->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeAktiv" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeAktiv->caption() ?><?= $Page->mshitjeAktiv->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeAktiv->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeAktiv">
<template id="tp_x_mshitjeAktiv">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeAktiv" name="x_mshitjeAktiv" id="x_mshitjeAktiv"<?= $Page->mshitjeAktiv->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeAktiv" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeAktiv"
    name="x_mshitjeAktiv"
    value="<?= HtmlEncode($Page->mshitjeAktiv->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeAktiv"
    data-bs-target="dsl_x_mshitjeAktiv"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeAktiv->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeAktiv"
    data-value-separator="<?= $Page->mshitjeAktiv->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeAktiv->editAttributes() ?>></selection-list>
<?= $Page->mshitjeAktiv->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeAktiv->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mshitjeShitur->Visible) { // mshitjeShitur ?>
    <div id="r_mshitjeShitur"<?= $Page->mshitjeShitur->rowAttributes() ?>>
        <label id="elh_makina_shitje_mshitjeShitur" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mshitjeShitur->caption() ?><?= $Page->mshitjeShitur->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mshitjeShitur->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeShitur">
<template id="tp_x_mshitjeShitur">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeShitur" name="x_mshitjeShitur" id="x_mshitjeShitur"<?= $Page->mshitjeShitur->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeShitur" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeShitur"
    name="x_mshitjeShitur"
    value="<?= HtmlEncode($Page->mshitjeShitur->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeShitur"
    data-bs-target="dsl_x_mshitjeShitur"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeShitur->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeShitur"
    data-value-separator="<?= $Page->mshitjeShitur->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeShitur->editAttributes() ?>></selection-list>
<?= $Page->mshitjeShitur->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mshitjeShitur->getErrorMessage() ?></div>
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
    ew.addEventHandlers("makina_shitje");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
