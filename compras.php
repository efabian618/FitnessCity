<?php

/**
 * Pantalla historial de compras
 */

require 'config/config.php';
require 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$token = generarToken();
$_SESSION['token'] = $token;

if (!isset($_SESSION['user_cliente'])) {
    header("Location: login.php");
    exit;
}

$idCliente = $_SESSION['user_cliente'];

$sql = $con->prepare("SELECT id_transaccion, fecha, status, total, medio_pago FROM venta WHERE id_cliente = ? ORDER BY fecha DESC");
$sql->execute([$idCliente]);

?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessCity - Mis Compras</title>
    <link rel="shortcut icon" type="image/x-icon" href="images/Logo_Fit.ico">
    <link href="<?php echo SITE_URL; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">


</head>

<body class="d-flex flex-column h-100">

    <?php include 'menu.php'; ?>

    <!-- Contenido -->
    <main class="flex-shrink-0">
        <div class="container">
            <h4>Mis compras</h4>

            <hr>

            <?php while ($row = $sql->fetch(PDO::FETCH_ASSOC)) { ?>

                <div class="card mb-2">
                    <div class="card-header">
                        <?php echo $row['fecha']; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Folio: <?php echo $row['id_transaccion']; ?></h5>
                        <p class="card-text">Total: <?php echo $row['total']; ?></p>
                        <a href="compra_detalle.php?orden=<?php echo $row['id_transaccion']; ?>&token=<?php echo $token; ?>" class="btn btn-primary">Ver compra</a>
                    </div>
                </div>

            <?php } ?>

        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>
</body>

</html>