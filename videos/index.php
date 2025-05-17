<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seguridad Electrónica Huacho</title>
  <style>
    /* Estilos generales */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    header {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px;
      background-color: #1A202C;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    header h1 {
      margin: 0;
      font-size: 1.6em;
      color: #F7FAFC;
      font-weight: 650;
      letter-spacing: 2px;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      padding: 20px;
    }
    .camera {
      background: #fff;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .camera-title {
      text-align: center;
      margin: 0 0 10px;
      font-size: 1.2em;
      font-weight: bold;
    }
    .video-container {
      position: relative;
      width: 100%;
      padding-bottom: 56.25%; /* 16:9 */
      background: #000;
      border-radius: 10px;
      overflow: hidden;
    }
    .video-container video {
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      border-radius: 10px;
    }
    .share-button {
      display: block;
      margin: 10px auto 0;
      padding: 10px 15px;
      background-color: #4f7ed4;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color .3s;
    }
    .share-button:hover {
      background-color: #365b99;
    }
    /* WhatsApp flotante */
    .floating-button {
      position: fixed;
      bottom: 20px; right: 20px;
      display: flex;
      align-items: center;
      padding: 10px 15px;
      background-color: #25D366;
      color: #fff;
      border-radius: 50px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      text-decoration: none;
      font-size: 1.2em;
      transition: background-color .3s;
      z-index: 1000;
    }
    .floating-button:hover {
      background-color: #128C7E;
    }
    .floating-button img {
      width: 24px; height: 24px;
      margin-right: 10px;
    }
    footer {
      background: #fff;
      text-align: center;
      padding: 10px 0;
    }
    footer p {
      margin: 5px 0;
      color: #555;
    }
    /* Responsivo */
    @media (min-width: 1024px) {
      .grid { grid-template-columns: repeat(3,1fr); }
      body { background: #e0e0e0; }
    }
    @media (max-width: 600px) {
      .video-container { padding-bottom: 75%; }
      body { background: #fff; }
    }
  </style>
</head>
<body>

  <header>
    <a href="https://sehuacho.com/" target="_blank">
      <h1>Cámaras de Seguridad</h1>
    </a>
  </header>

  <div class="grid">
    <!-- Cámara 1 -->
    <div class="camera">
      <h2 class="camera-title">Cámara 1</h2>
      <p style="text-align:center; margin:0 0 10px;">Desde las 9:00 AM...</p>
      <div class="video-container">
        <video controls autoplay>
          <source src="videos/camara1.mp4" type="video/mp4">
          Tu navegador no soporta video.
        </video>
      </div>
      <button class="share-button" onclick="share('Cámara 1', 'https://sehuacho.com/videos/camara1.mp4')">
        Compartir
      </button>
    </div>

    <!-- Cámara 2 -->
    <div class="camera">
      <h2 class="camera-title">Cámara 2</h2>
      <p style="text-align:center; margin:0 0 10px;">Desde las 10:00 AM...</p>
      <div class="video-container">
        <video controls autoplay>
          <source src="videos/camara2.mp4" type="video/mp4">
          Tu navegador no soporta video.
        </video>
      </div>
      <button class="share-button" onclick="share('Cámara 2', 'https://sehuacho.com/videos/camara2.mp4')">
        Compartir
      </button>
    </div>

    <!-- Agrega más cámaras siguiendo el mismo patrón -->
  </div>

  <!-- Botón flotante de WhatsApp -->
  <a href="https://wa.me/948793154" class="floating-button" target="_blank">
    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
    Contactar
  </a>

  <script>
    function share(cameraName, cameraUrl) {
      const text = `Revisa esta cámara de seguridad ${cameraName}: ${cameraUrl}`;
      const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
      window.open(url, '_blank');
    }
  </script>

  <footer>
    <p>Descarga las evidencias antes de 30 días; luego serán borradas.</p>
    <p>© 2024 Cámaras de Seguridad. Todos los derechos reservados.</p>
  </footer>

</body>
</html>
