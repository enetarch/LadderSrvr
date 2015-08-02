<?
/*	=======================================
	Copyright 1998 .. 2015 - E Net Arch
		www.ENetArch.net - 248-686-1407
		ENetArch on AIM, Yahoo, and Skype

	This program is distributed under the terms of the GNU 
	General Public License (or the Lesser GPL).
	======================================= */

namespace ENetArch\Ladder\OSQL\VM;

use \Enetarch\Ladder\OSQL\Parser;
use \ENetArch\Ladder\OSQL\Parser\miniParser;
use \ENetArch\Ladder\OSQL\Parser\osqlParser;

use \ENetArch\Ladder\Server\Globals;

class exec
{
	private $osql = [];
	private $bVerbose = false;

	function process ($szOSQL, $bVerbose)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		print ("szOSQL = " . $szOSQL . "<P>");
		
		$this->bVerbose = $bVerbose;
		$objParser = new osqlParser ();

		$objParser->process ($szOSQL);
		if (gblError()->No() != 0) 
			return;
		
		$this->osql = $objParser->stateData;
		$this->print_vmVar_Array ("exec.osql", $this->osql);
		
		$szCommand = $this->osql ["command"];
		if (is_array ($szCommand))
			$szCommand = array_shift ($this->osql ["command"]);
			
		$results = [];
		
		switch ($szCommand)
		{
			case "SELECT" : $results = $this->cmdSelect (); break;
			case "UPDATE" : $results = $this->cmdUpdate (); break;
			case "DELETE" : $results = $this->cmdDelete (); break;
			case "INSERT" : $results = $this->cmdInsert (); break;
			
			case "CREATE" : $results = $this->cmdCreate (); break;
			case "ALTER" : $results = $this->cmdAlter (); break;
			case "DROP" : $results = $this->cmdDrop (); break;
			
			case "MOVE" : $results = $this->cmdMove (); break;
			case "COPY" : $results = $this->cmdCopy (); break;
			case "DUPE" : $results = $this->cmdDupe (); break;
		}

