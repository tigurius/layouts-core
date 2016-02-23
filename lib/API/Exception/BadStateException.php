<?php

namespace Netgen\BlockManager\API\Exception;

use Exception as BaseException;

class BadStateException extends Exception
{
    /**
     * Creates a new invalid argument exception.
     *
     * @param string $argument
     * @param string $whatIsWrong
     * @param \Exception $previousException
     */
    public function __construct($argument, $whatIsWrong, BaseException $previousException = null)
    {
        parent::__construct(
            'Argument "' . $argument . '" has an invalid state. ' . $whatIsWrong,
            0,
            $previousException
        );
    }
}
