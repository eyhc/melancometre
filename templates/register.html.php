<!DOCTYPE html>
<html>
<?php include('head.html.php'); ?>
<body class="text-center bg-light">

    <div class="container">
        <div class="py-5">
            <img class="d-block mx-auto mb-4" src="public/icon_600.png" alt="" width="80" height="80">
            <h2><?= trans('Register') ?></h2>
        </div>

        <div class="col-md-8 offset-md-2">
            <form method="post">
                <?php if (in_array(SOMETHING_WENT_WRONG, $errors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong><?= trans('Something went wrong!') ?></strong>
                        <?= trans('Please try it later') ?>.
                    </div>
                <?php endif ?>
                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="lastName"><?= trans('Last name') ?> <span class="text-muted">(<?= trans('Optional') ?>)</span></label>
                        <input type="text" class="form-control" id="lastName" name="lastName" <?= !empty($_POST['lastName']) ? 'value="' . $_POST['lastName'] . '"':null ?> aria-describedby="nameHelp">

                    </div>
                    <div class="col-md-6">
                        <label for="firstName"><?= trans('First name') ?> <span class="text-muted">(<?= trans('Optional') ?>)</span></label>
                        <input type="text" class="form-control" id="firstName" name="firstName" <?= !empty($_POST['firstName']) ? 'value="' . $_POST['firstName'] . '"':null ?> describedby="nameHelp">
                    </div>
                    <div class="col-12">
                        <small id="nameHelp" class="form-text text-muted">
                            <?= trans('It only helps us to say hello!') ?>
                        </small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="username"><?= trans('Username') ?></label>
                    <input type="text" class="form-control <?= (in_array(MISSING_USERNAME, $errors) || in_array(ALREADY_EXISTS_USERNAME, $errors)) ? 'is-invalid':null ?>" id="username" name="username" required="" <?= !empty($_POST['username']) ? 'value="' . $_POST['username'] . '"':null ?>>
                    <div class="invalid-feedback" style="width: 100%;">
                        <?= in_array(MISSING_USERNAME, $errors) ? trans('Your username is required') . '.' : null ?>
                        <?= in_array(ALREADY_EXISTS_USERNAME, $errors) ? trans('This username already exists, choose an other') . '.' : null ?>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-md-6">
                        <label for="password"><?= trans('Password') ?></label>
                        <input type="password" class="form-control <?= in_array(INVALID_PASSWORD, $errors) ? 'is-invalid':null ?>" id="password" name="password" required="">
                        <div class="invalid-feedback">
                            <?= trans('A password is required') ?>.
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="repeat-password"><?= trans('Repeat password') ?></label>
                        <input type="password" class="form-control <?= in_array(NONCORRESPONDING_PASSWORD, $errors) ? 'is-invalid':null ?>" id="repeat-password" name="repeatPassword" required="">
                        <div class="invalid-feedback">
                            <?= trans('The passwords must be identical') ?>.
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">
                        <i class="fa fa-user-plus"></i>
                        <?= trans('Register') ?>
                    </button>
                    &nbsp;&nbsp;
                    <a href="login.php" class="btn btn-info">
                        <i class="fa fa-external-link-alt"></i>
                        <?= trans('Sign in') ?>
                    </a>
                </div>
            </form>
        </div>

      <footer class="my-5 text-muted text-center text-small">
        <p class="mb-1"><i class="far fa-copyright"></i> 2020</p>
      </footer>
    </div>

    <?php  include('scripts.html'); ?>
</body>
</html>
