<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\Request;

class Morpher
{
    private static $instance;
    private $words;

    private function __construct() {
        $this->words = [];
    }

    public static function getInstance(): Morpher
    {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function set(string $word, array $variants) {
        $this->words[$word] = $variants;
    }

    public function get(string $word, int $case) {
        if(!isset($this->words[$word][$case])) $case = 0;
        return $this->words[$word][$case];
    }
}