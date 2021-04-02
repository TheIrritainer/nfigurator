<?php

/**
 * This file is part of the Nginx Config Processor package.
 *
 * (c) Michael Tiel <michael@tiel.dev>
 * (c) Toms Seisums
 * (c) Roman Piták <roman@pitak.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Nfigurator;

class Text
{
    private const CURRENT_POSITION = -1;

    /** @var string $data */
    private $data;

    /** @var int $position */
    private $position;

    /**
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->position = 0;
        $this->data = $data;
    }

    /*
     * ========== Getters ==========
     */

    /**
     * Returns one character of the string.
     *
     * Does not move the string pointer. Use inc() to move the pointer after getChar().
     *
     * @param int $position If not specified, current character is returned.
     * @return string The current character (under the pointer).
     * @throws Exception When out of range
     */
    public function getChar(int $position = self::CURRENT_POSITION)
    {
        if (self::CURRENT_POSITION === $position) {
            $position = $this->position;
        }

        if (!is_int($position)) {
            throw new Exception('Position is not int. ' . gettype($position));
        }

        if ($this->eof()) {
            throw new Exception('Index out of range. Position: ' . $position . '.');
        }

        return $this->data[$position];
    }

    /**
     * Get the text from $position to the next end of line.
     *
     * Does not move the string pointer.
     *
     * @param int $position
     * @return string
     */
    public function getRestOfTheLine(int $position = self::CURRENT_POSITION): string
    {
        if (self::CURRENT_POSITION === $position) {
            $position = $this->position;
        }
        $text = '';
        while ((false === $this->eof($position)) && (false === $this->eol($position))) {
            $text .= $this->getChar($position);
            $position++;
        }
        return $text;
    }

    /**
     * Is this the end of line?
     *
     * @param int $position
     * @return bool
     * @throws Exception
     */
    public function eol(int $position = self::CURRENT_POSITION): bool
    {
        return (("\r" === $this->getChar($position)) || ("\n" === $this->getChar($position)));
    }

    /**
     * Is this line empty?
     *
     * @param int $position
     * @return bool
     */
    public function isEmptyLine(int $position = self::CURRENT_POSITION): bool
    {
        $line = $this->getCurrentLine($position);
        return (0 === strlen(trim($line)));
    }

    /**
     * Get the current line.
     *
     * @param int $position
     * @return string
     */
    public function getCurrentLine(int $position = self::CURRENT_POSITION): string
    {
        if (self::CURRENT_POSITION === $position) {
            $position = $this->position;
        }

        $offset = $this->getLastEol($position);
        $length = $this->getNextEol($position) - $offset;
        return substr($this->data, $offset, $length);
    }

    /**
     * Get the position of the last (previous) EOL.
     *
     * @param int|bool $position
     * @return int
     */
    public function getLastEol(int $position = self::CURRENT_POSITION)
    {
        if (self::CURRENT_POSITION === $position) {
            $position = $this->position;
        }

        return strrpos(substr($this->data, 0, $position), "\n", 0);
    }

    /**
     * Get the position of the next EOL.
     *
     * @param int $position
     * @return int
     */
    public function getNextEol(int $position = self::CURRENT_POSITION): int
    {
        if (self::CURRENT_POSITION === $position) {
            $position = $this->position;
        }

        $eolPosition = strpos($this->data, "\n", $position);
        if (false === $eolPosition) {
            $eolPosition = strlen($this->data) - 1;
        }

        return $eolPosition;
    }

    /**
     * Is this the end of file (string) or beyond?
     *
     * @param int $position
     * @return bool
     */
    public function eof(int $position = self::CURRENT_POSITION): bool
    {
        if (self::CURRENT_POSITION === $position) {
            $position = $this->position;
        }
        return (!isset($this->data[$position]));
    }

    /*
     * ========== Manipulators ==========
     */

    /**
     * Move string pointer.
     *
     * @param int $inc
     * @return self
     */
    public function inc(int $inc = 1): self
    {
        $this->position += $inc;

        return $this;
    }

    /**
     * Move pointer (position) to the next EOL.
     *
     * @param int $position
     * @return self
     */
    public function gotoNextEol(int $position = self::CURRENT_POSITION): self
    {
        if (self::CURRENT_POSITION === $position) {
            $position = $this->position;
        }
        $this->position = $this->getNextEol($position);

        return $this;
    }
}
