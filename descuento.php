<?php

/**
 * Pantalla principal para mostrar el listado de productos
 */

require 'config/config.php';

$db = new Database();
$con = $db->conectar();



$params = [];

$sql = "SELECT id, slug, nombre, precio,descuento FROM productos WHERE activo=1 AND descuento > 1";

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



?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/x-icon" href="images/Logo_Fit.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitnessCity - Ofertas</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>

<body class="d-flex flex-column h-100">

    <?php include 'menu.php'; ?>

    <!-- Contenido -->
    <main class="flex-shrink-0 ">
        <div class="container">
            <h2 class="text-danger">OFERTAS</h2>
            <div class="row">
            

                <div class="col-12 col-md-12 col-lg-12">
                    

                    <div class="row">
                        <?php foreach ($resultado as $row) { ?>
                            <div class="col-lg-4 col-md-6 col-sm-6 d-flex">
                                <div class="card w-100 my-2 shadow-2-strong ">

                                    <?php
                                    $id = $row['id'];
                                    $imagen = "images/productos/$id/principal.jpg";

                                    if (!file_exists($imagen)) {
                                        $imagen = "images/no-photo.jpg";
                                    }
                                    ?>
                                    <a href="details/<?php echo $row['slug']; ?>">
                                        <img src="<?php echo $imagen; ?>" class="img-thumbnail border-0" style="max-height: 400px">
                                    </a>

                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex flex-row">
                                            <h5 class="card-title text-center fs-5 "><?php echo $row['nombre'];?></h5>
                                            
                                        </div>
                                        <br>
                                            <?php 
                                            $precio=$row['precio'];
                                            $descuento=$row['descuento'];
                                            $precio_desc=$precio-(($precio*$descuento)/100);?>

                                        <p class="mb-1 me-1 text-center fs-4 text-decoration-line-through " style="color:#abe0ab;"><?php echo MONEDA . ' ' . number_format($row['precio'], 2, '.', ','); ?></p>
                                        <h4 class="mb-1 me-1 text-center fs-2 " style="color:#149c68;"><?php echo MONEDA . ' ' . number_format($precio_desc, 2, '.', ','); ?></h4>
                                    </div>

                                    <div class="card-footer bg-transparent border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a class="btn btn-outline-primary" onClick="addProducto(<?php echo $row['id']; ?>)">Agregar</a>
                                            
                                            <a class="icono_corazon" onClick="addLista(<?php echo $row['id']; ?>)">
                                                <i style="color:#f7480" type="button" class="fa-solid fa-heart position-relative "> 
                    
                                                </i>
                                            </a>
                                            <div class="btn-group">
                                                <a href="details/<?php echo $row['slug']; ?>" class="btn btn-primary">Detalles</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>
    <script>
        function addProducto(id) {
            var url = 'clases/carrito.php';
            var formData = new FormData();
            formData.append('id', id);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors',
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_cart")
                        elemento.innerHTML = data.numero;
                    } else {
                        alert("No hay suficientes productos en el stock")
                    }
                })
        }
        function addLista(id) {
            var url = 'clases/deseos.php';
            var formData = new FormData();
            formData.append('id', id);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors',
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let elemento = document.getElementById("num_wish")
                        elemento.innerHTML = data.numero;
                    } else {
                        alert("Ya está en la lista")
                    }
                })
        }


        function submitForm() {
            document.getElementById("ordenForm").submit();
        }
    </script>
</body>

</html>