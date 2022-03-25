<?php

namespace PHPMaker2022\volalservice;

// Page object
$IndexTipsList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { index_tips: currentTable } });
var currentForm, currentPageID;
var findex_tipslist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    findex_tipslist = new ew.Form("findex_tipslist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = findex_tipslist;
    findex_tipslist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("findex_tipslist");
});
var findex_tipssrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    findex_tipssrch = new ew.Form("findex_tipssrch", "list");
    currentSearchForm = findex_tipssrch;

    // Dynamic selection lists

    // Filters
    findex_tipssrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    findex_tipssrch.initSearchPanel = true;
    loadjs.done("findex_tipssrch");
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
<form name="findex_tipssrch" id="findex_tipssrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="findex_tipssrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="index_tips">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="findex_tipssrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="findex_tipssrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="findex_tipssrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="findex_tipssrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> index_tips">
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
<form name="findex_tipslist" id="findex_tipslist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="index_tips">
<div id="gmp_index_tips" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_index_tipslist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->iTipsID->Visible) { // iTipsID ?>
        <th data-name="iTipsID" class="<?= $Page->iTipsID->headerCellClass() ?>"><div id="elh_index_tips_iTipsID" class="index_tips_iTipsID"><?= $Page->renderFieldHeader($Page->iTipsID) ?></div></th>
<?php } ?>
<?php if ($Page->iTipsTeksti->Visible) { // iTipsTeksti ?>
        <th data-name="iTipsTeksti" class="<?= $Page->iTipsTeksti->headerCellClass() ?>"><div id="elh_index_tips_iTipsTeksti" class="index_tips_iTipsTeksti"><?= $Page->renderFieldHeader($Page->iTipsTeksti) ?></div></th>
<?php } ?>
<?php if ($Page->iTipsAutori->Visible) { // iTipsAutori ?>
        <th data-name="iTipsAutori" class="<?= $Page->iTipsAutori->headerCellClass() ?>"><div id="elh_index_tips_iTipsAutori" class="index_tips_iTipsAutori"><?= $Page->renderFieldHeader($Page->iTipsAutori) ?></div></th>
<?php } ?>
<?php if ($Page->iTipsKrijuar->Visible) { // iTipsKrijuar ?>
        <th data-name="iTipsKrijuar" class="<?= $Page->iTipsKrijuar->headerCellClass() ?>"><div id="elh_index_tips_iTipsKrijuar" class="index_tips_iTipsKrijuar"><?= $Page->renderFieldHeader($Page->iTipsKrijuar) ?></div></th>
<?php } ?>
<?php if ($Page->iTipsAzhornuar->Visible) { // iTipsAzhornuar ?>
        <th data-name="iTipsAzhornuar" class="<?= $Page->iTipsAzhornuar->headerCellClass() ?>"><div id="elh_index_tips_iTipsAzhornuar" class="index_tips_iTipsAzhornuar"><?= $Page->renderFieldHeader($Page->iTipsAzhornuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_index_tips",
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
    <?php if ($Page->iTipsID->Visible) { // iTipsID ?>
        <td data-name="iTipsID"<?= $Page->iTipsID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsID" class="el_index_tips_iTipsID">
<span<?= $Page->iTipsID->viewAttributes() ?>>
<?= $Page->iTipsID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iTipsTeksti->Visible) { // iTipsTeksti ?>
        <td data-name="iTipsTeksti"<?= $Page->iTipsTeksti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsTeksti" class="el_index_tips_iTipsTeksti">
<span<?= $Page->iTipsTeksti->viewAttributes() ?>>
<?= $Page->iTipsTeksti->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iTipsAutori->Visible) { // iTipsAutori ?>
        <td data-name="iTipsAutori"<?= $Page->iTipsAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsAutori" class="el_index_tips_iTipsAutori">
<span<?= $Page->iTipsAutori->viewAttributes() ?>>
<?= $Page->iTipsAutori->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iTipsKrijuar->Visible) { // iTipsKrijuar ?>
        <td data-name="iTipsKrijuar"<?= $Page->iTipsKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsKrijuar" class="el_index_tips_iTipsKrijuar">
<span<?= $Page->iTipsKrijuar->viewAttributes() ?>>
<?= $Page->iTipsKrijuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iTipsAzhornuar->Visible) { // iTipsAzhornuar ?>
        <td data-name="iTipsAzhornuar"<?= $Page->iTipsAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsAzhornuar" class="el_index_tips_iTipsAzhornuar">
<span<?= $Page->iTipsAzhornuar->viewAttributes() ?>>
<?= $Page->iTipsAzhornuar->getViewValue() ?></span>
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
    ew.addEventHandlers("index_tips");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
