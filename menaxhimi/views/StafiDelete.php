<?php

namespace PHPMaker2022\volalservice;

// Page object
$StafiDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { stafi: currentTable } });
var currentForm, currentPageID;
var fstafidelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fstafidelete = new ew.Form("fstafidelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fstafidelete;
    loadjs.done("fstafidelete");
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
<form name="fstafidelete" id="fstafidelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="stafi">
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
<?php if ($Page->stafiID->Visible) { // stafiID ?>
        <th class="<?= $Page->stafiID->headerCellClass() ?>"><span id="elh_stafi_stafiID" class="stafi_stafiID"><?= $Page->stafiID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->stafiEmri->Visible) { // stafiEmri ?>
        <th class="<?= $Page->stafiEmri->headerCellClass() ?>"><span id="elh_stafi_stafiEmri" class="stafi_stafiEmri"><?= $Page->stafiEmri->caption() ?></span></th>
<?php } ?>
<?php if ($Page->stafiDatelindja->Visible) { // stafiDatelindja ?>
        <th class="<?= $Page->stafiDatelindja->headerCellClass() ?>"><span id="elh_stafi_stafiDatelindja" class="stafi_stafiDatelindja"><?= $Page->stafiDatelindja->caption() ?></span></th>
<?php } ?>
<?php if ($Page->stafiStudime->Visible) { // stafiStudime ?>
        <th class="<?= $Page->stafiStudime->headerCellClass() ?>"><span id="elh_stafi_stafiStudime" class="stafi_stafiStudime"><?= $Page->stafiStudime->caption() ?></span></th>
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
<?php if ($Page->stafiID->Visible) { // stafiID ?>
        <td<?= $Page->stafiID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_stafi_stafiID" class="el_stafi_stafiID">
<span<?= $Page->stafiID->viewAttributes() ?>>
<?= $Page->stafiID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->stafiEmri->Visible) { // stafiEmri ?>
        <td<?= $Page->stafiEmri->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_stafi_stafiEmri" class="el_stafi_stafiEmri">
<span<?= $Page->stafiEmri->viewAttributes() ?>>
<?= $Page->stafiEmri->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->stafiDatelindja->Visible) { // stafiDatelindja ?>
        <td<?= $Page->stafiDatelindja->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_stafi_stafiDatelindja" class="el_stafi_stafiDatelindja">
<span<?= $Page->stafiDatelindja->viewAttributes() ?>>
<?= $Page->stafiDatelindja->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->stafiStudime->Visible) { // stafiStudime ?>
        <td<?= $Page->stafiStudime->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_stafi_stafiStudime" class="el_stafi_stafiStudime">
<span<?= $Page->stafiStudime->viewAttributes() ?>>
<?= $Page->stafiStudime->getViewValue() ?></span>
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
