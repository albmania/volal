<?php

namespace PHPMaker2022\volalservice;

// Page object
$FaqeEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { faqe: currentTable } });
var currentForm, currentPageID;
var ffaqeedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    ffaqeedit = new ew.Form("ffaqeedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = ffaqeedit;

    // Add fields
    var fields = currentTable.fields;
    ffaqeedit.addFields([
        ["faqeID", [fields.faqeID.visible && fields.faqeID.required ? ew.Validators.required(fields.faqeID.caption) : null], fields.faqeID.isInvalid],
        ["faqeEmri_sq", [fields.faqeEmri_sq.visible && fields.faqeEmri_sq.required ? ew.Validators.required(fields.faqeEmri_sq.caption) : null], fields.faqeEmri_sq.isInvalid],
        ["faqeTxt_sq", [fields.faqeTxt_sq.visible && fields.faqeTxt_sq.required ? ew.Validators.required(fields.faqeTxt_sq.caption) : null], fields.faqeTxt_sq.isInvalid],
        ["faqeEmri_en", [fields.faqeEmri_en.visible && fields.faqeEmri_en.required ? ew.Validators.required(fields.faqeEmri_en.caption) : null], fields.faqeEmri_en.isInvalid],
        ["faqeTxt_en", [fields.faqeTxt_en.visible && fields.faqeTxt_en.required ? ew.Validators.required(fields.faqeTxt_en.caption) : null], fields.faqeTxt_en.isInvalid],
        ["faqeFoto", [fields.faqeFoto.visible && fields.faqeFoto.required ? ew.Validators.fileRequired(fields.faqeFoto.caption) : null], fields.faqeFoto.isInvalid],
        ["faqeAutori", [fields.faqeAutori.visible && fields.faqeAutori.required ? ew.Validators.required(fields.faqeAutori.caption) : null], fields.faqeAutori.isInvalid],
        ["faqeAzhornuar", [fields.faqeAzhornuar.visible && fields.faqeAzhornuar.required ? ew.Validators.required(fields.faqeAzhornuar.caption) : null], fields.faqeAzhornuar.isInvalid]
    ]);

    // Form_CustomValidate
    ffaqeedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    ffaqeedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    ffaqeedit.lists.faqeAutori = <?= $Page->faqeAutori->toClientList($Page) ?>;
    loadjs.done("ffaqeedit");
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
<form name="ffaqeedit" id="ffaqeedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="faqe">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->faqeID->Visible) { // faqeID ?>
    <div id="r_faqeID"<?= $Page->faqeID->rowAttributes() ?>>
        <label id="elh_faqe_faqeID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->faqeID->caption() ?><?= $Page->faqeID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->faqeID->cellAttributes() ?>>
<span id="el_faqe_faqeID">
<span<?= $Page->faqeID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->faqeID->getDisplayValue($Page->faqeID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="faqe" data-field="x_faqeID" data-hidden="1" name="x_faqeID" id="x_faqeID" value="<?= HtmlEncode($Page->faqeID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->faqeEmri_sq->Visible) { // faqeEmri_sq ?>
    <div id="r_faqeEmri_sq"<?= $Page->faqeEmri_sq->rowAttributes() ?>>
        <label id="elh_faqe_faqeEmri_sq" for="x_faqeEmri_sq" class="<?= $Page->LeftColumnClass ?>"><?= $Page->faqeEmri_sq->caption() ?><?= $Page->faqeEmri_sq->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->faqeEmri_sq->cellAttributes() ?>>
<span id="el_faqe_faqeEmri_sq">
<input type="<?= $Page->faqeEmri_sq->getInputTextType() ?>" name="x_faqeEmri_sq" id="x_faqeEmri_sq" data-table="faqe" data-field="x_faqeEmri_sq" value="<?= $Page->faqeEmri_sq->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->faqeEmri_sq->getPlaceHolder()) ?>"<?= $Page->faqeEmri_sq->editAttributes() ?> aria-describedby="x_faqeEmri_sq_help">
<?= $Page->faqeEmri_sq->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->faqeEmri_sq->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->faqeTxt_sq->Visible) { // faqeTxt_sq ?>
    <div id="r_faqeTxt_sq"<?= $Page->faqeTxt_sq->rowAttributes() ?>>
        <label id="elh_faqe_faqeTxt_sq" class="<?= $Page->LeftColumnClass ?>"><?= $Page->faqeTxt_sq->caption() ?><?= $Page->faqeTxt_sq->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->faqeTxt_sq->cellAttributes() ?>>
<span id="el_faqe_faqeTxt_sq">
<?php $Page->faqeTxt_sq->EditAttrs->appendClass("editor"); ?>
<textarea data-table="faqe" data-field="x_faqeTxt_sq" name="x_faqeTxt_sq" id="x_faqeTxt_sq" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->faqeTxt_sq->getPlaceHolder()) ?>"<?= $Page->faqeTxt_sq->editAttributes() ?> aria-describedby="x_faqeTxt_sq_help"><?= $Page->faqeTxt_sq->EditValue ?></textarea>
<?= $Page->faqeTxt_sq->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->faqeTxt_sq->getErrorMessage() ?></div>
<script>
loadjs.ready(["ffaqeedit", "editor"], function() {
    ew.createEditor("ffaqeedit", "x_faqeTxt_sq", 35, 4, <?= $Page->faqeTxt_sq->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->faqeEmri_en->Visible) { // faqeEmri_en ?>
    <div id="r_faqeEmri_en"<?= $Page->faqeEmri_en->rowAttributes() ?>>
        <label id="elh_faqe_faqeEmri_en" for="x_faqeEmri_en" class="<?= $Page->LeftColumnClass ?>"><?= $Page->faqeEmri_en->caption() ?><?= $Page->faqeEmri_en->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->faqeEmri_en->cellAttributes() ?>>
<span id="el_faqe_faqeEmri_en">
<input type="<?= $Page->faqeEmri_en->getInputTextType() ?>" name="x_faqeEmri_en" id="x_faqeEmri_en" data-table="faqe" data-field="x_faqeEmri_en" value="<?= $Page->faqeEmri_en->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->faqeEmri_en->getPlaceHolder()) ?>"<?= $Page->faqeEmri_en->editAttributes() ?> aria-describedby="x_faqeEmri_en_help">
<?= $Page->faqeEmri_en->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->faqeEmri_en->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->faqeTxt_en->Visible) { // faqeTxt_en ?>
    <div id="r_faqeTxt_en"<?= $Page->faqeTxt_en->rowAttributes() ?>>
        <label id="elh_faqe_faqeTxt_en" class="<?= $Page->LeftColumnClass ?>"><?= $Page->faqeTxt_en->caption() ?><?= $Page->faqeTxt_en->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->faqeTxt_en->cellAttributes() ?>>
<span id="el_faqe_faqeTxt_en">
<?php $Page->faqeTxt_en->EditAttrs->appendClass("editor"); ?>
<textarea data-table="faqe" data-field="x_faqeTxt_en" name="x_faqeTxt_en" id="x_faqeTxt_en" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->faqeTxt_en->getPlaceHolder()) ?>"<?= $Page->faqeTxt_en->editAttributes() ?> aria-describedby="x_faqeTxt_en_help"><?= $Page->faqeTxt_en->EditValue ?></textarea>
<?= $Page->faqeTxt_en->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->faqeTxt_en->getErrorMessage() ?></div>
<script>
loadjs.ready(["ffaqeedit", "editor"], function() {
    ew.createEditor("ffaqeedit", "x_faqeTxt_en", 35, 4, <?= $Page->faqeTxt_en->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->faqeFoto->Visible) { // faqeFoto ?>
    <div id="r_faqeFoto"<?= $Page->faqeFoto->rowAttributes() ?>>
        <label id="elh_faqe_faqeFoto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->faqeFoto->caption() ?><?= $Page->faqeFoto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->faqeFoto->cellAttributes() ?>>
<span id="el_faqe_faqeFoto">
<div id="fd_x_faqeFoto" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->faqeFoto->title() ?>" data-table="faqe" data-field="x_faqeFoto" name="x_faqeFoto" id="x_faqeFoto" lang="<?= CurrentLanguageID() ?>" multiple<?= $Page->faqeFoto->editAttributes() ?> aria-describedby="x_faqeFoto_help"<?= ($Page->faqeFoto->ReadOnly || $Page->faqeFoto->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
</div>
<?= $Page->faqeFoto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->faqeFoto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_faqeFoto" id= "fn_x_faqeFoto" value="<?= $Page->faqeFoto->Upload->FileName ?>">
<input type="hidden" name="fa_x_faqeFoto" id= "fa_x_faqeFoto" value="<?= (Post("fa_x_faqeFoto") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_faqeFoto" id= "fs_x_faqeFoto" value="16777215">
<input type="hidden" name="fx_x_faqeFoto" id= "fx_x_faqeFoto" value="<?= $Page->faqeFoto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_faqeFoto" id= "fm_x_faqeFoto" value="<?= $Page->faqeFoto->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_faqeFoto" id= "fc_x_faqeFoto" value="<?= $Page->faqeFoto->UploadMaxFileCount ?>">
<table id="ft_x_faqeFoto" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
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
    ew.addEventHandlers("faqe");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
