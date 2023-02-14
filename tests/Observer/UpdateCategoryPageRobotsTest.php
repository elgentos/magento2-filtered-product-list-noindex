<?php

/**
 * Copyright Elgentos. All rights reserved.
 * https://www.elgentos.nl
 */

declare(strict_types=1);

namespace Elgentos\FilteredProductListNoIndex\Tests\Observer;

use Elgentos\FilteredProductListNoIndex\Model\Config as ModelConfig;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\Item;
use Magento\Catalog\Model\Layer\State;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Layout;
use Magento\Framework\View\Page\Config;
use PHPUnit\Framework\TestCase;
use Elgentos\FilteredProductListNoIndex\Observer\UpdateCategoryPageRobots;

/**
 * @coversDefaultClass \Elgentos\FilteredProductListNoIndex\Observer\UpdateCategoryPageRobots
 */
class UpdateCategoryPageRobotsTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::execute
     * @dataProvider setDataProvider
     */
    public function testExecute(
        string $fullActionName = 'cms_index_index',
        bool $isValidProductListBlock = true,
        bool $hasActiveFilters = true
    ): void {
        $request = $this->createMock(Http::class);
        $request->expects(self::once())
            ->method('getFullActionName')
            ->willReturn($fullActionName);

        $subject = new UpdateCategoryPageRobots(
            $request,
            $this->createMock(Config::class),
            $this->createMock(ModelConfig::class)
        );

        $productListBlock = $this->createMock(ListProduct::class);

        $layout = $this->createMock(Layout::class);
        $layout->expects(self::any())
            ->method('getBlock')
            ->willReturn($isValidProductListBlock ? $productListBlock : false);

        $state = $this->createMock(State::class);
        $state->expects(self::any())
            ->method('getFilters')
            ->willReturn(
                $hasActiveFilters
                    ? [$this->createMock(Item::class)]
                    : []
            );

        $layer = $this->createMock(Layer::class);
        $layer->expects(self::any())
            ->method('getState')
            ->willReturn($state);

        $productListBlock->expects(self::any())
            ->method('getLayer')
            ->willReturn($layer);

        $observer = $this->createMock(Observer::class);
        $observer->expects(self::any())
            ->method('getData')
            ->willReturn($layout);

        $subject->execute(
            $observer
        );
    }

    public function setDataProvider(): array
    {
        return [
            [],
            ['catalog_category_view'],
            ['catalog_category_view', false, true],
            ['catalog_category_view', true, false],
            ['catalogsearch_result_index'],
            ['catalogsearch_result_index', false, true],
            ['catalogsearch_result_index', true, false]
        ];
    }
}
