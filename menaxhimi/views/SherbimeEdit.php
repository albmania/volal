<?php

namespace PHPMaker2022\volalservice;

// Page object
$SherbimeEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { sherbime: currentTable } });
var currentForm, currentPageID;
var fsherbimeedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsherbimeedit = new ew.Form("fsherbimeedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fsherbimeedit;

    // Add fields
    var fields = currentTable.fields;
    fsherbimeedit.addFields([
        ["sherbimeID", [fields.sherbimeID.visible && fields.sherbimeID.required ? ew.Validators.required(fields.sherbimeID.caption) : null], fields.sherbimeID.isInvalid],
        ["sherbimeEmertimi_sq", [fields.sherbimeEmertimi_sq.visible && fields.sherbimeEmertimi_sq.required ? ew.Validators.required(fields.sherbimeEmertimi_sq.caption) : null], fields.sherbimeEmertimi_sq.isInvalid],
        ["sherbimeTxt_sq", [fields.sherbimeTxt_sq.visible && fields.sherbimeTxt_sq.required ? ew.Validators.required(fields.sherbimeTxt_sq.caption) : null], fields.sherbimeTxt_sq.isInvalid],
        ["sherbimeCmimi", [fields.sherbimeCmimi.visible && fields.sherbimeCmimi.required ? ew.Validators.required(fields.sherbimeCmimi.caption) : null, ew.Validators.float], fields.sherbimeCmimi.isInvalid],
        ["sherbimeEmertimi_en", [fields.sherbimeEmertimi_en.visible && fields.sherbimeEmertimi_en.required ? ew.Validators.required(fields.sherbimeEmertimi_en.caption) : null], fields.sherbimeEmertimi_en.isInvalid],
        ["sherbimeTxt_en", [fields.sherbimeTxt_en.visible && fields.sherbimeTxt_en.required ? ew.Validators.required(fields.sherbimeTxt_en.caption) : null], fields.sherbimeTxt_en.isInvalid],
        ["sherbimeFoto", [fields.sherbimeFoto.visible && fields.sherbimeFoto.required ? ew.Validators.fileRequired(fields.sherbimeFoto.caption) : null], fields.sherbimeFoto.isInvalid],
        ["sherbimeIkona", [fields.sherbimeIkona.visible && fields.sherbimeIkona.required ? ew.Validators.required(fields.sherbimeIkona.caption) : null], fields.sherbimeIkona.isInvalid],
        ["sherbimeIndex", [fields.sherbimeIndex.visible && fields.sherbimeIndex.required ? ew.Validators.required(fields.sherbimeIndex.caption) : null], fields.sherbimeIndex.isInvalid]
    ]);

    // Form_CustomValidate
    fsherbimeedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fsherbimeedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fsherbimeedit.lists.sherbimeIndex = <?= $Page->sherbimeIndex->toClientList($Page) ?>;
    loadjs.done("fsherbimeedit");
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
<form name="fsherbimeedit" id="fsherbimeedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="sherbime">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->sherbimeID->Visible) { // sherbimeID ?>
    <div id="r_sherbimeID"<?= $Page->sherbimeID->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeID->caption() ?><?= $Page->sherbimeID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeID->cellAttributes() ?>>
<span id="el_sherbime_sherbimeID">
<span<?= $Page->sherbimeID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->sherbimeID->getDisplayValue($Page->sherbimeID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="sherbime" data-field="x_sherbimeID" data-hidden="1" name="x_sherbimeID" id="x_sherbimeID" value="<?= HtmlEncode($Page->sherbimeID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_sq->Visible) { // sherbimeEmertimi_sq ?>
    <div id="r_sherbimeEmertimi_sq"<?= $Page->sherbimeEmertimi_sq->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeEmertimi_sq" for="x_sherbimeEmertimi_sq" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeEmertimi_sq->caption() ?><?= $Page->sherbimeEmertimi_sq->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeEmertimi_sq->cellAttributes() ?>>
<span id="el_sherbime_sherbimeEmertimi_sq">
<input type="<?= $Page->sherbimeEmertimi_sq->getInputTextType() ?>" name="x_sherbimeEmertimi_sq" id="x_sherbimeEmertimi_sq" data-table="sherbime" data-field="x_sherbimeEmertimi_sq" value="<?= $Page->sherbimeEmertimi_sq->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->sherbimeEmertimi_sq->getPlaceHolder()) ?>"<?= $Page->sherbimeEmertimi_sq->editAttributes() ?> aria-describedby="x_sherbimeEmertimi_sq_help">
<?= $Page->sherbimeEmertimi_sq->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sherbimeEmertimi_sq->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sherbimeTxt_sq->Visible) { // sherbimeTxt_sq ?>
    <div id="r_sherbimeTxt_sq"<?= $Page->sherbimeTxt_sq->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeTxt_sq" for="x_sherbimeTxt_sq" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeTxt_sq->caption() ?><?= $Page->sherbimeTxt_sq->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeTxt_sq->cellAttributes() ?>>
<span id="el_sherbime_sherbimeTxt_sq">
<input type="<?= $Page->sherbimeTxt_sq->getInputTextType() ?>" name="x_sherbimeTxt_sq" id="x_sherbimeTxt_sq" data-table="sherbime" data-field="x_sherbimeTxt_sq" value="<?= $Page->sherbimeTxt_sq->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->sherbimeTxt_sq->getPlaceHolder()) ?>"<?= $Page->sherbimeTxt_sq->editAttributes() ?> aria-describedby="x_sherbimeTxt_sq_help">
<?= $Page->sherbimeTxt_sq->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sherbimeTxt_sq->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sherbimeCmimi->Visible) { // sherbimeCmimi ?>
    <div id="r_sherbimeCmimi"<?= $Page->sherbimeCmimi->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeCmimi" for="x_sherbimeCmimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeCmimi->caption() ?><?= $Page->sherbimeCmimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeCmimi->cellAttributes() ?>>
<span id="el_sherbime_sherbimeCmimi">
<input type="<?= $Page->sherbimeCmimi->getInputTextType() ?>" name="x_sherbimeCmimi" id="x_sherbimeCmimi" data-table="sherbime" data-field="x_sherbimeCmimi" value="<?= $Page->sherbimeCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->sherbimeCmimi->getPlaceHolder()) ?>"<?= $Page->sherbimeCmimi->editAttributes() ?> aria-describedby="x_sherbimeCmimi_help">
<?= $Page->sherbimeCmimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sherbimeCmimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_en->Visible) { // sherbimeEmertimi_en ?>
    <div id="r_sherbimeEmertimi_en"<?= $Page->sherbimeEmertimi_en->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeEmertimi_en" for="x_sherbimeEmertimi_en" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeEmertimi_en->caption() ?><?= $Page->sherbimeEmertimi_en->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeEmertimi_en->cellAttributes() ?>>
<span id="el_sherbime_sherbimeEmertimi_en">
<input type="<?= $Page->sherbimeEmertimi_en->getInputTextType() ?>" name="x_sherbimeEmertimi_en" id="x_sherbimeEmertimi_en" data-table="sherbime" data-field="x_sherbimeEmertimi_en" value="<?= $Page->sherbimeEmertimi_en->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->sherbimeEmertimi_en->getPlaceHolder()) ?>"<?= $Page->sherbimeEmertimi_en->editAttributes() ?> aria-describedby="x_sherbimeEmertimi_en_help">
<?= $Page->sherbimeEmertimi_en->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sherbimeEmertimi_en->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sherbimeTxt_en->Visible) { // sherbimeTxt_en ?>
    <div id="r_sherbimeTxt_en"<?= $Page->sherbimeTxt_en->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeTxt_en" for="x_sherbimeTxt_en" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeTxt_en->caption() ?><?= $Page->sherbimeTxt_en->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeTxt_en->cellAttributes() ?>>
<span id="el_sherbime_sherbimeTxt_en">
<input type="<?= $Page->sherbimeTxt_en->getInputTextType() ?>" name="x_sherbimeTxt_en" id="x_sherbimeTxt_en" data-table="sherbime" data-field="x_sherbimeTxt_en" value="<?= $Page->sherbimeTxt_en->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->sherbimeTxt_en->getPlaceHolder()) ?>"<?= $Page->sherbimeTxt_en->editAttributes() ?> aria-describedby="x_sherbimeTxt_en_help">
<?= $Page->sherbimeTxt_en->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sherbimeTxt_en->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sherbimeFoto->Visible) { // sherbimeFoto ?>
    <div id="r_sherbimeFoto"<?= $Page->sherbimeFoto->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeFoto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeFoto->caption() ?><?= $Page->sherbimeFoto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeFoto->cellAttributes() ?>>
<span id="el_sherbime_sherbimeFoto">
<div id="fd_x_sherbimeFoto" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->sherbimeFoto->title() ?>" data-table="sherbime" data-field="x_sherbimeFoto" name="x_sherbimeFoto" id="x_sherbimeFoto" lang="<?= CurrentLanguageID() ?>"<?= $Page->sherbimeFoto->editAttributes() ?> aria-describedby="x_sherbimeFoto_help"<?= ($Page->sherbimeFoto->ReadOnly || $Page->sherbimeFoto->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
</div>
<?= $Page->sherbimeFoto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sherbimeFoto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_sherbimeFoto" id= "fn_x_sherbimeFoto" value="<?= $Page->sherbimeFoto->Upload->FileName ?>">
<input type="hidden" name="fa_x_sherbimeFoto" id= "fa_x_sherbimeFoto" value="<?= (Post("fa_x_sherbimeFoto") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_sherbimeFoto" id= "fs_x_sherbimeFoto" value="255">
<input type="hidden" name="fx_x_sherbimeFoto" id= "fx_x_sherbimeFoto" value="<?= $Page->sherbimeFoto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_sherbimeFoto" id= "fm_x_sherbimeFoto" value="<?= $Page->sherbimeFoto->UploadMaxFileSize ?>">
<table id="ft_x_sherbimeFoto" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sherbimeIkona->Visible) { // sherbimeIkona ?>
    <div id="r_sherbimeIkona"<?= $Page->sherbimeIkona->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeIkona" for="x_sherbimeIkona" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeIkona->caption() ?><?= $Page->sherbimeIkona->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeIkona->cellAttributes() ?>>
<span id="el_sherbime_sherbimeIkona">
<input type="<?= $Page->sherbimeIkona->getInputTextType() ?>" name="x_sherbimeIkona" id="x_sherbimeIkona" data-table="sherbime" data-field="x_sherbimeIkona" value="<?= $Page->sherbimeIkona->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->sherbimeIkona->getPlaceHolder()) ?>"<?= $Page->sherbimeIkona->editAttributes() ?> aria-describedby="x_sherbimeIkona_help">
<?= $Page->sherbimeIkona->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sherbimeIkona->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->sherbimeIndex->Visible) { // sherbimeIndex ?>
    <div id="r_sherbimeIndex"<?= $Page->sherbimeIndex->rowAttributes() ?>>
        <label id="elh_sherbime_sherbimeIndex" class="<?= $Page->LeftColumnClass ?>"><?= $Page->sherbimeIndex->caption() ?><?= $Page->sherbimeIndex->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->sherbimeIndex->cellAttributes() ?>>
<span id="el_sherbime_sherbimeIndex">
<template id="tp_x_sherbimeIndex">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="sherbime" data-field="x_sherbimeIndex" name="x_sherbimeIndex" id="x_sherbimeIndex"<?= $Page->sherbimeIndex->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_sherbimeIndex" class="ew-item-list"></div>
<selection-list hidden
    id="x_sherbimeIndex"
    name="x_sherbimeIndex"
    value="<?= HtmlEncode($Page->sherbimeIndex->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_sherbimeIndex"
    data-bs-target="dsl_x_sherbimeIndex"
    data-repeatcolumn="5"
    class="form-control<?= $Page->sherbimeIndex->isInvalidClass() ?>"
    data-table="sherbime"
    data-field="x_sherbimeIndex"
    data-value-separator="<?= $Page->sherbimeIndex->displayValueSeparatorAttribute() ?>"
    <?= $Page->sherbimeIndex->editAttributes() ?>></selection-list>
<?= $Page->sherbimeIndex->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->sherbimeIndex->getErrorMessage() ?></div>
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
    ew.addEventHandlers("sherbime");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
