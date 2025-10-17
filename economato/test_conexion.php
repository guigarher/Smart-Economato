<?php
require 'db.php';

echo "<h2>Conexión OK ✅</h2>";

$info = $pdo->query("SELECT DATABASE() AS db, VERSION() AS version")->fetch();
echo "<p>Base actual: <b>{$info['db']}</b></p>";
echo "<p>MySQL versión: <b>{$info['version']}</b></p>";

$cuenta = $pdo->query("SELECT COUNT(*) AS total FROM producto")->fetch();
echo "<p>Filas en producto: <b>{$cuenta['total']}</b></p>";
