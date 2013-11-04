<?php
class TrailingLayout implements PageLayout {

	public function fetchPagedLinks($baselink, $parent, $queryVars,$directions ) {
	
		$currentPage = $parent->getPageNumber();
		$totalPages = $parent->fetchNumberPages();
		$str = "";

		if($totalPages >= 1) {
		
			for($i = 1; $i <= $totalPages; $i++) {
		
				$str .= " <a href=\"$baselink/{$i}$queryVars\"> $i</a>";
				$str .= $i != $totalPages ? " | " : "";
			}
		}

		return $str;
	}
}
?>