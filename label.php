<?php
$page = "label";
include "common/header.php";
include "db/db_functions.php";


$labels = DB_LABEL::getAllLABEL();
?>

<div class="row main justify-content-center align-items-center">
    <div class="col-8 content-page content-accueil text-center">
        LISTING DES LABELS
    </div>
    <table class="table table-dark">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">name</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($labels as $label) { ?>
            <tr>
                <th scope="row"> <?php echo $label['id_label']; ?> </th>
                <td><?php echo htmlspecialchars($label['name_label']); ?></td>
                <td style="display:flex; justify-content: space-between;">
                    <form method="post" action="edit_label.php">
                        <input type="hidden" name="action" value="show" />
                        <input type="hidden" name="id_label" value="<?php echo $label['id_label']; ?>" />
                        <button class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </button>
                    </form>
                    <form method="post" action="edit_label.php">
                        <input type="hidden" name="action" value="delete" />
                        <input type="hidden" name="id_label" value="<?php echo $label['id_label']; ?>" />
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>



<?php
include "common/footer.php";
?>
