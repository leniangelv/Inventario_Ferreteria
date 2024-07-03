<?php
  $page_title = 'Agregar Venta';
  require_once('includes/load.php');
  // Verificando que el nivel de usuario tiene permiso para ver esta página
  page_require_level(array (1, 2));
  $products = find_all('products');

  if(isset($_POST['add_sale'])){
    $req_fields = array('product_id', 'qty', 'price', 'date' );
    validate_fields($req_fields);

    if(empty($errors)){
      $product_id   = (int)$db->escape($_POST['product_id']);
      $qty          = (int)$db->escape($_POST['qty']);
      $price        = $db->escape($_POST['price']);
      $date         = $db->escape($_POST['date']);

      // Inserción de la venta en la tabla sales
      $query  = "INSERT INTO sales (";
      $query .= "product_id, qty, price, date";
      $query .= ") VALUES (";
      $query .= "'{$product_id}', '{$qty}', '{$price}', '{$date}'";
      $query .= ")";
      
      if($db->query($query)){
        // Actualización del stock del producto
        $update_quantity = "UPDATE products SET quantity = quantity - {$qty} WHERE id = {$product_id}";
        $db->query($update_quantity);
        
        // Éxito
        $session->msg('s', "Venta agregada exitosamente.");
        redirect('sales.php', false);
      } else {
        // Falló
        $session->msg('d', 'No se pudo agregar la venta.');
        redirect('add_sale.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_sale.php', false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="container">
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Agregar Venta</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-6">
        <form method="post" action="add_sale.php">
          <div class="form-group">
            <label for="product_id">Producto</label>
            <select class="form-control" name="product_id" required>
              <option value="">Selecciona un producto</option>
              <?php foreach ($products as $product ): ?>
                <option value="<?php echo (int)$product['id']; ?>">
                  <?php echo $product['name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="qty">Cantidad</label>
            <input type="number" class="form-control" name="qty" placeholder="Cantidad vendida" required>
          </div>
          <div class="form-group">
            <label for="price">Precio</label>
            <input type="number" step="0.01" class="form-control" name="price" placeholder="Precio de venta" required>
          </div>
          <div class="form-group">
            <label for="date">Fecha</label>
            <input type="datetime-local" class="form-control" name="date" required>
          </div>
          <div class="form-group clearfix">
            <button type="submit" name="add_sale" id="btn-save" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>

<?php include_once('layouts/footer.php'); ?>

<style>
  .container {
    width: 70%;
  }
  #btn-save {
    float: inline-end;
    right: 0%;
  }
  .panel-body {
    width: 100%;
    margin-left: 25%;
  }
</style>