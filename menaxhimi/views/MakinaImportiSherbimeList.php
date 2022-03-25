<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaImportiSherbimeList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_importi_sherbime: currentTable } });
var currentForm, currentPageID;
var fmakina_importi_sherbimelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importi_sherbimelist = new ew.Form("fmakina_importi_sherbimelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fmakina_importi_sherbimelist;
    fmakina_importi_sherbimelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fmakina_importi_sherbimelist");
});
var fmakina_importi_sherbimesrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fmakina_importi_sherbimesrch = new ew.Form("fmakina_importi_sherbimesrch", "list");
    currentSearchForm = fmakina_importi_sherbimesrch;

    // Dynamic selection lists

    // Filters
    fmakina_importi_sherbimesrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fmakina_importi_sherbimesrch.initSearchPanel = true;
    loadjs.done("fmakina_importi_sherbimesrch");
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
if ($Page->DbMasterFilter != "" && $Page->getCurrentMasterTable() == "makina_importi") {
    if ($Page->MasterRecordExists) {
        include_once "views/MakinaImportiMaster.php";
    }
}
?>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fmakina_importi_sherbimesrch" id="fmakina_importi_sherbimesrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fmakina_importi_sherbimesrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="makina_importi_sherbime">
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
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "" ? " active" : "" ?>" form="fmakina_importi_sherbimesrch" data-ew-action="search-type"><?= $Language->phrase("QuickSearchAuto") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "=" ? " active" : "" ?>" form="fmakina_importi_sherbimesrch" data-ew-action="search-type" data-search-type="="><?= $Language->phrase("QuickSearchExact") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "AND" ? " active" : "" ?>" form="fmakina_importi_sherbimesrch" data-ew-action="search-type" data-search-type="AND"><?= $Language->phrase("QuickSearchAll") ?></button>
                <button type="button" class="dropdown-item<?= $Page->BasicSearch->getType() == "OR" ? " active" : "" ?>" form="fmakina_importi_sherbimesrch" data-ew-action="search-type" data-search-type="OR"><?= $Language->phrase("QuickSearchAny") ?></button>
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
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> makina_importi_sherbime">
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
<form name="fmakina_importi_sherbimelist" id="fmakina_importi_sherbimelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_importi_sherbime">
<?php if ($Page->getCurrentMasterTable() == "makina_importi" && $Page->CurrentAction) { ?>
<input type="hidden" name="<?= Config("TABLE_SHOW_MASTER") ?>" value="makina_importi">
<input type="hidden" name="fk_mimpID" value="<?= HtmlEncode($Page->mishMakinaImporti->getSessionValue()) ?>">
<?php } ?>
<div id="gmp_makina_importi_sherbime" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_makina_importi_sherbimelist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->mishID->Visible) { // mishID ?>
        <th data-name="mishID" class="<?= $Page->mishID->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishID" class="makina_importi_sherbime_mishID"><?= $Page->renderFieldHeader($Page->mishID) ?></div></th>
<?php } ?>
<?php if ($Page->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <th data-name="mishMakinaImporti" class="<?= $Page->mishMakinaImporti->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishMakinaImporti" class="makina_importi_sherbime_mishMakinaImporti"><?= $Page->renderFieldHeader($Page->mishMakinaImporti) ?></div></th>
<?php } ?>
<?php if ($Page->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <th data-name="mishPershkrimi" class="<?= $Page->mishPershkrimi->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishPershkrimi" class="makina_importi_sherbime_mishPershkrimi"><?= $Page->renderFieldHeader($Page->mishPershkrimi) ?></div></th>
<?php } ?>
<?php if ($Page->mishKryer->Visible) { // mishKryer ?>
        <th data-name="mishKryer" class="<?= $Page->mishKryer->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishKryer" class="makina_importi_sherbime_mishKryer"><?= $Page->renderFieldHeader($Page->mishKryer) ?></div></th>
<?php } ?>
<?php if ($Page->mishCmimi->Visible) { // mishCmimi ?>
        <th data-name="mishCmimi" class="<?= $Page->mishCmimi->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishCmimi" class="makina_importi_sherbime_mishCmimi"><?= $Page->renderFieldHeader($Page->mishCmimi) ?></div></th>
<?php } ?>
<?php if ($Page->mishData->Visible) { // mishData ?>
        <th data-name="mishData" class="<?= $Page->mishData->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishData" class="makina_importi_sherbime_mishData"><?= $Page->renderFieldHeader($Page->mishData) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_makina_importi_sherbime",
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
    <?php if ($Page->mishID->Visible) { // mishID ?>
        <td data-name="mishID"<?= $Page->mishID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishID" class="el_makina_importi_sherbime_mishID">
<span<?= $Page->mishID->viewAttributes() ?>>
<?= $Page->mishID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <td data-name="mishMakinaImporti"<?= $Page->mishMakinaImporti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Page->mishMakinaImporti->viewAttributes() ?>>
<?= $Page->mishMakinaImporti->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <td data-name="mishPershkrimi"<?= $Page->mishPershkrimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishPershkrimi" class="el_makina_importi_sherbime_mishPershkrimi">
<span<?= $Page->mishPershkrimi->viewAttributes() ?>>
<?= $Page->mishPershkrimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mishKryer->Visible) { // mishKryer ?>
        <td data-name="mishKryer"<?= $Page->mishKryer->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishKryer" class="el_makina_importi_sherbime_mishKryer">
<span<?= $Page->mishKryer->viewAttributes() ?>>
<?= $Page->mishKryer->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mishCmimi->Visible) { // mishCmimi ?>
        <td data-name="mishCmimi"<?= $Page->mishCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishCmimi" class="el_makina_importi_sherbime_mishCmimi">
<span<?= $Page->mishCmimi->viewAttributes() ?>>
<?= $Page->mishCmimi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->mishData->Visible) { // mishData ?>
        <td data-name="mishData"<?= $Page->mishData->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishData" class="el_makina_importi_sherbime_mishData">
<span<?= $Page->mishData->viewAttributes() ?>>
<?= $Page->mishData->getViewValue() ?></span>
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
    <?php if ($Page->mishID->Visible) { // mishID ?>
        <td data-name="mishID" class="<?= $Page->mishID->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishID" class="makina_importi_sherbime_mishID">
        <span class="ew-aggregate"><?= $Language->phrase("COUNT") ?></span><span class="ew-aggregate-value">
        <?= $Page->mishID->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <td data-name="mishMakinaImporti" class="<?= $Page->mishMakinaImporti->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishMakinaImporti" class="makina_importi_sherbime_mishMakinaImporti">
        </span></td>
    <?php } ?>
    <?php if ($Page->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <td data-name="mishPershkrimi" class="<?= $Page->mishPershkrimi->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishPershkrimi" class="makina_importi_sherbime_mishPershkrimi">
        </span></td>
    <?php } ?>
    <?php if ($Page->mishKryer->Visible) { // mishKryer ?>
        <td data-name="mishKryer" class="<?= $Page->mishKryer->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishKryer" class="makina_importi_sherbime_mishKryer">
        </span></td>
    <?php } ?>
    <?php if ($Page->mishCmimi->Visible) { // mishCmimi ?>
        <td data-name="mishCmimi" class="<?= $Page->mishCmimi->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishCmimi" class="makina_importi_sherbime_mishCmimi">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->mishCmimi->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Page->mishData->Visible) { // mishData ?>
        <td data-name="mishData" class="<?= $Page->mishData->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishData" class="makina_importi_sherbime_mishData">
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
    ew.addEventHandlers("makina_importi_sherbime");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
