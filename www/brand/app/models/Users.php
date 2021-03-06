<?php

use Phalcon\Mvc\Model\Validator\Email as EmailValidator;
use Phalcon\Mvc\Model\Validator\Uniqueness as UniquenessValidator;

class Users extends ModelBase
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $email;

    public function validation()
    {
        $this->validate(new EmailValidator(array(
            'field' => 'email'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'email',
            'message' => 'Diese E-Mail-Adresse wurde bereits registriert.'
        )));
        $this->validate(new UniquenessValidator(array(
            'field' => 'username',
            'message' => 'Dieser Benutzername wird bereits verwendet.'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

}
