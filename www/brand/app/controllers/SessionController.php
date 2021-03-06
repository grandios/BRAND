<?php

use Phalcon\Tag as Tag;
use Phalcon\Forms\Form;
class SessionController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Sign Up/Sign In');
        parent::initialize();
    }

    public function indexAction()
    {
        if (!$this->request->isPost()) {
            Tag::setDefault('email', 'demo@phalconphp.com');
            Tag::setDefault('password', 'phalcon');
        }
    }

    public function registerAction()
    {
        $formElements = array(
            'name' => array(
                'label' => 'Vollständiger Name',
                'placeholder' => 'Vollständiger Name'
            ),
            'username' => array(
                'label' => 'Benutzername',
                'placeholder' => 'Benutzername',
                'required' => true
            ),
            'email' => array(
                'label' => 'E-Mail-Adresse',
                'placeholder' => 'E-Mail-Adresse',
                'type' => 'email',
                'required' => true
            ),
            'password' => array(
                'label' => 'Passwort',
                'placeholder' => 'Passwort',
                'type' => 'password',
                'required' => true
            ),
            'passwordRepeat' => array(
                'label' => 'Passwort wiederholen',
                'placeholder' => 'Passwort wiederholen',
                'type' => 'password',
                'required' => true
            )
        );
        $form = new FormBase();
        $form->setFormElements($formElements);
        $request = $this->request;
        $messages = array();
        if ($request->isPost()) {
            $customMessages = array();
            $user = new Users();
            $form->bind($_POST, $user);
            // Compare Passwords
            $password = $request->getPost('password');
            $passwordRepeat = $this->request->getPost('passwordRepeat');
            if ($password != $passwordRepeat) {
                $customMessages['passwordRepeat'] = array('Die Passwörter stimmen nicht überein.');
            }
            // Validate Form
            if($form->isValid() && !count($customMessages)) {
                $user->created_at = new Phalcon\Db\RawValue('now()');
                $user->save();
                $this->flash->success('Thanks for sign-up, please log-in to start generating invoices');
                return $this->forward('session/index');
            } else {
                $form->setCustomMessages($customMessages);
                $form->clear(array('password', 'passwordRepeat'));
            }
        }
        $this->view->setVar('form', $form->renderForm());
        $this->view->setVar("messages", $messages);
    }

    /**
     * Register authenticated user into session data
     *
     * @param Users $user
     */
    private function _registerSession($user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'name' => $user->name
        ));
    }

    /**
     * This actions receive the input from the login form
     *
     */
    public function startAction()
    {
        if ($this->request->isPost()) {
            $email = $this->request->getPost('email', 'email');

            $password = $this->request->getPost('password');
            $password = sha1($password);

            $user = Users::findFirst("email='$email' AND password='$password' AND active='Y'");
            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->name);
                return $this->forward('invoices/index');
            }

            $username = $this->request->getPost('email', 'alphanum');
            $user = Users::findFirst("username='$username' AND password='$password' AND active='Y'");
            if ($user != false) {
                $this->_registerSession($user);
                $this->flash->success('Welcome ' . $user->name);
                return $this->forward('invoices/index');
            }

            $this->flash->error('Wrong email/password');
        }

        return $this->forward('session/index');
    }

    /**
     * Finishes the active session redirecting to the index
     *
     * @return unknown
     */
    public function endAction()
    {
        $this->session->remove('auth');
        $this->flash->success('Goodbye!');
        return $this->forward('index/index');
    }
}
