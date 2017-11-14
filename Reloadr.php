<?php

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class Reloadr
{
  protected $config = array(
    'jq' => false,
    'key' => '',
    'freq' => 5000,
    'root_path' => null,
    'route' => '/reloadr',
    'dirs' => array(),
    'files' => array(),
    'filter' => array(
      'except' => null,
      'accept' => 'php','htm','css','js'
      ),
    'set_header' => true,
    ),
   $jquery = 'https://code.jquery.com/latest.min.js',
   $script = 'window.reloadr=function(n){var r,t=[];return{start:function(e){r=setInterval(function(){n.ajax({url:e.url,method:"GET",dataType:"json",success:function(n){if(t.length<=0)t=n;else for(var r in n)n[r]>t[r]&&location.reload()}})},e.freq)}}}($);',
   $list = array();

  public function __construct(array $config = [])
  {
    $this->config = array_merge($config, $this->config);
  }

  public function init($jq = null)
  {
    if($jq || null !== $jq){$this->config['jq'] = $jq;}

    return $this->getScript($jq);
  }

  public function ajax()
  {
    if(!empty($dirs = $this->config['dirs'])){
      foreach($dirs as $dir){
        $dir = $this->config['root_path'].$dir;
        if(file_exists($dir)){
          foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS)) as $file){
            $this->filterExt($file);
          }
        }
      }
    }

    if(!empty($files = $this->config['files'])){
      foreach($files as $file){
        $file = $this->config['root_path'].$file;
        if($file_exists($file)){
          $this->filterExt($file);
        }
      }
    }

    if($this->config['set_header']){
      header("Content-type: application/json; charset=utf-8");
    }

		echo json_encode($this->list);
  }

  public function getScript($jq = false)
  {
    $script = str_ireplace('e.freq', $this->config['freq'], '<script type="text/javascript">'.$this->script.'</script>');
    $script = str_ireplace('e.url', $this->config['route'], $script);
    return ($jq||$this->config['jq']?'<script src="'.$this->jquery.'" type="text/javascript></script>"':'').$script;
  }

  protected function filterExt($file)
  {
    $except = (null !== $this->config['filter']['except'] ? $this->config['filter']['except'] : []);
    $accept = (null !== $this->config['filter']['accept'] ? $this->config['filter']['accept'] : []);

    if(!empty($except)){
      foreach($except as $ext){
        if(pathinfo($file, PATHINFO_EXTENSION) !== strtolower($ext)){
          $this->list[] = filemtime($file);
        }
      }
    }

    if(!empty($accept)){
      foreach($accept as $ext){
        if(pathinfo($file, PATHINFO_EXTENSION) === strtolower($ext)){
          $this->list[] = filemtime($file);
        }
      }
    }
  }
}
