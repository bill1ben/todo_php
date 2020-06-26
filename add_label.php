<?php
$page = "add_label";
include "common/header.php";
include "db/db_functions.php";

if($_SESSION['isConnected'] !== true ){
    header('Location: /login.php');
}

if ( $_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['name_label']) && !empty($_POST['name_label']) )
{
    $name_label = $_POST['name_label'];
    $result = DB_LABEL::createLABEL($name_label);
}
?>

    <div class="row main justify-content-center align-items-center">
        <div class="col-8 content-page content-accueil text-center">
            <?php if ( isset($result) && !empty($result) ){ ?>
                <div class="col-12 alert alert-success">Label ajouté !</div>
            <?php } ?>
            <div class="row justify-content-center">
                <h1 class="col-12">
                    Ajouter un label
                </h1>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="row justify-content-center">
                    <div class="col-8 form-group">
                        <input type="text"
                               class="form-control" name="name_label" required
                               placeholder="nom du label">
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