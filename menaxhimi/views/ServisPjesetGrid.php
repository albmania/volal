<?php

namespace PHPMaker2022\volalservice;

// Set up and run Grid object
$Grid = Container("ServisPjesetGrid");
$Grid->run();
?>
<?php if (!$Grid->isExport()) { ?>
<script>
var fservis_pjesetgrid;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservis_pjesetgrid = new ew.Form("fservis_pjesetgrid", "grid");
    fservis_pjesetgrid.formKeyCountName = "<?= $Grid->FormKeyCountName ?>";

    // Add fields
    var currentTable = <?= JsonEncode($Grid->toClientVar()) ?>;
    ew.deepAssign(ew.vars, { tables: { servis_pjeset: currentTable } });
    var fields = currentTable.fields;
    fservis_pjesetgrid.addFields([
        ["servisPjeseID", [fields.servisPjeseID.visible && fields.servisPjeseID.required ? ew.Validators.required(fields.servisPjeseID.caption) : null], fields.servisPjeseID.isInvalid],
        ["servisPjeseServisID", [fields.servisPjeseServisID.visible && fields.servisPjeseServisID.required ? ew.Validators.required(fields.servisPjeseServisID.caption) : null, ew.Validators.integer], fields.servisPjeseServisID.isInvalid],
        ["servisPjesePjesa", [fields.servisPjesePjesa.visible && fields.servisPjesePjesa.required ? ew.Validators.required(fields.servisPjesePjesa.caption) : null], fields.servisPjesePjesa.isInvalid],
        ["servisPjeseSasia", [fields.servisPjeseSasia.visible && fields.servisPjeseSasia.required ? ew.Validators.required(fields.servisPjeseSasia.caption) : null, ew.Validators.integer], fields.servisPjeseSasia.isInvalid],
        ["servisPjeseCmimi", [fields.servisPjeseCmimi.visible && fields.servisPjeseCmimi.required ? ew.Validators.required(fields.servisPjeseCmimi.caption) : null, ew.Validators.float], fields.servisPjeseCmimi.isInvalid],
        ["servisPjeseShenim", [fields.servisPjeseShenim.visible && fields.servisPjeseShenim.required ? ew.Validators.required(fields.servisPjeseShenim.caption) : null], fields.servisPjeseShenim.isInvalid]
    ]);

    // Check empty row
    fservis_pjesetgrid.emptyRow = function (rowIndex) {
        var fobj = this.getForm(),
            fields = [["servisPjeseServisID",false],["servisPjesePjesa",false],["servisPjeseSasia",false],["servisPjeseCmimi",false],["servisPjeseShenim",false]];
        if (fields.some(field => ew.valueChanged(fobj, rowIndex, ...field)))
            return false;
        return true;
    }

    // Form_CustomValidate
    fservis_pjesetgrid.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fservis_pjesetgrid.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fservis_pjesetgrid.lists.servisPjesePjesa = <?= $Grid->servisPjesePjesa->toClientList($Grid) ?>;
    loadjs.done("fservis_pjesetgrid");
});
</script>
<?php } ?>
<?php
$Grid->renderOtherOptions();
?>
<?php if ($Grid->TotalRecords > 0 || $Grid->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Grid->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> servis_pjeset">
<?php if ($Grid->ShowOtherOptions) { ?>
<div class="card-header ew-grid-upper-panel">
<?php $Grid->OtherOptions->render("body") ?>
</div>
<?php } ?>
<div id="fservis_pjesetgrid" class="ew-form ew-list-form">
<div id="gmp_servis_pjeset" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<table id="tbl_servis_pjesetgrid" class="table table-sm ew-table"><!-- .ew-table -->
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
<?php if ($Grid->servisPjeseID->Visible) { // servisPjeseID ?>
        <th data-name="servisPjeseID" class="<?= $Grid->servisPjeseID->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseID" class="servis_pjeset_servisPjeseID"><?= $Grid->renderFieldHeader($Grid->servisPjeseID) ?></div></th>
<?php } ?>
<?php if ($Grid->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <th data-name="servisPjeseServisID" class="<?= $Grid->servisPjeseServisID->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseServisID" class="servis_pjeset_servisPjeseServisID"><?= $Grid->renderFieldHeader($Grid->servisPjeseServisID) ?></div></th>
<?php } ?>
<?php if ($Grid->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <th data-name="servisPjesePjesa" class="<?= $Grid->servisPjesePjesa->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjesePjesa" class="servis_pjeset_servisPjesePjesa"><?= $Grid->renderFieldHeader($Grid->servisPjesePjesa) ?></div></th>
<?php } ?>
<?php if ($Grid->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <th data-name="servisPjeseSasia" class="<?= $Grid->servisPjeseSasia->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseSasia" class="servis_pjeset_servisPjeseSasia"><?= $Grid->renderFieldHeader($Grid->servisPjeseSasia) ?></div></th>
<?php } ?>
<?php if ($Grid->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <th data-name="servisPjeseCmimi" class="<?= $Grid->servisPjeseCmimi->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseCmimi" class="servis_pjeset_servisPjeseCmimi"><?= $Grid->renderFieldHeader($Grid->servisPjeseCmimi) ?></div></th>
<?php } ?>
<?php if ($Grid->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <th data-name="servisPjeseShenim" class="<?= $Grid->servisPjeseShenim->headerCellClass() ?>"><div id="elh_servis_pjeset_servisPjeseShenim" class="servis_pjeset_servisPjeseShenim"><?= $Grid->renderFieldHeader($Grid->servisPjeseShenim) ?></div></th>
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
            "id" => "r" . $Grid->RowCount . "_servis_pjeset",
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
    <?php if ($Grid->servisPjeseID->Visible) { // servisPjeseID ?>
        <td data-name="servisPjeseID"<?= $Grid->servisPjeseID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseID" class="el_servis_pjeset_servisPjeseID"></span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseID" id="o<?= $Grid->RowIndex ?>_servisPjeseID" value="<?= HtmlEncode($Grid->servisPjeseID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseID" class="el_servis_pjeset_servisPjeseID">
<span<?= $Grid->servisPjeseID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseID->getDisplayValue($Grid->servisPjeseID->EditValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisPjeseID" id="x<?= $Grid->RowIndex ?>_servisPjeseID" value="<?= HtmlEncode($Grid->servisPjeseID->CurrentValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseID" class="el_servis_pjeset_servisPjeseID">
<span<?= $Grid->servisPjeseID->viewAttributes() ?>>
<?= $Grid->servisPjeseID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseID" data-hidden="1" name="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseID" id="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseID" value="<?= HtmlEncode($Grid->servisPjeseID->FormValue) ?>">
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseID" data-hidden="1" name="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseID" id="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseID" value="<?= HtmlEncode($Grid->servisPjeseID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } else { ?>
            <input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisPjeseID" id="x<?= $Grid->RowIndex ?>_servisPjeseID" value="<?= HtmlEncode($Grid->servisPjeseID->CurrentValue) ?>">
    <?php } ?>
    <?php if ($Grid->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <td data-name="servisPjeseServisID"<?= $Grid->servisPjeseServisID->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<?php if ($Grid->servisPjeseServisID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<span<?= $Grid->servisPjeseServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseServisID->getDisplayValue($Grid->servisPjeseServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_servisPjeseServisID" name="x<?= $Grid->RowIndex ?>_servisPjeseServisID" value="<?= HtmlEncode(FormatNumber($Grid->servisPjeseServisID->CurrentValue, $Grid->servisPjeseServisID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<input type="<?= $Grid->servisPjeseServisID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseServisID" id="x<?= $Grid->RowIndex ?>_servisPjeseServisID" data-table="servis_pjeset" data-field="x_servisPjeseServisID" value="<?= $Grid->servisPjeseServisID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisPjeseServisID->getPlaceHolder()) ?>"<?= $Grid->servisPjeseServisID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseServisID->getErrorMessage() ?></div>
</span>
<?php } ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseServisID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseServisID" id="o<?= $Grid->RowIndex ?>_servisPjeseServisID" value="<?= HtmlEncode($Grid->servisPjeseServisID->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<?php if ($Grid->servisPjeseServisID->getSessionValue() != "") { ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<span<?= $Grid->servisPjeseServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseServisID->getDisplayValue($Grid->servisPjeseServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_servisPjeseServisID" name="x<?= $Grid->RowIndex ?>_servisPjeseServisID" value="<?= HtmlEncode(FormatNumber($Grid->servisPjeseServisID->CurrentValue, $Grid->servisPjeseServisID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<input type="<?= $Grid->servisPjeseServisID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseServisID" id="x<?= $Grid->RowIndex ?>_servisPjeseServisID" data-table="servis_pjeset" data-field="x_servisPjeseServisID" value="<?= $Grid->servisPjeseServisID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisPjeseServisID->getPlaceHolder()) ?>"<?= $Grid->servisPjeseServisID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseServisID->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<span<?= $Grid->servisPjeseServisID->viewAttributes() ?>>
<?= $Grid->servisPjeseServisID->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseServisID" data-hidden="1" name="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseServisID" id="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseServisID" value="<?= HtmlEncode($Grid->servisPjeseServisID->FormValue) ?>">
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseServisID" data-hidden="1" name="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseServisID" id="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseServisID" value="<?= HtmlEncode($Grid->servisPjeseServisID->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <td data-name="servisPjesePjesa"<?= $Grid->servisPjesePjesa->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjesePjesa" class="el_servis_pjeset_servisPjesePjesa">
    <select
        id="x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        name="x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        class="form-select ew-select<?= $Grid->servisPjesePjesa->isInvalidClass() ?>"
        data-select2-id="fservis_pjesetgrid_x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        data-table="servis_pjeset"
        data-field="x_servisPjesePjesa"
        data-value-separator="<?= $Grid->servisPjesePjesa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->servisPjesePjesa->getPlaceHolder()) ?>"
        <?= $Grid->servisPjesePjesa->editAttributes() ?>>
        <?= $Grid->servisPjesePjesa->selectOptionListHtml("x{$Grid->RowIndex}_servisPjesePjesa") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->servisPjesePjesa->getErrorMessage() ?></div>
<?= $Grid->servisPjesePjesa->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_servisPjesePjesa") ?>
<script>
loadjs.ready("fservis_pjesetgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", selectId: "fservis_pjesetgrid_x<?= $Grid->RowIndex ?>_servisPjesePjesa" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservis_pjesetgrid.lists.servisPjesePjesa.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", form: "fservis_pjesetgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", form: "fservis_pjesetgrid", limit: 20 };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis_pjeset.fields.servisPjesePjesa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjesePjesa" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjesePjesa" id="o<?= $Grid->RowIndex ?>_servisPjesePjesa" value="<?= HtmlEncode($Grid->servisPjesePjesa->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjesePjesa" class="el_servis_pjeset_servisPjesePjesa">
    <select
        id="x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        name="x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        class="form-select ew-select<?= $Grid->servisPjesePjesa->isInvalidClass() ?>"
        data-select2-id="fservis_pjesetgrid_x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        data-table="servis_pjeset"
        data-field="x_servisPjesePjesa"
        data-value-separator="<?= $Grid->servisPjesePjesa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->servisPjesePjesa->getPlaceHolder()) ?>"
        <?= $Grid->servisPjesePjesa->editAttributes() ?>>
        <?= $Grid->servisPjesePjesa->selectOptionListHtml("x{$Grid->RowIndex}_servisPjesePjesa") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->servisPjesePjesa->getErrorMessage() ?></div>
<?= $Grid->servisPjesePjesa->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_servisPjesePjesa") ?>
<script>
loadjs.ready("fservis_pjesetgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", selectId: "fservis_pjesetgrid_x<?= $Grid->RowIndex ?>_servisPjesePjesa" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservis_pjesetgrid.lists.servisPjesePjesa.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", form: "fservis_pjesetgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", form: "fservis_pjesetgrid", limit: 20 };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis_pjeset.fields.servisPjesePjesa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjesePjesa" class="el_servis_pjeset_servisPjesePjesa">
<span<?= $Grid->servisPjesePjesa->viewAttributes() ?>>
<?= $Grid->servisPjesePjesa->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjesePjesa" data-hidden="1" name="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjesePjesa" id="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjesePjesa" value="<?= HtmlEncode($Grid->servisPjesePjesa->FormValue) ?>">
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjesePjesa" data-hidden="1" name="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjesePjesa" id="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjesePjesa" value="<?= HtmlEncode($Grid->servisPjesePjesa->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <td data-name="servisPjeseSasia"<?= $Grid->servisPjeseSasia->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseSasia" class="el_servis_pjeset_servisPjeseSasia">
<input type="<?= $Grid->servisPjeseSasia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseSasia" id="x<?= $Grid->RowIndex ?>_servisPjeseSasia" data-table="servis_pjeset" data-field="x_servisPjeseSasia" value="<?= $Grid->servisPjeseSasia->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->servisPjeseSasia->getPlaceHolder()) ?>"<?= $Grid->servisPjeseSasia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseSasia->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseSasia" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseSasia" id="o<?= $Grid->RowIndex ?>_servisPjeseSasia" value="<?= HtmlEncode($Grid->servisPjeseSasia->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseSasia" class="el_servis_pjeset_servisPjeseSasia">
<input type="<?= $Grid->servisPjeseSasia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseSasia" id="x<?= $Grid->RowIndex ?>_servisPjeseSasia" data-table="servis_pjeset" data-field="x_servisPjeseSasia" value="<?= $Grid->servisPjeseSasia->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->servisPjeseSasia->getPlaceHolder()) ?>"<?= $Grid->servisPjeseSasia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseSasia->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseSasia" class="el_servis_pjeset_servisPjeseSasia">
<span<?= $Grid->servisPjeseSasia->viewAttributes() ?>>
<?= $Grid->servisPjeseSasia->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseSasia" data-hidden="1" name="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseSasia" id="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseSasia" value="<?= HtmlEncode($Grid->servisPjeseSasia->FormValue) ?>">
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseSasia" data-hidden="1" name="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseSasia" id="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseSasia" value="<?= HtmlEncode($Grid->servisPjeseSasia->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <td data-name="servisPjeseCmimi"<?= $Grid->servisPjeseCmimi->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseCmimi" class="el_servis_pjeset_servisPjeseCmimi">
<input type="<?= $Grid->servisPjeseCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseCmimi" id="x<?= $Grid->RowIndex ?>_servisPjeseCmimi" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" value="<?= $Grid->servisPjeseCmimi->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->servisPjeseCmimi->getPlaceHolder()) ?>"<?= $Grid->servisPjeseCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseCmimi->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseCmimi" id="o<?= $Grid->RowIndex ?>_servisPjeseCmimi" value="<?= HtmlEncode($Grid->servisPjeseCmimi->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseCmimi" class="el_servis_pjeset_servisPjeseCmimi">
<input type="<?= $Grid->servisPjeseCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseCmimi" id="x<?= $Grid->RowIndex ?>_servisPjeseCmimi" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" value="<?= $Grid->servisPjeseCmimi->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->servisPjeseCmimi->getPlaceHolder()) ?>"<?= $Grid->servisPjeseCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseCmimi->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseCmimi" class="el_servis_pjeset_servisPjeseCmimi">
<span<?= $Grid->servisPjeseCmimi->viewAttributes() ?>>
<?= $Grid->servisPjeseCmimi->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" data-hidden="1" name="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseCmimi" id="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseCmimi" value="<?= HtmlEncode($Grid->servisPjeseCmimi->FormValue) ?>">
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" data-hidden="1" name="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseCmimi" id="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseCmimi" value="<?= HtmlEncode($Grid->servisPjeseCmimi->OldValue) ?>">
<?php } ?>
<?php } ?>
</td>
    <?php } ?>
    <?php if ($Grid->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <td data-name="servisPjeseShenim"<?= $Grid->servisPjeseShenim->cellAttributes() ?>>
<?php if ($Grid->RowType == ROWTYPE_ADD) { // Add record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseShenim" class="el_servis_pjeset_servisPjeseShenim">
<input type="<?= $Grid->servisPjeseShenim->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseShenim" id="x<?= $Grid->RowIndex ?>_servisPjeseShenim" data-table="servis_pjeset" data-field="x_servisPjeseShenim" value="<?= $Grid->servisPjeseShenim->EditValue ?>" size="20" maxlength="250" placeholder="<?= HtmlEncode($Grid->servisPjeseShenim->getPlaceHolder()) ?>"<?= $Grid->servisPjeseShenim->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseShenim->getErrorMessage() ?></div>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseShenim" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseShenim" id="o<?= $Grid->RowIndex ?>_servisPjeseShenim" value="<?= HtmlEncode($Grid->servisPjeseShenim->OldValue) ?>">
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_EDIT) { // Edit record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseShenim" class="el_servis_pjeset_servisPjeseShenim">
<input type="<?= $Grid->servisPjeseShenim->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseShenim" id="x<?= $Grid->RowIndex ?>_servisPjeseShenim" data-table="servis_pjeset" data-field="x_servisPjeseShenim" value="<?= $Grid->servisPjeseShenim->EditValue ?>" size="20" maxlength="250" placeholder="<?= HtmlEncode($Grid->servisPjeseShenim->getPlaceHolder()) ?>"<?= $Grid->servisPjeseShenim->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseShenim->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php if ($Grid->RowType == ROWTYPE_VIEW) { // View record ?>
<span id="el<?= $Grid->RowCount ?>_servis_pjeset_servisPjeseShenim" class="el_servis_pjeset_servisPjeseShenim">
<span<?= $Grid->servisPjeseShenim->viewAttributes() ?>>
<?= $Grid->servisPjeseShenim->getViewValue() ?></span>
</span>
<?php if ($Grid->isConfirm()) { ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseShenim" data-hidden="1" name="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseShenim" id="fservis_pjesetgrid$x<?= $Grid->RowIndex ?>_servisPjeseShenim" value="<?= HtmlEncode($Grid->servisPjeseShenim->FormValue) ?>">
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseShenim" data-hidden="1" name="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseShenim" id="fservis_pjesetgrid$o<?= $Grid->RowIndex ?>_servisPjeseShenim" value="<?= HtmlEncode($Grid->servisPjeseShenim->OldValue) ?>">
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
loadjs.ready(["fservis_pjesetgrid","load"], () => fservis_pjesetgrid.updateLists(<?= $Grid->RowIndex ?>));
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
    $Grid->RowAttrs->merge(["data-rowindex" => $Grid->RowIndex, "id" => "r0_servis_pjeset", "data-rowtype" => ROWTYPE_ADD]);
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
    <?php if ($Grid->servisPjeseID->Visible) { // servisPjeseID ?>
        <td data-name="servisPjeseID">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseID" class="el_servis_pjeset_servisPjeseID"></span>
<?php } else { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseID" class="el_servis_pjeset_servisPjeseID">
<span<?= $Grid->servisPjeseID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseID->getDisplayValue($Grid->servisPjeseID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisPjeseID" id="x<?= $Grid->RowIndex ?>_servisPjeseID" value="<?= HtmlEncode($Grid->servisPjeseID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseID" id="o<?= $Grid->RowIndex ?>_servisPjeseID" value="<?= HtmlEncode($Grid->servisPjeseID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <td data-name="servisPjeseServisID">
<?php if (!$Grid->isConfirm()) { ?>
<?php if ($Grid->servisPjeseServisID->getSessionValue() != "") { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<span<?= $Grid->servisPjeseServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseServisID->getDisplayValue($Grid->servisPjeseServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" id="x<?= $Grid->RowIndex ?>_servisPjeseServisID" name="x<?= $Grid->RowIndex ?>_servisPjeseServisID" value="<?= HtmlEncode(FormatNumber($Grid->servisPjeseServisID->CurrentValue, $Grid->servisPjeseServisID->formatPattern())) ?>" data-hidden="1">
<?php } else { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<input type="<?= $Grid->servisPjeseServisID->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseServisID" id="x<?= $Grid->RowIndex ?>_servisPjeseServisID" data-table="servis_pjeset" data-field="x_servisPjeseServisID" value="<?= $Grid->servisPjeseServisID->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Grid->servisPjeseServisID->getPlaceHolder()) ?>"<?= $Grid->servisPjeseServisID->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseServisID->getErrorMessage() ?></div>
</span>
<?php } ?>
<?php } else { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseServisID" class="el_servis_pjeset_servisPjeseServisID">
<span<?= $Grid->servisPjeseServisID->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseServisID->getDisplayValue($Grid->servisPjeseServisID->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseServisID" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisPjeseServisID" id="x<?= $Grid->RowIndex ?>_servisPjeseServisID" value="<?= HtmlEncode($Grid->servisPjeseServisID->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseServisID" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseServisID" id="o<?= $Grid->RowIndex ?>_servisPjeseServisID" value="<?= HtmlEncode($Grid->servisPjeseServisID->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <td data-name="servisPjesePjesa">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_pjeset_servisPjesePjesa" class="el_servis_pjeset_servisPjesePjesa">
    <select
        id="x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        name="x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        class="form-select ew-select<?= $Grid->servisPjesePjesa->isInvalidClass() ?>"
        data-select2-id="fservis_pjesetgrid_x<?= $Grid->RowIndex ?>_servisPjesePjesa"
        data-table="servis_pjeset"
        data-field="x_servisPjesePjesa"
        data-value-separator="<?= $Grid->servisPjesePjesa->displayValueSeparatorAttribute() ?>"
        data-placeholder="<?= HtmlEncode($Grid->servisPjesePjesa->getPlaceHolder()) ?>"
        <?= $Grid->servisPjesePjesa->editAttributes() ?>>
        <?= $Grid->servisPjesePjesa->selectOptionListHtml("x{$Grid->RowIndex}_servisPjesePjesa") ?>
    </select>
    <div class="invalid-feedback"><?= $Grid->servisPjesePjesa->getErrorMessage() ?></div>
<?= $Grid->servisPjesePjesa->Lookup->getParamTag($Grid, "p_x" . $Grid->RowIndex . "_servisPjesePjesa") ?>
<script>
loadjs.ready("fservis_pjesetgrid", function() {
    var options = { name: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", selectId: "fservis_pjesetgrid_x<?= $Grid->RowIndex ?>_servisPjesePjesa" },
        el = document.querySelector("select[data-select2-id='" + options.selectId + "']");
    options.dropdownParent = el.closest("#ew-modal-dialog, #ew-add-opt-dialog");
    if (fservis_pjesetgrid.lists.servisPjesePjesa.lookupOptions.length) {
        options.data = { id: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", form: "fservis_pjesetgrid" };
    } else {
        options.ajax = { id: "x<?= $Grid->RowIndex ?>_servisPjesePjesa", form: "fservis_pjesetgrid", limit: 20 };
    }
    options.minimumInputLength = ew.selectMinimumInputLength;
    options = Object.assign({}, ew.selectOptions, options, ew.vars.tables.servis_pjeset.fields.servisPjesePjesa.selectOptions);
    ew.createSelect(options);
});
</script>
</span>
<?php } else { ?>
<span id="el$rowindex$_servis_pjeset_servisPjesePjesa" class="el_servis_pjeset_servisPjesePjesa">
<span<?= $Grid->servisPjesePjesa->viewAttributes() ?>>
<span class="form-control-plaintext"><?= $Grid->servisPjesePjesa->getDisplayValue($Grid->servisPjesePjesa->ViewValue) ?></span></span>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjesePjesa" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisPjesePjesa" id="x<?= $Grid->RowIndex ?>_servisPjesePjesa" value="<?= HtmlEncode($Grid->servisPjesePjesa->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjesePjesa" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjesePjesa" id="o<?= $Grid->RowIndex ?>_servisPjesePjesa" value="<?= HtmlEncode($Grid->servisPjesePjesa->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <td data-name="servisPjeseSasia">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseSasia" class="el_servis_pjeset_servisPjeseSasia">
<input type="<?= $Grid->servisPjeseSasia->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseSasia" id="x<?= $Grid->RowIndex ?>_servisPjeseSasia" data-table="servis_pjeset" data-field="x_servisPjeseSasia" value="<?= $Grid->servisPjeseSasia->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->servisPjeseSasia->getPlaceHolder()) ?>"<?= $Grid->servisPjeseSasia->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseSasia->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseSasia" class="el_servis_pjeset_servisPjeseSasia">
<span<?= $Grid->servisPjeseSasia->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseSasia->getDisplayValue($Grid->servisPjeseSasia->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseSasia" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisPjeseSasia" id="x<?= $Grid->RowIndex ?>_servisPjeseSasia" value="<?= HtmlEncode($Grid->servisPjeseSasia->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseSasia" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseSasia" id="o<?= $Grid->RowIndex ?>_servisPjeseSasia" value="<?= HtmlEncode($Grid->servisPjeseSasia->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <td data-name="servisPjeseCmimi">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseCmimi" class="el_servis_pjeset_servisPjeseCmimi">
<input type="<?= $Grid->servisPjeseCmimi->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseCmimi" id="x<?= $Grid->RowIndex ?>_servisPjeseCmimi" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" value="<?= $Grid->servisPjeseCmimi->EditValue ?>" size="10" placeholder="<?= HtmlEncode($Grid->servisPjeseCmimi->getPlaceHolder()) ?>"<?= $Grid->servisPjeseCmimi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseCmimi->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseCmimi" class="el_servis_pjeset_servisPjeseCmimi">
<span<?= $Grid->servisPjeseCmimi->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseCmimi->getDisplayValue($Grid->servisPjeseCmimi->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisPjeseCmimi" id="x<?= $Grid->RowIndex ?>_servisPjeseCmimi" value="<?= HtmlEncode($Grid->servisPjeseCmimi->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseCmimi" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseCmimi" id="o<?= $Grid->RowIndex ?>_servisPjeseCmimi" value="<?= HtmlEncode($Grid->servisPjeseCmimi->OldValue) ?>">
</td>
    <?php } ?>
    <?php if ($Grid->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <td data-name="servisPjeseShenim">
<?php if (!$Grid->isConfirm()) { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseShenim" class="el_servis_pjeset_servisPjeseShenim">
<input type="<?= $Grid->servisPjeseShenim->getInputTextType() ?>" name="x<?= $Grid->RowIndex ?>_servisPjeseShenim" id="x<?= $Grid->RowIndex ?>_servisPjeseShenim" data-table="servis_pjeset" data-field="x_servisPjeseShenim" value="<?= $Grid->servisPjeseShenim->EditValue ?>" size="20" maxlength="250" placeholder="<?= HtmlEncode($Grid->servisPjeseShenim->getPlaceHolder()) ?>"<?= $Grid->servisPjeseShenim->editAttributes() ?>>
<div class="invalid-feedback"><?= $Grid->servisPjeseShenim->getErrorMessage() ?></div>
</span>
<?php } else { ?>
<span id="el$rowindex$_servis_pjeset_servisPjeseShenim" class="el_servis_pjeset_servisPjeseShenim">
<span<?= $Grid->servisPjeseShenim->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= HtmlEncode(RemoveHtml($Grid->servisPjeseShenim->getDisplayValue($Grid->servisPjeseShenim->ViewValue))) ?>"></span>
</span>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseShenim" data-hidden="1" name="x<?= $Grid->RowIndex ?>_servisPjeseShenim" id="x<?= $Grid->RowIndex ?>_servisPjeseShenim" value="<?= HtmlEncode($Grid->servisPjeseShenim->FormValue) ?>">
<?php } ?>
<input type="hidden" data-table="servis_pjeset" data-field="x_servisPjeseShenim" data-hidden="1" name="o<?= $Grid->RowIndex ?>_servisPjeseShenim" id="o<?= $Grid->RowIndex ?>_servisPjeseShenim" value="<?= HtmlEncode($Grid->servisPjeseShenim->OldValue) ?>">
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Grid->ListOptions->render("body", "right", $Grid->RowIndex);
?>
<script>
loadjs.ready(["fservis_pjesetgrid","load"], () => fservis_pjesetgrid.updateLists(<?= $Grid->RowIndex ?>, true));
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
    <?php if ($Grid->servisPjeseID->Visible) { // servisPjeseID ?>
        <td data-name="servisPjeseID" class="<?= $Grid->servisPjeseID->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseID" class="servis_pjeset_servisPjeseID">
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisPjeseServisID->Visible) { // servisPjeseServisID ?>
        <td data-name="servisPjeseServisID" class="<?= $Grid->servisPjeseServisID->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseServisID" class="servis_pjeset_servisPjeseServisID">
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisPjesePjesa->Visible) { // servisPjesePjesa ?>
        <td data-name="servisPjesePjesa" class="<?= $Grid->servisPjesePjesa->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjesePjesa" class="servis_pjeset_servisPjesePjesa">
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisPjeseSasia->Visible) { // servisPjeseSasia ?>
        <td data-name="servisPjeseSasia" class="<?= $Grid->servisPjeseSasia->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseSasia" class="servis_pjeset_servisPjeseSasia">
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisPjeseCmimi->Visible) { // servisPjeseCmimi ?>
        <td data-name="servisPjeseCmimi" class="<?= $Grid->servisPjeseCmimi->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseCmimi" class="servis_pjeset_servisPjeseCmimi">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Grid->servisPjeseCmimi->ViewValue ?></span>
        </span></td>
    <?php } ?>
    <?php if ($Grid->servisPjeseShenim->Visible) { // servisPjeseShenim ?>
        <td data-name="servisPjeseShenim" class="<?= $Grid->servisPjeseShenim->footerCellClass() ?>"><span id="elf_servis_pjeset_servisPjeseShenim" class="servis_pjeset_servisPjeseShenim">
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
<input type="hidden" name="detailpage" value="fservis_pjesetgrid">
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
    ew.addEventHandlers("servis_pjeset");
});
</script>
<script>
loadjs.ready("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
