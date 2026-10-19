<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Sustituye estos valores por los datos de conexión de tu servidor.
// Este archivo contiene credenciales: NO lo subas a Git.
$mysqli = new mysqli('localhost', 'root', '123456', 'agenda');
$mysqli->set_charset('utf8mb4');
