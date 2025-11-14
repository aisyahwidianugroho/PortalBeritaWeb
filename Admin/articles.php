<?php
// articles.php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

switch($method) {
    case 'GET':
        // Ambil artikel berdasarkan role
        if ($user_role === 'jurnalis') {
            $stmt = $pdo->prepare("
                SELECT a.*, c.nama_kategori, u.nama_lengkap as nama_penulis 
                FROM articles a 
                LEFT JOIN categories c ON a.id_kategori = c.id 
                LEFT JOIN users u ON a.id_penulis = u.id 
                WHERE a.id_penulis = ?
                ORDER BY a.tanggal_dibuat DESC
            ");
            $stmt->execute([$user_id]);
        } elseif ($user_role === 'editor') {
            $stmt = $pdo->prepare("
                SELECT a.*, c.nama_kategori, u.nama_lengkap as nama_penulis 
                FROM articles a 
                LEFT JOIN categories c ON a.id_kategori = c.id 
                LEFT JOIN users u ON a.id_penulis = u.id 
                WHERE a.status IN ('pending', 'review')
                ORDER BY a.tanggal_dibuat DESC
            ");
            $stmt->execute();
        } elseif ($user_role === 'admin') {
            $stmt = $pdo->prepare("
                SELECT a.*, c.nama_kategori, u.nama_lengkap as nama_penulis 
                FROM articles a 
                LEFT JOIN categories c ON a.id_kategori = c.id 
                LEFT JOIN users u ON a.id_penulis = u.id 
                ORDER BY a.tanggal_dibuat DESC
            ");
            $stmt->execute();
        }
        
        $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'articles' => $articles]);
        break;
        
    case 'POST':
        // Tambah artikel baru
        $input = json_decode(file_get_contents('php://input'), true);
        
        $judul = $input['judul'] ?? '';
        $konten = $input['konten'] ?? '';
        $id_kategori = $input['id_kategori'] ?? null;
        $tags = $input['tags'] ?? '';
        $status = $input['status'] ?? 'draft';
        
        $stmt = $pdo->prepare("
            INSERT INTO articles (judul, konten, id_kategori, id_penulis, tags, status) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        
        if ($stmt->execute([$judul, $konten, $id_kategori, $user_id, $tags, $status])) {
            echo json_encode(['success' => true, 'message' => 'Artikel berhasil disimpan']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan artikel']);
        }
        break;
}
?>