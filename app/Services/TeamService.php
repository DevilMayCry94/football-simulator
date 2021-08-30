<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Team;

class TeamService extends CrudBaseService
{
    public function getModel(): string
    {
        return Team::class;
    }
}
