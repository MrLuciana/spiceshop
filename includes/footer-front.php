<footer class="bg-[#F3E9DD] text-[#5C3A21] text-sm text-center py-5 border-t">
  <div class="max-w-7xl mx-auto px-4">
    &copy; <?= date('Y') ?> ร้านเครื่องแกงชุมชนบ้านไสหลวง. พัฒนาโดยทีมชุมชน
  </div>
</footer>

<?php if (!empty($_SESSION['flash'])):
  $flash = $_SESSION['flash'];
  unset($_SESSION['flash']);
?>
  <script>
    Swal.fire({
      icon: '<?= isset($flash['success']) ? 'success' : 'error' ?>',
      html: '<?= $flash['success'] ?? $flash['error'] ?>',
      confirmButtonText: 'ตกลง',
      timer: 2000,
      timerProgressBar: true
    });
  </script>
<?php endif; ?>

</body>
</html>