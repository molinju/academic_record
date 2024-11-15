<?php

namespace local_academic_record\domain;

class AcademicRecordGroupNotFoundError extends \RuntimeException
{
  public function __construct()
  {
    parent::__construct(get_string('academic_record_not_found', 'local_academic_record'));
  }
}
