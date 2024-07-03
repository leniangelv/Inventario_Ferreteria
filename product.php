<?php
$page_title = 'Lista de productos';
require_once('includes/load.php');
// Verificando que el nivel de usuario tiene permiso para ver esta página
page_require_level(array(1, 2, 3));
$products = join_product_table();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <?php if($user['user_level'] <= 2): // Solo usuarios de nivel 1 y 2 pueden agregar productos ?>
                <div class="pull-right">
                    <a href="add_product.php" class="btn btn-primary">Agregar producto</a>
                </div>
                <?php endif; ?>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th> Imagen</th>
                            <th> Descripción </th>
                            <th class="text-center" style="width: 10%;"> Categoría </th>
                            <th class="text-center" style="width: 10%;"> Stock </th>
                            <th class="text-center" style="width: 10%;"> Precio de compra </th>
                            <th class="text-center" style="width: 10%;"> Precio de venta </th>
                            <th class="text-center" style="width: 10%;"> Agregado </th>
                            <?php if($user['user_level'] <= 2): // Solo mostrar acciones a usuarios de nivel 1 y 2 ?>
                            <th class="text-center" style="width: 100px;"> Acciones </th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="text-center"><?php echo count_id(); ?></td>
                            <td>
                                <?php if($product['media_id'] === '0'): ?>
                                    <img class="img-avatar img-circle" src="uploads/products/no_image.jpg" alt="">
                                <?php else: ?>
                                    <img class="img-avatar img-circle" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                <?php endif; ?>
                            </td>
                            <td> <?php echo remove_junk($product['name']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['categorie']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['quantity']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['buy_price']); ?></td>
                            <td class="text-center"> <?php echo remove_junk($product['sale_price']); ?></td>
                            <td class="text-center"> <?php echo read_date($product['date']); ?></td>
                            <?php if($user['user_level'] <= 2): // Solo mostrar botones de acción a usuarios de nivel 1 y 2 ?>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-info btn-xs" title="Editar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-edit"></span>
                                    </a>
                                    <a href="delete_product.php?id=<?php echo (int)$product['id']; ?>" class="btn btn-danger btn-xs" title="Eliminar" data-toggle="tooltip">
                                        <span class="glyphicon glyphicon-trash"></span>
                                    </a>
                                </div>
                            </td>
                            <?php endif; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
