#!/bin/bash

set -o errexit

# Function uruchamiająca aplikację
function run_app() {
  symfony server:start --daemon --no-tls
}

# Funkcja zatrzymująca aplikację
function stop_app() {
  symfony server:stop
}

# Funkcja uruchamiająca testy kontraktowe
# Zakłada:
# * [PUBLISH_ARTIFACTS] - publikujemy zaślepki
# * [PUBLISH_ARTIFACTS_OFFLINE] - publikujemy zaślepki do lokalnego repozytorium Mavena
#       (na potrzeby demonstracji). Moglibyśmy trzymać je też w Artifactory lub w Gicie
# * kontrakty zostały zdefiniowane w podkatalogu [contracts]
# * efekty uruchomienia testów kontraktowych będą obecne w podkatalogu [build/spring-cloud-contract/output]
function run_contract_tests() {
  mkdir -p build/spring-cloud-contract/output
  docker run --rm \
          -e "APPLICATION_BASE_URL=${APPLICATION_BASE_URL}" \
          -e "PUBLISH_ARTIFACTS=false" \
          -e "PROJECT_NAME=${PROJECT_NAME}" \
          -e "PROJECT_GROUP=${PROJECT_GROUP}" \
          -e "PROJECT_VERSION=${PROJECT_VERSION}" \
          -e "PUBLISH_ARTIFACTS_OFFLINE=true" \
          -v "${CURRENT_DIR}/contracts/:/contracts:ro" \
          -v "${CURRENT_DIR}/build/spring-cloud-contract/output:/spring-cloud-contract-output/" \
          -v "${HOME}/.m2/:/root/.m2/:rw" \
          springcloud/spring-cloud-contract:"${SC_CONTRACT_DOCKER_VERSION}"
}

# Na koniec uruchomienia testu, zatrzymaj aplikację
trap 'stop_app' EXIT
# Jeśli aplikacja działa, zatrzymaj ją
stop_app || echo "Failed to kill app"
# Uruchom aplikację w osobnym procesie
run_app

# Konfiguracja testów kontraktowych
# Wersja obrazu dockerowego Spring Cloud Contract
SC_CONTRACT_DOCKER_VERSION="2.2.4.RELEASE"
# Zewnętrzne IP, na którym uruchamiamy Twoją aplikację i
# po którym wygenerowane testy w obrazie dockerowym będą mogły wysłać Twojej aplikacji żądanie
APP_IP="$( ./whats_my_ip.sh )"
# Port na którym uruchomiona jest Twoja aplikacja
APP_PORT="8000"
# Zestawienie pełnego URL Twojej aplikacji
APPLICATION_BASE_URL="http://${APP_IP}:${APP_PORT}"
CURRENT_DIR="$( pwd )"
# Nazwa Twojego projektu
PROJECT_NAME="loan-issuance"
# Nazwa grupy, w której Twój projekt się znajduje
PROJECT_GROUP="pl.smarttesting"
# Wersja projektu
PROJECT_VERSION="0.0.1-SNAPSHOT"

echo "Spring Cloud Contract Version [${SC_CONTRACT_DOCKER_VERSION}]"
echo "Application URL [${APPLICATION_BASE_URL}]"
echo "Project Version [${PROJECT_VERSION}]"

run_contract_tests
