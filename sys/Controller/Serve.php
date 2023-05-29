<?php

namespace Controller;

use CODOF\Util;

/**
 * Serves static files
 */
class Serve
{

    /**
     * Serves forum attachments
     *
     * @return void
     */
    public function attachment()
    {

        $this->serve('assets/img/attachments/');
    }

    /**
     * Serves forum attachments preview
     *
     * @return void
     */
    public function previewAttachment()
    {

        $this->serve('assets/img/attachments/preview/');
    }

    /**
     * Serves the attachment
     *
     * @return void
     */
    private function serve($path)
    {

        $dir = DATA_PATH . $path;
        $name = $_GET['path'];
        $absPath = $dir . $name;

        if (Util::isPathInBaseDir($absPath, $dir)) {
            $path = $absPath;
            $contentType = $this->getMimeType($path);
            if ($contentType == "video/mp4") {
                header("Location: " . ASSET_URL . "img/attachments/$name");
            } else {
                $this->setBasicheaders($absPath);
                header('Content-Disposition: attachment; filename="' . $this->getRealFileName($name) . '"');
                @readfile($path);
            }
            exit;
        }
    }

    /**
     * Gets the original name of file from table from hash
     * If not found a dummy name is returned
     *
     * @param [type] $hash
     * @return void
     */
    private function getRealFileName($hash)
    {

        $dummyName = "download";
        $name = \DB::table(PREFIX . 'codo_attachments')
            ->where('visible_hash', '=', $hash)
            ->pluck('original_name');

        return $name ? $name : $dummyName;
    }

    /**
     * Sets some basic headers for serving file
     *
     * @param [string] $name
     * @param [string] $dir
     * @return string
     */
    private function setBasicheaders($filename)
    {

        $mime_type = $this->getMimeType($filename);

        header("Content-type: $mime_type");
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+6 months")), true);
        header("Pragma: public");
        header("Cache-Control: public");

        return $filename;
    }

    /**
     * Gets the content type of the file
     * @param $file
     * @return mixed|string
     */
    private function getMimeType($file)
    {

        if (function_exists('finfo_file')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($finfo, $file);
            finfo_close($finfo);
            return $type;
        }

        return mime_content_type($file);
    }

}
