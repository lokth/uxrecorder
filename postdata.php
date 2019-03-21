<?php 
        $url = $_POST['siteurl'];
        $data =  $_POST['rec_data'];

		//database tasks handeling
        $hostname = "localhost";
		$username ="root";
		$password ="";
		$con=mysqli_connect($hostname,$username,$password,"uxrecorder");
		if (mysqli_connect_errno())
		  {
		  	echo "Failed to connect to MySQL: " . mysqli_connect_error();
		  }

		$sql="UPDATE tbl_sitedata SET data_json='$data' ORDER BY id DESC LIMIT 1";
				if (!mysqli_query($con,$sql))
				  {
				  	die('Error: ' . mysqli_error($con));
				  }	

		mysqli_close($con);
        //$html = str_replace('<a href="javascript:void(0)" onclick="seeRecording()" id='view_record_btn'>See Recording</a>','');
        // $handle = fopen('temp.html', 'rw+');
        // $handle2 = fopen('data.json', 'w+');
        // fwrite($handle, $html);
        // fwrite($handle2, $data);
        // fclose($handle);
        // fclose($handle2);
        //echo json_encode($data);
?>