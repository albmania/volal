<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaImportiAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_importi: currentTable } });
var currentForm, currentPageID;
var fmakina_importiadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importiadd = new ew.Form("fmakina_importiadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fmakina_importiadd;

    // Add fields
    var fields = currentTable.fields;
    fmakina_importiadd.addFields([
        ["mimpMarka", [fields.mimpMarka.visible && fields.mimpMarka.required ? ew.Validators.required(fields.mimpMarka.caption) : null], fields.mimpMarka.isInvalid],
        ["mimpModeli", [fields.mimpModeli.visible && fields.mimpModeli.required ? ew.Validators.required(fields.mimpModeli.caption) : null], fields.mimpModeli.isInvalid],
        ["mimpTipi", [fields.mimpTipi.visible && fields.mimpTipi.required ? ew.Validators.required(fields.mimpTipi.caption) : null], fields.mimpTipi.isInvalid],
        ["mimpShasia", [fields.mimpShasia.visible && fields.mimpShasia.required ? ew.Validators.required(fields.mimpShasia.caption) : null], fields.mimpShasia.isInvalid],
        ["mimpViti", [fields.mimpViti.visible && fields.mimpViti.required ? ew.Validators.required(fields.mimpViti.caption) : null, ew.Validators.integer], fields.mimpViti.isInvalid],
        ["mimpKarburant", [fields.mimpKarburant.visible && fields.mimpKarburant.required ? ew.Validators.required(fields.mimpKarburant.caption) : null], fields.mimpKarburant.isInvalid],
        ["mimpKambio", [fields.mimpKambio.visible && fields.mimpKambio.required ? ew.Validators.required(fields.mimpKambio.caption) : null], fields.mimpKambio.isInvalid],
        ["mimpNgjyra", [fields.mimpNgjyra.visible && fields.mimpNgjyra.required ? ew.Validators.required(fields.mimpNgjyra.caption) : null], fields.mimpNgjyra.isInvalid],
        ["mimpPrejardhja", [fields.mimpPrejardhja.visible && fields.mimpPrejardhja.required ? ew.Validators.required(fields.mimpPrejardhja.caption) : null], fields.mimpPrejardhja.isInvalid],
        ["mimpInfo", [fields.mimpInfo.visible && fields.mimpInfo.required ? ew.Validators.required(fields.mimpInfo.caption) : null], fields.mimpInfo.isInvalid],
        ["mimpCmimiBlerjes", [fields.mimpCmimiBlerjes.visible && fields.mimpCmimiBlerjes.required ? ew.Validators.required(fields.mimpCmimiBlerjes.caption) : null, ew.Validators.float], fields.mimpCmimiBlerjes.isInvalid],
        ["mimpDogana", [fields.mimpDogana.visible && fields.mimpDogana.required ? ew.Validators.required(fields.mimpDogana.caption) : null, ew.Validators.float], fields.mimpDogana.isInvalid],
        ["mimpTransporti", [fields.mimpTransporti.visible && fields.mimpTransporti.required ? ew.Validators.required(fields.mimpTransporti.caption) : null, ew.Validators.float], fields.mimpTransporti.isInvalid],
        ["mimpTjera", [fields.mimpTjera.visible && fields.mimpTjera.required ? ew.Validators.required(fields.mimpTjera.caption) : null, ew.Validators.float], fields.mimpTjera.isInvalid],
        ["mimpDtHyrjes", [fields.mimpDtHyrjes.visible && fields.mimpDtHyrjes.required ? ew.Validators.required(fields.mimpDtHyrjes.caption) : null, ew.Validators.datetime(fields.mimpDtHyrjes.clientFormatPattern)], fields.mimpDtHyrjes.isInvalid],
        ["mimpCmimiShitjes", [fields.mimpCmimiShitjes.visible && fields.mimpCmimiShitjes.required ? ew.Validators.required(fields.mimpCmimiShitjes.caption) : null, ew.Validators.float], fields.mimpCmimiShitjes.isInvalid],
        ["mimpGati", [fields.mimpGati.visible && fields.mimpGati.required ? ew.Validators.required(fields.mimpGati.caption) : null], fields.mimpGati.isInvalid]
    ]);

    // Form_CustomValidate
    fmakina_importiadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_importiadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakina_importiadd.lists.mimpMarka = <?= $Page->mimpMarka->toClientList($Page) ?>;
    fmakina_importiadd.lists.mimpModeli = <?= $Page->mimpModeli->toClientList($Page) ?>;
    fmakina_importiadd.lists.mimpTipi = <?= $Page->mimpTipi->toClientList($Page) ?>;
    fmakina_importiadd.lists.mimpKarburant = <?= $Page->mimpKarburant->toClientList($Page) ?>;
    fmakina_importiadd.lists.mimpKambio = <?= $Page->mimpKambio->toClientList($Page) ?>;
    fmakina_importiadd.lists.mimpGati = <?= $Page->mimpGati->toClientList($Page) ?>;
    loadjs.done("fmakina_importiadd");
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
<form name="fmakina_importiadd" id="fmakina_importiadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_importi">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->mimpMarka->Visible) { // mimpMarka ?>
    <div id="r_mimpMarka"<?= $Page->mimpMarka->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpMarka" for="x_mimpMarka" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpMarka->caption() ?><?= $Page->mimpMarka->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpMarka->cellAttributes() ?>>
<span id="el_makina_importi_mimpMarka">
<?php $Page->mimpMarka->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mimpMarka"
        name="x_mimpMarka"
        class="form-select ew-select<?= $Page->mimpMarka->isInvalidClass() ?>"
        data-select2-id="fmakina_importiadd_x_mimpMarka"
        data-table="makina_importi"
        data-field="x_mimpMarka"
        data-value-separator="<?= $Page->mimpMarka->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpMarka->getPlaceHolder()) ?>"
        <?= $Page->mimpMarka->editAttributes() ?>>
        <?= $Page->mimpMarka->selectOptionListHtml("x_mimpMarka") ?>
    </select>
    <?= $Page->mimpMarka->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mimpMarka->getErrorMessage() ?></div>
<?= $Page->mimpMarka->Lookup->getParamTag($Page, "p_x_mimpMarka") ?>
<script>
loadjs.ready("fmakina_importiadd", function() {
    var options = { name: "x_mimpMarka", selectId: "fmakina_importiadd_x_mimpMarka" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importiadd.lists.mimpMarka.lookupOptions.length) {
        options.data = { id: "x_mimpMarka", form: "fmakina_importiadd" };
    } else {
        options.ajax = { id: "x_mimpMarka", form: "fmakina_importiadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpMarka.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpModeli->Visible) { // mimpModeli ?>
    <div id="r_mimpModeli"<?= $Page->mimpModeli->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpModeli" for="x_mimpModeli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpModeli->caption() ?><?= $Page->mimpModeli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpModeli->cellAttributes() ?>>
<span id="el_makina_importi_mimpModeli">
<?php $Page->mimpModeli->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mimpModeli"
        name="x_mimpModeli"
        class="form-select ew-select<?= $Page->mimpModeli->isInvalidClass() ?>"
        data-select2-id="fmakina_importiadd_x_mimpModeli"
        data-table="makina_importi"
        data-field="x_mimpModeli"
        data-value-separator="<?= $Page->mimpModeli->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpModeli->getPlaceHolder()) ?>"
        <?= $Page->mimpModeli->editAttributes() ?>>
        <?= $Page->mimpModeli->selectOptionListHtml("x_mimpModeli") ?>
    </select>
    <?= $Page->mimpModeli->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mimpModeli->getErrorMessage() ?></div>
<?= $Page->mimpModeli->Lookup->getParamTag($Page, "p_x_mimpModeli") ?>
<script>
loadjs.ready("fmakina_importiadd", function() {
    var options = { name: "x_mimpModeli", selectId: "fmakina_importiadd_x_mimpModeli" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importiadd.lists.mimpModeli.lookupOptions.length) {
        options.data = { id: "x_mimpModeli", form: "fmakina_importiadd" };
    } else {
        options.ajax = { id: "x_mimpModeli", form: "fmakina_importiadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpModeli.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpTipi->Visible) { // mimpTipi ?>
    <div id="r_mimpTipi"<?= $Page->mimpTipi->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpTipi" for="x_mimpTipi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpTipi->caption() ?><?= $Page->mimpTipi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpTipi->cellAttributes() ?>>
<span id="el_makina_importi_mimpTipi">
    <select
        id="x_mimpTipi"
        name="x_mimpTipi"
        class="form-select ew-select<?= $Page->mimpTipi->isInvalidClass() ?>"
        data-select2-id="fmakina_importiadd_x_mimpTipi"
        data-table="makina_importi"
        data-field="x_mimpTipi"
        data-value-separator="<?= $Page->mimpTipi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpTipi->getPlaceHolder()) ?>"
        <?= $Page->mimpTipi->editAttributes() ?>>
        <?= $Page->mimpTipi->selectOptionListHtml("x_mimpTipi") ?>
    </select>
    <?= $Page->mimpTipi->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mimpTipi->getErrorMessage() ?></div>
<?= $Page->mimpTipi->Lookup->getParamTag($Page, "p_x_mimpTipi") ?>
<script>
loadjs.ready("fmakina_importiadd", function() {
    var options = { name: "x_mimpTipi", selectId: "fmakina_importiadd_x_mimpTipi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importiadd.lists.mimpTipi.lookupOptions.length) {
        options.data = { id: "x_mimpTipi", form: "fmakina_importiadd" };
    } else {
        options.ajax = { id: "x_mimpTipi", form: "fmakina_importiadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpTipi.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpShasia->Visible) { // mimpShasia ?>
    <div id="r_mimpShasia"<?= $Page->mimpShasia->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpShasia" for="x_mimpShasia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpShasia->caption() ?><?= $Page->mimpShasia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpShasia->cellAttributes() ?>>
<span id="el_makina_importi_mimpShasia">
<input type="<?= $Page->mimpShasia->getInputTextType() ?>" name="x_mimpShasia" id="x_mimpShasia" data-table="makina_importi" data-field="x_mimpShasia" value="<?= $Page->mimpShasia->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->mimpShasia->getPlaceHolder()) ?>"<?= $Page->mimpShasia->editAttributes() ?> aria-describedby="x_mimpShasia_help">
<?= $Page->mimpShasia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpShasia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpViti->Visible) { // mimpViti ?>
    <div id="r_mimpViti"<?= $Page->mimpViti->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpViti" for="x_mimpViti" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpViti->caption() ?><?= $Page->mimpViti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpViti->cellAttributes() ?>>
<span id="el_makina_importi_mimpViti">
<input type="<?= $Page->mimpViti->getInputTextType() ?>" name="x_mimpViti" id="x_mimpViti" data-table="makina_importi" data-field="x_mimpViti" value="<?= $Page->mimpViti->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mimpViti->getPlaceHolder()) ?>"<?= $Page->mimpViti->editAttributes() ?> aria-describedby="x_mimpViti_help">
<?= $Page->mimpViti->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpViti->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpKarburant->Visible) { // mimpKarburant ?>
    <div id="r_mimpKarburant"<?= $Page->mimpKarburant->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpKarburant" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpKarburant->caption() ?><?= $Page->mimpKarburant->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpKarburant->cellAttributes() ?>>
<span id="el_makina_importi_mimpKarburant">
<template id="tp_x_mimpKarburant">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi" data-field="x_mimpKarburant" name="x_mimpKarburant" id="x_mimpKarburant"<?= $Page->mimpKarburant->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mimpKarburant" class="ew-item-list"></div>
<selection-list hidden
    id="x_mimpKarburant"
    name="x_mimpKarburant"
    value="<?= HtmlEncode($Page->mimpKarburant->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mimpKarburant"
    data-bs-target="dsl_x_mimpKarburant"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mimpKarburant->isInvalidClass() ?>"
    data-table="makina_importi"
    data-field="x_mimpKarburant"
    data-value-separator="<?= $Page->mimpKarburant->displayValueSeparatorAttribute() ?>"
    <?= $Page->mimpKarburant->editAttributes() ?>></selection-list>
<?= $Page->mimpKarburant->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpKarburant->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpKambio->Visible) { // mimpKambio ?>
    <div id="r_mimpKambio"<?= $Page->mimpKambio->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpKambio" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpKambio->caption() ?><?= $Page->mimpKambio->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpKambio->cellAttributes() ?>>
<span id="el_makina_importi_mimpKambio">
<template id="tp_x_mimpKambio">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi" data-field="x_mimpKambio" name="x_mimpKambio" id="x_mimpKambio"<?= $Page->mimpKambio->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mimpKambio" class="ew-item-list"></div>
<selection-list hidden
    id="x_mimpKambio"
    name="x_mimpKambio"
    value="<?= HtmlEncode($Page->mimpKambio->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mimpKambio"
    data-bs-target="dsl_x_mimpKambio"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mimpKambio->isInvalidClass() ?>"
    data-table="makina_importi"
    data-field="x_mimpKambio"
    data-value-separator="<?= $Page->mimpKambio->displayValueSeparatorAttribute() ?>"
    <?= $Page->mimpKambio->editAttributes() ?>></selection-list>
<?= $Page->mimpKambio->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpKambio->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpNgjyra->Visible) { // mimpNgjyra ?>
    <div id="r_mimpNgjyra"<?= $Page->mimpNgjyra->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpNgjyra" for="x_mimpNgjyra" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpNgjyra->caption() ?><?= $Page->mimpNgjyra->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpNgjyra->cellAttributes() ?>>
<span id="el_makina_importi_mimpNgjyra">
<input type="<?= $Page->mimpNgjyra->getInputTextType() ?>" name="x_mimpNgjyra" id="x_mimpNgjyra" data-table="makina_importi" data-field="x_mimpNgjyra" value="<?= $Page->mimpNgjyra->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->mimpNgjyra->getPlaceHolder()) ?>"<?= $Page->mimpNgjyra->editAttributes() ?> aria-describedby="x_mimpNgjyra_help">
<?= $Page->mimpNgjyra->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpNgjyra->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpPrejardhja->Visible) { // mimpPrejardhja ?>
    <div id="r_mimpPrejardhja"<?= $Page->mimpPrejardhja->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpPrejardhja" for="x_mimpPrejardhja" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpPrejardhja->caption() ?><?= $Page->mimpPrejardhja->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpPrejardhja->cellAttributes() ?>>
<span id="el_makina_importi_mimpPrejardhja">
<input type="<?= $Page->mimpPrejardhja->getInputTextType() ?>" name="x_mimpPrejardhja" id="x_mimpPrejardhja" data-table="makina_importi" data-field="x_mimpPrejardhja" value="<?= $Page->mimpPrejardhja->EditValue ?>" size="30" maxlength="25" placeholder="<?= HtmlEncode($Page->mimpPrejardhja->getPlaceHolder()) ?>"<?= $Page->mimpPrejardhja->editAttributes() ?> aria-describedby="x_mimpPrejardhja_help">
<?= $Page->mimpPrejardhja->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpPrejardhja->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpInfo->Visible) { // mimpInfo ?>
    <div id="r_mimpInfo"<?= $Page->mimpInfo->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpInfo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpInfo->caption() ?><?= $Page->mimpInfo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpInfo->cellAttributes() ?>>
<span id="el_makina_importi_mimpInfo">
<?php $Page->mimpInfo->EditAttrs->appendClass("editor"); ?>
<textarea data-table="makina_importi" data-field="x_mimpInfo" name="x_mimpInfo" id="x_mimpInfo" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->mimpInfo->getPlaceHolder()) ?>"<?= $Page->mimpInfo->editAttributes() ?> aria-describedby="x_mimpInfo_help"><?= $Page->mimpInfo->EditValue ?></textarea>
<?= $Page->mimpInfo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpInfo->getErrorMessage() ?></div>
<script>
loadjs.ready(["fmakina_importiadd", "editor"], function() {
    ew.createEditor("fmakina_importiadd", "x_mimpInfo", 35, 4, <?= $Page->mimpInfo->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpCmimiBlerjes->Visible) { // mimpCmimiBlerjes ?>
    <div id="r_mimpCmimiBlerjes"<?= $Page->mimpCmimiBlerjes->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpCmimiBlerjes" for="x_mimpCmimiBlerjes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpCmimiBlerjes->caption() ?><?= $Page->mimpCmimiBlerjes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpCmimiBlerjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpCmimiBlerjes">
<input type="<?= $Page->mimpCmimiBlerjes->getInputTextType() ?>" name="x_mimpCmimiBlerjes" id="x_mimpCmimiBlerjes" data-table="makina_importi" data-field="x_mimpCmimiBlerjes" value="<?= $Page->mimpCmimiBlerjes->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mimpCmimiBlerjes->getPlaceHolder()) ?>"<?= $Page->mimpCmimiBlerjes->editAttributes() ?> aria-describedby="x_mimpCmimiBlerjes_help">
<?= $Page->mimpCmimiBlerjes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpCmimiBlerjes->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpDogana->Visible) { // mimpDogana ?>
    <div id="r_mimpDogana"<?= $Page->mimpDogana->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpDogana" for="x_mimpDogana" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpDogana->caption() ?><?= $Page->mimpDogana->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpDogana->cellAttributes() ?>>
<span id="el_makina_importi_mimpDogana">
<input type="<?= $Page->mimpDogana->getInputTextType() ?>" name="x_mimpDogana" id="x_mimpDogana" data-table="makina_importi" data-field="x_mimpDogana" value="<?= $Page->mimpDogana->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mimpDogana->getPlaceHolder()) ?>"<?= $Page->mimpDogana->editAttributes() ?> aria-describedby="x_mimpDogana_help">
<?= $Page->mimpDogana->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpDogana->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpTransporti->Visible) { // mimpTransporti ?>
    <div id="r_mimpTransporti"<?= $Page->mimpTransporti->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpTransporti" for="x_mimpTransporti" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpTransporti->caption() ?><?= $Page->mimpTransporti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpTransporti->cellAttributes() ?>>
<span id="el_makina_importi_mimpTransporti">
<input type="<?= $Page->mimpTransporti->getInputTextType() ?>" name="x_mimpTransporti" id="x_mimpTransporti" data-table="makina_importi" data-field="x_mimpTransporti" value="<?= $Page->mimpTransporti->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mimpTransporti->getPlaceHolder()) ?>"<?= $Page->mimpTransporti->editAttributes() ?> aria-describedby="x_mimpTransporti_help">
<?= $Page->mimpTransporti->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpTransporti->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpTjera->Visible) { // mimpTjera ?>
    <div id="r_mimpTjera"<?= $Page->mimpTjera->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpTjera" for="x_mimpTjera" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpTjera->caption() ?><?= $Page->mimpTjera->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpTjera->cellAttributes() ?>>
<span id="el_makina_importi_mimpTjera">
<input type="<?= $Page->mimpTjera->getInputTextType() ?>" name="x_mimpTjera" id="x_mimpTjera" data-table="makina_importi" data-field="x_mimpTjera" value="<?= $Page->mimpTjera->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mimpTjera->getPlaceHolder()) ?>"<?= $Page->mimpTjera->editAttributes() ?> aria-describedby="x_mimpTjera_help">
<?= $Page->mimpTjera->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpTjera->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpDtHyrjes->Visible) { // mimpDtHyrjes ?>
    <div id="r_mimpDtHyrjes"<?= $Page->mimpDtHyrjes->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpDtHyrjes" for="x_mimpDtHyrjes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpDtHyrjes->caption() ?><?= $Page->mimpDtHyrjes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpDtHyrjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpDtHyrjes">
<input type="<?= $Page->mimpDtHyrjes->getInputTextType() ?>" name="x_mimpDtHyrjes" id="x_mimpDtHyrjes" data-table="makina_importi" data-field="x_mimpDtHyrjes" value="<?= $Page->mimpDtHyrjes->EditValue ?>" placeholder="<?= HtmlEncode($Page->mimpDtHyrjes->getPlaceHolder()) ?>"<?= $Page->mimpDtHyrjes->editAttributes() ?> aria-describedby="x_mimpDtHyrjes_help">
<?= $Page->mimpDtHyrjes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpDtHyrjes->getErrorMessage() ?></div>
<?php if (!$Page->mimpDtHyrjes->ReadOnly && !$Page->mimpDtHyrjes->Disabled && !isset($Page->mimpDtHyrjes->EditAttrs["readonly"]) && !isset($Page->mimpDtHyrjes->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmakina_importiadd", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
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
    ew.createDateTimePicker("fmakina_importiadd", "x_mimpDtHyrjes", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpCmimiShitjes->Visible) { // mimpCmimiShitjes ?>
    <div id="r_mimpCmimiShitjes"<?= $Page->mimpCmimiShitjes->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpCmimiShitjes" for="x_mimpCmimiShitjes" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpCmimiShitjes->caption() ?><?= $Page->mimpCmimiShitjes->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpCmimiShitjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpCmimiShitjes">
<input type="<?= $Page->mimpCmimiShitjes->getInputTextType() ?>" name="x_mimpCmimiShitjes" id="x_mimpCmimiShitjes" data-table="makina_importi" data-field="x_mimpCmimiShitjes" value="<?= $Page->mimpCmimiShitjes->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mimpCmimiShitjes->getPlaceHolder()) ?>"<?= $Page->mimpCmimiShitjes->editAttributes() ?> aria-describedby="x_mimpCmimiShitjes_help">
<?= $Page->mimpCmimiShitjes->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpCmimiShitjes->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mimpGati->Visible) { // mimpGati ?>
    <div id="r_mimpGati"<?= $Page->mimpGati->rowAttributes() ?>>
        <label id="elh_makina_importi_mimpGati" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mimpGati->caption() ?><?= $Page->mimpGati->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mimpGati->cellAttributes() ?>>
<span id="el_makina_importi_mimpGati">
<template id="tp_x_mimpGati">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi" data-field="x_mimpGati" name="x_mimpGati" id="x_mimpGati"<?= $Page->mimpGati->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mimpGati" class="ew-item-list"></div>
<selection-list hidden
    id="x_mimpGati"
    name="x_mimpGati"
    value="<?= HtmlEncode($Page->mimpGati->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mimpGati"
    data-bs-target="dsl_x_mimpGati"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mimpGati->isInvalidClass() ?>"
    data-table="makina_importi"
    data-field="x_mimpGati"
    data-value-separator="<?= $Page->mimpGati->displayValueSeparatorAttribute() ?>"
    <?= $Page->mimpGati->editAttributes() ?>></selection-list>
<?= $Page->mimpGati->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mimpGati->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php
    if (in_array("makina_importi_sherbime", explode(",", $Page->getCurrentDetailTable())) && $makina_importi_sherbime->DetailAdd) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("makina_importi_sherbime", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "MakinaImportiSherbimeGrid.php" ?>
<?php } ?>
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
    ew.addEventHandlers("makina_importi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
