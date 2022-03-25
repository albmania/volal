<?php

namespace PHPMaker2022\volalservice;

// Page object
$PerdoruesitView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { perdoruesit: currentTable } });
var currentForm, currentPageID;
var fperdoruesitview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fperdoruesitview = new ew.Form("fperdoruesitview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fperdoruesitview;
    loadjs.done("fperdoruesitview");
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
<form name="fperdoruesitview" id="fperdoruesitview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="perdoruesit">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->perdID->Visible) { // perdID ?>
    <tr id="r_perdID"<?= $Page->perdID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdID"><?= $Page->perdID->caption() ?></span></td>
        <td data-name="perdID"<?= $Page->perdID->cellAttributes() ?>>
<span id="el_perdoruesit_perdID">
<span<?= $Page->perdID->viewAttributes() ?>>
<?= $Page->perdID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->perdEmri->Visible) { // perdEmri ?>
    <tr id="r_perdEmri"<?= $Page->perdEmri->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdEmri"><?= $Page->perdEmri->caption() ?></span></td>
        <td data-name="perdEmri"<?= $Page->perdEmri->cellAttributes() ?>>
<span id="el_perdoruesit_perdEmri">
<span<?= $Page->perdEmri->viewAttributes() ?>>
<?= $Page->perdEmri->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->perdUsername->Visible) { // perdUsername ?>
    <tr id="r_perdUsername"<?= $Page->perdUsername->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdUsername"><?= $Page->perdUsername->caption() ?></span></td>
        <td data-name="perdUsername"<?= $Page->perdUsername->cellAttributes() ?>>
<span id="el_perdoruesit_perdUsername">
<span<?= $Page->perdUsername->viewAttributes() ?>>
<?= $Page->perdUsername->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->perdFjalekalimi->Visible) { // perdFjalekalimi ?>
    <tr id="r_perdFjalekalimi"<?= $Page->perdFjalekalimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdFjalekalimi"><?= $Page->perdFjalekalimi->caption() ?></span></td>
        <td data-name="perdFjalekalimi"<?= $Page->perdFjalekalimi->cellAttributes() ?>>
<span id="el_perdoruesit_perdFjalekalimi">
<span<?= $Page->perdFjalekalimi->viewAttributes() ?>>
<?= $Page->perdFjalekalimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->perdEmail->Visible) { // perdEmail ?>
    <tr id="r_perdEmail"<?= $Page->perdEmail->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdEmail"><?= $Page->perdEmail->caption() ?></span></td>
        <td data-name="perdEmail"<?= $Page->perdEmail->cellAttributes() ?>>
<span id="el_perdoruesit_perdEmail">
<span<?= $Page->perdEmail->viewAttributes() ?>>
<?= $Page->perdEmail->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->perdNiveliPerdoruesit->Visible) { // perdNiveliPerdoruesit ?>
    <tr id="r_perdNiveliPerdoruesit"<?= $Page->perdNiveliPerdoruesit->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdNiveliPerdoruesit"><?= $Page->perdNiveliPerdoruesit->caption() ?></span></td>
        <td data-name="perdNiveliPerdoruesit"<?= $Page->perdNiveliPerdoruesit->cellAttributes() ?>>
<span id="el_perdoruesit_perdNiveliPerdoruesit">
<span<?= $Page->perdNiveliPerdoruesit->viewAttributes() ?>>
<?= $Page->perdNiveliPerdoruesit->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->perdDtReg->Visible) { // perdDtReg ?>
    <tr id="r_perdDtReg"<?= $Page->perdDtReg->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdDtReg"><?= $Page->perdDtReg->caption() ?></span></td>
        <td data-name="perdDtReg"<?= $Page->perdDtReg->cellAttributes() ?>>
<span id="el_perdoruesit_perdDtReg">
<span<?= $Page->perdDtReg->viewAttributes() ?>>
<?= $Page->perdDtReg->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->perdActivated->Visible) { // perdActivated ?>
    <tr id="r_perdActivated"<?= $Page->perdActivated->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdActivated"><?= $Page->perdActivated->caption() ?></span></td>
        <td data-name="perdActivated"<?= $Page->perdActivated->cellAttributes() ?>>
<span id="el_perdoruesit_perdActivated">
<span<?= $Page->perdActivated->viewAttributes() ?>>
<?= $Page->perdActivated->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->perdProfileField->Visible) { // perdProfileField ?>
    <tr id="r_perdProfileField"<?= $Page->perdProfileField->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_perdoruesit_perdProfileField"><?= $Page->perdProfileField->caption() ?></span></td>
        <td data-name="perdProfileField"<?= $Page->perdProfileField->cellAttributes() ?>>
<span id="el_perdoruesit_perdProfileField">
<span<?= $Page->perdProfileField->viewAttributes() ?>>
<?= $Page->perdProfileField->getViewValue() ?></span>
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
