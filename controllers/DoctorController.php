<?php
namespace app\controllers;

use app\models\Doctor;
use app\models\LoginForm;
use Yii;
use app\controllers\FunctionController;
use yii\filters\auth\HttpBearerAuth;
use function PHPUnit\Framework\returnArgument;
use yii\rest\ActiveController;
class DoctorController extends FunctionController
{
public $modelClass = 'app\models\Doctor';

    public function behaviors()
    {
        /*
         * Указание на аутентификации по токену
         */
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['add', 'del', 'red', 'alldoctor'], //Перечислите для контроллера методы, требующие аутентификации

        ];
        return $behaviors;
    }

    /*Просмотр докторов*/
    public function actionDoctor()
    {
        $doctor = Doctor::find()
            ->orderBy('id_doctor')
            ->all();
        return $this->send(200, ['Doctors' => $doctor]);
    }

    public function actionAllDoctor()
    {
        if (!$this->is_admin())
            return $this->send(401, ['content' => ['code' => 401, 'message' => 'Вы не являетесь администратором']]);
        $doctor = Doctor::find()
            ->IndexBy('id_doctor')
            ->all();
        return $this->send(200, ['Doctor' => $doctor]);
    }

    /*Добавление доков*/
    public function actionAdd()
    {
        if (!$this->is_admin())
            return $this->send(401, ['content' => ['code' => 401, 'message' => 'Вы не являетесь администратором']]);
        $request = Yii::$app->request->post(); //получение данных из post запроса
        $doctor = new Doctor($request); // Создание модели на основе присланных данных
        if (!$doctor->validate()) return $this->validation($doctor); //Валидация модели
        $doctor->save();//Сохранение модели в БД
        return $this->send(200, ['content' => ['code' => 200, 'message' => 'Специалист добавлен']]);
    }

    /*Удалить дока*/
    public function actionDel($id_doctor)
    {
        $doctor = Doctor::findOne($id_doctor);
        if (!$doctor) return $this->send(404, ['content' => ['code' => 404, 'message' => 'Специалист не найден']]);

        if (!$this->is_admin())
            return $this->send(401, ['content' => ['code' => 401, 'message' => 'Вы не являетесь администратором']]);
        $doctor = Doctor::findOne($id_doctor);
        $doctor->delete();
        return $this->send(200, ['content' => ['Status' => 'ok']]);
    }

    /*Редактировать данные дока*/
    public function actionRed($id_doctor)
    {
        if (!$this->is_admin())
            return $this->send(403, ['content' => ['code' => 403, 'message' => 'Вы не являетесь администратором']]);

        $request = Yii::$app->request->getBodyParams();
        $doctor = Doctor::findOne($id_doctor);
        if (!$doctor) return $this->send(404, ['content' => ['code' => 404, 'message' => 'Специалист не найден']]);

        if (isset($request['doctor_photo'])) $doctor->doctor_photo = $request['doctor_photo'];
        if (isset($request['doctor_surname'])) $doctor->doctor_surname = $request['doctor_surname'];
        if (isset($request['doctor_name'])) $doctor->doctor_name = $request['doctor_name'];
        if (isset($request['doctor_patronymic'])) $doctor->doctor_patronymic = $request['doctor_patronymic'];
        if (isset($request['doctor_office'])) $doctor->doctor_office = $request['doctor_office'];
        if (isset($request['start_date'])) $doctor->start_date = $request['start_date'];
        if (isset($request['weekday'])) $doctor->weekday = $request['weekday'];
        if (isset($request['office_hours'])) $doctor->office_hours = $request['office_hours'];

        if (!$doctor->validate()) return $this->validation($doctor);
        $doctor->save();
        return $this->send(200, ['content' => ['code' => 200, 'message' => 'Данные обновлены']]);
    }
}