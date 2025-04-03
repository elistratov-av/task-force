<?php

namespace app\controllers;

use app\models\Category;
use app\models\Opinion;
use app\models\Reply;
use app\models\Task;
use Yii;
use yii\web\NotFoundHttpException;

class TasksController extends SecuredController
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

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($id): string
    {
        $task = Task::findOne($id);
        if (!$task) {
            throw new NotFoundHttpException("Задача #$id не найдена.");
        }
        $reply = new Reply;
        $opinion = new Opinion;

        return $this->render('view', ['model' => $task, 'newReply' => $reply, 'opinion' => $opinion]);
    }
}
