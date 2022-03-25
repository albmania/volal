<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisPjesetList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis_pjeset: currentTable } });
var currentForm, currentPageID;
var fservis_pjesetlist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_pjesetlist = new ew.Form("fservis_pjesetlist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fservis_pjesetlist;
    fservis_pjesetlist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fservis_pjesetlist");
});
var fservis_pjesetsrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fservis_pjesetsrch = new ew.Form("fservis_pjesetsrch", "list");
    currentSearchForm = fservis_pjesetsrch;

    // Dynamic selection lists

    // Filters
    fservis_pjesetsrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fservis_pjesetsrch.initSearchPanel = true;
    loadjs.done("fservis_pjesetsrch");
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
<form name="fservis_pjesetsrch" id="fservis_pjesetsrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fservis_pjesetsrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="servis_pjeset">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fservis_pjesetsrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fservis_pjesetsrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fservis_pjesetsrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fservis_pjesetsrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> servis_pjeset">
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
<form name="fservis_pjesetlist" id="fservis_pjesetlist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis_pjeset">
<?php if ($Page->getCurrentMasterTable() == "servis" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="servis">
<input type="hidden" name="fk_servisID" value="<?= HtmlEncode($Page->servisPjeseServisID->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_servis_pjeset" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_servis_pjesetlist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->servisPjeseID->Visible) { // servisPjeseID ?>
        <th data-name="servisPjeseID" class="<?= $Page->servisPjeseID->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseID" class="servis_pjeset_servisPjeseID"><?= $Page->renderFieldHeader($Page->servisPjeseID) ?></div></th>
<?php } ?>
<?php if ($Page->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <th data-name="servisPjeseServisID" class="<?= $Page->servisPjeseServisID->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseServisID" class="servis_pjeset_servisPjeseServisID"><?= $Page->renderFieldHeader($Page->servisPjeseServisID) ?></div></th>
<?php } ?>
<?php if ($Page->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <th data-name="servisPjesePjesa" class="<?= $Page->servisPjesePjesa->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjesePjesa" class="servis_pjeset_servisPjesePjesa"><?= $Page->renderFieldHeader($Page->servisPjesePjesa) ?></div></th>
<?php } ?>
<?php if ($Page->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <th data-name="servisPjeseSasia" class="<?= $Page->servisPjeseSasia->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseSasia" class="servis_pjeset_servisPjeseSasia"><?= $Page->renderFieldHeader($Page->servisPjeseSasia) ?></div></th>
<?php } ?>
<?php if ($Page->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <th data-name="servisPjeseCmimi" class="<?= $Page->servisPjeseCmimi->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseCmimi" class="servis_pjeset_servisPjeseCmimi"><?= $Page->renderFieldHeader($Page->servisPjeseCmimi) ?></div></th>
<?php } ?>
<?php if ($Page->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <th data-name="servisPjeseShenim" class="<?= $Page->servisPjeseShenim->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseShenim" class="servis_pjeset_servisPjeseShenim"><?= $Page->renderFieldHeader($Page->servisPjeseShenim) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_servis_pjeset",
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
    <?php if ($Page->servisPjeseID->Visible) { // servisPjeseID ?>
        <td data-name="servisPjeseID"<?= $Page->servisPjeseID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseID" class="el_servis_pjeset_servisPjeseID">
<span<?= $Page->servisPjeseID->viewAttributes() ?>>
<?= $Page->servisPjeseID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <td data-name="servisPjeseServisID"<?= $Page->servisPjeseServisID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<span<?= $Page->servisPjeseServisID->viewAttributes() ?>>
<?= $Page->servisPjeseServisID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <td data-name="servisPjesePjesa"<?= $Page->servisPjesePjesa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjesePjesa" class="el_servis_pjeset_servisPjesePjesa">
<span<?= $Page->servisPjesePjesa->viewAttributes() ?>>
<?= $Page->servisPjesePjesa->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <td data-name="servisPjeseSasia"<?= $Page->servisPjeseSasia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseSasia" class="el_servis_pjeset_servisPjeseSasia">
<span<?= $Page->servisPjeseSasia->viewAttributes() ?>>
<?= $Page->servisPjeseSasia->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <td data-name="servisPjeseCmimi"<?= $Page->servisPjeseCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseCmimi" class="el_servis_pjeset_servisPjeseCmimi">
<span<?= $Page->servisPjeseCmimi->viewAttributes() ?>>
<?= $Page->servisPjeseCmimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <td data-name="servisPjeseShenim"<?= $Page->servisPjeseShenim->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseShenim" class="el_servis_pjeset_servisPjeseShenim">
<span<?= $Page->servisPjeseShenim->viewAttributes() ?>>
<?= $Page->servisPjeseShenim->getViewValue() ?></span>
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
    <?php if ($Page->servisPjeseID->Visible) { // servisPjeseID ?>
        <td data-name="servisPjeseID" class="<?= $Page->servisPjeseID->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseID" class="servis_pjeset_servisPjeseID">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <td data-name="servisPjeseServisID" class="<?= $Page->servisPjeseServisID->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseServisID" class="servis_pjeset_servisPjeseServisID">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <td data-name="servisPjesePjesa" class="<?= $Page->servisPjesePjesa->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjesePjesa" class="servis_pjeset_servisPjesePjesa">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <td data-name="servisPjeseSasia" class="<?= $Page->servisPjeseSasia->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseSasia" class="servis_pjeset_servisPjeseSasia">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <td data-name="servisPjeseCmimi" class="<?= $Page->servisPjeseCmimi->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseCmimi" class="servis_pjeset_servisPjeseCmimi">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->servisPjeseCmimi->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <td data-name="servisPjeseShenim" class="<?= $Page->servisPjeseShenim->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseShenim" class="servis_pjeset_servisPjeseShenim">
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
    ew.addEventHandlers("servis_pjeset");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
