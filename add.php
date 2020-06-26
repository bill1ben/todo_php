<?php
$page = "add";
$scripts = ["todo_add.js"];
include "common/header.php";
include "db/db_functions.php";

if($_SESSION['isConnected'] !== true ){
    header('Location: /login.php');
}

if ( $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['title']) && !empty($_POST['title']) )
{

    $title = $_POST['title'];
    $description = isset($_POST['description']) && !empty($_POST['description']) ? $_POST['description'] : NULL;
    $id_priority = isset($_POST['id_priority']) && !empty($_POST['id_priority']) ? $_POST['id_priority'] : NULL;
    $done = isset($_POST['done']) && !empty($_POST['done']) ? '1':'0';
    $lastInsertIdUrlImage = NULL;
    $url_image = isset($_POST['url_image']) && !empty($_POST['url_image']) ? $_POST['url_image'] : NULL;
    $id_labels = isset($_POST['id_labels']) && !empty($_POST['id_labels']) ? $_POST['id_labels'] : [];
    $items = isset($_POST['items']) && !empty($_POST['items']) ? $_POST['items'] : [];

    if ($url_image) {
        $lastInsertIdUrlImage = DB_TODO::createUrlImage($url_image);
    }
    $lastInsertId = DB_TODO::createTODO($title, $id_priority, $description, $done, $lastInsertIdUrlImage);

    $result = 0;
    if( count($id_labels) > 0 ) {
        foreach ($id_labels as $id_label)
        {
            $ret = DB_LABEL::associateTodoLabel($lastInsertId, $id_label);
            if ($ret){
                $result ++;
            }
        }
    }
    $nb_items = 0;
    if ( count($items) > 0) {
        foreach ($items as $item)
        {
            if ( isset($item['desc_item']) && !empty($item['desc_item'])){
                if ($item['action'] == "new"){
                    $ret = DB_ITEM::createItem($lastInsertId, $item['desc_item']);
                    if ($ret){
                        $nb_items ++;
                    }
                }
            }
        }
    }
}


$priorities = DB_TODO::getAllPriorities();
$labels = DB_LABEL::getAllLABEL();
?>

    <div class="row main justify-content-center align-items-center">
        <div class="col-8 content-page content-accueil text-center">
            <?php if ( isset($lastInsertId) && !empty($lastInsertId) ){ ?>
                <div class="col-12 alert alert-success">Tâche ajoutée !</div>
                <?php if ( isset($result) && !empty($result) && $result > 0 ) { ?>
                    <div class="col-12 alert alert-success">associée avec <?php echo $result;?> label !</div>
                <?php } ?>
                <?php if ( isset($nb_items) && !empty($nb_items) && $nb_items > 0 ) { ?>
                    <div class="col-12 alert alert-success">avec <?php echo $nb_items;?> commentaire !</div>
                <?php } ?>
            <?php } ?>
            <div class="row justify-content-center">
                <h1 class="col-12">
                    Ajouter une tâche
                </h1>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="row justify-content-center">
                    <div class="col-8 form-group">
                        <label>Titre</label>
                        <input type="text"
                               class="form-control" name="title" required
                               placeholder="titre de la tâche">
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description"  rows="3"></textarea>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-group">
                        <label>Image</label>
                        <input type="text"
                               class="form-control" name="url_image"
                               placeholder="url de l'image">
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-check">
                        <label class="form-check-label">
                            <input type="checkbox" name="done" class="form-check-input">
                            Réalisée ?
                        </label>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-check">
                        <div class="col-8 form-group">
                            <label>Ordre d'importance :</label>
                            <select class="form-control" name="id_priority">
                                <?php foreach ($priorities as $priority) { ?>
                                    <option value="<?php echo $priority['id_priority']?>"><?php echo $priority['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-check">
                        <div class="row form-group">
                            <?php foreach ($labels as $label) { ?>
                                <label class="col-3">
                                    <input type="checkbox" name="id_labels[]" value="<?php echo $label['id_label']?>"/> <?php echo $label['name_label']; ?>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-check">
                        <div class="row form-group">
                            <label>Commentaires :</label>
                            <div id="no-comment" class="col-12 alert alert-info text-center font-italic">
                                Aucun commentaire
                            </div>
                            <div id="comments" class="col-12"></div>
                            <div class="col-12 p-0">
                                <button id="add-comment" type="button" class="btn btn-sm btn-success float-right">
                                    <i class="fas fa-plus"></i> ajouter un commentaire
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 p-0">
                        <button type="submit" class="btn btn-sm btn-primary float-right">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php
include "common/footer.php";
?>