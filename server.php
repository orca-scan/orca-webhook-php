<?php

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Receive data from Orca Scan (Webhook Out)
if ($path === '/orca-webhook-out' && $method === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    if (!$data) {
        http_response_code(400);
        echo 'Bad Request';
        exit;
    }

    error_log('Request received:' . PHP_EOL . json_encode($data, JSON_PRETTY_PRINT));

    // get the event that triggered this request (rows:add, rows:update, rows:delete, test)
    $event = $data['___orca_event'];

    // get the name of the sheet this event relates to
    $sheetName = $data['___orca_sheet_name'];

    // get the email of the user who performed the action (empty if not HTTPS)
    $userEmail = $data['___orca_user_email'];

    // NOTE:
    // orca system fields start with ___
    // access your sheet fields by name e.g. $data['Barcode'], $data['Name']
    switch ($event) {
        case 'rows:add':
            // TODO: do something when a row has been added
            break;
        case 'rows:update':
            // TODO: do something when a row has been updated
            break;
        case 'rows:delete':
            // TODO: do something when a row has been deleted
            break;
        case 'test':
            // TODO: do something when the user hits the test button
            break;
    }

    http_response_code(200);
    echo 'OK';
}

// Push data into Orca Scan (Webhook In)
elseif ($path === '/trigger-webhook-in' && $method === 'GET') {

    $data = json_encode([
        '___orca_action' => 'add',
        'Barcode'        => '0123456789',
        'Name'           => 'New 1',
        'Quantity'       => 12,
        'Description'    => 'Add new row example'
    ]);

    // TODO: change url to https://api.orcascan.com/sheets/{id}
    $ch = curl_init('https://httpbin.org/post');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    header('Content-Type: application/json');
    echo $response;
}

else {
    http_response_code(404);
    echo 'Not found';
}
