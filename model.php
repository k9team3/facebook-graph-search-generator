<?php

/**
 * Class FbGSG
 * - "Facebook Graph Search Generator"
 * @author Tormund Gerhardsen <tormund@metis.no>
 */
class FbGSG {

    var $fb_root_url = 'https://www.facebook.com/';
    var $fb_get_uid_api_root_url = 'http://findmyfbid.com/';


    /**
     * @param $friendlyname
     * @param $fb_username_or_uid
     * @return array
     */
    function add_fb_item ($friendlyname, $fb_username_or_uid) {

        // Get UID of this is username
        if(!is_int($fb_username_or_uid)) {
            $fb_uid = $this->get_fb_uid($fb_username_or_uid);
        } else {
            $fb_uid = $fb_username_or_uid;
        }

        // TODO: Add to cookie

        // Return as JSON element
        return array(
            'friendlyname' => $friendlyname,
            'fb_uid' => $fb_uid,
        );
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
            $api_uid = $element->nodeValue;
        }

        // Return
        return $api_uid;
    }
}