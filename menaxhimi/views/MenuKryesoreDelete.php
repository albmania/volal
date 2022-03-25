<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuKryesoreDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_kryesore: currentTable } });
var currentForm, currentPageID;
var fmenu_kryesoredelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_kryesoredelete = new ew.Form("fmenu_kryesoredelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmenu_kryesoredelete;
    loadjs.done("fmenu_kryesoredelete");
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
<form name="fmenu_kryesoredelete" id="fmenu_kryesoredelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="menu_kryesore">
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
<?php if ($Page->menukID->Visible) { // menukID ?>
        <th class="<?= $Page->menukID->headerCellClass() ?>"><span id="elh_menu_kryesore_menukID" class="menu_kryesore_menukID"><?= $Page->menukID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukGjuha->Visible) { // menukGjuha ?>
        <th class="<?= $Page->menukGjuha->headerCellClass() ?>"><span id="elh_menu_kryesore_menukGjuha" class="menu_kryesore_menukGjuha"><?= $Page->menukGjuha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukTitull->Visible) { // menukTitull ?>
        <th class="<?= $Page->menukTitull->headerCellClass() ?>"><span id="elh_menu_kryesore_menukTitull" class="menu_kryesore_menukTitull"><?= $Page->menukTitull->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukUrl->Visible) { // menukUrl ?>
        <th class="<?= $Page->menukUrl->headerCellClass() ?>"><span id="elh_menu_kryesore_menukUrl" class="menu_kryesore_menukUrl"><?= $Page->menukUrl->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukBlank->Visible) { // menukBlank ?>
        <th class="<?= $Page->menukBlank->headerCellClass() ?>"><span id="elh_menu_kryesore_menukBlank" class="menu_kryesore_menukBlank"><?= $Page->menukBlank->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukRadhe->Visible) { // menukRadhe ?>
        <th class="<?= $Page->menukRadhe->headerCellClass() ?>"><span id="elh_menu_kryesore_menukRadhe" class="menu_kryesore_menukRadhe"><?= $Page->menukRadhe->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukAktiv->Visible) { // menukAktiv ?>
        <th class="<?= $Page->menukAktiv->headerCellClass() ?>"><span id="elh_menu_kryesore_menukAktiv" class="menu_kryesore_menukAktiv"><?= $Page->menukAktiv->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukAutor->Visible) { // menukAutor ?>
        <th class="<?= $Page->menukAutor->headerCellClass() ?>"><span id="elh_menu_kryesore_menukAutor" class="menu_kryesore_menukAutor"><?= $Page->menukAutor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukKrijuar->Visible) { // menukKrijuar ?>
        <th class="<?= $Page->menukKrijuar->headerCellClass() ?>"><span id="elh_menu_kryesore_menukKrijuar" class="menu_kryesore_menukKrijuar"><?= $Page->menukKrijuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menukAzhornuar->Visible) { // menukAzhornuar ?>
        <th class="<?= $Page->menukAzhornuar->headerCellClass() ?>"><span id="elh_menu_kryesore_menukAzhornuar" class="menu_kryesore_menukAzhornuar"><?= $Page->menukAzhornuar->caption() ?></span></th>
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
<?php if ($Page->menukID->Visible) { // menukID ?>
        <td<?= $Page->menukID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukID" class="el_menu_kryesore_menukID">
<span<?= $Page->menukID->viewAttributes() ?>>
<?= $Page->menukID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukGjuha->Visible) { // menukGjuha ?>
        <td<?= $Page->menukGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukGjuha" class="el_menu_kryesore_menukGjuha">
<span<?= $Page->menukGjuha->viewAttributes() ?>>
<?= $Page->menukGjuha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukTitull->Visible) { // menukTitull ?>
        <td<?= $Page->menukTitull->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukTitull" class="el_menu_kryesore_menukTitull">
<span<?= $Page->menukTitull->viewAttributes() ?>>
<?= $Page->menukTitull->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukUrl->Visible) { // menukUrl ?>
        <td<?= $Page->menukUrl->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukUrl" class="el_menu_kryesore_menukUrl">
<span<?= $Page->menukUrl->viewAttributes() ?>>
<?= $Page->menukUrl->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukBlank->Visible) { // menukBlank ?>
        <td<?= $Page->menukBlank->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukBlank" class="el_menu_kryesore_menukBlank">
<span<?= $Page->menukBlank->viewAttributes() ?>>
<?= $Page->menukBlank->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukRadhe->Visible) { // menukRadhe ?>
        <td<?= $Page->menukRadhe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukRadhe" class="el_menu_kryesore_menukRadhe">
<span<?= $Page->menukRadhe->viewAttributes() ?>>
<?= $Page->menukRadhe->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukAktiv->Visible) { // menukAktiv ?>
        <td<?= $Page->menukAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukAktiv" class="el_menu_kryesore_menukAktiv">
<span<?= $Page->menukAktiv->viewAttributes() ?>>
<?= $Page->menukAktiv->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukAutor->Visible) { // menukAutor ?>
        <td<?= $Page->menukAutor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukAutor" class="el_menu_kryesore_menukAutor">
<span<?= $Page->menukAutor->viewAttributes() ?>>
<?= $Page->menukAutor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukKrijuar->Visible) { // menukKrijuar ?>
        <td<?= $Page->menukKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukKrijuar" class="el_menu_kryesore_menukKrijuar">
<span<?= $Page->menukKrijuar->viewAttributes() ?>>
<?= $Page->menukKrijuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menukAzhornuar->Visible) { // menukAzhornuar ?>
        <td<?= $Page->menukAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_kryesore_menukAzhornuar" class="el_menu_kryesore_menukAzhornuar">
<span<?= $Page->menukAzhornuar->viewAttributes() ?>>
<?= $Page->menukAzhornuar->getViewValue() ?></span>
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
