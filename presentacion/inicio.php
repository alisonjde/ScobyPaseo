<body>
  <?php
  include("presentacion/encabezado.php");
  include("presentacion/fondo.php");

  $paseador = new Paseador();
  $listaPaseadores = $paseador->consultar_estad2();
  $cantidad = count($listaPaseadores);
  ?>


  <!-- CARRUSEL -->
  <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="container-xl py-5">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h2 class="section-title">¿Quiénes somos?</h2>
              <p>Perritours es una plataforma que conecta dueños de mascotas con paseadores de confianza. Ofrecemos una forma fácil y segura de programar paseos, pagar electrónicamente y obtener reportes y facturas. Cada paseo es monitoreado y verificado para que tú estés tranquilo mientras tu perrito disfruta.</p>
            </div>
            <div class="col-md-6 text-center">
              <img src="imagenes/quienes.png" class="img-fluid" alt="Nosotros">
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="container-xl py-5">
          <div class="row align-items-center">
            <div class="col-md-6 text-center">
              <img src="imagenes/servicios2.png" class="img-fluid" alt="Servicios">
            </div>
            <div class="col-md-6">
              <h2 class="section-title">Nuestros Servicios</h2>
              <ul>
                <li>🧾 Facturación en PDF con código QR</li>
                <li>🗓️ Agenda de paseos con selección de fecha, hora y paseador</li>
                <li>📱 Gestión de usuarios con roles claros (dueños, paseadores, admins)</li>
                <li>📊 Consulta de historial y estadísticas de actividad</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <div class="container-xl py-5">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h2 class="section-title">Estadísticas</h2>
              <p>Visualiza datos relevantes para el negocio: frecuencia de paseos, ingresos por paseador, calificaciones y más. Presentamos la información de forma clara para que puedas tomar decisiones acertadas.</p>
            </div>
            <div class="col-md-6 text-center">
              <img src="imagenes/analisis.png" class="img-fluid" alt="Estadísticas">
            </div>
          </div>
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Anterior</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Siguiente</span>
    </button>
  </div>


  <div class="container">
    <h2 class="section-title text-center mb-4">Nuestros Paseadores</h2>


    <div class="mb-4 text-center">
      <input type="text" id="filtro" class="form-control w-50 mx-auto" placeholder="Buscar paseador por nombre, correo o teléfono...">
    </div>


    <div class="row" id="resultados">
      <?php
      for ($i = 0; $i < $cantidad; $i++) {
        $p = $listaPaseadores[$i];

        
       
          $link = "#";
          echo '
        <div class="col-md-4 mb-4">
          <a href="' . $link . '" class="text-decoration-none text-dark">
            <div class="card h-100 shadow">
              <img src="' . $p->getFoto() . '" class="card-img-top" alt="Foto de ' . $p->getNombre() . '" onerror="this.src=\'img/default-profile.png\'">

              <div class="card-body">
                <h5 class="card-title">' . $p->getNombre() . ' ' . $p->getApellido() . '</h5>
                <p class="card-text"><strong>Correo:</strong> ' . $p->getCorreo() . '</p>
                <p class="card-text"><strong>Teléfono:</strong> ' . $p->getTelefono() . '</p>
                <p class="card-text"><strong>Descripción:</strong> ' . $p->getDescripcion() . '</p>
              </div>
            </div>
          </a>
        </div>';
        
      }

      ?>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="text-center mt-5">
    <div class="container-xl">
      <div class="row mb-3">
        <div class="col-md-4">
          <h6>Contacto</h6>
          <p>Email: info@perritours.com</p>
          <p>Tel: +57 300 123 4567</p>
        </div>
        <div class="col-md-4">
          <h6>Redes Sociales</h6>
          <p><a href="#">Instagram</a> | <a href="#">Facebook</a> | <a href="#">TikTok</a></p>
        </div>
        <div class="col-md-4">
          <h6>Enlaces útiles</h6>
          <p><a href="#about">Sobre nosotros</a> | <a href="#services">Servicios</a></p>
        </div>
      </div>
      <hr style="background-color: #444;">
      <p class="small">&copy; 2025 Perritours. Todos los derechos reservados.</p>
    </div>
  </footer>



  <script>
    $(document).ready(function() {
      $("#filtro").keyup(function() {
        var texto = $("#filtro").val().trim();
        if (texto.length === 0 || texto.length > 2) {
          var ruta = "buscarPaseadorAjax.php?filtro=" + encodeURIComponent(texto);
          $("#resultados").load(ruta);
        }
      });
    });
  </script>
</body>