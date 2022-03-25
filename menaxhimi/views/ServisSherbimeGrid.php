<?php

namespace PHPMaker2022\volalservice;

// Set up and run Grid object
$Grid = Container("ServisSherbimeGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fservis_sherbimegrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_sherbimegrid = new ew.Form("fservis_sherbimegrid", "grid");
    fservis_sherbimegrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { servis_sherbime: currentTable } });
    var fields = currentTable.fields;
    fservis_sherbimegrid.addFields([
        ["servisSherbimID", [fields.servisSherbimID.visible && fields.servisSherbimID.required ? ew.Validators.required(fields.servisSherbimID.caption) : null], fields.servisSherbimID.isInvalid],
        ["servisSherbimServisID", [fields.servisSherbimServisID.visible && fields.servisSherbimServisID.required ? ew.Validators.required(fields.servisSherbimServisID.caption) : null, ew.Validators.integer], fields.servisSherbimServisID.isInvalid],
        ["servisSherbimSherbimi", [fields.servisSherbimSherbimi.visible && fields.servisSherbimSherbimi.required ? ew.Validators.required(fields.servisSherbimSherbimi.caption) : null], fields.servisSherbimSherbimi.isInvalid],
        ["servisSherbimCmimi", [fields.servisSherbimCmimi.visible && fields.servisSherbimCmimi.required ? ew.Validators.required(fields.servisSherbimCmimi.caption) : null, ew.Validators.float], fields.servisSherbimCmimi.isInvalid],
        ["servisSherbimShenim", [fields.servisSherbimShenim.visible && fields.servisSherbimShenim.required ? ew.Validators.required(fields.servisSherbimShenim.caption) : null], fields.servisSherbimShenim.isInvalid]
    ]);

    // Check empty row
    fservis_sherbimegrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["servisSherbimServisID",false],["servisSherbimSherbimi",false],["servisSherbimCmimi",false],["servisSherbimShenim",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fservis_sherbimegrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fservis_sherbimegrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fservis_sherbimegrid.lists.servisSherbimSherbimi = <?= $Grid->servisSherbimSherbimi->toClientList($Grid) ?>;
    loadjs.done("fservis_sherbimegrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> servis_sherbime">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<div id="fservis_sherbimegrid" class="ew-form ew-list-form">
<div id="gmp_servis_sherbime" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_servis_sherbimegrid" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Grid->servisSherbimID->Visible) { // servisSherbimID ?>
        <th data-name="servisSherbimID" class="<?= $Grid->servisSherbimID->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimID" class="servis_sherbime_servisSherbimID"><?= $Grid->renderFieldHeader($Grid->servisSherbimID) ?></div></th>
<?php } ?>
<?php if ($Grid->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <th data-name="servisSherbimServisID" class="<?= $Grid->servisSherbimServisID->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimServisID" class="servis_sherbime_servisSherbimServisID"><?= $Grid->renderFieldHeader($Grid->servisSherbimServisID) ?></div></th>
<?php } ?>
<?php if ($Grid->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <th data-name="servisSherbimSherbimi" class="<?= $Grid->servisSherbimSherbimi->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimSherbimi" class="servis_sherbime_servisSherbimSherbimi"><?= $Grid->renderFieldHeader($Grid->servisSherbimSherbimi) ?></div></th>
<?php } ?>
<?php if ($Grid->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <th data-name="servisSherbimCmimi" class="<?= $Grid->servisSherbimCmimi->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimCmimi" class="servis_sherbime_servisSherbimCmimi"><?= $Grid->renderFieldHeader($Grid->servisSherbimCmimi) ?></div></th>
<?php } ?>
<?php if ($Grid->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <th data-name="servisSherbimShenim" class="<?= $Grid->servisSherbimShenim->headerCellClass() ?>"><div id="elh_servis_sherbime_servisSherbimShenim" class="servis_sherbime_servisSherbimShenim"><?= $Grid->renderFieldHeader($Grid->servisSherbimShenim) ?></div></th>
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
            "id" => "r" . $Grid->RowCount . "_servis_sherbime",
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
    <?php if ($Grid->servisSherbimID->Visible) { // servisSherbimID ?>
        <td data-name="servisSherbimID"<?= $Grid->servisSherbimID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimID" class="el_servis_sherbime_servisSherbimID"></span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimID" id="o<?= $Grid->RowIndex ?>_servisSherbimID" value="<?= HtmlEncode($Grid->servisSherbimID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimID" class="el_servis_sherbime_servisSherbimID">
<span<?= $Grid->servisSherbimID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisSherbimID->getDisplayValue($Grid->servisSherbimID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisSherbimID" id="x<?= $Grid->RowIndex ?>_servisSherbimID" value="<?= HtmlEncode($Grid->servisSherbimID->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimID" class="el_servis_sherbime_servisSherbimID">
<span<?= $Grid->servisSherbimID->viewAttributes() ?>>
<?= $Grid->servisSherbimID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimID" data-hidden="1" name="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimID" id="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimID" value="<?= HtmlEncode($Grid->servisSherbimID->FormValue) ?>">
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimID" data-hidden="1" name="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimID" id="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimID" value="<?= HtmlEncode($Grid->servisSherbimID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisSherbimID" id="x<?= $Grid->RowIndex ?>_servisSherbimID" value="<?= HtmlEncode($Grid->servisSherbimID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <td data-name="servisSherbimServisID"<?= $Grid->servisSherbimServisID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->servisSherbimServisID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<span<?= $Grid->servisSherbimServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisSherbimServisID->getDisplayValue($Grid->servisSherbimServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_servisSherbimServisID" name="x<?= $Grid->RowIndex ?>_servisSherbimServisID" value="<?= HtmlEncode(FormatNumber($Grid->servisSherbimServisID->CurrentValue, $Grid->servisSherbimServisID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<input type="<?= $Grid->servisSherbimServisID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimServisID" id="x<?= $Grid->RowIndex ?>_servisSherbimServisID" data-table="servis_sherbime" data-field="x_servisSherbimServisID" value="<?= $Grid->servisSherbimServisID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisSherbimServisID->getPlaceHolder()) ?>"<?= $Grid->servisSherbimServisID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimServisID->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimServisID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimServisID" id="o<?= $Grid->RowIndex ?>_servisSherbimServisID" value="<?= HtmlEncode($Grid->servisSherbimServisID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->servisSherbimServisID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<span<?= $Grid->servisSherbimServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisSherbimServisID->getDisplayValue($Grid->servisSherbimServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_servisSherbimServisID" name="x<?= $Grid->RowIndex ?>_servisSherbimServisID" value="<?= HtmlEncode(FormatNumber($Grid->servisSherbimServisID->CurrentValue, $Grid->servisSherbimServisID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<input type="<?= $Grid->servisSherbimServisID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimServisID" id="x<?= $Grid->RowIndex ?>_servisSherbimServisID" data-table="servis_sherbime" data-field="x_servisSherbimServisID" value="<?= $Grid->servisSherbimServisID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisSherbimServisID->getPlaceHolder()) ?>"<?= $Grid->servisSherbimServisID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimServisID->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<span<?= $Grid->servisSherbimServisID->viewAttributes() ?>>
<?= $Grid->servisSherbimServisID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimServisID" data-hidden="1" name="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimServisID" id="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimServisID" value="<?= HtmlEncode($Grid->servisSherbimServisID->FormValue) ?>">
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimServisID" data-hidden="1" name="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimServisID" id="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimServisID" value="<?= HtmlEncode($Grid->servisSherbimServisID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <td data-name="servisSherbimSherbimi"<?= $Grid->servisSherbimSherbimi->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimSherbimi" class="el_servis_sherbime_servisSherbimSherbimi">
    <select
        id="x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        name="x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        class="form-select ew-select<?= $Grid->servisSherbimSherbimi->isInvalidClass() ?>"
        data-select2-id="fservis_sherbimegrid_x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        data-table="servis_sherbime"
        data-field="x_servisSherbimSherbimi"
        data-value-separator="<?= $Grid->servisSherbimSherbimi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->servisSherbimSherbimi->getPlaceHolder()) ?>"
        <?= $Grid->servisSherbimSherbimi->editAttributes() ?>>
        <?= $Grid->servisSherbimSherbimi->selectOptionListHtml("x{$Grid->RowIndex}_servisSherbimSherbimi") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->servisSherbimSherbimi->getErrorMessage() ?></div>
<?= $Grid->servisSherbimSherbimi->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_servisSherbimSherbimi") ?>
<script>
loadjs.ready("fservis_sherbimegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", selectId: "fservis_sherbimegrid_x<?= $Grid->RowIndex ?>_servisSherbimSherbimi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservis_sherbimegrid.lists.servisSherbimSherbimi.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", form: "fservis_sherbimegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", form: "fservis_sherbimegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis_sherbime.fields.servisSherbimSherbimi.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimSherbimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimSherbimi" id="o<?= $Grid->RowIndex ?>_servisSherbimSherbimi" value="<?= HtmlEncode($Grid->servisSherbimSherbimi->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimSherbimi" class="el_servis_sherbime_servisSherbimSherbimi">
    <select
        id="x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        name="x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        class="form-select ew-select<?= $Grid->servisSherbimSherbimi->isInvalidClass() ?>"
        data-select2-id="fservis_sherbimegrid_x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        data-table="servis_sherbime"
        data-field="x_servisSherbimSherbimi"
        data-value-separator="<?= $Grid->servisSherbimSherbimi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->servisSherbimSherbimi->getPlaceHolder()) ?>"
        <?= $Grid->servisSherbimSherbimi->editAttributes() ?>>
        <?= $Grid->servisSherbimSherbimi->selectOptionListHtml("x{$Grid->RowIndex}_servisSherbimSherbimi") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->servisSherbimSherbimi->getErrorMessage() ?></div>
<?= $Grid->servisSherbimSherbimi->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_servisSherbimSherbimi") ?>
<script>
loadjs.ready("fservis_sherbimegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", selectId: "fservis_sherbimegrid_x<?= $Grid->RowIndex ?>_servisSherbimSherbimi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservis_sherbimegrid.lists.servisSherbimSherbimi.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", form: "fservis_sherbimegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", form: "fservis_sherbimegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis_sherbime.fields.servisSherbimSherbimi.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimSherbimi" class="el_servis_sherbime_servisSherbimSherbimi">
<span<?= $Grid->servisSherbimSherbimi->viewAttributes() ?>>
<?= $Grid->servisSherbimSherbimi->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimSherbimi" data-hidden="1" name="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimSherbimi" id="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimSherbimi" value="<?= HtmlEncode($Grid->servisSherbimSherbimi->FormValue) ?>">
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimSherbimi" data-hidden="1" name="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimSherbimi" id="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimSherbimi" value="<?= HtmlEncode($Grid->servisSherbimSherbimi->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <td data-name="servisSherbimCmimi"<?= $Grid->servisSherbimCmimi->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimCmimi" class="el_servis_sherbime_servisSherbimCmimi">
<input type="<?= $Grid->servisSherbimCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimCmimi" id="x<?= $Grid->RowIndex ?>_servisSherbimCmimi" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" value="<?= $Grid->servisSherbimCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisSherbimCmimi->getPlaceHolder()) ?>"<?= $Grid->servisSherbimCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimCmimi->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimCmimi" id="o<?= $Grid->RowIndex ?>_servisSherbimCmimi" value="<?= HtmlEncode($Grid->servisSherbimCmimi->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimCmimi" class="el_servis_sherbime_servisSherbimCmimi">
<input type="<?= $Grid->servisSherbimCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimCmimi" id="x<?= $Grid->RowIndex ?>_servisSherbimCmimi" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" value="<?= $Grid->servisSherbimCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisSherbimCmimi->getPlaceHolder()) ?>"<?= $Grid->servisSherbimCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimCmimi->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimCmimi" class="el_servis_sherbime_servisSherbimCmimi">
<span<?= $Grid->servisSherbimCmimi->viewAttributes() ?>>
<?= $Grid->servisSherbimCmimi->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" data-hidden="1" name="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimCmimi" id="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimCmimi" value="<?= HtmlEncode($Grid->servisSherbimCmimi->FormValue) ?>">
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" data-hidden="1" name="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimCmimi" id="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimCmimi" value="<?= HtmlEncode($Grid->servisSherbimCmimi->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <td data-name="servisSherbimShenim"<?= $Grid->servisSherbimShenim->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimShenim" class="el_servis_sherbime_servisSherbimShenim">
<input type="<?= $Grid->servisSherbimShenim->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimShenim" id="x<?= $Grid->RowIndex ?>_servisSherbimShenim" data-table="servis_sherbime" data-field="x_servisSherbimShenim" value="<?= $Grid->servisSherbimShenim->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Grid->servisSherbimShenim->getPlaceHolder()) ?>"<?= $Grid->servisSherbimShenim->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimShenim->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimShenim" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimShenim" id="o<?= $Grid->RowIndex ?>_servisSherbimShenim" value="<?= HtmlEncode($Grid->servisSherbimShenim->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimShenim" class="el_servis_sherbime_servisSherbimShenim">
<input type="<?= $Grid->servisSherbimShenim->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimShenim" id="x<?= $Grid->RowIndex ?>_servisSherbimShenim" data-table="servis_sherbime" data-field="x_servisSherbimShenim" value="<?= $Grid->servisSherbimShenim->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Grid->servisSherbimShenim->getPlaceHolder()) ?>"<?= $Grid->servisSherbimShenim->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimShenim->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_sherbime_servisSherbimShenim" class="el_servis_sherbime_servisSherbimShenim">
<span<?= $Grid->servisSherbimShenim->viewAttributes() ?>>
<?= $Grid->servisSherbimShenim->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimShenim" data-hidden="1" name="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimShenim" id="fservis_sherbimegrid$x<?= $Grid->RowIndex ?>_servisSherbimShenim" value="<?= HtmlEncode($Grid->servisSherbimShenim->FormValue) ?>">
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimShenim" data-hidden="1" name="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimShenim" id="fservis_sherbimegrid$o<?= $Grid->RowIndex ?>_servisSherbimShenim" value="<?= HtmlEncode($Grid->servisSherbimShenim->OldValue) ?>">
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
loadjs.ready(["fservis_sherbimegrid","load"], () => fservis_sherbimegrid.updateLists(<?= $Grid->RowIndex ?>));
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
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_servis_sherbime", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->servisSherbimID->Visible) { // servisSherbimID ?>
        <td data-name="servisSherbimID">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimID" class="el_servis_sherbime_servisSherbimID"></span>
<?php } else { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimID" class="el_servis_sherbime_servisSherbimID">
<span<?= $Grid->servisSherbimID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisSherbimID->getDisplayValue($Grid->servisSherbimID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisSherbimID" id="x<?= $Grid->RowIndex ?>_servisSherbimID" value="<?= HtmlEncode($Grid->servisSherbimID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimID" id="o<?= $Grid->RowIndex ?>_servisSherbimID" value="<?= HtmlEncode($Grid->servisSherbimID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <td data-name="servisSherbimServisID">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->servisSherbimServisID->getSessionValue() != "") { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<span<?= $Grid->servisSherbimServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisSherbimServisID->getDisplayValue($Grid->servisSherbimServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_servisSherbimServisID" name="x<?= $Grid->RowIndex ?>_servisSherbimServisID" value="<?= HtmlEncode(FormatNumber($Grid->servisSherbimServisID->CurrentValue, $Grid->servisSherbimServisID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<input type="<?= $Grid->servisSherbimServisID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimServisID" id="x<?= $Grid->RowIndex ?>_servisSherbimServisID" data-table="servis_sherbime" data-field="x_servisSherbimServisID" value="<?= $Grid->servisSherbimServisID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisSherbimServisID->getPlaceHolder()) ?>"<?= $Grid->servisSherbimServisID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimServisID->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimServisID" class="el_servis_sherbime_servisSherbimServisID">
<span<?= $Grid->servisSherbimServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisSherbimServisID->getDisplayValue($Grid->servisSherbimServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimServisID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisSherbimServisID" id="x<?= $Grid->RowIndex ?>_servisSherbimServisID" value="<?= HtmlEncode($Grid->servisSherbimServisID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimServisID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimServisID" id="o<?= $Grid->RowIndex ?>_servisSherbimServisID" value="<?= HtmlEncode($Grid->servisSherbimServisID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <td data-name="servisSherbimSherbimi">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimSherbimi" class="el_servis_sherbime_servisSherbimSherbimi">
    <select
        id="x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        name="x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        class="form-select ew-select<?= $Grid->servisSherbimSherbimi->isInvalidClass() ?>"
        data-select2-id="fservis_sherbimegrid_x<?= $Grid->RowIndex ?>_servisSherbimSherbimi"
        data-table="servis_sherbime"
        data-field="x_servisSherbimSherbimi"
        data-value-separator="<?= $Grid->servisSherbimSherbimi->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->servisSherbimSherbimi->getPlaceHolder()) ?>"
        <?= $Grid->servisSherbimSherbimi->editAttributes() ?>>
        <?= $Grid->servisSherbimSherbimi->selectOptionListHtml("x{$Grid->RowIndex}_servisSherbimSherbimi") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->servisSherbimSherbimi->getErrorMessage() ?></div>
<?= $Grid->servisSherbimSherbimi->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_servisSherbimSherbimi") ?>
<script>
loadjs.ready("fservis_sherbimegrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", selectId: "fservis_sherbimegrid_x<?= $Grid->RowIndex ?>_servisSherbimSherbimi" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservis_sherbimegrid.lists.servisSherbimSherbimi.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", form: "fservis_sherbimegrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_servisSherbimSherbimi", form: "fservis_sherbimegrid", limit: ew.LOOKUP_PAGE_SIZE };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis_sherbime.fields.servisSherbimSherbimi.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimSherbimi" class="el_servis_sherbime_servisSherbimSherbimi">
<span<?= $Grid->servisSherbimSherbimi->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->servisSherbimSherbimi->getDisplayValue($Grid->servisSherbimSherbimi->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimSherbimi" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisSherbimSherbimi" id="x<?= $Grid->RowIndex ?>_servisSherbimSherbimi" value="<?= HtmlEncode($Grid->servisSherbimSherbimi->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimSherbimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimSherbimi" id="o<?= $Grid->RowIndex ?>_servisSherbimSherbimi" value="<?= HtmlEncode($Grid->servisSherbimSherbimi->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <td data-name="servisSherbimCmimi">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimCmimi" class="el_servis_sherbime_servisSherbimCmimi">
<input type="<?= $Grid->servisSherbimCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimCmimi" id="x<?= $Grid->RowIndex ?>_servisSherbimCmimi" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" value="<?= $Grid->servisSherbimCmimi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisSherbimCmimi->getPlaceHolder()) ?>"<?= $Grid->servisSherbimCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimCmimi->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimCmimi" class="el_servis_sherbime_servisSherbimCmimi">
<span<?= $Grid->servisSherbimCmimi->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisSherbimCmimi->getDisplayValue($Grid->servisSherbimCmimi->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisSherbimCmimi" id="x<?= $Grid->RowIndex ?>_servisSherbimCmimi" value="<?= HtmlEncode($Grid->servisSherbimCmimi->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimCmimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimCmimi" id="o<?= $Grid->RowIndex ?>_servisSherbimCmimi" value="<?= HtmlEncode($Grid->servisSherbimCmimi->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <td data-name="servisSherbimShenim">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimShenim" class="el_servis_sherbime_servisSherbimShenim">
<input type="<?= $Grid->servisSherbimShenim->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisSherbimShenim" id="x<?= $Grid->RowIndex ?>_servisSherbimShenim" data-table="servis_sherbime" data-field="x_servisSherbimShenim" value="<?= $Grid->servisSherbimShenim->EditValue ?>" size="30" maxlength="250" placeholder="<?= HtmlEncode($Grid->servisSherbimShenim->getPlaceHolder()) ?>"<?= $Grid->servisSherbimShenim->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisSherbimShenim->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_servis_sherbime_servisSherbimShenim" class="el_servis_sherbime_servisSherbimShenim">
<span<?= $Grid->servisSherbimShenim->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisSherbimShenim->getDisplayValue($Grid->servisSherbimShenim->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimShenim" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisSherbimShenim" id="x<?= $Grid->RowIndex ?>_servisSherbimShenim" value="<?= HtmlEncode($Grid->servisSherbimShenim->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_sherbime" data-field="x_servisSherbimShenim" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisSherbimShenim" id="o<?= $Grid->RowIndex ?>_servisSherbimShenim" value="<?= HtmlEncode($Grid->servisSherbimShenim->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fservis_sherbimegrid","load"], () => fservis_sherbimegrid.updateLists(<?= $Grid->RowIndex ?>, true));
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
    <?php if ($Grid->servisSherbimID->Visible) { // servisSherbimID ?>
        <td data-name="servisSherbimID" class="<?= $Grid->servisSherbimID->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimID" class="servis_sherbime_servisSherbimID">
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisSherbimServisID->Visible) { // servisSherbimServisID ?>
        <td data-name="servisSherbimServisID" class="<?= $Grid->servisSherbimServisID->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimServisID" class="servis_sherbime_servisSherbimServisID">
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisSherbimSherbimi->Visible) { // servisSherbimSherbimi ?>
        <td data-name="servisSherbimSherbimi" class="<?= $Grid->servisSherbimSherbimi->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimSherbimi" class="servis_sherbime_servisSherbimSherbimi">
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisSherbimCmimi->Visible) { // servisSherbimCmimi ?>
        <td data-name="servisSherbimCmimi" class="<?= $Grid->servisSherbimCmimi->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimCmimi" class="servis_sherbime_servisSherbimCmimi">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->servisSherbimCmimi->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisSherbimShenim->Visible) { // servisSherbimShenim ?>
        <td data-name="servisSherbimShenim" class="<?= $Grid->servisSherbimShenim->footerCellClass() ?>"><span id="elf_servis_sherbime_servisSherbimShenim" class="servis_sherbime_servisSherbimShenim">
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
<input type="hidden" name="detailpage" value="fservis_sherbimegrid">
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
    ew.addEventHandlers("servis_sherbime");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $("a[class='btn btn-default ew-add-edit ew-add-blank-row']").append(' SHTO RRJESHT TJETER ');
});
</script>
<?php } ?>
