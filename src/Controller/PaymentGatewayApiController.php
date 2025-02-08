<?php

namespace App\Controller;

use App\Service\Payment;
use App\PaymentGateway\Aci\Aci;
use App\PaymentGateway\Shift4\Shift4;
use App\Request\PaymentChargerRequest;
use App\Exception\PaymentGatewayException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentGatewayApiController extends AbstractController
{
    private Payment $paymentService;

    public function __construct(Payment $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    #[Route('/payment/gateway/api/{gateway}', name: 'app_payment_gateway_api', methods: 'POST')]
    public function index(PaymentChargerRequest $request, string $gateway): JsonResponse
    {
        $errors = $request->validate();

        if (count($errors) > 0) {
            $messages = ['message' => 'invalid_request', 'errors' => []];
            foreach ($errors as $error) {
                $messages['errors'][] = [
                    'property' => $error->getPropertyPath(),
                    'value' => $error->getInvalidValue(),
                    'message' => $error->getMessage(),
                ];
            }
            return new JsonResponse($messages, Response::HTTP_BAD_REQUEST);
        }

        if (!in_array($gateway, [Aci::NAME, Shift4::NAME])) {
            return $this->json([
                'message' => 'invalid_gateway',
                'errors' => ["Invalid gateway"]
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $transaction = $this->paymentService->processPayment($request, $gateway);
        } catch (PaymentGatewayException $exception) {
            return $this->json([
                'message' => 'charge_transaction_failed',
                'errors' => [$exception->getError()]
            ], Response::HTTP_BAD_REQUEST);
        } catch (\InvalidArgumentException $exception) {
            return $this->json([
                'message' => 'invalid_request',
                'errors' => [$exception->getMessage()]
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Payment charged successfully!',
            'data' => $transaction->toArray(),
        ], Response::HTTP_OK);
    }
}
