<?php

namespace PHPMaker2022\volalservice;

// Page object
$BlogEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { blog: currentTable } });
var currentForm, currentPageID;
var fblogedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fblogedit = new ew.Form("fblogedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fblogedit;

    // Add fields
    var fields = currentTable.fields;
    fblogedit.addFields([
        ["blogID", [fields.blogID.visible && fields.blogID.required ? ew.Validators.required(fields.blogID.caption) : null], fields.blogID.isInvalid],
        ["blogGjuha", [fields.blogGjuha.visible && fields.blogGjuha.required ? ew.Validators.required(fields.blogGjuha.caption) : null], fields.blogGjuha.isInvalid],
        ["blogKategoria", [fields.blogKategoria.visible && fields.blogKategoria.required ? ew.Validators.required(fields.blogKategoria.caption) : null], fields.blogKategoria.isInvalid],
        ["blogTitulli", [fields.blogTitulli.visible && fields.blogTitulli.required ? ew.Validators.required(fields.blogTitulli.caption) : null], fields.blogTitulli.isInvalid],
        ["blogTxt", [fields.blogTxt.visible && fields.blogTxt.required ? ew.Validators.required(fields.blogTxt.caption) : null], fields.blogTxt.isInvalid],
        ["blogFoto", [fields.blogFoto.visible && fields.blogFoto.required ? ew.Validators.fileRequired(fields.blogFoto.caption) : null], fields.blogFoto.isInvalid],
        ["blogVideo", [fields.blogVideo.visible && fields.blogVideo.required ? ew.Validators.required(fields.blogVideo.caption) : null], fields.blogVideo.isInvalid],
        ["blogDtPublik", [fields.blogDtPublik.visible && fields.blogDtPublik.required ? ew.Validators.required(fields.blogDtPublik.caption) : null, ew.Validators.datetime(fields.blogDtPublik.clientFormatPattern)], fields.blogDtPublik.isInvalid],
        ["blogAutori", [fields.blogAutori.visible && fields.blogAutori.required ? ew.Validators.required(fields.blogAutori.caption) : null], fields.blogAutori.isInvalid],
        ["blogModifikuar", [fields.blogModifikuar.visible && fields.blogModifikuar.required ? ew.Validators.required(fields.blogModifikuar.caption) : null], fields.blogModifikuar.isInvalid]
    ]);

    // Form_CustomValidate
    fblogedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fblogedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fblogedit.lists.blogGjuha = <?= $Page->blogGjuha->toClientList($Page) ?>;
    fblogedit.lists.blogKategoria = <?= $Page->blogKategoria->toClientList($Page) ?>;
    fblogedit.lists.blogAutori = <?= $Page->blogAutori->toClientList($Page) ?>;
    loadjs.done("fblogedit");
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
<form name="fblogedit" id="fblogedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->blogID->Visible) { // blogID ?>
    <div id="r_blogID"<?= $Page->blogID->rowAttributes() ?>>
        <label id="elh_blog_blogID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogID->caption() ?><?= $Page->blogID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogID->cellAttributes() ?>>
<span id="el_blog_blogID">
<span<?= $Page->blogID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->blogID->getDisplayValue($Page->blogID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="blog" data-field="x_blogID" data-hidden="1" name="x_blogID" id="x_blogID" value="<?= HtmlEncode($Page->blogID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blogGjuha->Visible) { // blogGjuha ?>
    <div id="r_blogGjuha"<?= $Page->blogGjuha->rowAttributes() ?>>
        <label id="elh_blog_blogGjuha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogGjuha->caption() ?><?= $Page->blogGjuha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogGjuha->cellAttributes() ?>>
<span id="el_blog_blogGjuha">
<template id="tp_x_blogGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="blog" data-field="x_blogGjuha" name="x_blogGjuha" id="x_blogGjuha"<?= $Page->blogGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_blogGjuha" class="ew-item-list"></div>
<?php $Page->blogGjuha->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<selection-list hidden
    id="x_blogGjuha"
    name="x_blogGjuha"
    value="<?= HtmlEncode($Page->blogGjuha->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_blogGjuha"
    data-bs-target="dsl_x_blogGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->blogGjuha->isInvalidClass() ?>"
    data-table="blog"
    data-field="x_blogGjuha"
    data-value-separator="<?= $Page->blogGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->blogGjuha->editAttributes() ?>></selection-list>
<?= $Page->blogGjuha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blogGjuha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blogKategoria->Visible) { // blogKategoria ?>
    <div id="r_blogKategoria"<?= $Page->blogKategoria->rowAttributes() ?>>
        <label id="elh_blog_blogKategoria" for="x_blogKategoria" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogKategoria->caption() ?><?= $Page->blogKategoria->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogKategoria->cellAttributes() ?>>
<span id="el_blog_blogKategoria">
    <select
        id="x_blogKategoria"
        name="x_blogKategoria"
        class="form-select ew-select<?= $Page->blogKategoria->isInvalidClass() ?>"
        data-select2-id="fblogedit_x_blogKategoria"
        data-table="blog"
        data-field="x_blogKategoria"
        data-value-separator="<?= $Page->blogKategoria->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->blogKategoria->getPlaceHolder()) ?>"
        <?= $Page->blogKategoria->editAttributes() ?>>
        <?= $Page->blogKategoria->selectOptionListHtml("x_blogKategoria") ?>
    </select>
    <?= $Page->blogKategoria->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->blogKategoria->getErrorMessage() ?></div>
<?= $Page->blogKategoria->Lookup->getParamTag($Page, "p_x_blogKategoria") ?>
<script>
loadjs.ready("fblogedit", function() {
    var options = { name: "x_blogKategoria", selectId: "fblogedit_x_blogKategoria" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fblogedit.lists.blogKategoria.lookupOptions.length) {
        options.data = { id: "x_blogKategoria", form: "fblogedit" };
    } else {
        options.ajax = { id: "x_blogKategoria", form: "fblogedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.blog.fields.blogKategoria.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blogTitulli->Visible) { // blogTitulli ?>
    <div id="r_blogTitulli"<?= $Page->blogTitulli->rowAttributes() ?>>
        <label id="elh_blog_blogTitulli" for="x_blogTitulli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogTitulli->caption() ?><?= $Page->blogTitulli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogTitulli->cellAttributes() ?>>
<span id="el_blog_blogTitulli">
<input type="<?= $Page->blogTitulli->getInputTextType() ?>" name="x_blogTitulli" id="x_blogTitulli" data-table="blog" data-field="x_blogTitulli" value="<?= $Page->blogTitulli->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->blogTitulli->getPlaceHolder()) ?>"<?= $Page->blogTitulli->editAttributes() ?> aria-describedby="x_blogTitulli_help">
<?= $Page->blogTitulli->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blogTitulli->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blogTxt->Visible) { // blogTxt ?>
    <div id="r_blogTxt"<?= $Page->blogTxt->rowAttributes() ?>>
        <label id="elh_blog_blogTxt" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogTxt->caption() ?><?= $Page->blogTxt->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogTxt->cellAttributes() ?>>
<span id="el_blog_blogTxt">
<?php $Page->blogTxt->EditAttrs->appendClass("editor"); ?>
<textarea data-table="blog" data-field="x_blogTxt" name="x_blogTxt" id="x_blogTxt" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->blogTxt->getPlaceHolder()) ?>"<?= $Page->blogTxt->editAttributes() ?> aria-describedby="x_blogTxt_help"><?= $Page->blogTxt->EditValue ?></textarea>
<?= $Page->blogTxt->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blogTxt->getErrorMessage() ?></div>
<script>
loadjs.ready(["fblogedit", "editor"], function() {
    ew.createEditor("fblogedit", "x_blogTxt", 35, 4, <?= $Page->blogTxt->ReadOnly || false ? "true" : "false" ?>);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blogFoto->Visible) { // blogFoto ?>
    <div id="r_blogFoto"<?= $Page->blogFoto->rowAttributes() ?>>
        <label id="elh_blog_blogFoto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogFoto->caption() ?><?= $Page->blogFoto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogFoto->cellAttributes() ?>>
<span id="el_blog_blogFoto">
<div id="fd_x_blogFoto" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->blogFoto->title() ?>" data-table="blog" data-field="x_blogFoto" name="x_blogFoto" id="x_blogFoto" lang="<?= CurrentLanguageID() ?>" multiple<?= $Page->blogFoto->editAttributes() ?> aria-describedby="x_blogFoto_help"<?= ($Page->blogFoto->ReadOnly || $Page->blogFoto->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFiles") ?></div>
</div>
<?= $Page->blogFoto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blogFoto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_blogFoto" id= "fn_x_blogFoto" value="<?= $Page->blogFoto->Upload->FileName ?>">
<input type="hidden" name="fa_x_blogFoto" id= "fa_x_blogFoto" value="<?= (Post("fa_x_blogFoto") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_blogFoto" id= "fs_x_blogFoto" value="65535">
<input type="hidden" name="fx_x_blogFoto" id= "fx_x_blogFoto" value="<?= $Page->blogFoto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_blogFoto" id= "fm_x_blogFoto" value="<?= $Page->blogFoto->UploadMaxFileSize ?>">
<input type="hidden" name="fc_x_blogFoto" id= "fc_x_blogFoto" value="<?= $Page->blogFoto->UploadMaxFileCount ?>">
<table id="ft_x_blogFoto" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blogVideo->Visible) { // blogVideo ?>
    <div id="r_blogVideo"<?= $Page->blogVideo->rowAttributes() ?>>
        <label id="elh_blog_blogVideo" for="x_blogVideo" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogVideo->caption() ?><?= $Page->blogVideo->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogVideo->cellAttributes() ?>>
<span id="el_blog_blogVideo">
<textarea data-table="blog" data-field="x_blogVideo" name="x_blogVideo" id="x_blogVideo" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->blogVideo->getPlaceHolder()) ?>"<?= $Page->blogVideo->editAttributes() ?> aria-describedby="x_blogVideo_help"><?= $Page->blogVideo->EditValue ?></textarea>
<?= $Page->blogVideo->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blogVideo->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->blogDtPublik->Visible) { // blogDtPublik ?>
    <div id="r_blogDtPublik"<?= $Page->blogDtPublik->rowAttributes() ?>>
        <label id="elh_blog_blogDtPublik" for="x_blogDtPublik" class="<?= $Page->LeftColumnClass ?>"><?= $Page->blogDtPublik->caption() ?><?= $Page->blogDtPublik->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->blogDtPublik->cellAttributes() ?>>
<span id="el_blog_blogDtPublik">
<input type="<?= $Page->blogDtPublik->getInputTextType() ?>" name="x_blogDtPublik" id="x_blogDtPublik" data-table="blog" data-field="x_blogDtPublik" value="<?= $Page->blogDtPublik->EditValue ?>" placeholder="<?= HtmlEncode($Page->blogDtPublik->getPlaceHolder()) ?>"<?= $Page->blogDtPublik->editAttributes() ?> aria-describedby="x_blogDtPublik_help">
<?= $Page->blogDtPublik->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->blogDtPublik->getErrorMessage() ?></div>
<?php if (!$Page->blogDtPublik->ReadOnly && !$Page->blogDtPublik->Disabled && !isset($Page->blogDtPublik->EditAttrs["readonly"]) && !isset($Page->blogDtPublik->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fblogedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fblogedit", "x_blogDtPublik", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
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
    ew.addEventHandlers("blog");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
