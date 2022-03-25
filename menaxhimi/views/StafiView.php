<?php

namespace PHPMaker2022\volalservice;

// Page object
$StafiView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { stafi: currentTable } });
var currentForm, currentPageID;
var fstafiview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fstafiview = new ew.Form("fstafiview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fstafiview;
    loadjs.done("fstafiview");
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
<form name="fstafiview" id="fstafiview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="stafi">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->stafiID->Visible) { // stafiID ?>
    <tr id="r_stafiID"<?= $Page->stafiID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_stafi_stafiID"><?= $Page->stafiID->caption() ?></span></td>
        <td data-name="stafiID"<?= $Page->stafiID->cellAttributes() ?>>
<span id="el_stafi_stafiID">
<span<?= $Page->stafiID->viewAttributes() ?>>
<?= $Page->stafiID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->stafiEmri->Visible) { // stafiEmri ?>
    <tr id="r_stafiEmri"<?= $Page->stafiEmri->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_stafi_stafiEmri"><?= $Page->stafiEmri->caption() ?></span></td>
        <td data-name="stafiEmri"<?= $Page->stafiEmri->cellAttributes() ?>>
<span id="el_stafi_stafiEmri">
<span<?= $Page->stafiEmri->viewAttributes() ?>>
<?= $Page->stafiEmri->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->stafiDatelindja->Visible) { // stafiDatelindja ?>
    <tr id="r_stafiDatelindja"<?= $Page->stafiDatelindja->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_stafi_stafiDatelindja"><?= $Page->stafiDatelindja->caption() ?></span></td>
        <td data-name="stafiDatelindja"<?= $Page->stafiDatelindja->cellAttributes() ?>>
<span id="el_stafi_stafiDatelindja">
<span<?= $Page->stafiDatelindja->viewAttributes() ?>>
<?= $Page->stafiDatelindja->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->stafiSpecialitete->Visible) { // stafiSpecialitete ?>
    <tr id="r_stafiSpecialitete"<?= $Page->stafiSpecialitete->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_stafi_stafiSpecialitete"><?= $Page->stafiSpecialitete->caption() ?></span></td>
        <td data-name="stafiSpecialitete"<?= $Page->stafiSpecialitete->cellAttributes() ?>>
<span id="el_stafi_stafiSpecialitete">
<span<?= $Page->stafiSpecialitete->viewAttributes() ?>>
<?= $Page->stafiSpecialitete->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->stafiStudime->Visible) { // stafiStudime ?>
    <tr id="r_stafiStudime"<?= $Page->stafiStudime->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_stafi_stafiStudime"><?= $Page->stafiStudime->caption() ?></span></td>
        <td data-name="stafiStudime"<?= $Page->stafiStudime->cellAttributes() ?>>
<span id="el_stafi_stafiStudime">
<span<?= $Page->stafiStudime->viewAttributes() ?>>
<?= $Page->stafiStudime->getViewValue() ?></span>
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
