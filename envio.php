<?php

/**
 * Pantalla para registro de cliente
 */

require 'config/config.php';
require 'clases/clienteFunciones.php';

if (!isset($_SESSION['user_name'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$opEstado=['Aguascalientes','Baja California','Baja California Sur','Campeche','Chiapas','Chihuahua','Coahuila de Zaragoza',
      'Colima','Ciudad de México','Durango','Guanajuato','Guerrero','Hidalgo','Jalisco','Estado de Mexico','Michoacan de Ocampo','Morelos',
      'Nayarit','Nuevo Leon','Oaxaca','Puebla','Queretaro de Arteaga','Quintana Roo','San Luis Potosi',
    'Sinaloa','Sonora','Tabasco','Tamaulipas','Tlaxcala','Veracruz de Ignacio de la Llave','Yucatan','Zacatecas',];
$errors = [];

if (!empty($_POST)) {

    $calle = trim($_POST['calle']);
    $cp = trim($_POST['cp']);
    $ciudad = trim($_POST['ciudad']);
    $estado = trim($_POST['estado']);


    $_SESSION['calle']=$calle;
    $_SESSION['cp']=$cp;
    $_SESSION['ciudad']=$ciudad;
    $_SESSION['estado']=$estado;

    header('Location: pago.php');

}


?>
<!DOCTYPE html>
<html lang="es" class="h-100">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="images/Logo_Fit.ico">
    <title>FitnessCity - Envío</title>
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
            <h3>Detalles del Envío</h3>

            <?php mostrarMensajes($errors); ?>

            <form class="row g-3" action="envio.php" method="post" autocomplete="off">
                
                <div class="col-md-6">
                    <label for="calle"><span class="text-danger">*</span> Calle y número</label>
                    <input type="text" name="calle" id="calle" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label for="cp"><span class="text-danger">*</span> Código Postal</label>
                    <input type="number" name="cp" id="cp" class="form-control" required>
                    <span id="validaUsuario" class="text-danger"></span>
                </div>
                <div class="col-md-6">
                    <label for="ciudad"><span class="text-danger">*</span> Ciudad</label>
                    <input type="text" name="ciudad" id="ciudad" class="form-control" required>
                    <span id="validaUsuario" class="text-danger"></span>
                </div>

                <div class="col-md-6">
                    <label for="estado"><span class="text-danger">*</span> Estado</label>
                    <select class="form-select" name="estado" id="estado" required>
                        <option value="">Seleccionar...</option>
                        <?php foreach ($opEstado as $i) { ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>

                <div class=" d-flex justify-content-center">
                    <button type="submit" class="btn " style="background-color: #a2d45e; border-color:#a2d45e; color:#fff;">PAGAR</button>
                </div>
                

            </form>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="<?php echo SITE_URL; ?>js/bootstrap.bundle.min.js"></script>

    <script>
        let txtUsuario = document.getElementById('usuario')
        txtUsuario.addEventListener("blur", function() {
            existeUsuario(txtUsuario.value)
        }, false)

        let txtEmail = document.getElementById('email')
        txtEmail.addEventListener("blur", function() {
            existeEmail(txtEmail.value)
        }, false)

        function existeEmail(email) {
            let url = "clases/clienteAjax.php"
            let formData = new FormData()
            formData.append("action", "existeEmail")
            formData.append("email", email)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('email').value = ''
                        document.getElementById('validaEmail').innerHTML = 'Email no disponible'
                    } else {
                        document.getElementById('validaEmail').innerHTML = ''
                    }

                })
        }

        function existeUsuario(usuario) {
            let url = "clases/clienteAjax.php"
            let formData = new FormData()
            formData.append("action", "existeUsuario")
            formData.append("usuario", usuario)

            fetch(url, {
                    method: 'POST',
                    body: formData
                }).then(response => response.json())
                .then(data => {

                    if (data.ok) {
                        document.getElementById('usuario').value = ''
                        document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible'
                    } else {
                        document.getElementById('validaUsuario').innerHTML = ''
                    }

                })
        }
    </script>

</body>

</html>