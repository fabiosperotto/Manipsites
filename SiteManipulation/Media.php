<?php
namespace SiteManipulation;

class Media{

    private $mediaTypes;

    public function __construct()
    {
        $this->mediaTypes = array(
            //application
            'zip'   => 'application/zip',
            'pdf'   => 'application/pdf',
            'doc'   => 'application/msword',
            'xls'   => 'application/vnd.ms-excel',
            'ppt'   => 'application/vnd.ms-powerpoint',
            'exe'   => 'application/octet-stream',
            'json'  => 'application/json',
            'xhtml' => 'application/xhtml+xml',
            //images
            'gif'   => 'image/gif',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/pjpeg',
            'svg'   => 'image/svg+xml',
            'tiff'  => 'image/tiff',
            //audio
            'mp3'   => 'audio/mpeg',
            'wav'   => 'audio/x-wav',
            'mpeg'  => 'video/mpeg',
            'mpg'   => 'video/mpeg',
            'mpe'   => 'video/mpeg',
            //video
            'mov'   => 'video/quicktime',
            'mpeg'  => 'video/mpeg',
            'mp4'   => 'video/mp4',
            'flv'   => 'video/x-flv',
            'avi'   => 'video/x-msvideo',
            'ogg'   => 'application/ogg',
            //text
            'css'   => 'text/css',
            'csv'   => 'text/csv',
            'html'  => 'text/html',
            'txt'   => 'text/plain',
            'xml'   => 'text/xml'
        );
    }

    public function getMediaTypes()
    {
        return $this->mediaTypes;
    }


    public function setMediaTypes($key,$value)
    {
        $this->mediaTypes[$key] = $value;
    }


    /**
     * Download remote file with extension
     * @param $urlFile address of a remote file with extension (http://www.example.com/archive.pdf)
     * @param $localPath absolute path to download directory
     * @return int returns the bytes copied from file_put_contents or 0 in case of some trouble
     */
    public function downloadFileURL($urlFile,$localPath)
    {
        set_time_limit(0);
        if(!filter_var($urlFile, FILTER_VALIDATE_URL)) return 0;
        $urlFile = filter_var($urlFile,FILTER_SANITIZE_URL);

        if(!is_dir($localPath)) return 0;

        return file_put_contents($localPath.basename($urlFile), fopen($urlFile, 'r'));
    }


    /**
     * This method can be used to download documents of various formats,
     * is more robust than the downloadFileURL method
     * @param $url address of a remote file without extension
     * @param $localPath absolute path to download directory
     * @param null $fileName custom name for the file (optional)
     * @return bool true if the download has completed or false in case of problems
     */
    public function downloadFile($url,$localPath,$fileName = null)
    {
        set_time_limit(0);
        if(!filter_var($url, FILTER_VALIDATE_URL)) return false;
        $urlFile = filter_var($url,FILTER_SANITIZE_URL);
        if(!is_dir($localPath)) return false;

        $headers = array_change_key_case(get_headers($url, TRUE));
        $extension = array_search($headers['content-type'],$this->mediaTypes);
        if($extension === false) return false;

        if($fileName == null) $fileName = 'file';

        try{
            $fo = fopen($url,"rb");

            while(!feof($fo))
            {
                $content = print_r(fread($fo, 2048),true);
                file_put_contents($localPath . $fileName. '.' .$extension, $content,FILE_APPEND);
                flush();
            }

            fclose ($fo);
            return true;

        }catch(Exception $e){
            return false;
        }
    }

}