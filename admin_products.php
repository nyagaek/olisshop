<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

require_once 'db.php';
$db = getDB();

// ── Ensure uploads folder exists ──
if (!is_dir('uploads')) mkdir('uploads', 0755, true);

$success = '';
$error   = '';

// ── HANDLE: Delete product ──
if (isset($_GET['delete'])) {
  $id = (int) $_GET['delete'];
  $stmt = $db->prepare("SELECT image FROM products WHERE id = ?");
  $stmt->execute([$id]);
  $row = $stmt->fetch();
  if ($row && $row['image'] && $row['image'] !== 'default.jpg') {
    $file = 'uploads/' . $row['image'];
    if (file_exists($file)) unlink($file);
  }
  $db->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
  header('Location: admin_products.php?msg=deleted');
  exit;
}

// ── HANDLE: Toggle active ──
if (isset($_GET['toggle'])) {
  $id = (int) $_GET['toggle'];
  $db->prepare("UPDATE products SET active = NOT active WHERE id = ?")->execute([$id]);
  header('Location: admin_products.php?msg=toggled');
  exit;
}

// ── HANDLE: Add product ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name        = trim($_POST['name']        ?? '');
  $description = trim($_POST['description'] ?? '');
  $price       = floatval($_POST['price']   ?? 0);
  $category_id = intval($_POST['category_id'] ?? 0);
  $sizes       = trim($_POST['sizes']       ?? '');
  $imageFile   = 'default.jpg';

  if (!$name || !$description || $price <= 0 || !$category_id) {
    $error = 'Please fill in all required fields (name, description, price, category).';
  } else {
    // ── Handle image upload ──
    if (!empty($_FILES['image']['name'])) {
      $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
      $mime    = mime_content_type($_FILES['image']['tmp_name']);
      $maxSize = 5 * 1024 * 1024; // 5 MB

      if (!in_array($mime, $allowed)) {
        $error = 'Invalid image type. Only JPG, PNG, WEBP, and GIF are allowed.';
      } elseif ($_FILES['image']['size'] > $maxSize) {
        $error = 'Image too large. Maximum size is 5MB.';
      } else {
        $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageFile = strtolower(preg_replace('/[^a-zA-Z0-9_-]/', '-', $name)) . '-' . time() . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageFile);
      }
    }

    if (!$error) {
      $stmt = $db->prepare("
        INSERT INTO products (name, description, price, category_id, sizes, image, active)
        VALUES (?, ?, ?, ?, ?, ?, 1)
      ");
      $stmt->execute([$name, $description, $price, $category_id, $sizes ?: null, $imageFile]);
      header('Location: admin_products.php?msg=added');
      exit;
    }
  }
}

// ── Flash messages ──
if (isset($_GET['msg'])) {
  if ($_GET['msg'] === 'added')   $success = '✅ Product added successfully!';
  if ($_GET['msg'] === 'deleted') $success = '🗑️ Product deleted.';
  if ($_GET['msg'] === 'toggled') $success = '🔄 Product status updated.';
}

