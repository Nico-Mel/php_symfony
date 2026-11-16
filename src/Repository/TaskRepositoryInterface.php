<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Task;

interface TaskRepositoryInterface
{
    /**
     * @return Task[]
     */
    public function findAll(): array;
}
