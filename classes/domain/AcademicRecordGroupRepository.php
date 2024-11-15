<?php

namespace local_academic_record\domain;

interface AcademicRecordGroupRepository
{
  public function create(AcademicRecordGroup $academicRecord): void;

    public function update(AcademicRecordGroup $academicRecord): void;

    public function findById(AcademicRecordGroupId $academicRecordId): ?AcademicRecordGroup;

    public function findAll(): AcademicRecordGroupCollection;

    public function delete(AcademicRecordGroupId $academicRecordId): void;


}
