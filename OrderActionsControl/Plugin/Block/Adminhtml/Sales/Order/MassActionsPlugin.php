<?php
namespace MagoArab\OrderActionsControl\Plugin\Block\Adminhtml\Sales\Order;

use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Framework\AuthorizationInterface;

class MassActionsPlugin
{
    protected $authSession;
    protected $authorization;

    public function __construct(
        AuthSession $authSession,
        AuthorizationInterface $authorization
    ) {
        $this->authSession = $authSession;
        $this->authorization = $authorization;
    }

    public function afterGetMassactionBlock($subject, $result)
    {
        // التحقق من صلاحيات الإجراءات الجماعية
        if (!$this->authorization->isAllowed('MagoArab_OrderActionsControl::order_mass_actions')) {
            // إزالة جميع الإجراءات إذا لم يكن لدى المستخدم إذن عام
            $result->removeItem('hold');
            $result->removeItem('unhold');
            $result->removeItem('print_invoices');
            $result->removeItem('print_packing_slips');
            $result->removeItem('print_credit_memos');
            $result->removeItem('print_all');
            $result->removeItem('print_shipping_labels');
            $result->removeItem('delete');
            
            return $result;
        }

        // التحقق من صلاحيات محددة
        if (!$this->authorization->isAllowed('MagoArab_OrderActionsControl::hold_action')) {
            $result->removeItem('hold');
            $result->removeItem('unhold');
        }

        if (!$this->authorization->isAllowed('MagoArab_OrderActionsControl::print_actions')) {
            $result->removeItem('print_invoices');
            $result->removeItem('print_packing_slips');
            $result->removeItem('print_credit_memos');
            $result->removeItem('print_all');
            $result->removeItem('print_shipping_labels');
        }

        if (!$this->authorization->isAllowed('MagoArab_OrderActionsControl::delete_action')) {
            $result->removeItem('delete');
        }

        return $result;
    }
}
