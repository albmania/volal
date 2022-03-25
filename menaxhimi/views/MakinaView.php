<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina: currentTable } });
var currentForm, currentPageID;
var fmakinaview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakinaview = new ew.Form("fmakinaview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmakinaview;
    loadjs.done("fmakinaview");
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
<form name="fmakinaview" id="fmakinaview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->makinaID->Visible) { // makinaID ?>
    <tr id="r_makinaID"<?= $Page->makinaID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaID"><?= $Page->makinaID->caption() ?></span></td>
        <td data-name="makinaID"<?= $Page->makinaID->cellAttributes() ?>>
<span id="el_makina_makinaID">
<span<?= $Page->makinaID->viewAttributes() ?>>
<?= $Page->makinaID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaKlienti->Visible) { // makinaKlienti ?>
    <tr id="r_makinaKlienti"<?= $Page->makinaKlienti->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaKlienti"><?= $Page->makinaKlienti->caption() ?></span></td>
        <td data-name="makinaKlienti"<?= $Page->makinaKlienti->cellAttributes() ?>>
<span id="el_makina_makinaKlienti">
<span<?= $Page->makinaKlienti->viewAttributes() ?>>
<?= $Page->makinaKlienti->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaMarka->Visible) { // makinaMarka ?>
    <tr id="r_makinaMarka"<?= $Page->makinaMarka->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaMarka"><?= $Page->makinaMarka->caption() ?></span></td>
        <td data-name="makinaMarka"<?= $Page->makinaMarka->cellAttributes() ?>>
<span id="el_makina_makinaMarka">
<span<?= $Page->makinaMarka->viewAttributes() ?>>
<?= $Page->makinaMarka->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaModeli->Visible) { // makinaModeli ?>
    <tr id="r_makinaModeli"<?= $Page->makinaModeli->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaModeli"><?= $Page->makinaModeli->caption() ?></span></td>
        <td data-name="makinaModeli"<?= $Page->makinaModeli->cellAttributes() ?>>
<span id="el_makina_makinaModeli">
<span<?= $Page->makinaModeli->viewAttributes() ?>>
<?= $Page->makinaModeli->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaKarburanti->Visible) { // makinaKarburanti ?>
    <tr id="r_makinaKarburanti"<?= $Page->makinaKarburanti->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaKarburanti"><?= $Page->makinaKarburanti->caption() ?></span></td>
        <td data-name="makinaKarburanti"<?= $Page->makinaKarburanti->cellAttributes() ?>>
<span id="el_makina_makinaKarburanti">
<span<?= $Page->makinaKarburanti->viewAttributes() ?>>
<?= $Page->makinaKarburanti->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaMadhesiaMotorrit->Visible) { // makinaMadhesiaMotorrit ?>
    <tr id="r_makinaMadhesiaMotorrit"<?= $Page->makinaMadhesiaMotorrit->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaMadhesiaMotorrit"><?= $Page->makinaMadhesiaMotorrit->caption() ?></span></td>
        <td data-name="makinaMadhesiaMotorrit"<?= $Page->makinaMadhesiaMotorrit->cellAttributes() ?>>
<span id="el_makina_makinaMadhesiaMotorrit">
<span<?= $Page->makinaMadhesiaMotorrit->viewAttributes() ?>>
<?= $Page->makinaMadhesiaMotorrit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaVitiProdhimit->Visible) { // makinaVitiProdhimit ?>
    <tr id="r_makinaVitiProdhimit"<?= $Page->makinaVitiProdhimit->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaVitiProdhimit"><?= $Page->makinaVitiProdhimit->caption() ?></span></td>
        <td data-name="makinaVitiProdhimit"<?= $Page->makinaVitiProdhimit->cellAttributes() ?>>
<span id="el_makina_makinaVitiProdhimit">
<span<?= $Page->makinaVitiProdhimit->viewAttributes() ?>>
<?= $Page->makinaVitiProdhimit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaNgjyra->Visible) { // makinaNgjyra ?>
    <tr id="r_makinaNgjyra"<?= $Page->makinaNgjyra->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaNgjyra"><?= $Page->makinaNgjyra->caption() ?></span></td>
        <td data-name="makinaNgjyra"<?= $Page->makinaNgjyra->cellAttributes() ?>>
<span id="el_makina_makinaNgjyra">
<span<?= $Page->makinaNgjyra->viewAttributes() ?>>
<?= $Page->makinaNgjyra->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaInfoShtese->Visible) { // makinaInfoShtese ?>
    <tr id="r_makinaInfoShtese"<?= $Page->makinaInfoShtese->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaInfoShtese"><?= $Page->makinaInfoShtese->caption() ?></span></td>
        <td data-name="makinaInfoShtese"<?= $Page->makinaInfoShtese->cellAttributes() ?>>
<span id="el_makina_makinaInfoShtese">
<span<?= $Page->makinaInfoShtese->viewAttributes() ?>>
<?= $Page->makinaInfoShtese->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaVitiRegAL->Visible) { // makinaVitiRegAL ?>
    <tr id="r_makinaVitiRegAL"<?= $Page->makinaVitiRegAL->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaVitiRegAL"><?= $Page->makinaVitiRegAL->caption() ?></span></td>
        <td data-name="makinaVitiRegAL"<?= $Page->makinaVitiRegAL->cellAttributes() ?>>
<span id="el_makina_makinaVitiRegAL">
<span<?= $Page->makinaVitiRegAL->viewAttributes() ?>>
<?= $Page->makinaVitiRegAL->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaTarga->Visible) { // makinaTarga ?>
    <tr id="r_makinaTarga"<?= $Page->makinaTarga->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaTarga"><?= $Page->makinaTarga->caption() ?></span></td>
        <td data-name="makinaTarga"<?= $Page->makinaTarga->cellAttributes() ?>>
<span id="el_makina_makinaTarga">
<span<?= $Page->makinaTarga->viewAttributes() ?>>
<?= $Page->makinaTarga->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaNrShasie->Visible) { // makinaNrShasie ?>
    <tr id="r_makinaNrShasie"<?= $Page->makinaNrShasie->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaNrShasie"><?= $Page->makinaNrShasie->caption() ?></span></td>
        <td data-name="makinaNrShasie"<?= $Page->makinaNrShasie->cellAttributes() ?>>
<span id="el_makina_makinaNrShasie">
<span<?= $Page->makinaNrShasie->viewAttributes() ?>>
<?= $Page->makinaNrShasie->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaPrejardhja->Visible) { // makinaPrejardhja ?>
    <tr id="r_makinaPrejardhja"<?= $Page->makinaPrejardhja->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaPrejardhja"><?= $Page->makinaPrejardhja->caption() ?></span></td>
        <td data-name="makinaPrejardhja"<?= $Page->makinaPrejardhja->cellAttributes() ?>>
<span id="el_makina_makinaPrejardhja">
<span<?= $Page->makinaPrejardhja->viewAttributes() ?>>
<?= $Page->makinaPrejardhja->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaShiturVOLAL->Visible) { // makinaShiturVOLAL ?>
    <tr id="r_makinaShiturVOLAL"<?= $Page->makinaShiturVOLAL->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaShiturVOLAL"><?= $Page->makinaShiturVOLAL->caption() ?></span></td>
        <td data-name="makinaShiturVOLAL"<?= $Page->makinaShiturVOLAL->cellAttributes() ?>>
<span id="el_makina_makinaShiturVOLAL">
<span<?= $Page->makinaShiturVOLAL->viewAttributes() ?>>
<?= $Page->makinaShiturVOLAL->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaAutori->Visible) { // makinaAutori ?>
    <tr id="r_makinaAutori"<?= $Page->makinaAutori->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaAutori"><?= $Page->makinaAutori->caption() ?></span></td>
        <td data-name="makinaAutori"<?= $Page->makinaAutori->cellAttributes() ?>>
<span id="el_makina_makinaAutori">
<span<?= $Page->makinaAutori->viewAttributes() ?>>
<?= $Page->makinaAutori->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaShtuar->Visible) { // makinaShtuar ?>
    <tr id="r_makinaShtuar"<?= $Page->makinaShtuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaShtuar"><?= $Page->makinaShtuar->caption() ?></span></td>
        <td data-name="makinaShtuar"<?= $Page->makinaShtuar->cellAttributes() ?>>
<span id="el_makina_makinaShtuar">
<span<?= $Page->makinaShtuar->viewAttributes() ?>>
<?= $Page->makinaShtuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->makinaModifikuar->Visible) { // makinaModifikuar ?>
    <tr id="r_makinaModifikuar"<?= $Page->makinaModifikuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_makinaModifikuar"><?= $Page->makinaModifikuar->caption() ?></span></td>
        <td data-name="makinaModifikuar"<?= $Page->makinaModifikuar->cellAttributes() ?>>
<span id="el_makina_makinaModifikuar">
<span<?= $Page->makinaModifikuar->viewAttributes() ?>>
<?= $Page->makinaModifikuar->getViewValue() ?></span>
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
