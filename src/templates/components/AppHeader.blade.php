<?php use Src\Config\AppConfig; ?>

<nav class="app-header navbar navbar-expand bg-body shadow">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Home</a>
            </li>
        </ul>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <Image src="{{ AppConfig::COMPANY["LOGO_PATH"] }}" class="user-image rounded-circle shadow"
                        alt="User Image" />
                    <span class="d-none d-md-inline">Alexander Pierce</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-primary">
                        <Image src="{{ AppConfig::COMPANY["LOGO_PATH"] }}" class="rounded-circle shadow"
                            alt="User Image" />
                        <p>
                            Alexander Pierce - Web Developer
                            <small>Member since Nov. 2023</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat">
                            <i class="bi bi-gear-fill"></i>
                        </a>
                        <a href="#" class="btn btn-default btn-flat float-end">
                            <i class="bi bi-ticket-detailed-fill"></i>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>