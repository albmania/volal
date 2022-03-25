<?php

namespace PHPMaker2022\volalservice;

// Page object
$ServisList = &$Page;
?>
<?php if (!$Page->isExport()) { ?>
<script>
var currentTable = <?= JsonEncode($Page->toClientVar()) ?>;
ew.deepAssign(ew.vars, { tables: { servis: currentTable } });
var currentForm, currentPageID;
var fservislist;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object
    fservislist = new ew.Form("fservislist", "list");
    currentPageID = ew.PAGE_ID = "list";
    currentForm = fservislist;
    fservislist.formKeyCountName = "<?= $Page->FormKeyCountName ?>";
    loadjs.done("fservislist");
});
var fservissrch, currentSearchForm, currentAdvancedSearchForm;
loadjs.ready(["wrapper", "head"], function () {
    var $ = jQuery;
    // Form object for search
    fservissrch = new ew.Form("fservissrch", "list");
    currentSearchForm = fservissrch;

    // Add fields
    var fields = currentTable.fields;
    fservissrch.addFields([
        ["servisID", [], fields.servisID.isInvalid],
        ["servisDate", [ew.Validators.datetime(fields.servisDate.clientFormatPattern)], fields.servisDate.isInvalid],
        ["y_servisDate", [ew.Validators.between], false],
        ["servisKlienti", [], fields.servisKlienti.isInvalid],
        ["y_servisKlienti", [ew.Validators.between], false],
        ["servisMakina", [], fields.servisMakina.isInvalid],
        ["y_servisMakina", [ew.Validators.between], false],
        ["servisKmMakines", [], fields.servisKmMakines.isInvalid],
        ["servisStafi", [], fields.servisStafi.isInvalid],
        ["y_servisStafi", [ew.Validators.between], false],
        ["servisTotaliFatures", [], fields.servisTotaliFatures.isInvalid]
    ]);

    // Validate form
    fservissrch.validate = function () {
        if (!this.validateRequired)
            return true; // Ignore validation
        var fobj = this.getForm();

        // Validate fields
        if (!this.validateFields())
            return false;

        // Call Form_CustomValidate event
        if (!this.customValidate(fobj)) {
            this.focus();
            return false;
        }
        return true;
    }

    // Form_CustomValidate
    fservissrch.customValidate = function(fobj) { // DO NOT CHANGE THIS LINE!
        // Your custom validation code here, return false if invalid.
        return true;
    }

    // Use JavaScript validation or not
    fservissrch.validateRequired = ew.CLIENT_VALIDATE;

    // Dynamic selection lists
    fservissrch.lists.servisKlienti = <?= $Page->servisKlienti->toClientList($Page) ?>;
    fservissrch.lists.servisMakina = <?= $Page->servisMakina->toClientList($Page) ?>;
    fservissrch.lists.servisStafi = <?= $Page->servisStafi->toClientList($Page) ?>;

    // Filters
    fservissrch.filterList = <?= $Page->getFilterList() ?>;

    // Init search panel as collapsed
    fservissrch.initSearchPanel = true;
    loadjs.done("fservissrch");
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
<?php if ($Page->TotalRecords > 0 && $Page->ExportOptions->visible()) { ?>
<?php $Page->ExportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->ImportOptions->visible()) { ?>
<?php $Page->ImportOptions->render("body") ?>
<?php } ?>
<?php if ($Page->SearchOptions->visible()) { ?>
<?php $Page->SearchOptions->render("body") ?>
<?php } ?>
<?php if ($Page->FilterOptions->visible()) { ?>
<?php $Page->FilterOptions->render("body") ?>
<?php } ?>
</div>
<?php } ?>
<?php
$Page->renderOtherOptions();
?>
<?php if ($Security->canSearch()) { ?>
<?php if (!$Page->isExport() && !$Page->CurrentAction && $Page->hasSearchFields()) { ?>
<form name="fservissrch" id="fservissrch" class="ew-form ew-ext-search-form" action="<?= CurrentPageUrl(false) ?>">
<div id="fservissrch_search_panel" class="mb-2 mb-sm-0 <?= $Page->SearchPanelClass ?>"><!-- .ew-search-panel -->
<input type="hidden" name="cmd" value="search">
<input type="hidden" name="t" value="servis">
<div class="ew-extended-search container-fluid">
<div class="row mb-0<?= ($Page->SearchFieldsPerRow > 0) ? " row-cols-sm-" . $Page->SearchFieldsPerRow : "" ?>">
<?php
// Render search row
$Page->RowType = ROWTYPE_SEARCH;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->servisDate->Visible) { // servisDate ?>
<?php
if (!$Page->servisDate->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_servisDate" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->servisDate->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_servisDate" class="ew-search-caption ew-label"><?= $Page->servisDate->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_servisDate" id="z_servisDate" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->servisDate->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->servisDate->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->servisDate->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->servisDate->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->servisDate->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->servisDate->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="BETWEEN"<?= $Page->servisDate->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_servis_servisDate" class="ew-search-field">
<input type="<?= $Page->servisDate->getInputTextType() ?>" name="x_servisDate" id="x_servisDate" data-table="servis" data-field="x_servisDate" value="<?= $Page->servisDate->EditValue ?>" placeholder="<?= HtmlEncode($Page->servisDate->getPlaceHolder()) ?>"<?= $Page->servisDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->servisDate->getErrorMessage(false) ?></div>
<?php if (!$Page->servisDate->ReadOnly && !$Page->servisDate->Disabled && !isset($Page->servisDate->EditAttrs["readonly"]) && !isset($Page->servisDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fservissrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fservissrch", "x_servisDate", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_servis_servisDate" class="ew-search-field2 d-none">
<input type="<?= $Page->servisDate->getInputTextType() ?>" name="y_servisDate" id="y_servisDate" data-table="servis" data-field="x_servisDate" value="<?= $Page->servisDate->EditValue2 ?>" placeholder="<?= HtmlEncode($Page->servisDate->getPlaceHolder()) ?>"<?= $Page->servisDate->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->servisDate->getErrorMessage(false) ?></div>
<?php if (!$Page->servisDate->ReadOnly && !$Page->servisDate->Disabled && !isset($Page->servisDate->EditAttrs["readonly"]) && !isset($Page->servisDate->EditAttrs["disabled"])) { ?>
<script>
loadjs.ready(["fservissrch", "datetimepicker"], function () {
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
    ew.createDateTimePicker("fservissrch", "y_servisDate", jQuery.extend(true, {"useCurrent":false}, options));
});
</script>
<?php } ?>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->servisKlienti->Visible) { // servisKlienti ?>
<?php
if (!$Page->servisKlienti->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_servisKlienti" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->servisKlienti->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_servisKlienti" class="ew-search-caption ew-label"><?= $Page->servisKlienti->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_servisKlienti" id="z_servisKlienti" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->servisKlienti->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_servis_servisKlienti" class="ew-search-field">
<input type="<?= $Page->servisKlienti->getInputTextType() ?>" name="x_servisKlienti" id="x_servisKlienti" data-table="servis" data-field="x_servisKlienti" value="<?= $Page->servisKlienti->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->servisKlienti->getPlaceHolder()) ?>"<?= $Page->servisKlienti->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->servisKlienti->getErrorMessage(false) ?></div>
<?= $Page->servisKlienti->Lookup->getParamTag($Page, "p_x_servisKlienti") ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_servis_servisKlienti" class="ew-search-field2 d-none">
<input type="<?= $Page->servisKlienti->getInputTextType() ?>" name="y_servisKlienti" id="y_servisKlienti" data-table="servis" data-field="x_servisKlienti" value="<?= $Page->servisKlienti->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->servisKlienti->getPlaceHolder()) ?>"<?= $Page->servisKlienti->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->servisKlienti->getErrorMessage(false) ?></div>
<?= $Page->servisKlienti->Lookup->getParamTag($Page, "p_y_servisKlienti") ?>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->servisMakina->Visible) { // servisMakina ?>
<?php
if (!$Page->servisMakina->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_servisMakina" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->servisMakina->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_servisMakina" class="ew-search-caption ew-label"><?= $Page->servisMakina->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_servisMakina" id="z_servisMakina" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->servisMakina->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->servisMakina->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->servisMakina->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_servis_servisMakina" class="ew-search-field">
<input type="<?= $Page->servisMakina->getInputTextType() ?>" name="x_servisMakina" id="x_servisMakina" data-table="servis" data-field="x_servisMakina" value="<?= $Page->servisMakina->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->servisMakina->getPlaceHolder()) ?>"<?= $Page->servisMakina->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->servisMakina->getErrorMessage(false) ?></div>
<?= $Page->servisMakina->Lookup->getParamTag($Page, "p_x_servisMakina") ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_servis_servisMakina" class="ew-search-field2 d-none">
<input type="<?= $Page->servisMakina->getInputTextType() ?>" name="y_servisMakina" id="y_servisMakina" data-table="servis" data-field="x_servisMakina" value="<?= $Page->servisMakina->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->servisMakina->getPlaceHolder()) ?>"<?= $Page->servisMakina->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->servisMakina->getErrorMessage(false) ?></div>
<?= $Page->servisMakina->Lookup->getParamTag($Page, "p_y_servisMakina") ?>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->servisStafi->Visible) { // servisStafi ?>
<?php
if (!$Page->servisStafi->UseFilter) {
    $Page->SearchColumnCount++;
}
?>
    <div id="xs_servisStafi" class="col-sm-auto d-sm-flex mb-3 px-0 pe-sm-2<?= $Page->servisStafi->UseFilter ? " ew-filter-field" : "" ?>">
        <div class="d-flex my-1 my-sm-0">
            <label for="x_servisStafi" class="ew-search-caption ew-label"><?= $Page->servisStafi->caption() ?></label>
            <div class="ew-search-operator">
<select name="z_servisStafi" id="z_servisStafi" class="form-select" onchange="ew.searchOperatorChanged(this);">
<option value="="<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "=" ? " selected" : "" ?>><?= $Language->phrase("EQUAL") ?></option>
<option value="&lt;&gt;"<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "<>" ? " selected" : "" ?>><?= $Language->phrase("<>") ?></option>
<option value="&lt;"<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "<" ? " selected" : "" ?>><?= $Language->phrase("<") ?></option>
<option value="&lt;="<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "<=" ? " selected" : "" ?>><?= $Language->phrase("<=") ?></option>
<option value="&gt;"<?= $Page->servisStafi->AdvancedSearch->SearchOperator == ">" ? " selected" : "" ?>><?= $Language->phrase(">") ?></option>
<option value="&gt;="<?= $Page->servisStafi->AdvancedSearch->SearchOperator == ">=" ? " selected" : "" ?>><?= $Language->phrase(">=") ?></option>
<option value="LIKE"<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "LIKE" ? " selected" : "" ?>><?= $Language->phrase("LIKE") ?></option>
<option value="NOT LIKE"<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "NOT LIKE" ? " selected" : "" ?>><?= $Language->phrase("NOT LIKE") ?></option>
<option value="STARTS WITH"<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "STARTS WITH" ? " selected" : "" ?>><?= $Language->phrase("STARTS WITH") ?></option>
<option value="ENDS WITH"<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "ENDS WITH" ? " selected" : "" ?>><?= $Language->phrase("ENDS WITH") ?></option>
<option value="BETWEEN"<?= $Page->servisStafi->AdvancedSearch->SearchOperator == "BETWEEN" ? " selected" : "" ?>><?= $Language->phrase("BETWEEN") ?></option>
</select>
</div>
        </div>
        <div id="el_servis_servisStafi" class="ew-search-field">
<input type="<?= $Page->servisStafi->getInputTextType() ?>" name="x_servisStafi" id="x_servisStafi" data-table="servis" data-field="x_servisStafi" value="<?= $Page->servisStafi->EditValue ?>" size="30" placeholder="<?= HtmlEncode($Page->servisStafi->getPlaceHolder()) ?>"<?= $Page->servisStafi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->servisStafi->getErrorMessage(false) ?></div>
<?= $Page->servisStafi->Lookup->getParamTag($Page, "p_x_servisStafi") ?>
</div>
        <div class="d-flex my-1 my-sm-0">
            <div class="ew-search-and d-none"><label><?= $Language->phrase("AND") ?></label></div>
        </div><!-- /.ew-search-field -->
        <div id="el2_servis_servisStafi" class="ew-search-field2 d-none">
<input type="<?= $Page->servisStafi->getInputTextType() ?>" name="y_servisStafi" id="y_servisStafi" data-table="servis" data-field="x_servisStafi" value="<?= $Page->servisStafi->EditValue2 ?>" size="30" placeholder="<?= HtmlEncode($Page->servisStafi->getPlaceHolder()) ?>"<?= $Page->servisStafi->editAttributes() ?>>
<div class="invalid-feedback"><?= $Page->servisStafi->getErrorMessage(false) ?></div>
<?= $Page->servisStafi->Lookup->getParamTag($Page, "p_y_servisStafi") ?>
</div>
    </div><!-- /.col-sm-auto -->
<?php } ?>
<?php if ($Page->SearchColumnCount > 0) { ?>
   <div class="col-sm-auto mb-3">
       <button class="btn btn-primary" name="btn-submit" id="btn-submit" type="submit"><?= $Language->phrase("SearchBtn") ?></button>
   </div>
<?php } ?>
</div><!-- /.row -->
</div><!-- /.ew-extended-search -->
</div><!-- /.ew-search-panel -->
</form>
<?php } ?>
<?php } ?>
<?php $Page->showPageHeader(); ?>
<?php
$Page->showMessage();
?>
<?php if ($Page->TotalRecords > 0 || $Page->CurrentAction) { ?>
<div class="card ew-card ew-grid<?php if ($Page->isAddOrEdit()) { ?> ew-grid-add-edit<?php } ?> servis">
<?php if (!$Page->isExport()) { ?>
<div class="card-header ew-grid-upper-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
</div>
<?php } ?>
<form name="fservislist" id="fservislist" class="ew-form ew-list-form" action="<?= CurrentPageUrl(false) ?>" method="post">
<?php if (Config("CHECK_TOKEN")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token name -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="servis">
<div id="gmp_servis" class="<?= ResponsiveTableClass() ?>card-body ew-grid-middle-panel">
<?php if ($Page->TotalRecords > 0 || $Page->isGridEdit()) { ?>
<table id="tbl_servislist" class="table table-sm ew-table"><!-- .ew-table -->
<thead>
    <tr class="ew-table-header">
<?php
// Header row
$Page->RowType = ROWTYPE_HEADER;

// Render list options
$Page->renderListOptions();

// Render list options (header, left)
$Page->ListOptions->render("header", "left");
?>
<?php if ($Page->servisID->Visible) { // servisID ?>
        <th data-name="servisID" class="<?= $Page->servisID->headerCellClass() ?>"><div id="elh_servis_servisID" class="servis_servisID"><?= $Page->renderFieldHeader($Page->servisID) ?></div></th>
<?php } ?>
<?php if ($Page->servisDate->Visible) { // servisDate ?>
        <th data-name="servisDate" class="<?= $Page->servisDate->headerCellClass() ?>"><div id="elh_servis_servisDate" class="servis_servisDate"><?= $Page->renderFieldHeader($Page->servisDate) ?></div></th>
<?php } ?>
<?php if ($Page->servisKlienti->Visible) { // servisKlienti ?>
        <th data-name="servisKlienti" class="<?= $Page->servisKlienti->headerCellClass() ?>"><div id="elh_servis_servisKlienti" class="servis_servisKlienti"><?= $Page->renderFieldHeader($Page->servisKlienti) ?></div></th>
<?php } ?>
<?php if ($Page->servisMakina->Visible) { // servisMakina ?>
        <th data-name="servisMakina" class="<?= $Page->servisMakina->headerCellClass() ?>"><div id="elh_servis_servisMakina" class="servis_servisMakina"><?= $Page->renderFieldHeader($Page->servisMakina) ?></div></th>
<?php } ?>
<?php if ($Page->servisKmMakines->Visible) { // servisKmMakines ?>
        <th data-name="servisKmMakines" class="<?= $Page->servisKmMakines->headerCellClass() ?>"><div id="elh_servis_servisKmMakines" class="servis_servisKmMakines"><?= $Page->renderFieldHeader($Page->servisKmMakines) ?></div></th>
<?php } ?>
<?php if ($Page->servisStafi->Visible) { // servisStafi ?>
        <th data-name="servisStafi" class="<?= $Page->servisStafi->headerCellClass() ?>"><div id="elh_servis_servisStafi" class="servis_servisStafi"><?= $Page->renderFieldHeader($Page->servisStafi) ?></div></th>
<?php } ?>
<?php if ($Page->servisTotaliFatures->Visible) { // servisTotaliFatures ?>
        <th data-name="servisTotaliFatures" class="<?= $Page->servisTotaliFatures->headerCellClass() ?>"><div id="elh_servis_servisTotaliFatures" class="servis_servisTotaliFatures"><?= $Page->renderFieldHeader($Page->servisTotaliFatures) ?></div></th>
<?php } ?>
<?php
// Render list options (header, right)
$Page->ListOptions->render("header", "right");
?>
    </tr>
</thead>
<tbody>
<?php
if ($Page->ExportAll && $Page->isExport()) {
    $Page->StopRecord = $Page->TotalRecords;
} else {
    // Set the last record to display
    if ($Page->TotalRecords > $Page->StartRecord + $Page->DisplayRecords - 1) {
        $Page->StopRecord = $Page->StartRecord + $Page->DisplayRecords - 1;
    } else {
        $Page->StopRecord = $Page->TotalRecords;
    }
}
$Page->RecordCount = $Page->StartRecord - 1;
if ($Page->Recordset && !$Page->Recordset->EOF) {
    // Nothing to do
} elseif ($Page->isGridAdd() && !$Page->AllowAddDeleteRow && $Page->StopRecord == 0) {
    $Page->StopRecord = $Page->GridAddRowCount;
}

// Initialize aggregate
$Page->RowType = ROWTYPE_AGGREGATEINIT;
$Page->resetAttributes();
$Page->renderRow();
while ($Page->RecordCount < $Page->StopRecord) {
    $Page->RecordCount++;
    if ($Page->RecordCount >= $Page->StartRecord) {
        $Page->RowCount++;

        // Set up key count
        $Page->KeyCount = $Page->RowIndex;

        // Init row class and style
        $Page->resetAttributes();
        $Page->CssClass = "";
        if ($Page->isGridAdd()) {
            $Page->loadRowValues(); // Load default values
            $Page->OldKey = "";
            $Page->setKey($Page->OldKey);
        } else {
            $Page->loadRowValues($Page->Recordset); // Load row values
            if ($Page->isGridEdit()) {
                $Page->OldKey = $Page->getKey(true); // Get from CurrentValue
                $Page->setKey($Page->OldKey);
            }
        }
        $Page->RowType = ROWTYPE_VIEW; // Render view

        // Set up row attributes
        $Page->RowAttrs->merge([
            "data-rowindex" => $Page->RowCount,
            "id" => "r" . $Page->RowCount . "_servis",
            "data-rowtype" => $Page->RowType,
            "class" => ($Page->RowCount % 2 != 1) ? "ew-table-alt-row" : "",
        ]);
        if ($Page->isAdd() && $Page->RowType == ROWTYPE_ADD || $Page->isEdit() && $Page->RowType == ROWTYPE_EDIT) { // Inline-Add/Edit row
            $Page->RowAttrs->appendClass("table-active");
        }

        // Render row
        $Page->renderRow();

        // Render list options
        $Page->renderListOptions();
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php
// Render list options (body, left)
$Page->ListOptions->render("body", "left", $Page->RowCount);
?>
    <?php if ($Page->servisID->Visible) { // servisID ?>
        <td data-name="servisID"<?= $Page->servisID->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisID" class="el_servis_servisID">
<span<?= $Page->servisID->viewAttributes() ?>>
<?= $Page->servisID->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisDate->Visible) { // servisDate ?>
        <td data-name="servisDate"<?= $Page->servisDate->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisDate" class="el_servis_servisDate">
<span<?= $Page->servisDate->viewAttributes() ?>>
<?= $Page->servisDate->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisKlienti->Visible) { // servisKlienti ?>
        <td data-name="servisKlienti"<?= $Page->servisKlienti->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisKlienti" class="el_servis_servisKlienti">
<span<?= $Page->servisKlienti->viewAttributes() ?>>
<?= $Page->servisKlienti->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisMakina->Visible) { // servisMakina ?>
        <td data-name="servisMakina"<?= $Page->servisMakina->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisMakina" class="el_servis_servisMakina">
<span<?= $Page->servisMakina->viewAttributes() ?>>
<?= $Page->servisMakina->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisKmMakines->Visible) { // servisKmMakines ?>
        <td data-name="servisKmMakines"<?= $Page->servisKmMakines->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisKmMakines" class="el_servis_servisKmMakines">
<span<?= $Page->servisKmMakines->viewAttributes() ?>>
<?= $Page->servisKmMakines->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisStafi->Visible) { // servisStafi ?>
        <td data-name="servisStafi"<?= $Page->servisStafi->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisStafi" class="el_servis_servisStafi">
<span<?= $Page->servisStafi->viewAttributes() ?>>
<?= $Page->servisStafi->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
    <?php if ($Page->servisTotaliFatures->Visible) { // servisTotaliFatures ?>
        <td data-name="servisTotaliFatures"<?= $Page->servisTotaliFatures->cellAttributes() ?>>
<span id="el<?= $Page->RowCount ?>_servis_servisTotaliFatures" class="el_servis_servisTotaliFatures">
<span<?= $Page->servisTotaliFatures->viewAttributes() ?>>
<?= $Page->servisTotaliFatures->getViewValue() ?></span>
</span>
</td>
    <?php } ?>
<?php
// Render list options (body, right)
$Page->ListOptions->render("body", "right", $Page->RowCount);
?>
    </tr>
<?php
    }
    if (!$Page->isGridAdd()) {
        $Page->Recordset->moveNext();
    }
}
?>
</tbody>
<?php
// Render aggregate row
$Page->RowType = ROWTYPE_AGGREGATE;
$Page->resetAttributes();
$Page->renderRow();
?>
<?php if ($Page->TotalRecords > 0 && !$Page->isGridAdd() && !$Page->isGridEdit()) { ?>
<tfoot><!-- Table footer -->
    <tr class="ew-table-footer">
<?php
// Render list options
$Page->renderListOptions();

// Render list options (footer, left)
$Page->ListOptions->render("footer", "left");
?>
    <?php if ($Page->servisID->Visible) { // servisID ?>
        <td data-name="servisID" class="<?= $Page->servisID->footerCellClass() ?>"><span id="elf_servis_servisID" class="servis_servisID">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisDate->Visible) { // servisDate ?>
        <td data-name="servisDate" class="<?= $Page->servisDate->footerCellClass() ?>"><span id="elf_servis_servisDate" class="servis_servisDate">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisKlienti->Visible) { // servisKlienti ?>
        <td data-name="servisKlienti" class="<?= $Page->servisKlienti->footerCellClass() ?>"><span id="elf_servis_servisKlienti" class="servis_servisKlienti">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisMakina->Visible) { // servisMakina ?>
        <td data-name="servisMakina" class="<?= $Page->servisMakina->footerCellClass() ?>"><span id="elf_servis_servisMakina" class="servis_servisMakina">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisKmMakines->Visible) { // servisKmMakines ?>
        <td data-name="servisKmMakines" class="<?= $Page->servisKmMakines->footerCellClass() ?>"><span id="elf_servis_servisKmMakines" class="servis_servisKmMakines">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisStafi->Visible) { // servisStafi ?>
        <td data-name="servisStafi" class="<?= $Page->servisStafi->footerCellClass() ?>"><span id="elf_servis_servisStafi" class="servis_servisStafi">
        </span></td>
    <?php } ?>
    <?php if ($Page->servisTotaliFatures->Visible) { // servisTotaliFatures ?>
        <td data-name="servisTotaliFatures" class="<?= $Page->servisTotaliFatures->footerCellClass() ?>"><span id="elf_servis_servisTotaliFatures" class="servis_servisTotaliFatures">
        <span class="ew-aggregate"><?= $Language->phrase("TOTAL") ?></span><span class="ew-aggregate-value">
        <?= $Page->servisTotaliFatures->ViewValue ?></span>
        </span></td>
    <?php } ?>
<?php
// Render list options (footer, right)
$Page->ListOptions->render("footer", "right");
?>
    </tr>
</tfoot>
<?php } ?>
</table><!-- /.ew-table -->
<?php } ?>
</div><!-- /.ew-grid-middle-panel -->
<?php if (!$Page->CurrentAction) { ?>
<input type="hidden" name="action" id="action" value="">
<?php } ?>
</form><!-- /.ew-list-form -->
<?php
// Close recordset
if ($Page->Recordset) {
    $Page->Recordset->close();
}
?>
<?php if (!$Page->isExport()) { ?>
<div class="card-footer ew-grid-lower-panel">
<?php if (!$Page->isGridAdd()) { ?>
<form name="ew-pager-form" class="ew-form ew-pager-form" action="<?= CurrentPageUrl(false) ?>">
<?= $Page->Pager->render() ?>
</form>
<?php } ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body", "bottom") ?>
</div>
</div>
<?php } ?>
</div><!-- /.ew-grid -->
<?php } else { ?>
<div class="ew-list-other-options">
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?php
$Page->showPageFooter();
echo GetDebugMessage();
?>
<?php if (!$Page->isExport()) { ?>
<script>
// Field event handlers
loadjs.ready("head", function() {
    ew.addEventHandlers("servis");
});
</script>
<script>
loadjs.ready("load", function () {
    // Startup script
    // Write your table-specific startup script here, no need to add script tags.
    $("a[class='btn btn-default ew-detail-add-group ew-detail-add']").append(' SHTO SERVIS TE KRYER ');
});
</script>
<?php } ?>
