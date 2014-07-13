<?php
/**
 * BoxBilling
 *
 * LICENSE
 *
 * This source file is subject to the license that is bundled
 * with this package in the file LICENSE.txt
 * It is also available through the world-wide-web at this URL:
 * http://www.boxbilling.com/LICENSE.txt
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@boxbilling.com so we can send you a copy immediately.
 *
 * @copyright Copyright (c) 2010-2012 BoxBilling (http://www.boxbilling.com)
 * @license   http://www.boxbilling.com/LICENSE.txt
 * @version   $Id$
 */
class Server_Manager_Vesta extends Server_Manager
{
    /**
     * Method is called just after obejct contruct is complete.
     * Add required parameters checks here. 
     */
	public function init()
    {
        
	}

    /**
     * Return server manager parameters.
     * @return type 
     */
    public static function getForm()
    {
        return array(
            'label'     =>  'Vesta Server Manager',
        );
    }

    /**
     * Returns link to account management page
     * 
     * @return string 
     */
    public function getLoginUrl()
    {
        return 'http://www.google.com?q=cpanel';
    }

    /**
     * Returns link to reseller account management
     * @return string 
     */
    public function getResellerLoginUrl()
    {
        return 'http://www.google.com?q=whm';
    }

   
private function _makeRequest($params)
    {

$host = 'http';
		if ($this->_config['secure']) {
			$host .= 's';
		}
		$host .= '://' . $this->_config['host'] . ':'.$this->_config['port'].'/api/';

    	
    	

    	

    	
// Send POST query via cURL
$postdata = http_build_query($params);
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $host);


curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);

$answer = curl_exec($curl);
// Check result
if($answer == 0) {
    echo "User account has been successfuly created\n";
    $result = TRUE;
} else {
     $result = FALSE;

    echo "Query returned error code: " .$answer. "\n";

    
}

