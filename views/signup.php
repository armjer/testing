<?php
$this->view('header.php', array_merge($data, [
    'styles' => [
        '../public/jquery.ketchup/css/jquery.ketchup.css' => 'rel="stylesheet" type="text/css" media="screen"',
    ],
    'scripts' => [
        '../public/jquery.ketchup/js/jquery.js' => 'type="text/javascript"',
        '../public/jquery.ketchup/js/jquery.ketchup.js"' => 'type="text/javascript"',
        '../public/jquery.ketchup/js/jquery.ketchup.validations.js' => 'type="text/javascript"',
        '../public/jquery.ketchup/js/jquery.ketchup.helpers.js"' => 'type="text/javascript"',
        '../public/jquery.ketchup/js/scaffold.js"' => 'type="text/javascript"',
    ],
]));
?>
    <div id="signup_form">
        <div class="header">
            <h1 style="margin-left: 140px;"><?= _t('signup') ?></h1>

            <div class="pull_right">
                <a href="?action=locale&page=signup&language=ru">
                    <img src="../public/images/ru.png"/>
                </a> <a href="?action=locale&page=signup&language=en"><img src="../public/images/uk.png"/></a>
            </div>
        </div>
        <?php if (isset($error) && $error): ?>
            <div class="alert_error">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <form enctype="multipart/form-data" id="registration" method="post" action="" onsubmit="return validator.validateForm(document.getElementById('registration'));">
            <div class="form_element">
                <label><?= _t('first_name') ?> *</label>
                <input type="text" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>" name="firstname"  data-validate="validate(required, minlength(3))"
                       onchange="validator.validateField(this);"/>
            </div>
            <div class="form_element">
                <label><?= _t('last_name') ?> *</label>
                <input type="text" value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : ''; ?>" name="lastname"  data-validate="validate(required, minlength(3))"
                       onchange="validator.validateField(this);"/>
            </div>
            <div class="form_element">
                <label><?= _t('email') ?> *</label>
                <input type="text" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>" name="email" id="email" data-validate="validate(required, email)"
                       onchange="validator.validateField(this);"/>            </div>
            <div class="form_element">
                <label><?= _t('username') ?> *</label>
                <input type="text" value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>" name="username"  data-validate="validate(required, minlength(3))"
                       onchange="validator.validateField(this);"/>
            </div>
            <div class="form_element">
                <label><?= _t('password') ?> *</label>
                <input type="password" name="password" id="password" onchange="validator.validateField(this);" data-validate="validate(required, minlength(6))" />
            </div>
            <div class="form_element">
                <label><?= _t('confirm_password') ?> *</label>
                <input type="password" name="confirmpassword"  data-validate="validate(required, match_password(#password))"
                       onchange="validator.validateField(this, document.getElementsByName('password')[0]);"/>
            </div>
            <div class="form_element">
                <label><?= _t('birthday') ?> * </label>
                <select name="birthday_month"  id="birthday_month"  data-validate="validate(birthday)"
                        onchange="validator.validateBirthday(this, document.getElementsByName('birthday_day')[0], document.getElementsByName('birthday_year')[0]);">
                    <option value="0"><?= _t('month') ?></option>
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <select name="birthday_day"  id="birthday_day"  data-validate="validate(birthday)"
                        onchange="validator.validateBirthday(document.getElementsByName('birthday_month')[0], this, document.getElementsByName('birthday_year')[0]);">
                    <option value="0"><?= _t('day') ?></option>
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <select name="birthday_year" id="year"   data-validate="validate(birthday)"
                        onchange="validator.validateBirthday(this, document.getElementsByName('birthday_day')[0], document.getElementsByName('birthday_month')[0], this);">
                    <option value="0"><?= _t('year') ?></option>
                    <?php for ($i = 2013; $i >= 1900; $i--): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form_element">
                <label><?= _t('gender') ?> *</label>
                <input type="radio" name="gender" value="1" checked="checked"/> <?= _t('male') ?>
                <input type="radio" name="gender" value="2"/> <?= _t('female') ?>
            </div>
            <div class="form_element">
                <label><?= _t('profile_picture') ?></label>
                <input type="file" name="image[]" value=""/>
            </div>
            <div class="form_element">
                <input type="submit" class="btn" value="<?= _t('signup') ?>"/>
            </div>
        </form>
        <script type="text/javascript">
            LOCALE = "<?= isset($LOCALE) && $LOCALE ? json_encode($LOCALE) : ''; ?>";
            validator = new Validator();
        </script>
    </div>

    <script>
        $('#registration').ketchup();
    </script>
<?php $this->view('footer.php', $data); ?>