<div class="sidebar position-fixed p-3">
    <h5 class="text-white mb-3">
        <img src="{{ asset('images/alianzaLogo.png') }}"
             alt="Logo"
             style="height: 5rem;">
    </h5>

    <ul class="nav nav-pills flex-column gap-1">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" 
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        @if(in_array('documentos.ver', session('permisos_usuario', [])))
        <li class="nav-item">
                <a href="{{ route('documentos.index') }}"
                    class="nav-link {{ request()->routeIs('documentos.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i>
                <span>Documentos</span>
            </a>
        </li>
        @endif

        {{-- Usuarios --}}
        @if(in_array('usuarios.ver', session('permisos_usuario', [])))
        <li class="nav-item">
            <a href="{{ route('usuarios.index') }}"
                class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Usuarios</span>
            </a>
        </li>
        @endif

        {{-- Colaboradores --}}
        @if(in_array('colaboradores.ver', session('permisos_usuario', [])))
        <li class="nav-item">
            <a href="{{ route('colaboradores.index') }}"
            class="nav-link {{ request()->routeIs('colaboradores.*', 'informacion_adicional.*','contrato.*','contratos.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge""></i>
                <span>Colaboradores</span>
            </a>
        </li>
        @endif

        {{-- Empresas --}}
        @if(in_array('empresas.ver', session('permisos_usuario', [])))
        <li class="nav-item">
            <a href="{{ route('empresas.index') }}"
            class="nav-link {{ request()->routeIs('empresas.*','contratosEmpresa.*') ? 'active' : '' }}">
                <i class="bi bi-buildings"></i>
                <span>Empresas</span>
            </a>
        </li>
        @endif


        {{-- Roles --}}
        @if(in_array('roles.ver', session('permisos_usuario', [])))
        <li class="nav-item">
            <a href="{{ route('roles.index') }}"
            class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                <i class="bi bi-shield-lock"></i>
                <span>Roles</span>
            </a>
        </li>
        @endif

        {{-- Categorias --}}
        @if(in_array('categorias.ver', session('permisos_usuario', [])))
        <li class="nav-item">
            <a href="{{ route('categorias.index') }}"
            class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i>
                <span>Categorías</span>
            </a>
        </li>
        @endif

        {{-- Areas --}}
        @if(in_array('areas.ver', session('permisos_usuario', [])))
        <li class="nav-item">
            <a href="{{ route('areas.index') }}"
            class="nav-link {{ request()->routeIs('areas.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i>
                <span>Áreas</span>
            </a>
        </li>
        @endif

        {{-- Historico Documentos --}}
        @if(in_array('historial.ver', session('permisos_usuario', [])))
        <li class="nav-item">
            <a href="{{ route('historial_documentos.index') }}"
            class="nav-link {{ request()->routeIs('historial_documentos.*') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i>
                <span>Historial Documentos</span>
            </a>
        </li>
        @endif


        <li class="nav-heading">Sistema</li>

        <li class="nav-item">
            <a href="{{ route('perfil.index') }}"
            class="nav-link {{ request()->routeIs('perfil.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                <span>Mi Perfil</span>
            </a>
        </li>

        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
            @csrf
                <button class="nav-link btn btn-link text-start">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </li>
    </ul>
</div>
