<?php

namespace App\PaymentGateway\Shift4;

use Psr\Log\LoggerInterface;
use App\Request\PaymentChargerRequest;
use App\Response\PaymentChargerResponse;
use App\Exception\PaymentGatewayException;
use App\PaymentGateway\Shift4\Response\Error;
use App\PaymentGateway\Shift4\Response\Success;
use App\PaymentGateway\PaymentGatewayInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Shift4 implements PaymentGatewayInterface
{
    public const NAME = 'shift4';

    private array $requestHeadersConfiguration;
    private array $requestPayloadConfiguration;
    public function __construct(
        private LoggerInterface $logger,
        private HttpClientInterface $httpClient,
        private array $paymentGateways,
    ) {
//        $this->requestHeadersConfiguration = $this->paymentGateways[self::NAME]['request_headers'];
        $this->requestPayloadConfiguration = $this->paymentGateways[self::NAME]['payload'];
    }


    public function buildPayload(PaymentChargerRequest $request): array
    {
        return [
            'amount' => $request->amount,
            'currency' => $request->currency,
            'card' => [
                "number" => $request->cardNumber,
                "expYear" => $request->cardExpiryYear,
                "expMonth" => $request->cardExpiryMonth,
                "cvc" => $request->cardCvv,
            ],
        ];
    }

    /**
     * @throws PaymentGatewayException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function charge(PaymentChargerRequest $request): PaymentChargerResponse
    {
        $payload = $this->buildPayload($request);
        $this->logger->info("Shift4 charge request".json_encode($payload));
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->paymentGateways[self::NAME]['url'],
                ['json' => $payload, 'headers' => $this->headers()]
            );

            return Success::build($response->toArray());
        } catch (\Exception $e) {
            throw new PaymentGatewayException(new Error($e));
        }
    }

    private function headers(): array
    {
        return [
            'auth_basic' => [
                'username' => "pr_test_tXHm9qV9qV9bjIRHcQr9PLPa",
                'password' => '',
            ]
        ];
    }
}