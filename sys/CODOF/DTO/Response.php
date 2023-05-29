<?php
/**
 * Created by IntelliJ IDEA.
 * User: silva
 * Date: 2019-01-14
 * Time: 22:13
 */

namespace CODOF\DTO;


class Response
{
    public $success = true;

    /*
     * @var Error
     */
    public $error;
    public $data;

    /**
     * Response constructor.
     */
    public function __construct()
    {
    }

     public function withData($dataObj){
           $this->data = $dataObj;
           return $this;
     }

    public function setError($err){
        $this->success = false;
        $this->error = $err;
    }
}