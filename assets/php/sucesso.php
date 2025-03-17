<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrega Realizada</title>
    <style>
        body{
            background-color: #eeeeee;
        }
        .success-container {
            text-align: center;
            padding: 20px;
            background-color: #eeeeee;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
            height: 100vh;
        }

        .success-message {
            font-size: 2rem;
            color: navy;
            font-weight: bold;
            opacity: 0;
            animation: slideIn 1.5s forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .back-button {
            background: #007bff;
            color: white;
            padding: 10px 40px;
            margin-top: 80px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .back-button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }
        h1{
            margin-top: 50px;
            margin-bottom: 50px;
        }
    </style>
</head>
<body>
    <div class="success-container">
    <div class="logo">
            <a href="index.html"> <img src="../imagens/logo-gagliardi.png" alt="Logo_atlastrack" width="300px"
                    height="70"></a>
        </div>
        <h1 class="success-message">Entrega Realizada!</h1>
        <p>AtlasTrack agradece a parceria e reconhece a eficiência do seu trabalho.</p>
        <button class="back-button" onclick="window.history.back()">Voltar</button>
    </div>
</body>
</html>
