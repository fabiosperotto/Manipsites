<?php
namespace SiteManipulation;
include_once 'SimpleHTMLDOM/simple_html_dom.php';

/**
 * This class uses the PHP Simple HTML DOM Parser to provide an even more easy
 * access on manipulate structures of websites.
 * @author fabio sperotto <fabio.aiub@gmail.com>
 * @license MIT license
 *        
 */
class ManipulationWebSite
{
    protected $urlToRetrieve;
    public $objHtml;
    protected $proxyAddress; //proxy address to solve some unauthorized errors
    protected $proxyPort;
    protected $proxyUsername; //if the proxy require username
    protected $proxyPasswd; //if the proxy require password
    
    
    public function getUrl()
    {
        return $this->urlToRetrieve;    
    }
    
    public function setUrl($url)
    {
        $this->urlToRetrieve = $url;
    }
    
    public function getProxyAddress()
    {
        return $this->proxyAddress; 
    }
    
    public function setProxyAddress($address)
    {
        $this->proxyAddress = $address;
    }
    
    public function getProxyPort()
    {
        return $this->proxyPort;
    }
    
    public function setProxyPort($port)
    {
        $this->proxyPort = $port;
    }
    
    public function getProxyUsername()
    {
    	return $this->proxyUsername;
    }
    
    public function setProxyUsername($username)
    {
    	$this->proxyUsername = $username;
    }
    
    public function getProxyPasswd()
    {
    	return $this->proxyPasswd;	
    }
    
    public function setProxyPasswd($passwd)
    {
    	$this->proxyPasswd = $passwd;
    }

    /**
     * Construct of the class with instance of simple_html_dom class which is a XHTML parser. 
     * @param string $url with site URL to access
     */
    public function __construct($url)
    {
        $this->urlToRetrieve = $url;
    	$this->objHtml = new \simple_html_dom();
    	  
    }
    
    /**
     * Load the targeted page. The parser will connect to page and load it. Automatically inserts the proxy configuration.
     */
    public function connect()
    {        
        // Create DOM from URL or file
        
        if($this->proxyAddress == '') $this->objHtml->load_file($this->urlToRetrieve);
        if(!empty($this->proxyAddress)){
        	
        	$curl = curl_init();
        	curl_setopt($curl, CURLOPT_URL,$this->urlToRetrieve);
        	curl_setopt($curl, CURLOPT_PROXY, $this->proxyAddress.':'.$this->proxyPort); 
        	
        	if(!empty($this->proxyUsername)){ //if proxy require username:passwd        		
        		$auth = $this->proxyUsername.':'.$this->proxyPasswd;
        		curl_setopt($curl, CURLOPT_PROXYUSERPWD, $auth);
        	}
        	
        	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        	curl_setopt($curl, CURLOPT_HEADER, 1);
        	$website = curl_exec($curl);
        	curl_close($curl);
        	$this->objHtml->load($website);
        }
    }
    
    /**
     * List of XHTML parser object used in this class.
     */
    public function listHtmlParserMethods()
    {
        $api = new \ReflectionClass('simple_html_dom');
        foreach($api->getMethods() as $method)
        {
            echo $method->getName() . '<br />';
        }
    }
    
    /**
     * List of XHTML parser object properties.
     */
    public function listHtmlParserProperties()
    {
        $api = new \ReflectionClass('simple_html_dom');
        foreach($api->getProperties() as $properties)
        {
            echo $properties->getName() . '<br />';
        }
    }
    
    /**
     * This method provide a way to get the N anchor of the page.
     * @param int $linkNumber the N anchor of the website.
     * @param boolean $useURL if true, add the current URL inputted in object constructor.
     * @return string with the n anchor of the page or null if the page do not have a anchor
     */
    public function getAnAnchor($linkNumber,$useURL = false)
    {
        $this->connect();
        if(!$useURL){
            $hyperlink = $this->objHtml->find('a',$linkNumber);
            $link = $hyperlink->href;
        }
        if($useURL){            
            $link = $this->urlToRetrieve;
            $hyperlink = $this->objHtml->find('a',$linkNumber);
            $link .= $hyperlink->href;
        }
        return $link;
    }

