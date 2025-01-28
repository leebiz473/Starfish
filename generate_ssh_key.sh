#!/bin/bash

# Load environment variables from the .env file
if [ -f .env ]; then
  source .env
else
  echo "Error: .env file not found!"
  exit 1
fi

# Check if APP_KEY_DIR is set
if [ -z "$APP_KEY_DIR" ]; then
  echo "Error: APP_KEY_DIR is not set in the .env file!"
  exit 1
fi

# Check if EMAIL_ADDRESS is set
if [ -z "$EMAIL_ADDRESS" ]; then
  echo "Error: EMAIL_ADDRESS is not set in the .env file!"
  exit 1
fi

# Variables
KEY_DIR=$APP_KEY_DIR
KEY_NAME="appkey"
KEY_PATH="$KEY_DIR/$KEY_NAME"

# Ensure the directory exists
mkdir -p "$KEY_DIR"

# Generate SSH key without passphrase
ssh-keygen -t ed25519 -C "$EMAIL_ADDRESS" -f "$KEY_PATH" -N ""

# Output message
echo "SSH key generated:"
echo "Private Key: $KEY_PATH"
echo "Public Key: $KEY_PATH.pub"