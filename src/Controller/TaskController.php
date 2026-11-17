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

    /**
     * Vista Web (HTML renderizado con Twig)
     * Al responder en HTML con </body>, Symfony inyecta automáticamente la Web Profiler Toolbar (barra negra)
     */
    #[Route('/', name: 'app_home', methods: ['GET'])]
    #[Route('/tasks', name: 'app_tasks_web', methods: ['GET'])]
    public function index(): Response
    {
        $tasks = $this->taskRepository->findAll();
        $processedTasks = $this->priorityCalculator->processTaskList($tasks);

        return $this->render('task/index.html.twig', [
            'tasks' => $processedTasks,
        ]);
    }

    /**
     * Endpoint API REST (JSON Headless)
     */
    #[Route('/api/tasks', name: 'api_tasks_list', methods: ['GET'])]
    public function listTasks(): JsonResponse
    {
        $tasks = $this->taskRepository->findAll();
        $processedTasks = $this->priorityCalculator->processTaskList($tasks);

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
