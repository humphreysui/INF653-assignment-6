<!-- 
1. require the models
2. get required data from the models - makes, types, classes 
3. get parameter data sent to the controller from forms 
4. get the data for the view - the precedence of make, type, and class - the 3 drop menus to make selections from. - you could do if make / else if type / else if class / else return all
5. show the view
-->

<?php 
  // Start session management 
  $lifetime = 60 * 60 * 24 * 14; // 2 weeks in seconds 
  session_set_cookie_params($lifetime, '/'); 
  session_start();


  require('model/database.php');
  require('model/vehicle_db.php');
  require('model/make_db.php');
  require('model/type_db.php');
  require('model/class_db.php');
  
  $makeID = filter_input(INPUT_POST, 'makeID', FILTER_VALIDATE_INT);
  if(!$makeID){
    $makeID = filter_input(INPUT_GET, 'makeID', FILTER_VALIDATE_INT);
  }
  $typeID = filter_input(INPUT_POST, 'typeID', FILTER_VALIDATE_INT);
  if(!$typeID){
    $typeID = filter_input(INPUT_GET, 'typeID', FILTER_VALIDATE_INT);
  }
  $classID = filter_input(INPUT_POST, 'classID', FILTER_VALIDATE_INT);
  if(!$classID){
    $classID = filter_input(INPUT_GET, 'classID', FILTER_VALIDATE_INT);
  }

  $sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING);

  $firstname = filter_input(INPUT_GET, 'firstname', FILTER_SANITIZE_STRING);
  if(!isset($firstname)){
    $firstname = false;
  }else{
   $_SESSION['userid'] = $firstname;
  }

  $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
  if(!$action){
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
    if(!$action){
      $action = 'list_vehicles';
    }
  } 

  switch ($action){

    case "list_vehicles":
      $makes = get_makes();
      $types = get_types();
      $classes = get_classes();
        
    /* 
      if($makeID){
        $vehicles = get_vehicles_by_make($makeID, $sort);
      }else if($typeID){
        $vehicles = get_vehicles_by_type($typeID, $sort);
      }else if($classID){
        $vehicles = get_vehicles_by_class($classID, $sort);
      }else{
        $vehicles = get_vehicles($sort);
      } 
    */

      $vehicles = get_vehicles_filtered($makeID, $typeID, $classID, $sort);

      include('view/vehicle_list.php');
      break;
    
    case "register":
      include('view/register.php');
      break;
    
    case "logout":
      unset($_SESSION['userid']); 
      header('Location: .?action=list_vehicles');
      break;

    default:
      $makes = get_makes();
      $types = get_types();
      $classes = get_classes();
      $vehicles = get_vehicles($sort);
      include('view/vehicle_list.php'); 
       
  }