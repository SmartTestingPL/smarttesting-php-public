#!/usr/bin/env bash

set -o errexit

function assertEquals() {
  local result="${1}"
  local expectedResult="${2}"
  if [ "${result}" != "${expectedResult}" ]; then
    echo "FAILED TO RETRIEVE PROPER RESULT. GOT [${result}], SHOULD GET [${expectedResult}]"
    exit 1
  else
    echo "Assertion successful"
  fi
}

# setup
./stop_stubrunner.sh
# cleanup
trap './stop_stubrunner.sh' EXIT

# given - running stubs
./start_stubrunner.sh

# when - fraud
result="$(node/node app)"

# then
expectedResult="401"
assertEquals "${result}" "${expectedResult}"

# when - non fraud
result="$(node/node non_fraud_app.js)"

# then
expectedResult="200"
assertEquals "${result}" "${expectedResult}"
