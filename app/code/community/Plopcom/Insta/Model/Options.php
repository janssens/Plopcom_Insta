<?php
class Plopcom_Insta_Model_Options
{
  /**
   * Provide available options as a value/label array
   *
   * @return array
   */
  public function toOptionArray()
  {
    return array(
      array('value'=>'account', 'label'=>'User account'),
      array('value'=>'hashtag', 'label'=>'Hashtagged')     
    );
  }
}