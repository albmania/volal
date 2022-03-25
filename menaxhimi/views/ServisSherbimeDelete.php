<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisSherbimeDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis_sherbime: currentTable } });
var currentForm, currentPageID;
var fservis_sherbimedelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_sherbimedelete = new ew.Form("fservis_sherbimedelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fservis_sherbimedelete;
    loadjs.done("fservis_sherbimedelete");
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
<form name="fservis_sherbimedelete" id="fservis_sherbimedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis_sherbime">
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
<?php if ($Page->servisSherbimID->Visible) { // servisSherbimID ?>
        <th class="<?= $Page->servisSherbimID->headerCellClass() ?>"><span id="elh_servis_sherbime_servisSherbimID" class="servis_sherbime_servisSherbimID"><?= $Page->servisSherbimID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <th class="<?= $Page->servisSherbimServisID->headerCellClass() ?>"><span id="elh_servis_sherbime_servisSherbimServisID" class="servis_sherbime_servisSherbimServisID"><?= $Page->servisSherbimServisID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <th class="<?= $Page->servisSherbimSherbimi->headerCellClass() ?>"><span id="elh_servis_sherbime_servisSherbimSherbimi" class="servis_sherbime_servisSherbimSherbimi"><?= $Page->servisSherbimSherbimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <th class="<?= $Page->servisSherbimCmimi->headerCellClass() ?>"><span id="elh_servis_sherbime_servisSherbimCmimi" class="servis_sherbime_servisSherbimCmimi"><?= $Page->servisSherbimCmimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <th class="<?= $Page->servisSherbimShenim->headerCellClass() ?>"><span id="elh_servis_sherbime_servisSherbimShenim" class="servis_sherbime_servisSherbimShenim"><?= $Page->servisSherbimShenim->caption() ?></span></th>
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
<?php if ($Page->servisSherbimID->Visible) { // servisSherbimID ?>
        <td<?= $Page->servisSherbimID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimID" class="el_servis_sherbime_servisSherbimID">
<span<?= $Page->servisSherbimID->viewAttributes() ?>>
<?= $Page->servisSherbimID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <td<?= $Page->servisSherbimServisID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<span<?= $Page->servisSherbimServisID->viewAttributes() ?>>
<?= $Page->servisSherbimServisID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <td<?= $Page->servisSherbimSherbimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimSherbimi" class="el_servis_sherbime_servisSherbimSherbimi">
<span<?= $Page->servisSherbimSherbimi->viewAttributes() ?>>
<?= $Page->servisSherbimSherbimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <td<?= $Page->servisSherbimCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimCmimi" class="el_servis_sherbime_servisSherbimCmimi">
<span<?= $Page->servisSherbimCmimi->viewAttributes() ?>>
<?= $Page->servisSherbimCmimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <td<?= $Page->servisSherbimShenim->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_sherbime_servisSherbimShenim" class="el_servis_sherbime_servisSherbimShenim">
<span<?= $Page->servisSherbimShenim->viewAttributes() ?>>
<?= $Page->servisSherbimShenim->getViewValue() ?></span>
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
