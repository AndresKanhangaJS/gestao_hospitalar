<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Level Soft</title>
    <link rel="stylesheet" href="{{asset('css/login.css')}}" />

    <style>
        /* Barra de progresso estilo YouTube */
        #topLoader {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0%;
            background: #3a50e0;
            z-index: 9999;
            transition: width 0.3s ease;
        }

        /* Spinner dentro do botão */
        .button-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .button-loading::after {
            content: "";
            width: 18px;
            height: 18px;
            border: 2px solid #fff;
            border-top-color: transparent;
            border-radius: 50%;
            display: inline-block;
            margin-left: 10px;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
<div id="topLoader"></div>

<div class="container">

    <!-- Lado Esquerdo - Imagem -->
    <div class="left">
        <div class="overlay"></div>
        <img src="{{ asset('images/financial-advisor-works-revenue-balance-with-annual-statistics.jpg') }}" alt="Imagem ilustrativa">
    </div>

    <!-- Lado Direito - Formulário -->
    <div class="right">
        <div class="form-box">

            <h2>Seja bem-vindo ao Level-Health</h2>
            <p class="subtitle">Software de Gestão Hospitalar</p>

            <form id="loginForm">

                @csrf

                <label>Email</label>
                <input type="email" id="email" placeholder="Enter email">

                <label>Senha</label>
                <div class="password-field">
                    <input type="password" id="password" placeholder="Enter password">
                </div>

                <button type="submit">Entrar</button>

                <p id="errorMessage" style="color: red; margin-top: 10px;"></p>

            </form>

        </div>
    </div>

</div>


<script>
    const loginForm = document.getElementById('loginForm');
    const button = loginForm.querySelector('button'); // 🔥 disponível globalmente
    const topLoader = document.getElementById('topLoader');
    const errorMessage = document.getElementById('errorMessage');

    loginForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const csrf = document.querySelector('input[name="_token"]').value;

        errorMessage.textContent = "";

        // 🔵 inicia barra
        topLoader.style.width = "30%";

        // Loading no botão
        button.classList.add("button-loading");
        button.disabled = true;

        try {
            const response = await fetch("{{ route('login') }}", {
                method: "POST",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf
                },
                body: JSON.stringify({
                    email: email,
                    password: password
                })
            });

            // Avança barra
            topLoader.style.width = "70%";

            let result;

            try {
                result = await response.json();
            } catch (jsonError) {
                errorMessage.textContent = "Erro inesperado: resposta inválida do servidor.";

                console.error("Resposta não-JSON:", await response.text());
                resetUI();
                return;
            }

            // 🔥 Erro de credenciais
            if (response.status === 401) {
                errorMessage.textContent = result.message;
                resetUI();
                return;
            }

            // 🔥 Erro de validação (422)
            if (response.status === 422) {
                if (result.errors.email) {
                    errorMessage.textContent = result.errors.email[0];
                } else if (result.errors.password) {
                    errorMessage.textContent = result.errors.password[0];
                }

                resetUI();
                return;
            }

            // 🔥 Falta redirect no backend
            if (!result.redirect) {
                errorMessage.textContent = "Erro: servidor não enviou rota de redirecionamento.";
                console.error("Backend retornou:", result);

                resetUI();
                return;
            }

            // Concluir barra
            topLoader.style.width = "100%";

            // Redirecionar
            window.location.href = result.redirect;

        } catch (error) {
            errorMessage.textContent = "Erro ao conectar ao servidor.";
            console.error(error);
            resetUI();
        }
    });

    // 🔧 Função global para restaurar UI
    function resetUI() {
        button.disabled = false;
        button.classList.remove("button-loading");
        topLoader.style.width = "0%";
    }
</script>





</body>
</html>
