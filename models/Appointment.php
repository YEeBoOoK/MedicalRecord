<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "appointment".
 *
 * @property int $id_appointment
 * @property int|null $id_patient
 * @property int $id_doctor
 * @property string $acceptance_date
 * @property string $acceptance_time
 * @property int $id_clinic
 *
 * @property Clinic $clinic
 * @property Doctor $doctor
 * @property Patient $patient
 */
class Appointment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appointment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_patient', 'id_doctor', 'id_clinic'], 'integer'],
            [['id_doctor', 'acceptance_date', 'acceptance_time', 'id_clinic'], 'required'],
            [['acceptance_date', 'acceptance_time'], 'safe'],
            [['id_patient'], 'exist', 'skipOnError' => true, 'targetClass' => Patient::className(), 'targetAttribute' => ['id_patient' => 'id_patient']],
            [['id_clinic'], 'exist', 'skipOnError' => true, 'targetClass' => Clinic::className(), 'targetAttribute' => ['id_clinic' => 'id_clinic']],
            [['id_doctor'], 'exist', 'skipOnError' => true, 'targetClass' => Doctor::className(), 'targetAttribute' => ['id_doctor' => 'id_doctor']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_appointment' => 'Id Appointment',
            'id_patient' => 'Id Patient',
            'id_doctor' => 'Id Doctor',
            'acceptance_date' => 'Acceptance Date',
            'acceptance_time' => 'Acceptance Time',
            'id_clinic' => 'Id Clinic',
        ];
    }

    /**
     * Gets query for [[Clinic]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClinic()
    {
        return $this->hasOne(Clinic::className(), ['id_clinic' => 'id_clinic']);
    }

    /**
     * Gets query for [[Doctor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDoctor()
    {
        return $this->hasOne(Doctor::className(), ['id_doctor' => 'id_doctor']);
    }

    /**
     * Gets query for [[Patient]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id_patient' => 'id_patient']);
    }
}
