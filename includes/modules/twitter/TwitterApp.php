<?php
class TwitterApp {
    
    /**
     * This variable holds the tmhOAuth object used throughout the class
     *
     * @var tmhOAuth An object of the tmhOAuth class
     */
    public $tmhOAuth;

    /**
     * User's Twitter account data
     *
     * @var array Information on the current authenticated user
     */
    public $userdata;

    /**
     * Authentication state
     *
     * Values:
     *  - 0: not authed
     *  - 1: Request token obtained
     *  - 2: Access token obtained (authed)
     *
     * @var int The current state of authentication
     */
    protected $state;

    /**
     * Initialize a new TwitterApp object
     *
     * @param tmhOAuth $tmhOAuth A tmhOAuth object with consumer key and secret
     */
    public function  __construct(tmhOAuth $tmhOAuth) {
        
        // save the tmhOAuth object
        $this->tmhOAuth = $tmhOAuth;

        // start a session if one does not exist
        if(!session_id()) {
            session_start();
        }
        
        // determine the authentication status
        // default to 0
        $this->state = 0;
        // 2 (authenticated) if the cookies are set
        if(isset($_COOKIE['access_token'], $_COOKIE['access_token_secret'])) {
            $this->state = 2;
        }
        // otherwise use value stored in session
        elseif(isset($_SESSION['authstate'])) {
            $this->state = (int)$_SESSION['authstate'];
        }
        
        // if we are in the process of authentication we continue
        if($this->state == 1) {
            $this->auth();
        }
        // verify authentication, clearing cookies if it fails
        elseif($this->state == 2 && !$this->auth()) {
        	print_r("end_session");
            $this->endSession();
        }
    }

    /**
     * Authenticate user with Twitter
     *
     * @return bool Authentication successful
     */
    public function auth() {
        // state 1 requires a GET variable to exist
        if($this->state == 1 && !isset($_GET['oauth_verifier'])) {
            $this->state = 0;
        }

        // Step 1: Get a request token
        if($this->state == 0) {
            return $this->getRequestToken();
        }
        // Step 2: Get an access token
        elseif($this->state == 1) {
            return $this->getAccessToken();
        }

        // Step 3: Verify the access token
        return $this->verifyAccessToken();
    }

    /**
     * Obtain a request token from Twitter
     *
     * @return bool False if request failed
     */
    private function getRequestToken() {
 
        // send request for a request token
        $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/request_token', ''), array(
            // pass a variable to set the callback
            'oauth_callback'    => $this->tmhOAuth->php_self()
        ));

        if($this->tmhOAuth->response['code'] == 200) {
            
            // get and store the request token
            $response = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
            $_SESSION['authtoken'] = $response['oauth_token'];
            $_SESSION['authsecret'] = $response['oauth_token_secret'];

            // state is now 1
            $_SESSION['authstate'] = 1;

            // redirect the user to Twitter to authorize
            $url = $this->tmhOAuth->url('oauth/authorize', '') . '?oauth_token=' . $response['oauth_token'];
            header('Location: ' . $url);
            exit;
        }
        return false;
    }

    /**
     * Obtain an access token from Twitter
     *
     * @return bool False if request failed
     */
    private function getAccessToken() {
        
        // set the request token and secret we have stored
        $this->tmhOAuth->config['user_token'] = $_SESSION['authtoken'];
        $this->tmhOAuth->config['user_secret'] = $_SESSION['authsecret'];

		
        // send request for an access token
        $this->tmhOAuth->request('POST', $this->tmhOAuth->url('oauth/access_token', ''), array(
            // pass the oauth_verifier received from Twitter
            'oauth_verifier'    => $_GET['oauth_verifier']
        ));

        if($this->tmhOAuth->response['code'] == 200) {

            // get the access token and store it in a cookie
            $response = $this->tmhOAuth->extract_params($this->tmhOAuth->response['response']);
            setcookie('access_token', $response['oauth_token'], time()+3600*24*30);
            setcookie('access_token_secret', $response['oauth_token_secret'], time()+3600*24*30);

            // state is now 2
            $_SESSION['authstate'] = 2;

            // redirect user to clear leftover GET variables
            header('Location: ' . $this->tmhOAuth->php_self());
            exit;
        }
        return false;
    }

    /**
     * Verify the validity of our access token
     *
     * @return bool Access token verified
     */
    private function verifyAccessToken() {
        $this->tmhOAuth->config['user_token'] = isset($_COOKIE['access_token'])? $_COOKIE['access_token'] : $_SESSION['access_token'];
        $this->tmhOAuth->config['user_secret'] = isset($_COOKIE['access_token_secret'])?$_COOKIE['access_token_secret']:$_SESSION['access_token_secret'];
        // send verification request to test access key
        $this->tmhOAuth->request('GET', $this->tmhOAuth->url('1/account/verify_credentials'));

        // store the user data returned from the API
        $this->userdata = json_decode($this->tmhOAuth->response['response']);

        // HTTP 200 means we were successful
        return ($this->tmhOAuth->response['code'] == 200);
    }

    /**
     * Check the current state of authentication
     *
     * @return bool True if state is 2 (authenticated)
     */
    public function isAuthed() {
        return $this->state == 2;
    }

    /**
     * Remove user's access token cookies
     */
    public function endSession() {
        $this->state = 0;
        $_SESSION['authstate'] = 0;
        setcookie('access_token', '', 0);
        setcookie('access_token_secret', '', 0);
    }
    
    /**
     * Send a tweet on the user's behalf
     *
     * @param string $text Text to tweet
     * @return bool Tweet successfully sent
     */
    public function sendTweet($text) {

        // limit the string to 140 characters
        $text = substr($text, 0, 140);

        // POST the text to the statuses/update method
        $this->tmhOAuth->request('POST', $this->tmhOAuth->url('1/statuses/update'), array(
            'status' => $text
        ));
        
        return ($this->tmhOAuth->response['code'] == 200);
    }
    
    
    
    /**
     * Send a tweet on the user's behalf
     *
     * @param string $text Text to tweet
     * @return bool Tweet successfully sent
     */
    public function retrieveTweets() {

        // POST the text to the statuses/update method
        $this->tmhOAuth->request('GET', $this->tmhOAuth->url('1/statuses/user_timeline'));
        
        return  json_decode($this->tmhOAuth->response['response']);
    }    
}