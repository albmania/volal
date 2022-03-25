<?php

namespace PHPMaker2022\volalservice;

// Page object
$BlogDelete = &$Page;
?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { blog: currentTable } });
var currentForm, currentPageID;
var fblogdelete;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fblogdelete = new ew.Form("fblogdelete", "delete");
    currentPageID = ew.PAGE_ID = "delete";
    currentForm = fblogdelete;
    loadjs.done("fblogdelete");
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
<form name="fblogdelete" id="fblogdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="blog">
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
<?php if ($Page->blogID->Visible) { // blogID ?>
        <th class="<?= $Page->blogID->headerCellClass() ?>"><span id="elh_blog_blogID" class="blog_blogID"><?= $Page->blogID->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogGjuha->Visible) { // blogGjuha ?>
        <th class="<?= $Page->blogGjuha->headerCellClass() ?>"><span id="elh_blog_blogGjuha" class="blog_blogGjuha"><?= $Page->blogGjuha->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogKategoria->Visible) { // blogKategoria ?>
        <th class="<?= $Page->blogKategoria->headerCellClass() ?>"><span id="elh_blog_blogKategoria" class="blog_blogKategoria"><?= $Page->blogKategoria->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogTitulli->Visible) { // blogTitulli ?>
        <th class="<?= $Page->blogTitulli->headerCellClass() ?>"><span id="elh_blog_blogTitulli" class="blog_blogTitulli"><?= $Page->blogTitulli->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogDtPublik->Visible) { // blogDtPublik ?>
        <th class="<?= $Page->blogDtPublik->headerCellClass() ?>"><span id="elh_blog_blogDtPublik" class="blog_blogDtPublik"><?= $Page->blogDtPublik->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogAutori->Visible) { // blogAutori ?>
        <th class="<?= $Page->blogAutori->headerCellClass() ?>"><span id="elh_blog_blogAutori" class="blog_blogAutori"><?= $Page->blogAutori->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogShtuar->Visible) { // blogShtuar ?>
        <th class="<?= $Page->blogShtuar->headerCellClass() ?>"><span id="elh_blog_blogShtuar" class="blog_blogShtuar"><?= $Page->blogShtuar->caption() ?></span></th>
<?php } ?>
<?php if ($Page->blogModifikuar->Visible) { // blogModifikuar ?>
        <th class="<?= $Page->blogModifikuar->headerCellClass() ?>"><span id="elh_blog_blogModifikuar" class="blog_blogModifikuar"><?= $Page->blogModifikuar->caption() ?></span></th>
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
<?php if ($Page->blogID->Visible) { // blogID ?>
        <td<?= $Page->blogID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogID" class="el_blog_blogID">
<span<?= $Page->blogID->viewAttributes() ?>>
<?= $Page->blogID->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogGjuha->Visible) { // blogGjuha ?>
        <td<?= $Page->blogGjuha->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogGjuha" class="el_blog_blogGjuha">
<span<?= $Page->blogGjuha->viewAttributes() ?>>
<?= $Page->blogGjuha->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogKategoria->Visible) { // blogKategoria ?>
        <td<?= $Page->blogKategoria->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogKategoria" class="el_blog_blogKategoria">
<span<?= $Page->blogKategoria->viewAttributes() ?>>
<?= $Page->blogKategoria->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogTitulli->Visible) { // blogTitulli ?>
        <td<?= $Page->blogTitulli->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogTitulli" class="el_blog_blogTitulli">
<span<?= $Page->blogTitulli->viewAttributes() ?>>
<?= $Page->blogTitulli->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogDtPublik->Visible) { // blogDtPublik ?>
        <td<?= $Page->blogDtPublik->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogDtPublik" class="el_blog_blogDtPublik">
<span<?= $Page->blogDtPublik->viewAttributes() ?>>
<?= $Page->blogDtPublik->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogAutori->Visible) { // blogAutori ?>
        <td<?= $Page->blogAutori->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogAutori" class="el_blog_blogAutori">
<span<?= $Page->blogAutori->viewAttributes() ?>>
<?= $Page->blogAutori->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogShtuar->Visible) { // blogShtuar ?>
        <td<?= $Page->blogShtuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogShtuar" class="el_blog_blogShtuar">
<span<?= $Page->blogShtuar->viewAttributes() ?>>
<?= $Page->blogShtuar->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->blogModifikuar->Visible) { // blogModifikuar ?>
        <td<?= $Page->blogModifikuar->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_blog_blogModifikuar" class="el_blog_blogModifikuar">
<span<?= $Page->blogModifikuar->viewAttributes() ?>>
<?= $Page->blogModifikuar->getViewValue() ?></span>
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
