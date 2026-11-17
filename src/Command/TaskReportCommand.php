<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\TaskRepositoryInterface;
use App\Service\TaskPriorityCalculatorInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:task-report',
    description: 'Genera un reporte ejecutivo de priorización de tareas en consola CLI'
)]
class TaskReportCommand extends Command
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly TaskPriorityCalculatorInterface $priorityCalculator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('SISTEMA DE GESTIÓN DE TAREAS — SYMFONY 7 CLI');

        $tasks = $this->taskRepository->findAll();

        $processedTasks = $this->priorityCalculator->processTaskList($tasks);

        $table = new Table($output);
        $table->setHeaders(['#', 'Título de la Tarea', 'Fecha Límite', 'Nivel de Prioridad']);

        $index = 1;
        foreach ($processedTasks as $task) {
            $table->addRow([
                $index++,
                $task->getTitle(),
                $task->getDueDate()->format('Y-m-d'),
                $task->getPriority(),
            ]);
        }

        $table->render();
        $io->success('Reporte CLI ejecutado satisfactoriamente reutilizando dependencias inyectadas.');

        return Command::SUCCESS;
    }
}
