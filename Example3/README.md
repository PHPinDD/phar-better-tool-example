# Example 3 - WebPHAR

## 1. Build the PHAR

```bash
php Example3/makephar.php
```

## 2. Run the WebPHAR with PHP's build in webserver

```bash
php -S localhost:8080 -file Example3/web.phar
```

## 3. Browse the phar

Go to your browser an open: http://localhost:8080/