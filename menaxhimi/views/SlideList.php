<?php

namespace PHPMaker2022\volalservice;

// Page object
$SlideList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { slide: currentTable } });
var currentForm, currentPageID;
var fslidelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fslidelist = new ew.Form("fslidelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fslidelist;
    fslidelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fslidelist");
});
var fslidesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fslidesrch = new ew.Form("fslidesrch", "list");
    currentSearchForm = fslidesrch;

    // Dynamic selection lists

    // Filters
    fslidesrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fslidesrch.initSearchPanel = true;
    loadjs.done("fslidesrch");
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
<form name="fslidesrch" id="fslidesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fslidesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="slide">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fslidesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fslidesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fslidesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fslidesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> slide">
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
<form name="fslidelist" id="fslidelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="slide">
<div id="gmp_slide" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_slidelist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->slideID->Visible) { // slideID ?>
        <th data-name="slideID" class="<?= $Page->slideID->headerCellClass() ?>"><div id="elh_slide_slideID" class="slide_slideID"><?= $Page->renderFieldHeader($Page->slideID) ?></div></th>
<?php } ?>
<?php if ($Page->slideGjuha->Visible) { // slideGjuha ?>
        <th data-name="slideGjuha" class="<?= $Page->slideGjuha->headerCellClass() ?>"><div id="elh_slide_slideGjuha" class="slide_slideGjuha"><?= $Page->renderFieldHeader($Page->slideGjuha) ?></div></th>
<?php } ?>
<?php if ($Page->slideFoto->Visible) { // slideFoto ?>
        <th data-name="slideFoto" class="<?= $Page->slideFoto->headerCellClass() ?>"><div id="elh_slide_slideFoto" class="slide_slideFoto"><?= $Page->renderFieldHeader($Page->slideFoto) ?></div></th>
<?php } ?>
<?php if ($Page->slideTxt1->Visible) { // slideTxt1 ?>
        <th data-name="slideTxt1" class="<?= $Page->slideTxt1->headerCellClass() ?>"><div id="elh_slide_slideTxt1" class="slide_slideTxt1"><?= $Page->renderFieldHeader($Page->slideTxt1) ?></div></th>
<?php } ?>
<?php if ($Page->slideTxt2->Visible) { // slideTxt2 ?>
        <th data-name="slideTxt2" class="<?= $Page->slideTxt2->headerCellClass() ?>"><div id="elh_slide_slideTxt2" class="slide_slideTxt2"><?= $Page->renderFieldHeader($Page->slideTxt2) ?></div></th>
<?php } ?>
<?php if ($Page->slideButonTxt->Visible) { // slideButonTxt ?>
        <th data-name="slideButonTxt" class="<?= $Page->slideButonTxt->headerCellClass() ?>"><div id="elh_slide_slideButonTxt" class="slide_slideButonTxt"><?= $Page->renderFieldHeader($Page->slideButonTxt) ?></div></th>
<?php } ?>
<?php if ($Page->slideRadha->Visible) { // slideRadha ?>
        <th data-name="slideRadha" class="<?= $Page->slideRadha->headerCellClass() ?>"><div id="elh_slide_slideRadha" class="slide_slideRadha"><?= $Page->renderFieldHeader($Page->slideRadha) ?></div></th>
<?php } ?>
<?php if ($Page->slideAktiv->Visible) { // slideAktiv ?>
        <th data-name="slideAktiv" class="<?= $Page->slideAktiv->headerCellClass() ?>"><div id="elh_slide_slideAktiv" class="slide_slideAktiv"><?= $Page->renderFieldHeader($Page->slideAktiv) ?></div></th>
<?php } ?>
<?php if ($Page->slideAutori->Visible) { // slideAutori ?>
        <th data-name="slideAutori" class="<?= $Page->slideAutori->headerCellClass() ?>"><div id="elh_slide_slideAutori" class="slide_slideAutori"><?= $Page->renderFieldHeader($Page->slideAutori) ?></div></th>
<?php } ?>
<?php if ($Page->slideKrijuar->Visible) { // slideKrijuar ?>
        <th data-name="slideKrijuar" class="<?= $Page->slideKrijuar->headerCellClass() ?>"><div id="elh_slide_slideKrijuar" class="slide_slideKrijuar"><?= $Page->renderFieldHeader($Page->slideKrijuar) ?></div></th>
<?php } ?>
<?php if ($Page->slideAzhornuar->Visible) { // slideAzhornuar ?>
        <th data-name="slideAzhornuar" class="<?= $Page->slideAzhornuar->headerCellClass() ?>"><div id="elh_slide_slideAzhornuar" class="slide_slideAzhornuar"><?= $Page->renderFieldHeader($Page->slideAzhornuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_slide",
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
    <?php if ($Page->slideID->Visible) { // slideID ?>
        <td data-name="slideID"<?= $Page->slideID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideID" class="el_slide_slideID">
<span<?= $Page->slideID->viewAttributes() ?>>
<?= $Page->slideID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideGjuha->Visible) { // slideGjuha ?>
        <td data-name="slideGjuha"<?= $Page->slideGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideGjuha" class="el_slide_slideGjuha">
<span<?= $Page->slideGjuha->viewAttributes() ?>>
<?= $Page->slideGjuha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideFoto->Visible) { // slideFoto ?>
        <td data-name="slideFoto"<?= $Page->slideFoto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideFoto" class="el_slide_slideFoto">
<span>
<?= GetFileViewTag($Page->slideFoto, $Page->slideFoto->getViewValue(), false) ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideTxt1->Visible) { // slideTxt1 ?>
        <td data-name="slideTxt1"<?= $Page->slideTxt1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideTxt1" class="el_slide_slideTxt1">
<span<?= $Page->slideTxt1->viewAttributes() ?>>
<?= $Page->slideTxt1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideTxt2->Visible) { // slideTxt2 ?>
        <td data-name="slideTxt2"<?= $Page->slideTxt2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideTxt2" class="el_slide_slideTxt2">
<span<?= $Page->slideTxt2->viewAttributes() ?>>
<?= $Page->slideTxt2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideButonTxt->Visible) { // slideButonTxt ?>
        <td data-name="slideButonTxt"<?= $Page->slideButonTxt->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideButonTxt" class="el_slide_slideButonTxt">
<span<?= $Page->slideButonTxt->viewAttributes() ?>>
<?php if (!EmptyString($Page->slideButonTxt->getViewValue()) && $Page->slideButonTxt->linkAttributes() != "") { ?>
<a<?= $Page->slideButonTxt->linkAttributes() ?>><?= $Page->slideButonTxt->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->slideButonTxt->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideRadha->Visible) { // slideRadha ?>
        <td data-name="slideRadha"<?= $Page->slideRadha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideRadha" class="el_slide_slideRadha">
<span<?= $Page->slideRadha->viewAttributes() ?>>
<?= $Page->slideRadha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideAktiv->Visible) { // slideAktiv ?>
        <td data-name="slideAktiv"<?= $Page->slideAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideAktiv" class="el_slide_slideAktiv">
<span<?= $Page->slideAktiv->viewAttributes() ?>>
<?= $Page->slideAktiv->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideAutori->Visible) { // slideAutori ?>
        <td data-name="slideAutori"<?= $Page->slideAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideAutori" class="el_slide_slideAutori">
<span<?= $Page->slideAutori->viewAttributes() ?>>
<?= $Page->slideAutori->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideKrijuar->Visible) { // slideKrijuar ?>
        <td data-name="slideKrijuar"<?= $Page->slideKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideKrijuar" class="el_slide_slideKrijuar">
<span<?= $Page->slideKrijuar->viewAttributes() ?>>
<?= $Page->slideKrijuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->slideAzhornuar->Visible) { // slideAzhornuar ?>
        <td data-name="slideAzhornuar"<?= $Page->slideAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideAzhornuar" class="el_slide_slideAzhornuar">
<span<?= $Page->slideAzhornuar->viewAttributes() ?>>
<?= $Page->slideAzhornuar->getViewValue() ?></span>
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
    ew.addEventHandlers("slide");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
