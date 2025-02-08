<?php

namespace App\PaymentGateway\Aci;

use App\Utils\Card;
use Psr\Log\LoggerInterface;
use App\Request\PaymentChargerRequest;
use App\Response\PaymentChargerResponse;
use App\Exception\PaymentGatewayException;
use App\PaymentGateway\Aci\Response\Error;
use App\PaymentGateway\Aci\Response\Success;
use App\PaymentGateway\PaymentGatewayInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Aci implements PaymentGatewayInterface
{
    public const NAME = 'aci';

    private array $requestHeadersConfiguration;
    private array $requestPayloadConfiguration;
    public function __construct(
        private LoggerInterface $logger,
        private HttpClientInterface $httpClient,
        private array $paymentGateways,
    ) {
        $this->requestHeadersConfiguration = $this->paymentGateways[self::NAME]['request_headers'];
        $this->requestPayloadConfiguration = $this->paymentGateways[self::NAME]['payload'];
    }

    public function buildPayload(PaymentChargerRequest $request) :array
    {
        $cardBrand = Card::getCardBrand($request->cardNumber);
        return [
            "amount" => $request->amount,
            "entityId" => $this->requestPayloadConfiguration["entity_id"],
            "currency" => $request->currency,
            "paymentBrand" => $cardBrand,
            "paymentType" => "PA",
            "card.number" => $request->cardNumber,
            "card.expiryMonth" => $request->cardExpiryMonth,
            "card.expiryYear" => $request->cardExpiryYear,
            "card.cvv" => $request->cardCvv,
            "card.holder"=> $request->cardHolder,
        ];
    }


    /**
     * @throws PaymentGatewayException
     */
    public function charge(PaymentChargerRequest $request): PaymentChargerResponse
    {
        $payload = $this->buildPayload($request);
        $this->logger->info("Aci charge request".json_encode($payload));
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->paymentGateways[self::NAME]['url'],
                ['body' => $payload, 'headers' => $this->headers()]
            );
            if ($response->getStatusCode() != 200) {
                $this->logger->error("Failed to charge Aci transaction. request: ".json_encode($payload)." response: ".$response->getContent(false));
                throw new PaymentGatewayException(new Error($response->getContent()));
            }

            return Success::build($response->toArray());
        } catch (\Throwable $exception) {
            $this->logger->error($exception->getMessage());
            throw new PaymentGatewayException(new Error($exception));
        }
    }

    private function headers(): array
    {
        return  [
            'Content-Type' => $this->requestHeadersConfiguration['content_type'],
            'Authorization' => $this->requestHeadersConfiguration['authorization']];
    }
}
