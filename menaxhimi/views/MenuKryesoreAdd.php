<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuKryesoreAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_kryesore: currentTable } });
var currentForm, currentPageID;
var fmenu_kryesoreadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_kryesoreadd = new ew.Form("fmenu_kryesoreadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fmenu_kryesoreadd;

    // Add fields
    var fields = currentTable.fields;
    fmenu_kryesoreadd.addFields([
        ["menukGjuha", [fields.menukGjuha.visible && fields.menukGjuha.required ? ew.Validators.required(fields.menukGjuha.caption) : null], fields.menukGjuha.isInvalid],
        ["menukTitull", [fields.menukTitull.visible && fields.menukTitull.required ? ew.Validators.required(fields.menukTitull.caption) : null], fields.menukTitull.isInvalid],
        ["menukUrl", [fields.menukUrl.visible && fields.menukUrl.required ? ew.Validators.required(fields.menukUrl.caption) : null], fields.menukUrl.isInvalid],
        ["menukBlank", [fields.menukBlank.visible && fields.menukBlank.required ? ew.Validators.required(fields.menukBlank.caption) : null], fields.menukBlank.isInvalid],
        ["menukRadhe", [fields.menukRadhe.visible && fields.menukRadhe.required ? ew.Validators.required(fields.menukRadhe.caption) : null, ew.Validators.integer], fields.menukRadhe.isInvalid],
        ["menukAktiv", [fields.menukAktiv.visible && fields.menukAktiv.required ? ew.Validators.required(fields.menukAktiv.caption) : null], fields.menukAktiv.isInvalid],
        ["menukAutor", [fields.menukAutor.visible && fields.menukAutor.required ? ew.Validators.required(fields.menukAutor.caption) : null], fields.menukAutor.isInvalid],
        ["menukKrijuar", [fields.menukKrijuar.visible && fields.menukKrijuar.required ? ew.Validators.required(fields.menukKrijuar.caption) : null], fields.menukKrijuar.isInvalid]
    ]);

    // Form_CustomValidate
    fmenu_kryesoreadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmenu_kryesoreadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmenu_kryesoreadd.lists.menukGjuha = <?= $Page->menukGjuha->toClientList($Page) ?>;
    fmenu_kryesoreadd.lists.menukBlank = <?= $Page->menukBlank->toClientList($Page) ?>;
    fmenu_kryesoreadd.lists.menukAktiv = <?= $Page->menukAktiv->toClientList($Page) ?>;
    fmenu_kryesoreadd.lists.menukAutor = <?= $Page->menukAutor->toClientList($Page) ?>;
    loadjs.done("fmenu_kryesoreadd");
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
<form name="fmenu_kryesoreadd" id="fmenu_kryesoreadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="menu_kryesore">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->menukGjuha->Visible) { // menukGjuha ?>
    <div id="r_menukGjuha"<?= $Page->menukGjuha->rowAttributes() ?>>
        <label id="elh_menu_kryesore_menukGjuha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menukGjuha->caption() ?><?= $Page->menukGjuha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menukGjuha->cellAttributes() ?>>
<span id="el_menu_kryesore_menukGjuha">
<template id="tp_x_menukGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="menu_kryesore" data-field="x_menukGjuha" name="x_menukGjuha" id="x_menukGjuha"<?= $Page->menukGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_menukGjuha" class="ew-item-list"></div>
<selection-list hidden
    id="x_menukGjuha"
    name="x_menukGjuha"
    value="<?= HtmlEncode($Page->menukGjuha->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_menukGjuha"
    data-bs-target="dsl_x_menukGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->menukGjuha->isInvalidClass() ?>"
    data-table="menu_kryesore"
    data-field="x_menukGjuha"
    data-value-separator="<?= $Page->menukGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->menukGjuha->editAttributes() ?>></selection-list>
<?= $Page->menukGjuha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menukGjuha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukTitull->Visible) { // menukTitull ?>
    <div id="r_menukTitull"<?= $Page->menukTitull->rowAttributes() ?>>
        <label id="elh_menu_kryesore_menukTitull" for="x_menukTitull" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menukTitull->caption() ?><?= $Page->menukTitull->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menukTitull->cellAttributes() ?>>
<span id="el_menu_kryesore_menukTitull">
<input type="<?= $Page->menukTitull->getInputTextType() ?>" name="x_menukTitull" id="x_menukTitull" data-table="menu_kryesore" data-field="x_menukTitull" value="<?= $Page->menukTitull->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->menukTitull->getPlaceHolder()) ?>"<?= $Page->menukTitull->editAttributes() ?> aria-describedby="x_menukTitull_help">
<?= $Page->menukTitull->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menukTitull->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukUrl->Visible) { // menukUrl ?>
    <div id="r_menukUrl"<?= $Page->menukUrl->rowAttributes() ?>>
        <label id="elh_menu_kryesore_menukUrl" for="x_menukUrl" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menukUrl->caption() ?><?= $Page->menukUrl->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menukUrl->cellAttributes() ?>>
<span id="el_menu_kryesore_menukUrl">
<input type="<?= $Page->menukUrl->getInputTextType() ?>" name="x_menukUrl" id="x_menukUrl" data-table="menu_kryesore" data-field="x_menukUrl" value="<?= $Page->menukUrl->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->menukUrl->getPlaceHolder()) ?>"<?= $Page->menukUrl->editAttributes() ?> aria-describedby="x_menukUrl_help">
<?= $Page->menukUrl->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menukUrl->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukBlank->Visible) { // menukBlank ?>
    <div id="r_menukBlank"<?= $Page->menukBlank->rowAttributes() ?>>
        <label id="elh_menu_kryesore_menukBlank" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menukBlank->caption() ?><?= $Page->menukBlank->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menukBlank->cellAttributes() ?>>
<span id="el_menu_kryesore_menukBlank">
<template id="tp_x_menukBlank">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="menu_kryesore" data-field="x_menukBlank" name="x_menukBlank" id="x_menukBlank"<?= $Page->menukBlank->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_menukBlank" class="ew-item-list"></div>
<selection-list hidden
    id="x_menukBlank"
    name="x_menukBlank"
    value="<?= HtmlEncode($Page->menukBlank->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_menukBlank"
    data-bs-target="dsl_x_menukBlank"
    data-repeatcolumn="5"
    class="form-control<?= $Page->menukBlank->isInvalidClass() ?>"
    data-table="menu_kryesore"
    data-field="x_menukBlank"
    data-value-separator="<?= $Page->menukBlank->displayValueSeparatorAttribute() ?>"
    <?= $Page->menukBlank->editAttributes() ?>></selection-list>
<?= $Page->menukBlank->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menukBlank->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukRadhe->Visible) { // menukRadhe ?>
    <div id="r_menukRadhe"<?= $Page->menukRadhe->rowAttributes() ?>>
        <label id="elh_menu_kryesore_menukRadhe" for="x_menukRadhe" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menukRadhe->caption() ?><?= $Page->menukRadhe->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menukRadhe->cellAttributes() ?>>
<span id="el_menu_kryesore_menukRadhe">
<input type="<?= $Page->menukRadhe->getInputTextType() ?>" name="x_menukRadhe" id="x_menukRadhe" data-table="menu_kryesore" data-field="x_menukRadhe" value="<?= $Page->menukRadhe->EditValue ?>" size="5" placeholder="<?= HtmlEncode($Page->menukRadhe->getPlaceHolder()) ?>"<?= $Page->menukRadhe->editAttributes() ?> aria-describedby="x_menukRadhe_help">
<?= $Page->menukRadhe->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menukRadhe->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukAktiv->Visible) { // menukAktiv ?>
    <div id="r_menukAktiv"<?= $Page->menukAktiv->rowAttributes() ?>>
        <label id="elh_menu_kryesore_menukAktiv" class="<?= $Page->LeftColumnClass ?>"><?= $Page->menukAktiv->caption() ?><?= $Page->menukAktiv->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->menukAktiv->cellAttributes() ?>>
<span id="el_menu_kryesore_menukAktiv">
<template id="tp_x_menukAktiv">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="menu_kryesore" data-field="x_menukAktiv" name="x_menukAktiv" id="x_menukAktiv"<?= $Page->menukAktiv->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_menukAktiv" class="ew-item-list"></div>
<selection-list hidden
    id="x_menukAktiv"
    name="x_menukAktiv"
    value="<?= HtmlEncode($Page->menukAktiv->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_menukAktiv"
    data-bs-target="dsl_x_menukAktiv"
    data-repeatcolumn="5"
    class="form-control<?= $Page->menukAktiv->isInvalidClass() ?>"
    data-table="menu_kryesore"
    data-field="x_menukAktiv"
    data-value-separator="<?= $Page->menukAktiv->displayValueSeparatorAttribute() ?>"
    <?= $Page->menukAktiv->editAttributes() ?>></selection-list>
<?= $Page->menukAktiv->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->menukAktiv->getErrorMessage() ?></div>
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
    ew.addEventHandlers("menu_kryesore");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
