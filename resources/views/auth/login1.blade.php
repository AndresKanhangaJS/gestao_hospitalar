<!doctype html>
<html lang="en" data-layout="twocolumn" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>Sign In | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

    <!-- Layout config Js -->
    <script src="{{ asset('js/layout.js') }}"></script>

    <!-- Bootstrap Css -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ asset('css/custom.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/login.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="auth-page-wrapper" style="overflow: hidden; max-height: 100vh;">
        <div class="auth-page-content" style="overflow: hidden;">
            <div class="cont" style="display: flex; min-height: 100vh;">
                <!-- Lado esquerdo -->
                <div class="left" style="flex: 1; background-color: #f5f5f5; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                    <img class="logo mb-3" src="{{ asset('images/logo-sm.png') }}" alt="Logo">
                    <img src="{{ asset('images/financial-advisor-works-revenue-balance-with-annual-statistics.jpg') }}" alt="Imagem ilustrativa" style="max-width: 100%; height: auto;">
                </div>

                <!-- Lado direito -->
                <div class="right" style="flex: 1; align-self: center; background-color: white; display: flex; justify-content: center; align-items: center;">
                    <div style="width: 100%; max-width: 400px; margin: 20px;">
                        <div class="card-body p-4" style="border: none; box-shadow: none;">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">Seja bem-vindo</h5>
                                <p class="text-muted">A plataforma Level-Soft</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form id="loginForm">
                                    @csrf

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" placeholder="Digite seu email" value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Senha -->
                                    <div class="mb-3">
                                        <label class="form-label" for="password">Senha</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password" placeholder="Digite sua senha">
                                            <button type="button" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" onclick="togglePasswordVisibility('password')">
                                                <i class="mdi mdi-eye-outline" id="password-icon"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Botão Entrar -->
                                    <div class="mt-4">
                                        <button class="btn btn-primary w-100" type="submit">
                                            Entrar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            function togglePasswordVisibility(id) {
                const input = document.getElementById(id);
                const icon = document.getElementById('password-icon');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('mdi-eye-outline');
                    icon.classList.add('mdi-eye-off-outline');
                } else {
                    input.type = 'password';
                    icon.classList.remove('mdi-eye-off-outline');
                    icon.classList.add('mdi-eye-outline');
                }
            }
        </script>
    </div>

    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>

    <!-- particles js -->
    <script src="{{ asset('libs/particles.js/particles.js') }}"></script>
    <!-- particles app js -->
    <script src="{{ asset('js/pages/particles.app.js') }}"></script>
    <!-- password-addon init -->
    <script src="{{ asset('js/pages/password-addon.init.js') }}"></script>
</body>

</html>
