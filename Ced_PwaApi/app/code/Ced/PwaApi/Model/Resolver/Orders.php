<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Ced\PwaApi\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface ;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactoryInterface;

/**
 * Orders data reslover
 */
class Orders extends \Magento\SalesGraphQl\Model\Resolver\Orders
{
    /**
     * @var CollectionFactoryInterface
     */
    private $collectionFactory;

    /**
     * @param CollectionFactoryInterface $collectionFactory
     */
    public function __construct(
        CollectionFactoryInterface $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        /** @var ContextInterface $context */
        // if (false === $context->getExtensionAttributes()->getIsCustomer()) {
        //     throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        // }

        $items = [];
        $orders = $this->collectionFactory->create($context->getUserId());
        /** @var \Magento\Sales\Model\Order $order */
        if($context->getUserId() != 0){
            foreach ($orders as $order) {
                $items[] = [
                    'id' => $order->getId(),
                    'increment_id' => $order->getIncrementId(),
                    'created_at' => $order->getCreatedAt(),
                    'grand_total' => $order->getGrandTotal(),
                    'status' => $order->getStatus(),
                    'currency' => $order->getOrderCurrencyCode(),
                    'ship_to' => $order->getShippingAddress()->getName()
                ];
            }
        }
        
        return ['items' => $items];
    }
}
