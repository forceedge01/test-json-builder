```php

$builder = new Builder();

$schema = 

$validator = new Validator();
$validator->schema([
     'name' => 'string',
     'dob' => 'string:/[0-9]{2}-[0-9]{2}-[0-9]{4}/',
     'age' => 'int',
     'address' => [
         'line 1' => 'string',
         'city' => 'string',
         'post code' => 'string',
         'country' => 'string',
     ]
]);

$builder->setValidator($validator);
$builder->setDataGenerator(new DataGenerator());

$address = new Builder();
$address->add('54 george road', 'line 1')
    ->add('Birmingham', 'city')
    ->add('post code', 'B23 7QB')
    ->add('country', 'United Kingdom');

$builder->add('Abdul', 'name')
    ->add('10-05-1989', 'dob')
    ->add(30, 'age');
    ->add($address, 'address');

// Internally calls the data generator to generate values if necessary.
$json = $builder->validate()->build();

```