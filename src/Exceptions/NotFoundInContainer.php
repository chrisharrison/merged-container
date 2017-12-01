<?php

declare(strict_types=1);

namespace ChrisHarrison\MergedContainer\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

final class NotFoundInContainer extends \Exception implements NotFoundExceptionInterface
{

}
