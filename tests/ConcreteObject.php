<?php

namespace Emagister\Collections\Tests;

final class ConcreteObject
{
    public function __construct(private readonly int $id, private readonly string $name)
    {
    }

    public function id(): int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }
}
