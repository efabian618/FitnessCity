<?php

/**
 * Pantalla principal para mostrar el listado de productos
 */

require 'config/config.php';

$db = new Database();
$con = $db->conectar();

$idCategoria = $_GET['cat'] ?? '';
$orden = $_GET['orden'] ?? '';
$buscar = $_GET['q'] ?? '';

$orders = [
    'asc' => 'nombre ASC',
    'desc' => 'nombre DESC',
    'precio_alto' => 'precio DESC',
    'precio_bajo' => 'precio ASC',
];

$order = $orders[$orden] ?? '';
$params = [];

$sql = "SELECT id, slug, nombre, precio FROM productos WHERE activo=1";

if (!empty($buscar)) {
    $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
    $params[] = "%$buscar%";
    $params[] = "%$buscar%";
}

if (!empty($idCategoria)) {
    $sql .= " AND id_categoria = ?";
    $params[] = $idCategoria;
}

if (!empty($order)) {
    $sql .= " ORDER BY $order";
}

$query = $con->prepare($sql);
$query->execute($params);
$resultado = $query->fetchAll(PDO::FETCH_ASSOC);
$totalRegistros = count($resultado);

$categoriaSql = $con->prepare("SELECT id, nombre FROM categorias WHERE activo=1");
$categoriaSql->execute();
$categorias = $categoriaSql->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/x-icon" href="images/Logo_Fit.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessCity</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
    <style>
         @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');
    </style>
</head>

<body class="d-flex flex-column h-100">

    <?php include 'menu.php'; ?>

    <!-- Contenido -->
    <main class="flex-shrink-0">
        <section class="content header">
            <h2 class="title">FITNESSCITY</h2>
            <p class="subtitle">Un paraíso donde la pasión por el movimiento se une a la cúspide del equipamiento y la ropa deportiva </p>
        </section>

        <section class="content sau">
            <h2 class="title_2">Productos</h2>
            <p class="subtitle_2">En FitnessCity te ofrecemos productos para tu entrenamiento  con la mejor calidad, para todos los gustos y con el mejor
                precio</p>

            <div class="box-container">
                <div class="box">
                    <ion-icon name="bed-outline"></ion-icon>
                    <h3>ACCESORIOS</h3>
                    <p>Productos que complementan el entrenamiento y la experiencia</p>
                </div>
                <div class="box">
                    <ion-icon name="glasses-outline"></ion-icon>
                    <h3>GIMNASIO</h3>
                    <p>Equipo de entrenamiento para realizar fuerza, cardio y entrenamiento funcional</p>
                </div>
                <div class="box">
                    <ion-icon name="medkit-outline"></ion-icon>
                    <h3>NUTRICIÓN</h3>
                    <p>Productos que ayudan a mantener la hidratación y el aporte nutricional</p>
                </div>
                
            </div>

            
        </section>
    
        <section class="container_video">
            <div class="video-container">

                <video src="images/video.mp4" autoplay muted loop></video>
            </div>
            <div class="content_video">
                    <h2 class="title">ENTRENA EN CASA</h1>
            </div>
        </section>
       
    </main>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>

    
</body>

</html>