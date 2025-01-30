ARG ALPINE_VERSION
FROM alpine:${ALPINE_VERSION} AS builder
#----------------------------------------------------------------
## Install required packages
RUN apk add --no-cache curl bash git
#----------------------------------------------------------------
## Set build arguments for Node.js and npm version
ARG TARGET_NODE_VERSION
ARG TARGET_NPM_VERSION
#----------------------------------------------------------------
## Set environment variables to prevent interactive prompts
ENV NODE_VERSION=${TARGET_NODE_VERSION} \
    NPM_VERSION=${TARGET_NPM_VERSION} \
    APP_CODE_PATH="/var/www/app"
#----------------------------------------------------------------
## Install Node.js and npm (specific versions)
RUN apk add --no-cache \
    curl \
    bash \
    git \
    ca-certificates \
    tar \
    xz \
    && update-ca-certificates
#----------------------------------------------------------------
## Download and install specific Node.js version
RUN curl -fsSL "https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.xz" -o /tmp/node.tar.xz \
    && ls -lh /tmp/node.tar.xz \
    && tar -xJf /tmp/node.tar.xz -C /usr/local --strip-components=1 \
    && rm /tmp/node.tar.xz \
    && ln -s /usr/local/bin/node /usr/bin/node \
    && ln -s /usr/local/bin/npm /usr/bin/npm
#----------------------------------------------------------------
## Upgrade npm to the specified version
RUN npm install -g npm@${NPM_VERSION} && npm -v
#----------------------------------------------------------------
## Verify installed versions
RUN node -v && npm -v
#----------------------------------------------------------------
## Use a lightweight final image for production
FROM alpine:${ALPINE_VERSION} AS runtime
#----------------------------------------------------------------
## Install only necessary runtime dependencies
RUN apk add --no-cache \
    bash \
    git \
    ca-certificates \
    && update-ca-certificates
#----------------------------------------------------------------
## Set working directory
ARG APP_CODE_PATH="/var/www/app"
WORKDIR "$APP_CODE_PATH"
#----------------------------------------------------------------
## Copy Node.js from the builder stage to keep the final image clean
COPY --from=builder /usr/local /usr/local
#----------------------------------------------------------------
## Ensure `node` and `npm` are globally available
RUN ln -s /usr/local/bin/node /usr/bin/node \
    && ln -s /usr/local/bin/npm /usr/bin/npm
#----------------------------------------------------------------
## Keep container running
CMD ["tail", "-f", "/dev/null"]