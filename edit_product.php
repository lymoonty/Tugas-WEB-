<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: produk.php'); 
    exit();
}

$pdo = getDBConnection();
$product_id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    die('Produk tidak ditemukan.');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name'] ?? '');
    $description = sanitizeInput($_POST['description'] ?? '');
    $price = filter_var($_POST['price'] ?? 0, FILTER_VALIDATE_INT);
    $stock = filter_var($_POST['stock'] ?? 0, FILTER_VALIDATE_INT);
    $category_id = filter_var($_POST['category_id'] ?? 0, FILTER_VALIDATE_INT);

    if (empty($name)) {
        $error = 'Nama produk wajib diisi!';
    } elseif ($price == false || $price < 0) {
        $error = 'Harga harus berupa angka dan tidak boleh negatif!';
    } elseif ($stock === false || $stock < 0) {
        $error = 'Stok tidak boleh negatif!';
    } elseif ($category_id <= 0) {
        $error = 'Kategori harus dipilih!';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $stock, $category_id, $product_id]);
            header('Location: produk.php?msg=updated'); 
            exit();
        } catch (PDOException $e) {
            error_log("Update Product Error: " . $e->getMessage());
            $error = 'Gagal memperbarui produk. Silakan coba lagi.';
        }
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Hiro Petshop</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        h1 {
            color: #667eea;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
        }
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            width: 100%;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
        .btn-back {
            background: #e9ecef;
            color: #333;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            margin-top: 10px;
            padding: 12px 25px;
            border-radius: 8px;
        }
        .alert-error {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-edit"></i> Edit Produk</h1>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Nama Produk *</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($product['name']) ?>">
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="price">Harga (Rp) *</label>
                <input type="number" id="price" name="price" min="1" required value="<?= htmlspecialchars($product['price']) ?>">
            </div>

            <div class="form-group">
                <label for="stock">Stok *</label>
                <input type="number" id="stock" name="stock" min="0" required value="<?= htmlspecialchars($product['stock']) ?>">
            </div>

            <div class="form-group">
                <label for="category_id">Kategori *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $product['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Perbarui Produk</button>
            <a href="produk.php" class="btn btn-back">← Kembali ke Daftar Produk</a>
        </form>
    </div>
</body>
</html>