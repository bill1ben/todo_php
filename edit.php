<?php
$page = "edit";
$scripts = ["todo_edit.js"];
include "common/header.php";
include "db/db_functions.php";

if($_SESSION['isConnected'] !== true ){
    header('Location: /login.php');
}

$id_todo = isset($_POST['id_todo']) && !empty($_POST['id_todo']) ? $_POST['id_todo'] : null;
$action = isset($_POST['action']) ? $_POST['action'] : 'show';

if (!$id_todo) {
    header("Location: accueil.php");
    exit;
}

if ($action === 'edit')
{
    $title = isset($_POST['title']) && !empty($_POST['title']) ? $_POST['title'] : '';
    $id_priority = isset($_POST['id_priority']) && !empty($_POST['id_priority']) ? $_POST['id_priority'] : '';
    $description = isset($_POST['description']) && !empty($_POST['description']) ? $_POST['description'] : 'NULL';
    $done = isset($_POST['done']) && !empty($_POST['done']) ? '1' : '0';

    $id_url_image = isset($_POST['id_url_image']) && !empty($_POST['id_url_image']) ? $_POST['id_url_image'] : null;
    $url_image = isset($_POST['url_image']) && !empty($_POST['url_image']) ? $_POST['url_image'] : null;

    $id_labels = isset($_POST['id_labels']) && !empty($_POST['id_labels']) ? $_POST['id_labels'] : [];
    $items = isset($_POST['items']) && !empty($_POST['items']) ? $_POST['items'] : [];

    DB_LABEL::deleteAllTodoLabelByTodo($id_todo);
    if( count($id_labels) > 0){
        foreach ($id_labels as $id_label)
        {
            DB_LABEL::associateTodoLabel($id_todo, $id_label);
        }
    }

    if ($id_url_image && $url_image) {
        DB_TODO::updateImageUrl($id_url_image, $url_image);
    }

    if (!$id_url_image && $url_image) {
        $id_url_image = DB_TODO::createUrlImage($url_image);
    }
    $result = DB_TODO::updateTODOById($id_todo, $title, $id_priority, $description, $done, $id_url_image);

    if ( count($items) > 0) {
        foreach ($items as $item)
        {
            $action_item = isset($item['action']) && !empty($item['action']) ? $item['action'] : '';
            $id_item = isset($item['id_item']) && !empty($item['id_item']) ? $item['id_item'] : null;

            if ($action_item == "delete" && $id_item){
                DB_ITEM::deleteItem($id_item);
            }

            if ( isset($item['desc_item']) && !empty($item['desc_item'])){
                if ($action_item == "new"){
                    DB_ITEM::createItem($id_todo, $item['desc_item']);
                }
                if ($action_item == "edit" && $id_item){
                    DB_ITEM::updateItem($id_item, $item['desc_item']);
                }
            }
        }
    }
}

if ($action === 'delete_image')
{
    if(isset($_POST['id_url_image']) && !empty($_POST['id_url_image'])){
        DB_TODO::deleteImageUrl($_POST['id_url_image']);
    }
    $result = DB_TODO::deleteTodoImageById($id_todo);
}


$todo = DB_TODO::getTODOById($id_todo);
$todo_labels = DB_LABEL::getTodoLabelByTodo($id_todo);

if ($action === 'delete')
{
    if(isset($todo['id_url_image']) && !empty($todo['id_url_image'])){
        DB_TODO::deleteImageUrl($todo['id_url_image']);
    }
    DB_LABEL::deleteAllTodoLabelByTodo($id_todo);
    DB_ITEM::deleteAllItemByTodo($id_todo);
    $result = DB_TODO::deleteTODO($id_todo);
    if ( isset($result) && !empty($result) ){
        header("Location: accueil.php");
        exit;
    }
}

