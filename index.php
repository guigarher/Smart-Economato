<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Productos</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
  </style>
</head>
<body>
  <h1>Listado de productos</h1>

  <p><a href="formulario.php">+ Añadir nuevo producto</a></p>

  <table>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Código de barras</th>
      <th>PVP (€)</th>
      <th>Fecha ingreso</th>
      <th>Imagen</th>
    </tr>
    <?php
    require 'db.php';
    $productos = $pdo->query("SELECT * FROM producto ORDER BY id DESC")->fetchAll();
    foreach ($productos as $p):
    ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['nombre']) ?></td>
        <td><?= htmlspecialchars($p['codigo_barra']) ?></td>
        <td><?= number_format($p['pvp'], 2) ?></td>
        <td><?= $p['fecha_ingreso'] ?></td>
        <td>
          <?php if (!empty($p['imagen'])): ?>
            <img src="<?= htmlspecialchars($p['imagen']) ?>" alt="Imagen" style="max-height:50px;">
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
