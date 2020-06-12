<?php 
    
    function querySelectSql($con,$sql){

        $list_result = mysqli_query($con, $sql);
        $list = [];
        while($item = mysqli_fetch_array($list_result)){
             $list[] = $item;
        }
        return $list;
    }
    
    function getTree($con,$pid,$level,&$list){

        $query = 'select * from folderlist where state=1 and pid='.$pid.' and userid=1 order by type,name';
        $items = querySelectSql($con,$query);
        foreach ($items as $key => $item) {
            $item['level'] = $level+1;
            if($item['type'] ==1)
            {
               $sublist = [];
               getTree($con,$item['id'],$item['level'],$sublist); 
               $item['list'] = $sublist;
            }
            $list[] = $item;
        }
        return;
    }

    function removeTree($con,$pid){
       
        $query = 'UPDATE folderlist SET state=2 where id='.$pid;
        $ret = mysqli_query($con,$query);
        if(!$ret)return false;

        $query = 'select * from folderlist where state=1 and pid='.$pid.' and userid=1';
        $items = querySelectSql($con,$query);
        
        foreach ($items as $key => $item) {
           if(!removeTree($con,$item['id']))return false;
        }
        return true;
    }

    function copyTree($con,$from,$to){

        $query = 'select * from folderlist where state=1 and userid=1 and id='.$from.' limit 1';
        $list_result = mysqli_query($con,$query);
        if(!($item = mysqli_fetch_array($list_result)))return false;
        $sql = sprintf("insert into folderlist (pid,name,type) value(%d,'%s',%d)"
                       ,$to,$item['name'],$item['type']);
        if(!mysqli_query($con,$sql)) return false;               
        $insertId = mysqli_insert_id($con);
        $query = 'select * from folderlist where state=1 and pid='.$from.' and userid=1';
        $items = querySelectSql($con,$query);
        foreach ($items as $key => $item) {
            if(!copyTree($con,$item['id'],$insertId)) return false;
        }
        return $insertId;
       
    }
    // create connection
    $host = "localhost";
    $user = "root";
    $passwd = "";
    $dbname = "cooldemo";
    $cxn = mysqli_connect($host,$user,$passwd,$dbname)
            or die(json_decode(["message"=>"couldn't connect to server","success"=>false]));
    $type = $_POST['type'];

    if($type == 'add-folder'){

        $data = $_POST['data'];  
        $pid = $data['pid'];
        $name = $data['name'];
        $type =  $data['type'];
        $userid = 1;  ///// we take userid as 1, so will use on mutil user support
        ///// Insert new Folder/List 
        $sql = sprintf("insert into folderlist (pid,name,type) value(%d,'%s',%d)",$pid,$name,$type);
        mysqli_query($cxn,$sql);
        $insertId = mysqli_insert_id($cxn);
        echo json_encode(['success'=>!!$insertId,'id'=>$insertId]);
        return;
    }
    else if($type == 'get-folder'){

        $list = [];
        getTree($cxn,0,0,$list);
        echo json_encode($list);
    }
    else if($type == 'change-name'){

        $id = $_POST['id'];
        $name = $_POST['name'];
        $sql = 'UPDATE folderlist SET name="'.$name.'" WHERE id='.$id;
        $success = mysqli_query($cxn,$sql);
        echo json_encode(['success'=>$success]);
    }
    else if($type == 'remove'){

        $id = $_POST['id'];
        $ret = removeTree($cxn,$id);
        echo json_encode(['success'=>$ret]);
    }
    else if($type == 'cut-paste'){

        $from = $_POST['from'];
        $to = $_POST['to'];
        $sql = 'UPDATE folderlist SET pid='.$to.' WHERE id='.$from;
        $success = mysqli_query($cxn,$sql);
        echo json_encode(['success'=>$success]);
    }
    else if($type == 'copy-paste'){

        $from = $_POST['from'];
        $to = $_POST['to'];
        $level = $_POST['level'];
        $insertId = copyTree($cxn,$from,$to);
        $list = [];
        getTree($cxn,$to,$level,$list);
        foreach ($list as $key => $item) {
            if($item['id']==$insertId)
              echo json_encode(['success'=>$insertId,'list'=>[$item]]);
        }
    }
    mysqli_close($cxn);
    
?>