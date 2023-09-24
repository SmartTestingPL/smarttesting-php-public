# 02-01 Testy jednostkowe c.d.
        
Tematy omawiane w tej części:

* Struktura klasy testowej:
  - setup i tear-down; zobacz: `LoanOrderServiceTest`
  - zastosowanie builderów; zobacz: `CustomerBuilder` (w klasie `CustomerTestBase`) i `CustomerVerifierTest`
  - klasy bazowe; zobacz: `LoanOrderTestBase`, `CustomerTestBase`
* Co i kiedy testować
  - testowanie wyniku operacji; zobacz: `LoanOrderServiceTest::testShouldCreateStudentLoanOrder`
  - testowanie stanu; zobacz: `LoanOrderTest::testShouldAddManagerPromo`
  - testowanie interakcji; zobacz: `CustomerVerifierTest::testShouldEmitVerificationEvent`
  - które metody testować
* Mockowanie i stubowanie
  - zobacz: `LoanOrderServiceTest`
* Szkoły testów jednostkowych
* Mocki i stuby - dobre praktyki
* Testy Data-Driven
  - zobacz: `NationalIdentificationNumberVerificationTest`
