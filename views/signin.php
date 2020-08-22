<?php $this->view('header.php', $data); ?>

<div id="signin_form">

    <div style="min-height: 50px;" class="header">
        <h1 class="pull_left" style=" margin: 0; line-height: 25px;">Sign In</h1>
        <div class="pull_right">
            <a href="?action=locale&page=signin&language=ru">
                <img src="../public/images/ru.png"/>
            </a> <a href="?action=locale&page=signin&language=en"><img src="../public/images/uk.png"/></a>
        </div>
    </div>
    <?php if (isset($error) && $error): ?>
        <div class="alert_error">
            <?= $error ?>
        </div>
    <?php endif; ?>
    <form method="post" action="">
        <div class="form_element">
            <label for="email"><?= _t('username/email') ?></label>
            <input type="text" name="username" id="username" />
        </div>
        <div class="form_element">
            <label for="password"><?= _t('password') ?></label>
            <input type="password" name="password" id="password" />
        </div>
        <div class="form_element">
            <input class="btn" type="submit" value="Sign In" />
            <a href="?action=signup">Not a member yet?</a>
        </div>
    </form>
</div>

<script type="text/javascript">
    function init() {
        var input = document.getElementById('email');
        input.focus();
    }
</script>

<?php $this->view('footer.php', $data); ?>
    