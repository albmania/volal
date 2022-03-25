<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaShitjeView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_shitje: currentTable } });
var currentForm, currentPageID;
var fmakina_shitjeview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_shitjeview = new ew.Form("fmakina_shitjeview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmakina_shitjeview;
    loadjs.done("fmakina_shitjeview");
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
<form name="fmakina_shitjeview" id="fmakina_shitjeview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_shitje">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->mshitjeID->Visible) { // mshitjeID ?>
    <tr id="r_mshitjeID"<?= $Page->mshitjeID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeID"><?= $Page->mshitjeID->caption() ?></span></td>
        <td data-name="mshitjeID"<?= $Page->mshitjeID->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeID">
<span<?= $Page->mshitjeID->viewAttributes() ?>>
<?= $Page->mshitjeID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeMarka->Visible) { // mshitjeMarka ?>
    <tr id="r_mshitjeMarka"<?= $Page->mshitjeMarka->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeMarka"><?= $Page->mshitjeMarka->caption() ?></span></td>
        <td data-name="mshitjeMarka"<?= $Page->mshitjeMarka->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeMarka">
<span<?= $Page->mshitjeMarka->viewAttributes() ?>>
<?= $Page->mshitjeMarka->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeModeli->Visible) { // mshitjeModeli ?>
    <tr id="r_mshitjeModeli"<?= $Page->mshitjeModeli->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeModeli"><?= $Page->mshitjeModeli->caption() ?></span></td>
        <td data-name="mshitjeModeli"<?= $Page->mshitjeModeli->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeModeli">
<span<?= $Page->mshitjeModeli->viewAttributes() ?>>
<?= $Page->mshitjeModeli->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeTipi->Visible) { // mshitjeTipi ?>
    <tr id="r_mshitjeTipi"<?= $Page->mshitjeTipi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeTipi"><?= $Page->mshitjeTipi->caption() ?></span></td>
        <td data-name="mshitjeTipi"<?= $Page->mshitjeTipi->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeTipi">
<span<?= $Page->mshitjeTipi->viewAttributes() ?>>
<?= $Page->mshitjeTipi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeStruktura->Visible) { // mshitjeStruktura ?>
    <tr id="r_mshitjeStruktura"<?= $Page->mshitjeStruktura->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeStruktura"><?= $Page->mshitjeStruktura->caption() ?></span></td>
        <td data-name="mshitjeStruktura"<?= $Page->mshitjeStruktura->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeStruktura">
<span<?= $Page->mshitjeStruktura->viewAttributes() ?>>
<?= $Page->mshitjeStruktura->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeKapacitetiMotorrit->Visible) { // mshitjeKapacitetiMotorrit ?>
    <tr id="r_mshitjeKapacitetiMotorrit"<?= $Page->mshitjeKapacitetiMotorrit->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeKapacitetiMotorrit"><?= $Page->mshitjeKapacitetiMotorrit->caption() ?></span></td>
        <td data-name="mshitjeKapacitetiMotorrit"<?= $Page->mshitjeKapacitetiMotorrit->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKapacitetiMotorrit">
<span<?= $Page->mshitjeKapacitetiMotorrit->viewAttributes() ?>>
<?= $Page->mshitjeKapacitetiMotorrit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeVitiProdhimit->Visible) { // mshitjeVitiProdhimit ?>
    <tr id="r_mshitjeVitiProdhimit"<?= $Page->mshitjeVitiProdhimit->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeVitiProdhimit"><?= $Page->mshitjeVitiProdhimit->caption() ?></span></td>
        <td data-name="mshitjeVitiProdhimit"<?= $Page->mshitjeVitiProdhimit->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeVitiProdhimit">
<span<?= $Page->mshitjeVitiProdhimit->viewAttributes() ?>>
<?= $Page->mshitjeVitiProdhimit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeKarburant->Visible) { // mshitjeKarburant ?>
    <tr id="r_mshitjeKarburant"<?= $Page->mshitjeKarburant->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeKarburant"><?= $Page->mshitjeKarburant->caption() ?></span></td>
        <td data-name="mshitjeKarburant"<?= $Page->mshitjeKarburant->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKarburant">
<span<?= $Page->mshitjeKarburant->viewAttributes() ?>>
<?= $Page->mshitjeKarburant->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeNgjyra->Visible) { // mshitjeNgjyra ?>
    <tr id="r_mshitjeNgjyra"<?= $Page->mshitjeNgjyra->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeNgjyra"><?= $Page->mshitjeNgjyra->caption() ?></span></td>
        <td data-name="mshitjeNgjyra"<?= $Page->mshitjeNgjyra->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeNgjyra">
<span<?= $Page->mshitjeNgjyra->viewAttributes() ?>>
<?= $Page->mshitjeNgjyra->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeNrVendeve->Visible) { // mshitjeNrVendeve ?>
    <tr id="r_mshitjeNrVendeve"<?= $Page->mshitjeNrVendeve->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeNrVendeve"><?= $Page->mshitjeNrVendeve->caption() ?></span></td>
        <td data-name="mshitjeNrVendeve"<?= $Page->mshitjeNrVendeve->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeNrVendeve">
<span<?= $Page->mshitjeNrVendeve->viewAttributes() ?>>
<?= $Page->mshitjeNrVendeve->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeKambio->Visible) { // mshitjeKambio ?>
    <tr id="r_mshitjeKambio"<?= $Page->mshitjeKambio->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeKambio"><?= $Page->mshitjeKambio->caption() ?></span></td>
        <td data-name="mshitjeKambio"<?= $Page->mshitjeKambio->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKambio">
<span<?= $Page->mshitjeKambio->viewAttributes() ?>>
<?= $Page->mshitjeKambio->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjePrejardhja->Visible) { // mshitjePrejardhja ?>
    <tr id="r_mshitjePrejardhja"<?= $Page->mshitjePrejardhja->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjePrejardhja"><?= $Page->mshitjePrejardhja->caption() ?></span></td>
        <td data-name="mshitjePrejardhja"<?= $Page->mshitjePrejardhja->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjePrejardhja">
<span<?= $Page->mshitjePrejardhja->viewAttributes() ?>>
<?= $Page->mshitjePrejardhja->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeTargaAL->Visible) { // mshitjeTargaAL ?>
    <tr id="r_mshitjeTargaAL"<?= $Page->mshitjeTargaAL->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeTargaAL"><?= $Page->mshitjeTargaAL->caption() ?></span></td>
        <td data-name="mshitjeTargaAL"<?= $Page->mshitjeTargaAL->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeTargaAL">
<span<?= $Page->mshitjeTargaAL->viewAttributes() ?>>
<?= $Page->mshitjeTargaAL->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeKilometra->Visible) { // mshitjeKilometra ?>
    <tr id="r_mshitjeKilometra"<?= $Page->mshitjeKilometra->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeKilometra"><?= $Page->mshitjeKilometra->caption() ?></span></td>
        <td data-name="mshitjeKilometra"<?= $Page->mshitjeKilometra->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKilometra">
<span<?= $Page->mshitjeKilometra->viewAttributes() ?>>
<?= $Page->mshitjeKilometra->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeFotografi->Visible) { // mshitjeFotografi ?>
    <tr id="r_mshitjeFotografi"<?= $Page->mshitjeFotografi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeFotografi"><?= $Page->mshitjeFotografi->caption() ?></span></td>
        <td data-name="mshitjeFotografi"<?= $Page->mshitjeFotografi->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeFotografi">
<span>
<?= GetFileViewTag($Page->mshitjeFotografi, $Page->mshitjeFotografi->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjePershkrimi->Visible) { // mshitjePershkrimi ?>
    <tr id="r_mshitjePershkrimi"<?= $Page->mshitjePershkrimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjePershkrimi"><?= $Page->mshitjePershkrimi->caption() ?></span></td>
        <td data-name="mshitjePershkrimi"<?= $Page->mshitjePershkrimi->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjePershkrimi">
<span<?= $Page->mshitjePershkrimi->viewAttributes() ?>>
<?= $Page->mshitjePershkrimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeCmimi->Visible) { // mshitjeCmimi ?>
    <tr id="r_mshitjeCmimi"<?= $Page->mshitjeCmimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeCmimi"><?= $Page->mshitjeCmimi->caption() ?></span></td>
        <td data-name="mshitjeCmimi"<?= $Page->mshitjeCmimi->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeCmimi">
<span<?= $Page->mshitjeCmimi->viewAttributes() ?>>
<?= $Page->mshitjeCmimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeIndex->Visible) { // mshitjeIndex ?>
    <tr id="r_mshitjeIndex"<?= $Page->mshitjeIndex->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeIndex"><?= $Page->mshitjeIndex->caption() ?></span></td>
        <td data-name="mshitjeIndex"<?= $Page->mshitjeIndex->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeIndex">
<span<?= $Page->mshitjeIndex->viewAttributes() ?>>
<?= $Page->mshitjeIndex->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjePromo->Visible) { // mshitjePromo ?>
    <tr id="r_mshitjePromo"<?= $Page->mshitjePromo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjePromo"><?= $Page->mshitjePromo->caption() ?></span></td>
        <td data-name="mshitjePromo"<?= $Page->mshitjePromo->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjePromo">
<span<?= $Page->mshitjePromo->viewAttributes() ?>>
<?= $Page->mshitjePromo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeAktiv->Visible) { // mshitjeAktiv ?>
    <tr id="r_mshitjeAktiv"<?= $Page->mshitjeAktiv->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeAktiv"><?= $Page->mshitjeAktiv->caption() ?></span></td>
        <td data-name="mshitjeAktiv"<?= $Page->mshitjeAktiv->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeAktiv">
<span<?= $Page->mshitjeAktiv->viewAttributes() ?>>
<?= $Page->mshitjeAktiv->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeShitur->Visible) { // mshitjeShitur ?>
    <tr id="r_mshitjeShitur"<?= $Page->mshitjeShitur->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeShitur"><?= $Page->mshitjeShitur->caption() ?></span></td>
        <td data-name="mshitjeShitur"<?= $Page->mshitjeShitur->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeShitur">
<span<?= $Page->mshitjeShitur->viewAttributes() ?>>
<?= $Page->mshitjeShitur->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeAutori->Visible) { // mshitjeAutori ?>
    <tr id="r_mshitjeAutori"<?= $Page->mshitjeAutori->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeAutori"><?= $Page->mshitjeAutori->caption() ?></span></td>
        <td data-name="mshitjeAutori"<?= $Page->mshitjeAutori->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeAutori">
<span<?= $Page->mshitjeAutori->viewAttributes() ?>>
<?= $Page->mshitjeAutori->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeKrijuar->Visible) { // mshitjeKrijuar ?>
    <tr id="r_mshitjeKrijuar"<?= $Page->mshitjeKrijuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeKrijuar"><?= $Page->mshitjeKrijuar->caption() ?></span></td>
        <td data-name="mshitjeKrijuar"<?= $Page->mshitjeKrijuar->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeKrijuar">
<span<?= $Page->mshitjeKrijuar->viewAttributes() ?>>
<?= $Page->mshitjeKrijuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mshitjeAzhornuar->Visible) { // mshitjeAzhornuar ?>
    <tr id="r_mshitjeAzhornuar"<?= $Page->mshitjeAzhornuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_shitje_mshitjeAzhornuar"><?= $Page->mshitjeAzhornuar->caption() ?></span></td>
        <td data-name="mshitjeAzhornuar"<?= $Page->mshitjeAzhornuar->cellAttributes() ?>>
<span id="el_makina_shitje_mshitjeAzhornuar">
<span<?= $Page->mshitjeAzhornuar->viewAttributes() ?>>
<?= $Page->mshitjeAzhornuar->getViewValue() ?></span>
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
