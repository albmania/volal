<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaImportiSherbimeView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_importi_sherbime: currentTable } });
var currentForm, currentPageID;
var fmakina_importi_sherbimeview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importi_sherbimeview = new ew.Form("fmakina_importi_sherbimeview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmakina_importi_sherbimeview;
    loadjs.done("fmakina_importi_sherbimeview");
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
<form name="fmakina_importi_sherbimeview" id="fmakina_importi_sherbimeview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_importi_sherbime">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->mishID->Visible) { // mishID ?>
    <tr id="r_mishID"<?= $Page->mishID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_sherbime_mishID"><?= $Page->mishID->caption() ?></span></td>
        <td data-name="mishID"<?= $Page->mishID->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishID">
<span<?= $Page->mishID->viewAttributes() ?>>
<?= $Page->mishID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
    <tr id="r_mishMakinaImporti"<?= $Page->mishMakinaImporti->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_sherbime_mishMakinaImporti"><?= $Page->mishMakinaImporti->caption() ?></span></td>
        <td data-name="mishMakinaImporti"<?= $Page->mishMakinaImporti->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Page->mishMakinaImporti->viewAttributes() ?>>
<?= $Page->mishMakinaImporti->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mishPershkrimi->Visible) { // mishPershkrimi ?>
    <tr id="r_mishPershkrimi"<?= $Page->mishPershkrimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_sherbime_mishPershkrimi"><?= $Page->mishPershkrimi->caption() ?></span></td>
        <td data-name="mishPershkrimi"<?= $Page->mishPershkrimi->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishPershkrimi">
<span<?= $Page->mishPershkrimi->viewAttributes() ?>>
<?= $Page->mishPershkrimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mishKryer->Visible) { // mishKryer ?>
    <tr id="r_mishKryer"<?= $Page->mishKryer->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_sherbime_mishKryer"><?= $Page->mishKryer->caption() ?></span></td>
        <td data-name="mishKryer"<?= $Page->mishKryer->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishKryer">
<span<?= $Page->mishKryer->viewAttributes() ?>>
<?= $Page->mishKryer->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mishCmimi->Visible) { // mishCmimi ?>
    <tr id="r_mishCmimi"<?= $Page->mishCmimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_sherbime_mishCmimi"><?= $Page->mishCmimi->caption() ?></span></td>
        <td data-name="mishCmimi"<?= $Page->mishCmimi->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishCmimi">
<span<?= $Page->mishCmimi->viewAttributes() ?>>
<?= $Page->mishCmimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->mishData->Visible) { // mishData ?>
    <tr id="r_mishData"<?= $Page->mishData->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_makina_importi_sherbime_mishData"><?= $Page->mishData->caption() ?></span></td>
        <td data-name="mishData"<?= $Page->mishData->cellAttributes() ?>>
<span id="el_makina_importi_sherbime_mishData">
<span<?= $Page->mishData->viewAttributes() ?>>
<?= $Page->mishData->getViewValue() ?></span>
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
