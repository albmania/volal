<?php

namespace PHPMaker2022\volalservice;

// Page object
$StafiAddopt = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { stafi: currentTable } });
var currentForm, currentPageID;
var fstafiaddopt;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fstafiaddopt = new ew.Form("fstafiaddopt", "addopt");
    currentPageID = ew.PAGE_ID = "addopt";
    currentForm = fstafiaddopt;

    // Add fields
    var fields = currentTable.fields;
    fstafiaddopt.addFields([
        ["stafiEmri", [fields.stafiEmri.visible && fields.stafiEmri.required ? ew.Validators.required(fields.stafiEmri.caption) : null], fields.stafiEmri.isInvalid],
        ["stafiDatelindja", [fields.stafiDatelindja.visible && fields.stafiDatelindja.required ? ew.Validators.required(fields.stafiDatelindja.caption) : null, ew.Validators.datetime(fields.stafiDatelindja.clientFormatPattern)], fields.stafiDatelindja.isInvalid],
        ["stafiSpecialitete", [fields.stafiSpecialitete.visible && fields.stafiSpecialitete.required ? ew.Validators.required(fields.stafiSpecialitete.caption) : null], fields.stafiSpecialitete.isInvalid],
        ["stafiStudime", [fields.stafiStudime.visible && fields.stafiStudime.required ? ew.Validators.required(fields.stafiStudime.caption) : null], fields.stafiStudime.isInvalid]
    ]);

    // Form_CustomValidate
    fstafiaddopt.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fstafiaddopt.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fstafiaddopt");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<form name="fstafiaddopt" id="fstafiaddopt" class="ew-form" action="<?= HtmlEncode(GetUrl(Config("API_URL"))) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="<?= Config("API_ACTION_NAME") ?>" id="<?= Config("API_ACTION_NAME") ?>" value="<?= Config("API_ADD_ACTION") ?>">
<input type="hidden" name="<?= Config("API_OBJECT_NAME") ?>" id="<?= Config("API_OBJECT_NAME") ?>" value="stafi">
<input type="hidden" name="addopt" id="addopt" value="1">
<?php if ($Page->stafiEmri->Visible) { // stafiEmri ?>
    <div<?= $Page->stafiEmri->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_stafiEmri"><?= $Page->stafiEmri->caption() ?><?= $Page->stafiEmri->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->stafiEmri->cellAttributes() ?>>
<input type="<?= $Page->stafiEmri->getInputTextType() ?>" name="x_stafiEmri" id="x_stafiEmri" data-table="stafi" data-field="x_stafiEmri" value="<?= $Page->stafiEmri->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->stafiEmri->getPlaceHolder()) ?>"<?= $Page->stafiEmri->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->stafiEmri->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->stafiDatelindja->Visible) { // stafiDatelindja ?>
    <div<?= $Page->stafiDatelindja->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_stafiDatelindja"><?= $Page->stafiDatelindja->caption() ?><?= $Page->stafiDatelindja->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->stafiDatelindja->cellAttributes() ?>>
<input type="<?= $Page->stafiDatelindja->getInputTextType() ?>" name="x_stafiDatelindja" id="x_stafiDatelindja" data-table="stafi" data-field="x_stafiDatelindja" value="<?= $Page->stafiDatelindja->EditValue ?>" placeholder="<?= HtmlEncode($Page->stafiDatelindja->getPlaceHolder()) ?>"<?= $Page->stafiDatelindja->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->stafiDatelindja->getErrorMessage() ?></div>
<?php if (!$Page->stafiDatelindja->ReadOnly && !$Page->stafiDatelindja->Disabled && !isset($Page->stafiDatelindja->EditAttrs["readonly"]) && !isset($Page->stafiDatelindja->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fstafiaddopt", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fstafiaddopt", "x_stafiDatelindja", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->stafiSpecialitete->Visible) { // stafiSpecialitete ?>
    <div<?= $Page->stafiSpecialitete->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_stafiSpecialitete"><?= $Page->stafiSpecialitete->caption() ?><?= $Page->stafiSpecialitete->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->stafiSpecialitete->cellAttributes() ?>>
<textarea data-table="stafi" data-field="x_stafiSpecialitete" name="x_stafiSpecialitete" id="x_stafiSpecialitete" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->stafiSpecialitete->getPlaceHolder()) ?>"<?= $Page->stafiSpecialitete->editAttributes() ?>><?= $Page->stafiSpecialitete->EditValue ?></textarea>
<div class="invalid-feedback"><?= $Page->stafiSpecialitete->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->stafiStudime->Visible) { // stafiStudime ?>
    <div<?= $Page->stafiStudime->rowAttributes() ?>>
        <label class="col-sm-2 col-form-label ew-label" for="x_stafiStudime"><?= $Page->stafiStudime->caption() ?><?= $Page->stafiStudime->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="col-sm-10"><div<?= $Page->stafiStudime->cellAttributes() ?>>
<input type="<?= $Page->stafiStudime->getInputTextType() ?>" name="x_stafiStudime" id="x_stafiStudime" data-table="stafi" data-field="x_stafiStudime" value="<?= $Page->stafiStudime->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->stafiStudime->getPlaceHolder()) ?>"<?= $Page->stafiStudime->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->stafiStudime->getErrorMessage() ?></div>
</div></div>
    </div>
<?php } ?>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("stafi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
