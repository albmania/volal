<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaImportiView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_importi: currentTable } });
var currentForm, currentPageID;
var fmakina_importiview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importiview = new ew.Form("fmakina_importiview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmakina_importiview;
    loadjs.done("fmakina_importiview");
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
<form name="fmakina_importiview" id="fmakina_importiview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_importi">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->mimpID->Visible) { // mimpID ?>
    <tr id="r_mimpID"<?= $Page->mimpID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpID"><?= $Page->mimpID->caption() ?></span></td>
        <td data-name="mimpID"<?= $Page->mimpID->cellAttributes() ?>>
<span id="el_makina_importi_mimpID">
<span<?= $Page->mimpID->viewAttributes() ?>>
<?= $Page->mimpID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpMarka->Visible) { // mimpMarka ?>
    <tr id="r_mimpMarka"<?= $Page->mimpMarka->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpMarka"><?= $Page->mimpMarka->caption() ?></span></td>
        <td data-name="mimpMarka"<?= $Page->mimpMarka->cellAttributes() ?>>
<span id="el_makina_importi_mimpMarka">
<span<?= $Page->mimpMarka->viewAttributes() ?>>
<?= $Page->mimpMarka->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpModeli->Visible) { // mimpModeli ?>
    <tr id="r_mimpModeli"<?= $Page->mimpModeli->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpModeli"><?= $Page->mimpModeli->caption() ?></span></td>
        <td data-name="mimpModeli"<?= $Page->mimpModeli->cellAttributes() ?>>
<span id="el_makina_importi_mimpModeli">
<span<?= $Page->mimpModeli->viewAttributes() ?>>
<?= $Page->mimpModeli->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpTipi->Visible) { // mimpTipi ?>
    <tr id="r_mimpTipi"<?= $Page->mimpTipi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpTipi"><?= $Page->mimpTipi->caption() ?></span></td>
        <td data-name="mimpTipi"<?= $Page->mimpTipi->cellAttributes() ?>>
<span id="el_makina_importi_mimpTipi">
<span<?= $Page->mimpTipi->viewAttributes() ?>>
<?= $Page->mimpTipi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpShasia->Visible) { // mimpShasia ?>
    <tr id="r_mimpShasia"<?= $Page->mimpShasia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpShasia"><?= $Page->mimpShasia->caption() ?></span></td>
        <td data-name="mimpShasia"<?= $Page->mimpShasia->cellAttributes() ?>>
<span id="el_makina_importi_mimpShasia">
<span<?= $Page->mimpShasia->viewAttributes() ?>>
<?= $Page->mimpShasia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpViti->Visible) { // mimpViti ?>
    <tr id="r_mimpViti"<?= $Page->mimpViti->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpViti"><?= $Page->mimpViti->caption() ?></span></td>
        <td data-name="mimpViti"<?= $Page->mimpViti->cellAttributes() ?>>
<span id="el_makina_importi_mimpViti">
<span<?= $Page->mimpViti->viewAttributes() ?>>
<?= $Page->mimpViti->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpKarburant->Visible) { // mimpKarburant ?>
    <tr id="r_mimpKarburant"<?= $Page->mimpKarburant->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpKarburant"><?= $Page->mimpKarburant->caption() ?></span></td>
        <td data-name="mimpKarburant"<?= $Page->mimpKarburant->cellAttributes() ?>>
<span id="el_makina_importi_mimpKarburant">
<span<?= $Page->mimpKarburant->viewAttributes() ?>>
<?= $Page->mimpKarburant->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpKambio->Visible) { // mimpKambio ?>
    <tr id="r_mimpKambio"<?= $Page->mimpKambio->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpKambio"><?= $Page->mimpKambio->caption() ?></span></td>
        <td data-name="mimpKambio"<?= $Page->mimpKambio->cellAttributes() ?>>
<span id="el_makina_importi_mimpKambio">
<span<?= $Page->mimpKambio->viewAttributes() ?>>
<?= $Page->mimpKambio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpNgjyra->Visible) { // mimpNgjyra ?>
    <tr id="r_mimpNgjyra"<?= $Page->mimpNgjyra->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpNgjyra"><?= $Page->mimpNgjyra->caption() ?></span></td>
        <td data-name="mimpNgjyra"<?= $Page->mimpNgjyra->cellAttributes() ?>>
<span id="el_makina_importi_mimpNgjyra">
<span<?= $Page->mimpNgjyra->viewAttributes() ?>>
<?= $Page->mimpNgjyra->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpPrejardhja->Visible) { // mimpPrejardhja ?>
    <tr id="r_mimpPrejardhja"<?= $Page->mimpPrejardhja->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpPrejardhja"><?= $Page->mimpPrejardhja->caption() ?></span></td>
        <td data-name="mimpPrejardhja"<?= $Page->mimpPrejardhja->cellAttributes() ?>>
<span id="el_makina_importi_mimpPrejardhja">
<span<?= $Page->mimpPrejardhja->viewAttributes() ?>>
<?= $Page->mimpPrejardhja->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpInfo->Visible) { // mimpInfo ?>
    <tr id="r_mimpInfo"<?= $Page->mimpInfo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpInfo"><?= $Page->mimpInfo->caption() ?></span></td>
        <td data-name="mimpInfo"<?= $Page->mimpInfo->cellAttributes() ?>>
<span id="el_makina_importi_mimpInfo">
<span<?= $Page->mimpInfo->viewAttributes() ?>>
<?= $Page->mimpInfo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpCmimiBlerjes->Visible) { // mimpCmimiBlerjes ?>
    <tr id="r_mimpCmimiBlerjes"<?= $Page->mimpCmimiBlerjes->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpCmimiBlerjes"><?= $Page->mimpCmimiBlerjes->caption() ?></span></td>
        <td data-name="mimpCmimiBlerjes"<?= $Page->mimpCmimiBlerjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpCmimiBlerjes">
<span<?= $Page->mimpCmimiBlerjes->viewAttributes() ?>>
<?= $Page->mimpCmimiBlerjes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpDogana->Visible) { // mimpDogana ?>
    <tr id="r_mimpDogana"<?= $Page->mimpDogana->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpDogana"><?= $Page->mimpDogana->caption() ?></span></td>
        <td data-name="mimpDogana"<?= $Page->mimpDogana->cellAttributes() ?>>
<span id="el_makina_importi_mimpDogana">
<span<?= $Page->mimpDogana->viewAttributes() ?>>
<?= $Page->mimpDogana->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpTransporti->Visible) { // mimpTransporti ?>
    <tr id="r_mimpTransporti"<?= $Page->mimpTransporti->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpTransporti"><?= $Page->mimpTransporti->caption() ?></span></td>
        <td data-name="mimpTransporti"<?= $Page->mimpTransporti->cellAttributes() ?>>
<span id="el_makina_importi_mimpTransporti">
<span<?= $Page->mimpTransporti->viewAttributes() ?>>
<?= $Page->mimpTransporti->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpTjera->Visible) { // mimpTjera ?>
    <tr id="r_mimpTjera"<?= $Page->mimpTjera->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpTjera"><?= $Page->mimpTjera->caption() ?></span></td>
        <td data-name="mimpTjera"<?= $Page->mimpTjera->cellAttributes() ?>>
<span id="el_makina_importi_mimpTjera">
<span<?= $Page->mimpTjera->viewAttributes() ?>>
<?= $Page->mimpTjera->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpDtHyrjes->Visible) { // mimpDtHyrjes ?>
    <tr id="r_mimpDtHyrjes"<?= $Page->mimpDtHyrjes->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpDtHyrjes"><?= $Page->mimpDtHyrjes->caption() ?></span></td>
        <td data-name="mimpDtHyrjes"<?= $Page->mimpDtHyrjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpDtHyrjes">
<span<?= $Page->mimpDtHyrjes->viewAttributes() ?>>
<?= $Page->mimpDtHyrjes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpCmimiShitjes->Visible) { // mimpCmimiShitjes ?>
    <tr id="r_mimpCmimiShitjes"<?= $Page->mimpCmimiShitjes->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpCmimiShitjes"><?= $Page->mimpCmimiShitjes->caption() ?></span></td>
        <td data-name="mimpCmimiShitjes"<?= $Page->mimpCmimiShitjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpCmimiShitjes">
<span<?= $Page->mimpCmimiShitjes->viewAttributes() ?>>
<?= $Page->mimpCmimiShitjes->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mimpGati->Visible) { // mimpGati ?>
    <tr id="r_mimpGati"<?= $Page->mimpGati->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_mimpGati"><?= $Page->mimpGati->caption() ?></span></td>
        <td data-name="mimpGati"<?= $Page->mimpGati->cellAttributes() ?>>
<span id="el_makina_importi_mimpGati">
<span<?= $Page->mimpGati->viewAttributes() ?>>
<?= $Page->mimpGati->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
<?php
    if (in_array("makina_importi_sherbime", explode(",", $Page->getCurrentDetailTable())) && $makina_importi_sherbime->DetailView) {
?>
<?php if ($Page->getCurrentDetailTable() != "") { ?>
<h4 class="ew-detail-caption"><?= $Language->tablePhrase("makina_importi_sherbime", "TblCaption") ?></h4>
<?php } ?>
<?php include_once "MakinaImportiSherbimeGrid.php" ?>
<?php } ?>
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
