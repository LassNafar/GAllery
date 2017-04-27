<?php
namespace Application\Service;

// Сервис менеджера изображений.
class ImageManager 
{
    // Каталог, куда мы сохраняем файлы изображений.
    private $saveToDir = './data/upload/';
        
    // Возвращаем путь к каталогу, куда мы сохраняем файлы изображений.
    public function getSaveToDir() 
    {
        return $this->saveToDir;
    }  
    
    // Возвращает путь к сохраненному файлу изображения.
    public function getImagePathByName($fileName) 
    {
        // Принимаем меры предосторожности, чтобы сделать файл безопасным.
        $fileName = str_replace("/", "", $fileName);  // Убираем слеши.
        $fileName = str_replace("\\", "", $fileName); // Убираем обратные слеши.
                
        // Возвращаем сцепленные имя каталога и имя файла.
        return $this->saveToDir . $fileName;                
    }
  
    // Возвращает содержимое файла изображения. При ошибке возвращает булевое false. 
    public function getImageFileContent($filePath) 
    {
        return file_get_contents($filePath);
    }
    
    // Извлекает информацию о файле (размер, MIME-тип) по его пути.
    public function getImageFileInfo($filePath) 
    {
        // Пробуем открыть файл        
        if (!is_readable($filePath)) {            
            return false;
        }
            
        // Получаем размер файла в байтах.
        $fileSize = filesize($filePath);

        // Получаем MIME-тип файла.
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filePath);
        if($mimeType===false)
            $mimeType = 'application/octet-stream';
    
        return [
            'size' => $fileSize,
            'type' => $mimeType 
        ];
    }  
    
    //  Изменяет размер изображения, сохраняя соотношение сторон.
    public  function resizeImage($filePath, $desiredWidth = 240) 
    {
        // Получаем исходную размерность файла.
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        // Вычисляем соотношение сторон.
        $aspectRatio = $originalWidth/$originalHeight;
        // Вычисляем получившуюся высоту.
        $desiredHeight = $desiredWidth/$aspectRatio;

        // Получаем информацию об изображении
        $fileInfo = $this->getImageFileInfo($filePath);
        
        // Изменяем размер изображения.
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        if (substr($fileInfo['type'], 0, 9) =='image/png')
            $originalImage = imagecreatefrompng($filePath);
        else
            $originalImage = imagecreatefromjpeg($filePath);
        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0, 
                $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        // Сохраняем измененное изображение во временное хранилище.
        $tmpFileName = tempnam("/var", "FOO");
        imagejpeg($resultingImage, $tmpFileName, 80);
        
        // Возвращаем путь к получившемуся изображению.
        return $tmpFileName;
    }
}
