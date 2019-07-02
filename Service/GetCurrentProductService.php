<?php
/**
 * Copyright © Tuna, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

declare(strict_types=1);

namespace Tuna\RegistryAlternative\Service;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GetCurrentProductService
 * @package Tuna\RegistryAlternative\Service
 */
class GetCurrentProductService
{
    /**
     * Current Product
     *
     * @var ProductInterface
     */
    private $currentProduct;

    /**
     * Current Product ID
     *
     * @var int|null
     */
    private $productId;

    /**
     * @var CatalogSession
     */
    private $catalogSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param CatalogSession $catalogSession
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        CatalogSession $catalogSession,
        ProductRepositoryInterface $productRepository
    ) {
        $this->catalogSession = $catalogSession;
        $this->productRepository = $productRepository;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        if (!$this->productId) {
            $productId = $this->catalogSession->getData('last_viewed_product_id');
            $this->productId = $productId ? (int)$productId : null;
        }

        return $this->productId;
    }

    /**
     * @return ProductInterface|null
     */
    public function getProduct(): ?ProductInterface
    {
        if (!$this->currentProduct) {
            $productId = $this->getProductId();

            if (!$productId) {
                return null;
            }

            try {
                $this->currentProduct =  $this->productRepository->getById($this->getProductId());
            } catch (NoSuchEntityException $e) {
                return null;
            }
        }

        return $this->currentProduct;
    }
}
