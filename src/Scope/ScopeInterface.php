<?php
declare(strict_types=1);

namespace SystemCtl\Scope;

/**
 * ScopeInterface
 *
 * @package SystemCtl\Scope
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
interface ScopeInterface
{
    /**
     * Return scope argument value for dispatching commands
     *
     * @return string
     */
    public function __toString(): string;
}
