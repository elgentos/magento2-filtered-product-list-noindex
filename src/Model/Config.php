<?php

declare(strict_types=1);

namespace Elgentos\FilteredProductListNoIndex\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    private const XML_PATH_ROBOTS_VALUE = 'catalog/seo/filtered_product_list_robots_value';

    private ScopeConfigInterface $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getRobotsValue(): string
    {
        return $this->scopeConfig->getValue(self::XML_PATH_ROBOTS_VALUE);
    }
}
