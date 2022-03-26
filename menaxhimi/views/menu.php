<?php

namespace PHPMaker2022\volalservice;

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
$topMenu->addMenuItem(22, "mci_Kreu", $MenuLanguage->MenuPhrase("22", "MenuText"), $MenuRelativePath . "/menaxhimi/Kreu", -1, "", IsLoggedIn(), false, true, "fas fa-home", "", true, false);
$topMenu->addMenuItem(75, "mci_Faqja_Kryesore", $MenuLanguage->MenuPhrase("75", "MenuText"), $MenuRelativePath . "/", -1, "", IsLoggedIn(), false, true, "fas fa-globe-europe", "", true, false);
$topMenu->addMenuItem(17, "mci_Makina_ne_Shitje", $MenuLanguage->MenuPhrase("17", "MenuText"), $MenuRelativePath . "/menaxhimi/MakinaShitjeList", -1, "", IsLoggedIn(), false, true, "fas fa-car", "", true, false);
$topMenu->addMenuItem(16, "mci_Makina_Importi", $MenuLanguage->MenuPhrase("16", "MenuText"), $MenuRelativePath . "/menaxhimi/MakinaImportiList", -1, "", IsLoggedIn(), false, true, "fas fa-globe-europe", "", true, false);
$topMenu->addMenuItem(18, "mci_Klientet", $MenuLanguage->MenuPhrase("18", "MenuText"), $MenuRelativePath . "/menaxhimi/KlientList", -1, "", IsLoggedIn(), false, true, "fas fa-user-circle", "", true, false);
$topMenu->addMenuItem(19, "mci_Makinat", $MenuLanguage->MenuPhrase("19", "MenuText"), $MenuRelativePath . "/menaxhimi/MakinaList", -1, "", IsLoggedIn(), false, true, "fas fa-car", "", true, false);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(23, "mi_kreu", $MenuLanguage->MenuPhrase("23", "MenuText"), $MenuRelativePath . "Kreu", -1, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}kreu.php'), false, false, "fas fa-home", "", false, true);
$sideMenu->addMenuItem(26, "mi_makina_shitje", $MenuLanguage->MenuPhrase("26", "MenuText"), $MenuRelativePath . "MakinaShitjeList", -1, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_shitje'), false, false, "fas fa-car", "", false, true);
$sideMenu->addMenuItem(111, "mi_makina_importi", $MenuLanguage->MenuPhrase("111", "MenuText"), $MenuRelativePath . "MakinaImportiList", -1, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_importi'), false, false, "fas fa-globe-europe", "", false, true);
$sideMenu->addMenuItem(49, "mci_Servisi", $MenuLanguage->MenuPhrase("49", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "fas fa-tools", "", false, true);
$sideMenu->addMenuItem(5, "mi_servis", $MenuLanguage->MenuPhrase("5", "MenuText"), $MenuRelativePath . "ServisList", 49, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}servis'), false, false, "", "", false, true);
$sideMenu->addMenuItem(2, "mi_makina", $MenuLanguage->MenuPhrase("2", "MenuText"), $MenuRelativePath . "MakinaList", 49, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina'), false, false, "", "", false, true);
$sideMenu->addMenuItem(8, "mi_sherbime", $MenuLanguage->MenuPhrase("8", "MenuText"), $MenuRelativePath . "SherbimeList", 49, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}sherbime'), false, false, "", "", false, true);
$sideMenu->addMenuItem(1, "mi_klient", $MenuLanguage->MenuPhrase("1", "MenuText"), $MenuRelativePath . "KlientList", 49, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}klient'), false, false, "", "", false, true);
$sideMenu->addMenuItem(4, "mi_pjese_kembimi", $MenuLanguage->MenuPhrase("4", "MenuText"), $MenuRelativePath . "PjeseKembimiList", -1, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}pjese_kembimi'), false, false, "fas fa-sync-alt", "", false, true);
$sideMenu->addMenuItem(127, "mci_Front-End", $MenuLanguage->MenuPhrase("127", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "fas fa-desktop", "", false, true);
$sideMenu->addMenuItem(48, "mci_Menuja", $MenuLanguage->MenuPhrase("48", "MenuText"), "", 127, "", IsLoggedIn(), false, true, "fas fa-th-list", "", false, true);
$sideMenu->addMenuItem(29, "mi_menu_kryesore", $MenuLanguage->MenuPhrase("29", "MenuText"), $MenuRelativePath . "MenuKryesoreList", 48, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}menu_kryesore'), false, false, "", "", false, true);
$sideMenu->addMenuItem(28, "mi_menu_dytesore", $MenuLanguage->MenuPhrase("28", "MenuText"), $MenuRelativePath . "MenuDytesoreList", 48, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}menu_dytesore'), false, false, "", "", false, true);
$sideMenu->addMenuItem(30, "mi_slide", $MenuLanguage->MenuPhrase("30", "MenuText"), $MenuRelativePath . "SlideList", 127, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}slide'), false, false, "fab fa-slideshare", "", false, true);
$sideMenu->addMenuItem(109, "mi_faqe", $MenuLanguage->MenuPhrase("109", "MenuText"), $MenuRelativePath . "FaqeList", 127, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}faqe'), false, false, "fas fa-file-alt", "", false, true);
$sideMenu->addMenuItem(108, "mci_Blog", $MenuLanguage->MenuPhrase("108", "MenuText"), "", 127, "", IsLoggedIn(), false, true, "fas fa-keyboard", "", false, true);
$sideMenu->addMenuItem(84, "mi_blog", $MenuLanguage->MenuPhrase("84", "MenuText"), $MenuRelativePath . "BlogList", 108, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}blog'), false, false, "", "", false, true);
$sideMenu->addMenuItem(85, "mi_blog_kategori", $MenuLanguage->MenuPhrase("85", "MenuText"), $MenuRelativePath . "BlogKategoriList", 108, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}blog_kategori'), false, false, "", "", false, true);
$sideMenu->addMenuItem(81, "mci_Blloqe_Index", $MenuLanguage->MenuPhrase("81", "MenuText"), "", 127, "", IsLoggedIn(), false, true, "fas fa-shapes", "", false, true);
$sideMenu->addMenuItem(76, "mi_index_tips", $MenuLanguage->MenuPhrase("76", "MenuText"), $MenuRelativePath . "IndexTipsList", 81, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}index_tips'), false, false, "", "", false, true);
$sideMenu->addMenuItem(82, "mi_index_psene", $MenuLanguage->MenuPhrase("82", "MenuText"), $MenuRelativePath . "IndexPseneList", 81, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}index_psene'), false, false, "", "", false, true);
$sideMenu->addMenuItem(110, "mi_review", $MenuLanguage->MenuPhrase("110", "MenuText"), $MenuRelativePath . "ReviewList", 127, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}review'), false, false, "fas fa-comments", "", false, true);
$sideMenu->addMenuItem(47, "mci_Konfigurime", $MenuLanguage->MenuPhrase("47", "MenuText"), "", -1, "", IsLoggedIn(), false, true, "fas fa-cogs", "", false, true);
$sideMenu->addMenuItem(24, "mi_makina_marka", $MenuLanguage->MenuPhrase("24", "MenuText"), $MenuRelativePath . "MakinaMarkaList", 47, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_marka'), false, false, "", "", false, true);
$sideMenu->addMenuItem(25, "mi_makina_modeli", $MenuLanguage->MenuPhrase("25", "MenuText"), $MenuRelativePath . "MakinaModeliList", 47, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_modeli'), false, false, "", "", false, true);
$sideMenu->addMenuItem(27, "mi_makina_tipi", $MenuLanguage->MenuPhrase("27", "MenuText"), $MenuRelativePath . "MakinaTipiList", 47, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_tipi'), false, false, "", "", false, true);
$sideMenu->addMenuItem(83, "mi_prodhues", $MenuLanguage->MenuPhrase("83", "MenuText"), $MenuRelativePath . "ProdhuesList", 47, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}prodhues'), false, false, "", "", false, true);
$sideMenu->addMenuItem(9, "mi_stafi", $MenuLanguage->MenuPhrase("9", "MenuText"), $MenuRelativePath . "StafiList", 47, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}stafi'), false, false, "fas fa-users-cog", "", false, true);
$sideMenu->addMenuItem(3, "mi_perdoruesit", $MenuLanguage->MenuPhrase("3", "MenuText"), $MenuRelativePath . "PerdoruesitList", 47, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}perdoruesit'), false, false, "fas fa-user-lock", "", false, true);
$sideMenu->addMenuItem(10, "mi_userlevelpermissions", $MenuLanguage->MenuPhrase("10", "MenuText"), $MenuRelativePath . "UserlevelpermissionsList", 3, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}userlevelpermissions'), false, false, "", "", false, true);
$sideMenu->addMenuItem(11, "mi_userlevels", $MenuLanguage->MenuPhrase("11", "MenuText"), $MenuRelativePath . "UserlevelsList", 3, "", AllowListMenu('{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}userlevels'), false, false, "", "", false, true);
$sideMenu->addMenuItem(22, "mci_Kreu", $MenuLanguage->MenuPhrase("22", "MenuText"), $MenuRelativePath . "/menaxhimi/Kreu", -1, "", IsLoggedIn(), false, true, "fas fa-home", "", true, true);
$sideMenu->addMenuItem(75, "mci_Faqja_Kryesore", $MenuLanguage->MenuPhrase("75", "MenuText"), $MenuRelativePath . "/", -1, "", IsLoggedIn(), false, true, "fas fa-globe-europe", "", true, true);
$sideMenu->addMenuItem(17, "mci_Makina_ne_Shitje", $MenuLanguage->MenuPhrase("17", "MenuText"), $MenuRelativePath . "/menaxhimi/MakinaShitjeList", -1, "", IsLoggedIn(), false, true, "fas fa-car", "", true, true);
$sideMenu->addMenuItem(16, "mci_Makina_Importi", $MenuLanguage->MenuPhrase("16", "MenuText"), $MenuRelativePath . "/menaxhimi/MakinaImportiList", -1, "", IsLoggedIn(), false, true, "fas fa-globe-europe", "", true, true);
$sideMenu->addMenuItem(18, "mci_Klientet", $MenuLanguage->MenuPhrase("18", "MenuText"), $MenuRelativePath . "/menaxhimi/KlientList", -1, "", IsLoggedIn(), false, true, "fas fa-user-circle", "", true, true);
$sideMenu->addMenuItem(19, "mci_Makinat", $MenuLanguage->MenuPhrase("19", "MenuText"), $MenuRelativePath . "/menaxhimi/MakinaList", -1, "", IsLoggedIn(), false, true, "fas fa-car", "", true, true);
echo $sideMenu->toScript();
