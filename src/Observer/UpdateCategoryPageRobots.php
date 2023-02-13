<?php

/**
 * Copyright Elgentos. All rights reserved.
 * https://www.elgentos.nl
 */

declare(strict_types=1);

namespace Elgentos\FilteredProductListNoIndex\Observer;

use Elgentos\FilteredProductListNoIndex\Model\Config as ModuleConfig;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Layout;
use Magento\Framework\View\Page\Config;

class UpdateCategoryPageRobots implements ObserverInterface
{
    private Http $request;

    private Config $pageConfig;

    private ModuleConfig $moduleConfig;

    private array $applicableActionNames = [
        'catalog_category_view',
        'catalogsearch_result_index'
    ];

    public function __construct(
        Http $request,
        Config $pageConfig,
        ModuleConfig $moduleConfig
    ) {
        $this->request      = $request;
        $this->pageConfig   = $pageConfig;
        $this->moduleConfig = $moduleConfig;
    }

    public function execute(Observer $observer): void
    {
        if (!in_array($this->request->getFullActionName(), $this->applicableActionNames)) {
            return;
        }

        /** @var Layout $layout */
        $layout           = $observer->getData('layout');
        $productListBlock = $layout->getBlock('category.products.list')
            ?: $layout->getBlock('search_result_list');

        if (!$productListBlock instanceof ListProduct) {
            return;
        }

        // Get the filters
        $state = $productListBlock->getLayer()
            ->getState();

        if (!$state->getFilters()) {
            return;
        }

        $this->pageConfig->setMetadata('robots', $this->moduleConfig->getRobotsValue());
    }
}
