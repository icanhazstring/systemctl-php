<?php
declare(strict_types=1);

namespace SystemCtl\Template;

use SystemCtl\Scope\ScopeInterface;

/**
 * PathResolverInterface
 *
 * @package SystemCtl\Template
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
interface PathResolverInterface
{
    /**
     * Resolve given scope into system path
     *
     * @param ScopeInterface $scope
     * @return string
     */
    public function __invoke(ScopeInterface $scope): string;
}
