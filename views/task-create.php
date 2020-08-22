<?php $this->view('header.php', array_merge($data, [
    'scripts' => [
        '../public/task.js' => 'type="text/javascript"',
    ]
])); ?>

<section class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="?action=taskIndex">Book - tasks</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>

    <?php $this->view('task-form.php', array_merge($data, ['formData' => isset($_POST) ? array_merge(['status' => 0], $_POST) : ''])); ?>
</section>

<?php $this->view('footer.php', $data); ?>