// ── Fetch data ──
$categories = $db->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$products   = $db->query("
  SELECT p.*, c.name AS category_name
  FROM products p
  JOIN categories c ON p.category_id = c.id
  ORDER BY p.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OlisShop Admin — Manage Products</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #f0f4f0; color: #1a1a1a; }

    /* ── HEADER ── */
    .header { background: #006400; color: #fff; padding: 0 28px; height: 60px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 8px rgba(0,0,0,0.15); }
    .logo { font-size: 20px; font-weight: 800; letter-spacing: 1px; }
    .logo span { color: #90EE90; }
    .header-links { display: flex; gap: 16px; align-items: center; }
    .header-link { color: #90EE90; font-size: 13px; text-decoration: none; font-weight: 600; }
    .header-link:hover { color: #fff; }
    .logout-link { color: #ffcccc; font-size: 13px; text-decoration: none; font-weight: 600; }
    .logout-link:hover { color: #fff; }

    /* ── LAYOUT ── */
    .container { max-width: 1100px; margin: 30px auto; padding: 0 20px; display: grid; grid-template-columns: 380px 1fr; gap: 24px; align-items: start; }

    /* ── CARD ── */
    .card { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); overflow: hidden; }
    .card-head { background: #006400; color: #fff; padding: 16px 20px; font-size: 15px; font-weight: 700; }
    .card-body { padding: 20px; }

    /* ── FORM ── */
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: #555; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px; }
    .form-group input,
    .form-group select,
    .form-group textarea { width: 100%; padding: 10px 13px; border: 1.5px solid #e0e0e0; border-radius: 9px; font-size: 14px; font-family: inherit; outline: none; transition: border-color 0.2s; background: #fafafa; }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus { border-color: #006400; background: #fff; }
    .form-group textarea { resize: vertical; min-height: 70px; }

    /* ── IMAGE UPLOAD ── */
    .img-upload-area { border: 2px dashed #c8e6c9; border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; transition: all 0.2s; background: #f9fdf9; position: relative; }
    .img-upload-area:hover, .img-upload-area.drag-over { border-color: #006400; background: #f0f7f0; }
    .img-upload-area input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
    .upload-icon { font-size: 32px; margin-bottom: 8px; }
    .upload-text { font-size: 13px; color: #666; }
    .upload-text strong { color: #006400; }
    .upload-hint { font-size: 11px; color: #aaa; margin-top: 4px; }
    .img-preview { display: none; margin-top: 12px; position: relative; }
    .img-preview img { width: 100%; height: 180px; object-fit: cover; border-radius: 10px; border: 2px solid #c8e6c9; }
    .img-preview-remove { position: absolute; top: 6px; right: 6px; background: #ff4444; color: #fff; border: none; border-radius: 50%; width: 26px; height: 26px; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-weight: 700; }

    /* ── SUBMIT BTN ── */
    .submit-btn { width: 100%; padding: 13px; background: #006400; color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; margin-top: 6px; transition: background 0.2s; }
    .submit-btn:hover { background: #005000; }

    /* ── ALERT ── */
    .alert { padding: 11px 14px; border-radius: 9px; font-size: 13px; margin-bottom: 16px; }
    .alert-success { background: #e6f9ee; border: 1px solid #6fcf97; color: #1a5e36; }
    .alert-error   { background: #fdecea; border: 1px solid #f5a3a3; color: #7b1a1a; }

    /* ── PRODUCTS TABLE ── */
    .table-wrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    thead th { background: #f5f5f5; padding: 11px 14px; text-align: left; font-weight: 600; color: #555; font-size: 12px; text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 2px solid #e8e8e8; white-space: nowrap; }
    tbody td { padding: 10px 14px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
    tbody tr:hover { background: #fafafa; }
    tbody tr:last-child td { border-bottom: none; }

    .thumb { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid #e0e0e0; background: #f0f7f0; }
    .thumb-missing { width: 48px; height: 48px; border-radius: 8px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #ccc; border: 1px solid #e0e0e0; }

    .badge { display: inline-block; padding: 3px 9px; border-radius: 50px; font-size: 11px; font-weight: 600; }
    .badge-active   { background: #e6f9ee; color: #1a5e36; }
    .badge-inactive { background: #fdecea; color: #7b1a1a; }

    .btn-sm { padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
    .btn-delete { background: #fdecea; color: #c0392b; }
    .btn-delete:hover { background: #f5b7b1; }
    .btn-toggle { background: #e8f4fd; color: #1a5276; }
    .btn-toggle:hover { background: #aed6f1; }

    .empty-row td { text-align: center; padding: 40px; color: #aaa; font-size: 14px; }

    @media (max-width: 768px) {
      .container { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<!-- ── HEADER ── -->
<div class="header">
  <div class="logo">Olis<span>Shop</span> <span style="font-size:13px;font-weight:400;opacity:0.8;">Admin</span></div>
  <div class="header-links">
    <a href="index.php" class="header-link">← Back to Shop</a>
    <a href="admin_logout.php" class="logout-link">🔓 Logout</a>
  </div>
</div>

<div class="container">

  <!-- ── LEFT: ADD PRODUCT FORM ── -->
  <div class="card">
    <div class="card-head">➕ Add New Product</div>
    <div class="card-body">

      <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <?php if ($success): ?>
        <div class="alert alert-success" id="flashMsg"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" id="addForm">

        <!-- Image Upload -->
        <div class="form-group">
          <label>Product Image</label>
          <div class="img-upload-area" id="uploadArea">
            <input type="file" name="image" id="imageInput" accept="image/*">
            <div class="upload-icon">📷</div>
            <div class="upload-text"><strong>Click to upload</strong> or drag & drop</div>
            <div class="upload-hint">JPG, PNG, WEBP · Max 5MB · Square images work best</div>
          </div>
          <div class="img-preview" id="imgPreview">
            <img id="previewImg" src="" alt="Preview">
            <button type="button" class="img-preview-remove" onclick="removeImage()">✕</button>
          </div>
        </div>

        <!-- Name -->
        <div class="form-group">
          <label>Product Name *</label>
          <input type="text" name="name" placeholder="e.g. Nike Air Max White" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>

        <!-- Category -->
        <div class="form-group">
          <label>Category *</label>
          <select name="category_id" required>
            <option value="">— Select category —</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= (($_POST['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                <?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Price -->
        <div class="form-group">
          <label>Price (KES) *</label>
          <input type="number" name="price" placeholder="e.g. 2500" min="1" step="1" required value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">
        </div>

        <!-- Description -->
        <div class="form-group">
          <label>Description *</label>
          <textarea name="description" placeholder="Describe the product..." required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <!-- Sizes -->
        <div class="form-group">
          <label>Sizes <span style="font-weight:400;color:#aaa">(optional — comma separated)</span></label>
          <input type="text" name="sizes" placeholder="e.g. S, M, L, XL  or  37, 38, 39, 40" value="<?= htmlspecialchars($_POST['sizes'] ?? '') ?>">
        </div>

        <button type="submit" class="submit-btn">➕ Add Product</button>
      </form>
    </div>
  </div>

  <!-- ── RIGHT: PRODUCTS TABLE ── -->
  <div class="card">
    <div class="card-head">📦 All Products (<?= count($products) ?>)</div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Sizes</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($products)): ?>
            <tr class="empty-row"><td colspan="7">No products yet. Add your first one!</td></tr>
          <?php else: ?>
            <?php foreach ($products as $p): ?>
              <tr>
                <td>
                  <?php $imgPath = 'uploads/' . $p['image']; ?>
                  <?php if ($p['image'] && file_exists($imgPath)): ?>
                    <img class="thumb" src="<?= $imgPath ?>?v=<?= filemtime($imgPath) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                  <?php else: ?>
                    <div class="thumb-missing">🖼</div>
                  <?php endif; ?>
                </td>
                <td style="font-weight:600;max-width:160px"><?= htmlspecialchars($p['name']) ?></td>
                <td style="color:#666"><?= htmlspecialchars($p['category_name']) ?></td>
                <td style="font-weight:700;color:#006400">KES <?= number_format($p['price'], 0) ?></td>
                <td style="color:#888;font-size:12px"><?= $p['sizes'] ? htmlspecialchars($p['sizes']) : '—' ?></td>
                <td>
                  <span class="badge <?= $p['active'] ? 'badge-active' : 'badge-inactive' ?>">
                    <?= $p['active'] ? 'Active' : 'Hidden' ?>
                  </span>
                </td>
                <td style="white-space:nowrap">
                  <a href="?toggle=<?= $p['id'] ?>" class="btn-sm btn-toggle" onclick="return confirm('Toggle visibility for this product?')">
                    <?= $p['active'] ? '🙈 Hide' : '👁 Show' ?>
                  </a>
                  &nbsp;
                  <a href="?delete=<?= $p['id'] ?>" class="btn-sm btn-delete" onclick="return confirm('Delete this product? This cannot be undone.')">
                    🗑 Delete
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<script>
// ── IMAGE PREVIEW ──────────────────────────────────────────────
const imageInput = document.getElementById('imageInput');
const uploadArea = document.getElementById('uploadArea');
const imgPreview = document.getElementById('imgPreview');
const previewImg = document.getElementById('previewImg');

imageInput.addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    previewImg.src           = e.target.result;
    imgPreview.style.display = 'block';
    uploadArea.style.display = 'none';
  };
  reader.readAsDataURL(file);
});

function removeImage() {
  imageInput.value         = '';
  previewImg.src           = '';
  imgPreview.style.display = 'none';
  uploadArea.style.display = 'block';
}

// ── DRAG & DROP ────────────────────────────────────────────────
uploadArea.addEventListener('dragover', e => {
  e.preventDefault();
  uploadArea.classList.add('drag-over');
});
uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
uploadArea.addEventListener('drop', e => {
  e.preventDefault();
  uploadArea.classList.remove('drag-over');
  const file = e.dataTransfer.files[0];
  if (file && file.type.startsWith('image/')) {
    const dt = new DataTransfer();
    dt.items.add(file);
    imageInput.files = dt.files;
    imageInput.dispatchEvent(new Event('change'));
  }
});

// ── AUTO-HIDE FLASH MESSAGE ────────────────────────────────────
const flash = document.getElementById('flashMsg');
if (flash) setTimeout(() => flash.style.display = 'none', 4000);
</script>
</body>
</html>
