## HEADS UP.. 

if you run the tests and they fail. set the search_interval to 5ms like this (using marvel sense)
`PUT mantelzorg/_settings
{
    "index.refresh_interval":"5ms"
}
`