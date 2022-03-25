<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisPjesetEdit = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis_pjeset: currentTable } });
var currentForm, currentPageID;
var fservis_pjesetedit;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_pjesetedit = new ew.Form("fservis_pjesetedit", "edit");
    currentPageID = ew.PAGE_ID = "edit";
    currentForm = fservis_pjesetedit;

    // Add fields
    var fields = currentTable.fields;
    fservis_pjesetedit.addFields([
        ["servisPjeseID", [fields.servisPjeseID.visible && fields.servisPjeseID.required ? ew.Validators.required(fields.servisPjeseID.caption) : null], fields.servisPjeseID.isInvalid],
        ["servisPjeseServisID", [fields.servisPjeseServisID.visible && fields.servisPjeseServisID.required ? ew.Validators.required(fields.servisPjeseServisID.caption) : null, ew.Validators.integer], fields.servisPjeseServisID.isInvalid],
        ["servisPjesePjesa", [fields.servisPjesePjesa.visible && fields.servisPjesePjesa.required ? ew.Validators.required(fields.servisPjesePjesa.caption) : null], fields.servisPjesePjesa.isInvalid],
        ["servisPjeseSasia", [fields.servisPjeseSasia.visible && fields.servisPjeseSasia.required ? ew.Validators.required(fields.servisPjeseSasia.caption) : null, ew.Validators.integer], fields.servisPjeseSasia.isInvalid],
        ["servisPjeseCmimi", [fields.servisPjeseCmimi.visible && fields.servisPjeseCmimi.required ? ew.Validators.required(fields.servisPjeseCmimi.caption) : null, ew.Validators.float], fields.servisPjeseCmimi.isInvalid],
        ["servisPjeseShenim", [fields.servisPjeseShenim.visible && fields.servisPjeseShenim.required ? ew.Validators.required(fields.servisPjeseShenim.caption) : null], fields.servisPjeseShenim.isInvalid]
    ]);

    // Form_CustomValidate
    fservis_pjesetedit.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fservis_pjesetedit.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fservis_pjesetedit.lists.servisPjesePjesa = <?= $Page->servisPjesePjesa->toClientList($Page) ?>;
    loadjs.done("fservis_pjesetedit");
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
<form name="fservis_pjesetedit" id="fservis_pjesetedit" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis_pjeset">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<?php if ($Page->getCurrentMasterTable() == "servis") { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="servis">
<input type="hidden" name="fk_servisID" value="<?= HtmlEncode($Page->servisPjeseServisID->getSessionValue()) ?>">
<?php } ?>
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->servisPjeseID->Visible) { // servisPjeseID ?>
    <div id="r_servisPjeseID"<?= $Page->servisPjeseID->rowAttributes() ?>>
        <label id="elh_servis_pjeset_servisPjeseID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisPjeseID->caption() ?><?= $Page->servisPjeseID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisPjeseID->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseID">
<span<?= $Page->servisPjeseID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->servisPjeseID->getDisplayValue($Page->servisPjeseID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseID" data-hidden="1" name="x_servisPjeseID" id="x_servisPjeseID" value="<?= HtmlEncode($Page->servisPjeseID->CurrentValue) ?>">
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
    <div id="r_servisPjeseServisID"<?= $Page->servisPjeseServisID->rowAttributes() ?>>
        <label id="elh_servis_pjeset_servisPjeseServisID" for="x_servisPjeseServisID" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisPjeseServisID->caption() ?><?= $Page->servisPjeseServisID->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisPjeseServisID->cellAttributes() ?>>
<?php if ($Page->servisPjeseServisID->getSessionValue() != "") { ?>
<span id="el_servis_pjeset_servisPjeseServisID">
<span<?= $Page->servisPjeseServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Page->servisPjeseServisID->getDisplayValue($Page->servisPjeseServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x_servisPjeseServisID" name="x_servisPjeseServisID" value="<?= HtmlEncode(FormatNumber($Page->servisPjeseServisID->CurrentValue, $Page->servisPjeseServisID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el_servis_pjeset_servisPjeseServisID">
<input type="<?= $Page->servisPjeseServisID->getInputTextType() ?>" name="x_servisPjeseServisID" id="x_servisPjeseServisID" data-table="servis_pjeset" data-field="x_servisPjeseServisID" value="<?= $Page->servisPjeseServisID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->servisPjeseServisID->getPlaceHolder()) ?>"<?= $Page->servisPjeseServisID->editAttributes() ?> aria-describedby="x_servisPjeseServisID_help">
<?= $Page->servisPjeseServisID->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisPjeseServisID->getErrorMessage() ?></div>
</span>
<?php } ?>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
    <div id="r_servisPjesePjesa"<?= $Page->servisPjesePjesa->rowAttributes() ?>>
        <label id="elh_servis_pjeset_servisPjesePjesa" for="x_servisPjesePjesa" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisPjesePjesa->caption() ?><?= $Page->servisPjesePjesa->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisPjesePjesa->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjesePjesa">
    <select
        id="x_servisPjesePjesa"
        name="x_servisPjesePjesa"
        class="form-select ew-select<?= $Page->servisPjesePjesa->isInvalidClass() ?>"
        data-select2-id="fservis_pjesetedit_x_servisPjesePjesa"
        data-table="servis_pjeset"
        data-field="x_servisPjesePjesa"
        data-value-separator="<?= $Page->servisPjesePjesa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->servisPjesePjesa->getPlaceHolder()) ?>"
        <?= $Page->servisPjesePjesa->editAttributes() ?>>
        <?= $Page->servisPjesePjesa->selectOptionListHtml("x_servisPjesePjesa") ?>
    </select>
    <?= $Page->servisPjesePjesa->getCustomMessage() ?>
    <div class="invalid-feedback"><?= $Page->servisPjesePjesa->getErrorMessage() ?></div>
<?= $Page->servisPjesePjesa->Lookup->getParamTag($Page, "p_x_servisPjesePjesa") ?>
<script>
loadjs.ready("fservis_pjesetedit", function() {
    var options = { name: "x_servisPjesePjesa", selectId: "fservis_pjesetedit_x_servisPjesePjesa" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservis_pjesetedit.lists.servisPjesePjesa.lookupOptions.length) {
        options.data = { id: "x_servisPjesePjesa", form: "fservis_pjesetedit" };
    } else {
        options.ajax = { id: "x_servisPjesePjesa", form: "fservis_pjesetedit", limit: 20 };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis_pjeset.fields.servisPjesePjesa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
    <div id="r_servisPjeseSasia"<?= $Page->servisPjeseSasia->rowAttributes() ?>>
        <label id="elh_servis_pjeset_servisPjeseSasia" for="x_servisPjeseSasia" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisPjeseSasia->caption() ?><?= $Page->servisPjeseSasia->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisPjeseSasia->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseSasia">
<input type="<?= $Page->servisPjeseSasia->getInputTextType() ?>" name="x_servisPjeseSasia" id="x_servisPjeseSasia" data-table="servis_pjeset" data-field="x_servisPjeseSasia" value="<?= $Page->servisPjeseSasia->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->servisPjeseSasia->getPlaceHolder()) ?>"<?= $Page->servisPjeseSasia->editAttributes() ?> aria-describedby="x_servisPjeseSasia_help">
<?= $Page->servisPjeseSasia->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisPjeseSasia->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
    <div id="r_servisPjeseCmimi"<?= $Page->servisPjeseCmimi->rowAttributes() ?>>
        <label id="elh_servis_pjeset_servisPjeseCmimi" for="x_servisPjeseCmimi" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisPjeseCmimi->caption() ?><?= $Page->servisPjeseCmimi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisPjeseCmimi->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseCmimi">
<input type="<?= $Page->servisPjeseCmimi->getInputTextType() ?>" name="x_servisPjeseCmimi" id="x_servisPjeseCmimi" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" value="<?= $Page->servisPjeseCmimi->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Page->servisPjeseCmimi->getPlaceHolder()) ?>"<?= $Page->servisPjeseCmimi->editAttributes() ?> aria-describedby="x_servisPjeseCmimi_help">
<?= $Page->servisPjeseCmimi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisPjeseCmimi->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
    <div id="r_servisPjeseShenim"<?= $Page->servisPjeseShenim->rowAttributes() ?>>
        <label id="elh_servis_pjeset_servisPjeseShenim" for="x_servisPjeseShenim" class="<?= $Page->LeftColumnClass ?>"><?= $Page->servisPjeseShenim->caption() ?><?= $Page->servisPjeseShenim->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisPjeseShenim->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseShenim">
<input type="<?= $Page->servisPjeseShenim->getInputTextType() ?>" name="x_servisPjeseShenim" id="x_servisPjeseShenim" data-table="servis_pjeset" data-field="x_servisPjeseShenim" value="<?= $Page->servisPjeseShenim->EditValue ?>" size="20" maxlength="250" placeholder="<?= HtmlEncode($Page->servisPjeseShenim->getPlaceHolder()) ?>"<?= $Page->servisPjeseShenim->editAttributes() ?> aria-describedby="x_servisPjeseShenim_help">
<?= $Page->servisPjeseShenim->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisPjeseShenim->getErrorMessage() ?></div>
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
    ew.addEventHandlers("servis_pjeset");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
