# Application Requirements

PHP >= 7.2
OpenSSL PHP Extension
PDO PHP Extension
Mbstring PHP Extension

# Feature Application
- soft delete
- login admin with oauth2
- orders cd
- orders return cd
- calculation late charge return
- calculation total orders when users return cd

# Module Admin
- control authorization data

# Module CD
- crud
- soft delete
- check stock cd

# Module Category CD
- crud
- soft delete

# Module Orders
- multiple orders cd
- calculate late return
- return orders

# How to run Application
- composer install
- set and replace env or download this env (https://drive.google.com/file/d/1BeO7eajICibFBcCpIX6Xsylwr_QxXQiK/view?usp=sharing)

CACHE_DRIVER=array
QUEUE_DRIVER=array
DB_STRICT_MODE=false

- composer dump-autoload
- php artisan migrate
- php artisan db:seed
- php artisan passport:install

# API Documentation
- Base route http://localhost/rental -> check version lumen
- checkit out https://drive.google.com/file/d/1_eVflwB9Y3kkQfOBIAbQqyAgi2_ljNdT/view?usp=sharing


