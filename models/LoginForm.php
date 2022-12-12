<?php

namespace app\models;


use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read Patient|null $patient
 *
 */
class LoginForm extends Model
{
    public $login;
    public $password;



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['login', 'password'], 'required'],

        ];
    }

}