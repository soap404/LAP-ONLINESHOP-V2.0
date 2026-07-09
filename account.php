<?php
require_once('templates/header.php');
require_once('controller/AccountController.php');
require_once('controller/AuthController.php');
require_once('middleware/IsUser.php');

if (!IsUser::check()) {
    header('Location: login.php');
    exit();
}

$errors = [];

if (isset($_POST['account_update'])) {
    $result = AccountController::updateUser($_POST);
    if ($result === true) {
        $message = 'Account updated successfully';
    } else if ($result === false) {
        AuthController::logout();
        header('Location: index.php');
        exit();
    } else {
        $errors = $result;
    }
}

if (isset($_POST['password_update'])) {
    $result = AccountController::updatePassword($_POST);
    if ($result === true) {
        $message = 'Password updated successfully';
    } else if ($result === false) {
        AuthController::logout();
        header('Location: index.php');
        exit();
    } else {
        $errors = $result;
    }
}

if (isset($_POST['create_address'])) {
    $result = AccountController::createAddress($_POST);
    if ($result === true) {
        header('Location: account.php');
        exit();
    } else if ($result === false) {
        AuthController::logout();
        header('Location: index.php');
        exit();
    } else {
        $errors = $result;
    }
}

$user = AccountController::getUser();
$addresses = AccountController::getUserAddresses();

?>

<?php if (isset($message)): ?>
    <div>
        <div class="alert alert-success" role="alert">
            <?= $message ?>
        </div>
    </div>
<?php endif; ?>

<?php foreach ($errors as $error) : ?>
    <div class="alert alert-danger" role="alert">
        <?= $error ?>
    </div>
<?php endforeach; ?>

    <div>
        <h2>Account Settings</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" id="email" name="email" value="<?= $user['email'] ?>">
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                       value="<?= $user['first_name'] ?>">
            </div>
            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                       value="<?= $user['last_name'] ?>">
            </div>
            <button type="submit" class="btn btn-primary" name="account_update">Update</button>
        </form>
    </div>

    <div class="mt-4">
        <h2>Password</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="old_password" class="form-label">Old Password</label>
                <input type="password" class="form-control" id="old_password" name="old_password">
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Neu Password</label>
                <input type="password" class="form-control" id="new_password" name="new_password">
            </div>
            <button type="submit" class="btn btn-primary" name="password_update">Update Password</button>
        </form>
    </div>

    <div class="mt-4">
        <h2>Addresses</h2>
        <div class="row">
            <div class="mt-2 col-md-6">
                <form method="POST">
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city"
                               value="<?= $_POST['city'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address_line1" class="form-label">Address Line 1</label>
                        <input type="text" class="form-control" id="address_line1" name="address_line1"
                               value="<?= $_POST['address_line1'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address_line2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="address_line2" name="address_line2"
                               value="<?= $_POST['address_line2'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label for="zip" class="form-label">Zip</label>
                        <input type="text" class="form-control" id="zip" name="zip"
                               value="<?= $_POST['zip'] ?? '' ?>">
                    </div>
                    <select class="form-select mb-3" name="type" aria-label="Type">
                        <option value="1">Delivery Address</option>
                        <option value="2">Invoice Address</option>
                    </select>
                    <button type="submit" class="btn btn-primary" name="create_address">Create</button>
                </form>
            </div>

            <div class="mt-2 col-md-6">
                <?php if (count($addresses) > 0) : ?>
                    <?php foreach ($addresses as $address) : ?>
                        <div class="card mt-2">
                            <div class="card-header">
                                <?= $address['type'] == 1 ? "Delivery Address" : "Invoice Address" ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= $address['city'] ?> - <?= $address['zip'] ?></h5>
                                <p class="card-text"><?= $address['address_line1'] ?>
                                    <?= $address['address_line2'] ? '- ' . $address['address_line2'] : '' ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-secondary text-center" role="alert">
                        ops you do not have addresses yet!
                    </div>
                <?php endif; ?>
            </div>


        </div>
    </div>

<?php require_once('templates/footer.php'); ?>