<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content="Sistema de sorteos"/>
    <meta name="author" content=""/>
    <title>Sistema de sorteos - @yield('title')</title>
    <link href="{{ asset('css/template.css')  }}" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @stack('css') {{-- Normalmente se usa para poner css, js en paginas que se heredan --}}
</head>

@auth()
    <body class="sb-nav-fixed">

    <x-navigation-header></x-navigation-header>

    <div id="layoutSidenav">

        <x-navigation-menu></x-navigation-menu>

        <div id="layoutSidenav_content">

            <main>
                @if(session('mensaje'))

                    <script>

                        $(document).ready(function () {
                            let Mensaje = "{{session('mensaje')}}";
                            console.log(Mensaje)
                            Mensaje = Mensaje.split('__');
                            console.log(Mensaje)

                            SwalToast(Mensaje[1], Mensaje[0], 3000, "top");
                        });


                    </script>
                @endif
                @yield('content')

            </main>

            <x-footer></x-footer>

        </div>
    </div>
    {{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
    <script src="{{ asset('js/scripts.js')  }}"></script>
    <script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
    <!-- SweetAlert -->
    <script>
        async function SwalAlert(icon, title, text = '') {
            return Swal.fire({
                icon,
                title,
                text
            });
        }

        async function SwalLoading(title = 'Cargando...') {
            return Swal.fire({
                title,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }

        async function SwalToast(title, icon, timer = 3000, position = 'top-end') {
            return Swal.fire({
                title,
                icon,
                toast: true,
                position,
                showConfirmButton: false,
                timer,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
        }

        async function SwalAlertOption(title, text, icon, confirmButtonText, denyButtonText, cancelButtonText = '') {
            const showCancelButton = cancelButtonText != '';
            return Swal.fire({
                title,
                text,
                icon,
                confirmButtonText,
                showDenyButton: true,
                denyButtonText,
                showCancelButton,
                cancelButtonText,
                customClass: {
                    confirmButton: "btn btn-success",
                    denyButton: "btn btn-danger"
                }
            });
        }

    </script>
    @stack('js')

    </body>
@endauth

@guest()
    @include('pages.401')
@endguest

</html>
