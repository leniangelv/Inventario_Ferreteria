<?php
  $page_title = 'Agregar usuarios';
  require_once('includes/load.php');
  // Verificando que el nivel de usuario tiene permiso para ver esta pagina
  page_require_level(1);
  $groups = find_all('user_groups');

  if(isset($_POST['add_user'])){
    $req_fields = array('full-name','username','password','level');
    validate_fields($req_fields);

    if(empty($errors)){
      $name       = remove_junk($db->escape($_POST['full-name']));
      $username   = remove_junk($db->escape($_POST['username']));
      $password   = remove_junk($db->escape($_POST['password']));
      $user_level = (int)$db->escape($_POST['level']);
      $password   = sha1($password);

      $query  = "INSERT INTO users (";
      $query .= "name,username,password,user_level,status";
      $query .= ") VALUES (";
      $query .= "'{$name}', '{$username}', '{$password}', '{$user_level}','1'";
      $query .= ")";

      if($db->query($query)){
        // Success
        $session->msg('s',"Cuenta de usuario ha sido creada");
        redirect('users.php', false); // Redirigir a users.php
      } else {
        // Failed
        $session->msg('d','No se pudo crear la cuenta.');
        redirect('add_user.php', false);
      }
    } else {
      $session->msg("d", $errors);
      redirect('add_user.php',false);
    }
  }
?>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>

<style>
  .container {
    width: 75%;
  }
  .form-container {
    width: 70%;
    margin-left: 15%;
  }
  #btn-save {
    float: inline-end;
    right: 0%;
  }
</style>

<div class="container">
<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Agregar usuario</span>
      </strong>
    </div>
    <div class="panel-body">
      <div class="col-md-12 form-container">
        <form method="post" action="add_user.php">
          <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" name="full-name" placeholder="Nombre completo" required>
          </div>
          <div class="form-group">
            <label for="username">Usuario</label>
            <input type="text" class="form-control" name="username" placeholder="Nombre de usuario">
          </div>
          <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" name="password" placeholder="Contraseña">
          </div>
          <div class="form-group">
            <label for="level">Rol de usuario</label>
            <select class="form-control" name="level">
              <?php foreach ($groups as $group ): ?>
                <option value="<?php echo $group['group_level']; ?>"><?php echo ucwords($group['group_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group clearfix">
            <button type="submit" id="btn-save" name="add_user" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<?php include_once('layouts/footer.php'); ?>
