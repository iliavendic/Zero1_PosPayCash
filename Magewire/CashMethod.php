<?php

declare(strict_types=1);

namespace Zero1\PosPayCash\Magewire;

use Magewirephp\Magewire\Component;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;

class CashMethod extends Component
{
    public $loader = 'Saving & calculating change...';

    public $listeners = [
        'save'
    ];

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var PricingHelper
     */
    protected $pricingHelper;

    /**
     * @var int
     */
    public $amountTendered = 0;
    
    /**
     * @param CheckoutSession $checkoutSession
     * @param PricingHelper $pricingHelper
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        PricingHelper $pricingHelper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->pricingHelper = $pricingHelper;
    }

    /**
     * Return change based on amount tendered.
     * 
     * @return float|string
     */
    public function getChange(): float|string
    {
        $quote = $this->checkoutSession->getQuote();
        $change = (float)$this->amountTendered - $quote->getGrandTotal();

        return $this->pricingHelper->currency($change, true, false);
    }

    /**
     * Save amount tendered to quote.
     * 
     * @return void
     */
    public function save(): void
    {
        if($this->amountTendered && !is_numeric($this->amountTendered)) {
            $this->dispatchErrorMessage('Amount entered is not valid!');
            $this->amountTendered = 0;
            return;
        }

        $payment = $this->checkoutSession->getQuote()->getPayment();
        $payment->setAdditionalInformation('cash_tendered', $this->amountTendered);
        $this->checkoutSession->getQuote()->save();
    }
}
