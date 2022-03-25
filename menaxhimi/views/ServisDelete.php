<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis: currentTable } });
var currentForm, currentPageID;
var fservisdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservisdelete = new ew.Form("fservisdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fservisdelete;
    loadjs.done("fservisdelete");
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
<form name="fservisdelete" id="fservisdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis">
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
<?php if ($Page->servisID->Visible) { // servisID ?>
        <th class="<?= $Page->servisID->headerCellClass() ?>"><span id="elh_servis_servisID" class="servis_servisID"><?= $Page->servisID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisDate->Visible) { // servisDate ?>
        <th class="<?= $Page->servisDate->headerCellClass() ?>"><span id="elh_servis_servisDate" class="servis_servisDate"><?= $Page->servisDate->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisKlienti->Visible) { // servisKlienti ?>
        <th class="<?= $Page->servisKlienti->headerCellClass() ?>"><span id="elh_servis_servisKlienti" class="servis_servisKlienti"><?= $Page->servisKlienti->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisMakina->Visible) { // servisMakina ?>
        <th class="<?= $Page->servisMakina->headerCellClass() ?>"><span id="elh_servis_servisMakina" class="servis_servisMakina"><?= $Page->servisMakina->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisKmMakines->Visible) { // servisKmMakines ?>
        <th class="<?= $Page->servisKmMakines->headerCellClass() ?>"><span id="elh_servis_servisKmMakines" class="servis_servisKmMakines"><?= $Page->servisKmMakines->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisStafi->Visible) { // servisStafi ?>
        <th class="<?= $Page->servisStafi->headerCellClass() ?>"><span id="elh_servis_servisStafi" class="servis_servisStafi"><?= $Page->servisStafi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisTotaliFatures->Visible) { // servisTotaliFatures ?>
        <th class="<?= $Page->servisTotaliFatures->headerCellClass() ?>"><span id="elh_servis_servisTotaliFatures" class="servis_servisTotaliFatures"><?= $Page->servisTotaliFatures->caption() ?></span></th>
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
<?php if ($Page->servisID->Visible) { // servisID ?>
        <td<?= $Page->servisID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisID" class="el_servis_servisID">
<span<?= $Page->servisID->viewAttributes() ?>>
<?= $Page->servisID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisDate->Visible) { // servisDate ?>
        <td<?= $Page->servisDate->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisDate" class="el_servis_servisDate">
<span<?= $Page->servisDate->viewAttributes() ?>>
<?= $Page->servisDate->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisKlienti->Visible) { // servisKlienti ?>
        <td<?= $Page->servisKlienti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisKlienti" class="el_servis_servisKlienti">
<span<?= $Page->servisKlienti->viewAttributes() ?>>
<?= $Page->servisKlienti->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisMakina->Visible) { // servisMakina ?>
        <td<?= $Page->servisMakina->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisMakina" class="el_servis_servisMakina">
<span<?= $Page->servisMakina->viewAttributes() ?>>
<?= $Page->servisMakina->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisKmMakines->Visible) { // servisKmMakines ?>
        <td<?= $Page->servisKmMakines->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisKmMakines" class="el_servis_servisKmMakines">
<span<?= $Page->servisKmMakines->viewAttributes() ?>>
<?= $Page->servisKmMakines->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisStafi->Visible) { // servisStafi ?>
        <td<?= $Page->servisStafi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisStafi" class="el_servis_servisStafi">
<span<?= $Page->servisStafi->viewAttributes() ?>>
<?= $Page->servisStafi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisTotaliFatures->Visible) { // servisTotaliFatures ?>
        <td<?= $Page->servisTotaliFatures->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisTotaliFatures" class="el_servis_servisTotaliFatures">
<span<?= $Page->servisTotaliFatures->viewAttributes() ?>>
<?= $Page->servisTotaliFatures->getViewValue() ?></span>
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
