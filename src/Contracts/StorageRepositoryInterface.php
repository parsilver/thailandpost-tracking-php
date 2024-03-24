<?php

namespace Farzai\ThaiPost\Contracts;

interface StorageRepositoryInterface
{
    public function create(string $name, string $value): void;

    public function update(string $name, string $value): void;

    public function delete(string $name): void;

    public function get(string $name): string;

    public function has(string $name): bool;
}
