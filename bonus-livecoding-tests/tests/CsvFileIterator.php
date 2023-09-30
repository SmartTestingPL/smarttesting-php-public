<?php

declare(strict_types=1);

namespace SmartTesting\Tests;

/**
 * Iterator po danych w pliku CSV, będzie nam potrzebny w przykładzie testów parametryzowanych.
 */
class CsvFileIterator implements \Iterator
{
    /**
     * @var false|resource
     */
    protected $file;

    protected int $key = 0;
    /**
     * @var array|false|null
     */
    protected $current;
    private int $numLinesToSkip;

    public function __construct(string $file, int $numLinesToSkip = 0)
    {
        $this->file = fopen($file, 'r');
        $this->numLinesToSkip = $numLinesToSkip;
        $this->skipLines();
    }

    public function __destruct()
    {
        fclose($this->file);
    }

    public function rewind()
    {
        rewind($this->file);
        $this->current = fgetcsv($this->file);
        $this->key = 0;
        $this->skipLines();
    }

    public function valid()
    {
        return !feof($this->file);
    }

    public function key()
    {
        return $this->key;
    }

    public function current()
    {
        return $this->current;
    }

    public function next()
    {
        $this->current = fgetcsv($this->file);
        ++$this->key;
    }

    private function skipLines(): void
    {
        $numLinesToSkip = $this->numLinesToSkip;

        while ($numLinesToSkip >= 1) {
            $this->next();
            --$numLinesToSkip;
        }
    }
}
