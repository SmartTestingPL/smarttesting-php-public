# 04.02 Testy E2E z poziomu GUI

Tematy omawiane w tej części:

* Częste problemy w testach E2E z poziomu GUI
  - zobacz: `PetClinicTest`
* Obsługa oczekiwania na załadowanie się strony
  - zobacz: `PageObject::pageReady`
* Zastosowanie wzorca PageObjectModel
  - zobacz: `PetClinicPageObjectModelTest`
* Nakładki na Selenium
  - zobacz `PetClinicPantherTest`

**Setup do Testów**

Testy Selenium w tym module są uruchamiane względem projektu [Pet Clinic](https://github.com/spring-projects/spring-petclinic).

Projekt należy sklonować z GitHuba i odpalić lokalnie:

```
git clone https://github.com/spring-projects/spring-petclinic.git
cd spring-petclinic
./mvnw spring-boot:run
```

Strona będzie dostępna z przeglądarki spod: http://localhost:8080/.

## Testowanie z dowolnym driverem

W celu uruchomienia testów z wykorzystaniem WebDriver'a należy ściągnąć wersję 
WebDriver'a odpowiednią dla przeglądarki i systemu operacyjnego ze strony,
a następnie uruchomić. Można wykorzystać wbudowane binarki z biblioteki symfony/panther.
Po instalacji (`composer install` i uruchomieniu polecenia `vendor/bin/bdi detect drivers`) są one dostępne w katalogach:

```
drivers/geckodriver
drivers/chromedriver
```

Uruchamianie testów:
 
```
vendor/bin/phpunit tests/PetClinicTest.php
```

## Testowanie z symfony/panther

symfony/panther mie wymaga uruchomienie drivera, jest on wykrywany i uruchamiany automatycznie (po wskazaniu ściężki do niego). Wystarczy w takim przypadku uruchomić:

```
PANTHER_GECKO_DRIVER_BINARY=./drivers/geckodriver vendor/bin/phpunit tests/PetClinicPantherTest.php
```

**DISCLAIMER**

* Kod jest czysto demonstracyjny i nie stanowi wzorcowego kodu projektowego - zamiast na jak
najlepiej napisanym kodzie produkcyjnym koncentrujemy się na przykładach, które pozwalają pokazać
wiele sposobów pracy z testami; często celowo dodajemy "produkcyjny" kod, który nie jest poprawnie zaprojektowany po to, żeby pokazać jak sobie z nim radzić i mimo wszystko być w stanie go przetestować
oraz przykłady złych testów, których radzimy unikać.
* Nie polecamy pisania komentarzy po polsku w projektach - tutaj robimy to ponieważ jest to wyłącznie kod szkoleniowy.
