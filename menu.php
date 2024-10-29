<!-- Menu de navegación -->
<header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #a2d45e;">
        <div class="container my-1">
            <a class="navbar-brand" href="index.php"><img src="images/Logo_Fit.png" height="50" width="50"/>
            <strong>FitnessCity</strong></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBarTop" aria-controls="navBarTop" aria-expanded="false">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navBarTop">
                <ul class="navbar-nav me-auto mb-2 ml-4 mb-lg-0 opcion_menu">
                    <li class="nav-item">
                        <a class="nav-link active" href="catalogo.php">PRODUCTOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="descuento.php">DESCUENTOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contacto.php">NOSOTROS</a>
                    </li>
                </ul>

                <form method="get" action="catalogo.php" autocomplete="off">
                    <div class="input-group pe-3">
                        <input type="text" name="q" class="form-control" placeholder="Buscar..." aria-describedby="icon-buscar">
                        <button class="btn btn-outline-light" type="submit" id="icon-buscar">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <a href="wish.php" class=" icono_menu">
                    <i  type="button" class="fa-solid fa-heart position-relative "> 
                        <span id="num_wish" class="position-absolute bottom-50 start-50  badge rounded-pill bg-danger"><?php echo $num_wish;?>
                        </span>
                    </i>
                </a>

                <a href="checkout.php" class=" icono_menu">
                    <i type="button" class="fa-solid fa-cart-plus position-relative "> 
                        <span id="num_cart" class="position-absolute bottom-50 start-50  badge rounded-pill bg-danger"><?php echo $num_cart;?>
                        </span>
                    </i>
                </a>

                <?php if (isset($_SESSION['user_id'])) { ?>
                    <div class="dropdown">
                        <button class="btn  mx-2 dropdown-toggle text-dark " style="background-color:#fff" type="button" id="btn_session" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user "></i> &nbsp; <?php echo $_SESSION['user_name']; ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btn_session">
                            <li><a class="dropdown-item" href="compras.php">Mis compras</a></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    
                    <a href="login.php" class=" icono_menu">
                        <i type="button" class="fa-solid fa-user position-relative "> 
                        </i>
                    </a>
                <?php } ?>
            </div>
        </div>
    </nav>
</header>