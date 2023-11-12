# 09-01 Zacznijmy od testu!

W PHP dla każdej klasy testowej (od 01 do 04) stworzyłem osobne klasy. To była symulacja pracy. Normalnie pracowalibyśmy na plikach z katalogu `src`. Niemniej chciałem zaprezentować jak wygląda proces krok po kroku. Z racji tego że w PHP nie da się ukrywać paczek w scope pakietu, zdecydowałem się na ten prefix _02_Client, 03_Client itp.

Najpierw kodujemy w `_01_AcceptanceTests`. Na slajdach będziemy przechodzić linia po linii włącznie z niekompilującym się kodem.

Następnie kod, gdzie tworzymy kontroler, który nic nie robi jest dostępny tu `_02_AcceptanceControllerTest`. W tym momencie tworzymy prostą implementację kontrolera, który zwraca `null`.

Potem w `_03_AcceptanceControllerSomethingTest.php` tworzymy klasę `Something`, która jeszcze nie do końca wiemy, co będzie robiła.

Po tym, jak rozpiszemy sobie co mamy zrobić z naszym klientem, dochodzimy do wniosku, że chcemy zweryfikować oszusta. Zatem tworzymy klasę `FraudVerifier` (widoczna w klasie `_04_FraudVerifierFailingTest`), która jeszcze nie ma implementacji.

W `_05_FraudVerifierTest` zapisujemy przypadki testowe dla naszej implementacji weryfikacji oszusta. Najpierw chcemy żeby jeden test przeszedł, a potem drugi.

W końcu możemy puścić suitę testów akceptacyjnych `_06_AcceptanceTests`, które na szczęście przejdą.
