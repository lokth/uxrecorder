<?php 
ini_set('max_execution_time', 360);
	/* <<<<  >>>>*/ 
	require'simple_html_dom.php';		//PHP Simple HTML DOM Parser
?>			

<?php
		error_reporting(0);

		$srcCode = $_POST['doc'];		//whole html file 

		$url = $_POST['myurl'];				//site URL
		
		$url_path=$url; 

		$u=str_replace('/', '',$url_path);

		$name=str_replace(':', '', $u);
		
		$hostname = "localhost";
		$username ="root";
		$password ="";
		$con=mysqli_connect($hostname,$username,$password,"uxrecorder");

		$sql="INSERT INTO tbl_sitedata (url, data_json)
				VALUES
				('$url','[]')";
				
				if (!mysqli_query($con,$sql))
				  {
				  	die('Error: ' . mysqli_error($con));
				  }	
		mysqli_close($con);
		
		if(!file_exists('template'))	//template folder
			{
				mkdir('template',0777, true);
			}

		$create=fopen("template/".$name.'.html','w+') or die("can't open file");
		$write=fwrite($create,$srcCode);
		fclose($create);

		$dom = new simple_html_dom();
		$html = file_get_html('template/'.$name.'.html');

		foreach($html->find('a') as $href) 	//get href
			{ 
				$href->href="javascript:void(0)";
				$newhref = $href->href;	
	        }

	        $doc = $html;
	        $read=fopen("template/".$name.'.html','w+') or die("can't open file");
			$modify=fwrite($read,$doc);
			fclose($read);

		foreach($html->find('script') as $script)		//get script 
			{
					$scriptPath=$script->src;
					$path="";
					$js = explode("/", $scriptPath);
					$jsName = end($js);
	  				for ($i=0;$i<(count($js)-1);$i++) 
					 	{
					        $path .= $js[$i] . "/";
					        if(!file_exists('template/'.$path))
							{
								mkdir('template/'.$path, 0777, true);		//js folder created
							}
						}
						if(!file_exists('template/'.$path.$jsName))
						{
							file_put_contents('template/'.$path.$jsName,file_get_contents($url_path.$scriptPath));
						}

					 // if (preg_match('|^[a-zA-Z]+://|', $scriptPath)) 	//download js
					 //    {
					 //       file_put_contents('../uxcam/template/'.$path.$jsName,file_get_contents($scriptPath));
					 //    } 
					 //    else if(substr($scriptPath, 0, 2)=='//')
					 //    {
					 //        file_put_contents('../uxcam/template/'.$path.$jsName,file_get_contents('http:'.$scriptPath));
					 //    }
					 //    else
					 //    {
						// 	file_put_contents('../uxcam/template/'.$path.$jsName,file_get_contents($url_path.$scriptPath));
					 //    }							
			}

		foreach($html->find('img') as $img) 	//image
				{

					$imgpath=$img->src;
					$path="";
				 	$image = explode("/", $imgpath);
				 	  for ($i=0;$i<(count($image)-1);$i++) 
				 	  {
				        $path .= $image[$i] . "/";
				        if(!file_exists('template/'.$path))
							{
								mkdir('template/'.$path, 0777, true);		//img folder created
							}		
				      }
					$imgName = end($image);
					
					if(!file_exists('template/'.$path.$imgName))
					{
						file_put_contents('template/'.$path.$imgName,file_get_contents($url_path.$imgpath)); 	//img downloaded
					}
				} 

			function extract_css_urls( $text )
				{
					$urls = array( );
					$url_pattern     = '(([^\\\\\'", \(\)]*(\\\\.)?)+)';
					$urlfunc_pattern = 'url\(\s*[\'"]?' . $url_pattern . '[\'"]?\s*\)';
					$pattern         = '/(' .
						 '(@import\s*[\'"]' . $url_pattern     . '[\'"])' .
						'|(@import\s*'      . $urlfunc_pattern . ')'      .
						'|('                . $urlfunc_pattern . ')'      .  ')/iu';
					if ( !preg_match_all( $pattern, $text, $matches ) )
						return $urls;
					foreach ( $matches[3] as $match )
						if ( !empty($match) )
							$urls['import'][] = 
								preg_replace( '/\\\\(.)/u', '\\1', $match );
					foreach ( $matches[7] as $match )
						if ( !empty($match) )
							$urls['import'][] = 
								preg_replace( '/\\\\(.)/u', '\\1', $match );
					foreach ( $matches[11] as $match )
						if ( !empty($match) )
							$urls['property'][] = 
								preg_replace( '/\\\\(.)/u', '\\1', $match );
					return $urls;
				}

		foreach($html->find('link') as $link)	//get link 
				{
						if(strtolower($link->getAttribute('rel')) == "stylesheet" ) 
						{
						    $linkpath=$link->getAttribute('href');
					    }
						$links = explode("/", $linkpath);
						$path="";	
						$linkName = end($links);
						for ($i=0;$i<(count($links)-1);$i++) 
				 	  	{
					        $path .= $links[$i] . "/";
					        if(!file_exists('template/'.$path))					//css folder
								{
									mkdir('template/'.$path, 0777, true);		
								}		
				      	}
				      	if(!file_exists('template/'.$path.$linkName))
				      	{
							file_put_contents('template/'.$path.$linkName,file_get_contents($url_path.$linkpath));	//download css
						}

						$url='template/'.$path.$linkName;
						$text = file_get_contents( $url );
						$urls = extract_css_urls( $text );

						if ( !empty( $urls['import'] ) )
						{
							for($i=0;$i<=count($urls['import']);$i++)
							{
								$u=$urls['import'][$i];
								if(!file_exists('template/'.$u))
				      			{
									file_put_contents('template/'.$u,file_get_contents($url_path.$u));
								}
							}
						}

						if ( !empty( $urls['property'] ) )
						{
							for($i=0;$i<=count($urls['property']);$i++)
							{
								$p=$urls['property'][$i];
								if(!file_exists('template/'.$p))
				      			{
									file_put_contents('template/'.$p,file_get_contents($url_path.$p));
								}
							}
						}
				}





?>