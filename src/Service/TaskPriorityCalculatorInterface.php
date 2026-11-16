<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Task;

interface TaskPriorityCalculatorInterface
{
    public function calculatePriority(Task $task): string;
    public function processTaskList(array $tasks): array;
}
