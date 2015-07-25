<?php
/**
 * Created by PhpStorm.
 * User: U6014642
 * Date: 7/23/2015
 * Time: 9:14 PM
 */

namespace AronKerr\Checkr;

use AronKerr\Checkr\InputFilter\CreateAdverseActionInputFilter;
use AronKerr\Checkr\InputFilter\CreateCandidateInputFilter;
use AronKerr\Checkr\InputFilter\CreateReportInputFilter;
use AronKerr\Checkr\InputFilter\FetchAllAdverseActionsInputFilter;
use AronKerr\Checkr\InputFilter\FetchAllCandidatesInputFilter;
use AronKerr\Checkr\InputFilter\UpdateReportInputFilter;
use AronKerr\Checkr\InputFilter\UploadCandidateDocumentInputFilter;
use Zend\Http\Client as HttpClient;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response;
use Zend\InputFilter\InputFilterInterface;
use Zend\Json\Json;
use Zend\Stdlib\Parameters;
use Zend\Stdlib\ParametersInterface;
use Zend\Stdlib\ResponseInterface;

/**
 * Class Checkr
 * @package AronKerr\Checkr
 */
class Checkr {

    /**
     * Base URI for the REST client
     */
    const URI_BASE = 'https://api.checkr.com/v1/';

    /**
     * Your Checkr API key
     *
     * @var string
     */
    public $apiKey;

    /**
     * @var HttpClient
     */
    protected $httpClient = null;

    /**
     * Performs object initializations
     *
     *  # Sets up character encoding
     *  # Saves the API key
     *
     * @param  string $apiKey Your Checkr API key
     */
    public function __construct($apiKey, HttpClient $httpClient = null)
    {
        $this->apiKey = (string) $apiKey;
        $this->setHttpClient($httpClient ?: new HttpClient);
        $this->getHttpClient()
            ->setAdapter('Zend\Http\Client\Adapter\Curl')
            ->setAuth($apiKey, '', HttpClient::AUTH_BASIC);
    }

    /**
     * @param HttpClient $httpClient
     * @return Checkr
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * This request is used to create a new Candidate.
     * Returns a representation of the Candidate.
     * @param array $params [options]
     * @option string           first_name                       +[options]
     * @option string           middle_name                      +[options] (optional)
     * @option string           last_name                        +[options]
     * @option string           email                            +[options]
     * @option string           phone                            +[options]
     * @option string           zipcode                          +[options]
     * @option date             dob                              +[options] format: YYYY-MM-DD
     * @option string           ssn                              +[options] format: xxx-xx-xxxx
     * @option string           driver_license_number            +[options]
     * @option string           driver_license_state             +[options] format: ST
     * @option string           previous_driver_license_number   +[options]
     * @option string           previous_driver_license_state    +[options] format: ST
     * @option boolean          copy_requested                   +[options] default: false
     * @option string           custom_id                        +[options]
     * @option array[string]    geo_ids                          +[options]
     * @return array
     */
    public function createCandidate($params=array())
    {
        $inputFilter = new CreateCandidateInputFilter();
        if(!$inputFilter->setData($params)->isValid()) {
            $message = $this->getInputFilterMessage($inputFilter);
            throw new Exception\InvalidArgumentException($message);
        }

        return $this->sendRequest('candidates', HttpRequest::METHOD_POST, $inputFilter->getValues());
    }

    /**
     * This request is used to retrieve existing Candidates.
     * Returns a representation of the Candidate.
     * @param string $id
     * @return array
     */
    public function fetchCandidate($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a candidate id');
        }

