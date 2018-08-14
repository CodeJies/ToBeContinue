<?php

/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/8/13
 * Time: 14:49
 */
class ContentModel {
    //put your code here
    var $ID;
    var $Name;
    var $Content;
    public function ParseFromDOMElement(DOMElement $row)
    {
        $cells_list = $row->getElementsByTagName('td');
        $cells_length = $row->length;

        $curCellIdx = 0;
        foreach ($cells_list as $cell)
        {
            switch ($curCellIdx++)
            {
                case 0:
                    $this->ID = $cell->nodeValue;
                    break;
                case 1:
                    $this->Name = $cell->nodeValue;
                    break;
                case 2:
                    $this->Content = $cell->nodeValue;
                    break;
            }
        }
    }
