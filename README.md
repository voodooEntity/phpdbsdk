# SlingshotDB PHP SDK      
This repo contains a simple-to-use php sdk to work with Slingshot db. 


## Examples    
All the following examples are based on this input example
```javascript
{
    "Type" : "IP",
    "Value" : "127.0.0.1",
    "Properties" : {
        "created_at" : "sometimestamp"
    },
    "Context" : "Pentest",
    "Children" : [
        {
            "Type" : "Port",
            "Value" : "80",
            "Context" : "Pentest",
            "Properties" : {
                "created_at" : "sometimestamp"
            },
            "Children" : [
                {
                    "Type" : "State",
                    "Value" : "Open",
                    "Properties" : {
                        "created_at" : "sometimestamp"
                    }
                }
            ]
        },
        {
            "Type" : "Port",
            "Value" : "443",
            "Context" : "Pentest",
            "Properties" : {
                "created_at" : "sometimestamp"
            },
            "Children" : [
                {
                    "Type" : "State",
                    "Value" : "Closed",
                    "Properties" : {
                        "created_at" : "sometimestamp"
                    }
                }
            ]
        }
    ]
}
```


### API functions    

```php
require_once __DIR__ . '/vendor/autoload.php';
use Slingshot\Connection;
use Slingshot\Api;

$connection = new Connection("127.0.0.1",8090,"v1");
$api = new Api();

// map a nested structure by json
$json = '{"Type":"IP","Value":"127.0.0.1","Properties":{"created_at":"sometimestamp"},"Context":"Pentest","Children":[{"Type":"Port","Value":"80","Context":"Pentest","Properties":{"created_at":"sometimestamp"},"Children":[{"Type":"State","Value":"Open","Properties":{"created_at":"sometimestamp"}}]},{"Type":"Port","Value":"443","Context":"Pentest","Properties":{"created_at":"sometimestamp"},"Children":[{"Type":"State","Value":"Closed","Properties":{"created_at":"sometimestamp"}}]}]}';
$api->mapJson($json);

// get all parents to a specific entity
$entities = $api->getParentEntities("Port",1);

// get all children to a specific entity
$entities = $api->getChildEntities("Port",1);

// get all entities with a specific value
$entities = $api->getEntitiesByValue("127.0.0.1");

// get all entities with a specific type and value
$entities = $api->getEntitiesByTypeAndValue("IP","127.0.0.1");

// get all entities of a specific type
$entities = $api->getEntitiesByType("IP");
```

### Entity Model    

Create an new Entity using the model
```php
require_once __DIR__ . '/vendor/autoload.php';
use Slingshot\Connection;
use Slingshot\Models\Entity;

$connection = new Connection("127.0.0.1",8090,"v1");

$entity = new Entity();
$entity->setType("Testtype");
$entity->setValue("Shit works");
$entity->setContext("it  works!!!!");
$entity->save(true);
var_dump($entity->getID());

```

Read an entity from database based on type and id using the model
```php
require_once __DIR__ . '/vendor/autoload.php';
use Slingshot\Connection;
use Slingshot\Models\Entity;

$connection = new Connection("127.0.0.1",8090,"v1");

$entity = new Entity("Testtype",1);
```


Update and delete an entity afterwards
```php
require_once __DIR__ . '/vendor/autoload.php';
use Slingshot\Connection;
use Slingshot\Models\Entity;

$entity = new Entity("Testtype",1);

// first update example
$entity->setContext("Example updated context");
$entity->save();

//than delete example
$entity->delete();
```

### Relation Model   

Create a new Relation using the model
```php
require_once __DIR__ . '/vendor/autoload.php';
use Slingshot\Connection;
use Slingshot\Models\Relation;

$relation = new Relation();
$relation->setSourceType("IP");
$relation->setSourceID(1);
$relation->setTargetType("State");
$relation->setTargetID(1);
$relation->save(true);
```


Read a relation from database based on source type and id and target type and id using the model
```php
require_once __DIR__ . '/vendor/autoload.php';
use Slingshot\Connection;
use Slingshot\Models\Relation;

$relation = new Relation("IP",1,"Port",1);
```

Update and delete an relation afterwards
```php
require_once __DIR__ . '/vendor/autoload.php';
use Slingshot\Connection;
use Slingshot\Models\Relation;

$relation = new Relation("IP",1,"Port",1);

// first update example
$relation->setContext("Example updated context");
$relation->save();

//than delete example
$relation->delete();
```
