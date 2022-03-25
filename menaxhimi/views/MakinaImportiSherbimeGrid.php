<?php

namespace PHPMaker2022\volalservice;

// Set up and run Grid object
$Grid = Container("MakinaImportiSherbimeGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fmakina_importi_sherbimegrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fmakina_importi_sherbimegrid = new ew.Form("fmakina_importi_sherbimegrid", "grid");
    fmakina_importi_sherbimegrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { makina_importi_sherbime: currentTable } });
    var fields = currentTable.fields;
    fmakina_importi_sherbimegrid.addFields([
        ["mishID", [fields.mishID.visible && fields.mishID.required ? ew.Validators.required(fields.mishID.caption) : null], fields.mishID.isInvalid],
        ["mishMakinaImporti", [fields.mishMakinaImporti.visible && fields.mishMakinaImporti.required ? ew.Validators.required(fields.mishMakinaImporti.caption) : null, ew.Validators.integer], fields.mishMakinaImporti.isInvalid],
        ["mishPershkrimi", [fields.mishPershkrimi.visible && fields.mishPershkrimi.required ? ew.Validators.required(fields.mishPershkrimi.caption) : null], fields.mishPershkrimi.isInvalid],
        ["mishKryer", [fields.mishKryer.visible && fields.mishKryer.required ? ew.Validators.required(fields.mishKryer.caption) : null], fields.mishKryer.isInvalid],
        ["mishCmimi", [fields.mishCmimi.visible && fields.mishCmimi.required ? ew.Validators.required(fields.mishCmimi.caption) : null, ew.Validators.float], fields.mishCmimi.isInvalid],
        ["mishData", [fields.mishData.visible && fields.mishData.required ? ew.Validators.required(fields.mishData.caption) : null, ew.Validators.datetime(fields.mishData.clientFormatPattern)], fields.mishData.isInvalid]
    ]);

    // Check empty row
    fmakina_importi_sherbimegrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["mishMakinaImporti",false],["mishPershkrimi",false],["mishKryer",false],["mishCmimi",false],["mishData",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fmakina_importi_sherbimegrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fmakina_importi_sherbimegrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fmakina_importi_sherbimegrid.lists.mishMakinaImporti = <?= $Grid->mishMakinaImporti->toClientList($Grid) ?>;
    fmakina_importi_sherbimegrid.lists.mishKryer = <?= $Grid->mishKryer->toClientList($Grid) ?>;
    loadjs.done("fmakina_importi_sherbimegrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> makina_importi_sherbime">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<div id="fmakina_importi_sherbimegrid" class="ew-form ew-list-form">
<div id="gmp_makina_importi_sherbime" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_makina_importi_sherbimegrid" class="table table-sm ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Grid->RowType = ROWTYPE_HEADER;

// Render list options
$Grid->renderListOptions();

// Render list options (header, left)
$Grid->ListOptions->render("header", "left");
?>
<?php if ($Grid->mishID->Visible) { // mishID ?>
        <th data-name="mishID" class="<?= $Grid->mishID->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishID" class="makina_importi_sherbime_mishID"><?= $Grid->renderFieldHeader($Grid->mishID) ?></div></th>
<?php } ?>
<?php if ($Grid->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <th data-name="mishMakinaImporti" class="<?= $Grid->mishMakinaImporti->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishMakinaImporti" class="makina_importi_sherbime_mishMakinaImporti"><?= $Grid->renderFieldHeader($Grid->mishMakinaImporti) ?></div></th>
<?php } ?>
<?php if ($Grid->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <th data-name="mishPershkrimi" class="<?= $Grid->mishPershkrimi->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishPershkrimi" class="makina_importi_sherbime_mishPershkrimi"><?= $Grid->renderFieldHeader($Grid->mishPershkrimi) ?></div></th>
<?php } ?>
<?php if ($Grid->mishKryer->Visible) { // mishKryer ?>
        <th data-name="mishKryer" class="<?= $Grid->mishKryer->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishKryer" class="makina_importi_sherbime_mishKryer"><?= $Grid->renderFieldHeader($Grid->mishKryer) ?></div></th>
<?php } ?>
<?php if ($Grid->mishCmimi->Visible) { // mishCmimi ?>
        <th data-name="mishCmimi" class="<?= $Grid->mishCmimi->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishCmimi" class="makina_importi_sherbime_mishCmimi"><?= $Grid->renderFieldHeader($Grid->mishCmimi) ?></div></th>
<?php } ?>
<?php if ($Grid->mishData->Visible) { // mishData ?>
        <th data-name="mishData" class="<?= $Grid->mishData->headerCellClass() ?>"><div id="elh_makina_importi_sherbime_mishData" class="makina_importi_sherbime_mishData"><?= $Grid->renderFieldHeader($Grid->mishData) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Grid->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
$Grid->StartRecord = 1;
$Grid->StopRecord = $Grid->TotalRecords; // Show all records

// Restore number of post back records
if ($CurrentForm && ($Grid->isConfirm() || $Grid->EventCancelled)) {
    $CurrentForm->Index = -1;
    if ($CurrentForm->hasValue($Grid->FormKeyCountName) && ($Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm())) {
        $Grid->KeyCount = $CurrentForm->getValue($Grid->FormKeyCountName);
        $Grid->StopRecord = $Grid->StartRecord + $Grid->KeyCount - 1;
    }
}
$Grid->RecordCount = $Grid->StartRecord - 1;
if ($Grid->Recordset && !$Grid->Recordset->EOF) {
    // Nothing to do
} elseif ($Grid->isGridAdd() && !$Grid->AllowAddDeleteRow && $Grid->StopRecord == 0) {
    $Grid->StopRecord = $Grid->GridAddRowCount;
}

// Initialize aggregate
$Grid->RowType = ROWTYPE_AGGREGATEINIT;
$Grid->resetAttributes();
$Grid->renderRow();
while ($Grid->RecordCount < $Grid->StopRecord) {
    $Grid->RecordCount++;
    if ($Grid->RecordCount >= $Grid->StartRecord) {
        $Grid->RowCount++;
        if ($Grid->isAdd() || $Grid->isGridAdd() || $Grid->isGridEdit() || $Grid->isConfirm()) {
            $Grid->RowIndex++;
            $CurrentForm->Index = $Grid->RowIndex;
            if ($CurrentForm->hasValue($Grid->FormActionName) && ($Grid->isConfirm() || $Grid->EventCancelled)) {
                $Grid->RowAction = strval($CurrentForm->getValue($Grid->FormActionName));
            } elseif ($Grid->isGridAdd()) {
                $Grid->RowAction = "insert";
            } else {
                $Grid->RowAction = "";
            }
        }

        // Set up key count
        $Grid->KeyCount = $Grid->RowIndex;

        // Init row class and style
        $Grid->resetAttributes();
        $Grid->CssClass = "";
        if ($Grid->isGridAdd()) {
            if ($Grid->CurrentMode == "copy") {
                $Grid->loadRowValues($Grid->Recordset); // Load row values
                $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
            } else {
                $Grid->loadRowValues(); // Load default values
                $Grid->OldKey = "";
            }
        } else {
            $Grid->loadRowValues($Grid->Recordset); // Load row values
            $Grid->OldKey = $Grid->getKey(true); // Get from CurrentValue
        }
        $Grid->setKey($Grid->OldKey);
        $Grid->RowType = ROWTYPE_VIEW; // Render view
        if ($Grid->isGridAdd()) { // Grid add
            $Grid->RowType = ROWTYPE_ADD; // Render add
        }
        if ($Grid->isGridAdd() && $Grid->EventCancelled && !$CurrentForm->hasValue("k_blankrow")) { // Insert failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->isGridEdit()) { // Grid edit
            if ($Grid->EventCancelled) {
                $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
            }
            if ($Grid->RowAction == "insert") {
                $Grid->RowType = ROWTYPE_ADD; // Render add
            } else {
                $Grid->RowType = ROWTYPE_EDIT; // Render edit
            }
            if (!$Grid->EventCancelled) {
                $Grid->HashValue = $Grid->getRowHash($Grid->Recordset); // Get hash value for record
            }
        }
        if ($Grid->isGridEdit() && ($Grid->RowType == ROWTYPE_EDIT || $Grid->RowType == ROWTYPE_ADD) && $Grid->EventCancelled) { // Update failed
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }
        if ($Grid->RowType == ROWTYPE_EDIT) { // Edit row
            $Grid->EditRowCount++;
        }
        if ($Grid->isConfirm()) { // Confirm row
            $Grid->restoreCurrentRowFormValues($Grid->RowIndex); // Restore form values
        }

        // Set up row attributes
        $Grid->RowAttrs->merge([
            "data-rowindex" => $Grid->RowCount,
            "id" => "r" . $Grid->RowCount . "_makina_importi_sherbime",
            "data-rowtype" => $Grid->RowType,
            "class" => ($Grid->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($Grid->isAdd() && $Grid->RowType == ROWTYPE_ADD || $Grid->isEdit() && $Grid->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $Grid->RowAttrs->appendClass("table-active");
        }

        // Render row
        $Grid->renderRow();

        // Render list options
        $Grid->renderListOptions();

        // Skip delete row / empty row for confirm page
        if (
            $Page->RowAction != "delete" &&
            $Page->RowAction != "insertdelete" &&
            !($Page->RowAction == "insert" && $Page->isConfirm() && $Page->emptyRow())
        ) {
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowCount);
?>
    <?php if ($Grid->mishID->Visible) { // mishID ?>
        <td data-name="mishID"<?= $Grid->mishID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishID" class="el_makina_importi_sherbime_mishID"></span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishID" id="o<?= $Grid->RowIndex ?>_mishID" value="<?= HtmlEncode($Grid->mishID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishID" class="el_makina_importi_sherbime_mishID">
<span<?= $Grid->mishID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->mishID->getDisplayValue($Grid->mishID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mishID" id="x<?= $Grid->RowIndex ?>_mishID" value="<?= HtmlEncode($Grid->mishID->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishID" class="el_makina_importi_sherbime_mishID">
<span<?= $Grid->mishID->viewAttributes() ?>>
<?= $Grid->mishID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishID" data-hidden="1" name="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishID" id="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishID" value="<?= HtmlEncode($Grid->mishID->FormValue) ?>">
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishID" data-hidden="1" name="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishID" id="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishID" value="<?= HtmlEncode($Grid->mishID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mishID" id="x<?= $Grid->RowIndex ?>_mishID" value="<?= HtmlEncode($Grid->mishID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <td data-name="mishMakinaImporti"<?= $Grid->mishMakinaImporti->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->mishMakinaImporti->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Grid->mishMakinaImporti->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->mishMakinaImporti->getDisplayValue($Grid->mishMakinaImporti->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_mishMakinaImporti" name="x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode(FormatNumber($Grid->mishMakinaImporti->CurrentValue, $Grid->mishMakinaImporti->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<?php
$onchange = $Grid->mishMakinaImporti->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->mishMakinaImporti->EditAttrs["onchange"] = "";
if (IsRTL()) {
    $Grid->mishMakinaImporti->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_mishMakinaImporti" class="ew-auto-suggest">
    <input type="<?= $Grid->mishMakinaImporti->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" id="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= RemoveHtml($Grid->mishMakinaImporti->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->mishMakinaImporti->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->mishMakinaImporti->getPlaceHolder()) ?>"<?= $Grid->mishMakinaImporti->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-input="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" data-value-separator="<?= $Grid->mishMakinaImporti->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_mishMakinaImporti" id="x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode($Grid->mishMakinaImporti->CurrentValue) ?>"<?= $onchange ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->mishMakinaImporti->getErrorMessage() ?></div>
<script>
loadjs.ready("fmakina_importi_sherbimegrid", function() {
    fmakina_importi_sherbimegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_mishMakinaImporti","forceSelect":false}, ew.vars.tables.makina_importi_sherbime.fields.mishMakinaImporti.autoSuggestOptions));
});
</script>
<?= $Grid->mishMakinaImporti->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_mishMakinaImporti") ?>
</span>
<?php } ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishMakinaImporti" id="o<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode($Grid->mishMakinaImporti->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->mishMakinaImporti->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Grid->mishMakinaImporti->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->mishMakinaImporti->getDisplayValue($Grid->mishMakinaImporti->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_mishMakinaImporti" name="x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode(FormatNumber($Grid->mishMakinaImporti->CurrentValue, $Grid->mishMakinaImporti->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<?php
$onchange = $Grid->mishMakinaImporti->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->mishMakinaImporti->EditAttrs["onchange"] = "";
if (IsRTL()) {
    $Grid->mishMakinaImporti->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_mishMakinaImporti" class="ew-auto-suggest">
    <input type="<?= $Grid->mishMakinaImporti->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" id="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= RemoveHtml($Grid->mishMakinaImporti->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->mishMakinaImporti->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->mishMakinaImporti->getPlaceHolder()) ?>"<?= $Grid->mishMakinaImporti->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-input="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" data-value-separator="<?= $Grid->mishMakinaImporti->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_mishMakinaImporti" id="x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode($Grid->mishMakinaImporti->CurrentValue) ?>"<?= $onchange ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->mishMakinaImporti->getErrorMessage() ?></div>
<script>
loadjs.ready("fmakina_importi_sherbimegrid", function() {
    fmakina_importi_sherbimegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_mishMakinaImporti","forceSelect":false}, ew.vars.tables.makina_importi_sherbime.fields.mishMakinaImporti.autoSuggestOptions));
});
</script>
<?= $Grid->mishMakinaImporti->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_mishMakinaImporti") ?>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Grid->mishMakinaImporti->viewAttributes() ?>>
<?= $Grid->mishMakinaImporti->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-hidden="1" name="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishMakinaImporti" id="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode($Grid->mishMakinaImporti->FormValue) ?>">
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-hidden="1" name="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishMakinaImporti" id="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode($Grid->mishMakinaImporti->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <td data-name="mishPershkrimi"<?= $Grid->mishPershkrimi->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishPershkrimi" class="el_makina_importi_sherbime_mishPershkrimi">
<input type="<?= $Grid->mishPershkrimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishPershkrimi" id="x<?= $Grid->RowIndex ?>_mishPershkrimi" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" value="<?= $Grid->mishPershkrimi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->mishPershkrimi->getPlaceHolder()) ?>"<?= $Grid->mishPershkrimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishPershkrimi->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishPershkrimi" id="o<?= $Grid->RowIndex ?>_mishPershkrimi" value="<?= HtmlEncode($Grid->mishPershkrimi->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishPershkrimi" class="el_makina_importi_sherbime_mishPershkrimi">
<input type="<?= $Grid->mishPershkrimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishPershkrimi" id="x<?= $Grid->RowIndex ?>_mishPershkrimi" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" value="<?= $Grid->mishPershkrimi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->mishPershkrimi->getPlaceHolder()) ?>"<?= $Grid->mishPershkrimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishPershkrimi->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishPershkrimi" class="el_makina_importi_sherbime_mishPershkrimi">
<span<?= $Grid->mishPershkrimi->viewAttributes() ?>>
<?= $Grid->mishPershkrimi->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" data-hidden="1" name="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishPershkrimi" id="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishPershkrimi" value="<?= HtmlEncode($Grid->mishPershkrimi->FormValue) ?>">
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" data-hidden="1" name="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishPershkrimi" id="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishPershkrimi" value="<?= HtmlEncode($Grid->mishPershkrimi->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->mishKryer->Visible) { // mishKryer ?>
        <td data-name="mishKryer"<?= $Grid->mishKryer->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishKryer" class="el_makina_importi_sherbime_mishKryer">
<template id="tp_x<?= $Grid->RowIndex ?>_mishKryer">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi_sherbime" data-field="x_mishKryer" name="x<?= $Grid->RowIndex ?>_mishKryer" id="x<?= $Grid->RowIndex ?>_mishKryer"<?= $Grid->mishKryer->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_mishKryer" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_mishKryer"
    name="x<?= $Grid->RowIndex ?>_mishKryer"
    value="<?= HtmlEncode($Grid->mishKryer->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_mishKryer"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_mishKryer"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->mishKryer->isInvalidClass() ?>"
    data-table="makina_importi_sherbime"
    data-field="x_mishKryer"
    data-value-separator="<?= $Grid->mishKryer->displayValueSeparatorAttribute() ?>"
    <?= $Grid->mishKryer->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->mishKryer->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishKryer" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishKryer" id="o<?= $Grid->RowIndex ?>_mishKryer" value="<?= HtmlEncode($Grid->mishKryer->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishKryer" class="el_makina_importi_sherbime_mishKryer">
<template id="tp_x<?= $Grid->RowIndex ?>_mishKryer">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi_sherbime" data-field="x_mishKryer" name="x<?= $Grid->RowIndex ?>_mishKryer" id="x<?= $Grid->RowIndex ?>_mishKryer"<?= $Grid->mishKryer->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_mishKryer" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_mishKryer"
    name="x<?= $Grid->RowIndex ?>_mishKryer"
    value="<?= HtmlEncode($Grid->mishKryer->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_mishKryer"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_mishKryer"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->mishKryer->isInvalidClass() ?>"
    data-table="makina_importi_sherbime"
    data-field="x_mishKryer"
    data-value-separator="<?= $Grid->mishKryer->displayValueSeparatorAttribute() ?>"
    <?= $Grid->mishKryer->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->mishKryer->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishKryer" class="el_makina_importi_sherbime_mishKryer">
<span<?= $Grid->mishKryer->viewAttributes() ?>>
<?= $Grid->mishKryer->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishKryer" data-hidden="1" name="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishKryer" id="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishKryer" value="<?= HtmlEncode($Grid->mishKryer->FormValue) ?>">
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishKryer" data-hidden="1" name="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishKryer" id="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishKryer" value="<?= HtmlEncode($Grid->mishKryer->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->mishCmimi->Visible) { // mishCmimi ?>
        <td data-name="mishCmimi"<?= $Grid->mishCmimi->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishCmimi" class="el_makina_importi_sherbime_mishCmimi">
<input type="<?= $Grid->mishCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishCmimi" id="x<?= $Grid->RowIndex ?>_mishCmimi" data-table="makina_importi_sherbime" data-field="x_mishCmimi" value="<?= $Grid->mishCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->mishCmimi->getPlaceHolder()) ?>"<?= $Grid->mishCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishCmimi->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishCmimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishCmimi" id="o<?= $Grid->RowIndex ?>_mishCmimi" value="<?= HtmlEncode($Grid->mishCmimi->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishCmimi" class="el_makina_importi_sherbime_mishCmimi">
<input type="<?= $Grid->mishCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishCmimi" id="x<?= $Grid->RowIndex ?>_mishCmimi" data-table="makina_importi_sherbime" data-field="x_mishCmimi" value="<?= $Grid->mishCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->mishCmimi->getPlaceHolder()) ?>"<?= $Grid->mishCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishCmimi->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishCmimi" class="el_makina_importi_sherbime_mishCmimi">
<span<?= $Grid->mishCmimi->viewAttributes() ?>>
<?= $Grid->mishCmimi->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishCmimi" data-hidden="1" name="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishCmimi" id="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishCmimi" value="<?= HtmlEncode($Grid->mishCmimi->FormValue) ?>">
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishCmimi" data-hidden="1" name="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishCmimi" id="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishCmimi" value="<?= HtmlEncode($Grid->mishCmimi->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->mishData->Visible) { // mishData ?>
        <td data-name="mishData"<?= $Grid->mishData->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishData" class="el_makina_importi_sherbime_mishData">
<input type="<?= $Grid->mishData->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishData" id="x<?= $Grid->RowIndex ?>_mishData" data-table="makina_importi_sherbime" data-field="x_mishData" value="<?= $Grid->mishData->EditValue ?>" placeholder="<?= HtmlEncode($Grid->mishData->getPlaceHolder()) ?>"<?= $Grid->mishData->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishData->getErrorMessage() ?></div>
<?php if (!$Grid->mishData->ReadOnly && !$Grid->mishData->Disabled && !isset($Grid->mishData->EditAttrs["readonly"]) && !isset($Grid->mishData->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmakina_importi_sherbimegrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fmakina_importi_sherbimegrid", "x<?= $Grid->RowIndex ?>_mishData", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishData" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishData" id="o<?= $Grid->RowIndex ?>_mishData" value="<?= HtmlEncode($Grid->mishData->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishData" class="el_makina_importi_sherbime_mishData">
<input type="<?= $Grid->mishData->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishData" id="x<?= $Grid->RowIndex ?>_mishData" data-table="makina_importi_sherbime" data-field="x_mishData" value="<?= $Grid->mishData->EditValue ?>" placeholder="<?= HtmlEncode($Grid->mishData->getPlaceHolder()) ?>"<?= $Grid->mishData->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishData->getErrorMessage() ?></div>
<?php if (!$Grid->mishData->ReadOnly && !$Grid->mishData->Disabled && !isset($Grid->mishData->EditAttrs["readonly"]) && !isset($Grid->mishData->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmakina_importi_sherbimegrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fmakina_importi_sherbimegrid", "x<?= $Grid->RowIndex ?>_mishData", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_makina_importi_sherbime_mishData" class="el_makina_importi_sherbime_mishData">
<span<?= $Grid->mishData->viewAttributes() ?>>
<?= $Grid->mishData->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishData" data-hidden="1" name="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishData" id="fmakina_importi_sherbimegrid$x<?= $Grid->RowIndex ?>_mishData" value="<?= HtmlEncode($Grid->mishData->FormValue) ?>">
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishData" data-hidden="1" name="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishData" id="fmakina_importi_sherbimegrid$o<?= $Grid->RowIndex ?>_mishData" value="<?= HtmlEncode($Grid->mishData->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowCount);
?>
    </tr>
<?php if ($Grid->RowType == ROWTYPE_ADD || $Grid->RowType == ROWTYPE_EDIT) { ?>
<script>
loadjs.ready(["fmakina_importi_sherbimegrid","load"], () => fmakina_importi_sherbimegrid.updateLists(<?= $Grid->RowIndex ?>));
</script>
<?php } ?>
<?php
    }
    } // End delete row checking
    if (!$Grid->isGridAdd() || $Grid->CurrentMode == "copy")
        if (!$Grid->Recordset->EOF) {
            $Grid->Recordset->moveNext();
        }
}
?>
<?php
if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy" || $Grid->CurrentMode == "edit") {
    $Grid->RowIndex = '$rowindex$';
    $Grid->loadRowValues();

    // Set row properties
    $Grid->resetAttributes();
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_makina_importi_sherbime", "data-rowtype" => ROWTYPE_ADD]);
    $Grid->RowAttrs->appendClass("ew-template");

    // Reset previous form error if any
    $Grid->resetFormError();

    // Render row
    $Grid->RowType = ROWTYPE_ADD;
    $Grid->renderRow();

    // Render list options
    $Grid->renderListOptions();
    $Grid->StartRowCount = 0;
?>
    <tr <?= $Grid->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Grid->ListOptions->render("body", "left", $Grid->RowIndex);
?>
    <?php if ($Grid->mishID->Visible) { // mishID ?>
        <td data-name="mishID">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishID" class="el_makina_importi_sherbime_mishID"></span>
<?php } else { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishID" class="el_makina_importi_sherbime_mishID">
<span<?= $Grid->mishID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->mishID->getDisplayValue($Grid->mishID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mishID" id="x<?= $Grid->RowIndex ?>_mishID" value="<?= HtmlEncode($Grid->mishID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishID" id="o<?= $Grid->RowIndex ?>_mishID" value="<?= HtmlEncode($Grid->mishID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <td data-name="mishMakinaImporti">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->mishMakinaImporti->getSessionValue() != "") { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Grid->mishMakinaImporti->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->mishMakinaImporti->getDisplayValue($Grid->mishMakinaImporti->ViewValue) ?></span></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_mishMakinaImporti" name="x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode(FormatNumber($Grid->mishMakinaImporti->CurrentValue, $Grid->mishMakinaImporti->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<?php
$onchange = $Grid->mishMakinaImporti->EditAttrs->prepend("onchange", "");
$onchange = ($onchange) ? ' onchange="' . JsEncode($onchange) . '"' : '';
$Grid->mishMakinaImporti->EditAttrs["onchange"] = "";
if (IsRTL()) {
    $Grid->mishMakinaImporti->EditAttrs["dir"] = "rtl";
}
?>
<span id="as_x<?= $Grid->RowIndex ?>_mishMakinaImporti" class="ew-auto-suggest">
    <input type="<?= $Grid->mishMakinaImporti->getInputTextType() ?>" class="form-control" name="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" id="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= RemoveHtml($Grid->mishMakinaImporti->EditValue) ?>" size="30" placeholder="<?= HtmlEncode($Grid->mishMakinaImporti->getPlaceHolder()) ?>" data-placeholder="<?= HtmlEncode($Grid->mishMakinaImporti->getPlaceHolder()) ?>"<?= $Grid->mishMakinaImporti->editAttributes() ?>>
</span>
<selection-list hidden class="form-control" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-input="sv_x<?= $Grid->RowIndex ?>_mishMakinaImporti" data-value-separator="<?= $Grid->mishMakinaImporti->displayValueSeparatorAttribute() ?>" name="x<?= $Grid->RowIndex ?>_mishMakinaImporti" id="x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode($Grid->mishMakinaImporti->CurrentValue) ?>"<?= $onchange ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->mishMakinaImporti->getErrorMessage() ?></div>
<script>
loadjs.ready("fmakina_importi_sherbimegrid", function() {
    fmakina_importi_sherbimegrid.createAutoSuggest(Object.assign({"id":"x<?= $Grid->RowIndex ?>_mishMakinaImporti","forceSelect":false}, ew.vars.tables.makina_importi_sherbime.fields.mishMakinaImporti.autoSuggestOptions));
});
</script>
<?= $Grid->mishMakinaImporti->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_mishMakinaImporti") ?>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishMakinaImporti" class="el_makina_importi_sherbime_mishMakinaImporti">
<span<?= $Grid->mishMakinaImporti->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->mishMakinaImporti->getDisplayValue($Grid->mishMakinaImporti->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mishMakinaImporti" id="x<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode($Grid->mishMakinaImporti->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishMakinaImporti" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishMakinaImporti" id="o<?= $Grid->RowIndex ?>_mishMakinaImporti" value="<?= HtmlEncode($Grid->mishMakinaImporti->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <td data-name="mishPershkrimi">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishPershkrimi" class="el_makina_importi_sherbime_mishPershkrimi">
<input type="<?= $Grid->mishPershkrimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishPershkrimi" id="x<?= $Grid->RowIndex ?>_mishPershkrimi" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" value="<?= $Grid->mishPershkrimi->EditValue ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Grid->mishPershkrimi->getPlaceHolder()) ?>"<?= $Grid->mishPershkrimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishPershkrimi->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishPershkrimi" class="el_makina_importi_sherbime_mishPershkrimi">
<span<?= $Grid->mishPershkrimi->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->mishPershkrimi->getDisplayValue($Grid->mishPershkrimi->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mishPershkrimi" id="x<?= $Grid->RowIndex ?>_mishPershkrimi" value="<?= HtmlEncode($Grid->mishPershkrimi->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishPershkrimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishPershkrimi" id="o<?= $Grid->RowIndex ?>_mishPershkrimi" value="<?= HtmlEncode($Grid->mishPershkrimi->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->mishKryer->Visible) { // mishKryer ?>
        <td data-name="mishKryer">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishKryer" class="el_makina_importi_sherbime_mishKryer">
<template id="tp_x<?= $Grid->RowIndex ?>_mishKryer">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="makina_importi_sherbime" data-field="x_mishKryer" name="x<?= $Grid->RowIndex ?>_mishKryer" id="x<?= $Grid->RowIndex ?>_mishKryer"<?= $Grid->mishKryer->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x<?= $Grid->RowIndex ?>_mishKryer" class="ew-item-list"></div>
<selection-list hidden
    id="x<?= $Grid->RowIndex ?>_mishKryer"
    name="x<?= $Grid->RowIndex ?>_mishKryer"
    value="<?= HtmlEncode($Grid->mishKryer->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x<?= $Grid->RowIndex ?>_mishKryer"
    data-bs-target="dsl_x<?= $Grid->RowIndex ?>_mishKryer"
    data-repeatcolumn="5"
    class="form-control<?= $Grid->mishKryer->isInvalidClass() ?>"
    data-table="makina_importi_sherbime"
    data-field="x_mishKryer"
    data-value-separator="<?= $Grid->mishKryer->displayValueSeparatorAttribute() ?>"
    <?= $Grid->mishKryer->editAttributes() ?>></selection-list>
<div class="invalid-feedback"><?= $Grid->mishKryer->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishKryer" class="el_makina_importi_sherbime_mishKryer">
<span<?= $Grid->mishKryer->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->mishKryer->getDisplayValue($Grid->mishKryer->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishKryer" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mishKryer" id="x<?= $Grid->RowIndex ?>_mishKryer" value="<?= HtmlEncode($Grid->mishKryer->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishKryer" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishKryer" id="o<?= $Grid->RowIndex ?>_mishKryer" value="<?= HtmlEncode($Grid->mishKryer->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->mishCmimi->Visible) { // mishCmimi ?>
        <td data-name="mishCmimi">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishCmimi" class="el_makina_importi_sherbime_mishCmimi">
<input type="<?= $Grid->mishCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishCmimi" id="x<?= $Grid->RowIndex ?>_mishCmimi" data-table="makina_importi_sherbime" data-field="x_mishCmimi" value="<?= $Grid->mishCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->mishCmimi->getPlaceHolder()) ?>"<?= $Grid->mishCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishCmimi->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishCmimi" class="el_makina_importi_sherbime_mishCmimi">
<span<?= $Grid->mishCmimi->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->mishCmimi->getDisplayValue($Grid->mishCmimi->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishCmimi" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mishCmimi" id="x<?= $Grid->RowIndex ?>_mishCmimi" value="<?= HtmlEncode($Grid->mishCmimi->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishCmimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishCmimi" id="o<?= $Grid->RowIndex ?>_mishCmimi" value="<?= HtmlEncode($Grid->mishCmimi->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->mishData->Visible) { // mishData ?>
        <td data-name="mishData">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishData" class="el_makina_importi_sherbime_mishData">
<input type="<?= $Grid->mishData->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_mishData" id="x<?= $Grid->RowIndex ?>_mishData" data-table="makina_importi_sherbime" data-field="x_mishData" value="<?= $Grid->mishData->EditValue ?>" placeholder="<?= HtmlEncode($Grid->mishData->getPlaceHolder()) ?>"<?= $Grid->mishData->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->mishData->getErrorMessage() ?></div>
<?php if (!$Grid->mishData->ReadOnly && !$Grid->mishData->Disabled && !isset($Grid->mishData->EditAttrs["readonly"]) && !isset($Grid->mishData->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fmakina_importi_sherbimegrid", "datetimepicker"], function () {
    let format = "<?= DateFormat(7) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID
            },
            display: {
                components: {
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                icons: {
                    previous: ew.IS_RTL ? "fas fa-chevron-right" : "fas fa-chevron-left",
                    next: ew.IS_RTL ? "fas fa-chevron-left" : "fas fa-chevron-right"
                }
            },
            meta: {
                format,
                numberingSystem: ew.getNumberingSystem()
            }
        };
    ew.createDateTimePicker("fmakina_importi_sherbimegrid", "x<?= $Grid->RowIndex ?>_mishData", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</span>
<?php } else { ?>
<span id="el$rowindex$_makina_importi_sherbime_mishData" class="el_makina_importi_sherbime_mishData">
<span<?= $Grid->mishData->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->mishData->getDisplayValue($Grid->mishData->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishData" data-hidden="1" name="x<?= $Grid->RowIndex ?>_mishData" id="x<?= $Grid->RowIndex ?>_mishData" value="<?= HtmlEncode($Grid->mishData->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="makina_importi_sherbime" data-field="x_mishData" data-hidden="1" name="o<?= $Grid->RowIndex ?>_mishData" id="o<?= $Grid->RowIndex ?>_mishData" value="<?= HtmlEncode($Grid->mishData->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fmakina_importi_sherbimegrid","load"], () => fmakina_importi_sherbimegrid.updateLists(<?= $Grid->RowIndex ?>, true));
</script>
    </tr>
<?php
}
?>
</tbody>
<?php
// Render aggregate row
$Grid->RowType = ROWTYPE_AGGREGATE;
$Grid->resetAttributes();
$Grid->renderRow();
?>
<?php if ($Grid->TotalRecords > 0 && $Grid->CurrentMode == "view") { ?>
<tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options
$Grid->renderListOptions();

// Render list options (footer, left)
$Grid->ListOptions->render("footer", "left");
?>
    <?php if ($Grid->mishID->Visible) { // mishID ?>
        <td data-name="mishID" class="<?= $Grid->mishID->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishID" class="makina_importi_sherbime_mishID">
        <span class="ew-aggregate"><?= $Language->phrase("COUNT") ?></span><span class="ew-aggregate-value">
        <?= $Grid->mishID->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->mishMakinaImporti->Visible) { // mishMakinaImporti ?>
        <td data-name="mishMakinaImporti" class="<?= $Grid->mishMakinaImporti->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishMakinaImporti" class="makina_importi_sherbime_mishMakinaImporti">
        </span></td>
    <?php } ?>
    <?php if ($Grid->mishPershkrimi->Visible) { // mishPershkrimi ?>
        <td data-name="mishPershkrimi" class="<?= $Grid->mishPershkrimi->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishPershkrimi" class="makina_importi_sherbime_mishPershkrimi">
        </span></td>
    <?php } ?>
    <?php if ($Grid->mishKryer->Visible) { // mishKryer ?>
        <td data-name="mishKryer" class="<?= $Grid->mishKryer->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishKryer" class="makina_importi_sherbime_mishKryer">
        </span></td>
    <?php } ?>
    <?php if ($Grid->mishCmimi->Visible) { // mishCmimi ?>
        <td data-name="mishCmimi" class="<?= $Grid->mishCmimi->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishCmimi" class="makina_importi_sherbime_mishCmimi">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->mishCmimi->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->mishData->Visible) { // mishData ?>
        <td data-name="mishData" class="<?= $Grid->mishData->footerCellClass() ?>"><span id="elf_makina_importi_sherbime_mishData" class="makina_importi_sherbime_mishData">
        </span></td>
    <?php } ?>
<?php
// Render list options (footer, right)
$Grid->ListOptions->render("footer", "right");
?>
    </tr>
</tfoot>
<?php } ?>
</table><!-- /.ew-table -->
</div><!-- /.ew-grid-middle-panel -->
<?php if ($Grid->CurrentMode == "add" || $Grid->CurrentMode == "copy") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "edit") { ?>
<input type="hidden" name="<?= $Grid->FormKeyCountName ?>" id="<?= $Grid->FormKeyCountName ?>" value="<?= $Grid->KeyCount ?>">
<?= $Grid->MultiSelectKey ?>
<?php } ?>
<?php if ($Grid->CurrentMode == "") { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
<input type="hidden" name="detailpage" value="fmakina_importi_sherbimegrid">
</div><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Grid->Recordset) {
    $Grid->Recordset->close();
}
?>
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php $Grid->OtherOptions->render("body", "bottom") ?>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php if (!$Grid->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("makina_importi_sherbime");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
