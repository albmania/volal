<?php

namespace PHPMaker2022\volalservice;

// Page object
$ProdhuesDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { prodhues: currentTable } });
var currentForm, currentPageID;
var fprodhuesdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fprodhuesdelete = new ew.Form("fprodhuesdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fprodhuesdelete;
    loadjs.done("fprodhuesdelete");
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
<form name="fprodhuesdelete" id="fprodhuesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="prodhues">
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
<?php if ($Page->prodhuesID->Visible) { // prodhuesID ?>
        <th class="<?= $Page->prodhuesID->headerCellClass() ?>"><span id="elh_prodhues_prodhuesID" class="prodhues_prodhuesID"><?= $Page->prodhuesID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->prodhuesEmri->Visible) { // prodhuesEmri ?>
        <th class="<?= $Page->prodhuesEmri->headerCellClass() ?>"><span id="elh_prodhues_prodhuesEmri" class="prodhues_prodhuesEmri"><?= $Page->prodhuesEmri->caption() ?></span></th>
<?php } ?>
<?php if ($Page->prodhuesLogo->Visible) { // prodhuesLogo ?>
        <th class="<?= $Page->prodhuesLogo->headerCellClass() ?>"><span id="elh_prodhues_prodhuesLogo" class="prodhues_prodhuesLogo"><?= $Page->prodhuesLogo->caption() ?></span></th>
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
<?php if ($Page->prodhuesID->Visible) { // prodhuesID ?>
        <td<?= $Page->prodhuesID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_prodhues_prodhuesID" class="el_prodhues_prodhuesID">
<span<?= $Page->prodhuesID->viewAttributes() ?>>
<?= $Page->prodhuesID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->prodhuesEmri->Visible) { // prodhuesEmri ?>
        <td<?= $Page->prodhuesEmri->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_prodhues_prodhuesEmri" class="el_prodhues_prodhuesEmri">
<span<?= $Page->prodhuesEmri->viewAttributes() ?>>
<?= $Page->prodhuesEmri->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->prodhuesLogo->Visible) { // prodhuesLogo ?>
        <td<?= $Page->prodhuesLogo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_prodhues_prodhuesLogo" class="el_prodhues_prodhuesLogo">
<span>
<?= GetFileViewTag($Page->prodhuesLogo, $Page->prodhuesLogo->getViewValue(), false) ?>
</span>
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
