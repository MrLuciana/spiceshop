<?php
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['quantity'];
  }
}
?>

<nav id="navbar" class="bg-[#A47551] bg-opacity-90 backdrop-blur-md text-white sticky top-0 z-50 transition-all duration-300 ease-in-out py-4">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
      <div id="navbar-logo" class="text-xl font-bold tracking-wide transition-all duration-300">เครื่องแกงชุมชนบ้านใสหลวง</div>

      <!-- Hamburger -->
      <div class="md:hidden">
        <button id="menu-toggle" class="focus:outline-none">
          <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:flex space-x-6 text-sm font-medium items-center">
        <a href="index.php" class="hover:underline">หน้าแรก</a>
        <a href="products.php" class="hover:underline">สินค้า</a>

        <a href="cart.php" class="relative hover:underline flex items-center gap-1">
          🛒 <span>ตะกร้า</span>
          <?php if ($cart_count > 0): ?>
            <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
              <?= $cart_count ?>
            </span>
          <?php endif; ?>
        </a>

        <?php if (isset($_SESSION['user'])): ?>
          <a href="profile.php" class="hover:underline">โปรไฟล์</a>
          <a href="logout.php" class="hover:underline">ออกจากระบบ</a>
        <?php else: ?>
          <a href="login.php" class="bg-gray-200 hover:bg-gray-100 text-black font-semibold px-4 py-2 rounded-md shadow transition">
            เข้าสู่ระบบ
          </a>
          <a href="register.php" class="bg-yellow-600 hover:bg-yellow-300 text-[#5C3A21] font-semibold px-4 py-2 rounded-md shadow transition">
            สมัครสมาชิก
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- JavaScript -->
<script>
  const toggle = document.getElementById('menu-toggle');
  const menu = document.getElementById('mobile-menu');
  const navbar = document.getElementById('navbar');
  const logo = document.getElementById('navbar-logo');

  toggle.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });

  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      navbar.classList.add('py-2', 'bg-[#5C3A21]', 'shadow-md');
      navbar.classList.remove('py-4');
      logo.classList.remove('text-xl');
      logo.classList.add('text-lg');
    } else {
      navbar.classList.remove('py-2', 'bg-[#5C3A21]', 'shadow-md');
      navbar.classList.add('py-4');
      logo.classList.remove('text-lg');
      logo.classList.add('text-xl');
    }
  });
</script>