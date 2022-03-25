<?php

namespace PHPMaker2022\volalservice;

// Page object
$FaqeDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { faqe: currentTable } });
var currentForm, currentPageID;
var ffaqedelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    ffaqedelete = new ew.Form("ffaqedelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = ffaqedelete;
    loadjs.done("ffaqedelete");
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
<form name="ffaqedelete" id="ffaqedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="faqe">
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
<?php if ($Page->faqeID->Visible) { // faqeID ?>
        <th class="<?= $Page->faqeID->headerCellClass() ?>"><span id="elh_faqe_faqeID" class="faqe_faqeID"><?= $Page->faqeID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->faqeEmri_sq->Visible) { // faqeEmri_sq ?>
        <th class="<?= $Page->faqeEmri_sq->headerCellClass() ?>"><span id="elh_faqe_faqeEmri_sq" class="faqe_faqeEmri_sq"><?= $Page->faqeEmri_sq->caption() ?></span></th>
<?php } ?>
<?php if ($Page->faqeEmri_en->Visible) { // faqeEmri_en ?>
        <th class="<?= $Page->faqeEmri_en->headerCellClass() ?>"><span id="elh_faqe_faqeEmri_en" class="faqe_faqeEmri_en"><?= $Page->faqeEmri_en->caption() ?></span></th>
<?php } ?>
<?php if ($Page->faqeAutori->Visible) { // faqeAutori ?>
        <th class="<?= $Page->faqeAutori->headerCellClass() ?>"><span id="elh_faqe_faqeAutori" class="faqe_faqeAutori"><?= $Page->faqeAutori->caption() ?></span></th>
<?php } ?>
<?php if ($Page->faqeKrijuar->Visible) { // faqeKrijuar ?>
        <th class="<?= $Page->faqeKrijuar->headerCellClass() ?>"><span id="elh_faqe_faqeKrijuar" class="faqe_faqeKrijuar"><?= $Page->faqeKrijuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->faqeAzhornuar->Visible) { // faqeAzhornuar ?>
        <th class="<?= $Page->faqeAzhornuar->headerCellClass() ?>"><span id="elh_faqe_faqeAzhornuar" class="faqe_faqeAzhornuar"><?= $Page->faqeAzhornuar->caption() ?></span></th>
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
<?php if ($Page->faqeID->Visible) { // faqeID ?>
        <td<?= $Page->faqeID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeID" class="el_faqe_faqeID">
<span<?= $Page->faqeID->viewAttributes() ?>>
<?= $Page->faqeID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->faqeEmri_sq->Visible) { // faqeEmri_sq ?>
        <td<?= $Page->faqeEmri_sq->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeEmri_sq" class="el_faqe_faqeEmri_sq">
<span<?= $Page->faqeEmri_sq->viewAttributes() ?>>
<?= $Page->faqeEmri_sq->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->faqeEmri_en->Visible) { // faqeEmri_en ?>
        <td<?= $Page->faqeEmri_en->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeEmri_en" class="el_faqe_faqeEmri_en">
<span<?= $Page->faqeEmri_en->viewAttributes() ?>>
<?= $Page->faqeEmri_en->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->faqeAutori->Visible) { // faqeAutori ?>
        <td<?= $Page->faqeAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeAutori" class="el_faqe_faqeAutori">
<span<?= $Page->faqeAutori->viewAttributes() ?>>
<?= $Page->faqeAutori->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->faqeKrijuar->Visible) { // faqeKrijuar ?>
        <td<?= $Page->faqeKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeKrijuar" class="el_faqe_faqeKrijuar">
<span<?= $Page->faqeKrijuar->viewAttributes() ?>>
<?= $Page->faqeKrijuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->faqeAzhornuar->Visible) { // faqeAzhornuar ?>
        <td<?= $Page->faqeAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_faqe_faqeAzhornuar" class="el_faqe_faqeAzhornuar">
<span<?= $Page->faqeAzhornuar->viewAttributes() ?>>
<?= $Page->faqeAzhornuar->getViewValue() ?></span>
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
