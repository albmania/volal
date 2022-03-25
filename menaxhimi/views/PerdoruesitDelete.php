<?php

namespace PHPMaker2022\volalservice;

// Page object
$PerdoruesitDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { perdoruesit: currentTable } });
var currentForm, currentPageID;
var fperdoruesitdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fperdoruesitdelete = new ew.Form("fperdoruesitdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fperdoruesitdelete;
    loadjs.done("fperdoruesitdelete");
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
<form name="fperdoruesitdelete" id="fperdoruesitdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="perdoruesit">
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
<?php if ($Page->perdID->Visible) { // perdID ?>
        <th class="<?= $Page->perdID->headerCellClass() ?>"><span id="elh_perdoruesit_perdID" class="perdoruesit_perdID"><?= $Page->perdID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->perdEmri->Visible) { // perdEmri ?>
        <th class="<?= $Page->perdEmri->headerCellClass() ?>"><span id="elh_perdoruesit_perdEmri" class="perdoruesit_perdEmri"><?= $Page->perdEmri->caption() ?></span></th>
<?php } ?>
<?php if ($Page->perdUsername->Visible) { // perdUsername ?>
        <th class="<?= $Page->perdUsername->headerCellClass() ?>"><span id="elh_perdoruesit_perdUsername" class="perdoruesit_perdUsername"><?= $Page->perdUsername->caption() ?></span></th>
<?php } ?>
<?php if ($Page->perdFjalekalimi->Visible) { // perdFjalekalimi ?>
        <th class="<?= $Page->perdFjalekalimi->headerCellClass() ?>"><span id="elh_perdoruesit_perdFjalekalimi" class="perdoruesit_perdFjalekalimi"><?= $Page->perdFjalekalimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->perdEmail->Visible) { // perdEmail ?>
        <th class="<?= $Page->perdEmail->headerCellClass() ?>"><span id="elh_perdoruesit_perdEmail" class="perdoruesit_perdEmail"><?= $Page->perdEmail->caption() ?></span></th>
<?php } ?>
<?php if ($Page->perdNiveliPerdoruesit->Visible) { // perdNiveliPerdoruesit ?>
        <th class="<?= $Page->perdNiveliPerdoruesit->headerCellClass() ?>"><span id="elh_perdoruesit_perdNiveliPerdoruesit" class="perdoruesit_perdNiveliPerdoruesit"><?= $Page->perdNiveliPerdoruesit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->perdDtReg->Visible) { // perdDtReg ?>
        <th class="<?= $Page->perdDtReg->headerCellClass() ?>"><span id="elh_perdoruesit_perdDtReg" class="perdoruesit_perdDtReg"><?= $Page->perdDtReg->caption() ?></span></th>
<?php } ?>
<?php if ($Page->perdActivated->Visible) { // perdActivated ?>
        <th class="<?= $Page->perdActivated->headerCellClass() ?>"><span id="elh_perdoruesit_perdActivated" class="perdoruesit_perdActivated"><?= $Page->perdActivated->caption() ?></span></th>
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
<?php if ($Page->perdID->Visible) { // perdID ?>
        <td<?= $Page->perdID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdID" class="el_perdoruesit_perdID">
<span<?= $Page->perdID->viewAttributes() ?>>
<?= $Page->perdID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->perdEmri->Visible) { // perdEmri ?>
        <td<?= $Page->perdEmri->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdEmri" class="el_perdoruesit_perdEmri">
<span<?= $Page->perdEmri->viewAttributes() ?>>
<?= $Page->perdEmri->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->perdUsername->Visible) { // perdUsername ?>
        <td<?= $Page->perdUsername->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdUsername" class="el_perdoruesit_perdUsername">
<span<?= $Page->perdUsername->viewAttributes() ?>>
<?= $Page->perdUsername->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->perdFjalekalimi->Visible) { // perdFjalekalimi ?>
        <td<?= $Page->perdFjalekalimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdFjalekalimi" class="el_perdoruesit_perdFjalekalimi">
<span<?= $Page->perdFjalekalimi->viewAttributes() ?>>
<?= $Page->perdFjalekalimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->perdEmail->Visible) { // perdEmail ?>
        <td<?= $Page->perdEmail->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdEmail" class="el_perdoruesit_perdEmail">
<span<?= $Page->perdEmail->viewAttributes() ?>>
<?= $Page->perdEmail->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->perdNiveliPerdoruesit->Visible) { // perdNiveliPerdoruesit ?>
        <td<?= $Page->perdNiveliPerdoruesit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdNiveliPerdoruesit" class="el_perdoruesit_perdNiveliPerdoruesit">
<span<?= $Page->perdNiveliPerdoruesit->viewAttributes() ?>>
<?= $Page->perdNiveliPerdoruesit->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->perdDtReg->Visible) { // perdDtReg ?>
        <td<?= $Page->perdDtReg->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdDtReg" class="el_perdoruesit_perdDtReg">
<span<?= $Page->perdDtReg->viewAttributes() ?>>
<?= $Page->perdDtReg->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->perdActivated->Visible) { // perdActivated ?>
        <td<?= $Page->perdActivated->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_perdoruesit_perdActivated" class="el_perdoruesit_perdActivated">
<span<?= $Page->perdActivated->viewAttributes() ?>>
<?= $Page->perdActivated->getViewValue() ?></span>
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
