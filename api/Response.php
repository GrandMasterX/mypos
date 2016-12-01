<?php
namespace grandmasterx\mypos\api;

/**
 * Class Response
 * @package grandmasterx\mypos\api
 */
class Response
{

    /**
     * @var Config;
     */
    private $cnf;

    /**
     * @var
     */
    private $raw_data;

    /**
     * @var
     */
    private $format;

    /**
     * @var
     */
    private $data;

    /**
     * @var
     */
    private $signature;

    /**
     *
     * @param Config $cnf
     * @param string|array $raw_data
     * @param string $format COMMUNICATION_FORMAT_JSON|COMMUNICATION_FORMAT_XML|COMMUNICATION_FORMAT_POST
     */
    public function __construct(Config $cnf, $raw_data, $format) {
        $this->cnf = $cnf;
        $this->_setData($raw_data, $format);
    }

    /**
     * Static class to create Response object
     * @param string|array $raw_data
     * @param type $format
     * @return \self
     */
    public static function getInstance(Config $cnf, $raw_data, $format) {
        return new self($cnf, $raw_data, $format);
    }

    /**
     * Validate Signature param from IPC repsonce
     * @return boolean
     */
    public function isSignatureCorrect() {
        try {
            $this->_verifySignature();
        } catch (\Exception $ex) {
            return false;
        }
        return true;
    }

    /**
     * Request param: Signature
     * @return string
     */
    function getSignature() {
        return $this->signature;
    }

    /**
     * Request param: Status
     * @return int
     */
    function getStatus() {
        return Helper::getArrayVal($this->getData(CASE_LOWER), 'status');
    }

    /**
     * Request param: StatusMsg
     * @return string
     */
    function getStatusMsg() {
        return Helper::getArrayVal($this->getData(CASE_LOWER), 'statusmsg');
    }

    /**
     * Return IPC Response in array
     * @param int $case CASE_LOWER|CASE_UPPER
     * @return array
     * @throws IPC_Exception
     */
    function getData($case = null) {
        if ($case !== null) {
            if (!in_array($case, [
                CASE_LOWER,
                CASE_UPPER
            ])
            ) {
                throw new IPC_Exception('Invalid Key Case!');
            }
            return array_change_key_case($this->data, $case);
        }

        return $this->data;
    }

    /**
     * Return IPC Response in original format json/xml/array
     * @return string|array
     */
    function getDataRaw() {
        return $this->raw_data;
    }

    /**
     * @param $raw_data
     * @param $format
     * @return $this
     * @throws IPC_Exception
     */
    private function _setData($raw_data, $format) {
        if (empty($raw_data)) {
            throw new IPC_Exception('Invalid Reponce data');
        }

        $this->format = $format;
        $this->raw_data = $raw_data;

        switch ($this->format) {
            case Defines::COMMUNICATION_FORMAT_JSON:
                $this->data = json_decode($this->raw_data, 1);
                break;
            case Defines::COMMUNICATION_FORMAT_XML:
                $this->data = (array)new \SimpleXMLElement($this->raw_data);
                break;
            case Defines::COMMUNICATION_FORMAT_POST:
                $this->data = $this->raw_data;
                break;
            default:
                throw new IPC_Exception('Invalid response format!');
                break;
        }

        if (empty($this->data)) {
            throw new IPC_Exception('No IPC Response!');
        }

        $this->_exctractSignature() && $this->_verifySignature();
        return $this;
    }

    /**
     * @return bool
     */
    private function _exctractSignature() {
        if (is_array($this->data) && !empty($this->data)) {
            foreach ($this->data as $k => $v) {
                if (strtolower($k) == 'signature') {
                    $this->signature = $v;
                    unset($this->data[$k]);
                }
            }
        }
        return true;
    }

    /**
     * @throws IPC_Exception
     */
    private function _verifySignature() {
        if (empty($this->signature)) {
            throw new IPC_Exception('Missing request signature!');
        }

        if (!$this->cnf) {
            throw new IPC_Exception('Missing config object!');
        }

        $pubKeyId = openssl_get_publickey($this->cnf->getAPIPublicKey());
        if (!openssl_verify($this->_getSignData(), base64_decode($this->signature), $pubKeyId, Defines::SIGNATURE_ALGO)) {
            throw new IPC_Exception('Signature check failed!');
        }
    }

    /**
     * @return string
     */
    private function _getSignData() {
        return base64_encode(implode('-', Helper::getValuesFromMultiDemensionalArray($this->data)));
    }

}
