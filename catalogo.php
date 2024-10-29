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
    <title>FitnessCity - Productos</title>
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
            <div class="row">
                <div class="col-12 col-md-3 col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            Categorías
                        </div>

                        <div class="list-group list-group-flush">
                            <a href="catalogo.php" class="list-group-item list-group-item-action">TODO</a>
                            <?php foreach ($categorias as $categoria) { ?>
                                <a href="catalogo.php?cat=<?php echo $categoria['id']; ?>" class="list-group-item list-group-item-action <?php echo ($categoria['id'] == $idCategoria) ? 'active' : ''; ?>">
                                    <?php echo $categoria['nombre']; ?>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-9 col-lg-9">
                    <header class="d-sm-flex align-items-center border-bottom mb-4 pb-3">
                        <strong class="d-block py-2"><?php echo $totalRegistros; ?> Artículos encontrados </strong>
                        <div class="ms-auto">
                            <form action="catalogo.php" id="ordenForm" method="get" onchange="submitForm()">
                                <input type="hidden" id="cat" name="cat" value="<?php echo $idCategoria; ?>">
                                <label for="cbx-orden" class="form-label">Ordena por</label>

                                <select class="form-select d-inline-block w-auto pt-1 form-select-sm" name="orden" id="orden">
                                    <option value="precio_alto" <?php echo ($orden === 'precio_alto') ? 'selected' : ''; ?>>Pecios más altos</option>
                                    <option value="precio_bajo" <?php echo ($orden === 'precio_bajo') ? 'selected' : ''; ?>>Pecios más bajos</option>
                                    <option value="asc" <?php echo ($orden === 'asc') ? 'selected' : ''; ?>>Nombre A-Z</option>
                                    <option value="desc" <?php echo ($orden === 'desc') ? 'selected' : ''; ?>>Nombre Z-A</option>
                                </select>
                            </form>
                        </div>
                    </header>

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
                                        <img src="<?php echo $imagen; ?>" class="img-thumbnail border-0" style="max-height: 300px">
                                    </a>

                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex flex-row">
                                            <h5 class="card-title text-center fs-5 "><?php echo $row['nombre'];?></h5>
                                            
                                        </div>
                                        <br>
                                        <p class="mb-1 me-1 text-center fs-2" style="color:#149c68;"><?php echo MONEDA . ' ' . number_format($row['precio'], 2, '.', ','); ?></h5>
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