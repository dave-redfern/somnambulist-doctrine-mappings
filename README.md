# Moved to https://github.com/dave-redfern/somnambulist-domain

This repository has been archived. Please update to the combined package.s

## Doctrine Mappings for Value Objects and Enumerations

Provides a basic set of mapping information for the somnambulist/value-objects library for
use with Doctrine. Mappings are available for Doctrine (.dcm.yml) and Symfony (.orm.yml).
The mappings are symlinked from symfony to doctrine.

A `Bootstrapper` is included for automatically registering the value-object enumerations
as Doctrine types.

### Requirements

 * PHP 7+
 * Doctrine ORM 2.5+

### Installation

Install using composer, or checkout / pull the files from github.com.

 * composer require somnambulist/value-object-doctrine-mappings

### Usage

Copy or link the mapping files to your project in the Doctrine configuration. These are
needed per entity manager. It is highly recommended to extend the value-objects to your
own and then copy and adapt the mappings as you need.

Remember: value-objects are part of your domain model and should be treated with care.

__Note:__ enumerations are used in these mappings.

#### Register Enumeration Handlers

To register the enumeration handlers add the following to your applications bootstrap
code (e.g.: AppBundle::boot or AppServiceProvider::register|boot):

    Somnambulist\Doctrine\Bootstrapper::registerEnumerations();

This will pre-register the following enumerations:

 * Geography\CountryCode
 * Geography\Srid
 * Measure\AreaUnit
 * Measure\DistanceUnit
 * Money\CurrencyCode
 
In addition extra helpers are registered to allow the Country and Currency value objects
to be used as enumerations. These are stored using the CountryCode and CurrencyCode
values and restored using the `::create()` method.

#### Register Custom Types

Custom types are included for:

 * datetime
 * datetimetz
 * date
 * time
 * json
 * jsonb
 * json_collection

The date types override the default Doctrine types and uses the VO DateTime that is an
extended DateTimeImmutable object.

json, jsonb and json_collection are equivalent and allow JSON data to be converted to and
from a Collection object instead of a plain array.

To register the types add the following to your application bootstrap:

    Somnambulist\Doctrine\Bootstrapper::registerTypes();

#### Mapping Files

To use the types and enumerations, in your mapping files set the type appropriately:

```yaml
fields:
    createdAt:
        type: datetime
    
    attributes:
        type: json

    country:
        type: Somnambulist\ValueObjects\Types\Geography\Country
    
    currency:
        type: Somnambulist\ValueObjects\Types\Money\Currency
```

To use the value-objects:

```yaml
embedded:
    contact:
        class: Somnambulist\ValueObjects\Types\Identity\EmailAddress
        
    homepage:
        class: Somnambulist\ValueObjects\Types\Web\Url
```

Or in XML format:

```xml
<entity name="My\Entity">
    <embedded name="contact" class="Somnambulist\ValueObjects\Types\Identity\EmailAddress" />
    <embedded name="homepage" class="Somnambulist\ValueObjects\Types\Web\Url" />
</entity>
```

When using embeddables, be sure to have added the necessary mapping files.

### Configuring Types for Symfony

Within a Symfony project, add a new mapping area to your orm configuration within the `doctrine` section:

```yaml
doctrine:
    # snip ...
    orm:
        mappings:
            App\Entities:
                mapping:   true
                type:      yml
                dir:       '%kernel.project_dir%/config/mappings/entities'
                is_bundle: false
                prefix:    App\Entities

            Somnambulist\ValueObjects\Types:
                mapping:   true
                type:      xml
                dir:       '%kernel.project_dir%/config/mappings/somnambulist'
                is_bundle: false
                prefix:    Somnambulist\ValueObjects\Types
```

Then either copy or symlink the appropriate config files from vendor config folder to your projects
mapping config section. If you have different requirements for field type, copy and update as appropriate.
It is recommended to copy and not link the mapping files to avoid issues with this library changing.

### Links

 * [Doctrine](http://doctrine-project.org)
 * [Enumeration Bridge](https://github.com/dave-redfern/somnambulist-doctrine-enum-bridge)
 * [Value Objects](https://github.com/dave-redfern/somnambulist-value-objects)
