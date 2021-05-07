<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Dependency;
use Ramsey\Uuid\Uuid;

class DependencyDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface,
    ItemDataProviderInterface
{
    public function __construct(private string $rootPath)
    {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Dependency::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): array
    {
        $item = [];
        foreach ($this->getDependencies() as $name => $version) {
            $item[] = new Dependency(Uuid::uuid5(Uuid::NAMESPACE_URL, $name), $name, $version);
        }

        return $item;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Dependency
    {
        $dependencies = $this->getDependencies();

        foreach ($dependencies as $name => $version) {
            $uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $name)->toString();

            if ($uuid === $id) {
                return new Dependency($uuid, $name, $version);
            }
        }

        return null;
    }

    private function getDependencies(): array
    {
        $path = $this->rootPath . '/composer.json';
        $json = json_decode(file_get_contents($path), true);

        return $json['require'];
    }
}
