<?php
namespace app\controllers;

use app\models\Clinic;
use app\models\LoginForm;
use Yii;
use app\controllers\FunctionController;
use yii\filters\auth\HttpBearerAuth;
use function PHPUnit\Framework\returnArgument;
use yii\rest\ActiveController;
class ClinicController extends FunctionController
{
public $modelClass = 'app\models\Clinic';

    public function behaviors()
    {
        /*
         * Указание на аутентификации по токену
         */
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'only' => ['add', 'del', 'red', 'allclinic'], //Перечислите для контроллера методы, требующие аутентификации

        ];
        return $behaviors;
    }

    /*Просмотр клиник*/
    public function actionClinic()
    {
        $clinic = Clinic::find()
            ->orderBy('id_clinic')
            ->all();
        return $this->send(200, ['Clinics' => $clinic]);
    }

    public function actionAllClinic()
    {
        if (!$this->is_admin())
            return $this->send(401, ['content' => ['code' => 401, 'message' => 'Вы не являетесь администратором']]);
        $clinic = Clinic::find()
            ->IndexBy('id_clinic')
            ->all();
        return $this->send(200, ['Clinic' => $clinic]);
    }

    /*Добавление клиник*/
    public function actionAdd()
    {
        if (!$this->is_admin())
            return $this->send(401, ['content' => ['code' => 401, 'message' => 'Вы не являетесь администратором']]);
        $request = Yii::$app->request->post(); //получение данных из post запроса
        $clinic = new Clinic($request); // Создание модели на основе присланных данных
        if (!$clinic->validate()) return $this->validation($clinic); //Валидация модели
        $clinic->save();//Сохранение модели в БД
        return $this->send(200, ['content' => ['code' => 200, 'message' => 'Поликлиника добавлена']]);
    }

    /*Удалить клинику*/
    public function actionDel($id_clinic)
    {
        $clinic = Clinic::findOne($id_clinic);
        if (!$clinic) return $this->send(404, ['content' => ['code' => 404, 'message' => 'Поликлиника не найдена']]);

        if (!$this->is_admin())
            return $this->send(401, ['content' => ['code' => 401, 'message' => 'Вы не являетесь администратором']]);
        $clinic = Clinic::findOne($id_clinic);
        $clinic->delete();
        return $this->send(200, ['content' => ['Status' => 'ok']]);
    }

    /*Редактировать клинику*/
    public function actionRed($id_clinic)
    {
        if (!$this->is_admin())
            return $this->send(403, ['content' => ['code' => 403, 'message' => 'Вы не являетесь администратором']]);

        $request = Yii::$app->request->getBodyParams();
        $clinic = Clinic::findOne($id_clinic);
        //die($trip-$id_trip);
        if (!$clinic) return $this->send(404, ['content' => ['code' => 404, 'message' => 'Поликлиника не найдена']]);
        // return $this->send(200, $flight);

        if (isset($request['id_clinic'])) $clinic->id_clinic = $request['id_clinic'];
        if (isset($request['clinic_city'])) $clinic->clinic_city = $request['clinic_city'];
        if (isset($request['clinic_region'])) $clinic->clinic_region = $request['clinic_region'];
        if (isset($request['clinic_name'])) $clinic->clinic_name = $request['clinic_name'];
        if (isset($request['clinic_address'])) $clinic->clinic_address = $request['clinic_address'];
        if (isset($request['clinic_phone'])) $clinic->clinic_phone = $request['clinic_phone'];

        if (!$clinic->validate()) return $this->validation($clinic);
        $clinic->save();
        return $this->send(200, ['content' => ['code' => 200, 'message' => 'Данные обновлены']]);
    }
}

