<?php
class Plopcom_Insta_Model_Resolutions
{
  /**
   * Provide available options as a value/label array
   *
   * @return array
   */
  public function toOptionArray()
  {
    return array(
      array('value'=>'thumbnail', 'label'=>'thumbnail (default) - 150x150'),
      array('value'=>'low_resolution', 'label'=>'low_resolution - 306x306'),
      array('value'=>'standard_resolution', 'label'=>'standard_resolution - 612x612'),      
    );
  }
}