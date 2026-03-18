<?php

declare(strict_types=1);

namespace Src\Infrastructure\Events;

use Illuminate\Contracts\Container\Container;
use Src\Infrastructure\Logging\OutboxLogger;
use Src\Shared\Outbox\Entities\OutboxMessage;

final class OutboxEventDispatcher
{
    private array $listeners = [];

    public function __construct(
        private Container $container,
        private OutboxLogger $logger
    ) {}

    public function register(string $eventClass, string $listenerClass): void
    {
        $this->listeners[$eventClass] = $listenerClass;
    }

    public function dispatch(OutboxMessage $message): void
    {
        try {
            $data = json_decode($message->payload, true);

            if (! $data) {
                $this->logger->error('Invalid payload format - not JSON');

                return;
            }

            $eventClass = $message->eventType;

            if (! $eventClass) {
                $this->logger->error('Payload missing type field', ['payload' => $data]);

                return;
            }

            if (! isset($this->listeners[$eventClass])) {
                $this->logger->warning("No listener for event: {$eventClass}");

                return;
            }

            $listenerClass = $this->listeners[$eventClass];
            $listener = $this->container->make($listenerClass);

            // Воссоздаем событие
            $event = $this->reconstituteEvent($eventClass, $data);

            if (! $event) {
                $this->logger->error("Failed to reconstitute event: {$eventClass}");

                return;
            }

            // Вызываем listener
            $listener->handle($event);

            $this->logger->info('Event dispatched', [
                'event' => $eventClass,
                'listener' => $listenerClass,
            ]);

        } catch (\Throwable $e) {
            $this->logger->error('Dispatch error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function reconstituteEvent(string $eventClass, array $data): ?object
    {
        if (! class_exists($eventClass)) {
            $this->logger->error("Event class does not exist: {$eventClass}");

            return null;
        }

        if (method_exists($eventClass, 'fromArray')) {
            $eventData = $data['data'] ?? $data;

            if (isset($eventData['type'])) {
                unset($eventData['type']);
            }

            return $eventClass::fromArray($eventData);
        }

        try {
            $reflection = new \ReflectionClass($eventClass);
            $constructor = $reflection->getConstructor();

            if (! $constructor) {
                return $reflection->newInstance();
            }

            $params = [];
            foreach ($constructor->getParameters() as $param) {
                $paramName = $param->getName();
                $params[] = $data['data'][$paramName] ?? $data[$paramName] ?? null;
            }

            return $reflection->newInstanceArgs($params);

        } catch (\Throwable $e) {
            $this->logger->error('Failed to create event: ' . $e->getMessage());

            return null;
        }
    }
}
