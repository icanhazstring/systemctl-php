<?php
declare(strict_types=1);

namespace SystemCtl\Scope;

/**
 * SystemScope
 *
 * @package SystemCtl\Scope
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class SystemScope extends AbstractScope
{
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'system';
    }
}
