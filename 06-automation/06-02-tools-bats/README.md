# 06-02 Testowanie Skryptów z Frameworkiem Bats

Całe repozytorium kodu SmartTesting budowane jest Composerem (https://getcomposer.org/), stąd obecność `composer.json` w tym katalogu. Composer pozwala na definiowanie własnych skryptów (więcej będzie w module `06-02-tools-composer`). W tym module możemy użyć następującycj poleceń aby zainstalować zależności

```bash
$ composer install-bats
$ composer install-zsd
```

żeby uruchomić testy Bats / Shellcheck / Editorconfig (https://editorconfig.org/) wpisujemy:

```bash
$ composer bats
$ composer zsd
```

Przykładowy output:
```
> build/bats/bin/bats src/test/bats
 ✓ should curl request to an external website
 ✓ should return a response from an external website
 ✓ should not fail when request retrieval failed

3 tests, 0 failures
```

```
> tools/build-helper.sh generate-zsd
zsh 5.8 (x86_64-ubuntu-linux-gnu)
/var/www/smarttesting-php/06-automation/06-02-tools-bats/src/main/bash /var/www/smarttesting-php/06-automation/06-02-tools-bats
Zsh control binary is: `zsh'
== zsd-transform starting for file `script.sh' (1-st pass)
Extracted   2-line function `request'...
Generated body of script `script.sh' (  9 lines)
== zsd-detect starting for file `script.sh' (2nd pass)
Written call tree (1 callers)
Written reverse call tree (1 called functions)
Extracted   1-line comment of `request'...
Generated 1 trees
== zsd-to-adoc starting for file `script.sh' (3rd pass)
AsciiDoc generated under `zsdoc/script.sh.adoc'. Found 0 env-vars sections.
You can run `asciidoctor script.sh.adoc' to generate HTML or commit
script.sh.adoc into GitHub (GitHub does render *.adoc files).
/var/www/smarttesting-php/06-automation/06-02-tools-bats
```

## Kod

W pliku `tools/build-helper.sh` mamy skrypty Bashowe pomagające przy buildzie. Skrypt potrafi np. dociągnąć różne aplikacje.

W katalogu `src/main/bash` mamy skrypty Bashowe, które chcemy przetestować.

W katalogu `src/test/bats` mamy skrypty Bats do testów skryptów bashowych.

W katalogu `src/main/bash/zsdoc` mamy dokumentację w Asciidoctor skryptów Bashowych (przez projekt https://github.com/zdharma/zshelldoc)
