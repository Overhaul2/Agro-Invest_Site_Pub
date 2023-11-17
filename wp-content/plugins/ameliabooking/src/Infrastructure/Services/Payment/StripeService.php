<?php
/**
 * @copyright © TMS-Plugins. All rights reserved.
 * @licence   See LICENCE.md for license details.
 */

namespace AmeliaBooking\Infrastructure\Services\Payment;

use AmeliaBooking\Domain\Services\Payment\AbstractPaymentService;
use AmeliaBooking\Domain\Services\Payment\PaymentServiceInterface;
use AmeliaStripe\PaymentIntent;
use AmeliaStripe\Stripe;
use AmeliaStripe\StripeClient;

/**
 * Class StripeService
 */
class StripeService extends AbstractPaymentService implements PaymentServiceInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function execute($data)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        Stripe::setApiKey(
            $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey']
        );

        $intent = null;

        if ($data['paymentMethodId']) {
            $stripeData = [
                'payment_method'      => $data['paymentMethodId'],
                'amount'              => $data['amount'],
                'currency'            => $this->settingsService->getCategorySettings('payments')['currency'],
                'confirmation_method' => 'manual',
                'confirm'             => true,
            ];

            if ($stripeSettings['manualCapture']) {
                $stripeData['capture_method'] = 'manual';
            }

            if ($data['metaData']) {
                $stripeData['metadata'] = $data['metaData'];
            }

            if ($data['description']) {
                $stripeData['description'] = $data['description'];
            }

            $stripeData = apply_filters(
                'amelia_before_stripe_payment',
                $stripeData
            );

            $intent = PaymentIntent::create($stripeData);
        }


        if ($data['paymentIntentId']) {
            $intent = PaymentIntent::retrieve(
                $data['paymentIntentId']
            );

            $intent->confirm();
        }

        $response = null;

        if ($intent && ($intent->status === 'requires_action' || $intent->status === 'requires_source_action') && $intent->next_action->type === 'use_stripe_sdk') {
            $response = [
                'requiresAction'            => true,
                'paymentIntentClientSecret' => $intent->client_secret
            ];
        } else if ($intent && ($intent->status === 'succeeded' || ($stripeSettings['manualCapture'] && $intent->status === 'requires_capture'))) {
            $response = [
                'paymentSuccessful' => true
            ];
        } else {
            $response = [
                'paymentSuccessful' => false
            ];
        }

        return $response;
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \AmeliaStripe\Exception\ApiErrorException
     */
    public function getPaymentLink($data)
    {
        $stripeSettings = $this->settingsService->getSetting('payments', 'stripe');

        $stripe = new StripeClient(
            $stripeSettings['testMode'] === true ? $stripeSettings['testSecretKey'] : $stripeSettings['liveSecretKey']
        );

        $price = $stripe->prices->create(
            [
            'unit_amount' => $data['amount'],
            'currency' => $data['currency'],
            'product_data' => ['name' => $data['description']],
            ]
        );

        if ($price) {
            $paymentLinkData = [
                'line_items' => [
                    [
                        'price' => $price['id'],
                        'quantity' => 1,
                    ],
                ],
                'after_completion' => [
                    'type' => 'redirect',
                    'redirect' => [
                        'url' => $data['returnUrl']
                    ]
                ],
//                'invoice_creation' => ['enabled' => true],
            ];

            if (!empty($data['metaData'])) {
                $paymentLinkData['metadata'] = $data['metaData'];
            }

            $response = $stripe->paymentLinks->create($paymentLinkData);
            return $response && $response['url'] ?
                ['link' => $response['url'], 'status' => 200] :
                ['message' => $response['message'], 'status' => $response['status']];
        }

        return ['message' => $price['message'], 'status' => $price['status']];
    }
}
