<?php

namespace PHPMaker2022\volalservice;

// Page object
$PjeseKembimiList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pjese_kembimi: currentTable } });
var currentForm, currentPageID;
var fpjese_kembimilist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fpjese_kembimilist = new ew.Form("fpjese_kembimilist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fpjese_kembimilist;
    fpjese_kembimilist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fpjese_kembimilist");
});
var fpjese_kembimisrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fpjese_kembimisrch = new ew.Form("fpjese_kembimisrch", "list");
    currentSearchForm = fpjese_kembimisrch;

    // Add fields
    var fields = currentTable.fields;
    fpjese_kembimisrch.addFields([
        ["pjeseID", [], fields.pjeseID.isInvalid],
        ["pjeseGjendja", [], fields.pjeseGjendja.isInvalid],
        ["pjeseKodiVolvo", [], fields.pjeseKodiVolvo.isInvalid],
        ["pjeseKodiProdhuesi", [], fields.pjeseKodiProdhuesi.isInvalid],
        ["pjeseProdhuesi", [], fields.pjeseProdhuesi.isInvalid],
        ["y_pjeseProdhuesi", [ew.Validators.between], false],
        ["pjesePerMarke", [], fields.pjesePerMarke.isInvalid],
        ["pjesePerModel", [], fields.pjesePerModel.isInvalid],
        ["pjesePerVitProdhimi", [], fields.pjesePerVitProdhimi.isInvalid],
        ["pjeseCmimBlerje", [], fields.pjeseCmimBlerje.isInvalid],
        ["pjeseCmimShitje", [], fields.pjeseCmimShitje.isInvalid],
        ["pjeseAutori", [], fields.pjeseAutori.isInvalid],
        ["pjeseShtuar", [], fields.pjeseShtuar.isInvalid],
        ["pjeseModifikuar", [], fields.pjeseModifikuar.isInvalid]
    ]);

    // Validate form
    fpjese_kembimisrch.validate = function () {
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
    fpjese_kembimisrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fpjese_kembimisrch.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fpjese_kembimisrch.lists.pjeseGjendja = <?= $Page->pjeseGjendja->toClientList($Page) ?>;

    // Filters
    fpjese_kembimisrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fpjese_kembimisrch.initSearchPanel = true;
    loadjs.done("fpjese_kembimisrch");
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
<form name="fpjese_kembimisrch" id="fpjese_kembimisrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fpjese_kembimisrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="pjese_kembimi">
<div class="ew-extended-search container-fluid">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->pjeseGjendja->Visible) { // pjeseGjendja ?>
<?php
if (!$Page->pjeseGjendja->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_pjeseGjendja" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->pjeseGjendja->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label class="ew-search-caption ew-label"><?= $Page->pjeseGjendja->caption() ?></label>
            <div class="ew-search-operator">
<?= $Language->phrase("=") ?>
<input type="hidden" name="z_pjeseGjendja" id="z_pjeseGjendja" value="=">
</div>
        </div>
        <div id="el_pjese_kembimi_pjeseGjendja" class="ew-search-field">
<template id="tp_x_pjeseGjendja">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="pjese_kembimi" data-field="x_pjeseGjendja" name="x_pjeseGjendja" id="x_pjeseGjendja"<?= $Page->pjeseGjendja->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_pjeseGjendja" class="ew-item-list"></div>
<selection-list hidden
    id="x_pjeseGjendja"
    name="x_pjeseGjendja"
    value="<?= HtmlEncode($Page->pjeseGjendja->AdvancedSearch->SearchValue) ?>"
    data-type="select-one"
    data-template="tp_x_pjeseGjendja"
    data-bs-target="dsl_x_pjeseGjendja"
    data-repeatcolumn="5"
    class="form-control<?= $Page->pjeseGjendja->isInvalidClass() ?>"
    data-table="pjese_kembimi"
    data-field="x_pjeseGjendja"
    data-value-separator="<?= $Page->pjeseGjendja->displayValueSeparatorAttribute() ?>"
    <?= $Page->pjeseGjendja->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Page->pjeseGjendja->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
        </div><!-- /.ew-search-field -->
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->pjeseProdhuesi->Visible) { // pjeseProdhuesi ?>
<?php
if (!$Page->pjeseProdhuesi->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_pjeseProdhuesi" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->pjeseProdhuesi->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_pjeseProdhuesi" class="ew-search-caption ew-label"><?= $Page->pjeseProdhuesi->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_pjeseProdhuesi" id="z_pjeseProdhuesi" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->pjeseProdhuesi->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_pjese_kembimi_pjeseProdhuesi" class="ew-search-field">
<input type="<?= $Page->pjeseProdhuesi->getInputTextType() ?>" name="x_pjeseProdhuesi" id="x_pjeseProdhuesi" data-table="pjese_kembimi" data-field="x_pjeseProdhuesi" value="<?= $Page->pjeseProdhuesi->EditValue ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->pjeseProdhuesi->getPlaceHolder()) ?>"<?= $Page->pjeseProdhuesi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->pjeseProdhuesi->getErrorMessage(false) ?></div>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_pjese_kembimi_pjeseProdhuesi" class="ew-search-field2 d-none">
<input type="<?= $Page->pjeseProdhuesi->getInputTextType() ?>" name="y_pjeseProdhuesi" id="y_pjeseProdhuesi" data-table="pjese_kembimi" data-field="x_pjeseProdhuesi" value="<?= $Page->pjeseProdhuesi->EditValue2 ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->pjeseProdhuesi->getPlaceHolder()) ?>"<?= $Page->pjeseProdhuesi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->pjeseProdhuesi->getErrorMessage(false) ?></div>
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fpjese_kembimisrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fpjese_kembimisrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fpjese_kembimisrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fpjese_kembimisrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> pjese_kembimi">
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
<form name="fpjese_kembimilist" id="fpjese_kembimilist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pjese_kembimi">
<div id="gmp_pjese_kembimi" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_pjese_kembimilist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->pjeseID->Visible) { // pjeseID ?>
        <th data-name="pjeseID" class="<?= $Page->pjeseID->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseID" class="pjese_kembimi_pjeseID"><?= $Page->renderFieldHeader($Page->pjeseID) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseGjendja->Visible) { // pjeseGjendja ?>
        <th data-name="pjeseGjendja" class="<?= $Page->pjeseGjendja->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseGjendja" class="pjese_kembimi_pjeseGjendja"><?= $Page->renderFieldHeader($Page->pjeseGjendja) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseKodiVolvo->Visible) { // pjeseKodiVolvo ?>
        <th data-name="pjeseKodiVolvo" class="<?= $Page->pjeseKodiVolvo->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseKodiVolvo" class="pjese_kembimi_pjeseKodiVolvo"><?= $Page->renderFieldHeader($Page->pjeseKodiVolvo) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseKodiProdhuesi->Visible) { // pjeseKodiProdhuesi ?>
        <th data-name="pjeseKodiProdhuesi" class="<?= $Page->pjeseKodiProdhuesi->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseKodiProdhuesi" class="pjese_kembimi_pjeseKodiProdhuesi"><?= $Page->renderFieldHeader($Page->pjeseKodiProdhuesi) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseProdhuesi->Visible) { // pjeseProdhuesi ?>
        <th data-name="pjeseProdhuesi" class="<?= $Page->pjeseProdhuesi->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseProdhuesi" class="pjese_kembimi_pjeseProdhuesi"><?= $Page->renderFieldHeader($Page->pjeseProdhuesi) ?></div></th>
<?php } ?>
<?php if ($Page->pjesePerMarke->Visible) { // pjesePerMarke ?>
        <th data-name="pjesePerMarke" class="<?= $Page->pjesePerMarke->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjesePerMarke" class="pjese_kembimi_pjesePerMarke"><?= $Page->renderFieldHeader($Page->pjesePerMarke) ?></div></th>
<?php } ?>
<?php if ($Page->pjesePerModel->Visible) { // pjesePerModel ?>
        <th data-name="pjesePerModel" class="<?= $Page->pjesePerModel->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjesePerModel" class="pjese_kembimi_pjesePerModel"><?= $Page->renderFieldHeader($Page->pjesePerModel) ?></div></th>
<?php } ?>
<?php if ($Page->pjesePerVitProdhimi->Visible) { // pjesePerVitProdhimi ?>
        <th data-name="pjesePerVitProdhimi" class="<?= $Page->pjesePerVitProdhimi->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjesePerVitProdhimi" class="pjese_kembimi_pjesePerVitProdhimi"><?= $Page->renderFieldHeader($Page->pjesePerVitProdhimi) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseCmimBlerje->Visible) { // pjeseCmimBlerje ?>
        <th data-name="pjeseCmimBlerje" class="<?= $Page->pjeseCmimBlerje->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseCmimBlerje" class="pjese_kembimi_pjeseCmimBlerje"><?= $Page->renderFieldHeader($Page->pjeseCmimBlerje) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseCmimShitje->Visible) { // pjeseCmimShitje ?>
        <th data-name="pjeseCmimShitje" class="<?= $Page->pjeseCmimShitje->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseCmimShitje" class="pjese_kembimi_pjeseCmimShitje"><?= $Page->renderFieldHeader($Page->pjeseCmimShitje) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseAutori->Visible) { // pjeseAutori ?>
        <th data-name="pjeseAutori" class="<?= $Page->pjeseAutori->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseAutori" class="pjese_kembimi_pjeseAutori"><?= $Page->renderFieldHeader($Page->pjeseAutori) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseShtuar->Visible) { // pjeseShtuar ?>
        <th data-name="pjeseShtuar" class="<?= $Page->pjeseShtuar->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseShtuar" class="pjese_kembimi_pjeseShtuar"><?= $Page->renderFieldHeader($Page->pjeseShtuar) ?></div></th>
<?php } ?>
<?php if ($Page->pjeseModifikuar->Visible) { // pjeseModifikuar ?>
        <th data-name="pjeseModifikuar" class="<?= $Page->pjeseModifikuar->headerCellClass() ?>"><div id="elh_pjese_kembimi_pjeseModifikuar" class="pjese_kembimi_pjeseModifikuar"><?= $Page->renderFieldHeader($Page->pjeseModifikuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_pjese_kembimi",
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
    <?php if ($Page->pjeseID->Visible) { // pjeseID ?>
        <td data-name="pjeseID"<?= $Page->pjeseID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseID" class="el_pjese_kembimi_pjeseID">
<span<?= $Page->pjeseID->viewAttributes() ?>>
<?= $Page->pjeseID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseGjendja->Visible) { // pjeseGjendja ?>
        <td data-name="pjeseGjendja"<?= $Page->pjeseGjendja->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseGjendja" class="el_pjese_kembimi_pjeseGjendja">
<span<?= $Page->pjeseGjendja->viewAttributes() ?>>
<?= $Page->pjeseGjendja->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseKodiVolvo->Visible) { // pjeseKodiVolvo ?>
        <td data-name="pjeseKodiVolvo"<?= $Page->pjeseKodiVolvo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseKodiVolvo" class="el_pjese_kembimi_pjeseKodiVolvo">
<span<?= $Page->pjeseKodiVolvo->viewAttributes() ?>>
<?= $Page->pjeseKodiVolvo->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseKodiProdhuesi->Visible) { // pjeseKodiProdhuesi ?>
        <td data-name="pjeseKodiProdhuesi"<?= $Page->pjeseKodiProdhuesi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseKodiProdhuesi" class="el_pjese_kembimi_pjeseKodiProdhuesi">
<span<?= $Page->pjeseKodiProdhuesi->viewAttributes() ?>>
<?= $Page->pjeseKodiProdhuesi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseProdhuesi->Visible) { // pjeseProdhuesi ?>
        <td data-name="pjeseProdhuesi"<?= $Page->pjeseProdhuesi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseProdhuesi" class="el_pjese_kembimi_pjeseProdhuesi">
<span<?= $Page->pjeseProdhuesi->viewAttributes() ?>>
<?= $Page->pjeseProdhuesi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjesePerMarke->Visible) { // pjesePerMarke ?>
        <td data-name="pjesePerMarke"<?= $Page->pjesePerMarke->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjesePerMarke" class="el_pjese_kembimi_pjesePerMarke">
<span<?= $Page->pjesePerMarke->viewAttributes() ?>>
<?= $Page->pjesePerMarke->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjesePerModel->Visible) { // pjesePerModel ?>
        <td data-name="pjesePerModel"<?= $Page->pjesePerModel->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjesePerModel" class="el_pjese_kembimi_pjesePerModel">
<span<?= $Page->pjesePerModel->viewAttributes() ?>>
<?= $Page->pjesePerModel->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjesePerVitProdhimi->Visible) { // pjesePerVitProdhimi ?>
        <td data-name="pjesePerVitProdhimi"<?= $Page->pjesePerVitProdhimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjesePerVitProdhimi" class="el_pjese_kembimi_pjesePerVitProdhimi">
<span<?= $Page->pjesePerVitProdhimi->viewAttributes() ?>>
<?= $Page->pjesePerVitProdhimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseCmimBlerje->Visible) { // pjeseCmimBlerje ?>
        <td data-name="pjeseCmimBlerje"<?= $Page->pjeseCmimBlerje->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseCmimBlerje" class="el_pjese_kembimi_pjeseCmimBlerje">
<span<?= $Page->pjeseCmimBlerje->viewAttributes() ?>>
<?= $Page->pjeseCmimBlerje->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseCmimShitje->Visible) { // pjeseCmimShitje ?>
        <td data-name="pjeseCmimShitje"<?= $Page->pjeseCmimShitje->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseCmimShitje" class="el_pjese_kembimi_pjeseCmimShitje">
<span<?= $Page->pjeseCmimShitje->viewAttributes() ?>>
<?= $Page->pjeseCmimShitje->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseAutori->Visible) { // pjeseAutori ?>
        <td data-name="pjeseAutori"<?= $Page->pjeseAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseAutori" class="el_pjese_kembimi_pjeseAutori">
<span<?= $Page->pjeseAutori->viewAttributes() ?>>
<?= $Page->pjeseAutori->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseShtuar->Visible) { // pjeseShtuar ?>
        <td data-name="pjeseShtuar"<?= $Page->pjeseShtuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseShtuar" class="el_pjese_kembimi_pjeseShtuar">
<span<?= $Page->pjeseShtuar->viewAttributes() ?>>
<?= $Page->pjeseShtuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->pjeseModifikuar->Visible) { // pjeseModifikuar ?>
        <td data-name="pjeseModifikuar"<?= $Page->pjeseModifikuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseModifikuar" class="el_pjese_kembimi_pjeseModifikuar">
<span<?= $Page->pjeseModifikuar->viewAttributes() ?>>
<?= $Page->pjeseModifikuar->getViewValue() ?></span>
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
    ew.addEventHandlers("pjese_kembimi");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $("a[class='btn btn-default ew-add-edit ew-add']").append(' SHTO PJESE KEMBIMI ');
});
</script>
<?php } ?>
