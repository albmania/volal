<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaShitjeDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_shitje: currentTable } });
var currentForm, currentPageID;
var fmakina_shitjedelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_shitjedelete = new ew.Form("fmakina_shitjedelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmakina_shitjedelete;
    loadjs.done("fmakina_shitjedelete");
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
<form name="fmakina_shitjedelete" id="fmakina_shitjedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_shitje">
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
<?php if ($Page->mshitjeID->Visible) { // mshitjeID ?>
        <th class="<?= $Page->mshitjeID->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeID" class="makina_shitje_mshitjeID"><?= $Page->mshitjeID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeMarka->Visible) { // mshitjeMarka ?>
        <th class="<?= $Page->mshitjeMarka->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeMarka" class="makina_shitje_mshitjeMarka"><?= $Page->mshitjeMarka->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeModeli->Visible) { // mshitjeModeli ?>
        <th class="<?= $Page->mshitjeModeli->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeModeli" class="makina_shitje_mshitjeModeli"><?= $Page->mshitjeModeli->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeTipi->Visible) { // mshitjeTipi ?>
        <th class="<?= $Page->mshitjeTipi->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeTipi" class="makina_shitje_mshitjeTipi"><?= $Page->mshitjeTipi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeKapacitetiMotorrit->Visible) { // mshitjeKapacitetiMotorrit ?>
        <th class="<?= $Page->mshitjeKapacitetiMotorrit->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeKapacitetiMotorrit" class="makina_shitje_mshitjeKapacitetiMotorrit"><?= $Page->mshitjeKapacitetiMotorrit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeVitiProdhimit->Visible) { // mshitjeVitiProdhimit ?>
        <th class="<?= $Page->mshitjeVitiProdhimit->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeVitiProdhimit" class="makina_shitje_mshitjeVitiProdhimit"><?= $Page->mshitjeVitiProdhimit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeKarburant->Visible) { // mshitjeKarburant ?>
        <th class="<?= $Page->mshitjeKarburant->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeKarburant" class="makina_shitje_mshitjeKarburant"><?= $Page->mshitjeKarburant->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeNrVendeve->Visible) { // mshitjeNrVendeve ?>
        <th class="<?= $Page->mshitjeNrVendeve->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeNrVendeve" class="makina_shitje_mshitjeNrVendeve"><?= $Page->mshitjeNrVendeve->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeKambio->Visible) { // mshitjeKambio ?>
        <th class="<?= $Page->mshitjeKambio->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeKambio" class="makina_shitje_mshitjeKambio"><?= $Page->mshitjeKambio->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeTargaAL->Visible) { // mshitjeTargaAL ?>
        <th class="<?= $Page->mshitjeTargaAL->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeTargaAL" class="makina_shitje_mshitjeTargaAL"><?= $Page->mshitjeTargaAL->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeKilometra->Visible) { // mshitjeKilometra ?>
        <th class="<?= $Page->mshitjeKilometra->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeKilometra" class="makina_shitje_mshitjeKilometra"><?= $Page->mshitjeKilometra->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeCmimi->Visible) { // mshitjeCmimi ?>
        <th class="<?= $Page->mshitjeCmimi->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeCmimi" class="makina_shitje_mshitjeCmimi"><?= $Page->mshitjeCmimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeIndex->Visible) { // mshitjeIndex ?>
        <th class="<?= $Page->mshitjeIndex->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeIndex" class="makina_shitje_mshitjeIndex"><?= $Page->mshitjeIndex->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjePromo->Visible) { // mshitjePromo ?>
        <th class="<?= $Page->mshitjePromo->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjePromo" class="makina_shitje_mshitjePromo"><?= $Page->mshitjePromo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeAktiv->Visible) { // mshitjeAktiv ?>
        <th class="<?= $Page->mshitjeAktiv->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeAktiv" class="makina_shitje_mshitjeAktiv"><?= $Page->mshitjeAktiv->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeShitur->Visible) { // mshitjeShitur ?>
        <th class="<?= $Page->mshitjeShitur->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeShitur" class="makina_shitje_mshitjeShitur"><?= $Page->mshitjeShitur->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeAutori->Visible) { // mshitjeAutori ?>
        <th class="<?= $Page->mshitjeAutori->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeAutori" class="makina_shitje_mshitjeAutori"><?= $Page->mshitjeAutori->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeKrijuar->Visible) { // mshitjeKrijuar ?>
        <th class="<?= $Page->mshitjeKrijuar->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeKrijuar" class="makina_shitje_mshitjeKrijuar"><?= $Page->mshitjeKrijuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mshitjeAzhornuar->Visible) { // mshitjeAzhornuar ?>
        <th class="<?= $Page->mshitjeAzhornuar->headerCellClass() ?>"><span id="elh_makina_shitje_mshitjeAzhornuar" class="makina_shitje_mshitjeAzhornuar"><?= $Page->mshitjeAzhornuar->caption() ?></span></th>
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
<?php if ($Page->mshitjeID->Visible) { // mshitjeID ?>
        <td<?= $Page->mshitjeID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeID" class="el_makina_shitje_mshitjeID">
<span<?= $Page->mshitjeID->viewAttributes() ?>>
<?= $Page->mshitjeID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeMarka->Visible) { // mshitjeMarka ?>
        <td<?= $Page->mshitjeMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeMarka" class="el_makina_shitje_mshitjeMarka">
<span<?= $Page->mshitjeMarka->viewAttributes() ?>>
<?= $Page->mshitjeMarka->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeModeli->Visible) { // mshitjeModeli ?>
        <td<?= $Page->mshitjeModeli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeModeli" class="el_makina_shitje_mshitjeModeli">
<span<?= $Page->mshitjeModeli->viewAttributes() ?>>
<?= $Page->mshitjeModeli->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeTipi->Visible) { // mshitjeTipi ?>
        <td<?= $Page->mshitjeTipi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeTipi" class="el_makina_shitje_mshitjeTipi">
<span<?= $Page->mshitjeTipi->viewAttributes() ?>>
<?= $Page->mshitjeTipi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeKapacitetiMotorrit->Visible) { // mshitjeKapacitetiMotorrit ?>
        <td<?= $Page->mshitjeKapacitetiMotorrit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKapacitetiMotorrit" class="el_makina_shitje_mshitjeKapacitetiMotorrit">
<span<?= $Page->mshitjeKapacitetiMotorrit->viewAttributes() ?>>
<?= $Page->mshitjeKapacitetiMotorrit->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeVitiProdhimit->Visible) { // mshitjeVitiProdhimit ?>
        <td<?= $Page->mshitjeVitiProdhimit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeVitiProdhimit" class="el_makina_shitje_mshitjeVitiProdhimit">
<span<?= $Page->mshitjeVitiProdhimit->viewAttributes() ?>>
<?= $Page->mshitjeVitiProdhimit->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeKarburant->Visible) { // mshitjeKarburant ?>
        <td<?= $Page->mshitjeKarburant->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKarburant" class="el_makina_shitje_mshitjeKarburant">
<span<?= $Page->mshitjeKarburant->viewAttributes() ?>>
<?= $Page->mshitjeKarburant->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeNrVendeve->Visible) { // mshitjeNrVendeve ?>
        <td<?= $Page->mshitjeNrVendeve->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeNrVendeve" class="el_makina_shitje_mshitjeNrVendeve">
<span<?= $Page->mshitjeNrVendeve->viewAttributes() ?>>
<?= $Page->mshitjeNrVendeve->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeKambio->Visible) { // mshitjeKambio ?>
        <td<?= $Page->mshitjeKambio->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKambio" class="el_makina_shitje_mshitjeKambio">
<span<?= $Page->mshitjeKambio->viewAttributes() ?>>
<?= $Page->mshitjeKambio->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeTargaAL->Visible) { // mshitjeTargaAL ?>
        <td<?= $Page->mshitjeTargaAL->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeTargaAL" class="el_makina_shitje_mshitjeTargaAL">
<span<?= $Page->mshitjeTargaAL->viewAttributes() ?>>
<?= $Page->mshitjeTargaAL->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeKilometra->Visible) { // mshitjeKilometra ?>
        <td<?= $Page->mshitjeKilometra->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKilometra" class="el_makina_shitje_mshitjeKilometra">
<span<?= $Page->mshitjeKilometra->viewAttributes() ?>>
<?= $Page->mshitjeKilometra->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeCmimi->Visible) { // mshitjeCmimi ?>
        <td<?= $Page->mshitjeCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeCmimi" class="el_makina_shitje_mshitjeCmimi">
<span<?= $Page->mshitjeCmimi->viewAttributes() ?>>
<?= $Page->mshitjeCmimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeIndex->Visible) { // mshitjeIndex ?>
        <td<?= $Page->mshitjeIndex->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeIndex" class="el_makina_shitje_mshitjeIndex">
<span<?= $Page->mshitjeIndex->viewAttributes() ?>>
<?= $Page->mshitjeIndex->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjePromo->Visible) { // mshitjePromo ?>
        <td<?= $Page->mshitjePromo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjePromo" class="el_makina_shitje_mshitjePromo">
<span<?= $Page->mshitjePromo->viewAttributes() ?>>
<?= $Page->mshitjePromo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeAktiv->Visible) { // mshitjeAktiv ?>
        <td<?= $Page->mshitjeAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeAktiv" class="el_makina_shitje_mshitjeAktiv">
<span<?= $Page->mshitjeAktiv->viewAttributes() ?>>
<?= $Page->mshitjeAktiv->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeShitur->Visible) { // mshitjeShitur ?>
        <td<?= $Page->mshitjeShitur->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeShitur" class="el_makina_shitje_mshitjeShitur">
<span<?= $Page->mshitjeShitur->viewAttributes() ?>>
<?= $Page->mshitjeShitur->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeAutori->Visible) { // mshitjeAutori ?>
        <td<?= $Page->mshitjeAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeAutori" class="el_makina_shitje_mshitjeAutori">
<span<?= $Page->mshitjeAutori->viewAttributes() ?>>
<?= $Page->mshitjeAutori->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeKrijuar->Visible) { // mshitjeKrijuar ?>
        <td<?= $Page->mshitjeKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeKrijuar" class="el_makina_shitje_mshitjeKrijuar">
<span<?= $Page->mshitjeKrijuar->viewAttributes() ?>>
<?= $Page->mshitjeKrijuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mshitjeAzhornuar->Visible) { // mshitjeAzhornuar ?>
        <td<?= $Page->mshitjeAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_shitje_mshitjeAzhornuar" class="el_makina_shitje_mshitjeAzhornuar">
<span<?= $Page->mshitjeAzhornuar->viewAttributes() ?>>
<?= $Page->mshitjeAzhornuar->getViewValue() ?></span>
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
