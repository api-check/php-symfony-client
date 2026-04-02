# ApiCheck Symfony Bundle

A Symfony bundle for the [ApiCheck API](https://apicheck.nl) - address validation, search, and verification for 18 European countries.

## Requirements

- PHP 8.1+
- Symfony 6.4+ | 7.0+
- An ApiCheck API key ([get one here](https://app.apicheck.nl))

## Installation

```bash
composer require api-check/php-symfony-client
```

The bundle is auto-enabled by Symfony Flex.

## Configuration

Add to your `.env`:

```env
APICHECK_API_KEY=your-api-key-here

# Optional - required if your API key has "Allowed Hosts" configured
APICHECK_REFERER=https://yourdomain.com
```

Or create `config/packages/apicheck.yaml`:

```yaml
apicheck:
    api_key: '%env(APICHECK_API_KEY)%'
    referer: '%env(APICHECK_REFERER)%'
```

## Usage

### Via Dependency Injection

```php
use ApiCheck\Api\ApiClient;

class MyService
{
    public function __construct(
        private ApiClient $apiCheck
    ) {}
}
```

## Global Search (Recommended)

The **global search** endpoint is the most powerful way to find addresses. It searches across streets, cities, and postal codes in one query with powerful filtering options.

```php
// Basic search - finds streets, cities, and postal codes
$results = $this->apiCheck->globalSearch('nl', 'Amsterdam', ['limit' => 10]);

foreach ($results->Results as $result) {
    echo "{$result->name} ({$result->type})\n";
}
// Output:
// Amsterdam (city)
// Amsterdamsestraat (street)
// 1012LM (postalcode)

// Filter by city - only return results within a specific city
$results = $this->apiCheck->globalSearch('nl', 'Dam', [
    'city_id' => 2465,
    'limit' => 10
]);

// Filter by street - only return results on a specific street  
$results = $this->apiCheck->globalSearch('nl', '1', [
    'street_id' => 12345,
    'limit' => 10
]);

// Filter by postal code area
$results = $this->apiCheck->globalSearch('nl', 'A', [
    'postalcode_id' => 54321,
    'limit' => 10
]);

// Belgium: filter by locality (deelgemeente)
$results = $this->apiCheck->globalSearch('be', 'Hoofd', [
    'locality_id' => 111,
    'limit' => 10
]);

// Belgium: filter by municipality (gemeente)
$results = $this->apiCheck->globalSearch('be', 'Station', [
    'municipality_id' => 222,
    'limit' => 10
]);
```

### Global Search Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| country | string | Country code (nl, be, lu, de, fr, cz, fi, it, no, pl, pt, ro, es, ch, at, dk, gb, se) |
| query | string | Search term (street name, city name, or postal code) |
| limit | int | Maximum results (default: 10) |
| city_id | int | Filter results to a specific city |
| street_id | int | Filter results to a specific street |
| postalcode_id | int | Filter results to a specific postal code area |
| locality_id | int | Filter results to a specific locality (Belgium) |
| municipality_id | int | Filter results to a specific municipality (Belgium) |

### Result Types

Results include a `type` field indicating what was matched:

- `city` - City/municipality
- `street` - Street name
- `postalcode` - Postal code area

## Address Lookup (Netherlands & Luxembourg)

For exact address lookup by postal code and house number:

```php
// Basic lookup
$address = $this->apiCheck->lookup('nl', [
    'postalcode' => '1012LM',
    'number' => '1'
]);
echo $address->street;  // Damrak
echo $address->city;    // Amsterdam

// With number addition (apartment/suite)
$address = $this->apiCheck->lookup('nl', [
    'postalcode' => '1012LM',
    'number' => '1',
    'numberAddition' => 'A'
]);

// Get available number additions for an address
$additions = $this->apiCheck->getNumberAdditions('nl', '1012LM', '1');
print_r($additions->numberAdditions);  // ['A', 'B', '1-3']
```

## Individual Search Endpoints

For targeted searches when you know exactly what you're looking for:

```php
// Search cities
$cities = $this->apiCheck->search('nl', 'city', ['name' => 'Amsterdam', 'limit' => 10]);

// Search streets
$streets = $this->apiCheck->search('nl', 'street', ['name' => 'Damrak', 'limit' => 10]);
$streets = $this->apiCheck->search('nl', 'street', ['name' => 'Dam', 'city_id' => 2465, 'limit' => 10]);

// Search postal codes
$postalcodes = $this->apiCheck->search('nl', 'postalcode', ['name' => '1012', 'limit' => 10]);

// Search localities (Belgium primarily)
$localities = $this->apiCheck->searchLocality('be', 'Antwerpen', ['limit' => 10]);

// Search municipalities (Belgium primarily)
$municipalities = $this->apiCheck->searchMunicipality('be', 'Antwerpen', ['limit' => 10]);

// Resolve full address using IDs from other searches
$addresses = $this->apiCheck->searchAddress('nl', [
    'city_id' => 2465,
    'number' => '1',
    'numberAddition' => 'A',
    'limit' => 10
]);
```

## Verification

```php
// Verify email
$result = $this->apiCheck->verifyEmail('test@example.com');
echo $result->status;          // valid, invalid, or unknown
echo $result->disposable_email; // true if disposable
echo $result->greylisted;       // true if greylisted

// Verify phone number
$result = $this->apiCheck->verifyPhone('+31612345678');
echo $result->valid;           // true if valid number
echo $result->country_code;    // NL
```

## Supported Countries

### All Search Endpoints (18 countries)
`nl`, `be`, `lu`, `de`, `fr`, `cz`, `fi`, `it`, `no`, `pl`, `pt`, `ro`, `es`, `ch`, `at`, `dk`, `gb`, `se`

### Address Lookup (Netherlands & Luxembourg only)
`nl`, `lu`

## Tips

1. **Use Global Search first** - It's the most flexible and covers all use cases
2. **Filter for precision** - Use city_id, street_id, etc. to narrow down results
3. **Chain searches** - Use Search City to get a city_id, then use it in Global Search or Search Address
4. **Belgium addresses** - Use locality_id and municipality_id filters for precise results

## License

MIT

## Support

- Website: [apicheck.nl](https://www.apicheck.nl)
- Email: support@apicheck.nl
- Docs: [docs.apicheck.nl](https://docs.apicheck.nl)