        return $this->sendRequest('candidates/' . $id, HttpRequest::METHOD_GET);
    }

    /**
     * This request is used to list existing Candidates.
     * Returns a list of paginated Candidates matching the filter(s).
     * @param array $params [options]
     * @option string   email           +[options] (optional) filter candidates by email
     * @option string   full_name       +[options] (optional) filter candidates by full name
     * @option string   adjudication    +[options] (optional) filter candidates by adjudication
     * @option string   custom_id       +[options] (optional) filter candidates by custom_id
     * @option date     created_after   +[options] (optional) format: YYYY-MM-DD filter candidates created after this timestamp
     * @option date     created_before  +[options] (optional) format: YYYY-MM-DD filter candidates created before this timestamp
     * @option string   geo_id          +[options] (optional) filter candidates by geo_id
     * @return array
     */
    public function fetchAllCandidates($params=array())
    {
        $inputFilter = new FetchAllCandidatesInputFilter();
        if(!$inputFilter->setData($params)->isValid()) {
            throw new Exception\InvalidArgumentException('Invalid parameters. ' . $inputFilter->getMessages());
        }

        return $this->sendRequest('candidates', HttpRequest::METHOD_GET, array(), $inputFilter->getValues());
    }

    /**
     * This request is used to create a new Report.
     * Returns a representation of the Report.
     * @param array $params [options]
     * @option string   package         +[options] allowed_values: tasker_standard, tasker_pro, driver_standard, driver_pro
     * @option string   candidate_id    +[options]
     * @return array
     */
    public function createReport($params=array())
    {
        $inputFilter = new CreateReportInputFilter();
        if(!$inputFilter->setData($params)->isValid()) {
            $message = $this->getInputFilterMessage($inputFilter);
            throw new Exception\InvalidArgumentException($message);
        }

        return $this->sendRequest('reports', HttpRequest::METHOD_POST, $inputFilter->getValues());
    }

    /**
     * This request is used to retrieve existing Reports.
     * Returns a representation of the Report.
     * @param string $id
     * @return array
     */
    public function fetchReport($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a report id');
        }

        return $this->sendRequest('reports/' . $id);
    }

    /**
     * This request is used to update an existing Report. For now, you can only update its package.
     * Returns a representation of the Report.
     * @param string $id
     * @param array $params [options]
     * @option string   package     +[options] allowed_values: tasker_standard, tasker_pro, driver_standard, driver_pro
     * @return array
     */
    public function updateReport($id, $params=array())
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply an report id');
        }

        $inputFilter = new UpdateReportInputFilter();
        if(!$inputFilter->setData($params)->isValid()) {
            $message = $this->getInputFilterMessage($inputFilter);
            throw new Exception\InvalidArgumentException($message);
        }

        return $this->sendRequest('reports/' . $id, HttpRequest::METHOD_POST, $inputFilter->getValues());
    }

    /**
     * This request is used to retrieve existing SSN traces.
     * Returns a representation of the Report.
     * @param string $id
     * @return array
     */
    public function fetchSSNTrace($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply an SSN trance id');
        }

        return $this->sendRequest('ssn_traces/' . $id);
    }

    /**
     * This request is used to retrieve existing sex offender searches.
     * Returns a representation of the sex offender search.
     * @param string $id
     * @return array
     */
    public function fetchSexOffenderSearch($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a sex offender search id');
        }

        return $this->sendRequest('sex_offender_searches/' . $id);
    }

    /**
     * This request is used to retrieve existing terrorist watchlist searches.
     * Returns a representation of the terrorist watchlist search.
     * @param string $id
     * @return array
     */
    public function fetchTerroristWatchlistSearch($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a terrorist watchlist search id');
        }

        return $this->sendRequest('terrorist_watchlist_searches/' . $id);
    }

    /**
     * This request is used to retrieve existing national criminal searches.
     * Returns a representation of the national criminal search.
     * @param string $id
     * @return array
     */
    public function fetchNationalCriminalSearch($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a national criminal search id');
        }

        return $this->sendRequest('national_criminal_searches/' . $id);
    }

    /**
     * This request is used to retrieve existing county criminal searches.
     * Returns a representation of the county criminal search.
     * @param string $id
     * @return array
     */
    public function fetchCountyCriminalSearch($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a county criminal search id');
        }

        return $this->sendRequest('county_criminal_searches/' . $id);
    }

    /**
     * This request is used to retrieve existing Motor Vehicle Reports.
     * Returns a representation of the Motor Vehicle Report.
     * @param string $id
     * @return array
     */
    public function fetchMotorVehicleReport($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a motor vehicle report id');
        }

        return $this->sendRequest('motor_vehicle_reports/' . $id);
    }

    /**
     * This request is used to upload a new Candidate Document.
     * Returns a representation of the uploaded document.
     * @param string $id
     * @param array $params [options]
     * @option string   id      +[options]
     * @option string   type    +[options] allowed values: driver_license, state_id_card, passport, ssn_card
     * @option file     file    +[options] valid mime types: image/gif, image/jpeg, image/png, image/bmp, image/tiff, application/pdf
     * @return array
     */
    public function uploadCandidateDocument($id, $params=array())
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a candidate id');
        }

        $inputFilter = new UploadCandidateDocumentInputFilter();
        if(!$inputFilter->setData($params)->isValid()) {
            $message = $this->getInputFilterMessage($inputFilter);
            throw new Exception\InvalidArgumentException($message);
        }

        $queryParameters = array('type' => $inputFilter->getValue('type'));
        $postParameters = $inputFilter->getValues();
        return $this->sendRequest('candidates/' . $id . '/documents', HttpRequest::METHOD_POST, $postParameters, $queryParameters);
    }

    /**
     * This request is used to retrieve existing Candidate documents
     * Returns a representation of the documents.
     * @param string $id
     * @return array
     */
    public function fetchCandidateDocuments($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a candidate id');
        }

        return $this->sendRequest('candidates/' . $id . '/documents');
    }

    /**
     * @param $id
     * @param $params
     * @return mixed
     */
    public function createAdverseAction($id, $params)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply an adverse action id');
        }

        $inputFilter = new CreateAdverseActionInputFilter();
        if(!$inputFilter->setData($params)->isValid()) {
            $message = $this->getInputFilterMessage($inputFilter);
            throw new Exception\InvalidArgumentException($message);
        }

        $postParameters = $inputFilter->getValues();
        return $this->sendRequest('reports/' . $id . 'adverse_actions', HttpRequest::METHOD_POST, $postParameters);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function fetchAdverseAction($id)
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply an adverse action id');
        }

        return $this->sendRequest('adverse_actions/' . $id);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function fetchAllAdverseActions($params=array())
    {
        if (empty($id)) {
            throw new Exception\InvalidArgumentException('You must supply a candidate id');
        }

        $inputFilter = new FetchAllAdverseActionsInputFilter();
        if(!$inputFilter->setData($params)->isValid()) {
            $message = $this->getInputFilterMessage($inputFilter);
            throw new Exception\InvalidArgumentException($message);
        }

        $queryParameters = $inputFilter->getValues();
        return $this->sendRequest('adverse_actions', null, null, $queryParameters);
    }

    /**
     * @param $uri
     * @param string $method
     * @param array $postParams
     * @param array $queryParams
     * @return mixed
     */
    protected function sendRequest($uri, $method=HttpRequest::METHOD_GET, $postParams=array(), $queryParams=array())
    {
        $request = new HttpRequest;
        $request->setUri(self::URI_BASE . $uri);
        $request->setMethod($method);

        $request->setQuery($this->prepareParameters($queryParams));
        $request->setPost($this->prepareParameters($postParams));

        $response = $this->httpClient->send($request);
        $this->checkErrors($response);

        return Json::decode($response->getBody(), JSON::TYPE_ARRAY);
    }

    /**
     * @param $params
     * @return Parameters
     */
    protected function prepareParameters($params)
    {
        if ($params instanceof ParametersInterface) {
            return $params;
        }

        if (is_array($params)) {
            return new Parameters($params);
        }

        throw new Exception\InvalidArgumentException('Parameters must either be an array or be a class that implements the ParametersInterface');
    }

    /**
     * @param InputFilterInterface $inputFilter
     * @return string
     */
    protected function getInputFilterMessage(InputFilterInterface $inputFilter)
    {
        $message = 'Invalid parameters. ';
        foreach($inputFilter->getMessages() as $element => $error) {
            $message .= $element . ' ';
            foreach($error as $k => $v) {
                $message .= $v . '. ';
            }
        }
        return $message;
    }

    /**
     * @param ResponseInterface $response
     */
    protected function checkErrors(ResponseInterface $response)
    {
        if ($response->isServerError() || $response->isClientError()) {
            throw new Exception\RuntimeException('An error occurred sending request. Status code: ' . $response->getStatusCode());
        }
    }
}