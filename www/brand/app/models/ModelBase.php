<?php
class ModelBase extends Phalcon\Mvc\Model {
    public function getMessages() {
        $messages = array();
        foreach (parent::getMessages() as $message) {
            $field = $message->getField();
            if(!isset($messages[$field])) {
                $messages[$field] = array();
            }
            switch ($message->getType()) {
                case 'PresenceOf':
                    $messages[$field][] = 'Bitte füllen Sie dieses Feld aus.';
                    break;
                case 'InvalidValue':
                    $messages[$field][] = 'Bitte korrigieren Sie Ihre Eingabe in diesem Feld.';
                    break;
            }
        }
        return $messages;
    }
}