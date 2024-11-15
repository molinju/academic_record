<?php

defined('MOODLE_INTERNAL') || die();

//use local_academic_group\infrastructure\AcademicGroupItemRepositorySql;
//
//function local_euipo_entity_group_pre_course_delete($course) {
//  $groupItemRepository = new GroupItemRepositorySql();
//  $groupItemRepository->deleteByEntity("course", $course->id);
//}

function local_academic_record_group_pluginfile($course, $cm, $context, $filearea, $args) {
  $fs = get_file_storage();
  $file = $fs->get_file($context->id, 'local_academic_repository', $filearea, $args[0], '/', $args[1]);
  send_stored_file($file, 86400, 0, false, array());
}

