<?php
$page = "login";
$identifiant = (isset($_POST['identifiant'])   && !empty($_POST['identifiant']) )? $_POST['identifiant'] : null;
$password = (isset($_POST['password'])   && !empty($_POST['password']) )? $_POST['password'] : null;

include "common/header.php";

if( $identifiant && $password ){
    include_once('./db/db_functions.php');
    $login = DB_TODO::login($identifiant, $password);
    if ($login) {
        $_SESSION['isConnected'] = true;
        $_SESSION['timeStamp'] = time();
        header('Location: /accueil.php');
        exit;
    }
}
$deconnexion = (isset($_GET['deconnexion'])   && !empty($_GET['deconnexion']) )? $_GET['deconnexion']:null;
if($deconnexion == true && isset($_SESSION) && $_SESSION['isConnected'] === true ){
    session_destroy();
    header('Location: /index.php');
}
if ( isset($_SESSION['isConnected']) && $_SESSION['isConnected'] === true ) {
    header('Location: /accueil.php');
    exit;
}
?>

<div class="row justify-content-center align-items-center">
    <div class="col-8 text-center">
        <div class="row mt-5">
            <form class="col-12" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="row mt-3">
                    <?php if ($identifiant && $password && $identifiant !== 'admin' && $password !== 'admin'){ ?>
                        <div class="col-12 alert alert-danger">Le couple identifiant/mot de passe n'existe pas.</div>
                    <?php } ?>
                    <?php if (isset($_POST['identifiant']) && empty($_POST['identifiant'])){ ?>
                        <div class="col-12 alert alert-danger">Veuillez indiquer votre identifiant</div>
                    <?php } ?>
                    <?php if (isset($_POST['password']) && empty($_POST['password'])){ ?>
                        <div class="col-12 alert alert-danger">Veuillez indiquer votre mot de passe</div>
                    <?php } ?>
                    <div class="col-6 text-right">Identifiant</div>
                    <div class="col-6 text-left">
                        <input type="text" name="identifiant" autocomplete="Off"/>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6 text-right">Mot de passe</div>
                    <div class="col-6 text-left">
                        <input type="password" name="password" autocomplete="Off"/>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-6"></div>
                    <div class="col-6 text-left">
                        <button type="submit" class="btn btn-primary">S'identifier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "common/footer.php";
?>