		return ($results);
	}

	// =====================================================================
	// =====================================================================

	function getTarget ($szPath)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		// $this->print_vmVar_Var ("szPath", $szPath);
		// $this->print_vmVar_Var ("strpos (szPath, *)", strpos ($szPath, "*"));
		
		if (is_numeric (strpos ($szPath, "\\")))
		{ $target = gblLadder()->getPath ($szPath); }
		else
		{ $target = gblLadder()->getItem ($szPath); }
		
		$this->print_vmVar_Array ("target", $target);
		
		return ($target);
	}

	function getFrom ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szFrom = $this->osql ["from"];
		// $this->print_vmVar_Array ("szFrom", $szFrom);
		
		$szPath = $szFrom ["Path"];
		// $this->print_vmVar_Var ("szPath", $szPath);
		
		$target = $this->getTarget ($szPath);
		
		return ($target);
	}

	function getTo ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szTo = $this->osql ["to"];
		// $this->print_vmVar_Array ("szTo", $szTo);
		
		$szPath = $szTo ["Path"];
		// $this->print_vmVar_Var ("szPath", $szPath);
		
		$target = $this->getTarget ($szPath);		
		
		return ($target);
	}

	// =====================================================================
	// =====================================================================

	function cmdSearch ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szFrom = $this->osql ["from"];
		$szPath = $szFrom ["Path"];
		$parent = $this->getTarget ($szPath);
		
		$this->print_vmVar_Array ("parent", $parent);
		
		if ($parent == null) 
			return (null);
			
		if ($this->bVerbose)
		{
			$this->print_vmVar_Array ("gblLadder()", gblLadder());
			$this->print_vmVar_Array ("parent", $parent);
			$this->print_vmVar_Var ("parent.ID", $parent->GUID());
			$this->print_vmVar_Var ("parent.classname", $parent->ClassName());
		}
		
		$this->addClass ($parent->ClassName(), $szFrom["Alias"]);
		$this->addClass 
		(
			$this->osql ['using']["Class"], 
			$this->osql ['using']["Alias"]
		);

		$lParam = 
			[
				"Class" => $parent->ClassName(), 
				"Alias" => $szFrom["Alias"]
			];
			
		$rParam = $this->osql ['using'];

		if (count ($this->osql['where']) == 0)
			$this->addContains ($lParam, $rParam);
		
		$this->addColumn ($lParam);
		$this->rows = [];
		$this->rows[] = [$parent];
		
		$this->where_clause ($this->osql['where']);
		
		$results = $this->getInstances ($rParam);
		
		if ($this->bVerbose)
			$this->print_vmVar_Array ("results", $results);

		if (is_array ($results))
		{ return ($results); }
		else
		{ return ($results->getItems()); }
	}
	
	function cmdSelect_Instances ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$results = $this->cmdSearch ();
		
		$results = $this->sort ($results);

		if ($this->bVerbose)
			$this->print_vmVar_Array ("results", $results);
			
		return ($results);
	}
	
	function cmdSelect_Fields ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$results = [];
		
		$instances = $this->cmdSelect_Instances ();
		$fields = $this->osql ["fields"];
		
		forEach ($instances AS $key => $element)
		{
			$row = [];
			
			forEach ($fields AS $key => $field)
			{
				$name = $field ["Field"];
				$row[$name] = $this->getField ($element, $name);
			}
			
			$results [] = $row;
		}
		
		return ($results);
	}
	
	function cmdSelect_Structure ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szClass = $this->osql ["structure"];
		
		$structure = gblLadder()->getStructure ($szClass);
		
		return ($structure);
	}
	
	function cmdSelect ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$results = [];

		$szCommand = $this->getNextCommand();

		// $this->print_vmVar_Var ("szCommand - 2", $szCommand);
		
		switch ($szCommand)
		{
			case "INSTANCES": $results = $this->cmdSelect_Instances (); break;
			case "FIELDS": $results = $this->cmdSelect_Fields (); break;
			
			case "STRUCTURE": $results = $this->cmdSelect_Structure (); break;
		}
		
		return ($results);
	}

	// =====================================================================

	function getNextCommand ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szCommand = $this->osql ["command"];
		if (is_array ($szCommand))
			$szCommand = array_shift ($this->osql ["command"]);
		else
			$this->osql ["command"] = "";
		
		return ($szCommand);
	}
	
	function cmdUpdate ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		print ("========================================================<BR>");
		print ("cmdUpdate .. " . __LINE__ . "<BR>");

		$szCommand = $this->getNextCommand();
		
		if ($szCommand == "IN")
		{ $results = $this->cmdSearch (); }
		else
		{ $results[] = $this->getFrom(); }

		// ==============================================
		// Update

		$fieldsets = $this->osql ["set"];

		$this->print_vmVar_Array ("results", $results);
		$this->print_vmVar_Array ("fieldsets", $fieldsets);

		forEach ($results AS $key => $item)
		{
			$this->currentElement = $item;
			
			forEach ($fieldsets AS $key => $fields)
			{
				$field = $fields ["field"];
				$value = $fields ["value"];
				
				$this->print_vmVar_Array ("field", $field );
				$this->print_vmVar_Array ("value", $value );
				
				$szField = $field["Field"];
				
				$szValue = $this->calcExpr ($value);
				
				$this->setField ($item, $szField, $szValue);
			}
			$item->store();
		}
	}

	// =====================================================================

	function cmdDelete ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szCommand = $this->getNextCommand();
		
		if ($szCommand == "FROM")
		{ $results = $this->cmdSearch (); }
		else
		{ $results[] = $this->getFrom(); }
		
		$this->print_vmVar_Var ("count (found)", count ($results) );
		
		if (count ($results) == 0)
			return;
			
		// ==============================================
		// Delete

		forEach ($results AS $key => $item)
			$item->Delete();
	}

	// =====================================================================

	function cmdInsert ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szCommand = $this->getNextCommand();
		
		$parent = $this->getFrom ();
		
		if ($parent == null) 
			return (null);
			
		if ($this->bVerbose)
		{
			$this->print_vmVar_Array ("gblLadder()", gblLadder());
			$this->print_vmVar_Array ("parent", $parent);
			$this->print_vmVar_Var ("parent.ID", $parent->GUID());
			$this->print_vmVar_Var ("parent.classname", $parent->ClassName());
		}

		$szClass = $this->osql ["using"] ["Class"];
		$fieldnames = $this->osql ["fields"];
		$fieldsets = $this->osql ["data"];
		
		forEach ($fieldsets AS $key => $data)
			$child = $this->create_Child ($parent, $szClass, $fieldnames, $data);
	}

	function create_Child ($parent, $szClass, $fieldnames, $data)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$class = gblLadder()->getClass ($szClass);
		$definition = $class->getItem ("", "Ladder_Defination");
		$basetype = $definition->Field ("nBaseType");
		
		$szName = array_shift ($data); array_shift ($fieldnames);
		$szDescription = array_shift ($data); array_shift ($fieldnames);
		
		$newItem = null;
		
		switch ($basetype)
		{
			case Globals::cisFolder() :
			$newItem = $parent->create_Folder ($szName, $szDescription, $szClass);
			break;
			
			case Globals::cisItem() :
			$newItem = $parent->create_Item ($szName, $szDescription, $szClass);
			$this->update_fields ($newItem, $fieldnames, $data);
			break;
		
			case Globals::cisReference() :
			$link = array_shift ($data); array_shift ($fieldnames);
			$newItem = $parent->create_Reference ($szName, $szDescription, $szClass, $link);
			break;
			
			default: 
			print ("class " . $szClass . " is the wrong basetype " . $class->BaseType() . "<BR>");
			return;
		}
		
		$this->print_vmVar_Array ("newItem", $newItem);
		$newItem->Store();
	}
	
	function update_fields ($newItem, $names, $data)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$structure = $newItem->getStructure();
		
		// $this->print_vmVar_Array ("structure", $structure);
		// $this->print_vmVar_Array ("names", $names);
		
		reset($names);
		
		$t = 0;
		forEach ($names AS $key => $name)
			if ($name != "ID")
			{
				$value = (isset ($data [$t]) ? $data [$t] : "");
				
				$this->print_vmVar_Var ("field", $name . " = " . $value);
				
				$newItem->setField ($name, $value);
				$t++;
			}
	}
	
	// =====================================================================

	function cmdCreate ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szCommand = $this->getNextCommand();
	}
	
	function cmdAlter ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szCommand = $this->getNextCommand();
	}
	
	function cmdDrop ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szClass = $this->osql ["drop"];
		
		$target = gblLadder()->getClass ($szClass);
		
		// Note .. should check to see if class is used in Ladder
		// and through error if it is.
		
		$target->Delete();
	}

	// =====================================================================

	function cmdMove ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szFrom = $this->osql ["from"];
		$target = gblLadder()->getPath ($szFrom ["Path"]);
		
		
		$szTo = $this->osql ["from"];
		$to = gblLadder()->getPath ($szTo ["Path"]);
		
		$parent->Move ($to);
	}

	function cmdCopy ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szFrom = $this->osql ["from"];
		$target = gblLadder()->getPath ($szFrom ["Path"]);
		
		
		$szTo = $this->osql ["from"];
		$to = gblLadder()->getPath ($szTo ["Path"]);
		
		$parent->Copy ($to);
	}

	function cmdDupe ()
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$szFrom = $this->osql ["from"];
		$target = gblLadder()->getPath ($szFrom ["Path"]);
		
		
		$szTo = $this->osql ["from"];
		$to = gblLadder()->getPath ($szTo ["Path"]);
		
		$parent->Dupe ($to);
	}

	// =====================================================================
	// =====================================================================

	function addContains ($lParam, $rParam)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		print ("================================================ <BR>");
		print ("addContains");
		
		$this->print_vmVar_Array ("lParam", $lParam );
		$this->print_vmVar_Array ("rParam", $rParam );

		if (! isset ($this->osql ['contains']))
			$this->osql ['contains'] = [];
			
		forEach ($this->osql ['contains'] AS $key => $value)
			if ($value[1] == $lParam)
				if ($value[2] == $rParam)
					return;
	
		$this->osql ['contains'][] = [ "CONTAINS", $lParam, $rParam ];
		
		// check / add the contains test to the where clause if needed.
		
		$this->osql ['where'] = 
		[ 
			"nOP" => "CONTAINS", 
			"lParam" => $lParam, 
			"rParam" => $rParam 
		];
	}

	// =====================================================================

	function addClass ($szClass, $szAlias)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		print ("================================================ <BR>");

		print ("addClass " . $szClass . " [ " . $szAlias . " ] <BR>");
	
		forEach ($this->osql ['aliases'] AS $key => $class)
		{
			$this->print_vmVar_Array ("aliases[" . $key . "].class", $class );
			
			if 
			(
				($szClass == $class["Class"]) &&
				($szAlias == $class["Alias"]) 
			)
				return;
		}

		$this->osql ['aliases'][] = 
		[ 
			"Class" => $szClass, 
			"Alias" => $szAlias 
		];
	}

	function getClass ($alias)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		print ("================================================ <BR>");
		print ("getClass");
		
		$this->print_vmVar_Array ("getClass", $alias);
		
		forEach ($this->osql ['aliases'] AS $key => $value)
		{
			if 
			(
				($alias["Class"] == $class["Class"]) &&
				($alias["Alias"] == $class["Alias"]) 
			)
				return ($this->osql ['aliases'][$key]);
		}
	
		return (null);
	}

	function getClassName ($alias)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		print ("================================================ <BR>");
		print ("getClassName");

		$this->print_vmVar_Array ("getClassName", $alias);
	
		forEach ($this->osql ['aliases'] AS $key => $class)
		{
			if 
			(
				($alias["Class"] == $class["Class"]) &&
				($alias["Alias"] == $class["Alias"]) 
			)
			{
				$this->print_vmVar_Array ("returning", $class);

				return ($class["Class"]);
			}
		}
	
		return (null);	
	}

	// =====================================================================
 
	function getField ($element, $field)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		$value = "";
		
		switch (strtoupper ($field))
		{
			case "ID" : $value = $element->getID(); break;
			case "GUID" : $value = $element->getGUID(); break;
			case "NAME" : $value = $element->getName(); break;
			case "DESCRIPTION" : $value = $element->getDescription(); break;
			case "PARENT" : $value = $element->getParentID(); break;
			case "BASETYPE" : $value = $element->getBasType(); break;
			case "CLASS" : $value = $element->getClassName(); break;
			case "CREATED" : $value = $element->getCreated(); break;
			case "UPDATED" : $value = $element->getUpdated(); break;
			case "REFERENCE" : $value = $element->getReference(); break;
			case "PATH" : $value = $element->getPath(); break;
			
			default: 
				if ($element->isItem ())
					$value = $element->getField ($field);
				// else
					// $this->genError ("Field [$field] not found in Element", $element);
		}
		
		return ($value);
	}

	function setField ($element, $field, $value)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		
		$this->print_vmVar_Array ("element", $element);
		$this->print_vmVar_Var ("field", $field);
		$this->print_vmVar_Var ("value", $value);
		
		switch (strtoupper ($field))
		{
			case "NAME" : $element->setName($value); break;
			case "DESCRIPTION" : $element->setDescription($value); break;
			
			case "REFERENCE" : 
				if ($element->isReference ())
					$value = $element->setLeaf ($value);
				else
					$this->getError ("Field [$field] not found");

			case "ID" : 
			case "GUID" : 
			case "PARENT" : 
			case "BASETYPE" : 
			case "CLASS" : 
			case "CREATED" : 
			case "UPDATED" : 
			case "Path" : $this->getError ("Field [$field] is read only");
			
			default: 
				if ($element->isItem ())
					$value = $element->setField ($field, $value);
				else
					$this->genError ("Field [$field] not found in Element", $element);
		}
	}

	// =================================================================
	// =================================================================

	private $rows = [];
	private $columns = [];
	private $currentRow = [];

	function addColumn ($alias)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		$this->print_vmVar_Array ("addColumn.alias", $alias);

		if ($this->getColumn ($alias) == -1)
			$this->columns[] = $alias; 
	}
	
	function getColumn ($alias)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		forEach ($this->columns as $key => $column)
		{
			$this->print_vmVar_Array ("getColumn.column", $column);
			
			if 
			(
				($alias["Class"] == $column ["Class"]) &&
				($alias["Alias"] == $column ["Alias"])
			)
				return ($key);
		}
		
		return (-1); // Not Found
	}

	// =================================================================
	// =================================================================
	// SELECT FIELDS count (*) ... WHERE a = max (b, c)
	// UPDATE ... SET a = max (b , c), d = d + 5
	
	function calcExpr ($expr) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		print (">>> ======================================== <BR>");
		
		$this->print_vmVar_Array ("expr", $expr);
		
		if (isset ($expr ["value"]))
			return ($expr ["value"]);
		
		if (isset ($expr ["Class"]))
		{
			// based on current set of elements, 
			// what is the value of the field
			// determine what element to work with based
			// on the class [alias]
			
			$this->print_vmVar_Array ("currerntRow", $this->currentRow);

			$nCol = $this->getColumn ($expr);
			
			$element = $this->currentRow [$nCol];
			
			if ($element->isItem())
			$this->print_vmVar_Array ("element.structure", $element->getStructure());
			
			
			$value = $this->getField ($element, $expr ["Field"]);
			
			return ($value);
		}	
				
		if (isset ($expr ["nOP"]))
		{
			$nOP = $expr ["nOP"];
			$lParam = isset ($expr["lParam"]) ? $expr ["lParam"] : "";
			$rParam = isset ($expr["rParam"]) ? $expr ["rParam"] : "";

			if ($this->bVerbose)
			{
				print ("nOP = " . $nOP . "<BR>");
				$this->print_vmVar_Array ("lParam", $lParam);
				$this->print_vmVar_Array ("rParam", $rParam);
			}
			
			$rtn = [];
		
			switch ($nOP)
			{
				case "AND": 
				{
					$lParam = $this->calcExpr ($lParam);
					$rParam = $this->expr ($rParam);

					if ($this->bVerbose)
					{
						print ("nOP = " . $nOP . "<BR>");
						print ("count (lParam) = " . count ($lParam) . "<BR>");
						print ("count (rParam) = " . count ($rParam) . "<BR>");
					}
								
					$rtn = $this->array_and ($lParam, $rParam);
					
				} break;
			
				case "OR": 
				{
					$lParam = $this->expr ($lParam);
					$rParam = $this->expr ($rParam);

					if ($this->bVerbose)
					{
						print ("nOP = " . $nOP . "<BR>");
						print ("count (lParam) = " . count ($lParam) . "<BR>");
						print ("count (rParam) = " . count ($rParam) . "<BR>");
					}
					
					$rtn = $this->array_or ($lParam, $rParam);

				} break;
			
				// =============================================================
			
			
				case "ALL" : 
				case "ANY" : 
				case "SOME" : 
				case "AVG" :
				case "MAX" : 
				case "MIN" : 
				case "SUM" : 
				case "COUNT" : 
				case "DISTINCT" : break;
			
				// =============================================================
			
				case "-" : 
					$rtn = $this->calcExpr($lParam) - $this->calcExpr ($rParam); 
					break;

				case "+" : 
					$rtn = $this->calcExpr($lParam) + $this->calcExpr($rParam); 
					break;

				case "*" :
					$rtn = $this->calcExpr($lParam) * $this->calcExpr($rParam); 
					break;

				case "/" : 
					$rtn = $this->calcExpr($lParam) / $this->calcExpr($rParam); 
					break;

				// =============================================================
			
				case "LIKE" : 
				case "=" : $rtn = ($lParam == $rParam); break;
				case ">" : $rtn = ($lParam > $rParam); break;
				case "<" : $rtn = ($lParam < $rParam); break;
				case ">=" : $rtn = ($lParam >= $rParam); break;
				case "<=" : $rtn = ($lParam <= $rParam); break;
				case "<>" :
				case "!=" : $rtn = ($lParam != $rParam); break;

				// =============================================================
			
				default : 
					$rtn = getValue ($lParam); 
					break;
			}
		
			if ($this->bVerbose)
			{
				print ("=================================================<BR>");
				print ("nOP = " . $nOP . "<BR>");
				print ("count (rtn) = " . count ($value) . "<BR>");

				if (count ($value) < 75)
				{ $this->print_vmVar_Array ("value", $value); }

				print ("=================================================<P>");
			}
		}
		
		return ($value);
	}

	// =====================================================================
	// =====================================================================
	function docFoo_where_clause ()
	{
		docFoo::docFoo 
		([
			"Method" => "where_clause",
			"Syntax" => "where_clause (expr)",
			"Parameters" => 
			[
				"expr" => "An array describing an expression.",
			],
			"Description" => "Evaluates a compound boolean expression",
			"Validations" => "",
			"Core Code" => "",
			"Returns" => "TRUE or FALSE",
			"Globals" => 
			[
				"osql" => "a JSON object describing the query being performed",
				"results" => "an Array of objects that match the where clause",
				
			],
			"Usage" => "",
			"Comments" => 
			"
				where (#1) .. [ AND, #2, #7 ]
					where (#2) .. [ AND, #3, #4 ]
						where (#3) .. [ CONTAINS, Rolodex, Person ]
						where (#4) .. [ AND, #5, #6 ]
							where (#3) .. [ CONTAINS, Person, Name ]
							where (#3) .. [ CONTAINS, Person, Address ]
					where (#3) .. [ AND, #8, #9 ]
						where (#3) .. [ =, name.first, 'Mike' ]
						where (#3) .. [ =, address.street, '725...' ]
			",
			"Testing" => "",
		]);
	}
    
	function where_clause ($clause) 
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		print (">>> ======================================== <BR>");
		
		$this->print_vmVar_Array ("clause", $clause);
		
		if (count ($clause) == 0)
			return;
			
		$nOP = $clause ["nOP"];
		$lParam = isset ($clause["lParam"]) ? $clause ["lParam"] : "";
		$rParam = isset ($clause["rParam"]) ? $clause ["rParam"] : "";

		$this->print_vmVar_Var ("nOP", $nOP);
		$this->print_vmVar_Array ("lParam", $lParam);
		$this->print_vmVar_Array ("rParam", $rParam);
		
		$rtn = false;
	
		switch ($nOP)
		{
			case "CONTAINS":  $this->addRows ($clause); break;
		
			// =========================================================
		
			case "AND": 
			{
				$lParam = $this->where_clause ($lParam);
				$rParam = $this->where_clause ($rParam);

				if ($this->bVerbose)
				{
					print ("nOP = " . $nOP . "<BR>");
					print ("count (lParam) = " . count ($lParam) . "<BR>");
					print ("count (rParam) = " . count ($rParam) . "<BR>");
				}
							
				$rtn = $lParam AND $rParam;
				
			} break;
		
			case "OR": 
			{
				$lParam = $this->where_clause ($lParam);
				$rParam = $this->where_clause ($rParam);

				if ($this->bVerbose)
				{
					print ("nOP = " . $nOP . "<BR>");
					print ("count (lParam) = " . count ($lParam) . "<BR>");
					print ("count (rParam) = " . count ($rParam) . "<BR>");
				}
				
				$rtn = $lParam OR $rParam;

			} break;
		
			// =========================================================
		
			case "NOT": 
			{
				$instances = $this->getInstances ($lParam);
				$field = $lParam[1];
				forEach ($instances AS $key => $item)
				{ 
					$data = $item->field ($field);
					// print ( "$field = " . $data . "<br>" );
					
					if (is_numeric ($data) )
					{
						if ($data == 0)
						$rtn [] = $item; 
					} 
					else if (is_bool ($data) )
					{
						if (! $data )
						$rtn [] = $item; 
					}
				}
			} break;
		
			// =========================================================
			
			case "IN": 
			{
				$instances = $this->getInstances ($lParam[0]);
				$field = $lParam[1];
				forEach ($instances AS $key => $item)
				{ 
					$data = $item->field ($field);
					if (in_array ($data, $rParam))
						$rtn [] = $item;
				}
			} break;
		
			// =========================================================
			
			case "IS": 
			case "ALL" : 
			case "ANY" : 
			case "SOME" : 
			case "AVG" :
			case "MAX" : 
			case "MIN" : 
			case "SUM" : 
			case "COUNT" : 
			case "DISTINCT" : break;
		
			// =========================================================
		
			case "LIKE": 
			case ">=" :
			case "<=" :
			case "=" :
			case ">" :
			case "<" :
			case "<>" :
			case "!=" : $this->pruneRows ($clause); break;

			// =========================================================
		
			default : 
				$rtn = $lParam; 
				break;
		}
	
		// =============================================================
		
		if ($this->bVerbose)
		{
			print ("=================================================<BR>");
			$this->print_vmVar_Var ("nOP", $nOP);
			$this->print_vmVar_Var ("rtn", $rtn);
			print ("=================================================<P>");
		}
		
		return ($rtn);
	}
	
	// =================================================================
	// =================================================================

	function addRows ($clause)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);

		print (">>> ======================================== <BR>");
		
		$this->print_vmVar_Array ("addRows.clause", $clause);

		$lParam = $clause ["lParam"];
		$rParam = $clause ["rParam"];
		$szClass = $rParam["Class"];
		
		$lpCol = $this->getColumn ($lParam);
		$rpCol = $this->addColumn ($rParam);
	
		$this->print_vmVar_Array ("lParam", $lParam);
		$this->print_vmVar_Var ("lpCol", $lpCol);
		$this->print_vmVar_Array ("columns", $this->columns);
		$this->print_vmVar_Array ("rows", $this->rows);
		
		$newRows = [];
		
		forEach ($this->rows AS $key => $row)
		{
			$parent = $row [$lpCol];
			$this->print_vmVar_Array ("parent", $parent);
		
			$children = $parent->getChildren ("", [$szClass]);
			$this->print_vmVar_Var ("children.count", $children->count());
				
			for ($t=1; $t<$children->count()+1; $t++)
			{
				$this->print_vmVar_Var ("t", $t);
				$child = $children->getItem ($t);
				$newRows [] = array_merge ($row, [$child]);
			}
		}
		
		$this->rows = $newRows;
		
		$this->print_vmVar_Array ("rows", $this->rows);
	}

	function pruneRows ($clause)
	{
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		print (">>> ======================================== <BR>");
		$this->print_vmVar_Array ("pruneRows.clause", $clause);
		
		$nOP = $clause ["nOP"];
		$lParam = $clause ["lParam"];
		$rParam = $clause ["rParam"];
		
		$newRows = [];
		
		forEach ($this->rows AS $key => $row)
		{
			$this->currentRow = $row;
			
			print (">>> ======================================== <BR>");
			$this->print_vmVar_Array ("pruneRows.clause", $clause);

			$lp = $this->calcExpr ($lParam);
			$rp = $this->calcExpr ($rParam);
			
			$this->print_vmVar_Var ("pruneRows.lp", $lp);
			$this->print_vmVar_Var ("pruneRows.rp", $rp);
			
			switch ($nOP)
			{
				case "LIKE": 
				case "=" : $bClip = ($lp == $rp); break;
				case "!=" : 
				case "<>" : $bClip = ($lp != $rp); break;
				case ">=" : $bClip = ($lp >= $rp); break;
				case "<=" : $bClip = ($lp <= $rp); break;
				case ">" : $bClip = ($lp > $rp); break;
				case "<" : $bClip = ($lp < $rp); break;
			}

			if ($bClip)
			{
				print (" >>>  Adding Row <<< <BR>");
				
				$newRows[] = $row;
			}
		}
		
		$this->rows = $newRows;
		
		$this->print_vmVar_Array ("rows", $this->rows);		
	}
	
	// =================================================================
	// =================================================================
	
	function sort ($results)
	{ 
		If (bDebugging) gblTraceLog()->Log ("Method", __METHOD__);
		
		if (count ($this->osql ['orderby']) == 0)
			return ($results); 
		
		// do something in here.
		
		return ($results);
	}

	// =================================================================
	// =================================================================
		
	function print_vmVar_Array ($name, $var)
	{
		print ("<P style='color:green;'>" . $name . " = <pre style='color:green;'>"); 
		print_r($var); 
		print ("</pre></P>");
	}

	function print_vmVar_Var ($name, $var)
	{
		print ("<P style='color:green;'>" . $name . " = ". $var . "</P>");
	}

	// =================================================================
	
	function genError ($txt, $element)
	{ 
		print ("<div style='color:red;'>");
		print ($txt . "<BR>");
		
		$this->print_vmVar_Array ("element", $element);
		$this->print_vmVar_Var ("isItem", $element->isItem() ? "TRUE" : "FASLE");
		
		if ($element->isItem())
			$this->print_vmVar_Array ("element.structure", $element->getStructure());

		print ("</div>");
		
		exit (); 
	}



}

?>
