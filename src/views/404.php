<?php
header("HTTP/1.0 404 Not Found");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found | Your Site Name</title>
    <style>
        /* CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #343a40;
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 20px;
        }
        
        .error-container {
            max-width: 600px;
            padding: 40px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .error-code {
            font-size: 120px;
            font-weight: 700;
            color: #495057;
            margin-bottom: 20px;
            line-height: 1;
        }
        
        .error-title {
            font-size: 32px;
            margin-bottom: 20px;
            color: #212529;
        }
        
        .error-message {
            font-size: 18px;
            margin-bottom: 30px;
            color: #6c757d;
        }
        
        .home-button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #4361ee;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .home-button:hover {
            background-color: #3a56d4;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(58, 86, 212, 0.3);
        }
        
        .error-image {
            max-width: 200px;
            margin: 0 auto 30px;
            display: block;
        }
        
        @media (max-width: 768px) {
            .error-code {
                font-size: 80px;
            }
            
            .error-title {
                font-size: 24px;
            }
            
            .error-message {
                font-size: 16px;
            }
            
            .error-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <svg class="error-image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#4361ee" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">
            The page you're looking for doesn't exist or has been moved.
            <br>
            Please check the URL or return to the homepage.
        </p>
        <a href="/" class="home-button">Return to Homepage</a>
    </div>
</body>
</html>