# Testowanie kontraktowe

Moduł 05-03-01 to producent wiadomości, zaś 05-03-02 jego konsument w NodeJS.

Zarówno konsument, jak i producent mogą być napisani w różnych językach i technologiach.

W tym przypadku technologia użyta do testów kontraktowych to Spring Cloud Contract.

Przydatne linki:

* Producent: https://docs.spring.io/spring-cloud-contract/docs/current/reference/html/docker-project.html#docker-how-it-works
* Konsument: https://docs.spring.io/spring-cloud-contract/docs/current/reference/html/docker-project.html#docker-stubrunner

## 05-03-01-producer

Zawiera kod producenta. Wystawia jeden prosty endpoint to sprawdzania oszóstów: `/fraudChcek`:
Kod: `VerificationController.php`


### Uwagi do producenta (jeżeli byłby w innym jeżyki/frameworku niż php/symfony)

W katalogu znajdują się dwa skrypty:

* `what_my_ip.sh` - wydobywający IP, po którym aplikacja może być dostępna dla wygenerowanego testu kontraktowego
* `run_contract_tests.sh`
** Należy w nim zmienić zawartość funkcji uruchamiającej i zatrzymującej Twoją aplikację (`run_app` oraz `stop_app`)
** Uruchamia Twoją aplikację
** Uruchamia testy kontraktowe wobec Twojej aplikacji
** Zatrzymuje Twoją aplikację

## 05-03-02-node-consumer

Konsument napisany w nodejs. Należy uruchomić `run_tests.sh` w celu uruchomienia zaślepek na podstawie artefaktów zainstalowanych w lokalnym repozytorium Mavena (`~/.m2/repository/pl/smartttesting/loan-issuance/0.0.1-SNAPSHOT`). Następnie testy zostaną uruchomione wobec tak uruchomionych zaślepek:

* Dla oszusta
* Dla osoby uczciwej
