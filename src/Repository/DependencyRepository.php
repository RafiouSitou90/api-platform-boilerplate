<?php

namespace App\Repository;

use App\Entity\Dependency;
use Ramsey\Uuid\Uuid;

class DependencyRepository
{
    private string $filePath;

    public function __construct(private string $rootPath)
    {
        $this->filePath = $this->rootPath . '/composer.json';
    }

    /**
     * @return Dependency[]
     */
    public function findAll(): array
    {
        $item = [];
        foreach ($this->getDependencies() as $name => $version) {
            $item[] = new Dependency($name, $version);
        }

        return $item;
    }

    public function find(string $uuid): ?Dependency
    {
        foreach ($this->findAll() as $dependency) {
            if ($uuid === $dependency->getUuid()) {
                return $dependency;
            }
        }

        return null;
    }

    public function persist(Dependency $dependency)
    {
        $json = json_decode(file_get_contents($this->filePath), true);
        $json['require'][$dependency->getName()] = $dependency->getVersion();
        file_put_contents($this->filePath, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function remove(Dependency $dependency)
    {
        $json = json_decode(file_get_contents($this->filePath), true);
        unset($json['require'][$dependency->getName()]);
        file_put_contents($this->filePath, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function getDependencies(): array
    {
        $json = json_decode(file_get_contents($this->filePath), true);

        return $json['require'];
    }
}
