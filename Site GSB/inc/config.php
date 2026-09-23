<?php
// Configuration de la base de données
// Modifiez ces constantes en fonction de votre environnement.

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'gsbV2');
define('DB_USER', 'slam');
define('DB_PASS', 'password');
define('DB_CHARSET', 'utf8mb4');

// Paramètres de session
define('SESSION_NAME', 'gsb_session');

// Contrôle des erreurs en production
define('DISPLAY_ERRORS', true);

if (DISPLAY_ERRORS) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}
