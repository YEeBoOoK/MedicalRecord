<?php
namespace app\controllers;
use app\models\Patient;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use app\models\LoginForm;
use app\controllers\FunctionController;
use function PHPUnit\Framework\returnArgument;

class PatientController extends FunctionController
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
        $patient->save(false);//Сохранение модели в БД
        return $this->send(201, ['content'=>['code'=>201, 'message'=>'Вы зарегистрировались']]);//Отправка сообщения пользователю
    }

    /*
    * Аутентификация пользователя
     * * Производится на основе модели loginForm со свойствами email и password
     * */

    public function actionLogin(){
        $request=Yii::$app->request->post();//Здесь не объект, а ассоциативный массив
        $loginForm=new LoginForm($request);
        if (!$loginForm->validate()) return $this->validation($loginForm);
        $patient=Patient::find()->where(['login'=>$request['login']])->one();
        if (isset($patient) && Yii::$app->getSecurity()->validatePassword($request['password'], $patient->password)){
            $patient->token=Yii::$app->getSecurity()->generateRandomString();
            $patient->save(false);
            return $this->send(200, ['content'=>['token'=>$patient->token]]);
        }
        return $this->send(401, ['content'=>['code'=>401, 'message'=>'Неверный логин или пароль']]);
    }
    /*
     * Личный кабинет пользователя
     */
    public function actionAccount(){
        $patient=Yii::$app->user->identity; // Получить идентифицированного пользователя
        return $this->send(200, ['content'=> ['patient'=>$patient]]);
    }


}