## ThreatExchange Client
Interact with the ThreatExchange API via PHP.

```
composer require certly/threatexchange
```

### Documentation
Initialize a `ThreatExchange` object with your application ID and application secret. All API calls will return a `stdClass` with the result.
```
$tx = new Certly\ThreatExchange\ThreatExchange("123", "abc");
echo var_dump($tx->getThreatExchangeMembers());
```

You can call other endpoints not directly implemented with the `call` function. You'll be authenticated automatically.

```
$tx = new Certly\ThreatExchange\ThreatExchange("123", "abc");
$tx->call("/898557073557972/descriptors", "GET");
```

If you need to pass parameters for a GET or POST request, just pass an associative array.

```
$tx = new Certly\ThreatExchange\ThreatExchange("123", "abc");
$tx->call("/898557073557972/descriptors", "GET", [
    "param_name" => "param_value"
]);
```

If you want to retrieve more results, simply use the `next` function with the pagination URL returned from your previous request.
```
$tx = new Certly\ThreatExchange\ThreatExchange("123", "abc");
$result = $tx->getThreatIndicators("domain", "ian.sh", ["limit" => 1]);
$tx->next($result->paging->next);
```
