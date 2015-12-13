# installation

## testing environment

whenever you do a migrate:refresh or setup a new instance, don't forget to seed the meta values by running

`
php artisan db:seed --class=MetaContextValueSeeder
`

run this through marvel, so we can run tests faster.

`PUT mantelzorg/_settings
{
    "index.refresh_interval":"5ms"
}
`