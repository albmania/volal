<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaShitjeList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_shitje: currentTable } });
var currentForm, currentPageID;
var fmakina_shitjelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_shitjelist = new ew.Form("fmakina_shitjelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fmakina_shitjelist;
    fmakina_shitjelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fmakina_shitjelist");
});
var fmakina_shitjesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fmakina_shitjesrch = new ew.Form("fmakina_shitjesrch", "list");
    currentSearchForm = fmakina_shitjesrch;

    // Add fields
    var fields = currentTable.fields;
    fmakina_shitjesrch.addFields([
        ["mshitjeID", [], fields.mshitjeID.isInvalid],
        ["mshitjeMarka", [], fields.mshitjeMarka.isInvalid],
        ["y_mshitjeMarka", [ew.Validators.between], false],
        ["mshitjeModeli", [], fields.mshitjeModeli.isInvalid],
        ["y_mshitjeModeli", [ew.Validators.between], false],
        ["mshitjeTipi", [], fields.mshitjeTipi.isInvalid],
        ["y_mshitjeTipi", [ew.Validators.between], false],
        ["mshitjeKapacitetiMotorrit", [], fields.mshitjeKapacitetiMotorrit.isInvalid],
        ["mshitjeVitiProdhimit", [ew.Validators.integer], fields.mshitjeVitiProdhimit.isInvalid],
        ["y_mshitjeVitiProdhimit", [ew.Validators.between], false],
        ["mshitjeKarburant", [], fields.mshitjeKarburant.isInvalid],
        ["y_mshitjeKarburant", [ew.Validators.between], false],
        ["mshitjeNrVendeve", [], fields.mshitjeNrVendeve.isInvalid],
        ["mshitjeKambio", [], fields.mshitjeKambio.isInvalid],
        ["y_mshitjeKambio", [ew.Validators.between], false],
        ["mshitjeTargaAL", [], fields.mshitjeTargaAL.isInvalid],
        ["y_mshitjeTargaAL", [ew.Validators.between], false],
        ["mshitjeKilometra", [], fields.mshitjeKilometra.isInvalid],
        ["mshitjeCmimi", [], fields.mshitjeCmimi.isInvalid],
        ["mshitjeIndex", [], fields.mshitjeIndex.isInvalid],
        ["y_mshitjeIndex", [ew.Validators.between], false],
        ["mshitjePromo", [], fields.mshitjePromo.isInvalid],
        ["y_mshitjePromo", [ew.Validators.between], false],
        ["mshitjeAktiv", [], fields.mshitjeAktiv.isInvalid],
        ["y_mshitjeAktiv", [ew.Validators.between], false],
        ["mshitjeShitur", [], fields.mshitjeShitur.isInvalid],
        ["y_mshitjeShitur", [ew.Validators.between], false],
        ["mshitjeAutori", [], fields.mshitjeAutori.isInvalid],
        ["mshitjeKrijuar", [], fields.mshitjeKrijuar.isInvalid],
        ["mshitjeAzhornuar", [], fields.mshitjeAzhornuar.isInvalid]
    ]);

    // Validate form
    fmakina_shitjesrch.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm();

        // Validate fields
        if (!this.validateFields())
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    fmakina_shitjesrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_shitjesrch.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakina_shitjesrch.lists.mshitjeMarka = <?= $Page->mshitjeMarka->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjeModeli = <?= $Page->mshitjeModeli->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjeTipi = <?= $Page->mshitjeTipi->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjeKarburant = <?= $Page->mshitjeKarburant->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjeKambio = <?= $Page->mshitjeKambio->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjeTargaAL = <?= $Page->mshitjeTargaAL->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjeIndex = <?= $Page->mshitjeIndex->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjePromo = <?= $Page->mshitjePromo->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjeAktiv = <?= $Page->mshitjeAktiv->toClientList($Page) ?>;
    fmakina_shitjesrch.lists.mshitjeShitur = <?= $Page->mshitjeShitur->toClientList($Page) ?>;

    // Filters
    fmakina_shitjesrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fmakina_shitjesrch.initSearchPanel = true;
    loadjs.done("fmakina_shitjesrch");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fmakina_shitjesrch" id="fmakina_shitjesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fmakina_shitjesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="makina_shitje">
