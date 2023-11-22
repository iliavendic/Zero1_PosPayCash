<?php

declare(strict_types=1);

namespace Zero1\PosPayCash\Magewire;

use Magewirephp\Magewire\Component;
use Magento\Checkout\Model\Session as CheckoutSession;

class CashMethod extends Component
{
    // protected $loader = true;

    protected $listeners = [
        'addTender',
        'clearTender'
    ];

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var int
     */
    public $amountTendered = 0;
    
    /**
     * @var int
     */
    public $change = 0;

    /**
     * @var int
     */
    public $customAmount = 0;

    /**
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @param int $amount
     * @return $void
     */
    public function addTender($amount): void
    {
        $this->amountTendered = $this->amountTendered + $amount;
    }

    /**
     * @return void
     */
    public function clearTender(): void
    {
        $this->amountTendered = 0;
        $this->customAmount = 0;
    }

    /**
     * @return float ??????
     */
    public function getChange()
    {
        $quote = $this->checkoutSession->getQuote();

        return (float)$this->amountTendered - $quote->getGrandTotal();
    }


    public function mount(): void
    {
        // $stripeClient = $this->paymentsConfig->getStripeClient();
        // $quote = $this->checkoutSession->getQuote();

        // $productResponse = $stripeClient->products->create([
        //     'name' => 'ZERO-1 POS Order - '.$quote->getId(), // TODO figure out why reserved order id isnt set
        //     'default_price_data' => [
        //         'currency' => $quote->getQuoteCurrencyCode(),
        //         'unit_amount' => (int)($quote->getGrandTotal() * 100) // TODO sort this shit out
        //     ]
        // ]);

        // if(!isset($productResponse->default_price)) {
        //     return;
        // }
        // $price = $productResponse->default_price;

        // $paymentLinkResponse = $stripeClient->paymentLinks->create([
        //     'line_items' => [
        //         [
        //             'price' => $price,
        //             'quantity' => 1,
        //         ],
        //     ],
        // ]);

        // if (isset($paymentLinkResponse->url)) {
        //     $this->qrSource = (new QRCode)->render($paymentLinkResponse->url);
        // }
    }

    public function updatingAmountTendered($value) {

        file_put_contents('../callum.log', 'Setting: '.$value.PHP_EOL, FILE_APPEND);

        $payment = $this->checkoutSession->getQuote()->getPayment();
        $payment->setAdditionalInformation('cash_tendered', $value);
        $this->checkoutSession->getQuote()->save();
        


        // $this->checkoutSession->getQuote()->setAdditionalInformation([
        //     'cash_tendered' => $value
        // ]);
        return $value;
    }
}
