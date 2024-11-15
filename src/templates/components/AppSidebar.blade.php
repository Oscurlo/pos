<?php use Src\Config\AppConfig; ?>

<aside class="app-sidebar bg-body-secondary" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="#" class="brand-link">
            <img src="{{ AppConfig::COMPANY["LOGO_PATH"] }}" alt="{{ AppConfig::COMPANY["NAME"] }} Logo"
                class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">{{ AppConfig::COMPANY["NAME"] }}</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-header" role="menuitem">Dashboard</li>
                <li class="nav-item" role="menuitem">
                    <a href="./" class="nav-link">
                        <i class="nav-icon bi bi-grid-1x2-fill"></i>
                        <p>Home</p>
                    </a>
                </li>
                <li class="nav-item" role="menuitem">
                    <a href="./customers" class="nav-link">
                        <i class="nav-icon bi bi-people"></i>
                        <p>Clientes</p>
                    </a>
                </li>
                <!-------------------------------------------------->
                <li class="nav-header" role="menuitem">Productos</li>
                <li class="nav-item" role="menuitem">
                    <a href="./products" class="nav-link">
                        <i class="nav-icon bi bi-archive"></i>
                        <p>Productos</p>
                    </a>
                </li>
                <li class="nav-item" role="menuitem">
                    <a href="./categories" class="nav-link">
                        <i class="nav-icon bi bi-list-task"></i>
                        <p>Categorias</p>
                    </a>
                </li>
                <li class="nav-item" role="menuitem">
                    <a href="./suppliers" class="nav-link">
                        <i class="nav-icon bi bi-truck"></i>
                        <p>Proveedores</p>
                    </a>
                </li>
                <!-------------------------------------------------->
                <li class="nav-header" role="menuitem">POS</li>
                <li class="nav-item" role="menuitem">
                    <a href="./cash_registers" class="nav-link">
                        <i class="nav-icon bi bi-basket"></i>
                        <p>Cajas</p>
                    </a>
                </li>
                <li class="nav-item" role="menuitem">
                    <a href="#" class="nav-link bg-danger text-light">
                        <i class="nav-icon bi bi-door-open-fill"></i>
                        <p>Salir</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>