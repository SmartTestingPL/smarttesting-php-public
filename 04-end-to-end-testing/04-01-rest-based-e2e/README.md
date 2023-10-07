# 04-01 Testy E2E z poziomu API

Tematy omawiane w tej części:

* Częste problemy w testach E2E po API
  - zobacz: `HttpClientBasedCustomerVerificationTest::testShouldSetOrderStatusToVerifiedWhenCorrectCustomer`
* Obsługa eventual consistency
  - zobacz: `HttpClientBasedCustomerVerificationTest::testShouldSetOrderStatusToFailedWhenInCorrectCustomer`
* Poprawa czytelności i ujednolicenie warstw abstrakcji
  - zobacz: `HttpClientBasedCustomerVerificationTest::testShouldSetOrderStatusToFailedWhenInCorrectCustomer`
* Zastosowanie bibliotek do generowania danych testowych
  - zobacz `FakerCustomerVerificationTest`

**Uruchamianie aplikacji i testów**

Moduł składa się z 3 podmodułów:
* 2 "aplikacji biznesowych": 
  - loan-orders - służy do składania wniosków o udzielenie pożyczki
  - fraud-verifier - służy do weryfikacji klientów
* e2e - modułu zawierającego testy E2E do uruchamienia względem już działającego systemu 

Cały gotowy skrypt uruchomieniowy znajduje się w model e2e - composer.json (przed uruchomieniem proszę pamiętać o instalacji zależności w wszystkich podmodułach `composer install` lub `composer install-modules` z poziomu głównego katalogu).

```
cd e2e
composer test
```

## Poszczególne etapy

Wszystkie przedstawione kroki są zawarte w skrypcie `composer test` w module e2e. Poniżej pokazuję jak ten proces wygląda krok po kroku.
Przed testami uruchamiane są dwie aplikacje na dwóch różnych portach:
```
cd ../fraud-verifier && symfony server:start --port=8001 --daemon
cd ../loan-orders && symfony server:start --port=8002 --daemon
```

Następnie uruchamiamy phpunit (będąc w module e2e):
```
vendor/bin/phpunit
```

Na koniec testów ubijamy testowane aplikacje:
```
cd ../fraud-verifier && symfony server:stop
cd ../loan-orders && symfony server:stop
```

**DISCLAIMER**

* Kod jest czysto demonstracyjny i nie stanowi wzorcowego kodu projektowego - zamiast na jak
najlepiej napisanym kodzie produkcyjnym koncentrujemy się na przykładach, które pozwalają pokazać
wiele sposobów pracy z testami; często celowo dodajemy "produkcyjny" kod, który nie jest poprawnie zaprojektowany po to, żeby pokazać jak sobie z nim radzić i mimo wszystko być w stanie go przetestować
oraz przykłady złych testów, których radzimy unikać.
* Nie polecamy pisania komentarzy po polsku w projektach - tutaj robimy to ponieważ jest to wyłącznie kod szkoleniowy.
