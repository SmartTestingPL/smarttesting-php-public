# 03-03 Kontroler / HTTP

## Kod

`FraudController.php`

## Notatki

Zanim napiszemy jakikolwiek test, powinniśmy sobie zadać pytanie, co dokładnie chcemy przetestować. 
Jaki jest cel naszego testu? W tym przypadku, mamy kontroler czyli obiekt przyjmujący żądania HTTP i zwracający odpowiedź w tym protokole.

Mamy kilka możliwości testowania kontrolerów:
testowanie kontrolera jako obiektu, testowanie kontrolera po warstwie HTTP z alokacją portu, testowanie kontrolera po warstwie HTTP bez alokacji portu

Rozważmy teraz przypadki takich testów, jednak zobaczmy najpierw kod. Przykładem będzie kod napisany w PHP z użyciem Symfony.

### Testowanie kontrolera jako obiektu [01]

#### Klasy

`_01_ControllerTests.php`

#### Notatki

Jeśli zainicjujemy kontroler jako obiekt oraz jego zależności to z punktu widzenia kontrolera mamy nic innego jak test jednostkowy. 
W taki sposób testujemy bez warstwy HTTP logikę naszych komponentów. Zakładając, że przetestowaliśmy jednostkowo customerVerifier, taki test nam nic nie daje. Zatem skoro naszym celem jest zweryfikowanie czy nasz kontroler komunikuje się po warstwie HTTP to kompletnie nam się to nie udało.

Czy jest to zły test? Nie, ale trzeba włączyć w to testowanie warstwy HTTP.

### Testowanie kontrolera po warstwie HTTP z alokacją portu [02]

#### Klasy

`_02_HttpFraudControllerTest.php` i `_03_HttpFraudControllerWithFakeTest.php`

#### Notatki

Ponieważ chcemy jedynie przetestować warstwę kontrolera, pierwszą rzeczą, którą powinniśmy zrobić to użyć fakeowowej implementacji / mocka dla naszego serwisu aplikacyjnego. Nie chcemy testować na tym etapie warstwy biznesowej.

W tym celu w naszej konfiguracji jesteśmy w stanie utworzyć ręcznie taką implementację, która na potrzeby naszego testu warstwy HTTP zwróci sztucznie pewne wyniki. Naszym celem jest asercja możliwości komunikacji po protokole HTTP.

Tak przygotowaną konfigurację możemy wykorzystać również w testach kontraktowych, o których będziemy mówić w późniejszych częściach tego szkolenia.

### Mockowanie warstwy sieciowej [04]

#### Klasy

`_04_WebFraudControllerTest.php`

#### Notatki

W różnych językach programowania narzędzia do obsługi kontrolerów często dostarczają możliwość uruchomienia testu z zamockowaną warstwą sieciową. Już wiemy, że interakcja z IO jest źródłem opóźnień w naszych testach.

W przypadku PHP i Symfony, takim narzędziem jest budowany w symfony client. Bez wchodzenia w większe szczegóły, możemy zobaczyć tu, gotowy test dziedziczący po `WebTestCase`, który pozwala na szybsze testowanie bez wykorzystania IO i bindowaniu na porcie.

Kontekst Symfony jest ograniczony, nie uruchamia całej aplikacji, tylko jej kluczowe elementy.

### Frameworki BDD do testowania API [05]

#### Kod

`fraud_check.feature`

#### Notatki

Czy w takim razie stawianie całej aplikacji niezależnie od tego czy alokujemy port czy nie jest złe?
Nie, gdyż można wykorzystać takie testy do testu integracyjnego krytycznych ścieżek naszej aplikacji.
