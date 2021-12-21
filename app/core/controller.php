<?php

// Class Controller
// {
//     public function view($path,$data = [])
//     {
//         if(file_exists("../app/views/" . $path . ".php"))
//         {
//             include "../app/views/" . $path . ".php";
//         } 
//         else 
//         {
//             include "../app/views/404.php";
//         }
//     }
// }


class Controller {  
     public $model;   
     
     public function __construct()    
     {    
          //  $this->model = new Model();  
     }   
          
     public function invoke()  
     {  
          if (isset($_GET['register']))  
          {   
               include './app/views/register.php'; 
          } 
          elseif (isset($_GET['forgot']))
          {
               include './app/views/forgot.php'; 
          }
          elseif (isset($_GET['verify']))
          {
               include './app/views/verify.php'; 
          }
          elseif (isset($_GET['changePassword']))
          {
               include './app/views/changePassword.php'; 
          }
          elseif (isset($_GET['welcome']))
          {
               include './app/views/welcome.php'; 
          }
          else
          { 
               // show the requested book 
               //   $book = $this->model->getBook($_GET['register']); 
               include './app/views/login.php';  
          }  
     }  
}  