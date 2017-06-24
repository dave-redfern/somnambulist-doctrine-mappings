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

    fields:
        createdAt:
            type: datetime
        
        attributes:
            type: json

        country:
            type: Somnambulist\ValueObjects\Types\Geography\Country
        
        currency:
            type: Somnambulist\ValueObjects\Types\Money\Currency

To use the value-objects:

    embedded:
        contact:
            class: Somnambulist\ValueObjects\Types\Identity\EmailAddress
            
        homepage:
            class: Somnambulist\ValueObjects\Types\Web\Url

When using embeddables, be sure to have added the necessary mapping files.

### Links

 * [Doctrine](http://doctrine-project.org)
 * [Enumeration Bridge](https://github.com/dave-redfern/somnambulist-doctrine-enum-bridge)
 * [Value Objects](https://github.com/dave-redfern/somnambulist-value-objects)
