<?php
if (isset($dashboard))
    $dashboard = ($dashboard == '' ? '' : $dashboard);
else
    $dashboard = "";


if (isset($sales))
    $sales = ($sales == '' ? '' : $sales);
else
    $sales = "";

if (isset($facturas))
    $facturas = ($facturas == '' ? '' : $facturas);
else
    $facturas = "";

if (isset($pedidos))
    $pedidos = ($pedidos == '' ? '' : $pedidos);
else
    $pedidos = "";

if (isset($cotizacion))
    $cotizacion = ($cotizacion == '' ? '' : $cotizacion);
else
    $cotizacion = "";

if (isset($purchase))
    $purchase = ($purchase == '' ? '' : $purchase);
else
    $purchase = "";

if (isset($proveedor))
    $proveedor = ($proveedor == '' ? '' : $proveedor);
else
    $proveedor = "";


if (isset($requisicion))
    $requisicion = ($requisicion == '' ? '' : $requisicion);
else
    $requisicion = "";


if (isset($ordenescompra))
    $ordenescompra = ($ordenescompra == '' ? '' : $ordenescompra);
else
    $ordenescompra = "";

if (isset($inventory))
    $inventory = ($inventory == '' ? '' : $inventory);
else
    $inventory = "";

if (isset($productos))
    $productos = ($productos == '' ? '' : $productos);
else
    $productos = "";


if (isset($categoria))
    $categoria = ($categoria == '' ? '' : $categoria);
else
    $categoria = "";

if (isset($clientes))
    $clientes = ($clientes == '' ? '' : $clientes);
else
    $clientes = "";



if (isset($configuration))
    $configuration = ($configuration == '' ? '' : $configuration);
else
    $configuration = "";




if (isset($actualizarPassword))
    $actualizarPassword = ($actualizarPassword == '' ? '' : $actualizarPassword);
else
    $actualizarPassword = "";


if (isset($cambiarstatus))
    $cambiarstatus = ($cambiarstatus == '' ? '' : $cambiarstatus);
else
    $cambiarstatus = "";

if (isset($almacen))
    $almacen = ($almacen == '' ? '' : $almacen);
else
    $almacen = "";


if (isset($rep_mercancia))
    $rep_mercancia = ($rep_mercancia == '' ? '' : $rep_mercancia);
else
    $rep_mercancia = "";

if (isset($des_mercancia))
    $des_mercancia = ($des_mercancia == '' ? '' : $des_mercancia);
else
    $des_mercancia = "";


if (isset($usersregister))
    $usersregister = ($usersregister == '' ? '' : $usersregister);
else
    $usersregister = "";



if (isset($logout))
    $logout = ($logout == '' ? '' : $logout);
else
    $logout = "";

//seguir
?>

<div class="sidebar-wrapper">
    <ul class="nav">
        <li class="nav-item  ">

            <a class="nav-link" href="#">
                <i class="material-icons">person</i>
                <p><?php echo $VAR_SESSION->username;?></p>
            </a>

        </li>

        <li class="nav-item <?php echo $dashboard; ?>  ">
            <a class="nav-link" href="./?view=dashboard">
                <i class="material-icons">dashboard</i>
                <p>Dashboard</p>
            </a>
        </li>


        <li class="nav-item <?php echo $sales; ?> ">
            <a class="nav-link collapsed" data-toggle="collapse" href="#sales" aria-expanded="false">
                <i class="material-icons">assignment</i>
                <p> Ventas

                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="sales" style="">
                <ul class="nav">
                    <li class="nav-item <?php echo $clientes; ?>">
                        <a class="nav-link" href="./?view=customers">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Clientes </span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo $pedidos; ?> ">
                        <a class="nav-link" href="./?view=orders">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Pedidos</span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo $cotizacion; ?>">
                        <a class="nav-link" href="./?view=quotes">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Cotizaciones </span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo $facturas; ?> ">
                        <a class="nav-link" href="./?view=bills">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Facturas </span>
                        </a>
                    </li>




                </ul>
            </div>
        </li>

        <li class="nav-item <?php echo $purchase; ?> ">
            <a class="nav-link collapsed" data-toggle="collapse" href="#purchase" aria-expanded="false">
                <i class="fa fa-shopping-cart"></i>
                <p> Compras

                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="purchase" style="">
                <ul class="nav">
                    <li class="nav-item <?php echo $proveedor; ?> ">
                        <a class="nav-link" href="./?view=providers">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Proveedor </span>
                        </a>
                    </li>


                    <li class="nav-item <?php echo $requisicion; ?> ">
                        <a class="nav-link" href="./?view=purchase-requests">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Requisición</span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo $ordenescompra; ?>">
                        <a class="nav-link" href="./?view=purchase-order">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Ordenes de Compra </span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item <?php echo $inventory; ?> ">
            <a class="nav-link collapsed" data-toggle="collapse" href="#inventory" aria-expanded="false">
                <i class="fa fa-server"></i>
                <p> Inventario

                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="inventory" style="">
                <ul class="nav">
                    <li class="nav-item <?php echo $productos; ?> ">
                        <a class="nav-link" href="./?view=products">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Productos </span>
                        </a>
                    </li>


                    <li class="nav-item <?php echo $categoria; ?> ">
                        <a class="nav-link" href="./?view=category-products">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Categoria L1</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item <?php echo $almacen; ?> ">
            <a class="nav-link collapsed" data-toggle="collapse" href="#almacen" aria-expanded="false">
                <i class="fa fa-paste"></i>
                <p> Almacen

                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="almacen" style="">
                <ul class="nav">
                    <li class="nav-item <?php echo $rep_mercancia; ?> ">
                        <a class="nav-link" href="./?view=receive-merchant">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Recepción de Mercancía </span>
                        </a>
                    </li>


                    <li class="nav-item <?php echo $des_mercancia; ?> ">
                        <a class="nav-link" href="./?view=dispatch-merchant">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Despacho de Mercancía</span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item <?php echo $configuration; ?> ">
            <a class="nav-link collapsed" data-toggle="collapse" href="#configuration" aria-expanded="false">
                <i class="fa fa-cog"></i>
                <p> Configuración

                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="configuration" style="">
                <ul class="nav">
                    <li class="nav-item <?php echo $actualizarPassword; ?> ">
                        <a class="nav-link" href="./?view=update-password">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Actualizar contraseña </span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $cambiarstatus; ?> ">
                        <a class="nav-link" href="./?view=change-status">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal"> Cambiar de status</span>
                        </a>
                    </li>
                    <li class="nav-item <?php echo $usersregister; ?> ">
                        <a class="nav-link" href="./?view=users">
                            <span class="sidebar-mini"> <i class="fa fa-arrow-right"></i> </span>
                            <span class="sidebar-normal">Usuarios </span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>


        <li class="nav-item <?php echo $logout; ?> ">
            <a class="nav-link" href="./?view=logout">
                <i class="material-icons">exit_to_app</i>
                <p>Salir</p>
            </a>
        </li>

    </ul>
</div>
