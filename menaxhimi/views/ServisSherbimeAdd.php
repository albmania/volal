<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisSherbimeAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis_sherbime: currentTable } });
var currentForm, currentPageID;
var fservis_sherbimeadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_sherbimeadd = new ew.Form("fservis_sherbimeadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fservis_sherbimeadd;

    // Add fields
    var fields = currentTable.fields;
    fservis_sherbimeadd.addFields([
        ["servisSherbimServisID", [fields.servisSherbimServisID.visible && fields.servisSherbimServisID.required ? ew.Validators.required(fields.servisSherbimServisID.caption) : null, ew.Validators.integer], fields.servisSherbimServisID.isInvalid],
        ["servisSherbimSherbimi", [fields.servisSherbimSherbimi.visible && fields.servisSherbimSherbimi.required ? ew.Validators.required(fields.servisSherbimSherbimi.caption) : null], fields.servisSherbimSherbimi.isInvalid],
        ["servisSherbimCmimi", [fields.servisSherbimCmimi.visible && fields.servisSherbimCmimi.required ? ew.Validators.required(fields.servisSherbimCmimi.caption) : null, ew.Validators.float], fields.servisSherbimCmimi.isInvalid],
        ["servisSherbimShenim", [fields.servisSherbimShenim.visible && fields.servisSherbimShenim.required ? ew.Validators.required(fields.servisSherbimShenim.caption) : null], fields.servisSherbimShenim.isInvalid]
    ]);

    // Form_CustomValidate
    fservis_sherbimeadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fservis_sherbimeadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fservis_sherbimeadd.lists.servisSherbimSherbimi = <?= $Page->servisSherbimSherbimi->toClientList($Page) ?>;
    loadjs.done("fservis_sherbimeadd");
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
<form name="fservis_sherbimeadd" id="fservis_sherbimeadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis_sherbime">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "servis") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="servis">
<input type="hidden" name="fk_servisID" value="<?= HtmlEncode($Page->servisSherbimServisID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
    <div id="r_servisSherbimServisID"<?= $Page->servisSherbimServisID->rowAttributes() ?>>
        <label id="elh_servis_sherbime_servisSherbimServisID" for="x_servisSherbimServisID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisSherbimServisID->caption() ?><?= $Page->servisSherbimServisID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisSherbimServisID->cellAttributes() ?>>
<?php if ($Page->servisSherbimServisID->getSessionValue() != "") { ?>
<span id="el_servis_sherbime_servisSherbimServisID">
<span<?= $Page->servisSherbimServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->servisSherbimServisID->getDisplayValue($Page->servisSherbimServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_servisSherbimServisID" name="x_servisSherbimServisID" value="<?= HtmlEncode(FormatNumber($Page->servisSherbimServisID->CurrentValue, $Page->servisSherbimServisID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_servis_sherbime_servisSherbimServisID">
<input type="<?= $Page->servisSherbimServisID->getInputTextType() ?>" name="x_servisSherbimServisID" id="x_servisSherbimServisID" data-table="servis_sherbime" data-field="x_servisSherbimServisID" value="<?= $Page->servisSherbimServisID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->servisSherbimServisID->getPlaceHolder()) ?>"<?= $Page->servisSherbimServisID->editAttributes() ?> aria-describedby="x_servisSherbimServisID_help">
<?= $Page->servisSherbimServisID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisSherbimServisID->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
    <div id="r_servisSherbimSherbimi"<?= $Page->servisSherbimSherbimi->rowAttributes() ?>>
        <label id="elh_servis_sherbime_servisSherbimSherbimi" for="x_servisSherbimSherbimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisSherbimSherbimi->caption() ?><?= $Page->servisSherbimSherbimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisSherbimSherbimi->cellAttributes() ?>>
<span id="el_servis_sherbime_servisSherbimSherbimi">
    <select
        id="x_servisSherbimSherbimi"
        name="x_servisSherbimSherbimi"
        class="form-select ew-select<?= $Page->servisSherbimSherbimi->isInvalidClass() ?>"
        data-select2-id="fservis_sherbimeadd_x_servisSherbimSherbimi"
        data-table="servis_sherbime"
        data-field="x_servisSherbimSherbimi"
        data-value-separator="<?= $Page->servisSherbimSherbimi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->servisSherbimSherbimi->getPlaceHolder()) ?>"
        <?= $Page->servisSherbimSherbimi->editAttributes() ?>>
        <?= $Page->servisSherbimSherbimi->selectOptionListHtml("x_servisSherbimSherbimi") ?>
    </select>
    <?= $Page->servisSherbimSherbimi->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->servisSherbimSherbimi->getErrorMessage() ?></div>
<?= $Page->servisSherbimSherbimi->Lookup->getParamTag($Page, "p_x_servisSherbimSherbimi") ?>
<script>
loadjs.ready("fservis_sherbimeadd", function() {
    var options = { name: "x_servisSherbimSherbimi", selectId: "fservis_sherbimeadd_x_servisSherbimSherbimi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservis_sherbimeadd.lists.servisSherbimSherbimi.lookupOptions.length) {
        options.data = { id: "x_servisSherbimSherbimi", form: "fservis_sherbimeadd" };
    } else {
        options.ajax = { id: "x_servisSherbimSherbimi", form: "fservis_sherbimeadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis_sherbime.fields.servisSherbimSherbimi.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
    <div id="r_servisSherbimCmimi"<?= $Page->servisSherbimCmimi->rowAttributes() ?>>
        <label id="elh_servis_sherbime_servisSherbimCmimi" for="x_servisSherbimCmimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisSherbimCmimi->caption() ?><?= $Page->servisSherbimCmimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisSherbimCmimi->cellAttributes() ?>>
<span id="el_servis_sherbime_servisSherbimCmimi">
<input type="<?= $Page->servisSherbimCmimi->getInputTextType() ?>" name="x_servisSherbimCmimi" id="x_servisSherbimCmimi" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" value="<?= $Page->servisSherbimCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->servisSherbimCmimi->getPlaceHolder()) ?>"<?= $Page->servisSherbimCmimi->editAttributes() ?> aria-describedby="x_servisSherbimCmimi_help">
<?= $Page->servisSherbimCmimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisSherbimCmimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
    <div id="r_servisSherbimShenim"<?= $Page->servisSherbimShenim->rowAttributes() ?>>
        <label id="elh_servis_sherbime_servisSherbimShenim" for="x_servisSherbimShenim" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisSherbimShenim->caption() ?><?= $Page->servisSherbimShenim->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisSherbimShenim->cellAttributes() ?>>
<span id="el_servis_sherbime_servisSherbimShenim">
<input type="<?= $Page->servisSherbimShenim->getInputTextType() ?>" name="x_servisSherbimShenim" id="x_servisSherbimShenim" data-table="servis_sherbime" data-field="x_servisSherbimShenim" value="<?= $Page->servisSherbimShenim->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Page->servisSherbimShenim->getPlaceHolder()) ?>"<?= $Page->servisSherbimShenim->editAttributes() ?> aria-describedby="x_servisSherbimShenim_help">
<?= $Page->servisSherbimShenim->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisSherbimShenim->getErrorMessage() ?></div>
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
    ew.addEventHandlers("servis_sherbime");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
