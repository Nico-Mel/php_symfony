<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Task;
use App\Repository\TaskRepositoryInterface;
use App\Service\TaskPriorityCalculatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TaskController extends AbstractController
{
    /**
     * Inyección de dependencias desacopladas mediante Autowiring (Constructor Property Promotion)
     */
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly TaskPriorityCalculatorInterface $priorityCalculator
    ) {}

    #[Route('/api/tasks', name: 'api_tasks_list', methods: ['GET'])]
    public function listTasks(): JsonResponse
    {
        // 1. Obtención de datos mediante el repositorio (separación de persistencia)
        $tasks = $this->taskRepository->findAll();

        // 2. Procesamiento de negocio mediante el servicio desacoplado
        $processedTasks = $this->priorityCalculator->processTaskList($tasks);

        // 3. Serialización de entidades para la respuesta HTTP
        $payload = array_map(static fn(Task $task): array => $task->toArray(), $processedTasks);

        return $this->json([
            'meta' => [
                'status' => 'success',
                'framework' => 'Symfony 7.x (Enterprise Standard)',
                'inversion_de_control' => 'Autowiring activo vía TaskRepositoryInterface y TaskPriorityCalculatorInterface',
                'total_tareas' => count($payload),
            ],
            'data' => $payload,
        ], Response::HTTP_OK);
    }
}
