<?php


namespace shohag\ZohoCreatorAPI;

/**
 * Class CreatorAPI
 * @package shohag\ZohoCreatorAPI
 */
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

    /**
     * application name
     * @var mixed
     */
    private $applicationName;

    /**
     * Authentication Token
     * @var mixed
     */
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

    /**
     * Search records from a view or report
     * @param $criteria
     * @param $viewName
     * @param int $start
     * @param int $limit
     * @return bool|\Exception|string
     */
    public function searchRecords($criteria, $viewName, $start = 0, $limit = 0)
    {
        if (($start != 0) && ($limit != 0)){
            $curlURL = "{$this->apiEndPoint}{$this->format}/{$this->applicationName}/view/{$viewName}?authtoken={$this->authToken}&scope=creatorapi&raw=true&zc_ownername={$this->applicationOwner}&startindex={$start}&limit={$limit}&criteria=({$criteria})";

        } else {
            $curlURL = "{$this->apiEndPoint}{$this->format}/{$this->applicationName}/view/{$viewName}?authtoken={$this->authToken}&scope=creatorapi&raw=true&zc_ownername={$this->applicationOwner}&criteria=({$criteria})";
        }

        try {
            return $this->doCurl($curlURL);
        } catch (\Exception $exception){
            return $exception;
        }

    }

    /**
     * get record by id
     * @param $recordID
     * @param $viewName
     * @return bool|\Exception|string
     */
    public function getRecordByID($recordID , $viewName)
    {
        return $this->searchRecords('ID=='.$recordID, $viewName );
    }

    /**
     * Get all record of a view
     * @param $viewName
     * @return bool|\Exception|string
     */
    public function allRecords($viewName)
    {

        $curlURL = "{$this->apiEndPoint}{$this->format}/{$this->applicationName}/view/{$viewName}?authtoken={$this->authToken}&scope=creatorapi&raw=true&zc_ownername={$this->applicationOwner}";

        try {
            return $this->doCurl($curlURL);
        } catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * Get all fields from a form
     * @param $formName
     * @return bool|\Exception|string
     */
    public function allFields($formName)
    {

        $curlURL = "{$this->apiEndPoint}{$this->format}/{$this->applicationName}/{$formName}/fields?authtoken={$this->authToken}&scope=creatorapi&zc_ownername={$this->applicationOwner}";

        try {
            return $this->doCurl($curlURL);
        } catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * Get applications
     * @param int $limit
     * @param bool $shared
     * @return bool|\Exception|string
     */
    public function listApplications( $limit = 0, $shared = false)
    {
        $need = 'applications';
        if ($shared){
            $need = 'sharedapps';
        }

        $curlURL = "{$this->apiEndPoint}{$this->format}/{$need}?authtoken={$this->authToken}&scope=creatorapi";

        if ($limit != 0){
            $curlURL = $curlURL . '&limit='. $limit;
        }

        try {
            return $this->doCurl($curlURL);
        } catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * Get All of your owned Applications
     * @return bool|\Exception|string
     */
    public function allApplications()
    {
        return $this->listApplications();
    }

    /**
     * Get the Applications shared to you
     * @param int $limit
     * @return bool|\Exception|string
     */
    public function listSharedApplications($limit = 0)
    {
        return $this->listApplications($limit, true);
    }

    /**
     * Get All the Applications shared to you
     * @return bool|\Exception|string
     */
    public function allSharedApplications()
    {
        return $this->listSharedApplications();
    }

    /**
     * Get all forms and reports of an application
     * @param string $applicationName
     * @return bool|\Exception|string
     */
    public function allFormsAndReports($applicationName = '')
    {
        if ($applicationName == ''){
            $curlURL = "{$this->apiEndPoint}{$this->format}/{$this->applicationName}/formsandviews?authtoken={$this->authToken}&scope=creatorapi&zc_ownername={$this->applicationOwner}";
        } else {
            $curlURL = "{$this->apiEndPoint}{$this->format}/{$applicationName}/formsandviews?authtoken={$this->authToken}&scope=creatorapi&zc_ownername={$this->applicationOwner}";
        }

        try {
            return $this->doCurl($curlURL);
        } catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * Get all forms of an application
     * @param string $applicationName
     * @return bool|\Exception|string
     */
    public function allForms($applicationName = '')
    {
        if ($applicationName == ''){
            $curlURL = "{$this->apiEndPoint}{$this->format}/{$this->applicationName}/forms?authtoken={$this->authToken}&scope=creatorapi&zc_ownername={$this->applicationOwner}";
        } else {
            $curlURL = "{$this->apiEndPoint}{$this->format}/{$applicationName}/forms?authtoken={$this->authToken}&scope=creatorapi&zc_ownername={$this->applicationOwner}";
        }

        try {
            return $this->doCurl($curlURL);
        } catch (\Exception $exception){
            return $exception;
        }
    }

    /**
     * Uploads file to a record
     * @param $recordID
     * @param $file
     * @param $formName
     * @param $fieldName
     * @param string $fileName
     * @return bool|\Exception|string
     */
    public function uploadFile($recordID, $file, $formName, $fieldName, $fileName = '')
    {

        $mime = mime_content_type($file);
        $info = pathinfo($file);
        $name = $info['basename'];
        if ($fileName != ''){
            $name = $fileName;
        }
        $output = new \CURLFile($file, $mime, $name);


        $curlURL = "{$this->apiEndPoint}xml/fileupload/";

        $dataArray = array(
            'authtoken' => $this->authToken,
            'scope' => 'creatorapi',
            'applinkname' => $this->applicationName,
            'formname' => $formName,
            'fieldname' => $fieldName,
            'recordId' => $recordID,
            'filename' => $name,
            'file' => $output,
        );

        try {
            $xml = $this->doCurl($curlURL, $dataArray);
            $xmlObject = simplexml_load_string($xml);

            return json_encode($xmlObject);
        } catch (\Exception $exception){
            return $exception;
        }

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
            CURLOPT_USERAGENT => 'Zoho Creator API',
            CURLOPT_POST => $post,
        ));

        if($post){
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: multipart/form-data',
                'Connection: Keep-Alive'
            ));
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        }



        $result = curl_exec($curl);
        if (!$result) {
            die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
        }
        curl_close($curl);
        return $result;

    }
}