<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Wala Ka Diri</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            background: #0e0e0e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            background-image: radial-gradient(ellipse at 50% 0%, rgba(201,168,76,0.07) 0%, transparent 65%);
        }

        .wrapper {
            text-align: center;
            padding: 40px;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 900;
            color: #C9A84C;
            line-height: 1;
            letter-spacing: -4px;
        }

        .error-message {
            font-size: 1.8rem;
            font-weight: 700;
            color: #f0f0f0;
            margin: 16px 0 8px;
        }

        .error-sub {
            font-size: 1rem;
            color: #666;
            margin-bottom: 32px;
        }

        .back-btn {
            display: inline-block;
            padding: 12px 32px;
            background: #C9A84C;
            color: #0e0e0e;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 10px;
            text-decoration: none;
            transition: background 0.2s ease, box-shadow 0.2s ease;
        }

        .back-btn:hover {
            background: #d9b85c;
            box-shadow: 0 4px 20px rgba(201,168,76,0.3);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="error-code">404</div>
        <h1 class="error-message">Asa Ka Choy? 👀</h1>
        <p class="error-sub">Wala kay access dinhi. Balik sa landing page.</p>
        <a href="{{ route('landing') }}" class="back-btn">Balik Sa Imo Lugar</a>
    </div>
</body>
</html>