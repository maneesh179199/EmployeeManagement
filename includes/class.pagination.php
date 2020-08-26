<?php
class ListTable {
	
	public $status;
	
	public $paged;
	
	public $limit;
	
	function per_page($limit) {
		echo "<select name='l' style='width:53px;'>\n";
		$paged = array(10,25,50,100,250,500);
		foreach($paged as $p) {
			if($p==$limit) {
				echo "<option value='$p' selected>$p</option>";
			} else {
				echo "<option value='$p'>$p</option>";
			}
		}
		echo "</select>\n";
	}
	
	function add_page_param($page) {
		$params = $page."?";
		foreach($_GET as $p=>$v) {
			$params .= $p."=".$v."&";
		}
		$result = rtrim($params, '&');
		return $result;
	}
	
	function remove_page_param($page) {
		//$params = $page."?";
		$params = array();
		foreach($_GET as $p=>$v) {
			if($p != 'paged') {
				if($v != '') {
					$params[] .= $p."=".$v;
				}
			}
		}
		$paramlink = implode('&', $params);
		$result = $page."?".$paramlink;
		return $result;
	}
	
	function get_table_sort_url($page,$param,$name='') {
		global $sdb;
		$params = $page."?";
		foreach($_GET as $p=>$v) {
			if($p!='orderby' && $p!='sort') {
				$params.=$p."=".$v."&";
			}
		}
		$params.= "orderby=".$sdb->escape($param)."&";
		if(isset($_GET['sort']) && $_GET['sort']!='') {
			if($_GET['sort']=='asc') {
				$params.= "sort=desc";
			} else if($_GET['sort']=='desc') {
				$params.= "sort=asc";
			}
		} else {
			$params.= "sort=desc";
		}
		$result = rtrim($params, '&');
		
		//Icons
		if(isset($_GET['orderby']) && $_GET['orderby'] != '') {			
			if($_GET['orderby'] == $sdb->escape($param)) {
				if(isset($_GET['sort']) && $_GET['sort']!='') {
					if($_GET['sort'] == 'asc') {
						$icon = "<i class=\"fa fa-caret-up\"></i>";
					} else if($_GET['sort']=='desc') {
						$icon = "<i class=\"fa fa-caret-down\"></i>";
					}					
				} else {
					$icon = "<i class=\"fa fa-caret-up\"></i>";
				}
			} else {
				$icon = "";
			}
		} else {
			$icon = "";
		}
		
		$url = "<a href=\"$result\">".$name." ".$icon."</a>";		
		echo $url;
	}
	
	function pagination($total='50',$per_page=25,$page=1,$self='?') {
		$adjacents = "2";	
		$page = ($page == 0 ? 1 : $page);  
		$start = ($page - 1) * $per_page;
		$prev = $page - 1;							
		$next = $page + 1;
		$lastpage = ceil($total/$per_page);
		$lpm1 = $lastpage - 1;	
		//$pagination = "";
		//$self = $_SERVER['REQUEST_URI'];
		$pagination = "<div class='col-md-5 col-sm-12'><div class='pagination-info'>Showing $page of $lastpage Pages</div></div><div class='col-md-7 col-sm-12'>";
		if($lastpage > 1) {	
			$pagination .= "<ul class='pagination float-right'>";
			if($lastpage < 7 + ($adjacents * 2)) {	
				for($counter = 1; $counter <= $lastpage; $counter++) {
					if($counter == $page) {
						$pagination .= "<li class='page-item active'><a class='page-link' href='#'>".$counter."</a></li>";
					} else {
						$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$counter."'>".$counter."</a></li>";		
					}			
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2)) {
				if($page < 1 + ($adjacents * 2)) {
					for($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
						if ($counter == $page) {
							$pagination .= "<li class='page-item active'><a class='page-link' href='#'>".$counter."</a></li>";
						} else {
							$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$counter."'>".$counter."</a></li>";	
						}				
					}
					$pagination .= "<li class='page-item dot'><a class='page-link' href='#'>..</a></li>";
					$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$lpm1."'>".$lpm1."</a></li>";
					$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$lastpage."'>".$lastpage."</a></li>";		
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
					$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=1'>1</a></li>";
					$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=2'>2</a></li>";
					$pagination .= "<li class='page-item dot'><a class='page-link' href='#'>..</a></li>";
					for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
						if ($counter == $page) {
							$pagination .= "<li class='page-item active'><a class='page-link' href='#'>".$counter."</a></li>";
						} else {
							$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$counter."'>".$counter."</a></li>";	
						}				
					}
					$pagination .= "<li class='page-item dot'><a class='page-link' href='#'>..</a></li>";
					$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$lpm1."'>".$lpm1."</a></li>";
					$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$lastpage."'>".$lastpage."</a></li>";		
				}
				else {
					$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=1'>1</a></li>";
					$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=2'>2</a></li>";
					$pagination .= "<li class='page-item dot'><a class='page-link' href='#'>..</a></li>";
					for($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
						if($counter == $page) {
							$pagination .= "<li class='page-item active'><a class='page-link' href='#'>".$counter."</a></li>";
						} else {
							$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$counter."'>".$counter."</a></li>";
						}					
					}
				}
			}		
			if($page < $counter - 1) { 
				$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$next."' aria-label='Next'>Next</a></li>";
				$pagination .= "<li class='page-item'><a class='page-link' href='".$self."&paged=".$lastpage."' aria-label='Last'>Last</a></li>";
			} else {
				$pagination .= "<li class='page-item active'><a class='page-link' href='#' aria-label='Next'>Next</a></li>";
				$pagination .= "<li class='page-item active'><a class='page-link' href='#' aria-label='Last'>Last</a></li>";
			}
			$pagination .= "</ul></div>";		
		}
		return $pagination;
	}
}
?>

