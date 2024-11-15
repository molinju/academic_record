<?php

namespace local_academic_record\domain;

class AcademicRecordGroupId
{

  public function __construct(private readonly ?int $id)
  {
  }

  public function value(): ?int
  {
    return $this->id;
  }
}