    /**
     * Method to get the last anchor of website.
     * @param boolean $useURL if true, add the current URL inputted in object constructor.
     * @return string with the last anchor of the page or null if the page do not have a anchor.
     */    
    public function getLastAnchor($useURL = false)
    {
        $link = $this->getAnAnchor(-1,$useURL);       
        if($link == null) return null;
        if($link != null) return $link;
    }
    
    /**
     * Method to get the first anchor of website.
     * @param boolean $useURL if true, add the current URL inputted in object constructor.
     * @return string with the first anchor of the page or null if the page do not have a anchor.
     */
    public function getFirstAnchor($useURL = false)
    {
        $link = $this->getAnAnchor(0,$useURL);       
        if($link == null) return null;
        if($link != null) return $link;              
    }
    
    /**
     * Gets all anchors (hyperlinks) of page.
     * @param boolean $useURL if true, add the current URL inputted in object constructor.
     * @return array with all links of the page or null if nothing found.
     */
    public function getAllLinks($useURL = false)
    {
        $links = array();
        $this->connect();
        foreach($this->objHtml->find('a') as $element)
        {
            if($useURL) array_push($links, $this->urlToRetrieve.$element->href);
            if(!$useURL) array_push($links, $element->href);
                        
        }
        if(count($links) <= 0) return null;
        if(count($links) > 0) return $links;
    }
    
    /**
     * Gets all the images of page.
     * @return array with all images of the page or null if nothing found.
     */
    public function getAllImages()
    {
        $images = array();
        $this->connect();
        foreach($this->objHtml->find('img') as $element){            
            array_push($images,$this->urlToRetrieve.$element->src);
        }
        if(count($images) <= 0) return null;
        if(count($images) > 0) return $images;
    }
    
    /**
     * Gets a content page in array. Line by line (each line in an array position) if the page have only text, for example. 
     * @param string $url with suffix for add in the initial url (urlToRetrieve).
     * @return array with the content page or null if nothing found.
     */
    public function getContentToArray($url = null)
    {
        $lines = array();
        $this->connect();
        
        if($url == null) $lines = file($this->urlToRetrieve);
        if($url != null) $lines = file($this->urlToRetrieve.$url);
        
        if(count($lines) <= 0) return null;
        if(count($lines) > 0) return $lines;       
    }
        
    /**
     * Gets a row in content page.
     * @param int $numberLine which line to retrieve.
     * @return the required line or null if nothing found
     */
    public function getRow($numberLine)
    {
        $content = $this->getContentToArray();
        
        if(empty($content)) return null;               
        
        if(!empty($content)){
            
            if($numberLine > count($content)) return null;
            //var_dump($content);
            $line = $content[$numberLine];
            echo $line;
            
        }
    }
    
    /**
     * 
     * This shows, line by line, all HTML of the page. (not generate the page, for this use another method).
     * @param boolean $showNumberLines true to show numbered lines, false to not show
     * @param string $url with suffix for add in the initial url (urlToRetrieve).
     */
    public function showPageLines($showNumberLines = false, $url = false)
    {
        $this->connect();
        if(!$url) $lines = file($this->urlToRetrieve);
        if($url) $lines = file($this->urlToRetrieve.$url);
        
        
        foreach($lines as $line_num => $line) {
            
            if($showNumberLines) echo '#'.$line_num.': ';
            echo htmlspecialchars($line).'<br />';            
        }
    }
    
    /**
     * Connect to page and takes the entire target page, cURL power!
     * @param string $url with suffix for add in the initial url (urlToRetrieve).
     */
    public function getContentPage($url = null)
    {
        $this->connect();
        if($url == null) $lines = file($this->urlToRetrieve);
        if($url != null) $lines = file($this->urlToRetrieve.$url);

        foreach($lines as $line){            
            echo $line;            
        }
    }
    
	/**
	 * Get the web page without HTML tags.
	 * @return string with the content.
	 */
    public function getOnlyText()
    {
    	$this->connect();
    	return $this->objHtml->plaintext;
    }
}