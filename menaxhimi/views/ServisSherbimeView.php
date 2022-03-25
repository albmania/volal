<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisSherbimeView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis_sherbime: currentTable } });
var currentForm, currentPageID;
var fservis_sherbimeview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_sherbimeview = new ew.Form("fservis_sherbimeview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fservis_sherbimeview;
    loadjs.done("fservis_sherbimeview");
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
<form name="fservis_sherbimeview" id="fservis_sherbimeview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis_sherbime">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->servisSherbimID->Visible) { // servisSherbimID ?>
    <tr id="r_servisSherbimID"<?= $Page->servisSherbimID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_sherbime_servisSherbimID"><?= $Page->servisSherbimID->caption() ?></span></td>
        <td data-name="servisSherbimID"<?= $Page->servisSherbimID->cellAttributes() ?>>
<span id="el_servis_sherbime_servisSherbimID">
<span<?= $Page->servisSherbimID->viewAttributes() ?>>
<?= $Page->servisSherbimID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
    <tr id="r_servisSherbimServisID"<?= $Page->servisSherbimServisID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_sherbime_servisSherbimServisID"><?= $Page->servisSherbimServisID->caption() ?></span></td>
        <td data-name="servisSherbimServisID"<?= $Page->servisSherbimServisID->cellAttributes() ?>>
<span id="el_servis_sherbime_servisSherbimServisID">
<span<?= $Page->servisSherbimServisID->viewAttributes() ?>>
<?= $Page->servisSherbimServisID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
    <tr id="r_servisSherbimSherbimi"<?= $Page->servisSherbimSherbimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_sherbime_servisSherbimSherbimi"><?= $Page->servisSherbimSherbimi->caption() ?></span></td>
        <td data-name="servisSherbimSherbimi"<?= $Page->servisSherbimSherbimi->cellAttributes() ?>>
<span id="el_servis_sherbime_servisSherbimSherbimi">
<span<?= $Page->servisSherbimSherbimi->viewAttributes() ?>>
<?= $Page->servisSherbimSherbimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
    <tr id="r_servisSherbimCmimi"<?= $Page->servisSherbimCmimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_sherbime_servisSherbimCmimi"><?= $Page->servisSherbimCmimi->caption() ?></span></td>
        <td data-name="servisSherbimCmimi"<?= $Page->servisSherbimCmimi->cellAttributes() ?>>
<span id="el_servis_sherbime_servisSherbimCmimi">
<span<?= $Page->servisSherbimCmimi->viewAttributes() ?>>
<?= $Page->servisSherbimCmimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
    <tr id="r_servisSherbimShenim"<?= $Page->servisSherbimShenim->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_sherbime_servisSherbimShenim"><?= $Page->servisSherbimShenim->caption() ?></span></td>
        <td data-name="servisSherbimShenim"<?= $Page->servisSherbimShenim->cellAttributes() ?>>
<span id="el_servis_sherbime_servisSherbimShenim">
<span<?= $Page->servisSherbimShenim->viewAttributes() ?>>
<?= $Page->servisSherbimShenim->getViewValue() ?></span>
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
