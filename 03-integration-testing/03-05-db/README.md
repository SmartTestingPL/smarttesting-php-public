# 03-05 Baza danych

## Kod

Przede wszystkim `CustomerVerifier.php`, który jako serwis aplikacyjny wykorzystuje `VerificationRepository` do połączenia z bazą danych.

W tym projekcie wykorzystujemy ORM (Doctrine) oraz mapping w postaci adnotacji.

Encją zapisywaną w bazie danych będzie `VerifiedPerson`.

Do wersjonowania schematu bazy danych wykorzystujemy narzędzie `doctrine/migrations`. Skrypty migrujące możemy znaleźć w katalogu `migrations` dla bazy `postregsql`.

## Notatki

W przypadku testów integracyjnych z bazą danych trzeba tę bazę danych w jakiś sposób uruchomić. Z jednej strony chcielibyśmy, żeby nie trwało to zbyt długo (uruchomienie bazy, zasilanie jej danymi, interakcja), z drugiej chcielibyśmy żeby testy jak najbardziej odzwierciedlały system produkcyjny. Oczywiście też chcemy pełnej automatyzacji uruchamiania tej bazy.

Potencjalne rozwiązania: Zaślepka, w pamięci, embedded, kontener

### Zaślepka [01]

#### Klasy

`_01_CustomerVerifierMocksDatabaseTest.php` - na slajdach zmienione tak, żeby się zmieściło na 1 slajdzie

#### Notatki

- Mocki weryfikują efekty uboczne
- By upewnić się, że się coś po prostu zadziało
- Czy doszło do zapisu do bazy danych
- Zalety
  - Natychmiastowe działanie
- Problemy
  - Brak testowania integracji
  - Dużo kodu ustawiającego stan początkowy

### Bazy danych uruchamiane w pamięci [02]

#### Klasy

`_02_InMemoryVerificationRepository.php` - jest to abstrakcja nad zwykłą mapę symulującą bazę danych

#### Notatki

- Ręcznie zaimplementowana kolekcja
  - Ekstremalnie szybkie testy
  - Do rozważenia dla głównej części domeny / uruchomienia deweloperskiego
  - Brak interakcji z IO

### Bazy danych embedded [03]

#### Klasy / Pliki

Klasa `_03_CustomerVerifierWithEmbeddedTest` zawiery testy aplikacji Symfony z użyciem wbudowanej bazy `sqlite`.
Przed jej uruchomieniem potrzebujemy uruchomić następujące polecenia:

`bin/console d:d:c --env=embedded` - stworzenie bazy danych (konfiguracja w .env.embedded)
`bin/console d:m:m --no-interaction --env=embedded` - uruchomienie migracji dla sqlite

Migracje zostały wygenerowane zarówno dla bazy typu `sqlite` jak i `postgresql` (patrz niżej).
W celu uniknięcia konfliktów platformowych wykorzystany został mechanizm `skipIf`:
```php
$this->skipIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration only for PostgreSQL platform');
``` 

Dzięki temu możemy bezpiecznie uruchamiać migracje w różnych środowiskach.
Dla bazy `sqlite` konfiguracja znajduje się w pliku: `.env.embedded`.

#### Notatki

* Może zapisywać dane w plikach
** Przy współbieżnym i natężonym użyciu mogą się pojawiać problemy
* Może instalować prawdziwą bazę danych na dysku
* Przykłady
** H2, HSQLDB, Apache Derby, RavenDB, MongoDB

### Bazy danych w kontenerze [04]

#### Klasy / Pliki

Klasa `_04_CustomerVerifierWithContainerDatabaseTest` zawiera testy aplikacji Symfony.
Do uruchomienia testów wymagany będzie kontener w postgresql:

```
docker-compose up -d
```

#### Notatki

- Baza uruchamiana dla danego testu / danej grupy testów w kontenerze
- Dość szybkie w momencie scache’owania obrazu bazy
  - Jeszcze szybciej jeśli reużywamy kontenerów
-  Przykłady natywnego wsparcia w testach
  - Testcontainers, dotnet-testcontainers
- Łatwe do ręcznego zaimplementowania
  - Uruchom kontener, uruchom testy wobec kontenera, ubij kontener

https://blog.jooq.org/tag/integration-testing/[Wywiad] z twórcą Testcontainers

> W 2015, przed tym jak rozpocząłem projekt Testcontainers, mieliśmy problemy z paroma funkcjonalnościami MySQL, które nie miały swoich odpowiedników w H2. Dochodziliśmy do wniosku, że być może będziemy musieli ograniczyć funkcjonalności bazy do tego, co pozwala nam H2. Wyszło nam, że jest pewna nisza na rynku do rozwiązania typu H2, które w rzeczywistości jest fasadą na Dockerową bazę danych uruchamianą w kontenerze.
