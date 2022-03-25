<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisPjesetDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis_pjeset: currentTable } });
var currentForm, currentPageID;
var fservis_pjesetdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_pjesetdelete = new ew.Form("fservis_pjesetdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fservis_pjesetdelete;
    loadjs.done("fservis_pjesetdelete");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fservis_pjesetdelete" id="fservis_pjesetdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis_pjeset">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode(Config("COMPOSITE_KEY_SEPARATOR"), $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($keyvalue) ?>">
<?php } ?>
<div class="card ew-card ew-grid">
<div class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table class="table table-sm ew-table">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->servisPjeseID->Visible) { // servisPjeseID ?>
        <th class="<?= $Page->servisPjeseID->headerCellClass() ?>"><span id="elh_servis_pjeset_servisPjeseID" class="servis_pjeset_servisPjeseID"><?= $Page->servisPjeseID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <th class="<?= $Page->servisPjeseServisID->headerCellClass() ?>"><span id="elh_servis_pjeset_servisPjeseServisID" class="servis_pjeset_servisPjeseServisID"><?= $Page->servisPjeseServisID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <th class="<?= $Page->servisPjesePjesa->headerCellClass() ?>"><span id="elh_servis_pjeset_servisPjesePjesa" class="servis_pjeset_servisPjesePjesa"><?= $Page->servisPjesePjesa->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <th class="<?= $Page->servisPjeseSasia->headerCellClass() ?>"><span id="elh_servis_pjeset_servisPjeseSasia" class="servis_pjeset_servisPjeseSasia"><?= $Page->servisPjeseSasia->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <th class="<?= $Page->servisPjeseCmimi->headerCellClass() ?>"><span id="elh_servis_pjeset_servisPjeseCmimi" class="servis_pjeset_servisPjeseCmimi"><?= $Page->servisPjeseCmimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <th class="<?= $Page->servisPjeseShenim->headerCellClass() ?>"><span id="elh_servis_pjeset_servisPjeseShenim" class="servis_pjeset_servisPjeseShenim"><?= $Page->servisPjeseShenim->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
$Page->RecordCount = 0;
$i = 0;
while (!$Page->Recordset->EOF) {
    $Page->RecordCount++;
    $Page->RowCount++;

    // Set row properties
    $Page->resetAttributes();
    $Page->RowType = ROWTYPE_VIEW; // View

    // Get the field contents
    $Page->loadRowValues($Page->Recordset);

    // Render row
    $Page->renderRow();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->servisPjeseID->Visible) { // servisPjeseID ?>
        <td<?= $Page->servisPjeseID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseID" class="el_servis_pjeset_servisPjeseID">
<span<?= $Page->servisPjeseID->viewAttributes() ?>>
<?= $Page->servisPjeseID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <td<?= $Page->servisPjeseServisID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<span<?= $Page->servisPjeseServisID->viewAttributes() ?>>
<?= $Page->servisPjeseServisID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <td<?= $Page->servisPjesePjesa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjesePjesa" class="el_servis_pjeset_servisPjesePjesa">
<span<?= $Page->servisPjesePjesa->viewAttributes() ?>>
<?= $Page->servisPjesePjesa->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <td<?= $Page->servisPjeseSasia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseSasia" class="el_servis_pjeset_servisPjeseSasia">
<span<?= $Page->servisPjeseSasia->viewAttributes() ?>>
<?= $Page->servisPjeseSasia->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <td<?= $Page->servisPjeseCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseCmimi" class="el_servis_pjeset_servisPjeseCmimi">
<span<?= $Page->servisPjeseCmimi->viewAttributes() ?>>
<?= $Page->servisPjeseCmimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <td<?= $Page->servisPjeseShenim->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_pjeset_servisPjeseShenim" class="el_servis_pjeset_servisPjeseShenim">
<span<?= $Page->servisPjeseShenim->viewAttributes() ?>>
<?= $Page->servisPjeseShenim->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
    $Page->Recordset->moveNext();
}
$Page->Recordset->close();
?>
</tbody>
</table>
</div>
</div>
<div>
<button class="btn btn-primary ew-btn" name="btn-action" id="btn-action" type="submit"><?= $Language->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= $Language->phrase("CancelBtn") ?></button>
</div>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
