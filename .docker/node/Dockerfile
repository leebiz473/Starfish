ARG ALPINE_VERSION
FROM alpine:${ALPINE_VERSION}

## Install Node.js and npm
RUN apk add --no-cache nodejs npm

## Verify installation
RUN node --version && npm --version

## Set the working directory
ARG APP_CODE_PATH="/var/www/app"
WORKDIR "$APP_CODE_PATH"

## Copy package.json and package-lock.json (or yarn.lock)
#COPY package*.json ./

## Install dependencies
RUN #npm install

## Copy the rest of the application code
#COPY . .

## Expose the port your app runs on
EXPOSE 3000

## Command to run your application
CMD ["npm", "start"]