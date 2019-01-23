<?php 

namespace Tornado;
use OAuth2\Storage\UserCredentialsInterface as UserCredentialsInterface;
/**
 * 	
 */
 class UserCredentials implements UserCredentialsInterface
 {
 	
 	public function checkUserCredentials($username, $password){
 		global $spot;
		$usersMapper = $spot->mapper("Entity\Users");
		$user = $usersMapper->where(["usuario" => $username]);
		if( $user->first() ){
			return $user->first()->password === md5($password)?true:false;	
		}else{
			return false;
		}
 	}
 	/**
     * @return
     * ARRAY the associated "user_id" and optional "scope" values
     * This function MUST return FALSE if the requested user does not exist or is
     * invalid. "scope" is a space-separated list of restricted scopes.
     * @code
     * return array(
     *     "user_id"  => USER_ID,    // REQUIRED user_id to be stored with the authorization code or access token
     *     "scope"    => SCOPE       // OPTIONAL space-separated list of restricted scopes
     * );
     * @endcode
     */
    public function getUserDetails($username){
    	global $spot;
		$usersMapper = $spot->mapper("Entity\Users");
    	$user = $usersMapper->where(["usuario" => $username])->first();
    	return array(
          "user_id"  => $user->usuario,    // REQUIRED user_id to be stored with the authorization code or access token
          "scope"    => "ALL"      // OPTIONAL space-separated list of restricted scopes
     	);
    }
 	
 } 



 ?>