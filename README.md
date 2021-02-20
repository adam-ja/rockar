# Rockar Tech Test

This is my solution to the [Rockar tech test](https://bitbucket.org/domsuttonrockar/rockar-tech-test).

## Instructions

### Requirements

- PHP 8
- composer

### Installation

```
composer install
```

### Running tests

```
vendor/bin/phpunit
```
or, for the prettier output
```
php artisan test
```

### Using the API

Set the app running:
```
php artisan serve
```

Then send a `GET` request to either of the endpoints. Example URLs are:
- http://127.0.0.1:8000/api/customers?identifierField=forename&identifier=Dominic&fields[]=forename&fields[]=surname&fields[]=email&fields[]=contact_number&fields[]=postcode
- http://127.0.0.1:8000/api/products?identifierField=vin&identifier=1G6DP567X50115827&fields[]=make&fields[]=model&fields[]=colour&fields[]=price

## Assumptions/interpretations and decisions made

### Request and response

I took the request design to mean that clients of the API could request a subset of attributes (the `fields`) of a product or customer using any field (indicated by the `identifierField`, with the value in `identifier`) as a unique identifier. Therefore, the endpoints I have implemented are simple get endpoints which return a single resource. This works for the simple data set provided in the CSVs as each row has unique values for every field.

Of course, in a real-world application with a larger data set, this would not be the case. Instead, we would have two `GET` endpoints for each resource. For example, the `/customers` endpoint would expect one or more key-value pairs of fields in the query params to search by and return a collection of all matching customers. A `/customers/{id}` endpoint would then exist which would use a unique identifier (most likely a UUID or auto-incrementing primary key, but possibly the email address if that was deemed appropriate) to find and return a single customer.

As for the response design, I have not returned a status in the body but all responses have appropriate HTTP status codes. On a successful response, the body is simply the JSON representation of the fields requested for the matching resource. Appropriate bodies are also returned for not found or bad request responses.

There is very little formatting done on the response - only the price is cast to an integer in the `ProductController`. If there were more formatting to do, this would be pulled out into a separate formatter or view model, but this felt like overkill to cast a single value.

### Framework

I realise that using the full Laravel framework could be considered overkill for this task. However, I understand that it is part of your tech stack and I believe that my submission can serve to demonstrate both some of my general OOP understanding and some of my familiarity with Laravel. Plus, I'm a big believer in not reinventing the wheel!

The default Laravel installation is all in the initial commit so you can review the code I've actually written [here](https://github.com/adam-ja/rockar/compare/8d278a6...master).

### Database repository

The implementation of the `DatabaseRepository` is admittedly not ideal. It is mostly intended to demonstrate the common interface and the ability to switch between the CSV and database sources through configuration. Similarly, the unit tests of this class do stub out the database connection as instructed but they are not how I would typically test database interactions as they are quite brittle and focus more on the implementation than the result.

In a more fully fleshed out application, we would have a test database to connect to, and the customer and product would be represented by Eloquent models. The repository would then be tested at an integration rather than unit level, with factories used to seed fake data (rolled back on tear down) and the tests asserting that the appropriate models are retrieved for the given input, rather than mocking each step of the query builder as in my unit test.

### Standards

I've followed the standards that I'm used to but can adapt to a team consensus - consistency is the important thing.
- PSR-12 coding standards.
- Docblocks are only included where they add some detail that is not already provided by type hints.
- There are not many comments as I would hope that my code is mostly self-documenting. I do of course comment where something more complicated is going on, or where some business logic needs explaining.
