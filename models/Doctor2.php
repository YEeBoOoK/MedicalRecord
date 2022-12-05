<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "doctor".
 *
 * @property int $id_doctor
 * @property string $doctor_photo
 * @property string $doctor_surname
 * @property string $doctor_name
 * @property string $doctor_patronymic
 * @property string $doctor_specialty
 * @property int $doctor_office
 * @property int $id_doctor_clinic
 * @property string $start_date
 * @property string $weekday
 * @property string $office_hours
 *
 * @property Appointment[] $appointments
 * @property Clinic $doctorClinic
 */
class Doctor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'doctor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['doctor_photo', 'doctor_surname', 'doctor_name', 'doctor_patronymic', 'doctor_specialty', 'doctor_office', 'id_doctor_clinic', 'start_date', 'weekday', 'office_hours'], 'required'],
            [['doctor_office', 'id_doctor_clinic'], 'integer'],
            [['start_date'], 'safe'],
            [['doctor_photo', 'doctor_surname', 'doctor_name', 'doctor_patronymic', 'doctor_specialty'], 'string', 'max' => 40],
            [['weekday', 'office_hours'], 'string', 'max' => 30],
            [['id_doctor_clinic'], 'unique'],
            [['id_doctor_clinic'], 'exist', 'skipOnError' => true, 'targetClass' => Clinic::className(), 'targetAttribute' => ['id_doctor_clinic' => 'id_clinic']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_doctor' => 'Id Doctor',
            'doctor_photo' => 'Doctor Photo',
            'doctor_surname' => 'Doctor Surname',
            'doctor_name' => 'Doctor Name',
            'doctor_patronymic' => 'Doctor Patronymic',
            'doctor_specialty' => 'Doctor Specialty',
            'doctor_office' => 'Doctor Office',
            'id_doctor_clinic' => 'Id Doctor Clinic',
            'start_date' => 'Start Date',
            'weekday' => 'Weekday',
            'office_hours' => 'Office Hours',
        ];
    }

    /**
     * Gets query for [[Appointments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['id_doctor' => 'id_doctor']);
    }

    /**
     * Gets query for [[DoctorClinic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctorClinic()
    {
        return $this->hasOne(Clinic::className(), ['id_clinic' => 'id_doctor_clinic']);
    }
}
