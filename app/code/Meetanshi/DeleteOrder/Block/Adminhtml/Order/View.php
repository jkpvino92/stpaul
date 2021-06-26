<?php
namespace Meetanshi\DeleteOrder\Block\Adminhtml\Order;

class View
{
    private $urlInterface;

    public function __construct(\Magento\Framework\UrlInterface $urlInterface)
    {
        $this->urlInterface = $urlInterface;
    }

    public function beforeGetOrder(\Magento\Sales\Block\Adminhtml\Order\View $subject)
    {

        $subject->addButton(
            'void_delete',
            [
            'label' => __('Delete'),
            'onclick' => 'setLocation(\'' . $this->urlInterface->getUrl('deleteorder/orders/delete', ['selected' => $subject->getRequest()->getParam('order_id')]) . '\')'
            ]
        );
    }
}
