<?php

namespace Go2Flow\PSPClient;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Go2Flow\PSPClient\Skeleton\SkeletonClass
 */
class PSPClientFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'psp-client';
    }
}
