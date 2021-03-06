<?php
/**
 * PHPMaker 2022 user level settings
 */
namespace PHPMaker2022\volalservice;

// User level info
$USER_LEVELS = [["-2","Anonymous"],
    ["0","Default"]];
// User level priv info
$USER_LEVEL_PRIVS = [["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}klient","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}klient","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}perdoruesit","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}perdoruesit","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}pjese_kembimi","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}pjese_kembimi","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}servis","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}servis","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}servis_pjeset","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}servis_pjeset","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}servis_sherbime","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}servis_sherbime","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}sherbime","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}sherbime","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}stafi","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}stafi","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}userlevelpermissions","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}userlevelpermissions","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}userlevels","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}userlevels","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}kreu.php","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}kreu.php","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_marka","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_marka","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_modeli","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_modeli","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_shitje","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_shitje","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_tipi","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_tipi","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}menu_dytesore","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}menu_dytesore","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}menu_kryesore","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}menu_kryesore","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}slide","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}slide","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}index_tips","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}index_tips","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}index_psene","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}index_psene","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}prodhues","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}prodhues","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}blog","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}blog","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}blog_kategori","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}blog_kategori","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}faqe","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}faqe","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}review","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}review","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_importi","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_importi","0","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_importi_sherbime","-2","0"],
    ["{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}makina_importi_sherbime","0","0"]];
// User level table info
$USER_LEVEL_TABLES = [["klient","klient","Klient Servisi",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","KlientList"],
    ["makina","makina","Makina Servisi",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MakinaList"],
    ["perdoruesit","perdoruesit","Perdoruesit",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","PerdoruesitList"],
    ["pjese_kembimi","pjese_kembimi","Pjese Kembimi",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","PjeseKembimiList"],
    ["servis","servis","Servis",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","ServisList"],
    ["servis_pjeset","servis_pjeset","Pjeset e Kembimit",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","ServisPjesetList"],
    ["servis_sherbime","servis_sherbime","Sherbimet",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","ServisSherbimeList"],
    ["sherbime","sherbime","Sherbime",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","SherbimeList"],
    ["stafi","stafi","Stafi",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","StafiList"],
    ["userlevelpermissions","userlevelpermissions","Lejet",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","UserlevelpermissionsList"],
    ["userlevels","userlevels","Nivelet",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","UserlevelsList"],
    ["kreu.php","kreu","Kreu",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","Kreu"],
    ["makina_marka","makina_marka","Marka Makinash",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MakinaMarkaList"],
    ["makina_modeli","makina_modeli","Modele Makinash",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MakinaModeliList"],
    ["makina_shitje","makina_shitje","Makina per Shitje",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MakinaShitjeList"],
    ["makina_tipi","makina_tipi","Tipe Makinash",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MakinaTipiList"],
    ["menu_dytesore","menu_dytesore","Menu Dytesore",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MenuDytesoreList"],
    ["menu_kryesore","menu_kryesore","Menu Kryesore",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MenuKryesoreList"],
    ["slide","slide","Slidet per Faqe Kryesore",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","SlideList"],
    ["index_tips","index_tips","Tips & Tricks per Makinat",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","IndexTipsList"],
    ["index_psene","index_psene","Pse Ne",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","IndexPseneList"],
    ["prodhues","prodhues","Prodhuesit e Pjeseve",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","ProdhuesList"],
    ["blog","blog","Artikuj Blogu",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","BlogList"],
    ["blog_kategori","blog_kategori","Kategorite e Blogut",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","BlogKategoriList"],
    ["faqe","faqe","Faqet",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","FaqeList"],
    ["review","review","Review e Klienteve",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","ReviewList"],
    ["makina_importi","makina_importi","Makinat e Importit",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MakinaImportiList"],
    ["makina_importi_sherbime","makina_importi_sherbime","Sherbimet e Makinave te Importit",true,"{5DBF31CF-CE07-42F2-AACA-F5EB0A61CE6E}","MakinaImportiSherbimeList"]];
