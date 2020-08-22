<?php $this->view('header.php', $data); ?>

<section class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active">Book - tasks</li>
    </ol>

    <div style="margin-top: 20px;margin-bottom: 20px;">
        <div class="clearfix">
            <a class="btn btn-primary btn-lg col-md-3" href="?action=taskCreate">Create</a>
        </div>
    </div>

    <br>

    <form id="task-sort" method="post">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-2"><label style="margin-top: 5px;" for="sort">Sort By:</label></div>
                    <div class="col-md-10">
                        <form method="post">
                            <select onchange="$(this).closest('form').submit();" class="form-control" name="sort" required="required">
                                <option value="id">Id</option>
                                <option <?= $sort == 'email' ? 'selected="selected"' : ''; ?> value="email">Email</option>
                                <option <?= $sort == 'text' ? 'selected="selected"' : ''; ?> value="text">Text</option>
                                <option <?= $sort == 'status' ? 'selected="selected"' : ''; ?> value="status">Status</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Email</th>
            <th>Text</th>
            <th colspan="2">Status</th>
        </tr>
        </thead>
        <?php if(isset($tasks) && !empty($tasks)): ?>
            <tbody>
            <?php foreach($tasks as $task): ?>
                <tr>
                    <th scope="row"><?= $task['id']; ?></th>
                    <td><?= $task['email']; ?></td>
                    <td><?= $task['text']; ?></td>
                    <td><?= $task['status'] == 1 ? 'Active' : 'No Active'; ?></td>
                    <td>
                        <a class="btn btn-info col-md-5" href="?action=taskUpdate&id=<?= $task['id']; ?>">Update</a>
                        <a class="btn btn-danger col-md-5 pull-right" href="?action=taskDelete&id=<?= $task['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        <?php endif; ?>
    </table>
</section>

<?php $this->view('footer.php', $data); ?>