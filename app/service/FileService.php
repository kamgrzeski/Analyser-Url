<?php

class FileService
{
    public function captureGivenFile($file)
    {
        $fileContent = $this->getFileContent($file);
        $fileOpen = $this->trimDataFromFileToArray($fileContent);

        return $this->resetArrayIndex($fileOpen);
    }

    private function getFileContent($file)
    {
        return file_get_contents($file);
    }

    private function trimDataFromFileToArray($file)
    {
        return array_filter(array_map('trim', explode(PHP_EOL, $file)));
    }

    private function resetArrayIndex($file)
    {
        return array_values($file);
    }

    public function countFile($file)
    {
        return count($file);
    }

    public function checkIfFileIsUploaded($file)
    {
        if (is_uploaded_file($file)) {
            return true;
        } else {
            return false;
        }
    }

    public function saveDetectedUrlsToFile($filename, $links)
    {
        return file_put_contents($filename, $links);
    }
}