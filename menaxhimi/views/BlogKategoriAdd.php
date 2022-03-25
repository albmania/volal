<?php

namespace PHPMaker2022\volalservice;

// Page object
$BlogKategoriAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { blog_kategori: currentTable } });
var currentForm, currentPageID;
var fblog_kategoriadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fblog_kategoriadd = new ew.Form("fblog_kategoriadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fblog_kategoriadd;

    // Add fields
    var fields = currentTable.fields;
    fblog_kategoriadd.addFields([
        ["blogKatGjuha", [fields.blogKatGjuha.visible && fields.blogKatGjuha.required ? ew.Validators.required(fields.blogKatGjuha.caption) : null], fields.blogKatGjuha.isInvalid],
        ["blogKatEmertimi", [fields.blogKatEmertimi.visible && fields.blogKatEmertimi.required ? ew.Validators.required(fields.blogKatEmertimi.caption) : null], fields.blogKatEmertimi.isInvalid]
    ]);

    // Form_CustomValidate
    fblog_kategoriadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fblog_kategoriadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fblog_kategoriadd.lists.blogKatGjuha = <?= $Page->blogKatGjuha->toClientList($Page) ?>;
    loadjs.done("fblog_kategoriadd");
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
<form name="fblog_kategoriadd" id="fblog_kategoriadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog_kategori">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->blogKatGjuha->Visible) { // blogKatGjuha ?>
    <div id="r_blogKatGjuha"<?= $Page->blogKatGjuha->rowAttributes() ?>>
        <label id="elh_blog_kategori_blogKatGjuha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogKatGjuha->caption() ?><?= $Page->blogKatGjuha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogKatGjuha->cellAttributes() ?>>
<span id="el_blog_kategori_blogKatGjuha">
<template id="tp_x_blogKatGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="blog_kategori" data-field="x_blogKatGjuha" name="x_blogKatGjuha" id="x_blogKatGjuha"<?= $Page->blogKatGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_blogKatGjuha" class="ew-item-list"></div>
<selection-list hidden
    id="x_blogKatGjuha"
    name="x_blogKatGjuha"
    value="<?= HtmlEncode($Page->blogKatGjuha->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_blogKatGjuha"
    data-bs-target="dsl_x_blogKatGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->blogKatGjuha->isInvalidClass() ?>"
    data-table="blog_kategori"
    data-field="x_blogKatGjuha"
    data-value-separator="<?= $Page->blogKatGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->blogKatGjuha->editAttributes() ?>></selection-list>
<?= $Page->blogKatGjuha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blogKatGjuha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blogKatEmertimi->Visible) { // blogKatEmertimi ?>
    <div id="r_blogKatEmertimi"<?= $Page->blogKatEmertimi->rowAttributes() ?>>
        <label id="elh_blog_kategori_blogKatEmertimi" for="x_blogKatEmertimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogKatEmertimi->caption() ?><?= $Page->blogKatEmertimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogKatEmertimi->cellAttributes() ?>>
<span id="el_blog_kategori_blogKatEmertimi">
<input type="<?= $Page->blogKatEmertimi->getInputTextType() ?>" name="x_blogKatEmertimi" id="x_blogKatEmertimi" data-table="blog_kategori" data-field="x_blogKatEmertimi" value="<?= $Page->blogKatEmertimi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->blogKatEmertimi->getPlaceHolder()) ?>"<?= $Page->blogKatEmertimi->editAttributes() ?> aria-describedby="x_blogKatEmertimi_help">
<?= $Page->blogKatEmertimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blogKatEmertimi->getErrorMessage() ?></div>
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
    ew.addEventHandlers("blog_kategori");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
