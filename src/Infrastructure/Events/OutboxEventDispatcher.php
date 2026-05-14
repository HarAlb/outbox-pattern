<?php

declare(strict_types=1);

namespace Src\Infrastructure\Events;

use Illuminate\Contracts\Container\Container;
use Src\Domain\Common\ValueObject\Id;
use Src\Domain\Outbox\Entities\OutboxMessage;
use Src\Domain\Shared\Events\DomainEvent;
use Src\Domain\User\Entities\ValueObject\Email;
use Src\Domain\User\Events\UserRegistered;
use Src\Infrastructure\Logging\OutboxLogger;

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
            $payload = $message->payload;

            if (empty($payload)) {
                $this->logger->error('Invalid payload format - empty payload', [
                    'message_id' => $message->id,
                    'event_type' => $message->eventType,
                ]);

                return;
            }

            $eventClass = $message->eventType;

            if (! $eventClass) {
                $this->logger->error('Event type is empty', [
                    'message_id' => $message->id,
                    'payload' => $payload,
                ]);

                return;
            }

            if (! isset($this->listeners[$eventClass])) {
                $this->logger->warning("No listener for event: {$eventClass}", [
                    'message_id' => $message->id,
                    'available_listeners' => array_keys($this->listeners),
                ]);

                return;
            }

            $event = $this->reconstructEvent($message);

            $listenerClass = $this->listeners[$eventClass];
            $listener = $this->container->make($listenerClass);

            if (! method_exists($listener, 'handle')) {
                $this->logger->error("Listener {$listenerClass} does not have handle method");

                return;
            }

            $listener->handle($event);

            $this->logger->info('Event dispatched successfully', [
                'message_id' => $message->id,
                'event' => $eventClass,
                'listener' => $listenerClass,
                'aggregate_id' => $message->aggregateId,
            ]);

        } catch (\Throwable $e) {
            $this->logger->error('Dispatch error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function reconstructEvent(OutboxMessage $message): DomainEvent
    {
        return match ($message->eventType) {
            UserRegistered::class => $this->reconstructUserRegistered($message->payload),
            default => throw new \RuntimeException("Unknown event type: {$message->eventType}")
        };
    }

    private function reconstructUserRegistered(array $payload): UserRegistered
    {
        $event = new UserRegistered(
            new Id($payload['user_id']),
            new Email($payload['email'])
        );

        if (isset($payload['ip_address'])) {
            $event->setIpAddress($payload['ip_address']);
        }

        if (isset($payload['user_agent'])) {
            $event->setUserAgent($payload['user_agent']);
        }

        return $event;
    }
}
