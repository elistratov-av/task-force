<?php

namespace app\controllers;

use app\models\Task;

class TasksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $tasks = Task::findAll(['status_id' => 1]);

        return $this->render('index', ['models' => $tasks]);
    }
}