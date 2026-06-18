<?php
require_once('templates/header.php');
require_once('controller/AuthController.php');
require_once('middleware/isGuest.php');

if(!IsGuest::check()){
    header('Location: index.php');
    exit();
}

$errors = [];

if(isset($_POST['login'])) {
    $result = AuthController::login($_POST);
    if($result === true) {
        header('Location: index.php');
    }else {
        $errors = $result;
    }
}

?>


<?php foreach ($errors as $error): ?>
    <div class="alert alert-danger" role="alert">
        <?= $error ?>
    </div>
<?php endforeach; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="text" class="form-control" id="email" name="email" value="<?= $_POST['email'] ?? '' ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <button type="submit" class="btn btn-primary" name="login">Login</button>
    </form>

<?php require_once('templates/footer.php');?>