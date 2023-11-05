# Dokumentacja przez testy [08-03]

## Kod

Przede wszystkim `FraudController` i `FraudControllerTest`.

Do wygenerowania dokumentacji możemy użyć polecenia:
```
composer docs
```

Używając `nelmio/api-doc-bundle` wygeneruje ono specyfikacje API w formacie OpenAPI. 
Dodatkowo zostanie wygenerowany plik `docs/index.html` który może posłużyć nam za referencję.

Dalej wykorzystując naszą specyfikację możemy użyć ją w testach i sprawdzić czy reqeust/response jest zgodny z formatem.

Do walidacji została wykorzystana paczka: 
https://github.com/thephpleague/openapi-psr7-validator

Nie posiada ona natywnego wsparcia dla Symfony, dlatego dodatkowo wykorzystano PSR-7 Bridge: 
https://symfony.com/doc/current/components/psr7.html

Całość kodu który dokonuje weryfikacji i konwersji request/response znajduje się w pliku `FunctionalTestCase`.
