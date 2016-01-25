<?php
namespace common\components;
use PHPExcel_IOFactory;
use PHPExcelReader\SpreadsheetReader;
class KReader extends   XReaderInterface
{

    public function init()
    {
        parent::init();
        if($this->file_object->getSize() > 768000)
        {
            ini_set('memory_limit', '768M');
            $spreadsheet = new SpreadsheetReader($this->file_object->getPathname(),false);
            $this->data = $spreadsheet->sheets[0]['cells'];
            $this->start_position = 1;
        }
        else
        {
            $objReader = PHPExcel_IOFactory::createReaderForFile($this->file_object->getPathname());
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($this->file_object->getPathname());
            $sheet = $objPHPExcel->getSheet(0);
            $this->data = $sheet->toArray(null,true,null);
            $objPHPExcel->disconnectWorksheets();
            $this->start_position = 0;
            unset($objPHPExcel);
        }
    }

}