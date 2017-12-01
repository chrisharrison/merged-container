<?php

declare(strict_types=1);

namespace ChrisHarrison\MergedContainer;

use ChrisHarrison\MergedContainer\Exceptions\CannotMergeNonArray;
use ChrisHarrison\MergedContainer\Exceptions\NotFoundInContainer;
use Psr\Container\ContainerInterface;

final class MergedContainer implements ContainerInterface
{
    private $containers;
    private $arrayValuesToMerge;
    private $merged;

    public function __construct(array $containers, array $arrayValuesToMerge = [])
    {
        $this->containers = array_reverse($containers);
        $this->arrayValuesToMerge = $arrayValuesToMerge;
        $this->merged = [];
    }

    public function get($id)
    {
        if (in_array($id, $this->arrayValuesToMerge)) {
            return $this->getMerged($id);
        }

        foreach ($this->containers as $container) {
            /* @var ContainerInterface $container */
            if ($container->has($id)) {
                return $container->get($id);
            }
        }

        throw new NotFoundInContainer;
    }

    public function has($id): bool
    {
        foreach ($this->containers as $container) {
            /* @var ContainerInterface $container */
            if ($container->has($id)) {
                return true;
            }
        }
        return false;
    }

    private function getMerged($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundInContainer;
        }

        if (array_key_exists($id, $this->merged)) {
            return $this->merged[$id];
        }

        $merged = [];

        foreach (array_reverse($this->containers) as $container) {
            /* @var ContainerInterface $container */
            if ($container->has($id)) {
                $value = $container->get($id);
                if (!is_array($value)) {
                    throw new CannotMergeNonArray($id);
                }
                $merged = array_merge($merged, $container->get($id));
            }
        }

        return $this->merged[$id] = $merged;
    }
}
