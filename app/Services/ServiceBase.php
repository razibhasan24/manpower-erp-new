<?php

namespace App\Services;

/** Shared base for the simple CRUD services (Phase 26). */
abstract class ServiceBase
{
    abstract public function create(array $data);

    abstract public function update($item, array $data);

    abstract public function delete($item): bool;
}
