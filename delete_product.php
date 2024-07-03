<?php
  require_once('includes/load.php');
  // Verificando que el nivel de usuario tiene permiso para ver esta pagina
  page_require_level(array(1, 2));
?>
<?php
  $product = find_by_id('products',(int)$_GET['id']);
  if(!$product){
    $session->msg("d","ID vacío");
    redirect('product.php');
  }
?>
<?php
  $delete_id = delete_by_id('products',(int)$product['id']);
  if($delete_id){
      $session->msg("s","Producto eliminado");
      redirect('product.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('product.php');
  }
?>
