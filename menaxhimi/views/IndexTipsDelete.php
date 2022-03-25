<?php

namespace PHPMaker2022\volalservice;

// Page object
$IndexTipsDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { index_tips: currentTable } });
var currentForm, currentPageID;
var findex_tipsdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    findex_tipsdelete = new ew.Form("findex_tipsdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = findex_tipsdelete;
    loadjs.done("findex_tipsdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="findex_tipsdelete" id="findex_tipsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="index_tips">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table table-sm ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->iTipsID->Visible) { // iTipsID ?>
        <th class="<?= $Page->iTipsID->headerCellClass() ?>"><span id="elh_index_tips_iTipsID" class="index_tips_iTipsID"><?= $Page->iTipsID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->iTipsTeksti->Visible) { // iTipsTeksti ?>
        <th class="<?= $Page->iTipsTeksti->headerCellClass() ?>"><span id="elh_index_tips_iTipsTeksti" class="index_tips_iTipsTeksti"><?= $Page->iTipsTeksti->caption() ?></span></th>
<?php } ?>
<?php if ($Page->iTipsAutori->Visible) { // iTipsAutori ?>
        <th class="<?= $Page->iTipsAutori->headerCellClass() ?>"><span id="elh_index_tips_iTipsAutori" class="index_tips_iTipsAutori"><?= $Page->iTipsAutori->caption() ?></span></th>
<?php } ?>
<?php if ($Page->iTipsKrijuar->Visible) { // iTipsKrijuar ?>
        <th class="<?= $Page->iTipsKrijuar->headerCellClass() ?>"><span id="elh_index_tips_iTipsKrijuar" class="index_tips_iTipsKrijuar"><?= $Page->iTipsKrijuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->iTipsAzhornuar->Visible) { // iTipsAzhornuar ?>
        <th class="<?= $Page->iTipsAzhornuar->headerCellClass() ?>"><span id="elh_index_tips_iTipsAzhornuar" class="index_tips_iTipsAzhornuar"><?= $Page->iTipsAzhornuar->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->iTipsID->Visible) { // iTipsID ?>
        <td<?= $Page->iTipsID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsID" class="el_index_tips_iTipsID">
<span<?= $Page->iTipsID->viewAttributes() ?>>
<?= $Page->iTipsID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->iTipsTeksti->Visible) { // iTipsTeksti ?>
        <td<?= $Page->iTipsTeksti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsTeksti" class="el_index_tips_iTipsTeksti">
<span<?= $Page->iTipsTeksti->viewAttributes() ?>>
<?= $Page->iTipsTeksti->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->iTipsAutori->Visible) { // iTipsAutori ?>
        <td<?= $Page->iTipsAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsAutori" class="el_index_tips_iTipsAutori">
<span<?= $Page->iTipsAutori->viewAttributes() ?>>
<?= $Page->iTipsAutori->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->iTipsKrijuar->Visible) { // iTipsKrijuar ?>
        <td<?= $Page->iTipsKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsKrijuar" class="el_index_tips_iTipsKrijuar">
<span<?= $Page->iTipsKrijuar->viewAttributes() ?>>
<?= $Page->iTipsKrijuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->iTipsAzhornuar->Visible) { // iTipsAzhornuar ?>
        <td<?= $Page->iTipsAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_index_tips_iTipsAzhornuar" class="el_index_tips_iTipsAzhornuar">
<span<?= $Page->iTipsAzhornuar->viewAttributes() ?>>
<?= $Page->iTipsAzhornuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
