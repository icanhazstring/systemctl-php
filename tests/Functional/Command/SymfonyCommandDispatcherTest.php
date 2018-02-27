<?php
declare(strict_types=1);

namespace SystemCtl\Tests\Functional\Command;

use PHPUnit\Framework\TestCase;
use SystemCtl\Command\SymfonyCommandDispatcher;

/**
 * SymfonyCommandDispatcherTest
 *
 * @package SystemCtl\Tests\Integration\Command
 * @author icanhazstring <blubb0r05+github@gmail.com>
 */
class SymfonyCommandDispatcherTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldDispatchACorrectCommand()
    {
        $dispatcher = new SymfonyCommandDispatcher();
        $dispatcher->setBinary('echo');

        $output = $dispatcher->dispatch('a')->getOutput();
        $this->assertEquals('a', trim($output));
    }
}
