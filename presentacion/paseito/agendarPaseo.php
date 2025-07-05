<body>
  <?php
  include("presentacion/dueño/menuDueño.php");
  include("presentacion/fondo.php");

  $paseador = new Paseador();
  $listaPaseadores = $paseador->consultar_estad2();
  $cantidad = count($listaPaseadores);
  ?>


  


  <div class="container mt-4">
    <h2 class="section-title text-center mb-4">Nuestros Paseadores</h2>


    <div class="mb-4 text-center">
      <input type="text" id="filtro" class="form-control w-50 mx-auto" placeholder="Buscar paseador por nombre, correo o teléfono...">
    </div>


    <div class="row" id="resultados">
      <?php
      for ($i = 0; $i < $cantidad; $i++) {
        $p = $listaPaseadores[$i];

        
       
          $link = '?pid=' . base64_encode('presentacion/paseito/Info-PaseadorDueño.php') . '&id=' . $p->getId();

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

 



  <script>
    $(document).ready(function() {
      $("#filtro").keyup(function() {
        var texto = $("#filtro").val().trim();
        if (texto.length === 0 || texto.length > 2) {
          var ruta = "buscarPaseadorAjax2.php?filtro=" + encodeURIComponent(texto);
          $("#resultados").load(ruta);
        }
      });
    });
  </script>
</body>