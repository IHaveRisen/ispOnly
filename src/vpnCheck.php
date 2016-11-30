<?php
class initialiser {
  /*
  * @var array contains isp's
  */
  protected $vpn = array('opengw', 'midphase.com', 'ipvanish', 'ovh', 'pointtoserver', 'privateinternetaccess', 'blazingfast', 'cyberghost');
  /*
  * @var string url for redirection
  */
  protected $redir;
  /*
  * @var string remote client domain
  */
  protected $domain;
  /*
  * Constructor
  * sets client variables and calls main
  */
  public function __construct()
  {
    if(isset($_SERVER['HTTP_CF_CONNECTING_IP']))
    {
      $this->domain = gethostbyaddr($_SERVER['HTTP_CF_CONNECTING_IP']); // Configured client address for cloudflare
    } else {
      $this->domain = gethostbyaddr($_SERVER['REMOTE_ADDR']); // Configured client address raw
    }
    $config = json_decode(file_get_contents('config.json')); 
    $this->redir = $config->{'redirurl'};
    $this->main();
  }
  /*
  * allows strpos to act within an array to detect vpn hostnames
  * @param array haystack
  * @param string needle
  */
  public function astrpos($haystack, $needle)
  {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $x)
    {
      if(strpos($haystack, $x) !== false) return true;
    }
    return false;
  }
  /*
  * Check if the hostname corresponds with the protected array
  * @returns boolean
  */
  public function isVPN()
  {
    if($this->astrpos($this->domain, $this->vpn) === false)
    {
      return false;
    } else {
      return true;
    }
  }
  /*
  * Main function that calls all returning objects
  */
  public function main()
  {
    if($this->isVPN() === true)
    {
      header("Location: " . $this->redir);
    }
  }
}
new initialiser();
 ?>
