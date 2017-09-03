<?php date_default_timezone_set('UTC'); ?>
<?php require_once __DIR__ . '/php-graph-sdk-5.0.0/src/Facebook/autoload.php'; ?>
<?php $temp=''; $temp2=''; $temp3=''; ?>
<?php
        $fb = new Facebook\Facebook([
                'app_id'=>'xxxxxxxxxxxxxxxxxxxxx',
                'app_secret'=>'xxxxxxxxxxxxxxxxxxxxx',
                'default_graph_version'=>'v2.8',
            ]);
        $q = '';
        $type = '';
        $address = '';
        $distance = '';
        $s = '';
        $api_key = "xxxxxxxxxx";
        $access_token = "xxxxxxxx";
        $fb->setDefaultAccessToken($access_token);
?>
<html>
    <head>
        <title>Search</title>
        <style>
            .background_box{
                position: relative;
                background-color: lightgrey;
                width: 60%;
                height: 200px;  
                margin-top: 150px;
                margin-left:auto;
                margin-right:auto;
                border: 3px solid darkgrey;
                border-radius: 10px;
            }
            #facebook_search{
                position: relative;
                text-align: center;
                font-size: 2em;
            }
            #line{
                position: relative;
                width: 97%;
                border: solid 1.5px darkgray;
            }
            .form_data{
                position: relative;
                margin-left:1%;
                
            }
            #albums,#posts{
                border: 1px solid darkgrey;
                position: relative;
                width: 100%;
                background: #e3e6ea;
                color: black;
                font-family: sans-serif;
                text-align: center;
            }
        </style>
        
        <script language="JavaScript">
            function showHidden()
            {
                if(document.getElementById("entity").value=="place")
                    {
                        document.getElementById("loca").style.visibility = "visible";
                        document.getElementById("dist").style.visibility = "visible";
                        document.getElementById("loc").style.visibility = "visible";
                        document.getElementById("dis").style.visibility = "visible";
                    }
                else
                    {
                        document.getElementById("loca").style.visibility = "hidden";
                        document.getElementById("dist").style.visibility = "hidden";
                        document.getElementById("loc").style.visibility = "hidden";
                        document.getElementById("dis").style.visibility = "hidden";
                    }
            }
            
            function clearForm(f)
            {
                window.location.href="http://cs-server.usc.edu:30274/search.php";
                showHidden();
            }
            
            function toggle(i_d)
            {
	               if(i_d=="albums")
                       {
                           if(document.getElementById("album_data").style.display=="none")
                               {
                                   document.getElementById("album_data").style.display="block";
                                   document.getElementById("post_data").style.display="none";
                               }
                           else if(document.getElementById("album_data").style.display=="block")
                               {
                                   document.getElementById("album_data").style.display="none";
                               }
                       }
	               if(i_d=="posts")
                       {
                           if(document.getElementById("post_data").style.display=="none")
                               {
                                   document.getElementById("post_data").style.display="block";
                                   document.getElementById("album_data").style.display="none";
                               }
                           else if(document.getElementById("post_data").style.display=="block")
                               {
                                   document.getElementById("post_data").style.display="none";
                               }
                       }
            }
            
            function processAlbum(y)
            {
                var i_d = y.id;
                if(document.getElementById(i_d).style.display=="none")
                        {
                                   document.getElementById(i_d).style.display="block";
                               }
                           else
                               {
                               document.getElementById(i_d).style.display="none";
                               }
            }
            
        </script>
    </head>
    <body>
        <div name="greybody" class="background_box">
            <div name="title" id="facebook_search"><i>Facebook Search</i></div>
            <hr name="line" id=line>
            <br>
            <form class="form_data" name="query_form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <table><tr>
                <td>Keyword</td><td><input required name="keyword" id="key" type="text" autocomplete="on" value="<?php echo isset($_POST['keyword']) ? $_POST['keyword'] : '' ?>"></td></tr>
                <tr><td>Type:</td><td><select name="entity_type" id="entity" required onchange="showHidden()">
                    <option value="user" <?php if(isset($_POST["entity_type"])){if($_POST["entity_type"] == "user"){echo "selected";};}  ?>>Users</option>
                    <option value="event" <?php if(isset($_POST["entity_type"])){if($_POST["entity_type"] == "event"){echo "selected";};}  ?>>Events</option>
                    <option value="place" <?php if(isset($_POST["entity_type"])){if($_POST["entity_type"] == "place"){echo "selected";};}  ?>>Places</option>
                    <option value="page" <?php if(isset($_POST["entity_type"])){if($_POST["entity_type"] == "page"){echo "selected";};}  ?>>Pages</option>  
                    <option value="group" <?php if(isset($_POST["entity_type"])){if($_POST["entity_type"] == "group"){echo "selected";};}  ?>>Group</option>
                </select></td></tr>
                <tr><td id="loca" style="visibility:hidden">Location</td><td><input type="text" name="location" id="loc" style="visibility:hidden" autocomplete="off" value="<?php echo isset($_POST['location']) ? $_POST['location'] : '' ?>"></td>
                <td id ="dist" style="visibility:hidden">Distance(meters)</td><td><input type="text" name="distance" id="dis" style="visibility:hidden" autocomplete="off"  pattern="\d*" value="<?php echo isset($_POST['distance']) ? $_POST['distance'] : '' ?>"></td></tr>
                <tr><td></td><td><input name="submit" type="submit" value="Search">
                <input name="clear" type="button" value="Clear" onclick="clearForm(this.form);"></td></tr></table>
            </form>
            <br><br>
            
        <?php if(isset($_POST["submit"])): ?>
        <script>showHidden();</script>
        <?php
        
        $q = $_POST['keyword'];
        $type = $_POST['entity_type'];
        $address = $_POST['location'];
        $distance = $_POST['distance'];
        $s = $_POST['submit'];
        
            
        if($type=="place")
        {
            if(isset($address))
        //      Google API call
            {
            if($address!='')
            {
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&key=".urlencode($api_key);
            $jsonData = file_get_contents($url);         
            $dat = json_decode($jsonData,true);
            $longitude = $dat["results"][0]["geometry"]["location"]["lng"];
            $latitude = $dat["results"][0]["geometry"]["location"]["lat"];
            }
            }
            if(!isset($longitude))
            {
                $longitude = 0;
            }
            if(!isset($latitude))
            {
                $latitude = 0;
            }
        //      Facebook API call
                        
$req = $fb->request('GET', 'search?q='.urlencode($q).'&type='.urlencode($type)."&center=".urlencode($latitude).",".urlencode($longitude)."&distance=".urlencode($distance).'&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
            $resp = $fb->getClient()->sendRequest($req);
            $gNode = $resp->getGraphEdge();
        }
        else{           
            $request1 = $fb->request('GET', 'search?q='.urlencode($q).'&type='.urlencode($type).'&fields=id,name,picture.width(700).height(700),albums.limit(5){name,photos.limit(2){name,picture}},posts.limit(5)');
            $response1 = $fb->getClient()->sendRequest($request1);
            $gNode = $response1->getGraphEdge();
            
        }
            if(sizeof($gNode)==0)
            {
                $temp .= '<html><head><style>
                .t1{
                font-family: sans-serif;
                position: relative;
                width:100%;
                background-color: #f3f3f3;
                border: 3px solid darkgrey;
                border-collapse: collapse;
                padding: 4px;
                }
                .head, .tdata{
                border: 1px solid darkgrey;
                text-align:center;
                }
                .head {
                background: #e3e6ea;
                color: black;
                text-align: left;
                }</style>
                </head><body>';
                $temp .= '<table class="t1">';
                $temp .= '<tr class="row"><th class="head">No Records Have Been Found</th><tr></table></body></html>';
            }
            if(($type=="user" or $type=="group" or $type == "page" or $type == "place") and sizeof($gNode)!=0){
                $temp .= '<table style="font-family: sans-serif;
                position: relative;
                width:100%;
                background-color: #f3f3f3;
                border: 3px solid darkgrey;
                border-collapse: collapse;
                padding: 4px;" class="t1">';
                $temp .= '<tr class="row"><th style="border: 1px solid darkgrey;background: #e3e6ea;
                color: black;
                text-align: left;" class="head">Profile Picture</th>';
                $temp .= '<th style="border: 1px solid darkgrey;background: #e3e6ea;
                color: black;
                text-align: left;" class="head">Name</th>';
                $temp .='<th style="border: 1px solid darkgrey;background: #e3e6ea;
                color: black;
                text-align: left;" class="head">Details</th>';
                foreach($gNode as $item)
                {
                    $temp .= '<tr  class="row">';
                    $temp .= '<td style="border: 1px solid darkgrey;" class="tdata"><a href="'.$item["picture"]["url"].'" target="_blank"><img src= "' . $item["picture"]["url"] . '" width ="40" height="30"></a></td>';
                    $temp .= '<td style="border: 1px solid darkgrey;" class="tdata">' . $item["name"] . '</td>';
                    if($address=='' and $distance=='' and $type=="place")
                    {
                        $u = "http://cs-server.usc.edu:30274/search.php?U_id=".$item["id"]."&keyword=".urlencode($q)."&type=".urlencode($type)."&address=null&distance=null&submit=".urlencode($s);
                    }
                    else if($address=='' and $distance=='' and $type!="place"){
                        $u = "http://cs-server.usc.edu:30274/search.php?U_id=".$item["id"]."&keyword=".urlencode($q)."&type=".urlencode($type)."&submit=".urlencode($s);
                    }
                    else{
                        $u = "http://cs-server.usc.edu:30274/search.php?U_id=".$item["id"]."&keyword=".urlencode($q)."&type=".urlencode($type)."&address=".urlencode($address)."&distance=".urlencode($distance)."&submit=".urlencode($s);
                    }
                    $temp .= '<td style="border: 1px solid darkgrey;" class="tdata"><a href="'.$u.'">Details</a></td>';
                    $temp .= "</tr>";
                }
                $temp .= "</table>";
            }
            if($type == "event")   
            {
                $request2 = $fb->request('GET', 'search?q='.urlencode($q).'&type='.urlencode($type).'&fields=id,name,picture.width(700).height(700),place');
                $response2 = $fb->getClient()->sendRequest($request2);
                $gNode = $response2->getGraphEdge(); 
                if(sizeof($gNode)!=0)
                {
                $temp .= '<table style="font-family: sans-serif;
                position: relative;
                width:100%;
                background-color: #f3f3f3;
                border: 3px solid darkgrey;
                border-collapse: collapse;
                padding: 4px;" class="t1">';
                $temp .= '<tr class="row"><th style="border: 1px solid darkgrey;background: #e3e6ea;
                color: black;
                text-align: left;" class="head">Profile Picture</th>';
                $temp .= '<th style="border: 1px solid darkgrey;background: #e3e6ea;
                color: black;
                text-align: left;" class="head">Name</th>';
                $temp .='<th style="border: 1px solid darkgrey;background: #e3e6ea;
                color: black;
                text-align: left;" class="head">Place</th>';
                foreach($gNode as $item)
                {
                    $temp .= '<tr  class="row">';
                    $temp .= '<td style="border: 1px solid darkgrey;" class="tdata"><a href="'.$item["picture"]["url"].'" target="_blank"><img src= "' . $item["picture"]["url"] . '" width ="40" height="30"></a></td>';
                    $temp .= '<td style="border: 1px solid darkgrey;" class="tdata">' . $item["name"] . '</td>';
                    if(isset($item["place"])):
                        $temp .= '<td style="border: 1px solid darkgrey;" class="tdata">'.$item["place"]["name"].'</td>';
                    else:
                        $temp .= '<td style="border: 1px solid darkgrey;" class="tdata"></td>';
                    $temp .= "</tr>";
                    endif;
                }
                $temp .= "</table>";
                }
            }
        ?>
        <?php endif; ?>
        <div id="table_data"> <?php echo $temp; ?> </div>
        
        <?php 
            if(isset($_GET["U_id"])){
            $fb->setDefaultAccessToken($access_token);            
            $request3 = $fb->request('GET', '/' . $_GET['U_id'] . '?fields=name,albums.limit(5){name,photos.limit(2){name,picture.width(700).height(700)}},posts.limit(5)');
            $response3 = $fb->getClient()->sendRequest($request3);
            $gNode = $response3->getGraphNode();   
        }
            ?>
        <?php if(isset($_GET["U_id"])){ ?>
                    <script>

                                    document.getElementById("key").setAttribute("value", "<?php echo $_GET["keyword"]?>");                    
                                    document.getElementById("entity").value = "<?php echo $_GET["type"] ?>";
                                    <?php if($_GET["type"]=="place"): ?>
                                        showHidden();
                                    
                                        document.getElementById("loc").setAttribute("value", "<?php echo $_GET["address"]?>");
                                        document.getElementById("dis").setAttribute("value", "<?php echo $_GET["distance"]?>");
                                        <?php if($_GET["address"] == "null"): ?>
                                            document.getElementById("loc").setAttribute("value", "");
                                        <?php endif;?>
                                        <?php if($_GET["distance"] == "null"): ?>
                                            document.getElementById("dis").setAttribute("value", "");
                                        <?php endif;?>
                                    <?php endif;?>

                    </script> 
                    <?php if(isset($gNode['albums'])):?>
                                    <div id = "albums"><a href="javascript:toggle('albums');">Albums</a></div>
                                    <?php
                                            $temp2 .= '<table style="font-family: sans-serif;
                                            position: relative;
                                            width:100%;
                                            background-color: #f3f3f3;
                                            border: 3px solid darkgrey;
                                            border-collapse: collapse;
                                            padding: 4px;">';
                                            for($i=0; $i<5; $i++)
                            {  
                                if(isset($gNode['albums'][$i]))
                                {
                                    if(isset($gNode['albums'][$i]['name']))
                                    {
                                        $a_name = $gNode['albums'][$i]['name'];
                                        $row_name = "row".$i;
                                        if(isset($gNode['albums'][$i]['photos']))
                                        {
                                            $temp2 .= '<tr>';
                                            $temp2 .= '<td style="border: 1px solid darkgrey"><a href="javascript:processAlbum('.$row_name.');">' . $a_name . '</a></td>';
                                            $temp2 .= '</tr>';
                                            $temp2.='<tr id='.$row_name.' style="display:none;">';
                                            $temp2.='<td>';
                                            for($j=0; $j<2; $j++)
                                            {
                                                if(isset($gNode['albums'][$i]['photos'][$j]))
                                                {
                                                    if(isset($gNode['albums'][$i]['photos'][$j]['picture']))
                                                    {
                                                        $pid = $gNode['albums'][$i]['photos'][$j]['id'];           
                                                        $request4 = $fb->request('GET', '/' . $pid . '/picture');
                                                        $response4 = $fb->getClient()->sendRequest($request4);
                                                        $gNode2 = $response4->getHeaders(); 
                                                        $temp2.= "<a href=".$gNode2['Location']." target='_blank'><img src=".$gNode['albums'][$i]['photos'][$j]['picture']." width='80' height='80' style='padding:3px;'></a>";
                                                    }
                                                }
                                            }
                                            $temp2.='</td>';
                                            $temp2.='</tr>';
                                        }
                                        else
                                        {
                                           $temp2 .= '<tr><td style="border: 1px solid darkgrey">' .$a_name .'</td></tr>'; 
                                        }
                                    }
                            }
                            }
                        $temp2 .= '</table>';
                    else:?>
                            <div id = "albums">No Albums have been found</div>  
                    <?php endif; ?>
                    <div id= "album_data" style="display: none;"><?php echo $temp2; ?></div>
            
            <br>
            
            <?php if(isset($gNode['posts'])): ?>
                    <div id = "posts"><a href="javascript:toggle('posts');">Posts</a></div>
                    <?php 
                    $temp3 .= '<table style="font-family: sans-serif;
                        position: relative;
                        width:100%;
                        background-color: #f3f3f3;
                        border: 3px solid darkgrey;
                        border-collapse: collapse;
                        padding: 4px;"><th style="border: 1px solid darkgrey;background: #e3e6ea;
                        color: black;
                        text-align: left">Message</th>';
                        $flag = 0;
                        for($i=0; $i<5; $i++)
                        {  
                            if(isset($gNode['posts'][$i]['message']))
                            {
                                $flag=1;
                                $a_name = $gNode['posts'][$i]['message'];
                                $temp3 .= '<tr class="row">';
                                $temp3 .= '<td style="border: 1px solid darkgrey" class="tdata">' . $a_name .'  '.'</td>';
                                $temp3 .= '</tr>';
                            }
                        }
                        if($flag==0)
                        {
                            $temp3.= '<tr><td style="border: 1px solid darkgrey" class="tdata"><b>SORRY! NO MESSAGES FOUND</b><td></tr>';
                        }
                    $temp3 .= '</table>';
            else:?>
                    <div id = "posts">No Posts have been found</div>  
            <?php endif; ?>
                    <div id= "post_data" style="display: none;"><?php echo $temp3; ?></div>
            
        <?php } ?>
            <br>
        </div>
    </body>
</html>
