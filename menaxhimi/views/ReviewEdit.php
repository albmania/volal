<?php

namespace PHPMaker2022\volalservice;

// Page object
$ReviewEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { review: currentTable } });
var currentForm, currentPageID;
var freviewedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    freviewedit = new ew.Form("freviewedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = freviewedit;

    // Add fields
    var fields = currentTable.fields;
    freviewedit.addFields([
        ["reviewID", [fields.reviewID.visible && fields.reviewID.required ? ew.Validators.required(fields.reviewID.caption) : null], fields.reviewID.isInvalid],
        ["reviewGjuha", [fields.reviewGjuha.visible && fields.reviewGjuha.required ? ew.Validators.required(fields.reviewGjuha.caption) : null], fields.reviewGjuha.isInvalid],
        ["reviewEmri", [fields.reviewEmri.visible && fields.reviewEmri.required ? ew.Validators.required(fields.reviewEmri.caption) : null], fields.reviewEmri.isInvalid],
        ["reviewMakine", [fields.reviewMakine.visible && fields.reviewMakine.required ? ew.Validators.required(fields.reviewMakine.caption) : null], fields.reviewMakine.isInvalid],
        ["reviewPer", [fields.reviewPer.visible && fields.reviewPer.required ? ew.Validators.required(fields.reviewPer.caption) : null], fields.reviewPer.isInvalid],
        ["reviewFoto", [fields.reviewFoto.visible && fields.reviewFoto.required ? ew.Validators.fileRequired(fields.reviewFoto.caption) : null], fields.reviewFoto.isInvalid],
        ["reviewTxt", [fields.reviewTxt.visible && fields.reviewTxt.required ? ew.Validators.required(fields.reviewTxt.caption) : null], fields.reviewTxt.isInvalid],
        ["reviewDate", [fields.reviewDate.visible && fields.reviewDate.required ? ew.Validators.required(fields.reviewDate.caption) : null, ew.Validators.datetime(fields.reviewDate.clientFormatPattern)], fields.reviewDate.isInvalid],
        ["reviewAktiv", [fields.reviewAktiv.visible && fields.reviewAktiv.required ? ew.Validators.required(fields.reviewAktiv.caption) : null], fields.reviewAktiv.isInvalid],
        ["reviewRegjistruarNga", [fields.reviewRegjistruarNga.visible && fields.reviewRegjistruarNga.required ? ew.Validators.required(fields.reviewRegjistruarNga.caption) : null], fields.reviewRegjistruarNga.isInvalid]
    ]);

    // Form_CustomValidate
    freviewedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    freviewedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    freviewedit.lists.reviewGjuha = <?= $Page->reviewGjuha->toClientList($Page) ?>;
    freviewedit.lists.reviewPer = <?= $Page->reviewPer->toClientList($Page) ?>;
    freviewedit.lists.reviewAktiv = <?= $Page->reviewAktiv->toClientList($Page) ?>;
    loadjs.done("freviewedit");
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
<form name="freviewedit" id="freviewedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="review">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->reviewID->Visible) { // reviewID ?>
    <div id="r_reviewID"<?= $Page->reviewID->rowAttributes() ?>>
        <label id="elh_review_reviewID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewID->caption() ?><?= $Page->reviewID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewID->cellAttributes() ?>>
<span id="el_review_reviewID">
<span<?= $Page->reviewID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->reviewID->getDisplayValue($Page->reviewID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="review" data-field="x_reviewID" data-hidden="1" name="x_reviewID" id="x_reviewID" value="<?= HtmlEncode($Page->reviewID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reviewGjuha->Visible) { // reviewGjuha ?>
    <div id="r_reviewGjuha"<?= $Page->reviewGjuha->rowAttributes() ?>>
        <label id="elh_review_reviewGjuha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewGjuha->caption() ?><?= $Page->reviewGjuha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewGjuha->cellAttributes() ?>>
<span id="el_review_reviewGjuha">
<template id="tp_x_reviewGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="review" data-field="x_reviewGjuha" name="x_reviewGjuha" id="x_reviewGjuha"<?= $Page->reviewGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_reviewGjuha" class="ew-item-list"></div>
<selection-list hidden
    id="x_reviewGjuha"
    name="x_reviewGjuha"
    value="<?= HtmlEncode($Page->reviewGjuha->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_reviewGjuha"
    data-bs-target="dsl_x_reviewGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->reviewGjuha->isInvalidClass() ?>"
    data-table="review"
    data-field="x_reviewGjuha"
    data-value-separator="<?= $Page->reviewGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->reviewGjuha->editAttributes() ?>></selection-list>
<?= $Page->reviewGjuha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reviewGjuha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reviewEmri->Visible) { // reviewEmri ?>
    <div id="r_reviewEmri"<?= $Page->reviewEmri->rowAttributes() ?>>
        <label id="elh_review_reviewEmri" for="x_reviewEmri" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewEmri->caption() ?><?= $Page->reviewEmri->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewEmri->cellAttributes() ?>>
<span id="el_review_reviewEmri">
<input type="<?= $Page->reviewEmri->getInputTextType() ?>" name="x_reviewEmri" id="x_reviewEmri" data-table="review" data-field="x_reviewEmri" value="<?= $Page->reviewEmri->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->reviewEmri->getPlaceHolder()) ?>"<?= $Page->reviewEmri->editAttributes() ?> aria-describedby="x_reviewEmri_help">
<?= $Page->reviewEmri->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reviewEmri->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reviewMakine->Visible) { // reviewMakine ?>
    <div id="r_reviewMakine"<?= $Page->reviewMakine->rowAttributes() ?>>
        <label id="elh_review_reviewMakine" for="x_reviewMakine" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewMakine->caption() ?><?= $Page->reviewMakine->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewMakine->cellAttributes() ?>>
<span id="el_review_reviewMakine">
<input type="<?= $Page->reviewMakine->getInputTextType() ?>" name="x_reviewMakine" id="x_reviewMakine" data-table="review" data-field="x_reviewMakine" value="<?= $Page->reviewMakine->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->reviewMakine->getPlaceHolder()) ?>"<?= $Page->reviewMakine->editAttributes() ?> aria-describedby="x_reviewMakine_help">
<?= $Page->reviewMakine->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reviewMakine->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reviewPer->Visible) { // reviewPer ?>
    <div id="r_reviewPer"<?= $Page->reviewPer->rowAttributes() ?>>
        <label id="elh_review_reviewPer" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewPer->caption() ?><?= $Page->reviewPer->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewPer->cellAttributes() ?>>
<span id="el_review_reviewPer">
<template id="tp_x_reviewPer">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="review" data-field="x_reviewPer" name="x_reviewPer" id="x_reviewPer"<?= $Page->reviewPer->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_reviewPer" class="ew-item-list"></div>
<selection-list hidden
    id="x_reviewPer"
    name="x_reviewPer"
    value="<?= HtmlEncode($Page->reviewPer->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_reviewPer"
    data-bs-target="dsl_x_reviewPer"
    data-repeatcolumn="5"
    class="form-control<?= $Page->reviewPer->isInvalidClass() ?>"
    data-table="review"
    data-field="x_reviewPer"
    data-value-separator="<?= $Page->reviewPer->displayValueSeparatorAttribute() ?>"
    <?= $Page->reviewPer->editAttributes() ?>></selection-list>
<?= $Page->reviewPer->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reviewPer->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reviewFoto->Visible) { // reviewFoto ?>
    <div id="r_reviewFoto"<?= $Page->reviewFoto->rowAttributes() ?>>
        <label id="elh_review_reviewFoto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewFoto->caption() ?><?= $Page->reviewFoto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewFoto->cellAttributes() ?>>
<span id="el_review_reviewFoto">
<div id="fd_x_reviewFoto" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->reviewFoto->title() ?>" data-table="review" data-field="x_reviewFoto" name="x_reviewFoto" id="x_reviewFoto" lang="<?= CurrentLanguageID() ?>"<?= $Page->reviewFoto->editAttributes() ?> aria-describedby="x_reviewFoto_help"<?= ($Page->reviewFoto->ReadOnly || $Page->reviewFoto->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
</div>
<?= $Page->reviewFoto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reviewFoto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_reviewFoto" id= "fn_x_reviewFoto" value="<?= $Page->reviewFoto->Upload->FileName ?>">
<input type="hidden" name="fa_x_reviewFoto" id= "fa_x_reviewFoto" value="<?= (Post("fa_x_reviewFoto") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_reviewFoto" id= "fs_x_reviewFoto" value="255">
<input type="hidden" name="fx_x_reviewFoto" id= "fx_x_reviewFoto" value="<?= $Page->reviewFoto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_reviewFoto" id= "fm_x_reviewFoto" value="<?= $Page->reviewFoto->UploadMaxFileSize ?>">
<table id="ft_x_reviewFoto" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reviewTxt->Visible) { // reviewTxt ?>
    <div id="r_reviewTxt"<?= $Page->reviewTxt->rowAttributes() ?>>
        <label id="elh_review_reviewTxt" for="x_reviewTxt" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewTxt->caption() ?><?= $Page->reviewTxt->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewTxt->cellAttributes() ?>>
<span id="el_review_reviewTxt">
<input type="<?= $Page->reviewTxt->getInputTextType() ?>" name="x_reviewTxt" id="x_reviewTxt" data-table="review" data-field="x_reviewTxt" value="<?= $Page->reviewTxt->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->reviewTxt->getPlaceHolder()) ?>"<?= $Page->reviewTxt->editAttributes() ?> aria-describedby="x_reviewTxt_help">
<?= $Page->reviewTxt->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reviewTxt->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reviewDate->Visible) { // reviewDate ?>
    <div id="r_reviewDate"<?= $Page->reviewDate->rowAttributes() ?>>
        <label id="elh_review_reviewDate" for="x_reviewDate" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewDate->caption() ?><?= $Page->reviewDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewDate->cellAttributes() ?>>
<span id="el_review_reviewDate">
<input type="<?= $Page->reviewDate->getInputTextType() ?>" name="x_reviewDate" id="x_reviewDate" data-table="review" data-field="x_reviewDate" value="<?= $Page->reviewDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->reviewDate->getPlaceHolder()) ?>"<?= $Page->reviewDate->editAttributes() ?> aria-describedby="x_reviewDate_help">
<?= $Page->reviewDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reviewDate->getErrorMessage() ?></div>
<?php if (!$Page->reviewDate->ReadOnly && !$Page->reviewDate->Disabled && !isset($Page->reviewDate->EditAttrs["readonly"]) && !isset($Page->reviewDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["freviewedit", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("freviewedit", "x_reviewDate", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->reviewAktiv->Visible) { // reviewAktiv ?>
    <div id="r_reviewAktiv"<?= $Page->reviewAktiv->rowAttributes() ?>>
        <label id="elh_review_reviewAktiv" class="<?= $Page->LeftColumnClass ?>"><?= $Page->reviewAktiv->caption() ?><?= $Page->reviewAktiv->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->reviewAktiv->cellAttributes() ?>>
<span id="el_review_reviewAktiv">
<template id="tp_x_reviewAktiv">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="review" data-field="x_reviewAktiv" name="x_reviewAktiv" id="x_reviewAktiv"<?= $Page->reviewAktiv->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_reviewAktiv" class="ew-item-list"></div>
<selection-list hidden
    id="x_reviewAktiv"
    name="x_reviewAktiv"
    value="<?= HtmlEncode($Page->reviewAktiv->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_reviewAktiv"
    data-bs-target="dsl_x_reviewAktiv"
    data-repeatcolumn="5"
    class="form-control<?= $Page->reviewAktiv->isInvalidClass() ?>"
    data-table="review"
    data-field="x_reviewAktiv"
    data-value-separator="<?= $Page->reviewAktiv->displayValueSeparatorAttribute() ?>"
    <?= $Page->reviewAktiv->editAttributes() ?>></selection-list>
<?= $Page->reviewAktiv->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->reviewAktiv->getErrorMessage() ?></div>
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
    ew.addEventHandlers("review");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
