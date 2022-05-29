<!DOCTYPE html>
<html>
<?php include('head.html.php'); ?>
<body class="text-center bg-light">
    <style>
        html,
        body {
          height: 100%;
        }

        body {
          display: -ms-flexbox;
          display: flex;
          -ms-flex-align: center;
          align-items: center;
          padding-top: 40px;
          padding-bottom: 40px;
        }

        .form-signin {
          width: 100%;
          max-width: 370px;
          padding: 15px;
          margin: auto;
        }
    </style>

    <form class="form-signin" method="post">
        <img class="mb-4" src="public/icon_600.png" alt="" width="80" height="80">
        <h1 class="h3 mb-3 font-weight-normal"><?= trans('Sign in') ?></h1>
        <?php if (in_array(UNKNOWN_USERNAME, $errors) || in_array(WRONG_PASSWORD, $errors)): ?>
            <div class="alert alert-danger">
                <strong><?= trans('Something went wrong!') ?></strong>
                <?= in_array(UNKNOWN_USERNAME, $errors) ? trans('User') . ' ' . $_POST['username'] . ' ' . trans('is unknown') . '.' : null ?>
                <?= in_array(WRONG_PASSWORD, $errors) ? trans('Invalid password') . '.':null ?>
            </div>
        <?php endif ?>
        <div class="form-group">
            <?php if (in_array(DISABLED_USER, $errors)): ?>
                <div class="alert alert-danger" role="alert">
                    <strong><?= trans('Caution!') ?></strong>
                    <?= trans('User') . ' ' . $user->username . ' ' . trans('is disabled') ?>.
                </div>
            <?php endif ?>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-user"></i></span>
                </div>
                <input type="text" name="username" class="form-control <?= in_array(USERNAME_MISSING, $errors) ? 'is-invalid':null ?>" id="username" placeholder="<?= trans('Username') ?>" required="" <?= !empty($_POST['username'])? 'value="' . $_POST['username'] . '"':null ?>>
                <div class="invalid-feedback" style="width: 100%;">
                    <?= trans('Your username is required') ?>.
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group" style="margin-bottom: 10px;">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                </div>
                <input type="password" name="password" class="form-control <?= in_array(PASSWORD_MISSING, $errors) ? 'is-invalid':null ?>" name="password" placeholder="<?= trans('Password') ?>" required="">
                <div class="invalid-feedback" style="width: 100%;">
                    <?= trans('Your password is required') ?>.
                </div>
            </div>
        </div>
        <div class="form-group">
            <button type="submit" id="_submit" name="_submit" class="btn btn-default btn-primary"><i class="fa fa-sign-in-alt"></i> <?= trans('Sign in') ?></button>&nbsp;&nbsp;
            <a href="register.php" class="btn btn-info"><i class="fa fa-external-link-alt"></i> <?= trans('Register') ?></a>
        </div>
        <p class="mt-5 mb-3 text-muted"><i class="far fa-copyright"></i> 2020</p>
    </form>

    <?php  include('scripts.html'); ?>
</body>
</html>
