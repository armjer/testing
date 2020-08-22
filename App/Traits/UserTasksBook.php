<?php

namespace App\Traits;

use App\Models\TasksBook;
use App\Models\UserManager;

trait UserTasksBook
{
    function taskIndex()
    {
        if (!UserManager::getInstance()->isUserLogged()) {
            redirectTo('signin');
        }

        $model = new TasksBook();

        $sort = 'id';
        if (isset($_POST['sort']) && in_array($_POST['sort'], ['id', 'email', 'text', 'status'])) {
            $sort = $_POST['sort'];
        }

        $tasks = $model->getTasks(UserManager::getInstance()->isAdmin(), $sort);

        $this->load->view('task-index.php', array('rec' => $this->data, 'tasks' => $tasks, 'sort' => $sort));
    }

    function taskDelete()
    {
        if (!UserManager::getInstance()->isUserLogged()) {
            redirectTo('signin');
        }

        if (isset($_GET['id'])) {
            $model = new TasksBook();
            $model->delete($_GET['id']);
        }

        redirectTo('taskIndex');
    }

    function taskCreate()
    {
        if (!UserManager::getInstance()->isUserLogged()) {
            redirectTo('signin');
        }

        $model = new TasksBook();

        if (isset($_POST) && !empty($_POST)) {
            $model->load($_POST);
            if ($model->validation()) {
                if ($model->upload() && $lastInsertId = $model->save()) {
                    redirectTo('taskUpdate&id=' . $lastInsertId);
                }
            }
        }

        $errors = $model->validator->getErrors();

        $this->load->view('task-create.php', array('rec' => $this->data, 'errors' => $errors));
    }

    function taskUpdate()
    {
        if (!UserManager::getInstance()->isUserLogged()) {
            redirectTo('signin');
        }

        if (!isset($_GET['id'])) {
            redirectTo('taskCreate');
        }

        $model = new TasksBook();
        $task = $model->getTaskById($_GET['id'], UserManager::getInstance()->isAdmin());

        if (empty($task)) {
            redirectTo('taskCreate');
        }

        if (isset($_POST) && !empty($_POST)) {
            $model->load($_POST);
            if ($model->validation()) {
                $model->upload($task['image']);
                $model->save(false, $task['id']);
                refresh();
            }
            $task = array_merge($task, $_POST);
        }

        $errors = $model->validator->getErrors();

        $this->load->view('task-update.php', array('rec' => $this->data, 'errors' => $errors, 'task' => $task));
    }
}