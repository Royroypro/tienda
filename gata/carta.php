<?php
include("menu.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página de Inicio</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <style>
    /* Estilos generales */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
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

    .hero {
      text-align: center;
      padding: 50px 20px;
      background-color: #e1f5fe;
    }

    .hero h2 {
      font-size: 2.5rem;
      color: #333;
    }

    .hero p {
      font-size: 1.2rem;
      margin: 20px 0;
    }

    .content {
      text-align: center;
      padding: 20px;
    }

    .content p {
      font-size: 1rem;
      line-height: 1.6;
      margin: 10px 0;
    }

    footer {
      background-color: #ff6f91;
      color: white;
      text-align: center;
      padding: 10px 0;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <header>
    <h1>Bienvenida</h1>
  </header>

  <section class="hero">
    <h2>Hola, Gata</h2>
    <p>Esta página fue creada especialmente para ti. ¡Espero que te guste! ❤️</p>
  </section>

  <section class="content">
    <p>En esta página puedes encontrar mensajes, imágenes, o lo que tú desees agregar.</p>
    <p>Siempre estoy pensando en cómo hacerte sonreír. 😊</p>
  </section>

 
</body>
</html>

<?php include("pie.php"); ?>

