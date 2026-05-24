<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OlisShop — Quality at Your Doorstep</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    :root {
      --green:       #1a6b3c;
      --green-dark:  #124d2b;
      --green-light: #e8f5ee;
      --accent:      #f0c040;
      --bg:          #f7f8f5;
      --card:        #ffffff;
      --text:        #1a1a1a;
      --muted:       #7a8070;
      --border:      #e4e8e0;
      --danger:      #e04040;
      --radius:      16px;
      --shadow:      0 2px 16px rgba(26,107,60,0.08);
    }
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--bg);
      color: var(--text);
      min-height: 100vh;
    }

    /* ── HEADER ── */
    .header {
      background: var(--green);
      color: #fff;
      padding: 0 28px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
      box-shadow: 0 2px 20px rgba(0,0,0,0.18);
    }
    .logo {
      font-family: 'Playfair Display', serif;
      font-size: 24px;
      font-weight: 900;
      letter-spacing: 0.5px;
      color: #fff;
    }
    .logo span { color: var(--accent); }
    .header-right { display: flex; align-items: center; gap: 12px; }
    .search-bar {
      background: rgba(255,255,255,0.12);
      border: 1.5px solid rgba(255,255,255,0.2);
      border-radius: 50px;
      padding: 7px 16px;
      color: #fff;
      font-size: 13px;
      font-family: 'DM Sans', sans-serif;
      outline: none;
      width: 180px;
      transition: all 0.2s;
    }
    .search-bar::placeholder { color: rgba(255,255,255,0.55); }
    .search-bar:focus { background: rgba(255,255,255,0.2); width: 220px; }
    .cart-btn {
      background: var(--accent);
      color: #1a1a1a;
      border: none;
      padding: 9px 18px;
      border-radius: 50px;
      font-weight: 700;
      font-size: 14px;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 7px;
      font-family: 'DM Sans', sans-serif;
      transition: transform 0.15s, box-shadow 0.15s;
    }
    .cart-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(240,192,64,0.4); }
    .cart-count {
      background: var(--danger);
      color: #fff;
      border-radius: 50%;
      width: 20px; height: 20px;
      font-size: 11px;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700;
    }

    /* ── HERO ── */
    .hero {
      background: linear-gradient(135deg, var(--green-dark) 0%, var(--green) 60%, #2d8a55 100%);
      color: #fff;
      padding: 52px 28px 44px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .hero::before {
      content: '';
      position: absolute;
      inset: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      pointer-events: none;
    }
    .hero-tag {
      display: inline-block;
      background: var(--accent);
      color: #1a1a1a;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      padding: 4px 14px;
      border-radius: 50px;
      margin-bottom: 14px;
    }
    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: clamp(26px, 5vw, 40px);
      font-weight: 900;
      margin-bottom: 10px;
      line-height: 1.15;
    }
    .hero p { font-size: 15px; color: rgba(255,255,255,0.75); font-weight: 300; }

    /* ── CATEGORIES ── */
    .cat-wrap {
      background: var(--card);
      border-bottom: 1px solid var(--border);
      padding: 0 20px;
    }
    .categories {
      display: flex;
      gap: 4px;
      overflow-x: auto;
      padding: 14px 0;
    }
    .categories::-webkit-scrollbar { display: none; }
    .cat-btn {
      flex-shrink: 0;
      padding: 8px 18px;
      border-radius: 50px;
      border: 1.5px solid var(--border);
      background: #fff;
      font-size: 13px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.2s;
      white-space: nowrap;
      font-family: 'DM Sans', sans-serif;
      color: var(--text);
    }
    .cat-btn.active, .cat-btn:hover {
      background: var(--green);
      color: #fff;
      border-color: var(--green);
    }

    /* ── MAIN ── */
    .main { padding: 28px 24px; max-width: 1200px; margin: 0 auto; }
    .section-top {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      font-weight: 700;
      color: var(--text);
    }
    .section-count {
      font-size: 13px;
      color: var(--muted);
      background: var(--green-light);
      padding: 4px 12px;
      border-radius: 50px;
      font-weight: 500;
    }

    /* ── GRID ── */
    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }
    .product-card {
      background: var(--card);
      border-radius: var(--radius);
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: transform 0.22s, box-shadow 0.22s;
      cursor: pointer;
      border: 1px solid var(--border);
      display: flex;
      flex-direction: column;
    }
    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 32px rgba(26,107,60,0.14);
    }
    .product-img-wrap {
      position: relative;
      width: 100%;
      height: 180px;
      background: var(--green-light);
      overflow: hidden;
    }
    .product-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      background-color:white;
      transition: transform 0.3s;
      display: block;
      overflow: hidden;
    }
    .product-card:hover .product-img-wrap img { transform: scale(1.05); }
    .product-img-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 52px;
      background: var(--green-light);
    }
    .product-badge {
      position: absolute;
      top: 10px;
      left: 10px;
      background: var(--accent);
      color: #1a1a1a;
      font-size: 10px;
      font-weight: 700;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      padding: 3px 9px;
      border-radius: 50px;
    }
    .product-info { padding: 14px; flex: 1; display: flex; flex-direction: column; }
    .product-cat {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--green);
      margin-bottom: 5px;
    }
    .product-name {
      font-size: 14px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 4px;
      line-height: 1.35;
    }
    .product-desc {
      font-size: 12px;
      color: var(--muted);
      margin-bottom: 10px;
      line-height: 1.5;
      flex: 1;
    }
    .product-bottom { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .product-price {
      font-size: 16px;
      font-weight: 700;
      color: var(--green);
      font-family: 'Playfair Display', serif;
    }
    .add-btn {
      padding: 7px 14px;
      background: var(--green);
      color: #fff;
      border: none;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      transition: background 0.2s;
      white-space: nowrap;
    }
    .add-btn:hover { background: var(--green-dark); }

    /* ── NO RESULTS ── */
    .no-results {
      text-align: center;
      padding: 60px 20px;
      color: var(--muted);
      display: none;
    }
    .no-results-icon { font-size: 48px; margin-bottom: 12px; }
    .no-results p { font-size: 15px; }

    /* ── PRODUCT MODAL ── */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.55);
      z-index: 200;
      align-items: flex-end;
      justify-content: center;
      backdrop-filter: blur(3px);
    }
    .modal-overlay.open { display: flex; }
    .modal {
      background: #fff;
      border-radius: 24px 24px 0 0;
      width: 100%;
      max-width: 520px;
      max-height: 92vh;
      overflow-y: auto;
      animation: slideUp 0.28s ease;
    }
    @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    .modal-img-wrap {
   .modal-img-wrap {
  width: 100%;
  height: 300px;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: white;
  overflow: hidden;
}

.modal-img-wrap img {
  max-width: 100%;
  max-height: 100%;
  width: auto;
  height: auto;
  object-fit: contain;
}
    }
    .modal-img-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 72px;
    }
    .modal-close {
      position: absolute;
      top: 14px;
      right: 14px;
      background: rgba(255,255,255,0.9);
      border: none;
      width: 34px; height: 34px;
      border-radius: 50%;
      font-size: 16px;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700;
      box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }
    .modal-body { padding: 20px 22px 28px; }
    .modal-cat { font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: var(--green); margin-bottom: 6px; }
    .modal-name { font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
    .modal-price { font-size: 22px; font-weight: 700; color: var(--green); margin-bottom: 10px; }
    .modal-desc { font-size: 13px; color: var(--muted); line-height: 1.6; margin-bottom: 16px; }
    .modal-label { font-size: 13px; font-weight: 600; color: #444; margin-bottom: 8px; }
    .size-options { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 16px; }
    .size-opt {
      padding: 7px 16px;
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-size: 13px;
      cursor: pointer;
      background: #fff;
      transition: all 0.15s;
      font-family: 'DM Sans', sans-serif;
    }
    .size-opt.selected { border-color: var(--green); background: var(--green); color: #fff; }
    .qty-row { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
    .qty-ctrl { display: flex; align-items: center; gap: 12px; }
    .qty-btn {
      width: 34px; height: 34px;
      border: 1.5px solid var(--border);
      border-radius: 8px;
      background: #fff;
      font-size: 18px;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700;
      transition: all 0.15s;
    }
    .qty-btn:hover { border-color: var(--green); color: var(--green); }
    .qty-val { font-size: 16px; font-weight: 700; min-width: 28px; text-align: center; }
    .add-cart-btn {
      width: 100%;
      padding: 15px;
      background: var(--green);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      transition: background 0.2s, transform 0.15s;
    }
    .add-cart-btn:hover { background: var(--green-dark); transform: translateY(-1px); }

    /* ── CART SIDEBAR ── */
    .cart-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 300; }
    .cart-overlay.open { display: block; }
    .cart-sidebar {
      position: fixed;
      top: 0; right: -420px;
      width: 380px; height: 100vh;
      background: #fff;
      z-index: 301;
      transition: right 0.3s cubic-bezier(0.4,0,0.2,1);
      display: flex; flex-direction: column;
      box-shadow: -6px 0 30px rgba(0,0,0,0.12);
    }
    .cart-sidebar.open { right: 0; }
    .cart-head {
      padding: 20px 22px;
      border-bottom: 1px solid var(--border);
      display: flex; justify-content: space-between; align-items: center;
    }
    .cart-head h3 { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; }
    .cart-items { flex: 1; overflow-y: auto; padding: 10px; }
    .cart-empty { text-align: center; color: var(--muted); padding: 56px 20px; }
    .cart-empty-icon { font-size: 40px; margin-bottom: 10px; }
    .cart-empty p { font-size: 14px; }
    .cart-item {
      display: flex;
      gap: 12px;
      padding: 12px 0;
      border-bottom: 1px solid var(--border);
      align-items: center;
    }
    .cart-item-img {
      width: 52px; height: 52px;
      border-radius: 10px;
      object-fit: cover;
      background: var(--green-light);
      flex-shrink: 0;
      display: flex; align-items: center; justify-content: center;
      font-size: 22px;
      overflow: hidden;
    }
    .cart-item-img img { width: 100%; max-width:100; overflow: hidden; height: 100%; object-fit: contain; padding:10px; border-radius: 10px; }
    .cart-item-info { flex: 1; min-width: 0; }
    .cart-item-name { font-size: 13px; font-weight: 600; margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .cart-item-meta { font-size: 11px; color: var(--muted); margin-bottom: 3px; }
    .cart-item-price { font-size: 13px; font-weight: 700; color: var(--green); }
    .cart-item-remove { background: none; border: none; color: #ccc; font-size: 18px; cursor: pointer; padding: 4px; flex-shrink: 0; }
    .cart-item-remove:hover { color: var(--danger); }
    .cart-footer { padding: 16px 22px; border-top: 1px solid var(--border); }
    .cart-total { display: flex; justify-content: space-between; font-size: 17px; font-weight: 700; margin-bottom: 14px; }
    .checkout-btn {
      width: 100%;
      padding: 14px;
      background: var(--green);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      transition: background 0.2s;
    }
    .checkout-btn:disabled { background: #ccc; cursor: not-allowed; }
    .checkout-btn:not(:disabled):hover { background: var(--green-dark); }

    /* ── CHECKOUT MODAL ── */
    .checkout-modal {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.55);
      z-index: 400;
      align-items: center;
      justify-content: center;
      padding: 20px;
      backdrop-filter: blur(3px);
    }
    .admin {
    background-color:  #e9e4cb;
    color: green;
    padding: 10px 24px;
    border: none;
    border-radius: 6px;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.3s, transform 0.2s;
    text-decoration: none;
    display: inline-block;
}

.admin:hover {
    background-color: #e9e4cb;
    transform: scale(1.05);
}
    .checkout-modal.open { display: flex; }
    .checkout-box {
      background: #fff;
      border-radius: 20px;
      padding: 30px;
      width: 100%;
      max-width: 420px;
      max-height: 90vh;
      overflow-y: auto;
    }
    .checkout-box h3 { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; margin-bottom: 4px; }
    .checkout-box > p { font-size: 13px; color: var(--muted); margin-bottom: 20px; }
    .order-summary { background: var(--green-light); border-radius: 12px; padding: 14px; margin-bottom: 18px; }
    .order-row { display: flex; justify-content: space-between; font-size: 13px; color: #555; margin-bottom: 6px; }
    .order-row:last-child { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 0; border-top: 1px solid var(--border); padding-top: 8px; margin-top: 4px; }
    .form-group { margin-bottom: 14px; }
    .form-group label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 6px; }
    .form-group input {
      width: 100%;
      padding: 11px 14px;
      border: 1.5px solid var(--border);
      border-radius: 10px;
      font-size: 14px;
      outline: none;
      font-family: 'DM Sans', sans-serif;
      transition: border-color 0.2s;
    }
    .form-group input:focus { border-color: var(--green); }
    .pay-btn {
      width: 100%;
      padding: 14px;
      background: var(--green);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      font-family: 'DM Sans', sans-serif;
      margin-top: 4px;
      transition: background 0.2s;
    }
    .pay-btn:disabled { background: #aaa; cursor: not-allowed; }
    .pay-btn:not(:disabled):hover { background: var(--green-dark); }
    .cancel-link { text-align: center; margin-top: 12px; font-size: 13px; color: var(--muted); cursor: pointer; }
    .cancel-link:hover { color: var(--text); }
    #checkoutStatus { display: none; margin-top: 14px; padding: 13px; border-radius: 10px; font-size: 13px; line-height: 1.5; }
    .status-loading { background: #e8f4fd; border: 1px solid #90cdf4; color: #1a5276; }
    .status-pin     { background: #fff8e1; border: 1px solid #ffd54f; color: #5d4037; }
    .status-success { background: #e6f9ee; border: 1px solid #6fcf97; color: #1a5e36; }
    .status-error   { background: #fdecea; border: 1px solid #f5a3a3; color: #7b1a1a; }
    .spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid currentColor; border-top-color: transparent; border-radius: 50%; animation: spin 0.7s linear infinite; vertical-align: middle; margin-right: 4px; }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── RESPONSIVE ── */
    @media (max-width: 600px) {
      .grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
      .cart-sidebar { width: 100%; right: -100%; }
      .search-bar { display: none; }
      .hero { padding: 36px 20px 30px; }
    }
  </style>
</head>
<body>

<?php require_once 'db.php'; ?>

<!-- ── HEADER ── -->
<div class="header">
  <div class="logo">Olis<span>Shop</span></div>
  <div class="header-right">
    <input class="search-bar" type="text" placeholder="Search products..." id="searchInput" oninput="searchProducts(this.value)">
    <button class="cart-btn" onclick="openCart()">
      🛒 Cart <span class="cart-count" id="cartCount">0</span>
    </button>
       <a href="admin_login.php">
  <button class="admin">Admin Login</button>
</a>
  </div>
</div>

<!-- ── HERO ── -->
<div class="hero">
  <div class="hero-tag">✨ New Arrivals Available</div>
  <h1>Quality at Your Doorstep</h1>
  <p>Shoes · Clothes · Bags & More — Pay via M-Pesa</p>
</div>

<!-- ── PHP DATA ── -->
<?php
$db = getDB();
$categories = $db->query("SELECT * FROM categories ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
$products   = $db->query("
    SELECT p.*, c.name AS category_name, c.slug AS category_slug, c.icon AS category_icon
    FROM products p
    JOIN categories c ON p.category_id = c.id
    WHERE p.active = 1
    ORDER BY p.category_id, p.id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- ── CATEGORIES ── -->
<div class="cat-wrap">
  <div class="categories">
    <button class="cat-btn active" onclick="filterCat('all', this)">🛍️ All</button>
    <?php foreach ($categories as $cat): ?>
      <button class="cat-btn" onclick="filterCat('<?= htmlspecialchars($cat['slug']) ?>', this)">
        <?= $cat['icon'] ?> <?= htmlspecialchars($cat['name']) ?>
      </button>
    <?php endforeach; ?>
  </div>
</div>

<!-- ── PRODUCTS GRID ── -->
<div class="main">
  <div class="section-top">
    <div class="section-title" id="gridTitle">All Items</div>
    <div class="section-count" id="gridCount"><?= count($products) ?> items</div>
  </div>

  <div class="grid" id="productGrid">
    <?php foreach ($products as $p): ?>
      <div class="product-card"
           data-cat="<?= htmlspecialchars($p['category_slug']) ?>"
           data-name="<?= strtolower(htmlspecialchars($p['name'])) ?>"
           onclick="openProduct(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)">

        <div class="product-img-wrap">
          <?php if (!empty($p['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($p['image']) ?>"
                 alt="<?= htmlspecialchars($p['name']) ?>"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div class="product-img-placeholder" style="display:none;"><?= !empty($p['icon']) ? $p['icon'] : '🛍️' ?></div>
          <?php else: ?>
            <div class="product-img-placeholder"><?= !empty($p['icon']) ? $p['icon'] : '🛍️' ?></div>
          <?php endif; ?>
          <?php if (!empty($p['is_new'])): ?>
            <div class="product-badge">New</div>
          <?php endif; ?>
        </div>

        <div class="product-info">
          <div class="product-cat"><?= htmlspecialchars($p['category_name']) ?></div>
          <div class="product-name"><?= htmlspecialchars($p['name']) ?></div>
          <div class="product-desc"><?= htmlspecialchars(substr($p['description'], 0, 60)) ?>...</div>
          <div class="product-bottom">
            <div class="product-price">KES <?= number_format($p['price'], 0) ?></div>
            <button class="add-btn" onclick="event.stopPropagation(); openProduct(<?= htmlspecialchars(json_encode($p), ENT_QUOTES) ?>)">+ Add</button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="no-results" id="noResults">
    <div class="no-results-icon">🔍</div>
    <p>No products found. Try a different search or category.</p>
  </div>
</div>

<!-- ── PRODUCT MODAL ── -->
<div class="modal-overlay" id="productModal" onclick="if(event.target===this)closeModal()">
  <div class="modal">
    <div class="modal-img-wrap">
      <div id="modalImgContainer"></div>
      <button class="modal-close" onclick="closeModal()">✕</button>
    </div>
    <div class="modal-body">
      <div class="modal-cat" id="modalCat"></div>
      <div class="modal-name" id="modalName"></div>
      <div class="modal-price" id="modalPrice"></div>
      <div class="modal-desc" id="modalDesc"></div>
      <div id="sizeSection">
        <div class="modal-label">Select Size:</div>
        <div class="size-options" id="sizeOptions"></div>
      </div>
      <div class="qty-row">
        <span class="modal-label">Quantity:</span>
        <div class="qty-ctrl">
          <button class="qty-btn" onclick="changeQty(-1)">−</button>
          <span class="qty-val" id="qtyVal">1</span>
          <button class="qty-btn" onclick="changeQty(1)">+</button>
        </div>
      </div>
      <button class="add-cart-btn" onclick="addToCart()">🛒 Add to Cart</button>
    </div>
  </div>
</div>

<!-- ── CART SIDEBAR ── -->
<div class="cart-overlay" id="cartOverlay" onclick="closeCart()"></div>
<div class="cart-sidebar" id="cartSidebar">
  <div class="cart-head">
    <h3>Your Cart</h3>
    <button class="modal-close" onclick="closeCart()">✕</button>
  </div>
  <div class="cart-items" id="cartItems">
    <div class="cart-empty">
      <div class="cart-empty-icon">🛒</div>
      <p>Your cart is empty.<br>Add items to get started!</p>
    </div>
  </div>
  <div class="cart-footer">
    <div class="cart-total">
      <span>Total</span>
      <span id="cartTotal">KES 0</span>
    </div>
    <button class="checkout-btn" id="checkoutBtn" onclick="openCheckout()" disabled>
      Pay via M-Pesa
    </button>
  </div>
</div>

<!-- ── CHECKOUT MODAL ── -->
<div class="checkout-modal" id="checkoutModal">
  <div class="checkout-box">
    <h3>📱 M-Pesa Checkout</h3>
    <p>Enter your details to complete your order</p>
    <div class="order-summary" id="orderSummary"></div>
    <div class="form-group">
      <label>Your Name</label>
      <input type="text" id="custName" placeholder="e.g. John Kamau">
    </div>
    <div class="form-group">
      <label>M-Pesa Phone Number</label>
      <input type="tel" id="custPhone" placeholder="e.g. 0712345678">
    </div>
    <button class="pay-btn" id="payBtn" onclick="processPayment()">Pay via M-Pesa</button>
    <div class="cancel-link" onclick="closeCheckout()">Cancel</div>
    <div id="checkoutStatus"></div>
  </div>
</div>

<script>
// ── STATE ──
let cart           = [];
let currentProduct = null;
let selectedSize   = null;
let currentQty     = 1;

// ── SEARCH ──
function searchProducts(query) {
  query = query.toLowerCase().trim();
  const cards = document.querySelectorAll('.product-card');
  let visible = 0;
  cards.forEach(card => {
    const name = card.dataset.name || '';
    const show = !query || name.includes(query);
    card.style.display = show ? '' : 'none';
    if (show) visible++;
  });
  document.getElementById('gridCount').textContent = visible + ' items';
  document.getElementById('noResults').style.display = visible === 0 ? 'block' : 'none';
}

// ── CATEGORY FILTER ──
function filterCat(slug, btn) {
  document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('searchInput') && (document.getElementById('searchInput').value = '');
  const cards = document.querySelectorAll('.product-card');
  let count = 0;
  cards.forEach(card => {
    const show = slug === 'all' || card.dataset.cat === slug;
    card.style.display = show ? '' : 'none';
    if (show) count++;
  });
  const label = slug === 'all' ? 'All Items' : btn.textContent.trim();
  document.getElementById('gridTitle').textContent = label;
  document.getElementById('gridCount').textContent = count + ' items';
  document.getElementById('noResults').style.display = count === 0 ? 'block' : 'none';
}

// ── PRODUCT MODAL ──
function openProduct(product) {
  currentProduct = product;
  selectedSize   = null;
  currentQty     = 1;

  // Image
  const imgContainer = document.getElementById('modalImgContainer');
  if (product.image) {
    imgContainer.innerHTML = `
      <img src="uploads/${product.image}" alt="${product.name}"
           style="width:100%;height:240px;object-fit:cover;"
           onerror="this.outerHTML='<div style=\\'width:100%;height:240px;display:flex;align-items:center;justify-content:center;font-size:72px;background:var(--green-light);\\'>🛍️</div>'">
    `;
  } else {
    imgContainer.innerHTML = `<div style="width:100%;height:240px;display:flex;align-items:center;justify-content:center;font-size:72px;background:var(--green-light);">${product.icon || '🛍️'}</div>`;
  }

  document.getElementById('modalCat').textContent   = product.category_name || '';
  document.getElementById('modalName').textContent  = product.name;
  document.getElementById('modalPrice').textContent = 'KES ' + Number(product.price).toLocaleString();
  document.getElementById('modalDesc').textContent  = product.description;
  document.getElementById('qtyVal').textContent     = 1;

  const sizeSection = document.getElementById('sizeSection');
  const sizeOptions = document.getElementById('sizeOptions');
  sizeOptions.innerHTML = '';

  if (product.sizes) {
    sizeSection.style.display = 'block';
    product.sizes.split(',').forEach(size => {
      const btn = document.createElement('button');
      btn.className   = 'size-opt';
      btn.textContent = size.trim();
      btn.onclick     = () => selectSize(size.trim(), btn);
      sizeOptions.appendChild(btn);
    });
  } else {
    sizeSection.style.display = 'none';
    selectedSize = 'One Size';
  }

  document.getElementById('productModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeModal() {
  document.getElementById('productModal').classList.remove('open');
  document.body.style.overflow = '';
}

function selectSize(size, btn) {
  selectedSize = size;
  document.querySelectorAll('.size-opt').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
}

function changeQty(delta) {
  currentQty = Math.max(1, currentQty + delta);
  document.getElementById('qtyVal').textContent = currentQty;
}

// ── CART ──
function addToCart() {
  if (currentProduct.sizes && !selectedSize) {
    alert('Please select a size first.');
    return;
  }
  const item = {
    id:    currentProduct.id,
    name:  currentProduct.name,
    image: currentProduct.image || null,
    icon:  currentProduct.icon  || '🛍️',
    price: parseFloat(currentProduct.price),
    size:  selectedSize,
    qty:   currentQty,
  };
  const existing = cart.find(c => c.id === item.id && c.size === item.size);
  if (existing) { existing.qty += item.qty; }
  else          { cart.push(item); }
  closeModal();
  renderCart();
  openCart();
}

function removeFromCart(index) {
  cart.splice(index, 1);
  renderCart();
}

function renderCart() {
  const container = document.getElementById('cartItems');
  const total     = cart.reduce((sum, item) => sum + item.price * item.qty, 0);

  document.getElementById('cartCount').textContent = cart.reduce((s, i) => s + i.qty, 0);
  document.getElementById('cartTotal').textContent = 'KES ' + total.toLocaleString();
  document.getElementById('checkoutBtn').disabled  = cart.length === 0;

  if (cart.length === 0) {
    container.innerHTML = '<div class="cart-empty"><div class="cart-empty-icon">🛒</div><p>Your cart is empty.<br>Add items to get started!</p></div>';
    return;
  }

  container.innerHTML = cart.map((item, i) => {
    const imgHtml = item.image
      ? `<img src="uploads/${item.image}" onerror="this.outerHTML='<span>${item.icon}</span>'">`
      : `<span>${item.icon}</span>`;
    return `
      <div class="cart-item">
        <div class="cart-item-img">${imgHtml}</div>
        <div class="cart-item-info">
          <div class="cart-item-name">${item.name}</div>
          <div class="cart-item-meta">${item.size && item.size !== 'One Size' ? 'Size: ' + item.size + ' · ' : ''}Qty: ${item.qty}</div>
          <div class="cart-item-price">KES ${(item.price * item.qty).toLocaleString()}</div>
        </div>
        <button class="cart-item-remove" onclick="removeFromCart(${i})">🗑</button>
      </div>
    `;
  }).join('');
}

function openCart()  {
  document.getElementById('cartSidebar').classList.add('open');
  document.getElementById('cartOverlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeCart() {
  document.getElementById('cartSidebar').classList.remove('open');
  document.getElementById('cartOverlay').classList.remove('open');
  document.body.style.overflow = '';
}

// ── CHECKOUT ──
function openCheckout() {
  closeCart();
  const total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
  let html = cart.map(item => `
    <div class="order-row">
      <span>${item.name}${item.size && item.size !== 'One Size' ? ' ('+item.size+')' : ''} × ${item.qty}</span>
      <span>KES ${(item.price * item.qty).toLocaleString()}</span>
    </div>
  `).join('');
  html += `<div class="order-row"><span>Total</span><span>KES ${total.toLocaleString()}</span></div>`;
  document.getElementById('orderSummary').innerHTML = html;
  document.getElementById('checkoutStatus').style.display = 'none';
  document.getElementById('payBtn').disabled    = false;
  document.getElementById('payBtn').textContent = 'Pay via M-Pesa';
  document.getElementById('checkoutModal').classList.add('open');
  document.body.style.overflow = 'hidden';
}

function closeCheckout() {
  document.getElementById('checkoutModal').classList.remove('open');
  document.body.style.overflow = '';
}

function setStatus(type, html) {
  const el = document.getElementById('checkoutStatus');
  el.style.display = 'block';
  el.className     = type;
  el.innerHTML     = html;
}

async function processPayment() {
  const name  = document.getElementById('custName').value.trim();
  const phone = document.getElementById('custPhone').value.trim();
  const total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);

  if (!name)  { alert('Please enter your name.'); return; }
  if (!phone) { alert('Please enter your M-Pesa number.'); return; }

  const btn = document.getElementById('payBtn');
  btn.disabled    = true;
  btn.textContent = 'Sending...';
  setStatus('status-loading', '<span class="spinner"></span> Contacting M-Pesa...');

  try {
    const res  = await fetch('place_order.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ name, phone, total, items: cart })
    });
    const data = await res.json();

    if (data.success) {
      setStatus('status-pin',
        `📲 <strong>Check your phone!</strong><br>Enter your M-Pesa PIN to pay <strong>KES ${total.toLocaleString()}</strong>.`
      );
      btn.textContent = 'Waiting for PIN...';
      pollOrder(data.checkoutRequestId, total, btn);
    } else {
      setStatus('status-error', '❌ ' + data.message);
      btn.disabled    = false;
      btn.textContent = 'Pay via M-Pesa';
    }
  } catch(e) {
    setStatus('status-error', '❌ Network error. Make sure your server is running.');
    btn.disabled    = false;
    btn.textContent = 'Pay via M-Pesa';
  }
}

function pollOrder(checkoutId, total, btn) {
  let attempts = 0;
  const interval = setInterval(async () => {
    attempts++;
    if (attempts > 10) {
      clearInterval(interval);
      setStatus('status-error', '⏱️ Timed out. Check your phone and try again.');
      btn.disabled    = false;
      btn.textContent = 'Pay via M-Pesa';
      return;
    }
    try {
      const res  = await fetch(`check_status.php?id=${encodeURIComponent(checkoutId)}`);
      const data = await res.json();
      if (data.status === 'success') {
        clearInterval(interval);
        cart = [];
        renderCart();
        setStatus('status-success',
          `✅ <strong>Order Paid!</strong><br>Receipt: <strong>${data.receipt}</strong><br>Thank you for shopping at OlisShop!`
        );
        btn.textContent = 'Payment Complete ✓';
      } else if (data.status === 'failed') {
        clearInterval(interval);
        setStatus('status-error', '❌ Payment failed: ' + data.message);
        btn.disabled    = false;
        btn.textContent = 'Pay via M-Pesa';
      }
    } catch(e) { /* keep polling */ }
  }, 3000);
}
</script>
</body>
</html>
