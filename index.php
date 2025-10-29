<?php

date_default_timezone_set('Asia/Jakarta');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hiro Petshop - Toko Hewan Peliharaan Terpercaya</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf9 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        
        .header .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.8rem;
            font-weight: 700;
        }
        
        .header h1::before {
            content: '🐾';
            margin-right: 10px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 60px 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .hero {
            background: white;
            padding: 60px 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        
        .hero h2 {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 20px;
        }
        
        .hero p {
            color: #555;
            font-size: 1.2rem;
            line-height: 1.7;
            margin-bottom: 30px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 40px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .features {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 40px;
            flex-wrap: wrap;
        }
        
        .feature {
            background: #f8f9ff;
            padding: 20px;
            border-radius: 15px;
            width: 180px;
        }
        
        .feature i {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 10px;
        }
        
        .feature h3 {
            font-size: 1rem;
            color: #333;
        }
        
        footer {
            text-align: center;
            padding: 20px;
            color: #777;
            font-size: 0.9rem;
            background: #f8f9fa;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Hiro Petshop</h1>
            <span>❤️ Sayangi Hewan Peliharaanmu</span>
        </div>
    </div>
    
    <div class="container">
        <div class="hero">
            <h2>Selamat Datang di Hiro Petshop!</h2>
            <p>Kami menyediakan berbagai kebutuhan hewan peliharaan Anda: makanan, mainan, aksesoris, dan perawatan berkualitas dengan harga terjangkau.</p>
            
            <a href="login.php" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Masuk ke Dashboard
            </a>
            
            <div class="features">
                <div class="feature">
                    <i class="fas fa-paw"></i>
                    <h3>Hewan Sehat</h3>
                </div>
                <div class="feature">
                    <i class="fas fa-box"></i>
                    <h3>Produk Lengkap</h3>
                </div>
                <div class="feature">
                    <i class="fas fa-shipping-fast"></i>
                    <h3>Pengiriman Cepat</h3>
                </div>
            </div>
        </div>
    </div>
    
    <footer>
        &copy; <?= date('Y') ?> Hiro Petshop. Sistem Manajemen Produk oleh Triya Khairun Nisa.
    </footer>
</body>
</html>