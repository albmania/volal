<?php

namespace PHPMaker2022\volalservice;

// Page object
$IndexPseneList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { index_psene: currentTable } });
var currentForm, currentPageID;
var findex_psenelist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    findex_psenelist = new ew.Form("findex_psenelist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = findex_psenelist;
    findex_psenelist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("findex_psenelist");
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
</div>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> index_psene">
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
<form name="findex_psenelist" id="findex_psenelist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="index_psene">
<div id="gmp_index_psene" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_index_psenelist" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Page->iPseNeGjuha->Visible) { // iPseNeGjuha ?>
        <th data-name="iPseNeGjuha" class="<?= $Page->iPseNeGjuha->headerCellClass() ?>"><div id="elh_index_psene_iPseNeGjuha" class="index_psene_iPseNeGjuha"><?= $Page->renderFieldHeader($Page->iPseNeGjuha) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeB1Titull->Visible) { // iPseNeB1Titull ?>
        <th data-name="iPseNeB1Titull" class="<?= $Page->iPseNeB1Titull->headerCellClass() ?>"><div id="elh_index_psene_iPseNeB1Titull" class="index_psene_iPseNeB1Titull"><?= $Page->renderFieldHeader($Page->iPseNeB1Titull) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeB1Ikona->Visible) { // iPseNeB1Ikona ?>
        <th data-name="iPseNeB1Ikona" class="<?= $Page->iPseNeB1Ikona->headerCellClass() ?>"><div id="elh_index_psene_iPseNeB1Ikona" class="index_psene_iPseNeB1Ikona"><?= $Page->renderFieldHeader($Page->iPseNeB1Ikona) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeB2Titull->Visible) { // iPseNeB2Titull ?>
        <th data-name="iPseNeB2Titull" class="<?= $Page->iPseNeB2Titull->headerCellClass() ?>"><div id="elh_index_psene_iPseNeB2Titull" class="index_psene_iPseNeB2Titull"><?= $Page->renderFieldHeader($Page->iPseNeB2Titull) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeB2Ikona->Visible) { // iPseNeB2Ikona ?>
        <th data-name="iPseNeB2Ikona" class="<?= $Page->iPseNeB2Ikona->headerCellClass() ?>"><div id="elh_index_psene_iPseNeB2Ikona" class="index_psene_iPseNeB2Ikona"><?= $Page->renderFieldHeader($Page->iPseNeB2Ikona) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeB3Titull->Visible) { // iPseNeB3Titull ?>
        <th data-name="iPseNeB3Titull" class="<?= $Page->iPseNeB3Titull->headerCellClass() ?>"><div id="elh_index_psene_iPseNeB3Titull" class="index_psene_iPseNeB3Titull"><?= $Page->renderFieldHeader($Page->iPseNeB3Titull) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeB3Ikona->Visible) { // iPseNeB3Ikona ?>
        <th data-name="iPseNeB3Ikona" class="<?= $Page->iPseNeB3Ikona->headerCellClass() ?>"><div id="elh_index_psene_iPseNeB3Ikona" class="index_psene_iPseNeB3Ikona"><?= $Page->renderFieldHeader($Page->iPseNeB3Ikona) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeB4Titull->Visible) { // iPseNeB4Titull ?>
        <th data-name="iPseNeB4Titull" class="<?= $Page->iPseNeB4Titull->headerCellClass() ?>"><div id="elh_index_psene_iPseNeB4Titull" class="index_psene_iPseNeB4Titull"><?= $Page->renderFieldHeader($Page->iPseNeB4Titull) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeB4Ikona->Visible) { // iPseNeB4Ikona ?>
        <th data-name="iPseNeB4Ikona" class="<?= $Page->iPseNeB4Ikona->headerCellClass() ?>"><div id="elh_index_psene_iPseNeB4Ikona" class="index_psene_iPseNeB4Ikona"><?= $Page->renderFieldHeader($Page->iPseNeB4Ikona) ?></div></th>
<?php } ?>
<?php if ($Page->iPseNeFoto->Visible) { // iPseNeFoto ?>
        <th data-name="iPseNeFoto" class="<?= $Page->iPseNeFoto->headerCellClass() ?>"><div id="elh_index_psene_iPseNeFoto" class="index_psene_iPseNeFoto"><?= $Page->renderFieldHeader($Page->iPseNeFoto) ?></div></th>
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
            "id" => "r" . $Page->RowCount . "_index_psene",
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
    <?php if ($Page->iPseNeGjuha->Visible) { // iPseNeGjuha ?>
        <td data-name="iPseNeGjuha"<?= $Page->iPseNeGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeGjuha" class="el_index_psene_iPseNeGjuha">
<span<?= $Page->iPseNeGjuha->viewAttributes() ?>>
<?= $Page->iPseNeGjuha->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeB1Titull->Visible) { // iPseNeB1Titull ?>
        <td data-name="iPseNeB1Titull"<?= $Page->iPseNeB1Titull->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeB1Titull" class="el_index_psene_iPseNeB1Titull">
<span<?= $Page->iPseNeB1Titull->viewAttributes() ?>>
<?= $Page->iPseNeB1Titull->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeB1Ikona->Visible) { // iPseNeB1Ikona ?>
        <td data-name="iPseNeB1Ikona"<?= $Page->iPseNeB1Ikona->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeB1Ikona" class="el_index_psene_iPseNeB1Ikona">
<span<?= $Page->iPseNeB1Ikona->viewAttributes() ?>>
<?= $Page->iPseNeB1Ikona->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeB2Titull->Visible) { // iPseNeB2Titull ?>
        <td data-name="iPseNeB2Titull"<?= $Page->iPseNeB2Titull->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeB2Titull" class="el_index_psene_iPseNeB2Titull">
<span<?= $Page->iPseNeB2Titull->viewAttributes() ?>>
<?= $Page->iPseNeB2Titull->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeB2Ikona->Visible) { // iPseNeB2Ikona ?>
        <td data-name="iPseNeB2Ikona"<?= $Page->iPseNeB2Ikona->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeB2Ikona" class="el_index_psene_iPseNeB2Ikona">
<span<?= $Page->iPseNeB2Ikona->viewAttributes() ?>>
<?= $Page->iPseNeB2Ikona->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeB3Titull->Visible) { // iPseNeB3Titull ?>
        <td data-name="iPseNeB3Titull"<?= $Page->iPseNeB3Titull->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeB3Titull" class="el_index_psene_iPseNeB3Titull">
<span<?= $Page->iPseNeB3Titull->viewAttributes() ?>>
<?= $Page->iPseNeB3Titull->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeB3Ikona->Visible) { // iPseNeB3Ikona ?>
        <td data-name="iPseNeB3Ikona"<?= $Page->iPseNeB3Ikona->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeB3Ikona" class="el_index_psene_iPseNeB3Ikona">
<span<?= $Page->iPseNeB3Ikona->viewAttributes() ?>>
<?= $Page->iPseNeB3Ikona->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeB4Titull->Visible) { // iPseNeB4Titull ?>
        <td data-name="iPseNeB4Titull"<?= $Page->iPseNeB4Titull->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeB4Titull" class="el_index_psene_iPseNeB4Titull">
<span<?= $Page->iPseNeB4Titull->viewAttributes() ?>>
<?= $Page->iPseNeB4Titull->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeB4Ikona->Visible) { // iPseNeB4Ikona ?>
        <td data-name="iPseNeB4Ikona"<?= $Page->iPseNeB4Ikona->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeB4Ikona" class="el_index_psene_iPseNeB4Ikona">
<span<?= $Page->iPseNeB4Ikona->viewAttributes() ?>>
<?= $Page->iPseNeB4Ikona->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->iPseNeFoto->Visible) { // iPseNeFoto ?>
        <td data-name="iPseNeFoto"<?= $Page->iPseNeFoto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_psene_iPseNeFoto" class="el_index_psene_iPseNeFoto">
<span>
<?= GetFileViewTag($Page->iPseNeFoto, $Page->iPseNeFoto->getViewValue(), false) ?>
</span>
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
    ew.addEventHandlers("index_psene");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