$priorities = DB_TODO::getAllPriorities();
$labels = DB_LABEL::getAllLABEL();
?>

    <div class="row main justify-content-center align-items-center">
        <div class="col-8 content-page content-accueil text-center">
            <?php if ( isset($result) && !empty($result) ){ ?>
                <div class="col-12 alert alert-success">Tâche modifiée !</div>
            <?php } ?>
            <div class="row justify-content-end">
                <form class="col" method="post" action="edit.php">
                    <input type="hidden" name="action" value="delete" />
                    <input type="hidden" name="id_todo" value="<?php echo $todo['id_todo']; ?>" />
                    <button class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </form>
                <?php if(isset($todo['id_url_image']) && !empty($todo['id_url_image'])) { ?>
                    <form class="col" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                        <input type="hidden" name="id_todo" value="<?php echo $todo['id_todo']; ?>"/>
                        <input type="hidden" name="action" value="delete_image"/>
                        <input type="hidden" name="id_url_image" value="<?php echo $todo['id_url_image']; ?>"/>
                        <button class="btn btn-sm btn-danger"> <i class="fas fa-trash"></i>  Supprimer l'image</button>
                    </form>
                <?php } ?>
            </div>
            <div class="row justify-content-center">
                <h1 class="col-12">
                    Edition de la todo n° <?php echo $id_todo; ?>
                </h1>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id_todo" value="<?php echo $todo['id_todo']; ?>"/>
                <input type="hidden" name="action" value="edit"/>
                <?php if(isset($todo['id_url_image']) && !empty($todo['id_url_image'])) { ?>
                    <input type="hidden" name="id_url_image" value="<?php echo $todo['id_url_image']; ?>"/>
                    <div class="row justify-content-center">
                        <div class="col-8 form-group">
                            <img src="<?php echo $todo['url_image']; ?>" class="img-thumbnail"/>
                        </div>
                    </div>
                <?php } ?>
                <div class="row justify-content-center">
                    <div class="col-8 form-group">
                        <label>Titre</label>
                        <input type="text"
                               class="form-control" name="title" required
                               placeholder="titre de la tâche"
                               value="<?php echo htmlspecialchars($todo['title']); ?>"
                        />
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description"  rows="3"><?php echo htmlspecialchars($todo['description']); ?></textarea>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-group">
                        <label>Image</label>
                        <input type="text"
                               class="form-control" name="url_image"
                               placeholder="<?php echo isset($todo['id_url_image']) && !empty($todo['id_url_image']) ? htmlspecialchars($todo['url_image']) : 'url de l\'image' ?>">
                        <div class="col-8 form-group">

                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-check">
                        <label class="form-check-label">
                            <input type="checkbox"
                                   name="done"
                                    <?php echo $todo['done'] == '1' ? 'checked':'' ; ?>
                                   class="form-check-input">
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
                                    <option value="<?php echo $priority['id_priority']?>"
                                        <?php  echo $priority['id_priority'] === $todo['id_priority'] ? 'selected':''; ?>
                                    ><?php echo $priority['name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-check">
                        <div class="row form-group">
                            <?php foreach ($labels as $label) {
                                $checked = '';
                                foreach ( $todo_labels as $todo_label ) {
                                    if ( isset($todo_label['id_label']) && $todo_label['id_label'] === $label['id_label']  ) {
                                        $checked = 'checked';
                                        break;
                                    }
                                }
                                ?>
                                <label class="col-3">
                                    <input type="checkbox" name="id_labels[]" <?php echo $checked; ?> value="<?php echo $label['id_label']?>"/> <?php echo $label['name_label']; ?>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-8 form-check">
                        <div class="row form-group">
                            <label>Commentaires :</label>
                            <?php if(count($todo['items']) == 0) { ?>
                                <div id="no-comment" class="col-12 alert alert-info text-center font-italic">
                                    Aucun commentaire
                                </div>
                            <?php } ?>
                            <div id="comments" class="col-12">
                            <?php foreach( $todo['items'] as $item ){
                                    $id_uniq = uniqid(7);
                                ?>
                                <div class="col-12 form-group comment edit">
                                    <div class="row justify-content-between">
                                        <input type="hidden" name="items[<?php echo $id_uniq; ?>][id_item]" value="<?php echo $item['id_item']?>"/>
                                        <input class="item-action" type="hidden" name="items[<?php echo $id_uniq; ?>][action]" value="edit"/>
                                        <input type="text" class="col-10 input-value"
                                               name="items[<?php echo $id_uniq; ?>][desc_item]" required
                                                value="<?php echo htmlspecialchars($item['desc_item']); ?>"/>
                                        <button type="button" class="col-1 btn btn-sm btn-danger del-item"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            <?php }?>
                            </div>
                            <div class="col-12 p-0">
                                <button id="add-comment" type="button" class="btn btn-sm btn-success float-right">
                                    <i class="fas fa-plus"></i> ajouter un commentaire
                                </button>
                            </div>
                        </div>
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