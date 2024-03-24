<?php

namespace Farzai\ThaiPost\Repositories;

use Farzai\ThaiPost\Contracts\StorageRepositoryInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class FilesystemCacheStorageRepository implements StorageRepositoryInterface
{
    private FilesystemAdapter $cache;

    public function __construct($keyName = 'farzai.thai-post')
    {
        $this->cache = new FilesystemAdapter($keyName);
    }

    public function create(string $name, string $value): void
    {
        $item = $this->cache->getItem($name);

        $item->set($value);

        $this->cache->save($item);
    }

    public function update(string $name, string $value): void
    {
        $item = $this->cache->getItem($name);
        $item->set($value);

        $this->cache->save($item);
    }

    public function delete(string $name): void
    {
        $this->cache->deleteItem($name);
    }

    public function get(string $name): string
    {
        $item = $this->cache->getItem($name);

        return $item->get();
    }

    public function has(string $name): bool
    {
        return $this->cache->hasItem($name);
    }
}
