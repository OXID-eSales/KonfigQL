<?php

/**
 * Copyright © __Vender__. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace MyVendor\GraphQL\MyPackage\Category\Service;

use MyVendor\GraphQL\MyPackage\Category\DataType\Category as CategoryDataType;
use MyVendor\GraphQL\MyPackage\Category\Exception\CategoryNotFound;
use MyVendor\GraphQL\MyPackage\Category\Infrastructure\CategoryRepository;
use OxidEsales\GraphQL\Base\Exception\InvalidLogin;
use OxidEsales\GraphQL\Base\Exception\NotFound;

final class Category
{
    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct(
        CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @throws CategoryNotFound
     */
    public function category(string $id): CategoryDataType
    {
        try {
            $category = $this->categoryRepository->category($id);
        } catch (NotFound $e) {
            throw CategoryNotFound::byId($id);
        }

        if (!$category->active()) {
            throw new InvalidLogin('Unauthorized');
        }

        return $category;
    }
}
