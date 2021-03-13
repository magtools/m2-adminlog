<?php

namespace Mtools\AdminLog\Helper;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem\Driver\File;

trait ReadLogFileTrait
{
    /**
     * Fetch block
     *
     * @param string $file
     * @param int    $start
     * @param int    $limit
     * @return array
     */
    public function fetch($file, $limit = 1, $start = 0): array
    {
        $output     = [];
        $block      = 0;
        $blockStamp = null;

        foreach ($this->fileReader()($file) as $line) {
            //match next log file text block by timestamp
            $matches = '';
            if (preg_match('#^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]#', $line, $matches)) {
                if ($start + $limit < ++$block) {
                    break;
                }
                if ($start + 1 > $block) {
                    continue;
                }

                $blockStamp = $matches[1]; // set new block timestamp
                $index = 0;
                while (isset($output[$blockStamp])) {
                    ++$index;
                    $blockStamp = $matches[1] . '.' . $index;
                }

                $line = str_replace($matches[0].' ', '', $line); // cut timestamp out
                $output[$blockStamp] = $line.PHP_EOL;
            } elseif ($output) {
                $output[$blockStamp] .= $line.PHP_EOL;
            }
        }

        return $output;
    }

    /**
     * @return \Closure
     */
    private function fileReader(): \Closure
    {
        return function ($filePath) {
            $fileDriver = new File;
            if (false !== strpos($filePath, '../')) {
                throw new FileSystemException(__('LFI protection. Parent directory is prohibited to use.'));
            }

            try {
                $file = $fileDriver->fileOpen($filePath, 'r');
            } catch (\Exception $e) {
                $file = false;
            }

            if (!$file) {
                throw new FileSystemException(__('Cannot open file: %1', $filePath));
            }

            if ($fileDriver->stat($filePath)['size'] > 0) {
                $contents = $fileDriver->fileReadLine($file, $fileDriver->stat($filePath)['size']);
                $rows = explode("\n", $contents);
                foreach ($rows as $row) {
                    yield $row;
                }
            }

            $fileDriver->fileClose($file);
        };
    }
}
