<?php
declare(strict_types=1);

namespace SystemCtl\Scope;

/**
 * AbstractScope
 *
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
abstract class AbstractScope implements ScopeInterface
{
    /**
     * @inheritdoc
     */
    public function getArgument(): string
    {
        return '--' . $this->getName();
    }
}
