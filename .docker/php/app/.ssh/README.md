This should not be replicated in production environments

Add the below to `~/.ssh/config`.

This will automatically disable strict checking for this specific connection, but again, it is not recommended for production since it bypasses a critical security check.

```
Host 127.0.0.1
  Port 2222
  StrictHostKeyChecking no
```

Optionally from the cli, the below can be used;
```ssh -o StrictHostKeyChecking=no -p 2222 user@127.0.0.1```