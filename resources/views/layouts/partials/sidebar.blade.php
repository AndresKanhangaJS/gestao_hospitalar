<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('dashboard') }}">
                        <i class="ri-home-8-line"></i> <span data-key="t-dashboards">Dashboards</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarConfigs" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarConfigs">
                        <i class="ri-settings-5-line"></i> <span data-key="t-apps">Configurações</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarConfigs">
                        <ul class="nav nav-sm flex-column">
                            @can('acl.menu')
                            <li class="nav-item">
                                <a href="#sidebarACL" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarACL" data-key="t-calender">
                                    ACL
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarACL">
                                    <ul class="nav nav-sm flex-column">
                                        @can('papeis.menu')
                                        <li class="nav-item">
                                            <a href="{{ route('roles.index') }}" class="nav-link" data-key="t-main-calender"> Papeis </a>
                                        </li>
                                        @endcan
                                        @can('permissoes.menu')
                                        <li class="nav-item">
                                            <a href="{{ route('permissions.index') }}" class="nav-link" data-key="t-month-grid"> Permissões </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                            @endcan
                            @can('usuarios.menu')
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link" data-key="t-users"> Usuários </a>
                            </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('seguradoras.index') }}" class="nav-link" data-key="t-convenios"> Convénios </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @can('gestao_pacientes.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarGestPacientes" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarGestPacientes">
                        <i class="ri-user-heart-line"></i> <span data-key="t-apps">Pacientes</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarGestPacientes">
                        <ul class="nav nav-sm flex-column">
                            @can('pacientes.menu')
                            <li class="nav-item">
                                <a href="{{ route('episodios.index') }}" class="nav-link">
                                    Atendimentos/Episodios
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#sidebarPacientes" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarPacientes" data-key="t-calender">
                                    Pacientes
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarPacientes">
                                    <ul class="nav nav-sm flex-column">
                                        @can('pacientes.registar')
                                        <li class="nav-item">
                                            <a href="{{ route('pacientes.create') }}" class="nav-link"> Registar </a>
                                        </li>
                                        @endcan
                                        @can('pacientes.listar')
                                        <li class="nav-item">
                                            <a href="{{ route('pacientes.index') }}" class="nav-link"> Listar </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan
                @can('gestao_medicos.menu')
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarGestMedicos" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarGestMedicos">
                        <i class="ri-stethoscope-line"></i> <span data-key="t-apps">Médicos</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarGestMedicos">
                        <ul class="nav nav-sm flex-column">
                            @can('medicos.menu')
                            <li class="nav-item">
                                <a href="#sidebarMedicos" class="nav-link" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMedicos" data-key="t-calender">
                                    Médicos
                                </a>
                                <div class="collapse menu-dropdown" id="sidebarMedicos">
                                    <ul class="nav nav-sm flex-column">
                                        @can('medicos.registar')
                                        <li class="nav-item">
                                            <a href="{{ route('medicos.create') }}" class="nav-link"> Registar </a>
                                        </li>
                                        @endcan
                                        @can('medicos.listar')
                                        <li class="nav-item">
                                            <a href="{{ route('medicos.index') }}" class="nav-link"> Listar </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
