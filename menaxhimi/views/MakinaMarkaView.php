<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaMarkaView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_marka: currentTable } });
var currentForm, currentPageID;
var fmakina_markaview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_markaview = new ew.Form("fmakina_markaview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmakina_markaview;
    loadjs.done("fmakina_markaview");
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
<form name="fmakina_markaview" id="fmakina_markaview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_marka">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->mmarkaID->Visible) { // mmarkaID ?>
    <tr id="r_mmarkaID"<?= $Page->mmarkaID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_marka_mmarkaID"><?= $Page->mmarkaID->caption() ?></span></td>
        <td data-name="mmarkaID"<?= $Page->mmarkaID->cellAttributes() ?>>
<span id="el_makina_marka_mmarkaID">
<span<?= $Page->mmarkaID->viewAttributes() ?>>
<?= $Page->mmarkaID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mmarkaMarka->Visible) { // mmarkaMarka ?>
    <tr id="r_mmarkaMarka"<?= $Page->mmarkaMarka->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_marka_mmarkaMarka"><?= $Page->mmarkaMarka->caption() ?></span></td>
        <td data-name="mmarkaMarka"<?= $Page->mmarkaMarka->cellAttributes() ?>>
<span id="el_makina_marka_mmarkaMarka">
<span<?= $Page->mmarkaMarka->viewAttributes() ?>>
<?= $Page->mmarkaMarka->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mmarkaLogo->Visible) { // mmarkaLogo ?>
    <tr id="r_mmarkaLogo"<?= $Page->mmarkaLogo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_marka_mmarkaLogo"><?= $Page->mmarkaLogo->caption() ?></span></td>
        <td data-name="mmarkaLogo"<?= $Page->mmarkaLogo->cellAttributes() ?>>
<span id="el_makina_marka_mmarkaLogo">
<span>
<?= GetFileViewTag($Page->mmarkaLogo, $Page->mmarkaLogo->getViewValue(), false) ?>
</span>
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
