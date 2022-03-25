<?php

namespace PHPMaker2022\volalservice;

// Page object
$SherbimeList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { sherbime: currentTable } });
var currentForm, currentPageID;
var fsherbimelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsherbimelist = new ew.Form("fsherbimelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fsherbimelist;
    fsherbimelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fsherbimelist");
});
var fsherbimesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fsherbimesrch = new ew.Form("fsherbimesrch", "list");
    currentSearchForm = fsherbimesrch;

    // Dynamic selection lists

    // Filters
    fsherbimesrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fsherbimesrch.initSearchPanel = true;
    loadjs.done("fsherbimesrch");
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
<form name="fsherbimesrch" id="fsherbimesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fsherbimesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="sherbime">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fsherbimesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fsherbimesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fsherbimesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fsherbimesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> sherbime">
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
<form name="fsherbimelist" id="fsherbimelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="sherbime">
<div id="gmp_sherbime" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_sherbimelist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->sherbimeID->Visible) { // sherbimeID ?>
        <th data-name="sherbimeID" class="<?= $Page->sherbimeID->headerCellClass() ?>"><div id="elh_sherbime_sherbimeID" class="sherbime_sherbimeID"><?= $Page->renderFieldHeader($Page->sherbimeID) ?></div></th>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_sq->Visible) { // sherbimeEmertimi_sq ?>
        <th data-name="sherbimeEmertimi_sq" class="<?= $Page->sherbimeEmertimi_sq->headerCellClass() ?>"><div id="elh_sherbime_sherbimeEmertimi_sq" class="sherbime_sherbimeEmertimi_sq"><?= $Page->renderFieldHeader($Page->sherbimeEmertimi_sq) ?></div></th>
<?php } ?>
<?php if ($Page->sherbimeTxt_sq->Visible) { // sherbimeTxt_sq ?>
        <th data-name="sherbimeTxt_sq" class="<?= $Page->sherbimeTxt_sq->headerCellClass() ?>"><div id="elh_sherbime_sherbimeTxt_sq" class="sherbime_sherbimeTxt_sq"><?= $Page->renderFieldHeader($Page->sherbimeTxt_sq) ?></div></th>
<?php } ?>
<?php if ($Page->sherbimeCmimi->Visible) { // sherbimeCmimi ?>
        <th data-name="sherbimeCmimi" class="<?= $Page->sherbimeCmimi->headerCellClass() ?>"><div id="elh_sherbime_sherbimeCmimi" class="sherbime_sherbimeCmimi"><?= $Page->renderFieldHeader($Page->sherbimeCmimi) ?></div></th>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_en->Visible) { // sherbimeEmertimi_en ?>
        <th data-name="sherbimeEmertimi_en" class="<?= $Page->sherbimeEmertimi_en->headerCellClass() ?>"><div id="elh_sherbime_sherbimeEmertimi_en" class="sherbime_sherbimeEmertimi_en"><?= $Page->renderFieldHeader($Page->sherbimeEmertimi_en) ?></div></th>
<?php } ?>
<?php if ($Page->sherbimeTxt_en->Visible) { // sherbimeTxt_en ?>
        <th data-name="sherbimeTxt_en" class="<?= $Page->sherbimeTxt_en->headerCellClass() ?>"><div id="elh_sherbime_sherbimeTxt_en" class="sherbime_sherbimeTxt_en"><?= $Page->renderFieldHeader($Page->sherbimeTxt_en) ?></div></th>
<?php } ?>
<?php if ($Page->sherbimeFoto->Visible) { // sherbimeFoto ?>
        <th data-name="sherbimeFoto" class="<?= $Page->sherbimeFoto->headerCellClass() ?>"><div id="elh_sherbime_sherbimeFoto" class="sherbime_sherbimeFoto"><?= $Page->renderFieldHeader($Page->sherbimeFoto) ?></div></th>
<?php } ?>
<?php if ($Page->sherbimeIndex->Visible) { // sherbimeIndex ?>
        <th data-name="sherbimeIndex" class="<?= $Page->sherbimeIndex->headerCellClass() ?>"><div id="elh_sherbime_sherbimeIndex" class="sherbime_sherbimeIndex"><?= $Page->renderFieldHeader($Page->sherbimeIndex) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_sherbime",
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
    <?php if ($Page->sherbimeID->Visible) { // sherbimeID ?>
        <td data-name="sherbimeID"<?= $Page->sherbimeID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeID" class="el_sherbime_sherbimeID">
<span<?= $Page->sherbimeID->viewAttributes() ?>>
<?= $Page->sherbimeID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sherbimeEmertimi_sq->Visible) { // sherbimeEmertimi_sq ?>
        <td data-name="sherbimeEmertimi_sq"<?= $Page->sherbimeEmertimi_sq->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeEmertimi_sq" class="el_sherbime_sherbimeEmertimi_sq">
<span<?= $Page->sherbimeEmertimi_sq->viewAttributes() ?>>
<?= $Page->sherbimeEmertimi_sq->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sherbimeTxt_sq->Visible) { // sherbimeTxt_sq ?>
        <td data-name="sherbimeTxt_sq"<?= $Page->sherbimeTxt_sq->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeTxt_sq" class="el_sherbime_sherbimeTxt_sq">
<span<?= $Page->sherbimeTxt_sq->viewAttributes() ?>>
<?= $Page->sherbimeTxt_sq->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sherbimeCmimi->Visible) { // sherbimeCmimi ?>
        <td data-name="sherbimeCmimi"<?= $Page->sherbimeCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeCmimi" class="el_sherbime_sherbimeCmimi">
<span<?= $Page->sherbimeCmimi->viewAttributes() ?>>
<?= $Page->sherbimeCmimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sherbimeEmertimi_en->Visible) { // sherbimeEmertimi_en ?>
        <td data-name="sherbimeEmertimi_en"<?= $Page->sherbimeEmertimi_en->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeEmertimi_en" class="el_sherbime_sherbimeEmertimi_en">
<span<?= $Page->sherbimeEmertimi_en->viewAttributes() ?>>
<?= $Page->sherbimeEmertimi_en->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sherbimeTxt_en->Visible) { // sherbimeTxt_en ?>
        <td data-name="sherbimeTxt_en"<?= $Page->sherbimeTxt_en->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeTxt_en" class="el_sherbime_sherbimeTxt_en">
<span<?= $Page->sherbimeTxt_en->viewAttributes() ?>>
<?= $Page->sherbimeTxt_en->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sherbimeFoto->Visible) { // sherbimeFoto ?>
        <td data-name="sherbimeFoto"<?= $Page->sherbimeFoto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeFoto" class="el_sherbime_sherbimeFoto">
<span>
<?= GetFileViewTag($Page->sherbimeFoto, $Page->sherbimeFoto->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->sherbimeIndex->Visible) { // sherbimeIndex ?>
        <td data-name="sherbimeIndex"<?= $Page->sherbimeIndex->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeIndex" class="el_sherbime_sherbimeIndex">
<span<?= $Page->sherbimeIndex->viewAttributes() ?>>
<?= $Page->sherbimeIndex->getViewValue() ?></span>
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
    ew.addEventHandlers("sherbime");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $("a[class='btn btn-default ew-add-edit ew-add']").append(' SHTO SHERBIM ');
});
</script>
<?php } ?>
