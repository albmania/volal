<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaImportiSherbimeAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_importi_sherbime: currentTable } });
var currentForm, currentPageID;
var fmakina_importi_sherbimeadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importi_sherbimeadd = new ew.Form("fmakina_importi_sherbimeadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fmakina_importi_sherbimeadd;

    // Add fields
    var fields = currentTable.fields;
    fmakina_importi_sherbimeadd.addFields([
        ["mishMakinaImporti", [fields.mishMakinaImporti.visible && fields.mishMakinaImporti.required ? ew.Validators.required(fields.mishMakinaImporti.caption) : null, ew.Validators.integer], fields.mishMakinaImporti.isInvalid],
        ["mishPershkrimi", [fields.mishPershkrimi.visible && fields.mishPershkrimi.required ? ew.Validators.required(fields.mishPershkrimi.caption) : null], fields.mishPershkrimi.isInvalid],
        ["mishKryer", [fields.mishKryer.visible && fields.mishKryer.required ? ew.Validators.required(fields.mishKryer.caption) : null], fields.mishKryer.isInvalid],
        ["mishCmimi", [fields.mishCmimi.visible && fields.mishCmimi.required ? ew.Validators.required(fields.mishCmimi.caption) : null, ew.Validators.float], fields.mishCmimi.isInvalid],
        ["mishData", [fields.mishData.visible && fields.mishData.required ? ew.Validators.required(fields.mishData.caption) : null, ew.Validators.datetime(fields.mishData.clientFormatPattern)], fields.mishData.isInvalid]
    ]);

    // Form_CustomValidate
    fmakina_importi_sherbimeadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_importi_sherbimeadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakina_importi_sherbimeadd.lists.mishMakinaImporti = <?= $Page->mishMakinaImporti->toClientList($Page) ?>;
    fmakina_importi_sherbimeadd.lists.mishKryer = <?= $Page->mishKryer->toClientList($Page) ?>;
    loadjs.done("fmakina_importi_sherbimeadd");
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
<form name="fmakina_importi_sherbimeadd" id="fmakina_importi_sherbimeadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_importi_sherbime">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "makina_importi") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="makina_importi">
<input type="hidden" name="fk_mimpID" value="<?= HtmlEncode($Page->mishMakinaImporti->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
    <div id="r_mishMakinaImporti"<?= $Page->mishMakinaImporti->rowAttributes() ?>>
        <label id="elh_makina_importi_sherbime_mishMakinaImporti" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mishMakinaImporti->caption() ?><?= $Page->mishMakinaImporti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mishMakinaImporti->cellAttributes() ?>>
