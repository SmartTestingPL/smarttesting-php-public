# Testy mutacyjne [08-04]

## Kod

Najpierw `AgeVerification` jako implementacja, którą będziemy testować.

Potem `AgeVerificationTest`

Czyli weryfikujemy:
* wiek z przyszłości
* wiek w ramach przedziału akceptowalnego
* wiek poniżej przedziału
* wiek powyżej przedziału

Jak uruchomimy narzędzie do policzenia pokrycia kodu testami, to wyjdzie nam 100% pokrycia kodu. 
Pytanie jest czy wszystkie ścieżki zostały rzeczywiście pokryte? Zapomnieliśmy o warunkach brzegowych!

```
composer code-coverage
```
Raport zostanie wygenerowany w katalogu `coverage`.

Jeśli uruchomimy:

```
composer test-mutation
```

Uzyskamy raport w pliku `infection.log`, z którego możemy wyczytać, że brakuje nam weryfikacji pewnych warunków w naszych testach. 
Wystarczy odkomentować zakomentowane testy w klasie `_AgeVerificationTest` i testy mutacyjne powinny przejść.
