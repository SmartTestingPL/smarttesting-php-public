# 01-01 Unit tests

Tematy omawiane w tej części:

* Czemu testy są niezbędne
* Czemu testy są opłacalne
* Jakie zasady powinny spełniać dobre testy
* Jak unikać fałszywych pozytywów:
   - przykład: `CustomerVerifierTest::testShouldFailSimpleVerification`
* Rodzaje testów
* Struktura testów
  - zobacz np. `NationalIdentificationNumberVerificationTest`
* Konwencje nazewnicze
  - zobacz: `NationalIdentificationNumberVerificationTest`
* Używanie konstruktorów
  - pozwala to między innymi na używanie Test Doubles, zobacz: `CustomerVerifierTest::verificationServiceWrapper`
* Radzenie sobie z metodami statycznymi:
  - zobacz setup pól klasy testowej w `CustomerVerifierTest`
* Asercje i frameworki do asercji (phpunit)
  - zobacz `AgeVerificationTest`
* Wzorzec AssertObject
 - zobacz `LoanOrderServiceTest`

