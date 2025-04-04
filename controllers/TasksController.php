<?php

namespace app\controllers;

use app\models\Category;
use app\models\File;
use app\models\Opinion;
use app\models\Reply;
use app\models\Task;
use Yii;
use yii\web\NotFoundHttpException;
use Yii\web\Response;
use yii\web\UploadedFile;

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

    public function actionCreate()
    {
        $task = new Task();
        $categories = Category::find()->all();

        if (!Yii::$app->session->has('task_uid')) {
            $uid = uniqid('upload');
            Yii::$app->session->set('task_uid', $uid);
        }

        if (Yii::$app->request->isPost) {
            $task->load(Yii::$app->request->post());
            $task->uid = Yii::$app->session->get('task_uid');
            $task->save();

            if ($task->id) {
                Yii::$app->session->remove('task_uid');
                return $this->redirect(['tasks/view', 'id' => $task->id]);
            }
        }

        return $this->render('create', ['model' => $task, 'categories' => $categories]);
    }

    public function actionUpload()
    {
        if (Yii::$app->request->isPost) {
            $model = new File();
            $model->task_uid = Yii::$app->session->get('task_uid');
            $model->file = UploadedFile::getInstanceByName('file');

            if (!$model->upload()) {
                return $this->renderDropzoneErrors($model->getErrors(), 406);
            }

            return $this->asJson($model->getAttributes());
        }
    }

    private function renderDropzoneErrors($errors, $statusCode) {
        Yii::$app->response->statusCode = $statusCode;
        Yii::$app->response->format = Response::FORMAT_RAW;
        $res = "";
        foreach ($errors as $errs) {
            $res .= ">> " . implode(" || ", $errs) . "\n";
        }
        return $res;
    }
}
