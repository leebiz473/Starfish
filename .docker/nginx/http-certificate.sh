#!/bin/sh

# Define variables
#DOMAIN="starfish.envx"

## Retrieve the domain name from the environment variable
DOMAIN=${DOMAIN_NAME}
CERT_DIR="/etc/ssl/certs"
NGINX_CONF="/etc/nginx/conf.d/default.conf"
KEY_FILE="${CERT_DIR}/secret.${DOMAIN}.key"
CERT_FILE="${CERT_DIR}/server.${DOMAIN}.crt"
#----------------------------------------------------------------
# Ensure DOMAIN_NAME variable is set and exists
if [ -z "$DOMAIN" ]; then
  echo "Error: DOMAIN_NAME environment variable is not set!"
  exit 1
fi
#----------------------------------------------------------------
## Replace placeholders in the Nginx configuration template
sed -i "s/\${DOMAIN_NAME}/$DOMAIN/g" "$NGINX_CONF"
#----------------------------------------------------------------
echo "Nginx configuration generated for domain: $DOMAIN"
echo "#----------------------------------------------------------------"
#----------------------------------------------------------------
echo "Creating HTTPS certificate for domain: $DOMAIN"
#----------------------------------------------------------------
# Ensure the certificate directory exists
echo "Creating directory for certificates: $CERT_DIR"
mkdir -p "${CERT_DIR}"
echo "#----------------------------------------------------------------"
#----------------------------------------------------------------
# Generate the private key
echo "Generating Key: $KEY_FILE"
openssl genrsa -out "${KEY_FILE}" 2048
#----------------------------------------------------------------
# Generate the certificate
openssl req -new -x509 -key "${KEY_FILE}" -out "${CERT_FILE}" -days 365 -subj "/C=US/ST=State/L=City/O=Organization/OU=DevOps/CN=${DOMAIN}"
#----------------------------------------------------------------
# Set correct permissions
chmod 644 "$CERT_FILE" "$KEY_FILE"  # Readable by all containers
#----------------------------------------------------------------
echo "#----------------------------------------------------------------"
# Output success message
echo "Self-signed certificate created for ${DOMAIN}:"
echo "Private Key: ${KEY_FILE}"
echo "Certificate: ${CERT_FILE}"
echo "#----------------------------------------------------------------"
echo -e "\n"
#----------------------------------------------------------------
echo "Nginx configuration:"
cat /etc/nginx/nginx.conf
#----------------------------------------------------------------
echo -e "\n"
#----------------------------------------------------------------
echo "Site configuration:"
cat /etc/nginx/conf.d/default.conf
echo -e "\n"