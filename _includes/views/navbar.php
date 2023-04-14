<div class="topnav" id="myTopnav">
  <a href="search.php" <?=$title == "Home"?'class="active"':''?>>Home</a>
  <a href="cart.php" <?=$title == "Cart"?'class="active"':''?>>Cart</a>
  <a href="checkout.php" <?=$title == "Checkout"?'class="active"':''?>>Checkout</a>
  <a href="orders.php" <?=$title == "Orders"||$title == "Commande"?'class="active"':''?>>Orders</a>
  <?php if (!$current_user) { ?>
    <a href="index.php">Login</a>
  <?php } else { ?>
    <?php if ($current_user->isAdmin()) { ?>
      <a href="admin/" <?=$title == "Admin"?'class="active"':''?>>Admin</a>
    <?php } ?>
    <a><?= $current_user->getName() ?></a>
    <a href="logout.php">Logout</a>
  <?php } ?>
  <a href="javascript:void(0);" class="icon">
    <div class="container" id="navbar-menu">
      <div class="bar1"></div>
      <div class="bar2"></div>
      <div class="bar3"></div>
    </div>
  </a>
</div>
<script>
  document.getElementById('navbar-menu').addEventListener('click', function() {
    var x = document.getElementById("myTopnav");
    this.classList.toggle("change");
    x.classList.toggle("responsive");
  });
</script>