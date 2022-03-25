<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaModeliDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_modeli: currentTable } });
var currentForm, currentPageID;
var fmakina_modelidelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_modelidelete = new ew.Form("fmakina_modelidelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmakina_modelidelete;
    loadjs.done("fmakina_modelidelete");
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
<form name="fmakina_modelidelete" id="fmakina_modelidelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_modeli">
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
<?php if ($Page->mmodeliID->Visible) { // mmodeliID ?>
        <th class="<?= $Page->mmodeliID->headerCellClass() ?>"><span id="elh_makina_modeli_mmodeliID" class="makina_modeli_mmodeliID"><?= $Page->mmodeliID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mmodeliMarka->Visible) { // mmodeliMarka ?>
        <th class="<?= $Page->mmodeliMarka->headerCellClass() ?>"><span id="elh_makina_modeli_mmodeliMarka" class="makina_modeli_mmodeliMarka"><?= $Page->mmodeliMarka->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mmodeliModeli->Visible) { // mmodeliModeli ?>
        <th class="<?= $Page->mmodeliModeli->headerCellClass() ?>"><span id="elh_makina_modeli_mmodeliModeli" class="makina_modeli_mmodeliModeli"><?= $Page->mmodeliModeli->caption() ?></span></th>
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
<?php if ($Page->mmodeliID->Visible) { // mmodeliID ?>
        <td<?= $Page->mmodeliID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_modeli_mmodeliID" class="el_makina_modeli_mmodeliID">
<span<?= $Page->mmodeliID->viewAttributes() ?>>
<?= $Page->mmodeliID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mmodeliMarka->Visible) { // mmodeliMarka ?>
        <td<?= $Page->mmodeliMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_modeli_mmodeliMarka" class="el_makina_modeli_mmodeliMarka">
<span<?= $Page->mmodeliMarka->viewAttributes() ?>>
<?= $Page->mmodeliMarka->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mmodeliModeli->Visible) { // mmodeliModeli ?>
        <td<?= $Page->mmodeliModeli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_modeli_mmodeliModeli" class="el_makina_modeli_mmodeliModeli">
<span<?= $Page->mmodeliModeli->viewAttributes() ?>>
<?= $Page->mmodeliModeli->getViewValue() ?></span>
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
