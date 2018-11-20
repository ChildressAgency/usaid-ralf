<?php
/**
 * Class DocPropsDirAppStructureDocFile
 * @version 1.0.2
 * @author v.raskin
 * @package vsword.structure
*/
class DocPropsDirAppStructureDocFile  extends StructureDocFile {
	public function __construct() {
		$this->name = 'app.xml';
	}
	
	public function getContent() {
		return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes"><Template>Normal.dotm</Template><TotalTime>0</TotalTime><Pages>1</Pages><Words>0</Words><Characters>0</Characters><Application>Microsoft Office Word</Application><DocSecurity>0</DocSecurity><Lines>0</Lines><Paragraphs>0</Paragraphs><ScaleCrop>false</ScaleCrop><Company></Company><LinksUpToDate>false</LinksUpToDate><CharactersWithSpaces>0</CharactersWithSpaces><SharedDoc>false</SharedDoc><HyperlinksChanged>false</HyperlinksChanged><AppVersion>14.0000</AppVersion></Properties>';
	}
}