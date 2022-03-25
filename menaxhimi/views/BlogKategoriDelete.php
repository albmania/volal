<?php

namespace PHPMaker2022\volalservice;

// Page object
$BlogKategoriDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { blog_kategori: currentTable } });
var currentForm, currentPageID;
var fblog_kategoridelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fblog_kategoridelete = new ew.Form("fblog_kategoridelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fblog_kategoridelete;
    loadjs.done("fblog_kategoridelete");
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
<form name="fblog_kategoridelete" id="fblog_kategoridelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog_kategori">
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
<?php if ($Page->blogKatID->Visible) { // blogKatID ?>
        <th class="<?= $Page->blogKatID->headerCellClass() ?>"><span id="elh_blog_kategori_blogKatID" class="blog_kategori_blogKatID"><?= $Page->blogKatID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogKatGjuha->Visible) { // blogKatGjuha ?>
        <th class="<?= $Page->blogKatGjuha->headerCellClass() ?>"><span id="elh_blog_kategori_blogKatGjuha" class="blog_kategori_blogKatGjuha"><?= $Page->blogKatGjuha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogKatEmertimi->Visible) { // blogKatEmertimi ?>
        <th class="<?= $Page->blogKatEmertimi->headerCellClass() ?>"><span id="elh_blog_kategori_blogKatEmertimi" class="blog_kategori_blogKatEmertimi"><?= $Page->blogKatEmertimi->caption() ?></span></th>
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
<?php if ($Page->blogKatID->Visible) { // blogKatID ?>
        <td<?= $Page->blogKatID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_kategori_blogKatID" class="el_blog_kategori_blogKatID">
<span<?= $Page->blogKatID->viewAttributes() ?>>
<?= $Page->blogKatID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogKatGjuha->Visible) { // blogKatGjuha ?>
        <td<?= $Page->blogKatGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_kategori_blogKatGjuha" class="el_blog_kategori_blogKatGjuha">
<span<?= $Page->blogKatGjuha->viewAttributes() ?>>
<?= $Page->blogKatGjuha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogKatEmertimi->Visible) { // blogKatEmertimi ?>
        <td<?= $Page->blogKatEmertimi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_kategori_blogKatEmertimi" class="el_blog_kategori_blogKatEmertimi">
<span<?= $Page->blogKatEmertimi->viewAttributes() ?>>
<?= $Page->blogKatEmertimi->getViewValue() ?></span>
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
