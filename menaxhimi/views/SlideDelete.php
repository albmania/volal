<?php

namespace PHPMaker2022\volalservice;

// Page object
$SlideDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { slide: currentTable } });
var currentForm, currentPageID;
var fslidedelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fslidedelete = new ew.Form("fslidedelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fslidedelete;
    loadjs.done("fslidedelete");
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
<form name="fslidedelete" id="fslidedelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="slide">
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
<?php if ($Page->slideID->Visible) { // slideID ?>
        <th class="<?= $Page->slideID->headerCellClass() ?>"><span id="elh_slide_slideID" class="slide_slideID"><?= $Page->slideID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideGjuha->Visible) { // slideGjuha ?>
        <th class="<?= $Page->slideGjuha->headerCellClass() ?>"><span id="elh_slide_slideGjuha" class="slide_slideGjuha"><?= $Page->slideGjuha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideFoto->Visible) { // slideFoto ?>
        <th class="<?= $Page->slideFoto->headerCellClass() ?>"><span id="elh_slide_slideFoto" class="slide_slideFoto"><?= $Page->slideFoto->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideTxt1->Visible) { // slideTxt1 ?>
        <th class="<?= $Page->slideTxt1->headerCellClass() ?>"><span id="elh_slide_slideTxt1" class="slide_slideTxt1"><?= $Page->slideTxt1->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideTxt2->Visible) { // slideTxt2 ?>
        <th class="<?= $Page->slideTxt2->headerCellClass() ?>"><span id="elh_slide_slideTxt2" class="slide_slideTxt2"><?= $Page->slideTxt2->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideButonTxt->Visible) { // slideButonTxt ?>
        <th class="<?= $Page->slideButonTxt->headerCellClass() ?>"><span id="elh_slide_slideButonTxt" class="slide_slideButonTxt"><?= $Page->slideButonTxt->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideRadha->Visible) { // slideRadha ?>
        <th class="<?= $Page->slideRadha->headerCellClass() ?>"><span id="elh_slide_slideRadha" class="slide_slideRadha"><?= $Page->slideRadha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideAktiv->Visible) { // slideAktiv ?>
        <th class="<?= $Page->slideAktiv->headerCellClass() ?>"><span id="elh_slide_slideAktiv" class="slide_slideAktiv"><?= $Page->slideAktiv->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideAutori->Visible) { // slideAutori ?>
        <th class="<?= $Page->slideAutori->headerCellClass() ?>"><span id="elh_slide_slideAutori" class="slide_slideAutori"><?= $Page->slideAutori->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideKrijuar->Visible) { // slideKrijuar ?>
        <th class="<?= $Page->slideKrijuar->headerCellClass() ?>"><span id="elh_slide_slideKrijuar" class="slide_slideKrijuar"><?= $Page->slideKrijuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->slideAzhornuar->Visible) { // slideAzhornuar ?>
        <th class="<?= $Page->slideAzhornuar->headerCellClass() ?>"><span id="elh_slide_slideAzhornuar" class="slide_slideAzhornuar"><?= $Page->slideAzhornuar->caption() ?></span></th>
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
<?php if ($Page->slideID->Visible) { // slideID ?>
        <td<?= $Page->slideID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideID" class="el_slide_slideID">
<span<?= $Page->slideID->viewAttributes() ?>>
<?= $Page->slideID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideGjuha->Visible) { // slideGjuha ?>
        <td<?= $Page->slideGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideGjuha" class="el_slide_slideGjuha">
<span<?= $Page->slideGjuha->viewAttributes() ?>>
<?= $Page->slideGjuha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideFoto->Visible) { // slideFoto ?>
        <td<?= $Page->slideFoto->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideFoto" class="el_slide_slideFoto">
<span>
<?= GetFileViewTag($Page->slideFoto, $Page->slideFoto->getViewValue(), false) ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideTxt1->Visible) { // slideTxt1 ?>
        <td<?= $Page->slideTxt1->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideTxt1" class="el_slide_slideTxt1">
<span<?= $Page->slideTxt1->viewAttributes() ?>>
<?= $Page->slideTxt1->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideTxt2->Visible) { // slideTxt2 ?>
        <td<?= $Page->slideTxt2->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideTxt2" class="el_slide_slideTxt2">
<span<?= $Page->slideTxt2->viewAttributes() ?>>
<?= $Page->slideTxt2->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideButonTxt->Visible) { // slideButonTxt ?>
        <td<?= $Page->slideButonTxt->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideButonTxt" class="el_slide_slideButonTxt">
<span<?= $Page->slideButonTxt->viewAttributes() ?>>
<?php if (!EmptyString($Page->slideButonTxt->getViewValue()) && $Page->slideButonTxt->linkAttributes() != "") { ?>
<a<?= $Page->slideButonTxt->linkAttributes() ?>><?= $Page->slideButonTxt->getViewValue() ?></a>
<?php } else { ?>
<?= $Page->slideButonTxt->getViewValue() ?>
<?php } ?>
</span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideRadha->Visible) { // slideRadha ?>
        <td<?= $Page->slideRadha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideRadha" class="el_slide_slideRadha">
<span<?= $Page->slideRadha->viewAttributes() ?>>
<?= $Page->slideRadha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideAktiv->Visible) { // slideAktiv ?>
        <td<?= $Page->slideAktiv->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideAktiv" class="el_slide_slideAktiv">
<span<?= $Page->slideAktiv->viewAttributes() ?>>
<?= $Page->slideAktiv->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideAutori->Visible) { // slideAutori ?>
        <td<?= $Page->slideAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideAutori" class="el_slide_slideAutori">
<span<?= $Page->slideAutori->viewAttributes() ?>>
<?= $Page->slideAutori->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideKrijuar->Visible) { // slideKrijuar ?>
        <td<?= $Page->slideKrijuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideKrijuar" class="el_slide_slideKrijuar">
<span<?= $Page->slideKrijuar->viewAttributes() ?>>
<?= $Page->slideKrijuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->slideAzhornuar->Visible) { // slideAzhornuar ?>
        <td<?= $Page->slideAzhornuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_slide_slideAzhornuar" class="el_slide_slideAzhornuar">
<span<?= $Page->slideAzhornuar->viewAttributes() ?>>
<?= $Page->slideAzhornuar->getViewValue() ?></span>
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
