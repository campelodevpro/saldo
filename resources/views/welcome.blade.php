<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jogo da Cobrinha</title>
    <style>
        body {
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #111;
            overflow: hidden;
        }

        .snake-container {
            position: relative;
            width: 100px;
            height: 100px;
        }

        .segment {
            position: absolute;
            width: 20px;
            height: 20px;
            background-color: limegreen;
            border-radius: 5px;
            animation: moveSnake 4s linear infinite;
        }

        .segment:nth-child(2) {
            animation-delay: 0.2s;
        }

        .segment:nth-child(3) {
            animation-delay: 0.4s;
        }

        .segment:nth-child(4) {
            animation-delay: 0.6s;
        }

        .segment:nth-child(5) {
            animation-delay: 0.8s;
        }

        @keyframes moveSnake {
            0% { transform: translate(0, 0); }
            25% { transform: translate(100px, 0); }
            50% { transform: translate(100px, 100px); }
            75% { transform: translate(0, 100px); }
            100% { transform: translate(0, 0); }
        }
    </style>
</head>
<body>
    <div class="snake-container">
        <div class="segment"></div>
        <div class="segment"></div>
        <div class="segment"></div>
        <div class="segment"></div>
        <div class="segment"></div>
    </div>
</body>
</html>
