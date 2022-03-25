<?php

namespace PHPMaker2022\volalservice;

// Page object
$SherbimeView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { sherbime: currentTable } });
var currentForm, currentPageID;
var fsherbimeview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fsherbimeview = new ew.Form("fsherbimeview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fsherbimeview;
    loadjs.done("fsherbimeview");
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
<form name="fsherbimeview" id="fsherbimeview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="sherbime">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->sherbimeID->Visible) { // sherbimeID ?>
    <tr id="r_sherbimeID"<?= $Page->sherbimeID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeID"><?= $Page->sherbimeID->caption() ?></span></td>
        <td data-name="sherbimeID"<?= $Page->sherbimeID->cellAttributes() ?>>
<span id="el_sherbime_sherbimeID">
<span<?= $Page->sherbimeID->viewAttributes() ?>>
<?= $Page->sherbimeID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_sq->Visible) { // sherbimeEmertimi_sq ?>
    <tr id="r_sherbimeEmertimi_sq"<?= $Page->sherbimeEmertimi_sq->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeEmertimi_sq"><?= $Page->sherbimeEmertimi_sq->caption() ?></span></td>
        <td data-name="sherbimeEmertimi_sq"<?= $Page->sherbimeEmertimi_sq->cellAttributes() ?>>
<span id="el_sherbime_sherbimeEmertimi_sq">
<span<?= $Page->sherbimeEmertimi_sq->viewAttributes() ?>>
<?= $Page->sherbimeEmertimi_sq->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sherbimeTxt_sq->Visible) { // sherbimeTxt_sq ?>
    <tr id="r_sherbimeTxt_sq"<?= $Page->sherbimeTxt_sq->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeTxt_sq"><?= $Page->sherbimeTxt_sq->caption() ?></span></td>
        <td data-name="sherbimeTxt_sq"<?= $Page->sherbimeTxt_sq->cellAttributes() ?>>
<span id="el_sherbime_sherbimeTxt_sq">
<span<?= $Page->sherbimeTxt_sq->viewAttributes() ?>>
<?= $Page->sherbimeTxt_sq->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sherbimeCmimi->Visible) { // sherbimeCmimi ?>
    <tr id="r_sherbimeCmimi"<?= $Page->sherbimeCmimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeCmimi"><?= $Page->sherbimeCmimi->caption() ?></span></td>
        <td data-name="sherbimeCmimi"<?= $Page->sherbimeCmimi->cellAttributes() ?>>
<span id="el_sherbime_sherbimeCmimi">
<span<?= $Page->sherbimeCmimi->viewAttributes() ?>>
<?= $Page->sherbimeCmimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sherbimeEmertimi_en->Visible) { // sherbimeEmertimi_en ?>
    <tr id="r_sherbimeEmertimi_en"<?= $Page->sherbimeEmertimi_en->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeEmertimi_en"><?= $Page->sherbimeEmertimi_en->caption() ?></span></td>
        <td data-name="sherbimeEmertimi_en"<?= $Page->sherbimeEmertimi_en->cellAttributes() ?>>
<span id="el_sherbime_sherbimeEmertimi_en">
<span<?= $Page->sherbimeEmertimi_en->viewAttributes() ?>>
<?= $Page->sherbimeEmertimi_en->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sherbimeTxt_en->Visible) { // sherbimeTxt_en ?>
    <tr id="r_sherbimeTxt_en"<?= $Page->sherbimeTxt_en->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeTxt_en"><?= $Page->sherbimeTxt_en->caption() ?></span></td>
        <td data-name="sherbimeTxt_en"<?= $Page->sherbimeTxt_en->cellAttributes() ?>>
<span id="el_sherbime_sherbimeTxt_en">
<span<?= $Page->sherbimeTxt_en->viewAttributes() ?>>
<?= $Page->sherbimeTxt_en->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sherbimeFoto->Visible) { // sherbimeFoto ?>
    <tr id="r_sherbimeFoto"<?= $Page->sherbimeFoto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeFoto"><?= $Page->sherbimeFoto->caption() ?></span></td>
        <td data-name="sherbimeFoto"<?= $Page->sherbimeFoto->cellAttributes() ?>>
<span id="el_sherbime_sherbimeFoto">
<span>
<?= GetFileViewTag($Page->sherbimeFoto, $Page->sherbimeFoto->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sherbimeIkona->Visible) { // sherbimeIkona ?>
    <tr id="r_sherbimeIkona"<?= $Page->sherbimeIkona->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeIkona"><?= $Page->sherbimeIkona->caption() ?></span></td>
        <td data-name="sherbimeIkona"<?= $Page->sherbimeIkona->cellAttributes() ?>>
<span id="el_sherbime_sherbimeIkona">
<span<?= $Page->sherbimeIkona->viewAttributes() ?>>
<?= $Page->sherbimeIkona->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->sherbimeIndex->Visible) { // sherbimeIndex ?>
    <tr id="r_sherbimeIndex"<?= $Page->sherbimeIndex->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_sherbime_sherbimeIndex"><?= $Page->sherbimeIndex->caption() ?></span></td>
        <td data-name="sherbimeIndex"<?= $Page->sherbimeIndex->cellAttributes() ?>>
<span id="el_sherbime_sherbimeIndex">
<span<?= $Page->sherbimeIndex->viewAttributes() ?>>
<?= $Page->sherbimeIndex->getViewValue() ?></span>
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
