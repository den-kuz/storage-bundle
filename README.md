Symfony Bundle providing storage folders as services

Add config to `config/packages/d3n_storage.yaml`:

```yaml
parameters:
  storage_dir: '%kernel.project_dir%/var/storage'

d3n_storage:
  default: main
  storages:
    main:         '%storage_dir%/main'
    some_storage: '%storage_dir%/some_storage'
```

And then use it in your services:

```php
use D3N\StorageBundle\Storage\StorageInterface;

// ...

// argument name = camelCased name of storage name
public function __construct(private StorageInterface $someStorage)
{
}
```
