<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Dependency;
use App\Repository\DependencyRepository;

class DependencyDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface,
    ItemDataProviderInterface
{
    public function __construct(private DependencyRepository $dependencyRepository)
    {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Dependency::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): array
    {
        return $this->dependencyRepository->findAll();
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Dependency
    {
        return $this->dependencyRepository->find($id);
    }
}
