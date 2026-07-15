<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Inicio</div>

                <a class="nav-link" href="{{ route('dashboard') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    Escritorio
                </a>

                <a class="nav-link" href="{{ route('user.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fa fa-users" aria-hidden="true"></i>
                    </div>
                    Usuarios
                </a>

                <a class="nav-link" href="{{ route('raffle.index') }}">
                    <div class="sb-nav-link-icon">
                        <i class="fa fa-ticket" aria-hidden="true"></i>
                    </div>
                    Sorteos
                </a>
                {{--<div class="sb-sidenav-menu-heading">Modulos</div>
                <a class="nav-link" href="{{route('banco.index')}}">
                    <div class="sb-nav-link-icon"><i class="fa fa-university" aria-hidden="true"></i></div>
                    Bancos
                </a>--}}
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Bienvenido:</div>
            {{ auth()->user()->name }}
        </div>
    </nav>
</div>
