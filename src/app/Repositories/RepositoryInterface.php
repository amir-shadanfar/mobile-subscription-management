<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): Model;

    public function update(array $data, $id): bool;

    public function delete($id): int;

    public function show($id): Model;

    public function filter(array $data, array $relations = [], $is_paginate = false);
}
