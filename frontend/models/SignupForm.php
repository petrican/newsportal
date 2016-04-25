<?php
namespace frontend\models;

use common\models\User;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    // public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

	    /* these are not required anymore since in the Crossover test it says 
	    Any user could register with an email address. The application sends a verification link to the email address. 
	    When the user clicks the link, the application asks for a new password (can be tested on localhost w/o need for a public domain).
	    Now the user is registered and is able to publish news. Without this verification user cannot publish news.
	    
	    So I understand that user DOES NOT NEED TO SUPPLY PASSWORD during initial registration screen. He has to do that only after he clicks the link - screen in which he is prompted for
	    setting his/her password.
	    [Peter]
	    
	    */	    
            // ['password', 'required'],      
            // ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($user->randomPass());
        $activate_token = $user->generateActivateToken();
	     
        // we also set the random token because I am reusing the password recovery feature of Yii to set new password ;) 
        $user->generatePasswordResetToken();

         
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
