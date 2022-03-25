<?php

namespace PHPMaker2022\volalservice;

// Page object
$ReviewView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { review: currentTable } });
var currentForm, currentPageID;
var freviewview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    freviewview = new ew.Form("freviewview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = freviewview;
    loadjs.done("freviewview");
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
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="freviewview" id="freviewview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="review">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->reviewID->Visible) { // reviewID ?>
    <tr id="r_reviewID"<?= $Page->reviewID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewID"><?= $Page->reviewID->caption() ?></span></td>
        <td data-name="reviewID"<?= $Page->reviewID->cellAttributes() ?>>
<span id="el_review_reviewID">
<span<?= $Page->reviewID->viewAttributes() ?>>
<?= $Page->reviewID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewGjuha->Visible) { // reviewGjuha ?>
    <tr id="r_reviewGjuha"<?= $Page->reviewGjuha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewGjuha"><?= $Page->reviewGjuha->caption() ?></span></td>
        <td data-name="reviewGjuha"<?= $Page->reviewGjuha->cellAttributes() ?>>
<span id="el_review_reviewGjuha">
<span<?= $Page->reviewGjuha->viewAttributes() ?>>
<?= $Page->reviewGjuha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewEmri->Visible) { // reviewEmri ?>
    <tr id="r_reviewEmri"<?= $Page->reviewEmri->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewEmri"><?= $Page->reviewEmri->caption() ?></span></td>
        <td data-name="reviewEmri"<?= $Page->reviewEmri->cellAttributes() ?>>
<span id="el_review_reviewEmri">
<span<?= $Page->reviewEmri->viewAttributes() ?>>
<?= $Page->reviewEmri->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewMakine->Visible) { // reviewMakine ?>
    <tr id="r_reviewMakine"<?= $Page->reviewMakine->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewMakine"><?= $Page->reviewMakine->caption() ?></span></td>
        <td data-name="reviewMakine"<?= $Page->reviewMakine->cellAttributes() ?>>
<span id="el_review_reviewMakine">
<span<?= $Page->reviewMakine->viewAttributes() ?>>
<?= $Page->reviewMakine->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewPer->Visible) { // reviewPer ?>
    <tr id="r_reviewPer"<?= $Page->reviewPer->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewPer"><?= $Page->reviewPer->caption() ?></span></td>
        <td data-name="reviewPer"<?= $Page->reviewPer->cellAttributes() ?>>
<span id="el_review_reviewPer">
<span<?= $Page->reviewPer->viewAttributes() ?>>
<?= $Page->reviewPer->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewFoto->Visible) { // reviewFoto ?>
    <tr id="r_reviewFoto"<?= $Page->reviewFoto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewFoto"><?= $Page->reviewFoto->caption() ?></span></td>
        <td data-name="reviewFoto"<?= $Page->reviewFoto->cellAttributes() ?>>
<span id="el_review_reviewFoto">
<span>
<?= GetFileViewTag($Page->reviewFoto, $Page->reviewFoto->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewTxt->Visible) { // reviewTxt ?>
    <tr id="r_reviewTxt"<?= $Page->reviewTxt->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewTxt"><?= $Page->reviewTxt->caption() ?></span></td>
        <td data-name="reviewTxt"<?= $Page->reviewTxt->cellAttributes() ?>>
<span id="el_review_reviewTxt">
<span<?= $Page->reviewTxt->viewAttributes() ?>>
<?= $Page->reviewTxt->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewDate->Visible) { // reviewDate ?>
    <tr id="r_reviewDate"<?= $Page->reviewDate->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewDate"><?= $Page->reviewDate->caption() ?></span></td>
        <td data-name="reviewDate"<?= $Page->reviewDate->cellAttributes() ?>>
<span id="el_review_reviewDate">
<span<?= $Page->reviewDate->viewAttributes() ?>>
<?= $Page->reviewDate->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewAktiv->Visible) { // reviewAktiv ?>
    <tr id="r_reviewAktiv"<?= $Page->reviewAktiv->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewAktiv"><?= $Page->reviewAktiv->caption() ?></span></td>
        <td data-name="reviewAktiv"<?= $Page->reviewAktiv->cellAttributes() ?>>
<span id="el_review_reviewAktiv">
<span<?= $Page->reviewAktiv->viewAttributes() ?>>
<?= $Page->reviewAktiv->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->reviewRegjistruarNga->Visible) { // reviewRegjistruarNga ?>
    <tr id="r_reviewRegjistruarNga"<?= $Page->reviewRegjistruarNga->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_review_reviewRegjistruarNga"><?= $Page->reviewRegjistruarNga->caption() ?></span></td>
        <td data-name="reviewRegjistruarNga"<?= $Page->reviewRegjistruarNga->cellAttributes() ?>>
<span id="el_review_reviewRegjistruarNga">
<span<?= $Page->reviewRegjistruarNga->viewAttributes() ?>>
<?= $Page->reviewRegjistruarNga->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
