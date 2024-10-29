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
        <div class="container">
            <section class="content contacto">
            <h2 class="title_2">Contáctanos</h2>
            <p class="subtitle_2 text-center">fitnesscity52@gmail.com<br>223 384 9000<br>FitnessCity Puebla</p>
            <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d10666.211341902877!2d-98.15691395150299!3d19.060431692053072!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses-419!2smx!4v1716186881347!5m2!1ses-419!2smx" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            
            </section>

        </div>
        
        
       
    </main>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>

    
</body>

</html>