<?php

/**
 * Pantalla para mostrar el listado de productos en el carrito
 */

require 'config/config.php';

$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$db = new Database();
$con = $db->conectar();

$lista_carrito = array();

if ($productos != null) {
    foreach ($productos as $clave => $producto) {
        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $producto AS cantidad FROM productos WHERE id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
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
    <title>FitnessCity - Carrito</title>
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
                            class="fas fa-long-arrow-alt-left me-2"></i>Continuar comprando</a></h5>
                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="mb-1 text-success">Carrito de Compras</h2>
                            <p class="mb-0">Tienes <b class="text-danger"><?php echo $num_cart;?></b> artículos en tu carrito</p>
                        </div>
                        </div>

                        <?php if($lista_carrito == null){
                                echo '<tr><td colspan="5" class="text-center"><b>Lista Vacía</b></td></tr>';
                                $total=0;
                            }else{
                                $total=0;

                                foreach($lista_carrito as $producto){
                                    $_id=$producto['id'];
                                    $nombre=$producto['nombre'];
                                    $precio=$producto['precio'];
                                    $cantidad = $producto['cantidad'];
                                    $descuento=$producto['descuento'];
                                    $precio_desc=$precio-(($precio*$descuento)/100);
                                    $subtotal=$cantidad*$precio_desc;
                                    $total+=$subtotal;
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
                            <div class="d-flex flex-row align-items-center">
                                <div class="mx-3" style="width: 70px;">
                                <input class="text-center" type="number" min="1" max="10" step="1" value="<?php echo $cantidad; ?>" size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value,<?php echo $_id; ?>)">
                                </div>
                                <div style="width: 100px;">
                                <div id="subtotal_<?php echo $_id;?>"class="mb-0" name="subtotal[]"><?php echo MONEDA . number_format($subtotal,2,'.',','); ?></div>
                                </div>
                                <div style="width: 50px;" class="align-items-center">
                                    <a  id="eliminar" style="color: #71c55b;"  data-bs-id="<?php echo $_id;?>" data-bs-toggle="modal" data-bs-target="#eliminaModal"><i class="fas fa-trash-alt"></i></a>
                                </div>
                                
                                
                            </div>
                            </div>
                        </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>
                    
                    </div>
                    <?php if($lista_carrito != null){   ?>    
                    <div class="d-flex flex-wrap justify-content-end align-items-center pb-9">
                    
                    <div class="d-flex">
                        
                        <div class="text-center mt-4">
                        <label class=" font-weight-normal m-0 h2 " style="color: #000000; ">TOTAL</label>
                        <div class="text-large "><p class="h3" id="total" style="color: #4b574e; "><?php echo MONEDA . number_format($total,2,'.',','); ?></p></div>
                        </div>
                    </div>
                    </div>
                        
                    <div class="d-flex justify-content-center">
                    
                    <a href="envio.php" type="button" class="btn btn-lg btn-primary mt-2" style="background-color: #a2d45e; border-color:#a2d45e; color:#fff;">CONTINUAR</a>
                    </div>
                    <?php } ?>

                </div>
                
                </div>
            </div>
            </div>
        </div>
     </div>
    </main>

    <div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Alerta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Desea eliminar el producto de la lista?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button id="btn-elimina" class="btn btn-danger" onclick="elimina()">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>


    <script>
        let eliminaModal = document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event) {
            // Button that triggered the modal
            let button = event.relatedTarget
            // Extract info from data-bs-* attributes
            let recipient = button.getAttribute('data-bs-id')
            let botonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
            botonElimina.value = recipient
        })

        function actualizaCantidad(cantidad, id) {

            let url = 'clases/actualizar_carrito.php';
            let formData = new FormData();
            formData.append('action', 'agregar');
            formData.append('id', id);
            formData.append('cantidad', cantidad);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors',
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        let divSubtotal = document.getElementById('subtotal_' + id)
                        divSubtotal.innerHTML = data.sub

                        let total = 0.00
                        let list = document.getElementsByName('subtotal[]')

                        for (var i = 0; i < list.length; ++i) {
                            total += parseFloat(list[i].innerHTML.replace(/[<?php echo MONEDA; ?>,]/g, ''))
                        }

                        total = new Intl.NumberFormat('en-US', {
                            minimumFractionDigits: 2
                        }).format(total)
                        document.getElementById("total").innerHTML = '<?php echo MONEDA; ?>' + total
                    } else {
                        alert("No ay suficientes productos en el stock")
                        let inputCantidad = document.getElementById('cantidad_' + id);
                        inputCantidad.value = data.cantidadAnterior;
                    }
                })
        }

        function elimina() {
            let botonElimina = document.getElementById('btn-elimina')
            let recipient = botonElimina.value

            let url = 'clases/actualizar_carrito.php';
            
            let formData = new FormData();
            formData.append('action', 'eliminar');
            formData.append('id', recipient);

            fetch(url, {
                    method: 'POST',
                    body: formData,
                    mode: 'cors',
                }).then(response => response.json())
                .then(data => {
                    if (data.ok) {
                        location.reload();
                    }
                })
        }
    </script>

</body>

</html>