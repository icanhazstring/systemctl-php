<?php
declare(strict_types=1);

namespace SystemCtl\Template;

use SystemCtl\Scope\ScopeInterface;

class PathResolver implements PathResolverInterface
{
    public function __invoke(ScopeInterface $scope): string
    {
    }
}
