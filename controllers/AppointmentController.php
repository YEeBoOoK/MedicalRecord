<?php
namespace app\controllers;
use app\models\Patient;
use app\models\Doctor;
use app\models\LoginForm;
use Yii;
use app\controllers\FunctionController;
use yii\filters\auth\HttpBearerAuth;
use function PHPUnit\Framework\returnArgument;

use app\models\Appointment;
use yii\rest\ActiveController;

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
            'only' => ['add','del','red','record','show'] //Перечислите для контроллера методы, требующие аутентификации
        ];
        return $behaviors;
    }

    /*Просмотр свободных талонов*/
    public function actionAppointment()
    {
        $appointment = Appointment::find()
            ->where(['id_patient' => null])
            ->orderBy('id_appointment')
            ->all();
        return $this->send(200, ['Свободные талончики' => $appointment]);
    }


    /*Просмотр талонов пользователем*/
    public function actionShow()
    {
        $patient = Yii::$app->user->identity; //массив пользователя

        $appointment = Appointment::findAll([
            'id_patient'=>$patient->id_patient
        ]);

        return $this->send(200, ['Ваши записи' => $appointment]);
    }

    /*Добавление талонов админом*/
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

    /*Редактирование талонов админом*/
    public function actionRed($id_appointment)
    {
        if (!$this->is_admin())
            return $this->send(403, ['content' => ['code' => 403, 'message' => 'Вы не являетесь администратором']]);

        $request = Yii::$app->request->getBodyParams();
        $appointment = Appointment::findOne($id_appointment);
        if (!$appointment) return $this->send(404, ['content' => ['code' => 404, 'message' => 'Запись не найдена']]);

        if (isset($request['id_patient'])) $appointment->id_patient = $request['id_patient'];
        if (isset($request['id_doctor'])) $appointment->id_doctor = $request['id_doctor'];
        if (isset($request['acceptance_date'])) $appointment->acceptance_date = $request['acceptance_date'];
        if (isset($request['acceptance_time'])) $appointment->acceptance_time = $request['acceptance_time'];
        if (isset($request['id_clinic '])) $appointment->id_clinic = $request['id_clinic'];

        if (!$appointment->validate()) return $this->validation($appointment);
        $appointment->save();
        return $this->send(200, ['content' => ['code' => 200, 'message' => 'Данные обновлены']]);
    }


    public function actionRecord($id_appointment)
    { //запись к врачу
        //Проверка id_Appointment и на какую дату хочет

        $patient = Yii::$app->user->identity;
        if (!$id_appointment) return $this->send(404, ['content' => ['code' => 404, 'message' => 'Запись не найдена']]);
        $appointment = Appointment::findOne($id_appointment);
        $appointment->id_patient = $patient->id_patient;
        if (!$appointment->validate()) return $this->validation($appointment);
        $appointment->save();
        return $this->send(200, ['content' => ['code' => 200, 'message' => 'Вы записались на прием']]);
    }

    /*Удалить клинику*/
    public function actionDel($id_appointment)
    {
        $appointment = Appointment::findOne($id_appointment);
        if (!$appointment) return $this->send(404, ['content' => ['code' => 404, 'message' => 'Запись не найдена']]);

        if (!$this->is_admin())
            return $this->send(401, ['content' => ['code' => 401, 'message' => 'Вы не являетесь администратором']]);
        $appointment = Appointment::findOne($id_appointment);
        $appointment->delete();
        return $this->send(200, ['content' => ['Status' => 'ok']]);
    }

}
