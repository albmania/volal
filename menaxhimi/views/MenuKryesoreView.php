<?php

namespace PHPMaker2022\volalservice;

// Page object
$MenuKryesoreView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { menu_kryesore: currentTable } });
var currentForm, currentPageID;
var fmenu_kryesoreview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmenu_kryesoreview = new ew.Form("fmenu_kryesoreview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fmenu_kryesoreview;
    loadjs.done("fmenu_kryesoreview");
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
<form name="fmenu_kryesoreview" id="fmenu_kryesoreview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="menu_kryesore">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->menukID->Visible) { // menukID ?>
    <tr id="r_menukID"<?= $Page->menukID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukID"><?= $Page->menukID->caption() ?></span></td>
        <td data-name="menukID"<?= $Page->menukID->cellAttributes() ?>>
<span id="el_menu_kryesore_menukID">
<span<?= $Page->menukID->viewAttributes() ?>>
<?= $Page->menukID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukGjuha->Visible) { // menukGjuha ?>
    <tr id="r_menukGjuha"<?= $Page->menukGjuha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukGjuha"><?= $Page->menukGjuha->caption() ?></span></td>
        <td data-name="menukGjuha"<?= $Page->menukGjuha->cellAttributes() ?>>
<span id="el_menu_kryesore_menukGjuha">
<span<?= $Page->menukGjuha->viewAttributes() ?>>
<?= $Page->menukGjuha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukTitull->Visible) { // menukTitull ?>
    <tr id="r_menukTitull"<?= $Page->menukTitull->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukTitull"><?= $Page->menukTitull->caption() ?></span></td>
        <td data-name="menukTitull"<?= $Page->menukTitull->cellAttributes() ?>>
<span id="el_menu_kryesore_menukTitull">
<span<?= $Page->menukTitull->viewAttributes() ?>>
<?= $Page->menukTitull->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukUrl->Visible) { // menukUrl ?>
    <tr id="r_menukUrl"<?= $Page->menukUrl->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukUrl"><?= $Page->menukUrl->caption() ?></span></td>
        <td data-name="menukUrl"<?= $Page->menukUrl->cellAttributes() ?>>
<span id="el_menu_kryesore_menukUrl">
<span<?= $Page->menukUrl->viewAttributes() ?>>
<?= $Page->menukUrl->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukBlank->Visible) { // menukBlank ?>
    <tr id="r_menukBlank"<?= $Page->menukBlank->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukBlank"><?= $Page->menukBlank->caption() ?></span></td>
        <td data-name="menukBlank"<?= $Page->menukBlank->cellAttributes() ?>>
<span id="el_menu_kryesore_menukBlank">
<span<?= $Page->menukBlank->viewAttributes() ?>>
<?= $Page->menukBlank->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukRadhe->Visible) { // menukRadhe ?>
    <tr id="r_menukRadhe"<?= $Page->menukRadhe->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukRadhe"><?= $Page->menukRadhe->caption() ?></span></td>
        <td data-name="menukRadhe"<?= $Page->menukRadhe->cellAttributes() ?>>
<span id="el_menu_kryesore_menukRadhe">
<span<?= $Page->menukRadhe->viewAttributes() ?>>
<?= $Page->menukRadhe->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukAktiv->Visible) { // menukAktiv ?>
    <tr id="r_menukAktiv"<?= $Page->menukAktiv->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukAktiv"><?= $Page->menukAktiv->caption() ?></span></td>
        <td data-name="menukAktiv"<?= $Page->menukAktiv->cellAttributes() ?>>
<span id="el_menu_kryesore_menukAktiv">
<span<?= $Page->menukAktiv->viewAttributes() ?>>
<?= $Page->menukAktiv->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukAutor->Visible) { // menukAutor ?>
    <tr id="r_menukAutor"<?= $Page->menukAutor->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukAutor"><?= $Page->menukAutor->caption() ?></span></td>
        <td data-name="menukAutor"<?= $Page->menukAutor->cellAttributes() ?>>
<span id="el_menu_kryesore_menukAutor">
<span<?= $Page->menukAutor->viewAttributes() ?>>
<?= $Page->menukAutor->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukKrijuar->Visible) { // menukKrijuar ?>
    <tr id="r_menukKrijuar"<?= $Page->menukKrijuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukKrijuar"><?= $Page->menukKrijuar->caption() ?></span></td>
        <td data-name="menukKrijuar"<?= $Page->menukKrijuar->cellAttributes() ?>>
<span id="el_menu_kryesore_menukKrijuar">
<span<?= $Page->menukKrijuar->viewAttributes() ?>>
<?= $Page->menukKrijuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->menukAzhornuar->Visible) { // menukAzhornuar ?>
    <tr id="r_menukAzhornuar"<?= $Page->menukAzhornuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_menu_kryesore_menukAzhornuar"><?= $Page->menukAzhornuar->caption() ?></span></td>
        <td data-name="menukAzhornuar"<?= $Page->menukAzhornuar->cellAttributes() ?>>
<span id="el_menu_kryesore_menukAzhornuar">
<span<?= $Page->menukAzhornuar->viewAttributes() ?>>
<?= $Page->menukAzhornuar->getViewValue() ?></span>
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
