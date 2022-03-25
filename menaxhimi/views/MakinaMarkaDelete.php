<?php

namespace PHPMaker2022\volalservice;

// Page object
$MakinaMarkaDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { makina_marka: currentTable } });
var currentForm, currentPageID;
var fmakina_markadelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_markadelete = new ew.Form("fmakina_markadelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmakina_markadelete;
    loadjs.done("fmakina_markadelete");
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
<form name="fmakina_markadelete" id="fmakina_markadelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="makina_marka">
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
<?php if ($Page->mmarkaID->Visible) { // mmarkaID ?>
        <th class="<?= $Page->mmarkaID->headerCellClass() ?>"><span id="elh_makina_marka_mmarkaID" class="makina_marka_mmarkaID"><?= $Page->mmarkaID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mmarkaMarka->Visible) { // mmarkaMarka ?>
        <th class="<?= $Page->mmarkaMarka->headerCellClass() ?>"><span id="elh_makina_marka_mmarkaMarka" class="makina_marka_mmarkaMarka"><?= $Page->mmarkaMarka->caption() ?></span></th>
<?php } ?>
<?php if ($Page->mmarkaLogo->Visible) { // mmarkaLogo ?>
        <th class="<?= $Page->mmarkaLogo->headerCellClass() ?>"><span id="elh_makina_marka_mmarkaLogo" class="makina_marka_mmarkaLogo"><?= $Page->mmarkaLogo->caption() ?></span></th>
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
<?php if ($Page->mmarkaID->Visible) { // mmarkaID ?>
        <td<?= $Page->mmarkaID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_marka_mmarkaID" class="el_makina_marka_mmarkaID">
<span<?= $Page->mmarkaID->viewAttributes() ?>>
<?= $Page->mmarkaID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mmarkaMarka->Visible) { // mmarkaMarka ?>
        <td<?= $Page->mmarkaMarka->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_marka_mmarkaMarka" class="el_makina_marka_mmarkaMarka">
<span<?= $Page->mmarkaMarka->viewAttributes() ?>>
<?= $Page->mmarkaMarka->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->mmarkaLogo->Visible) { // mmarkaLogo ?>
        <td<?= $Page->mmarkaLogo->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_makina_marka_mmarkaLogo" class="el_makina_marka_mmarkaLogo">
<span>
<?= GetFileViewTag($Page->mmarkaLogo, $Page->mmarkaLogo->getViewValue(), false) ?>
</span>
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
