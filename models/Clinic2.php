<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "clinic".
 *
 * @property int $id_clinic
 * @property string $clinic_city
 * @property string $clinic_region
 * @property string $clinic_name
 * @property string $clinic_address
 * @property string $clinic_phone
 *
 * @property Appointment[] $appointments
 * @property Doctor $doctor
 * @property Patient $patient
 */
class Clinic extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clinic';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['clinic_city', 'clinic_region', 'clinic_name', 'clinic_address', 'clinic_phone'], 'required'],
            [['clinic_city', 'clinic_region', 'clinic_phone'], 'string', 'max' => 40],
            [['clinic_name', 'clinic_address'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_clinic' => 'Id Clinic',
            'clinic_city' => 'Clinic City',
            'clinic_region' => 'Clinic Region',
            'clinic_name' => 'Clinic Name',
            'clinic_address' => 'Clinic Address',
            'clinic_phone' => 'Clinic Phone',
        ];
    }

    /**
     * Gets query for [[Appointments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAppointments()
    {
        return $this->hasMany(Appointment::className(), ['id_clinic' => 'id_clinic']);
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['id_doctor_clinic' => 'id_clinic']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id_patient_clinic' => 'id_clinic']);
    }
}
