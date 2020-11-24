<?php

/**

 * Autor: Aryldo César

 * Data: 20/02/2020

 */	

class MailChimp {
	
	private function __construct() {
		$this->api_key  = 'YOUR_KEY';
		$this->data_center = substr($this->api_key,strpos($this->api_key,'-')+1);
	}

	public function AddContact($contact=["CONTACT_EMAIL", "CONTACT_NAME"]) {
    $list_id = 'LIST_ID';
    $api_key =  $this->api_key;
    $data_center = $this->data_center;
    
    $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'. $list_id .'/members';
		
	$json = json_encode([
		'email_address' => strtolower("CONTACT_EMAIL"),
		'status'        => 'subscribed', 
		'merge_fields' => [
			'FNAME'=>"CONTACT_NAME"
		]
	]);
		
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
	$result = curl_exec($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
        return $status_code;
}

	public function createCampaign($category_id) {
    $api_key =  $this->api_key;
    $data_center = $this->data_center;
    $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/campaigns/';
	
        $json = json_encode([
            'type' => "regular",
            'recipients' => [
                'list_id' => "dfb0d9eb1f",
                'segment_opts' => [
                    'match' => 'all',
                    'conditions' => [
                        [
                            'condition_type' => 'Interests',
                            'field' => 'interests-'.$category_id,
                            'op' => 'interestcontains',
                            'value' => [$category_id]
                        ]
                    ]
                ]
            ],
            'settings'   => [
                "subject_line" => (string) "MESSAGE_SUBJECT", 
                "title" => (string) "MESSAGE_TITLE", 
                "reply_to" => (string) "REPLY_TO", 
                "from_name" => "Contador Amigo" 
            ]

        ]);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    
    return $status_code;

}

	public function CreateMessage($campaign_id) {
    $api_key =  $this->api_key;
    $data_center = $this->data_center;
    $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/campaigns/'.$id_campanha.'/content';

    $message_body = "<h1>YOUR MESSAGE HERE</h1>"; //you can use a html template
	
        $json = json_encode([
            'template' => [
                'id' => "TEMPLATE_ID",
                'sections' => array(
                    'body' => $message_body
                 )
            ]
        ]);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $status_code;
		
}

	public function SendEmail($campaign_id) {
    $api_key =  $this->api_key;
    $data_center = $this->data_center;
    $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/campaigns/'.$campaign_id.'/actions/schedule';

    
    $date = date('c', mktime(date("H")+1, 15 , 0, date("m"), date("d"), date("Y") ));

    $json = json_encode([
        "schedule_time" => $date,
        "timewarp" => false
    ]);

    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
       // curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($status_code != 204){
            $url = 'https://'. $data_center .'.api.mailchimp.com/3.0/campaigns/'.$campaign_id.'';
			
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            $result = curl_exec($ch);
            curl_close($ch);
        }
		
        return $status_code;
}

	public function createInterest() {
		$list_id = 'LIST_ID';
		$id_categoria = "CATEGORY_ID"; //produção
		$api_key =  $this->api_key;
		$data_center = $this->data_center;
		$url = 'https://'. $data_center .'.api.mailchimp.com/3.0/lists/'.$list_id.'/interest-categories/'.$id_categoria.'/interests';


		$interest_name = "INTEREST_NAME";

		$json = json_encode([
			'name' => $interest_name
		]);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_USERPWD, 'user:' . $api_key);
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		$result = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
	
	
		if($status_code==200){
			$message = "Interest successfully created";
		}
	
		if($status_code==400){
		  $message = "Error creating interest";
		}
		
		return $message;
     
}

}
?>