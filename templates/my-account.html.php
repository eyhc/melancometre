<!doctype html>
<html class="h-100" ng-app="app">
    <?php include('head.html.php'); ?>
    <body class="d-flex flex-column h-100">

        <!-- nav -->
        <?php $with_return = true; include('nav.html.php'); ?>

        <div class="container py-4">
            <div class="card card-body bg-light">
                <?php if (in_array(SUCCESS_INFO, $errors) || in_array(SUCCESS_CHANGE_PASSWORD, $errors)): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fa fa-info-circle"></i>
                    <?= in_array(SUCCESS_INFO, $errors) ? trans('Your profile is successfully edited') : trans('Your password is successfully edited') ?>.
                </div>
                <?php endif; ?>
                <?php if (!in_array(SUCCESS_INFO, $errors) && !in_array(SUCCESS_CHANGE_PASSWORD, $errors) && $errors !== array()): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="fa fa-exclamation-triangle"></i>
                    <?= trans('Any errors occurred') ?>.
                </div>
                <?php endif; ?>
                <legend class="border-bottom border-dark text-dark"><?= trans('My profile') ?></legend>
                <form method="post" class="mt-2">
                    <input type="hidden" name="action" value="info" />

                    <div class="row form-group">
                        <div class="col-md-6">
                            <label for="lastName"><?= trans('Last name') ?> <span class="text-muted">(<?= trans('Optional') ?>)</span></label>
                            <input type="text" class="form-control" id="lastName" name="lastName" value="<?= isset($_POST['lastName']) ? $_POST['lastName'] : $user->last_name ?>" aria-describedby="nameHelp">

                        </div>
                        <div class="col-md-6">
                            <label for="firstName"><?= trans('First name') ?> <span class="text-muted">(<?= trans('Optional') ?>)</span></label>
                            <input type="text" class="form-control" id="firstName" name="firstName" value="<?= isset($_POST['firstName']) ? $_POST['firstName'] : $user->first_name ?>" describedby="nameHelp">
                        </div>
                        <div class="col-12">
                            <small id="nameHelp" class="form-text text-muted">
                                <?= trans('It only helps us to say hello!') ?>
                            </small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username"><?= trans('Username') ?></label>
                        <input type="text" class="form-control <?= (in_array(MISSING_USERNAME, $errors) || in_array(ALREADY_EXISTS_USERNAME, $errors)) ? 'is-invalid':null ?>" id="username" name="username" required="" value="<?= isset($_POST['username']) ? $_POST['username'] : $user->username ?>">
                        <div class="invalid-feedback" style="width: 100%;">
                            <?= in_array(MISSING_USERNAME, $errors) ? trans('Your username is required') . '.' : null ?>
                            <?= in_array(ALREADY_EXISTS_USERNAME, $errors) ? trans('This username already exists, choose an other') . '.' : null ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password"><?= trans('Current password') ?></label>
                        <input type="password" class="form-control <?= in_array(INVALID_PASSWORD, $errors) || in_array(MISSING_PASSWORD, $errors) ? 'is-invalid':null ?>" id="password" name="password" required="">
                        <div class="invalid-feedback" style="width: 100%;">
                            <?= in_array(INVALID_PASSWORD, $errors) ? trans('Invalid password') : trans('Your password is required') ?>.
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-edit"></i>
                            <?= trans('Edit') ?>
                        </button>
                    </div>
                </form>

                <legend class="border-bottom border-dark text-dark mt-3"><?= trans('My password') ?></legend>
                <form method="post" class="mt-2">
                    <input type="hidden" name="action" value="change_password" />

                    <div class="form-group">
                        <label for="newPassword"><?= trans('New password') ?></label>
                        <input type="password" class="form-control <?= in_array(INVALID_NEW_PASSWORD, $errors) ? 'is-invalid':null ?>" id="newPassword" name="newPassword" required="">
                        <div class="invalid-feedback" style="width: 100%;">
                            <?= trans('A password is required') ?>.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="repeatPassword"><?= trans('Repeat password') ?></label>
                        <input type="password" class="form-control <?= in_array(INVALID_REPEAT_PASSWORD, $errors) ? 'is-invalid':null ?>" id="repeatPassword" name="repeatPassword" required="">
                        <div class="invalid-feedback" style="width: 100%;">
                            <?= trans('The passwords must be identical') ?>.
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password2"><?= trans('Current password') ?></label>
                        <input type="password" class="form-control <?= in_array(INVALID_PASSWORD_2, $errors) || in_array(MISSING_PASSWORD_2, $errors) ? 'is-invalid':null ?>" id="password2" name="password" required="">
                        <div class="invalid-feedback" style="width: 100%;">
                            <?= in_array(INVALID_PASSWORD_2, $errors) ? trans('Invalid password') : trans('Your password is required') ?>.
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-warning">
                            <i class="far fa-check-square"></i>
                            <?= trans('Change password') ?>
                        </button>
                    </div>
                </form>

                <legend class="border-bottom border-dark text-dark mt-3"><?= trans('Get all my data') ?></legend>
                <div class="mt-2">
                    <input type="hidden" name="action" value="get_data" />

                    <div class="form-group">
                        <label for="password3"><?= trans('Current password') ?></label>
                        <input type="password" class="form-control" id="password3" name="password">
                        <div class="invalid-feedback" style="width: 100%;" id="invalidData"></div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success" id="dataFormButton">
                            <i class="fa fa-table"></i>
                            <?= trans('Get data') ?>
                        </button>
                    </div>
                </div>

                <legend class="border-bottom border-dark text-dark mt-3"><?= trans('Delete my account') ?></legend>
                <form method="post" class="mt-2">
                    <input type="hidden" name="action" value="delete" />

                    <div class="form-group">
                        <label for="password4"><?= trans('Current password') ?></label>
                        <input type="password" class="form-control <?= in_array(INVALID_PASSWORD_4, $errors) || in_array(MISSING_PASSWORD_4, $errors) ? 'is-invalid':null ?>" id="password4" name="password" required="">
                        <div class="invalid-feedback" style="width: 100%;">
                            <?= in_array(INVALID_PASSWORD_4, $errors) ? trans('Invalid password') : trans('Your password is required') ?>.
                        </div>
                    </div>

                    <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="confirm window" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle"><?= trans('Confirmation needed') ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p>
                                <?= trans('Do you really want to delete this user?') ?>
                            </p>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= trans('Cancel') ?></button>
                            <button type="submit" class="btn btn-danger"><?= trans('Confirm') ?></button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-danger" id="deleteModal">
                            <i class="fa fa-trash-alt"></i>
                            <?= trans('Delete my account') ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <?php include('scripts.html'); ?>
        <script src="public/form-account.js"></script>
        <script type="text/javascript">
            var inv = "<?= trans('Invalid password') ?>.";
            var mis = "<?= trans('Your password is required') ?>.";
        </script>
    </body>
</html>
