<?php

declare(strict_types=1);

namespace Zero1\PosPayCash\Magewire;

use Magewirephp\Magewire\Component;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Hyva\Checkout\Model\Magewire\Component\EvaluationInterface;
use Hyva\Checkout\Model\Magewire\Component\EvaluationResultFactory;
use Hyva\Checkout\Model\Magewire\Component\EvaluationResultInterface;

class CashMethod extends Component implements EvaluationInterface
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
     * @var bool
     */
    public $applied = false;

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

        return $this->pricingHelper->currency($change, true, true);
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

        $this->applied = true;

        $payment = $this->checkoutSession->getQuote()->getPayment();
        $payment->setAdditionalInformation('cash_tendered', $this->amountTendered);
        $this->checkoutSession->getQuote()->save();
    }

    /**
     * @param EvaluationResultFactory $factory
     * @return EvaluationResultInterface
     */
    public function evaluateCompletion(EvaluationResultFactory $factory): EvaluationResultInterface
    {
        if(!$this->applied) {
            return $factory->createErrorMessage((string) __('Cannot place order. You must apply the amount tendered.'));
        }

        return $factory->createSuccess();
    }
}
