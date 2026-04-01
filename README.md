# ApiCheck Symfony Bundle

A thin Symfony wrapper for the [ApiCheck PHP Client](https://github.com/api-check/php-client).

ApiCheck helps you validate customer data - addresses, emails, and phone numbers.

## Requirements

- PHP 8.1+
- Symfony 6.4+ | 7.0+
- An ApiCheck API key ([get one here](https://app.apicheck.nl/authentication/register))

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

    public function getAddress(string $postalcode, int $number): object
    {
        return $this->apiCheck->lookup('nl', [
            'postalcode' => $postalcode,
            'number' => $number,
        ]);
    }
}
```

## Available Methods

All methods from [api-check/php-client](https://github.com/api-check/php-client):

### Lookup API (NL, LU)
- `lookup($country, $query)` - Look up address by postal code + house number
- `getNumberAdditions($country, $postalcode, $number)` - Get available number additions

### Search API (18 European countries)
- `search($country, $type, $query)` - Search by type: city, street, postalcode, address
- `globalSearch($country, $query, $options)` - Global search across all scopes
- `searchLocality($country, $name, $options)` - Search localities
- `searchMunicipality($country, $name, $options)` - Search municipalities
- `searchAddress($country, $params)` - Resolve full address from IDs
- `getSupportedSearchCountries()` - List supported countries

### Verify API
- `verifyEmail($email)` - Validate email address
- `verifyPhone($number)` - Validate phone number

## Supported Countries

### Lookup
- Netherlands (nl), Luxembourg (lu)

### Search & Verify
18 European countries: nl, be, lu, fr, de, cz, fi, it, no, pl, pt, ro, es, ch, at, dk, gb, se

## License

MIT

## Support

- Website: [apicheck.nl](https://www.apicheck.nl)
- Email: support@apicheck.nl
- Docs: [apicheck.nl/documentation](https://apicheck.nl/documentation)
