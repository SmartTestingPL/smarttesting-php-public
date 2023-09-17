# SmartTesting PHP

## Struktura folderów

* Każda lekcja ma swoje podfoldery (e.g `01-introduction-to-testing`).
* Każdy folder zawiera plik `README.md` z dodatkowym opisem dla każdej lekcji.

## Wymagania

W celu uruchomienia projektu należy mieć zainstalowane:

* [PHP](https://www.php.net/) (min. `8.0`)
* [Composer](https://getcomposer.org/)
* [Symfony CLI](https://symfony.com/download)
* [Docker](https://docs.docker.com/engine/install/)
* [Docker Compose](https://docs.docker.com/compose/install/)

## Instalacja

Projekt posiada specjalny skrypt, który zainstaluje automatycznie wszystkie zależności wszystkich modułów:

```
composer install 
composer install-modules
```

Każdy moduł można również budować i wykorzystywać niezależnie:

```
cd 03-integration-testing/03-02-ioc
composer install
vendor/bin/phpunit
```

## Testowanie

Analogicznie jak z instalacją, możliwe jest uruchomienie wszystkich testów dla wszystkich modułów:

```
composer tests
```

Istnieje również możliwość uruchamiania pojedynczych modułów:
```
cd 01-introduction-to-testing/01-01-unit-tests
vendor/bin/phpunit
```

## Uwagi do uczestników

Kod produkcyjny nie jest kodem referencyjnym. Tzn. jest on przygotowany pod szkolenie, a nie ma być wzorem pisania kodu produkcyjnego.

Kod jest napisany w języku angielskim natomiast PhpDoci i komentarze piszemy po polsku, żeby uczestnikom szkolenia było wygodniej przyswajać wiedzę.

## CI/CD

Skrypt dla GitHub znajduje się w pliku `.github/workflows/ci.yml`. Bazuje on na specjalnie przygotowanym obrazie dockerowym.
Dzięki takiej konfiguracji pipeline testowy szybciej (bez potrzeby powtarzania kroków w `before_script`.
W razie zmian trzeba dodać je w pliku `Dockerfile` i wypchać nowy obraz:

```bash
docker build -t smarttesting . 
docker tag smarttesting akondas/smarttesting:php-8.2.11
docker push akondas/smarttesting:php-8.2.11
```

(trzeba podmienić nazwę konta/organizacji z `akondas` na własną, dowolną - należy wtedy pamiętać o zmianie taga w pliku `.github/workflows/ci.yml`)
