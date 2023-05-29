<?php
/**
 * Created by IntelliJ IDEA.
 * User: silva
 * Date: 2019-01-14
 * Time: 22:26
 */

namespace CODOF\DTO;


class Error
{
    public $code = 0;
    public $message = "";

    /**
     * Error constructor.
     * @param int $code
     * @param string $message
     */
    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

}