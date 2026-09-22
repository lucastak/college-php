# PHP

## Iniciar mysql e phpmyadmin:
```bash
brew services start mysql && php -S localhost:8080 -t $(brew --prefix)/share/phpmyadmin
```