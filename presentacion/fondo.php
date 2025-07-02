<style>
  body {
    background: url('imagenes/fondo.png') no-repeat center center fixed;
    background-size: cover;
    color: rgb(0, 0, 0);
    font-family: 'Segoe UI', sans-serif;


  }

  .card-img-top {
    margin: 10px;
    width: calc(100% - 20px);
    height: 250px;
    object-fit: cover;
    border-radius: 8px;
  }


  .glass {
    background: rgba(52, 128, 35, 0.7);
    border-radius: 20px;
    backdrop-filter: blur(8px);
    padding: 2rem;
  }

  .navbar {
    background-color: rgb(90, 179, 90);
  }



  .btn-primary {
    background-color: rgb(81, 178, 42);
    border: none;
  }


  footer {
    background-color: rgb(90, 179, 90);
    color: #ccc;
    padding: 2rem 0;
  }

  footer a {
    color: #c5f5dc;
    text-decoration: none;
  }

  footer a:hover {
    text-decoration: underline;
  }

  .rounded-card {
    border-radius: 1.5rem;
  }

  .img-fluid {
    border-radius: 1rem;
    max-height: 300px;
    object-fit: cover;
  }

  /* Carrusel */
  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    background-color: rgb(85, 178, 42);

    border-radius: 50%;
    padding: 1.2rem;
    background-size: 100% 100%;
    filter: none;
  }

  .carousel-control-prev:hover .carousel-control-prev-icon,
  .carousel-control-next:hover .carousel-control-next-icon {
    background-color: rgb(20, 99, 10);

  }

  /* Menus */
  .dropdown-menu {
    background-color: rgb(48, 48, 48);
    border-radius: 0.5rem;
    padding: 0.5rem 0;
    min-width: 200px;
  }

  .dropdown-item:hover {
    background-color: rgb(90, 179, 90) !important;
    color: #ffffff !important;
  }


  /* Tablas mostrar datos*/

  .table-custom th {
    background-color: rgb(129, 240, 129);
    color: rgb(0, 0, 0);
    border-bottom: 2px rgb(233, 132, 0);
    text-align: center;
  }

  .table-custom td {
    background-color: rgb(255, 255, 255);
    color: rgb(0, 0, 0);
    border-top: 1px solidrgb(255, 136, 0);
    vertical-align: middle;
  }

  .table-custom tr:hover {
    background-color: rgb(255, 0, 0);
    transition: background-color 0.3s ease;
  }

  .tarifa-badge {
    background-color: rgb(165, 248, 168);
    border-radius: 12px;
    padding: 3px 10px;
    font-size: 0.85em;
  }
</style>