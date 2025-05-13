<?php
declare(strict_types = 1);

namespace Edifference\Sendy\Plugin;

use Edifference\Sendy\Api\EventRepositoryInterface;
use Edifference\Sendy\Api\Data\EventInterface;
use Edifference\Sendy\Api\Data\EventInterfaceFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Http\Message\UriInterface;
use Sendy\Api\Connection;
use Throwable;

/**
 * @copyright (c) eDifference 2024
 */
class ConnectionPlugin
{
    /**
     * @param EventRepositoryInterface $eventRepository
     * @param EventInterfaceFactory    $eventInterfaceFactory
     * @param Json                     $json
     */
    public function __construct(
        private readonly EventRepositoryInterface $eventRepository,
        private readonly EventInterfaceFactory    $eventInterfaceFactory,
        private readonly Json                     $json
    ) {
    }

    /**
     * Log GET requests
     *
     * @param Connection          $subject
     * @param callable            $proceed
     * @param UriInterface|string $url
     * @param array               $params
     * @param array               $headers
     * @return void
     */
    public function aroundGet(
        Connection $subject,
        callable   $proceed,
        $url,
        array      $params = [],
        array      $headers = []
    ) {
        $event = $this->eventInterfaceFactory->create();
        $event->setRequest($url);
        $this->eventRepository->save($event);
        try {
            $result = $proceed(
                $url,
                $params,
                $headers
            );
            $event->setResponse($this->json->serialize($result));
            return $result;
        } catch (Throwable $e) {
            $event->setMessage($e->getMessage());
            $event->setTrace($e->getTraceAsString());
            $event->setStatus(EventInterface::STATUS_ERROR);
            throw $e;
        } finally {
            $this->eventRepository->save($event);
        }
    }

    /**
     * Log POST requests
     *
     * @param Connection          $subject
     * @param callable            $proceed
     * @param UriInterface|string $url
     * @param ?array              $body
     * @param array               $params
     * @param array               $headers
     * @return array
     */
    public function aroundPost(
        Connection $subject,
        callable   $proceed,
        $url,
        array      $body = null,
        array      $params = [],
        array      $headers = []
    ): array {
        return $this->logRequest(
            $proceed,
            $url,
            $body,
            $params,
            $headers
        );
    }

    /**
     * Log PUT requests
     *
     * @param Connection          $subject
     * @param callable            $proceed
     * @param UriInterface|string $url
     * @param array               $body
     * @param array               $params
     * @param array               $headers
     * @return array
     */
    public function aroundPut(
        Connection $subject,
        callable   $proceed,
        $url,
        array      $body = [],
        array      $params = [],
        array      $headers = []
    ): array {
        return $this->logRequest(
            $proceed,
            $url,
            $body,
            $params,
            $headers
        );
    }

    /**
     * Log the data request
     *
     * @param callable            $proceed
     * @param UriInterface|string $url
     * @param array|null          $body
     * @param array               $params
     * @param array               $headers
     * @return array
     */
    private function logRequest(
        callable   $proceed,
        $url,
        array      $body = null,
        array      $params = [],
        array      $headers = []
    ): array {
        $event = $this->eventInterfaceFactory->create();
        $event->setRequest($url);
        if ($body !== null) {
            $event->setRequest(json_encode($body));
        }
        $this->eventRepository->save($event);
        try {
            $result = $proceed(
                $url,
                $body,
                $params,
                $headers
            );
            $event->setResponse($this->json->serialize($result));
            return $result;
        } catch (Throwable $e) {
            $event->setMessage($e->getMessage());
            $event->setTrace($e->getTraceAsString());
            $event->setStatus(EventInterface::STATUS_ERROR);
            throw $e;
        } finally {
            $this->eventRepository->save($event);
        }
    }
}
