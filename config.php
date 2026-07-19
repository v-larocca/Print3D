<?php


// PRINT3D - File di Configurazione

// ----------Database-----------------------------
define('DB_HOST', 'localhost');
define('DB_NAME', 'print3d');
define('DB_USER', 'root');
define('DB_PASS', '');

// ----------Sessione----------------------------
define('COOKIE_EXP_TIME', 2592000); // 30 giorni in secondi

// -------Immagini di anteprima------------------
define('IMAGE_MAX_SIZE', 5 * 1024 * 1024);
define('IMAGE_ALLOWED_TYPES', ['image/jpeg', 'image/png', 'image/jpg', 'image/webp']);
define('IMAGE_ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'webp']);

// --------File ZIP progetti-----------------------
define('ZIP_MAX_SIZE', 150 * 1024 * 1024);
define('ZIP_MAX_UNCOMPRESSED_SIZE', 500 * 1024 * 1024);
define('ZIP_MAX_COMPRESSION_RATIO', 10);
define('ZIP_ALLOWED_TYPES', [
    'application/zip',
    'application/x-zip-compressed',
    'application/x-zip',
    'multipart/x-zip'
]);
define('ZIP_ALLOWED_EXTENSIONS', ['zip']);
