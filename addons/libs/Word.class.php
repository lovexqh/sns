<?php
class word 
{  
	function start(){ 
		ob_start(); 
		echo '<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40"><head><xml><w:WordDocument><w:View>Print</w:View></w:WordDocument></xml><meta http-equiv="Content-Type" content="text/html; charset=utf-8">'; 
		echo '<style>
@page WordSection1
	{size:595.3pt 841.9pt;
	margin:2.0cm 2.0cm 2.0cm 2.0cm;
	mso-header-margin:42.55pt;
	mso-footer-margin:49.6pt;
	mso-paper-source:0;
	layout-grid:15.6pt;}
div.WordSection1{page:WordSection1;}
div.WordSection1 td{padding:5pt;font-size:12.0pt;font-family:
宋体;}
</style></head><body lang=ZH-CN link=blue vlink=purple style="tab-interval:21.0pt;text-justify-trim:
punctuation"><div class=WordSection1 style="layout-grid:15.6pt">';	
	} 
	function save($path) { 
  		echo '</div></body></html>';  
		$data = ob_get_contents(); 
		ob_end_clean();
		$this->wirtefile ($path,$data); 
	} 
  
	function wirtefile ($fn,$data) { 
		$fp=fopen($fn,"wb"); 
		fwrite($fp,$data); 
		fclose($fp); 
	} 
} 
?>