<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaImportiDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_importi: currentTable } });
var currentForm, currentPageID;
var fmakina_importidelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importidelete = new ew.Form("fmakina_importidelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmakina_importidelete;
    loadjs.done("fmakina_importidelete");
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
<form name="fmakina_importidelete" id="fmakina_importidelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_importi">
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
<?php if ($Page->mimpID->Visible) { // mimpID ?>
        <th class="<?= $Page->mimpID->headerCellClass() ?>"><span id="elh_makina_importi_mimpID" class="makina_importi_mimpID"><?= $Page->mimpID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpMarka->Visible) { // mimpMarka ?>
        <th class="<?= $Page->mimpMarka->headerCellClass() ?>"><span id="elh_makina_importi_mimpMarka" class="makina_importi_mimpMarka"><?= $Page->mimpMarka->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpModeli->Visible) { // mimpModeli ?>
        <th class="<?= $Page->mimpModeli->headerCellClass() ?>"><span id="elh_makina_importi_mimpModeli" class="makina_importi_mimpModeli"><?= $Page->mimpModeli->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpTipi->Visible) { // mimpTipi ?>
        <th class="<?= $Page->mimpTipi->headerCellClass() ?>"><span id="elh_makina_importi_mimpTipi" class="makina_importi_mimpTipi"><?= $Page->mimpTipi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpShasia->Visible) { // mimpShasia ?>
        <th class="<?= $Page->mimpShasia->headerCellClass() ?>"><span id="elh_makina_importi_mimpShasia" class="makina_importi_mimpShasia"><?= $Page->mimpShasia->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpViti->Visible) { // mimpViti ?>
        <th class="<?= $Page->mimpViti->headerCellClass() ?>"><span id="elh_makina_importi_mimpViti" class="makina_importi_mimpViti"><?= $Page->mimpViti->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpKarburant->Visible) { // mimpKarburant ?>
        <th class="<?= $Page->mimpKarburant->headerCellClass() ?>"><span id="elh_makina_importi_mimpKarburant" class="makina_importi_mimpKarburant"><?= $Page->mimpKarburant->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpPrejardhja->Visible) { // mimpPrejardhja ?>
        <th class="<?= $Page->mimpPrejardhja->headerCellClass() ?>"><span id="elh_makina_importi_mimpPrejardhja" class="makina_importi_mimpPrejardhja"><?= $Page->mimpPrejardhja->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpCmimiBlerjes->Visible) { // mimpCmimiBlerjes ?>
        <th class="<?= $Page->mimpCmimiBlerjes->headerCellClass() ?>"><span id="elh_makina_importi_mimpCmimiBlerjes" class="makina_importi_mimpCmimiBlerjes"><?= $Page->mimpCmimiBlerjes->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpDogana->Visible) { // mimpDogana ?>
        <th class="<?= $Page->mimpDogana->headerCellClass() ?>"><span id="elh_makina_importi_mimpDogana" class="makina_importi_mimpDogana"><?= $Page->mimpDogana->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpTransporti->Visible) { // mimpTransporti ?>
        <th class="<?= $Page->mimpTransporti->headerCellClass() ?>"><span id="elh_makina_importi_mimpTransporti" class="makina_importi_mimpTransporti"><?= $Page->mimpTransporti->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpTjera->Visible) { // mimpTjera ?>
        <th class="<?= $Page->mimpTjera->headerCellClass() ?>"><span id="elh_makina_importi_mimpTjera" class="makina_importi_mimpTjera"><?= $Page->mimpTjera->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpDtHyrjes->Visible) { // mimpDtHyrjes ?>
        <th class="<?= $Page->mimpDtHyrjes->headerCellClass() ?>"><span id="elh_makina_importi_mimpDtHyrjes" class="makina_importi_mimpDtHyrjes"><?= $Page->mimpDtHyrjes->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpCmimiShitjes->Visible) { // mimpCmimiShitjes ?>
        <th class="<?= $Page->mimpCmimiShitjes->headerCellClass() ?>"><span id="elh_makina_importi_mimpCmimiShitjes" class="makina_importi_mimpCmimiShitjes"><?= $Page->mimpCmimiShitjes->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mimpGati->Visible) { // mimpGati ?>
        <th class="<?= $Page->mimpGati->headerCellClass() ?>"><span id="elh_makina_importi_mimpGati" class="makina_importi_mimpGati"><?= $Page->mimpGati->caption() ?></span></th>
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
<?php if ($Page->mimpID->Visible) { // mimpID ?>
        <td<?= $Page->mimpID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpID" class="el_makina_importi_mimpID">
<span<?= $Page->mimpID->viewAttributes() ?>>
<?= $Page->mimpID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpMarka->Visible) { // mimpMarka ?>
        <td<?= $Page->mimpMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpMarka" class="el_makina_importi_mimpMarka">
<span<?= $Page->mimpMarka->viewAttributes() ?>>
<?= $Page->mimpMarka->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpModeli->Visible) { // mimpModeli ?>
        <td<?= $Page->mimpModeli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpModeli" class="el_makina_importi_mimpModeli">
<span<?= $Page->mimpModeli->viewAttributes() ?>>
<?= $Page->mimpModeli->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpTipi->Visible) { // mimpTipi ?>
        <td<?= $Page->mimpTipi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpTipi" class="el_makina_importi_mimpTipi">
<span<?= $Page->mimpTipi->viewAttributes() ?>>
<?= $Page->mimpTipi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpShasia->Visible) { // mimpShasia ?>
        <td<?= $Page->mimpShasia->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpShasia" class="el_makina_importi_mimpShasia">
<span<?= $Page->mimpShasia->viewAttributes() ?>>
<?= $Page->mimpShasia->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpViti->Visible) { // mimpViti ?>
        <td<?= $Page->mimpViti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpViti" class="el_makina_importi_mimpViti">
<span<?= $Page->mimpViti->viewAttributes() ?>>
<?= $Page->mimpViti->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpKarburant->Visible) { // mimpKarburant ?>
        <td<?= $Page->mimpKarburant->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpKarburant" class="el_makina_importi_mimpKarburant">
<span<?= $Page->mimpKarburant->viewAttributes() ?>>
<?= $Page->mimpKarburant->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpPrejardhja->Visible) { // mimpPrejardhja ?>
        <td<?= $Page->mimpPrejardhja->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpPrejardhja" class="el_makina_importi_mimpPrejardhja">
<span<?= $Page->mimpPrejardhja->viewAttributes() ?>>
<?= $Page->mimpPrejardhja->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpCmimiBlerjes->Visible) { // mimpCmimiBlerjes ?>
        <td<?= $Page->mimpCmimiBlerjes->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpCmimiBlerjes" class="el_makina_importi_mimpCmimiBlerjes">
<span<?= $Page->mimpCmimiBlerjes->viewAttributes() ?>>
<?= $Page->mimpCmimiBlerjes->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpDogana->Visible) { // mimpDogana ?>
        <td<?= $Page->mimpDogana->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpDogana" class="el_makina_importi_mimpDogana">
<span<?= $Page->mimpDogana->viewAttributes() ?>>
<?= $Page->mimpDogana->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpTransporti->Visible) { // mimpTransporti ?>
        <td<?= $Page->mimpTransporti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpTransporti" class="el_makina_importi_mimpTransporti">
<span<?= $Page->mimpTransporti->viewAttributes() ?>>
<?= $Page->mimpTransporti->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpTjera->Visible) { // mimpTjera ?>
        <td<?= $Page->mimpTjera->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpTjera" class="el_makina_importi_mimpTjera">
<span<?= $Page->mimpTjera->viewAttributes() ?>>
<?= $Page->mimpTjera->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpDtHyrjes->Visible) { // mimpDtHyrjes ?>
        <td<?= $Page->mimpDtHyrjes->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpDtHyrjes" class="el_makina_importi_mimpDtHyrjes">
<span<?= $Page->mimpDtHyrjes->viewAttributes() ?>>
<?= $Page->mimpDtHyrjes->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpCmimiShitjes->Visible) { // mimpCmimiShitjes ?>
        <td<?= $Page->mimpCmimiShitjes->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpCmimiShitjes" class="el_makina_importi_mimpCmimiShitjes">
<span<?= $Page->mimpCmimiShitjes->viewAttributes() ?>>
<?= $Page->mimpCmimiShitjes->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mimpGati->Visible) { // mimpGati ?>
        <td<?= $Page->mimpGati->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_mimpGati" class="el_makina_importi_mimpGati">
<span<?= $Page->mimpGati->viewAttributes() ?>>
<?= $Page->mimpGati->getViewValue() ?></span>
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
