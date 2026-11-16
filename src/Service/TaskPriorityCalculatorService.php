<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Task;

class TaskPriorityCalculatorService implements TaskPriorityCalculatorInterface
{
    public function calculatePriority(Task $task): string
    {
        $today = new \DateTimeImmutable('today');
        $dueDate = $task->getDueDate();
        $interval = $today->diff($dueDate);

        if ($interval->invert === 1 || $interval->days === 0) {
            return 'CRÍTICA (Vence hoy o se encuentra atrasada)';
        }

        if ($interval->days <= 3) {
            return 'ALTA (Vence en un plazo máximo de 72 horas)';
        }

        if ($interval->days <= 7) {
            return 'MEDIA (Vence durante la semana en curso)';
        }

        return 'BAJA (Planificada a mediano o largo plazo)';
    }

    public function processTaskList(array $tasks): array
    {
        foreach ($tasks as $task) {
            $priority = $this->calculatePriority($task);
            $task->setPriority($priority);
        }

        return $tasks;
    }
}
