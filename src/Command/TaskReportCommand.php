<?php

declare(strict_types=1);

namespace App\Command;

use App\Model\Task;
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
        private readonly TaskPriorityCalculatorInterface $priorityCalculator
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('SISTEMA DE GESTIÓN DE TAREAS — SYMFONY 7 CLI');

        $tasks = [
            new Task('Aprovisionamiento de Infraestructura AWS', 'Instalar PHP 8.4, Composer y extensiones', new \DateTimeImmutable('+2 days')),
            new Task('Configuración de Seguridad y Firewall UFW', 'Restricción de puertos y permisos de carpeta var/', new \DateTimeImmutable('today')),
            new Task('Auditoría de Requisitos del Sistema', 'Ejecución del diagnóstico oficial symfony check:req', new \DateTimeImmutable('-2 days')),
            new Task('Revisión Bibliográfica en Normas APA 7', 'Validación de citas en el documento de investigación', new \DateTimeImmutable('+8 days')),
            new Task('Diseño de Diapositivas Grupales', 'Elaboración de 12 láminas para la defensa oral', new \DateTimeImmutable('+4 days')),
            new Task('Planificación de Pruebas Unitarias', 'Mocking de servicios para pruebas automatizadas', new \DateTimeImmutable('+14 days')),
            new Task('Inspección con Symfony Web Profiler', 'Medición de latencia de endpoints y contenedores', new \DateTimeImmutable('+1 day')),
            new Task('Optimización de Rendimiento en Linux', 'Configuración de PHP-FPM y precompilación de rutas', new \DateTimeImmutable('+18 days')),
        ];

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

        $io->success('Reporte ejecutado satisfactoriamente.');

        return Command::SUCCESS;
    }
}
