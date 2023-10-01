# 03-07 Testowanie na środowisku deweloperskim

## Kod

Jedna aplikacja posiadająca konfigurację wraz z klasami,
które pozwalają na uruchomienie wszystkich komponentów integracyjnych w pamięci. 

Dostępny jest również plik `Dockerfile` który pozwala na zbudowaniu obrazu aplikacji.
Plik `docker-compose.yml` posiada opcję uruchomienia naszej aplikacji wraz z brokerem i bazą danych w kontenerach. W ten sposób jesteśmy bliżej wersji produkcyjnej, natomiast nadal część komponentów będzie zaślepiona.

Wymuszenie ponownego budowania obraz można wywołać poleceniem:
```shell script
docker-compose up -d --build
```

## Notatki

Jako deweloperzy dostarczający oprogramowanie, zależy nam na tym, żeby funkcjonalności działały poprawnie. Po to mamy suity testów, żeby przestrzec się przed błędami regresji oraz żeby upewnić się, że nowe funkcjonalności są napisane zgodnie z wymaganiami. Z mojego doświadczenia muszę stwierdzić, że do tego zawsze czułem potrzebę dodatkowo “sklikania” funkcjonalności ręcznie.

Niezależnie od tego czy to był pojedynczy mikroserwis czy aplikacja monolityczna. Po co? Po to żeby zobaczyć czy funkcjonalność rzeczywiście działa tak jak należy, być może jakiś element UI został zmieniony i chcielibyśmy zobaczyć jak wygląda, albo czy w ogóle nasze rozwiązanie jest sensowne pod względem ergonomii.

Naturalnie mógłbym chcieć zmergować moje zmiany z brancha, na którym pracowałem do mastera, zbudować aplikację i wgrać ją na jakieś środowisko. Wolałem jednak odpalić tę aplikację na mojej lokalnej maszynie.

Z drugiej strony nie chciałem, uruchamiać żadnych usług zależnych do mojej aplikacji. Czyli moja usługa nie potrzebowała działającej bazy danych, brokera wiadomości oraz będzie komunikować się z zaślepionymi serwisami zależnymi lub po prostu będzie zwracać zaślepione odpowiedzi.

### Co to jest środowisko deweloperskie?

- Możliwość uruchomienia aplikacji w profilu innym niż produkcyjny
- W szczególności wygodne, mając kontener IOC
- Opcja w pamięci
  - Zamiast baz danych - kolekcja lub baza w pamięci
  - Zamiast brokera - kolejka w pamięci lub broker w pamięci
  - Zamiast klientów HTTP - zaślepki lub odpowiedzi zabite na sztywno
- Opcja z kontenerami
  - Odpalenie w kontenerze zależnych serwisów

