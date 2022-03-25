<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuDytesoreList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_dytesore: currentTable } });
var currentForm, currentPageID;
var fmenu_dytesorelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_dytesorelist = new ew.Form("fmenu_dytesorelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fmenu_dytesorelist;
    fmenu_dytesorelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fmenu_dytesorelist");
});
var fmenu_dytesoresrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fmenu_dytesoresrch = new ew.Form("fmenu_dytesoresrch", "list");
    currentSearchForm = fmenu_dytesoresrch;

    // Dynamic selection lists

    // Filters
    fmenu_dytesoresrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fmenu_dytesoresrch.initSearchPanel = true;
    loadjs.done("fmenu_dytesoresrch");
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
<form name="fmenu_dytesoresrch" id="fmenu_dytesoresrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fmenu_dytesoresrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="menu_dytesore">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fmenu_dytesoresrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fmenu_dytesoresrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fmenu_dytesoresrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fmenu_dytesoresrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> menu_dytesore">
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
<form name="fmenu_dytesorelist" id="fmenu_dytesorelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="menu_dytesore">
<div id="gmp_menu_dytesore" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_menu_dytesorelist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->menudID->Visible) { // menudID ?>
        <th data-name="menudID" class="<?= $Page->menudID->headerCellClass() ?>"><div id="elh_menu_dytesore_menudID" class="menu_dytesore_menudID"><?= $Page->renderFieldHeader($Page->menudID) ?></div></th>
<?php } ?>
<?php if ($Page->menudGjuha->Visible) { // menudGjuha ?>
        <th data-name="menudGjuha" class="<?= $Page->menudGjuha->headerCellClass() ?>"><div id="elh_menu_dytesore_menudGjuha" class="menu_dytesore_menudGjuha"><?= $Page->renderFieldHeader($Page->menudGjuha) ?></div></th>
<?php } ?>
<?php if ($Page->menudKryesore->Visible) { // menudKryesore ?>
        <th data-name="menudKryesore" class="<?= $Page->menudKryesore->headerCellClass() ?>"><div id="elh_menu_dytesore_menudKryesore" class="menu_dytesore_menudKryesore"><?= $Page->renderFieldHeader($Page->menudKryesore) ?></div></th>
<?php } ?>
<?php if ($Page->menudTitulli->Visible) { // menudTitulli ?>
        <th data-name="menudTitulli" class="<?= $Page->menudTitulli->headerCellClass() ?>"><div id="elh_menu_dytesore_menudTitulli" class="menu_dytesore_menudTitulli"><?= $Page->renderFieldHeader($Page->menudTitulli) ?></div></th>
<?php } ?>
<?php if ($Page->menudUrl->Visible) { // menudUrl ?>
        <th data-name="menudUrl" class="<?= $Page->menudUrl->headerCellClass() ?>"><div id="elh_menu_dytesore_menudUrl" class="menu_dytesore_menudUrl"><?= $Page->renderFieldHeader($Page->menudUrl) ?></div></th>
<?php } ?>
<?php if ($Page->menudBlank->Visible) { // menudBlank ?>
        <th data-name="menudBlank" class="<?= $Page->menudBlank->headerCellClass() ?>"><div id="elh_menu_dytesore_menudBlank" class="menu_dytesore_menudBlank"><?= $Page->renderFieldHeader($Page->menudBlank) ?></div></th>
<?php } ?>
<?php if ($Page->menudRadhe->Visible) { // menudRadhe ?>
        <th data-name="menudRadhe" class="<?= $Page->menudRadhe->headerCellClass() ?>"><div id="elh_menu_dytesore_menudRadhe" class="menu_dytesore_menudRadhe"><?= $Page->renderFieldHeader($Page->menudRadhe) ?></div></th>
<?php } ?>
<?php if ($Page->menudAktiv->Visible) { // menudAktiv ?>
        <th data-name="menudAktiv" class="<?= $Page->menudAktiv->headerCellClass() ?>"><div id="elh_menu_dytesore_menudAktiv" class="menu_dytesore_menudAktiv"><?= $Page->renderFieldHeader($Page->menudAktiv) ?></div></th>
<?php } ?>
<?php if ($Page->menudAutor->Visible) { // menudAutor ?>
        <th data-name="menudAutor" class="<?= $Page->menudAutor->headerCellClass() ?>"><div id="elh_menu_dytesore_menudAutor" class="menu_dytesore_menudAutor"><?= $Page->renderFieldHeader($Page->menudAutor) ?></div></th>
<?php } ?>
<?php if ($Page->menudKrijuar->Visible) { // menudKrijuar ?>
        <th data-name="menudKrijuar" class="<?= $Page->menudKrijuar->headerCellClass() ?>"><div id="elh_menu_dytesore_menudKrijuar" class="menu_dytesore_menudKrijuar"><?= $Page->renderFieldHeader($Page->menudKrijuar) ?></div></th>
<?php } ?>
<?php if ($Page->menudAzhornuar->Visible) { // menudAzhornuar ?>
        <th data-name="menudAzhornuar" class="<?= $Page->menudAzhornuar->headerCellClass() ?>"><div id="elh_menu_dytesore_menudAzhornuar" class="menu_dytesore_menudAzhornuar"><?= $Page->renderFieldHeader($Page->menudAzhornuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_menu_dytesore",
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
    <?php if ($Page->menudID->Visible) { // menudID ?>
        <td data-name="menudID"<?= $Page->menudID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudID" class="el_menu_dytesore_menudID">
<span<?= $Page->menudID->viewAttributes() ?>>
<?= $Page->menudID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudGjuha->Visible) { // menudGjuha ?>
        <td data-name="menudGjuha"<?= $Page->menudGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudGjuha" class="el_menu_dytesore_menudGjuha">
<span<?= $Page->menudGjuha->viewAttributes() ?>>
<?= $Page->menudGjuha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudKryesore->Visible) { // menudKryesore ?>
        <td data-name="menudKryesore"<?= $Page->menudKryesore->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudKryesore" class="el_menu_dytesore_menudKryesore">
<span<?= $Page->menudKryesore->viewAttributes() ?>>
<?= $Page->menudKryesore->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudTitulli->Visible) { // menudTitulli ?>
        <td data-name="menudTitulli"<?= $Page->menudTitulli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudTitulli" class="el_menu_dytesore_menudTitulli">
<span<?= $Page->menudTitulli->viewAttributes() ?>>
<?= $Page->menudTitulli->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudUrl->Visible) { // menudUrl ?>
        <td data-name="menudUrl"<?= $Page->menudUrl->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudUrl" class="el_menu_dytesore_menudUrl">
<span<?= $Page->menudUrl->viewAttributes() ?>>
<?= $Page->menudUrl->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudBlank->Visible) { // menudBlank ?>
        <td data-name="menudBlank"<?= $Page->menudBlank->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudBlank" class="el_menu_dytesore_menudBlank">
<span<?= $Page->menudBlank->viewAttributes() ?>>
<?= $Page->menudBlank->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudRadhe->Visible) { // menudRadhe ?>
        <td data-name="menudRadhe"<?= $Page->menudRadhe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudRadhe" class="el_menu_dytesore_menudRadhe">
<span<?= $Page->menudRadhe->viewAttributes() ?>>
<?= $Page->menudRadhe->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudAktiv->Visible) { // menudAktiv ?>
        <td data-name="menudAktiv"<?= $Page->menudAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudAktiv" class="el_menu_dytesore_menudAktiv">
<span<?= $Page->menudAktiv->viewAttributes() ?>>
<?= $Page->menudAktiv->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudAutor->Visible) { // menudAutor ?>
        <td data-name="menudAutor"<?= $Page->menudAutor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudAutor" class="el_menu_dytesore_menudAutor">
<span<?= $Page->menudAutor->viewAttributes() ?>>
<?= $Page->menudAutor->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudKrijuar->Visible) { // menudKrijuar ?>
        <td data-name="menudKrijuar"<?= $Page->menudKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudKrijuar" class="el_menu_dytesore_menudKrijuar">
<span<?= $Page->menudKrijuar->viewAttributes() ?>>
<?= $Page->menudKrijuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menudAzhornuar->Visible) { // menudAzhornuar ?>
        <td data-name="menudAzhornuar"<?= $Page->menudAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudAzhornuar" class="el_menu_dytesore_menudAzhornuar">
<span<?= $Page->menudAzhornuar->viewAttributes() ?>>
<?= $Page->menudAzhornuar->getViewValue() ?></span>
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
    ew.addEventHandlers("menu_dytesore");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
