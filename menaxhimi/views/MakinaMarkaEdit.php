<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaMarkaEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_marka: currentTable } });
var currentForm, currentPageID;
var fmakina_markaedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_markaedit = new ew.Form("fmakina_markaedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fmakina_markaedit;

    // Add fields
    var fields = currentTable.fields;
    fmakina_markaedit.addFields([
        ["mmarkaID", [fields.mmarkaID.visible && fields.mmarkaID.required ? ew.Validators.required(fields.mmarkaID.caption) : null], fields.mmarkaID.isInvalid],
        ["mmarkaMarka", [fields.mmarkaMarka.visible && fields.mmarkaMarka.required ? ew.Validators.required(fields.mmarkaMarka.caption) : null], fields.mmarkaMarka.isInvalid],
        ["mmarkaLogo", [fields.mmarkaLogo.visible && fields.mmarkaLogo.required ? ew.Validators.fileRequired(fields.mmarkaLogo.caption) : null], fields.mmarkaLogo.isInvalid]
    ]);

    // Form_CustomValidate
    fmakina_markaedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_markaedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fmakina_markaedit");
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
<form name="fmakina_markaedit" id="fmakina_markaedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_marka">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->mmarkaID->Visible) { // mmarkaID ?>
    <div id="r_mmarkaID"<?= $Page->mmarkaID->rowAttributes() ?>>
        <label id="elh_makina_marka_mmarkaID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mmarkaID->caption() ?><?= $Page->mmarkaID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mmarkaID->cellAttributes() ?>>
<span id="el_makina_marka_mmarkaID">
<span<?= $Page->mmarkaID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->mmarkaID->getDisplayValue($Page->mmarkaID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_marka" data-field="x_mmarkaID" data-hidden="1" name="x_mmarkaID" id="x_mmarkaID" value="<?= HtmlEncode($Page->mmarkaID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mmarkaMarka->Visible) { // mmarkaMarka ?>
    <div id="r_mmarkaMarka"<?= $Page->mmarkaMarka->rowAttributes() ?>>
        <label id="elh_makina_marka_mmarkaMarka" for="x_mmarkaMarka" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mmarkaMarka->caption() ?><?= $Page->mmarkaMarka->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mmarkaMarka->cellAttributes() ?>>
<span id="el_makina_marka_mmarkaMarka">
<input type="<?= $Page->mmarkaMarka->getInputTextType() ?>" name="x_mmarkaMarka" id="x_mmarkaMarka" data-table="makina_marka" data-field="x_mmarkaMarka" value="<?= $Page->mmarkaMarka->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->mmarkaMarka->getPlaceHolder()) ?>"<?= $Page->mmarkaMarka->editAttributes() ?> aria-describedby="x_mmarkaMarka_help">
<?= $Page->mmarkaMarka->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mmarkaMarka->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mmarkaLogo->Visible) { // mmarkaLogo ?>
    <div id="r_mmarkaLogo"<?= $Page->mmarkaLogo->rowAttributes() ?>>
        <label id="elh_makina_marka_mmarkaLogo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mmarkaLogo->caption() ?><?= $Page->mmarkaLogo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mmarkaLogo->cellAttributes() ?>>
<span id="el_makina_marka_mmarkaLogo">
<div id="fd_x_mmarkaLogo" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->mmarkaLogo->title() ?>" data-table="makina_marka" data-field="x_mmarkaLogo" name="x_mmarkaLogo" id="x_mmarkaLogo" lang="<?= CurrentLanguageID() ?>"<?= $Page->mmarkaLogo->editAttributes() ?> aria-describedby="x_mmarkaLogo_help"<?= ($Page->mmarkaLogo->ReadOnly || $Page->mmarkaLogo->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
</div>
<?= $Page->mmarkaLogo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mmarkaLogo->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_mmarkaLogo" id= "fn_x_mmarkaLogo" value="<?= $Page->mmarkaLogo->Upload->FileName ?>">
<input type="hidden" name="fa_x_mmarkaLogo" id= "fa_x_mmarkaLogo" value="<?= (Post("fa_x_mmarkaLogo") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_mmarkaLogo" id= "fs_x_mmarkaLogo" value="255">
<input type="hidden" name="fx_x_mmarkaLogo" id= "fx_x_mmarkaLogo" value="<?= $Page->mmarkaLogo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_mmarkaLogo" id= "fm_x_mmarkaLogo" value="<?= $Page->mmarkaLogo->UploadMaxFileSize ?>">
<table id="ft_x_mmarkaLogo" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
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
    ew.addEventHandlers("makina_marka");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
