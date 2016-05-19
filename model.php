<?php

/**
 * Class FbGSG
 * - "Facebook Graph Search Generator"
 * @author Tormund Gerhardsen <tormund.gerhardsen@gmail.com>
 */
class FbGSG {

    var $fb_root_url = 'https://www.facebook.com/';
    var $fb_get_uid_api_root_url = 'http://findmyfbid.com/';
    var $cookie_list_name = 'FbGSG_list';


    /**
     * @param $friendlyname
     * @param $fb_username_or_uid
     * @return array
     */
    function add_fb_item ($friendlyname, $fb_username_or_uid) {

        // Get UID of this is username
        if(!is_numeric($fb_username_or_uid)) {
            $fb_uid = $this->get_fb_uid($fb_username_or_uid);
        } else {
            $fb_uid = $fb_username_or_uid;
        }

        // Friendly name is optional, so use username if not set
        if(empty($friendlyname)) {
            $friendlyname = $fb_username_or_uid;
        }

        // So, did we end up with a number here?
        if(is_numeric($fb_uid)) {
            // Add to cookie
            $this->add_to_cookie($friendlyname, $fb_uid);

            // Return as JSON element
            return array(
                'friendlyname' => $friendlyname,
                'fb_uid' => $fb_uid,
            );
        } else {
            return array(
                'friendlyname' => 'Could not find UID',
                'fb_uid' => 0,
            );
        }
    }


    /**
     * Getting the Facebook User ID form a unique username
     * @param string $fb_username
     * @return int
     */
    function get_fb_uid ($fb_username) {

        $api_uid = 0;
        $url = $this->fb_get_uid_api_root_url;
        $post_url = $this->fb_root_url.$fb_username;

        // Load from API with POST
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'url='.$post_url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $api_result = curl_exec($curl);
        curl_close($curl);

        // Parse result
        $api_dom = new DOMDocument();
        $api_dom->loadHTML($api_result);

        // Get <code> element
        $api_code_elements = $api_dom->getElementsByTagName('code');
        foreach ($api_code_elements as $element) {
            $api_uid = intval($element->nodeValue);
        }

        // Return
        return $api_uid;
    }


    /**
     * Add item to cookie
     * @param string $friendlyname
     * @param int $fb_uid
     * @return int
     */
    function add_to_cookie ($friendlyname, $fb_uid) {

        // Check if cookie exist, and add data
        if(!$cookie_data = $this->get_cookie_data()) {
            $cookie_data = array();
        }
        $cookie_data[$fb_uid] = $friendlyname;

        setcookie(
            $this->cookie_list_name,
            json_encode($cookie_data),
            time() + 604800 /* Expire in 1 week */
        );

        return true;
    }


    /**
     * Remove item from cookie
     * @param int $fb_uid
     * @return bool
     */
    function remove_from_cookie ($fb_uid) {

        // Remove item from cookie if exist
        if($cookie_data = $this->get_cookie_data()) {
            if(isset($cookie_data[$fb_uid])) {
                unset($cookie_data[$fb_uid]);
            }
        } else $cookie_data = array();

        // So, empty?
        if(empty($cookie_data)) {
            $cookie_time = -3600; /* Delete cookie if now empty */
        } else {
            $cookie_time = time() + 604800; /* Expire in 1 week */
        }

        setcookie(
            $this->cookie_list_name,
            json_encode($cookie_data),
            $cookie_time
        );

        return true;
    }


    /**
     * Getting cookie data
     * @return bool|array - Cookie data as array, if exist. Else false.
     */
    function get_cookie_data () {

        if(isset($_COOKIE[$this->cookie_list_name])) {

            $cookie_data = stripslashes($_COOKIE[$this->cookie_list_name]);
            return json_decode($cookie_data, 1);

        } else return false;
    }
}