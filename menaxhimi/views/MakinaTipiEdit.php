<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaTipiEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_tipi: currentTable } });
var currentForm, currentPageID;
var fmakina_tipiedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_tipiedit = new ew.Form("fmakina_tipiedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fmakina_tipiedit;

    // Add fields
    var fields = currentTable.fields;
    fmakina_tipiedit.addFields([
        ["mtipiID", [fields.mtipiID.visible && fields.mtipiID.required ? ew.Validators.required(fields.mtipiID.caption) : null], fields.mtipiID.isInvalid],
        ["mtipiMarka", [fields.mtipiMarka.visible && fields.mtipiMarka.required ? ew.Validators.required(fields.mtipiMarka.caption) : null], fields.mtipiMarka.isInvalid],
        ["mtipiModeli", [fields.mtipiModeli.visible && fields.mtipiModeli.required ? ew.Validators.required(fields.mtipiModeli.caption) : null], fields.mtipiModeli.isInvalid],
        ["mtipiTipi", [fields.mtipiTipi.visible && fields.mtipiTipi.required ? ew.Validators.required(fields.mtipiTipi.caption) : null], fields.mtipiTipi.isInvalid]
    ]);

    // Form_CustomValidate
    fmakina_tipiedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_tipiedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakina_tipiedit.lists.mtipiMarka = <?= $Page->mtipiMarka->toClientList($Page) ?>;
    fmakina_tipiedit.lists.mtipiModeli = <?= $Page->mtipiModeli->toClientList($Page) ?>;
    loadjs.done("fmakina_tipiedit");
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
<form name="fmakina_tipiedit" id="fmakina_tipiedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_tipi">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->mtipiID->Visible) { // mtipiID ?>
    <div id="r_mtipiID"<?= $Page->mtipiID->rowAttributes() ?>>
        <label id="elh_makina_tipi_mtipiID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mtipiID->caption() ?><?= $Page->mtipiID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mtipiID->cellAttributes() ?>>
<span id="el_makina_tipi_mtipiID">
<span<?= $Page->mtipiID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->mtipiID->getDisplayValue($Page->mtipiID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_tipi" data-field="x_mtipiID" data-hidden="1" name="x_mtipiID" id="x_mtipiID" value="<?= HtmlEncode($Page->mtipiID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mtipiMarka->Visible) { // mtipiMarka ?>
    <div id="r_mtipiMarka"<?= $Page->mtipiMarka->rowAttributes() ?>>
        <label id="elh_makina_tipi_mtipiMarka" for="x_mtipiMarka" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mtipiMarka->caption() ?><?= $Page->mtipiMarka->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mtipiMarka->cellAttributes() ?>>
<span id="el_makina_tipi_mtipiMarka">
<?php $Page->mtipiMarka->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mtipiMarka"
        name="x_mtipiMarka"
        class="form-select ew-select<?= $Page->mtipiMarka->isInvalidClass() ?>"
        data-select2-id="fmakina_tipiedit_x_mtipiMarka"
        data-table="makina_tipi"
        data-field="x_mtipiMarka"
        data-value-separator="<?= $Page->mtipiMarka->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mtipiMarka->getPlaceHolder()) ?>"
        <?= $Page->mtipiMarka->editAttributes() ?>>
        <?= $Page->mtipiMarka->selectOptionListHtml("x_mtipiMarka") ?>
    </select>
    <?= $Page->mtipiMarka->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mtipiMarka->getErrorMessage() ?></div>
<?= $Page->mtipiMarka->Lookup->getParamTag($Page, "p_x_mtipiMarka") ?>
<script>
loadjs.ready("fmakina_tipiedit", function() {
    var options = { name: "x_mtipiMarka", selectId: "fmakina_tipiedit_x_mtipiMarka" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_tipiedit.lists.mtipiMarka.lookupOptions.length) {
        options.data = { id: "x_mtipiMarka", form: "fmakina_tipiedit" };
    } else {
        options.ajax = { id: "x_mtipiMarka", form: "fmakina_tipiedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_tipi.fields.mtipiMarka.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mtipiModeli->Visible) { // mtipiModeli ?>
    <div id="r_mtipiModeli"<?= $Page->mtipiModeli->rowAttributes() ?>>
        <label id="elh_makina_tipi_mtipiModeli" for="x_mtipiModeli" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mtipiModeli->caption() ?><?= $Page->mtipiModeli->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mtipiModeli->cellAttributes() ?>>
<span id="el_makina_tipi_mtipiModeli">
    <select
        id="x_mtipiModeli"
        name="x_mtipiModeli"
        class="form-select ew-select<?= $Page->mtipiModeli->isInvalidClass() ?>"
        data-select2-id="fmakina_tipiedit_x_mtipiModeli"
        data-table="makina_tipi"
        data-field="x_mtipiModeli"
        data-value-separator="<?= $Page->mtipiModeli->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mtipiModeli->getPlaceHolder()) ?>"
        <?= $Page->mtipiModeli->editAttributes() ?>>
        <?= $Page->mtipiModeli->selectOptionListHtml("x_mtipiModeli") ?>
    </select>
    <?= $Page->mtipiModeli->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->mtipiModeli->getErrorMessage() ?></div>
<?= $Page->mtipiModeli->Lookup->getParamTag($Page, "p_x_mtipiModeli") ?>
<script>
loadjs.ready("fmakina_tipiedit", function() {
    var options = { name: "x_mtipiModeli", selectId: "fmakina_tipiedit_x_mtipiModeli" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_tipiedit.lists.mtipiModeli.lookupOptions.length) {
        options.data = { id: "x_mtipiModeli", form: "fmakina_tipiedit" };
    } else {
        options.ajax = { id: "x_mtipiModeli", form: "fmakina_tipiedit", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_tipi.fields.mtipiModeli.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->mtipiTipi->Visible) { // mtipiTipi ?>
    <div id="r_mtipiTipi"<?= $Page->mtipiTipi->rowAttributes() ?>>
        <label id="elh_makina_tipi_mtipiTipi" for="x_mtipiTipi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->mtipiTipi->caption() ?><?= $Page->mtipiTipi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->mtipiTipi->cellAttributes() ?>>
<span id="el_makina_tipi_mtipiTipi">
<input type="<?= $Page->mtipiTipi->getInputTextType() ?>" name="x_mtipiTipi" id="x_mtipiTipi" data-table="makina_tipi" data-field="x_mtipiTipi" value="<?= $Page->mtipiTipi->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->mtipiTipi->getPlaceHolder()) ?>"<?= $Page->mtipiTipi->editAttributes() ?> aria-describedby="x_mtipiTipi_help">
<?= $Page->mtipiTipi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->mtipiTipi->getErrorMessage() ?></div>
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
    ew.addEventHandlers("makina_tipi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
