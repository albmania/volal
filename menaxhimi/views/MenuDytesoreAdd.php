<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuDytesoreAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_dytesore: currentTable } });
var currentForm, currentPageID;
var fmenu_dytesoreadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_dytesoreadd = new ew.Form("fmenu_dytesoreadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fmenu_dytesoreadd;

    // Add fields
    var fields = currentTable.fields;
    fmenu_dytesoreadd.addFields([
        ["menudGjuha", [fields.menudGjuha.visible && fields.menudGjuha.required ? ew.Validators.required(fields.menudGjuha.caption) : null], fields.menudGjuha.isInvalid],
        ["menudKryesore", [fields.menudKryesore.visible && fields.menudKryesore.required ? ew.Validators.required(fields.menudKryesore.caption) : null], fields.menudKryesore.isInvalid],
        ["menudTitulli", [fields.menudTitulli.visible && fields.menudTitulli.required ? ew.Validators.required(fields.menudTitulli.caption) : null], fields.menudTitulli.isInvalid],
        ["menudUrl", [fields.menudUrl.visible && fields.menudUrl.required ? ew.Validators.required(fields.menudUrl.caption) : null], fields.menudUrl.isInvalid],
        ["menudBlank", [fields.menudBlank.visible && fields.menudBlank.required ? ew.Validators.required(fields.menudBlank.caption) : null], fields.menudBlank.isInvalid],
        ["menudRadhe", [fields.menudRadhe.visible && fields.menudRadhe.required ? ew.Validators.required(fields.menudRadhe.caption) : null, ew.Validators.integer], fields.menudRadhe.isInvalid],
        ["menudAktiv", [fields.menudAktiv.visible && fields.menudAktiv.required ? ew.Validators.required(fields.menudAktiv.caption) : null], fields.menudAktiv.isInvalid],
        ["menudAutor", [fields.menudAutor.visible && fields.menudAutor.required ? ew.Validators.required(fields.menudAutor.caption) : null], fields.menudAutor.isInvalid],
        ["menudKrijuar", [fields.menudKrijuar.visible && fields.menudKrijuar.required ? ew.Validators.required(fields.menudKrijuar.caption) : null], fields.menudKrijuar.isInvalid]
    ]);

    // Form_CustomValidate
    fmenu_dytesoreadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmenu_dytesoreadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmenu_dytesoreadd.lists.menudGjuha = <?= $Page->menudGjuha->toClientList($Page) ?>;
    fmenu_dytesoreadd.lists.menudKryesore = <?= $Page->menudKryesore->toClientList($Page) ?>;
    fmenu_dytesoreadd.lists.menudBlank = <?= $Page->menudBlank->toClientList($Page) ?>;
    fmenu_dytesoreadd.lists.menudAktiv = <?= $Page->menudAktiv->toClientList($Page) ?>;
    fmenu_dytesoreadd.lists.menudAutor = <?= $Page->menudAutor->toClientList($Page) ?>;
    loadjs.done("fmenu_dytesoreadd");
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
<form name="fmenu_dytesoreadd" id="fmenu_dytesoreadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="menu_dytesore">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->menudGjuha->Visible) { // menudGjuha ?>
    <div id="r_menudGjuha"<?= $Page->menudGjuha->rowAttributes() ?>>
        <label id="elh_menu_dytesore_menudGjuha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menudGjuha->caption() ?><?= $Page->menudGjuha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menudGjuha->cellAttributes() ?>>
<span id="el_menu_dytesore_menudGjuha">
<template id="tp_x_menudGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="menu_dytesore" data-field="x_menudGjuha" name="x_menudGjuha" id="x_menudGjuha"<?= $Page->menudGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_menudGjuha" class="ew-item-list"></div>
<?php $Page->menudGjuha->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<selection-list hidden
    id="x_menudGjuha"
    name="x_menudGjuha"
    value="<?= HtmlEncode($Page->menudGjuha->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_menudGjuha"
    data-bs-target="dsl_x_menudGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->menudGjuha->isInvalidClass() ?>"
    data-table="menu_dytesore"
    data-field="x_menudGjuha"
    data-value-separator="<?= $Page->menudGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->menudGjuha->editAttributes() ?>></selection-list>
<?= $Page->menudGjuha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menudGjuha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menudKryesore->Visible) { // menudKryesore ?>
    <div id="r_menudKryesore"<?= $Page->menudKryesore->rowAttributes() ?>>
        <label id="elh_menu_dytesore_menudKryesore" for="x_menudKryesore" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menudKryesore->caption() ?><?= $Page->menudKryesore->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menudKryesore->cellAttributes() ?>>
<span id="el_menu_dytesore_menudKryesore">
<div class="input-group flex-nowrap">
    <select
        id="x_menudKryesore"
        name="x_menudKryesore"
        class="form-select ew-select<?= $Page->menudKryesore->isInvalidClass() ?>"
        data-select2-id="fmenu_dytesoreadd_x_menudKryesore"
        data-table="menu_dytesore"
        data-field="x_menudKryesore"
        data-value-separator="<?= $Page->menudKryesore->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->menudKryesore->getPlaceHolder()) ?>"
        <?= $Page->menudKryesore->editAttributes() ?>>
        <?= $Page->menudKryesore->selectOptionListHtml("x_menudKryesore") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "menu_kryesore") && !$Page->menudKryesore->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_menudKryesore" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->menudKryesore->caption() ?>" data-title="<?= $Page->menudKryesore->caption() ?>" data-ew-action="add-option" data-el="x_menudKryesore" data-url="<?= GetUrl("MenuKryesoreAddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<?= $Page->menudKryesore->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menudKryesore->getErrorMessage() ?></div>
