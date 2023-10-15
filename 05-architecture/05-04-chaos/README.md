# 05-04 Testy chaosu

## Kod

`CustomerVerifier.php`. Mamy tam kod odpowiedzialny za wyciągnięcie danych z bazy danych. W pierwotnej wersji nie ma w tym kodzie obsługi wyjątków.

Jedyny i najważniejszy test - `SmartTestingAppTest.php` - gdzie uruchamiamy eksperymenty inżynierii chaosu.

## Uruchomienie

Przygotowanie bazy danych:
```
docker-compose up -d
bin/console d:m:m
```

Zainstalowanie pumba (https://github.com/alexei-led/pumba):

```
scripts/download-pumba.sh
```

Teraz możemy uruchomić testy.

Oba testy się wywalą.

* `test_should_return_401_within_500_ms_when_calling_fraud_check_with_introduced_latency` - opóźnienie wynosi sekundę więc po 500 ms poleci `idle timeout`
* `test_should_return_401_within_500_ms_when_calling_fraud_check_with_database_issues` - dostaniemy `ConnectionException` i status `500`, ponieważ poleci nam wyjątek z kodu bazodanowego, którego nie obsługujemy.

Następnie należy zakomentować kod w `CustomerVerifier` odpowiedzialny za połączenie z bazą danych i odkomentowanie tego, który dodaje obsługę błędów. Po uruchomieniu ponownym testów, jeden test przejdzie, a drugi, z oczywistych względów się wywali.
