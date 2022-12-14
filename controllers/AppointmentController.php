<?php
namespace app\controllers;
use app\models\Appointment;
use app\models\Patient;
use app\models\Doctor;
use yii\rest\ActiveController;
use app\controllers\FunctionController;
use Yii;
use yii\filters\auth\HttpBearerAuth;

class AppointmentController extends FunctionController
{
public $modelClass = 'app\models\Appointment';

    public function behaviors()
    {
        /*
         * Указание на аутентификации по токену
         */
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['add','record','show'] //Перечислите для контроллера методы, требующие аутентификации
        ];
        return $behaviors;
    }

    /*Добавление доков*/
    public function actionAdd()
    {
        if (!$this->is_admin())
            return $this->send(401, ['content' => ['code' => 401, 'message' => 'Вы не являетесь администратором']]);
        $request = Yii::$app->request->post(); //получение данных из post запроса
        $appointment = new Appointment($request); // Создание модели на основе присланных данных
        if (!$appointment->validate()) return $this->validation($appointment); //Валидация модели
        $appointment->save();//Сохранение модели в БД
        return $this->send(200, ['content' => ['code' => 200, 'message' => 'Талон добавлен']]);
    }

    public function actionRecord()
    { //запись к врачу
        //Проверка id_Appointment и на какую дату хочет
        $patient = Yii::$app->user->identity;
        $request = Yii::$app->request->post();
        $doctor = Doctor::find();
        if (isset($request['id_appointment'])) $id_appointment = $request['id_appointment']; else return $this->send(404, ['content' => ['code' => 404, 'message' => 'Талон не найден']]);
        $appointment = Appointment::findOne(['id_appointment' => $id_appointment]);
       // if (!$appointment) return $this->send(404, ['content' => ['code' => 404, 'message' => 'Талон не найден']]);
       // $appointment->seats = -1;

        $appointment = new Appointment();// Создание модели на основе присланных данных
        $appointment->id_patient = $patient->id_patient;
        $appointment->id_doctor = $doctor->id_doctor;
        if (isset($request['acceptance_date'])) $appointment->acceptance_date = $request['acceptance_date']; else return $this->send(422, ['content' => ['code' => 422, 'message' => 'Выберите дату']]);


        if (!$appointment->validate()) return $this->validation($appointment); //Валидация модели
        $appointment->save();//Сохранение модели в БД
        return $this->send(200, ['content' => ['Appointment' => $doctor,'message' => 'Вы записались к врачу', 'Код записи' => $appointment->id_appointment]]);//Отправка сообщения пользователю

    }

    public function actionShow()
    {
        $patient = Yii::$app->user->identity; //массив пользователя

        $appointment = Appointment::findAll([
            'id_patient'=>$patient->id_patient
        ]);

        return $this->send(200, ['Appointments' => $appointment]);
    }
}
