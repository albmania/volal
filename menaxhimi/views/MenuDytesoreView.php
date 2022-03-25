<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuDytesoreView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_dytesore: currentTable } });
var currentForm, currentPageID;
var fmenu_dytesoreview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_dytesoreview = new ew.Form("fmenu_dytesoreview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmenu_dytesoreview;
    loadjs.done("fmenu_dytesoreview");
});
</script>
<script>
loadjs.ready("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<form name="fmenu_dytesoreview" id="fmenu_dytesoreview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="menu_dytesore">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->menudID->Visible) { // menudID ?>
    <tr id="r_menudID"<?= $Page->menudID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudID"><?= $Page->menudID->caption() ?></span></td>
        <td data-name="menudID"<?= $Page->menudID->cellAttributes() ?>>
<span id="el_menu_dytesore_menudID">
<span<?= $Page->menudID->viewAttributes() ?>>
<?= $Page->menudID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudGjuha->Visible) { // menudGjuha ?>
    <tr id="r_menudGjuha"<?= $Page->menudGjuha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudGjuha"><?= $Page->menudGjuha->caption() ?></span></td>
        <td data-name="menudGjuha"<?= $Page->menudGjuha->cellAttributes() ?>>
<span id="el_menu_dytesore_menudGjuha">
<span<?= $Page->menudGjuha->viewAttributes() ?>>
<?= $Page->menudGjuha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudKryesore->Visible) { // menudKryesore ?>
    <tr id="r_menudKryesore"<?= $Page->menudKryesore->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudKryesore"><?= $Page->menudKryesore->caption() ?></span></td>
        <td data-name="menudKryesore"<?= $Page->menudKryesore->cellAttributes() ?>>
<span id="el_menu_dytesore_menudKryesore">
<span<?= $Page->menudKryesore->viewAttributes() ?>>
<?= $Page->menudKryesore->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudTitulli->Visible) { // menudTitulli ?>
    <tr id="r_menudTitulli"<?= $Page->menudTitulli->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudTitulli"><?= $Page->menudTitulli->caption() ?></span></td>
        <td data-name="menudTitulli"<?= $Page->menudTitulli->cellAttributes() ?>>
<span id="el_menu_dytesore_menudTitulli">
<span<?= $Page->menudTitulli->viewAttributes() ?>>
<?= $Page->menudTitulli->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudUrl->Visible) { // menudUrl ?>
    <tr id="r_menudUrl"<?= $Page->menudUrl->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudUrl"><?= $Page->menudUrl->caption() ?></span></td>
        <td data-name="menudUrl"<?= $Page->menudUrl->cellAttributes() ?>>
<span id="el_menu_dytesore_menudUrl">
<span<?= $Page->menudUrl->viewAttributes() ?>>
<?= $Page->menudUrl->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudBlank->Visible) { // menudBlank ?>
    <tr id="r_menudBlank"<?= $Page->menudBlank->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudBlank"><?= $Page->menudBlank->caption() ?></span></td>
        <td data-name="menudBlank"<?= $Page->menudBlank->cellAttributes() ?>>
<span id="el_menu_dytesore_menudBlank">
<span<?= $Page->menudBlank->viewAttributes() ?>>
<?= $Page->menudBlank->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudRadhe->Visible) { // menudRadhe ?>
    <tr id="r_menudRadhe"<?= $Page->menudRadhe->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudRadhe"><?= $Page->menudRadhe->caption() ?></span></td>
        <td data-name="menudRadhe"<?= $Page->menudRadhe->cellAttributes() ?>>
<span id="el_menu_dytesore_menudRadhe">
<span<?= $Page->menudRadhe->viewAttributes() ?>>
<?= $Page->menudRadhe->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudAktiv->Visible) { // menudAktiv ?>
    <tr id="r_menudAktiv"<?= $Page->menudAktiv->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudAktiv"><?= $Page->menudAktiv->caption() ?></span></td>
        <td data-name="menudAktiv"<?= $Page->menudAktiv->cellAttributes() ?>>
<span id="el_menu_dytesore_menudAktiv">
<span<?= $Page->menudAktiv->viewAttributes() ?>>
<?= $Page->menudAktiv->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudAutor->Visible) { // menudAutor ?>
    <tr id="r_menudAutor"<?= $Page->menudAutor->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudAutor"><?= $Page->menudAutor->caption() ?></span></td>
        <td data-name="menudAutor"<?= $Page->menudAutor->cellAttributes() ?>>
<span id="el_menu_dytesore_menudAutor">
<span<?= $Page->menudAutor->viewAttributes() ?>>
<?= $Page->menudAutor->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudKrijuar->Visible) { // menudKrijuar ?>
    <tr id="r_menudKrijuar"<?= $Page->menudKrijuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudKrijuar"><?= $Page->menudKrijuar->caption() ?></span></td>
        <td data-name="menudKrijuar"<?= $Page->menudKrijuar->cellAttributes() ?>>
<span id="el_menu_dytesore_menudKrijuar">
<span<?= $Page->menudKrijuar->viewAttributes() ?>>
<?= $Page->menudKrijuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menudAzhornuar->Visible) { // menudAzhornuar ?>
    <tr id="r_menudAzhornuar"<?= $Page->menudAzhornuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_dytesore_menudAzhornuar"><?= $Page->menudAzhornuar->caption() ?></span></td>
        <td data-name="menudAzhornuar"<?= $Page->menudAzhornuar->cellAttributes() ?>>
<span id="el_menu_dytesore_menudAzhornuar">
<span<?= $Page->menudAzhornuar->viewAttributes() ?>>
<?= $Page->menudAzhornuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
