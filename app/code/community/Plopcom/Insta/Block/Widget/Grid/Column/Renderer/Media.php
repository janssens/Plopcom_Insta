<?php

class Plopcom_Insta_Block_Widget_Grid_Column_Renderer_Media
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $html = '<img src="'.$row->getMediaUrl().'" style="max-height: 50px;" />';
        return $html;
    }

}