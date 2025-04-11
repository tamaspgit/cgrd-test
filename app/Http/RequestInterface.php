<?php

namespace CGRD\Http;

interface RequestInterface {

    public function getMethod();

    public function getUri();
    
}