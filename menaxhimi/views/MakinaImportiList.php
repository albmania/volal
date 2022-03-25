<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaImportiList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_importi: currentTable } });
var currentForm, currentPageID;
var fmakina_importilist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importilist = new ew.Form("fmakina_importilist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fmakina_importilist;
    fmakina_importilist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fmakina_importilist");
});
var fmakina_importisrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fmakina_importisrch = new ew.Form("fmakina_importisrch", "list");
    currentSearchForm = fmakina_importisrch;

    // Add fields
    var fields = currentTable.fields;
    fmakina_importisrch.addFields([
        ["mimpID", [], fields.mimpID.isInvalid],
        ["mimpMarka", [], fields.mimpMarka.isInvalid],
        ["y_mimpMarka", [ew.Validators.between], false],
        ["mimpModeli", [], fields.mimpModeli.isInvalid],
        ["y_mimpModeli", [ew.Validators.between], false],
        ["mimpTipi", [], fields.mimpTipi.isInvalid],
        ["y_mimpTipi", [ew.Validators.between], false],
        ["mimpShasia", [], fields.mimpShasia.isInvalid],
        ["mimpViti", [ew.Validators.integer], fields.mimpViti.isInvalid],
        ["mimpKarburant", [], fields.mimpKarburant.isInvalid],
        ["y_mimpKarburant", [ew.Validators.between], false],
        ["mimpPrejardhja", [], fields.mimpPrejardhja.isInvalid],
        ["mimpCmimiBlerjes", [], fields.mimpCmimiBlerjes.isInvalid],
        ["mimpDogana", [], fields.mimpDogana.isInvalid],
        ["mimpTransporti", [], fields.mimpTransporti.isInvalid],
        ["mimpTjera", [], fields.mimpTjera.isInvalid],
        ["mimpDtHyrjes", [], fields.mimpDtHyrjes.isInvalid],
        ["mimpCmimiShitjes", [], fields.mimpCmimiShitjes.isInvalid],
        ["mimpGati", [], fields.mimpGati.isInvalid],
        ["y_mimpGati", [ew.Validators.between], false]
    ]);

    // Validate form
    fmakina_importisrch.validate = function () {
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
    fmakina_importisrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_importisrch.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakina_importisrch.lists.mimpMarka = <?= $Page->mimpMarka->toClientList($Page) ?>;
    fmakina_importisrch.lists.mimpModeli = <?= $Page->mimpModeli->toClientList($Page) ?>;
    fmakina_importisrch.lists.mimpTipi = <?= $Page->mimpTipi->toClientList($Page) ?>;
    fmakina_importisrch.lists.mimpKarburant = <?= $Page->mimpKarburant->toClientList($Page) ?>;
    fmakina_importisrch.lists.mimpGati = <?= $Page->mimpGati->toClientList($Page) ?>;

    // Filters
    fmakina_importisrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fmakina_importisrch.initSearchPanel = true;
    loadjs.done("fmakina_importisrch");
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
<form name="fmakina_importisrch" id="fmakina_importisrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fmakina_importisrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="makina_importi">
<div class="ew-extended-search container-fluid">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->mimpMarka->Visible) { // mimpMarka ?>
<?php
if (!$Page->mimpMarka->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mimpMarka" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mimpMarka->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_mimpMarka" class="ew-search-caption ew-label"><?= $Page->mimpMarka->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mimpMarka" id="z_mimpMarka" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mimpMarka->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mimpMarka->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mimpMarka->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mimpMarka->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mimpMarka->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mimpMarka->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->mimpMarka->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_importi_mimpMarka" class="ew-search-field">
<?php $Page->mimpMarka->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mimpMarka"
        name="x_mimpMarka"
        class="form-select ew-select<?= $Page->mimpMarka->isInvalidClass() ?>"
        data-select2-id="fmakina_importisrch_x_mimpMarka"
        data-table="makina_importi"
        data-field="x_mimpMarka"
        data-value-separator="<?= $Page->mimpMarka->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpMarka->getPlaceHolder()) ?>"
        <?= $Page->mimpMarka->editAttributes() ?>>
        <?= $Page->mimpMarka->selectOptionListHtml("x_mimpMarka") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mimpMarka->getErrorMessage(false) ?></div>
<?= $Page->mimpMarka->Lookup->getParamTag($Page, "p_x_mimpMarka") ?>
<script>
loadjs.ready("fmakina_importisrch", function() {
    var options = { name: "x_mimpMarka", selectId: "fmakina_importisrch_x_mimpMarka" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importisrch.lists.mimpMarka.lookupOptions.length) {
        options.data = { id: "x_mimpMarka", form: "fmakina_importisrch" };
    } else {
        options.ajax = { id: "x_mimpMarka", form: "fmakina_importisrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpMarka.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_importi_mimpMarka" class="ew-search-field2 d-none">
    <select
        id="y_mimpMarka"
        name="y_mimpMarka"
        class="form-select ew-select<?= $Page->mimpMarka->isInvalidClass() ?>"
        data-select2-id="fmakina_importisrch_y_mimpMarka"
        data-table="makina_importi"
        data-field="x_mimpMarka"
        data-value-separator="<?= $Page->mimpMarka->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpMarka->getPlaceHolder()) ?>"
        <?= $Page->mimpMarka->editAttributes() ?>>
        <?= $Page->mimpMarka->selectOptionListHtml("y_mimpMarka") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mimpMarka->getErrorMessage(false) ?></div>
<?= $Page->mimpMarka->Lookup->getParamTag($Page, "p_y_mimpMarka") ?>
<script>
loadjs.ready("fmakina_importisrch", function() {
    var options = { name: "y_mimpMarka", selectId: "fmakina_importisrch_y_mimpMarka" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importisrch.lists.mimpMarka.lookupOptions.length) {
        options.data = { id: "y_mimpMarka", form: "fmakina_importisrch" };
    } else {
        options.ajax = { id: "y_mimpMarka", form: "fmakina_importisrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpMarka.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mimpModeli->Visible) { // mimpModeli ?>
<?php
if (!$Page->mimpModeli->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mimpModeli" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mimpModeli->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_mimpModeli" class="ew-search-caption ew-label"><?= $Page->mimpModeli->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mimpModeli" id="z_mimpModeli" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mimpModeli->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mimpModeli->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mimpModeli->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mimpModeli->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mimpModeli->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mimpModeli->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->mimpModeli->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_importi_mimpModeli" class="ew-search-field">
<?php $Page->mimpModeli->EditAttrs->prepend("onchange", "ew.updateOptions.call(this);"); ?>
    <select
        id="x_mimpModeli"
        name="x_mimpModeli"
        class="form-select ew-select<?= $Page->mimpModeli->isInvalidClass() ?>"
        data-select2-id="fmakina_importisrch_x_mimpModeli"
        data-table="makina_importi"
        data-field="x_mimpModeli"
        data-value-separator="<?= $Page->mimpModeli->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpModeli->getPlaceHolder()) ?>"
        <?= $Page->mimpModeli->editAttributes() ?>>
        <?= $Page->mimpModeli->selectOptionListHtml("x_mimpModeli") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mimpModeli->getErrorMessage(false) ?></div>
<?= $Page->mimpModeli->Lookup->getParamTag($Page, "p_x_mimpModeli") ?>
<script>
loadjs.ready("fmakina_importisrch", function() {
    var options = { name: "x_mimpModeli", selectId: "fmakina_importisrch_x_mimpModeli" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importisrch.lists.mimpModeli.lookupOptions.length) {
        options.data = { id: "x_mimpModeli", form: "fmakina_importisrch" };
    } else {
        options.ajax = { id: "x_mimpModeli", form: "fmakina_importisrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpModeli.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_importi_mimpModeli" class="ew-search-field2 d-none">
    <select
        id="y_mimpModeli"
        name="y_mimpModeli"
        class="form-select ew-select<?= $Page->mimpModeli->isInvalidClass() ?>"
        data-select2-id="fmakina_importisrch_y_mimpModeli"
        data-table="makina_importi"
        data-field="x_mimpModeli"
        data-value-separator="<?= $Page->mimpModeli->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpModeli->getPlaceHolder()) ?>"
        <?= $Page->mimpModeli->editAttributes() ?>>
        <?= $Page->mimpModeli->selectOptionListHtml("y_mimpModeli") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mimpModeli->getErrorMessage(false) ?></div>
<?= $Page->mimpModeli->Lookup->getParamTag($Page, "p_y_mimpModeli") ?>
<script>
loadjs.ready("fmakina_importisrch", function() {
    var options = { name: "y_mimpModeli", selectId: "fmakina_importisrch_y_mimpModeli" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importisrch.lists.mimpModeli.lookupOptions.length) {
        options.data = { id: "y_mimpModeli", form: "fmakina_importisrch" };
    } else {
        options.ajax = { id: "y_mimpModeli", form: "fmakina_importisrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpModeli.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mimpTipi->Visible) { // mimpTipi ?>
<?php
if (!$Page->mimpTipi->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mimpTipi" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mimpTipi->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_mimpTipi" class="ew-search-caption ew-label"><?= $Page->mimpTipi->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mimpTipi" id="z_mimpTipi" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mimpTipi->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mimpTipi->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mimpTipi->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mimpTipi->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mimpTipi->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mimpTipi->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->mimpTipi->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_importi_mimpTipi" class="ew-search-field">
    <select
        id="x_mimpTipi"
        name="x_mimpTipi"
        class="form-select ew-select<?= $Page->mimpTipi->isInvalidClass() ?>"
        data-select2-id="fmakina_importisrch_x_mimpTipi"
        data-table="makina_importi"
        data-field="x_mimpTipi"
        data-value-separator="<?= $Page->mimpTipi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpTipi->getPlaceHolder()) ?>"
        <?= $Page->mimpTipi->editAttributes() ?>>
        <?= $Page->mimpTipi->selectOptionListHtml("x_mimpTipi") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mimpTipi->getErrorMessage(false) ?></div>
<?= $Page->mimpTipi->Lookup->getParamTag($Page, "p_x_mimpTipi") ?>
<script>
loadjs.ready("fmakina_importisrch", function() {
    var options = { name: "x_mimpTipi", selectId: "fmakina_importisrch_x_mimpTipi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importisrch.lists.mimpTipi.lookupOptions.length) {
        options.data = { id: "x_mimpTipi", form: "fmakina_importisrch" };
    } else {
        options.ajax = { id: "x_mimpTipi", form: "fmakina_importisrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpTipi.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_importi_mimpTipi" class="ew-search-field2 d-none">
    <select
        id="y_mimpTipi"
        name="y_mimpTipi"
        class="form-select ew-select<?= $Page->mimpTipi->isInvalidClass() ?>"
        data-select2-id="fmakina_importisrch_y_mimpTipi"
        data-table="makina_importi"
        data-field="x_mimpTipi"
        data-value-separator="<?= $Page->mimpTipi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Page->mimpTipi->getPlaceHolder()) ?>"
        <?= $Page->mimpTipi->editAttributes() ?>>
        <?= $Page->mimpTipi->selectOptionListHtml("y_mimpTipi") ?>
    </select>
    <div class="invalid-feedback"><?= $Page->mimpTipi->getErrorMessage(false) ?></div>
<?= $Page->mimpTipi->Lookup->getParamTag($Page, "p_y_mimpTipi") ?>
<script>
loadjs.ready("fmakina_importisrch", function() {
    var options = { name: "y_mimpTipi", selectId: "fmakina_importisrch_y_mimpTipi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fmakina_importisrch.lists.mimpTipi.lookupOptions.length) {
        options.data = { id: "y_mimpTipi", form: "fmakina_importisrch" };
    } else {
        options.ajax = { id: "y_mimpTipi", form: "fmakina_importisrch", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumResultsForSearch = Infinity;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.makina_importi.fields.mimpTipi.selectOptions);
    ew.createSelect(options);
});
</script>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mimpViti->Visible) { // mimpViti ?>
<?php
if (!$Page->mimpViti->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mimpViti" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mimpViti->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_mimpViti" class="ew-search-caption ew-label"><?= $Page->mimpViti->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_mimpViti" id="z_mimpViti" value="=">
</div>
        </div>
        <div id="el_makina_importi_mimpViti" class="ew-search-field">
<input type="<?= $Page->mimpViti->getInputTextType() ?>" name="x_mimpViti" id="x_mimpViti" data-table="makina_importi" data-field="x_mimpViti" value="<?= $Page->mimpViti->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->mimpViti->getPlaceHolder()) ?>"<?= $Page->mimpViti->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->mimpViti->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mimpKarburant->Visible) { // mimpKarburant ?>
<?php
if (!$Page->mimpKarburant->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mimpKarburant" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mimpKarburant->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mimpKarburant->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mimpKarburant" id="z_mimpKarburant" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mimpKarburant->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_importi_mimpKarburant" class="ew-search-field">
<template id="tp_x_mimpKarburant">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi" data-field="x_mimpKarburant" name="x_mimpKarburant" id="x_mimpKarburant"<?= $Page->mimpKarburant->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mimpKarburant" class="ew-item-list"></div>
<selection-list hidden
    id="x_mimpKarburant"
    name="x_mimpKarburant"
    value="<?= HtmlEncode($Page->mimpKarburant->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mimpKarburant"
    data-bs-target="dsl_x_mimpKarburant"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mimpKarburant->isInvalidClass() ?>"
    data-table="makina_importi"
    data-field="x_mimpKarburant"
    data-value-separator="<?= $Page->mimpKarburant->displayValueSeparatorAttribute() ?>"
    <?= $Page->mimpKarburant->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mimpKarburant->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_importi_mimpKarburant" class="ew-search-field2 d-none">
<template id="tp_y_mimpKarburant">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi" data-field="x_mimpKarburant" name="y_mimpKarburant" id="y_mimpKarburant"<?= $Page->mimpKarburant->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mimpKarburant" class="ew-item-list"></div>
<selection-list hidden
    id="y_mimpKarburant"
    name="y_mimpKarburant"
    value="<?= HtmlEncode($Page->mimpKarburant->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mimpKarburant"
    data-bs-target="dsl_y_mimpKarburant"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mimpKarburant->isInvalidClass() ?>"
    data-table="makina_importi"
    data-field="x_mimpKarburant"
    data-value-separator="<?= $Page->mimpKarburant->displayValueSeparatorAttribute() ?>"
    <?= $Page->mimpKarburant->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mimpKarburant->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->mimpGati->Visible) { // mimpGati ?>
<?php
if (!$Page->mimpGati->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_mimpGati" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->mimpGati->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->mimpGati->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_mimpGati" id="z_mimpGati" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->mimpGati->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->mimpGati->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->mimpGati->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_importi_mimpGati" class="ew-search-field">
<template id="tp_x_mimpGati">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi" data-field="x_mimpGati" name="x_mimpGati" id="x_mimpGati"<?= $Page->mimpGati->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_mimpGati" class="ew-item-list"></div>
<selection-list hidden
    id="x_mimpGati"
    name="x_mimpGati"
    value="<?= HtmlEncode($Page->mimpGati->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_mimpGati"
    data-bs-target="dsl_x_mimpGati"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mimpGati->isInvalidClass() ?>"
    data-table="makina_importi"
    data-field="x_mimpGati"
    data-value-separator="<?= $Page->mimpGati->displayValueSeparatorAttribute() ?>"
    <?= $Page->mimpGati->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mimpGati->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_importi_mimpGati" class="ew-search-field2 d-none">
<template id="tp_y_mimpGati">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi" data-field="x_mimpGati" name="y_mimpGati" id="y_mimpGati"<?= $Page->mimpGati->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_mimpGati" class="ew-item-list"></div>
<selection-list hidden
    id="y_mimpGati"
    name="y_mimpGati"
    value="<?= HtmlEncode($Page->mimpGati->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_mimpGati"
    data-bs-target="dsl_y_mimpGati"
    data-repeatcolumn="5"
    class="form-control<?= $Page->mimpGati->isInvalidClass() ?>"
    data-table="makina_importi"
    data-field="x_mimpGati"
    data-value-separator="<?= $Page->mimpGati->displayValueSeparatorAttribute() ?>"
    <?= $Page->mimpGati->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->mimpGati->getErrorMessage(false) ?></div>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fmakina_importisrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fmakina_importisrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fmakina_importisrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fmakina_importisrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> makina_importi">
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
<form name="fmakina_importilist" id="fmakina_importilist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_importi">
<div id="gmp_makina_importi" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_makina_importilist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->mimpID->Visible) { // mimpID ?>
        <th data-name="mimpID" class="<?= $Page->mimpID->headerCellClass() ?>"><div id="elh_makina_importi_mimpID" class="makina_importi_mimpID"><?= $Page->renderFieldHeader($Page->mimpID) ?></div></th>
<?php } ?>
<?php if ($Page->mimpMarka->Visible) { // mimpMarka ?>
        <th data-name="mimpMarka" class="<?= $Page->mimpMarka->headerCellClass() ?>"><div id="elh_makina_importi_mimpMarka" class="makina_importi_mimpMarka"><?= $Page->renderFieldHeader($Page->mimpMarka) ?></div></th>
<?php } ?>
<?php if ($Page->mimpModeli->Visible) { // mimpModeli ?>
        <th data-name="mimpModeli" class="<?= $Page->mimpModeli->headerCellClass() ?>"><div id="elh_makina_importi_mimpModeli" class="makina_importi_mimpModeli"><?= $Page->renderFieldHeader($Page->mimpModeli) ?></div></th>
<?php } ?>
<?php if ($Page->mimpTipi->Visible) { // mimpTipi ?>
        <th data-name="mimpTipi" class="<?= $Page->mimpTipi->headerCellClass() ?>"><div id="elh_makina_importi_mimpTipi" class="makina_importi_mimpTipi"><?= $Page->renderFieldHeader($Page->mimpTipi) ?></div></th>
<?php } ?>
<?php if ($Page->mimpShasia->Visible) { // mimpShasia ?>
        <th data-name="mimpShasia" class="<?= $Page->mimpShasia->headerCellClass() ?>"><div id="elh_makina_importi_mimpShasia" class="makina_importi_mimpShasia"><?= $Page->renderFieldHeader($Page->mimpShasia) ?></div></th>
<?php } ?>
<?php if ($Page->mimpViti->Visible) { // mimpViti ?>
        <th data-name="mimpViti" class="<?= $Page->mimpViti->headerCellClass() ?>"><div id="elh_makina_importi_mimpViti" class="makina_importi_mimpViti"><?= $Page->renderFieldHeader($Page->mimpViti) ?></div></th>
<?php } ?>
<?php if ($Page->mimpKarburant->Visible) { // mimpKarburant ?>
        <th data-name="mimpKarburant" class="<?= $Page->mimpKarburant->headerCellClass() ?>"><div id="elh_makina_importi_mimpKarburant" class="makina_importi_mimpKarburant"><?= $Page->renderFieldHeader($Page->mimpKarburant) ?></div></th>
<?php } ?>
<?php if ($Page->mimpPrejardhja->Visible) { // mimpPrejardhja ?>
        <th data-name="mimpPrejardhja" class="<?= $Page->mimpPrejardhja->headerCellClass() ?>"><div id="elh_makina_importi_mimpPrejardhja" class="makina_importi_mimpPrejardhja"><?= $Page->renderFieldHeader($Page->mimpPrejardhja) ?></div></th>
<?php } ?>
<?php if ($Page->mimpCmimiBlerjes->Visible) { // mimpCmimiBlerjes ?>
        <th data-name="mimpCmimiBlerjes" class="<?= $Page->mimpCmimiBlerjes->headerCellClass() ?>"><div id="elh_makina_importi_mimpCmimiBlerjes" class="makina_importi_mimpCmimiBlerjes"><?= $Page->renderFieldHeader($Page->mimpCmimiBlerjes) ?></div></th>
<?php } ?>
<?php if ($Page->mimpDogana->Visible) { // mimpDogana ?>
        <th data-name="mimpDogana" class="<?= $Page->mimpDogana->headerCellClass() ?>"><div id="elh_makina_importi_mimpDogana" class="makina_importi_mimpDogana"><?= $Page->renderFieldHeader($Page->mimpDogana) ?></div></th>
<?php } ?>
<?php if ($Page->mimpTransporti->Visible) { // mimpTransporti ?>
        <th data-name="mimpTransporti" class="<?= $Page->mimpTransporti->headerCellClass() ?>"><div id="elh_makina_importi_mimpTransporti" class="makina_importi_mimpTransporti"><?= $Page->renderFieldHeader($Page->mimpTransporti) ?></div></th>
<?php } ?>
<?php if ($Page->mimpTjera->Visible) { // mimpTjera ?>
        <th data-name="mimpTjera" class="<?= $Page->mimpTjera->headerCellClass() ?>"><div id="elh_makina_importi_mimpTjera" class="makina_importi_mimpTjera"><?= $Page->renderFieldHeader($Page->mimpTjera) ?></div></th>
<?php } ?>
<?php if ($Page->mimpDtHyrjes->Visible) { // mimpDtHyrjes ?>
        <th data-name="mimpDtHyrjes" class="<?= $Page->mimpDtHyrjes->headerCellClass() ?>"><div id="elh_makina_importi_mimpDtHyrjes" class="makina_importi_mimpDtHyrjes"><?= $Page->renderFieldHeader($Page->mimpDtHyrjes) ?></div></th>
<?php } ?>
<?php if ($Page->mimpCmimiShitjes->Visible) { // mimpCmimiShitjes ?>
        <th data-name="mimpCmimiShitjes" class="<?= $Page->mimpCmimiShitjes->headerCellClass() ?>"><div id="elh_makina_importi_mimpCmimiShitjes" class="makina_importi_mimpCmimiShitjes"><?= $Page->renderFieldHeader($Page->mimpCmimiShitjes) ?></div></th>
<?php } ?>
<?php if ($Page->mimpGati->Visible) { // mimpGati ?>
        <th data-name="mimpGati" class="<?= $Page->mimpGati->headerCellClass() ?>"><div id="elh_makina_importi_mimpGati" class="makina_importi_mimpGati"><?= $Page->renderFieldHeader($Page->mimpGati) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_makina_importi",
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
    <?php if ($Page->mimpID->Visible) { // mimpID ?>
        <td data-name="mimpID"<?= $Page->mimpID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpID" class="el_makina_importi_mimpID">
<span<?= $Page->mimpID->viewAttributes() ?>>
<?= $Page->mimpID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpMarka->Visible) { // mimpMarka ?>
        <td data-name="mimpMarka"<?= $Page->mimpMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpMarka" class="el_makina_importi_mimpMarka">
<span<?= $Page->mimpMarka->viewAttributes() ?>>
<?= $Page->mimpMarka->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpModeli->Visible) { // mimpModeli ?>
        <td data-name="mimpModeli"<?= $Page->mimpModeli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpModeli" class="el_makina_importi_mimpModeli">
<span<?= $Page->mimpModeli->viewAttributes() ?>>
<?= $Page->mimpModeli->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpTipi->Visible) { // mimpTipi ?>
        <td data-name="mimpTipi"<?= $Page->mimpTipi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpTipi" class="el_makina_importi_mimpTipi">
<span<?= $Page->mimpTipi->viewAttributes() ?>>
<?= $Page->mimpTipi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpShasia->Visible) { // mimpShasia ?>
        <td data-name="mimpShasia"<?= $Page->mimpShasia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpShasia" class="el_makina_importi_mimpShasia">
<span<?= $Page->mimpShasia->viewAttributes() ?>>
<?= $Page->mimpShasia->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpViti->Visible) { // mimpViti ?>
        <td data-name="mimpViti"<?= $Page->mimpViti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpViti" class="el_makina_importi_mimpViti">
<span<?= $Page->mimpViti->viewAttributes() ?>>
<?= $Page->mimpViti->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpKarburant->Visible) { // mimpKarburant ?>
        <td data-name="mimpKarburant"<?= $Page->mimpKarburant->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpKarburant" class="el_makina_importi_mimpKarburant">
<span<?= $Page->mimpKarburant->viewAttributes() ?>>
<?= $Page->mimpKarburant->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpPrejardhja->Visible) { // mimpPrejardhja ?>
        <td data-name="mimpPrejardhja"<?= $Page->mimpPrejardhja->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpPrejardhja" class="el_makina_importi_mimpPrejardhja">
<span<?= $Page->mimpPrejardhja->viewAttributes() ?>>
<?= $Page->mimpPrejardhja->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpCmimiBlerjes->Visible) { // mimpCmimiBlerjes ?>
        <td data-name="mimpCmimiBlerjes"<?= $Page->mimpCmimiBlerjes->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpCmimiBlerjes" class="el_makina_importi_mimpCmimiBlerjes">
<span<?= $Page->mimpCmimiBlerjes->viewAttributes() ?>>
<?= $Page->mimpCmimiBlerjes->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpDogana->Visible) { // mimpDogana ?>
        <td data-name="mimpDogana"<?= $Page->mimpDogana->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpDogana" class="el_makina_importi_mimpDogana">
<span<?= $Page->mimpDogana->viewAttributes() ?>>
<?= $Page->mimpDogana->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpTransporti->Visible) { // mimpTransporti ?>
        <td data-name="mimpTransporti"<?= $Page->mimpTransporti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpTransporti" class="el_makina_importi_mimpTransporti">
<span<?= $Page->mimpTransporti->viewAttributes() ?>>
<?= $Page->mimpTransporti->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpTjera->Visible) { // mimpTjera ?>
        <td data-name="mimpTjera"<?= $Page->mimpTjera->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpTjera" class="el_makina_importi_mimpTjera">
<span<?= $Page->mimpTjera->viewAttributes() ?>>
<?= $Page->mimpTjera->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpDtHyrjes->Visible) { // mimpDtHyrjes ?>
        <td data-name="mimpDtHyrjes"<?= $Page->mimpDtHyrjes->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpDtHyrjes" class="el_makina_importi_mimpDtHyrjes">
<span<?= $Page->mimpDtHyrjes->viewAttributes() ?>>
<?= $Page->mimpDtHyrjes->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpCmimiShitjes->Visible) { // mimpCmimiShitjes ?>
        <td data-name="mimpCmimiShitjes"<?= $Page->mimpCmimiShitjes->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpCmimiShitjes" class="el_makina_importi_mimpCmimiShitjes">
<span<?= $Page->mimpCmimiShitjes->viewAttributes() ?>>
<?= $Page->mimpCmimiShitjes->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mimpGati->Visible) { // mimpGati ?>
        <td data-name="mimpGati"<?= $Page->mimpGati->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpGati" class="el_makina_importi_mimpGati">
<span<?= $Page->mimpGati->viewAttributes() ?>>
<?= $Page->mimpGati->getViewValue() ?></span>
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
    ew.addEventHandlers("makina_importi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
