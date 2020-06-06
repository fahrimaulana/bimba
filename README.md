# biMBA
Application web untuk manajemen biMBA

## Configuration
- Install wkhtmltopdf version 0.12.0 from [wkhtmltopdf site.](https://wkhtmltopdf.org/downloads.html)
- Edit snappy.php For Linux / OSX: 'binary' => '/usr/local/bin/wkhtmltopdf', For Windows: 'binary' => '"C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf"',
- PHP memory_limit 1024MB
- PHP max_execution_time 10800
- PHP max_input_vars 5000
- Set .env
- Composer Install
- php artisan migrate --seed
- php artisan optimize:clear
