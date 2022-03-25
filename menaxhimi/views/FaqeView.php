<?php

namespace PHPMaker2022\volalservice;

// Page object
$FaqeView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { faqe: currentTable } });
var currentForm, currentPageID;
var ffaqeview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    ffaqeview = new ew.Form("ffaqeview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = ffaqeview;
    loadjs.done("ffaqeview");
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
<form name="ffaqeview" id="ffaqeview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="faqe">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->faqeID->Visible) { // faqeID ?>
    <tr id="r_faqeID"<?= $Page->faqeID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeID"><?= $Page->faqeID->caption() ?></span></td>
        <td data-name="faqeID"<?= $Page->faqeID->cellAttributes() ?>>
<span id="el_faqe_faqeID">
<span<?= $Page->faqeID->viewAttributes() ?>>
<?= $Page->faqeID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->faqeEmri_sq->Visible) { // faqeEmri_sq ?>
    <tr id="r_faqeEmri_sq"<?= $Page->faqeEmri_sq->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeEmri_sq"><?= $Page->faqeEmri_sq->caption() ?></span></td>
        <td data-name="faqeEmri_sq"<?= $Page->faqeEmri_sq->cellAttributes() ?>>
<span id="el_faqe_faqeEmri_sq">
<span<?= $Page->faqeEmri_sq->viewAttributes() ?>>
<?= $Page->faqeEmri_sq->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->faqeTxt_sq->Visible) { // faqeTxt_sq ?>
    <tr id="r_faqeTxt_sq"<?= $Page->faqeTxt_sq->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeTxt_sq"><?= $Page->faqeTxt_sq->caption() ?></span></td>
        <td data-name="faqeTxt_sq"<?= $Page->faqeTxt_sq->cellAttributes() ?>>
<span id="el_faqe_faqeTxt_sq">
<span<?= $Page->faqeTxt_sq->viewAttributes() ?>>
<?= $Page->faqeTxt_sq->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->faqeEmri_en->Visible) { // faqeEmri_en ?>
    <tr id="r_faqeEmri_en"<?= $Page->faqeEmri_en->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeEmri_en"><?= $Page->faqeEmri_en->caption() ?></span></td>
        <td data-name="faqeEmri_en"<?= $Page->faqeEmri_en->cellAttributes() ?>>
<span id="el_faqe_faqeEmri_en">
<span<?= $Page->faqeEmri_en->viewAttributes() ?>>
<?= $Page->faqeEmri_en->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->faqeTxt_en->Visible) { // faqeTxt_en ?>
    <tr id="r_faqeTxt_en"<?= $Page->faqeTxt_en->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeTxt_en"><?= $Page->faqeTxt_en->caption() ?></span></td>
        <td data-name="faqeTxt_en"<?= $Page->faqeTxt_en->cellAttributes() ?>>
<span id="el_faqe_faqeTxt_en">
<span<?= $Page->faqeTxt_en->viewAttributes() ?>>
<?= $Page->faqeTxt_en->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->faqeFoto->Visible) { // faqeFoto ?>
    <tr id="r_faqeFoto"<?= $Page->faqeFoto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeFoto"><?= $Page->faqeFoto->caption() ?></span></td>
        <td data-name="faqeFoto"<?= $Page->faqeFoto->cellAttributes() ?>>
<span id="el_faqe_faqeFoto">
<span<?= $Page->faqeFoto->viewAttributes() ?>>
<?= GetFileViewTag($Page->faqeFoto, $Page->faqeFoto->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->faqeAutori->Visible) { // faqeAutori ?>
    <tr id="r_faqeAutori"<?= $Page->faqeAutori->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeAutori"><?= $Page->faqeAutori->caption() ?></span></td>
        <td data-name="faqeAutori"<?= $Page->faqeAutori->cellAttributes() ?>>
<span id="el_faqe_faqeAutori">
<span<?= $Page->faqeAutori->viewAttributes() ?>>
<?= $Page->faqeAutori->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->faqeKrijuar->Visible) { // faqeKrijuar ?>
    <tr id="r_faqeKrijuar"<?= $Page->faqeKrijuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeKrijuar"><?= $Page->faqeKrijuar->caption() ?></span></td>
        <td data-name="faqeKrijuar"<?= $Page->faqeKrijuar->cellAttributes() ?>>
<span id="el_faqe_faqeKrijuar">
<span<?= $Page->faqeKrijuar->viewAttributes() ?>>
<?= $Page->faqeKrijuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->faqeAzhornuar->Visible) { // faqeAzhornuar ?>
    <tr id="r_faqeAzhornuar"<?= $Page->faqeAzhornuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_faqe_faqeAzhornuar"><?= $Page->faqeAzhornuar->caption() ?></span></td>
        <td data-name="faqeAzhornuar"<?= $Page->faqeAzhornuar->cellAttributes() ?>>
<span id="el_faqe_faqeAzhornuar">
<span<?= $Page->faqeAzhornuar->viewAttributes() ?>>
<?= $Page->faqeAzhornuar->getViewValue() ?></span>
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
