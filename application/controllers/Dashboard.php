<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 30000);
use WebPConvert\WebPConvert;

class Dashboard extends CI_Controller {

    public $viewFolder = "";

    public function __construct()
    {
        parent::__construct();

        $this->viewFolder = "dashboard";

    }

    public function index()
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "listele";
//        $viewData->files = $this->getFilesInFolder("assets");

//        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        $this->load->view("{$viewData->viewFolder}/index", $viewData);
    }

//    public function getFilesInFolder($folder){
//        $folder = FCPATH.$folder;
//        return scandir($folder);
//    }

    public function getFilesInFolder(){
        $result = [];
        $folder = html_entity_decode($this->input->get("folder"));
        $result["folder"] = $folder;
        $folder = str_replace(FCPATH, "", $folder);
        $sourceFolder = FCPATH.$folder;
        $contents = scandir($sourceFolder);
        foreach ($contents as $content){
            $path = $folder."/".$content;
            $name = ($content == "..") ? "Geri" : $content;
            $type = mime_content_type($path);
            $size = filesize($path);
            $createdDate = date("d-m-Y H:i:s", filemtime($path));
            $replacePath = str_replace(FCPATH, "", $path);

            if($content == "."){
                continue;
            }

            $contentArray = [
                "name" => $name,
                "type" => $type,
                "size" => $size,
                "created_date" => $createdDate,
                "path" => ($type == "directory") ? $replacePath : base_url().$replacePath,
            ];
            $result["contents"][] = $contentArray;
        }
        echo json_encode($result);
    }

    public function getImagesInFolder(){
        $extensions = ["jpeg", "jpg", "png"];
        $result = [];
        $folder = html_entity_decode($this->input->get("folder"));
        $result["folder"] = $folder;
        $sourceFolder = FCPATH.$folder;

        function scanDirectory($sourceFolder){
            $di = new RecursiveDirectoryIterator($sourceFolder);
            return $di;
        }

        $allFiles = scanDirectory($sourceFolder);

        foreach (new RecursiveIteratorIterator($allFiles) as $filename => $file) {
            if($file->getType() == "file" && in_array($file->getExtension(), $extensions)) {
                $path = $filename;
                $replacePath = str_replace(FCPATH, "", $path);
                $replacePath = preg_replace('/\\\\/', '/', $replacePath);
                $contentArray = [
                    "name" => $file->getFilename(),
                    "path" => $filename,
                    "type" => $file->getType(),
                    "extension" => $file->getExtension(),
                    "folder" => $replacePath,
                    "status" => "uncomplete",
                ];
                $result["contents"][] = $contentArray;
            }
        }

        echo json_encode($result);
    }

    public function convertToWebp(){
        $file = json_decode($this->input->post("file"));
        $result = [];
        $extensions = ["jpeg", "jpg", "png"];

        $result["file"] = $file;

        if($file->type == "file" && in_array($file->extension, $extensions)){
            $source = $file->path;
            $destination = $source . '.webp';
            $options = [];
            $convertResponse = WebPConvert::convert($source, $destination, $options);
            $result["convertResponse"] = $convertResponse;
            $result["status"] = "complete";
        }

        echo json_encode($result);
    }

    public function getFilesInFolderDeneme(){
        $result = [];
        $folder = html_entity_decode($this->input->get("folder"));
        $result["folder"] = $folder;
        $folder = str_replace(FCPATH, "", $folder);
        $sourceFolder = FCPATH.$folder;
        $contents = scandir($sourceFolder);
        foreach ($contents as $content){
            $path = $folder."/".$content;
            $name = ($content == "..") ? "Geri" : $content;
            $type = mime_content_type($path);
            $size = filesize($path);
            $createdDate = date("d-m-Y H:i:s", filemtime($path));
            $replacePath = str_replace(FCPATH, "", $path);

            if($content == "."){
                continue;
            }

            $contentArray = [
                "name" => $name,
                "type" => $type,
                "size" => $size,
                "created_date" => $createdDate,
                "path" => ($type == "directory") ? $replacePath : base_url().$replacePath,
            ];
            $result["contents"][] = $contentArray;
        }
        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
}
