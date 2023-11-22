<?php

namespace Zero1\PosPayCash\Observer;

use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Framework\Event\Observer;
use Magento\Quote\Api\Data\PaymentInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class DataAssignObserver extends AbstractDataAssignObserver
{
    const CASH_TENDERED = 'cash_tendered';

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }


    /**
     * @var array
     */
    protected $additionalInformationList = [
        self::CASH_TENDERED,
    ];

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        // not needed I dont think.

        
        // $data = $this->readDataArgument($observer);

        // $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        // // if (!is_array($additionalData)) {
        // //     return;
        // // }

        // file_put_contents('../callum.log', 'tits: '.print_r($additionalData, true).PHP_EOL, FILE_APPEND);

        // $paymentInfo = $this->readPaymentModelArgument($observer);

        // $paymentInfo->setAdditionalInformation(
        //     self::CASH_TENDERED,
        //     'foo'
        // );

        // foreach ($this->additionalInformationList as $additionalInformationKey) {
        //     if (isset($additionalData[$additionalInformationKey])) {
        //         $paymentInfo->setAdditionalInformation(
        //             $additionalInformationKey,
        //             $additionalData[$additionalInformationKey]
        //         );
        //     }
        // }
    }
}
