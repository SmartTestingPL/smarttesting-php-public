<?php

declare(strict_types=1);

namespace SmartTesting\Verifier\Customer;

use Artprima\PrometheusMetricsBundle\Metrics\MetricsGeneratorInterface;
use Prometheus\CollectorRegistry;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class CustomerVerificationResultMetricGenerator implements MetricsGeneratorInterface
{
    private string $namespace;
    private CollectorRegistry $collectionRegistry;

    public function init(string $namespace, CollectorRegistry $collectionRegistry)
    {
        $this->namespace = $namespace;
        $this->collectionRegistry = $collectionRegistry;
    }

    public function collectResponse(TerminateEvent $event)
    {
        if ($event->getRequest()->attributes->get('_route') !== 'verify_customer') {
            return;
        }

        $response = json_decode($event->getResponse()->getContent(), true);
        $counter = $this->collectionRegistry->getOrRegisterCounter(
            $this->namespace,
            sprintf('verification_result_%s_total', $response['status']),
            sprintf('total %s result count', $response['status'])
        );
        $counter->inc();
    }

    public function collectStart(RequestEvent $event)
    {
    }

    public function collectRequest(RequestEvent $event)
    {
    }
}
