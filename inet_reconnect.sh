#!/bin/sh

# Script checks the internet connection and
# executes reconnecting command if it is broken.

if [ "$#" -ne 3 ]; then
    echo "Error: Invalid input params, must be: \"CHECK_ADDRESS\" \"MODEM_ADDRESS\" \"MODEM_PASSWORD\". Exit."
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
    echo "Error: No access to modem address. Exit."
    exit 1
fi

wget -q --spider $CHECK_ADDRESS
RESPONSE=$?
echo "Wget check response: "$RESPONSE

echo -n "Result: "
if [ "$RESPONSE" = "0" ]; then
    echo "Online. Nothing to do."
else
    echo "Offline. Reconnecting..."
    $RECONNECT_CMD 2>&1
fi
