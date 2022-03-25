<?php

namespace PHPMaker2022\volalservice;

// Page object
$BlogKategoriView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { blog_kategori: currentTable } });
var currentForm, currentPageID;
var fblog_kategoriview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fblog_kategoriview = new ew.Form("fblog_kategoriview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fblog_kategoriview;
    loadjs.done("fblog_kategoriview");
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
<form name="fblog_kategoriview" id="fblog_kategoriview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog_kategori">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->blogKatID->Visible) { // blogKatID ?>
    <tr id="r_blogKatID"<?= $Page->blogKatID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_kategori_blogKatID"><?= $Page->blogKatID->caption() ?></span></td>
        <td data-name="blogKatID"<?= $Page->blogKatID->cellAttributes() ?>>
<span id="el_blog_kategori_blogKatID">
<span<?= $Page->blogKatID->viewAttributes() ?>>
<?= $Page->blogKatID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogKatGjuha->Visible) { // blogKatGjuha ?>
    <tr id="r_blogKatGjuha"<?= $Page->blogKatGjuha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_kategori_blogKatGjuha"><?= $Page->blogKatGjuha->caption() ?></span></td>
        <td data-name="blogKatGjuha"<?= $Page->blogKatGjuha->cellAttributes() ?>>
<span id="el_blog_kategori_blogKatGjuha">
<span<?= $Page->blogKatGjuha->viewAttributes() ?>>
<?= $Page->blogKatGjuha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogKatEmertimi->Visible) { // blogKatEmertimi ?>
    <tr id="r_blogKatEmertimi"<?= $Page->blogKatEmertimi->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_kategori_blogKatEmertimi"><?= $Page->blogKatEmertimi->caption() ?></span></td>
        <td data-name="blogKatEmertimi"<?= $Page->blogKatEmertimi->cellAttributes() ?>>
<span id="el_blog_kategori_blogKatEmertimi">
<span<?= $Page->blogKatEmertimi->viewAttributes() ?>>
<?= $Page->blogKatEmertimi->getViewValue() ?></span>
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
