<?php
include("menu.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notas Bonitas</title>
  <style>
    body {
  font-family: Arial, sans-serif;
  line-height: 1.6;
  margin: 0;
  padding: 0;
  background-color: #f4f4f4;
  color: #333;
}

header {
  background-color: #af4c7a;
  color: white;
  padding: 10px 20px;
  text-align: center;
}

h1, h2 {
  margin: 0;
}

.note {
  background: linear-gradient(135deg, #ffe4e1, #ffe0b3);
  margin-bottom: 20px;
  padding: 15px;
  border: 2px solid #af4c7a;
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  position: relative;
}

.note::before {
  content: "";
  position: absolute;
  top: -10px;
  left: -10px;
  right: -10px;
  bottom: -10px;
  border: 2px dashed #933c64;
  border-radius: 20px;
  z-index: -1;
}

.note-name {
  font-size: 1.2rem;
  font-weight: bold;
  color: #5d3c61;
}

.note-message {
  font-size: 1rem;
  margin: 10px 0;
  line-height: 1.5;
  color: #333;
}

.note-date {
  font-size: 0.85rem;
  color: #666;
  text-align: right;
}

/* Modal */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

.modal-content h2 {
  margin-top: 0;
}

.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

  .btn-modal {
    background-color: #af4c7a;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: block;
    margin: 20px auto;
    text-align: center;
  }

  .btn-modal:hover {
    background-color: #933c64;
  }

  </style>
</head>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notas Bonitas</title>
  <link rel="stylesheet" href="styles.css"> <!-- Archivo CSS externo -->
</head>
<body>
  <header>
    <h1>Notas Bonitas</h1>
  </header>

  <main>
    <section class="notes">
      <h2>Lista de Notas</h2>
      <p>Aquí podrás ver las notas que se han agregado:</p>
      <div class="note-list">
        <?php
          $file_path = "notas.txt";
          if (file_exists($file_path) && is_readable($file_path)) {
              $file = fopen($file_path, "r");
              while (!feof($file)) {
                  $linea = fgets($file);
                  $datos = explode("|", $linea);
                  if (count($datos) === 3) {
                      list($nombre, $mensaje, $fecha) = $datos;
                      echo "<article class='note' style='border: 1px solid #ddd; border-radius: 5px; padding: 10px; margin-bottom: 10px;'>";
                      echo "<p class='note-name'><strong>Nombre:</strong> $nombre</p>";
                      echo "<p class='note-message'><strong>Mensaje:</strong> $mensaje</p>";
                      echo "<p class='note-date'><small><strong>Fecha:</strong> $fecha</small></p>";
                      echo "</article>";
                  }
              }
              fclose($file);
          } else {
              echo "<p class='no-notes'>No se encontraron notas o el archivo no es accesible.</p>";
          }
        ?>
      </div>
    </section>

    <button class="btn-modal" onclick="openModal()">Agregar Nota</button>

    <div id="myModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Agregar Nueva Nota</h2>
        <form action="guardar_notas.php" method="post">
          <div class="form-group">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" maxlength="50" required>
          </div>
          <div class="form-group">
            <label for="mensaje">Mensaje:</label>
            <textarea name="mensaje" id="mensaje" cols="30" rows="5" maxlength="20000" required></textarea>
          </div>
          <button type="submit" class="btn-submit">Guardar</button>
        </form>
      </div>
    </div>
  </main>
<br>
  <?php include("pie.php"); ?>

  <script>
    // Abrir el modal
    function openModal() {
      document.getElementById('myModal').style.display = 'block';
    }

    // Cerrar el modal
    function closeModal() {
      document.getElementById('myModal').style.display = 'none';
    }
  </script>
</body>
</html>


