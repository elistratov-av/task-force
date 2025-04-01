<?php

namespace app\controllers;

use app\models\Category;
use app\models\Task;
use Yii;

class TasksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $task = new Task();
        $task->load(Yii::$app->request->post());

        $tasksQuery = $task->getSearchQuery();
        $categories = Category::find()->all();
        $tasks = $tasksQuery->all();

        return $this->render('index', ['models' => $tasks, 'task' => $task, 'categories' => $categories]);
    }
}