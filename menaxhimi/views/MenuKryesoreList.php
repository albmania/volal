<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuKryesoreList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_kryesore: currentTable } });
var currentForm, currentPageID;
var fmenu_kryesorelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_kryesorelist = new ew.Form("fmenu_kryesorelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fmenu_kryesorelist;
    fmenu_kryesorelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fmenu_kryesorelist");
});
var fmenu_kryesoresrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fmenu_kryesoresrch = new ew.Form("fmenu_kryesoresrch", "list");
    currentSearchForm = fmenu_kryesoresrch;

    // Dynamic selection lists

    // Filters
    fmenu_kryesoresrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fmenu_kryesoresrch.initSearchPanel = true;
    loadjs.done("fmenu_kryesoresrch");
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
<form name="fmenu_kryesoresrch" id="fmenu_kryesoresrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fmenu_kryesoresrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="menu_kryesore">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fmenu_kryesoresrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fmenu_kryesoresrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fmenu_kryesoresrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fmenu_kryesoresrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> menu_kryesore">
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
<form name="fmenu_kryesorelist" id="fmenu_kryesorelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="menu_kryesore">
<div id="gmp_menu_kryesore" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_menu_kryesorelist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->menukID->Visible) { // menukID ?>
        <th data-name="menukID" class="<?= $Page->menukID->headerCellClass() ?>"><div id="elh_menu_kryesore_menukID" class="menu_kryesore_menukID"><?= $Page->renderFieldHeader($Page->menukID) ?></div></th>
<?php } ?>
<?php if ($Page->menukGjuha->Visible) { // menukGjuha ?>
        <th data-name="menukGjuha" class="<?= $Page->menukGjuha->headerCellClass() ?>"><div id="elh_menu_kryesore_menukGjuha" class="menu_kryesore_menukGjuha"><?= $Page->renderFieldHeader($Page->menukGjuha) ?></div></th>
<?php } ?>
<?php if ($Page->menukTitull->Visible) { // menukTitull ?>
        <th data-name="menukTitull" class="<?= $Page->menukTitull->headerCellClass() ?>"><div id="elh_menu_kryesore_menukTitull" class="menu_kryesore_menukTitull"><?= $Page->renderFieldHeader($Page->menukTitull) ?></div></th>
<?php } ?>
<?php if ($Page->menukUrl->Visible) { // menukUrl ?>
        <th data-name="menukUrl" class="<?= $Page->menukUrl->headerCellClass() ?>"><div id="elh_menu_kryesore_menukUrl" class="menu_kryesore_menukUrl"><?= $Page->renderFieldHeader($Page->menukUrl) ?></div></th>
<?php } ?>
<?php if ($Page->menukBlank->Visible) { // menukBlank ?>
        <th data-name="menukBlank" class="<?= $Page->menukBlank->headerCellClass() ?>"><div id="elh_menu_kryesore_menukBlank" class="menu_kryesore_menukBlank"><?= $Page->renderFieldHeader($Page->menukBlank) ?></div></th>
<?php } ?>
<?php if ($Page->menukRadhe->Visible) { // menukRadhe ?>
        <th data-name="menukRadhe" class="<?= $Page->menukRadhe->headerCellClass() ?>"><div id="elh_menu_kryesore_menukRadhe" class="menu_kryesore_menukRadhe"><?= $Page->renderFieldHeader($Page->menukRadhe) ?></div></th>
<?php } ?>
<?php if ($Page->menukAktiv->Visible) { // menukAktiv ?>
        <th data-name="menukAktiv" class="<?= $Page->menukAktiv->headerCellClass() ?>"><div id="elh_menu_kryesore_menukAktiv" class="menu_kryesore_menukAktiv"><?= $Page->renderFieldHeader($Page->menukAktiv) ?></div></th>
<?php } ?>
<?php if ($Page->menukAutor->Visible) { // menukAutor ?>
        <th data-name="menukAutor" class="<?= $Page->menukAutor->headerCellClass() ?>"><div id="elh_menu_kryesore_menukAutor" class="menu_kryesore_menukAutor"><?= $Page->renderFieldHeader($Page->menukAutor) ?></div></th>
<?php } ?>
<?php if ($Page->menukKrijuar->Visible) { // menukKrijuar ?>
        <th data-name="menukKrijuar" class="<?= $Page->menukKrijuar->headerCellClass() ?>"><div id="elh_menu_kryesore_menukKrijuar" class="menu_kryesore_menukKrijuar"><?= $Page->renderFieldHeader($Page->menukKrijuar) ?></div></th>
<?php } ?>
<?php if ($Page->menukAzhornuar->Visible) { // menukAzhornuar ?>
        <th data-name="menukAzhornuar" class="<?= $Page->menukAzhornuar->headerCellClass() ?>"><div id="elh_menu_kryesore_menukAzhornuar" class="menu_kryesore_menukAzhornuar"><?= $Page->renderFieldHeader($Page->menukAzhornuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_menu_kryesore",
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
    <?php if ($Page->menukID->Visible) { // menukID ?>
        <td data-name="menukID"<?= $Page->menukID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukID" class="el_menu_kryesore_menukID">
<span<?= $Page->menukID->viewAttributes() ?>>
<?= $Page->menukID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukGjuha->Visible) { // menukGjuha ?>
        <td data-name="menukGjuha"<?= $Page->menukGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukGjuha" class="el_menu_kryesore_menukGjuha">
<span<?= $Page->menukGjuha->viewAttributes() ?>>
<?= $Page->menukGjuha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukTitull->Visible) { // menukTitull ?>
        <td data-name="menukTitull"<?= $Page->menukTitull->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukTitull" class="el_menu_kryesore_menukTitull">
<span<?= $Page->menukTitull->viewAttributes() ?>>
<?= $Page->menukTitull->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukUrl->Visible) { // menukUrl ?>
        <td data-name="menukUrl"<?= $Page->menukUrl->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukUrl" class="el_menu_kryesore_menukUrl">
<span<?= $Page->menukUrl->viewAttributes() ?>>
<?= $Page->menukUrl->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukBlank->Visible) { // menukBlank ?>
        <td data-name="menukBlank"<?= $Page->menukBlank->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukBlank" class="el_menu_kryesore_menukBlank">
<span<?= $Page->menukBlank->viewAttributes() ?>>
<?= $Page->menukBlank->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukRadhe->Visible) { // menukRadhe ?>
        <td data-name="menukRadhe"<?= $Page->menukRadhe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukRadhe" class="el_menu_kryesore_menukRadhe">
<span<?= $Page->menukRadhe->viewAttributes() ?>>
<?= $Page->menukRadhe->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukAktiv->Visible) { // menukAktiv ?>
        <td data-name="menukAktiv"<?= $Page->menukAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukAktiv" class="el_menu_kryesore_menukAktiv">
<span<?= $Page->menukAktiv->viewAttributes() ?>>
<?= $Page->menukAktiv->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukAutor->Visible) { // menukAutor ?>
        <td data-name="menukAutor"<?= $Page->menukAutor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukAutor" class="el_menu_kryesore_menukAutor">
<span<?= $Page->menukAutor->viewAttributes() ?>>
<?= $Page->menukAutor->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukKrijuar->Visible) { // menukKrijuar ?>
        <td data-name="menukKrijuar"<?= $Page->menukKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukKrijuar" class="el_menu_kryesore_menukKrijuar">
<span<?= $Page->menukKrijuar->viewAttributes() ?>>
<?= $Page->menukKrijuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->menukAzhornuar->Visible) { // menukAzhornuar ?>
        <td data-name="menukAzhornuar"<?= $Page->menukAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukAzhornuar" class="el_menu_kryesore_menukAzhornuar">
<span<?= $Page->menukAzhornuar->viewAttributes() ?>>
<?= $Page->menukAzhornuar->getViewValue() ?></span>
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
    ew.addEventHandlers("menu_kryesore");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
