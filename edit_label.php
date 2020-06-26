<?php
$page = "edit_label";
include "common/header.php";
include "db/db_functions.php";

if($_SESSION['isConnected'] !== true ){
    header('Location: /login.php');
}

$id_label = isset($_POST['id_label']) && !empty($_POST['id_label']) ? $_POST['id_label'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : 'show';

if (!$id_label) {
    header("Location: accueil.php");
    exit;
}

if ($action === 'edit')
{
    $name_label = isset($_POST['name_label']) && !empty($_POST['name_label']) ? $_POST['name_label'] : '';
    $result = DB_LABEL::updateLabel($id_label, $name_label);
}


$label = DB_LABEL::getLabelById($id_label);

if ($action === 'delete')
{
    DB_LABEL::deleteAllTodoLabelByLabel($id_label);
    $result = DB_LABEL::deleteLabel($id_label);
    if ( isset($result) && !empty($result) ){
        header("Location: label.php");
        exit;
    }
}

?>

    <div class="row main justify-content-center align-items-center">
        <div class="col-8 content-page content-accueil text-center">
            <?php if ( isset($result) && !empty($result) ){ ?>
                <div class="col-12 alert alert-success">Label modifié !</div>
            <?php } ?>
            <div class="row justify-content-end">
                <form class="col" method="post" action="edit_label.php">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="id_label" value="<?php echo $label['id_label']; ?>" />
                    <button class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
            </div>
            <div class="row justify-content-center">
                <h1 class="col-12">
                    Edition du label n° <?php echo $id_label; ?>
                </h1>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id_label" value="<?php echo $label['id_label']; ?>"/>
                <input type="hidden" name="action" value="edit"/>
                <div class="row justify-content-center">
                    <div class="col-8 form-group">
                        <input type="text"
                               class="form-control" name="name_label" required
                               placeholder="nom du label"
                               value="<?php echo htmlspecialchars($label['name_label']); ?>"
                        />
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-8 form-check">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php
include "common/footer.php";
?>