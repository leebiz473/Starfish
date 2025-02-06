# Use the official Redis image based on Alpine
FROM redis:alpine

# Optionally, copy custom Redis configuration if you have any
COPY ./.docker/redis/redis.conf /usr/local/etc/redis/redis.conf

# Expose Redis port
EXPOSE 6379

# Optionally, set the entrypoint to run Redis with a custom config (if you are using one)
CMD ["redis-server", "/usr/local/etc/redis/redis.conf"]