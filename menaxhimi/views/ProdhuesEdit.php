<?php

namespace PHPMaker2022\volalservice;

// Page object
$ProdhuesEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { prodhues: currentTable } });
var currentForm, currentPageID;
var fprodhuesedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fprodhuesedit = new ew.Form("fprodhuesedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fprodhuesedit;

    // Add fields
    var fields = currentTable.fields;
    fprodhuesedit.addFields([
        ["prodhuesID", [fields.prodhuesID.visible && fields.prodhuesID.required ? ew.Validators.required(fields.prodhuesID.caption) : null], fields.prodhuesID.isInvalid],
        ["prodhuesEmri", [fields.prodhuesEmri.visible && fields.prodhuesEmri.required ? ew.Validators.required(fields.prodhuesEmri.caption) : null], fields.prodhuesEmri.isInvalid],
        ["prodhuesLogo", [fields.prodhuesLogo.visible && fields.prodhuesLogo.required ? ew.Validators.fileRequired(fields.prodhuesLogo.caption) : null], fields.prodhuesLogo.isInvalid],
        ["prodhuesPershkrim", [fields.prodhuesPershkrim.visible && fields.prodhuesPershkrim.required ? ew.Validators.required(fields.prodhuesPershkrim.caption) : null], fields.prodhuesPershkrim.isInvalid]
    ]);

    // Form_CustomValidate
    fprodhuesedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fprodhuesedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fprodhuesedit");
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
<form name="fprodhuesedit" id="fprodhuesedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="prodhues">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->prodhuesID->Visible) { // prodhuesID ?>
    <div id="r_prodhuesID"<?= $Page->prodhuesID->rowAttributes() ?>>
        <label id="elh_prodhues_prodhuesID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->prodhuesID->caption() ?><?= $Page->prodhuesID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->prodhuesID->cellAttributes() ?>>
<span id="el_prodhues_prodhuesID">
<span<?= $Page->prodhuesID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->prodhuesID->getDisplayValue($Page->prodhuesID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="prodhues" data-field="x_prodhuesID" data-hidden="1" name="x_prodhuesID" id="x_prodhuesID" value="<?= HtmlEncode($Page->prodhuesID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->prodhuesEmri->Visible) { // prodhuesEmri ?>
    <div id="r_prodhuesEmri"<?= $Page->prodhuesEmri->rowAttributes() ?>>
        <label id="elh_prodhues_prodhuesEmri" for="x_prodhuesEmri" class="<?= $Page->LeftColumnClass ?>"><?= $Page->prodhuesEmri->caption() ?><?= $Page->prodhuesEmri->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->prodhuesEmri->cellAttributes() ?>>
<span id="el_prodhues_prodhuesEmri">
<input type="<?= $Page->prodhuesEmri->getInputTextType() ?>" name="x_prodhuesEmri" id="x_prodhuesEmri" data-table="prodhues" data-field="x_prodhuesEmri" value="<?= $Page->prodhuesEmri->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->prodhuesEmri->getPlaceHolder()) ?>"<?= $Page->prodhuesEmri->editAttributes() ?> aria-describedby="x_prodhuesEmri_help">
<?= $Page->prodhuesEmri->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->prodhuesEmri->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->prodhuesLogo->Visible) { // prodhuesLogo ?>
    <div id="r_prodhuesLogo"<?= $Page->prodhuesLogo->rowAttributes() ?>>
        <label id="elh_prodhues_prodhuesLogo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->prodhuesLogo->caption() ?><?= $Page->prodhuesLogo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->prodhuesLogo->cellAttributes() ?>>
<span id="el_prodhues_prodhuesLogo">
<div id="fd_x_prodhuesLogo" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->prodhuesLogo->title() ?>" data-table="prodhues" data-field="x_prodhuesLogo" name="x_prodhuesLogo" id="x_prodhuesLogo" lang="<?= CurrentLanguageID() ?>"<?= $Page->prodhuesLogo->editAttributes() ?> aria-describedby="x_prodhuesLogo_help"<?= ($Page->prodhuesLogo->ReadOnly || $Page->prodhuesLogo->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
</div>
<?= $Page->prodhuesLogo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->prodhuesLogo->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_prodhuesLogo" id= "fn_x_prodhuesLogo" value="<?= $Page->prodhuesLogo->Upload->FileName ?>">
<input type="hidden" name="fa_x_prodhuesLogo" id= "fa_x_prodhuesLogo" value="<?= (Post("fa_x_prodhuesLogo") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_prodhuesLogo" id= "fs_x_prodhuesLogo" value="255">
<input type="hidden" name="fx_x_prodhuesLogo" id= "fx_x_prodhuesLogo" value="<?= $Page->prodhuesLogo->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_prodhuesLogo" id= "fm_x_prodhuesLogo" value="<?= $Page->prodhuesLogo->UploadMaxFileSize ?>">
<table id="ft_x_prodhuesLogo" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->prodhuesPershkrim->Visible) { // prodhuesPershkrim ?>
    <div id="r_prodhuesPershkrim"<?= $Page->prodhuesPershkrim->rowAttributes() ?>>
        <label id="elh_prodhues_prodhuesPershkrim" for="x_prodhuesPershkrim" class="<?= $Page->LeftColumnClass ?>"><?= $Page->prodhuesPershkrim->caption() ?><?= $Page->prodhuesPershkrim->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->prodhuesPershkrim->cellAttributes() ?>>
<span id="el_prodhues_prodhuesPershkrim">
<textarea data-table="prodhues" data-field="x_prodhuesPershkrim" name="x_prodhuesPershkrim" id="x_prodhuesPershkrim" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->prodhuesPershkrim->getPlaceHolder()) ?>"<?= $Page->prodhuesPershkrim->editAttributes() ?> aria-describedby="x_prodhuesPershkrim_help"><?= $Page->prodhuesPershkrim->EditValue ?></textarea>
<?= $Page->prodhuesPershkrim->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->prodhuesPershkrim->getErrorMessage() ?></div>
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
    ew.addEventHandlers("prodhues");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
