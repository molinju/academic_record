<?php

namespace local_academic_record\application;

use local_academic_record\domain\AcademicRecordGroup;
use local_academic_record\domain\AcademicRecordGroupDuplicatedError;
use local_academic_record\domain\AcademicRecordGroupRepository;
use local_academic_record\domain\AcademicRecordGroupShortname;

class GroupBundleCreator
{
  public function __construct(private readonly AcademicRecordGroupRepository $repository)
  {
  }

  public function __invoke(string $shortname, string $name, string $description): void
  {
    $group = $this->repository->findByShortname(new AcademicRecordGroupShortname($shortname));

    if (!is_null($group)) {
      throw new AcademicRecordGroupDuplicatedError();
    }

    $this->repository->create(new AcademicRecordGroup(
      null,
      $shortname,
      $name,
      $description,
    ));
  }
}
