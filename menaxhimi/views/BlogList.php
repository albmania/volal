<?php

namespace PHPMaker2022\volalservice;

// Page object
$BlogList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { blog: currentTable } });
var currentForm, currentPageID;
var fbloglist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fbloglist = new ew.Form("fbloglist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fbloglist;
    fbloglist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fbloglist");
});
var fblogsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fblogsrch = new ew.Form("fblogsrch", "list");
    currentSearchForm = fblogsrch;

    // Dynamic selection lists

    // Filters
    fblogsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fblogsrch.initSearchPanel = true;
    loadjs.done("fblogsrch");
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
<form name="fblogsrch" id="fblogsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fblogsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="blog">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fblogsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fblogsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fblogsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fblogsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> blog">
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
<form name="fbloglist" id="fbloglist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog">
<div id="gmp_blog" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_bloglist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->blogID->Visible) { // blogID ?>
        <th data-name="blogID" class="<?= $Page->blogID->headerCellClass() ?>"><div id="elh_blog_blogID" class="blog_blogID"><?= $Page->renderFieldHeader($Page->blogID) ?></div></th>
<?php } ?>
<?php if ($Page->blogGjuha->Visible) { // blogGjuha ?>
        <th data-name="blogGjuha" class="<?= $Page->blogGjuha->headerCellClass() ?>"><div id="elh_blog_blogGjuha" class="blog_blogGjuha"><?= $Page->renderFieldHeader($Page->blogGjuha) ?></div></th>
<?php } ?>
<?php if ($Page->blogKategoria->Visible) { // blogKategoria ?>
        <th data-name="blogKategoria" class="<?= $Page->blogKategoria->headerCellClass() ?>"><div id="elh_blog_blogKategoria" class="blog_blogKategoria"><?= $Page->renderFieldHeader($Page->blogKategoria) ?></div></th>
<?php } ?>
<?php if ($Page->blogTitulli->Visible) { // blogTitulli ?>
        <th data-name="blogTitulli" class="<?= $Page->blogTitulli->headerCellClass() ?>"><div id="elh_blog_blogTitulli" class="blog_blogTitulli"><?= $Page->renderFieldHeader($Page->blogTitulli) ?></div></th>
<?php } ?>
<?php if ($Page->blogDtPublik->Visible) { // blogDtPublik ?>
        <th data-name="blogDtPublik" class="<?= $Page->blogDtPublik->headerCellClass() ?>"><div id="elh_blog_blogDtPublik" class="blog_blogDtPublik"><?= $Page->renderFieldHeader($Page->blogDtPublik) ?></div></th>
<?php } ?>
<?php if ($Page->blogAutori->Visible) { // blogAutori ?>
        <th data-name="blogAutori" class="<?= $Page->blogAutori->headerCellClass() ?>"><div id="elh_blog_blogAutori" class="blog_blogAutori"><?= $Page->renderFieldHeader($Page->blogAutori) ?></div></th>
<?php } ?>
<?php if ($Page->blogShtuar->Visible) { // blogShtuar ?>
        <th data-name="blogShtuar" class="<?= $Page->blogShtuar->headerCellClass() ?>"><div id="elh_blog_blogShtuar" class="blog_blogShtuar"><?= $Page->renderFieldHeader($Page->blogShtuar) ?></div></th>
<?php } ?>
<?php if ($Page->blogModifikuar->Visible) { // blogModifikuar ?>
        <th data-name="blogModifikuar" class="<?= $Page->blogModifikuar->headerCellClass() ?>"><div id="elh_blog_blogModifikuar" class="blog_blogModifikuar"><?= $Page->renderFieldHeader($Page->blogModifikuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_blog",
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
    <?php if ($Page->blogID->Visible) { // blogID ?>
        <td data-name="blogID"<?= $Page->blogID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogID" class="el_blog_blogID">
<span<?= $Page->blogID->viewAttributes() ?>>
<?= $Page->blogID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->blogGjuha->Visible) { // blogGjuha ?>
        <td data-name="blogGjuha"<?= $Page->blogGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogGjuha" class="el_blog_blogGjuha">
<span<?= $Page->blogGjuha->viewAttributes() ?>>
<?= $Page->blogGjuha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->blogKategoria->Visible) { // blogKategoria ?>
        <td data-name="blogKategoria"<?= $Page->blogKategoria->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogKategoria" class="el_blog_blogKategoria">
<span<?= $Page->blogKategoria->viewAttributes() ?>>
<?= $Page->blogKategoria->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->blogTitulli->Visible) { // blogTitulli ?>
        <td data-name="blogTitulli"<?= $Page->blogTitulli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogTitulli" class="el_blog_blogTitulli">
<span<?= $Page->blogTitulli->viewAttributes() ?>>
<?= $Page->blogTitulli->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->blogDtPublik->Visible) { // blogDtPublik ?>
        <td data-name="blogDtPublik"<?= $Page->blogDtPublik->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogDtPublik" class="el_blog_blogDtPublik">
<span<?= $Page->blogDtPublik->viewAttributes() ?>>
<?= $Page->blogDtPublik->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->blogAutori->Visible) { // blogAutori ?>
        <td data-name="blogAutori"<?= $Page->blogAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogAutori" class="el_blog_blogAutori">
<span<?= $Page->blogAutori->viewAttributes() ?>>
<?= $Page->blogAutori->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->blogShtuar->Visible) { // blogShtuar ?>
        <td data-name="blogShtuar"<?= $Page->blogShtuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogShtuar" class="el_blog_blogShtuar">
<span<?= $Page->blogShtuar->viewAttributes() ?>>
<?= $Page->blogShtuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->blogModifikuar->Visible) { // blogModifikuar ?>
        <td data-name="blogModifikuar"<?= $Page->blogModifikuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogModifikuar" class="el_blog_blogModifikuar">
<span<?= $Page->blogModifikuar->viewAttributes() ?>>
<?= $Page->blogModifikuar->getViewValue() ?></span>
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
    ew.addEventHandlers("blog");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
