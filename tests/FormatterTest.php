<?php

class FormatterTest extends PHPUnit_Framework_TestCase {
    
    const JSON_NULL = 'null';
    
    /**
     *
     * @var \webignition\JsonPrettyPrinter\JsonPrettyPrinter
     */
    private $formatter = null;
    
    public function setUp() {
        $this->getFormatter()->reset();
    }
    
    public function testNullEncoding() {        
        $this->assertEquals(self::JSON_NULL, $this->getFormatter()->format());
    }
    
    public function testSimpleArrayEncoding() {        
        $this->assertEquals('[
  "one",
  "two",
  "three"
]', $this->getFormatter()->format(json_encode(array('one', 'two', 'three'))));
    }
    
    public function testSimpleObjectEncoding() {
        $object = new stdClass();
        $object->one = 'a';
        $object->two = 'b';
        $object->three = 'c';
        
        $this->assertEquals('{
  "one": "a",
  "two": "b",
  "three": "c"
}', $this->getFormatter()->format(json_encode($object)));
    }
    
    public function testComplexObjectEncoding() {        
        $this->assertEquals($this->complexObjectFormattedJson(), $this->getFormatter()->format($this->complexObjectJson()));
    }
    
    
    /**
     *
     * @return \webignition\JsonPrettyPrinter\JsonPrettyPrinter
     */
    private function getFormatter() {
        if (is_null($this->formatter)) {
            $this->formatter = new \webignition\JsonPrettyPrinter\JsonPrettyPrinter();
        }
        
        return $this->formatter;
    }
    
    
    /**
     *
     * @return string 
     */
    private function complexObjectJson() {
        return '{"id":1,"user":{"id":1,"username":"public","username_canonical":"public","email":"public@simplytestable.com","email_canonical":"public@simplytestable.com","enabled":true,"salt":"fvuu3xfejooowocwg8c4kc0c8c8ocs4","password":"1\/uiKjsk1nMp8zYZ1kRKOoIEPgaj26NX8\/oYbSDjy6DNE\/ZWT8MXzlMzbu9nbncCuWVZygG4\/9mof9LA\/EUduw==","locked":false,"expired":false,"roles":[],"credentials_expired":false},"website":{"id":1,"canonical_url":"http:\/\/example.com\/"},"state":{"id":4,"name":"job-new","next_state":{"id":3,"name":"job-queued","next_state":{"id":2,"name":"job-in-progress","next_state":{"id":1,"name":"job-completed"}}}},"tasks":[],"start_date_time":"2012-07-23T18:56:43+0100"}';
    }
    
    
    /**
     *
     * @return string 
     */
    private function complexObjectFormattedJson() {
        return '{
  "id": 1,
  "user": {
    "id": 1,
    "username": "public",
    "username_canonical": "public",
    "email": "public@simplytestable.com",
    "email_canonical": "public@simplytestable.com",
    "enabled": true,
    "salt": "fvuu3xfejooowocwg8c4kc0c8c8ocs4",
    "password": "1\/uiKjsk1nMp8zYZ1kRKOoIEPgaj26NX8\/oYbSDjy6DNE\/ZWT8MXzlMzbu9nbncCuWVZygG4\/9mof9LA\/EUduw==",
    "locked": false,
    "expired": false,
    "roles": [
      
    ],
    "credentials_expired": false
  },
  "website": {
    "id": 1,
    "canonical_url": "http:\/\/example.com\/"
  },
  "state": {
    "id": 4,
    "name": "job-new",
    "next_state": {
      "id": 3,
      "name": "job-queued",
      "next_state": {
        "id": 2,
        "name": "job-in-progress",
        "next_state": {
          "id": 1,
          "name": "job-completed"
        }
      }
    }
  },
  "tasks": [
    
  ],
  "start_date_time": "2012-07-23T18:56:43+0100"
}';
    }
    
}