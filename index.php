<html>
    <head>
        <title>ZK Test</title>
        <meta http-equiv="refresh" content="30" > 
    </head>
    
    <body>
<?php
    include("zklib/zklib.php");
    date_default_timezone_set('Asia/Dhaka');
    $zk = new ZKLib("192.168.0.201", 4370);
   $ret = $zk->connect();
//    var_dump($ret);
//    echo $ret = true;
    //sleep(1);
    if ( $ret ){
        $zk->disableDevice();
       // sleep(1);
    ?>
        
        <table border="1" cellpadding="5" cellspacing="2">
            <tr>
                <td><b>Status</b></td>
                <td>Connected</td>
                <td><b>Version</b></td>
                <td><?php echo $zk->version() ?></td>
                <td><b>OS Version</b></td>
                <td><?php echo $zk->osversion() ?></td>
                <td><b>Platform</b></td>
                <td><?php echo $zk->platform() ?></td>
            </tr>
            <tr>
                <td><b>Firmware Version</b></td>
                <td><?php echo $zk->fmVersion() ?></td>
                <td><b>WorkCode</b></td>
                <td><?php echo $zk->workCode() ?></td>
                <td><b>SSR</b></td>
                <td><?php echo $zk->ssr() ?></td>
                <td><b>Pin Width</b></td>
                <td><?php echo $zk->pinWidth() ?></td>
            </tr>
            <tr>
                <td><b>Face Function On</b></td>
                <td><?php echo $zk->faceFunctionOn() ?></td>
                <td><b>Serial Number</b></td>
                <td><?php echo $zk->serialNumber() ?></td>
                <td><b>Device Name</b></td>
                <td><?php echo $zk->deviceName(); ?></td>
                <td><b>Get Time</b></td>
                <td><?php echo $zk->getTime() ?></td>
            </tr>
        </table>
        <hr />
        <table border="1" cellpadding="5" cellspacing="2" style="float: left; margin-right: 10px;">
            <tr>
                <th colspan="5">Data User</th>
            </tr>
            <tr>
                <th>UID</th>
                <th>ID</th>
                <th>Name</th>
                <th>Role</th>
                <th>Password</th>
            </tr>
            <?php
            try {
                
                //$zk->setUser(1, '1', 'Admin', '', LEVEL_ADMIN);
                $user = $zk->getUser();
               // sleep(1);
                foreach ($user as $uid => $userdata) {
                    if ($userdata[2] == LEVEL_ADMIN)
                        $role = 'ADMIN';
                    elseif ($userdata[2] == LEVEL_USER)
                        $role = 'USER';
                    else
                        $role = 'Unknown';

                   $result= $zk->userSave($userdata[0],$userdata[1],$userdata[2],$userdata[3],'localhost:8000');
                    //$zk->debug($result);
                ?>
                <tr>
                    <td><?php echo $uid ?></td>
                    <td><?php echo $userdata[0] ?></td>
                    <td><?php echo $userdata[1] ?></td>
                    <td><?php echo $role ?></td>
                    <td><?php echo $userdata[3] ?>&nbsp;</td>
                </tr>
                <?php
                }
            } catch (Exception $e) {
                header("HTTP/1.0 404 Not Found");
                header('HTTP', true, 500); // 500 internal server error                
            }
            //$zk->clearAdmin();
            ?>
        </table>
        
        <table border="1" cellpadding="5" cellspacing="2">
            <tr>
                <th colspan="6">Data Attendance</th>
            </tr>
            <tr>
                <th>Index</th>
                <th>UID</th>
                <th>ID</th>
                <th>Status</th>
                <th>Date</th>
                <th>Time</th>
            </tr>
            <?php
            $attendance = $zk->getAttendance();
          //  sleep(1);
          $date = date("d-m-Y");
            foreach ($attendance as $idx =>$attendancedata) {


                if ( $attendancedata[2] == 14 )
                    $status = 'Check Out';
                else
                    $status = 'Check In';

                  $result=$zk->dataSend($attendancedata[1],$attendancedata[3],'localhost:8000');
                    $fWrite = fopen("log.txt","a+");
                    $qry= [
                        "sending"=>$result,
                        "data"=>$attendancedata,
                    ];
                    $qry = json_encode($qry,true);
                    $wrote = fwrite($fWrite, $qry."\n");
                fclose($fWrite);
                   //$zk->debug($result);
                    $date = $attendancedata[3];
            ?>
            <tr>
                <td><?php echo $idx ?></td>
                <td><?php echo $attendancedata[0] ?></td>
                <td><?php echo $attendancedata[1] ?></td>
                <td><?php echo $status ?></td>
                <td><?php echo date( "d-m-Y", strtotime( $attendancedata[3] ) ) ?></td>
                <td><?php echo date( "H:i:s", strtotime( $attendancedata[3] ) ) ?></td>
            </tr>
            <?php
            }
            $zk->clearAttendance();
            // if(date( "d-m-Y", strtotime($date))!=date( "d-m-Y")){
            //  $zk->clearAttendance();
            // }
            ?>
        </table>
    <?php
        //$zk->enrollUser('123');
        //$zk->setUser(123, '123', 'Suman Sen', '', LEVEL_USER);
        $zk->enableDevice();
        //sleep(1);
        $zk->disconnect();
}
?>
    </body>
</html>
