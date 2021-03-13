<?php

namespace Mtools\AdminLog\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Driver\File;

class Data extends AbstractHelper
{
    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @var File
     */
    private $driverFile;

    /**
     * @param Context $context
     * @param DirectoryList $directoryList
     * @param File $driverFile
     */
    public function __construct(
        Context $context,
        DirectoryList $directoryList,
        File $driverFile
    ) {
        $this->directoryList = $directoryList;
        $this->driverFile = $driverFile;
        parent::__construct($context);
    }

    /**
     * @return string
     */
    public function getLogPath()
    {
        return $this->directoryList->getPath('log');
    }

    /**
     * @var string
     * @return array
     */
    protected function getLogFiles($path)
    {
        $list = $this->driverFile->readDirectory($path);
        $result = [];
        foreach ($list as $file) {
            $value = $file;
            if ($this->driverFile->isDirectory($file)) {
                foreach ($this->getLogFiles($file) as $childFile) {
                    $value = $childFile;
                }
            }
            $result[] = $value;
        }

        return $result;
    }

    /**
     * @param $bytes
     * @param int $precision
     * @return string
     */
    protected function filesizeToReadableString($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    /**
     * @return array
     */
    public function buildLogData()
    {
        $maxNumOfLogs = 30;
        $logFileData = [];
        $path = $this->getLogPath().DIRECTORY_SEPARATOR;

        //build log data into array
        $list = $this->getLogFiles($this->getLogPath());
        foreach ($list as $file) {
            $fileName = str_replace($path, '', $file);
            $fileStat = $this->driverFile->stat($file);
            $logFileData[$fileName]['name'] = $fileName;
            $logFileData[$fileName]['filesize'] = $this->filesizeToReadableString($fileStat['size']);
            $logFileData[$fileName]['modTime'] = $fileStat['mtime'];
            $logFileData[$fileName]['modTimeLong'] = date("F d Y H:i:s.", $fileStat['mtime']);
        }

        //sort array by modified time
        usort($logFileData, function ($item1, $item2) {
            return $item2['modTime'] <=> $item1['modTime'];
        });

        //limit the amount of log data $maxNumOfLogs
        $logFileData = array_slice($logFileData, 0, $maxNumOfLogs);

        return $logFileData;
    }

    /**
     * @param $fileName
     * @param $numOfLines
     *
     * @return string
     */
    public function getLastLinesOfFile($fileName, $numOfLines = 100)
    {
        $path = $this->getLogPath();
        $fullPath = $path . DS . $fileName;

        return $this->getTailCustom($fullPath, $numOfLines);
    }

    /**
     * @param      $filepath
     * @param int  $lines
     * @return false|string
     */
    protected function getTailCustom($filepath, $lines = 1)
    {
        // Open file
        try {
            $file = $this->driverFile->fileOpen($filepath, "rb");
        } catch (\Exception $e) {
            return false;
        }

        if ($file === false) {
            return false;
        }

        // Sets buffer size, according to the number of lines to retrieve.
        // This gives a performance boost when reading a few lines from the file.
        $buffer = 4096;
        $buffer = ($lines < 2 ? 64 : ($lines < 10 ? 512 : 4096));

        // Jump to last character
        fseek($file, -1, SEEK_END);

        // Read it and adjust line number if necessary
        // (Otherwise the result would be wrong if file doesn't end with a blank line)
        if ($this->driverFile->fileRead($file, 1) != "\n") {
            --$lines;
        }

        // Start reading
        $output = '';
        $chunk = '';

        // While we would like more
        while (ftell($file) > 0 && $lines >= 0) {

            // Figure out how far back we should jump
            $seek = min(ftell($file), $buffer);

            // Do the jump (backwards, relative to where we are)
            fseek($file, -$seek, SEEK_CUR);

            // Read a chunk and prepend it to our output
            $output = ($chunk = $this->driverFile->fileRead($file, $seek)) . $output;

            // Jump back to where we started reading
            fseek($file, -mb_strlen($chunk, '8bit'), SEEK_CUR);

            // Decrease our line counter
            $lines -= substr_count($chunk, "\n");

        }

        // While we have too many lines
        // (Because of buffer size we might have read too many)
        while ($lines++ < 0) {

            // Find first newline and remove all text before that
            $output = substr($output, strpos($output, "\n") + 1);

        }

        // Close file and return
        $this->driverFile->fileClose($file);
        return trim($output);
    }
}
