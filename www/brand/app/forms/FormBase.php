<?php

use Phalcon\Forms\Form,
    Phalcon\Validation\Validator\PresenceOf;

class FormBase extends Form {
    protected $formElements = array();
    protected $customMessages = array();

    public function setFormElements($elements) {
        $this->formElements = $elements;
        foreach($this->formElements as $key => $value) {
            $type = isset($value['type']) ? $value['type'] : 'text';
            $elementClass = 'Phalcon\\Forms\\Element\\'.ucfirst($type);
            $element = new $elementClass($key);
            if(isset($value['required']) && $value['required'] == true) {
                $element->addValidator(new PresenceOf(array(
                    'message' => 'The '.$value['label'].' is required'
                )));
            }
            $this->add($element);
        }
    }

    public function setCustomMessages($messages) {
        $this->customMessages = $messages;
    }

    public function renderForm() {
        $html = '';
        foreach($this->formElements as $key => $value) {
            $messageTexts = array();
            $messages = $this->getMessagesFor($key);
            if(count($messages)) {
                foreach($messages as $message) {
                    $messageTexts[] = $message->getMessage();
                }
            }
            if(isset($this->customMessages[$key]) && count($this->customMessages[$key])) {
                $messageTexts = array_merge($messageTexts, $this->customMessages[$key]);
            }
            $label = isset($value['label']) ? $value['label'] : $key;
            $attributes = array(
                'class' => 'form-control',
            );
            if(isset($value['placeholder'])) {
                $attributes['placeholder'] = $value['placeholder'];
            }
            $html .= '
                <div class="form-group">
                    <label for="name" class="control-label">'.$label.'</label>
                    '.$this->render($key, $attributes).'
                    '.(count($messageTexts) ? '<p class="help-block">'.join('<br>', $messageTexts).'</p>' : '').'
                </div>
            ';
        }
        return $html;
    }

}