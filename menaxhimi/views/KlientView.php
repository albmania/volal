<?php

namespace PHPMaker2022\volalservice;

// Page object
$KlientView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { klient: currentTable } });
var currentForm, currentPageID;
var fklientview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fklientview = new ew.Form("fklientview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fklientview;
    loadjs.done("fklientview");
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
<form name="fklientview" id="fklientview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="klient">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->klientID->Visible) { // klientID ?>
    <tr id="r_klientID"<?= $Page->klientID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientID"><?= $Page->klientID->caption() ?></span></td>
        <td data-name="klientID"<?= $Page->klientID->cellAttributes() ?>>
<span id="el_klient_klientID">
<span<?= $Page->klientID->viewAttributes() ?>>
<?= $Page->klientID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientTipi->Visible) { // klientTipi ?>
    <tr id="r_klientTipi"<?= $Page->klientTipi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientTipi"><?= $Page->klientTipi->caption() ?></span></td>
        <td data-name="klientTipi"<?= $Page->klientTipi->cellAttributes() ?>>
<span id="el_klient_klientTipi">
<span<?= $Page->klientTipi->viewAttributes() ?>>
<?= $Page->klientTipi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientEmertimi->Visible) { // klientEmertimi ?>
    <tr id="r_klientEmertimi"<?= $Page->klientEmertimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientEmertimi"><?= $Page->klientEmertimi->caption() ?></span></td>
        <td data-name="klientEmertimi"<?= $Page->klientEmertimi->cellAttributes() ?>>
<span id="el_klient_klientEmertimi">
<span<?= $Page->klientEmertimi->viewAttributes() ?>>
<?= $Page->klientEmertimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientNIPT->Visible) { // klientNIPT ?>
    <tr id="r_klientNIPT"<?= $Page->klientNIPT->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientNIPT"><?= $Page->klientNIPT->caption() ?></span></td>
        <td data-name="klientNIPT"<?= $Page->klientNIPT->cellAttributes() ?>>
<span id="el_klient_klientNIPT">
<span<?= $Page->klientNIPT->viewAttributes() ?>>
<?= $Page->klientNIPT->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientAdresa->Visible) { // klientAdresa ?>
    <tr id="r_klientAdresa"<?= $Page->klientAdresa->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientAdresa"><?= $Page->klientAdresa->caption() ?></span></td>
        <td data-name="klientAdresa"<?= $Page->klientAdresa->cellAttributes() ?>>
<span id="el_klient_klientAdresa">
<span<?= $Page->klientAdresa->viewAttributes() ?>>
<?= $Page->klientAdresa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientQyteti->Visible) { // klientQyteti ?>
    <tr id="r_klientQyteti"<?= $Page->klientQyteti->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientQyteti"><?= $Page->klientQyteti->caption() ?></span></td>
        <td data-name="klientQyteti"<?= $Page->klientQyteti->cellAttributes() ?>>
<span id="el_klient_klientQyteti">
<span<?= $Page->klientQyteti->viewAttributes() ?>>
<?= $Page->klientQyteti->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientTel1->Visible) { // klientTel1 ?>
    <tr id="r_klientTel1"<?= $Page->klientTel1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientTel1"><?= $Page->klientTel1->caption() ?></span></td>
        <td data-name="klientTel1"<?= $Page->klientTel1->cellAttributes() ?>>
<span id="el_klient_klientTel1">
<span<?= $Page->klientTel1->viewAttributes() ?>>
<?= $Page->klientTel1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientTel2->Visible) { // klientTel2 ?>
    <tr id="r_klientTel2"<?= $Page->klientTel2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientTel2"><?= $Page->klientTel2->caption() ?></span></td>
        <td data-name="klientTel2"<?= $Page->klientTel2->cellAttributes() ?>>
<span id="el_klient_klientTel2">
<span<?= $Page->klientTel2->viewAttributes() ?>>
<?= $Page->klientTel2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientEmail->Visible) { // klientEmail ?>
    <tr id="r_klientEmail"<?= $Page->klientEmail->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientEmail"><?= $Page->klientEmail->caption() ?></span></td>
        <td data-name="klientEmail"<?= $Page->klientEmail->cellAttributes() ?>>
<span id="el_klient_klientEmail">
<span<?= $Page->klientEmail->viewAttributes() ?>>
<?= $Page->klientEmail->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientAutori->Visible) { // klientAutori ?>
    <tr id="r_klientAutori"<?= $Page->klientAutori->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientAutori"><?= $Page->klientAutori->caption() ?></span></td>
        <td data-name="klientAutori"<?= $Page->klientAutori->cellAttributes() ?>>
<span id="el_klient_klientAutori">
<span<?= $Page->klientAutori->viewAttributes() ?>>
<?= $Page->klientAutori->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientShtuar->Visible) { // klientShtuar ?>
    <tr id="r_klientShtuar"<?= $Page->klientShtuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientShtuar"><?= $Page->klientShtuar->caption() ?></span></td>
        <td data-name="klientShtuar"<?= $Page->klientShtuar->cellAttributes() ?>>
<span id="el_klient_klientShtuar">
<span<?= $Page->klientShtuar->viewAttributes() ?>>
<?= $Page->klientShtuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->klientModifikuar->Visible) { // klientModifikuar ?>
    <tr id="r_klientModifikuar"<?= $Page->klientModifikuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_klient_klientModifikuar"><?= $Page->klientModifikuar->caption() ?></span></td>
        <td data-name="klientModifikuar"<?= $Page->klientModifikuar->cellAttributes() ?>>
<span id="el_klient_klientModifikuar">
<span<?= $Page->klientModifikuar->viewAttributes() ?>>
<?= $Page->klientModifikuar->getViewValue() ?></span>
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
