<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Noon-Care</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 480px;
            margin: 60px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            padding: 32px 24px;
            text-align: center;
        }
        h1 {
            color: #2d6a4f;
            margin-bottom: 16px;
        }
        p {
            color: #555;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            background: #2d6a4f;
            color: #fff;
            padding: 12px 32px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn:hover {
            background: #40916c;
        }
        .logo {
            width: 64px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://img.icons8.com/fluency/96/medical-doctor.png" alt="Noon-Care Logo" class="logo">
        <h1>Welcome to Noon-Care</h1>
        <p>
            Your trusted platform for booking clinics and doctor appointments.<br>
            Find the right healthcare professional and schedule your visit with ease.
        </p>
        <a href="{{ route('login') }}" class="btn">Get Started</a>
    </div>
</body>
</html>