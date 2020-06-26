<?php
$page = "admin";
include "common/header.php";
if($_SESSION['isConnected'] !== true ){
    header('Location: /login.php');
}
?>

<div class="row content-row justify-content-center align-items-center">
    <div class="col-8 content-page content-accueil text-center">
        <div class="col">Bienvenue <?php  echo $_SESSION['user']; ?> !</div>
        <div class="col">Dans votre espace sécurisé.</div>
        <div class="col">Email : <?php echo $_SESSION["userEmail"]; ?></div>
    </div>
</div>

<?php
include "common/footer.php";
?>
