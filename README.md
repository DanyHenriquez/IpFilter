# IpFilter
PSR-7 IpFilter

##ALLOW mode

The default allow rule set allows all connections through except when the address matches

## DENY mode

A default deny rule set will deny all connections through unless an address matches.

## Usage

```php

use Prezto\IpFilter\IpFilterMiddleware;
use Prezto\IpFilter\Mode;

# Instantiate with a single address. Allow only one ip.
$filter = new IpFilterMiddleware(['192.168.1.7'], Mode::DENY)

# Instantiate with an address range. Allow only this range.
$filter = new IpFilterMiddleware([['192.168.1.100', '192.168.1.200']], Mode::DENY)

# Adding addresses after instantiating.
$filter->addIpRange('192.168.1.100', '192.168.1.200');
$filter->addIp('192.168.1.98');

```

After this you can add the class to a psr7 mjiddleware loader that accepts $request, $response and next.
