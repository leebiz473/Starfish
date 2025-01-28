#!/bin/bash

set -e

# Fix for host.docker.internal not existing
HOST_DOMAIN="host.docker.internal"

# Ensure 'dig' is installed
if ! command -v dig &> /dev/null; then
    echo "Error: 'dig' command is not available. Please install it."
    exit 1
fi


# Get host IP (assuming it's the first IP in the default route)
# Check if host exists
# Detect OS and fetch gateway IP
# Check if host.docker.internal exists
if dig ${HOST_DOMAIN} | grep -q 'NXDOMAIN'; then
    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        # Linux
        HOST_IP=$(ip route | awk 'NR==1 {print $3}')
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        # macOS
        HOST_IP=$(route -n get default | awk '/gateway/ {print $2}')
    else
        echo "Unsupported OS: $OSTYPE"
        exit 1
    fi

    if [[ -z "$HOST_IP" ]]; then
        echo "Error: Could not determine the host IP."
        exit 1
    fi

    # Add host.docker.internal to /etc/hosts if it doesn't already exist
    if ! grep -q "$HOST_DOMAIN" /etc/hosts; then
        printf "%s\t%s needs to be added to /etc/hosts\n" "$HOST_IP" "$HOST_DOMAIN"
        echo -e "$HOST_IP\t$HOST_DOMAIN" | sudo tee -a /etc/hosts > /dev/null
    fi
else
    echo "$HOST_DOMAIN already exists or resolves as $HOST_IP\t$HOST_DOMAIN"
fi

# Pass control to the CMD or arguments provided to the script
exec "$@"