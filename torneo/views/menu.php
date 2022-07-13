<?php

namespace PHPMaker2022\project1;

// Menu Language
if ($Language && function_exists(PROJECT_NAMESPACE . "Config") && $Language->LanguageFolder == Config("LANGUAGE_FOLDER")) {
    $MenuRelativePath = "";
    $MenuLanguage = &$Language;
} else { // Compat reports
    $LANGUAGE_FOLDER = "../lang/";
    $MenuRelativePath = "../";
    $MenuLanguage = Container("language");
}

// Navbar menu
$topMenu = new Menu("navbar", true, true);
$topMenu->addMenuItem(2, "mi_encuesta", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "EncuestaList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}encuesta'), false, false, "", "", true, false);
$topMenu->addMenuItem(3, "mi_equipo", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "EquipoList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}equipo'), false, false, "", "", true, false);
$topMenu->addMenuItem(4, "mi_equipotorneo", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "EquipotorneoList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}equipotorneo'), false, false, "", "", true, false);
$topMenu->addMenuItem(5, "mi_participante", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "ParticipanteList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}participante'), false, false, "", "", true, false);
$topMenu->addMenuItem(6, "mi_partidos", $MenuLanguage->MenuPhrase("6", "MenuText"), $MenuRelativePath . "PartidosList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}partidos'), false, false, "", "", true, false);
$topMenu->addMenuItem(7, "mi_torneo", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "TorneoList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}torneo'), false, false, "", "", true, false);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(2, "mi_encuesta", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "EncuestaList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}encuesta'), false, false, "", "", true, true);
$sideMenu->addMenuItem(3, "mi_equipo", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "EquipoList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}equipo'), false, false, "", "", true, true);
$sideMenu->addMenuItem(4, "mi_equipotorneo", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "EquipotorneoList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}equipotorneo'), false, false, "", "", true, true);
$sideMenu->addMenuItem(5, "mi_participante", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "ParticipanteList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}participante'), false, false, "", "", true, true);
$sideMenu->addMenuItem(6, "mi_partidos", $MenuLanguage->MenuPhrase("6", "MenuText"), $MenuRelativePath . "PartidosList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}partidos'), false, false, "", "", true, true);
$sideMenu->addMenuItem(7, "mi_torneo", $MenuLanguage->MenuPhrase("7", "MenuText"), $MenuRelativePath . "TorneoList", -1, "", IsLoggedIn() || AllowListMenu('{E0BE5F69-72DF-4732-B801-A5C48B4AD2BD}torneo'), false, false, "", "", true, true);
echo $sideMenu->toScript();
