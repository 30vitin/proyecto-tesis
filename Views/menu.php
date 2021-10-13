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
                            <span class="sidebar-mini"> CL </span>
                            <span class="sidebar-normal"> Clientes </span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo $cotizacion; ?>">
                        <a class="nav-link" href="./?view=quotes">
                            <span class="sidebar-mini"> CO </span>
                            <span class="sidebar-normal"> Cotizaciones </span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo $facturas; ?> ">
                        <a class="nav-link" href="./?view=bills">
                            <span class="sidebar-mini"> FA </span>
                            <span class="sidebar-normal"> Facturas </span>
                        </a>
                    </li>


                    <li class="nav-item <?php echo $pedidos; ?> ">
                        <a class="nav-link" href="./?view=orders">
                            <span class="sidebar-mini"> PE </span>
                            <span class="sidebar-normal"> Pedidos</span>
                        </a>
                    </li>


                </ul>
            </div>
        </li>

        <li class="nav-item <?php echo $purchase; ?> ">
            <a class="nav-link collapsed" data-toggle="collapse" href="#purchase" aria-expanded="false">
                <i class="material-icons">assignment</i>
                <p> Compras

                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="purchase" style="">
                <ul class="nav">
                    <li class="nav-item <?php echo $proveedor; ?> ">
                        <a class="nav-link" href="./?view=providers">
                            <span class="sidebar-mini"> PR </span>
                            <span class="sidebar-normal"> Proveedor </span>
                        </a>
                    </li>


                    <li class="nav-item <?php echo $requisicion; ?> ">
                        <a class="nav-link" href="./?view=purchase-requests">
                            <span class="sidebar-mini"> RE </span>
                            <span class="sidebar-normal"> Requisición</span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo $ordenescompra; ?>">
                        <a class="nav-link" href="./?view=purchase-order">
                            <span class="sidebar-mini"> OC </span>
                            <span class="sidebar-normal"> Ordenes de Compra </span>
                        </a>
                    </li>

                </ul>
            </div>
        </li>

        <li class="nav-item <?php echo $inventory; ?> ">
            <a class="nav-link collapsed" data-toggle="collapse" href="#inventory" aria-expanded="false">
                <i class="material-icons">assignment</i>
                <p> Inventario

                    <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="inventory" style="">
                <ul class="nav">
                    <li class="nav-item <?php echo $productos; ?> ">
                        <a class="nav-link" href="./?view=products">
                            <span class="sidebar-mini"> PR </span>
                            <span class="sidebar-normal"> Productos </span>
                        </a>
                    </li>


                    <li class="nav-item <?php echo $categoria; ?> ">
                        <a class="nav-link" href="./?view=category-products">
                            <span class="sidebar-mini"> RE </span>
                            <span class="sidebar-normal"> Categoria L1</span>
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
