<?php

class Plopcom_Insta_SecretController extends Mage_Core_Controller_Front_Action
{
    public function loadAction()
    {
        $_customer = Mage::getSingleton('customer/session')->isLoggedIn();
        if ($_customer){
            $email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
            $admin_user = Mage::getModel('admin/user')->load($email,'email');
            if ($admin_user){
                $helper = Mage::helper('plopcom_insta');
                if (isset($_POST['content'])&&isset($_POST['location'])&&isset($_POST['salt'])){
                    if (md5($helper->getSalt())==$_POST['salt']){
                        $location = $_POST['location'];
                        $username = false;
                        foreach ($helper->getAllUniqueUrls() as $k => $url){
                            if (strpos($url,$location)===0){
                                $username = $k;
                                break;
                            }
                        }
                        if ($username){
                            $content = $_POST['content'];
                            $imported = Mage::helper('plopcom_insta')->getFromHtml($content,$username,10);
                            echo $imported.' post imported '.(($imported>0) ? "🙂":"").'!'."\n";
                        }else{
                            echo 'Cannot import form this url ⚠ !'."\n";
                        }
                    }else{
                        echo 'Someting went wrong 😥 !'."\n";
                    }
                }else{
                    echo 'no data posted 😥 !'."\n";
                    echo 'NB : it only works with chrome or chronium, not firefox 🦊!'."\n";
                }
            }else{
                echo 'Your do not have an admin user with this email ('.$email.') 🚷 !'."\n";
            }
        }else{
            echo 'Your are not logged in as a customer (frontend) !'."\n";
            echo '<script> setTimeout(function(){window.location = "'.Mage::getUrl('customer/account/login').'";},2000) </script>'."\n";
            die();
        }

        echo '<script> setTimeout(function(){window.close();},3000) </script>'."\n";
        die();
    }

}
