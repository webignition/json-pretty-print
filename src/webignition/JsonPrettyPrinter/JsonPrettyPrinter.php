<?php
namespace webignition\JsonPrettyPrinter;

class JsonPrettyPrinter {
    
    const OBJECT_START_IDENTIFIER = '{';
    const OBJECT_END_IDENTIFIER = '}';
    const ARRAY_START_IDENTIFIER = '[';
    const ARRAY_END_IDENTIFIER = ']';
    const COLLECTION_ITEM_SEPARATOR = ',';
    const NAME_VALUE_PAIR_SEPARATOR = ':';
    const ESCAPE_CHARACTER = '\\';
    const STRING_DELIMITER = '"';
    const TAB = '  ';
    
    /**
     *
     * @var string
     */
    private $input = null;
    
    
    /**
     *
     * @var int
     */
    private $inputLength = 0;
    
    /**
     *
     * @var int
     */
    private $currentCharacterIndex = 0;
    
    /**
     *
     * @var string
     */
    private $output = null;
    
    
    /**
     *
     * @var boolean
     */
    private $isInString = false;
    
    
    /**
     *
     * @var int
     */
    private $indentationLevel = 0;
    
    
    public function reset() {
        $this->input = null;
        $this->inputLength = 0;
        $this->currentCharacterIndex = 0;
        $this->output = null;
        $this->isInString = false;
        $this->indentationLevel = 0;
    }
    
    
    /**
     *
     * @param string $json 
     * @return string
     */
    public function format($json = null) {
        $this->input = (is_string($json)) ? trim($json) : null;
        if (is_null($this->input) || is_null(json_decode($this->input))) {
            return json_encode(null);
        }
        
        $this->inputLength = strlen($this->input);
        return $this->getOutput();
    }
    
    
    /**
     *
     * @return string
     */
    private function getOutput() {
        if (is_null($this->output)) {
            $this->generateOutput();
        }
        
        return $this->output;
    }
    
    
    private function generateOutput() {
        $this->output = '';
        
        for ($this->currentCharacterIndex = 0; $this->currentCharacterIndex < $this->inputLength; $this->currentCharacterIndex++) {
            $this->generateOutputForCurrentCharacter();
        }        
    }
    
    
    /**
     *
     * @return string
     */
    private function getCurrentCharacter() {
        return $this->input[$this->currentCharacterIndex];
    }
    
    
    /**
     *
     * @return string
     */
    private function getPreviousCharacter() {
        $previousCharacterIndex = $this->currentCharacterIndex - 1;
        return ($previousCharacterIndex < 0) ? null : $this->input[$previousCharacterIndex];
    }
    
    
    /**
     *
     * @return boolean
     */
    private function hasPreviousCharacter() {
        return !is_null($this->getPreviousCharacter());
    }
    
    
    /**
     *
     * @return boolean
     */
    private function isCurrentCharacterObjectStart() {
        return $this->getCurrentCharacter() == self::OBJECT_START_IDENTIFIER;
    }
    
    
    /**
     *
     * @return boolean
     */
    private function isCurrentCharacterObjectEnd() {
        return $this->getCurrentCharacter() == self::OBJECT_END_IDENTIFIER;
    }
    
    /**
     *
     * @return boolean
     */
    private function isCurrentCharacterArrayStart() {
        return $this->getCurrentCharacter() == self::ARRAY_START_IDENTIFIER;
    }
    
    /**
     *
     * @return boolean
     */
    private function isCurrentCharacterArrayEnd() {
        return $this->getCurrentCharacter() == self::ARRAY_END_IDENTIFIER;
    } 
    
    
    /**
     *
     * @return boolean
     */
    private function isCurrentCharacterCollectionItemSeparator() {
        return $this->getCurrentCharacter() == self::COLLECTION_ITEM_SEPARATOR;
    }
    
    /**
     *
     * @return boolean
     */
    private function isCurrentCharacterNameValuePairSeparator() {
        return $this->getCurrentCharacter() == self::NAME_VALUE_PAIR_SEPARATOR;
    }
    
    
    /**
     *
     * @return boolean
     */
    private function isCurrentCharacterStringDelimiter() {
        return $this->getCurrentCharacter() == self::STRING_DELIMITER;
    }    
    
    
    /**
     *
     * @return boolean 
     */
    private function isCurrentCharacterValidStringDelimiter() {
        if (!$this->isCurrentCharacterStringDelimiter()) {
            return false;
        }
        
        if (!$this->hasPreviousCharacter()) {
            return true;
        }
        
        return $this->getPreviousCharacter() != self::ESCAPE_CHARACTER;
    }
    
    
    private function generateOutputForCurrentCharacter() {
        if ($this->isCurrentCharacterObjectStart() || $this->isCurrentCharacterArrayStart()) {
            if ($this->isInString) {
                return $this->output .= $this->getCurrentCharacter();
            }
            
            return $this->output .= $this->getCurrentCharacter() . "\n" . str_repeat(self::TAB, ++$this->indentationLevel);
        }
        
        
        if ($this->isCurrentCharacterObjectEnd() || $this->isCurrentCharacterArrayEnd()) {            
            if ($this->isInString) {
                return $this->output .= $this->getCurrentCharacter();
            }
            
            $this->indentationLevel--;
            return $this->output .=  "\n" . str_repeat(self::TAB, $this->indentationLevel) . $this->getCurrentCharacter();
        }
        
        if ($this->isCurrentCharacterCollectionItemSeparator()) {
            if ($this->isInString) {
                return $this->output .= $this->getCurrentCharacter();
            }
            
            return $this->output .= self::COLLECTION_ITEM_SEPARATOR . "\n" . str_repeat(self::TAB, $this->indentationLevel);
        }
        
        if ($this->isCurrentCharacterNameValuePairSeparator()) {
            if ($this->isInString) {
                return $this->output .= $this->getCurrentCharacter();
            }
            
            return $this->output .= self::NAME_VALUE_PAIR_SEPARATOR . ' ';
        }
        
        if ($this->isCurrentCharacterValidStringDelimiter()) {
            $this->isInString = !$this->isInString;
        }
        
        return $this->output .= $this->getCurrentCharacter();
        
        $char = $this->getCurrentCharacter(); 
        
        
        
        switch($char) 
        { 
            case '{': 
            case '[': 
                if(!$in_string) 
                { 
                    $new_json .= $char . "\n" . str_repeat($tab, $indent_level+1); 
                    $indent_level++; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case '}': 
            case ']': 
                if(!$in_string) 
                { 
                    $indent_level--; 
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case ',': 
                if(!$in_string) 
                { 
                    $new_json .= ",\n" . str_repeat($tab, $indent_level); 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case ':': 
                if(!$in_string) 
                { 
                    $new_json .= ": "; 
                } 
                else 
                { 
                    $new_json .= $char; 
                } 
                break; 
            case '"': 
                if($c > 0 && $json[$c-1] != '\\') 
                { 
                    $in_string = !$in_string; 
                } 
            default: 
                $new_json .= $char; 
                break;                    
        }         
    }
    
    
}