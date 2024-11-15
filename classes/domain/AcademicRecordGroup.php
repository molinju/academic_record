<?php

namespace local_academic_record\domain;

use core\event\webservice_function_called;

final class AcademicRecordGroup
{
  private readonly AcademicRecordGroupId $id;
  private readonly AcademicRecordGroupShortname $shortname;


  public function __construct(
    ?int $id,
    string $shortname,
    private readonly string $name,
    private readonly string $description
  )
  {

  }

}
