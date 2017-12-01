<?php

declare(strict_types=1);

namespace ChrisHarrison\MergedContainer\Exceptions;

final class CannotMergeNonArray extends \Exception
{
    public function __construct($id)
    {
        parent::__construct('A non-array value was encountered in one of the containers when attempting to merge: ' . $id);
    }
}
