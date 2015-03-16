Additional Hydrator Components
==============================

A set of two very simple hydrator helpers to easily hydrate joined query results into their respective nested objects.

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/6bf71bc6-f69c-4381-9cdc-ee2b223e5a15/mini.png)](https://insight.sensiolabs.com/projects/6bf71bc6-f69c-4381-9cdc-ee2b223e5a15)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/manuakasam/join-hydrator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/manuakasam/join-hydrator/?branch=master)
[![Build Status](https://travis-ci.org/manuakasam/join-hydrator.png?branch=master)](https://travis-ci.org/manuakasam/join-hydrator)
[![Coverage Status](https://coveralls.io/repos/manuakasam/join-hydrator/badge.svg?branch=master)](https://coveralls.io/r/manuakasam/join-hydrator?branch=master)


Installation
============

Installation is best done using composr

```
composer require manuakasam/join-hydrator
```

If asked for a version choose `dev-master`


Usage examples
==============

The usage can easily be checked within the `/test` folder. But here they are again:

*Standalone Usage*

```php
<?php
namespace Test {
    use Sam\Hydrator\AggregateHydrator;
    use Sam\Hydrator\OneToOneHydrator;
    
    $data = [
        'foo_one' => 'TEST FOO ONE',
        'foo_two' => 'TEST FOO TWO',
        'bar_one' => 'BAR FIGHT ONE',
        'bar_two' => 'BAR FIGHT TWO',
        'baz'     => 'LONELY BAZ'
    ];
    
    $aggregateHydrator = new AggregateHydrator(
        new ClassMethods(),
        new OneToOneHydrator('foo_', 'setFoo', new Foo()),
        new OneToOneHydrator('bar_', 'setBar', new Bar())
    );
    
    $dummy = $aggregateHydrator->hydrate($data, new Dummy());
    
    get_class($dummy);           // instanceof Dummy
    get_class($dummy->getFoo()); // instanceof Foo()
    get_class($dummy->getBar()); // instanceof Bar()
}
```

*Usage with Zend\Db\TableGateway*

```php
namespace Test {
    use Zend\Db\Adapter\Adapter;
    use Zend\Db\ResultSet\HydratingResultSet;
    use Zend\Db\Sql\Select;
    use Zend\Db\TableGateway\TableGateway;
    use Sam\Hydrator\AggregateHydrator;
    use Sam\Hydrator\OneToOneHydrator;

    $adapter = new Adapter([
        'driver'         => 'Pdo',
        'username'       => 'admin',  //edit this
        'password'       => 'admin',  //edit this
        'dsn'            => 'mysql:dbname=someDB;host=localhost',
        'driver_options' => array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        )
    ]);

    $prototype = new HydratingResultSet(new AggregateHydrator(
        new ClassMethods(),
        new OneToOneHydrator('foo_', 'setFoo', new Foo()),
        new OneToOneHydrator('bar_', 'setBar', new Bar())
    ));
    
    $gateway   = new TableGateway('dummies', $adapter, null, $prototype);

    $resultSet = $gateway->select(function (Select $select) {
        $select->join('foo', 'clients.foo_id = foo.id', [
            'foo_id'   => 'id',
            'foo_bar'  => 'foo_bar',
            'foo_baz'  => 'foo_baz',
        ]);
        $select->join('bar', 'dummies.bar_id = bar.id', [
            'bar_id'   => 'id',
            'bar_bar'  => 'bar_bar',
            'bar_baz'  => 'bar_baz',
        ]);
    });

    $entryOne = $resultSet->current();
    
    get_class($entryOne);           // instanceof Dummy()
    get_class($entryOne->getFoo()); // instanceof Foo()
    get_class($entryOne->getBar()); // instanceof Bar()
}
```
