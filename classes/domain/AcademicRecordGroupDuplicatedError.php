<?php

namespace local_academic_record\domain;

use RuntimeException;

class AcademicRecordGroupDuplicatedError extends RuntimeException {

  public function __construct()
  {
    parent::__construct(get_string('academic_record_duplicated_error', 'local_academic_record'));
  }
}
