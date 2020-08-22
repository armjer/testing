<?php

namespace App\Models;

use PDO;

/**
 * Class TasksBook
 */
class TasksBook
{
    private $_rules = [
        'email, text' => ['required', 'message' => '%s cannot be blank.'],
        'email' => ['email', 'min' => 3, 'max' => 255, 'message' => '%s please fill out this  field.'],
        'text' => ['string', 'min' => 3, 'max' => 255, 'message' => '%s cannot be blank.'],
        'image' => ['file', 'types' => ['image/png', 'image/gif', 'image/jpg', 'image/jpeg'], 'message' => '%s error file.'],
    ];
    private $tabelName = 'task_book';
    private $pk = "id";
    private $attributes = [
        'user_id' => 0,
        'status' => 0
    ];
    private $only = ['user_id', 'email', 'text', 'image'];
    public $validator;

    private $data = [];
    public $imageSize = ['width' => 320, 'height' => 240];


    public function __construct()
    {
        $this->validator = new FormValidation();
        $this->validator->setRules($this->_rules);
        $this->attributes['user_id'] = UserManager::getInstance()->getUserId();

        if (UserManager::getInstance()->getCurrentUser()->isAdmin()) {
            $this->only = array_merge($this->only, ['status']);
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function load($data)
    {
        $this->data = $data;
        $this->validator->setData($data);
    }

    public function getTasks($all = false, $sort = 'id')
    {
        if ($all) {
            $query = "SELECT * FROM `" . $this->tabelName . "` ORDER BY `" . $sort . "` ASC;";
        } else {
            $query = "SELECT * FROM `" . $this->tabelName . "` WHERE `user_id`=? ORDER BY `" . $sort . "` ASC;";
        }

        $pdo = Database::getInstance();
        $st = $pdo->prepare($query);
        $st->execute(array($this->attributes['user_id']));

        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTaskById($id, $all = false)
    {
        $pdo = Database::getInstance();

        if ($all) {
            $query = "SELECT * FROM `" . $this->tabelName . "` WHERE `id`=?;";
            $st = $pdo->prepare($query);
            $st->execute(array($id));
        } else {
            $query = "SELECT * FROM `" . $this->tabelName . "` WHERE `id`=? AND `user_id`=?;";
            $st = $pdo->prepare($query);
            $st->execute(array($id, $this->attributes['user_id']));
        }

        return $st->fetch(PDO::FETCH_ASSOC);
    }

    public function validation()
    {
        return $this->validator->validation();
    }

    public function save($insert = true, $id = 0)
    {
        $data = $this->getData();
        $attributes = array_merge($this->attributes, $data);
        $only = $this->only;
        $attrKeys = [];
        $attrValues = [];
        $upAttrs = [];

        if (empty($data) || empty($only)) return false;

        $n = 0;
        foreach ($only as $attrName) {
            $attribute = isset($attributes[$attrName]) ? $attributes[$attrName] : '';
            $attrKeys[$attrName] = '?';
            $attrValues[$attrName] = $attribute;
            $upAttrs[$n] = '`' . $attrName . '`=?';
            $n++;
        }

        if ($insert) {
            $query = "INSERT INTO `" . $this->tabelName . "` (" . implode(',', $only) . ") VALUES (" . implode(',', $attrKeys) . ");";
        } else {
            $query = "UPDATE `" . $this->tabelName . "` SET " . implode(',', $upAttrs) . " WHERE `" . $this->pk . "`='" . $id . "';";
        }

        $pdo = Database::getInstance();
        $st = $pdo->prepare($query);
        if ($st->execute(array_values($attrValues))) {
            return $pdo->lastInsertId();
        }

        return 0;
    }

    public function delete($id)
    {
        $task = $this->getTaskById($id);
        if (!empty($task)) {
            $pdo = Database::getInstance();
            $query = "DELETE FROM `" . $this->tabelName . "` WHERE `" . $this->pk . "`=?;";
            $st = $pdo->prepare($query);

            $this->deleteImage($task['image']);

            return $st->execute(array($id));
        }

        return false;
    }

    public function deleteImage($name)
    {
        $old_image = UPLOADS_PATH . "tasks/$name";
        if (is_file($old_image)) {
            unlink($old_image);
        }
    }

    public function upload($old_image_name = null)
    {
        if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
            if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["image"]["tmp_name"];
                // basename() может спасти от атак на файловую систему;
                // может понадобиться дополнительная проверка/очистка имени файла
                $name = basename($_FILES["image"]["name"]);
                $file = UPLOADS_PATH . "tasks/$name";
                if (is_file($file)) {
                    $name = uniqid() . "_" . $name;
                    $file = UPLOADS_PATH . "tasks/$name";
                }
                if (move_uploaded_file($tmp_name, $file)) {
                    $image = new SimpleImage();
                    $image->load($file);
                    $image->resizeTo($this->imageSize['width'], $this->imageSize['height']);
                    $image->save($file);

                    $this->deleteImage($old_image_name);

                    $this->data['image'] = $name;
                    return true;
                }
                return false;
            }
        }
        $this->data['image'] = $old_image_name;
        return true;
    }
}