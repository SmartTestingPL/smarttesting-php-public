# 07 Idziemy na produkcję

Wszystkie skrypty composera odpala z pozimu głównego katalogu (`07-going-to-production`)
Instalacja zależności (cały moduł 7): `composer install-all`

## Feature flagi

Najpierw uruchom wszystkie serwisy poleceniem:
```
composer start-services
``` 

Następnie tworzymy nowy wniosek:
```
http post http://127.0.0.1:8002/orders < loanOrder.json
```
W odpowiedzi dostaniemy je id, sprawdź status wniosku (będzie `verified`):
```
http get http://127.0.0.1:8002/orders/tutaj_wklej_uuid
```

Teraz w pliku `fraud-verifier/config/packages/flagception.yaml` zmieniamy flagę `new_verification` na `true`:
```
flagception:
    features:
        new_verification:
            default: true
```
Powtarzamy kroki z nowy wnioskiem (POST) i sprawdzamy jego stan (GET): `rejected`. 
Użyty bundle dla symfony posiada również kilka innych ciekawych opcji:
- warunkowe włącznie flagi (przykładowo: `constraint: date("H") > 8 and date("H") < 18`)
- wsparcie dla twig
- adnotacja controlera
- przechowywanie stanu flag w bazie (database activator)

Więcej informacji znajduje się pod adresem: [https://github.com/bestit/flagception-bundle](https://github.com/bestit/flagception-bundle)

## Metrics

Metryki dostępne od ręki (dzięki `artprima/prometheus-metrics-bundle`):

```
# HELP fraud_verifier_http_2xx_responses_total total 2xx response count
# TYPE fraud_verifier_http_2xx_responses_total counter
fraud_verifier_http_2xx_responses_total{action="POST-smarttesting_customer_verify"} 3
fraud_verifier_http_2xx_responses_total{action="POST-verify_customer"} 5
fraud_verifier_http_2xx_responses_total{action="all"} 8
# HELP fraud_verifier_http_4xx_responses_total total 4xx response count
# TYPE fraud_verifier_http_4xx_responses_total counter
fraud_verifier_http_4xx_responses_total{action="POST-smarttesting_customer_verify"} 3
fraud_verifier_http_4xx_responses_total{action="all"} 5
# HELP fraud_verifier_http_requests_total total request count
# TYPE fraud_verifier_http_requests_total counter
fraud_verifier_http_requests_total{action="POST-smarttesting_customer_verify"} 6
fraud_verifier_http_requests_total{action="POST-verify_customer"} 7
fraud_verifier_http_requests_total{action="all"} 13
# HELP fraud_verifier_instance_name app instance name
# TYPE fraud_verifier_instance_name gauge
fraud_verifier_instance_name{instance="dev"} 1
# HELP fraud_verifier_request_durations_histogram_seconds request durations in seconds
# TYPE fraud_verifier_request_durations_histogram_seconds histogram
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.005"} 0
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.01"} 0
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.025"} 0
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.05"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.075"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.1"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.25"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.5"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="0.75"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="1"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="2.5"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="5"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="7.5"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="10"} 1
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-smarttesting_customer_verify",le="+Inf"} 1
fraud_verifier_request_durations_histogram_seconds_count{action="POST-smarttesting_customer_verify"} 1
fraud_verifier_request_durations_histogram_seconds_sum{action="POST-smarttesting_customer_verify"} 0.044
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.005"} 0
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.01"} 2
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.025"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.05"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.075"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.1"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.25"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.5"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="0.75"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="1"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="2.5"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="5"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="7.5"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="10"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="POST-verify_customer",le="+Inf"} 5
fraud_verifier_request_durations_histogram_seconds_count{action="POST-verify_customer"} 5
fraud_verifier_request_durations_histogram_seconds_sum{action="POST-verify_customer"} 0.077
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.005"} 0
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.01"} 2
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.025"} 5
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.05"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.075"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.1"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.25"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.5"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="0.75"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="1"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="2.5"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="5"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="7.5"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="10"} 6
fraud_verifier_request_durations_histogram_seconds_bucket{action="all",le="+Inf"} 6
fraud_verifier_request_durations_histogram_seconds_count{action="all"} 6
fraud_verifier_request_durations_histogram_seconds_sum{action="all"} 0.121
# HELP php_info Information about the PHP environment.
# TYPE php_info gauge
php_info{version="7.4.3"} 1
```

Customowe metryki dodane przez `CustomerVerificationResultMetricGenerator`

```
# HELP fraud_verifier_verification_result_failed_total total failed result count
# TYPE fraud_verifier_verification_result_failed_total counter
fraud_verifier_verification_result_failed_total 1
# HELP fraud_verifier_verification_result_passed_total total passed result count
# TYPE fraud_verifier_verification_result_passed_total counter
fraud_verifier_verification_result_passed_total 2
```
