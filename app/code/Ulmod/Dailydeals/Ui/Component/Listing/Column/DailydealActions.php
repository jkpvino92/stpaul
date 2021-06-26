<?php
/**
 * Copyright © Ulmod. All rights reserved.
 * See LICENSE.txt for license details.
 */
 
namespace Ulmod\Dailydeals\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column as ListingColumn;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class DailydealActions extends ListingColumn
{
    /**
     * Url path  to edit
     *
     * @var string
     */
    const URL_PATH_EDIT = 'um_dailydeals/dailydeal/edit';

    /**
     * Url path  to delete
     *
     * @var string
     */
    const URL_PATH_DELETE = 'um_dailydeals/dailydeal/delete';

    /**
     * URL builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * constructor
     *
     * @param UrlInterface $urlBuilder
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        UrlInterface $urlBuilder,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
    
        $this->urlBuilder = $urlBuilder;
        parent::__construct(
            $context, 
            $uiComponentFactory, 
            $components, 
            $data
        );
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $dataItem) {
                if (isset($dataItem['dailydeal_id'])) {
                    $dataItem[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'dailydeal_id' => $dataItem[
                                        'dailydeal_id'
                                    ]
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'dailydeal_id' => $dataItem[
                                        'dailydeal_id'
                                    ]
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete "${ $.$data.um_product_sku }"'),
                                'message' => __('Are you sure you wan\'t to delete the 
                                    Dailydeal "${ $.$data.um_product_sku }" ?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
