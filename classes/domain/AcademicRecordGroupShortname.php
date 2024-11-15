<?php
namespace local_academic_record\domain;

class AcademicRecordGroupShortname
{
    public function __construct(private readonly string $shortname)
    {
    }

    public function value(): string
    {
        return $this->shortname;
    }
}
