# PHP-Reloadr - Live reload PHP app while developing.

## Using

  $reloadr = new Reloadr(array(
              'root_path' => __DIR__, 
              'route' => 'domain.tld/reloadr',
              'dirs' => array(
                'public',
                'views',
                'config'
                ),
              'files' => array(
                'lang/en.php'
                ),
              'filter' => array(
                'accept' => array(
                  'css',
                  'js',
                  'html',
                  'php'
                  )
                )
              ));
  
  echo/return $reloader->init(); // returns js script. `echo` it into template OR from your `controller` [with `javascript` header]
  
  echo/return $reloadr->ajax(); // call it into the `route` controller

### Config

  'jq' => false, // set TRUE if jQuery is not added
  'freq' => 5000, // freq of AJAX call
  'root_path' => null, // root path/realpath
  'route' => '/reloadr', // route for ajax call
  'dirs' => array(), // recuresively check all files for reload
  'files' => array(), // files
  'filter' => array(
    'except' => null, // all files, except these array()
    'accept' => 'php','htm','css','js' // accept only
    ),
  'set_header' => true // set `application/javascript` and `application/json` header to ajax route 
  
  
