<?php

namespace PHPMaker2022\volalservice;

// Page object
$KlientList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { klient: currentTable } });
var currentForm, currentPageID;
var fklientlist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fklientlist = new ew.Form("fklientlist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fklientlist;
    fklientlist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fklientlist");
});
var fklientsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fklientsrch = new ew.Form("fklientsrch", "list");
    currentSearchForm = fklientsrch;

    // Dynamic selection lists

    // Filters
    fklientsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fklientsrch.initSearchPanel = true;
    loadjs.done("fklientsrch");
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
<form name="fklientsrch" id="fklientsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fklientsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="klient">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fklientsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fklientsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fklientsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fklientsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> klient">
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
<form name="fklientlist" id="fklientlist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="klient">
<div id="gmp_klient" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_klientlist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->klientID->Visible) { // klientID ?>
        <th data-name="klientID" class="<?= $Page->klientID->headerCellClass() ?>"><div id="elh_klient_klientID" class="klient_klientID"><?= $Page->renderFieldHeader($Page->klientID) ?></div></th>
<?php } ?>
<?php if ($Page->klientTipi->Visible) { // klientTipi ?>
        <th data-name="klientTipi" class="<?= $Page->klientTipi->headerCellClass() ?>"><div id="elh_klient_klientTipi" class="klient_klientTipi"><?= $Page->renderFieldHeader($Page->klientTipi) ?></div></th>
<?php } ?>
<?php if ($Page->klientEmertimi->Visible) { // klientEmertimi ?>
        <th data-name="klientEmertimi" class="<?= $Page->klientEmertimi->headerCellClass() ?>"><div id="elh_klient_klientEmertimi" class="klient_klientEmertimi"><?= $Page->renderFieldHeader($Page->klientEmertimi) ?></div></th>
<?php } ?>
<?php if ($Page->klientNIPT->Visible) { // klientNIPT ?>
        <th data-name="klientNIPT" class="<?= $Page->klientNIPT->headerCellClass() ?>"><div id="elh_klient_klientNIPT" class="klient_klientNIPT"><?= $Page->renderFieldHeader($Page->klientNIPT) ?></div></th>
<?php } ?>
<?php if ($Page->klientAdresa->Visible) { // klientAdresa ?>
        <th data-name="klientAdresa" class="<?= $Page->klientAdresa->headerCellClass() ?>"><div id="elh_klient_klientAdresa" class="klient_klientAdresa"><?= $Page->renderFieldHeader($Page->klientAdresa) ?></div></th>
<?php } ?>
<?php if ($Page->klientQyteti->Visible) { // klientQyteti ?>
        <th data-name="klientQyteti" class="<?= $Page->klientQyteti->headerCellClass() ?>"><div id="elh_klient_klientQyteti" class="klient_klientQyteti"><?= $Page->renderFieldHeader($Page->klientQyteti) ?></div></th>
<?php } ?>
<?php if ($Page->klientTel1->Visible) { // klientTel1 ?>
        <th data-name="klientTel1" class="<?= $Page->klientTel1->headerCellClass() ?>"><div id="elh_klient_klientTel1" class="klient_klientTel1"><?= $Page->renderFieldHeader($Page->klientTel1) ?></div></th>
<?php } ?>
<?php if ($Page->klientTel2->Visible) { // klientTel2 ?>
        <th data-name="klientTel2" class="<?= $Page->klientTel2->headerCellClass() ?>"><div id="elh_klient_klientTel2" class="klient_klientTel2"><?= $Page->renderFieldHeader($Page->klientTel2) ?></div></th>
<?php } ?>
<?php if ($Page->klientEmail->Visible) { // klientEmail ?>
        <th data-name="klientEmail" class="<?= $Page->klientEmail->headerCellClass() ?>"><div id="elh_klient_klientEmail" class="klient_klientEmail"><?= $Page->renderFieldHeader($Page->klientEmail) ?></div></th>
<?php } ?>
<?php if ($Page->klientAutori->Visible) { // klientAutori ?>
        <th data-name="klientAutori" class="<?= $Page->klientAutori->headerCellClass() ?>"><div id="elh_klient_klientAutori" class="klient_klientAutori"><?= $Page->renderFieldHeader($Page->klientAutori) ?></div></th>
<?php } ?>
<?php if ($Page->klientShtuar->Visible) { // klientShtuar ?>
        <th data-name="klientShtuar" class="<?= $Page->klientShtuar->headerCellClass() ?>"><div id="elh_klient_klientShtuar" class="klient_klientShtuar"><?= $Page->renderFieldHeader($Page->klientShtuar) ?></div></th>
<?php } ?>
<?php if ($Page->klientModifikuar->Visible) { // klientModifikuar ?>
        <th data-name="klientModifikuar" class="<?= $Page->klientModifikuar->headerCellClass() ?>"><div id="elh_klient_klientModifikuar" class="klient_klientModifikuar"><?= $Page->renderFieldHeader($Page->klientModifikuar) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_klient",
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
    <?php if ($Page->klientID->Visible) { // klientID ?>
        <td data-name="klientID"<?= $Page->klientID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientID" class="el_klient_klientID">
<span<?= $Page->klientID->viewAttributes() ?>>
<?= $Page->klientID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientTipi->Visible) { // klientTipi ?>
        <td data-name="klientTipi"<?= $Page->klientTipi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientTipi" class="el_klient_klientTipi">
<span<?= $Page->klientTipi->viewAttributes() ?>>
<?= $Page->klientTipi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientEmertimi->Visible) { // klientEmertimi ?>
        <td data-name="klientEmertimi"<?= $Page->klientEmertimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientEmertimi" class="el_klient_klientEmertimi">
<span<?= $Page->klientEmertimi->viewAttributes() ?>>
<?= $Page->klientEmertimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientNIPT->Visible) { // klientNIPT ?>
        <td data-name="klientNIPT"<?= $Page->klientNIPT->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientNIPT" class="el_klient_klientNIPT">
<span<?= $Page->klientNIPT->viewAttributes() ?>>
<?= $Page->klientNIPT->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientAdresa->Visible) { // klientAdresa ?>
        <td data-name="klientAdresa"<?= $Page->klientAdresa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientAdresa" class="el_klient_klientAdresa">
<span<?= $Page->klientAdresa->viewAttributes() ?>>
<?= $Page->klientAdresa->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientQyteti->Visible) { // klientQyteti ?>
        <td data-name="klientQyteti"<?= $Page->klientQyteti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientQyteti" class="el_klient_klientQyteti">
<span<?= $Page->klientQyteti->viewAttributes() ?>>
<?= $Page->klientQyteti->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientTel1->Visible) { // klientTel1 ?>
        <td data-name="klientTel1"<?= $Page->klientTel1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientTel1" class="el_klient_klientTel1">
<span<?= $Page->klientTel1->viewAttributes() ?>>
<?= $Page->klientTel1->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientTel2->Visible) { // klientTel2 ?>
        <td data-name="klientTel2"<?= $Page->klientTel2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientTel2" class="el_klient_klientTel2">
<span<?= $Page->klientTel2->viewAttributes() ?>>
<?= $Page->klientTel2->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientEmail->Visible) { // klientEmail ?>
        <td data-name="klientEmail"<?= $Page->klientEmail->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientEmail" class="el_klient_klientEmail">
<span<?= $Page->klientEmail->viewAttributes() ?>>
<?= $Page->klientEmail->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientAutori->Visible) { // klientAutori ?>
        <td data-name="klientAutori"<?= $Page->klientAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientAutori" class="el_klient_klientAutori">
<span<?= $Page->klientAutori->viewAttributes() ?>>
<?= $Page->klientAutori->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientShtuar->Visible) { // klientShtuar ?>
        <td data-name="klientShtuar"<?= $Page->klientShtuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientShtuar" class="el_klient_klientShtuar">
<span<?= $Page->klientShtuar->viewAttributes() ?>>
<?= $Page->klientShtuar->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->klientModifikuar->Visible) { // klientModifikuar ?>
        <td data-name="klientModifikuar"<?= $Page->klientModifikuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientModifikuar" class="el_klient_klientModifikuar">
<span<?= $Page->klientModifikuar->viewAttributes() ?>>
<?= $Page->klientModifikuar->getViewValue() ?></span>
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
    ew.addEventHandlers("klient");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $("a[class='btn btn-default ew-add-edit ew-add']").append(' SHTO KLIENT ');
});
</script>
<?php } ?>