<?php if ($Page->mishMakinaImporti->getSessionValue() != "") { ?>
<span id="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Page->mishMakinaImporti->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Page->mishMakinaImporti->getDisplayValue($Page->mishMakinaImporti->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x_mishMakinaImporti" name="x_mishMakinaImporti" value="<?= HtmlEncode(FormatNumber($Page->mishMakinaImporti->CurrentValue, $Page->mishMakinaImporti->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_makina_importi_sherbime_mishMakinaImporti">
<?php
$onchange = $Page->mishMakinaImporti->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Page->mishMakinaImporti->EditAttrs["onchange"] = "";
if (IsRTL()) {
    $Page->mishMakinaImporti->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x_mishMakinaImporti" class="ew-auto-suggest">
    <input type="<?= $Page->mishMakinaImporti->getInputTextType() ?>" class="form-control" name="sv_x_mishMakinaImporti" id="sv_x_mishMakinaImporti" value="<?= RemoveHtml($Page->mishMakinaImporti->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Page->mishMakinaImporti->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Page->mishMakinaImporti->getPlaceHolder()) ?>"<?= $Page->mishMakinaImporti->editAttributes() ?> aria-describedby="x_mishMakinaImporti_help">
</span>
<selection-list hidden class="form-control" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-input="sv_x_mishMakinaImporti" data-value-separator="<?= $Page->mishMakinaImporti->displayValueSeparatorAttribute() ?>" name="x_mishMakinaImporti" id="x_mishMakinaImporti" value="<?= HtmlEncode($Page->mishMakinaImporti->CurrentValue) ?>"<?= $onchange ?>></selection-list>
<?= $Page->mishMakinaImporti->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mishMakinaImporti->getErrorMessage() ?></div>
<script>
loadjs.ready("fmakina_importi_sherbimeadd", function() {
    fmakina_importi_sherbimeadd.createAutoSuggest(Object.assign({"id":"x_mishMakinaImporti","forceSelect":false}, ew.vars.tables.makina_importi_sherbime.fields.mishMakinaImporti.autoSuggestOptions));
});
</script>
<?= $Page->mishMakinaImporti->Lookup->getParamTag($Page, "p_x_mishMakinaImporti") ?>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mishPershkrimi->Visible) { // mishPershkrimi ?>
    <div id="r_mishPershkrimi"<?= $Page->mishPershkrimi->rowAttributes() ?>>
        <label id="elh_makina_importi_sherbime_mishPershkrimi" for="x_mishPershkrimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mishPershkrimi->caption() ?><?= $Page->mishPershkrimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mishPershkrimi->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishPershkrimi">
<input type="<?= $Page->mishPershkrimi->getInputTextType() ?>" name="x_mishPershkrimi" id="x_mishPershkrimi" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" value="<?= $Page->mishPershkrimi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->mishPershkrimi->getPlaceHolder()) ?>"<?= $Page->mishPershkrimi->editAttributes() ?> aria-describedby="x_mishPershkrimi_help">
<?= $Page->mishPershkrimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mishPershkrimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mishKryer->Visible) { // mishKryer ?>
    <div id="r_mishKryer"<?= $Page->mishKryer->rowAttributes() ?>>
        <label id="elh_makina_importi_sherbime_mishKryer" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mishKryer->caption() ?><?= $Page->mishKryer->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mishKryer->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishKryer">
<template id="tp_x_mishKryer">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi_sherbime" data-field="x_mishKryer" name="x_mishKryer" id="x_mishKryer"<?= $Page->mishKryer->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mishKryer" class="ew-item-list"></div>
<selection-list hidden
    id="x_mishKryer"
    name="x_mishKryer"
    value="<?= HtmlEncode($Page->mishKryer->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_mishKryer"
    data-bs-target="dsl_x_mishKryer"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mishKryer->isInvalidClass() ?>"
    data-table="makina_importi_sherbime"
    data-field="x_mishKryer"
    data-value-separator="<?= $Page->mishKryer->displayValueSeparatorAttribute() ?>"
    <?= $Page->mishKryer->editAttributes() ?>></selection-list>
<?= $Page->mishKryer->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mishKryer->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mishCmimi->Visible) { // mishCmimi ?>
    <div id="r_mishCmimi"<?= $Page->mishCmimi->rowAttributes() ?>>
        <label id="elh_makina_importi_sherbime_mishCmimi" for="x_mishCmimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mishCmimi->caption() ?><?= $Page->mishCmimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mishCmimi->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishCmimi">
<input type="<?= $Page->mishCmimi->getInputTextType() ?>" name="x_mishCmimi" id="x_mishCmimi" data-table="makina_importi_sherbime" data-field="x_mishCmimi" value="<?= $Page->mishCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mishCmimi->getPlaceHolder()) ?>"<?= $Page->mishCmimi->editAttributes() ?> aria-describedby="x_mishCmimi_help">
<?= $Page->mishCmimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mishCmimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mishData->Visible) { // mishData ?>
    <div id="r_mishData"<?= $Page->mishData->rowAttributes() ?>>
        <label id="elh_makina_importi_sherbime_mishData" for="x_mishData" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mishData->caption() ?><?= $Page->mishData->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mishData->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishData">
<input type="<?= $Page->mishData->getInputTextType() ?>" name="x_mishData" id="x_mishData" data-table="makina_importi_sherbime" data-field="x_mishData" value="<?= $Page->mishData->EditValue ?>" placeholder="<?= HtmlEncode($Page->mishData->getPlaceHolder()) ?>"<?= $Page->mishData->editAttributes() ?> aria-describedby="x_mishData_help">
<?= $Page->mishData->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mishData->getErrorMessage() ?></div>
<?php if (!$Page->mishData->ReadOnly && !$Page->mishData->Disabled && !isset($Page->mishData->EditAttrs["readonly"]) && !isset($Page->mishData->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmakina_importi_sherbimeadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fmakina_importi_sherbimeadd", "x_mishData", jQuery.extend(true, {"useCurrent":false}, options));
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
    ew.addEventHandlers("makina_importi_sherbime");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
