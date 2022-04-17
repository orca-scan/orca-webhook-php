<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrcaWebHookController extends Controller
{
    public function webhook_out(Request $request){
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

    public function webhook_in()
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
}
