<?php 
	
	class Main {


		function __construct() {
			$this->sub = "photo-gallery";
			$this->home = "http://localhost/{$this->sub}/";
			$this->request = trim(str_replace("/".$this->sub,"",$_SERVER['REQUEST_URI']),"/");
			$this->current_rel = $_SERVER["DOCUMENT_ROOT"].$_SERVER['REQUEST_URI'];
			$this->current_abs = $this->home.$this->request;
			$this->image_dir = $_SERVER["DOCUMENT_ROOT"]."/{$this->sub}/images";
			($this->request == "" ? $this->directory = $this->image_dir."/home" : $this->directory .=  $this->image_dir."/".$this->request );

			if(!is_dir($this->directory)){ header("HTTP/1.0 404 Not Found"); die(); }

		}

		function getNav(){
			$folders = $this->dir_tree($this->image_dir,array("cache","home"));
			$tree = $this->build_tree($folders);
			return $this->build_list($tree,$this->home);
		}


		function getContent($dir = ''){
	
			$folder_contents = $this->dir_tree($this->directory, array(), "files");
			
			$content = "";
			$image_paths = array();
			if(count($folder_contents) > 1){
				foreach ($folder_contents as $folder_content) {
					
					$info = pathinfo($folder_content);
					
					$path = $this->home . str_replace($_SERVER["DOCUMENT_ROOT"]."/".$this->sub."/","",$folder_content);

					if(in_array($info["extension"], array("bmp","gif","jpg","png","jpeg"))){
						
						list($width, $height) = getimagesize($path);

						$image_paths[] = array(
							"path" => $path,
							"width" => $width,
							"height" => $height
						);
					}elseif(in_array($info["extension"], array("txt","html"))){
						
						$file_handle = fopen($path, "r");
						$line = '';
						while (!feof($file_handle)) {
						   $line .= fgets($file_handle);
						}
						fclose($file_handle);

						$pages = explode("[page break]", $line);
						foreach ($pages as $page) {
							$content .= "<div>{$page}</div>";
						}
					}

				}	
			}

			foreach ($image_paths as $image_info) {
				if($image_info["height"] > 0){
					$content .= '<img src="' . $this->home . "images/timthumb.php?src=".$image_info["path"].'&h=550" height="550" width="' . round( (500 * $image_info["width"] ) / $image_info["height"])  . '" />';
					$content .= "\n";	
				}
				
			}
			return $content;
		}
		

		// http://stackoverflow.com/questions/4903668/convert-array-of-paths-into-ul-list

		function build_tree($path_list) {
		    $path_tree = array();
		    foreach ($path_list as $path => $title) {
		        $list = explode('/', trim($path, '/'));
		        $last_dir = &$path_tree;
		        foreach ($list as $dir) {
		            $last_dir =& $last_dir[$dir];
		        }
		        $last_dir['__title'] = ucwords(str_replace("-"," ",$title));
		    }
		    return $path_tree;
		}



		function build_list($tree, $prefix = '') {
		    $ul = '';
		    foreach ($tree as $key => $value) {
		        $li = '';
		        if (is_array($value)) {
		            if (array_key_exists('__title', $value)) {
						// $li .= sprintf('%s%s/ <a href="/%s%s/">%s</a>', $prefix, $key, $prefix, $key, $value['__title']);
		                if($this->current_abs == $prefix.$key){
		               	 $li .= sprintf('<a class="current" href="%s%s/">%s</a>', $prefix, $key, $value['__title']);
		                }else{
		                	$li .= sprintf('<a href="%s%s/">%s</a>', $prefix, $key, $value['__title']);
		                }

		            } else {
		                $li .= "$prefix$key/";
		            }
		            $li .= $this->build_list($value, "$prefix$key/");
		            $ul .= strlen($li) ? sprintf('<li>%s</li>', $li) : '';
		        }
		    }
		    return strlen($ul) ? sprintf('<ul>%s</ul>', $ul) : '';
		}




		function dir_tree($dir, $ignore = array(), $type = "folder") {
		   $path = '';
		   $stack[] = $dir;

		   while ($stack) {
		       $thisdir = array_pop($stack);
		       if ($dircont = scandir($thisdir)) {
		           $i=0;
		           while (isset($dircont[$i])) {
		               if ($dircont[$i] !== '.' && $dircont[$i] !== '..') {
		                   $current_file = "{$thisdir}/{$dircont[$i]}";
		                  switch($type){
		                  	case "folder":
								if (is_dir($current_file)) {
										$new = str_replace($dir,"",$thisdir);
										($new == "" ? "" : $new = $new . "/" );
									if(!in_array($dircont[$i], $ignore)){
									    $path[$new.$dircont[$i]] = $dircont[$i];
								    }
								    $stack[] = $current_file;
								}
		                  	break;
		                  	case "files":
								if (is_file($current_file)) {
								   $path[] = "{$thisdir}/{$dircont[$i]}";
								} else

		                  	break;
		                  }	
		               }
		               $i++;
		           }
		       }
		   }

		   return $path;
		}

	}

$main = new Main(); 

?>