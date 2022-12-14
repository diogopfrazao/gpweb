<?php
/*
	File: tableUpdater.inc.php

	Contains a class that can be used to invoke DOM calls on the browser which
	will create or update an HTML table.

	Title: clsTableUpdater class

	Please see <copyright.inc.php> for a detailed description, copyright
	and license information.
*/

if (false == class_exists('xajaxPlugin') || false == class_exists('xajaxPluginManager'))
{
	$sBaseFolder = dirname(dirname(dirname(__FILE__)));
	$sXajaxCore = $sBaseFolder . '/xajax_core';
	
	if (false == class_exists('xajaxPlugin'))
		require $sXajaxCore . '/xajaxPlugin.inc.php';
	if (false == class_exists('xajaxPluginManager'))
		require $sXajaxCore . '/xajaxPluginManager.inc.php';
}

/*
	Class: clsTableUpdater
*/
class clsTableUpdater extends xajaxResponsePlugin
{
	/*
		String: sDefer
		
		Used to store the state of the scriptDeferral configuration setting.  When
		script deferral is desired, this member contains 'defer' which will request
		that the browser defer loading of the javascript until the rest of the page 
		has been loaded.
	*/
	public $sDefer;
	
	/*
		String: sJavascriptURI
		
		Used to store the base URI for where the javascript files are located.  This
		enables the plugin to generate a script reference to it's javascript file
		if the javascript code is NOT inlined.
	*/
	public $sJavascriptURI;
	
	/*
		Boolean: bInlineScript
		
		Used to store the value of the inlineScript configuration option.  When true,
		the plugin will return it's javascript code as part of the javascript header
		for the page, else, it will generate a script tag referencing the file by
		using the <clsTableUpdater->sJavascriptURI>.
	*/
	public $bInlineScript;
	
	/*
		Function: clsTableUpdater
		
		Constructs and initializes an instance of the table updater class.
	*/
	public function __construct()
	{
		$this->sDefer = '';
		$this->sJavascriptURI = '';
		$this->bInlineScript = true;
	}
	
	/*
		Function: configure
		
		Receives configuration settings set by <xajax> or user script calls to 
		<xajax->configure>.
		
		sName - (string):  The name of the configuration option being set.
		mValue - (mixed):  The value being associated with the configuration option.
	*/
	public function configure( $sName, $mValue)
	{
		if ('scriptDeferral' == $sName) {
			if (true === $mValue || false === $mValue) {
				if ($mValue) $this->sDefer = 'defer ';
				else $this->sDefer = '';
			}
		} else if ('javascript URI' == $sName) {
			$this->sJavascriptURI = $mValue;
		} else if ('inlineScript' == $sName) {
				if (true === $mValue || false === $mValue)
					$this->bInlineScript = $mValue;
			}
	}
	
	/*
		Function: generateClientScript
		
		Called by the <xajaxPluginManager> during the script generation phase.  This
		will either inline the script or insert a script tag which references the
		<tableUpdater.js> file based on the value of the <clsTableUpdater->bInlineScript>
		configuration option.
	*/
	public function generateClientScript()
	{
		if ($this->bInlineScript)
		{
			echo "\n<script type='text/javascript' " . $this->sDefer . "charset='UTF-8'>\n";
			echo "/* <![CDATA[ */\n";
			
			include(dirname(__FILE__) . '/tableUpdater.js');
			
			echo "/* ]]> */\n";
			echo "</script>\n";
		} else {
			echo "\n<script type='text/javascript' src='" . $this->sJavascriptURI . "tableUpdater.js' " . $this->sDefer . "charset='UTF-8'>\n";
		}
	}
	
	public function getName()
	{
		return get_class($this);
	}
	
	// tables
	public function appendTable( $table, $superior) {
		$command = array(
				'cmd'=>'et_at', 
				'id'=>$superior
				);
		$this->addCommand($command, $table);	
	}
	public function insertTable( $table, $superior, $position) {
		$command = array(
				'cmd'=>'et_it', 
				'id'=>$superior, 
				'pos'=>$position
				);
		$this->addCommand($command, $table);
	}
	public function deleteTable( $table) {
		$this->addCommand(
				array(
					'cmd'=>'et_dt'
					), 
				$table
				);
	}
	// rows
	public function appendRow( $row, $superior, $position = null) {
		$command = array(
				'cmd'=>'et_ar', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $row);
	}
	public function insertRow( $row, $superior, $position = null, $before = null) {
		$command = array(
				'cmd'=>'et_ir', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		if (null != $before)
			$command['type'] = $before;
		$this->addCommand($command, $row);
	}
	public function replaceRow( $row, $superior, $position = null, $before = null) {
		$command = array(
				'cmd'=>'et_rr', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		if (null != $before)
			$command['type'] = $before;
		$this->addCommand($command, $row);
	}
	public function deleteRow( $superior, $position = null) {
		$command = array(
				'cmd'=>'et_dr', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, null);
	}
	public function assignRow( $values, $superior, $position = null, $start_column = null) {
		$command = array(
				'cmd'=>'et_asr', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		if (null != $start_column)
			$command['type'] = $start_column;
		$this->addCommand($command, $values);
	}
	public function assignRowProperty( $property, $value, $superior, $position = null) {
		$command = array(
				'cmd'=>'et_asrp', 
				'id'=>$superior,
				'prop'=>$property
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $value);
	}
	// columns
	public function appendColumn( $column, $superior, $position = null) {
		$command = array(
				'cmd'=>'et_acol', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $column);
	}
	public function insertColumn( $column, $superior, $position = null) {
		$command = array(
				'cmd'=>'et_icol', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $column);
	}
	public function replaceColumn( $column, $superior, $position = null) {
		$command = array(
				'cmd'=>'et_rcol', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $column);
	}
	public function deleteColumn( $superior, $position = null) {
		$command = array(
				'cmd'=>'et_dcol', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, null);
	}
	public function assignColumn( $values, $superior, $position = null, $start_row = null) {
		$command = array(
				'cmd'=>'et_ascol', 
				'id'=>$superior
				);
		if (null != $position)
			$command['pos'] = $position;
		if (null != $start_row)
			$command['type'] = $start_row;
		$this->addCommand($command, $values);
	}
	public function assignColumnProperty( $property, $value, $superior, $position = null) {
		$command = array(
				'cmd'=>'et_ascolp', 
				'id'=>$superior,
				'prop'=>$property
				);
		if (null != $position)
			$command['pos'] = $position;
		$this->addCommand($command, $value);
	}
	public function assignCell( $row, $column, $value) {
		$this->addCommand(
				array(
					'cmd'=>'et_asc', 
					'id'=>$row, 
					'pos'=>$column
					), 
				$value
				);
	}
	public function assignCellProperty( $row, $column, $property, $value) {
		$this->addCommand(
				array(
					'cmd'=>'et_ascp', 
					'id'=>$row, 
					'pos'=>$column,
					'prop'=>$property
					), 
				$value
				);
	}
}

@$objPluginManager =& xajaxPluginManager::getInstance();
$objPluginManager->registerPlugin(new clsTableUpdater());
