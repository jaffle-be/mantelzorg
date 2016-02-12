# installation

## testing environment

whenever you do a migrate:refresh or setup a new instance, don't forget to seed the meta values by running

`
php artisan db:seed --class=MetaContextValueSeeder
`

if you're getting errors when downloading pdf, make sure to
 
`sudo apt-get install libxrender1`