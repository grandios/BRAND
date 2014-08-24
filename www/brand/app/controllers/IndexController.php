<?php

class IndexController extends ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Phalcon\Tag::setTitle('Übersicht');
        parent::initialize();
    }

    public function indexAction()
    {
        $this->persistent->searchParams = null;
        $this->view->setVar("users", Users::find());
    }
}
