<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisSherbimeList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis_sherbime: currentTable } });
var currentForm, currentPageID;
var fservis_sherbimelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_sherbimelist = new ew.Form("fservis_sherbimelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fservis_sherbimelist;
    fservis_sherbimelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fservis_sherbimelist");
});
var fservis_sherbimesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fservis_sherbimesrch = new ew.Form("fservis_sherbimesrch", "list");
    currentSearchForm = fservis_sherbimesrch;

    // Dynamic selection lists

    // Filters
    fservis_sherbimesrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fservis_sherbimesrch.initSearchPanel = true;
    loadjs.done("fservis_sherbimesrch");
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
<?php if (!$Page->isExport() || Config("EXPORT_MASTER_RECORD") && $Page->isExport("print")) { ?>
<?php
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "servis") {
    if ($Page->MasterRecordExists) {
        include_once "views/ServisMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fservis_sherbimesrch" id="fservis_sherbimesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fservis_sherbimesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="servis_sherbime">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fservis_sherbimesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fservis_sherbimesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fservis_sherbimesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fservis_sherbimesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> servis_sherbime">
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
<form name="fservis_sherbimelist" id="fservis_sherbimelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis_sherbime">
<?php if ($Page->getCurrentMasterTable() == "servis" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="servis">
<input type="hidden" name="fk_servisID" value="<?= HtmlEncode($Page->servisSherbimServisID->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_servis_sherbime" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_servis_sherbimelist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->servisSherbimID->Visible) { // servisSherbimID ?>
        <th data-name="servisSherbimID" class="<?= $Page->servisSherbimID->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimID" class="servis_sherbime_servisSherbimID"><?= $Page->renderFieldHeader($Page->servisSherbimID) ?></div></th>
<?php } ?>
<?php if ($Page->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <th data-name="servisSherbimServisID" class="<?= $Page->servisSherbimServisID->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimServisID" class="servis_sherbime_servisSherbimServisID"><?= $Page->renderFieldHeader($Page->servisSherbimServisID) ?></div></th>
<?php } ?>
<?php if ($Page->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <th data-name="servisSherbimSherbimi" class="<?= $Page->servisSherbimSherbimi->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimSherbimi" class="servis_sherbime_servisSherbimSherbimi"><?= $Page->renderFieldHeader($Page->servisSherbimSherbimi) ?></div></th>
<?php } ?>
<?php if ($Page->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <th data-name="servisSherbimCmimi" class="<?= $Page->servisSherbimCmimi->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimCmimi" class="servis_sherbime_servisSherbimCmimi"><?= $Page->renderFieldHeader($Page->servisSherbimCmimi) ?></div></th>
<?php } ?>
<?php if ($Page->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <th data-name="servisSherbimShenim" class="<?= $Page->servisSherbimShenim->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimShenim" class="servis_sherbime_servisSherbimShenim"><?= $Page->renderFieldHeader($Page->servisSherbimShenim) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_servis_sherbime",
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
    <?php if ($Page->servisSherbimID->Visible) { // servisSherbimID ?>
        <td data-name="servisSherbimID"<?= $Page->servisSherbimID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimID" class="el_servis_sherbime_servisSherbimID">
<span<?= $Page->servisSherbimID->viewAttributes() ?>>
<?= $Page->servisSherbimID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <td data-name="servisSherbimServisID"<?= $Page->servisSherbimServisID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<span<?= $Page->servisSherbimServisID->viewAttributes() ?>>
<?= $Page->servisSherbimServisID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <td data-name="servisSherbimSherbimi"<?= $Page->servisSherbimSherbimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimSherbimi" class="el_servis_sherbime_servisSherbimSherbimi">
<span<?= $Page->servisSherbimSherbimi->viewAttributes() ?>>
<?= $Page->servisSherbimSherbimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <td data-name="servisSherbimCmimi"<?= $Page->servisSherbimCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimCmimi" class="el_servis_sherbime_servisSherbimCmimi">
<span<?= $Page->servisSherbimCmimi->viewAttributes() ?>>
<?= $Page->servisSherbimCmimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <td data-name="servisSherbimShenim"<?= $Page->servisSherbimShenim->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimShenim" class="el_servis_sherbime_servisSherbimShenim">
<span<?= $Page->servisSherbimShenim->viewAttributes() ?>>
<?= $Page->servisSherbimShenim->getViewValue() ?></span>
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
<?php
// Render aggregate row
$Page->RowType = ROWTYPE_AGGREGATE;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->TotalRecords > 0 && !$Page->isGridAdd() && !$Page->isGridEdit()) { ?>
<tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options
$Page->renderListOptions();

// Render list options (footer, left)
$Page->ListOptions->render("footer", "left");
?>
    <?php if ($Page->servisSherbimID->Visible) { // servisSherbimID ?>
        <td data-name="servisSherbimID" class="<?= $Page->servisSherbimID->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimID" class="servis_sherbime_servisSherbimID">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <td data-name="servisSherbimServisID" class="<?= $Page->servisSherbimServisID->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimServisID" class="servis_sherbime_servisSherbimServisID">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <td data-name="servisSherbimSherbimi" class="<?= $Page->servisSherbimSherbimi->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimSherbimi" class="servis_sherbime_servisSherbimSherbimi">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <td data-name="servisSherbimCmimi" class="<?= $Page->servisSherbimCmimi->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimCmimi" class="servis_sherbime_servisSherbimCmimi">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->servisSherbimCmimi->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <td data-name="servisSherbimShenim" class="<?= $Page->servisSherbimShenim->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimShenim" class="servis_sherbime_servisSherbimShenim">
        </span></td>
    <?php } ?>
<?php
// Render list options (footer, right)
$Page->ListOptions->render("footer", "right");
?>
    </tr>
</tfoot>
<?php } ?>
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
    ew.addEventHandlers("servis_sherbime");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $("a[class='btn btn-default ew-add-edit ew-add-blank-row']").append(' SHTO RRJESHT TJETER ');
});
</script>
<?php } ?>
