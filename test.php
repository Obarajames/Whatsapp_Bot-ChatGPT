<?php

$openAiApi = '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';
$api_version = 'v16.0';

$challenge = $_REQUEST['hub_challenge'];
$verify_token = $_REQUEST['hub_verify_token'];

if ($verify_token === 'james') {
    echo $challenge;
};

$token = "EAAHwSbc9uRQBOZCwnZAoxE3i72jlBnAv9L6l7Ji6P5wwNUpjPZCdRsgdoXniMEAg7qNtR7qtShAWrLJOeQQAdB1kQ4SZAItGtJ9tiBoiQPEl3bk6tkEwEOZBpDhCSL1qIVb9k3PqNlCvZAiPAxyD1ktzO5s2fviRIkrNK4f3zLRcz10CZA16ZBrRj1Jtno7pUkLMwXToj0aFXupZBjW9C0MtcXDhO6JE745rYkj0V";

$phoneId = "104720472587431";

$payload = file_get_contents('php://input');
if (empty($payload)) {
    $payload = '{"object":"whatsapp_business_account","entry":[{"id":"107036135722828","changes":[{"value":{"messaging_product":"whatsapp","metadata":{"display_phone_number":"104720472587431","phone_number_id":"108047392287351"},"contacts":[{"profile":{"name":"james omwoyo"},"wa_id":"923162292811"}],"messages":[{"from":"923162292811","id":"wamid.HBgMOTIzMTYyMjkyODExFQIAEhggNTZENjQwM0ZBMDg0RTQ3MEJEMTM3RUFDMzlENzk4ODAA","timestamp":"1683385994","text":{"body":"Hi, Who is william ruto on youtube?"},"type":"text"}]},"field":"messages"}]}]}';
}
 //print_r($payload);

$decode = json_decode($payload, true);



$ownerno = $decode['entry'][0]['changes']['0']['value']['metadata']['display_phone_number'];
echo $userno;
$username = $decode['entry'][0]['changes']['0']['value']['contacts'][0]['profile']['name'];
$userno = $decode['entry'][0]['changes']['0']['value']['messages'][0]['from'];
$usermessage = $decode['entry'][0]['changes']['0']['value']['messages'][0]['text']['body'];


//send message to openai 

$ar = array(
    'prompt' => 'My name is ' . $username . ' and my question is ' . $usermessage,
    'model' => 'text-davinci-003',
    'temperature' => 0.6,
    'max_tokens' => 150,
    'top_p' => 1,
    'frequency_penalty' => 1,
    'presence_penalty' => 1,
);

$data = json_encode($ar);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/completions");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt(
    $ch,
    CURLOPT_POSTFIELDS,
    $data
);

$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization:Bearer ' . $openAiApi;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


$result = curl_exec($ch);


curl_close($ch);

// echo $result;


$openapirespose = json_decode($result, true);

// echo $openapirespose;

$finalresponse = $openapirespose['choices'][0]['text'];


//echo $finalresponse;

try {

	/// sending message back to user ///
// Set your access token and API version


// Set the endpoint URL and request payload
$endpoint = "https://graph.facebook.com/v17.0/104720472587431/messages";
$data = array(
    'messaging_product' => 'whatsapp',
    'to' => $userno,
    'text' => array(
        'body' => $finalresponse
    )
);

// Set the cURL options and execute the request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Authorization: Bearer {$token}",
    "Content-Type: application/json"
));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Output the response
//echo $response;
	
	
	
}

catch (customException $e) {
  //display custom message
  echo $e->errorMessage();
}


//$myfile = fopen("response.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $payload);
//fclose($myfile);



