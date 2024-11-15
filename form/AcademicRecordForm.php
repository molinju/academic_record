<?php

namespace local_academic_record\form;

use moodleform;
use context_system;

global $CFG;

require_once($CFG->libdir . "/formslib.php");

class AcademicRecordForm extends moodleform
{
  public function __construct(
    private readonly bool $locked = false,
                          $action = null,
                          $customData = null,
                          $method = 'post',
                          $target = '',
                          $attributes = null,
                          $editable = true,
    array $ajaxFormData = null
  ) {
    parent::__construct($action, $customData, $method, $target, $attributes, $editable, $ajaxFormData);
  }

  public function definition()
  {
    global $CFG;

    $form = $this->_form; // Don't forget the underscore!
    $requiredLabelString = get_string('required');

    // Adding the "id" field
    $form->addElement('hidden', 'id', '');

    // Adding the "name" field
    $form->addElement('text', 'name', get_string('name'));
    $form->setType('name', PARAM_NOTAGS);
    $form->addRule('name', $requiredLabelString, 'required', null, 'client');
    $form->addHelpButton('name', 'name', 'local_academic_record');

    // Adding the "shortname" field
    $elem = $form->addElement('text', 'shortname', get_string('shortname'));
    $form->setType('shortname', PARAM_NOTAGS);
    $form->addHelpButton('shortname', 'shortname', 'local_academic_record');
    if ($this->locked) {
      $elem->freeze();
    } else {
      $form->addRule('shortname', $requiredLabelString, 'required', null, 'client');
    }

    // Adding the "description" field
    $textfieldoptions = [
      'trusttext' => true,
      'subdirs' => 0,
      'maxfiles' => EDITOR_UNLIMITED_FILES,
      'maxbytes' => $CFG->maxbytes,
      'context' => context_system::instance(),
    ];
    $form->addElement('editor', 'description_editor', get_string('description'),null, $textfieldoptions);

    // Adding the "submit" button
    $this->add_action_buttons();
  }
}
