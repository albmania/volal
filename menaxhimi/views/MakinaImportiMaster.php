<?php

namespace PHPMaker2022\volalservice;

// Table
$makina_importi = Container("makina_importi");
?>
<?php if ($makina_importi->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_makina_importimaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($makina_importi->mimpID->Visible) { // mimpID ?>
        <tr id="r_mimpID"<?= $makina_importi->mimpID->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpID->caption() ?></td>
            <td<?= $makina_importi->mimpID->cellAttributes() ?>>
<span id="el_makina_importi_mimpID">
<span<?= $makina_importi->mimpID->viewAttributes() ?>>
<?= $makina_importi->mimpID->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpMarka->Visible) { // mimpMarka ?>
        <tr id="r_mimpMarka"<?= $makina_importi->mimpMarka->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpMarka->caption() ?></td>
            <td<?= $makina_importi->mimpMarka->cellAttributes() ?>>
<span id="el_makina_importi_mimpMarka">
<span<?= $makina_importi->mimpMarka->viewAttributes() ?>>
<?= $makina_importi->mimpMarka->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpModeli->Visible) { // mimpModeli ?>
        <tr id="r_mimpModeli"<?= $makina_importi->mimpModeli->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpModeli->caption() ?></td>
            <td<?= $makina_importi->mimpModeli->cellAttributes() ?>>
<span id="el_makina_importi_mimpModeli">
<span<?= $makina_importi->mimpModeli->viewAttributes() ?>>
<?= $makina_importi->mimpModeli->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpTipi->Visible) { // mimpTipi ?>
        <tr id="r_mimpTipi"<?= $makina_importi->mimpTipi->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpTipi->caption() ?></td>
            <td<?= $makina_importi->mimpTipi->cellAttributes() ?>>
<span id="el_makina_importi_mimpTipi">
<span<?= $makina_importi->mimpTipi->viewAttributes() ?>>
<?= $makina_importi->mimpTipi->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpShasia->Visible) { // mimpShasia ?>
        <tr id="r_mimpShasia"<?= $makina_importi->mimpShasia->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpShasia->caption() ?></td>
            <td<?= $makina_importi->mimpShasia->cellAttributes() ?>>
<span id="el_makina_importi_mimpShasia">
<span<?= $makina_importi->mimpShasia->viewAttributes() ?>>
<?= $makina_importi->mimpShasia->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpViti->Visible) { // mimpViti ?>
        <tr id="r_mimpViti"<?= $makina_importi->mimpViti->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpViti->caption() ?></td>
            <td<?= $makina_importi->mimpViti->cellAttributes() ?>>
<span id="el_makina_importi_mimpViti">
<span<?= $makina_importi->mimpViti->viewAttributes() ?>>
<?= $makina_importi->mimpViti->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpKarburant->Visible) { // mimpKarburant ?>
        <tr id="r_mimpKarburant"<?= $makina_importi->mimpKarburant->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpKarburant->caption() ?></td>
            <td<?= $makina_importi->mimpKarburant->cellAttributes() ?>>
<span id="el_makina_importi_mimpKarburant">
<span<?= $makina_importi->mimpKarburant->viewAttributes() ?>>
<?= $makina_importi->mimpKarburant->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpPrejardhja->Visible) { // mimpPrejardhja ?>
        <tr id="r_mimpPrejardhja"<?= $makina_importi->mimpPrejardhja->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpPrejardhja->caption() ?></td>
            <td<?= $makina_importi->mimpPrejardhja->cellAttributes() ?>>
<span id="el_makina_importi_mimpPrejardhja">
<span<?= $makina_importi->mimpPrejardhja->viewAttributes() ?>>
<?= $makina_importi->mimpPrejardhja->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpCmimiBlerjes->Visible) { // mimpCmimiBlerjes ?>
        <tr id="r_mimpCmimiBlerjes"<?= $makina_importi->mimpCmimiBlerjes->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpCmimiBlerjes->caption() ?></td>
            <td<?= $makina_importi->mimpCmimiBlerjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpCmimiBlerjes">
<span<?= $makina_importi->mimpCmimiBlerjes->viewAttributes() ?>>
<?= $makina_importi->mimpCmimiBlerjes->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpDogana->Visible) { // mimpDogana ?>
        <tr id="r_mimpDogana"<?= $makina_importi->mimpDogana->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpDogana->caption() ?></td>
            <td<?= $makina_importi->mimpDogana->cellAttributes() ?>>
<span id="el_makina_importi_mimpDogana">
<span<?= $makina_importi->mimpDogana->viewAttributes() ?>>
<?= $makina_importi->mimpDogana->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpTransporti->Visible) { // mimpTransporti ?>
        <tr id="r_mimpTransporti"<?= $makina_importi->mimpTransporti->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpTransporti->caption() ?></td>
            <td<?= $makina_importi->mimpTransporti->cellAttributes() ?>>
<span id="el_makina_importi_mimpTransporti">
<span<?= $makina_importi->mimpTransporti->viewAttributes() ?>>
<?= $makina_importi->mimpTransporti->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpTjera->Visible) { // mimpTjera ?>
        <tr id="r_mimpTjera"<?= $makina_importi->mimpTjera->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpTjera->caption() ?></td>
            <td<?= $makina_importi->mimpTjera->cellAttributes() ?>>
<span id="el_makina_importi_mimpTjera">
<span<?= $makina_importi->mimpTjera->viewAttributes() ?>>
<?= $makina_importi->mimpTjera->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpDtHyrjes->Visible) { // mimpDtHyrjes ?>
        <tr id="r_mimpDtHyrjes"<?= $makina_importi->mimpDtHyrjes->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpDtHyrjes->caption() ?></td>
            <td<?= $makina_importi->mimpDtHyrjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpDtHyrjes">
<span<?= $makina_importi->mimpDtHyrjes->viewAttributes() ?>>
<?= $makina_importi->mimpDtHyrjes->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpCmimiShitjes->Visible) { // mimpCmimiShitjes ?>
        <tr id="r_mimpCmimiShitjes"<?= $makina_importi->mimpCmimiShitjes->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpCmimiShitjes->caption() ?></td>
            <td<?= $makina_importi->mimpCmimiShitjes->cellAttributes() ?>>
<span id="el_makina_importi_mimpCmimiShitjes">
<span<?= $makina_importi->mimpCmimiShitjes->viewAttributes() ?>>
<?= $makina_importi->mimpCmimiShitjes->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($makina_importi->mimpGati->Visible) { // mimpGati ?>
        <tr id="r_mimpGati"<?= $makina_importi->mimpGati->rowAttributes() ?>>
            <td class="<?= $makina_importi->TableLeftColumnClass ?>"><?= $makina_importi->mimpGati->caption() ?></td>
            <td<?= $makina_importi->mimpGati->cellAttributes() ?>>
<span id="el_makina_importi_mimpGati">
<span<?= $makina_importi->mimpGati->viewAttributes() ?>>
<?= $makina_importi->mimpGati->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
