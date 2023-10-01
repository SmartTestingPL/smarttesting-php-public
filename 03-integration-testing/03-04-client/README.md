# 03-04 Testowanie Klientów HTTP

## Kod

Przede wszystkim `BIKVerificationService.php`

## Notatki

Jaki problem chcemy rozwiązać?

* Nie chcemy uruchamiać testów całej aplikacji
* Szybkie testy weryfikujące również zwracanie błędów

W jaki sposób możemy przetestować z API zewnętrznym?

* możemy postawić zewnętrzną usługę (np. inna aplikacja w naszym systemie)
* możemy postawić zaślepkę usługi zewnętrznej
* zaślepka usługi zewnętrznej przychodzi od tamtej usługi
* możemy wykonać wywołanie do API środowiska sandboxowego

Różne języki programowania pozwalają na uruchomienie zaślepki serwera HTTP w ramach procesu testowania. Koncepcje natomiast są uniwersalne.
Przed uruchomieniem lub w trakcie trwania procesu testowania uruchamiamy zaślepkę serwera HTTP.
Innymi słowy jest to albo proces uruchomiony i skonfigurowany przed uruchomieniem naszych testów lub ma to miejsce w trakcie odpalania testów.
Zaślepka serwera HTTP działa jako osobny proces lub wątek i zachowuje się tak jak ją nauczymy.

Głównym problemem w przypadku takiego podejścia jest to, że jako konsument API konfigurujemy zaślepkę.
Czyli możemy skonfigurować ją w sposób, który nie ma nic wspólnego z rzeczywistością.
Poruszymy ten temat głębiej później w ramach tego szkolenia, podczas rozmowy o testach kontraktowych.

Póki co zobaczmy jak takie konfigurowanie może wyglądać. Przede wszystkim zobaczymy jak możemy nauczyć zaślepkę zwracać konkretne kody błędu dzięki temu upewnimy się, że nasz klient HTTP potrafi je poprawnie obsłużyć.

Przejdźmy do przykładu w PHP z użyciem narzędzia `internations/http-mock` do zaślepiania serwera HTTP.
Jaki jest problem z zaślepką pisaną przez klienta?

### Złe defaulty [01]

#### Klasy

`BIKVerificationServiceDefaultsTests.php`

#### Notatki

W tej klasie testowej chcemy:

* Wytłumaczyć czemu wybieramy randomowe porty
** Żeby nie było clasha portów jeśli jakiś inny proces na takim porcie działa
** Pprerequisite do zrównoleglania testów
* W jaki sposób uruchamiamy `http-mock`
* Odkomentować test i wytłumaczyć dlaczego on nigdy nie przejdzie
** Domyślna konfiguracja połączenia ma ustawiony timeout na wieczność

### Złe defaulty [02]

#### Klasy

`BIKVerificationServiceNoIOExceptionsTests.php`

#### Notatki

Klasa testowa wykorzystująca ręcznie ustawione wartości połączenia po HTTP. W tym przypadku, domyślna implementacja BIKVerificationService, w przypadku błędu zaloguje informacje o wyjątku.

W tej klasie testowej pokazujemy:
* Jak powinniśmy przetestować naszego klienta HTTP.
* Czy potrafimy obsłużyć wyjątki? Czy potrafimy obsłużyć scenariusze biznesowe?

O problemach związanych z pisaniem zaślepek przez konsumenta API, będziemy mówić w dalszej części szkolenia. Tu pokażemy ręczne zaślepianie scenariuszy biznesowych.

### Bonus [03]

Tego nie ma na slajdach i w ogóle o tym nie mówię w szkoleniu, ale jest w kodzie.

#### Klasy

`BIKVerificationServiceTests.php`

#### Notatki

Ta klasa testowa, nadpisuje zachowanie naszego klienta HTTP w taki sposób, że zamiast logować rzuca wyjątki. Udowadniamy naszym kursantom, że gdyby nie poprawne obsłużenie wyjątków, faktycznie zostałyby one rzucone.