<?= $Page->menudKryesore->Lookup->getParamTag($Page, "p_x_menudKryesore") ?>
<script>
loadjs.ready("fmenu_dytesoreadd", function() {
    var options = { name: "x_menudKryesore", selectId: "fmenu_dytesoreadd_x_menudKryesore" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmenu_dytesoreadd.lists.menudKryesore.lookupOptions.length) {
        options.data = { id: "x_menudKryesore", form: "fmenu_dytesoreadd" };
    } else {
        options.ajax = { id: "x_menudKryesore", form: "fmenu_dytesoreadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.menu_dytesore.fields.menudKryesore.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menudTitulli->Visible) { // menudTitulli ?>
    <div id="r_menudTitulli"<?= $Page->menudTitulli->rowAttributes() ?>>
        <label id="elh_menu_dytesore_menudTitulli" for="x_menudTitulli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menudTitulli->caption() ?><?= $Page->menudTitulli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menudTitulli->cellAttributes() ?>>
<span id="el_menu_dytesore_menudTitulli">
<input type="<?= $Page->menudTitulli->getInputTextType() ?>" name="x_menudTitulli" id="x_menudTitulli" data-table="menu_dytesore" data-field="x_menudTitulli" value="<?= $Page->menudTitulli->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->menudTitulli->getPlaceHolder()) ?>"<?= $Page->menudTitulli->editAttributes() ?> aria-describedby="x_menudTitulli_help">
<?= $Page->menudTitulli->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menudTitulli->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menudUrl->Visible) { // menudUrl ?>
    <div id="r_menudUrl"<?= $Page->menudUrl->rowAttributes() ?>>
        <label id="elh_menu_dytesore_menudUrl" for="x_menudUrl" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menudUrl->caption() ?><?= $Page->menudUrl->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menudUrl->cellAttributes() ?>>
<span id="el_menu_dytesore_menudUrl">
<input type="<?= $Page->menudUrl->getInputTextType() ?>" name="x_menudUrl" id="x_menudUrl" data-table="menu_dytesore" data-field="x_menudUrl" value="<?= $Page->menudUrl->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->menudUrl->getPlaceHolder()) ?>"<?= $Page->menudUrl->editAttributes() ?> aria-describedby="x_menudUrl_help">
<?= $Page->menudUrl->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menudUrl->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menudBlank->Visible) { // menudBlank ?>
    <div id="r_menudBlank"<?= $Page->menudBlank->rowAttributes() ?>>
        <label id="elh_menu_dytesore_menudBlank" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menudBlank->caption() ?><?= $Page->menudBlank->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menudBlank->cellAttributes() ?>>
<span id="el_menu_dytesore_menudBlank">
<template id="tp_x_menudBlank">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="menu_dytesore" data-field="x_menudBlank" name="x_menudBlank" id="x_menudBlank"<?= $Page->menudBlank->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_menudBlank" class="ew-item-list"></div>
<selection-list hidden
    id="x_menudBlank"
    name="x_menudBlank"
    value="<?= HtmlEncode($Page->menudBlank->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_menudBlank"
    data-bs-target="dsl_x_menudBlank"
    data-repeatcolumn="5"
    class="form-control<?= $Page->menudBlank->isInvalidClass() ?>"
    data-table="menu_dytesore"
    data-field="x_menudBlank"
    data-value-separator="<?= $Page->menudBlank->displayValueSeparatorAttribute() ?>"
    <?= $Page->menudBlank->editAttributes() ?>></selection-list>
<?= $Page->menudBlank->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menudBlank->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menudRadhe->Visible) { // menudRadhe ?>
    <div id="r_menudRadhe"<?= $Page->menudRadhe->rowAttributes() ?>>
        <label id="elh_menu_dytesore_menudRadhe" for="x_menudRadhe" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menudRadhe->caption() ?><?= $Page->menudRadhe->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menudRadhe->cellAttributes() ?>>
<span id="el_menu_dytesore_menudRadhe">
<input type="<?= $Page->menudRadhe->getInputTextType() ?>" name="x_menudRadhe" id="x_menudRadhe" data-table="menu_dytesore" data-field="x_menudRadhe" value="<?= $Page->menudRadhe->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->menudRadhe->getPlaceHolder()) ?>"<?= $Page->menudRadhe->editAttributes() ?> aria-describedby="x_menudRadhe_help">
<?= $Page->menudRadhe->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menudRadhe->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menudAktiv->Visible) { // menudAktiv ?>
    <div id="r_menudAktiv"<?= $Page->menudAktiv->rowAttributes() ?>>
        <label id="elh_menu_dytesore_menudAktiv" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menudAktiv->caption() ?><?= $Page->menudAktiv->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menudAktiv->cellAttributes() ?>>
<span id="el_menu_dytesore_menudAktiv">
<template id="tp_x_menudAktiv">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="menu_dytesore" data-field="x_menudAktiv" name="x_menudAktiv" id="x_menudAktiv"<?= $Page->menudAktiv->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_menudAktiv" class="ew-item-list"></div>
<selection-list hidden
    id="x_menudAktiv"
    name="x_menudAktiv"
    value="<?= HtmlEncode($Page->menudAktiv->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_menudAktiv"
    data-bs-target="dsl_x_menudAktiv"
    data-repeatcolumn="5"
    class="form-control<?= $Page->menudAktiv->isInvalidClass() ?>"
    data-table="menu_dytesore"
    data-field="x_menudAktiv"
    data-value-separator="<?= $Page->menudAktiv->displayValueSeparatorAttribute() ?>"
    <?= $Page->menudAktiv->editAttributes() ?>></selection-list>
<?= $Page->menudAktiv->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menudAktiv->getErrorMessage() ?></div>
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
    ew.addEventHandlers("menu_dytesore");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
