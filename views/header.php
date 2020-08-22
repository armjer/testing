<!DOCTYPE html>
<head>
    <title>Test</title>

    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script type="text/javascript" src="../public/jquery.min.js"></script>
    <script type="text/javascript" src="../public/validator.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link href="../public/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <?php
    if (isset($data['styles']) && !empty($data['styles'])) {
        foreach ($data['styles'] as $sKey => $sValue) {
            printf('<link href="%s" %s />', $sKey, $sValue);
        }
    }
    if (isset($data['scripts']) && !empty($data['scripts'])) {
        foreach ($data['scripts'] as $jsKey => $jsValue) {
            printf('<script src="%s" %s></script>', $jsKey, $jsValue);
        }
    }
    ?>
</head>
<body>

<?php
$action = isset($_GET['action']) && $_GET['action'] ? $_GET['action'] : '/'
?>

<header style="margin-bottom: 30px;" role="banner">
    <nav class="navbar navbar-expand-lg navbar-light bg-light rounded">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample09" aria-controls="navbarsExample09" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample09">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?= $action == '/' ? 'active' : ''; ?>">
                    <a class="nav-link" href="/"><span><?= _t('Home') ?></span></a>
                </li>

                <?php if (\App\Models\UserManager::getInstance()->isUserLogged()): ?>
                    <li class="nav-item <?= $action == 'profile' ? 'active' : ''; ?>">
                        <a class="nav-link" href="?action=profile"><span><?= _t('Profile') ?></span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="http://example.com" id="dropdown09" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?= _t('task_book') ?></a>
                        <div class="dropdown-menu" aria-labelledby="dropdown09">
                            <a class="dropdown-item" href="?action=taskIndex"><?= _t('book_tasks') ?></a>
                            <a class="dropdown-item" href="?action=taskCreate">Create</a>
                        </div>
                    </li>
                <?php else: ?>
                    <li class="<?= $action == 'signin' ? 'active' : ''; ?>">
                        <a class="nav-link" href="?action=signin"><span><?= _t('signin') ?></span></a>
                    </li>
                    <li class="<?= $action == 'signup' ? 'active' : ''; ?>">
                        <a class="nav-link" href="?action=signup"><span><?= _t('signup') ?></span></a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a class="nav-link" href="?action=locale&page=signup&language=ru"><img src="../public/images/ru.png"/></a></li>
                <li><a class="nav-link" href="?action=locale&page=signup&language=en"><img src="../public/images/uk.png"/></a></li>
                <li class="active">
                    <?php if (\App\Models\UserManager::getInstance()->isUserLogged()): ?>
                        <a class="nav-link" href="?action=logout"><span><?= _t('logout') ?></a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </nav>
</header>