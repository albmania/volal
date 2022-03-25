<?php

namespace PHPMaker2022\volalservice;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Handle Routes
return function (App $app) {
    // klient
    $app->map(["GET","POST","OPTIONS"], '/KlientList[/{klientID}]', KlientController::class . ':list')->add(PermissionMiddleware::class)->setName('KlientList-klient-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/KlientAdd[/{klientID}]', KlientController::class . ':add')->add(PermissionMiddleware::class)->setName('KlientAdd-klient-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/KlientAddopt', KlientController::class . ':addopt')->add(PermissionMiddleware::class)->setName('KlientAddopt-klient-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/KlientView[/{klientID}]', KlientController::class . ':view')->add(PermissionMiddleware::class)->setName('KlientView-klient-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/KlientEdit[/{klientID}]', KlientController::class . ':edit')->add(PermissionMiddleware::class)->setName('KlientEdit-klient-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/KlientDelete[/{klientID}]', KlientController::class . ':delete')->add(PermissionMiddleware::class)->setName('KlientDelete-klient-delete'); // delete
    $app->group(
        '/klient',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{klientID}]', KlientController::class . ':list')->add(PermissionMiddleware::class)->setName('klient/list-klient-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{klientID}]', KlientController::class . ':add')->add(PermissionMiddleware::class)->setName('klient/add-klient-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADDOPT_ACTION") . '', KlientController::class . ':addopt')->add(PermissionMiddleware::class)->setName('klient/addopt-klient-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{klientID}]', KlientController::class . ':view')->add(PermissionMiddleware::class)->setName('klient/view-klient-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{klientID}]', KlientController::class . ':edit')->add(PermissionMiddleware::class)->setName('klient/edit-klient-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{klientID}]', KlientController::class . ':delete')->add(PermissionMiddleware::class)->setName('klient/delete-klient-delete-2'); // delete
        }
    );

    // makina
    $app->map(["GET","POST","OPTIONS"], '/MakinaList[/{makinaID}]', MakinaController::class . ':list')->add(PermissionMiddleware::class)->setName('MakinaList-makina-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MakinaAdd[/{makinaID}]', MakinaController::class . ':add')->add(PermissionMiddleware::class)->setName('MakinaAdd-makina-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MakinaAddopt', MakinaController::class . ':addopt')->add(PermissionMiddleware::class)->setName('MakinaAddopt-makina-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/MakinaView[/{makinaID}]', MakinaController::class . ':view')->add(PermissionMiddleware::class)->setName('MakinaView-makina-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MakinaEdit[/{makinaID}]', MakinaController::class . ':edit')->add(PermissionMiddleware::class)->setName('MakinaEdit-makina-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MakinaDelete[/{makinaID}]', MakinaController::class . ':delete')->add(PermissionMiddleware::class)->setName('MakinaDelete-makina-delete'); // delete
    $app->group(
        '/makina',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{makinaID}]', MakinaController::class . ':list')->add(PermissionMiddleware::class)->setName('makina/list-makina-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{makinaID}]', MakinaController::class . ':add')->add(PermissionMiddleware::class)->setName('makina/add-makina-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADDOPT_ACTION") . '', MakinaController::class . ':addopt')->add(PermissionMiddleware::class)->setName('makina/addopt-makina-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{makinaID}]', MakinaController::class . ':view')->add(PermissionMiddleware::class)->setName('makina/view-makina-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{makinaID}]', MakinaController::class . ':edit')->add(PermissionMiddleware::class)->setName('makina/edit-makina-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{makinaID}]', MakinaController::class . ':delete')->add(PermissionMiddleware::class)->setName('makina/delete-makina-delete-2'); // delete
        }
    );

    // perdoruesit
    $app->map(["GET","POST","OPTIONS"], '/PerdoruesitList[/{perdID}]', PerdoruesitController::class . ':list')->add(PermissionMiddleware::class)->setName('PerdoruesitList-perdoruesit-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/PerdoruesitAdd[/{perdID}]', PerdoruesitController::class . ':add')->add(PermissionMiddleware::class)->setName('PerdoruesitAdd-perdoruesit-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/PerdoruesitView[/{perdID}]', PerdoruesitController::class . ':view')->add(PermissionMiddleware::class)->setName('PerdoruesitView-perdoruesit-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/PerdoruesitEdit[/{perdID}]', PerdoruesitController::class . ':edit')->add(PermissionMiddleware::class)->setName('PerdoruesitEdit-perdoruesit-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/PerdoruesitDelete[/{perdID}]', PerdoruesitController::class . ':delete')->add(PermissionMiddleware::class)->setName('PerdoruesitDelete-perdoruesit-delete'); // delete
    $app->group(
        '/perdoruesit',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{perdID}]', PerdoruesitController::class . ':list')->add(PermissionMiddleware::class)->setName('perdoruesit/list-perdoruesit-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{perdID}]', PerdoruesitController::class . ':add')->add(PermissionMiddleware::class)->setName('perdoruesit/add-perdoruesit-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{perdID}]', PerdoruesitController::class . ':view')->add(PermissionMiddleware::class)->setName('perdoruesit/view-perdoruesit-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{perdID}]', PerdoruesitController::class . ':edit')->add(PermissionMiddleware::class)->setName('perdoruesit/edit-perdoruesit-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{perdID}]', PerdoruesitController::class . ':delete')->add(PermissionMiddleware::class)->setName('perdoruesit/delete-perdoruesit-delete-2'); // delete
        }
    );

    // pjese_kembimi
    $app->map(["GET","POST","OPTIONS"], '/PjeseKembimiList[/{pjeseID}]', PjeseKembimiController::class . ':list')->add(PermissionMiddleware::class)->setName('PjeseKembimiList-pjese_kembimi-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/PjeseKembimiAdd[/{pjeseID}]', PjeseKembimiController::class . ':add')->add(PermissionMiddleware::class)->setName('PjeseKembimiAdd-pjese_kembimi-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/PjeseKembimiView[/{pjeseID}]', PjeseKembimiController::class . ':view')->add(PermissionMiddleware::class)->setName('PjeseKembimiView-pjese_kembimi-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/PjeseKembimiEdit[/{pjeseID}]', PjeseKembimiController::class . ':edit')->add(PermissionMiddleware::class)->setName('PjeseKembimiEdit-pjese_kembimi-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/PjeseKembimiDelete[/{pjeseID}]', PjeseKembimiController::class . ':delete')->add(PermissionMiddleware::class)->setName('PjeseKembimiDelete-pjese_kembimi-delete'); // delete
    $app->group(
        '/pjese_kembimi',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{pjeseID}]', PjeseKembimiController::class . ':list')->add(PermissionMiddleware::class)->setName('pjese_kembimi/list-pjese_kembimi-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{pjeseID}]', PjeseKembimiController::class . ':add')->add(PermissionMiddleware::class)->setName('pjese_kembimi/add-pjese_kembimi-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{pjeseID}]', PjeseKembimiController::class . ':view')->add(PermissionMiddleware::class)->setName('pjese_kembimi/view-pjese_kembimi-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{pjeseID}]', PjeseKembimiController::class . ':edit')->add(PermissionMiddleware::class)->setName('pjese_kembimi/edit-pjese_kembimi-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{pjeseID}]', PjeseKembimiController::class . ':delete')->add(PermissionMiddleware::class)->setName('pjese_kembimi/delete-pjese_kembimi-delete-2'); // delete
        }
    );

    // servis
    $app->map(["GET","POST","OPTIONS"], '/ServisList[/{servisID}]', ServisController::class . ':list')->add(PermissionMiddleware::class)->setName('ServisList-servis-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ServisAdd[/{servisID}]', ServisController::class . ':add')->add(PermissionMiddleware::class)->setName('ServisAdd-servis-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ServisDelete[/{servisID}]', ServisController::class . ':delete')->add(PermissionMiddleware::class)->setName('ServisDelete-servis-delete'); // delete
    $app->group(
        '/servis',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{servisID}]', ServisController::class . ':list')->add(PermissionMiddleware::class)->setName('servis/list-servis-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{servisID}]', ServisController::class . ':add')->add(PermissionMiddleware::class)->setName('servis/add-servis-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{servisID}]', ServisController::class . ':delete')->add(PermissionMiddleware::class)->setName('servis/delete-servis-delete-2'); // delete
        }
    );

    // servis_pjeset
    $app->map(["GET","POST","OPTIONS"], '/ServisPjesetList[/{servisPjeseID}]', ServisPjesetController::class . ':list')->add(PermissionMiddleware::class)->setName('ServisPjesetList-servis_pjeset-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ServisPjesetAdd[/{servisPjeseID}]', ServisPjesetController::class . ':add')->add(PermissionMiddleware::class)->setName('ServisPjesetAdd-servis_pjeset-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ServisPjesetView[/{servisPjeseID}]', ServisPjesetController::class . ':view')->add(PermissionMiddleware::class)->setName('ServisPjesetView-servis_pjeset-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ServisPjesetEdit[/{servisPjeseID}]', ServisPjesetController::class . ':edit')->add(PermissionMiddleware::class)->setName('ServisPjesetEdit-servis_pjeset-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ServisPjesetDelete[/{servisPjeseID}]', ServisPjesetController::class . ':delete')->add(PermissionMiddleware::class)->setName('ServisPjesetDelete-servis_pjeset-delete'); // delete
    $app->group(
        '/servis_pjeset',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{servisPjeseID}]', ServisPjesetController::class . ':list')->add(PermissionMiddleware::class)->setName('servis_pjeset/list-servis_pjeset-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{servisPjeseID}]', ServisPjesetController::class . ':add')->add(PermissionMiddleware::class)->setName('servis_pjeset/add-servis_pjeset-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{servisPjeseID}]', ServisPjesetController::class . ':view')->add(PermissionMiddleware::class)->setName('servis_pjeset/view-servis_pjeset-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{servisPjeseID}]', ServisPjesetController::class . ':edit')->add(PermissionMiddleware::class)->setName('servis_pjeset/edit-servis_pjeset-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{servisPjeseID}]', ServisPjesetController::class . ':delete')->add(PermissionMiddleware::class)->setName('servis_pjeset/delete-servis_pjeset-delete-2'); // delete
        }
    );

    // servis_sherbime
    $app->map(["GET","POST","OPTIONS"], '/ServisSherbimeList[/{servisSherbimID}]', ServisSherbimeController::class . ':list')->add(PermissionMiddleware::class)->setName('ServisSherbimeList-servis_sherbime-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ServisSherbimeAdd[/{servisSherbimID}]', ServisSherbimeController::class . ':add')->add(PermissionMiddleware::class)->setName('ServisSherbimeAdd-servis_sherbime-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ServisSherbimeView[/{servisSherbimID}]', ServisSherbimeController::class . ':view')->add(PermissionMiddleware::class)->setName('ServisSherbimeView-servis_sherbime-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ServisSherbimeEdit[/{servisSherbimID}]', ServisSherbimeController::class . ':edit')->add(PermissionMiddleware::class)->setName('ServisSherbimeEdit-servis_sherbime-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ServisSherbimeDelete[/{servisSherbimID}]', ServisSherbimeController::class . ':delete')->add(PermissionMiddleware::class)->setName('ServisSherbimeDelete-servis_sherbime-delete'); // delete
    $app->group(
        '/servis_sherbime',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{servisSherbimID}]', ServisSherbimeController::class . ':list')->add(PermissionMiddleware::class)->setName('servis_sherbime/list-servis_sherbime-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{servisSherbimID}]', ServisSherbimeController::class . ':add')->add(PermissionMiddleware::class)->setName('servis_sherbime/add-servis_sherbime-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{servisSherbimID}]', ServisSherbimeController::class . ':view')->add(PermissionMiddleware::class)->setName('servis_sherbime/view-servis_sherbime-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{servisSherbimID}]', ServisSherbimeController::class . ':edit')->add(PermissionMiddleware::class)->setName('servis_sherbime/edit-servis_sherbime-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{servisSherbimID}]', ServisSherbimeController::class . ':delete')->add(PermissionMiddleware::class)->setName('servis_sherbime/delete-servis_sherbime-delete-2'); // delete
        }
    );

    // sherbime
    $app->map(["GET","POST","OPTIONS"], '/SherbimeList[/{sherbimeID}]', SherbimeController::class . ':list')->add(PermissionMiddleware::class)->setName('SherbimeList-sherbime-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/SherbimeAdd[/{sherbimeID}]', SherbimeController::class . ':add')->add(PermissionMiddleware::class)->setName('SherbimeAdd-sherbime-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/SherbimeView[/{sherbimeID}]', SherbimeController::class . ':view')->add(PermissionMiddleware::class)->setName('SherbimeView-sherbime-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/SherbimeEdit[/{sherbimeID}]', SherbimeController::class . ':edit')->add(PermissionMiddleware::class)->setName('SherbimeEdit-sherbime-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/SherbimeDelete[/{sherbimeID}]', SherbimeController::class . ':delete')->add(PermissionMiddleware::class)->setName('SherbimeDelete-sherbime-delete'); // delete
    $app->group(
        '/sherbime',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{sherbimeID}]', SherbimeController::class . ':list')->add(PermissionMiddleware::class)->setName('sherbime/list-sherbime-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{sherbimeID}]', SherbimeController::class . ':add')->add(PermissionMiddleware::class)->setName('sherbime/add-sherbime-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{sherbimeID}]', SherbimeController::class . ':view')->add(PermissionMiddleware::class)->setName('sherbime/view-sherbime-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{sherbimeID}]', SherbimeController::class . ':edit')->add(PermissionMiddleware::class)->setName('sherbime/edit-sherbime-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{sherbimeID}]', SherbimeController::class . ':delete')->add(PermissionMiddleware::class)->setName('sherbime/delete-sherbime-delete-2'); // delete
        }
    );

    // stafi
    $app->map(["GET","POST","OPTIONS"], '/StafiList[/{stafiID}]', StafiController::class . ':list')->add(PermissionMiddleware::class)->setName('StafiList-stafi-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/StafiAdd[/{stafiID}]', StafiController::class . ':add')->add(PermissionMiddleware::class)->setName('StafiAdd-stafi-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/StafiAddopt', StafiController::class . ':addopt')->add(PermissionMiddleware::class)->setName('StafiAddopt-stafi-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/StafiView[/{stafiID}]', StafiController::class . ':view')->add(PermissionMiddleware::class)->setName('StafiView-stafi-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/StafiEdit[/{stafiID}]', StafiController::class . ':edit')->add(PermissionMiddleware::class)->setName('StafiEdit-stafi-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/StafiDelete[/{stafiID}]', StafiController::class . ':delete')->add(PermissionMiddleware::class)->setName('StafiDelete-stafi-delete'); // delete
    $app->group(
        '/stafi',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{stafiID}]', StafiController::class . ':list')->add(PermissionMiddleware::class)->setName('stafi/list-stafi-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{stafiID}]', StafiController::class . ':add')->add(PermissionMiddleware::class)->setName('stafi/add-stafi-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADDOPT_ACTION") . '', StafiController::class . ':addopt')->add(PermissionMiddleware::class)->setName('stafi/addopt-stafi-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{stafiID}]', StafiController::class . ':view')->add(PermissionMiddleware::class)->setName('stafi/view-stafi-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{stafiID}]', StafiController::class . ':edit')->add(PermissionMiddleware::class)->setName('stafi/edit-stafi-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{stafiID}]', StafiController::class . ':delete')->add(PermissionMiddleware::class)->setName('stafi/delete-stafi-delete-2'); // delete
        }
    );

    // userlevelpermissions
    $app->map(["GET","POST","OPTIONS"], '/UserlevelpermissionsList[/{keys:.*}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsList-userlevelpermissions-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/UserlevelpermissionsAdd[/{keys:.*}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsAdd-userlevelpermissions-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/UserlevelpermissionsView[/{keys:.*}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsView-userlevelpermissions-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/UserlevelpermissionsEdit[/{keys:.*}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsEdit-userlevelpermissions-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/UserlevelpermissionsDelete[/{keys:.*}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('UserlevelpermissionsDelete-userlevelpermissions-delete'); // delete
    $app->group(
        '/userlevelpermissions',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevelpermissions/list-userlevelpermissions-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevelpermissions/add-userlevelpermissions-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevelpermissions/view-userlevelpermissions-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevelpermissions/edit-userlevelpermissions-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{keys:.*}]', UserlevelpermissionsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevelpermissions/delete-userlevelpermissions-delete-2'); // delete
        }
    );

    // userlevels
    $app->map(["GET","POST","OPTIONS"], '/UserlevelsList[/{userlevelid}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('UserlevelsList-userlevels-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/UserlevelsAdd[/{userlevelid}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('UserlevelsAdd-userlevels-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/UserlevelsView[/{userlevelid}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('UserlevelsView-userlevels-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/UserlevelsEdit[/{userlevelid}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('UserlevelsEdit-userlevels-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/UserlevelsDelete[/{userlevelid}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('UserlevelsDelete-userlevels-delete'); // delete
    $app->group(
        '/userlevels',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':list')->add(PermissionMiddleware::class)->setName('userlevels/list-userlevels-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':add')->add(PermissionMiddleware::class)->setName('userlevels/add-userlevels-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':view')->add(PermissionMiddleware::class)->setName('userlevels/view-userlevels-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':edit')->add(PermissionMiddleware::class)->setName('userlevels/edit-userlevels-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{userlevelid}]', UserlevelsController::class . ':delete')->add(PermissionMiddleware::class)->setName('userlevels/delete-userlevels-delete-2'); // delete
        }
    );

    // kreu
    $app->map(["GET", "POST", "OPTIONS"], '/Kreu[/{params:.*}]', KreuController::class)->add(PermissionMiddleware::class)->setName('Kreu-kreu-custom'); // custom

    // makina_marka
    $app->map(["GET","POST","OPTIONS"], '/MakinaMarkaList[/{mmarkaID}]', MakinaMarkaController::class . ':list')->add(PermissionMiddleware::class)->setName('MakinaMarkaList-makina_marka-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MakinaMarkaAdd[/{mmarkaID}]', MakinaMarkaController::class . ':add')->add(PermissionMiddleware::class)->setName('MakinaMarkaAdd-makina_marka-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MakinaMarkaView[/{mmarkaID}]', MakinaMarkaController::class . ':view')->add(PermissionMiddleware::class)->setName('MakinaMarkaView-makina_marka-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MakinaMarkaEdit[/{mmarkaID}]', MakinaMarkaController::class . ':edit')->add(PermissionMiddleware::class)->setName('MakinaMarkaEdit-makina_marka-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MakinaMarkaDelete[/{mmarkaID}]', MakinaMarkaController::class . ':delete')->add(PermissionMiddleware::class)->setName('MakinaMarkaDelete-makina_marka-delete'); // delete
    $app->group(
        '/makina_marka',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{mmarkaID}]', MakinaMarkaController::class . ':list')->add(PermissionMiddleware::class)->setName('makina_marka/list-makina_marka-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{mmarkaID}]', MakinaMarkaController::class . ':add')->add(PermissionMiddleware::class)->setName('makina_marka/add-makina_marka-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{mmarkaID}]', MakinaMarkaController::class . ':view')->add(PermissionMiddleware::class)->setName('makina_marka/view-makina_marka-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{mmarkaID}]', MakinaMarkaController::class . ':edit')->add(PermissionMiddleware::class)->setName('makina_marka/edit-makina_marka-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{mmarkaID}]', MakinaMarkaController::class . ':delete')->add(PermissionMiddleware::class)->setName('makina_marka/delete-makina_marka-delete-2'); // delete
        }
    );

    // makina_modeli
    $app->map(["GET","POST","OPTIONS"], '/MakinaModeliList[/{mmodeliID}]', MakinaModeliController::class . ':list')->add(PermissionMiddleware::class)->setName('MakinaModeliList-makina_modeli-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MakinaModeliAdd[/{mmodeliID}]', MakinaModeliController::class . ':add')->add(PermissionMiddleware::class)->setName('MakinaModeliAdd-makina_modeli-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MakinaModeliView[/{mmodeliID}]', MakinaModeliController::class . ':view')->add(PermissionMiddleware::class)->setName('MakinaModeliView-makina_modeli-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MakinaModeliEdit[/{mmodeliID}]', MakinaModeliController::class . ':edit')->add(PermissionMiddleware::class)->setName('MakinaModeliEdit-makina_modeli-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MakinaModeliDelete[/{mmodeliID}]', MakinaModeliController::class . ':delete')->add(PermissionMiddleware::class)->setName('MakinaModeliDelete-makina_modeli-delete'); // delete
    $app->group(
        '/makina_modeli',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{mmodeliID}]', MakinaModeliController::class . ':list')->add(PermissionMiddleware::class)->setName('makina_modeli/list-makina_modeli-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{mmodeliID}]', MakinaModeliController::class . ':add')->add(PermissionMiddleware::class)->setName('makina_modeli/add-makina_modeli-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{mmodeliID}]', MakinaModeliController::class . ':view')->add(PermissionMiddleware::class)->setName('makina_modeli/view-makina_modeli-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{mmodeliID}]', MakinaModeliController::class . ':edit')->add(PermissionMiddleware::class)->setName('makina_modeli/edit-makina_modeli-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{mmodeliID}]', MakinaModeliController::class . ':delete')->add(PermissionMiddleware::class)->setName('makina_modeli/delete-makina_modeli-delete-2'); // delete
        }
    );

    // makina_shitje
    $app->map(["GET","POST","OPTIONS"], '/MakinaShitjeList[/{mshitjeID}]', MakinaShitjeController::class . ':list')->add(PermissionMiddleware::class)->setName('MakinaShitjeList-makina_shitje-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MakinaShitjeAdd[/{mshitjeID}]', MakinaShitjeController::class . ':add')->add(PermissionMiddleware::class)->setName('MakinaShitjeAdd-makina_shitje-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MakinaShitjeView[/{mshitjeID}]', MakinaShitjeController::class . ':view')->add(PermissionMiddleware::class)->setName('MakinaShitjeView-makina_shitje-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MakinaShitjeEdit[/{mshitjeID}]', MakinaShitjeController::class . ':edit')->add(PermissionMiddleware::class)->setName('MakinaShitjeEdit-makina_shitje-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MakinaShitjeDelete[/{mshitjeID}]', MakinaShitjeController::class . ':delete')->add(PermissionMiddleware::class)->setName('MakinaShitjeDelete-makina_shitje-delete'); // delete
    $app->group(
        '/makina_shitje',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{mshitjeID}]', MakinaShitjeController::class . ':list')->add(PermissionMiddleware::class)->setName('makina_shitje/list-makina_shitje-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{mshitjeID}]', MakinaShitjeController::class . ':add')->add(PermissionMiddleware::class)->setName('makina_shitje/add-makina_shitje-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{mshitjeID}]', MakinaShitjeController::class . ':view')->add(PermissionMiddleware::class)->setName('makina_shitje/view-makina_shitje-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{mshitjeID}]', MakinaShitjeController::class . ':edit')->add(PermissionMiddleware::class)->setName('makina_shitje/edit-makina_shitje-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{mshitjeID}]', MakinaShitjeController::class . ':delete')->add(PermissionMiddleware::class)->setName('makina_shitje/delete-makina_shitje-delete-2'); // delete
        }
    );

    // makina_tipi
    $app->map(["GET","POST","OPTIONS"], '/MakinaTipiList[/{mtipiID}]', MakinaTipiController::class . ':list')->add(PermissionMiddleware::class)->setName('MakinaTipiList-makina_tipi-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MakinaTipiAdd[/{mtipiID}]', MakinaTipiController::class . ':add')->add(PermissionMiddleware::class)->setName('MakinaTipiAdd-makina_tipi-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MakinaTipiView[/{mtipiID}]', MakinaTipiController::class . ':view')->add(PermissionMiddleware::class)->setName('MakinaTipiView-makina_tipi-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MakinaTipiEdit[/{mtipiID}]', MakinaTipiController::class . ':edit')->add(PermissionMiddleware::class)->setName('MakinaTipiEdit-makina_tipi-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MakinaTipiDelete[/{mtipiID}]', MakinaTipiController::class . ':delete')->add(PermissionMiddleware::class)->setName('MakinaTipiDelete-makina_tipi-delete'); // delete
    $app->group(
        '/makina_tipi',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{mtipiID}]', MakinaTipiController::class . ':list')->add(PermissionMiddleware::class)->setName('makina_tipi/list-makina_tipi-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{mtipiID}]', MakinaTipiController::class . ':add')->add(PermissionMiddleware::class)->setName('makina_tipi/add-makina_tipi-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{mtipiID}]', MakinaTipiController::class . ':view')->add(PermissionMiddleware::class)->setName('makina_tipi/view-makina_tipi-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{mtipiID}]', MakinaTipiController::class . ':edit')->add(PermissionMiddleware::class)->setName('makina_tipi/edit-makina_tipi-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{mtipiID}]', MakinaTipiController::class . ':delete')->add(PermissionMiddleware::class)->setName('makina_tipi/delete-makina_tipi-delete-2'); // delete
        }
    );

    // menu_dytesore
    $app->map(["GET","POST","OPTIONS"], '/MenuDytesoreList[/{menudID}]', MenuDytesoreController::class . ':list')->add(PermissionMiddleware::class)->setName('MenuDytesoreList-menu_dytesore-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MenuDytesoreAdd[/{menudID}]', MenuDytesoreController::class . ':add')->add(PermissionMiddleware::class)->setName('MenuDytesoreAdd-menu_dytesore-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MenuDytesoreView[/{menudID}]', MenuDytesoreController::class . ':view')->add(PermissionMiddleware::class)->setName('MenuDytesoreView-menu_dytesore-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MenuDytesoreEdit[/{menudID}]', MenuDytesoreController::class . ':edit')->add(PermissionMiddleware::class)->setName('MenuDytesoreEdit-menu_dytesore-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MenuDytesoreDelete[/{menudID}]', MenuDytesoreController::class . ':delete')->add(PermissionMiddleware::class)->setName('MenuDytesoreDelete-menu_dytesore-delete'); // delete
    $app->group(
        '/menu_dytesore',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{menudID}]', MenuDytesoreController::class . ':list')->add(PermissionMiddleware::class)->setName('menu_dytesore/list-menu_dytesore-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{menudID}]', MenuDytesoreController::class . ':add')->add(PermissionMiddleware::class)->setName('menu_dytesore/add-menu_dytesore-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{menudID}]', MenuDytesoreController::class . ':view')->add(PermissionMiddleware::class)->setName('menu_dytesore/view-menu_dytesore-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{menudID}]', MenuDytesoreController::class . ':edit')->add(PermissionMiddleware::class)->setName('menu_dytesore/edit-menu_dytesore-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{menudID}]', MenuDytesoreController::class . ':delete')->add(PermissionMiddleware::class)->setName('menu_dytesore/delete-menu_dytesore-delete-2'); // delete
        }
    );

    // menu_kryesore
    $app->map(["GET","POST","OPTIONS"], '/MenuKryesoreList[/{menukID}]', MenuKryesoreController::class . ':list')->add(PermissionMiddleware::class)->setName('MenuKryesoreList-menu_kryesore-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MenuKryesoreAdd[/{menukID}]', MenuKryesoreController::class . ':add')->add(PermissionMiddleware::class)->setName('MenuKryesoreAdd-menu_kryesore-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MenuKryesoreAddopt', MenuKryesoreController::class . ':addopt')->add(PermissionMiddleware::class)->setName('MenuKryesoreAddopt-menu_kryesore-addopt'); // addopt
    $app->map(["GET","POST","OPTIONS"], '/MenuKryesoreView[/{menukID}]', MenuKryesoreController::class . ':view')->add(PermissionMiddleware::class)->setName('MenuKryesoreView-menu_kryesore-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MenuKryesoreEdit[/{menukID}]', MenuKryesoreController::class . ':edit')->add(PermissionMiddleware::class)->setName('MenuKryesoreEdit-menu_kryesore-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MenuKryesoreDelete[/{menukID}]', MenuKryesoreController::class . ':delete')->add(PermissionMiddleware::class)->setName('MenuKryesoreDelete-menu_kryesore-delete'); // delete
    $app->group(
        '/menu_kryesore',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{menukID}]', MenuKryesoreController::class . ':list')->add(PermissionMiddleware::class)->setName('menu_kryesore/list-menu_kryesore-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{menukID}]', MenuKryesoreController::class . ':add')->add(PermissionMiddleware::class)->setName('menu_kryesore/add-menu_kryesore-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADDOPT_ACTION") . '', MenuKryesoreController::class . ':addopt')->add(PermissionMiddleware::class)->setName('menu_kryesore/addopt-menu_kryesore-addopt-2'); // addopt
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{menukID}]', MenuKryesoreController::class . ':view')->add(PermissionMiddleware::class)->setName('menu_kryesore/view-menu_kryesore-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{menukID}]', MenuKryesoreController::class . ':edit')->add(PermissionMiddleware::class)->setName('menu_kryesore/edit-menu_kryesore-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{menukID}]', MenuKryesoreController::class . ':delete')->add(PermissionMiddleware::class)->setName('menu_kryesore/delete-menu_kryesore-delete-2'); // delete
        }
    );

    // slide
    $app->map(["GET","POST","OPTIONS"], '/SlideList[/{slideID}]', SlideController::class . ':list')->add(PermissionMiddleware::class)->setName('SlideList-slide-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/SlideAdd[/{slideID}]', SlideController::class . ':add')->add(PermissionMiddleware::class)->setName('SlideAdd-slide-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/SlideView[/{slideID}]', SlideController::class . ':view')->add(PermissionMiddleware::class)->setName('SlideView-slide-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/SlideEdit[/{slideID}]', SlideController::class . ':edit')->add(PermissionMiddleware::class)->setName('SlideEdit-slide-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/SlideDelete[/{slideID}]', SlideController::class . ':delete')->add(PermissionMiddleware::class)->setName('SlideDelete-slide-delete'); // delete
    $app->group(
        '/slide',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{slideID}]', SlideController::class . ':list')->add(PermissionMiddleware::class)->setName('slide/list-slide-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{slideID}]', SlideController::class . ':add')->add(PermissionMiddleware::class)->setName('slide/add-slide-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{slideID}]', SlideController::class . ':view')->add(PermissionMiddleware::class)->setName('slide/view-slide-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{slideID}]', SlideController::class . ':edit')->add(PermissionMiddleware::class)->setName('slide/edit-slide-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{slideID}]', SlideController::class . ':delete')->add(PermissionMiddleware::class)->setName('slide/delete-slide-delete-2'); // delete
        }
    );

    // index_tips
    $app->map(["GET","POST","OPTIONS"], '/IndexTipsList[/{iTipsID}]', IndexTipsController::class . ':list')->add(PermissionMiddleware::class)->setName('IndexTipsList-index_tips-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/IndexTipsAdd[/{iTipsID}]', IndexTipsController::class . ':add')->add(PermissionMiddleware::class)->setName('IndexTipsAdd-index_tips-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/IndexTipsView[/{iTipsID}]', IndexTipsController::class . ':view')->add(PermissionMiddleware::class)->setName('IndexTipsView-index_tips-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/IndexTipsEdit[/{iTipsID}]', IndexTipsController::class . ':edit')->add(PermissionMiddleware::class)->setName('IndexTipsEdit-index_tips-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/IndexTipsDelete[/{iTipsID}]', IndexTipsController::class . ':delete')->add(PermissionMiddleware::class)->setName('IndexTipsDelete-index_tips-delete'); // delete
    $app->group(
        '/index_tips',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{iTipsID}]', IndexTipsController::class . ':list')->add(PermissionMiddleware::class)->setName('index_tips/list-index_tips-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{iTipsID}]', IndexTipsController::class . ':add')->add(PermissionMiddleware::class)->setName('index_tips/add-index_tips-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{iTipsID}]', IndexTipsController::class . ':view')->add(PermissionMiddleware::class)->setName('index_tips/view-index_tips-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{iTipsID}]', IndexTipsController::class . ':edit')->add(PermissionMiddleware::class)->setName('index_tips/edit-index_tips-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{iTipsID}]', IndexTipsController::class . ':delete')->add(PermissionMiddleware::class)->setName('index_tips/delete-index_tips-delete-2'); // delete
        }
    );

    // index_psene
    $app->map(["GET","POST","OPTIONS"], '/IndexPseneList[/{iPseNeID}]', IndexPseneController::class . ':list')->add(PermissionMiddleware::class)->setName('IndexPseneList-index_psene-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/IndexPseneEdit[/{iPseNeID}]', IndexPseneController::class . ':edit')->add(PermissionMiddleware::class)->setName('IndexPseneEdit-index_psene-edit'); // edit
    $app->group(
        '/index_psene',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{iPseNeID}]', IndexPseneController::class . ':list')->add(PermissionMiddleware::class)->setName('index_psene/list-index_psene-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{iPseNeID}]', IndexPseneController::class . ':edit')->add(PermissionMiddleware::class)->setName('index_psene/edit-index_psene-edit-2'); // edit
        }
    );

    // prodhues
    $app->map(["GET","POST","OPTIONS"], '/ProdhuesList[/{prodhuesID}]', ProdhuesController::class . ':list')->add(PermissionMiddleware::class)->setName('ProdhuesList-prodhues-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ProdhuesAdd[/{prodhuesID}]', ProdhuesController::class . ':add')->add(PermissionMiddleware::class)->setName('ProdhuesAdd-prodhues-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ProdhuesView[/{prodhuesID}]', ProdhuesController::class . ':view')->add(PermissionMiddleware::class)->setName('ProdhuesView-prodhues-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ProdhuesEdit[/{prodhuesID}]', ProdhuesController::class . ':edit')->add(PermissionMiddleware::class)->setName('ProdhuesEdit-prodhues-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ProdhuesDelete[/{prodhuesID}]', ProdhuesController::class . ':delete')->add(PermissionMiddleware::class)->setName('ProdhuesDelete-prodhues-delete'); // delete
    $app->group(
        '/prodhues',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{prodhuesID}]', ProdhuesController::class . ':list')->add(PermissionMiddleware::class)->setName('prodhues/list-prodhues-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{prodhuesID}]', ProdhuesController::class . ':add')->add(PermissionMiddleware::class)->setName('prodhues/add-prodhues-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{prodhuesID}]', ProdhuesController::class . ':view')->add(PermissionMiddleware::class)->setName('prodhues/view-prodhues-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{prodhuesID}]', ProdhuesController::class . ':edit')->add(PermissionMiddleware::class)->setName('prodhues/edit-prodhues-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{prodhuesID}]', ProdhuesController::class . ':delete')->add(PermissionMiddleware::class)->setName('prodhues/delete-prodhues-delete-2'); // delete
        }
    );

    // blog
    $app->map(["GET","POST","OPTIONS"], '/BlogList[/{blogID}]', BlogController::class . ':list')->add(PermissionMiddleware::class)->setName('BlogList-blog-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/BlogAdd[/{blogID}]', BlogController::class . ':add')->add(PermissionMiddleware::class)->setName('BlogAdd-blog-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/BlogView[/{blogID}]', BlogController::class . ':view')->add(PermissionMiddleware::class)->setName('BlogView-blog-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/BlogEdit[/{blogID}]', BlogController::class . ':edit')->add(PermissionMiddleware::class)->setName('BlogEdit-blog-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/BlogDelete[/{blogID}]', BlogController::class . ':delete')->add(PermissionMiddleware::class)->setName('BlogDelete-blog-delete'); // delete
    $app->group(
        '/blog',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{blogID}]', BlogController::class . ':list')->add(PermissionMiddleware::class)->setName('blog/list-blog-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{blogID}]', BlogController::class . ':add')->add(PermissionMiddleware::class)->setName('blog/add-blog-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{blogID}]', BlogController::class . ':view')->add(PermissionMiddleware::class)->setName('blog/view-blog-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{blogID}]', BlogController::class . ':edit')->add(PermissionMiddleware::class)->setName('blog/edit-blog-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{blogID}]', BlogController::class . ':delete')->add(PermissionMiddleware::class)->setName('blog/delete-blog-delete-2'); // delete
        }
    );

    // blog_kategori
    $app->map(["GET","POST","OPTIONS"], '/BlogKategoriList[/{blogKatID}]', BlogKategoriController::class . ':list')->add(PermissionMiddleware::class)->setName('BlogKategoriList-blog_kategori-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/BlogKategoriAdd[/{blogKatID}]', BlogKategoriController::class . ':add')->add(PermissionMiddleware::class)->setName('BlogKategoriAdd-blog_kategori-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/BlogKategoriView[/{blogKatID}]', BlogKategoriController::class . ':view')->add(PermissionMiddleware::class)->setName('BlogKategoriView-blog_kategori-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/BlogKategoriEdit[/{blogKatID}]', BlogKategoriController::class . ':edit')->add(PermissionMiddleware::class)->setName('BlogKategoriEdit-blog_kategori-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/BlogKategoriDelete[/{blogKatID}]', BlogKategoriController::class . ':delete')->add(PermissionMiddleware::class)->setName('BlogKategoriDelete-blog_kategori-delete'); // delete
    $app->group(
        '/blog_kategori',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{blogKatID}]', BlogKategoriController::class . ':list')->add(PermissionMiddleware::class)->setName('blog_kategori/list-blog_kategori-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{blogKatID}]', BlogKategoriController::class . ':add')->add(PermissionMiddleware::class)->setName('blog_kategori/add-blog_kategori-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{blogKatID}]', BlogKategoriController::class . ':view')->add(PermissionMiddleware::class)->setName('blog_kategori/view-blog_kategori-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{blogKatID}]', BlogKategoriController::class . ':edit')->add(PermissionMiddleware::class)->setName('blog_kategori/edit-blog_kategori-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{blogKatID}]', BlogKategoriController::class . ':delete')->add(PermissionMiddleware::class)->setName('blog_kategori/delete-blog_kategori-delete-2'); // delete
        }
    );

    // faqe
    $app->map(["GET","POST","OPTIONS"], '/FaqeList[/{faqeID}]', FaqeController::class . ':list')->add(PermissionMiddleware::class)->setName('FaqeList-faqe-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/FaqeAdd[/{faqeID}]', FaqeController::class . ':add')->add(PermissionMiddleware::class)->setName('FaqeAdd-faqe-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/FaqeView[/{faqeID}]', FaqeController::class . ':view')->add(PermissionMiddleware::class)->setName('FaqeView-faqe-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/FaqeEdit[/{faqeID}]', FaqeController::class . ':edit')->add(PermissionMiddleware::class)->setName('FaqeEdit-faqe-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/FaqeDelete[/{faqeID}]', FaqeController::class . ':delete')->add(PermissionMiddleware::class)->setName('FaqeDelete-faqe-delete'); // delete
    $app->group(
        '/faqe',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{faqeID}]', FaqeController::class . ':list')->add(PermissionMiddleware::class)->setName('faqe/list-faqe-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{faqeID}]', FaqeController::class . ':add')->add(PermissionMiddleware::class)->setName('faqe/add-faqe-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{faqeID}]', FaqeController::class . ':view')->add(PermissionMiddleware::class)->setName('faqe/view-faqe-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{faqeID}]', FaqeController::class . ':edit')->add(PermissionMiddleware::class)->setName('faqe/edit-faqe-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{faqeID}]', FaqeController::class . ':delete')->add(PermissionMiddleware::class)->setName('faqe/delete-faqe-delete-2'); // delete
        }
    );

    // review
    $app->map(["GET","POST","OPTIONS"], '/ReviewList[/{reviewID}]', ReviewController::class . ':list')->add(PermissionMiddleware::class)->setName('ReviewList-review-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ReviewAdd[/{reviewID}]', ReviewController::class . ':add')->add(PermissionMiddleware::class)->setName('ReviewAdd-review-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ReviewView[/{reviewID}]', ReviewController::class . ':view')->add(PermissionMiddleware::class)->setName('ReviewView-review-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ReviewEdit[/{reviewID}]', ReviewController::class . ':edit')->add(PermissionMiddleware::class)->setName('ReviewEdit-review-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ReviewDelete[/{reviewID}]', ReviewController::class . ':delete')->add(PermissionMiddleware::class)->setName('ReviewDelete-review-delete'); // delete
    $app->group(
        '/review',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{reviewID}]', ReviewController::class . ':list')->add(PermissionMiddleware::class)->setName('review/list-review-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{reviewID}]', ReviewController::class . ':add')->add(PermissionMiddleware::class)->setName('review/add-review-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{reviewID}]', ReviewController::class . ':view')->add(PermissionMiddleware::class)->setName('review/view-review-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{reviewID}]', ReviewController::class . ':edit')->add(PermissionMiddleware::class)->setName('review/edit-review-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{reviewID}]', ReviewController::class . ':delete')->add(PermissionMiddleware::class)->setName('review/delete-review-delete-2'); // delete
        }
    );

    // makina_importi
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiList[/{mimpID}]', MakinaImportiController::class . ':list')->add(PermissionMiddleware::class)->setName('MakinaImportiList-makina_importi-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiAdd[/{mimpID}]', MakinaImportiController::class . ':add')->add(PermissionMiddleware::class)->setName('MakinaImportiAdd-makina_importi-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiView[/{mimpID}]', MakinaImportiController::class . ':view')->add(PermissionMiddleware::class)->setName('MakinaImportiView-makina_importi-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiEdit[/{mimpID}]', MakinaImportiController::class . ':edit')->add(PermissionMiddleware::class)->setName('MakinaImportiEdit-makina_importi-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiDelete[/{mimpID}]', MakinaImportiController::class . ':delete')->add(PermissionMiddleware::class)->setName('MakinaImportiDelete-makina_importi-delete'); // delete
    $app->group(
        '/makina_importi',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{mimpID}]', MakinaImportiController::class . ':list')->add(PermissionMiddleware::class)->setName('makina_importi/list-makina_importi-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{mimpID}]', MakinaImportiController::class . ':add')->add(PermissionMiddleware::class)->setName('makina_importi/add-makina_importi-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{mimpID}]', MakinaImportiController::class . ':view')->add(PermissionMiddleware::class)->setName('makina_importi/view-makina_importi-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{mimpID}]', MakinaImportiController::class . ':edit')->add(PermissionMiddleware::class)->setName('makina_importi/edit-makina_importi-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{mimpID}]', MakinaImportiController::class . ':delete')->add(PermissionMiddleware::class)->setName('makina_importi/delete-makina_importi-delete-2'); // delete
        }
    );

    // makina_importi_sherbime
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiSherbimeList[/{mishID}]', MakinaImportiSherbimeController::class . ':list')->add(PermissionMiddleware::class)->setName('MakinaImportiSherbimeList-makina_importi_sherbime-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiSherbimeAdd[/{mishID}]', MakinaImportiSherbimeController::class . ':add')->add(PermissionMiddleware::class)->setName('MakinaImportiSherbimeAdd-makina_importi_sherbime-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiSherbimeView[/{mishID}]', MakinaImportiSherbimeController::class . ':view')->add(PermissionMiddleware::class)->setName('MakinaImportiSherbimeView-makina_importi_sherbime-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiSherbimeEdit[/{mishID}]', MakinaImportiSherbimeController::class . ':edit')->add(PermissionMiddleware::class)->setName('MakinaImportiSherbimeEdit-makina_importi_sherbime-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/MakinaImportiSherbimeDelete[/{mishID}]', MakinaImportiSherbimeController::class . ':delete')->add(PermissionMiddleware::class)->setName('MakinaImportiSherbimeDelete-makina_importi_sherbime-delete'); // delete
    $app->group(
        '/makina_importi_sherbime',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{mishID}]', MakinaImportiSherbimeController::class . ':list')->add(PermissionMiddleware::class)->setName('makina_importi_sherbime/list-makina_importi_sherbime-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{mishID}]', MakinaImportiSherbimeController::class . ':add')->add(PermissionMiddleware::class)->setName('makina_importi_sherbime/add-makina_importi_sherbime-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{mishID}]', MakinaImportiSherbimeController::class . ':view')->add(PermissionMiddleware::class)->setName('makina_importi_sherbime/view-makina_importi_sherbime-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{mishID}]', MakinaImportiSherbimeController::class . ':edit')->add(PermissionMiddleware::class)->setName('makina_importi_sherbime/edit-makina_importi_sherbime-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{mishID}]', MakinaImportiSherbimeController::class . ':delete')->add(PermissionMiddleware::class)->setName('makina_importi_sherbime/delete-makina_importi_sherbime-delete-2'); // delete
        }
    );

    // error
    $app->map(["GET","POST","OPTIONS"], '/error', OthersController::class . ':error')->add(PermissionMiddleware::class)->setName('error');

    // personal_data
    $app->map(["GET","POST","OPTIONS"], '/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->map(["GET","POST","OPTIONS"], '/login', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // reset_password
    $app->map(["GET","POST","OPTIONS"], '/resetpassword', OthersController::class . ':resetpassword')->add(PermissionMiddleware::class)->setName('resetpassword');

    // change_password
    $app->map(["GET","POST","OPTIONS"], '/changepassword', OthersController::class . ':changepassword')->add(PermissionMiddleware::class)->setName('changepassword');

    // register
    $app->map(["GET","POST","OPTIONS"], '/register', OthersController::class . ':register')->add(PermissionMiddleware::class)->setName('register');

    // userpriv
    $app->map(["GET","POST","OPTIONS"], '/userpriv', OthersController::class . ':userpriv')->add(PermissionMiddleware::class)->setName('userpriv');

    // logout
    $app->map(["GET","POST","OPTIONS"], '/logout', OthersController::class . ':logout')->add(PermissionMiddleware::class)->setName('logout');

    // Swagger
    $app->get('/' . Config("SWAGGER_ACTION"), OthersController::class . ':swagger')->setName(Config("SWAGGER_ACTION")); // Swagger

    // Index
    $app->get('/[index]', OthersController::class . ':index')->add(PermissionMiddleware::class)->setName('index');

    // Route Action event
    if (function_exists(PROJECT_NAMESPACE . "Route_Action")) {
        Route_Action($app);
    }

    /**
     * Catch-all route to serve a 404 Not Found page if none of the routes match
     * NOTE: Make sure this route is defined last.
     */
    $app->map(
        ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'],
        '/{routes:.+}',
        function ($request, $response, $params) {
            $error = [
                "statusCode" => "404",
                "error" => [
                    "class" => "text-warning",
                    "type" => Container("language")->phrase("Error"),
                    "description" => str_replace("%p", $params["routes"], Container("language")->phrase("PageNotFound")),
                ],
            ];
            Container("flash")->addMessage("error", $error);
            return $response->withStatus(302)->withHeader("Location", GetUrl("error")); // Redirect to error page
        }
    );
};
