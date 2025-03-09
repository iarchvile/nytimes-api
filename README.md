# NYT Best Sellers API Wrapper

This is a Laravel-based JSON API that acts as a middleware for the New York Times Best Sellers API. It provides a
simplified interface to query the NYT Best Sellers list with support for filtering by author, ISBN, title, and offset.

## Features

- Filter best sellers by `author`, `isbn[]`, `title`, and `offset`.
- Well-tested with Laravel's HTTP tests.
- Caching for improved performance (temporary, in compliance with NYT API terms).
- API versioning support.

## NYT API Usage

Data obtained through the NYT API belongs to The New York Times and is used in accordance with
their [Terms of Use](https://developer.nytimes.com/terms).

### Branding and Attribution

This project uses data from The New York Times API. Please follow
their [branding guidelines](https://developer.nytimes.com/branding) when using this API.
