# Praca z zastanym kodem

Klasa `_01_FraudVerifier` widoczna na slajdzie po [Cel pracy ze źle zaprojektowanym kodem].

W pliku `_02_FraudVerifierTest` mamy klasę `_03_DatabaseAccessorImpl`. Na jej podstawie powstał kod na slajdzie po screenshocie 4 000 linii kodu.

Następnie próba napisania testu `test_should_mark_client_with_debt_as_fraud`.

Czas na szew (seam) - `_04_FakeDatabaseAccessor`. Nadpisujemy problematyczną metodę bez zmiany kodu produkcyjnego i test `test_should_mark_client_with_debt_as_fraud_with_seam`.

Teraz chcemy dodać nową funkcję systemu do klasy `_05_FraudTaxPenaltyCalculatorImpl`.

Pierwsze podejście z `if/else` w `_06_FraudTaxPenaltyCalculatorImplIfElse`. Problem w tym, że dodajemy nowy kod do nieprzetestowanego.

Wprowadzamy pojęcie Klasy Kiełkowania (Sprout). Czyli za pomocą TDD piszemy nową, przetestowaną klasę, który wywołamy w naszym kodzie legacy (`_07_FraudTaxPenaltyCalculatorImplSprout`). Process TDD widoczny tu `_08_SpecialTaxCalculatorTest`.

Załóżmy, że mamy klasę, która wylicza czy dana osoba jest oszustem lub nie, w zależności od tego, czy posiada dług. By wyciągnąć te informacje, musimy odpytać bazę danych. Akcesor do bazy danych tworzony jest w konstruktorze. Załóżmy, że mamy taką implementację weryfikatora oszustów `_09_FraudVerifierLogicInConstructor` i taką dostępu do bazy danych `_10_DatabaseAccessorImplWithLogicInTheConstructor`. Pierwszą rzeczą, którą możemy zrobić to spróbować w ogóle utworzyć nasz obiekt. Napiszmy test `_02_FraudVerifierTest#test_should_create_an_instance_of_fraud_verifier`. Test wybuchnie! Co możemy zrobić?

W `_11_FraudVerifierLogicInConstructorExtractLogic` widzimy, że możemy dodać drugi konstruktor obok istniejącego, żeby nie tworzyć problematycznego obiektu w konstruktorze, tylko przekazać już otworzony obiekt przez konstruktor. Teraz, możemy utworzyc mocka problematycznego obiektu i napisać test `_02_FraudVerifierTest#test_should_mark_client_with_debt_as_fraud_with_a_mock`.

Teraz możemy wprowadzić nowy interfejs `_12_DatabaseAccessor`,  który pokrywa się z już istniejącym kodem. Podmieniamy w konstruktorze `FraudVerifier`a klasę na interfejs (`_13_FraudVerifierWithInterface`). Dzięki temu możemy też stworzyć sztuczną implementację interfejsu `_14_FakeDatabaseAccessorWithInterface`.

Poprzez taką operację jesteśmy w stanie bardzo uprościć nasz test `_02_FraudVerifierTest#test_should_mark_client_with_debt_as_fraud_with_an_extracted_interface`.

## Obiektu nie da się łatwo utworzyć

Klasa `_15_FraudVerifierTest`. Zawiera implementację `_16_FraudVerifier` jako przykład implementacji z wieloma zależnościami i dużą liczbą linijek kodu.

Pokazujemy dwa przykłady testów, w których próbujemy odgadnąć, które zależności są wymagane poprzez podstawienie mocka. `_15_FraudVerifierTest#test_should_calculate_penalty_when_fraud_applies_for_a_loan` nie trafiamy i leci błąd. W `_15_FraudVerifierTest#test_should_mark_client_with_debt_as_fraud` trafiamy i test nam przechodzi. W teście `_15_FraudVerifierTest#test_should_calculate_penalty_when_fraud_applies_for_a_loan_with_both_deps` przekazujemy brakującą zależność i test przechodzi.

## Globalne zależności

Klasa `_17_FraudVerifierTest`. W klasie `_18_FraudVerifier` mamy przykład implementacji wołającej singleton `DatabaseAccessorImpl`. Przykład implementacji widać tu `_19_DatabaseAccessorImpl`.

To, co możemy zrobić to dodać statyczny setter do implementacji singletona, pozwalający na nadpisanie globalnej instancji, instancją testową. Przykład `_20_DatabaseAccessorImplWithSetter`. Instancja testowa może wyglądać tak jak `_21_FakeDatabaseAccessor`.

Po każdym teście warto po sobie wyczyścić czyli zresetować wartość do tej produkcyjnej. (`tearDown` w teście).

