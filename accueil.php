<?php
$page = "accueil";
include "common/header.php";
include "db/db_functions.php";


$todos = DB_TODO::getAllTODO();
?>

<div class="row main justify-content-center align-items-center">
    <div class="col-8 content-page content-accueil text-center">
        LISTING DES TODO
    </div>
    <table class="table table-dark">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"></th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Labels</th>
            <th scope="col">Priorité</th>
            <th scope="col">Nb comments</th>
            <th scope="col">Réalisée ?</th>
            <?php if ($is_connected) { ?>
                <th></th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
            <?php foreach ($todos as $todo) { ?>
                <tr>
                    <th scope="row"> <?php echo $todo['id_todo']; ?> </th>
                    <td>
                        <?php if(isset($todo['id_url_image']) && !empty($todo['id_url_image'])) { ?>
                            <img src="<?php echo $todo['url_image']; ?>" class="img-thumbnail"/>
                        <?php } ?>
                    </td>
                    <td><?php echo htmlspecialchars($todo['title']); ?></td>
                    <td><?php echo htmlspecialchars($todo['description']); ?></td>
                    <td><?php foreach ($todo['labels'] as $todolabel) {  if (isset($todolabel['name_label'])) { ?>
                            <span class="badge badge-secondary"><?php echo $todolabel['name_label']; ?></span>
                        <?php } }?>
                    </td>
                    <td><?php echo $todo['name']; ?></td>
                    <td><?php echo $todo['nb_comments']; ?></td>
                    <td><?php echo $todo['done'] == '0' ? 'NON':'OUI'; ?></td>
                    <?php if ($is_connected) { ?>
                        <td style="display:flex; justify-content: space-between;">
                            <form method="post" action="edit.php">
                                <input type="hidden" name="action" value="show" />
                                <input type="hidden" name="id_todo" value="<?php echo $todo['id_todo']; ?>" />
                                <button class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            <form method="post" action="edit.php">
                                <input type="hidden" name="action" value="delete" />
                                <input type="hidden" name="id_todo" value="<?php echo $todo['id_todo']; ?>" />
                                <button class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>



<?php
include "common/footer.php";
?>
