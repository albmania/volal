<?php

namespace PHPMaker2022\volalservice;

// Page object
$FaqeList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { faqe: currentTable } });
var currentForm, currentPageID;
var ffaqelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    ffaqelist = new ew.Form("ffaqelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = ffaqelist;
    ffaqelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("ffaqelist");
});
var ffaqesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    ffaqesrch = new ew.Form("ffaqesrch", "list");
    currentSearchForm = ffaqesrch;

    // Dynamic selection lists

    // Filters
    ffaqesrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    ffaqesrch.initSearchPanel = true;
    loadjs.done("ffaqesrch");
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
<form name="ffaqesrch" id="ffaqesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="ffaqesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="faqe">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="ffaqesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="ffaqesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="ffaqesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="ffaqesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> faqe">
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
<form name="ffaqelist" id="ffaqelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="faqe">
<div id="gmp_faqe" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_faqelist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->faqeID->Visible) { // faqeID ?>
        <th data-name="faqeID" class="<?= $Page->faqeID->headerCellClass() ?>"><div id="elh_faqe_faqeID" class="faqe_faqeID"><?= $Page->renderFieldHeader($Page->faqeID) ?></div></th>
<?php } ?>
<?php if ($Page->faqeEmri_sq->Visible) { // faqeEmri_sq ?>
        <th data-name="faqeEmri_sq" class="<?= $Page->faqeEmri_sq->headerCellClass() ?>"><div id="elh_faqe_faqeEmri_sq" class="faqe_faqeEmri_sq"><?= $Page->renderFieldHeader($Page->faqeEmri_sq) ?></div></th>
<?php } ?>
<?php if ($Page->faqeEmri_en->Visible) { // faqeEmri_en ?>
        <th data-name="faqeEmri_en" class="<?= $Page->faqeEmri_en->headerCellClass() ?>"><div id="elh_faqe_faqeEmri_en" class="faqe_faqeEmri_en"><?= $Page->renderFieldHeader($Page->faqeEmri_en) ?></div></th>
<?php } ?>
<?php if ($Page->faqeAutori->Visible) { // faqeAutori ?>
        <th data-name="faqeAutori" class="<?= $Page->faqeAutori->headerCellClass() ?>"><div id="elh_faqe_faqeAutori" class="faqe_faqeAutori"><?= $Page->renderFieldHeader($Page->faqeAutori) ?></div></th>
<?php } ?>
<?php if ($Page->faqeKrijuar->Visible) { // faqeKrijuar ?>
        <th data-name="faqeKrijuar" class="<?= $Page->faqeKrijuar->headerCellClass() ?>"><div id="elh_faqe_faqeKrijuar" class="faqe_faqeKrijuar"><?= $Page->renderFieldHeader($Page->faqeKrijuar) ?></div></th>
<?php } ?>
<?php if ($Page->faqeAzhornuar->Visible) { // faqeAzhornuar ?>
        <th data-name="faqeAzhornuar" class="<?= $Page->faqeAzhornuar->headerCellClass() ?>"><div id="elh_faqe_faqeAzhornuar" class="faqe_faqeAzhornuar"><?= $Page->renderFieldHeader($Page->faqeAzhornuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_faqe",
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
    <?php if ($Page->faqeID->Visible) { // faqeID ?>
        <td data-name="faqeID"<?= $Page->faqeID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeID" class="el_faqe_faqeID">
<span<?= $Page->faqeID->viewAttributes() ?>>
<?= $Page->faqeID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->faqeEmri_sq->Visible) { // faqeEmri_sq ?>
        <td data-name="faqeEmri_sq"<?= $Page->faqeEmri_sq->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeEmri_sq" class="el_faqe_faqeEmri_sq">
<span<?= $Page->faqeEmri_sq->viewAttributes() ?>>
<?= $Page->faqeEmri_sq->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->faqeEmri_en->Visible) { // faqeEmri_en ?>
        <td data-name="faqeEmri_en"<?= $Page->faqeEmri_en->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeEmri_en" class="el_faqe_faqeEmri_en">
<span<?= $Page->faqeEmri_en->viewAttributes() ?>>
<?= $Page->faqeEmri_en->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->faqeAutori->Visible) { // faqeAutori ?>
        <td data-name="faqeAutori"<?= $Page->faqeAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeAutori" class="el_faqe_faqeAutori">
<span<?= $Page->faqeAutori->viewAttributes() ?>>
<?= $Page->faqeAutori->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->faqeKrijuar->Visible) { // faqeKrijuar ?>
        <td data-name="faqeKrijuar"<?= $Page->faqeKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeKrijuar" class="el_faqe_faqeKrijuar">
<span<?= $Page->faqeKrijuar->viewAttributes() ?>>
<?= $Page->faqeKrijuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->faqeAzhornuar->Visible) { // faqeAzhornuar ?>
        <td data-name="faqeAzhornuar"<?= $Page->faqeAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeAzhornuar" class="el_faqe_faqeAzhornuar">
<span<?= $Page->faqeAzhornuar->viewAttributes() ?>>
<?= $Page->faqeAzhornuar->getViewValue() ?></span>
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
    ew.addEventHandlers("faqe");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
