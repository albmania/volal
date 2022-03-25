<?php

namespace PHPMaker2022\volalservice;

// Page object
$SlideAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { slide: currentTable } });
var currentForm, currentPageID;
var fslideadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fslideadd = new ew.Form("fslideadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fslideadd;

    // Add fields
    var fields = currentTable.fields;
    fslideadd.addFields([
        ["slideGjuha", [fields.slideGjuha.visible && fields.slideGjuha.required ? ew.Validators.required(fields.slideGjuha.caption) : null], fields.slideGjuha.isInvalid],
        ["slideFoto", [fields.slideFoto.visible && fields.slideFoto.required ? ew.Validators.fileRequired(fields.slideFoto.caption) : null], fields.slideFoto.isInvalid],
        ["slideTxt1", [fields.slideTxt1.visible && fields.slideTxt1.required ? ew.Validators.required(fields.slideTxt1.caption) : null], fields.slideTxt1.isInvalid],
        ["slideTxt2", [fields.slideTxt2.visible && fields.slideTxt2.required ? ew.Validators.required(fields.slideTxt2.caption) : null], fields.slideTxt2.isInvalid],
        ["slideTxt3", [fields.slideTxt3.visible && fields.slideTxt3.required ? ew.Validators.required(fields.slideTxt3.caption) : null], fields.slideTxt3.isInvalid],
        ["slideButonTxt", [fields.slideButonTxt.visible && fields.slideButonTxt.required ? ew.Validators.required(fields.slideButonTxt.caption) : null], fields.slideButonTxt.isInvalid],
        ["slideLink", [fields.slideLink.visible && fields.slideLink.required ? ew.Validators.required(fields.slideLink.caption) : null], fields.slideLink.isInvalid],
        ["slideTarget", [fields.slideTarget.visible && fields.slideTarget.required ? ew.Validators.required(fields.slideTarget.caption) : null], fields.slideTarget.isInvalid],
        ["slideRadha", [fields.slideRadha.visible && fields.slideRadha.required ? ew.Validators.required(fields.slideRadha.caption) : null, ew.Validators.integer], fields.slideRadha.isInvalid],
        ["slideAktiv", [fields.slideAktiv.visible && fields.slideAktiv.required ? ew.Validators.required(fields.slideAktiv.caption) : null], fields.slideAktiv.isInvalid],
        ["slideAutori", [fields.slideAutori.visible && fields.slideAutori.required ? ew.Validators.required(fields.slideAutori.caption) : null], fields.slideAutori.isInvalid],
        ["slideKrijuar", [fields.slideKrijuar.visible && fields.slideKrijuar.required ? ew.Validators.required(fields.slideKrijuar.caption) : null], fields.slideKrijuar.isInvalid]
    ]);

    // Form_CustomValidate
    fslideadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fslideadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fslideadd.lists.slideGjuha = <?= $Page->slideGjuha->toClientList($Page) ?>;
    fslideadd.lists.slideTarget = <?= $Page->slideTarget->toClientList($Page) ?>;
    fslideadd.lists.slideAktiv = <?= $Page->slideAktiv->toClientList($Page) ?>;
    fslideadd.lists.slideAutori = <?= $Page->slideAutori->toClientList($Page) ?>;
    loadjs.done("fslideadd");
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
<form name="fslideadd" id="fslideadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="slide">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->slideGjuha->Visible) { // slideGjuha ?>
    <div id="r_slideGjuha"<?= $Page->slideGjuha->rowAttributes() ?>>
        <label id="elh_slide_slideGjuha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideGjuha->caption() ?><?= $Page->slideGjuha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideGjuha->cellAttributes() ?>>
<span id="el_slide_slideGjuha">
<template id="tp_x_slideGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="slide" data-field="x_slideGjuha" name="x_slideGjuha" id="x_slideGjuha"<?= $Page->slideGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_slideGjuha" class="ew-item-list"></div>
<selection-list hidden
    id="x_slideGjuha"
    name="x_slideGjuha"
    value="<?= HtmlEncode($Page->slideGjuha->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_slideGjuha"
    data-bs-target="dsl_x_slideGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->slideGjuha->isInvalidClass() ?>"
    data-table="slide"
    data-field="x_slideGjuha"
    data-value-separator="<?= $Page->slideGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->slideGjuha->editAttributes() ?>></selection-list>
<?= $Page->slideGjuha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideGjuha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideFoto->Visible) { // slideFoto ?>
    <div id="r_slideFoto"<?= $Page->slideFoto->rowAttributes() ?>>
        <label id="elh_slide_slideFoto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideFoto->caption() ?><?= $Page->slideFoto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideFoto->cellAttributes() ?>>
<span id="el_slide_slideFoto">
<div id="fd_x_slideFoto" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->slideFoto->title() ?>" data-table="slide" data-field="x_slideFoto" name="x_slideFoto" id="x_slideFoto" lang="<?= CurrentLanguageID() ?>"<?= $Page->slideFoto->editAttributes() ?> aria-describedby="x_slideFoto_help"<?= ($Page->slideFoto->ReadOnly || $Page->slideFoto->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
</div>
<?= $Page->slideFoto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideFoto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_slideFoto" id= "fn_x_slideFoto" value="<?= $Page->slideFoto->Upload->FileName ?>">
<input type="hidden" name="fa_x_slideFoto" id= "fa_x_slideFoto" value="0">
<input type="hidden" name="fs_x_slideFoto" id= "fs_x_slideFoto" value="255">
<input type="hidden" name="fx_x_slideFoto" id= "fx_x_slideFoto" value="<?= $Page->slideFoto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_slideFoto" id= "fm_x_slideFoto" value="<?= $Page->slideFoto->UploadMaxFileSize ?>">
<table id="ft_x_slideFoto" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideTxt1->Visible) { // slideTxt1 ?>
    <div id="r_slideTxt1"<?= $Page->slideTxt1->rowAttributes() ?>>
        <label id="elh_slide_slideTxt1" for="x_slideTxt1" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideTxt1->caption() ?><?= $Page->slideTxt1->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideTxt1->cellAttributes() ?>>
<span id="el_slide_slideTxt1">
<input type="<?= $Page->slideTxt1->getInputTextType() ?>" name="x_slideTxt1" id="x_slideTxt1" data-table="slide" data-field="x_slideTxt1" value="<?= $Page->slideTxt1->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->slideTxt1->getPlaceHolder()) ?>"<?= $Page->slideTxt1->editAttributes() ?> aria-describedby="x_slideTxt1_help">
<?= $Page->slideTxt1->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideTxt1->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideTxt2->Visible) { // slideTxt2 ?>
    <div id="r_slideTxt2"<?= $Page->slideTxt2->rowAttributes() ?>>
        <label id="elh_slide_slideTxt2" for="x_slideTxt2" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideTxt2->caption() ?><?= $Page->slideTxt2->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideTxt2->cellAttributes() ?>>
<span id="el_slide_slideTxt2">
<input type="<?= $Page->slideTxt2->getInputTextType() ?>" name="x_slideTxt2" id="x_slideTxt2" data-table="slide" data-field="x_slideTxt2" value="<?= $Page->slideTxt2->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->slideTxt2->getPlaceHolder()) ?>"<?= $Page->slideTxt2->editAttributes() ?> aria-describedby="x_slideTxt2_help">
<?= $Page->slideTxt2->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideTxt2->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideTxt3->Visible) { // slideTxt3 ?>
    <div id="r_slideTxt3"<?= $Page->slideTxt3->rowAttributes() ?>>
        <label id="elh_slide_slideTxt3" for="x_slideTxt3" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideTxt3->caption() ?><?= $Page->slideTxt3->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideTxt3->cellAttributes() ?>>
<span id="el_slide_slideTxt3">
<input type="<?= $Page->slideTxt3->getInputTextType() ?>" name="x_slideTxt3" id="x_slideTxt3" data-table="slide" data-field="x_slideTxt3" value="<?= $Page->slideTxt3->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->slideTxt3->getPlaceHolder()) ?>"<?= $Page->slideTxt3->editAttributes() ?> aria-describedby="x_slideTxt3_help">
<?= $Page->slideTxt3->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideTxt3->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideButonTxt->Visible) { // slideButonTxt ?>
    <div id="r_slideButonTxt"<?= $Page->slideButonTxt->rowAttributes() ?>>
        <label id="elh_slide_slideButonTxt" for="x_slideButonTxt" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideButonTxt->caption() ?><?= $Page->slideButonTxt->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideButonTxt->cellAttributes() ?>>
<span id="el_slide_slideButonTxt">
<input type="<?= $Page->slideButonTxt->getInputTextType() ?>" name="x_slideButonTxt" id="x_slideButonTxt" data-table="slide" data-field="x_slideButonTxt" value="<?= $Page->slideButonTxt->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->slideButonTxt->getPlaceHolder()) ?>"<?= $Page->slideButonTxt->editAttributes() ?> aria-describedby="x_slideButonTxt_help">
<?= $Page->slideButonTxt->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideButonTxt->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideLink->Visible) { // slideLink ?>
    <div id="r_slideLink"<?= $Page->slideLink->rowAttributes() ?>>
        <label id="elh_slide_slideLink" for="x_slideLink" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideLink->caption() ?><?= $Page->slideLink->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideLink->cellAttributes() ?>>
<span id="el_slide_slideLink">
<input type="<?= $Page->slideLink->getInputTextType() ?>" name="x_slideLink" id="x_slideLink" data-table="slide" data-field="x_slideLink" value="<?= $Page->slideLink->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->slideLink->getPlaceHolder()) ?>"<?= $Page->slideLink->editAttributes() ?> aria-describedby="x_slideLink_help">
<?= $Page->slideLink->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideLink->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideTarget->Visible) { // slideTarget ?>
    <div id="r_slideTarget"<?= $Page->slideTarget->rowAttributes() ?>>
        <label id="elh_slide_slideTarget" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideTarget->caption() ?><?= $Page->slideTarget->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideTarget->cellAttributes() ?>>
<span id="el_slide_slideTarget">
<template id="tp_x_slideTarget">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="slide" data-field="x_slideTarget" name="x_slideTarget" id="x_slideTarget"<?= $Page->slideTarget->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_slideTarget" class="ew-item-list"></div>
<selection-list hidden
    id="x_slideTarget"
    name="x_slideTarget"
    value="<?= HtmlEncode($Page->slideTarget->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_slideTarget"
    data-bs-target="dsl_x_slideTarget"
    data-repeatcolumn="5"
    class="form-control<?= $Page->slideTarget->isInvalidClass() ?>"
    data-table="slide"
    data-field="x_slideTarget"
    data-value-separator="<?= $Page->slideTarget->displayValueSeparatorAttribute() ?>"
    <?= $Page->slideTarget->editAttributes() ?>></selection-list>
<?= $Page->slideTarget->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideTarget->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideRadha->Visible) { // slideRadha ?>
    <div id="r_slideRadha"<?= $Page->slideRadha->rowAttributes() ?>>
        <label id="elh_slide_slideRadha" for="x_slideRadha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideRadha->caption() ?><?= $Page->slideRadha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideRadha->cellAttributes() ?>>
<span id="el_slide_slideRadha">
<input type="<?= $Page->slideRadha->getInputTextType() ?>" name="x_slideRadha" id="x_slideRadha" data-table="slide" data-field="x_slideRadha" value="<?= $Page->slideRadha->EditValue ?>" size="5" placeholder="<?= HtmlEncode($Page->slideRadha->getPlaceHolder()) ?>"<?= $Page->slideRadha->editAttributes() ?> aria-describedby="x_slideRadha_help">
<?= $Page->slideRadha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideRadha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->slideAktiv->Visible) { // slideAktiv ?>
    <div id="r_slideAktiv"<?= $Page->slideAktiv->rowAttributes() ?>>
        <label id="elh_slide_slideAktiv" class="<?= $Page->LeftColumnClass ?>"><?= $Page->slideAktiv->caption() ?><?= $Page->slideAktiv->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->slideAktiv->cellAttributes() ?>>
<span id="el_slide_slideAktiv">
<template id="tp_x_slideAktiv">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="slide" data-field="x_slideAktiv" name="x_slideAktiv" id="x_slideAktiv"<?= $Page->slideAktiv->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_slideAktiv" class="ew-item-list"></div>
<selection-list hidden
    id="x_slideAktiv"
    name="x_slideAktiv"
    value="<?= HtmlEncode($Page->slideAktiv->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_slideAktiv"
    data-bs-target="dsl_x_slideAktiv"
    data-repeatcolumn="5"
    class="form-control<?= $Page->slideAktiv->isInvalidClass() ?>"
    data-table="slide"
    data-field="x_slideAktiv"
    data-value-separator="<?= $Page->slideAktiv->displayValueSeparatorAttribute() ?>"
    <?= $Page->slideAktiv->editAttributes() ?>></selection-list>
<?= $Page->slideAktiv->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->slideAktiv->getErrorMessage() ?></div>
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
    ew.addEventHandlers("slide");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
