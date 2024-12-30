<div class="sidebar">
    <div class="sidebar-header">
        <img src="/assets/img/logo-primary.svg" alt="Logo Delisnack" width="120">
        <div class="header-text">
            <p><i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?></p>
        </div>
    </div>
    <div class="sidebar-menu">
        <a href="/pages/dashboard.php" class="menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'active' : ''; ?>">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="/pages/products/index.php" class="menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'products') !== false) ? 'active' : ''; ?>">
            <i class="fas fa-box"></i>
            <span>Produk</span>
        </a>
        <a href="/pages/categories/index.php" class="menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'categories') !== false) ? 'active' : ''; ?>">
            <i class="fas fa-tags"></i>
            <span>Kategori</span>
        </a>
        <a href="/pages/suppliers/index.php" class="menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'suppliers') !== false) ? 'active' : ''; ?>">
            <i class="fas fa-truck"></i>
            <span>Supplier</span>
        </a>
        <a href="/pages/sales/index.php" class="menu-item <?php echo (strpos($_SERVER['REQUEST_URI'], 'sales') !== false) ? 'active' : ''; ?>">
            <i class="fas fa-shopping-cart"></i>
            <span>Penjualan</span>
        </a>
        <a href="/auth/logout.php" class="menu-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<div class="main-content">