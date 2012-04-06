<?php
/**
 * Расширение для ресайзинга и загрузки изображений
 */
class ImageEditor extends CApplicationComponent{

    /**
     * Ресайзинг изображения
     * @param $file
     * @param $dir
     * @param $id
     * @param $width
     * @param null $height
     * @return bool
     */
    public function resize($file, $dir, $id, $width, $height=null){
        $upload = new upload($file);
        $upload->file_new_name_body = $id . '_' . $width;
        $upload->allowed = array('image/png', 'image/jpg', 'image/jpeg');
        $upload->image_convert = 'jpg';
        $upload->file_new_name_ext = 'jpg';
        $upload->image_resize = true;
        $upload->jpeg_quality = 87;
        $upload->file_overwrite = true;
        $upload->dir_auto_create = true;
        if (is_null($height)){
            $upload->image_ratio_y = true;
        }else{
            $upload->image_y = $height;
            $upload->image_ratio_crop = true;
        }
        $upload->image_x = $width;
        $upload->process($dir);
        if (!$upload->processed){
            return false;
        }
        return $upload->file_dst_pathname;
    }

    public function init(){
        parent::init();
    }
}