<?php

namespace SystemCtl\Test\Integration\Command;

use PHPUnit\Framework\TestCase;
use SystemCtl\Command\SymfonyCommandDispatcher;

/**
 * Class SymfonyCommandDispatcherTest
 *
 * @package SystemCtl\Test\Integration\Command
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommandDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldDispatchACorrectCommand(): void
    {
        $dispatcher = new SymfonyCommandDispatcher();
        $dispatcher->setBinary('echo');

        $output = $dispatcher->dispatch('a')->getOutput();
        $this->assertEquals('a', trim($output));
    }
}
