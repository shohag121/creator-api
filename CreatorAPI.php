<?php


namespace shohag\CreatorAPI;


class CreatorAPI
{
    private $applicationOwner;
    private $format = 'json';
    private $applicationName;
    private $authToken;
    private $apiEndPoint = 'https://creator.zoho.com/api/';

    public function __construct($config)
    {
        $this->applicationName = $config['applicationName'];
        $this->applicationOwner = $config['applicationOwner'];
        $this->authToken = $config['authToken'];
    }

    public function addRecord( $formName, $dataArray)
    {
        // https://creator.zoho.com/api/<ownername>/<format>/<applicationName>/form/<formName>/record/add
        $curlURL = $this->apiEndPoint . $this->applicationOwner . '/' . $this->applicationName . '/form/' . $formName . '/record/add?authtoken='. $this->authToken. '&scope=creatorapi&';

        try {
            return $this->doCurl($curlURL, http_build_query($dataArray));
        } catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * @param $url
     * @param null $postData
     * @return bool|string
     */
    private function doCurl($url, $postData = null){

        $post = 0;
        if ($postData != NULL) {
            $post = 1;
        }


        // Do curl to get data from creator
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT => 'W3S Cloud API',
            CURLOPT_POST => $post,
        ));

        if($post){
//            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
//            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//                'Content-Type: multipart/form-data',
//                'Connection: Keep-Alive'
//            ));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        }



        $result = curl_exec($curl);
        if (!curl_exec($curl)) {
            die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }
        curl_close($curl);
        return $result;

    }
}