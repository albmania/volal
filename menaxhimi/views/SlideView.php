<?php

namespace PHPMaker2022\volalservice;

// Page object
$SlideView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { slide: currentTable } });
var currentForm, currentPageID;
var fslideview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fslideview = new ew.Form("fslideview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fslideview;
    loadjs.done("fslideview");
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
<form name="fslideview" id="fslideview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="slide">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->slideID->Visible) { // slideID ?>
    <tr id="r_slideID"<?= $Page->slideID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideID"><?= $Page->slideID->caption() ?></span></td>
        <td data-name="slideID"<?= $Page->slideID->cellAttributes() ?>>
<span id="el_slide_slideID">
<span<?= $Page->slideID->viewAttributes() ?>>
<?= $Page->slideID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideGjuha->Visible) { // slideGjuha ?>
    <tr id="r_slideGjuha"<?= $Page->slideGjuha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideGjuha"><?= $Page->slideGjuha->caption() ?></span></td>
        <td data-name="slideGjuha"<?= $Page->slideGjuha->cellAttributes() ?>>
<span id="el_slide_slideGjuha">
<span<?= $Page->slideGjuha->viewAttributes() ?>>
<?= $Page->slideGjuha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideFoto->Visible) { // slideFoto ?>
    <tr id="r_slideFoto"<?= $Page->slideFoto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideFoto"><?= $Page->slideFoto->caption() ?></span></td>
        <td data-name="slideFoto"<?= $Page->slideFoto->cellAttributes() ?>>
<span id="el_slide_slideFoto">
<span>
<?= GetFileViewTag($Page->slideFoto, $Page->slideFoto->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideTxt1->Visible) { // slideTxt1 ?>
    <tr id="r_slideTxt1"<?= $Page->slideTxt1->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideTxt1"><?= $Page->slideTxt1->caption() ?></span></td>
        <td data-name="slideTxt1"<?= $Page->slideTxt1->cellAttributes() ?>>
<span id="el_slide_slideTxt1">
<span<?= $Page->slideTxt1->viewAttributes() ?>>
<?= $Page->slideTxt1->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideTxt2->Visible) { // slideTxt2 ?>
    <tr id="r_slideTxt2"<?= $Page->slideTxt2->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideTxt2"><?= $Page->slideTxt2->caption() ?></span></td>
        <td data-name="slideTxt2"<?= $Page->slideTxt2->cellAttributes() ?>>
<span id="el_slide_slideTxt2">
<span<?= $Page->slideTxt2->viewAttributes() ?>>
<?= $Page->slideTxt2->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideTxt3->Visible) { // slideTxt3 ?>
    <tr id="r_slideTxt3"<?= $Page->slideTxt3->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideTxt3"><?= $Page->slideTxt3->caption() ?></span></td>
        <td data-name="slideTxt3"<?= $Page->slideTxt3->cellAttributes() ?>>
<span id="el_slide_slideTxt3">
<span<?= $Page->slideTxt3->viewAttributes() ?>>
<?= $Page->slideTxt3->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideButonTxt->Visible) { // slideButonTxt ?>
    <tr id="r_slideButonTxt"<?= $Page->slideButonTxt->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideButonTxt"><?= $Page->slideButonTxt->caption() ?></span></td>
        <td data-name="slideButonTxt"<?= $Page->slideButonTxt->cellAttributes() ?>>
<span id="el_slide_slideButonTxt">
<span<?= $Page->slideButonTxt->viewAttributes() ?>>
<?php if (!EmptyString($Page->slideButonTxt->getViewValue()) && $Page->slideButonTxt->linkAttributes() != "") { ?>
<a<?= $Page->slideButonTxt->linkAttributes() ?>><?= $Page->slideButonTxt->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->slideButonTxt->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideLink->Visible) { // slideLink ?>
    <tr id="r_slideLink"<?= $Page->slideLink->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideLink"><?= $Page->slideLink->caption() ?></span></td>
        <td data-name="slideLink"<?= $Page->slideLink->cellAttributes() ?>>
<span id="el_slide_slideLink">
<span<?= $Page->slideLink->viewAttributes() ?>>
<?= $Page->slideLink->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideTarget->Visible) { // slideTarget ?>
    <tr id="r_slideTarget"<?= $Page->slideTarget->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideTarget"><?= $Page->slideTarget->caption() ?></span></td>
        <td data-name="slideTarget"<?= $Page->slideTarget->cellAttributes() ?>>
<span id="el_slide_slideTarget">
<span<?= $Page->slideTarget->viewAttributes() ?>>
<?= $Page->slideTarget->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideRadha->Visible) { // slideRadha ?>
    <tr id="r_slideRadha"<?= $Page->slideRadha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideRadha"><?= $Page->slideRadha->caption() ?></span></td>
        <td data-name="slideRadha"<?= $Page->slideRadha->cellAttributes() ?>>
<span id="el_slide_slideRadha">
<span<?= $Page->slideRadha->viewAttributes() ?>>
<?= $Page->slideRadha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideAktiv->Visible) { // slideAktiv ?>
    <tr id="r_slideAktiv"<?= $Page->slideAktiv->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideAktiv"><?= $Page->slideAktiv->caption() ?></span></td>
        <td data-name="slideAktiv"<?= $Page->slideAktiv->cellAttributes() ?>>
<span id="el_slide_slideAktiv">
<span<?= $Page->slideAktiv->viewAttributes() ?>>
<?= $Page->slideAktiv->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideAutori->Visible) { // slideAutori ?>
    <tr id="r_slideAutori"<?= $Page->slideAutori->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideAutori"><?= $Page->slideAutori->caption() ?></span></td>
        <td data-name="slideAutori"<?= $Page->slideAutori->cellAttributes() ?>>
<span id="el_slide_slideAutori">
<span<?= $Page->slideAutori->viewAttributes() ?>>
<?= $Page->slideAutori->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideKrijuar->Visible) { // slideKrijuar ?>
    <tr id="r_slideKrijuar"<?= $Page->slideKrijuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideKrijuar"><?= $Page->slideKrijuar->caption() ?></span></td>
        <td data-name="slideKrijuar"<?= $Page->slideKrijuar->cellAttributes() ?>>
<span id="el_slide_slideKrijuar">
<span<?= $Page->slideKrijuar->viewAttributes() ?>>
<?= $Page->slideKrijuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->slideAzhornuar->Visible) { // slideAzhornuar ?>
    <tr id="r_slideAzhornuar"<?= $Page->slideAzhornuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_slide_slideAzhornuar"><?= $Page->slideAzhornuar->caption() ?></span></td>
        <td data-name="slideAzhornuar"<?= $Page->slideAzhornuar->cellAttributes() ?>>
<span id="el_slide_slideAzhornuar">
<span<?= $Page->slideAzhornuar->viewAttributes() ?>>
<?= $Page->slideAzhornuar->getViewValue() ?></span>
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
