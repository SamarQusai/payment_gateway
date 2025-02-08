<?php

namespace App\Command;

use App\PaymentGateway\Aci\Aci;
use App\PaymentGateway\Shift4\Shift4;
use App\Service\Payment;
use Psr\Log\LoggerInterface;
use App\Request\BaseRequest;
use App\Request\PaymentChargerRequest;
use App\Exception\PaymentGatewayException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PaymentGatewayCommand extends Command
{
    protected static $defaultName = 'app:example';


    private Payment $paymentService;
    private LoggerInterface $logger;
    private ValidatorInterface $validator;


    public function __construct(Payment $paymentService, ValidatorInterface $validator, LoggerInterface $logger)
    {
        parent::__construct();
        $this->paymentService = $paymentService;
        $this->validator = $validator;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Payment gateway charger')
            ->addArgument('gateway', InputArgument::REQUIRED, 'Payment gateway (aci|shift4)')
            ->addOption('amount', null, InputOption::VALUE_REQUIRED, 'The amount to be paid')
            ->addOption('currency', null, InputOption::VALUE_REQUIRED, 'Payment currency')
            ->addOption('cardNumber', null, InputOption::VALUE_REQUIRED, 'Credit card number')
            ->addOption('cardExpiryYear', null, InputOption::VALUE_REQUIRED, 'Credit card expiration year')
            ->addOption('cardExpiryMonth', null, InputOption::VALUE_REQUIRED, 'Credit card expiration month')
            ->addOption('cardCvv', null, InputOption::VALUE_REQUIRED, 'Credit card CVV')
            ->addOption('cardHolder', null, InputOption::VALUE_REQUIRED, 'Credit card holder name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $gateway = $input->getArgument('gateway');
        $request = new PaymentChargerRequest($this->validator, BaseRequest::BaseRequestCommandSource);
        $request->amount = $input->getOption('amount');
        $request->currency = $input->getOption('currency');
        $request->cardNumber = $input->getOption('cardNumber');
        $request->cardExpiryYear = $input->getOption('cardExpiryYear');
        $request->cardExpiryMonth = $input->getOption('cardExpiryMonth');

        $request->cardCvv = $input->getOption('cardCvv');
        $request->cardHolder = $input->getOption('cardHolder');
        $errors = $request->validate();

        if (count($errors) > 0) {
            $symfonyStyle->error('Invalid request');
            foreach ($errors as $error) {
                $symfonyStyle->error($error);
            }
            return Command::FAILURE;
        }

        if (!in_array($gateway, [Aci::NAME, Shift4::NAME])) {
            $symfonyStyle->error("Invalid gateway.");
            return Command::FAILURE;
        }

        try {
            $transaction = $this->paymentService->processPayment($request, $gateway);
        } catch (PaymentGatewayException $e) {
            $this->logger->error("Error while charging transaction. error:". $e->getMessage());
            foreach ($e->getError() as $error) {
                $symfonyStyle->error("Error while charging transaction. error: ".$error);
            }
            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->logger->error("Error while charging transaction. error:". $e->getMessage());
            $symfonyStyle->error("Error while charging transaction. error: ".$e->getMessage());
            return Command::FAILURE;
        }

        $symfonyStyle->success('Payment successful');
        $symfonyStyle->table(
            ['Transaction ID', 'Issued at', 'Amount', 'Currency', 'Card Bin'],
            [[$transaction->transactionId, $transaction->issuedAt, $transaction->amount, $transaction->currency, $transaction->cardBin]]
        );

        return Command::SUCCESS;
    }
}