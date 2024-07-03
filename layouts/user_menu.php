<ul>
  <li>
    <a href="home.php">
      <i class="glyphicon glyphicon-home"></i>
      <span>Panel de control</span>
    </a>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-shopping-cart"></i>
      <span>Productos</span>
    </a>
    <ul class="nav submenu">
      <li><a href="product.php">Ver productos</a></li>
      <?php if($user['user_level'] <= 2): // Solo usuarios de nivel 1 y 2 pueden agregar productos ?>
      <li><a href="add_product.php">Agregar producto</a></li>
      <?php endif; ?>
    </ul>
  </li>
  <li>
    <a href="#" class="submenu-toggle">
      <i class="glyphicon glyphicon-th-list"></i>
      <span>Ventas</span>
    </a>
    <ul class="nav submenu">
      <li><a href="sales.php">Ver ventas</a> </li>
    </ul>
  </li>
</ul>
