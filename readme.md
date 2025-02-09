# Payment gateway
   An API endpoint that, based on a parameter passed in the URL, sends a request to the appropriate payment gateway.

## Technologies
- **Symfony**: 6
- **PHP**: 8

### Structure
- **Controller**: Controllers that handle any API request.
- **Command**: Command lines.
- **Factory**: Factory pattern that create instance of payment gateway.
- **Request**: Contains request models.
- **Response**: Contains response models.
- **Service**: Service logic that responsible for charging transactions.
- **PaymentGateway**: Contains all payment gateways and their integrations.
- **tests/**: Contains all tests.


## How to run
- **Manual**
    - Install PHP version 8.
    - Install Symfony.
    - Run `composer install`
    - Run `symfony server:start`
    - To run tests `php bin/phpunit`
- **Using docker**
  - Run `docker compose build`
  - Run ` docker compose up`
  - To run tests `php bin/phpunit`

## Charge payment
- **Command**
  ```sh
    php bin/console app:example aci --amount=100 --currency=EUR --cardNumber=4111111111111111 --cardExpiryYear=2025 --cardExpiryMonth=1 --cardCvv=123 --cardHolder="test"
    ```
- **cURL**
  ```bash
  curl --location --request POST 'localhost:8000/payment/gateway/api/aci' \
  --header 'Content-Type: application/json' \
  --data-raw '{
  "amount": 92.00,
  "currency": "EUR",
  "cardNumber": "4200000000000000",
  "cardExpiryYear": "2025",
  "cardExpiryMonth": "02",
  "cardCvv": "123",
  "cardHolder": "test"
  }'
```

