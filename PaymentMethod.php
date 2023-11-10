<?php
namespace Zero1\PosPayCash\Model;


class PaymentMethod extends \Magento\Payment\Model\Method\AbstractMethod
{
    protected $_code = 'zero1_pos_pay_cash';
    protected $_isOffline = true;

    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        return parent::isAvailable($quote);
    }

    public function authorize(\Magento\Payment\Model\InfoInterface $payment, $amount)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $payment->getOrder();
        $order->setIsInProcess(true);
        $order->setState(\Magento\Sales\Model\Order::STATE_PROCESSING)->setStatus('processing');

        return $this;
    }
}
