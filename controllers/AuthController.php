<?php

namespace app\controllers;

use app\models\City;
use app\models\User;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AuthController extends \yii\web\Controller
{
    public function actionSignup()
    {
        $user = new User(['scenario' => 'insert']);
        $cities = City::find()->orderBy('name')->all();

        if (Yii::$app->request->getIsPost()) {
            $user->load(Yii::$app->request->post());

            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($user);
            }

            if ($user->validate()) {
                $user->password = Yii::$app->security->generatePasswordHash($user->password);

                $user->save(false);
                $this->goHome();
            }
        }

        return $this->render('signup', ['model' => $user, 'cities' => $cities]);
    }
}