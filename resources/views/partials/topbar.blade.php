<nav class="navbar navbar-light bg-white shadow-sm px-4">
    <form class="d-flex w-50">
        <input class="form-control" type="search" placeholder="Buscar documentos, usuarios..." aria-label="Buscar">
    </form>

    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
           data-bs-toggle="dropdown">
            <i class="bi bi-person-circle fs-4 me-2"></i>
            {{ session('usuario_nombre') }}
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <span class="dropdown-item-text">
                    Rol: {{ session('rol_nombre') }}
                </span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="{{ route('perfil.index') }}">
                    <i class="bi bi-person-circle me-2"></i> Mi Perfil
                </a>
            </li>
        </ul>
    </div>
</nav>