return $result ;

    }




    /**
     * This method is called to check if configuration is correct
     * and class can connect to server
     * 
     * @return boolean 
     */


    public function testConnection()
    {
      

    
           
        // Server credentials
$vst_username = $this->_config['username'];
$vst_password = $this->_config['password'];
$vst_command = 'list-sys-info';
$vst_returncode = 'yes';


// Prepare POST query
$postvars = array(
    'user' => $vst_username,
    'password' => $vst_password,
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => $a->getUsername(),
    

						

);    
// Make request and check sys info

		$result = $this->_makeRequest($postvars);

// Check result
if($result == 0) {
    echo "Conection to server is OK \n";
  $ret = TRUE;
} else {
    echo "Query returned error code: " .$answer. "\n";
  $ret = false;
}

     return $ret;
    }



    /**
     * MEthods retrieves information from server, assignes new values to
     * cloned Server_Account object and returns it.
     * @param Server_Account $a
     * @return Server_Account 
     */
    public function synchronizeAccount(Server_Account $a)
    {
        $this->getLog()->info('Synchronizing account with server '.$a->getUsername());
        $new = clone $a;
        //@example - retrieve username from server and set it to cloned object
        //$new->setUsername('newusername');
        return $new;
    }



    /**
     * Create new account on server
     * 
     * @param Server_Account $a 
     */



	public function createAccount(Server_Account $a)


    {

           $p = $a->getPackage();
		$resourcePlan = $p;
		
		$client = $a->getClient();
        // Server credentials
$vst_username = $this->_config['username'];
$vst_password = $this->_config['password'];
$vst_command = 'v-add-user';
$vst_returncode = 'yes';

// New Account
$package = 'default';
$fist_name = 'Rust';
$last_name = 'Cohle';

// Prepare POST query
$postvars = array(
    'user' => $vst_username,
    'password' => $vst_password,
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => $a->getUsername(),
    'arg2' => $a->getPassword(),
    'arg3' => $client->getEmail(),
    'arg4' => $package,
    'arg5' => $a->getUsername(),
    'arg6' => $a->getUsername()

						

);    
// Make request and create user 

		$result = $this->_makeRequest($postvars);

		

// Create Domain Prepare POST query
$postvars = array(
    'user' => $vst_username,
    'password' => $vst_password,

    'returncode' => 'yes',
    'cmd' => 'v-add-domain',
    'arg1' => $a->getUsername(),
    'arg2' => $a->getDomain()
    
);

$result = $this->_makeRequest($postvars);


	return $result;


	}




    /**
     * Suspend account on server
     * @param Server_Account $a 
     */
	public function suspendAccount(Server_Account $a)
    {




           
        // Server credentials
$vst_username = $this->_config['username'];
$vst_password = $this->_config['password'];
$vst_command = 'v-suspend-user';
$vst_returncode = 'yes';


// Prepare POST query
$postvars = array(
    'user' => $vst_username,
    'password' => $vst_password,
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => $a->getUsername(),
    

						

);    
// Make request and suspend user 

		$result = $this->_makeRequest($postvars);






        if($a->getReseller()) {
            $this->getLog()->info('Suspending reseller hosting account');
        } else {
            $this->getLog()->info('Suspending shared hosting account');
        }
	}

    /**
     * Unsuspend account on server
     * @param Server_Account $a 
     */
	public function unsuspendAccount(Server_Account $a)
    {


           
        // Server credentials
$vst_username = $this->_config['username'];
$vst_password = $this->_config['password'];
$vst_command = 'v-unsuspend-user';
$vst_returncode = 'yes';


// Prepare POST query
$postvars = array(
    'user' => $vst_username,
    'password' => $vst_password,
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => $a->getUsername(),
    

						

);    
// Make request and suspend user 

		$result = $this->_makeRequest($postvars);






        if($a->getReseller()) {
            $this->getLog()->info('Unsuspending reseller hosting account');
        } else {
            $this->getLog()->info('Unsuspending shared hosting account');
        }
	}


	

    /**
     * Cancel account on server
     * @param Server_Account $a 
     */
	public function cancelAccount(Server_Account $a)
    {
        




           
        // Server credentials
$vst_username = $this->_config['username'];
$vst_password = $this->_config['password'];
$vst_command = 'v-delete-user';
$vst_returncode = 'yes';


// Prepare POST query
$postvars = array(
    'user' => $vst_username,
    'password' => $vst_password,
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => $a->getUsername(),
    

						

);    
// Make request and suspend user 

		$result = $this->_makeRequest($postvars);



if($a->getReseller()) {
            $this->getLog()->info('Canceling reseller hosting account');
        } else {
            $this->getLog()->info('Canceling shared hosting account');
        }
	}

    /**
     * Change account package on server
     * @param Server_Account $a
     * @param Server_Package $p 
     */
	public function changeAccountPackage(Server_Account $a, Server_Package $p)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Updating reseller hosting account');
        } else {
            $this->getLog()->info('Updating shared hosting account');
        }
        
        $p->getName();
        $p->getQuota();
        $p->getBandwidth();
        $p->getMaxSubdomains();
        $p->getMaxParkedDomains();
        $p->getMaxDomains();
        $p->getMaxFtp();
        $p->getMaxSql();
        $p->getMaxPop();
        
        $p->getVestaValue('param_name');
	}

    /**
     * Change account username on server
     * @param Server_Account $a
     * @param type $new - new account username
     */
    public function changeAccountUsername(Server_Account $a, $new)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Changing reseller hosting account username');
        } else {
            $this->getLog()->info('Changing shared hosting account username');
        }
    }

    /**
     * Change account domain on server
     * @param Server_Account $a
     * @param type $new - new domain name
     */
    public function changeAccountDomain(Server_Account $a, $new)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Changing reseller hosting account domain');
        } else {
            $this->getLog()->info('Changing shared hosting account domain');
        }
    }

    /**
     * Change account password on server
     * @param Server_Account $a
     * @param type $new - new password
     */
    public function changeAccountPassword(Server_Account $a, $new)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Changing reseller hosting account password');
        } else {
            $this->getLog()->info('Changing shared hosting account password');
        }
    }

    /**
     * Change account IP on server
     * @param Server_Account $a
     * @param type $new - account IP
     */
    public function changeAccountIp(Server_Account $a, $new)
    {
        if($a->getReseller()) {
            $this->getLog()->info('Changing reseller hosting account ip');
        } else {
            $this->getLog()->info('Changing shared hosting account ip');
        }
    }
}
