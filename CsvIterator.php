<?php

namespace Iterator;

use Exception;
use Iterator;

class CsvIterator implements Iterator
{

    const ROW_SIZE = 4096;

    /**
     * The pointer to the CSV file.
     *
     * @var resource
     */
    protected  $filePointer = null;

    /**
     * The current element, which is returned on each iteration.
     *
     * @var array|null
     */
    protected ?array $currentElement = null;

    /**
     * The row counter.
     *
     * @var int|null
     */
    protected ?int $rowCounter = null;

    /**
     * The delimiter for the CSV file.
     *
     * @var string|null
     */
    protected ?string $delimiter = null;

    /**
     * The constructor tries to open the CSV file. It throws an exception on
     * failure.
     *
     * @param string $file The CSV file.
     * @param string $delimiter The delimiter.
     *
     * @throws Exception
     */
    public function __construct(string $file, string $delimiter = ',')
    {
        try {
            $this->filePointer = fopen($file, 'rb');
            $this->delimiter = $delimiter;
        } catch (Exception $e) {
            throw new Exception('The file "' . $file . '" cannot be read.');
        }
    }


    /**
     * This method returns the current CSV row as a 2-dimensional array.
     *
     * @return array The current CSV row as a 2-dimensional array.
     */
    public function current(): array
    {
        return $this->currentElement ?: [];
    }

    /**
     * This method moves to the next element.
     */
    public function next(): void
    {
        if (is_resource($this->filePointer)) {
            $this->currentElement = fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
            $this->rowCounter++;
        }
    }

    /**
     * This method returns the current row number.
     *
     * @return int|null The current row number.
     */
    public function key(): ?int
    {
        return $this->rowCounter;
    }

    /**
     * This method checks if the current position is valid.
     *
     * @return bool If the current position is valid.
     */
    public function valid(): bool
    {
        if ($this->currentElement === false) {
            if (is_resource($this->filePointer)) {
                fclose($this->filePointer);
            }

            return false;
        }

        return is_resource($this->filePointer);
    }

    /**
     * @inheritDoc
     */
    public function rewind(): void
    {
        $this->rowCounter = 0;
        rewind($this->filePointer);
        // Read the first row to initialize
        $this->currentElement = fgetcsv($this->filePointer, self::ROW_SIZE, $this->delimiter);
    }
}