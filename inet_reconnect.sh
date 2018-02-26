#!/bin/sh

# Script checks the internet connection and
# executes reconnect command if it is absent.

if [ "$#" -ne 3 ]; then
    echo "Invalid input params. Must be: \"CHECK_ADDRESS\" \"MODEM_ADDRESS\" \"MODEM_PASSWORD\". Exit."
    exit 1
fi

# Settings
CHECK_ADDRESS=$1
MODEM_ADDRESS=$2
MODEM_PASSWORD=$3
RECONNECT_CMD="$(dirname $0)/inet_novatel4620.php $MODEM_ADDRESS $MODEM_PASSWORD"

# Run
echo
echo [ $(date +%Y-%m-%d\ %H:%M:%S,%3N) ]

echo "Check address: "$CHECK_ADDRESS
echo "Modem address: "$MODEM_ADDRESS

wget -q --spider $MODEM_ADDRESS
RESPONSE=$?
echo "Wget modem response: "$RESPONSE
if [ "$RESPONSE" != "0" ]; then
    echo "Error: no access to modem address. Exit."
    exit 1
fi

wget -q --spider $CHECK_ADDRESS
RESPONSE=$?
echo "Wget check response: "$RESPONSE

ACTIVE=
echo -n "Result: "
if [ "$RESPONSE" = "0" ]; then
    echo "Online"
    ACTIVE=true
else
    echo "Offline"
    ACTIVE=false
fi

if [ "$ACTIVE" != "true" ]; then
  echo 'Reconnecting...'
  $RECONNECT_CMD 2>&1
fi
