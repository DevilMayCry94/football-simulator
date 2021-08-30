<?php

declare(strict_types=1);

namespace App\Services;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class CrudBaseService
{
    abstract public function getModel(): string;

    public function create(array $data): ?Model
    {
        return $this->getModel()::create($data);
    }

    public function read(int $id): ?Model
    {
        return $this->getModel()::where('id', $id)->first();
    }

    public function update(int $id, array $data): bool
    {
        return (bool) $this->getModel()::where('id', '=', $id)->update($data);
    }

    public function delete(int $id): bool
    {
        return $this->getModel()::where('id', $id)->delete();
    }

    public function getQueryBuilder(): Builder
    {
        return $this->getModel()::query();
    }
}
