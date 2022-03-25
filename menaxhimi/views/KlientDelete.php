<?php

namespace PHPMaker2022\volalservice;

// Page object
$KlientDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { klient: currentTable } });
var currentForm, currentPageID;
var fklientdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fklientdelete = new ew.Form("fklientdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fklientdelete;
    loadjs.done("fklientdelete");
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
<form name="fklientdelete" id="fklientdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="klient">
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
<?php if ($Page->klientID->Visible) { // klientID ?>
        <th class="<?= $Page->klientID->headerCellClass() ?>"><span id="elh_klient_klientID" class="klient_klientID"><?= $Page->klientID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientTipi->Visible) { // klientTipi ?>
        <th class="<?= $Page->klientTipi->headerCellClass() ?>"><span id="elh_klient_klientTipi" class="klient_klientTipi"><?= $Page->klientTipi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientEmertimi->Visible) { // klientEmertimi ?>
        <th class="<?= $Page->klientEmertimi->headerCellClass() ?>"><span id="elh_klient_klientEmertimi" class="klient_klientEmertimi"><?= $Page->klientEmertimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientNIPT->Visible) { // klientNIPT ?>
        <th class="<?= $Page->klientNIPT->headerCellClass() ?>"><span id="elh_klient_klientNIPT" class="klient_klientNIPT"><?= $Page->klientNIPT->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientAdresa->Visible) { // klientAdresa ?>
        <th class="<?= $Page->klientAdresa->headerCellClass() ?>"><span id="elh_klient_klientAdresa" class="klient_klientAdresa"><?= $Page->klientAdresa->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientQyteti->Visible) { // klientQyteti ?>
        <th class="<?= $Page->klientQyteti->headerCellClass() ?>"><span id="elh_klient_klientQyteti" class="klient_klientQyteti"><?= $Page->klientQyteti->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientTel1->Visible) { // klientTel1 ?>
        <th class="<?= $Page->klientTel1->headerCellClass() ?>"><span id="elh_klient_klientTel1" class="klient_klientTel1"><?= $Page->klientTel1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientTel2->Visible) { // klientTel2 ?>
        <th class="<?= $Page->klientTel2->headerCellClass() ?>"><span id="elh_klient_klientTel2" class="klient_klientTel2"><?= $Page->klientTel2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientEmail->Visible) { // klientEmail ?>
        <th class="<?= $Page->klientEmail->headerCellClass() ?>"><span id="elh_klient_klientEmail" class="klient_klientEmail"><?= $Page->klientEmail->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientAutori->Visible) { // klientAutori ?>
        <th class="<?= $Page->klientAutori->headerCellClass() ?>"><span id="elh_klient_klientAutori" class="klient_klientAutori"><?= $Page->klientAutori->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientShtuar->Visible) { // klientShtuar ?>
        <th class="<?= $Page->klientShtuar->headerCellClass() ?>"><span id="elh_klient_klientShtuar" class="klient_klientShtuar"><?= $Page->klientShtuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->klientModifikuar->Visible) { // klientModifikuar ?>
        <th class="<?= $Page->klientModifikuar->headerCellClass() ?>"><span id="elh_klient_klientModifikuar" class="klient_klientModifikuar"><?= $Page->klientModifikuar->caption() ?></span></th>
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
<?php if ($Page->klientID->Visible) { // klientID ?>
        <td<?= $Page->klientID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientID" class="el_klient_klientID">
<span<?= $Page->klientID->viewAttributes() ?>>
<?= $Page->klientID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientTipi->Visible) { // klientTipi ?>
        <td<?= $Page->klientTipi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientTipi" class="el_klient_klientTipi">
<span<?= $Page->klientTipi->viewAttributes() ?>>
<?= $Page->klientTipi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientEmertimi->Visible) { // klientEmertimi ?>
        <td<?= $Page->klientEmertimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientEmertimi" class="el_klient_klientEmertimi">
<span<?= $Page->klientEmertimi->viewAttributes() ?>>
<?= $Page->klientEmertimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientNIPT->Visible) { // klientNIPT ?>
        <td<?= $Page->klientNIPT->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientNIPT" class="el_klient_klientNIPT">
<span<?= $Page->klientNIPT->viewAttributes() ?>>
<?= $Page->klientNIPT->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientAdresa->Visible) { // klientAdresa ?>
        <td<?= $Page->klientAdresa->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientAdresa" class="el_klient_klientAdresa">
<span<?= $Page->klientAdresa->viewAttributes() ?>>
<?= $Page->klientAdresa->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientQyteti->Visible) { // klientQyteti ?>
        <td<?= $Page->klientQyteti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientQyteti" class="el_klient_klientQyteti">
<span<?= $Page->klientQyteti->viewAttributes() ?>>
<?= $Page->klientQyteti->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientTel1->Visible) { // klientTel1 ?>
        <td<?= $Page->klientTel1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientTel1" class="el_klient_klientTel1">
<span<?= $Page->klientTel1->viewAttributes() ?>>
<?= $Page->klientTel1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientTel2->Visible) { // klientTel2 ?>
        <td<?= $Page->klientTel2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientTel2" class="el_klient_klientTel2">
<span<?= $Page->klientTel2->viewAttributes() ?>>
<?= $Page->klientTel2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientEmail->Visible) { // klientEmail ?>
        <td<?= $Page->klientEmail->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientEmail" class="el_klient_klientEmail">
<span<?= $Page->klientEmail->viewAttributes() ?>>
<?= $Page->klientEmail->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientAutori->Visible) { // klientAutori ?>
        <td<?= $Page->klientAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientAutori" class="el_klient_klientAutori">
<span<?= $Page->klientAutori->viewAttributes() ?>>
<?= $Page->klientAutori->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientShtuar->Visible) { // klientShtuar ?>
        <td<?= $Page->klientShtuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientShtuar" class="el_klient_klientShtuar">
<span<?= $Page->klientShtuar->viewAttributes() ?>>
<?= $Page->klientShtuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->klientModifikuar->Visible) { // klientModifikuar ?>
        <td<?= $Page->klientModifikuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_klient_klientModifikuar" class="el_klient_klientModifikuar">
<span<?= $Page->klientModifikuar->viewAttributes() ?>>
<?= $Page->klientModifikuar->getViewValue() ?></span>
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
