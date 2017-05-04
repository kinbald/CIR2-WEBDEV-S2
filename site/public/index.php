<?php
/**
 * Created by PhpStorm.
 * User: billaud
 * Date: 14/03/17
 * Time: 23:03
 */

ini_set('display_errors', 'On');
require '../vendor/autoload.php';
$settings = require __DIR__ . '/../App/settings.php';
$app = new \Slim\App($settings);
// Set up dependencies
require __DIR__ . '/../App/dependencies.php';

//load routes
require __DIR__.'/../App/routes.php';
phpinfo();


$objPHPExcel = new PHPExcel();
$objWorksheet = $objPHPExcel->getActiveSheet();
$objWorksheet->fromArray(
    array(
        array('',	2010,	2011,	2012),
        array('Q1',   12,   15,		21),
        array('Q2',   56,   73,		86),
        array('Q3',   52,   61,		69),
        array('Q4',   30,   32,		0),
    )
);


//	Set the Labels for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesLabels1 = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', NULL, 1),	//	2011
);
//	Set the X-Axis Labels
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$xAxisTickValues1 = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$5', NULL, 4),	//	Q1 to Q4
);
//	Set the Data values for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesValues1 = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$2:$C$5', NULL, 4),
);

//	Build the dataseries
$series1 = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
    NULL,			                                        // plotGrouping (Pie charts don't have any grouping)
    range(0, count($dataSeriesValues1)-1),					// plotOrder
    $dataSeriesLabels1,										// plotLabel
    $xAxisTickValues1,										// plotCategory
    $dataSeriesValues1										// plotValues
);

//	Set up a layout object for the Pie chart
$layout1 = new PHPExcel_Chart_Layout();
$layout1->setShowVal(TRUE);
$layout1->setShowPercent(TRUE);

//	Set the series in the plot area
$plotArea1 = new PHPExcel_Chart_PlotArea($layout1, array($series1));
//	Set the chart legend
$legend1 = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);

$title1 = new PHPExcel_Chart_Title('Test Pie Chart');


//	Create the chart
$chart1 = new PHPExcel_Chart(
    'chart1',		// name
    $title1,		// title
    $legend1,		// legend
    $plotArea1,		// plotArea
    true,			// plotVisibleOnly
    0,				// displayBlanksAs
    NULL,			// xAxisLabel
    NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
);

//	Set the position where the chart should appear in the worksheet
$chart1->setTopLeftPosition('A7');
$chart1->setBottomRightPosition('H20');

//	Add the chart to the worksheet
$objWorksheet->addChart($chart1);


//	Set the Labels for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesLabels2 = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$C$1', NULL, 1),	//	2011
);
//	Set the X-Axis Labels
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$xAxisTickValues2 = array(
    new PHPExcel_Chart_DataSeriesValues('String', 'Worksheet!$A$2:$A$5', NULL, 4),	//	Q1 to Q4
);
//	Set the Data values for each data series we want to plot
//		Datatype
//		Cell reference for data
//		Format Code
//		Number of datapoints in series
//		Data values
//		Data Marker
$dataSeriesValues2 = array(
    new PHPExcel_Chart_DataSeriesValues('Number', 'Worksheet!$C$2:$C$5', NULL, 4),
);

//	Build the dataseries
$series2 = new PHPExcel_Chart_DataSeries(
    PHPExcel_Chart_DataSeries::TYPE_DONUTCHART,		// plotType
    NULL,			                                // plotGrouping (Donut charts don't have any grouping)
    range(0, count($dataSeriesValues2)-1),			// plotOrder
    $dataSeriesLabels2,								// plotLabel
    $xAxisTickValues2,								// plotCategory
    $dataSeriesValues2								// plotValues
);

//	Set up a layout object for the Pie chart
$layout2 = new PHPExcel_Chart_Layout();
$layout2->setShowVal(TRUE);
$layout2->setShowCatName(TRUE);

//	Set the series in the plot area
$plotArea2 = new PHPExcel_Chart_PlotArea($layout2, array($series2));

$title2 = new PHPExcel_Chart_Title('Test Donut Chart');


//	Create the chart
$chart2 = new PHPExcel_Chart(
    'chart2',		// name
    $title2,		// title
    NULL,			// legend
    $plotArea2,		// plotArea
    true,			// plotVisibleOnly
    0,				// displayBlanksAs
    NULL,			// xAxisLabel
    NULL			// yAxisLabel		- Like Pie charts, Donut charts don't have a Y-Axis
);

//	Set the position where the chart should appear in the worksheet
$chart2->setTopLeftPosition('I7');
$chart2->setBottomRightPosition('P20');

//	Add the chart to the worksheet
$objWorksheet->addChart($chart2);


// Save Excel 2007 file
echo date('H:i:s') , " Write to Excel2007 format" , EOL;
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
echo date('H:i:s') , " File written to " , str_replace('.php', '.xlsx', pathinfo(__FILE__, PATHINFO_BASENAME)) , EOL;


// Echo memory peak usage
echo date('H:i:s') , " Peak memory usage: " , (memory_get_peak_usage(true) / 1024 / 1024) , " MB" , EOL;

// Echo done
echo date('H:i:s') , " Done writing file" , EOL;
echo 'File has been created in ' , getcwd() , EOL;


//run app
$app->run();

