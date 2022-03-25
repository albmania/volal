<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuDytesoreDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_dytesore: currentTable } });
var currentForm, currentPageID;
var fmenu_dytesoredelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_dytesoredelete = new ew.Form("fmenu_dytesoredelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fmenu_dytesoredelete;
    loadjs.done("fmenu_dytesoredelete");
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
<form name="fmenu_dytesoredelete" id="fmenu_dytesoredelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="menu_dytesore">
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
<?php if ($Page->menudID->Visible) { // menudID ?>
        <th class="<?= $Page->menudID->headerCellClass() ?>"><span id="elh_menu_dytesore_menudID" class="menu_dytesore_menudID"><?= $Page->menudID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudGjuha->Visible) { // menudGjuha ?>
        <th class="<?= $Page->menudGjuha->headerCellClass() ?>"><span id="elh_menu_dytesore_menudGjuha" class="menu_dytesore_menudGjuha"><?= $Page->menudGjuha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudKryesore->Visible) { // menudKryesore ?>
        <th class="<?= $Page->menudKryesore->headerCellClass() ?>"><span id="elh_menu_dytesore_menudKryesore" class="menu_dytesore_menudKryesore"><?= $Page->menudKryesore->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudTitulli->Visible) { // menudTitulli ?>
        <th class="<?= $Page->menudTitulli->headerCellClass() ?>"><span id="elh_menu_dytesore_menudTitulli" class="menu_dytesore_menudTitulli"><?= $Page->menudTitulli->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudUrl->Visible) { // menudUrl ?>
        <th class="<?= $Page->menudUrl->headerCellClass() ?>"><span id="elh_menu_dytesore_menudUrl" class="menu_dytesore_menudUrl"><?= $Page->menudUrl->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudBlank->Visible) { // menudBlank ?>
        <th class="<?= $Page->menudBlank->headerCellClass() ?>"><span id="elh_menu_dytesore_menudBlank" class="menu_dytesore_menudBlank"><?= $Page->menudBlank->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudRadhe->Visible) { // menudRadhe ?>
        <th class="<?= $Page->menudRadhe->headerCellClass() ?>"><span id="elh_menu_dytesore_menudRadhe" class="menu_dytesore_menudRadhe"><?= $Page->menudRadhe->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudAktiv->Visible) { // menudAktiv ?>
        <th class="<?= $Page->menudAktiv->headerCellClass() ?>"><span id="elh_menu_dytesore_menudAktiv" class="menu_dytesore_menudAktiv"><?= $Page->menudAktiv->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudAutor->Visible) { // menudAutor ?>
        <th class="<?= $Page->menudAutor->headerCellClass() ?>"><span id="elh_menu_dytesore_menudAutor" class="menu_dytesore_menudAutor"><?= $Page->menudAutor->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudKrijuar->Visible) { // menudKrijuar ?>
        <th class="<?= $Page->menudKrijuar->headerCellClass() ?>"><span id="elh_menu_dytesore_menudKrijuar" class="menu_dytesore_menudKrijuar"><?= $Page->menudKrijuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->menudAzhornuar->Visible) { // menudAzhornuar ?>
        <th class="<?= $Page->menudAzhornuar->headerCellClass() ?>"><span id="elh_menu_dytesore_menudAzhornuar" class="menu_dytesore_menudAzhornuar"><?= $Page->menudAzhornuar->caption() ?></span></th>
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
<?php if ($Page->menudID->Visible) { // menudID ?>
        <td<?= $Page->menudID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudID" class="el_menu_dytesore_menudID">
<span<?= $Page->menudID->viewAttributes() ?>>
<?= $Page->menudID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudGjuha->Visible) { // menudGjuha ?>
        <td<?= $Page->menudGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudGjuha" class="el_menu_dytesore_menudGjuha">
<span<?= $Page->menudGjuha->viewAttributes() ?>>
<?= $Page->menudGjuha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudKryesore->Visible) { // menudKryesore ?>
        <td<?= $Page->menudKryesore->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudKryesore" class="el_menu_dytesore_menudKryesore">
<span<?= $Page->menudKryesore->viewAttributes() ?>>
<?= $Page->menudKryesore->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudTitulli->Visible) { // menudTitulli ?>
        <td<?= $Page->menudTitulli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudTitulli" class="el_menu_dytesore_menudTitulli">
<span<?= $Page->menudTitulli->viewAttributes() ?>>
<?= $Page->menudTitulli->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudUrl->Visible) { // menudUrl ?>
        <td<?= $Page->menudUrl->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudUrl" class="el_menu_dytesore_menudUrl">
<span<?= $Page->menudUrl->viewAttributes() ?>>
<?= $Page->menudUrl->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudBlank->Visible) { // menudBlank ?>
        <td<?= $Page->menudBlank->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudBlank" class="el_menu_dytesore_menudBlank">
<span<?= $Page->menudBlank->viewAttributes() ?>>
<?= $Page->menudBlank->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudRadhe->Visible) { // menudRadhe ?>
        <td<?= $Page->menudRadhe->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudRadhe" class="el_menu_dytesore_menudRadhe">
<span<?= $Page->menudRadhe->viewAttributes() ?>>
<?= $Page->menudRadhe->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudAktiv->Visible) { // menudAktiv ?>
        <td<?= $Page->menudAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudAktiv" class="el_menu_dytesore_menudAktiv">
<span<?= $Page->menudAktiv->viewAttributes() ?>>
<?= $Page->menudAktiv->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudAutor->Visible) { // menudAutor ?>
        <td<?= $Page->menudAutor->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudAutor" class="el_menu_dytesore_menudAutor">
<span<?= $Page->menudAutor->viewAttributes() ?>>
<?= $Page->menudAutor->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudKrijuar->Visible) { // menudKrijuar ?>
        <td<?= $Page->menudKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudKrijuar" class="el_menu_dytesore_menudKrijuar">
<span<?= $Page->menudKrijuar->viewAttributes() ?>>
<?= $Page->menudKrijuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->menudAzhornuar->Visible) { // menudAzhornuar ?>
        <td<?= $Page->menudAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_menu_dytesore_menudAzhornuar" class="el_menu_dytesore_menudAzhornuar">
<span<?= $Page->menudAzhornuar->viewAttributes() ?>>
<?= $Page->menudAzhornuar->getViewValue() ?></span>
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
