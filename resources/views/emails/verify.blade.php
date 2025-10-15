<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        .header {
            text-align: center;
            padding: 10px 0;
        }
        .header p {
            margin: 0;
            color: #3d4852;
            font-size: 19px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            color: #555555;
            line-height: 1.6;
        }
        .content p {
            margin: 0 0 20px;
        }
        .content a {
            display: inline-block;
            padding: 8px 10px;
            background-color: #22416B;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .content a:hover {
            background-color: #3AAFE2;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #aaaaaa;
            border-top: 1px solid #dddddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <p>Border Life - LIS</p>
    </div>
    <div class="container">
        <div class="content">
            <p> <b>Good Day</b> {{ $first_name }} {{ $surname }},</p>
            <p>Your account has been created, you need to click on the link below to set up your password and 2FA:</p>
            <a href="{{ url('verify', $token) }}">Create Password</a>
        </div>
        <div class="footer">
            <p>© 2024 BorderLife-LIS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
