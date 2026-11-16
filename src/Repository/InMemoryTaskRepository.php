<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Task;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    /**
     * @return Task[]
     */
    public function findAll(): array
    {
        return [
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
    }
}
