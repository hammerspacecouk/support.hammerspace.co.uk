<?php

declare(strict_types=1);
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    private const CONFIG_FILE = 'config';

    public function __construct($environment, $debug)
    {
        date_default_timezone_set(@date_default_timezone_get());
        parent::__construct($environment, $debug);
    }

    public function registerBundles(): array
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new AppBundle\AppBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'])) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
        }

        return $bundles;
    }

    public function getRootDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        return dirname(__DIR__).'/tmp/cache/'.$this->getEnvironment();
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__).'/tmp/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $suffix = '';
        $env = $this->getEnvironment();
        if (in_array($env, ['dev','alpha','beta'])) {
            $suffix = '_' . $env;
        }
        $loader->load($this->getRootDir().'/config/' . static::CONFIG_FILE . $suffix . '.yml');
    }
}