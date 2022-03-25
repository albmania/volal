<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisAdd = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis: currentTable } });
var currentForm, currentPageID;
var fservisadd;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservisadd = new ew.Form("fservisadd", "add");
    currentPageID = ew.PAGE_ID = "add";
    currentForm = fservisadd;

    // Add fields
    var fields = currentTable.fields;
    fservisadd.addFields([
        ["servisDate", [fields.servisDate.visible && fields.servisDate.required ? ew.Validators.required(fields.servisDate.caption) : null, ew.Validators.datetime(fields.servisDate.clientFormatPattern)], fields.servisDate.isInvalid],
        ["servisKlienti", [fields.servisKlienti.visible && fields.servisKlienti.required ? ew.Validators.required(fields.servisKlienti.caption) : null], fields.servisKlienti.isInvalid],
        ["servisMakina", [fields.servisMakina.visible && fields.servisMakina.required ? ew.Validators.required(fields.servisMakina.caption) : null], fields.servisMakina.isInvalid],
        ["servisKmMakines", [fields.servisKmMakines.visible && fields.servisKmMakines.required ? ew.Validators.required(fields.servisKmMakines.caption) : null, ew.Validators.integer], fields.servisKmMakines.isInvalid],
        ["servisStafi", [fields.servisStafi.visible && fields.servisStafi.required ? ew.Validators.required(fields.servisStafi.caption) : null], fields.servisStafi.isInvalid],
        ["servisTotaliFatures", [fields.servisTotaliFatures.visible && fields.servisTotaliFatures.required ? ew.Validators.required(fields.servisTotaliFatures.caption) : null, ew.Validators.float], fields.servisTotaliFatures.isInvalid],
        ["servisAutori", [fields.servisAutori.visible && fields.servisAutori.required ? ew.Validators.required(fields.servisAutori.caption) : null], fields.servisAutori.isInvalid],
        ["servisShtuar", [fields.servisShtuar.visible && fields.servisShtuar.required ? ew.Validators.required(fields.servisShtuar.caption) : null], fields.servisShtuar.isInvalid]
    ]);

    // Form_CustomValidate
    fservisadd.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fservisadd.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fservisadd.lists.servisKlienti = <?= $Page->servisKlienti->toClientList($Page) ?>;
    fservisadd.lists.servisMakina = <?= $Page->servisMakina->toClientList($Page) ?>;
    fservisadd.lists.servisStafi = <?= $Page->servisStafi->toClientList($Page) ?>;
    fservisadd.lists.servisAutori = <?= $Page->servisAutori->toClientList($Page) ?>;
    loadjs.done("fservisadd");
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
<form name="fservisadd" id="fservisadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<input type="hidden" name="<?= $Page->OldKeyName ?>" value="<?= $Page->OldKey ?>">
<div class="ew-add-div d-none"><!-- page* -->
<?php if ($Page->servisDate->Visible) { // servisDate ?>
    <div id="r_servisDate"<?= $Page->servisDate->rowAttributes() ?>>
        <label id="elh_servis_servisDate" for="x_servisDate" class="<?= $Page->LeftColumnClass ?>"><template id="tpc_servis_servisDate"><?= $Page->servisDate->caption() ?><?= $Page->servisDate->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></template></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisDate->cellAttributes() ?>>
<template id="tpx_servis_servisDate"><span id="el_servis_servisDate">
<input type="<?= $Page->servisDate->getInputTextType() ?>" name="x_servisDate" id="x_servisDate" data-table="servis" data-field="x_servisDate" value="<?= $Page->servisDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->servisDate->getPlaceHolder()) ?>"<?= $Page->servisDate->editAttributes() ?> aria-describedby="x_servisDate_help">
<?= $Page->servisDate->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisDate->getErrorMessage() ?></div>
<?php if (!$Page->servisDate->ReadOnly && !$Page->servisDate->Disabled && !isset($Page->servisDate->EditAttrs["readonly"]) && !isset($Page->servisDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fservisadd", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fservisadd", "x_servisDate", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span></template>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisKlienti->Visible) { // servisKlienti ?>
    <div id="r_servisKlienti"<?= $Page->servisKlienti->rowAttributes() ?>>
        <label id="elh_servis_servisKlienti" for="x_servisKlienti" class="<?= $Page->LeftColumnClass ?>"><template id="tpc_servis_servisKlienti"><?= $Page->servisKlienti->caption() ?><?= $Page->servisKlienti->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></template></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisKlienti->cellAttributes() ?>>
<template id="tpx_servis_servisKlienti"><span id="el_servis_servisKlienti">
<?php $Page->servisKlienti->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
<div class="input-group flex-nowrap">
    <select
        id="x_servisKlienti"
        name="x_servisKlienti"
        class="form-select ew-select<?= $Page->servisKlienti->isInvalidClass() ?>"
        data-select2-id="fservisadd_x_servisKlienti"
        data-table="servis"
        data-field="x_servisKlienti"
        data-value-separator="<?= $Page->servisKlienti->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->servisKlienti->getPlaceHolder()) ?>"
        <?= $Page->servisKlienti->editAttributes() ?>>
        <?= $Page->servisKlienti->selectOptionListHtml("x_servisKlienti") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "klient") && !$Page->servisKlienti->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_servisKlienti" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->servisKlienti->caption() ?>" data-title="<?= $Page->servisKlienti->caption() ?>" data-ew-action="add-option" data-el="x_servisKlienti" data-url="<?= GetUrl("KlientAddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<?= $Page->servisKlienti->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisKlienti->getErrorMessage() ?></div>
<?= $Page->servisKlienti->Lookup->getParamTag($Page, "p_x_servisKlienti") ?>
<script>
loadjs.ready("fservisadd", function() {
    var options = { name: "x_servisKlienti", selectId: "fservisadd_x_servisKlienti" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservisadd.lists.servisKlienti.lookupOptions.length) {
        options.data = { id: "x_servisKlienti", form: "fservisadd" };
    } else {
        options.ajax = { id: "x_servisKlienti", form: "fservisadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis.fields.servisKlienti.selectOptions);
    ew.createSelect(options);
});
</script>
</span></template>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisMakina->Visible) { // servisMakina ?>
    <div id="r_servisMakina"<?= $Page->servisMakina->rowAttributes() ?>>
        <label id="elh_servis_servisMakina" for="x_servisMakina" class="<?= $Page->LeftColumnClass ?>"><template id="tpc_servis_servisMakina"><?= $Page->servisMakina->caption() ?><?= $Page->servisMakina->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></template></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisMakina->cellAttributes() ?>>
<template id="tpx_servis_servisMakina"><span id="el_servis_servisMakina">
<div class="input-group flex-nowrap">
    <select
        id="x_servisMakina"
        name="x_servisMakina"
        class="form-select ew-select<?= $Page->servisMakina->isInvalidClass() ?>"
        data-select2-id="fservisadd_x_servisMakina"
        data-table="servis"
        data-field="x_servisMakina"
        data-value-separator="<?= $Page->servisMakina->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->servisMakina->getPlaceHolder()) ?>"
        <?= $Page->servisMakina->editAttributes() ?>>
        <?= $Page->servisMakina->selectOptionListHtml("x_servisMakina") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "makina") && !$Page->servisMakina->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_servisMakina" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->servisMakina->caption() ?>" data-title="<?= $Page->servisMakina->caption() ?>" data-ew-action="add-option" data-el="x_servisMakina" data-url="<?= GetUrl("MakinaAddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<?= $Page->servisMakina->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisMakina->getErrorMessage() ?></div>
<?= $Page->servisMakina->Lookup->getParamTag($Page, "p_x_servisMakina") ?>
<script>
loadjs.ready("fservisadd", function() {
    var options = { name: "x_servisMakina", selectId: "fservisadd_x_servisMakina" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservisadd.lists.servisMakina.lookupOptions.length) {
        options.data = { id: "x_servisMakina", form: "fservisadd" };
    } else {
        options.ajax = { id: "x_servisMakina", form: "fservisadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis.fields.servisMakina.selectOptions);
    ew.createSelect(options);
});
</script>
</span></template>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisKmMakines->Visible) { // servisKmMakines ?>
    <div id="r_servisKmMakines"<?= $Page->servisKmMakines->rowAttributes() ?>>
        <label id="elh_servis_servisKmMakines" for="x_servisKmMakines" class="<?= $Page->LeftColumnClass ?>"><template id="tpc_servis_servisKmMakines"><?= $Page->servisKmMakines->caption() ?><?= $Page->servisKmMakines->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></template></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisKmMakines->cellAttributes() ?>>
<template id="tpx_servis_servisKmMakines"><span id="el_servis_servisKmMakines">
<input type="<?= $Page->servisKmMakines->getInputTextType() ?>" name="x_servisKmMakines" id="x_servisKmMakines" data-table="servis" data-field="x_servisKmMakines" value="<?= $Page->servisKmMakines->EditValue ?>" size="20" maxlength="10" placeholder="<?= HtmlEncode($Page->servisKmMakines->getPlaceHolder()) ?>"<?= $Page->servisKmMakines->editAttributes() ?> aria-describedby="x_servisKmMakines_help">
<?= $Page->servisKmMakines->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisKmMakines->getErrorMessage() ?></div>
</span></template>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisStafi->Visible) { // servisStafi ?>
    <div id="r_servisStafi"<?= $Page->servisStafi->rowAttributes() ?>>
        <label id="elh_servis_servisStafi" for="x_servisStafi" class="<?= $Page->LeftColumnClass ?>"><template id="tpc_servis_servisStafi"><?= $Page->servisStafi->caption() ?><?= $Page->servisStafi->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></template></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisStafi->cellAttributes() ?>>
<template id="tpx_servis_servisStafi"><span id="el_servis_servisStafi">
<div class="input-group flex-nowrap">
    <select
        id="x_servisStafi"
        name="x_servisStafi"
        class="form-select ew-select<?= $Page->servisStafi->isInvalidClass() ?>"
        data-select2-id="fservisadd_x_servisStafi"
        data-table="servis"
        data-field="x_servisStafi"
        data-value-separator="<?= $Page->servisStafi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->servisStafi->getPlaceHolder()) ?>"
        <?= $Page->servisStafi->editAttributes() ?>>
        <?= $Page->servisStafi->selectOptionListHtml("x_servisStafi") ?>
    </select>
    <?php if (AllowAdd(CurrentProjectID() . "stafi") && !$Page->servisStafi->ReadOnly) { ?>
    <button type="button" class="btn btn-default ew-add-opt-btn" id="aol_x_servisStafi" title="<?= HtmlTitle($Language->phrase("AddLink")) . "&nbsp;" . $Page->servisStafi->caption() ?>" data-title="<?= $Page->servisStafi->caption() ?>" data-ew-action="add-option" data-el="x_servisStafi" data-url="<?= GetUrl("StafiAddopt") ?>"><i class="fas fa-plus ew-icon"></i></button>
    <?php } ?>
</div>
<?= $Page->servisStafi->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisStafi->getErrorMessage() ?></div>
<?= $Page->servisStafi->Lookup->getParamTag($Page, "p_x_servisStafi") ?>
<script>
loadjs.ready("fservisadd", function() {
    var options = { name: "x_servisStafi", selectId: "fservisadd_x_servisStafi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservisadd.lists.servisStafi.lookupOptions.length) {
        options.data = { id: "x_servisStafi", form: "fservisadd" };
    } else {
        options.ajax = { id: "x_servisStafi", form: "fservisadd", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis.fields.servisStafi.selectOptions);
    ew.createSelect(options);
});
</script>
</span></template>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->servisTotaliFatures->Visible) { // servisTotaliFatures ?>
    <div id="r_servisTotaliFatures"<?= $Page->servisTotaliFatures->rowAttributes() ?>>
        <label id="elh_servis_servisTotaliFatures" for="x_servisTotaliFatures" class="<?= $Page->LeftColumnClass ?>"><template id="tpc_servis_servisTotaliFatures"><?= $Page->servisTotaliFatures->caption() ?><?= $Page->servisTotaliFatures->Required ? $Language->phrase("FieldRequiredIndicator") : "" ?></template></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->servisTotaliFatures->cellAttributes() ?>>
<template id="tpx_servis_servisTotaliFatures"><span id="el_servis_servisTotaliFatures">
<input type="<?= $Page->servisTotaliFatures->getInputTextType() ?>" name="x_servisTotaliFatures" id="x_servisTotaliFatures" data-table="servis" data-field="x_servisTotaliFatures" value="<?= $Page->servisTotaliFatures->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->servisTotaliFatures->getPlaceHolder()) ?>"<?= $Page->servisTotaliFatures->editAttributes() ?> aria-describedby="x_servisTotaliFatures_help">
<?= $Page->servisTotaliFatures->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->servisTotaliFatures->getErrorMessage() ?></div>
</span></template>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<div id="tpd_servisadd" class="ew-custom-template"></div>
<template id="tpm_servisadd">
<div id="ct_ServisAdd"><div class="row modServis">
	<div class="col-sm-6 majtas">
	<div class="col-sm-12">
		<div class="col-sm-4 inlineFloat"><strong><?= $Page->servisDate->caption() ?> <span class="detyrueshme">*</span></strong></div>
		<div class="col-sm-8 inlineFloat">	<slot class="ew-slot" name="tpx_servis_servisDate"></slot></div>
	</div>
	<div class="col-sm-12">
		<div class="col-sm-4 inlineFloat"><strong><?= $Page->servisStafi->caption() ?> <span class="detyrueshme">*</span></strong></div>
		<div class="col-sm-8 inlineFloat">	<slot class="ew-slot" name="tpx_servis_servisStafi"></slot></div>
	</div>
	<div class="col-sm-12">
		<div class="col-sm-4 inlineFloat"><strong><?= $Page->servisTotaliFatures->caption() ?> <span class="detyrueshme">*</span></strong></div>
		<div class="col-sm-8 inlineFloat">	<slot class="ew-slot" name="tpx_servis_servisTotaliFatures"></slot></div>
	</div>
	</div>
	<div class="col-sm-6 djathtas">
		<div class="col-sm-12">
			<div class="col-sm-4 inlineFloat"><strong><?= $Page->servisKlienti->caption() ?> <span class="detyrueshme">*</span></strong></div>
			<div class="col-sm-8 inlineFloat">	<slot class="ew-slot" name="tpx_servis_servisKlienti"></slot></div>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-4 inlineFloat"><strong><?= $Page->servisMakina->caption() ?> <span class="detyrueshme">*</span></strong></div>
			<div class="col-sm-8 inlineFloat">	<slot class="ew-slot" name="tpx_servis_servisMakina"></slot></div>
		</div>
		<div class="col-sm-12">
			<div class="col-sm-4 inlineFloat"><strong><?= $Page->servisKmMakines->caption() ?> <span class="detyrueshme">*</span></strong></div>
			<div class="col-sm-8 inlineFloat">	<slot class="ew-slot" name="tpx_servis_servisKmMakines"></slot></div>
		</div>
	</div>
</div>
</div>
</template>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<?php
    $Page->DetailPages->ValidKeys = explode(",", $Page->getCurrentDetailTable());
?>
<div class="ew-detail-pages"><!-- detail-pages -->
<div class="ew-nav<?= $Page->DetailPages->containerClasses() ?>" id="details_Page"><!-- tabs -->
    <ul class="<?= $Page->DetailPages->navClasses() ?>" role="tablist"><!-- .nav -->
<?php
    if (in_array("servis_pjeset", explode(",", $Page->getCurrentDetailTable())) && $servis_pjeset->DetailAdd) {
?>
        <li class="nav-item"><button class="<?= $Page->DetailPages->navLinkClasses("servis_pjeset") ?><?= $Page->DetailPages->activeClasses("servis_pjeset") ?>" data-bs-target="#tab_servis_pjeset" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_servis_pjeset" aria-selected="<?= JsonEncode($Page->DetailPages->isActive("servis_pjeset")) ?>"><?= $Language->tablePhrase("servis_pjeset", "TblCaption") ?></button></li>
<?php
    }
?>
<?php
    if (in_array("servis_sherbime", explode(",", $Page->getCurrentDetailTable())) && $servis_sherbime->DetailAdd) {
?>
        <li class="nav-item"><button class="<?= $Page->DetailPages->navLinkClasses("servis_sherbime") ?><?= $Page->DetailPages->activeClasses("servis_sherbime") ?>" data-bs-target="#tab_servis_sherbime" data-bs-toggle="tab" type="button" role="tab" aria-controls="tab_servis_sherbime" aria-selected="<?= JsonEncode($Page->DetailPages->isActive("servis_sherbime")) ?>"><?= $Language->tablePhrase("servis_sherbime", "TblCaption") ?></button></li>
<?php
    }
?>
    </ul><!-- /.nav -->
    <div class="<?= $Page->DetailPages->tabContentClasses() ?>"><!-- .tab-content -->
<?php
    if (in_array("servis_pjeset", explode(",", $Page->getCurrentDetailTable())) && $servis_pjeset->DetailAdd) {
?>
        <div class="<?= $Page->DetailPages->tabPaneClasses("servis_pjeset") ?><?= $Page->DetailPages->activeClasses("servis_pjeset") ?>" id="tab_servis_pjeset" role="tabpanel"><!-- page* -->
<?php include_once "ServisPjesetGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
<?php
    if (in_array("servis_sherbime", explode(",", $Page->getCurrentDetailTable())) && $servis_sherbime->DetailAdd) {
?>
        <div class="<?= $Page->DetailPages->tabPaneClasses("servis_sherbime") ?><?= $Page->DetailPages->activeClasses("servis_sherbime") ?>" id="tab_servis_sherbime" role="tabpanel"><!-- page* -->
<?php include_once "ServisSherbimeGrid.php" ?>
        </div><!-- /page* -->
<?php } ?>
    </div><!-- /.tab-content -->
</div><!-- /tabs -->
</div><!-- /detail-pages -->
<?php } ?>
<?php if (!$Page->IsModal) { ?>
<div class="row"><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("AddBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
    </div><!-- /buttons offset -->
</div><!-- /buttons .row -->
<?php } ?>
</form>
<script class="ew-apply-template">
loadjs.ready(ew.applyTemplateId, function() {
    ew.templateData = { rows: <?= JsonEncode($Page->Rows) ?> };
    ew.applyTemplate("tpd_servisadd", "tpm_servisadd", "servisadd", "<?= $Page->CustomExport ?>", ew.templateData.rows[0]);
    loadjs.done("customtemplate");
});
</script>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("servis");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
