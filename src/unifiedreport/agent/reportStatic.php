<?php
/*
 Author: Shaheem Azmal, anupam.ghosh@siemens.com
 Copyright (C) 2017, Siemens AG

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 version 2 as published by the Free Software Foundation.
 
 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 
 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

use PhpOffice\PhpWord\Element\Section;
use \PhpOffice\PhpWord\Shared\Html;
use \PhpOffice\PhpWord\Style;

class ReportStatic
{
  /** @var timeStamp */
  private $timeStamp;

  /** @var subHeadingStyle */
  private $subHeadingStyle = array("size" => 9,
                                   "align" => "center",
                                   "bold" => true
                                  );

  /** @var tablestyle */
  private $tablestyle = array("borderSize" => 2,
                              "name" => "Arial",
                              "borderColor" => "000000",
                              "cellSpacing" => 5
                             );
 
  function __construct($timeStamp) {
    $this->timeStamp = $timeStamp ?: time();
  }


  /**
   * @param Section $section 
   */
  function reportHeader(Section $section)
  {
    global $SysConf;
    $text = $SysConf['SYSCONFIG']["ReportHeaderText"];
    $headerStyle = array("color" => "009999", "size" => 20, "bold" => true);
    $header = $section->addHeader();
    $header->addText(htmlspecialchars($text), $headerStyle);
  }


  /**
   * @param PhpWord $phpWord
   * @param Section $section 
   */
  function reportFooter($phpWord, Section $section, $otherStatement)
  { 
    global $SysConf;

    $commitId = $SysConf['BUILD']['COMMIT_HASH'];
    $commitDate = $SysConf['BUILD']['COMMIT_DATE'];
    $styleTable = array('borderSize'=>10, 'borderColor'=>'FFFFFF' );
    $styleFirstRow = array('borderTopSize'=>10, 'borderTopColor'=>'000000');
    $phpWord->addTableStyle('footerTableStyle', $styleTable, $styleFirstRow);
    $footerStyle = array("color" => "000000", "size" => 9, "bold" => true);
    $footerTime = "Gen Date: ".date("Y/m/d H:i:s T", $this->timeStamp);
    $footerCopyright = $otherStatement['ri_footer']; 
    $footerSpace = str_repeat("  ", 7);
    $footerPageNo = "Page {PAGE} of {NUMPAGES}";
    $footer = $section->addFooter(); 
    $table = $footer->addTable("footerTableStyle");
    $table->addRow(200, $styleFirstRow);
    $table->addCell(15000,$styleFirstRow)->addPreserveText(htmlspecialchars("$footerCopyright $footerSpace $footerTime $footerSpace FOSSology Ver:#$commitId-$commitDate $footerSpace $footerPageNo"), $footerStyle); 
  }


  /**
   * @param Section $section 
   */ 
  function clearingProtocolChangeLogTable(Section $section)
  {
    $thColor = array("bgColor" => "E0E0E0");
    $thText = array("size" => 12, "bold" => true);
    $rowWidth = 600;
    $rowWidth1 = 200;
    $cellFirstLen = 2000;
    $cellSecondLen = 4500;
    $cellThirdLen = 9000;

    $heading = "Clearing Protocol Change Log";
    $section->addTitle(htmlspecialchars($heading), 2);

    $table = $section->addTable($this->tablestyle);

    $table->addRow($rowWidth);
    $cell = $table->addCell($cellFirstLen, $thColor)->addText(htmlspecialchars("Last Update"), $thText);
    $cell = $table->addCell($cellSecondLen, $thColor)->addText(htmlspecialchars("Responsible"), $thText);
    $cell = $table->addCell($cellThirdLen, $thColor)->addText(htmlspecialchars("Comments"), $thText);

    $table->addRow($rowWidth1);
    $cell = $table->addCell($cellFirstLen);
    $cell = $table->addCell($cellSecondLen);
    $cell = $table->addCell($cellThirdLen);

    $section->addTextBreak();
  }


  /**
   * @param Section $section 
   */ 
  function assessmentSummaryTable(Section $section, $otherStatement)
  {          
    $heading = "Assessment Summary";
    $infoText = "The following table only contains significant obligations, restrictions & risks for a quick overview – all obligations, restrictions & risks according to Section 3 must be considered.";
    
    $thColor = array("bgColor" => "E0E0E0");  
    $infoTextStyle = array("size" => 10, "color" => "000000");
    $leftColStyle = array("size" => 11, "color" => "000000","bold" => true);
    $firstRowStyle1 = array("size" => 10, "bold" => true);
    $rightColStyleBlue = array("size" => 11, "color" => "0000A0","italic" => true);
    $rightColStyleBlack = array("size" => 11, "color" => "000000");
    $rightColStyleBlackWithItalic = array("size" => 11, "color" => "000000","italic" => true);
    
    $cellRowSpan = array("vMerge" => "restart", "valign" => "top");
    $cellRowContinue = array("vMerge" => "continue");    
    $cellColSpan = array("gridSpan" => 4);
    $cellColSpan2 = array("gridSpan" => 3);

    $rowWidth = 200;
    $rowWidth2 = 300;
    $cellFirstLen = 4300;
    $cellSecondLen = 4300;
    $cellThirdLen = 2300;
    $cellFourthLen = 2300;
    $cellFifthLen = 2300;
    $cellLen = 10000;

    // TODO: checkedBoxes need to check
    // $getCheckboxList = explode(',', $otherStatement['ri_ga_checkbox_selection']);   //get checkbox status from db

    $section->addTitle(htmlspecialchars($heading), 2);
    $section->addText(htmlspecialchars($infoText), $infoTextStyle);

    $table = $section->addTable($this->tablestyle);

    $table->addRow($rowWidth);
    $table->addCell($cellFirstLen)->addText(htmlspecialchars(" General assessment"), $leftColStyle, "pStyle");
    $table->addCell($cellLen)->addText(htmlspecialchars($otherStatement['ri_general_assesment']), $rightColStyleBlue, "pStyle");

    $table->addRow($rowWidth);
    $table->addCell($cellFirstLen)->addText(htmlspecialchars(" "), $leftColStyle, "pStyle");
    $table->addCell($cellLen)->addText(htmlspecialchars(" "), $rightColStyleBlue, "pStyle");

    $table->addRow($rowWidth);
    $table->addCell($cellFirstLen)->addText(htmlspecialchars(" Source / binary integration notes"), $leftColStyle, "pStyle");
    $cell = $table->addCell($cellLen);
    $cell->addCheckBox("nocriticalfiles", htmlspecialchars(" no critical files found, source code and binaries can be used as is"), $rightColStyleBlackWithItalic, "pStyle");
    $cell->addCheckBox("criticalfiles", htmlspecialchars(" critical files found, source code needs to be adapted and binaries possibly re-built"), $rightColStyleBlackWithItalic, "pStyle");

    $table->addRow($rowWidth);
    $table->addCell($cellFirstLen)->addText(htmlspecialchars(" Dependency notes"), $leftColStyle, "pStyle");
    $cell = $table->addCell($cellLen);
    $cell->addCheckBox("nodependenciesfound", htmlspecialchars(" no dependencies found, neither in source code nor in binaries"), $rightColStyleBlackWithItalic, "pStyle");
    $cell->addCheckBox("dependenciesfoundinsourcecode", htmlspecialchars(" dependencies found in source code (see obligations)"), $rightColStyleBlackWithItalic, "pStyle");
    $cell->addCheckBox("dependenciesfoundinbinaries", htmlspecialchars(" dependencies found in binaries (see obligations)"), $rightColStyleBlackWithItalic, "pStyle");

    $table->addRow($rowWidth);
    $table->addCell($cellFirstLen)->addText(htmlspecialchars(" Export restrictions by copyright owner"), $leftColStyle, "pStyle");
    $cell = $table->addCell($cellLen);
    $cell->addCheckBox("noexportrestrictionsfound", htmlspecialchars(" no export restrictions found"), $rightColStyleBlackWithItalic, "pStyle");
    $cell->addCheckBox("exportrestrictionsfound", htmlspecialchars(" export restrictions found (see obligations)"), $rightColStyleBlackWithItalic, "pStyle");

    $table->addRow($rowWidth);
    $table->addCell($cellFirstLen)->addText(htmlspecialchars(" Restrictions for use (e.g. not for Nuclear Power) by copyright owner"), $leftColStyle, "pStyle");
    $cell = $table->addCell($cellLen);
    $cell->addCheckBox("norestrictionsforusefound", htmlspecialchars(" no restrictions for use found"), $rightColStyleBlackWithItalic, "pStyle");
    $cell->addCheckBox("restrictionsforusefound", htmlspecialchars(" restrictions for use found (see obligations)"), $rightColStyleBlackWithItalic, "pStyle");

    $table->addRow($rowWidth, "pStyle");
    $table->addCell($cellFirstLen)->addText(htmlspecialchars(" Additional notes"), $leftColStyle, "pStyle");
    $cell = $table->addCell($cellLen)->addText(htmlspecialchars($otherStatement['ri_ga_additional']), $rightColStyleBlue, "pStyle");

    $table->addRow($rowWidth);
    $cell = $table->addCell($cellFirstLen)->addText(htmlspecialchars(" General Risks (optional)"), $leftColStyle, "pStyle");
    $cell = $table->addCell($cellLen)->addText(htmlspecialchars($otherStatement['ri_ga_risk']), $rightColStyleBlue, "pStyle");

    $section->addTextBreak();
  }


  /**
   * @param Section $section 
   */ 
  function todoTable(Section $section)
  {
    global $SysConf;
    $text1 = $SysConf['SYSCONFIG']["CommonObligation"];
    $text2 = $SysConf['SYSCONFIG']["AdditionalObligation"];

    $rowStyle = array("bgColor" => "E0E0E0", "spaceBefore" => 0, "spaceAfter" => 0, "spacing" => 0);
    $secondRowColorStyle = array("color" => "008000");
    $rowTextStyleLeft = array("size" => 10, "bold" => true);
    $rowTextStyleRight = array("size" => 10, "bold" => false);
    $rowTextStyleRightBold = array("size" => 10, "bold" => true);

    $heading = "Required license compliance tasks";
    $subHeading = "Common obligations, restrictions and risks:";
    $subHeadingInfoText = "  There is a list of common rules which was defined to simplify the To-Dos for development and distribution. The following list contains rules for development, and distribution which must always be followed!";
    $rowWidth = 5;
    $firstColLen = 500;
    $secondColLen = 15000;
    
    $section->addTitle(htmlspecialchars($heading), 2);
    $section->addTitle(htmlspecialchars($subHeading), 3);
    $section->addText(htmlspecialchars($subHeadingInfoText), $rowTextStyleRight);

    $r1c1 = "2.1.1";
    $r2c1 = "2.1.2";

    $r1c2 = "Documentation of license conditions and copyright notices in product documentation (License Notice File / README_OSS) is provided by this component clearing report:";
    $r2c2 = "Additional Common Obligations:";
    $r2c3 = "Need to be ensured by the distributing party:";

    $table = $section->addTable($this->tablestyle);

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen, $rowStyle)->addText(htmlspecialchars($r1c1), $rowTextStyleLeft, "pStyle");
    $cell = $table->addCell($secondColLen, $rowStyle)->addText(htmlspecialchars($r1c2), $rowTextStyleRightBold, "pStyle");

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen);
    $cell = $table->addCell($secondColLen);
    if (!empty($text1) && strpos($text1, '\n')!==FALSE){
      $texts = explode('\n', $text1);
      foreach($texts as $text){
        $cell->addText(htmlspecialchars($text), $secondRowColorStyle, "pStyle");
      }
    }
    else{
      $cell->addText(htmlspecialchars($text1),$secondRowColorStyle , "pStyle");
    }

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen, $rowStyle)->addText(htmlspecialchars($r2c1), $rowTextStyleLeft, "pStyle");
    $cell = $table->addCell($secondColLen, $rowStyle);
    $cell->addText(htmlspecialchars($r2c2), $rowTextStyleRightBold, "pStyle");
    $cell->addText(htmlspecialchars($r2c3), $rowTextStyleRightBold, "pStyle");

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen);
    $cell = $table->addCell($secondColLen);
    if (!empty($text1) && strpos($text2, '\n')!==FALSE){
      $texts = explode('\n', $text2);
      foreach($texts as $text){
        $cell->addText(htmlspecialchars($text), null, "pStyle");
      }
    }
    else{
      $cell->addText(htmlspecialchars($text2), null, "pStyle");
    }
    $section->addTextBreak();
  }

  
  /**
   * @param1 Section $section
   * @param2 array of obloigations  
   */ 
  function todoObliTable(Section $section, $obligations)
  {
    $firstRowStyle = array("bgColor" => "D2D0CE");
    $firstRowTextStyle = array("size" => 11, "bold" => true);
    $secondRowTextStyle1 = array("size" => 11, "bold" => false);
    $secondRowTextStyle2 = array("size" => 10, "bold" => false);
    $secondRowTextStyle2Bold = array("size" => 10, "bold" => true);
    $firstColStyle = array ("size" => 11 , "bold"=> true, "bgcolor" => "FFFFC2");
    $secondColStyle = array ("size" => 11 , "bold"=> true, "bgcolor"=> "E0FFFF");
    $subHeading = " Additional obligations, restrictions & risks beyond common rules";
    $subHeadingInfoText1 = "This chapter contains all obligations in addition to “common obligations, restrictions and risks” (common rules) of included OSS licenses (need to get added manually during component clearing process).";

    $cellRowSpan = array("vMerge" => "restart", "valign" => "top","size" => 11 , "bold"=> true, "bgcolor" => "FFFFC2");
    $cellRowContinue = array("vMerge" => "continue","size" => 11 , "bold"=> true, "bgcolor" => "FFFFC2");
    
    $section->addTitle(htmlspecialchars($subHeading), 3);
    $section->addText(htmlspecialchars($subHeadingInfoText1));

    $rowWidth = 200;
    $firstColLen = 3000;
    $secondColLen = 2500;
    $thirdColLen = 9000;

    $table = $section->addTable($this->tablestyle);
    
    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen, $firstRowStyle)->addText(htmlspecialchars("Obligation"), $firstRowTextStyle);
    $cell = $table->addCell($secondColLen, $firstRowStyle)->addText(htmlspecialchars("License"), $firstRowTextStyle);
    $cell = $table->addCell($thirdColLen, $firstRowStyle)->addText(htmlspecialchars("License section reference and short Description"), $firstRowTextStyle);

    if(!empty($obligations)){
      foreach($obligations as $obligation){
          $table->addRow($rowWidth);
          $table->addCell($firstColLen,$firstColStyle)->addText(htmlspecialchars($obligation["topic"]), $firstRowTextStyle);
          $table->addCell($secondColLen,$secondColStyle)->addText(htmlspecialchars(implode(",",$obligation["license"])));
          $table->addCell($thirdColLen)->addText(htmlspecialchars($obligation["text"]));
      }
    }
    else{
      $table->addRow($rowWidth);
      $table->addCell($firstColLen,$firstColStyle)->addText(htmlspecialchars($key), $firstRowTextStyle);
      $table->addCell($secondColLen,$secondColStyle);
      $table->addCell($thirdColLen);
    }
    $section->addTextBreak();
  }

  /**
   * @param Section $section
   */
  function allLicensesWithAndWithoutObligations(Section $section, $heading, $obligations, $whiteLists, $titleSubHeadingObli)
  {
    $section->addTitle(htmlspecialchars("$heading"), 2);
    $section->addText($titleSubHeadingObli, $this->subHeadingStyle);
    $firstRowStyle = array("size" => 12, "bold" => false);

    $rowWidth = 200;
    $firstColLen = 3500;
    $secondColLen = 10000;

    $table = $section->addTable($this->tablestyle);

    if(!empty($obligations)){
      foreach($obligations as $obligation){
        $table->addRow($rowWidth);
        $table->addCell($secondColLen,$firstColStyle)->addText(htmlspecialchars(implode(",",$obligation["license"])));
        $table->addCell($firstColLen,$firstColStyle)->addText(htmlspecialchars($obligation["topic"]));
      }
    }
    if(!empty($whiteLists)){
      foreach($whiteLists as $whiteList){
        $table->addRow($rowWidth);
        $table->addCell($firstColLen,$firstColStyle)->addText(htmlspecialchars($whiteList));
        $table->addCell($secondColLen,$firstColStyle)->addText("");
      }
    }
    $section->addTextBreak();
  }
  
  /**
   * @param Section $section 
   */ 
  function basicForClearingReport(Section $section)
  {
    $heading = "Basis for Clearing Report";
    $section->addTitle(htmlspecialchars($heading), 2);
    
    $table = $section->addTable($this->tablestyle);

    $cellRowContinue = array("vMerge" => "continue");
    $firstRowStyle = array("size" => 12, "bold" => true);
    $rowTextStyle = array("size" => 11, "bold" => false);
    
    $cellRowSpan = array("vMerge" => "restart", "valign" => "top");
    $cellColSpan = array("gridSpan" => 2, "valign" => "center");

    $rowWidth = 200;

    $firstColLen = 3500;
    $secondColLen = 7500;
    $thirdColLen = 4500;

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen, $cellRowSpan)->addText(htmlspecialchars("Preparation basis for OSS"), $firstRowStyle);
    $cell = $table->addCell($secondColLen, $cellColSpan);
    $cell->addCheckBox("chkBox1", htmlspecialchars("Legally relevant Steps from analysis to clearing report"), $rowTextStyle);
    $cell->addCheckBox("chkBox2", htmlspecialchars("no"), $rowTextStyle);
    $cell = $table->addCell($thirdColLen);

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen, $cellRowContinue);
    $cell = $table->addCell($secondColLen, $cellColSpan);
    $cell->addCheckBox("checkBox1", htmlspecialchars("According to “Common Principles for Open Source License Interpretation” "), $rowTextStyle);
    $cell->addCheckBox("checkBox2", htmlspecialchars("no"), $rowTextStyle);
    $cell = $table->addCell($thirdColLen);

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen, $cellRowSpan)->addText(htmlspecialchars("OSS Source Code"), $firstRowStyle);
    $cell = $table->addCell($thirdColLen)->addText(htmlspecialchars("Link to Upload page of component:"), $rowTextStyle); 
    $cell = $table->addCell($secondColLen, $cellColSpan)->addText(""); 

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen, $cellRowContinue);
    $cell = $table->addCell($thirdColLen)->addText(htmlspecialchars("MD5 hash value of source code:"), $rowTextStyle);
    $cell = $table->addCell($secondColLen, $cellColSpan)->addText(htmlspecialchars("n/a"), $rowTextStyle);

    $table->addRow($rowWidth);
    $cell = $table->addCell($firstColLen)->addText(htmlspecialchars("Result of LCR editor" ), $firstRowStyle);
    $cell = $table->addCell($thirdColLen)->addText(htmlspecialchars("Embedded .xml file which can be checked by the LCR Editor is embedded here:"), $rowTextStyle);
    $cell = $table->addCell($secondColLen, $cellColSpan)->addText(htmlspecialchars("n/a"), $rowTextStyle);
  
    $section->addTextBreak();
  }

  /**
   * @param Section $section 
   * @param $heading 
   */ 
  function getNonFunctionalLicenses(Section $section, $heading)
  {
    $styleFont = array('bold'=>true, 'size'=>10, 'name'=>'Arial');

    $section->addTitle(htmlspecialchars($heading), 2);
    $text = "In this section the files and their licenses can be listed which do not “go” into the delivered “binary”, e.g. /test or /example.";
    $section->addText($text, $styleFont);   
    $section->addTextBreak();
  }

  /** 
   * @param Section $section
   */ 
  function notes(Section $section, $heading, $subHeading)
  {
    $firstColLen = 3500;
    $secondColLen = 8000;
    $thirdColLen = 4000;
    $styleFont = array('bold'=>true, 'size'=>10, 'name'=>'Arial','underline' => 'single');
    $styleFont1 = array('bold'=>false, 'size'=>10, 'name'=>'Arial','underline' => 'single');
    $styleFont2 = array('bold'=>false, 'size'=>10, 'name'=>'Arial');


    $section->addTitle(htmlspecialchars("$heading"), 2); 
    $section->addText("Only such source code of this component may be used-");
    $section->addListItem("which has been checked by and obtained via the Clearing Center or", 1, "Arial", PhpOffice\PhpWord\Style\ListItem::TYPE_SQUARE_FILLED);
    $section->addListItem("which has been submitted to Clearing Support to be checked", 1 , "Arial", PhpOffice\PhpWord\Style\ListItem::TYPE_SQUARE_FILLED);

    $textrun = $section->createTextRun();
    $textrun->addText("Other source code or binaries from the Internet ", $styleFont2);
    $textrun->addText("must not be ", $styleFont);
    $textrun->addText("used.", $styleFont1);
    $section->addText("");
    $section->addText("The following chapters are generated by the source code scanner.");

    $section->addTextBreak(); 
    $section->addTitle(htmlspecialchars($subHeading), 3);
  }

}
