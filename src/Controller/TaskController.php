<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Task;
use App\Service\TaskPriorityCalculatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    public function __construct(
        private readonly TaskPriorityCalculatorInterface $priorityCalculator
    ) {}

    #[Route('/api/tasks', name: 'api_tasks_list', methods: ['GET'])]
    public function listTasks(): JsonResponse
    {
        $tasks = [
            new Task(
                'Despliegue de Servidor en AWS EC2',
                'Aprovisionar instancia Ubuntu Server 24.04 y configurar entorno PHP 8.4',
                new \DateTimeImmutable('+1 day')
            ),
            new Task(
                'Preparación de Diapositivas Grupales',
                'Diseñar presentación PowerPoint bajo el estándar institucional FICCT',
                new \DateTimeImmutable('+5 days')
            ),
            new Task(
                'Auditoría con Symfony Web Profiler',
                'Inspeccionar tiempos de respuesta, ruteo e inyección del Service Container',
                new \DateTimeImmutable('+10 days')
            ),
            new Task(
                'Entrega del Informe Impreso',
                'Presentación formal del documento encuadernado al docente evaluador',
                new \DateTimeImmutable('today')
            ),
            new Task(
                'Corrección de Diagramas UML (Comunicación y Secuencia)',
                'Alinear el modelado formal al formato metodológico de la FICCT',
                new \DateTimeImmutable('-1 day')
            ),
            new Task(
                'Hardening y Configuración de Firewall UFW',
                'Cerrar puertos innecesarios y restringir acceso SSH en AWS',
                new \DateTimeImmutable('+2 days')
            ),
            new Task(
                'Implementación de Pruebas Unitarias con PHPUnit',
                'Diseñar mocks para verificar el cálculo del servicio desacoplado',
                new \DateTimeImmutable('+14 days')
            ),
            new Task(
                'Optimización de Caché y OPcache',
                'Configurar precompilación de bytecode para producción en Linux',
                new \DateTimeImmutable('+20 days')
            ),
        ];

        $processedTasks = $this->priorityCalculator->processTaskList($tasks);

        $payload = array_map(static fn(Task $task): array => $task->toArray(), $processedTasks);

        return $this->json([
            'meta' => [
                'status' => 'success',
                'framework' => 'Symfony 7.x (Enterprise Standard)',
                'inversion_de_control' => 'Autowiring activo vía TaskPriorityCalculatorInterface',
                'total_tareas' => count($payload),
            ],
            'data' => $payload,
        ], Response::HTTP_OK);
    }
}
