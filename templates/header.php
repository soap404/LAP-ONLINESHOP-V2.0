<?php
session_start();

require_once "middleware/isUser.php";
require_once "middleware/isAdmin.php";
require_once "middleware/isGuest.php";
require_once "controller/AuthController.php";

if (isset($_POST['logout'])) {
    AuthController::logout();
    header('Location: index.php');
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Online Shop</title>
</head>
<body>

<!-- Nav -->

<nav class="navbar navbar-expand-lg bg-body-tertiary" style="padding-right: 140px; padding-left: 140px">
    <div class="container-fluid">
        <a class="navbar-brand" href="/lap-onlineshop-v2.0">Online Shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Products</a>
                </li>

                <?php if (isGuest::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                <?php endif; ?>

                <?php if (isAdmin::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">Admin</a>
                    </li>
                <?php endif; ?>

                <?php if (IsUser::check()): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Account</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cart</a>
                    </li>
                    <li class="nav-item">
                        <form action="" method="POST">
                            <button class="nav-link" name="logout">Logout</button>
                        </form>

                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>

<div style="padding: 40px 140px;">

