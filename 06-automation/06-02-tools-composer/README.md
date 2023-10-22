# 06-02 Composer

Praktycznie każdy z poprzednich modułów wykorzystywał skrypty composera. Skryptem może być callback PHP lub inna dowolna komenda (wykonywalna).

Poniżej kilka przykładów:

```
{
    "scripts": {
        "check-cs": [
            "php-cs-fixer fix --dry-run --diff"
        ],
        "fix-cs": [
            "php-cs-fixer fix"
        ],
        "test": [
            "phpunit --colors=always"
        ],
        "reset-db": [
            "bin/console doctrine:database:drop --force --if-exists",
            "bin/console doctrine:database:create",
            "bin/console doctrine:migrations:migrate --no-interaction"
        ],
    }
}
```

Skrypty znajdujące się w katalogu `vendor/bin` nie wymagają prefixu, jak w powyższym przykładzie. Jest to domyślne miejsce gdzie poszczególne biblioteki instalują swoje pliki wykonywalne (jak `phpunit` czy `phpstan`). Miejsce to można zmienić używając parametru `bin-dir`.

Skrypty możemy zagnieżdżać i wywoływać kolejno za pomocą operatora `@`. Poniżej przykład skryptu, który możemy używać na przykład w pipeline integracyjnym. Kolejno: sprawdzi czy code style jest poprawny, uruchomi analizę statyczną, uruchomi testy, wygeneruje dokumentację, sprawdzi czy produkcyjny cache da się rozgrzać:

```
{
    "scripts": {
        "tests": [
            "@check-cs",
            "@phpstan",
            "@phpunit",
            "@docs",
            "rm -rf var/cache/prod",
            "bin/console cache:warmup --env=prod"
        ],
    }
}
```

Skrypty mogą również być uruchamiane automatycznie w momencie wywołania zdarzenia (pełna list eventów: https://getcomposer.org/doc/articles/scripts.md#event-names).

Przykładowo:

```
{
    "scripts": {
        "post-update-cmd": "MyVendor\\MyClass::postUpdate",
        "post-package-install": [
            "MyVendor\\MyClass::postPackageInstall"
        ],
        "post-install-cmd": [
            "MyVendor\\MyClass::warmCache",
            "phpunit -c app/"
        ],
        "post-autoload-dump": [
            "MyVendor\\MyClass::postAutoloadDump"
        ],
        "post-create-project-cmd": [
            "php -r \"copy('config/local-example.php', 'config/local.php');\""
        ]
    }
}
```

`post-update-cmd` ten skrypt wykona się po pomyślnym wykonaniu `composer update` lub `composer install` (jeżeli nie było pliku `composer.lock`).

Listę wszystkch dostępnych skryptów możemy uzyskać wykonują polecenie `composer list`.
Za pomocą pola `scripts-descriptions` może zmienić opisy skryptów, które wyświetlają się na liście:

```
{
    "scripts-descriptions": {
        "test": "Run all tests!"
    }
}
```

Więcej na temat skryptów composer można znaleźć na stronie: https://getcomposer.org/doc/articles/scripts.md
