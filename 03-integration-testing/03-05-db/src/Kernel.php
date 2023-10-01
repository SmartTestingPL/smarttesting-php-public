<?php

declare(strict_types=1);

namespace SmartTesting;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        if (is_file(\dirname(__DIR__).'/config/config.yaml')) {
            $container->import('../config/{config}.yaml');
            $container->import('../config/{config}_'.$this->environment.'.yaml');
        } elseif (is_file($path = \dirname(__DIR__).'/config/config.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }
}
