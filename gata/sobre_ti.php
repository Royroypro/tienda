<?php
include("menu.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sobre ti</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    /* Estilos generales */
    body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f1f8e9;
      color: #333;
      line-height: 1.6;
    }

    header {
      background-color: #ff6f91;
      padding: 30px 20px;
      text-align: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    header h1 {
      color: white;
      font-size: 2.5rem;
      margin: 0;
      font-weight: 700;
    }

    .hero {
      text-align: center;
      padding: 60px 20px;
      background: linear-gradient(145deg, #ff7eb9, #ff6f91);
      color: white;
      border-radius: 10px;
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .hero h2 {
      font-size: 3rem;
      margin-bottom: 20px;
    }

    .hero p {
      font-size: 1.4rem;
      margin: 20px 0;
    }

    .content {
      text-align: center;
      padding: 40px 20px;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin: 20px;
    }

    .content p {
      font-size: 1.1rem;
      margin-bottom: 20px;
    }

    .content form {
      margin-top: 30px;
    }

    .content input[type="text"] {
      padding: 10px;
      font-size: 1rem;
      width: 80%;
      max-width: 400px;
      border: 2px solid #ccc;
      border-radius: 4px;
      margin: 10px 0;
    }

    .content input[type="submit"] {
      background-color: #ff6f91;
      border: none;
      color: white;
      padding: 12px 20px;
      font-size: 1.1rem;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .content input[type="submit"]:hover {
      background-color: #ff7eb9;
    }

    footer {
      background-color: #ff6f91;
      color: white;
      text-align: center;
      padding: 20px 0;
      margin-top: 40px;
      font-size: 1rem;
    }

    footer i {
      color: #ffd54f;
    }
  </style>
</head>
<body>
  <header>
    <h1>Sobre la Gata</h1>
  </header>

  <section class="hero">
    <h2>Me encantas</h2>
    <p>Gata gracias por la carta y el los detallitos de tu parte. 
        Cuando los leo me hace recordar a la chica buena
         y tierna que tengo como enamodarada🤗 
         a veces me pregunto ¿Como es que pasa todo? 
         ¿Cómo es que nos encontramos en el paso de esta vida?
          Quiero pensar en el destino o algo que hizo que nos encontraramos.
          Ha pasado un año y es como si fuera ayer que nos hubieramos conocido.
          pues como dicen "El tiempo es relativo" que cuando estas o haces algo que te gusta
          el tiempo pasa volando. No solo ha pasado con el año. sino tambien cuando estamos juntos
          el tiempo pasa rapido y no se siente dos horas de ya tu sabes😈
          espero que sigamos teniendo buenos momentos, los cuales se conviertan
          en recuerdos que se guardaran en la mente, donde seguro estaran por siempre y volveran a causar sentimientos
          bonitos cuando se recuerde.
           ¡Te quiero mucho! ❤️</p>
    <i class="fas fa-heart fa-3x"></i>
  </section>

  <section class="content">
  <p>Sobre la Gata</p>

  <p>1. ¿Cuáles son tus pasatiempos favoritos?</p>
  <input type="text" name="hobbies" id="hobbies" value="Ver películas, leer un libro, pasear, caminar" style="width: 100%;" readonly><br>

  <p>2. ¿Qué te hace sonreír?</p>
  <input type="text" name="smile" id="smile" value="Disfrutar momentos bonitos con personas queridas" style="width: 100%;" readonly><br>

  <p>3. ¿Cuál es tu comida favorita?</p>
  <input type="text" name="favorite_food" id="favorite_food" value="Sopa seca y carapulcra" style="width: 100%;" readonly><br>

  <p>4. ¿A dónde te gustaría Viajar?</p>
  <input type="text" name="dream_vacation" id="dream_vacation" value="Finlandia - Apreciar una aurora boreal" style="width: 100%;" readonly><br>

  <p>5. Si pudieras tener un superpoder, ¿cuál sería?</p>
  <input type="text" name="superpower" id="superpower" value="El súper poder de la mente" style="width: 100%;" readonly><br>

  <p>6. ¿Qué recuerdo favorito te gustaria guardar conmigo?</p>
  <input type="text" name="childhood_memory" id="childhood_memory" value="Nuestra primera cita, ya que fue el inicio de una bonita relación que hemos ido construyendo con el tiempo, gracias al cariño, al respecto, a la lealtad y la conexión que tenemos." style="width: 100%;" readonly><br>

  <p>7. ¿Tienes algún lema o frase que sigas en la vida?</p>
  <input type="text" name="motto" id="motto" value="&quot;Muchos de los fracasos de la vida son personas que no se dieron cuenta de lo cerca que estaban del éxito cuando se rindieron&quot;. Thomas A. Edison" style="width: 100%;" readonly><br>









</section>



</body>
</html>

<?php include("pie.php"); ?>
