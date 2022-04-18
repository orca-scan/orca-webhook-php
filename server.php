<?php
//WebHook Out Controller
if (preg_match('/orca-webhook-out$/', $_SERVER["REQUEST_URI"])){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

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
}
//WebHook In Controller
elseif (preg_match('/trigger-webhook-in$/', $_SERVER["REQUEST_URI"])){
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // The following example adds a new row to a sheet, setting the value of Barcode, Name, Quantity and Description
        // TODO: change url to https://api.orcascan.com/sheets/{id}
        $url = 'https://httpbin.org/post';
        $data = array(
            "___orca_action" => "update",
            "barcode" => "0123456789",
            "Name" => "New 1",
            "Quantity" => 12,
            "Description" => "Add new row example"
        );
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        echo $response;
    }
}
else {
    echo "<p>Welcome to Orca Example.</p>";
}

?>