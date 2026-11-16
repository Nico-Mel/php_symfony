<?php

declare(strict_types=1);

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Task
{
    #[Assert\NotBlank(message: 'El título de la tarea es obligatorio.')]
    #[Assert\Length(
        min: 3, 
        max: 100, 
        minMessage: 'El título debe tener al menos {{ limit }} caracteres.',
        maxMessage: 'El título no puede exceder los {{ limit }} caracteres.'
    )]
    private string $title;

    #[Assert\NotBlank(message: 'La descripción no puede quedar vacía.')]
    private string $description;

    #[Assert\NotNull(message: 'La fecha límite de la tarea es obligatoria.')]
    #[Assert\Type(\DateTimeImmutable::class)]
    private \DateTimeImmutable $dueDate;

    private string $priority = 'NO_CALCULADA';

    public function __construct(
        string $title,
        string $description,
        \DateTimeImmutable $dueDate
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->dueDate = $dueDate;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDueDate(): \DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function toArray(): array
    {
        return [
            'titulo' => $this->title,
            'descripcion' => $this->description,
            'fecha_limite' => $this->dueDate->format('Y-m-d'),
            'prioridad' => $this->priority,
        ];
    }
}
