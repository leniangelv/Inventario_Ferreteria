<?php
require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Función para encontrar todas las filas de una tabla de base de datos por nombre de tabla
/*--------------------------------------------------------------*/
function find_all($tabla) {
   global $db;
   if (tableExists($tabla)) {
     return find_by_sql("SELECT * FROM ".$db->escape($tabla));
   }
}

/*--------------------------------------------------------------*/
/* Función para realizar consultas
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $resultado = $db->query($sql);
  $resultado_set = $db->while_loop($resultado);
  return $resultado_set;
}

/*--------------------------------------------------------------*/
/* Función para encontrar datos de una tabla por id
/*--------------------------------------------------------------*/
function find_by_id($tabla, $id)
{
  global $db;
  $id = (int)$id;
  if (tableExists($tabla)) {
    $sql = $db->query("SELECT * FROM {$db->escape($tabla)} WHERE id='{$db->escape($id)}' LIMIT 1");
    if ($resultado = $db->fetch_assoc($sql))
      return $resultado;
    else
      return null;
  }
}

/*--------------------------------------------------------------*/
/* Función para eliminar datos de una tabla por id
/*--------------------------------------------------------------*/
function delete_by_id($tabla, $id)
{
  global $db;
  if (tableExists($tabla)) {
    $sql = "DELETE FROM ".$db->escape($tabla);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
  }
}

/*--------------------------------------------------------------*/
/* Función para contar id por nombre de tabla
/*--------------------------------------------------------------*/
function count_by_id($tabla){
  global $db;
  if (tableExists($tabla)) {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($tabla);
    $resultado = $db->query($sql);
    return($db->fetch_assoc($resultado));
  }
}

/*--------------------------------------------------------------*/
/* Determinar si la tabla de base de datos existe
/*--------------------------------------------------------------*/
function tableExists($tabla){
  global $db;
  $tabla_existe = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($tabla).'"');
  if ($tabla_existe) {
    if ($db->num_rows($tabla_existe) > 0)
      return true;
    else
      return false;
  }
}

/*--------------------------------------------------------------*/
/* Iniciar sesión con los datos proporcionados en $_POST,
/* provenientes del formulario de inicio de sesión.
/*--------------------------------------------------------------*/
function authenticate($usuario='', $contraseña='') {
  global $db;
  $usuario = $db->escape($usuario);
  $contraseña = $db->escape($contraseña);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $usuario);
  $resultado = $db->query($sql);
  if ($db->num_rows($resultado)) {
    $usuario = $db->fetch_assoc($resultado);
    $contraseña_solicitada = sha1($contraseña);
    if ($contraseña_solicitada === $usuario['password'] ) {
      return $usuario['id'];
    }
  }
  return false;
}

/*--------------------------------------------------------------*/
/* Iniciar sesión con los datos proporcionados en $_POST,
/* provenientes del formulario login_v2.php.
/* Si usaste este método, elimina la función authenticate.
/*--------------------------------------------------------------*/
function authenticate_v2($usuario='', $contraseña='') {
  global $db;
  $usuario = $db->escape($usuario);
  $contraseña = $db->escape($contraseña);
  $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $usuario);
  $resultado = $db->query($sql);
  if ($db->num_rows($resultado)) {
    $usuario = $db->fetch_assoc($resultado);
    $contraseña_solicitada = sha1($contraseña);
    if ($contraseña_solicitada === $usuario['password'] ) {
      return $usuario;
    }
  }
  return false;
}

/*--------------------------------------------------------------*/
/* Encontrar usuario actualmente conectado por id de sesión
/*--------------------------------------------------------------*/
function current_user(){
  static $usuario_actual;
  global $db;
  if (!$usuario_actual) {
    if (isset($_SESSION['user_id'])) {
      $id_usuario = intval($_SESSION['user_id']);
      $usuario_actual = find_by_id('users', $id_usuario);
    }
  }
  return $usuario_actual;
}

/*--------------------------------------------------------------*/
/* Encontrar todos los usuarios
/* Uniendo la tabla de usuarios y la tabla de grupos de usuarios
/*--------------------------------------------------------------*/
function find_all_user(){
  global $db;
  $resultados = array();
  $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
  $sql .= "g.group_name ";
  $sql .= "FROM users u ";
  $sql .= "LEFT JOIN user_groups g ";
  $sql .= "ON g.group_level=u.user_level ORDER BY u.name ASC";
  $resultado = find_by_sql($sql);
  return $resultado;
}

/*--------------------------------------------------------------*/
/* Función para actualizar el último inicio de sesión de un usuario
/*--------------------------------------------------------------*/
function updateLastLogIn($id_usuario)
{
  global $db;
  $fecha = make_date();
  $sql = "UPDATE users SET last_login='{$fecha}' WHERE id ='{$id_usuario}' LIMIT 1";
  $resultado = $db->query($sql);
  return ($resultado && $db->affected_rows() === 1 ? true : false);
}

