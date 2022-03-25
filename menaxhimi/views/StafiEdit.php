<?php

namespace PHPMaker2022\volalservice;

// Page object
$StafiEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { stafi: currentTable } });
var currentForm, currentPageID;
var fstafiedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fstafiedit = new ew.Form("fstafiedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fstafiedit;

    // Add fields
    var fields = currentTable.fields;
    fstafiedit.addFields([
        ["stafiID", [fields.stafiID.visible && fields.stafiID.required ? ew.Validators.required(fields.stafiID.caption) : null], fields.stafiID.isInvalid],
        ["stafiEmri", [fields.stafiEmri.visible && fields.stafiEmri.required ? ew.Validators.required(fields.stafiEmri.caption) : null], fields.stafiEmri.isInvalid],
        ["stafiDatelindja", [fields.stafiDatelindja.visible && fields.stafiDatelindja.required ? ew.Validators.required(fields.stafiDatelindja.caption) : null, ew.Validators.datetime(fields.stafiDatelindja.clientFormatPattern)], fields.stafiDatelindja.isInvalid],
        ["stafiSpecialitete", [fields.stafiSpecialitete.visible && fields.stafiSpecialitete.required ? ew.Validators.required(fields.stafiSpecialitete.caption) : null], fields.stafiSpecialitete.isInvalid],
        ["stafiStudime", [fields.stafiStudime.visible && fields.stafiStudime.required ? ew.Validators.required(fields.stafiStudime.caption) : null], fields.stafiStudime.isInvalid]
    ]);

    // Form_CustomValidate
    fstafiedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fstafiedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    loadjs.done("fstafiedit");
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
<form name="fstafiedit" id="fstafiedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="stafi">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->stafiID->Visible) { // stafiID ?>
    <div id="r_stafiID"<?= $Page->stafiID->rowAttributes() ?>>
        <label id="elh_stafi_stafiID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->stafiID->caption() ?><?= $Page->stafiID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->stafiID->cellAttributes() ?>>
<span id="el_stafi_stafiID">
<span<?= $Page->stafiID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->stafiID->getDisplayValue($Page->stafiID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="stafi" data-field="x_stafiID" data-hidden="1" name="x_stafiID" id="x_stafiID" value="<?= HtmlEncode($Page->stafiID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->stafiEmri->Visible) { // stafiEmri ?>
    <div id="r_stafiEmri"<?= $Page->stafiEmri->rowAttributes() ?>>
        <label id="elh_stafi_stafiEmri" for="x_stafiEmri" class="<?= $Page->LeftColumnClass ?>"><?= $Page->stafiEmri->caption() ?><?= $Page->stafiEmri->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->stafiEmri->cellAttributes() ?>>
<span id="el_stafi_stafiEmri">
<input type="<?= $Page->stafiEmri->getInputTextType() ?>" name="x_stafiEmri" id="x_stafiEmri" data-table="stafi" data-field="x_stafiEmri" value="<?= $Page->stafiEmri->EditValue ?>" size="30" maxlength="30" placeholder="<?= HtmlEncode($Page->stafiEmri->getPlaceHolder()) ?>"<?= $Page->stafiEmri->editAttributes() ?> aria-describedby="x_stafiEmri_help">
<?= $Page->stafiEmri->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->stafiEmri->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->stafiDatelindja->Visible) { // stafiDatelindja ?>
    <div id="r_stafiDatelindja"<?= $Page->stafiDatelindja->rowAttributes() ?>>
        <label id="elh_stafi_stafiDatelindja" for="x_stafiDatelindja" class="<?= $Page->LeftColumnClass ?>"><?= $Page->stafiDatelindja->caption() ?><?= $Page->stafiDatelindja->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->stafiDatelindja->cellAttributes() ?>>
<span id="el_stafi_stafiDatelindja">
<input type="<?= $Page->stafiDatelindja->getInputTextType() ?>" name="x_stafiDatelindja" id="x_stafiDatelindja" data-table="stafi" data-field="x_stafiDatelindja" value="<?= $Page->stafiDatelindja->EditValue ?>" placeholder="<?= HtmlEncode($Page->stafiDatelindja->getPlaceHolder()) ?>"<?= $Page->stafiDatelindja->editAttributes() ?> aria-describedby="x_stafiDatelindja_help">
<?= $Page->stafiDatelindja->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->stafiDatelindja->getErrorMessage() ?></div>
<?php if (!$Page->stafiDatelindja->ReadOnly && !$Page->stafiDatelindja->Disabled && !isset($Page->stafiDatelindja->EditAttrs["readonly"]) && !isset($Page->stafiDatelindja->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fstafiedit", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fstafiedit", "x_stafiDatelindja", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->stafiSpecialitete->Visible) { // stafiSpecialitete ?>
    <div id="r_stafiSpecialitete"<?= $Page->stafiSpecialitete->rowAttributes() ?>>
        <label id="elh_stafi_stafiSpecialitete" for="x_stafiSpecialitete" class="<?= $Page->LeftColumnClass ?>"><?= $Page->stafiSpecialitete->caption() ?><?= $Page->stafiSpecialitete->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->stafiSpecialitete->cellAttributes() ?>>
<span id="el_stafi_stafiSpecialitete">
<textarea data-table="stafi" data-field="x_stafiSpecialitete" name="x_stafiSpecialitete" id="x_stafiSpecialitete" cols="35" rows="4" placeholder="<?= HtmlEncode($Page->stafiSpecialitete->getPlaceHolder()) ?>"<?= $Page->stafiSpecialitete->editAttributes() ?> aria-describedby="x_stafiSpecialitete_help"><?= $Page->stafiSpecialitete->EditValue ?></textarea>
<?= $Page->stafiSpecialitete->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->stafiSpecialitete->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->stafiStudime->Visible) { // stafiStudime ?>
    <div id="r_stafiStudime"<?= $Page->stafiStudime->rowAttributes() ?>>
        <label id="elh_stafi_stafiStudime" for="x_stafiStudime" class="<?= $Page->LeftColumnClass ?>"><?= $Page->stafiStudime->caption() ?><?= $Page->stafiStudime->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->stafiStudime->cellAttributes() ?>>
<span id="el_stafi_stafiStudime">
<input type="<?= $Page->stafiStudime->getInputTextType() ?>" name="x_stafiStudime" id="x_stafiStudime" data-table="stafi" data-field="x_stafiStudime" value="<?= $Page->stafiStudime->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->stafiStudime->getPlaceHolder()) ?>"<?= $Page->stafiStudime->editAttributes() ?> aria-describedby="x_stafiStudime_help">
<?= $Page->stafiStudime->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->stafiStudime->getErrorMessage() ?></div>
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
    ew.addEventHandlers("stafi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
