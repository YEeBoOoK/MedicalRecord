<?php
namespace app\controllers;
use app\models\Patient;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

class PatientController extends ActiveController
{
public $modelClass = 'app\models\Patient';
    public function behaviors()
    {
        /*
         * Указание на аутентификации по токену
         */
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only'=>['account'] //Перечислите для контроллера методы, требующие аутентификации
            //здесь метод actionAccount()
        ];
        return $behaviors;
    }

    /*Регистрация пользователя*/
    public function actionCreate(){
        $request=Yii::$app->request->post(); //получение данных из post запроса
        $patient=new Patient($request); // Создание модели на основе присланных данных
        if (!$patient->validate()) return $this->validation($patient); //Валидация модели
        $patient->password=Yii::$app->getSecurity()->generatePasswordHash($patient->password); //хэширование пароля
        $patient->save();//Сохранение модели в БД
        return $this->send(201, ['content'=>['code'=>201, 'message'=>'Вы зарегистрировались']]);//Отправка сообщения пользователю
    }
}