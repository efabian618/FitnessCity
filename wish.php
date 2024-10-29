<?php

/**
 * Pantalla para mostrar el listado de productos en el carrito
 */

require 'config/config.php';

$productos = isset($_SESSION['deseos']['productos']) ? $_SESSION['deseos']['productos'] : null;

$db = new Database();
$con = $db->conectar();

$lista_deseos = array();

if ($productos != null) {
    foreach ($productos as $clave => $producto) {
        $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_deseos[] = $sql->fetch(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="images/Logo_Fit.ico">
    <title>FitnessCity - WishList</title>
    <link href="<?php echo SITE_URL; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="css/all.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
</head>

<body class="d-flex flex-column h-100">

    <?php include 'menu.php'; ?>

    <!-- Contenido -->
    <main class="flex-shrink-0 " >
    <div class="container  h-50  h-custom" >
        <div class="py-0 h-100">
            <div class="row d-flex j h-100">
            <div class="col " >
                <div class="card">
                <div class="card-body p-4">

                    <div class="row">

                    <div class="col-lg-12">
                        <h5 class="mb-3"><a href="catalogo.php" class="text-body"><i
                            class="fas fa-long-arrow-alt-left me-2"></i>Continuar añadiendo</a></h5>
                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 style="color:#f74780"class="mb-1 ">Lista de deseos</h2>
                            <p class="mb-0">Tienes <b class="text-danger"><?php echo $num_wish;?></b> artículos en tu lista de deseos</p>
                        </div>
                        </div>

                        <?php if($lista_deseos == null){
                                echo '<tr><td colspan="5" class="text-center"><b>Lista Vacía</b></td></tr>';
                                $total=0;
                            }else{
                                $total=0;

                                foreach($lista_deseos as $producto){
                                    $_id=$producto['id'];
                                    $nombre=$producto['nombre'];
                                    $precio=$producto['precio'];
                                    $descuento=$producto['descuento'];
                                    $precio_desc=$precio-(($precio*$descuento)/100);
                                    
                                    $imagen="images/productos/". $_id . "/principal.jpg";

                                    ?>
                        <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                            <div class="d-flex flex-row align-items-center">
                                <div>
                                <img
                                    src="<?php echo $imagen;?>"
                                    class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;">
                                </div>
                                <div class="ms-3">
                                <h5><?php echo $nombre; ?></h5>
                                <p class="small mb-0"><?php echo MONEDA . number_format($precio_desc,2,'.',','); ?></p>
                                
                                </div>

                                
                            </div>
                            <div class="d-flex flex-row d-flex  align-items-center justify-content-around">
                          
                                
                                <div style="width: 200px;" class="align-items-center">
                                <a class="btn btn-outline-success px-2" onClick="addProducto(<?php echo $_id; ?>)">Comprar</a>
                                    
                                </div>

                                <div style="width: 100px; " class="align-items-center">
                               
                                    <a id="eliminar" style="color: #71c55b;" onClick="elimina(<?php echo $_id; ?>)"> 
                                        <i class="fas fa-trash-alt "></i>
                                    </a>  
                                </div>

                            </div>
                            </div>
                        </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    </div>

                </div>
                
                </div>
            </div>
            </div>
        </div>
     </div>
    </main>

   

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>


    <script>
       
        
        function elimina(id) {
            var url = 'clases/actualizar_deseos.php';
            var formData = new FormData();
            formData.append('id', id);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors',
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload();

                    } else {
                        alert("Ya está eliminado")
                    }
                })
        }


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
                        elimina(id);
                    } else {
                        alert("No hay suficientes productos en el stock")
                    }
                })
        }
        
    </script>

</body>

</html>