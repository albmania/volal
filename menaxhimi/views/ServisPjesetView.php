<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisPjesetView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis_pjeset: currentTable } });
var currentForm, currentPageID;
var fservis_pjesetview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_pjesetview = new ew.Form("fservis_pjesetview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fservis_pjesetview;
    loadjs.done("fservis_pjesetview");
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
<form name="fservis_pjesetview" id="fservis_pjesetview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis_pjeset">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->servisPjeseID->Visible) { // servisPjeseID ?>
    <tr id="r_servisPjeseID"<?= $Page->servisPjeseID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_pjeset_servisPjeseID"><?= $Page->servisPjeseID->caption() ?></span></td>
        <td data-name="servisPjeseID"<?= $Page->servisPjeseID->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseID">
<span<?= $Page->servisPjeseID->viewAttributes() ?>>
<?= $Page->servisPjeseID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
    <tr id="r_servisPjeseServisID"<?= $Page->servisPjeseServisID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_pjeset_servisPjeseServisID"><?= $Page->servisPjeseServisID->caption() ?></span></td>
        <td data-name="servisPjeseServisID"<?= $Page->servisPjeseServisID->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseServisID">
<span<?= $Page->servisPjeseServisID->viewAttributes() ?>>
<?= $Page->servisPjeseServisID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
    <tr id="r_servisPjesePjesa"<?= $Page->servisPjesePjesa->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_pjeset_servisPjesePjesa"><?= $Page->servisPjesePjesa->caption() ?></span></td>
        <td data-name="servisPjesePjesa"<?= $Page->servisPjesePjesa->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjesePjesa">
<span<?= $Page->servisPjesePjesa->viewAttributes() ?>>
<?= $Page->servisPjesePjesa->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
    <tr id="r_servisPjeseSasia"<?= $Page->servisPjeseSasia->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_pjeset_servisPjeseSasia"><?= $Page->servisPjeseSasia->caption() ?></span></td>
        <td data-name="servisPjeseSasia"<?= $Page->servisPjeseSasia->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseSasia">
<span<?= $Page->servisPjeseSasia->viewAttributes() ?>>
<?= $Page->servisPjeseSasia->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
    <tr id="r_servisPjeseCmimi"<?= $Page->servisPjeseCmimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_pjeset_servisPjeseCmimi"><?= $Page->servisPjeseCmimi->caption() ?></span></td>
        <td data-name="servisPjeseCmimi"<?= $Page->servisPjeseCmimi->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseCmimi">
<span<?= $Page->servisPjeseCmimi->viewAttributes() ?>>
<?= $Page->servisPjeseCmimi->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
    <tr id="r_servisPjeseShenim"<?= $Page->servisPjeseShenim->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_servis_pjeset_servisPjeseShenim"><?= $Page->servisPjeseShenim->caption() ?></span></td>
        <td data-name="servisPjeseShenim"<?= $Page->servisPjeseShenim->cellAttributes() ?>>
<span id="el_servis_pjeset_servisPjeseShenim">
<span<?= $Page->servisPjeseShenim->viewAttributes() ?>>
<?= $Page->servisPjeseShenim->getViewValue() ?></span>
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
