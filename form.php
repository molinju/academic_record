<?php

use core\output\notification;
use local_academic_record\domain\AcademicRecordGroupRepository;

require_once("../../config.php");
require_once ("../../lib/formslib.php");

global $CFG, $DB, $OUTPUT, $PAGE, $SITE;

/* Permissions check */


/* Request params retrieval */

$academicGroupIdParam = optional_param('id', null, PARAM_INT);

/* Page setup */

$PAGE->set_pagelayout('columns2');
$PAGE->set_title($SITE->fullname . ' | Create new Academic Record Group');
$PAGE->set_heading('Create new group');

/* Navigation settings */

$PAGE->navbar->ignore_active();

// EDITOR OPTIONS

$editoroptions = array('maxfiles' => EDITOR_UNLIMITED_FILES, 'maxbytes'=>$CFG->maxbytes, 'trusttext'=>false, 'noclean'=>true);
$editoroptions['context'] = context_system::instance();
$editoroptions['subdirs'] = file_area_contains_subdirs(context_system::instance(), 'local_academic_record', 'description', 0);


if ($academicGroupIdParam){
  $PAGE->navbar->add('Edit');
  $PAGE->set_title($SITE->fullname . ' | Edit group');
  $PAGE->set_heading('Edit group');
} else {
  $PAGE->navbar->add('Create');
}

/* Data retrieval */

$groupData = null;

if (!$academicGroupIdParam) {
  $form = new GroupBundleForm();
} else {
  $bundleRepository = new GroupBundleRepositorySql();
  $bundleFinder = new GroupBundleFinder($bundleRepository);
  $bundleData = $bundleFinder->__invoke($academicGroupIdParam);
  $form = new GroupBundleForm($bundleData['locked']);
}

if ($form->is_cancelled()) {
  redirect(new moodle_url('/local/euipo_entity_group'));
}

// From submitted data. NULL when accessing.
$submittedData = $form->get_data();

// Form rendering
if (is_null($submittedData)) {

  // If ti is editing
  if (isset($bundleData)) {
    // Cast to object to use file_prepare_standard_editor
    $bundleData = (object) $bundleData;

    $bundleData = file_prepare_standard_editor(
    // The submitted data.
      $bundleData,
      // The field name in the database.
      'description',
      // The options.
      $editoroptions,
      // The combination of contextid, component, filearea, and itemid.
      context_system::instance(),
      'local_euipo_enitity_group',
      'description',
      $bundleData->id
    );
  }

  $form->set_data($bundleData);
  echo $OUTPUT->header();
  $form->display();
  echo $OUTPUT->footer();
}
// Form submit processing
else {
  $groupBundleRepository = new GroupBundleRepositorySql();

  // Bundle created
  if (!$bundleIdParam) {
    $bundleCreator = new GroupBundleCreator($groupBundleRepository);
    $bundleUpdater = new GroupBundleUpdater($groupBundleRepository);
    $bundleFinder = new GroupBundleShortnameFinder($groupBundleRepository);

    try {
      $bundleCreator->__invoke(
        $submittedData->shortname,
        $submittedData->name,
        $submittedData->description_editor['text'],
        $submittedData->visible
      );

      $justCreatedBundle = (object) $bundleFinder->__invoke($submittedData->shortname);

      $submittedData = file_postupdate_standard_editor(
        $submittedData,
        'description',
        $editoroptions,
        context_system::instance(),
        'local_euipo_enitity_group',
        'description',
        $justCreatedBundle->id
      );

      $bundleUpdater->__invoke(
        $justCreatedBundle->id,
        $submittedData->shortname,
        $submittedData->name,
        $submittedData->description,
        $submittedData->visible
      );

      $message = "\"" . $submittedData->name . "\" " . $message = get_string("entity_group_save_created", "local_euipo_translations");
    } catch (Exception $e) {
      $message = $e->getMessage();
      $notificationType = notification::NOTIFY_ERROR;
    }
  }
  // Bundle updated
  else {
    $bundleUpdater = new GroupBundleUpdater($groupBundleRepository);
    try {

      $submittedData = file_postupdate_standard_editor(
      // The submitted data.
        $submittedData,
        // The field name in the database.
        'description',
        // The options.
        $editoroptions,
        // The combination of contextid, component, filearea, and itemid.
        context_system::instance(),
        'local_euipo_enitity_group',
        'description',
        $submittedData->id
      );
      $bundleUpdater->__invoke(
        $bundleIdParam,
        // If bundle is locked, shortname in $submittedData will be null so we use the one from $bundleData
        $submittedData->shortname ?? $bundleData['shortname'],
        $submittedData->name,
        $submittedData->description,
        $submittedData->visible
      );
      $message = "\"" . $submittedData->name . "\" " . $message = get_string("entity_group_save_updated", "local_euipo_translations");
    } catch (Exception $e) {
      $message = $e->getMessage();
      $notificationType = notification::NOTIFY_ERROR;
    }
  }

  redirect(
    new moodle_url('/local/euipo_entity_group'),
    $message,
    null,
    $notificationType ?? notification::NOTIFY_SUCCESS
  );
}

