<?php
/*
Copyright (c) 2020 HOSTINPL (HOSTING-RUS) https://vk.com/hosting_rus
Developed by Samir Shelenko and Alexander Zemlyanoy  (https://vk.com/id00v / https://vk.com/mrsasha082)
*/
class paginationLibrary {
	public $total = 0;
	public $page = 1;
	public $limit = 20;
	public $num_links = 10;
	public $url = '';
	public $text_next = '<i class="ki ki-bold-arrow-next icon-xs"></i>';
	public $text_prev = '<i class="ki ki-bold-arrow-back icon-xs"></i>';
	 
	public function render() {
		$total = $this->total;
		
		if ($this->page < 1) {
			$page = 1;
		} else {
			$page = $this->page;
		}
		
		if (!(int)$this->limit) {
			$limit = 10;
		} else {
			$limit = $this->limit;
		}
		
		$num_links = $this->num_links;
		$num_pages = ceil($total / $limit);
		
		$output = '';
		
		if ($page > 1) {
			$output .= '<a class="btn btn-icon btn-sm btn-light mr-2 my-1" href="' . str_replace('{page}', $page - 1, $this->url) . '">' . $this->text_prev . '</a>';
		}

		if ($num_pages > 1) {
			if ($num_pages <= $num_links) {
				$start = 1;
				$end = $num_pages;
			} else {
				$start = $page - floor($num_links / 2);
				$end = $page + floor($num_links / 2);
			
				if ($start < 1) {
					$end += abs($start) + 1;
					$start = 1;
				}
						
				if ($end > $num_pages) {
					$start -= ($end - $num_pages);
					$end = $num_pages;
				}
			}

			if ($start > 1) {
				$output .= '<li class="btn btn-icon btn-sm border-0 btn-light mr-2 my-1"><a href="javascript:;">...</a></li>';
			}

			for ($i = $start; $i <= $end; $i++) {
				if ($page == $i) {
					$output .= '<a class="btn btn-icon btn-sm border-0 btn-light btn-hover-primary active mr-2 my-1" href="javascript:;">' . $i . '</a>';
				} else {
					$output .= '<a class="btn btn-icon btn-sm btn-light mr-2 my-1" href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a>';
				}	
			}
							
			if ($end < $num_pages) {
				$output .= '<a class="btn btn-icon btn-sm border-0 btn-light mr-2 my-1" href="javascript:;">...</a>';
			}
		}
		
		if ($page < $num_pages) {
			$output .= '<a class="btn btn-icon btn-sm btn-light mr-2 my-1" href="' . str_replace('{page}', $page + 1, $this->url) . '">' . $this->text_next . '</a>';
		}
		return $output ? '<div class="d-flex justify-content-between align-items-center flex-wrap"><div class="d-flex flex-wrap py-2 mr-3">' . $output . '</div></div>' : '';
  	}
}
?>