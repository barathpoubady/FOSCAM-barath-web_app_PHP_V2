<?php

class Template
	{
		
	var $classname = "Template";

	/* if set, echo assignments */
	var $debug		 = false;

	/* $file[handle] = "filename"; */
	var $file	= array();

	/* relative filenames are relative to this pathname */
	var $root	 = "";

	/* $varkeys[key] = "key"; $varvals[key] = "value"; */
	var $varkeys = array();
	var $varvals = array();

	/* "remove"	=> remove undefined variables
	 * "comment" => replace undefined variables with comments
	 * "keep"		=> keep undefined variables
	 */
	var $unknowns = "remove";
	
	/* "yes" => halt, "report" => report error, continue, "no" => ignore error quietly */
	var $halt_on_error	= "yes";
	
	/* last error message is retained here */
	var $last_error		 = "";

	var $type			= "{}";
	var $bloc_type		= array(0=>"<!--",1=>"-->");
	var $delimiteur		= array(0=>"BEGIN",1=>"END");
	/***************************************************************************/
	/* public: Constructor.
	 * root:		 template directory.
	 * unknowns: how to handle unknown variables.
	 */
	function Template($root = ".", $unknowns = "remove")
		{
		global $_CFG;
//		echo $root;
		$this->set_root($root);
		$this->set_unknowns($unknowns);
		}

	/* public: setroot(pathname $root)
	 * root:	 new template directory.
	 */	
	function set_root($root)
		{
		if (!is_dir($root))
			{
			$this->halt("set_root: $root n'est pas un repertoir.");
			return false;
			}
		$this->root = $root;
		return true;
		}

	/* public: set_unknowns(enum $unknowns)
	 * unknowns: "remove", "comment", "keep"
	 *
	 */
	function set_unknowns($unknowns = "keep")
		{
		$this->unknowns = $unknowns;
		}

		
		
	/* public: set_type(enum $type)
	 * type: "{}", "[]",
	 * Added method by Neo
	 */
	function set_type($type = "{}")
	{
		$this->type = $type;
	}
	
	/* public: set_bloc_type(array $bloc_type)
	 * type: 
	 * Added method by Neo
	 */
	function set_bloc_type($bloc_type)
	{
		$this->bloc_type = $bloc_type;
	}
	
	/* public: set_bloc_type(array $bloc_type)
	 * type: 
	 * Added method by Neo
	 */
	function set_delimiteur($delimiteur)
	{		
		$this->delimiteur = $delimiteur;
	}

		
	/* public: set_file(array $filelist)
	 * filelist: array of handle, filename pairs.
	 *
	 * public: set_file(string $handle, string $filename)
	 * handle: handle for a filename,
	 * filename: name of template file
	 */
	function set_file($handle, $filename = "")
		{
		if (!is_array($handle))
			{
			if ($filename == "")
				{
				$this->halt("set_file: For handle $handle filename is empty.");
				return false;
				}
			$this->file[$handle] = $this->filename($filename);
			}
		else
			{
			reset($handle);
			while(list($h, $f) = each($handle))
				{
				$this->file[$h] = $this->filename($f);
				}
			}
		}
	
/* fonctions perso pour chercher les pubs non-définies */
	function get_pub()
		{
		$pubIndefini = array();
		while (list($key, $val) = each($this->listeFichier))
			{
			while (list($k, $v) = each($this->get_undefined($val)))
				{
				if (eregi("PUB_[[:digit:]]{1,3}\.[[:digit:]]", $v))
					{
					$pubIndefini[] = $v;
					}
				}
			}
		return $pubIndefini;
		}
	/* public: set_block(string $parent, string $handle, string $name = "")
	 * extract the template $handle from $parent, 
	 * place variable {$name} instead.
	 */
	function set_block($parent, $handle, $name = "")
			{
		if (!$this->loadfile($parent))
			{
			$this->halt("subst: Imposible de charger $parent.");
			return false;
			}
		if ($name == "") $name = $handle;
		$str = $this->get_var($parent);
//		print_r($this->bloc_type);
		
		$reg = "/".$this->bloc_type[0]."\s+".$this->delimiteur[0]." $handle\s+".$this->bloc_type[1]."(.*)\s*".$this->bloc_type[0]."\s+".$this->delimiteur[1]." $handle\s+".$this->bloc_type[1]."/Usm";
//		echo $reg."\n";
		preg_match_all($reg, $str, $m);
		$str = preg_replace($reg, $this->type[0].$name.$this->type[1], $str);
		@$this->set_var($handle, $m[1][0]);
		$this->set_var($parent, $str);
		}
	
	// Rajout

	function empty_block($parent, $handle)
		{
		if (!$this->loadfile($parent))
		 	{
			$this->halt("subst: unable to load $parent.");
			return false;
			}
		if (is_array($handle))
			{
			while(list($k, $v) = each($handle))
				{
				$reg = "/".$this->bloc_type[0]."\s+".$this->delimiteur[0]." $v\s+".$this->bloc_type[1]."(.*)\s+".$this->bloc_type[0]."\s+".$this->delimiteur[1]." $v\s+".$this->bloc_type[1]."/sm";
				$str = $this->get_var($parent);
				$str = preg_replace($reg, "", $str);
				$this->set_var($parent, $str);
				}
			}
		else
			{
			$reg = "/".$this->bloc_type[0]."\s+".$this->delimiteur[0]." $handle\s+".$this->bloc_type[1]."(.*)\s+".$this->bloc_type[0]."\s+".$this->delimiteur[1]." $handle\s+".$this->bloc_type[1]."/sm";
			$str = $this->get_var($parent);
			$str = preg_replace($reg, "", $str);
			$this->set_var($parent, $str);
			}
		}
	
	/* public: set_var(array $values)
	 * values: array of variable name, value pairs.
	 *
	 * public: set_var(string $varname, string $value)
	 * varname: name of a variable that is to be defined
	 * value:	 value of that variable
	 */
	function set_var($varname, $value = "")
		{
		if (!is_array($varname))
			{
			if (!empty($varname))
				if ($this->debug) print "scalar: set *$varname* to *$value*<br>\n";
				$this->varkeys[$varname] = "/".$this->varname($varname)."/";
				$this->varvals[$varname] = $value;
			}
		else
			{
			$nb_args = @func_num_args();
			for ($i = 0; $i < $nb_args; $i ++)
				{
				$arg = func_get_arg($i);
				if (is_array($arg))
					{
					reset($arg);
					foreach($arg as $k => $v)
						{
						if (!empty($k))
						if ($this->debug) print "array: set *$k* to *$v*<br>\n";
						$this->varkeys[$k] = "/".$this->varname($k)."/";
						$this->varvals[$k] = $v;
						}
					}
				}
			}
		}

	/* public: subst(string $handle)
	 * handle: handle of template where variables are to be substituted.
	 */
	function subst($varname)
		{
		$varvals_quoted = array();
		if (!$this->loadfile($varname))
			{
			$this->halt("subst: imposible de charger $varname.");
			return false;
			}
		
		// quote the replacement strings to prevent bogus stripping of special chars
		reset($this->varvals);
		while(list($k, $v) = each($this->varvals))
			{ $varvals_quoted[$k] = @preg_replace(array('/\\\\/', '/\$/'), array('\\\\\\\\', '\\\\$'), $v); }
		
		$str = $this->get_var($varname);
		$str = @preg_replace($this->varkeys, $varvals_quoted, $str);
		return $str;
		}
	/* public: psubst(string $handle)
	 * handle: handle of template where variables are to be substituted.
	 */
	function psubst($handle)
		{
		print $this->subst($handle);
		
		return false;
		}

	/* public: parse(string $target, string $handle, boolean append)
	 * public: parse(string $target, array	$handle, boolean append)
	 * target: handle of variable to generate
	 * handle: handle of template to substitute
	 * append: append to target handle
	 */
	function parse($target, $handle, $append = false, $rmvBlock = array())
		{
			
		
			
			
		if (!is_array($handle))
			{
			$str = $this->subst($handle);
			if (is_array($rmvBlock))
				{
				foreach($rmvBlock as $v)
					{
//					$reg = "/<!--\s+BEGIN $v\s+-->(.*)\n\s*<!--\s+END $v\s+-->/sm";
					$reg = "/".$this->bloc_type[0]."\s+".$this->delimiteur[0]." $v\s+".$this->bloc_type[1]."(.*)".$this->bloc_type[0]."\s+".$this->delimiteur[1]." $v\s+".$this->bloc_type[1]."/Usm";
					$str = preg_replace($reg, "", $str);
					}
				}
			if ($append)
				$this->set_var($target, $this->get_var($target) . rtrim($str));
			else
				$this->set_var($target, $str);
			}
		else
			{
			reset($handle);
			while(list($i, $h) = each($handle))
				{
				$str = $this->subst($h);
				$this->set_var($target, $str);
				}
			}
			
//			$reg = "/<!-- BEGIN ([^-]*) -->(.*)\s+<!-- END [^-]* -->/sm";
//			preg_match_all($reg,$this->varvals[$handle],$Treg);
//			echo $target."<br />";
//			print_r($Treg);
//			preg_match_all($reg,$this->varkeys[$handle],$Treg);
//			echo $target."<br />";
//			print_r($Treg);
//			echo "<br />";
		
		return $str;
		}
	
	function pparse($target, $handle, $append = false)
		{
		print $this->parse($target, $handle, $append);
		return false;
		}
	
	/* public: get_vars()
	 */
	function get_vars()
		{
		reset($this->varkeys);
		while(list($k, $v) = each($this->varkeys))
			{
			$result[$k] = $this->varvals[$k];
			}
		return $result;
		}
	
	/* public: get_var(string varname)
	 * varname: name of variable.
	 *
	 * public: get_var(array varname)
	 * varname: array of variable names
	 */
	function get_var($varname)
		{
		if (!is_array($varname))
			{
			if (isset($this->varvals[$varname])) return $this->varvals[$varname];
			}
		else
			{
			reset($varname);
			while(list($k, $v) = each($varname))
				{
				$result[$k] = $this->varvals[$k];
				}
			return $result;
			}
		}
	
	/* public: get_undefined($handle)
	 * handle: handle of a template.
	 */
	function get_undefined($handle)
		{
		if (!$this->loadfile($handle))
			{
			$this->halt("get_undefined: imposible de charger $handle.");
			return false;
			}
			
		$tag[0] = $this->type[0];	
		$tag[1] = $this->type[1];	
		preg_match_all("/".$tag[0]."([^".$tag[1]."]+)".$tag[1]."/", $this->get_var($handle), $m);
		$m = $m[1];
		if (!is_array($m))
			return false;

		reset($m);
		while(list($k, $v) = each($m))
			{
			if (!isset($this->varkeys[$v]))
				$result[$v] = $v;
			}
		
		if (count($result))
			return $result;
		else
			return false;
		}

	/* public: finish(string $str)
	 * str: string to finish.
	 */
	function finish($str) {
		$tag[0] = $this->type[0];	
		$tag[1] = $this->type[1];
		
//		echobr($str);
		switch ($this->unknowns) {
			case "keep":
			break;
			
			case "remove":
				$str = preg_replace('/'.$tag[0].'[^ \t\r\n'.$tag[1].']+'.$tag[1].'/', "", $str);
//				$str = preg_replace("/(\x0d\x0a)+/", "\n", $str);
				$str = preg_replace("`\n\t*\n\t*\n`", "\n", $str);
				$str = preg_replace("/".$this->bloc_type[0]." [^-]* ".$this->bloc_type[1]."/", "", $str);
			break;
			case "removeVars":
				$str = preg_replace('/'.$tag[0].'[^ \t\r\n'.$tag[1].']+'.$tag[1].'/', "", $str);
//				$str = preg_replace("/(\x0d\x0a)+/", "\n", $str);
				$str = preg_replace("`\n\t*\n\t*\n`", "\n", $str);
			break;
		
			case "comment":
				$str = preg_replace('/{([^ \t\r\n}]+)}/', "<!-- Template $handle: Variable \\1 undefined -->", $str);
			break;
		}
		
//		echobr($str);
//		exit();
		return $str;
	}

	/* public: p(string $varname)
	 * varname: name of variable to print.
	 */
	function p($varname, $clean = true)
		{
		print $this->ToString($varname, $clean);
		}
	
	function ToString($varname, $clean = true)
		{
		$v = $this->get_var($varname);
		if ($clean)
			$v = preg_replace("/".$this->bloc_type[0].".*".$this->bloc_type[1]."/U", "", $v);
		return $this->finish($v);
		}
	
	function OutPut($varname, $filename, $clean = true)
		{
		$v = $this->get_var($varname);
		
		if ($clean) {
			$v = preg_replace("/".$this->bloc_type[0]." [^-]* ".$this->bloc_type[1]."/", "", $v);
		}
		$f = fopen($filename, "w");
		
		fputs($f, $this->finish($v));
		fclose($f);
		}
	
	function get($varname)
		{
		return $this->finish($this->get_var($varname));
		}
		
	/***************************************************************************/
	/* private: filename($filename)
	 * filename: name to be completed.
	 */
	function filename($filename)
		{
		if (substr($filename, 0, 1) != "/")
			{
			$filename = $this->root."/".$filename;
			}
		
		if (!file_exists($filename))
			$this->halt("Fichier Introuvable : le fichier $filename n'existe pas.");

		return $filename;
		}
	
	/* private: varname($varname)
	 * varname: name of a replacement variable to be protected.
	 */
	function varname($varname)
		{
		$tag[0] = $this->type[0];	
		$tag[1] = $this->type[1];	
		return preg_quote($tag[0].$varname.$tag[1]);
		}

	/* private: loadfile(string $handle)
	 * handle:	load file defined by handle, if it is not loaded yet.
	 */
	function loadfile($handle)
		{
		if (isset($this->varkeys[$handle]) and !empty($this->varvals[$handle]))
			return true;

		if (!isset($this->file[$handle]))
			{
			$this->halt("loadfile: $handle is not a valid handle.");
			return false;
			}
		$filename = $this->file[$handle];

		$str = implode("", @file($filename));
		if (empty($str))
			{
			$this->halt("loadfile: Erreur pendant le chargement de $handle, $filename est vide ou n'existe pas.");
			return false;
			}

		$this->set_var($handle, $str);
		
		return true;
		}

	/***************************************************************************/
	/* public: halt(string $msg)
	 * msg:		error message to show.
	 */
	function halt($msg)
	{
		    
		$this->last_error = $msg;	
		
		//$Erreur = newClass("erreur",0,"Template",$msg);	
		$this->haltmsg($msg);
		exit();
		// return false;
		
	}
	
	/* public, override: haltmsg($msg)
	 * msg: error message to show.
	 */
	function haltmsg($msg)
		{
		printf("<b>Erreur de template:</b> %s<br>\n", $msg);
		}
	}

?>