<div class="ew-extended-search container-fluid">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->mshitjeMarka->Visible) { // mshitjeMarka ?>
<?php
if (!$Page->mshitjeMarka->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeMarka" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeMarka->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_mshitjeMarka" class="ew-search-caption ew-label"><?= $Page->mshitjeMarka->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeMarka" id="z_mshitjeMarka" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeMarka->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeMarka->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeMarka->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeMarka->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeMarka->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeMarka->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeMarka->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeMarka" class="ew-search-field">
<?php $Page->mshitjeMarka->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mshitjeMarka"
        name="x_mshitjeMarka"
        class="form-select ew-select<?= $Page->mshitjeMarka->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjesrch_x_mshitjeMarka"
        data-table="makina_shitje"
        data-field="x_mshitjeMarka"
        data-value-separator="<?= $Page->mshitjeMarka->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeMarka->getPlaceHolder()) ?>"
        <?= $Page->mshitjeMarka->editAttributes() ?>>
        <?= $Page->mshitjeMarka->selectOptionListHtml("x_mshitjeMarka") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mshitjeMarka->getErrorMessage(false) ?></div>
<?= $Page->mshitjeMarka->Lookup->getParamTag($Page, "p_x_mshitjeMarka") ?>
<script>
loadjs.ready("fmakina_shitjesrch", function() {
    var options = { name: "x_mshitjeMarka", selectId: "fmakina_shitjesrch_x_mshitjeMarka" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjesrch.lists.mshitjeMarka.lookupOptions.length) {
        options.data = { id: "x_mshitjeMarka", form: "fmakina_shitjesrch" };
    } else {
        options.ajax = { id: "x_mshitjeMarka", form: "fmakina_shitjesrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeMarka.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeMarka" class="ew-search-field2 d-none">
    <select
        id="y_mshitjeMarka"
        name="y_mshitjeMarka"
        class="form-select ew-select<?= $Page->mshitjeMarka->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjesrch_y_mshitjeMarka"
        data-table="makina_shitje"
        data-field="x_mshitjeMarka"
        data-value-separator="<?= $Page->mshitjeMarka->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeMarka->getPlaceHolder()) ?>"
        <?= $Page->mshitjeMarka->editAttributes() ?>>
        <?= $Page->mshitjeMarka->selectOptionListHtml("y_mshitjeMarka") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mshitjeMarka->getErrorMessage(false) ?></div>
<?= $Page->mshitjeMarka->Lookup->getParamTag($Page, "p_y_mshitjeMarka") ?>
<script>
loadjs.ready("fmakina_shitjesrch", function() {
    var options = { name: "y_mshitjeMarka", selectId: "fmakina_shitjesrch_y_mshitjeMarka" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjesrch.lists.mshitjeMarka.lookupOptions.length) {
        options.data = { id: "y_mshitjeMarka", form: "fmakina_shitjesrch" };
    } else {
        options.ajax = { id: "y_mshitjeMarka", form: "fmakina_shitjesrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeMarka.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeModeli->Visible) { // mshitjeModeli ?>
<?php
if (!$Page->mshitjeModeli->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeModeli" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeModeli->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_mshitjeModeli" class="ew-search-caption ew-label"><?= $Page->mshitjeModeli->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeModeli" id="z_mshitjeModeli" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeModeli->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeModeli->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeModeli->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeModeli->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeModeli->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeModeli->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeModeli->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeModeli" class="ew-search-field">
<?php $Page->mshitjeModeli->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mshitjeModeli"
        name="x_mshitjeModeli"
        class="form-select ew-select<?= $Page->mshitjeModeli->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjesrch_x_mshitjeModeli"
        data-table="makina_shitje"
        data-field="x_mshitjeModeli"
        data-value-separator="<?= $Page->mshitjeModeli->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeModeli->getPlaceHolder()) ?>"
        <?= $Page->mshitjeModeli->editAttributes() ?>>
        <?= $Page->mshitjeModeli->selectOptionListHtml("x_mshitjeModeli") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mshitjeModeli->getErrorMessage(false) ?></div>
<?= $Page->mshitjeModeli->Lookup->getParamTag($Page, "p_x_mshitjeModeli") ?>
<script>
loadjs.ready("fmakina_shitjesrch", function() {
    var options = { name: "x_mshitjeModeli", selectId: "fmakina_shitjesrch_x_mshitjeModeli" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjesrch.lists.mshitjeModeli.lookupOptions.length) {
        options.data = { id: "x_mshitjeModeli", form: "fmakina_shitjesrch" };
    } else {
        options.ajax = { id: "x_mshitjeModeli", form: "fmakina_shitjesrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeModeli.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeModeli" class="ew-search-field2 d-none">
    <select
        id="y_mshitjeModeli"
        name="y_mshitjeModeli"
        class="form-select ew-select<?= $Page->mshitjeModeli->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjesrch_y_mshitjeModeli"
        data-table="makina_shitje"
        data-field="x_mshitjeModeli"
        data-value-separator="<?= $Page->mshitjeModeli->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeModeli->getPlaceHolder()) ?>"
        <?= $Page->mshitjeModeli->editAttributes() ?>>
        <?= $Page->mshitjeModeli->selectOptionListHtml("y_mshitjeModeli") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mshitjeModeli->getErrorMessage(false) ?></div>
<?= $Page->mshitjeModeli->Lookup->getParamTag($Page, "p_y_mshitjeModeli") ?>
<script>
loadjs.ready("fmakina_shitjesrch", function() {
    var options = { name: "y_mshitjeModeli", selectId: "fmakina_shitjesrch_y_mshitjeModeli" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjesrch.lists.mshitjeModeli.lookupOptions.length) {
        options.data = { id: "y_mshitjeModeli", form: "fmakina_shitjesrch" };
    } else {
        options.ajax = { id: "y_mshitjeModeli", form: "fmakina_shitjesrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeModeli.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeTipi->Visible) { // mshitjeTipi ?>
<?php
if (!$Page->mshitjeTipi->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeTipi" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeTipi->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_mshitjeTipi" class="ew-search-caption ew-label"><?= $Page->mshitjeTipi->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeTipi" id="z_mshitjeTipi" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeTipi->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeTipi->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeTipi->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeTipi->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeTipi->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeTipi->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeTipi->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeTipi" class="ew-search-field">
    <select
        id="x_mshitjeTipi"
        name="x_mshitjeTipi"
        class="form-select ew-select<?= $Page->mshitjeTipi->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjesrch_x_mshitjeTipi"
        data-table="makina_shitje"
        data-field="x_mshitjeTipi"
        data-value-separator="<?= $Page->mshitjeTipi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeTipi->getPlaceHolder()) ?>"
        <?= $Page->mshitjeTipi->editAttributes() ?>>
        <?= $Page->mshitjeTipi->selectOptionListHtml("x_mshitjeTipi") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mshitjeTipi->getErrorMessage(false) ?></div>
<?= $Page->mshitjeTipi->Lookup->getParamTag($Page, "p_x_mshitjeTipi") ?>
<script>
loadjs.ready("fmakina_shitjesrch", function() {
    var options = { name: "x_mshitjeTipi", selectId: "fmakina_shitjesrch_x_mshitjeTipi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjesrch.lists.mshitjeTipi.lookupOptions.length) {
        options.data = { id: "x_mshitjeTipi", form: "fmakina_shitjesrch" };
    } else {
        options.ajax = { id: "x_mshitjeTipi", form: "fmakina_shitjesrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeTipi.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeTipi" class="ew-search-field2 d-none">
    <select
        id="y_mshitjeTipi"
        name="y_mshitjeTipi"
        class="form-select ew-select<?= $Page->mshitjeTipi->isInvalidClass() ?>"
        data-select2-id="fmakina_shitjesrch_y_mshitjeTipi"
        data-table="makina_shitje"
        data-field="x_mshitjeTipi"
        data-value-separator="<?= $Page->mshitjeTipi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mshitjeTipi->getPlaceHolder()) ?>"
        <?= $Page->mshitjeTipi->editAttributes() ?>>
        <?= $Page->mshitjeTipi->selectOptionListHtml("y_mshitjeTipi") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mshitjeTipi->getErrorMessage(false) ?></div>
<?= $Page->mshitjeTipi->Lookup->getParamTag($Page, "p_y_mshitjeTipi") ?>
<script>
loadjs.ready("fmakina_shitjesrch", function() {
    var options = { name: "y_mshitjeTipi", selectId: "fmakina_shitjesrch_y_mshitjeTipi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_shitjesrch.lists.mshitjeTipi.lookupOptions.length) {
        options.data = { id: "y_mshitjeTipi", form: "fmakina_shitjesrch" };
    } else {
        options.ajax = { id: "y_mshitjeTipi", form: "fmakina_shitjesrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_shitje.fields.mshitjeTipi.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeVitiProdhimit->Visible) { // mshitjeVitiProdhimit ?>
<?php
if (!$Page->mshitjeVitiProdhimit->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeVitiProdhimit" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeVitiProdhimit->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_mshitjeVitiProdhimit" class="ew-search-caption ew-label"><?= $Page->mshitjeVitiProdhimit->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeVitiProdhimit" id="z_mshitjeVitiProdhimit" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeVitiProdhimit->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeVitiProdhimit" class="ew-search-field">
<input type="<?= $Page->mshitjeVitiProdhimit->getInputTextType() ?>" name="x_mshitjeVitiProdhimit" id="x_mshitjeVitiProdhimit" data-table="makina_shitje" data-field="x_mshitjeVitiProdhimit" value="<?= $Page->mshitjeVitiProdhimit->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mshitjeVitiProdhimit->getPlaceHolder()) ?>"<?= $Page->mshitjeVitiProdhimit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->mshitjeVitiProdhimit->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeVitiProdhimit" class="ew-search-field2 d-none">
<input type="<?= $Page->mshitjeVitiProdhimit->getInputTextType() ?>" name="y_mshitjeVitiProdhimit" id="y_mshitjeVitiProdhimit" data-table="makina_shitje" data-field="x_mshitjeVitiProdhimit" value="<?= $Page->mshitjeVitiProdhimit->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->mshitjeVitiProdhimit->getPlaceHolder()) ?>"<?= $Page->mshitjeVitiProdhimit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->mshitjeVitiProdhimit->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeKarburant->Visible) { // mshitjeKarburant ?>
<?php
if (!$Page->mshitjeKarburant->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeKarburant" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeKarburant->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mshitjeKarburant->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeKarburant" id="z_mshitjeKarburant" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeKarburant->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeKarburant" class="ew-search-field">
<template id="tp_x_mshitjeKarburant">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeKarburant" name="x_mshitjeKarburant" id="x_mshitjeKarburant"<?= $Page->mshitjeKarburant->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeKarburant" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeKarburant"
    name="x_mshitjeKarburant"
    value="<?= HtmlEncode($Page->mshitjeKarburant->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeKarburant"
    data-bs-target="dsl_x_mshitjeKarburant"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeKarburant->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeKarburant"
    data-value-separator="<?= $Page->mshitjeKarburant->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeKarburant->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeKarburant->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeKarburant" class="ew-search-field2 d-none">
<template id="tp_y_mshitjeKarburant">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeKarburant" name="y_mshitjeKarburant" id="y_mshitjeKarburant"<?= $Page->mshitjeKarburant->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mshitjeKarburant" class="ew-item-list"></div>
<selection-list hidden
    id="y_mshitjeKarburant"
    name="y_mshitjeKarburant"
    value="<?= HtmlEncode($Page->mshitjeKarburant->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mshitjeKarburant"
    data-bs-target="dsl_y_mshitjeKarburant"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeKarburant->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeKarburant"
    data-value-separator="<?= $Page->mshitjeKarburant->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeKarburant->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeKarburant->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeKambio->Visible) { // mshitjeKambio ?>
<?php
if (!$Page->mshitjeKambio->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeKambio" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeKambio->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mshitjeKambio->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeKambio" id="z_mshitjeKambio" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeKambio->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeKambio" class="ew-search-field">
<template id="tp_x_mshitjeKambio">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeKambio" name="x_mshitjeKambio" id="x_mshitjeKambio"<?= $Page->mshitjeKambio->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeKambio" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeKambio"
    name="x_mshitjeKambio"
    value="<?= HtmlEncode($Page->mshitjeKambio->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeKambio"
    data-bs-target="dsl_x_mshitjeKambio"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeKambio->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeKambio"
    data-value-separator="<?= $Page->mshitjeKambio->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeKambio->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeKambio->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeKambio" class="ew-search-field2 d-none">
<template id="tp_y_mshitjeKambio">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeKambio" name="y_mshitjeKambio" id="y_mshitjeKambio"<?= $Page->mshitjeKambio->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mshitjeKambio" class="ew-item-list"></div>
<selection-list hidden
    id="y_mshitjeKambio"
    name="y_mshitjeKambio"
    value="<?= HtmlEncode($Page->mshitjeKambio->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mshitjeKambio"
    data-bs-target="dsl_y_mshitjeKambio"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeKambio->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeKambio"
    data-value-separator="<?= $Page->mshitjeKambio->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeKambio->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeKambio->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeTargaAL->Visible) { // mshitjeTargaAL ?>
<?php
if (!$Page->mshitjeTargaAL->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeTargaAL" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeTargaAL->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mshitjeTargaAL->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeTargaAL" id="z_mshitjeTargaAL" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeTargaAL->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeTargaAL" class="ew-search-field">
<template id="tp_x_mshitjeTargaAL">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeTargaAL" name="x_mshitjeTargaAL" id="x_mshitjeTargaAL"<?= $Page->mshitjeTargaAL->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeTargaAL" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeTargaAL"
    name="x_mshitjeTargaAL"
    value="<?= HtmlEncode($Page->mshitjeTargaAL->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeTargaAL"
    data-bs-target="dsl_x_mshitjeTargaAL"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeTargaAL->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeTargaAL"
    data-value-separator="<?= $Page->mshitjeTargaAL->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeTargaAL->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeTargaAL->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeTargaAL" class="ew-search-field2 d-none">
<template id="tp_y_mshitjeTargaAL">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeTargaAL" name="y_mshitjeTargaAL" id="y_mshitjeTargaAL"<?= $Page->mshitjeTargaAL->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mshitjeTargaAL" class="ew-item-list"></div>
<selection-list hidden
    id="y_mshitjeTargaAL"
    name="y_mshitjeTargaAL"
    value="<?= HtmlEncode($Page->mshitjeTargaAL->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mshitjeTargaAL"
    data-bs-target="dsl_y_mshitjeTargaAL"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeTargaAL->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeTargaAL"
    data-value-separator="<?= $Page->mshitjeTargaAL->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeTargaAL->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeTargaAL->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeIndex->Visible) { // mshitjeIndex ?>
<?php
if (!$Page->mshitjeIndex->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeIndex" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeIndex->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mshitjeIndex->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeIndex" id="z_mshitjeIndex" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeIndex->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeIndex" class="ew-search-field">
<template id="tp_x_mshitjeIndex">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeIndex" name="x_mshitjeIndex" id="x_mshitjeIndex"<?= $Page->mshitjeIndex->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeIndex" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeIndex"
    name="x_mshitjeIndex"
    value="<?= HtmlEncode($Page->mshitjeIndex->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeIndex"
    data-bs-target="dsl_x_mshitjeIndex"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeIndex->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeIndex"
    data-value-separator="<?= $Page->mshitjeIndex->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeIndex->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeIndex->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeIndex" class="ew-search-field2 d-none">
<template id="tp_y_mshitjeIndex">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeIndex" name="y_mshitjeIndex" id="y_mshitjeIndex"<?= $Page->mshitjeIndex->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mshitjeIndex" class="ew-item-list"></div>
<selection-list hidden
    id="y_mshitjeIndex"
    name="y_mshitjeIndex"
    value="<?= HtmlEncode($Page->mshitjeIndex->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mshitjeIndex"
    data-bs-target="dsl_y_mshitjeIndex"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeIndex->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeIndex"
    data-value-separator="<?= $Page->mshitjeIndex->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeIndex->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeIndex->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjePromo->Visible) { // mshitjePromo ?>
<?php
if (!$Page->mshitjePromo->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjePromo" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjePromo->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mshitjePromo->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjePromo" id="z_mshitjePromo" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mshitjePromo->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjePromo" class="ew-search-field">
<template id="tp_x_mshitjePromo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjePromo" name="x_mshitjePromo" id="x_mshitjePromo"<?= $Page->mshitjePromo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjePromo" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjePromo"
    name="x_mshitjePromo"
    value="<?= HtmlEncode($Page->mshitjePromo->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjePromo"
    data-bs-target="dsl_x_mshitjePromo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjePromo->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjePromo"
    data-value-separator="<?= $Page->mshitjePromo->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjePromo->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjePromo->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjePromo" class="ew-search-field2 d-none">
<template id="tp_y_mshitjePromo">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjePromo" name="y_mshitjePromo" id="y_mshitjePromo"<?= $Page->mshitjePromo->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mshitjePromo" class="ew-item-list"></div>
<selection-list hidden
    id="y_mshitjePromo"
    name="y_mshitjePromo"
    value="<?= HtmlEncode($Page->mshitjePromo->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mshitjePromo"
    data-bs-target="dsl_y_mshitjePromo"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjePromo->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjePromo"
    data-value-separator="<?= $Page->mshitjePromo->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjePromo->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjePromo->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeAktiv->Visible) { // mshitjeAktiv ?>
<?php
if (!$Page->mshitjeAktiv->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeAktiv" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeAktiv->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mshitjeAktiv->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeAktiv" id="z_mshitjeAktiv" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeAktiv->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeAktiv" class="ew-search-field">
<template id="tp_x_mshitjeAktiv">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeAktiv" name="x_mshitjeAktiv" id="x_mshitjeAktiv"<?= $Page->mshitjeAktiv->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeAktiv" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeAktiv"
    name="x_mshitjeAktiv"
    value="<?= HtmlEncode($Page->mshitjeAktiv->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeAktiv"
    data-bs-target="dsl_x_mshitjeAktiv"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeAktiv->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeAktiv"
    data-value-separator="<?= $Page->mshitjeAktiv->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeAktiv->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeAktiv->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeAktiv" class="ew-search-field2 d-none">
<template id="tp_y_mshitjeAktiv">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeAktiv" name="y_mshitjeAktiv" id="y_mshitjeAktiv"<?= $Page->mshitjeAktiv->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mshitjeAktiv" class="ew-item-list"></div>
<selection-list hidden
    id="y_mshitjeAktiv"
    name="y_mshitjeAktiv"
    value="<?= HtmlEncode($Page->mshitjeAktiv->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mshitjeAktiv"
    data-bs-target="dsl_y_mshitjeAktiv"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeAktiv->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeAktiv"
    data-value-separator="<?= $Page->mshitjeAktiv->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeAktiv->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeAktiv->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mshitjeShitur->Visible) { // mshitjeShitur ?>
<?php
if (!$Page->mshitjeShitur->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mshitjeShitur" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mshitjeShitur->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mshitjeShitur->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mshitjeShitur" id="z_mshitjeShitur" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mshitjeShitur->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_shitje_mshitjeShitur" class="ew-search-field">
<template id="tp_x_mshitjeShitur">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeShitur" name="x_mshitjeShitur" id="x_mshitjeShitur"<?= $Page->mshitjeShitur->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mshitjeShitur" class="ew-item-list"></div>
<selection-list hidden
    id="x_mshitjeShitur"
    name="x_mshitjeShitur"
    value="<?= HtmlEncode($Page->mshitjeShitur->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mshitjeShitur"
    data-bs-target="dsl_x_mshitjeShitur"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeShitur->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeShitur"
    data-value-separator="<?= $Page->mshitjeShitur->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeShitur->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeShitur->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_shitje_mshitjeShitur" class="ew-search-field2 d-none">
<template id="tp_y_mshitjeShitur">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_shitje" data-field="x_mshitjeShitur" name="y_mshitjeShitur" id="y_mshitjeShitur"<?= $Page->mshitjeShitur->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mshitjeShitur" class="ew-item-list"></div>
<selection-list hidden
    id="y_mshitjeShitur"
    name="y_mshitjeShitur"
    value="<?= HtmlEncode($Page->mshitjeShitur->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mshitjeShitur"
    data-bs-target="dsl_y_mshitjeShitur"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mshitjeShitur->isInvalidClass() ?>"
    data-table="makina_shitje"
    data-field="x_mshitjeShitur"
    data-value-separator="<?= $Page->mshitjeShitur->displayValueSeparatorAttribute() ?>"
    <?= $Page->mshitjeShitur->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mshitjeShitur->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
</div><!-- /.row -->
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fmakina_shitjesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fmakina_shitjesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fmakina_shitjesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fmakina_shitjesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
            </div>
        </div>
    </div>
    <div class="col-sm-auto mb-3">
        <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
    </div>
</div>
</div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> makina_shitje">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
</div>
<?php } ?>
<form name="fmakina_shitjelist" id="fmakina_shitjelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_shitje">
<div id="gmp_makina_shitje" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_makina_shitjelist" class="table table-sm ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->mshitjeID->Visible) { // mshitjeID ?>
        <th data-name="mshitjeID" class="<?= $Page->mshitjeID->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeID" class="makina_shitje_mshitjeID"><?= $Page->renderFieldHeader($Page->mshitjeID) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeMarka->Visible) { // mshitjeMarka ?>
        <th data-name="mshitjeMarka" class="<?= $Page->mshitjeMarka->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeMarka" class="makina_shitje_mshitjeMarka"><?= $Page->renderFieldHeader($Page->mshitjeMarka) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeModeli->Visible) { // mshitjeModeli ?>
        <th data-name="mshitjeModeli" class="<?= $Page->mshitjeModeli->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeModeli" class="makina_shitje_mshitjeModeli"><?= $Page->renderFieldHeader($Page->mshitjeModeli) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeTipi->Visible) { // mshitjeTipi ?>
        <th data-name="mshitjeTipi" class="<?= $Page->mshitjeTipi->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeTipi" class="makina_shitje_mshitjeTipi"><?= $Page->renderFieldHeader($Page->mshitjeTipi) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeKapacitetiMotorrit->Visible) { // mshitjeKapacitetiMotorrit ?>
        <th data-name="mshitjeKapacitetiMotorrit" class="<?= $Page->mshitjeKapacitetiMotorrit->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeKapacitetiMotorrit" class="makina_shitje_mshitjeKapacitetiMotorrit"><?= $Page->renderFieldHeader($Page->mshitjeKapacitetiMotorrit) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeVitiProdhimit->Visible) { // mshitjeVitiProdhimit ?>
        <th data-name="mshitjeVitiProdhimit" class="<?= $Page->mshitjeVitiProdhimit->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeVitiProdhimit" class="makina_shitje_mshitjeVitiProdhimit"><?= $Page->renderFieldHeader($Page->mshitjeVitiProdhimit) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeKarburant->Visible) { // mshitjeKarburant ?>
        <th data-name="mshitjeKarburant" class="<?= $Page->mshitjeKarburant->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeKarburant" class="makina_shitje_mshitjeKarburant"><?= $Page->renderFieldHeader($Page->mshitjeKarburant) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeNrVendeve->Visible) { // mshitjeNrVendeve ?>
        <th data-name="mshitjeNrVendeve" class="<?= $Page->mshitjeNrVendeve->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeNrVendeve" class="makina_shitje_mshitjeNrVendeve"><?= $Page->renderFieldHeader($Page->mshitjeNrVendeve) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeKambio->Visible) { // mshitjeKambio ?>
        <th data-name="mshitjeKambio" class="<?= $Page->mshitjeKambio->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeKambio" class="makina_shitje_mshitjeKambio"><?= $Page->renderFieldHeader($Page->mshitjeKambio) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeTargaAL->Visible) { // mshitjeTargaAL ?>
        <th data-name="mshitjeTargaAL" class="<?= $Page->mshitjeTargaAL->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeTargaAL" class="makina_shitje_mshitjeTargaAL"><?= $Page->renderFieldHeader($Page->mshitjeTargaAL) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeKilometra->Visible) { // mshitjeKilometra ?>
        <th data-name="mshitjeKilometra" class="<?= $Page->mshitjeKilometra->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeKilometra" class="makina_shitje_mshitjeKilometra"><?= $Page->renderFieldHeader($Page->mshitjeKilometra) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeCmimi->Visible) { // mshitjeCmimi ?>
        <th data-name="mshitjeCmimi" class="<?= $Page->mshitjeCmimi->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeCmimi" class="makina_shitje_mshitjeCmimi"><?= $Page->renderFieldHeader($Page->mshitjeCmimi) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeIndex->Visible) { // mshitjeIndex ?>
        <th data-name="mshitjeIndex" class="<?= $Page->mshitjeIndex->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeIndex" class="makina_shitje_mshitjeIndex"><?= $Page->renderFieldHeader($Page->mshitjeIndex) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjePromo->Visible) { // mshitjePromo ?>
        <th data-name="mshitjePromo" class="<?= $Page->mshitjePromo->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjePromo" class="makina_shitje_mshitjePromo"><?= $Page->renderFieldHeader($Page->mshitjePromo) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeAktiv->Visible) { // mshitjeAktiv ?>
        <th data-name="mshitjeAktiv" class="<?= $Page->mshitjeAktiv->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeAktiv" class="makina_shitje_mshitjeAktiv"><?= $Page->renderFieldHeader($Page->mshitjeAktiv) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeShitur->Visible) { // mshitjeShitur ?>
        <th data-name="mshitjeShitur" class="<?= $Page->mshitjeShitur->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeShitur" class="makina_shitje_mshitjeShitur"><?= $Page->renderFieldHeader($Page->mshitjeShitur) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeAutori->Visible) { // mshitjeAutori ?>
        <th data-name="mshitjeAutori" class="<?= $Page->mshitjeAutori->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeAutori" class="makina_shitje_mshitjeAutori"><?= $Page->renderFieldHeader($Page->mshitjeAutori) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeKrijuar->Visible) { // mshitjeKrijuar ?>
        <th data-name="mshitjeKrijuar" class="<?= $Page->mshitjeKrijuar->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeKrijuar" class="makina_shitje_mshitjeKrijuar"><?= $Page->renderFieldHeader($Page->mshitjeKrijuar) ?></div></th>
<?php } ?>
<?php if ($Page->mshitjeAzhornuar->Visible) { // mshitjeAzhornuar ?>
        <th data-name="mshitjeAzhornuar" class="<?= $Page->mshitjeAzhornuar->headerCellClass() ?>"><div id="elh_makina_shitje_mshitjeAzhornuar" class="makina_shitje_mshitjeAzhornuar"><?= $Page->renderFieldHeader($Page->mshitjeAzhornuar) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif ($Page->isGridAdd() && !$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row attributes
        $Page->RowAttrs->merge([
            "data-rowindex" => $Page->RowCount,
            "id" => "r" . $Page->RowCount . "_makina_shitje",
            "data-rowtype" => $Page->RowType,
            "class" => ($Page->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($Page->isAdd() && $Page->RowType == ROWTYPE_ADD || $Page->isEdit() && $Page->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $Page->RowAttrs->appendClass("table-active");
        }

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->mshitjeID->Visible) { // mshitjeID ?>
        <td data-name="mshitjeID"<?= $Page->mshitjeID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeID" class="el_makina_shitje_mshitjeID">
<span<?= $Page->mshitjeID->viewAttributes() ?>>
<?= $Page->mshitjeID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeMarka->Visible) { // mshitjeMarka ?>
        <td data-name="mshitjeMarka"<?= $Page->mshitjeMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeMarka" class="el_makina_shitje_mshitjeMarka">
<span<?= $Page->mshitjeMarka->viewAttributes() ?>>
<?= $Page->mshitjeMarka->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeModeli->Visible) { // mshitjeModeli ?>
        <td data-name="mshitjeModeli"<?= $Page->mshitjeModeli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeModeli" class="el_makina_shitje_mshitjeModeli">
<span<?= $Page->mshitjeModeli->viewAttributes() ?>>
<?= $Page->mshitjeModeli->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeTipi->Visible) { // mshitjeTipi ?>
        <td data-name="mshitjeTipi"<?= $Page->mshitjeTipi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeTipi" class="el_makina_shitje_mshitjeTipi">
<span<?= $Page->mshitjeTipi->viewAttributes() ?>>
<?= $Page->mshitjeTipi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeKapacitetiMotorrit->Visible) { // mshitjeKapacitetiMotorrit ?>
        <td data-name="mshitjeKapacitetiMotorrit"<?= $Page->mshitjeKapacitetiMotorrit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKapacitetiMotorrit" class="el_makina_shitje_mshitjeKapacitetiMotorrit">
<span<?= $Page->mshitjeKapacitetiMotorrit->viewAttributes() ?>>
<?= $Page->mshitjeKapacitetiMotorrit->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeVitiProdhimit->Visible) { // mshitjeVitiProdhimit ?>
        <td data-name="mshitjeVitiProdhimit"<?= $Page->mshitjeVitiProdhimit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeVitiProdhimit" class="el_makina_shitje_mshitjeVitiProdhimit">
<span<?= $Page->mshitjeVitiProdhimit->viewAttributes() ?>>
<?= $Page->mshitjeVitiProdhimit->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeKarburant->Visible) { // mshitjeKarburant ?>
        <td data-name="mshitjeKarburant"<?= $Page->mshitjeKarburant->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKarburant" class="el_makina_shitje_mshitjeKarburant">
<span<?= $Page->mshitjeKarburant->viewAttributes() ?>>
<?= $Page->mshitjeKarburant->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeNrVendeve->Visible) { // mshitjeNrVendeve ?>
        <td data-name="mshitjeNrVendeve"<?= $Page->mshitjeNrVendeve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeNrVendeve" class="el_makina_shitje_mshitjeNrVendeve">
<span<?= $Page->mshitjeNrVendeve->viewAttributes() ?>>
<?= $Page->mshitjeNrVendeve->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeKambio->Visible) { // mshitjeKambio ?>
        <td data-name="mshitjeKambio"<?= $Page->mshitjeKambio->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKambio" class="el_makina_shitje_mshitjeKambio">
<span<?= $Page->mshitjeKambio->viewAttributes() ?>>
<?= $Page->mshitjeKambio->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeTargaAL->Visible) { // mshitjeTargaAL ?>
        <td data-name="mshitjeTargaAL"<?= $Page->mshitjeTargaAL->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeTargaAL" class="el_makina_shitje_mshitjeTargaAL">
<span<?= $Page->mshitjeTargaAL->viewAttributes() ?>>
<?= $Page->mshitjeTargaAL->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeKilometra->Visible) { // mshitjeKilometra ?>
        <td data-name="mshitjeKilometra"<?= $Page->mshitjeKilometra->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKilometra" class="el_makina_shitje_mshitjeKilometra">
<span<?= $Page->mshitjeKilometra->viewAttributes() ?>>
<?= $Page->mshitjeKilometra->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeCmimi->Visible) { // mshitjeCmimi ?>
        <td data-name="mshitjeCmimi"<?= $Page->mshitjeCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeCmimi" class="el_makina_shitje_mshitjeCmimi">
<span<?= $Page->mshitjeCmimi->viewAttributes() ?>>
<?= $Page->mshitjeCmimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeIndex->Visible) { // mshitjeIndex ?>
        <td data-name="mshitjeIndex"<?= $Page->mshitjeIndex->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeIndex" class="el_makina_shitje_mshitjeIndex">
<span<?= $Page->mshitjeIndex->viewAttributes() ?>>
<?= $Page->mshitjeIndex->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjePromo->Visible) { // mshitjePromo ?>
        <td data-name="mshitjePromo"<?= $Page->mshitjePromo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjePromo" class="el_makina_shitje_mshitjePromo">
<span<?= $Page->mshitjePromo->viewAttributes() ?>>
<?= $Page->mshitjePromo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeAktiv->Visible) { // mshitjeAktiv ?>
        <td data-name="mshitjeAktiv"<?= $Page->mshitjeAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeAktiv" class="el_makina_shitje_mshitjeAktiv">
<span<?= $Page->mshitjeAktiv->viewAttributes() ?>>
<?= $Page->mshitjeAktiv->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeShitur->Visible) { // mshitjeShitur ?>
        <td data-name="mshitjeShitur"<?= $Page->mshitjeShitur->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeShitur" class="el_makina_shitje_mshitjeShitur">
<span<?= $Page->mshitjeShitur->viewAttributes() ?>>
<?= $Page->mshitjeShitur->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeAutori->Visible) { // mshitjeAutori ?>
        <td data-name="mshitjeAutori"<?= $Page->mshitjeAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeAutori" class="el_makina_shitje_mshitjeAutori">
<span<?= $Page->mshitjeAutori->viewAttributes() ?>>
<?= $Page->mshitjeAutori->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeKrijuar->Visible) { // mshitjeKrijuar ?>
        <td data-name="mshitjeKrijuar"<?= $Page->mshitjeKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKrijuar" class="el_makina_shitje_mshitjeKrijuar">
<span<?= $Page->mshitjeKrijuar->viewAttributes() ?>>
<?= $Page->mshitjeKrijuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mshitjeAzhornuar->Visible) { // mshitjeAzhornuar ?>
        <td data-name="mshitjeAzhornuar"<?= $Page->mshitjeAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeAzhornuar" class="el_makina_shitje_mshitjeAzhornuar">
<span<?= $Page->mshitjeAzhornuar->viewAttributes() ?>>
<?= $Page->mshitjeAzhornuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("makina_shitje");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
