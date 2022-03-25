<?php

namespace PHPMaker2022\volalservice;

// Page object
$PjeseKembimiDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { pjese_kembimi: currentTable } });
var currentForm, currentPageID;
var fpjese_kembimidelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fpjese_kembimidelete = new ew.Form("fpjese_kembimidelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fpjese_kembimidelete;
    loadjs.done("fpjese_kembimidelete");
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
<form name="fpjese_kembimidelete" id="fpjese_kembimidelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="pjese_kembimi">
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
<?php if ($Page->pjeseID->Visible) { // pjeseID ?>
        <th class="<?= $Page->pjeseID->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseID" class="pjese_kembimi_pjeseID"><?= $Page->pjeseID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseGjendja->Visible) { // pjeseGjendja ?>
        <th class="<?= $Page->pjeseGjendja->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseGjendja" class="pjese_kembimi_pjeseGjendja"><?= $Page->pjeseGjendja->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseKodiVolvo->Visible) { // pjeseKodiVolvo ?>
        <th class="<?= $Page->pjeseKodiVolvo->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseKodiVolvo" class="pjese_kembimi_pjeseKodiVolvo"><?= $Page->pjeseKodiVolvo->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseKodiProdhuesi->Visible) { // pjeseKodiProdhuesi ?>
        <th class="<?= $Page->pjeseKodiProdhuesi->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseKodiProdhuesi" class="pjese_kembimi_pjeseKodiProdhuesi"><?= $Page->pjeseKodiProdhuesi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseProdhuesi->Visible) { // pjeseProdhuesi ?>
        <th class="<?= $Page->pjeseProdhuesi->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseProdhuesi" class="pjese_kembimi_pjeseProdhuesi"><?= $Page->pjeseProdhuesi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjesePerMarke->Visible) { // pjesePerMarke ?>
        <th class="<?= $Page->pjesePerMarke->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjesePerMarke" class="pjese_kembimi_pjesePerMarke"><?= $Page->pjesePerMarke->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjesePerModel->Visible) { // pjesePerModel ?>
        <th class="<?= $Page->pjesePerModel->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjesePerModel" class="pjese_kembimi_pjesePerModel"><?= $Page->pjesePerModel->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjesePerVitProdhimi->Visible) { // pjesePerVitProdhimi ?>
        <th class="<?= $Page->pjesePerVitProdhimi->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjesePerVitProdhimi" class="pjese_kembimi_pjesePerVitProdhimi"><?= $Page->pjesePerVitProdhimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseCmimBlerje->Visible) { // pjeseCmimBlerje ?>
        <th class="<?= $Page->pjeseCmimBlerje->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseCmimBlerje" class="pjese_kembimi_pjeseCmimBlerje"><?= $Page->pjeseCmimBlerje->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseCmimShitje->Visible) { // pjeseCmimShitje ?>
        <th class="<?= $Page->pjeseCmimShitje->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseCmimShitje" class="pjese_kembimi_pjeseCmimShitje"><?= $Page->pjeseCmimShitje->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseAutori->Visible) { // pjeseAutori ?>
        <th class="<?= $Page->pjeseAutori->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseAutori" class="pjese_kembimi_pjeseAutori"><?= $Page->pjeseAutori->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseShtuar->Visible) { // pjeseShtuar ?>
        <th class="<?= $Page->pjeseShtuar->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseShtuar" class="pjese_kembimi_pjeseShtuar"><?= $Page->pjeseShtuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->pjeseModifikuar->Visible) { // pjeseModifikuar ?>
        <th class="<?= $Page->pjeseModifikuar->headerCellClass() ?>"><span id="elh_pjese_kembimi_pjeseModifikuar" class="pjese_kembimi_pjeseModifikuar"><?= $Page->pjeseModifikuar->caption() ?></span></th>
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
<?php if ($Page->pjeseID->Visible) { // pjeseID ?>
        <td<?= $Page->pjeseID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseID" class="el_pjese_kembimi_pjeseID">
<span<?= $Page->pjeseID->viewAttributes() ?>>
<?= $Page->pjeseID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseGjendja->Visible) { // pjeseGjendja ?>
        <td<?= $Page->pjeseGjendja->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseGjendja" class="el_pjese_kembimi_pjeseGjendja">
<span<?= $Page->pjeseGjendja->viewAttributes() ?>>
<?= $Page->pjeseGjendja->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseKodiVolvo->Visible) { // pjeseKodiVolvo ?>
        <td<?= $Page->pjeseKodiVolvo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseKodiVolvo" class="el_pjese_kembimi_pjeseKodiVolvo">
<span<?= $Page->pjeseKodiVolvo->viewAttributes() ?>>
<?= $Page->pjeseKodiVolvo->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseKodiProdhuesi->Visible) { // pjeseKodiProdhuesi ?>
        <td<?= $Page->pjeseKodiProdhuesi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseKodiProdhuesi" class="el_pjese_kembimi_pjeseKodiProdhuesi">
<span<?= $Page->pjeseKodiProdhuesi->viewAttributes() ?>>
<?= $Page->pjeseKodiProdhuesi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseProdhuesi->Visible) { // pjeseProdhuesi ?>
        <td<?= $Page->pjeseProdhuesi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseProdhuesi" class="el_pjese_kembimi_pjeseProdhuesi">
<span<?= $Page->pjeseProdhuesi->viewAttributes() ?>>
<?= $Page->pjeseProdhuesi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjesePerMarke->Visible) { // pjesePerMarke ?>
        <td<?= $Page->pjesePerMarke->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjesePerMarke" class="el_pjese_kembimi_pjesePerMarke">
<span<?= $Page->pjesePerMarke->viewAttributes() ?>>
<?= $Page->pjesePerMarke->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjesePerModel->Visible) { // pjesePerModel ?>
        <td<?= $Page->pjesePerModel->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjesePerModel" class="el_pjese_kembimi_pjesePerModel">
<span<?= $Page->pjesePerModel->viewAttributes() ?>>
<?= $Page->pjesePerModel->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjesePerVitProdhimi->Visible) { // pjesePerVitProdhimi ?>
        <td<?= $Page->pjesePerVitProdhimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjesePerVitProdhimi" class="el_pjese_kembimi_pjesePerVitProdhimi">
<span<?= $Page->pjesePerVitProdhimi->viewAttributes() ?>>
<?= $Page->pjesePerVitProdhimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseCmimBlerje->Visible) { // pjeseCmimBlerje ?>
        <td<?= $Page->pjeseCmimBlerje->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseCmimBlerje" class="el_pjese_kembimi_pjeseCmimBlerje">
<span<?= $Page->pjeseCmimBlerje->viewAttributes() ?>>
<?= $Page->pjeseCmimBlerje->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseCmimShitje->Visible) { // pjeseCmimShitje ?>
        <td<?= $Page->pjeseCmimShitje->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseCmimShitje" class="el_pjese_kembimi_pjeseCmimShitje">
<span<?= $Page->pjeseCmimShitje->viewAttributes() ?>>
<?= $Page->pjeseCmimShitje->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseAutori->Visible) { // pjeseAutori ?>
        <td<?= $Page->pjeseAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseAutori" class="el_pjese_kembimi_pjeseAutori">
<span<?= $Page->pjeseAutori->viewAttributes() ?>>
<?= $Page->pjeseAutori->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseShtuar->Visible) { // pjeseShtuar ?>
        <td<?= $Page->pjeseShtuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseShtuar" class="el_pjese_kembimi_pjeseShtuar">
<span<?= $Page->pjeseShtuar->viewAttributes() ?>>
<?= $Page->pjeseShtuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->pjeseModifikuar->Visible) { // pjeseModifikuar ?>
        <td<?= $Page->pjeseModifikuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_pjese_kembimi_pjeseModifikuar" class="el_pjese_kembimi_pjeseModifikuar">
<span<?= $Page->pjeseModifikuar->viewAttributes() ?>>
<?= $Page->pjeseModifikuar->getViewValue() ?></span>
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
