<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Password form
 */
class PasswordForm extends Model
{
    public $password;
    public $passwordc;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
             ['password', 'required'],      
             ['password', 'string', 'min' => 6],
             
             ['passwordc', 'required'],      
             ['passwordc', 'string', 'min' => 6],
             
        ];
    }

    /**
     * @return User|null the saved model or null if saving fails
     */
    public function setpass()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = Yii::$app->user;
        if($user===null){
    	    return false;
        } else {
    	    $user->setPassword($this->password);
    	    return $user->save() ? $user : null;
        }
    }
}
