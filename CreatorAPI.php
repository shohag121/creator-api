<?php


namespace shohag\CreatorAPI;


class CreatorAPI
{
    /**
     * application Owner
     * @var string
     */
    private $applicationOwner;

    /**
     * format for the request
     * @var string
     */
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

    /**
     * Add record to a form
     * @param $formName
     * @param $dataArray
     * @return bool|\Exception|string
     */
    public function addRecord( $formName, $dataArray)
    {
        
        $curlURL = $this->apiEndPoint . $this->applicationOwner . '/' . $this->format. '/' . $this->applicationName . '/form/' . $formName . '/record/add/?authtoken='. $this->authToken. '&scope=creatorapi';

        try {
            return $this->doCurl($curlURL, $dataArray);
        } catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * update record based on criteria
     * @param $criteria
     * @param $formName
     * @param $dataArray
     * @param bool $sharedUser
     * @return bool|\Exception|string
     */
    public function updateRecord($criteria, $formName, $dataArray, $sharedUser = false)
    {
        if ($sharedUser){

            $curlURL = "{$this->apiEndPoint}{$this->applicationOwner}/{$this->format}/{$this->applicationName}/view/{$formName}/record/update/?authtoken={$this->authToken}&scope=creatorapi&criteria=({$criteria})";
        } else {

            $curlURL = "{$this->apiEndPoint}{$this->applicationOwner}/{$this->format}/{$this->applicationName}/form/{$formName}/record/update/?authtoken={$this->authToken}&scope=creatorapi&criteria=({$criteria})";
        }

        try {
            return $this->doCurl($curlURL, $dataArray);
        } catch (\Exception $exception){
            return $exception;
        }

    }

    /**
     * delete record based on criteria
     * @param $criteria
     * @param $formName
     * @param $dataArray
     * @param bool $sharedUser
     * @return bool|\Exception|string
     */
    public function deleteRecord($criteria, $formName, $dataArray, $sharedUser = false)
    {
        if ($sharedUser){

            $curlURL = "{$this->apiEndPoint}{$this->applicationOwner}/{$this->format}/{$this->applicationName}/view/{$formName}/record/delete/?authtoken={$this->authToken}&scope=creatorapi&criteria=({$criteria})";
        } else {

            $curlURL = "{$this->apiEndPoint}{$this->applicationOwner}/{$this->format}/{$this->applicationName}/form/{$formName}/record/delete/?authtoken={$this->authToken}&scope=creatorapi&criteria=({$criteria})";
        }

        try {
            return $this->doCurl($curlURL, $dataArray);
        } catch (\Exception $exception){
            return $exception;
        }

    }

    public function searchRecords($criteria, $viewName, $page = 1, $limit = 200)
    {
// TODO: check it and fix
        $curlURL = "{$this->apiEndPoint}{$this->format}/{$this->applicationName}/view/{$viewName}/?authtoken={$this->authToken}&scope=creatorapi&raw=true&zc_ownername={$this->applicationOwner}&startindex={$page}&limit={$limit}&criteria=({$criteria})";

        return $this->doCurl($curlURL);
    }


    public function allRecords($viewName)
    {
// https://creator.zoho.com/api/<format>/<applicationLinkName>/view/<viewLinkName>
        $curlURL = "{$this->apiEndPoint}{$this->format}/{$this->applicationName}/view/{$viewName}/?authtoken={$this->authToken}&scope=creatorapi&raw=true&zc_ownername={$this->applicationOwner}";

        // TODO: check it and fix
        return $this->doCurl($curlURL);
    }

    /**
     * send the request
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
            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: multipart/form-data',
                'Connection: Keep-Alive'
            ));
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