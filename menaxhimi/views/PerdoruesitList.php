<?php

namespace PHPMaker2022\volalservice;

// Page object
$PerdoruesitList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { perdoruesit: currentTable } });
var currentForm, currentPageID;
var fperdoruesitlist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fperdoruesitlist = new ew.Form("fperdoruesitlist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fperdoruesitlist;
    fperdoruesitlist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fperdoruesitlist");
});
var fperdoruesitsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fperdoruesitsrch = new ew.Form("fperdoruesitsrch", "list");
    currentSearchForm = fperdoruesitsrch;

    // Dynamic selection lists

    // Filters
    fperdoruesitsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fperdoruesitsrch.initSearchPanel = true;
    loadjs.done("fperdoruesitsrch");
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
<form name="fperdoruesitsrch" id="fperdoruesitsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fperdoruesitsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="perdoruesit">
<div class="ew-extended-search container-fluid">
<div class="row mb-0">
    <div class="col-sm-auto px-0 pe-sm-2">
        <div class="ew-basic-search input-group">
            <input type="search" name="<?= Config("TABLE_BASIC_SEARCH") ?>" id="<?= Config("TABLE_BASIC_SEARCH") ?>" class="form-control ew-basic-search-keyword" value="<?= HtmlEncode($Page->BasicSearch->getKeyword()) ?>" placeholder="<?= HtmlEncode($Language->phrase("Search")) ?>" aria-label="<?= HtmlEncode($Language->phrase("Search")) ?>">
            <input type="hidden" name="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" id="<?= Config("TABLE_BASIC_SEARCH_TYPE") ?>" class="ew-basic-search-type" value="<?= HtmlEncode($Page->BasicSearch->getType()) ?>">
            <button type="button" data-bs-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" aria-haspopup="true" aria-expanded="false">
                <span id="searchtype"><?= $Page->BasicSearch->getTypeNameShort() ?></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fperdoruesitsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fperdoruesitsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fperdoruesitsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fperdoruesitsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> perdoruesit">
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
<form name="fperdoruesitlist" id="fperdoruesitlist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="perdoruesit">
<div id="gmp_perdoruesit" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_perdoruesitlist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->perdID->Visible) { // perdID ?>
        <th data-name="perdID" class="<?= $Page->perdID->headerCellClass() ?>"><div id="elh_perdoruesit_perdID" class="perdoruesit_perdID"><?= $Page->renderFieldHeader($Page->perdID) ?></div></th>
<?php } ?>
<?php if ($Page->perdEmri->Visible) { // perdEmri ?>
        <th data-name="perdEmri" class="<?= $Page->perdEmri->headerCellClass() ?>"><div id="elh_perdoruesit_perdEmri" class="perdoruesit_perdEmri"><?= $Page->renderFieldHeader($Page->perdEmri) ?></div></th>
<?php } ?>
<?php if ($Page->perdUsername->Visible) { // perdUsername ?>
        <th data-name="perdUsername" class="<?= $Page->perdUsername->headerCellClass() ?>"><div id="elh_perdoruesit_perdUsername" class="perdoruesit_perdUsername"><?= $Page->renderFieldHeader($Page->perdUsername) ?></div></th>
<?php } ?>
<?php if ($Page->perdFjalekalimi->Visible) { // perdFjalekalimi ?>
        <th data-name="perdFjalekalimi" class="<?= $Page->perdFjalekalimi->headerCellClass() ?>"><div id="elh_perdoruesit_perdFjalekalimi" class="perdoruesit_perdFjalekalimi"><?= $Page->renderFieldHeader($Page->perdFjalekalimi) ?></div></th>
<?php } ?>
<?php if ($Page->perdEmail->Visible) { // perdEmail ?>
        <th data-name="perdEmail" class="<?= $Page->perdEmail->headerCellClass() ?>"><div id="elh_perdoruesit_perdEmail" class="perdoruesit_perdEmail"><?= $Page->renderFieldHeader($Page->perdEmail) ?></div></th>
<?php } ?>
<?php if ($Page->perdNiveliPerdoruesit->Visible) { // perdNiveliPerdoruesit ?>
        <th data-name="perdNiveliPerdoruesit" class="<?= $Page->perdNiveliPerdoruesit->headerCellClass() ?>"><div id="elh_perdoruesit_perdNiveliPerdoruesit" class="perdoruesit_perdNiveliPerdoruesit"><?= $Page->renderFieldHeader($Page->perdNiveliPerdoruesit) ?></div></th>
<?php } ?>
<?php if ($Page->perdDtReg->Visible) { // perdDtReg ?>
        <th data-name="perdDtReg" class="<?= $Page->perdDtReg->headerCellClass() ?>"><div id="elh_perdoruesit_perdDtReg" class="perdoruesit_perdDtReg"><?= $Page->renderFieldHeader($Page->perdDtReg) ?></div></th>
<?php } ?>
<?php if ($Page->perdActivated->Visible) { // perdActivated ?>
        <th data-name="perdActivated" class="<?= $Page->perdActivated->headerCellClass() ?>"><div id="elh_perdoruesit_perdActivated" class="perdoruesit_perdActivated"><?= $Page->renderFieldHeader($Page->perdActivated) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_perdoruesit",
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
    <?php if ($Page->perdID->Visible) { // perdID ?>
        <td data-name="perdID"<?= $Page->perdID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdID" class="el_perdoruesit_perdID">
<span<?= $Page->perdID->viewAttributes() ?>>
<?= $Page->perdID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->perdEmri->Visible) { // perdEmri ?>
        <td data-name="perdEmri"<?= $Page->perdEmri->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdEmri" class="el_perdoruesit_perdEmri">
<span<?= $Page->perdEmri->viewAttributes() ?>>
<?= $Page->perdEmri->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->perdUsername->Visible) { // perdUsername ?>
        <td data-name="perdUsername"<?= $Page->perdUsername->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdUsername" class="el_perdoruesit_perdUsername">
<span<?= $Page->perdUsername->viewAttributes() ?>>
<?= $Page->perdUsername->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->perdFjalekalimi->Visible) { // perdFjalekalimi ?>
        <td data-name="perdFjalekalimi"<?= $Page->perdFjalekalimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdFjalekalimi" class="el_perdoruesit_perdFjalekalimi">
<span<?= $Page->perdFjalekalimi->viewAttributes() ?>>
<?= $Page->perdFjalekalimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->perdEmail->Visible) { // perdEmail ?>
        <td data-name="perdEmail"<?= $Page->perdEmail->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdEmail" class="el_perdoruesit_perdEmail">
<span<?= $Page->perdEmail->viewAttributes() ?>>
<?= $Page->perdEmail->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->perdNiveliPerdoruesit->Visible) { // perdNiveliPerdoruesit ?>
        <td data-name="perdNiveliPerdoruesit"<?= $Page->perdNiveliPerdoruesit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdNiveliPerdoruesit" class="el_perdoruesit_perdNiveliPerdoruesit">
<span<?= $Page->perdNiveliPerdoruesit->viewAttributes() ?>>
<?= $Page->perdNiveliPerdoruesit->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->perdDtReg->Visible) { // perdDtReg ?>
        <td data-name="perdDtReg"<?= $Page->perdDtReg->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdDtReg" class="el_perdoruesit_perdDtReg">
<span<?= $Page->perdDtReg->viewAttributes() ?>>
<?= $Page->perdDtReg->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->perdActivated->Visible) { // perdActivated ?>
        <td data-name="perdActivated"<?= $Page->perdActivated->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdActivated" class="el_perdoruesit_perdActivated">
<span<?= $Page->perdActivated->viewAttributes() ?>>
<?= $Page->perdActivated->getViewValue() ?></span>
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
    ew.addEventHandlers("perdoruesit");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
