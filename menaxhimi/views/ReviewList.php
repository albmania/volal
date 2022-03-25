<?php

namespace PHPMaker2022\volalservice;

// Page object
$ReviewList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { review: currentTable } });
var currentForm, currentPageID;
var freviewlist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    freviewlist = new ew.Form("freviewlist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = freviewlist;
    freviewlist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("freviewlist");
});
var freviewsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    freviewsrch = new ew.Form("freviewsrch", "list");
    currentSearchForm = freviewsrch;

    // Add fields
    var fields = currentTable.fields;
    freviewsrch.addFields([
        ["reviewID", [], fields.reviewID.isInvalid],
        ["reviewGjuha", [], fields.reviewGjuha.isInvalid],
        ["y_reviewGjuha", [ew.Validators.between], false],
        ["reviewEmri", [], fields.reviewEmri.isInvalid],
        ["reviewMakine", [], fields.reviewMakine.isInvalid],
        ["reviewPer", [], fields.reviewPer.isInvalid],
        ["y_reviewPer", [ew.Validators.between], false],
        ["reviewFoto", [], fields.reviewFoto.isInvalid],
        ["reviewTxt", [], fields.reviewTxt.isInvalid],
        ["reviewDate", [ew.Validators.datetime(fields.reviewDate.clientFormatPattern)], fields.reviewDate.isInvalid],
        ["reviewAktiv", [], fields.reviewAktiv.isInvalid]
    ]);

    // Validate form
    freviewsrch.validate = function () {
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
    freviewsrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    freviewsrch.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    freviewsrch.lists.reviewGjuha = <?= $Page->reviewGjuha->toClientList($Page) ?>;
    freviewsrch.lists.reviewPer = <?= $Page->reviewPer->toClientList($Page) ?>;
    freviewsrch.lists.reviewAktiv = <?= $Page->reviewAktiv->toClientList($Page) ?>;

    // Filters
    freviewsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    freviewsrch.initSearchPanel = true;
    loadjs.done("freviewsrch");
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
<form name="freviewsrch" id="freviewsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="freviewsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="review">
<div class="ew-extended-search container-fluid">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->reviewGjuha->Visible) { // reviewGjuha ?>
<?php
if (!$Page->reviewGjuha->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_reviewGjuha" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->reviewGjuha->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->reviewGjuha->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_reviewGjuha" id="z_reviewGjuha" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->reviewGjuha->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_review_reviewGjuha" class="ew-search-field">
<template id="tp_x_reviewGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="review" data-field="x_reviewGjuha" name="x_reviewGjuha" id="x_reviewGjuha"<?= $Page->reviewGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_reviewGjuha" class="ew-item-list"></div>
<selection-list hidden
    id="x_reviewGjuha"
    name="x_reviewGjuha"
    value="<?= HtmlEncode($Page->reviewGjuha->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_reviewGjuha"
    data-bs-target="dsl_x_reviewGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->reviewGjuha->isInvalidClass() ?>"
    data-table="review"
    data-field="x_reviewGjuha"
    data-value-separator="<?= $Page->reviewGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->reviewGjuha->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->reviewGjuha->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_review_reviewGjuha" class="ew-search-field2 d-none">
<template id="tp_y_reviewGjuha">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="review" data-field="x_reviewGjuha" name="y_reviewGjuha" id="y_reviewGjuha"<?= $Page->reviewGjuha->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_reviewGjuha" class="ew-item-list"></div>
<selection-list hidden
    id="y_reviewGjuha"
    name="y_reviewGjuha"
    value="<?= HtmlEncode($Page->reviewGjuha->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_reviewGjuha"
    data-bs-target="dsl_y_reviewGjuha"
    data-repeatcolumn="5"
    class="form-control<?= $Page->reviewGjuha->isInvalidClass() ?>"
    data-table="review"
    data-field="x_reviewGjuha"
    data-value-separator="<?= $Page->reviewGjuha->displayValueSeparatorAttribute() ?>"
    <?= $Page->reviewGjuha->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->reviewGjuha->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->reviewPer->Visible) { // reviewPer ?>
<?php
if (!$Page->reviewPer->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_reviewPer" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->reviewPer->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->reviewPer->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_reviewPer" id="z_reviewPer" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->reviewPer->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->reviewPer->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->reviewPer->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_review_reviewPer" class="ew-search-field">
<template id="tp_x_reviewPer">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="review" data-field="x_reviewPer" name="x_reviewPer" id="x_reviewPer"<?= $Page->reviewPer->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_reviewPer" class="ew-item-list"></div>
<selection-list hidden
    id="x_reviewPer"
    name="x_reviewPer"
    value="<?= HtmlEncode($Page->reviewPer->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_reviewPer"
    data-bs-target="dsl_x_reviewPer"
    data-repeatcolumn="5"
    class="form-control<?= $Page->reviewPer->isInvalidClass() ?>"
    data-table="review"
    data-field="x_reviewPer"
    data-value-separator="<?= $Page->reviewPer->displayValueSeparatorAttribute() ?>"
    <?= $Page->reviewPer->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->reviewPer->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_review_reviewPer" class="ew-search-field2 d-none">
<template id="tp_y_reviewPer">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="review" data-field="x_reviewPer" name="y_reviewPer" id="y_reviewPer"<?= $Page->reviewPer->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_y_reviewPer" class="ew-item-list"></div>
<selection-list hidden
    id="y_reviewPer"
    name="y_reviewPer"
    value="<?= HtmlEncode($Page->reviewPer->AdvancedSearch->SearchValue2) ?>"
    data-type="select-one"
    data-template="tp_y_reviewPer"
    data-bs-target="dsl_y_reviewPer"
    data-repeatcolumn="5"
    class="form-control<?= $Page->reviewPer->isInvalidClass() ?>"
    data-table="review"
    data-field="x_reviewPer"
    data-value-separator="<?= $Page->reviewPer->displayValueSeparatorAttribute() ?>"
    <?= $Page->reviewPer->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->reviewPer->getErrorMessage(false) ?></div>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->reviewDate->Visible) { // reviewDate ?>
<?php
if (!$Page->reviewDate->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_reviewDate" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->reviewDate->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_reviewDate" class="ew-search-caption ew-label"><?= $Page->reviewDate->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_reviewDate" id="z_reviewDate" value="=">
</div>
        </div>
        <div id="el_review_reviewDate" class="ew-search-field">
<input type="<?= $Page->reviewDate->getInputTextType() ?>" name="x_reviewDate" id="x_reviewDate" data-table="review" data-field="x_reviewDate" value="<?= $Page->reviewDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->reviewDate->getPlaceHolder()) ?>"<?= $Page->reviewDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->reviewDate->getErrorMessage(false) ?></div>
<?php if (!$Page->reviewDate->ReadOnly && !$Page->reviewDate->Disabled && !isset($Page->reviewDate->EditAttrs["readonly"]) && !isset($Page->reviewDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["freviewsrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("freviewsrch", "x_reviewDate", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->reviewAktiv->Visible) { // reviewAktiv ?>
<?php
if (!$Page->reviewAktiv->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_reviewAktiv" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->reviewAktiv->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->reviewAktiv->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_reviewAktiv" id="z_reviewAktiv" value="=">
</div>
        </div>
        <div id="el_review_reviewAktiv" class="ew-search-field">
<template id="tp_x_reviewAktiv">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="review" data-field="x_reviewAktiv" name="x_reviewAktiv" id="x_reviewAktiv"<?= $Page->reviewAktiv->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_reviewAktiv" class="ew-item-list"></div>
<selection-list hidden
    id="x_reviewAktiv"
    name="x_reviewAktiv"
    value="<?= HtmlEncode($Page->reviewAktiv->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_reviewAktiv"
    data-bs-target="dsl_x_reviewAktiv"
    data-repeatcolumn="5"
    class="form-control<?= $Page->reviewAktiv->isInvalidClass() ?>"
    data-table="review"
    data-field="x_reviewAktiv"
    data-value-separator="<?= $Page->reviewAktiv->displayValueSeparatorAttribute() ?>"
    <?= $Page->reviewAktiv->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->reviewAktiv->getErrorMessage(false) ?></div>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="freviewsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="freviewsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="freviewsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="freviewsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> review">
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
<form name="freviewlist" id="freviewlist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="review">
<div id="gmp_review" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_reviewlist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->reviewID->Visible) { // reviewID ?>
        <th data-name="reviewID" class="<?= $Page->reviewID->headerCellClass() ?>"><div id="elh_review_reviewID" class="review_reviewID"><?= $Page->renderFieldHeader($Page->reviewID) ?></div></th>
<?php } ?>
<?php if ($Page->reviewGjuha->Visible) { // reviewGjuha ?>
        <th data-name="reviewGjuha" class="<?= $Page->reviewGjuha->headerCellClass() ?>"><div id="elh_review_reviewGjuha" class="review_reviewGjuha"><?= $Page->renderFieldHeader($Page->reviewGjuha) ?></div></th>
<?php } ?>
<?php if ($Page->reviewEmri->Visible) { // reviewEmri ?>
        <th data-name="reviewEmri" class="<?= $Page->reviewEmri->headerCellClass() ?>"><div id="elh_review_reviewEmri" class="review_reviewEmri"><?= $Page->renderFieldHeader($Page->reviewEmri) ?></div></th>
<?php } ?>
<?php if ($Page->reviewMakine->Visible) { // reviewMakine ?>
        <th data-name="reviewMakine" class="<?= $Page->reviewMakine->headerCellClass() ?>"><div id="elh_review_reviewMakine" class="review_reviewMakine"><?= $Page->renderFieldHeader($Page->reviewMakine) ?></div></th>
<?php } ?>
<?php if ($Page->reviewPer->Visible) { // reviewPer ?>
        <th data-name="reviewPer" class="<?= $Page->reviewPer->headerCellClass() ?>"><div id="elh_review_reviewPer" class="review_reviewPer"><?= $Page->renderFieldHeader($Page->reviewPer) ?></div></th>
<?php } ?>
<?php if ($Page->reviewFoto->Visible) { // reviewFoto ?>
        <th data-name="reviewFoto" class="<?= $Page->reviewFoto->headerCellClass() ?>"><div id="elh_review_reviewFoto" class="review_reviewFoto"><?= $Page->renderFieldHeader($Page->reviewFoto) ?></div></th>
<?php } ?>
<?php if ($Page->reviewTxt->Visible) { // reviewTxt ?>
        <th data-name="reviewTxt" class="<?= $Page->reviewTxt->headerCellClass() ?>"><div id="elh_review_reviewTxt" class="review_reviewTxt"><?= $Page->renderFieldHeader($Page->reviewTxt) ?></div></th>
<?php } ?>
<?php if ($Page->reviewDate->Visible) { // reviewDate ?>
        <th data-name="reviewDate" class="<?= $Page->reviewDate->headerCellClass() ?>"><div id="elh_review_reviewDate" class="review_reviewDate"><?= $Page->renderFieldHeader($Page->reviewDate) ?></div></th>
<?php } ?>
<?php if ($Page->reviewAktiv->Visible) { // reviewAktiv ?>
        <th data-name="reviewAktiv" class="<?= $Page->reviewAktiv->headerCellClass() ?>"><div id="elh_review_reviewAktiv" class="review_reviewAktiv"><?= $Page->renderFieldHeader($Page->reviewAktiv) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_review",
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
    <?php if ($Page->reviewID->Visible) { // reviewID ?>
        <td data-name="reviewID"<?= $Page->reviewID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewID" class="el_review_reviewID">
<span<?= $Page->reviewID->viewAttributes() ?>>
<?= $Page->reviewID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->reviewGjuha->Visible) { // reviewGjuha ?>
        <td data-name="reviewGjuha"<?= $Page->reviewGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewGjuha" class="el_review_reviewGjuha">
<span<?= $Page->reviewGjuha->viewAttributes() ?>>
<?= $Page->reviewGjuha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->reviewEmri->Visible) { // reviewEmri ?>
        <td data-name="reviewEmri"<?= $Page->reviewEmri->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewEmri" class="el_review_reviewEmri">
<span<?= $Page->reviewEmri->viewAttributes() ?>>
<?= $Page->reviewEmri->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->reviewMakine->Visible) { // reviewMakine ?>
        <td data-name="reviewMakine"<?= $Page->reviewMakine->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewMakine" class="el_review_reviewMakine">
<span<?= $Page->reviewMakine->viewAttributes() ?>>
<?= $Page->reviewMakine->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->reviewPer->Visible) { // reviewPer ?>
        <td data-name="reviewPer"<?= $Page->reviewPer->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewPer" class="el_review_reviewPer">
<span<?= $Page->reviewPer->viewAttributes() ?>>
<?= $Page->reviewPer->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->reviewFoto->Visible) { // reviewFoto ?>
        <td data-name="reviewFoto"<?= $Page->reviewFoto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewFoto" class="el_review_reviewFoto">
<span>
<?= GetFileViewTag($Page->reviewFoto, $Page->reviewFoto->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->reviewTxt->Visible) { // reviewTxt ?>
        <td data-name="reviewTxt"<?= $Page->reviewTxt->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewTxt" class="el_review_reviewTxt">
<span<?= $Page->reviewTxt->viewAttributes() ?>>
<?= $Page->reviewTxt->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->reviewDate->Visible) { // reviewDate ?>
        <td data-name="reviewDate"<?= $Page->reviewDate->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewDate" class="el_review_reviewDate">
<span<?= $Page->reviewDate->viewAttributes() ?>>
<?= $Page->reviewDate->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->reviewAktiv->Visible) { // reviewAktiv ?>
        <td data-name="reviewAktiv"<?= $Page->reviewAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewAktiv" class="el_review_reviewAktiv">
<span<?= $Page->reviewAktiv->viewAttributes() ?>>
<?= $Page->reviewAktiv->getViewValue() ?></span>
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
    ew.addEventHandlers("review");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
