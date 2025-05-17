<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procesar formulario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }

        h1 {
            text-align: center;
        }

        p {
            text-align: center;
        }

        button {
            background-color:rgb(175, 76, 122);
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            opacity: 0.8;
        }
    </style>
</head>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hobbies = isset($_POST['hobbies']) ? $_POST['hobbies'] : '';
    $smile = isset($_POST['smile']) ? $_POST['smile'] : '';
    $favorite_food = isset($_POST['favorite_food']) ? $_POST['favorite_food'] : '';
    $dream_vacation = isset($_POST['dream_vacation']) ? $_POST['dream_vacation'] : '';
    $superpower = isset($_POST['superpower']) ? $_POST['superpower'] : '';
    $childhood_memory = isset($_POST['childhood_memory']) ? $_POST['childhood_memory'] : '';
    $motto = isset($_POST['motto']) ? $_POST['motto'] : '';

    // Mostrar los datos recibidos


    // Guardar los datos en un archivo
    $data = "hobbies: $hobbies\nsmile: $smile\nfavorite_food: $favorite_food\ndream_vacation: $dream_vacation\nsuperpower: $superpower\nchildhood_memory: $childhood_memory\nmotto: $motto\n";
    file_put_contents('data.txt', $data);

    // Mostrar mensaje de agradecimiento
    echo "<p>Gracias por contarme sobre ti. ¡Me ayuda mucho a conocerte mejor! 😊</p>";

    // Botón para regresar a inicio.php
    echo "<a href='sobre_ti.php'><button>Regresar</button></a>";
}
?>
