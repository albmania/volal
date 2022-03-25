<?php

namespace PHPMaker2022\volalservice;

// Page object
$ReviewDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { review: currentTable } });
var currentForm, currentPageID;
var freviewdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    freviewdelete = new ew.Form("freviewdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = freviewdelete;
    loadjs.done("freviewdelete");
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
<form name="freviewdelete" id="freviewdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="review">
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
<?php if ($Page->reviewID->Visible) { // reviewID ?>
        <th class="<?= $Page->reviewID->headerCellClass() ?>"><span id="elh_review_reviewID" class="review_reviewID"><?= $Page->reviewID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reviewGjuha->Visible) { // reviewGjuha ?>
        <th class="<?= $Page->reviewGjuha->headerCellClass() ?>"><span id="elh_review_reviewGjuha" class="review_reviewGjuha"><?= $Page->reviewGjuha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reviewEmri->Visible) { // reviewEmri ?>
        <th class="<?= $Page->reviewEmri->headerCellClass() ?>"><span id="elh_review_reviewEmri" class="review_reviewEmri"><?= $Page->reviewEmri->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reviewMakine->Visible) { // reviewMakine ?>
        <th class="<?= $Page->reviewMakine->headerCellClass() ?>"><span id="elh_review_reviewMakine" class="review_reviewMakine"><?= $Page->reviewMakine->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reviewPer->Visible) { // reviewPer ?>
        <th class="<?= $Page->reviewPer->headerCellClass() ?>"><span id="elh_review_reviewPer" class="review_reviewPer"><?= $Page->reviewPer->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reviewFoto->Visible) { // reviewFoto ?>
        <th class="<?= $Page->reviewFoto->headerCellClass() ?>"><span id="elh_review_reviewFoto" class="review_reviewFoto"><?= $Page->reviewFoto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reviewTxt->Visible) { // reviewTxt ?>
        <th class="<?= $Page->reviewTxt->headerCellClass() ?>"><span id="elh_review_reviewTxt" class="review_reviewTxt"><?= $Page->reviewTxt->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reviewDate->Visible) { // reviewDate ?>
        <th class="<?= $Page->reviewDate->headerCellClass() ?>"><span id="elh_review_reviewDate" class="review_reviewDate"><?= $Page->reviewDate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->reviewAktiv->Visible) { // reviewAktiv ?>
        <th class="<?= $Page->reviewAktiv->headerCellClass() ?>"><span id="elh_review_reviewAktiv" class="review_reviewAktiv"><?= $Page->reviewAktiv->caption() ?></span></th>
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
<?php if ($Page->reviewID->Visible) { // reviewID ?>
        <td<?= $Page->reviewID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewID" class="el_review_reviewID">
<span<?= $Page->reviewID->viewAttributes() ?>>
<?= $Page->reviewID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->reviewGjuha->Visible) { // reviewGjuha ?>
        <td<?= $Page->reviewGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewGjuha" class="el_review_reviewGjuha">
<span<?= $Page->reviewGjuha->viewAttributes() ?>>
<?= $Page->reviewGjuha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->reviewEmri->Visible) { // reviewEmri ?>
        <td<?= $Page->reviewEmri->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewEmri" class="el_review_reviewEmri">
<span<?= $Page->reviewEmri->viewAttributes() ?>>
<?= $Page->reviewEmri->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->reviewMakine->Visible) { // reviewMakine ?>
        <td<?= $Page->reviewMakine->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewMakine" class="el_review_reviewMakine">
<span<?= $Page->reviewMakine->viewAttributes() ?>>
<?= $Page->reviewMakine->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->reviewPer->Visible) { // reviewPer ?>
        <td<?= $Page->reviewPer->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewPer" class="el_review_reviewPer">
<span<?= $Page->reviewPer->viewAttributes() ?>>
<?= $Page->reviewPer->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->reviewFoto->Visible) { // reviewFoto ?>
        <td<?= $Page->reviewFoto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewFoto" class="el_review_reviewFoto">
<span>
<?= GetFileViewTag($Page->reviewFoto, $Page->reviewFoto->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->reviewTxt->Visible) { // reviewTxt ?>
        <td<?= $Page->reviewTxt->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewTxt" class="el_review_reviewTxt">
<span<?= $Page->reviewTxt->viewAttributes() ?>>
<?= $Page->reviewTxt->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->reviewDate->Visible) { // reviewDate ?>
        <td<?= $Page->reviewDate->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewDate" class="el_review_reviewDate">
<span<?= $Page->reviewDate->viewAttributes() ?>>
<?= $Page->reviewDate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->reviewAktiv->Visible) { // reviewAktiv ?>
        <td<?= $Page->reviewAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_review_reviewAktiv" class="el_review_reviewAktiv">
<span<?= $Page->reviewAktiv->viewAttributes() ?>>
<?= $Page->reviewAktiv->getViewValue() ?></span>
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
