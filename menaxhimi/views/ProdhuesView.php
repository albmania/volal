<?php

namespace PHPMaker2022\volalservice;

// Page object
$ProdhuesView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { prodhues: currentTable } });
var currentForm, currentPageID;
var fprodhuesview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fprodhuesview = new ew.Form("fprodhuesview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fprodhuesview;
    loadjs.done("fprodhuesview");
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
<form name="fprodhuesview" id="fprodhuesview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="prodhues">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->prodhuesID->Visible) { // prodhuesID ?>
    <tr id="r_prodhuesID"<?= $Page->prodhuesID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_prodhues_prodhuesID"><?= $Page->prodhuesID->caption() ?></span></td>
        <td data-name="prodhuesID"<?= $Page->prodhuesID->cellAttributes() ?>>
<span id="el_prodhues_prodhuesID">
<span<?= $Page->prodhuesID->viewAttributes() ?>>
<?= $Page->prodhuesID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->prodhuesEmri->Visible) { // prodhuesEmri ?>
    <tr id="r_prodhuesEmri"<?= $Page->prodhuesEmri->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_prodhues_prodhuesEmri"><?= $Page->prodhuesEmri->caption() ?></span></td>
        <td data-name="prodhuesEmri"<?= $Page->prodhuesEmri->cellAttributes() ?>>
<span id="el_prodhues_prodhuesEmri">
<span<?= $Page->prodhuesEmri->viewAttributes() ?>>
<?= $Page->prodhuesEmri->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->prodhuesLogo->Visible) { // prodhuesLogo ?>
    <tr id="r_prodhuesLogo"<?= $Page->prodhuesLogo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_prodhues_prodhuesLogo"><?= $Page->prodhuesLogo->caption() ?></span></td>
        <td data-name="prodhuesLogo"<?= $Page->prodhuesLogo->cellAttributes() ?>>
<span id="el_prodhues_prodhuesLogo">
<span>
<?= GetFileViewTag($Page->prodhuesLogo, $Page->prodhuesLogo->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->prodhuesPershkrim->Visible) { // prodhuesPershkrim ?>
    <tr id="r_prodhuesPershkrim"<?= $Page->prodhuesPershkrim->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_prodhues_prodhuesPershkrim"><?= $Page->prodhuesPershkrim->caption() ?></span></td>
        <td data-name="prodhuesPershkrim"<?= $Page->prodhuesPershkrim->cellAttributes() ?>>
<span id="el_prodhues_prodhuesPershkrim">
<span<?= $Page->prodhuesPershkrim->viewAttributes() ?>>
<?= $Page->prodhuesPershkrim->getViewValue() ?></span>
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
