<?php

namespace local_acacemic_record\infrastructure;

use local_academic_record\domain\AcademicRecordGroup;
use local_academic_record\domain\AcademicRecordGroupCollection;
use local_academic_record\domain\AcademicRecordGroupId;
use local_academic_record\domain\AcademicRecordGroupShortname;
use stdClass;

final class AcademicRecordRepositorySql implements GroupBundleRepository
{

  private function recordToInstance(stdClass $record): AcademicRecordGroup
  {
    return new AcademicRecordGroup(
      $record->id,
      $record->shortname,
      $record->name,
      $record->description
    );
  }

  private function instanceToObject(AcademicRecordGroup $academicRecordGroup): stdClass
  {
    $academicRecordGroupObject = new stdClass();
    $academicRecordGroupObject->id = $academicRecordGroup->id();
    $academicRecordGroupObject->shortname = $academicRecordGroup->shortname();
    $academicRecordGroupObject->name = $academicRecordGroup->name();
    $academicRecordGroupObject->description = $academicRecordGroup->description();

    return $academicRecordGroupObject;
  }

  public function create(AcademicRecordGroup $academicRecordGroup): void
  {
    global $DB;

    $DB->insert_record('academic_record_group', self::instanceToObject($academicRecordGroup));
  }

  public function update(AcademicRecordGroup $academicRecordGroup): void
  {
    global $DB;

    $DB->update_record('academic_record_group', self::instanceToObject($academicRecordGroup));
  }

  public function findById(AcademicRecordGroupId $academicRecordGroupId): ?AcademicRecordGroup
  {
    global $DB;

    $academicRecordGroupData = $DB->get_record('academic_record_group', array('id' => $academicRecordGroupId->value()));

    if (!$academicRecordGroupData) {
      return null;
    }

    return $this->recordToInstance($academicRecordGroupData);
  }


  public function findByShortname(AcademicRecordGroupShortname $shortname): ?AcademicRecordGroup
  {
    global $DB;

    $academicRecordGroupData = $DB->get_record('academic_record_group', array(
      'shortname' => $shortname->value()
    ));

    if (!$academicRecordGroupData) {
      return null;
    }

    return self::recordToInstance($academicRecordGroupData);
  }

  public function findAll(): AcademicRecordGroupCollection
  {
    global $DB;

    $academicRecordGroupData = $DB->get_records('academic_record_group', null, 'weight asc');
    $academicRecordGroups = [];

    foreach ($academicRecordGroupData as $groupData) {
      $academicRecordGroups[] = self::recordToInstance($groupData);
    }

    return new AcademicRecordGroupCollection($academicRecordGroups);
  }

  public function delete(AcademicRecordGroupId $academicRecordGroupId): void
  {
    global $DB;

    $DB->delete_records('academic_record_group', array('id' => $academicRecordGroupId->value()));
  }
}
