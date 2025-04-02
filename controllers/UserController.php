<?php

namespace app\controllers;

use app\models\Category;
use app\models\Task;
use app\models\User;
use Yii;
use yii\web\NotFoundHttpException;

class UserController extends \yii\web\Controller
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
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("Пользователь #$id не найден.");
        }

        return $this->render('view', ['model' => $user]);
    }
}
