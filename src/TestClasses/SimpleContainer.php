<?php

declare(strict_types=1);

namespace ChrisHarrison\MergedContainer\TestClasses;

use ChrisHarrison\MergedContainer\Exceptions\NotFoundInContainer;
use Psr\Container\ContainerInterface;

final class SimpleContainer implements ContainerInterface
{
    private $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function get($id)
    {
        if (array_key_exists($id, $this->items)) {
            return $this->items[$id];
        }
        throw new NotFoundInContainer;
    }
    
    public function has($id): bool
    {
        return array_key_exists($id, $this->items);
    }
}
