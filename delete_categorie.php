<?php
  require_once('includes/load.php');
  // Verificando que el nivel de usuario tiene permiso para ver esta pagina
  page_require_level(array(1, 2));
?>
<?php
  $categorie = find_by_id('categories',(int)$_GET['id']);
  if(!$categorie){
    $session->msg("d","ID de la categoría falta.");
    redirect('categorie.php');
  }
?>
<?php
  $delete_id = delete_by_id('categories',(int)$categorie['id']);
  if($delete_id){
      $session->msg("s","Categoría eliminada");
      redirect('categorie.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('categorie.php');
  }
?>
