<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaImportiSherbimeDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_importi_sherbime: currentTable } });
var currentForm, currentPageID;
var fmakina_importi_sherbimedelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importi_sherbimedelete = new ew.Form("fmakina_importi_sherbimedelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmakina_importi_sherbimedelete;
    loadjs.done("fmakina_importi_sherbimedelete");
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
<form name="fmakina_importi_sherbimedelete" id="fmakina_importi_sherbimedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_importi_sherbime">
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
<?php if ($Page->mishID->Visible) { // mishID ?>
        <th class="<?= $Page->mishID->headerCellClass() ?>"><span id="elh_makina_importi_sherbime_mishID" class="makina_importi_sherbime_mishID"><?= $Page->mishID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <th class="<?= $Page->mishMakinaImporti->headerCellClass() ?>"><span id="elh_makina_importi_sherbime_mishMakinaImporti" class="makina_importi_sherbime_mishMakinaImporti"><?= $Page->mishMakinaImporti->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <th class="<?= $Page->mishPershkrimi->headerCellClass() ?>"><span id="elh_makina_importi_sherbime_mishPershkrimi" class="makina_importi_sherbime_mishPershkrimi"><?= $Page->mishPershkrimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mishKryer->Visible) { // mishKryer ?>
        <th class="<?= $Page->mishKryer->headerCellClass() ?>"><span id="elh_makina_importi_sherbime_mishKryer" class="makina_importi_sherbime_mishKryer"><?= $Page->mishKryer->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mishCmimi->Visible) { // mishCmimi ?>
        <th class="<?= $Page->mishCmimi->headerCellClass() ?>"><span id="elh_makina_importi_sherbime_mishCmimi" class="makina_importi_sherbime_mishCmimi"><?= $Page->mishCmimi->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mishData->Visible) { // mishData ?>
        <th class="<?= $Page->mishData->headerCellClass() ?>"><span id="elh_makina_importi_sherbime_mishData" class="makina_importi_sherbime_mishData"><?= $Page->mishData->caption() ?></span></th>
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
<?php if ($Page->mishID->Visible) { // mishID ?>
        <td<?= $Page->mishID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishID" class="el_makina_importi_sherbime_mishID">
<span<?= $Page->mishID->viewAttributes() ?>>
<?= $Page->mishID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <td<?= $Page->mishMakinaImporti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Page->mishMakinaImporti->viewAttributes() ?>>
<?= $Page->mishMakinaImporti->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <td<?= $Page->mishPershkrimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishPershkrimi" class="el_makina_importi_sherbime_mishPershkrimi">
<span<?= $Page->mishPershkrimi->viewAttributes() ?>>
<?= $Page->mishPershkrimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mishKryer->Visible) { // mishKryer ?>
        <td<?= $Page->mishKryer->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishKryer" class="el_makina_importi_sherbime_mishKryer">
<span<?= $Page->mishKryer->viewAttributes() ?>>
<?= $Page->mishKryer->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mishCmimi->Visible) { // mishCmimi ?>
        <td<?= $Page->mishCmimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishCmimi" class="el_makina_importi_sherbime_mishCmimi">
<span<?= $Page->mishCmimi->viewAttributes() ?>>
<?= $Page->mishCmimi->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mishData->Visible) { // mishData ?>
        <td<?= $Page->mishData->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_importi_sherbime_mishData" class="el_makina_importi_sherbime_mishData">
<span<?= $Page->mishData->viewAttributes() ?>>
<?= $Page->mishData->getViewValue() ?></span>
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
