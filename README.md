# Lottery Data Fetcher

### Instalation

Use composer to install dependencies

```
composer install
```

### Command

Use symfony command to fetch lottery data and save them into json file. 
Files are stored in var/lottery_results directory.

```
php ./import.php app:lottery:save-data
```