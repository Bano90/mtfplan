<?php
 class Plan{
     function get($app,$params)
     {
         $db=new \DB\Jig('plans/',\DB\Jig::FORMAT_JSON);
         $mapper=new \DB\Jig\Mapper($db,'plans.json');
         $plans=$mapper->find(Array('@id=?',$params['id']));
         $resault=[];

         foreach($plans as $k=>$plan){
             foreach($plan as $a=>$b){
                 if($a!="_id" && $plan["id"]==$params["id"])
                 {
                     $resault[$a]=$b;
                 }
             }
         }

         $res=[];
         foreach($plans as $k=>$plan){
             if($plan["id"]==$params["id"]){
                 $r=[];
                 foreach($plan as $a=>$b){
                     if($a!="_id")
                     {
                         $r[$a]=$b;
                     }
                 }
                 array_push($res,$r);
             }
         }
         echo json_encode($res);
     }

     function post($app,$params)
     {
         $data=json_decode($app['BODY']);
         echo json_encode($data);
         $db=new \DB\Jig('plans',\DB\Jig::FORMAT_JSON);
         $mapper=new \DB\Jig\Mapper($db,'plans.json');

         $mapper->id=$data->id; //azonosító
         $mapper->date=$data->date; //dátum
         $mapper->projectname=$data->projectname; //projekt neve
         $mapper->type=$date->type; //típus
         $mapper->amountshift1=$data->amountshift1; // tervezett darab szám reggeles szak
         $mapper->amountshift2=$data->amountshift2; // tervezett darab szám délutános szak
         $mapper->amountshift3=$data->amountshift3; // tervezett darab szám éjszakás szak
         $mapper->description=$data->description; // részletes leírás
         $mapper->attempt=$data->attempt; //próbálkozás
         $mapper->whogo=$data->whogo; //ki megy
         $mapper->where=$data->where; //hova megy
         $mapper->period=$data->period; //mennyi ideig
         $mapper->person=$data->person; //hány főre van szükség
         $mapper->save();

         echo "OK";

         @unlink($data);
         @unlink($mapper);
         @unlink($db);
     }

     function put($app,$params)
     {
         $data=json_decode($app['BODY']);
         $db=new \DB\Jig('plans/',\DB\Jig::FORMAT_JSON);
         $mapper=new \DB\Jig\Mapper($db,'plans.json');
         $plan=$mapper->load(Array('@id=?',$params['id']));

         $plan->projectname=$data->projectname;
         $plan->type=$data->type;
         $plan->amountshift1=$data->amountshift1;
         $plan->amountshift2=$data->amountshift2;
         $plan->amountshift3=$data->amountshift3;
         $plan->description=$data->description;
         $plan->attempt=$data->attempt;
         $plan->whogo=$data->whogo;
         $plan->where=$data->where;
         $plan->period=$data->period;
         $plan->person=$data->person;
         $plan->save();

         echo "OK";

         @unlink($data);
         @unlink($mapper);
         @unlink($db);
         @unlink($plan);
     }

     function delete($app,$params)
     {
         $db=new \DB\Jig('plans/',\DB\Jig::FORMAT_JSON);
         $mapper=new \DB\Jig\Mapper($db,'plans.json');
         $plan=$mapper->find(Array('@id=?',$params['id']));
         $plan[0]->erase();

         echo "OK";

         @unlink($mapper);
         @unlink($db);
         @unlink($plan);
     }

     $app=require('../f3lib/base.php');
     $app->map('/plan/@id','Plan');

     $app->route('GET /plan/@id/@date',function($app,$params){
         $db=new \DB\Jig('plans/',\DB\Jig::FORMAT_JSON);
         $mapper=new \DB\Jig\Mapper($db,'plans.json');
         $plans=$mapper->find(Array('@id=? and @date=?',$params['id'],$params['date']));
          $res=[];
          foreach($plans as $k=>$plan){
              if($plan["id"]==$params["id"]){
                  $r=[];
                  foreach($plan as $a=>$b){
                      if($a!="_id")
                      {
                          $r[$a]=$b;
                      }
                  }
                  array_push($res,$r);
              }
          }
     });

     $app->route('GET/allplans',function($app){
         $data=file_get_contents('plans/plans.json');
         $data=json_decode($data);

         $resault=[];
         foreach($data as $k->$v){
             array_push($resault,$v);
         }
         echo json_encode($resault);
     });
     $app->run();
 }
?>