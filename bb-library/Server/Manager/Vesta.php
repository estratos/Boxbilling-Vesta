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

    	
    	
// Server credentials


$params['user'] = $this->_config['username'];
$params['password'] = $this->_config['password'];
   	
    	
// Send POST query via cURL
$postdata = http_build_query($params);
$curl = curl_init();
$timeout = 5;

curl_setopt($curl, CURLOPT_URL, $host);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $timeout);

$result = curl_exec($curl);

curl_close($curl);
		
		if($result == 0)
			return true;
		else
			return false;


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
$vst_command = 'v-list-sys-info';
$vst_returncode = 'yes';


// Prepare POST query
$postvars = array(
    
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => '',
    'arg2' => '',
    'arg3' =>'',
    'arg4' =>'',
    'arg5' =>'',
    'arg6' =>'',
    'arg7' =>'',
    'arg8' =>'',
    'arg9' =>''		

);

    
// Make request and check sys info

		return $this->_makeRequest($postvars);


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
$vst_command = 'v-add-user';
$vst_returncode = 'yes';

// New Account
$package = 'default';
$fist_name = 'Rust';
$last_name = 'Cohle';

// Prepare POST query
$postvars = array(
    
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => $a->getUsername(),
    'arg2' => $a->getPassword(),
    'arg3' => $client->getEmail(),
    'arg4' => $package,
    'arg5' => $a->getUsername(),
    'arg6' => $a->getUsername(),
    'arg7' =>'',
    'arg8' =>'',
    'arg9' =>''


						

);    
// Make request and create user 

		$result = $this->_makeRequest($postvars);

		

// Create Domain Prepare POST query
$postvars = array(
    

    'returncode' => 'yes',
    'cmd' => 'v-add-domain',
    'arg1' => $a->getUsername(),
    'arg2' => $a->getDomain(),
    'arg3' =>'',
    'arg4' =>'',
    'arg5' =>'',
    'arg6' =>'',
    'arg7' =>'',
    'arg8' =>'',
    'arg9' =>''

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



// Prepare POST query
$postvars = array(    
    'returncode' => 'yes',
    'cmd' => 'v-suspend-user',
    'arg1' => $a->getUsername(),
    'arg2' => 'no',
    'arg3' =>'',
    'arg4' =>'',
    'arg5' =>'',
    'arg6' =>'',
    'arg7' =>'',
    'arg8' =>'',
    'arg9' =>''
    


);    
// Make request and suspend user 


return $this->_makeRequest($postvars);

        
	}





    /**
     * Unsuspend account on server
     * @param Server_Account $a 
     */
	public function unsuspendAccount(Server_Account $a)
    {


           
        // Server credentials
$vst_command = 'v-unsuspend-user';
$vst_returncode = 'yes';


// Prepare POST query
$postvars = array(
    
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => $a->getUsername(),
    'arg2' => 'no',
    'arg3' =>'',
    'arg4' =>'',
    'arg5' =>'',
    'arg6' =>'',
    'arg7' =>'',
    'arg8' =>'',
    'arg9' =>''
		
);  
  
// Make request and unsuspend user 
// Boxbilling trowing error 505 on this particular action 
// So is not working anymore on ver 3.6.11
// Will try  a work arround later

  		
   


		return $this->_makeRequest($postvars);



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
    
    'returncode' => $vst_returncode,
    'cmd' => $vst_command,
    'arg1' => $a->getUsername(),
    'arg2' => '',
    'arg3' =>'',
    'arg4' =>'',
    'arg5' =>'',
    'arg6' =>'',
    'arg7' =>'',
    'arg8' =>'',
    'arg9' =>''


						

);    
// Make request and delete user 

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
