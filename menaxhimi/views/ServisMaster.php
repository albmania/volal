<?php

namespace PHPMaker2022\volalservice;

// Table
$servis = Container("servis");
?>
<?php if ($servis->Visible) { ?>
<div class="ew-master-div">
<table id="tbl_servismaster" class="table ew-view-table ew-master-table ew-vertical">
    <tbody>
<?php if ($servis->servisID->Visible) { // servisID ?>
        <tr id="r_servisID"<?= $servis->servisID->rowAttributes() ?>>
            <td class="<?= $servis->TableLeftColumnClass ?>"><?= $servis->servisID->caption() ?></td>
            <td<?= $servis->servisID->cellAttributes() ?>>
<span id="el_servis_servisID">
<span<?= $servis->servisID->viewAttributes() ?>>
<?= $servis->servisID->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($servis->servisDate->Visible) { // servisDate ?>
        <tr id="r_servisDate"<?= $servis->servisDate->rowAttributes() ?>>
            <td class="<?= $servis->TableLeftColumnClass ?>"><?= $servis->servisDate->caption() ?></td>
            <td<?= $servis->servisDate->cellAttributes() ?>>
<span id="el_servis_servisDate">
<span<?= $servis->servisDate->viewAttributes() ?>>
<?= $servis->servisDate->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($servis->servisKlienti->Visible) { // servisKlienti ?>
        <tr id="r_servisKlienti"<?= $servis->servisKlienti->rowAttributes() ?>>
            <td class="<?= $servis->TableLeftColumnClass ?>"><?= $servis->servisKlienti->caption() ?></td>
            <td<?= $servis->servisKlienti->cellAttributes() ?>>
<span id="el_servis_servisKlienti">
<span<?= $servis->servisKlienti->viewAttributes() ?>>
<?= $servis->servisKlienti->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($servis->servisMakina->Visible) { // servisMakina ?>
        <tr id="r_servisMakina"<?= $servis->servisMakina->rowAttributes() ?>>
            <td class="<?= $servis->TableLeftColumnClass ?>"><?= $servis->servisMakina->caption() ?></td>
            <td<?= $servis->servisMakina->cellAttributes() ?>>
<span id="el_servis_servisMakina">
<span<?= $servis->servisMakina->viewAttributes() ?>>
<?= $servis->servisMakina->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($servis->servisKmMakines->Visible) { // servisKmMakines ?>
        <tr id="r_servisKmMakines"<?= $servis->servisKmMakines->rowAttributes() ?>>
            <td class="<?= $servis->TableLeftColumnClass ?>"><?= $servis->servisKmMakines->caption() ?></td>
            <td<?= $servis->servisKmMakines->cellAttributes() ?>>
<span id="el_servis_servisKmMakines">
<span<?= $servis->servisKmMakines->viewAttributes() ?>>
<?= $servis->servisKmMakines->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($servis->servisStafi->Visible) { // servisStafi ?>
        <tr id="r_servisStafi"<?= $servis->servisStafi->rowAttributes() ?>>
            <td class="<?= $servis->TableLeftColumnClass ?>"><?= $servis->servisStafi->caption() ?></td>
            <td<?= $servis->servisStafi->cellAttributes() ?>>
<span id="el_servis_servisStafi">
<span<?= $servis->servisStafi->viewAttributes() ?>>
<?= $servis->servisStafi->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
<?php if ($servis->servisTotaliFatures->Visible) { // servisTotaliFatures ?>
        <tr id="r_servisTotaliFatures"<?= $servis->servisTotaliFatures->rowAttributes() ?>>
            <td class="<?= $servis->TableLeftColumnClass ?>"><?= $servis->servisTotaliFatures->caption() ?></td>
            <td<?= $servis->servisTotaliFatures->cellAttributes() ?>>
<span id="el_servis_servisTotaliFatures">
<span<?= $servis->servisTotaliFatures->viewAttributes() ?>>
<?= $servis->servisTotaliFatures->getViewValue() ?></span>
</span>
</td>
        </tr>
<?php } ?>
    </tbody>
</table>
</div>
<?php } ?>
