<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaTipiView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_tipi: currentTable } });
var currentForm, currentPageID;
var fmakina_tipiview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_tipiview = new ew.Form("fmakina_tipiview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmakina_tipiview;
    loadjs.done("fmakina_tipiview");
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
<form name="fmakina_tipiview" id="fmakina_tipiview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_tipi">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->mtipiID->Visible) { // mtipiID ?>
    <tr id="r_mtipiID"<?= $Page->mtipiID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_tipi_mtipiID"><?= $Page->mtipiID->caption() ?></span></td>
        <td data-name="mtipiID"<?= $Page->mtipiID->cellAttributes() ?>>
<span id="el_makina_tipi_mtipiID">
<span<?= $Page->mtipiID->viewAttributes() ?>>
<?= $Page->mtipiID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mtipiMarka->Visible) { // mtipiMarka ?>
    <tr id="r_mtipiMarka"<?= $Page->mtipiMarka->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_tipi_mtipiMarka"><?= $Page->mtipiMarka->caption() ?></span></td>
        <td data-name="mtipiMarka"<?= $Page->mtipiMarka->cellAttributes() ?>>
<span id="el_makina_tipi_mtipiMarka">
<span<?= $Page->mtipiMarka->viewAttributes() ?>>
<?= $Page->mtipiMarka->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mtipiModeli->Visible) { // mtipiModeli ?>
    <tr id="r_mtipiModeli"<?= $Page->mtipiModeli->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_tipi_mtipiModeli"><?= $Page->mtipiModeli->caption() ?></span></td>
        <td data-name="mtipiModeli"<?= $Page->mtipiModeli->cellAttributes() ?>>
<span id="el_makina_tipi_mtipiModeli">
<span<?= $Page->mtipiModeli->viewAttributes() ?>>
<?= $Page->mtipiModeli->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mtipiTipi->Visible) { // mtipiTipi ?>
    <tr id="r_mtipiTipi"<?= $Page->mtipiTipi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_tipi_mtipiTipi"><?= $Page->mtipiTipi->caption() ?></span></td>
        <td data-name="mtipiTipi"<?= $Page->mtipiTipi->cellAttributes() ?>>
<span id="el_makina_tipi_mtipiTipi">
<span<?= $Page->mtipiTipi->viewAttributes() ?>>
<?= $Page->mtipiTipi->getViewValue() ?></span>
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
