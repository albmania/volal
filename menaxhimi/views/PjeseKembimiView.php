<?php

namespace PHPMaker2022\volalservice;

// Page object
$PjeseKembimiView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pjese_kembimi: currentTable } });
var currentForm, currentPageID;
var fpjese_kembimiview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fpjese_kembimiview = new ew.Form("fpjese_kembimiview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fpjese_kembimiview;
    loadjs.done("fpjese_kembimiview");
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
<form name="fpjese_kembimiview" id="fpjese_kembimiview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pjese_kembimi">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->pjeseID->Visible) { // pjeseID ?>
    <tr id="r_pjeseID"<?= $Page->pjeseID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseID"><?= $Page->pjeseID->caption() ?></span></td>
        <td data-name="pjeseID"<?= $Page->pjeseID->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseID">
<span<?= $Page->pjeseID->viewAttributes() ?>>
<?= $Page->pjeseID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseGjendja->Visible) { // pjeseGjendja ?>
    <tr id="r_pjeseGjendja"<?= $Page->pjeseGjendja->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseGjendja"><?= $Page->pjeseGjendja->caption() ?></span></td>
        <td data-name="pjeseGjendja"<?= $Page->pjeseGjendja->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseGjendja">
<span<?= $Page->pjeseGjendja->viewAttributes() ?>>
<?= $Page->pjeseGjendja->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseKodiVolvo->Visible) { // pjeseKodiVolvo ?>
    <tr id="r_pjeseKodiVolvo"<?= $Page->pjeseKodiVolvo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseKodiVolvo"><?= $Page->pjeseKodiVolvo->caption() ?></span></td>
        <td data-name="pjeseKodiVolvo"<?= $Page->pjeseKodiVolvo->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseKodiVolvo">
<span<?= $Page->pjeseKodiVolvo->viewAttributes() ?>>
<?= $Page->pjeseKodiVolvo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseKodiProdhuesi->Visible) { // pjeseKodiProdhuesi ?>
    <tr id="r_pjeseKodiProdhuesi"<?= $Page->pjeseKodiProdhuesi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseKodiProdhuesi"><?= $Page->pjeseKodiProdhuesi->caption() ?></span></td>
        <td data-name="pjeseKodiProdhuesi"<?= $Page->pjeseKodiProdhuesi->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseKodiProdhuesi">
<span<?= $Page->pjeseKodiProdhuesi->viewAttributes() ?>>
<?= $Page->pjeseKodiProdhuesi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseProdhuesi->Visible) { // pjeseProdhuesi ?>
    <tr id="r_pjeseProdhuesi"<?= $Page->pjeseProdhuesi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseProdhuesi"><?= $Page->pjeseProdhuesi->caption() ?></span></td>
        <td data-name="pjeseProdhuesi"<?= $Page->pjeseProdhuesi->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseProdhuesi">
<span<?= $Page->pjeseProdhuesi->viewAttributes() ?>>
<?= $Page->pjeseProdhuesi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjesePerMarke->Visible) { // pjesePerMarke ?>
    <tr id="r_pjesePerMarke"<?= $Page->pjesePerMarke->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjesePerMarke"><?= $Page->pjesePerMarke->caption() ?></span></td>
        <td data-name="pjesePerMarke"<?= $Page->pjesePerMarke->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjesePerMarke">
<span<?= $Page->pjesePerMarke->viewAttributes() ?>>
<?= $Page->pjesePerMarke->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjesePerModel->Visible) { // pjesePerModel ?>
    <tr id="r_pjesePerModel"<?= $Page->pjesePerModel->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjesePerModel"><?= $Page->pjesePerModel->caption() ?></span></td>
        <td data-name="pjesePerModel"<?= $Page->pjesePerModel->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjesePerModel">
<span<?= $Page->pjesePerModel->viewAttributes() ?>>
<?= $Page->pjesePerModel->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjesePerVitProdhimi->Visible) { // pjesePerVitProdhimi ?>
    <tr id="r_pjesePerVitProdhimi"<?= $Page->pjesePerVitProdhimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjesePerVitProdhimi"><?= $Page->pjesePerVitProdhimi->caption() ?></span></td>
        <td data-name="pjesePerVitProdhimi"<?= $Page->pjesePerVitProdhimi->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjesePerVitProdhimi">
<span<?= $Page->pjesePerVitProdhimi->viewAttributes() ?>>
<?= $Page->pjesePerVitProdhimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseCmimBlerje->Visible) { // pjeseCmimBlerje ?>
    <tr id="r_pjeseCmimBlerje"<?= $Page->pjeseCmimBlerje->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseCmimBlerje"><?= $Page->pjeseCmimBlerje->caption() ?></span></td>
        <td data-name="pjeseCmimBlerje"<?= $Page->pjeseCmimBlerje->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseCmimBlerje">
<span<?= $Page->pjeseCmimBlerje->viewAttributes() ?>>
<?= $Page->pjeseCmimBlerje->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseCmimShitje->Visible) { // pjeseCmimShitje ?>
    <tr id="r_pjeseCmimShitje"<?= $Page->pjeseCmimShitje->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseCmimShitje"><?= $Page->pjeseCmimShitje->caption() ?></span></td>
        <td data-name="pjeseCmimShitje"<?= $Page->pjeseCmimShitje->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseCmimShitje">
<span<?= $Page->pjeseCmimShitje->viewAttributes() ?>>
<?= $Page->pjeseCmimShitje->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseAutori->Visible) { // pjeseAutori ?>
    <tr id="r_pjeseAutori"<?= $Page->pjeseAutori->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseAutori"><?= $Page->pjeseAutori->caption() ?></span></td>
        <td data-name="pjeseAutori"<?= $Page->pjeseAutori->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseAutori">
<span<?= $Page->pjeseAutori->viewAttributes() ?>>
<?= $Page->pjeseAutori->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseShtuar->Visible) { // pjeseShtuar ?>
    <tr id="r_pjeseShtuar"<?= $Page->pjeseShtuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseShtuar"><?= $Page->pjeseShtuar->caption() ?></span></td>
        <td data-name="pjeseShtuar"<?= $Page->pjeseShtuar->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseShtuar">
<span<?= $Page->pjeseShtuar->viewAttributes() ?>>
<?= $Page->pjeseShtuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->pjeseModifikuar->Visible) { // pjeseModifikuar ?>
    <tr id="r_pjeseModifikuar"<?= $Page->pjeseModifikuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_pjese_kembimi_pjeseModifikuar"><?= $Page->pjeseModifikuar->caption() ?></span></td>
        <td data-name="pjeseModifikuar"<?= $Page->pjeseModifikuar->cellAttributes() ?>>
<span id="el_pjese_kembimi_pjeseModifikuar">
<span<?= $Page->pjeseModifikuar->viewAttributes() ?>>
<?= $Page->pjeseModifikuar->getViewValue() ?></span>
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
