<?php

namespace PHPMaker2022\volalservice;

// Page object
$IndexTipsView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { index_tips: currentTable } });
var currentForm, currentPageID;
var findex_tipsview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    findex_tipsview = new ew.Form("findex_tipsview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = findex_tipsview;
    loadjs.done("findex_tipsview");
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
<form name="findex_tipsview" id="findex_tipsview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="index_tips">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->iTipsID->Visible) { // iTipsID ?>
    <tr id="r_iTipsID"<?= $Page->iTipsID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_index_tips_iTipsID"><?= $Page->iTipsID->caption() ?></span></td>
        <td data-name="iTipsID"<?= $Page->iTipsID->cellAttributes() ?>>
<span id="el_index_tips_iTipsID">
<span<?= $Page->iTipsID->viewAttributes() ?>>
<?= $Page->iTipsID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iTipsTeksti->Visible) { // iTipsTeksti ?>
    <tr id="r_iTipsTeksti"<?= $Page->iTipsTeksti->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_index_tips_iTipsTeksti"><?= $Page->iTipsTeksti->caption() ?></span></td>
        <td data-name="iTipsTeksti"<?= $Page->iTipsTeksti->cellAttributes() ?>>
<span id="el_index_tips_iTipsTeksti">
<span<?= $Page->iTipsTeksti->viewAttributes() ?>>
<?= $Page->iTipsTeksti->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iTipsAutori->Visible) { // iTipsAutori ?>
    <tr id="r_iTipsAutori"<?= $Page->iTipsAutori->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_index_tips_iTipsAutori"><?= $Page->iTipsAutori->caption() ?></span></td>
        <td data-name="iTipsAutori"<?= $Page->iTipsAutori->cellAttributes() ?>>
<span id="el_index_tips_iTipsAutori">
<span<?= $Page->iTipsAutori->viewAttributes() ?>>
<?= $Page->iTipsAutori->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iTipsKrijuar->Visible) { // iTipsKrijuar ?>
    <tr id="r_iTipsKrijuar"<?= $Page->iTipsKrijuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_index_tips_iTipsKrijuar"><?= $Page->iTipsKrijuar->caption() ?></span></td>
        <td data-name="iTipsKrijuar"<?= $Page->iTipsKrijuar->cellAttributes() ?>>
<span id="el_index_tips_iTipsKrijuar">
<span<?= $Page->iTipsKrijuar->viewAttributes() ?>>
<?= $Page->iTipsKrijuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->iTipsAzhornuar->Visible) { // iTipsAzhornuar ?>
    <tr id="r_iTipsAzhornuar"<?= $Page->iTipsAzhornuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_index_tips_iTipsAzhornuar"><?= $Page->iTipsAzhornuar->caption() ?></span></td>
        <td data-name="iTipsAzhornuar"<?= $Page->iTipsAzhornuar->cellAttributes() ?>>
<span id="el_index_tips_iTipsAzhornuar">
<span<?= $Page->iTipsAzhornuar->viewAttributes() ?>>
<?= $Page->iTipsAzhornuar->getViewValue() ?></span>
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
