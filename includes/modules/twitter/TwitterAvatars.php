<?php
class TwitterAvatars extends TwitterApp {
    
    /**
     * The path to our temporary files directory
     *
     * @var string Path to store image files
     */
    public $path;
    
    /**
     * These are all the GD image filters available in this class
     *
     * @var array Associative array of image filters
     */
    protected $filters = array(
        'grayscale'     => IMG_FILTER_GRAYSCALE,
        'negative'      => IMG_FILTER_NEGATE,
        'edgedetect'    => IMG_FILTER_EDGEDETECT,
        'embossed'      => IMG_FILTER_EMBOSS,
        'blurry'        => IMG_FILTER_GAUSSIAN_BLUR,
        'sketchy'       => IMG_FILTER_MEAN_REMOVAL
    );
    
    /**
     * Initialize a new TwitterAvatars object
     *
     * @param tmhOAuth $tmhOAuth A tmhOAuth object with consumer key and secret
     * @param string $path Path to store image files (default 'tmp')
     */
    public function  __construct(tmhOAuth $tmhOAuth, $path = 'tmp') {
        
        // call the parent class' constructor
        parent::__construct($tmhOAuth);

        // save the path variable
        $this->path = $path;
    }

    /**
     * Download data from specified URL
     *
     * @param string $url URL to download
     * @return string Downloaded data
     */
    protected function download($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $ret = curl_exec($ch);
        curl_close($ch);

        return $ret;
    }

    /**
     * Get the URL to the standard sized avatar
     *
     * @return string The URL to the image file
     */
    protected function getImageURL() {

        // request user's 'bigger' profile image
        $this->tmhOAuth->request('GET', $this->tmhOAuth->url('1/users/profile_image/' . $this->userdata->screen_name), array(
            'screen_name'   => $this->userdata->screen_name,
            'size'          => 'bigger'
        ));

        if($this->tmhOAuth->response['code'] == 302) {
            
            // the direct URL is in the Location header
            return $this->tmhOAuth->response['headers']['location'];
        }
        throw new Exception('Error locating image');
    }

    /**
     * Get the URL to the full sized avatar
     *
     * @return string The URL to the image file
     */
    protected function getOriginalImageURL() {

        // get the regular sized avatar
        $url = $this->userdata->profile_image_url;

        // save the extension for later
        $ext = strrchr($url, '.');

        // strip the '_normal' suffix and add back the extension
        return substr($url, 0, strrpos($url, '_')) . $ext;
    }

    /**
     * Convert raw image data to a GD resource
     *
     * @param string $data Binary image data to parse
     * @return resource A GD image resource identifier
     */
    protected function readImage($data) {

        // read in the original image
        $src = imagecreatefromstring($data);

        if(!$src) {
            throw new Exception('Error reading image');
        }

        // get the dimensions
        $width = imagesx($src);
        $height = imagesy($src);

        // create a blank true color image of the same size
        $img = imagecreatetruecolor($width, $height);

        // copy the original image to this new canvas
        imagecopy($img, $src, 0, 0, 0, 0, $width, $height);

        // discard the source image
        imagedestroy($src);

        return $img;
    }

    /**
     * Save a GD image resource to a PNG file
     *
     * @param resource $img GD image resource identifier
     * @param string $name Name of the image
     * @return string Path to the saved image
     */
    protected function saveImage($img, $name) {
        $path = $this->path . '/' . $name . '.png';
        imagepng($img, $path);
        imagedestroy($img);
        return $path;
    }

    /**
     * Generate previews for each image filter
     *
     * @return array Associative array of image previews
     */
    public function generatePreviews() {
        
        // we need valid user info to know whose avatar to handle
        if(!$this->isAuthed()) {
            throw new Exception('Requires oauth authorization');
        }
        $username = $this->userdata->screen_name;

        // cache the raw data to use
        $data = $this->download($this->getImageURL());

        // copy the original image
        $img = $this->readImage($data);
        $this->saveImage($img, $username . '_orig');
        
        // array to hold the list of previews
        $images = array();

        // loop through each filter to generate previews
        foreach($this->filters as $filter_name => $filter) {
            $img = $this->readImage($data);
            imagefilter($img, $filter);
            $images[$filter_name] = $this->saveImage($img, $username . '_' . $filter_name);
        }

        return $images;
    }

    /**
     * Get the path to a previously generated preview
     *
     * @param string $filter The image filter to get the preview for
     * @return string The path to the preview file or null if not found
     */
    public function getPreview($filter = 'orig') {
        if(!$this->isAuthed()) {
            throw new Exception('Requires oauth authorization');
        }
        $path = $this->path . '/' . $this->userdata->screen_name . '_' . $filter . '.png';
        if(file_exists($path)) {
            return $path;
        }
        return null;
    }

    /**
     * Process the user's full avatar using one of the filters
     *
     * @param string $filter The filter to apply to the image
     * @return string Path to the output file
     */
    protected function processImage($filter = 'grayscale') {
        
        // make sure the filter exists
        $filter = strtolower($filter);
        if(!array_key_exists($filter, $this->filters)) {
            throw new Exception('Unsupported image filter');
        }

        $username = $this->userdata->screen_name;

        // get the full sized avatar
        $data = $this->download($this->getOriginalImageURL());
        $img = $this->readImage($data);

        // apply the filter to the image
        imagefilter($img, $this->filters[$filter]);
        
        // save the image and return the path
        return $this->saveImage($img, $username . '_' . $filter . '_full');
    }

    /**
     * Update user's avatar with a filtered version
     *
     * @param string $filter The filter to use
     * @return bool Operation successful
     */
    public function commitAvatar($filter) {
        if(!$this->isAuthed()) {
            throw new Exception('Requires oauth authorization');
        }

        // generate the image and get the path
        $path = $this->processImage($filter);
        if(file_exists($path)) {

            // send a multipart POST request with the image file data
            $this->tmhOAuth->request('POST', $this->tmhOAuth->url('1/account/update_profile_image'), array(
                // format: @local/path.png;type=mime/type;filename=file_name.png
                'image' => '@' . $path . ';type=image/png;filename=' . basename($path)
            ), true, true);

            return ($this->tmhOAuth->response['code'] == 200);
        }

        return false;
    }

    /**
     * Delete leftover image files
     */
    public function cleanupFiles() {
        
        // file to track when we last checked
        $flag = $this->path . '/.last_check';

        $time = time();

        // have we checked within the last hour?
        if(!file_exists($flag) || $time - filemtime($flag) > 3600) {
            
            // get an array of PNG files in the directory
            $files = glob($this->path . '/*.png');

            // loop through files, deleting old files (12+ hours)
            foreach($files as $file) {
                if($time - filemtime($file) > 60*60*12) {
                    unlink($file);
                }
            }

            // update the timestamp of our flag file
            touch($flag);
        }
    }
}