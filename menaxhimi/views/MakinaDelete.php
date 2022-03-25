<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina: currentTable } });
var currentForm, currentPageID;
var fmakinadelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakinadelete = new ew.Form("fmakinadelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmakinadelete;
    loadjs.done("fmakinadelete");
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
<form name="fmakinadelete" id="fmakinadelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina">
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
<?php if ($Page->makinaID->Visible) { // makinaID ?>
        <th class="<?= $Page->makinaID->headerCellClass() ?>"><span id="elh_makina_makinaID" class="makina_makinaID"><?= $Page->makinaID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaKlienti->Visible) { // makinaKlienti ?>
        <th class="<?= $Page->makinaKlienti->headerCellClass() ?>"><span id="elh_makina_makinaKlienti" class="makina_makinaKlienti"><?= $Page->makinaKlienti->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaMarka->Visible) { // makinaMarka ?>
        <th class="<?= $Page->makinaMarka->headerCellClass() ?>"><span id="elh_makina_makinaMarka" class="makina_makinaMarka"><?= $Page->makinaMarka->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaModeli->Visible) { // makinaModeli ?>
        <th class="<?= $Page->makinaModeli->headerCellClass() ?>"><span id="elh_makina_makinaModeli" class="makina_makinaModeli"><?= $Page->makinaModeli->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaKarburanti->Visible) { // makinaKarburanti ?>
        <th class="<?= $Page->makinaKarburanti->headerCellClass() ?>"><span id="elh_makina_makinaKarburanti" class="makina_makinaKarburanti"><?= $Page->makinaKarburanti->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaMadhesiaMotorrit->Visible) { // makinaMadhesiaMotorrit ?>
        <th class="<?= $Page->makinaMadhesiaMotorrit->headerCellClass() ?>"><span id="elh_makina_makinaMadhesiaMotorrit" class="makina_makinaMadhesiaMotorrit"><?= $Page->makinaMadhesiaMotorrit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaVitiProdhimit->Visible) { // makinaVitiProdhimit ?>
        <th class="<?= $Page->makinaVitiProdhimit->headerCellClass() ?>"><span id="elh_makina_makinaVitiProdhimit" class="makina_makinaVitiProdhimit"><?= $Page->makinaVitiProdhimit->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaTarga->Visible) { // makinaTarga ?>
        <th class="<?= $Page->makinaTarga->headerCellClass() ?>"><span id="elh_makina_makinaTarga" class="makina_makinaTarga"><?= $Page->makinaTarga->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaShiturVOLAL->Visible) { // makinaShiturVOLAL ?>
        <th class="<?= $Page->makinaShiturVOLAL->headerCellClass() ?>"><span id="elh_makina_makinaShiturVOLAL" class="makina_makinaShiturVOLAL"><?= $Page->makinaShiturVOLAL->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaAutori->Visible) { // makinaAutori ?>
        <th class="<?= $Page->makinaAutori->headerCellClass() ?>"><span id="elh_makina_makinaAutori" class="makina_makinaAutori"><?= $Page->makinaAutori->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaShtuar->Visible) { // makinaShtuar ?>
        <th class="<?= $Page->makinaShtuar->headerCellClass() ?>"><span id="elh_makina_makinaShtuar" class="makina_makinaShtuar"><?= $Page->makinaShtuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->makinaModifikuar->Visible) { // makinaModifikuar ?>
        <th class="<?= $Page->makinaModifikuar->headerCellClass() ?>"><span id="elh_makina_makinaModifikuar" class="makina_makinaModifikuar"><?= $Page->makinaModifikuar->caption() ?></span></th>
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
<?php if ($Page->makinaID->Visible) { // makinaID ?>
        <td<?= $Page->makinaID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaID" class="el_makina_makinaID">
<span<?= $Page->makinaID->viewAttributes() ?>>
<?= $Page->makinaID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaKlienti->Visible) { // makinaKlienti ?>
        <td<?= $Page->makinaKlienti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaKlienti" class="el_makina_makinaKlienti">
<span<?= $Page->makinaKlienti->viewAttributes() ?>>
<?= $Page->makinaKlienti->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaMarka->Visible) { // makinaMarka ?>
        <td<?= $Page->makinaMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaMarka" class="el_makina_makinaMarka">
<span<?= $Page->makinaMarka->viewAttributes() ?>>
<?= $Page->makinaMarka->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaModeli->Visible) { // makinaModeli ?>
        <td<?= $Page->makinaModeli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaModeli" class="el_makina_makinaModeli">
<span<?= $Page->makinaModeli->viewAttributes() ?>>
<?= $Page->makinaModeli->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaKarburanti->Visible) { // makinaKarburanti ?>
        <td<?= $Page->makinaKarburanti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaKarburanti" class="el_makina_makinaKarburanti">
<span<?= $Page->makinaKarburanti->viewAttributes() ?>>
<?= $Page->makinaKarburanti->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaMadhesiaMotorrit->Visible) { // makinaMadhesiaMotorrit ?>
        <td<?= $Page->makinaMadhesiaMotorrit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaMadhesiaMotorrit" class="el_makina_makinaMadhesiaMotorrit">
<span<?= $Page->makinaMadhesiaMotorrit->viewAttributes() ?>>
<?= $Page->makinaMadhesiaMotorrit->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaVitiProdhimit->Visible) { // makinaVitiProdhimit ?>
        <td<?= $Page->makinaVitiProdhimit->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaVitiProdhimit" class="el_makina_makinaVitiProdhimit">
<span<?= $Page->makinaVitiProdhimit->viewAttributes() ?>>
<?= $Page->makinaVitiProdhimit->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaTarga->Visible) { // makinaTarga ?>
        <td<?= $Page->makinaTarga->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaTarga" class="el_makina_makinaTarga">
<span<?= $Page->makinaTarga->viewAttributes() ?>>
<?= $Page->makinaTarga->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaShiturVOLAL->Visible) { // makinaShiturVOLAL ?>
        <td<?= $Page->makinaShiturVOLAL->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaShiturVOLAL" class="el_makina_makinaShiturVOLAL">
<span<?= $Page->makinaShiturVOLAL->viewAttributes() ?>>
<?= $Page->makinaShiturVOLAL->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaAutori->Visible) { // makinaAutori ?>
        <td<?= $Page->makinaAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaAutori" class="el_makina_makinaAutori">
<span<?= $Page->makinaAutori->viewAttributes() ?>>
<?= $Page->makinaAutori->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaShtuar->Visible) { // makinaShtuar ?>
        <td<?= $Page->makinaShtuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaShtuar" class="el_makina_makinaShtuar">
<span<?= $Page->makinaShtuar->viewAttributes() ?>>
<?= $Page->makinaShtuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->makinaModifikuar->Visible) { // makinaModifikuar ?>
        <td<?= $Page->makinaModifikuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_makinaModifikuar" class="el_makina_makinaModifikuar">
<span<?= $Page->makinaModifikuar->viewAttributes() ?>>
<?= $Page->makinaModifikuar->getViewValue() ?></span>
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
