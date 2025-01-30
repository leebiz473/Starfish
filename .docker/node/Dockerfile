ARG ALPINE_VERSION
FROM alpine:${ALPINE_VERSION}

# Define build arguments for Node.js and npm versions
ARG NODE_VERSION
ARG NPM_VERSION

# Install specific versions of Node.js and npm using the build arguments
RUN apk add --no-cache nodejs-current=${NODE_VERSION} npm=${NPM_VERSION}

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