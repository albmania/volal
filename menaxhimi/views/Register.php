<?php

namespace PHPMaker2022\volalservice;

// Page object
$Register = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { perdoruesit: currentTable } });
var currentForm, currentPageID;
var fregister;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fregister = new ew.Form("fregister", "register");
    currentPageID = ew.PAGE_ID = "register";
    currentForm = fregister;

    // Add fields
    var fields = currentTable.fields;
    fregister.addFields([
        ["perdEmri", [fields.perdEmri.visible && fields.perdEmri.required ? ew.Validators.required(fields.perdEmri.caption) : null], fields.perdEmri.isInvalid],
        ["perdUsername", [fields.perdUsername.visible && fields.perdUsername.required ? ew.Validators.required(fields.perdUsername.caption) : null, ew.Validators.username(fields.perdUsername.raw)], fields.perdUsername.isInvalid],
        ["c_perdFjalekalimi", [ew.Validators.required(ew.language.phrase("ConfirmPassword")), ew.Validators.mismatchPassword], fields.perdFjalekalimi.isInvalid],
        ["perdFjalekalimi", [fields.perdFjalekalimi.visible && fields.perdFjalekalimi.required ? ew.Validators.required(fields.perdFjalekalimi.caption) : null, ew.Validators.password(fields.perdFjalekalimi.raw)], fields.perdFjalekalimi.isInvalid],
        ["perdEmail", [fields.perdEmail.visible && fields.perdEmail.required ? ew.Validators.required(fields.perdEmail.caption) : null], fields.perdEmail.isInvalid]
    ]);

    // Form_CustomValidate
    fregister.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fregister.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fregister");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fregister" id="fregister" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="t" value="perdoruesit">
<input type="hidden" name="action" id="action" value="insert">
<div class="ew-register-div"><!-- page* -->
<?php if ($Page->perdEmri->Visible) { // perdEmri ?>
    <div id="r_perdEmri"<?= $Page->perdEmri->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdEmri" for="x_perdEmri" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdEmri->caption() ?><?= $Page->perdEmri->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdEmri->cellAttributes() ?>>
<span id="el_perdoruesit_perdEmri">
<input type="<?= $Page->perdEmri->getInputTextType() ?>" name="x_perdEmri" id="x_perdEmri" data-table="perdoruesit" data-field="x_perdEmri" value="<?= $Page->perdEmri->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdEmri->getPlaceHolder()) ?>"<?= $Page->perdEmri->editAttributes() ?> aria-describedby="x_perdEmri_help">
<?= $Page->perdEmri->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdEmri->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdUsername->Visible) { // perdUsername ?>
    <div id="r_perdUsername"<?= $Page->perdUsername->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdUsername" for="x_perdUsername" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdUsername->caption() ?><?= $Page->perdUsername->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdUsername->cellAttributes() ?>>
<span id="el_perdoruesit_perdUsername">
<input type="<?= $Page->perdUsername->getInputTextType() ?>" name="x_perdUsername" id="x_perdUsername" data-table="perdoruesit" data-field="x_perdUsername" value="<?= $Page->perdUsername->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdUsername->getPlaceHolder()) ?>"<?= $Page->perdUsername->editAttributes() ?> aria-describedby="x_perdUsername_help">
<?= $Page->perdUsername->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdUsername->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdFjalekalimi->Visible) { // perdFjalekalimi ?>
    <div id="r_perdFjalekalimi"<?= $Page->perdFjalekalimi->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdFjalekalimi" for="x_perdFjalekalimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdFjalekalimi->caption() ?><?= $Page->perdFjalekalimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdFjalekalimi->cellAttributes() ?>>
<span id="el_perdoruesit_perdFjalekalimi">
<input type="<?= $Page->perdFjalekalimi->getInputTextType() ?>" name="x_perdFjalekalimi" id="x_perdFjalekalimi" data-table="perdoruesit" data-field="x_perdFjalekalimi" value="<?= $Page->perdFjalekalimi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdFjalekalimi->getPlaceHolder()) ?>"<?= $Page->perdFjalekalimi->editAttributes() ?> aria-describedby="x_perdFjalekalimi_help">
<?= $Page->perdFjalekalimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdFjalekalimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdFjalekalimi->Visible) { // perdFjalekalimi ?>
    <div id="r_c_perdFjalekalimi" class="row">
        <label id="elh_c_perdoruesit_perdFjalekalimi" for="c_perdFjalekalimi" class="<?= $Page->LeftColumnClass ?>"><?= $Language->phrase("Confirm") ?> <?= $Page->perdFjalekalimi->caption() ?><?= $Page->perdFjalekalimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdFjalekalimi->cellAttributes() ?>>
<span id="el_c_perdoruesit_perdFjalekalimi">
<input type="<?= $Page->perdFjalekalimi->getInputTextType() ?>" name="c_perdFjalekalimi" id="c_perdFjalekalimi" data-table="perdoruesit" data-field="x_perdFjalekalimi" value="<?= $Page->perdFjalekalimi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdFjalekalimi->getPlaceHolder()) ?>"<?= $Page->perdFjalekalimi->editAttributes() ?> aria-describedby="x_perdFjalekalimi_help">
<?= $Page->perdFjalekalimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdFjalekalimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->perdEmail->Visible) { // perdEmail ?>
    <div id="r_perdEmail"<?= $Page->perdEmail->rowAttributes() ?>>
        <label id="elh_perdoruesit_perdEmail" for="x_perdEmail" class="<?= $Page->LeftColumnClass ?>"><?= $Page->perdEmail->caption() ?><?= $Page->perdEmail->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->perdEmail->cellAttributes() ?>>
<span id="el_perdoruesit_perdEmail">
<input type="<?= $Page->perdEmail->getInputTextType() ?>" name="x_perdEmail" id="x_perdEmail" data-table="perdoruesit" data-field="x_perdEmail" value="<?= $Page->perdEmail->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->perdEmail->getPlaceHolder()) ?>"<?= $Page->perdEmail->editAttributes() ?> aria-describedby="x_perdEmail_help">
<?= $Page->perdEmail->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->perdEmail->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?php if (!$Page->IsModal) { ?>
<div class="row"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("RegisterBtn") ?></button>
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
    ew.addEventHandlers("perdoruesit");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your startup script here, no need to add script tags.
});
</script>
