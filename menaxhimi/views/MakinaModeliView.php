<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaModeliView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_modeli: currentTable } });
var currentForm, currentPageID;
var fmakina_modeliview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_modeliview = new ew.Form("fmakina_modeliview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmakina_modeliview;
    loadjs.done("fmakina_modeliview");
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
<form name="fmakina_modeliview" id="fmakina_modeliview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_modeli">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->mmodeliID->Visible) { // mmodeliID ?>
    <tr id="r_mmodeliID"<?= $Page->mmodeliID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_modeli_mmodeliID"><?= $Page->mmodeliID->caption() ?></span></td>
        <td data-name="mmodeliID"<?= $Page->mmodeliID->cellAttributes() ?>>
<span id="el_makina_modeli_mmodeliID">
<span<?= $Page->mmodeliID->viewAttributes() ?>>
<?= $Page->mmodeliID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mmodeliMarka->Visible) { // mmodeliMarka ?>
    <tr id="r_mmodeliMarka"<?= $Page->mmodeliMarka->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_modeli_mmodeliMarka"><?= $Page->mmodeliMarka->caption() ?></span></td>
        <td data-name="mmodeliMarka"<?= $Page->mmodeliMarka->cellAttributes() ?>>
<span id="el_makina_modeli_mmodeliMarka">
<span<?= $Page->mmodeliMarka->viewAttributes() ?>>
<?= $Page->mmodeliMarka->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mmodeliModeli->Visible) { // mmodeliModeli ?>
    <tr id="r_mmodeliModeli"<?= $Page->mmodeliModeli->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_modeli_mmodeliModeli"><?= $Page->mmodeliModeli->caption() ?></span></td>
        <td data-name="mmodeliModeli"<?= $Page->mmodeliModeli->cellAttributes() ?>>
<span id="el_makina_modeli_mmodeliModeli">
<span<?= $Page->mmodeliModeli->viewAttributes() ?>>
<?= $Page->mmodeliModeli->getViewValue() ?></span>
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
