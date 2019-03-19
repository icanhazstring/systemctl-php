<?php
declare(strict_types=1);

namespace SystemCtl\Scope;

/**
 * UserScope
 *
 * @package SystemCtl\Scope
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class UserScope extends AbstractScope
{
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'user';
    }
}
