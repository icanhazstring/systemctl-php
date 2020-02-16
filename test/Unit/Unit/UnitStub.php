<?php

namespace SystemCtl\Test\Unit\Unit;

use SystemCtl\Unit\AbstractUnit;

/**
 * This stub's one and only purpose it to make the abstract implementation of
 * the abstract class 'AbstractUnit' unit testable.
 *
 * @package SystemCtl\Test\Unit\Unit
 */
class UnitStub extends AbstractUnit
{
    /**
     * @inheritdoc
     */
    protected function getUnitSuffix(): string
    {
        return 'stub';
    }
}
