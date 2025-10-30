<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$pdo = getDBConnection();
$records_per_page = 5;
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($current_page - 1) * $records_per_page;

$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$where_conditions = [];
$params = [];

if (!empty($search)) {
    $where_conditions[] = "(p.name LIKE ? OR p.description LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if ($category_filter > 0) {
    $where_conditions[] = "p.category_id = ?";
    $params[] = $category_filter;
}

$where_sql = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

$count_sql = "SELECT COUNT(*) FROM products p {$where_sql}";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_records = $count_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

$sql = "SELECT p.*, c.name as category_name 
        FROM products p 
        INNER JOIN categories c ON p.category_id = c.id 
        {$where_sql} 
        ORDER BY p.created_at DESC 
        LIMIT {$records_per_page} OFFSET {$offset}";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$success_msg = '';
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'added':
            $success_msg = 'Produk berhasil ditambahkan!';
            break;
        case 'updated':
            $success_msg = 'Produk berhasil diupdate!';
            break;
        case 'deleted':
            $success_msg = 'Produk berhasil dihapus!';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - Hiro Petshop</title>
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
            background: #f8f9fa;
            min-height: 100vh;
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
        
        .btn-logout {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .page-title {
            font-size: 1.8rem;
            color: #667eea;
        }
        
        .btn-add {
            background: linear-gradient(135deg, #4CAF50 0%, #2E7D32 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-add:hover {
            opacity: 0.9;
        }
        
        .filters {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        
        .filters form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items-end;
        }
        
        .filters input,
        .filters select,
        .filters button {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
        }
        
        .filters button {
            background: #667eea;
            color: white;
            cursor: pointer;
        }
        
        .filters button:hover {
            background: #5a6fd8;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        .price {
            font-weight: 600;
            color: #2E7D32;
        }
        
        .stock-low {
            color: #e74c3c;
            font-weight: 600;
        }
        
        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 4px 0;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: opacity 0.2s;
        }
        
        .action-btn:hover {
            opacity: 0.9;
        }
        
        .edit-btn {
            background: #4CAF50;
        }
        
        .delete-btn {
            background: #f44336;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }
        
        .pagination a,
        .pagination span {
            padding: 8px 14px;
            text-decoration: none;
            border: 1px solid #ddd;
            color: #667eea;
            border-radius: 6px;
        }
        
        .pagination .current {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }
        
        .pagination a:hover {
            background: #f0f4ff;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Daftar Produk</h1>
            <a href="logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
    
    <div class="container">
        <div class="page-header">
            <h2 class="page-title">Kelola Produk</h2>
            <a href="add_product.php" class="btn-add">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>

        <?php if ($success_msg): ?>
            <div class="alert-success"><?= htmlspecialchars($success_msg) ?></div>
        <?php endif; ?>

        <!-- Filter Pencarian -->
        <div class="filters">
            <form method="GET">
                <input type="text" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
                <select name="category">
                    <option value="0">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit"><i class="fas fa-search"></i> Cari</button>
                <?php if ($search || $category_filter): ?>
                    <a href="produk.php" style="padding: 10px 15px; background: #e9ecef; border-radius: 8px; text-decoration: none; color: #333;">
                        Reset Filter
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Tabel Produk -->
        <div class="table-container">
            <?php if (empty($products)): ?>
                <div style="padding: 40px; text-align: center; color: #6c757d;">
                    <i class="fas fa-box-open" style="font-size: 2rem; margin-bottom: 10px;"></i><br>
                    Tidak ada produk ditemukan.
                </div>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                            <td><?= htmlspecialchars($p['category_name']) ?></td>
                            <td><?= htmlspecialchars(substr($p['description'], 0, 50)) . (strlen($p['description']) > 50 ? '...' : '') ?></td>
                            <td class="price"><?= formatRupiah($p['price']) ?></td>
                            <td><?= $p['stock'] < 5 ? '<span class="stock-low">' . $p['stock'] . '</span>' : $p['stock'] ?></td>
                            <td><?= htmlspecialchars(date('d M Y', strtotime($p['created_at']))) ?></td>
                            <td>
                                <a href="edit_product.php?id=<?= $p['id'] ?>" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_product.php?id=<?= $p['id'] ?>" 
                                   onclick="return confirm('Yakin ingin menghapus produk \"<?= addslashes(htmlspecialchars_decode($p['name'])) ?>\"?')"
                                   class="action-btn delete-btn">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($current_page > 1): ?>
                    <a href="?page=<?= $current_page - 1 ?>&<?= http_build_query(array_filter(['search' => $search, 'category' => $category_filter ?: null])) ?>">&laquo; Sebelumnya</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if (abs($i - $current_page) <= 3 || $i == 1 || $i == $total_pages): ?>
                        <a href="?page=<?= $i ?>&<?= http_build_query(array_filter(['search' => $search, 'category' => $category_filter ?: null])) ?>" 
                           class="<?= $i == $current_page ? 'current' : '' ?>">
                            <?= $i ?>
                        </a>
                    <?php elseif ($i == 2 && $current_page > 4): ?>
                        <span>...</span>
                    <?php elseif ($i == $total_pages - 1 && $current_page < $total_pages - 3): ?>
                        <span>...</span>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($current_page < $total_pages): ?>
                    <a href="?page=<?= $current_page + 1 ?>&<?= http_build_query(array_filter(['search' => $search, 'category' => $category_filter ?: null])) ?>">Berikutnya &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>