<?php

/**
 * Copyright Elgentos. All rights reserved.
 * https://elgentos.nl
 */

declare(strict_types=1);

namespace Elgentos\FilteredProductListNoIndex\Tests\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Elgentos\FilteredProductListNoIndex\Model\Config;

/**
 * @coversDefaultClass \Elgentos\FilteredProductListNoIndex\Model\Config
 */
class ConfigTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::getRobotsValue
     */
    public function testGetRobotsValue(): void
    {
        $subject = new Config(
            $this->createScopeConfigMock()
        );

        $this->assertEquals('NOINDEX,FOLLOW', $subject->getRobotsValue());
    }

    /**
     * @throws Exception
     */
    public function createScopeConfigMock()
    {
        $scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $scopeConfig->expects(self::once())
            ->method('getValue')
            ->willReturn('NOINDEX,FOLLOW');

        return $scopeConfig;
    }
}
