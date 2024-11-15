<?php

namespace local_academic_record\form;

use moodleform;

global $CFG;

require_once($CFG->libdir . "/formslib.php");

class DeleteForm extends moodleform
{
  public function definition(): void
  {

    $form = $this->_form; // Don't forget the underscore!

    $form->addElement('hidden', 'id', '');

    $form->addElement(
      'static',
      'description',
      get_string('sure_delete_academic_record', 'local_academic_record')
    );

    $this->add_action_buttons(true, get_string("confirm"));
  }
}
