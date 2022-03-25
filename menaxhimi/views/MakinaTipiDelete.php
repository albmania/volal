<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaTipiDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_tipi: currentTable } });
var currentForm, currentPageID;
var fmakina_tipidelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_tipidelete = new ew.Form("fmakina_tipidelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmakina_tipidelete;
    loadjs.done("fmakina_tipidelete");
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
<form name="fmakina_tipidelete" id="fmakina_tipidelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_tipi">
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
<?php if ($Page->mtipiID->Visible) { // mtipiID ?>
        <th class="<?= $Page->mtipiID->headerCellClass() ?>"><span id="elh_makina_tipi_mtipiID" class="makina_tipi_mtipiID"><?= $Page->mtipiID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mtipiMarka->Visible) { // mtipiMarka ?>
        <th class="<?= $Page->mtipiMarka->headerCellClass() ?>"><span id="elh_makina_tipi_mtipiMarka" class="makina_tipi_mtipiMarka"><?= $Page->mtipiMarka->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mtipiModeli->Visible) { // mtipiModeli ?>
        <th class="<?= $Page->mtipiModeli->headerCellClass() ?>"><span id="elh_makina_tipi_mtipiModeli" class="makina_tipi_mtipiModeli"><?= $Page->mtipiModeli->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mtipiTipi->Visible) { // mtipiTipi ?>
        <th class="<?= $Page->mtipiTipi->headerCellClass() ?>"><span id="elh_makina_tipi_mtipiTipi" class="makina_tipi_mtipiTipi"><?= $Page->mtipiTipi->caption() ?></span></th>
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
<?php if ($Page->mtipiID->Visible) { // mtipiID ?>
        <td<?= $Page->mtipiID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_tipi_mtipiID" class="el_makina_tipi_mtipiID">
<span<?= $Page->mtipiID->viewAttributes() ?>>
<?= $Page->mtipiID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mtipiMarka->Visible) { // mtipiMarka ?>
        <td<?= $Page->mtipiMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_tipi_mtipiMarka" class="el_makina_tipi_mtipiMarka">
<span<?= $Page->mtipiMarka->viewAttributes() ?>>
<?= $Page->mtipiMarka->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mtipiModeli->Visible) { // mtipiModeli ?>
        <td<?= $Page->mtipiModeli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_tipi_mtipiModeli" class="el_makina_tipi_mtipiModeli">
<span<?= $Page->mtipiModeli->viewAttributes() ?>>
<?= $Page->mtipiModeli->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mtipiTipi->Visible) { // mtipiTipi ?>
        <td<?= $Page->mtipiTipi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_tipi_mtipiTipi" class="el_makina_tipi_mtipiTipi">
<span<?= $Page->mtipiTipi->viewAttributes() ?>>
<?= $Page->mtipiTipi->getViewValue() ?></span>
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
