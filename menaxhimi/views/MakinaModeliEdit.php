<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaModeliEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_modeli: currentTable } });
var currentForm, currentPageID;
var fmakina_modeliedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_modeliedit = new ew.Form("fmakina_modeliedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fmakina_modeliedit;

    // Add fields
    var fields = currentTable.fields;
    fmakina_modeliedit.addFields([
        ["mmodeliID", [fields.mmodeliID.visible && fields.mmodeliID.required ? ew.Validators.required(fields.mmodeliID.caption) : null], fields.mmodeliID.isInvalid],
        ["mmodeliMarka", [fields.mmodeliMarka.visible && fields.mmodeliMarka.required ? ew.Validators.required(fields.mmodeliMarka.caption) : null], fields.mmodeliMarka.isInvalid],
        ["mmodeliModeli", [fields.mmodeliModeli.visible && fields.mmodeliModeli.required ? ew.Validators.required(fields.mmodeliModeli.caption) : null], fields.mmodeliModeli.isInvalid]
    ]);

    // Form_CustomValidate
    fmakina_modeliedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_modeliedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakina_modeliedit.lists.mmodeliMarka = <?= $Page->mmodeliMarka->toClientList($Page) ?>;
    loadjs.done("fmakina_modeliedit");
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
<form name="fmakina_modeliedit" id="fmakina_modeliedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_modeli">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->mmodeliID->Visible) { // mmodeliID ?>
    <div id="r_mmodeliID"<?= $Page->mmodeliID->rowAttributes() ?>>
        <label id="elh_makina_modeli_mmodeliID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mmodeliID->caption() ?><?= $Page->mmodeliID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mmodeliID->cellAttributes() ?>>
<span id="el_makina_modeli_mmodeliID">
<span<?= $Page->mmodeliID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->mmodeliID->getDisplayValue($Page->mmodeliID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_modeli" data-field="x_mmodeliID" data-hidden="1" name="x_mmodeliID" id="x_mmodeliID" value="<?= HtmlEncode($Page->mmodeliID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mmodeliMarka->Visible) { // mmodeliMarka ?>
    <div id="r_mmodeliMarka"<?= $Page->mmodeliMarka->rowAttributes() ?>>
        <label id="elh_makina_modeli_mmodeliMarka" for="x_mmodeliMarka" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mmodeliMarka->caption() ?><?= $Page->mmodeliMarka->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mmodeliMarka->cellAttributes() ?>>
<span id="el_makina_modeli_mmodeliMarka">
    <select
        id="x_mmodeliMarka"
        name="x_mmodeliMarka"
        class="form-select ew-select<?= $Page->mmodeliMarka->isInvalidClass() ?>"
        data-select2-id="fmakina_modeliedit_x_mmodeliMarka"
        data-table="makina_modeli"
        data-field="x_mmodeliMarka"
        data-value-separator="<?= $Page->mmodeliMarka->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mmodeliMarka->getPlaceHolder()) ?>"
        <?= $Page->mmodeliMarka->editAttributes() ?>>
        <?= $Page->mmodeliMarka->selectOptionListHtml("x_mmodeliMarka") ?>
    </select>
    <?= $Page->mmodeliMarka->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mmodeliMarka->getErrorMessage() ?></div>
<?= $Page->mmodeliMarka->Lookup->getParamTag($Page, "p_x_mmodeliMarka") ?>
<script>
loadjs.ready("fmakina_modeliedit", function() {
    var options = { name: "x_mmodeliMarka", selectId: "fmakina_modeliedit_x_mmodeliMarka" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_modeliedit.lists.mmodeliMarka.lookupOptions.length) {
        options.data = { id: "x_mmodeliMarka", form: "fmakina_modeliedit" };
    } else {
        options.ajax = { id: "x_mmodeliMarka", form: "fmakina_modeliedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_modeli.fields.mmodeliMarka.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mmodeliModeli->Visible) { // mmodeliModeli ?>
    <div id="r_mmodeliModeli"<?= $Page->mmodeliModeli->rowAttributes() ?>>
        <label id="elh_makina_modeli_mmodeliModeli" for="x_mmodeliModeli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mmodeliModeli->caption() ?><?= $Page->mmodeliModeli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mmodeliModeli->cellAttributes() ?>>
<span id="el_makina_modeli_mmodeliModeli">
<input type="<?= $Page->mmodeliModeli->getInputTextType() ?>" name="x_mmodeliModeli" id="x_mmodeliModeli" data-table="makina_modeli" data-field="x_mmodeliModeli" value="<?= $Page->mmodeliModeli->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->mmodeliModeli->getPlaceHolder()) ?>"<?= $Page->mmodeliModeli->editAttributes() ?> aria-describedby="x_mmodeliModeli_help">
<?= $Page->mmodeliModeli->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mmodeliModeli->getErrorMessage() ?></div>
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
    ew.addEventHandlers("makina_modeli");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
