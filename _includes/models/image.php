<?php
    require_once(__DIR__."/model.php");
    class Image extends Model {
        private $id = null;
        private $filename = "";
        public function __construct(int $id = null) {
            parent::__construct();
            if ($id) {
                $this->id = $id;
                $this->load();
            }
        }
        public function getID() {
            return $this->id;
        }
        public function getFilename() {
            return $this->filename;
        }
        public function getURL() {
            return PROJECT_IMAGES . "/" . $this->filename;
        }
        public function load() {
            $image = $this->get("images", "id", $this->id);
            if ($image)
                $this->copy($image);
            return $image;
        }
        public static function newImage(
            string $filename,
        ) {
            // $image = new Image();
            // $image->name = $name;
            // $image->description = $description;
            // $image->price = $price;
            // $image->category = $category;
            // $image->age = $age;
            // $image->create("images", [
            //     "name" => $image->name,
            //     "description" => $image->description,
            //     "price" => $image->price,
            //     "category" => $image->category,
            //     "age" => $image->age,
            // ]);
            // return $image;
        }
        public function copy(array $image) {
            $this->id = $image["id"];
            $this->filename = $image["filename"];
        }
        public static function upload(string $name) {
            $img = $_FILES[$name]["tmp_name"];
            $uuid = str_replace(".","",uniqid("IMG_",true));
            $dst = PROJECT_IMAGES . "/" . $uuid;
            if (($img_info = getimagesize($img)) === FALSE)
                die("Image not found or not an image");
            
            $width = $img_info[0];
            $height = $img_info[1];

            switch ($img_info[2]) {
                case IMAGETYPE_GIF:
                    $src = imagecreatefromgif($img);
                    break;
                case IMAGETYPE_JPEG:
                    $src = imagecreatefromjpeg($img);
                    break;
                case IMAGETYPE_PNG:
                    $src = imagecreatefrompng($img);
                    break;
                default:
                    die("Image type not supported");
            }

            $tmp = imagecreatetruecolor($width, $height);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $width, $height, $width, $height);
            imagejpeg($tmp, $dst . ".jpg", 100);
            imagedestroy($tmp);

            $image = new Image();
            $image->filename = $uuid . ".jpg";
            $image->create("images", [
                "filename" => $image->filename,
            ]);
            return $image;
        }
    }