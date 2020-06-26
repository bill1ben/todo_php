<?php
session_name("SessionWebSite");
session_start();

$page = isset($page) && !empty($page) ? "$page" : '';
$page_title = isset($page) && !empty($page) ? "$page - " : '';
$is_connected = isset($_SESSION['isConnected']) && !empty($_SESSION['isConnected']) ? $_SESSION['isConnected'] : false;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/all.css" >
    <link rel="stylesheet" href="css/style.css">

    <title><?php echo $page_title; ?>TODO</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">TODO MANY2ONE</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item <?php echo $page === "accueil" ? 'active':''; ?>">
                <a class="nav-link" href="/accueil.php">Accueil</a>
            </li>
            <?php if ($is_connected) { ?>
                <li class="nav-item <?php echo $page === "login" ? 'active':''; ?>">
                    <a class="nav-link" href="/login.php?deconnexion=true">Deconnexion</a>
                </li>
                <li class="nav-item <?php echo $page === "add" ? 'active':''; ?>">
                    <a class="nav-link" href="/add.php">Ajouter une todo</a>
                </li>
                <li class="nav-item dropdown <?php echo $page === "label" || $page === "add_label" || $page === "edit_label" ? 'active':''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Label
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="/add_label.php">Ajouter un label</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/label.php">Lister les labels</a>
                    </div>
                </li>
                <li class="nav-item <?php echo $page === "admin" ? 'active':''; ?>">
                    <a class="nav-link" href="/admin.php">Admin</a>
                </li>
            <?php } else { ?>
                <li class="nav-item <?php echo $page === "login" ? 'active':''; ?>">
                    <a class="nav-link" href="/login.php">Login</a>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>
<div class="container-fluid main">