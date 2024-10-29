<?php

/**
 * Pantalla para detalles de compra
 */

require 'config/config.php';
require 'clases/clienteFunciones.php';

if (!isset($_SESSION['token'])) {
    header("Location: compras.php");
    exit;
}

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if (empty($orden) || empty($token) || $token != $token_session) {
    header("Location: compras.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$sqlVenta = $con->prepare("SELECT id, id_transaccion, fecha, total, envio FROM venta WHERE id_transaccion = ? LIMIT 1");
$sqlVenta->execute([$orden]);
$rowVenta = $sqlVenta->fetch(PDO::FETCH_ASSOC);
$idVenta = $rowVenta['id'];
$envio=$rowVenta['envio'];
$fecha = new DateTime($rowVenta['fecha']);
$fecha = $fecha->format('d-m-Y H:i');

$sqlDetalle = $con->prepare("SELECT id, nombre, precio, cantidad FROM detalle_venta WHERE id_venta = ?");
$sqlDetalle->execute([$idVenta]);

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

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Detalle de la compra</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Fecha: </strong><?php echo $fecha; ?></p>
                            <p><strong>Orden: </strong><?php echo $rowVenta['id_transaccion']; ?></p>
                            <p><strong>Total: </strong><?php echo MONEDA . ' ' . number_format($rowVenta['total'], 2, '.', ','); ?></p>
                            <p><strong>Envío: </strong><?php echo $envio; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php

                                while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                                    $precio = $row['precio'];
                                    $cantidad = $row['cantidad'];
                                    $subtotal = $precio * $cantidad;
                                ?>
                                    <tr>
                                        <td><?php echo $row['nombre']; ?></td>
                                        <td><?php echo MONEDA . ' ' . number_format($precio, 2, '.', ','); ?></td>
                                        <td><?php echo $cantidad; ?></td>
                                        <td><?php echo MONEDA . ' ' . number_format($subtotal, 2, '.', ','); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>
</body>

</html>