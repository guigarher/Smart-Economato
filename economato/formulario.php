<?php
require 'db.php';

$errores = [];

// Cuando el formulario se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $codigo = trim($_POST['codigo_barra'] ?? '');
  $pvp    = floatval($_POST['pvp'] ?? 0);
  $rutaImagen = null;

  if ($nombre === '') {
    $errores[] = "El nombre es obligatorio.";
  }

  // Procesar imagen si se subió
  if (!empty($_FILES['imagen']['name'])) {
    $carpetaUploads = __DIR__ . '/uploads/';
    if (!is_dir($carpetaUploads)) {
      mkdir($carpetaUploads, 0775, true);
    }

    $nombreSeguro = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/','_', $_FILES['imagen']['name']);
    $rutaDestino = $carpetaUploads . $nombreSeguro;

    if (is_uploaded_file($_FILES['imagen']['tmp_name'])) {
      if (move_uploaded_file($_FILES['imagen']['tmp_name'], $rutaDestino)) {
        $rutaImagen = 'uploads/' . $nombreSeguro; // ruta relativa para mostrar en la web
      } else {
        $errores[] = "Error al guardar la imagen.";
      }
    } else {
      $errores[] = "Subida de imagen no válida.";
    }
  }

  // Si no hay errores, guardamos en la base de datos
  if (!$errores) {
    try {
      $stmt = $pdo->prepare(
        "INSERT INTO producto (nombre, codigo_barra, imagen, pvp)
         VALUES (:nombre, :codigo, :imagen, :pvp)"
      );
      $stmt->execute([
        ':nombre' => $nombre,
        ':codigo' => $codigo !== '' ? $codigo : null,
        ':imagen' => $rutaImagen,
        ':pvp'    => $pvp
      ]);
      header("Location: index.php?ok=1");
      exit;
    } catch (PDOException $e) {
      $errores[] = "Error al guardar: " . $e->getMessage();
    }
  }
}
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo producto</title>
  <style>
    body { font-family: Arial; margin: 2rem; }
    form { max-width: 400px; }
    label { display: block; margin-top: 10px; }
  </style>
</head>
<body>

<h1>Nuevo producto</h1>

<?php if ($errores): ?>
  <ul style="color:red;">
    <?php foreach ($errores as $er): ?>
      <li><?= htmlspecialchars($er) ?></li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <label>Nombre:
    <input type="text" name="nombre" required>
  </label>

  <label>Código de barras:
    <input type="text" name="codigo_barra">
  </label>

  <label>PVP (€):
    <input type="number" step="0.01" name="pvp" required>
  </label>

  <label>Imagen:
    <input type="file" name="imagen" accept="image/*">
  </label>

  <br>
  <button type="submit">Guardar producto</button>
</form>

<p><a href="index.php">← Volver al listado</a></p>

</body>
</html>
