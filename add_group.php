<?php
$page_title = 'Agregar grupo';
require_once('includes/load.php');
// Verificando que el nivel de usuario tiene permiso para ver esta página
page_require_level(1);

if (isset($_POST['add'])) {

    $req_fields = array('group-name', 'group-level');
    validate_fields($req_fields);

    $group_name = remove_junk($db->escape($_POST['group-name']));
    $group_level = remove_junk($db->escape($_POST['group-level']));

    if (find_by_groupName($group_name) !== false) {
        $session->msg('d', '<b>Error!</b> El nombre de grupo ya existe en la base de datos');
        redirect('add_group.php', false);
    } elseif (find_by_groupLevel($group_level) !== false) {
        $session->msg('d', '<b>Error!</b> El nivel de grupo ya existe en la base de datos');
        redirect('add_group.php', false);
    }

    if (empty($errors)) {
        $status = remove_junk($db->escape($_POST['status']));

        $query  = "INSERT INTO user_groups (";
        $query .= "group_name,group_level,group_status";
        $query .= ") VALUES (";
        $query .= " '{$group_name}', '{$group_level}', '{$status}'";
        $query .= ")";

        if ($db->query($query)) {
            // Success
            $session->msg('s', "Grupo ha sido creado!");
            redirect('add_group.php', false);
        } else {
            // Failed
            $session->msg('d', 'Lamentablemente no se pudo crear el grupo!');
            redirect('add_group.php', false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('add_group.php', false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="login-page">
  <div class="text-center">
    <h3>Agregar nuevo grupo de usuarios</h3>
  </div>
  <hr>
  <?php echo display_msg($msg); ?>
  <form method="post" action="add_group.php" class="clearfix">
    <div class="form-group">
      <label for="name" class="control-label">Nombre del grupo</label>
      <input type="text" class="form-control" name="group-name" required>
    </div>
    <div class="form-group">
      <label for="level" class="control-label">Nivel del grupo</label>
      <input type="number" class="form-control" name="group-level" required>
    </div>
    <div class="form-group">
      <label for="status">Estado</label>
      <select class="form-control" name="status">
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
      </select>
    </div>
    <div class="form-group clearfix">
      <button type="submit" name="add" class="btn btn-info">Guardar</button>
    </div>
  </form>
</div>
<?php include_once('layouts/footer.php'); ?>

<style>
  button {
    float: inline-end;
    right: 0%;
  }
</style>
