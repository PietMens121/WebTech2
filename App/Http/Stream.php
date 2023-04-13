<?php

namespace App\Http;

use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class Stream implements StreamInterface
{

    private $stream;

    public function __construct($stream)
    {
        if (is_resource($stream)) {
            $this->stream = $stream;
        } elseif (is_string($stream)) {
            $this->stream = fopen($stream, 'r+');
        } else {
            throw new InvalidArgumentException('Invalid stream');
        }
    }

    public function __toString()
    {
        if (!$this->isSeekable()) {
            return '';
        }

        try {
            $this->rewind();
            return $this->getContents();
        } catch (RuntimeException $e) {
            return '';
        }
    }

    public function close()
    {
        if (isset($this->stream)) {
            fclose($this->stream);
        }
        $this->detach();
    }

    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }

        $result = $this->stream;
        unset($this->stream);
        return $result;
    }

    public function getSize()
    {
        if (!isset($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);
        return isset($stats['size']) ? $stats['size'] : null;
    }

    public function tell()
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached');
        }

        $result = ftell($this->stream);
        if ($result === false) {
            throw new RuntimeException('Unable to determine stream position');
        }

        return $result;
    }

    public function eof()
    {
        if (!isset($this->stream)) {
            return true;
        }

        return feof($this->stream);
    }

    public function isSeekable()
    {
        if (!isset($this->stream)) {
            return false;
        }

        $meta = stream_get_meta_data($this->stream);
        return $meta['seekable'];
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached');
        }

        if (!$this->isSeekable()) {
            throw new RuntimeException('Stream is not seekable');
        }

        if (fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException('Unable to seek to stream position ' . $offset . ' with whence ' . var_export($whence, true));
        }
    }

    public function rewind()
    {
        $this->seek(0);
    }

    public function isWritable()
    {
        if (!isset($this->stream)) {
            return false;
        }

        $meta = stream_get_meta_data($this->stream);
        $mode = $meta['mode'];
        return (str_contains($mode, 'x') || str_contains($mode, 'w') || str_contains($mode, 'c') || str_contains($mode,
                'a') || str_contains($mode, '+'));
    }

    public function write($string)
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached');
        }

        if (!$this->isWritable()) {
            throw new RuntimeException('Stream is not writable');
        }

        $result = fwrite($this->stream, $string);
        if ($result === false) {
            throw new RuntimeException('Unable to write to stream');
        }

        return $result;
    }

    public function isReadable()
    {
        if (!isset($this->stream)) {
            return false;
        }

        $meta = stream_get_meta_data($this->stream);
        $mode = $meta['mode'];
        return (strpos($mode, 'r') !== false || strpos($mode, '+') !== false);
    }

    public function read($length)
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached');
        }

        if (!$this->isReadable()) {
            throw new RuntimeException('Stream is not readable');
        }

        $result = fread($this->stream, $length);
        if ($result === false) {
            throw new RuntimeException('Unable to read from stream');
        }

        return $result;
    }

    public function getContents()
    {
        if (!isset($this->stream)) {
            throw new RuntimeException('Stream is detached');
        }

        $result = stream_get_contents($this->stream);
        if ($result === false) {
            throw new RuntimeException('Unable to read stream contents');
        }

        return $result;
    }

    public function getMetadata($key = null)
    {
        if (!isset($this->stream)) {
            return null;
        }

        $meta = stream_get_meta_data($this->stream);

        if ($key === null) {
            return $meta;
        }

        return isset($meta[$key]) ? $meta[$key] : null;
    }
}