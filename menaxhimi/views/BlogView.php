<?php

namespace PHPMaker2022\volalservice;

// Page object
$BlogView = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { blog: currentTable } });
var currentForm, currentPageID;
var fblogview;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fblogview = new ew.Form("fblogview", "view");
    currentPageID = ew.PAGE_ID = "view";
    currentForm = fblogview;
    loadjs.done("fblogview");
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
<form name="fblogview" id="fblogview" class="ew-form ew-view-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="table table-striped table-sm ew-view-table">
<?php if ($Page->blogID->Visible) { // blogID ?>
    <tr id="r_blogID"<?= $Page->blogID->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogID"><?= $Page->blogID->caption() ?></span></td>
        <td data-name="blogID"<?= $Page->blogID->cellAttributes() ?>>
<span id="el_blog_blogID">
<span<?= $Page->blogID->viewAttributes() ?>>
<?= $Page->blogID->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogGjuha->Visible) { // blogGjuha ?>
    <tr id="r_blogGjuha"<?= $Page->blogGjuha->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogGjuha"><?= $Page->blogGjuha->caption() ?></span></td>
        <td data-name="blogGjuha"<?= $Page->blogGjuha->cellAttributes() ?>>
<span id="el_blog_blogGjuha">
<span<?= $Page->blogGjuha->viewAttributes() ?>>
<?= $Page->blogGjuha->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogKategoria->Visible) { // blogKategoria ?>
    <tr id="r_blogKategoria"<?= $Page->blogKategoria->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogKategoria"><?= $Page->blogKategoria->caption() ?></span></td>
        <td data-name="blogKategoria"<?= $Page->blogKategoria->cellAttributes() ?>>
<span id="el_blog_blogKategoria">
<span<?= $Page->blogKategoria->viewAttributes() ?>>
<?= $Page->blogKategoria->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogTitulli->Visible) { // blogTitulli ?>
    <tr id="r_blogTitulli"<?= $Page->blogTitulli->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogTitulli"><?= $Page->blogTitulli->caption() ?></span></td>
        <td data-name="blogTitulli"<?= $Page->blogTitulli->cellAttributes() ?>>
<span id="el_blog_blogTitulli">
<span<?= $Page->blogTitulli->viewAttributes() ?>>
<?= $Page->blogTitulli->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogTxt->Visible) { // blogTxt ?>
    <tr id="r_blogTxt"<?= $Page->blogTxt->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogTxt"><?= $Page->blogTxt->caption() ?></span></td>
        <td data-name="blogTxt"<?= $Page->blogTxt->cellAttributes() ?>>
<span id="el_blog_blogTxt">
<span<?= $Page->blogTxt->viewAttributes() ?>>
<?= $Page->blogTxt->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogFoto->Visible) { // blogFoto ?>
    <tr id="r_blogFoto"<?= $Page->blogFoto->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogFoto"><?= $Page->blogFoto->caption() ?></span></td>
        <td data-name="blogFoto"<?= $Page->blogFoto->cellAttributes() ?>>
<span id="el_blog_blogFoto">
<span>
<?= GetFileViewTag($Page->blogFoto, $Page->blogFoto->getViewValue(), false) ?>
</span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogVideo->Visible) { // blogVideo ?>
    <tr id="r_blogVideo"<?= $Page->blogVideo->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogVideo"><?= $Page->blogVideo->caption() ?></span></td>
        <td data-name="blogVideo"<?= $Page->blogVideo->cellAttributes() ?>>
<span id="el_blog_blogVideo">
<span<?= $Page->blogVideo->viewAttributes() ?>>
<?= $Page->blogVideo->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogDtPublik->Visible) { // blogDtPublik ?>
    <tr id="r_blogDtPublik"<?= $Page->blogDtPublik->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogDtPublik"><?= $Page->blogDtPublik->caption() ?></span></td>
        <td data-name="blogDtPublik"<?= $Page->blogDtPublik->cellAttributes() ?>>
<span id="el_blog_blogDtPublik">
<span<?= $Page->blogDtPublik->viewAttributes() ?>>
<?= $Page->blogDtPublik->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogAutori->Visible) { // blogAutori ?>
    <tr id="r_blogAutori"<?= $Page->blogAutori->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogAutori"><?= $Page->blogAutori->caption() ?></span></td>
        <td data-name="blogAutori"<?= $Page->blogAutori->cellAttributes() ?>>
<span id="el_blog_blogAutori">
<span<?= $Page->blogAutori->viewAttributes() ?>>
<?= $Page->blogAutori->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogShtuar->Visible) { // blogShtuar ?>
    <tr id="r_blogShtuar"<?= $Page->blogShtuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogShtuar"><?= $Page->blogShtuar->caption() ?></span></td>
        <td data-name="blogShtuar"<?= $Page->blogShtuar->cellAttributes() ?>>
<span id="el_blog_blogShtuar">
<span<?= $Page->blogShtuar->viewAttributes() ?>>
<?= $Page->blogShtuar->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->blogModifikuar->Visible) { // blogModifikuar ?>
    <tr id="r_blogModifikuar"<?= $Page->blogModifikuar->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_blog_blogModifikuar"><?= $Page->blogModifikuar->caption() ?></span></td>
        <td data-name="blogModifikuar"<?= $Page->blogModifikuar->cellAttributes() ?>>
<span id="el_blog_blogModifikuar">
<span<?= $Page->blogModifikuar->viewAttributes() ?>>
<?= $Page->blogModifikuar->getViewValue() ?></span>
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
