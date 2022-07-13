<?php

namespace PHPMaker2022\project1;

use Slim\App;
use Slim\Routing\RouteCollectorProxy;

// Handle Routes
return function (App $app) {
    // audittrail
    $app->map(["GET","POST","OPTIONS"], '/AudittrailList[/{id}]', AudittrailController::class . ':list')->add(PermissionMiddleware::class)->setName('AudittrailList-audittrail-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/AudittrailAdd[/{id}]', AudittrailController::class . ':add')->add(PermissionMiddleware::class)->setName('AudittrailAdd-audittrail-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/AudittrailView[/{id}]', AudittrailController::class . ':view')->add(PermissionMiddleware::class)->setName('AudittrailView-audittrail-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/AudittrailEdit[/{id}]', AudittrailController::class . ':edit')->add(PermissionMiddleware::class)->setName('AudittrailEdit-audittrail-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/AudittrailDelete[/{id}]', AudittrailController::class . ':delete')->add(PermissionMiddleware::class)->setName('AudittrailDelete-audittrail-delete'); // delete
    $app->group(
        '/audittrail',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{id}]', AudittrailController::class . ':list')->add(PermissionMiddleware::class)->setName('audittrail/list-audittrail-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{id}]', AudittrailController::class . ':add')->add(PermissionMiddleware::class)->setName('audittrail/add-audittrail-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{id}]', AudittrailController::class . ':view')->add(PermissionMiddleware::class)->setName('audittrail/view-audittrail-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{id}]', AudittrailController::class . ':edit')->add(PermissionMiddleware::class)->setName('audittrail/edit-audittrail-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{id}]', AudittrailController::class . ':delete')->add(PermissionMiddleware::class)->setName('audittrail/delete-audittrail-delete-2'); // delete
        }
    );

    // encuesta
    $app->map(["GET","POST","OPTIONS"], '/EncuestaList[/{ID_ENCUESTA}]', EncuestaController::class . ':list')->add(PermissionMiddleware::class)->setName('EncuestaList-encuesta-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/EncuestaAdd[/{ID_ENCUESTA}]', EncuestaController::class . ':add')->add(PermissionMiddleware::class)->setName('EncuestaAdd-encuesta-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/EncuestaView[/{ID_ENCUESTA}]', EncuestaController::class . ':view')->add(PermissionMiddleware::class)->setName('EncuestaView-encuesta-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/EncuestaEdit[/{ID_ENCUESTA}]', EncuestaController::class . ':edit')->add(PermissionMiddleware::class)->setName('EncuestaEdit-encuesta-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/EncuestaDelete[/{ID_ENCUESTA}]', EncuestaController::class . ':delete')->add(PermissionMiddleware::class)->setName('EncuestaDelete-encuesta-delete'); // delete
    $app->group(
        '/encuesta',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{ID_ENCUESTA}]', EncuestaController::class . ':list')->add(PermissionMiddleware::class)->setName('encuesta/list-encuesta-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{ID_ENCUESTA}]', EncuestaController::class . ':add')->add(PermissionMiddleware::class)->setName('encuesta/add-encuesta-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{ID_ENCUESTA}]', EncuestaController::class . ':view')->add(PermissionMiddleware::class)->setName('encuesta/view-encuesta-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{ID_ENCUESTA}]', EncuestaController::class . ':edit')->add(PermissionMiddleware::class)->setName('encuesta/edit-encuesta-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{ID_ENCUESTA}]', EncuestaController::class . ':delete')->add(PermissionMiddleware::class)->setName('encuesta/delete-encuesta-delete-2'); // delete
        }
    );

    // equipo
    $app->map(["GET","POST","OPTIONS"], '/EquipoList[/{ID_EQUIPO}]', EquipoController::class . ':list')->add(PermissionMiddleware::class)->setName('EquipoList-equipo-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/EquipoAdd[/{ID_EQUIPO}]', EquipoController::class . ':add')->add(PermissionMiddleware::class)->setName('EquipoAdd-equipo-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/EquipoView[/{ID_EQUIPO}]', EquipoController::class . ':view')->add(PermissionMiddleware::class)->setName('EquipoView-equipo-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/EquipoEdit[/{ID_EQUIPO}]', EquipoController::class . ':edit')->add(PermissionMiddleware::class)->setName('EquipoEdit-equipo-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/EquipoDelete[/{ID_EQUIPO}]', EquipoController::class . ':delete')->add(PermissionMiddleware::class)->setName('EquipoDelete-equipo-delete'); // delete
    $app->group(
        '/equipo',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{ID_EQUIPO}]', EquipoController::class . ':list')->add(PermissionMiddleware::class)->setName('equipo/list-equipo-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{ID_EQUIPO}]', EquipoController::class . ':add')->add(PermissionMiddleware::class)->setName('equipo/add-equipo-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{ID_EQUIPO}]', EquipoController::class . ':view')->add(PermissionMiddleware::class)->setName('equipo/view-equipo-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{ID_EQUIPO}]', EquipoController::class . ':edit')->add(PermissionMiddleware::class)->setName('equipo/edit-equipo-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{ID_EQUIPO}]', EquipoController::class . ':delete')->add(PermissionMiddleware::class)->setName('equipo/delete-equipo-delete-2'); // delete
        }
    );

    // equipotorneo
    $app->map(["GET","POST","OPTIONS"], '/EquipotorneoList[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':list')->add(PermissionMiddleware::class)->setName('EquipotorneoList-equipotorneo-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/EquipotorneoAdd[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':add')->add(PermissionMiddleware::class)->setName('EquipotorneoAdd-equipotorneo-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/EquipotorneoView[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':view')->add(PermissionMiddleware::class)->setName('EquipotorneoView-equipotorneo-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/EquipotorneoEdit[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':edit')->add(PermissionMiddleware::class)->setName('EquipotorneoEdit-equipotorneo-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/EquipotorneoDelete[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':delete')->add(PermissionMiddleware::class)->setName('EquipotorneoDelete-equipotorneo-delete'); // delete
    $app->group(
        '/equipotorneo',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':list')->add(PermissionMiddleware::class)->setName('equipotorneo/list-equipotorneo-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':add')->add(PermissionMiddleware::class)->setName('equipotorneo/add-equipotorneo-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':view')->add(PermissionMiddleware::class)->setName('equipotorneo/view-equipotorneo-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':edit')->add(PermissionMiddleware::class)->setName('equipotorneo/edit-equipotorneo-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{ID_EQUIPO_TORNEO}]', EquipotorneoController::class . ':delete')->add(PermissionMiddleware::class)->setName('equipotorneo/delete-equipotorneo-delete-2'); // delete
        }
    );

    // participante
    $app->map(["GET","POST","OPTIONS"], '/ParticipanteList[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':list')->add(PermissionMiddleware::class)->setName('ParticipanteList-participante-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/ParticipanteAdd[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':add')->add(PermissionMiddleware::class)->setName('ParticipanteAdd-participante-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/ParticipanteView[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':view')->add(PermissionMiddleware::class)->setName('ParticipanteView-participante-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/ParticipanteEdit[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':edit')->add(PermissionMiddleware::class)->setName('ParticipanteEdit-participante-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/ParticipanteDelete[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':delete')->add(PermissionMiddleware::class)->setName('ParticipanteDelete-participante-delete'); // delete
    $app->group(
        '/participante',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':list')->add(PermissionMiddleware::class)->setName('participante/list-participante-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':add')->add(PermissionMiddleware::class)->setName('participante/add-participante-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':view')->add(PermissionMiddleware::class)->setName('participante/view-participante-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':edit')->add(PermissionMiddleware::class)->setName('participante/edit-participante-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{ID_PARTICIPANTE}]', ParticipanteController::class . ':delete')->add(PermissionMiddleware::class)->setName('participante/delete-participante-delete-2'); // delete
        }
    );

    // partidos
    $app->map(["GET","POST","OPTIONS"], '/PartidosList[/{ID_PARTIDO}]', PartidosController::class . ':list')->add(PermissionMiddleware::class)->setName('PartidosList-partidos-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/PartidosAdd[/{ID_PARTIDO}]', PartidosController::class . ':add')->add(PermissionMiddleware::class)->setName('PartidosAdd-partidos-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/PartidosView[/{ID_PARTIDO}]', PartidosController::class . ':view')->add(PermissionMiddleware::class)->setName('PartidosView-partidos-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/PartidosEdit[/{ID_PARTIDO}]', PartidosController::class . ':edit')->add(PermissionMiddleware::class)->setName('PartidosEdit-partidos-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/PartidosDelete[/{ID_PARTIDO}]', PartidosController::class . ':delete')->add(PermissionMiddleware::class)->setName('PartidosDelete-partidos-delete'); // delete
    $app->group(
        '/partidos',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{ID_PARTIDO}]', PartidosController::class . ':list')->add(PermissionMiddleware::class)->setName('partidos/list-partidos-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{ID_PARTIDO}]', PartidosController::class . ':add')->add(PermissionMiddleware::class)->setName('partidos/add-partidos-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{ID_PARTIDO}]', PartidosController::class . ':view')->add(PermissionMiddleware::class)->setName('partidos/view-partidos-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{ID_PARTIDO}]', PartidosController::class . ':edit')->add(PermissionMiddleware::class)->setName('partidos/edit-partidos-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{ID_PARTIDO}]', PartidosController::class . ':delete')->add(PermissionMiddleware::class)->setName('partidos/delete-partidos-delete-2'); // delete
        }
    );

    // torneo
    $app->map(["GET","POST","OPTIONS"], '/TorneoList[/{ID_TORNEO}]', TorneoController::class . ':list')->add(PermissionMiddleware::class)->setName('TorneoList-torneo-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/TorneoAdd[/{ID_TORNEO}]', TorneoController::class . ':add')->add(PermissionMiddleware::class)->setName('TorneoAdd-torneo-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/TorneoView[/{ID_TORNEO}]', TorneoController::class . ':view')->add(PermissionMiddleware::class)->setName('TorneoView-torneo-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/TorneoEdit[/{ID_TORNEO}]', TorneoController::class . ':edit')->add(PermissionMiddleware::class)->setName('TorneoEdit-torneo-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/TorneoDelete[/{ID_TORNEO}]', TorneoController::class . ':delete')->add(PermissionMiddleware::class)->setName('TorneoDelete-torneo-delete'); // delete
    $app->group(
        '/torneo',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{ID_TORNEO}]', TorneoController::class . ':list')->add(PermissionMiddleware::class)->setName('torneo/list-torneo-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{ID_TORNEO}]', TorneoController::class . ':add')->add(PermissionMiddleware::class)->setName('torneo/add-torneo-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{ID_TORNEO}]', TorneoController::class . ':view')->add(PermissionMiddleware::class)->setName('torneo/view-torneo-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{ID_TORNEO}]', TorneoController::class . ':edit')->add(PermissionMiddleware::class)->setName('torneo/edit-torneo-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{ID_TORNEO}]', TorneoController::class . ':delete')->add(PermissionMiddleware::class)->setName('torneo/delete-torneo-delete-2'); // delete
        }
    );

    // usuario
    $app->map(["GET","POST","OPTIONS"], '/UsuarioList[/{ID_USUARIO}]', UsuarioController::class . ':list')->add(PermissionMiddleware::class)->setName('UsuarioList-usuario-list'); // list
    $app->map(["GET","POST","OPTIONS"], '/UsuarioAdd[/{ID_USUARIO}]', UsuarioController::class . ':add')->add(PermissionMiddleware::class)->setName('UsuarioAdd-usuario-add'); // add
    $app->map(["GET","POST","OPTIONS"], '/UsuarioView[/{ID_USUARIO}]', UsuarioController::class . ':view')->add(PermissionMiddleware::class)->setName('UsuarioView-usuario-view'); // view
    $app->map(["GET","POST","OPTIONS"], '/UsuarioEdit[/{ID_USUARIO}]', UsuarioController::class . ':edit')->add(PermissionMiddleware::class)->setName('UsuarioEdit-usuario-edit'); // edit
    $app->map(["GET","POST","OPTIONS"], '/UsuarioDelete[/{ID_USUARIO}]', UsuarioController::class . ':delete')->add(PermissionMiddleware::class)->setName('UsuarioDelete-usuario-delete'); // delete
    $app->group(
        '/usuario',
        function (RouteCollectorProxy $group) {
            $group->map(["GET","POST","OPTIONS"], '/' . Config("LIST_ACTION") . '[/{ID_USUARIO}]', UsuarioController::class . ':list')->add(PermissionMiddleware::class)->setName('usuario/list-usuario-list-2'); // list
            $group->map(["GET","POST","OPTIONS"], '/' . Config("ADD_ACTION") . '[/{ID_USUARIO}]', UsuarioController::class . ':add')->add(PermissionMiddleware::class)->setName('usuario/add-usuario-add-2'); // add
            $group->map(["GET","POST","OPTIONS"], '/' . Config("VIEW_ACTION") . '[/{ID_USUARIO}]', UsuarioController::class . ':view')->add(PermissionMiddleware::class)->setName('usuario/view-usuario-view-2'); // view
            $group->map(["GET","POST","OPTIONS"], '/' . Config("EDIT_ACTION") . '[/{ID_USUARIO}]', UsuarioController::class . ':edit')->add(PermissionMiddleware::class)->setName('usuario/edit-usuario-edit-2'); // edit
            $group->map(["GET","POST","OPTIONS"], '/' . Config("DELETE_ACTION") . '[/{ID_USUARIO}]', UsuarioController::class . ':delete')->add(PermissionMiddleware::class)->setName('usuario/delete-usuario-delete-2'); // delete
        }
    );

    // error
    $app->map(["GET","POST","OPTIONS"], '/error', OthersController::class . ':error')->add(PermissionMiddleware::class)->setName('error');

    // personal_data
    $app->map(["GET","POST","OPTIONS"], '/personaldata', OthersController::class . ':personaldata')->add(PermissionMiddleware::class)->setName('personaldata');

    // login
    $app->map(["GET","POST","OPTIONS"], '/login', OthersController::class . ':login')->add(PermissionMiddleware::class)->setName('login');

    // change_password
    $app->map(["GET","POST","OPTIONS"], '/changepassword', OthersController::class . ':changepassword')->add(PermissionMiddleware::class)->setName('changepassword');

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
