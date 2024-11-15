<?php

namespace local_academic_record\domain;

use local_academic_record\shared\Collection;

class AcademicRecordGroupCollection extends Collection
{
  protected function type(): string {
    return AcademicRecordGroup::class;
  }
}
