<?php

class UserController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Benutzer');
        parent::initialize();
    }

    public function indexAction()
    {
        $this->persistent->searchParams = null;
        $this->view->setVar("users", Users::find());
    }
}
