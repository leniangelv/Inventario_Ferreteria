<?php
  require_once('includes/load.php');
  // Verificando que el nivel de usuario tiene permiso para ver esta pagina
   page_require_level(1);
?>
<?php
  $delete_id = delete_by_id('user_groups',(int)$_GET['id']);
  if($delete_id){
      $session->msg("s","Grupo eliminado");
      redirect('group.php');
  } else {
      $session->msg("d","Eliminación falló");
      redirect('group.php');
  }
?>
