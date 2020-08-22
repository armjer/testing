<?php
$this->view('header.php', array_merge($data, [
    'styles' => [
        '../public/jquery.ketchup/css/jquery.ketchup.css' => 'rel="stylesheet" type="text/css" media="screen"',
        '//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css' => 'rel="stylesheet"',
    ],
    'scripts' => [
        '../public/jquery.ketchup/js/jquery.js' => 'type="text/javascript"',
        '../public/jquery.ketchup/js/jquery.ketchup.js"' => 'type="text/javascript"',
        '../public/jquery.ketchup/js/jquery.ketchup.validations.js' => 'type="text/javascript"',
        '../public/jquery.ketchup/js/jquery.ketchup.helpers.js"' => 'type="text/javascript"',
        '../public/jquery.ketchup/js/scaffold.js"' => 'type="text/javascript"',
        '//code.jquery.com/ui/1.11.1/jquery-ui.js"' => 'type="text/javascript"',
    ],
]));
?>

<section class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>

    <div id="profile_leftbox">
        <div class="header">
            <h1><?= _t('Profile') ?>, <?= $user->getFirstname() . ' ' . $user->getLastname() ?></h1>
        </div>
        <div class="body">
            <?php
            if ($user->getPicture()) {
                $style = ' style="background-image:url(' . url('../public/uploads/' . $user->getPicture()) . ')"';
            }
            ?>
            <div id="profile_picture"<?php echo isset($style) ? $style : '' ?>></div>
            <div id="profile_info">
               <section class="col-md-12">
                   <div class="row">
                       <div class="col-md-6">
                           <p><i><?php echo $age ?> <?= _t('years_old') ?></i></p><br/>

                           <p><?= _t('birthday') ?>: <b><?php echo $birthdate ?></b></p><br/>
                       </div>
                       <div class="col-md-6">
                           <p><?= _t('gender') ?>: <b><?php echo $user->getGender() == 1 ? _t('male') : _t('female'); ?></b></p><br/>

                           <p><?= _t('email') ?>: <b><?= $user->getEmail() ?></b></p><br/>
                       </div>
                   </div>
               </section>
            </div>
        </div>
    </div>

    <form enctype="multipart/form-data" id="default-behavior" method="post" action="?action=profile">
        <ul id="sortable">
            <li class="ui-state-default">
                <label for="email"><?= _t('email') ?></label> <br>
                <input type="text" name="email" id="email" data-validate="validate(required, email)" value="<?= $user->getEmail() ?>"/>
            </li>
            <li class="ui-state-default">
                <label for="date"><?= _t('first_name') ?></label> <br>
                <input type="text" id="name" name="name" data-validate="validate(required, minlength(3))" value="<?= $user->getFirstname() ?>"/>
            </li>
        </ul>
        <div class="form-group">
            <input style="margin-top: 15px;" class="btn btn-primary col-md-3" type="submit" value="<?= _t('save') ?>"/>
        </div>
    </form>
</section>

<script>
    $(function () {
        $('#default-behavior').ketchup();
        $("#sortable").sortable();
        $("#sortable").disableSelection();
        $("#datepicker").datepicker();
    });
</script>

<?php $this->view('footer.php', $data); ?>
    