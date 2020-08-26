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
		$params = $page."?";
		foreach($_GET as $p=>$v) {
			if($p != 'paged') {
				if($v != '') {
					$params .= $p."=".$v."&";
				}
			}
		}
		$result = rtrim($params, '&');
		return $result;
	}
	
	function get_table_sort_url($page,$param,$name='') {
		global $db;
		$params = $page."?";
		foreach($_GET as $p=>$v) {
			if($p!='orderby' && $p!='sort') {
				$params.=$p."=".$v."&";
			}
		}
		$params.= "orderby=".$db->escape($param)."&";
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
			if($_GET['orderby'] == $db->escape($param)) {
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
	
	//pagination
	function pagination($total=0,$per_page=25,$page=1,$self='?',$style='default') { 
		$adjacents = "2";	
		$page = ($page == 0 ? 1 : $page);  
		$start = ($page - 1) * $per_page;
		$prev = $page - 1;							
		$next = $page + 1;
		$lastpage = ceil($total/$per_page);
		$lpm1 = $lastpage - 1;	
		$pagination = "<div class='col-sm-12 col-md-5'><div class='dataTables_info' id='datatable_info' role='status' aria-live='polite'>Showing 1 to 10 of 57 entries</div></div>";
		//$self = $_SERVER['REQUEST_URI'];
		if($lastpage > 1) {	
			$pagination .= "<ul class='paginatiocvxcvn'>";
				
			if ($lastpage < 7 + ($adjacents * 2)) {	
				for ($counter = 1; $counter <= $lastpage; $counter++) {
					if ($counter == $page)
						$pagination.= "<li><a class='current'>$counter</a></li>";
					else
						$pagination.= "<li><a href='$self&paged=$counter'>$counter</a></li>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2)) {
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='$self&paged=$counter'>$counter</a></li>";					
					}
					$pagination.= "<li class='dot'><a>...</a></li>";
					$pagination.= "<li><a href='$self&paged=$lpm1'>$lpm1</a></li>";
					$pagination.= "<li><a href='$self&paged=$lastpage'>$lastpage</a></li>";		
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li><a href='$self&paged=1'>1</a></li>";
					$pagination.= "<li><a href='$self&paged=2'>2</a></li>";
					$pagination.= "<li class='dot'><a>...</a></li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='$self&paged=$counter'>$counter</a></li>";					
					}
					$pagination.= "<li class='dot'><a>...</a></li>";
					$pagination.= "<li><a href='$self&paged=$lpm1'>$lpm1</a></li>";
					$pagination.= "<li><a href='$self&paged=$lastpage'>$lastpage</a></li>";		
				}
				else
				{
					$pagination.= "<li><a href='$self&paged=1'>1</a></li>";
					$pagination.= "<li><a href='$self&paged=2'>2</a></li>";
					$pagination.= "<li class='dot'><a>...</a></li>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='$self&paged=$counter'>$counter</a></li>";					
					}
				}
			}		
			if ($page < $counter - 1){ 
				$pagination.= "<li><a href='$self&paged=$next'>Next</a></li>";
				$pagination.= "<li><a href='$self&paged=$lastpage' class='btn btn-primary waves-effect waves-light'></a></li>";
			} else {
				$pagination.= "<li><a class='disabled'>Next</a></li>";
				$pagination.= "<li><a class='disabled'>Last</a></li>";
			}
			$pagination.= "</ul></div>";		
		}
		return $pagination;
	}
	/* New pagination Date: 14-05-2019 */
	function paginate($total=0,$per_page=25,$page=1,$self='?',$style='default') { 
		$adjacents = "2";	
		$page = ($page == 0 ? 1 : $page);  
		$start = ($page - 1) * $per_page;
		$prev = $page - 1;							
		$next = $page + 1;
		$lastpage = ceil($total/$per_page);
		$lpm1 = $lastpage - 1;	
		$pagination = "";
		//$self = $_SERVER['REQUEST_URI'];
		if($lastpage > 1) {	
			$pagination .= "<ul class='asdpagination'>";
				
			if ($lastpage < 7 + ($adjacents * 2)) {	
				for ($counter = 1; $counter <= $lastpage; $counter++) {
					if ($counter == $page)
						$pagination.= "<li><a class='current'>$counter</a></li>";
					else
						$pagination.= "<li><a href='".$self.$counter."/'>$counter</a></li>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2)) {
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='".$self.$counter."/'>$counter</a></li>";					
					}
					$pagination.= "<li class='dot'><a>...</a></li>";
					$pagination.= "<li><a href='".$self.$lpm1."/'>$lpm1</a></li>";
					$pagination.= "<li><a href='".$self.$lastpage."/'>$lastpage</a></li>";		
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li><a href='".$self."1/'>1</a></li>";
					$pagination.= "<li><a href='".$self."2/'>2</a></li>";
					$pagination.= "<li class='dot'><a>...</a></li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='".$self.$counter."/'>$counter</a></li>";					
					}
					$pagination.= "<li class='dot'><a>...</a></li>";
					$pagination.= "<li><a href='".$self.$lpm1."/'>$lpm1</a></li>";
					$pagination.= "<li><a href='".$self.$lastpage."/'>$lastpage</a></li>";		
				}
				else
				{
					$pagination.= "<li><a href='".$self."1/'>1</a></li>";
					$pagination.= "<li><a href='".$self."2/'>2</a></li>";
					$pagination.= "<li class='dot'><a>...</a></li>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='".$self.$counter."/'>$counter</a></li>";					
					}
				}
			}		
			if ($page < $counter - 1){ 
				$pagination.= "<li><a href='".$self.$next."/'>Next</a></li>";
				$pagination.= "<li><a href='".$self.$lastpage."/'>Last</a></li>";
			} else {
				$pagination.= "<li><a class='disabled'>Next</a></li>";
				$pagination.= "<li><a class='disabled'>Last</a></li>";
			}
			$pagination.= "</ul>\n";		
		}
		return $pagination;
	}
	function paginatelatest($total=0,$per_page=25,$page=1,$self='?',$style='default') { 
		$adjacents = "2";	
		$page = ($page == 0 ? 1 : $page);  
		$start = ($page - 1) * $per_page;
		$prev = $page - 1;							
		$next = $page + 1;
		$lastpage = ceil($total/$per_page);
		$lpm1 = $lastpage - 1;	
		$pagination = "";
		//$self = $_SERVER['REQUEST_URI'];
		if($lastpage > 1) {	
			$pagination .= "<ul class='dfsdfspagination'>";
				
			if ($lastpage < 7 + ($adjacents * 2)) {	
				for ($counter = 1; $counter <= $lastpage; $counter++) {
					if ($counter == $page)
						$pagination.= "<li><a class='current'>$counter</a></li>";
					else
						$pagination.= "<li><a href='".$self.$counter."/'>$counter</a></li>";					
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2)) {
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='".$self.$counter."/'>$counter</a></li>";					
					}
					$pagination.= "<li class='dot'><a>...</a></li>";
					$pagination.= "<li><a href='".$self.$lpm1."/'>$lpm1</a></li>";
					$pagination.= "<li><a href='".$self.$lastpage."/'>$lastpage</a></li>";		
				}
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagination.= "<li><a href='".$self."1/'>1</a></li>";
					$pagination.= "<li><a href='".$self."2/'>2</a></li>";
					$pagination.= "<li class='dot'><a>...</a></li>";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='".$self.$counter."/'>$counter</a></li>";					
					}
					$pagination.= "<li class='dot'><a>...</a></li>";
					$pagination.= "<li><a href='".$self.$lpm1."/'>$lpm1</a></li>";
					$pagination.= "<li><a href='".$self.$lastpage."/'>$lastpage</a></li>";		
				}
				else
				{
					$pagination.= "<li><a href='".$self."1/'>1</a></li>";
					$pagination.= "<li><a href='".$self."2/'>2</a></li>";
					$pagination.= "<li class='dot'><a>...</a></li>";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<li><a class='current'>$counter</a></li>";
						else
							$pagination.= "<li><a href='".$self.$counter."/'>$counter</a></li>";					
					}
				}
			}		
			if ($page < $counter - 1){ 
				$pagination.= "<li><a href='".$self.$next."/'>Next</a></li>";
				$pagination.= "<li><a href='".$self.$lastpage."/'>Last</a></li>";
			} else {
				$pagination.= "<li><a class='disabled'>Next</a></li>";
				$pagination.= "<li><a class='disabled'>Last</a></li>";
			}
			$pagination.= "</ul>\n";		
		}
		return $pagination;
	}
}
?>
