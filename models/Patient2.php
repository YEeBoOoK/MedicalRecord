<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "patient".
 *
 * @property int $id_patient
 * @property string $patient_surname
 * @property string $patient_name
 * @property string $patient_patronymic
 * @property string $patient_birthday
 * @property string $patient_phone
 * @property string $patient_address
 * @property int $id_patient_clinic
 * @property string $ID_card
 * @property string $insurance
 * @property string $login
 * @property string $password
 * @property string $token
 *
 * @property Appointment[] $appointments
 * @property Clinic $patientClinic
 */
class Patient extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'patient';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_surname', 'patient_name', 'patient_patronymic', 'patient_birthday', 'patient_phone', 'patient_address', 'id_patient_clinic', 'ID_card', 'insurance', 'login', 'password', 'token'], 'required'],
            [['patient_birthday'], 'safe'],
            [['id_patient_clinic'], 'integer'],
            [['patient_surname', 'patient_name', 'patient_patronymic'], 'string', 'max' => 40],
            [['patient_phone'], 'string', 'max' => 20],
            [['patient_address'], 'string', 'max' => 100],
            [['ID_card', 'insurance', 'login', 'password'], 'string', 'max' => 30],
            [['token'], 'string', 'max' => 255],
            [['id_patient_clinic'], 'unique'],
            [['id_patient_clinic'], 'exist', 'skipOnError' => true, 'targetClass' => Clinic::className(), 'targetAttribute' => ['id_patient_clinic' => 'id_clinic']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_patient' => 'Id Patient',
            'patient_surname' => 'Patient Surname',
            'patient_name' => 'Patient Name',
            'patient_patronymic' => 'Patient Patronymic',
            'patient_birthday' => 'Patient Birthday',
            'patient_phone' => 'Patient Phone',
            'patient_address' => 'Patient Address',
            'id_patient_clinic' => 'Id Patient Clinic',
            'ID_card' => 'Id Card',
            'insurance' => 'Insurance',
            'login' => 'Login',
            'password' => 'Password',
            'token' => 'Token',
        ];
    }

    /**
     * Gets query for [[Appointments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['id_patient' => 'id_patient']);
    }

    /**
     * Gets query for [[PatientClinic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatientClinic()
    {
        return $this->hasOne(Clinic::className(), ['id_clinic' => 'id_patient_clinic']);
    }
}
