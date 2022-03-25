<?php

namespace PHPMaker2022\volalservice;

// Page object
$IndexTipsAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { index_tips: currentTable } });
var currentForm, currentPageID;
var findex_tipsadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    findex_tipsadd = new ew.Form("findex_tipsadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = findex_tipsadd;

    // Add fields
    var fields = currentTable.fields;
    findex_tipsadd.addFields([
        ["iTipsTeksti", [fields.iTipsTeksti.visible && fields.iTipsTeksti.required ? ew.Validators.required(fields.iTipsTeksti.caption) : null], fields.iTipsTeksti.isInvalid],
        ["iTipsAutori", [fields.iTipsAutori.visible && fields.iTipsAutori.required ? ew.Validators.required(fields.iTipsAutori.caption) : null], fields.iTipsAutori.isInvalid],
        ["iTipsKrijuar", [fields.iTipsKrijuar.visible && fields.iTipsKrijuar.required ? ew.Validators.required(fields.iTipsKrijuar.caption) : null], fields.iTipsKrijuar.isInvalid]
    ]);

    // Form_CustomValidate
    findex_tipsadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    findex_tipsadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("findex_tipsadd");
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
<form name="findex_tipsadd" id="findex_tipsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="index_tips">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->iTipsTeksti->Visible) { // iTipsTeksti ?>
    <div id="r_iTipsTeksti"<?= $Page->iTipsTeksti->rowAttributes() ?>>
        <label id="elh_index_tips_iTipsTeksti" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iTipsTeksti->caption() ?><?= $Page->iTipsTeksti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iTipsTeksti->cellAttributes() ?>>
<span id="el_index_tips_iTipsTeksti">
<?php $Page->iTipsTeksti->EditAttrs->appendClass("editor"); ?>
<textarea data-table="index_tips" data-field="x_iTipsTeksti" name="x_iTipsTeksti" id="x_iTipsTeksti" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->iTipsTeksti->getPlaceHolder()) ?>"<?= $Page->iTipsTeksti->editAttributes() ?> aria-describedby="x_iTipsTeksti_help"><?= $Page->iTipsTeksti->EditValue ?></textarea>
<?= $Page->iTipsTeksti->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iTipsTeksti->getErrorMessage() ?></div>
<script>
loadjs.ready(["findex_tipsadd", "editor"], function() {
    ew.createEditor("findex_tipsadd", "x_iTipsTeksti", 0, 0, <?= $Page->iTipsTeksti->ReadOnly || false ? "true" : "false" ?>);
});
</script>
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
    ew.addEventHandlers("index_tips");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
