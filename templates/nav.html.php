<!-- nav -->
<nav class="navbar navbar-dark bg-dark navbar-expand-lg">
    <span class="navbar-brand">
        <img src="public/icon_600.png" width="60" class="d-inline-block align-middle" alt="" loading="lazy">
        &nbsp;&nbsp;<?= trans('My Blues') ?>
    </span>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <?php if (isset($with_return) && $with_return): ?>
            <ul class="navbar-nav">
                <li class="nav-item"><a href="index.php" class="nav-link"><i class="fa fa-reply"></i> <?= trans('Return') ?></a></li>
            </ul>
        <?php endif; ?>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user-circle"></i> <?= trans('Hello').', ' . $session->getUserName() ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="my-account.php">
                        <i class="fa fa-cog"></i>
                        <?= trans('My account') ?>
                    </a>
                    <a class="dropdown-item" href="logout.php">
                        <i class="fa fa-sign-out-alt"></i>
                        <?= trans('Logout') ?>
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
