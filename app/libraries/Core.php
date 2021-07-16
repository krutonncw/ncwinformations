<?php
  /*
   * App Core Class
   * Creates URL & loads core controller
   * URL FORMAT - /controller/method/params
   */
  class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct(){
      //print_r($this->getUrl());

      $url = $this->getUrl();

      // Look in BLL for first value
      // ucwords => Convert the first character of each word to uppercase:
      if(file_exists('../app/controllers/' . ucwords($url[0]). '.php')){
        // If exists, set as controller
        $this->currentController = ucwords($url[0]);
        // Unset 0 Index
        unset($url[0]);
      }

      // Require the controller
      require_once '../app/controllers/'. $this->currentController . '.php';

      // Instantiate controller class
      $this->currentController = new $this->currentController;

      // Check for second part of url
      if(isset($url[1])){
        // Check to see if method exists in controller
        if(method_exists($this->currentController, $url[1])){
          $this->currentMethod = $url[1];
          // Unset 1 index
          unset($url[1]);
        }
      }

      // Get params
      // array_values => Return all the values of an array (not the keys):
      $this->params = $url ? array_values($url) : [];

      // Call a callback with array of params
      // เรียกกลับไปที่ instance ของ object ที่สร้างไว้ปัจจุบัน โดยส่ง method และ paramiter ไปด้วย
      // โดยฟังก์ชั่น call_user_func_array สามารถเรียนกลับเป็น ฟังก์ชั้น หรือ object ก็ได้
      call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    public function getUrl(){
      if(isset($_GET['url'])){
        // Remove characters from the right side of a string:
        $url = rtrim($_GET['url'], '/');
        // Filters a variable with a specified filter
        // FILTER_SANITIZE_URL =>Remove all characters except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=.
        $url = filter_var($url, FILTER_SANITIZE_URL);
        // The explode() function breaks a string into an array. ดึงข้อความระหว่างเครื่องหมาย / เป็น array
        $url = explode('/', $url);
        return $url;
      }
    }
  }


