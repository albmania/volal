<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina: currentTable } });
var currentForm, currentPageID;
var fmakinalist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakinalist = new ew.Form("fmakinalist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fmakinalist;
    fmakinalist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fmakinalist");
});
var fmakinasrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fmakinasrch = new ew.Form("fmakinasrch", "list");
    currentSearchForm = fmakinasrch;

    // Add fields
    var fields = currentTable.fields;
    fmakinasrch.addFields([
        ["makinaID", [], fields.makinaID.isInvalid],
        ["makinaKlienti", [], fields.makinaKlienti.isInvalid],
        ["y_makinaKlienti", [ew.Validators.between], false],
        ["makinaMarka", [], fields.makinaMarka.isInvalid],
        ["makinaModeli", [], fields.makinaModeli.isInvalid],
        ["y_makinaModeli", [ew.Validators.between], false],
        ["makinaKarburanti", [], fields.makinaKarburanti.isInvalid],
        ["makinaMadhesiaMotorrit", [], fields.makinaMadhesiaMotorrit.isInvalid],
        ["makinaVitiProdhimit", [ew.Validators.integer], fields.makinaVitiProdhimit.isInvalid],
        ["y_makinaVitiProdhimit", [ew.Validators.between], false],
        ["makinaTarga", [], fields.makinaTarga.isInvalid],
        ["makinaShiturVOLAL", [], fields.makinaShiturVOLAL.isInvalid],
        ["makinaAutori", [], fields.makinaAutori.isInvalid],
        ["makinaShtuar", [], fields.makinaShtuar.isInvalid],
        ["makinaModifikuar", [], fields.makinaModifikuar.isInvalid]
    ]);

    // Validate form
    fmakinasrch.validate = function () {
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
    fmakinasrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakinasrch.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakinasrch.lists.makinaKlienti = <?= $Page->makinaKlienti->toClientList($Page) ?>;
    fmakinasrch.lists.makinaShiturVOLAL = <?= $Page->makinaShiturVOLAL->toClientList($Page) ?>;

    // Filters
    fmakinasrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fmakinasrch.initSearchPanel = true;
    loadjs.done("fmakinasrch");
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
<form name="fmakinasrch" id="fmakinasrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fmakinasrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="makina">
<div class="ew-extended-search container-fluid">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->makinaKlienti->Visible) { // makinaKlienti ?>
<?php
if (!$Page->makinaKlienti->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_makinaKlienti" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->makinaKlienti->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_makinaKlienti" class="ew-search-caption ew-label"><?= $Page->makinaKlienti->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_makinaKlienti" id="z_makinaKlienti" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->makinaKlienti->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_makinaKlienti" class="ew-search-field">
<input type="<?= $Page->makinaKlienti->getInputTextType() ?>" name="x_makinaKlienti" id="x_makinaKlienti" data-table="makina" data-field="x_makinaKlienti" value="<?= $Page->makinaKlienti->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->makinaKlienti->getPlaceHolder()) ?>"<?= $Page->makinaKlienti->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaKlienti->getErrorMessage(false) ?></div>
<?= $Page->makinaKlienti->Lookup->getParamTag($Page, "p_x_makinaKlienti") ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_makinaKlienti" class="ew-search-field2 d-none">
<input type="<?= $Page->makinaKlienti->getInputTextType() ?>" name="y_makinaKlienti" id="y_makinaKlienti" data-table="makina" data-field="x_makinaKlienti" value="<?= $Page->makinaKlienti->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->makinaKlienti->getPlaceHolder()) ?>"<?= $Page->makinaKlienti->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaKlienti->getErrorMessage(false) ?></div>
<?= $Page->makinaKlienti->Lookup->getParamTag($Page, "p_y_makinaKlienti") ?>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->makinaModeli->Visible) { // makinaModeli ?>
<?php
if (!$Page->makinaModeli->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_makinaModeli" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->makinaModeli->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_makinaModeli" class="ew-search-caption ew-label"><?= $Page->makinaModeli->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_makinaModeli" id="z_makinaModeli" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->makinaModeli->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_makinaModeli" class="ew-search-field">
<input type="<?= $Page->makinaModeli->getInputTextType() ?>" name="x_makinaModeli" id="x_makinaModeli" data-table="makina" data-field="x_makinaModeli" value="<?= $Page->makinaModeli->EditValue ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->makinaModeli->getPlaceHolder()) ?>"<?= $Page->makinaModeli->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaModeli->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_makinaModeli" class="ew-search-field2 d-none">
<input type="<?= $Page->makinaModeli->getInputTextType() ?>" name="y_makinaModeli" id="y_makinaModeli" data-table="makina" data-field="x_makinaModeli" value="<?= $Page->makinaModeli->EditValue2 ?>" size="30" maxlength="50" placeholder="<?= HtmlEncode($Page->makinaModeli->getPlaceHolder()) ?>"<?= $Page->makinaModeli->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaModeli->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->makinaVitiProdhimit->Visible) { // makinaVitiProdhimit ?>
<?php
if (!$Page->makinaVitiProdhimit->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_makinaVitiProdhimit" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->makinaVitiProdhimit->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_makinaVitiProdhimit" class="ew-search-caption ew-label"><?= $Page->makinaVitiProdhimit->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_makinaVitiProdhimit" id="z_makinaVitiProdhimit" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->makinaVitiProdhimit->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->makinaVitiProdhimit->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->makinaVitiProdhimit->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->makinaVitiProdhimit->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->makinaVitiProdhimit->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->makinaVitiProdhimit->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->makinaVitiProdhimit->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_makina_makinaVitiProdhimit" class="ew-search-field">
<input type="<?= $Page->makinaVitiProdhimit->getInputTextType() ?>" name="x_makinaVitiProdhimit" id="x_makinaVitiProdhimit" data-table="makina" data-field="x_makinaVitiProdhimit" value="<?= $Page->makinaVitiProdhimit->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->makinaVitiProdhimit->getPlaceHolder()) ?>"<?= $Page->makinaVitiProdhimit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaVitiProdhimit->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_makina_makinaVitiProdhimit" class="ew-search-field2 d-none">
<input type="<?= $Page->makinaVitiProdhimit->getInputTextType() ?>" name="y_makinaVitiProdhimit" id="y_makinaVitiProdhimit" data-table="makina" data-field="x_makinaVitiProdhimit" value="<?= $Page->makinaVitiProdhimit->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->makinaVitiProdhimit->getPlaceHolder()) ?>"<?= $Page->makinaVitiProdhimit->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->makinaVitiProdhimit->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->makinaShiturVOLAL->Visible) { // makinaShiturVOLAL ?>
<?php
if (!$Page->makinaShiturVOLAL->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_makinaShiturVOLAL" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->makinaShiturVOLAL->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->makinaShiturVOLAL->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_makinaShiturVOLAL" id="z_makinaShiturVOLAL" value="=">
</div>
        </div>
        <div id="el_makina_makinaShiturVOLAL" class="ew-search-field">
<template id="tp_x_makinaShiturVOLAL">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina" data-field="x_makinaShiturVOLAL" name="x_makinaShiturVOLAL" id="x_makinaShiturVOLAL"<?= $Page->makinaShiturVOLAL->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_makinaShiturVOLAL" class="ew-item-list"></div>
<selection-list hidden
    id="x_makinaShiturVOLAL"
    name="x_makinaShiturVOLAL"
    value="<?= HtmlEncode($Page->makinaShiturVOLAL->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_makinaShiturVOLAL"
    data-bs-target="dsl_x_makinaShiturVOLAL"
    data-repeatcolumn="5"
    class="form-control<?= $Page->makinaShiturVOLAL->isInvalidClass() ?>"
    data-table="makina"
    data-field="x_makinaShiturVOLAL"
    data-value-separator="<?= $Page->makinaShiturVOLAL->displayValueSeparatorAttribute() ?>"
    <?= $Page->makinaShiturVOLAL->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->makinaShiturVOLAL->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fmakinasrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fmakinasrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fmakinasrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fmakinasrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> makina">
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
<form name="fmakinalist" id="fmakinalist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina">
<div id="gmp_makina" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_makinalist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->makinaID->Visible) { // makinaID ?>
        <th data-name="makinaID" class="<?= $Page->makinaID->headerCellClass() ?>"><div id="elh_makina_makinaID" class="makina_makinaID"><?= $Page->renderFieldHeader($Page->makinaID) ?></div></th>
<?php } ?>
<?php if ($Page->makinaKlienti->Visible) { // makinaKlienti ?>
        <th data-name="makinaKlienti" class="<?= $Page->makinaKlienti->headerCellClass() ?>"><div id="elh_makina_makinaKlienti" class="makina_makinaKlienti"><?= $Page->renderFieldHeader($Page->makinaKlienti) ?></div></th>
<?php } ?>
<?php if ($Page->makinaMarka->Visible) { // makinaMarka ?>
        <th data-name="makinaMarka" class="<?= $Page->makinaMarka->headerCellClass() ?>"><div id="elh_makina_makinaMarka" class="makina_makinaMarka"><?= $Page->renderFieldHeader($Page->makinaMarka) ?></div></th>
<?php } ?>
<?php if ($Page->makinaModeli->Visible) { // makinaModeli ?>
        <th data-name="makinaModeli" class="<?= $Page->makinaModeli->headerCellClass() ?>"><div id="elh_makina_makinaModeli" class="makina_makinaModeli"><?= $Page->renderFieldHeader($Page->makinaModeli) ?></div></th>
<?php } ?>
<?php if ($Page->makinaKarburanti->Visible) { // makinaKarburanti ?>
        <th data-name="makinaKarburanti" class="<?= $Page->makinaKarburanti->headerCellClass() ?>"><div id="elh_makina_makinaKarburanti" class="makina_makinaKarburanti"><?= $Page->renderFieldHeader($Page->makinaKarburanti) ?></div></th>
<?php } ?>
<?php if ($Page->makinaMadhesiaMotorrit->Visible) { // makinaMadhesiaMotorrit ?>
        <th data-name="makinaMadhesiaMotorrit" class="<?= $Page->makinaMadhesiaMotorrit->headerCellClass() ?>"><div id="elh_makina_makinaMadhesiaMotorrit" class="makina_makinaMadhesiaMotorrit"><?= $Page->renderFieldHeader($Page->makinaMadhesiaMotorrit) ?></div></th>
<?php } ?>
<?php if ($Page->makinaVitiProdhimit->Visible) { // makinaVitiProdhimit ?>
        <th data-name="makinaVitiProdhimit" class="<?= $Page->makinaVitiProdhimit->headerCellClass() ?>"><div id="elh_makina_makinaVitiProdhimit" class="makina_makinaVitiProdhimit"><?= $Page->renderFieldHeader($Page->makinaVitiProdhimit) ?></div></th>
<?php } ?>
<?php if ($Page->makinaTarga->Visible) { // makinaTarga ?>
        <th data-name="makinaTarga" class="<?= $Page->makinaTarga->headerCellClass() ?>"><div id="elh_makina_makinaTarga" class="makina_makinaTarga"><?= $Page->renderFieldHeader($Page->makinaTarga) ?></div></th>
<?php } ?>
<?php if ($Page->makinaShiturVOLAL->Visible) { // makinaShiturVOLAL ?>
        <th data-name="makinaShiturVOLAL" class="<?= $Page->makinaShiturVOLAL->headerCellClass() ?>"><div id="elh_makina_makinaShiturVOLAL" class="makina_makinaShiturVOLAL"><?= $Page->renderFieldHeader($Page->makinaShiturVOLAL) ?></div></th>
<?php } ?>
<?php if ($Page->makinaAutori->Visible) { // makinaAutori ?>
        <th data-name="makinaAutori" class="<?= $Page->makinaAutori->headerCellClass() ?>"><div id="elh_makina_makinaAutori" class="makina_makinaAutori"><?= $Page->renderFieldHeader($Page->makinaAutori) ?></div></th>
<?php } ?>
<?php if ($Page->makinaShtuar->Visible) { // makinaShtuar ?>
        <th data-name="makinaShtuar" class="<?= $Page->makinaShtuar->headerCellClass() ?>"><div id="elh_makina_makinaShtuar" class="makina_makinaShtuar"><?= $Page->renderFieldHeader($Page->makinaShtuar) ?></div></th>
<?php } ?>
<?php if ($Page->makinaModifikuar->Visible) { // makinaModifikuar ?>
        <th data-name="makinaModifikuar" class="<?= $Page->makinaModifikuar->headerCellClass() ?>"><div id="elh_makina_makinaModifikuar" class="makina_makinaModifikuar"><?= $Page->renderFieldHeader($Page->makinaModifikuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_makina",
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
    <?php if ($Page->makinaID->Visible) { // makinaID ?>
        <td data-name="makinaID"<?= $Page->makinaID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaID" class="el_makina_makinaID">
<span<?= $Page->makinaID->viewAttributes() ?>>
<?= $Page->makinaID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaKlienti->Visible) { // makinaKlienti ?>
        <td data-name="makinaKlienti"<?= $Page->makinaKlienti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaKlienti" class="el_makina_makinaKlienti">
<span<?= $Page->makinaKlienti->viewAttributes() ?>>
<?= $Page->makinaKlienti->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaMarka->Visible) { // makinaMarka ?>
        <td data-name="makinaMarka"<?= $Page->makinaMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaMarka" class="el_makina_makinaMarka">
<span<?= $Page->makinaMarka->viewAttributes() ?>>
<?= $Page->makinaMarka->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaModeli->Visible) { // makinaModeli ?>
        <td data-name="makinaModeli"<?= $Page->makinaModeli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaModeli" class="el_makina_makinaModeli">
<span<?= $Page->makinaModeli->viewAttributes() ?>>
<?= $Page->makinaModeli->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaKarburanti->Visible) { // makinaKarburanti ?>
        <td data-name="makinaKarburanti"<?= $Page->makinaKarburanti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaKarburanti" class="el_makina_makinaKarburanti">
<span<?= $Page->makinaKarburanti->viewAttributes() ?>>
<?= $Page->makinaKarburanti->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaMadhesiaMotorrit->Visible) { // makinaMadhesiaMotorrit ?>
        <td data-name="makinaMadhesiaMotorrit"<?= $Page->makinaMadhesiaMotorrit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaMadhesiaMotorrit" class="el_makina_makinaMadhesiaMotorrit">
<span<?= $Page->makinaMadhesiaMotorrit->viewAttributes() ?>>
<?= $Page->makinaMadhesiaMotorrit->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaVitiProdhimit->Visible) { // makinaVitiProdhimit ?>
        <td data-name="makinaVitiProdhimit"<?= $Page->makinaVitiProdhimit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaVitiProdhimit" class="el_makina_makinaVitiProdhimit">
<span<?= $Page->makinaVitiProdhimit->viewAttributes() ?>>
<?= $Page->makinaVitiProdhimit->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaTarga->Visible) { // makinaTarga ?>
        <td data-name="makinaTarga"<?= $Page->makinaTarga->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaTarga" class="el_makina_makinaTarga">
<span<?= $Page->makinaTarga->viewAttributes() ?>>
<?= $Page->makinaTarga->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaShiturVOLAL->Visible) { // makinaShiturVOLAL ?>
        <td data-name="makinaShiturVOLAL"<?= $Page->makinaShiturVOLAL->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaShiturVOLAL" class="el_makina_makinaShiturVOLAL">
<span<?= $Page->makinaShiturVOLAL->viewAttributes() ?>>
<?= $Page->makinaShiturVOLAL->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaAutori->Visible) { // makinaAutori ?>
        <td data-name="makinaAutori"<?= $Page->makinaAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaAutori" class="el_makina_makinaAutori">
<span<?= $Page->makinaAutori->viewAttributes() ?>>
<?= $Page->makinaAutori->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaShtuar->Visible) { // makinaShtuar ?>
        <td data-name="makinaShtuar"<?= $Page->makinaShtuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaShtuar" class="el_makina_makinaShtuar">
<span<?= $Page->makinaShtuar->viewAttributes() ?>>
<?= $Page->makinaShtuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->makinaModifikuar->Visible) { // makinaModifikuar ?>
        <td data-name="makinaModifikuar"<?= $Page->makinaModifikuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaModifikuar" class="el_makina_makinaModifikuar">
<span<?= $Page->makinaModifikuar->viewAttributes() ?>>
<?= $Page->makinaModifikuar->getViewValue() ?></span>
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
    ew.addEventHandlers("makina");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $("a[class='btn btn-default ew-add-edit ew-add']").append(' SHTO MAKINE ');
});
</script>
<?php } ?>
