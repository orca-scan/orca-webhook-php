# orca-webhook-php

Example of how to build an [Orca Scan WebHook Out](https://orcascan.com/docs/api/webhooks) endpoint and [Orca Scan WebHook In](https://orcascan.com/guides/how-to-update-orca-scan-from-your-system-4b249706) using [Php](https://www.php.ne) and [Laravel](https://laravel.com/) framework.

## Install

First ensure you have [Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/install/) installed:

## Run

```bash
# build a Docker image
docker-compose build

# run Docker image
docker-compose up
```

Your WebHook receiver will now be running on port 80.

You can emulate an Orca Scan WebHook using [cURL](https://dev.to/ibmdeveloper/what-is-curl-and-why-is-it-all-over-api-docs-9mh) by running the following:

```bash
curl --location --request POST 'http://127.0.0.1:80/orca-webhook-out' \
--header 'Content-Type: application/json' \
--data-raw '{
    "___orca_action": "add",
    "___orca_sheet_name": "Vehicle Checks",
    "___orca_user_email": "hidden@requires.https",
    "___orca_row_id": "5cf5c1efc66a9681047a0f3d",
    "Barcode": "4S3BMHB68B3286050",
    "Make": "SUBARU",
    "Model": "Legacy",
    "Model Year": "2011",
    "Vehicle Type": "PASSENGER CAR",
    "Plant City": "Lafayette",
    "Trim": "Premium",
    "Location": "52.2034823, 0.1235817",
    "Notes": "Needs new tires"
}'
```
### Important things to note

1. Only Orca Scan system fields start with `___`
2. Properties in the JSON payload are an exact match to the  field names in your sheet _(case and space)_
3. WebHooks are never retried, regardless of the HTTP response

## Example

This [example]() uses the [Laravel](https://laravel.com/) framework:

### WebHook Out 

[Orca Scan WebHook Out](https://orcascan.com/docs/api/webhooks)

```php
public function webhook_out(Request $request)
{
    $json = $request->getContent(); //json as a string.
    $data = json_decode($json, true);

    // get the name of the action that triggered this request (add, update, delete, test)
    $action = $data["___orca_action"];

    // get the name of the sheet this action impacts
    $sheetName = $data["___orca_sheet_name"];

    // get the email of the user who preformed the action (empty if not HTTPS)
    $userEmail = $data["___orca_user_email"];

    // NOTE:
    // orca system fields start with ___
    // you can access the value of each field using the field name (data.Name, data.Barcode, data.Location)
    switch ($action) {
        case "add":
            // TODO: do something when a row has been added
            break;
        case "update":
            
            // TODO: do something when a row has been updated
            break;
        case "delete":
            // TODO: do something when a row has been deleted
            break;
        case "test":
            // TODO: do something when the user in the web app hits the test button
            break;
    }
    return 'ok';
}
```

### WebHook In 

[Orca Scan WebHook In](https://orcascan.com/guides/how-to-update-orca-scan-from-your-system-4b249706)

```php
public static function webhook_in()
{
    // The following example adds a new row to a sheet, setting the value of Barcode, Name, Quantity and Description
    $response = Http::post('https://httpbin.org/post', [  // TODO: change url to https://api.orcascan.com/sheets/{id}
        "___orca_action" => "update",
        "Barcode" => "0123456789",
        "Name" => "New 1",
        "Quantity" => 12,
        "Description" => "Add new row example"
    ]);
    
    echo $response->getBody();
}
```

Use `http://127.0.0.1:80/trigger-webhook-in` to trigget the in webhook and send the request.

## Test server locally on Orca Cloud

To expose the server securely from localhost and test it easily on the real Orca Cloud environment you can use [Secure Tunnels](https://ngrok.com/docs/secure-tunnels#what-are-ngrok-secure-tunnels). Take a look at [Ngrok](https://ngrok.com/) or [Cloudflare](https://www.cloudflare.com/).

```bash
ngrok http 80
```

## Troubleshooting

If you run into any issues not listed here, please [open a ticket](https://github.com/orca-scan/orca-webhook-python/issues).

## Examples in other langauges
* [orca-webhook-dotnet](https://github.com/orca-scan/orca-webhook-dotnet)
* [orca-webhook-python](https://github.com/orca-scan/orca-webhook-python)
* [orca-webhook-go](https://github.com/orca-scan/orca-webhook-go)
* [orca-webhook-java](https://github.com/orca-scan/orca-webhook-java)
* [orca-webhook-php](https://github.com/orca-scan/orca-webhook-php)
* [orca-webhook-node](https://github.com/orca-scan/orca-webhook-node)

## History

For change-log, check [releases](https://github.com/orca-scan/orca-webhook-python/releases).

## License

&copy; Orca Scan, the [Barcode Scanner app for iOS and Android](https://orcascan.com).