/*--------------------------------------------------------------*/
/* Encontrar todos los nombres de grupo
/*--------------------------------------------------------------*/
// Archivo sql.php
function find_by_groupName($group_name) {
  global $db;
  $group_name = $db->escape($group_name);
  $sql = "SELECT * FROM user_groups WHERE group_name='{$group_name}' LIMIT 1";
  $result = $db->query($sql);
  if ($db->num_rows($result) > 0) {
    return $db->fetch_assoc($result);
  } else {
    return false;
  }
}

function find_by_groupLevel($group_level) {
  global $db;
  $group_level = $db->escape($group_level);
  $sql = "SELECT * FROM user_groups WHERE group_level='{$group_level}' LIMIT 1";
  $result = $db->query($sql);
  if ($db->num_rows($result) > 0) {
    return $db->fetch_assoc($result);
  } else {
    return false;
  }
}


/*--------------------------------------------------------------*/
/* Función para verificar qué nivel de usuario tiene acceso a la página
/*--------------------------------------------------------------*/
function page_require_level($require_level) {
  global $session;
  $current_user = current_user();
  $login_level = (int)$current_user['user_level'];
  
  if (is_array($require_level)) {
      if (!in_array($login_level, $require_level)) {
          $session->msg("d", "¡Lo siento! No tienes permiso para ver la página.");
          redirect('home.php', false);
      }
  } else {
      if ($login_level !== $require_level) {
          $session->msg("d", "¡Lo siento! No tienes permiso para ver la página.");
          redirect('home.php', false);
      }
  }
}


/*--------------------------------------------------------------*/
/* Función para encontrar todos los nombres de producto
/* Unirse con las tablas de categorías y medios
/*--------------------------------------------------------------*/
function join_product_table(){
  global $db;
  $sql  =" SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name";
  $sql  .= " AS categorie,m.file_name AS image";
  $sql  .= " FROM products p";
  $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
  $sql  .= " ORDER BY p.id ASC";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Función para encontrar todos los nombres de producto
/* Solicitud proveniente de ajax.php para sugerencia automática
/*--------------------------------------------------------------*/
function find_product_by_title($nombre_producto){
  global $db;
  $nombre_producto_limpio = remove_junk($db->escape($nombre_producto));
  $sql = "SELECT name FROM products WHERE name like '%$nombre_producto_limpio%' LIMIT 5";
  $resultado = find_by_sql($sql);
  return $resultado;
}

/*--------------------------------------------------------------*/
/* Función para encontrar toda la información del producto por título del producto
/* Solicitud proveniente de ajax.php
/*--------------------------------------------------------------*/
function find_all_product_info_by_title($titulo){
  global $db;
  $sql  = "SELECT * FROM products ";
  $sql .= " WHERE name ='{$titulo}'";
  $sql .=" LIMIT 1";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Función para actualizar la cantidad de producto
/*--------------------------------------------------------------*/
function update_product_qty($qty, $p_id){
  global $db;
  $qty = (int) $qty;
  $id  = (int)$p_id;
  $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
  $resultado = $db->query($sql);
  return ($db->affected_rows() === 1 ? true : false);
}

/*--------------------------------------------------------------*/
/* Función para mostrar productos recientemente agregados
/*--------------------------------------------------------------*/
function find_recent_product_added($limite){
  global $db;
  $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
  $sql  .= "m.file_name AS image FROM products p";
  $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
  $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
  $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limite);
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Función para encontrar el producto más vendido
/*--------------------------------------------------------------*/
function find_higest_saleing_product($limite){
  global $db;
  $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
  $sql .= " GROUP BY s.product_id";
  $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limite);
  return $db->query($sql);
}

/*--------------------------------------------------------------*/
/* Función para encontrar todas las ventas
/*--------------------------------------------------------------*/
function find_all_sale(){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Función para mostrar las ventas recientes
/*--------------------------------------------------------------*/
function find_recent_sale_added($limite){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limite);
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Función para generar un informe de ventas entre dos fechas
/*--------------------------------------------------------------*/
function find_sale_by_dates($fecha_inicio, $fecha_fin){
  global $db;
  $fecha_inicio  = date("Y-m-d", strtotime($fecha_inicio));
  $fecha_fin    = date("Y-m-d", strtotime($fecha_fin));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$fecha_inicio}' AND '{$fecha_fin}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}

/*--------------------------------------------------------------*/
/* Función para generar un informe de ventas diario
/*--------------------------------------------------------------*/
function dailySales($año, $mes){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$año}-{$mes}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/* Función para generar un informe de ventas mensual
/*--------------------------------------------------------------*/
function monthlySales($año){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$año}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
  $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}

?>
