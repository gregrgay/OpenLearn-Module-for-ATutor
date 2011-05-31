
<?php

class Search {
    function execute($sql) {
        $sql = trim($sql);
        global $db;
        $result = mysql_query($sql, $db) or die($sql . "<br />". mysql_error());

        // for 'select' SQL, return retrieved rows
        if (strtolower(substr($sql, 0, 6)) == 'select') {
            if (mysql_num_rows($result) > 0) {
                for($i = 0; $i < mysql_num_rows($result); $i++) {
                    $rows[] = mysql_fetch_assoc($result);
                }
                mysql_free_result($result);
                return $rows;
            } else {
                return false;
            }
        }
        else {
            return true;
        }
    }

    public function getSearchResult($keywords) {
        $keywords = trim($keywords);
        //list($sql_where, $sql_order) = $this->getSearchSqlParams($keywords);

        //if ($sql_where <> '') $sql_where = ' AND '. $sql_where;
		//define('AT_INCLUDE_PATH', '../../include/');
		//require (AT_INCLUDE_PATH.'vitals.inc.php');
        // sql search
        $sql = "SELECT DISTINCT title, description FROM ".TABLE_PREFIX."hello_world WHERE title like '%"
		.$keywords."%' OR description like '%".$keywords."%'";
        //if ($sql_where <> '') $sql .= $sql_where;

        //echo "<br/>".$sql."<br/>";
        return $this->execute($sql);
    }
    /*
	private function getSearchSqlParams($all_keywords)
	{
		if (!is_array($all_keywords) || count($all_keywords) == 0) return array();
		
		$sql_search_template = "(title like '%{KEYWORD}%' ".
		                "OR description like '%{KEYWORD}%' ".
		                "OR keywords like '%{KEYWORD}%' )";
						
		$sql_order_template = " 15* ((LENGTH(title) - LENGTH(REPLACE(lower(title),lower('{KEYWORD}'), ''))) / LENGTH(lower('{KEYWORD}'))) + ".
		                      " 12* ((LENGTH(description) - LENGTH(REPLACE(lower(description),lower('{KEYWORD}'), ''))) / LENGTH(lower('{KEYWORD}'))) + ".
		                      " 8* ((LENGTH(keywords) - LENGTH(REPLACE(lower(keywords),lower('{KEYWORD}'), ''))) / LENGTH(lower('{KEYWORD}'))) + ";
		
		// get all OR conditions
		$found_first_or_item = false;
		foreach ($all_keywords as $i => $keyword)
		{
			if ($keyword == 'OR')
			{
				// if the first keyword is "OR" without the leading keyword,
				// OR, the last keyword is "OR" without the following keyword,
				// remove this "OR"
				if ((!isset($all_keywords[$i-1]) && !$found_first_or_item) ||
				    !isset($all_keywords[$i+1]))
				{
					unset($all_keywords[$i]);
					continue;
				}
				
				// The first "OR" joins the 2 keywords around it, 
				// the following "OR" only needs to join the keyword followed.
				// Removed the keywords that have been pushed into OR sql from 
				// the keywords array. 
				if (!$found_first_or_item)
				{
					$found_first_or_item = true;
					$sql_where_or .= str_replace('{KEYWORD}', $all_keywords[$i-1], $sql_search_template) .
					                 ' OR '. 
					                 str_replace('{KEYWORD}', $all_keywords[$i+1], $sql_search_template);
					$sql_order_or .= str_replace('{KEYWORD}', $all_keywords[$i-1], $sql_order_template) .
					              ' + '.
					              str_replace('{KEYWORD}', $all_keywords[$i+1], $sql_order_template);
					unset($all_keywords[$i-1]);  // the keyword before "OR"
					unset($all_keywords[$i]);    // "OR"
					unset($all_keywords[$i+1]);  // the keyword after "OR"
				}
				else
				{
					$sql_where_or .= ' OR '.str_replace('{KEYWORD}', $all_keywords[$i+1], $sql_search_template);
					$sql_order_or .= ' + '.str_replace('{KEYWORD}', $all_keywords[$i+1], $sql_order_template);
					unset($all_keywords[$i]);   // "OR"
					unset($all_keywords[$i+1]); // the keyword after "OR"
				}
			}
		}
		
		// the left-over in $all_keywords array is "AND" condition
		if (count($all_keywords) > 0)
		{
			foreach ($all_keywords as $keyword)
			{
				$sql_where .= str_replace('{KEYWORD}', $keyword, $sql_search_template). ' AND ';
				$sql_order .= str_replace('{KEYWORD}', $keyword, $sql_order_template). ' + ';
			}
		} 
		if ($sql_where_or == '') $sql_where = substr($sql_where, 0, -5);
		else $sql_where .= "(".$sql_where_or.")";
		
		if ($sql_order_or == '') $sql_order = substr($sql_order, 0, -3);
		else $sql_order .= $sql_order_or;
		
		return array($sql_where, $sql_order);
	}
    */
}

?>