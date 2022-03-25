<?php

namespace PHPMaker2022\volalservice;

// Page object
$SherbimeDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { sherbime: currentTable } });
var currentForm, currentPageID;
var fsherbimedelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsherbimedelete = new ew.Form("fsherbimedelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fsherbimedelete;
    loadjs.done("fsherbimedelete");
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
<form name="fsherbimedelete" id="fsherbimedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="sherbime">
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
<?php if ($Page->sherbimeID->Visible) { // sherbimeID ?>
        <th class="<?= $Page->sherbimeID->headerCellClass() ?>"><span id="elh_sherbime_sherbimeID" class="sherbime_sherbimeID"><?= $Page->sherbimeID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_sq->Visible) { // sherbimeEmertimi_sq ?>
        <th class="<?= $Page->sherbimeEmertimi_sq->headerCellClass() ?>"><span id="elh_sherbime_sherbimeEmertimi_sq" class="sherbime_sherbimeEmertimi_sq"><?= $Page->sherbimeEmertimi_sq->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sherbimeTxt_sq->Visible) { // sherbimeTxt_sq ?>
        <th class="<?= $Page->sherbimeTxt_sq->headerCellClass() ?>"><span id="elh_sherbime_sherbimeTxt_sq" class="sherbime_sherbimeTxt_sq"><?= $Page->sherbimeTxt_sq->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sherbimeCmimi->Visible) { // sherbimeCmimi ?>
        <th class="<?= $Page->sherbimeCmimi->headerCellClass() ?>"><span id="elh_sherbime_sherbimeCmimi" class="sherbime_sherbimeCmimi"><?= $Page->sherbimeCmimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_en->Visible) { // sherbimeEmertimi_en ?>
        <th class="<?= $Page->sherbimeEmertimi_en->headerCellClass() ?>"><span id="elh_sherbime_sherbimeEmertimi_en" class="sherbime_sherbimeEmertimi_en"><?= $Page->sherbimeEmertimi_en->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sherbimeTxt_en->Visible) { // sherbimeTxt_en ?>
        <th class="<?= $Page->sherbimeTxt_en->headerCellClass() ?>"><span id="elh_sherbime_sherbimeTxt_en" class="sherbime_sherbimeTxt_en"><?= $Page->sherbimeTxt_en->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sherbimeFoto->Visible) { // sherbimeFoto ?>
        <th class="<?= $Page->sherbimeFoto->headerCellClass() ?>"><span id="elh_sherbime_sherbimeFoto" class="sherbime_sherbimeFoto"><?= $Page->sherbimeFoto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->sherbimeIndex->Visible) { // sherbimeIndex ?>
        <th class="<?= $Page->sherbimeIndex->headerCellClass() ?>"><span id="elh_sherbime_sherbimeIndex" class="sherbime_sherbimeIndex"><?= $Page->sherbimeIndex->caption() ?></span></th>
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
<?php if ($Page->sherbimeID->Visible) { // sherbimeID ?>
        <td<?= $Page->sherbimeID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeID" class="el_sherbime_sherbimeID">
<span<?= $Page->sherbimeID->viewAttributes() ?>>
<?= $Page->sherbimeID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_sq->Visible) { // sherbimeEmertimi_sq ?>
        <td<?= $Page->sherbimeEmertimi_sq->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeEmertimi_sq" class="el_sherbime_sherbimeEmertimi_sq">
<span<?= $Page->sherbimeEmertimi_sq->viewAttributes() ?>>
<?= $Page->sherbimeEmertimi_sq->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sherbimeTxt_sq->Visible) { // sherbimeTxt_sq ?>
        <td<?= $Page->sherbimeTxt_sq->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeTxt_sq" class="el_sherbime_sherbimeTxt_sq">
<span<?= $Page->sherbimeTxt_sq->viewAttributes() ?>>
<?= $Page->sherbimeTxt_sq->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sherbimeCmimi->Visible) { // sherbimeCmimi ?>
        <td<?= $Page->sherbimeCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeCmimi" class="el_sherbime_sherbimeCmimi">
<span<?= $Page->sherbimeCmimi->viewAttributes() ?>>
<?= $Page->sherbimeCmimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_en->Visible) { // sherbimeEmertimi_en ?>
        <td<?= $Page->sherbimeEmertimi_en->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeEmertimi_en" class="el_sherbime_sherbimeEmertimi_en">
<span<?= $Page->sherbimeEmertimi_en->viewAttributes() ?>>
<?= $Page->sherbimeEmertimi_en->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sherbimeTxt_en->Visible) { // sherbimeTxt_en ?>
        <td<?= $Page->sherbimeTxt_en->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeTxt_en" class="el_sherbime_sherbimeTxt_en">
<span<?= $Page->sherbimeTxt_en->viewAttributes() ?>>
<?= $Page->sherbimeTxt_en->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->sherbimeFoto->Visible) { // sherbimeFoto ?>
        <td<?= $Page->sherbimeFoto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeFoto" class="el_sherbime_sherbimeFoto">
<span>
<?= GetFileViewTag($Page->sherbimeFoto, $Page->sherbimeFoto->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->sherbimeIndex->Visible) { // sherbimeIndex ?>
        <td<?= $Page->sherbimeIndex->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_sherbime_sherbimeIndex" class="el_sherbime_sherbimeIndex">
<span<?= $Page->sherbimeIndex->viewAttributes() ?>>
<?= $Page->sherbimeIndex->getViewValue() ?></span>
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
