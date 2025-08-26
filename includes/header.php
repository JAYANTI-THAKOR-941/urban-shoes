<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<header class="header">
    <div class="container">
        <div class="logo">
            <a href="/urban-shoes/index.php">Urban Shoes</a>
        </div>
        <nav id="nav-menu" class="nav-menu">
            <ul>
                <li><a href="/urban-shoes/index.php">Home</a></li>
                <li><a href="/urban-shoes/pages/shop.php">Shop</a></li>
                <li><a href="/urban-shoes/pages/about.php">About Us</a></li>
                <li><a href="/urban-shoes/pages/contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="header-icons">
            <div class="search-bars">
                <form action="/urban-shoes/pages/search.php" method="get">
                    <input type="text" name="query" placeholder="Search shoes..." />
                    <button type="submit">Search</button>
                </form>
            </div>
            <div class="cart-icon">
                <a href="/urban-shoes/pages/cart.php">
                    <img src="https://cdn-icons-png.freepik.com/512/6713/6713667.png" alt="Cart" />
                    <span class="cart-count"><?php echo $cartCount; ?></span>
                </a>
            </div>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="profile-menu">
                    <div class="profile-icon" onclick="toggleProfileMenu()">
                        <?php if (!empty($_SESSION['profile_image'])): ?>
                            <img src="/urban-shoes/uploads/<?php echo basename($_SESSION['profile_image']); ?>" alt="Profile Image">
                        <?php else: ?>
                            <span class="user-initial"><?php echo strtoupper($_SESSION['username'][0]); ?></span>
                        <?php endif; ?>
                    </div>
                    <div id="profile-menu-dropdown" class="profile-menu-dropdown">
                        <?php if (!empty($_SESSION['profile_image'])): ?>
                            <img src="/urban-shoes/uploads/<?php echo basename($_SESSION['profile_image']); ?>" class="profile-icon"; alt="Profile Image">
                        <?php else: ?>
                            <span class="user-initial user-icon"><?php echo strtoupper($_SESSION['username'][0]); ?></span>
                        <?php endif; ?>
                        <h2><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></h2>
                        <p><?php echo htmlspecialchars($_SESSION['email'] ?? 'N/A'); ?></p>
                        <a href="/urban-shoes/pages/account.php" class="btn-account">View Profile</a>
                        <a href="/urban-shoes/pages/logout.php" class="btn-logout">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="login-button">
                    <a href="/urban-shoes/pages/login.php" class="btn-login">Login</a>
                </div>
            <?php endif; ?>

            <button class="menu-toggle" onclick="toggleMenu()">☰</button>
        </div>
    </div>
</header>

<script>
function toggleMenu() {
    const menu = document.getElementById('nav-menu');
    menu.classList.toggle('active');
}

function toggleProfileMenu() {
    const profileMenu = document.getElementById('profile-menu-dropdown');
    profileMenu.classList.toggle('active');
}
</script>

<style>
/* CSS for the header, profile icons, and dropdowns */
.user-initial {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 40px;
    height: 40px;
    background-color: #ff4d4d;
    color: #fff;
    font-weight: bold;
    font-size: 18px;
    border-radius: 50%;
    text-transform: uppercase;
    cursor: pointer;
}

.profile-menu-dropdown {
    display: none;
    position: absolute;
    right: 10px;
    top: 55px;
    background-color: #fff;
    color:#000;
    border: 1px solid #ddd;
    border-radius:5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 40px;
    z-index: 1000;
    text-align: center;
}
.profile-menu-dropdown h2,.profile-menu-dropdown p{
    margin: 0;
    padding: 0;
}
.profile-menu-dropdown p{
    margin-bottom:20px;
}
.profile-menu-dropdown.active {
    display: block;
}

.profile-icon img,.profile-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    cursor: pointer;
}
.user-icon{
    margin: auto;
}
.btn-account,
.btn-logout {
    background-color: #007bff;
    color: #fff;
    padding: 8px 12px;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
}

.btn-logout {
    background-color: #ff4d4d;
}
</style>
