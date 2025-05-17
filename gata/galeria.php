<?php

include("menu.php");

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Galería de Fotos</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <style>
    /* Estilos generales */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f7f7f7;
      color: #333;
    }

    header {
      background-color: #ff6f91;
      padding: 20px;
      text-align: center;
    }

    header h1 {
      color: white;
      margin: 0;
    }

    .gallery {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
      padding: 20px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .gallery img {
      width: 100%;
      height: auto;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gallery img:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    footer {
      background-color: #ff6f91;
      color: white;
      text-align: center;
      padding: 10px 0;
    }
  </style>
</head>
<body>
  <header>
    <h1>Galería de Fotos</h1>
  </header>

  <main>
    <section class="gallery">
      <?php
        $fotos = scandir("img");
        foreach ($fotos as $foto) {
          if ($foto != "." && $foto != "..") {
            echo "<a href='img/$foto' data-lightbox='galeria'><img src='img/$foto' alt='$foto'></a>";
          }
        }
      ?>
    </section>
  </main>

</body>
</html>

<?php include("pie.php");
