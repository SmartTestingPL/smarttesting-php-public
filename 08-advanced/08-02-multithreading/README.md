# Wielowątkowość

Do uruchomienia kodu potrzebne będzie rozszerzenie `ext-parallel`.
Można zainstalować je ręcznie, lub wykorzytać gotowy obraz dockerowy:

```
docker run --rm -v `pwd`:`pwd` -w `pwd` akondas/smarttesting:php-7.4-zst php vendor/bin/phpunit
```

Zaczynamy od testów w `_01_CustomerVerifierTest`. Pierwsze testy `test_should_return_results_in_order_of_execution`  pokazują jak zapięcie się na konkretne wyniki w konkretnej kolejności, tam gdzie nie ma to sensu, może zepsuć nam testy - w przypadku PHP wynik jest trochę inny.

Odkomentowany test `test_should_work_in_parallel_without_a_sleep` w klasie `_01_CustomerVerifierTest` się wywali, ponieważ zakończy się szybciej niż procesowanie.

Rozwiązaniem skutecznym, aczkolwiek nieskalującym się i po prostu nienajlepszym, jest umieszczenie oczekiwania przez wątek testu przez X czasu. Przykładem tego jest `_01_CustomerVerifierTest::test_should_work_in_parallel_with_a_sleep`. Zdecydowanie lepszym rozwiązaniem jest odpytywanie komponentu nasłuchującego na zdarzenia co X czasu, maksymalnie przez Y czasu. Przykład `_01_CustomerVerifierTest::test_should_work_in_parallel_with_backoff`.

## Testowanie wielowątkowe - obsługa błędów

Testowanie wielowątkowe - obsługa błędów. Kod produkcyjny - `CustomerVerifier#verify` oraz `_02_CustomerVerifierExceptionTest`. W teście `ExceptionCustomerVerifierTests` pokazujemy jak wyjątek rzucony w osobnym wątku wpływa na nasz główny wątek i jak możemy temu zaradzić.


---

## Docker

```bash
docker build -t st-php-zst . 
docker tag st-php-zst akondas/smarttesting:php-7.4-zst
docker push akondas/smarttesting:php-7.4-zst
```
