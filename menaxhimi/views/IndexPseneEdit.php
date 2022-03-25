<?php

namespace PHPMaker2022\volalservice;

// Page object
$IndexPseneEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { index_psene: currentTable } });
var currentForm, currentPageID;
var findex_pseneedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    findex_pseneedit = new ew.Form("findex_pseneedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = findex_pseneedit;

    // Add fields
    var fields = currentTable.fields;
    findex_pseneedit.addFields([
        ["iPseNeGjuha", [fields.iPseNeGjuha.visible && fields.iPseNeGjuha.required ? ew.Validators.required(fields.iPseNeGjuha.caption) : null], fields.iPseNeGjuha.isInvalid],
        ["iPseNeB1Titull", [fields.iPseNeB1Titull.visible && fields.iPseNeB1Titull.required ? ew.Validators.required(fields.iPseNeB1Titull.caption) : null], fields.iPseNeB1Titull.isInvalid],
        ["iPseNeB1Txt", [fields.iPseNeB1Txt.visible && fields.iPseNeB1Txt.required ? ew.Validators.required(fields.iPseNeB1Txt.caption) : null], fields.iPseNeB1Txt.isInvalid],
        ["iPseNeB1Ikona", [fields.iPseNeB1Ikona.visible && fields.iPseNeB1Ikona.required ? ew.Validators.required(fields.iPseNeB1Ikona.caption) : null], fields.iPseNeB1Ikona.isInvalid],
        ["iPseNeB2Titull", [fields.iPseNeB2Titull.visible && fields.iPseNeB2Titull.required ? ew.Validators.required(fields.iPseNeB2Titull.caption) : null], fields.iPseNeB2Titull.isInvalid],
        ["iPseNeB2Txt", [fields.iPseNeB2Txt.visible && fields.iPseNeB2Txt.required ? ew.Validators.required(fields.iPseNeB2Txt.caption) : null], fields.iPseNeB2Txt.isInvalid],
        ["iPseNeB2Ikona", [fields.iPseNeB2Ikona.visible && fields.iPseNeB2Ikona.required ? ew.Validators.required(fields.iPseNeB2Ikona.caption) : null], fields.iPseNeB2Ikona.isInvalid],
        ["iPseNeB3Titull", [fields.iPseNeB3Titull.visible && fields.iPseNeB3Titull.required ? ew.Validators.required(fields.iPseNeB3Titull.caption) : null], fields.iPseNeB3Titull.isInvalid],
        ["iPseNeB3Txt", [fields.iPseNeB3Txt.visible && fields.iPseNeB3Txt.required ? ew.Validators.required(fields.iPseNeB3Txt.caption) : null], fields.iPseNeB3Txt.isInvalid],
        ["iPseNeB3Ikona", [fields.iPseNeB3Ikona.visible && fields.iPseNeB3Ikona.required ? ew.Validators.required(fields.iPseNeB3Ikona.caption) : null], fields.iPseNeB3Ikona.isInvalid],
        ["iPseNeB4Titull", [fields.iPseNeB4Titull.visible && fields.iPseNeB4Titull.required ? ew.Validators.required(fields.iPseNeB4Titull.caption) : null], fields.iPseNeB4Titull.isInvalid],
        ["iPseNeB4Txt", [fields.iPseNeB4Txt.visible && fields.iPseNeB4Txt.required ? ew.Validators.required(fields.iPseNeB4Txt.caption) : null], fields.iPseNeB4Txt.isInvalid],
        ["iPseNeB4Ikona", [fields.iPseNeB4Ikona.visible && fields.iPseNeB4Ikona.required ? ew.Validators.required(fields.iPseNeB4Ikona.caption) : null], fields.iPseNeB4Ikona.isInvalid],
        ["iPseNeFoto", [fields.iPseNeFoto.visible && fields.iPseNeFoto.required ? ew.Validators.fileRequired(fields.iPseNeFoto.caption) : null], fields.iPseNeFoto.isInvalid]
    ]);

    // Form_CustomValidate
    findex_pseneedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    findex_pseneedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    findex_pseneedit.lists.iPseNeGjuha = <?= $Page->iPseNeGjuha->toClientList($Page) ?>;
    loadjs.done("findex_pseneedit");
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
<form name="findex_pseneedit" id="findex_pseneedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="index_psene">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->iPseNeGjuha->Visible) { // iPseNeGjuha ?>
    <div id="r_iPseNeGjuha"<?= $Page->iPseNeGjuha->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeGjuha" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeGjuha->caption() ?><?= $Page->iPseNeGjuha->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeGjuha->cellAttributes() ?>>
<span id="el_index_psene_iPseNeGjuha">
<template id="tp_x_iPseNeGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="index_psene" data-field="x_iPseNeGjuha" name="x_iPseNeGjuha" id="x_iPseNeGjuha"<?= $Page->iPseNeGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_iPseNeGjuha" class="ew-item-list"></div>
<selection-list hidden
    id="x_iPseNeGjuha"
    name="x_iPseNeGjuha"
    value="<?= HtmlEncode($Page->iPseNeGjuha->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_iPseNeGjuha"
    data-bs-target="dsl_x_iPseNeGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->iPseNeGjuha->isInvalidClass() ?>"
    data-table="index_psene"
    data-field="x_iPseNeGjuha"
    data-value-separator="<?= $Page->iPseNeGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->iPseNeGjuha->editAttributes() ?>></selection-list>
<?= $Page->iPseNeGjuha->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeGjuha->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB1Titull->Visible) { // iPseNeB1Titull ?>
    <div id="r_iPseNeB1Titull"<?= $Page->iPseNeB1Titull->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB1Titull" for="x_iPseNeB1Titull" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB1Titull->caption() ?><?= $Page->iPseNeB1Titull->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB1Titull->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB1Titull">
<input type="<?= $Page->iPseNeB1Titull->getInputTextType() ?>" name="x_iPseNeB1Titull" id="x_iPseNeB1Titull" data-table="index_psene" data-field="x_iPseNeB1Titull" value="<?= $Page->iPseNeB1Titull->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->iPseNeB1Titull->getPlaceHolder()) ?>"<?= $Page->iPseNeB1Titull->editAttributes() ?> aria-describedby="x_iPseNeB1Titull_help">
<?= $Page->iPseNeB1Titull->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB1Titull->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB1Txt->Visible) { // iPseNeB1Txt ?>
    <div id="r_iPseNeB1Txt"<?= $Page->iPseNeB1Txt->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB1Txt" for="x_iPseNeB1Txt" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB1Txt->caption() ?><?= $Page->iPseNeB1Txt->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB1Txt->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB1Txt">
<input type="<?= $Page->iPseNeB1Txt->getInputTextType() ?>" name="x_iPseNeB1Txt" id="x_iPseNeB1Txt" data-table="index_psene" data-field="x_iPseNeB1Txt" value="<?= $Page->iPseNeB1Txt->EditValue ?>" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->iPseNeB1Txt->getPlaceHolder()) ?>"<?= $Page->iPseNeB1Txt->editAttributes() ?> aria-describedby="x_iPseNeB1Txt_help">
<?= $Page->iPseNeB1Txt->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB1Txt->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB1Ikona->Visible) { // iPseNeB1Ikona ?>
    <div id="r_iPseNeB1Ikona"<?= $Page->iPseNeB1Ikona->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB1Ikona" for="x_iPseNeB1Ikona" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB1Ikona->caption() ?><?= $Page->iPseNeB1Ikona->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB1Ikona->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB1Ikona">
<input type="<?= $Page->iPseNeB1Ikona->getInputTextType() ?>" name="x_iPseNeB1Ikona" id="x_iPseNeB1Ikona" data-table="index_psene" data-field="x_iPseNeB1Ikona" value="<?= $Page->iPseNeB1Ikona->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->iPseNeB1Ikona->getPlaceHolder()) ?>"<?= $Page->iPseNeB1Ikona->editAttributes() ?> aria-describedby="x_iPseNeB1Ikona_help">
<?= $Page->iPseNeB1Ikona->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB1Ikona->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB2Titull->Visible) { // iPseNeB2Titull ?>
    <div id="r_iPseNeB2Titull"<?= $Page->iPseNeB2Titull->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB2Titull" for="x_iPseNeB2Titull" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB2Titull->caption() ?><?= $Page->iPseNeB2Titull->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB2Titull->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB2Titull">
<input type="<?= $Page->iPseNeB2Titull->getInputTextType() ?>" name="x_iPseNeB2Titull" id="x_iPseNeB2Titull" data-table="index_psene" data-field="x_iPseNeB2Titull" value="<?= $Page->iPseNeB2Titull->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->iPseNeB2Titull->getPlaceHolder()) ?>"<?= $Page->iPseNeB2Titull->editAttributes() ?> aria-describedby="x_iPseNeB2Titull_help">
<?= $Page->iPseNeB2Titull->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB2Titull->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB2Txt->Visible) { // iPseNeB2Txt ?>
    <div id="r_iPseNeB2Txt"<?= $Page->iPseNeB2Txt->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB2Txt" for="x_iPseNeB2Txt" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB2Txt->caption() ?><?= $Page->iPseNeB2Txt->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB2Txt->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB2Txt">
<input type="<?= $Page->iPseNeB2Txt->getInputTextType() ?>" name="x_iPseNeB2Txt" id="x_iPseNeB2Txt" data-table="index_psene" data-field="x_iPseNeB2Txt" value="<?= $Page->iPseNeB2Txt->EditValue ?>" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->iPseNeB2Txt->getPlaceHolder()) ?>"<?= $Page->iPseNeB2Txt->editAttributes() ?> aria-describedby="x_iPseNeB2Txt_help">
<?= $Page->iPseNeB2Txt->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB2Txt->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB2Ikona->Visible) { // iPseNeB2Ikona ?>
    <div id="r_iPseNeB2Ikona"<?= $Page->iPseNeB2Ikona->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB2Ikona" for="x_iPseNeB2Ikona" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB2Ikona->caption() ?><?= $Page->iPseNeB2Ikona->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB2Ikona->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB2Ikona">
<input type="<?= $Page->iPseNeB2Ikona->getInputTextType() ?>" name="x_iPseNeB2Ikona" id="x_iPseNeB2Ikona" data-table="index_psene" data-field="x_iPseNeB2Ikona" value="<?= $Page->iPseNeB2Ikona->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->iPseNeB2Ikona->getPlaceHolder()) ?>"<?= $Page->iPseNeB2Ikona->editAttributes() ?> aria-describedby="x_iPseNeB2Ikona_help">
<?= $Page->iPseNeB2Ikona->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB2Ikona->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB3Titull->Visible) { // iPseNeB3Titull ?>
    <div id="r_iPseNeB3Titull"<?= $Page->iPseNeB3Titull->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB3Titull" for="x_iPseNeB3Titull" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB3Titull->caption() ?><?= $Page->iPseNeB3Titull->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB3Titull->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB3Titull">
<input type="<?= $Page->iPseNeB3Titull->getInputTextType() ?>" name="x_iPseNeB3Titull" id="x_iPseNeB3Titull" data-table="index_psene" data-field="x_iPseNeB3Titull" value="<?= $Page->iPseNeB3Titull->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->iPseNeB3Titull->getPlaceHolder()) ?>"<?= $Page->iPseNeB3Titull->editAttributes() ?> aria-describedby="x_iPseNeB3Titull_help">
<?= $Page->iPseNeB3Titull->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB3Titull->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB3Txt->Visible) { // iPseNeB3Txt ?>
    <div id="r_iPseNeB3Txt"<?= $Page->iPseNeB3Txt->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB3Txt" for="x_iPseNeB3Txt" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB3Txt->caption() ?><?= $Page->iPseNeB3Txt->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB3Txt->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB3Txt">
<input type="<?= $Page->iPseNeB3Txt->getInputTextType() ?>" name="x_iPseNeB3Txt" id="x_iPseNeB3Txt" data-table="index_psene" data-field="x_iPseNeB3Txt" value="<?= $Page->iPseNeB3Txt->EditValue ?>" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->iPseNeB3Txt->getPlaceHolder()) ?>"<?= $Page->iPseNeB3Txt->editAttributes() ?> aria-describedby="x_iPseNeB3Txt_help">
<?= $Page->iPseNeB3Txt->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB3Txt->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB3Ikona->Visible) { // iPseNeB3Ikona ?>
    <div id="r_iPseNeB3Ikona"<?= $Page->iPseNeB3Ikona->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB3Ikona" for="x_iPseNeB3Ikona" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB3Ikona->caption() ?><?= $Page->iPseNeB3Ikona->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB3Ikona->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB3Ikona">
<input type="<?= $Page->iPseNeB3Ikona->getInputTextType() ?>" name="x_iPseNeB3Ikona" id="x_iPseNeB3Ikona" data-table="index_psene" data-field="x_iPseNeB3Ikona" value="<?= $Page->iPseNeB3Ikona->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->iPseNeB3Ikona->getPlaceHolder()) ?>"<?= $Page->iPseNeB3Ikona->editAttributes() ?> aria-describedby="x_iPseNeB3Ikona_help">
<?= $Page->iPseNeB3Ikona->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB3Ikona->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB4Titull->Visible) { // iPseNeB4Titull ?>
    <div id="r_iPseNeB4Titull"<?= $Page->iPseNeB4Titull->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB4Titull" for="x_iPseNeB4Titull" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB4Titull->caption() ?><?= $Page->iPseNeB4Titull->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB4Titull->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB4Titull">
<input type="<?= $Page->iPseNeB4Titull->getInputTextType() ?>" name="x_iPseNeB4Titull" id="x_iPseNeB4Titull" data-table="index_psene" data-field="x_iPseNeB4Titull" value="<?= $Page->iPseNeB4Titull->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->iPseNeB4Titull->getPlaceHolder()) ?>"<?= $Page->iPseNeB4Titull->editAttributes() ?> aria-describedby="x_iPseNeB4Titull_help">
<?= $Page->iPseNeB4Titull->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB4Titull->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB4Txt->Visible) { // iPseNeB4Txt ?>
    <div id="r_iPseNeB4Txt"<?= $Page->iPseNeB4Txt->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB4Txt" for="x_iPseNeB4Txt" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB4Txt->caption() ?><?= $Page->iPseNeB4Txt->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB4Txt->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB4Txt">
<input type="<?= $Page->iPseNeB4Txt->getInputTextType() ?>" name="x_iPseNeB4Txt" id="x_iPseNeB4Txt" data-table="index_psene" data-field="x_iPseNeB4Txt" value="<?= $Page->iPseNeB4Txt->EditValue ?>" size="30" maxlength="200" placeholder="<?= HtmlEncode($Page->iPseNeB4Txt->getPlaceHolder()) ?>"<?= $Page->iPseNeB4Txt->editAttributes() ?> aria-describedby="x_iPseNeB4Txt_help">
<?= $Page->iPseNeB4Txt->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB4Txt->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeB4Ikona->Visible) { // iPseNeB4Ikona ?>
    <div id="r_iPseNeB4Ikona"<?= $Page->iPseNeB4Ikona->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeB4Ikona" for="x_iPseNeB4Ikona" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeB4Ikona->caption() ?><?= $Page->iPseNeB4Ikona->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeB4Ikona->cellAttributes() ?>>
<span id="el_index_psene_iPseNeB4Ikona">
<input type="<?= $Page->iPseNeB4Ikona->getInputTextType() ?>" name="x_iPseNeB4Ikona" id="x_iPseNeB4Ikona" data-table="index_psene" data-field="x_iPseNeB4Ikona" value="<?= $Page->iPseNeB4Ikona->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->iPseNeB4Ikona->getPlaceHolder()) ?>"<?= $Page->iPseNeB4Ikona->editAttributes() ?> aria-describedby="x_iPseNeB4Ikona_help">
<?= $Page->iPseNeB4Ikona->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeB4Ikona->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->iPseNeFoto->Visible) { // iPseNeFoto ?>
    <div id="r_iPseNeFoto"<?= $Page->iPseNeFoto->rowAttributes() ?>>
        <label id="elh_index_psene_iPseNeFoto" class="<?= $Page->LeftColumnClass ?>"><?= $Page->iPseNeFoto->caption() ?><?= $Page->iPseNeFoto->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->iPseNeFoto->cellAttributes() ?>>
<span id="el_index_psene_iPseNeFoto">
<div id="fd_x_iPseNeFoto" class="fileinput-button ew-file-drop-zone">
    <input type="file" class="form-control ew-file-input" title="<?= $Page->iPseNeFoto->title() ?>" data-table="index_psene" data-field="x_iPseNeFoto" name="x_iPseNeFoto" id="x_iPseNeFoto" lang="<?= CurrentLanguageID() ?>"<?= $Page->iPseNeFoto->editAttributes() ?> aria-describedby="x_iPseNeFoto_help"<?= ($Page->iPseNeFoto->ReadOnly || $Page->iPseNeFoto->Disabled) ? " disabled" : "" ?>>
    <div class="text-muted ew-file-text"><?= $Language->phrase("ChooseFile") ?></div>
</div>
<?= $Page->iPseNeFoto->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->iPseNeFoto->getErrorMessage() ?></div>
<input type="hidden" name="fn_x_iPseNeFoto" id= "fn_x_iPseNeFoto" value="<?= $Page->iPseNeFoto->Upload->FileName ?>">
<input type="hidden" name="fa_x_iPseNeFoto" id= "fa_x_iPseNeFoto" value="<?= (Post("fa_x_iPseNeFoto") == "0") ? "0" : "1" ?>">
<input type="hidden" name="fs_x_iPseNeFoto" id= "fs_x_iPseNeFoto" value="255">
<input type="hidden" name="fx_x_iPseNeFoto" id= "fx_x_iPseNeFoto" value="<?= $Page->iPseNeFoto->UploadAllowedFileExt ?>">
<input type="hidden" name="fm_x_iPseNeFoto" id= "fm_x_iPseNeFoto" value="<?= $Page->iPseNeFoto->UploadMaxFileSize ?>">
<table id="ft_x_iPseNeFoto" class="table table-sm float-start ew-upload-table"><tbody class="files"></tbody></table>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
    <input type="hidden" data-table="index_psene" data-field="x_iPseNeID" data-hidden="1" name="x_iPseNeID" id="x_iPseNeID" value="<?= HtmlEncode($Page->iPseNeID->CurrentValue) ?>">
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
    ew.addEventHandlers("index_psene");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
