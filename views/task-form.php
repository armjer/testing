<form id="task-form" method="post" enctype="multipart/form-data">
    <?php if(!empty($errors)): ?>
        <ul style="list-style: none;" class="alert alert-danger">
            <?php foreach($errors as $error): ?>
                <li><?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if(\App\Models\UserManager::getInstance()->isAdmin()): ?>
        <div class="form-group">
            <label for="task-status">Status:</label>
            <select required="required" id="task-status" name="status" class="form-control">
                <option value="0">No Active</option>
                <option <?= $formData['status'] == 1 ? 'selected="selected"' : ''; ?> value="1">Active</option>
            </select>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="task-email">Email:</label>
        <input type="email" name="email" value="<?= isset($formData['email']) ? $formData['email'] : ''; ?>" id="task-email" class="form-control" placeholder="example@mail.ru" required="required">
    </div>

    <div class="form-group">
        <label for="task-text">Text:</label>
        <textarea name="text" id="task-text" class="form-control" rows="5" minlength="3" required="required"><?= isset($formData['text']) ? $formData['text'] : ''; ?></textarea>
    </div>

    <?php if(isset($formData['image']) && $formData['image']): ?>
        <div class="form-group">
            <img src="../public/uploads/tasks/<?= $formData['image']; ?>">
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="task-text">Image:</label>
        <input type="file" name="image" id="task-image" class="form-control" accept="image/jpg, image/gif, image/png">
    </div>

    <div class="form-group">
        <div class="row">
            <div class="btn-group col-md-6 col-md-offset-6">
                <input id="form-save" type="submit" class="btn btn-primary col-md-6" value="<?= _t('create_task') ?>">
                <input id="preview-task" data-toggle="modal" data-target="#myModal" type="button" class="btn btn-info col-md-6" value="<?= _t('preview') ?>">
            </div>
        </div>
    </div>
</form>







<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <?php if(!empty($errors)): ?>
                    <ul style="list-style: none;" class="alert alert-danger">
                        <?php foreach($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="form-group">
                    <label for="tpreview-ask-status">Status:</label>
                    <select disabled="disabled" id="preview-task-status" class="form-control">
                        <option value="0">No Active</option>
                        <option <?= $formData['status'] == 1 ? 'selected="selected"' : ''; ?> value="1">Active</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="preview-task-email">Email:</label>
                    <input disabled="disabled" type="email" name="email" value="<?= isset($formData['email']) ? $formData['email'] : ''; ?>" id="preview-task-email" class="form-control" placeholder="example@mail.ru" required="required">
                </div>

                <div class="form-group">
                    <label for="preview-task-text">Text:</label>
                    <textarea disabled="disabled" name="text" id="preview-task-text" class="form-control" rows="5" min="3" required="required"><?= isset($formData['text']) ? $formData['text'] : ''; ?></textarea>
                </div>

                <div id="preview-image" class="form-group">
                    <?php if(isset($formData['image']) && $formData['image']): ?>
                        <img src="../public/uploads/tasks/<?= $formData['image']; ?>">
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>