<?php

namespace PHPMaker2022\volalservice;

// Page object
$IndexTipsEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { index_tips: currentTable } });
var currentForm, currentPageID;
var findex_tipsedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    findex_tipsedit = new ew.Form("findex_tipsedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = findex_tipsedit;

    // Add fields
    var fields = currentTable.fields;
    findex_tipsedit.addFields([
        ["iTipsID", [fields.iTipsID.visible && fields.iTipsID.required ? ew.Validators.required(fields.iTipsID.caption) : null], fields.iTipsID.isInvalid],
        ["iTipsTeksti", [fields.iTipsTeksti.visible && fields.iTipsTeksti.required ? ew.Validators.required(fields.iTipsTeksti.caption) : null], fields.iTipsTeksti.isInvalid],
        ["iTipsAutori", [fields.iTipsAutori.visible && fields.iTipsAutori.required ? ew.Validators.required(fields.iTipsAutori.caption) : null], fields.iTipsAutori.isInvalid],
        ["iTipsAzhornuar", [fields.iTipsAzhornuar.visible && fields.iTipsAzhornuar.required ? ew.Validators.required(fields.iTipsAzhornuar.caption) : null], fields.iTipsAzhornuar.isInvalid]
    ]);

    // Form_CustomValidate
    findex_tipsedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    findex_tipsedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("findex_tipsedit");
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
<form name="findex_tipsedit" id="findex_tipsedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="index_tips">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->iTipsID->Visible) { // iTipsID ?>
    <div id="r_iTipsID"<?= $Page->iTipsID->rowAttributes() ?>>
        <label id="elh_index_tips_iTipsID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iTipsID->caption() ?><?= $Page->iTipsID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iTipsID->cellAttributes() ?>>
<span id="el_index_tips_iTipsID">
<span<?= $Page->iTipsID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->iTipsID->getDisplayValue($Page->iTipsID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="index_tips" data-field="x_iTipsID" data-hidden="1" name="x_iTipsID" id="x_iTipsID" value="<?= HtmlEncode($Page->iTipsID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
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
loadjs.ready(["findex_tipsedit", "editor"], function() {
    ew.createEditor("findex_tipsedit", "x_iTipsTeksti", 0, 0, <?= $Page->iTipsTeksti->ReadOnly || false ? "true" : "false" ?>);
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
    ew.addEventHandlers("index_tips");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
