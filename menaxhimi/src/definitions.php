<?php

namespace PHPMaker2022\volalservice;

use Slim\Views\PhpRenderer;
use Slim\Csrf\Guard;
use Psr\Container\ContainerInterface;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Doctrine\DBAL\Logging\LoggerChain;
use Doctrine\DBAL\Logging\DebugStack;

return [
    "cache" => function (ContainerInterface $c) {
        return new \Slim\HttpCache\CacheProvider();
    },
    "view" => function (ContainerInterface $c) {
        return new PhpRenderer("views/");
    },
    "flash" => function (ContainerInterface $c) {
        return new \Slim\Flash\Messages();
    },
    "audit" => function (ContainerInterface $c) {
        $logger = new Logger("audit"); // For audit trail
        $logger->pushHandler(new AuditTrailHandler("auditlogs/audit.log"));
        return $logger;
    },
    "log" => function (ContainerInterface $c) {
        global $RELATIVE_PATH;
        $logger = new Logger("log");
        $logger->pushHandler(new RotatingFileHandler($RELATIVE_PATH . "auditlogs/log.log"));
        return $logger;
    },
    "sqllogger" => function (ContainerInterface $c) {
        $loggers = [];
        if (Config("DEBUG")) {
            $loggers[] = $c->get("debugstack");
        }
        return (count($loggers) > 0) ? new LoggerChain($loggers) : null;
    },
    "csrf" => function (ContainerInterface $c) {
        global $ResponseFactory;
        return new Guard($ResponseFactory, Config("CSRF_PREFIX"));
    },
    "debugstack" => \DI\create(DebugStack::class),
    "debugsqllogger" => \DI\create(DebugSqlLogger::class),
    "security" => \DI\create(AdvancedSecurity::class),
    "profile" => \DI\create(UserProfile::class),
    "language" => \DI\create(Language::class),
    "timer" => \DI\create(Timer::class),
    "session" => \DI\create(HttpSession::class),

    // Tables
    "klient" => \DI\create(Klient::class),
    "makina" => \DI\create(Makina::class),
    "perdoruesit" => \DI\create(Perdoruesit::class),
    "pjese_kembimi" => \DI\create(PjeseKembimi::class),
    "servis" => \DI\create(Servis::class),
    "servis_pjeset" => \DI\create(ServisPjeset::class),
    "servis_sherbime" => \DI\create(ServisSherbime::class),
    "sherbime" => \DI\create(Sherbime::class),
    "stafi" => \DI\create(Stafi::class),
    "userlevelpermissions" => \DI\create(Userlevelpermissions::class),
    "userlevels" => \DI\create(Userlevels::class),
    "kreu" => \DI\create(Kreu::class),
    "makina_marka" => \DI\create(MakinaMarka::class),
    "makina_modeli" => \DI\create(MakinaModeli::class),
    "makina_shitje" => \DI\create(MakinaShitje::class),
    "makina_tipi" => \DI\create(MakinaTipi::class),
    "menu_dytesore" => \DI\create(MenuDytesore::class),
    "menu_kryesore" => \DI\create(MenuKryesore::class),
    "slide" => \DI\create(Slide::class),
    "index_tips" => \DI\create(IndexTips::class),
    "index_psene" => \DI\create(IndexPsene::class),
    "prodhues" => \DI\create(Prodhues::class),
    "blog" => \DI\create(Blog::class),
    "blog_kategori" => \DI\create(BlogKategori::class),
    "faqe" => \DI\create(Faqe::class),
    "review" => \DI\create(Review::class),
    "makina_importi" => \DI\create(MakinaImporti::class),
    "makina_importi_sherbime" => \DI\create(MakinaImportiSherbime::class),

    // User table
    "usertable" => \DI\get("perdoruesit"),
];
