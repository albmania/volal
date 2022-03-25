<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuKryesoreAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_kryesore: currentTable } });
var currentForm, currentPageID;
var fmenu_kryesoreaddopt;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_kryesoreaddopt = new ew.Form("fmenu_kryesoreaddopt", "addopt");
    currentPageID = ew.PAGE_ID = "addopt";
    currentForm = fmenu_kryesoreaddopt;

    // Add fields
    var fields = currentTable.fields;
    fmenu_kryesoreaddopt.addFields([
        ["menukGjuha", [fields.menukGjuha.visible && fields.menukGjuha.required ? ew.Validators.required(fields.menukGjuha.caption) : null], fields.menukGjuha.isInvalid],
        ["menukTitull", [fields.menukTitull.visible && fields.menukTitull.required ? ew.Validators.required(fields.menukTitull.caption) : null], fields.menukTitull.isInvalid],
        ["menukUrl", [fields.menukUrl.visible && fields.menukUrl.required ? ew.Validators.required(fields.menukUrl.caption) : null], fields.menukUrl.isInvalid],
        ["menukBlank", [fields.menukBlank.visible && fields.menukBlank.required ? ew.Validators.required(fields.menukBlank.caption) : null], fields.menukBlank.isInvalid],
        ["menukRadhe", [fields.menukRadhe.visible && fields.menukRadhe.required ? ew.Validators.required(fields.menukRadhe.caption) : null, ew.Validators.integer], fields.menukRadhe.isInvalid],
        ["menukAktiv", [fields.menukAktiv.visible && fields.menukAktiv.required ? ew.Validators.required(fields.menukAktiv.caption) : null], fields.menukAktiv.isInvalid],
        ["menukAutor", [fields.menukAutor.visible && fields.menukAutor.required ? ew.Validators.required(fields.menukAutor.caption) : null], fields.menukAutor.isInvalid],
        ["menukKrijuar", [fields.menukKrijuar.visible && fields.menukKrijuar.required ? ew.Validators.required(fields.menukKrijuar.caption) : null], fields.menukKrijuar.isInvalid],
        ["menukAzhornuar", [fields.menukAzhornuar.visible && fields.menukAzhornuar.required ? ew.Validators.required(fields.menukAzhornuar.caption) : null], fields.menukAzhornuar.isInvalid]
    ]);

    // Form_CustomValidate
    fmenu_kryesoreaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmenu_kryesoreaddopt.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmenu_kryesoreaddopt.lists.menukGjuha = <?= $Page->menukGjuha->toClientList($Page) ?>;
    fmenu_kryesoreaddopt.lists.menukBlank = <?= $Page->menukBlank->toClientList($Page) ?>;
    fmenu_kryesoreaddopt.lists.menukAktiv = <?= $Page->menukAktiv->toClientList($Page) ?>;
    fmenu_kryesoreaddopt.lists.menukAutor = <?= $Page->menukAutor->toClientList($Page) ?>;
    loadjs.done("fmenu_kryesoreaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fmenu_kryesoreaddopt" id="fmenu_kryesoreaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="menu_kryesore">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->menukGjuha->Visible) { // menukGjuha ?>
    <div<?= $Page->menukGjuha->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->menukGjuha->caption() ?><?= $Page->menukGjuha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->menukGjuha->cellAttributes() ?>>
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
<div class="invalid-feedback"><?= $Page->menukGjuha->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukTitull->Visible) { // menukTitull ?>
    <div<?= $Page->menukTitull->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_menukTitull"><?= $Page->menukTitull->caption() ?><?= $Page->menukTitull->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->menukTitull->cellAttributes() ?>>
<input type="<?= $Page->menukTitull->getInputTextType() ?>" name="x_menukTitull" id="x_menukTitull" data-table="menu_kryesore" data-field="x_menukTitull" value="<?= $Page->menukTitull->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->menukTitull->getPlaceHolder()) ?>"<?= $Page->menukTitull->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->menukTitull->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukUrl->Visible) { // menukUrl ?>
    <div<?= $Page->menukUrl->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_menukUrl"><?= $Page->menukUrl->caption() ?><?= $Page->menukUrl->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->menukUrl->cellAttributes() ?>>
<input type="<?= $Page->menukUrl->getInputTextType() ?>" name="x_menukUrl" id="x_menukUrl" data-table="menu_kryesore" data-field="x_menukUrl" value="<?= $Page->menukUrl->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->menukUrl->getPlaceHolder()) ?>"<?= $Page->menukUrl->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->menukUrl->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukBlank->Visible) { // menukBlank ?>
    <div<?= $Page->menukBlank->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->menukBlank->caption() ?><?= $Page->menukBlank->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->menukBlank->cellAttributes() ?>>
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
<div class="invalid-feedback"><?= $Page->menukBlank->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukRadhe->Visible) { // menukRadhe ?>
    <div<?= $Page->menukRadhe->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_menukRadhe"><?= $Page->menukRadhe->caption() ?><?= $Page->menukRadhe->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->menukRadhe->cellAttributes() ?>>
<input type="<?= $Page->menukRadhe->getInputTextType() ?>" name="x_menukRadhe" id="x_menukRadhe" data-table="menu_kryesore" data-field="x_menukRadhe" value="<?= $Page->menukRadhe->EditValue ?>" size="5" placeholder="<?= HtmlEncode($Page->menukRadhe->getPlaceHolder()) ?>"<?= $Page->menukRadhe->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->menukRadhe->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukAktiv->Visible) { // menukAktiv ?>
    <div<?= $Page->menukAktiv->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label"><?= $Page->menukAktiv->caption() ?><?= $Page->menukAktiv->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->menukAktiv->cellAttributes() ?>>
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
<div class="invalid-feedback"><?= $Page->menukAktiv->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->menukAutor->Visible) { // menukAutor ?>
    <input type="hidden" data-table="menu_kryesore" data-field="x_menukAutor" data-hidden="1" name="x_menukAutor" id="x_menukAutor" value="<?= HtmlEncode($Page->menukAutor->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->menukKrijuar->Visible) { // menukKrijuar ?>
    <input type="hidden" data-table="menu_kryesore" data-field="x_menukKrijuar" data-hidden="1" name="x_menukKrijuar" id="x_menukKrijuar" value="<?= HtmlEncode($Page->menukKrijuar->CurrentValue) ?>">
<?php } ?>
<?php if ($Page->menukAzhornuar->Visible) { // menukAzhornuar ?>
    <input type="hidden" data-table="menu_kryesore" data-field="x_menukAzhornuar" data-hidden="1" name="x_menukAzhornuar" id="x_menukAzhornuar" value="<?= HtmlEncode($Page->menukAzhornuar->CurrentValue) ?>">
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
