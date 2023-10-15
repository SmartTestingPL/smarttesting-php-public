# Testowanie architektury

Aplikacja podzielona jest na dwa moduły, `core` i `symfony`. Pierwsza reprezentuje domenę biznesową naszej aplikacji a druga kod z podpiętymi frameworkami technicznymi.

W tej lekcji zobaczymy jak możemy uchronić się przed popsuciem naszej domeny
i przed tym, że klasy z jednych pakietów są widoczne w innych.

## Testy a architektura [01]

### Kod

Tylko moduł `05-02-01-core` i klasa `BadClassTests.php` (w katalogu `tests/lesson1`)

### Notatki

W tej lekcji przyjrzymy się jak mają się testy do architektury naszej aplikacji. Czy w ogóle się mają i jakie są oznaki, że chyba popełniliśmy jakieś błędy architektoniczne.

Istnieją metryki, o których będziemy sobie jeszcze rozmawiać w ramach tego kursu, które są w stanie nam określić czy nasza architektura jest poprawna. Natomiast jednym z bardziej niedocenianych chyba sposobów takiej weryfikacji jest… łatwość testowania naszej aplikacji.

Czy zdarzyło Ci się, że dodawanie kolejnych testów było dla Ciebie drogą przez mękę? Czy widziałaś setki linijek kodu przygotowującego pod uruchomienie testu? Oznacza to, że najprawdopodobniej albo nasz sposób testowania jest niepoprawny albo architektura aplikacji jest zła.

W przypadku relatywnie dobrej architektury i relatywnie dobrego pisania kodu, pisanie testów powinno być łatwe, bez potrzeb używania skomplikowanych narzędzi.

Podsumowując, jeśli testowanie naszej aplikacji jest trudne i nieprzyjemne to przyczyna może leżeć po stronie, testu, designu lub osoby piszącej test.


## Testowanie pakietowania [02]

### Kod

* `05-02-01-core`
** `BrokenDomainTests.php`, `ArchitectureTests.php`
* `05-02-02-symfony`
** `ArchitectureTests.php` oraz `VerificationControllerTest.php`

### Notatki

W teście `BrokenDomainTests.php` pokazujemy przykład popsutej domeny (kod do slajdu).

W teście `ArchitectureTests.php` (core) pokazujemy przykład testu weryfikującego pakietowanie w domenie biznesowej.

W teście `ArchitectureTests.php` (symfony) pokazujemy przykład testu weryfikującego pakietowanie w części z frameworkami.

W teście `VerificationControllerTest.php` mamy test end to end dla naszej aplikacji uruchomionej w trybie deweloperskim. Ten test nie jest widoczny na slajdach, napisany został, aby upewnić się, że na poziomie ścieżek krytycznych aplikacja nadal działa.

