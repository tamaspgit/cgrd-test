<?php

namespace CGRD\Http;

class ResponseStream implements ResponseStreamInterface
{

    private $content;

    public function __construct()
    {
        $this->content = fopen('php://memory', 'rw');
    }

    public function close()
    {
        fclose($this->content);
    }

    public function rewind()
    {
        rewind($this->content);
    }

  
    public function write($string)
    {
        fwrite($this->content, $string);
    }

    public function getContents()
    {
       $lines = '';
       $this->rewind();
       while (false !== ($line = fgets($this->content))) {
           $lines .= $line;
       }

       return $lines;
    }

}