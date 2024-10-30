<?php
/*
Plugin Name: BMI Calculator
Plugin URI: http://www.calculator.net/projects/bmi-calculator-widget.php
Description: This BMI calculator can give out the BMI value as well as basic understandings based on age, height, and weight. It accepts both the units for the United States and the metric units. This calculator can be inserted either to the sidebar or into the post, but not both. Install "BMI Calculator" through the WordPress admin menu of Appearance or Design and then widgets to add to the sidebar. Place [calculatornet_bmi_calculator] in the content to insert into a post.
Author: calculator.net
Version: 1.2
Author URI: http://www.calculator.net
License: GNU GPL see http://www.gnu.org/licenses/licenses.html#GPL
*/

class calculatornet_bmi_calculator {

    function calc_init() {
    	$class_name = 'calculatornet_bmi_calculator';
    	$calc_title = 'BMI Calculator';
    	$calc_desc = 'Allows the user to calculate the Body Mass Index (BMI) from body weight and height.';

    	if (!function_exists('wp_register_sidebar_widget')) return;

    	wp_register_sidebar_widget(
    		$class_name,
    		$calc_title,
    		array($class_name, 'calc_widget'),
            array(
            	'classname' => $class_name,
            	'description' => $calc_desc
            )
        );

    	wp_register_widget_control(
    		$class_name,
    		$calc_title,
    		array($class_name, 'calc_control'),
    	    array('width' => '100%')
        );

        add_shortcode(
        	$class_name,
        	array($class_name, 'calc_shortcode')
        );
    }

    function calc_display($is_widget, $args=array()) {
    	if($is_widget){
    		extract($args);
			$options = get_option('calculatornet_bmi_calculator');
			$title = $options['title'];
			$output[] = $before_widget . $before_title . $title . $after_title;
		}


		$output[] = '<div style="margin-top:5px;">
			<script type="text/javascript">
			function gObj(obj) {
				var theObj;
				if(document.all){
					if(typeof obj=="string"){
						return document.all(obj);
					}else{
						return obj.style;
					}
				}
				if(document.getElementById){
					if(typeof obj=="string"){
						return document.getElementById(obj);
					}else{
						return obj.style;
					}
				}
				return null;
			}
			function trimAll(sString){
				while (sString.substring(0,1) == " "){
					sString = sString.substring(1, sString.length);
				}
				while (sString.substring(sString.length-1, sString.length) == " "){
					sString = sString.substring(0,sString.length-1);
				}
				return sString;
			}
			function isNumber(val){
				val=val+"";
				if (val.length<1) return false;
				if (isNaN(val)){
					return false;
				}else{
					return true;
				}
			}
			function formatNum(inNum){
				outStr = ""+inNum;
				inNum = parseFloat(outStr);
				if ((outStr.length)>10){
					outStr = "" + inNum.toPrecision(10);
				}
				if (outStr.indexOf(".")>-1){
					while (outStr.charAt(outStr.length-1) == "0"){
						outStr = outStr.substr(0,(outStr.length-1));
					}
					if (outStr.charAt(outStr.length-1) == ".") outStr = outStr.substr(0,(outStr.length-1));
					return outStr;
				}else{
					return outStr;
				}
			}
			function showquickmsg(inStr, isError){
				if (isError) inStr = "<font color=red>" + inStr + "</font>";
				gObj("bmicoutput").innerHTML = inStr;
			}

			var girlA = new Array();
			girlA[0] = new Array(0,0,0);
			girlA[1] = new Array(0,0,0);
			girlA[2] = new Array(14.4, 18, 19.1);
			girlA[3] = new Array(14, 17.2, 18.3);
			girlA[4] = new Array( 13.7, 16.8, 18);
			girlA[5] = new Array( 13.5, 16.8, 18.3);
			girlA[6] = new Array( 13.4, 17.1, 18.8);
			girlA[7] = new Array( 13.4, 17.6, 19.6);
			girlA[8] = new Array( 13.5, 18.3, 20.7);
			girlA[9] = new Array( 13.7, 19.1, 21.8);
			girlA[10] = new Array( 14, 19.9, 22.9);
			girlA[11] = new Array( 14.4, 20.8, 24.1);
			girlA[12] = new Array( 14.8, 21.7, 25.2);
			girlA[13] = new Array( 15.3, 22.5, 26.5);
			girlA[14] = new Array( 15.8, 23.5, 27.2);
			girlA[15] = new Array( 16.3, 24, 28.1);
			girlA[16] = new Array( 16.8, 24.7, 28.9);
			girlA[17] = new Array( 17.2, 25.2, 29.6);
			girlA[18] = new Array( 17.5, 25.7, 30.3);
			girlA[19] = new Array( 17.8, 26.1, 31);
			girlA[20] = new Array( 17.8, 26.5, 31.8);

			var boyA = new Array();
			boyA[0] = new Array(0,0,0);
			boyA[1] = new Array(0,0,0);
			boyA[2] = new Array(14.7, 18.2, 19.3);
			boyA[3] = new Array( 14.4, 17.4, 18.3);
			boyA[4] = new Array( 14, 16.9, 17.8);
			boyA[5] = new Array( 13.8, 16.8, 17.9);
			boyA[6] = new Array( 13.7, 17, 18.4);
			boyA[7] = new Array( 13.7, 17.4, 19.1);
			boyA[8] = new Array( 13.8, 17.9, 20);
			boyA[9] = new Array( 14, 18.6, 21.1);
			boyA[10] = new Array( 14.2, 19.4, 22.1);
			boyA[11] = new Array( 14.5, 20.2, 23.2);
			boyA[12] = new Array( 15, 21, 24.2);
			boyA[13] = new Array( 15.5, 21.8, 25.1);
			boyA[14] = new Array( 16, 22.6, 26);
			boyA[15] = new Array( 16.5, 23.4, 26.8);
			boyA[16] = new Array( 17.1, 24.2, 27.5);
			boyA[17] = new Array( 17.7, 24.9, 28.2);
			boyA[18] = new Array( 18.2, 25.6, 28.9);
			boyA[19] = new Array( 18.7, 26.3, 29.7);
			boyA[20] = new Array( 19.1, 27, 30.6);

			function showCalc(inval){
				if (inval == 2){
					gObj("standardheightweight").style.display = "none";
					gObj("metricheightweight").style.display = "block";
				}else{
					gObj("standardheightweight").style.display = "block";
					gObj("metricheightweight").style.display = "none";
				}
			}

			function getTheWeight(bmiNum, heightNum, weightUnit){
				outPutNum = 0;
				if (weightUnit == "kg"){
					outPutNum = bmiNum * heightNum * heightNum / 10000;
					outPutNum = outPutNum.toFixed(1);
				}else{
					outPutNum = bmiNum * heightNum * heightNum / 4535.92;
					outPutNum = outPutNum.toFixed(1);
				}
				return outPutNum;
			}

			function bmicalc(){
				showquickmsg("calculating...",true);
				cage = gObj("cage").value;

				cheightfeet = gObj("cheightfeet").value;
				cheightinch = gObj("cheightinch").value;
				cpound = gObj("cpound").value;
				cheightmeter = gObj("cheightmeter").value;
				ckg = gObj("ckg").value;
				ctype = "standard";
				if (!(gObj("ctype1").checked)){
					ctype = "metric";
				}
				ismale=false;
				if (gObj("csex1").checked){
					ismale = true;
				}


				if (!isNumber(cage) || (cage.length<1)){
					showquickmsg("age need to be numeric",true);
					return;
				}else{
					if ((cage < 2) || (cage > 120)){
						showquickmsg("age need to be between 2 and 120",true);
						return;
					}
				}

				if (ctype=="standard"){
					if ((!isNumber(cheightfeet)) || (!isNumber(cheightinch)) || (cheightfeet.length<1) || (cheightinch.length<1)){
						showquickmsg("height need to be numeric",true);
						return;
					}else if (!isNumber(cpound) || (cpound.length<1)){
						showquickmsg("weight need to be numeric",true);
						return;
					}

					cheightmeter = 30.48 * parseFloat(cheightfeet) + 2.54 * parseFloat(cheightinch);
					ckg = parseFloat(cpound) * 0.453592;
				}else{
					if (!isNumber(cheightmeter) || (cheightmeter.length<1)){
						showquickmsg("height need to be numeric",true);
						return;
					}else if (!isNumber(ckg) || (ckg.length<1)){
						showquickmsg("weight need to be numeric",true);
						return;
					}
					ckg=parseFloat(ckg);
					cheightmeter=parseFloat(cheightmeter);
				}

				cage=parseFloat(cage);

				cbmi = 10000*ckg/cheightmeter/cheightmeter;
				cbmi = parseFloat(formatNum(cbmi)).toFixed(2);

				outPutStr = "BMI = " + cbmi + " kg/m<sup>2</sup> &nbsp; (";
				if (cage > 20){
					if (cbmi<16.5){
						outPutStr += "<font color=red><b>severely underweight</b></font>";
					}else if(cbmi<18.5){
						outPutStr += "<font color=#FDD790><b>Underweight</b></font>";
					}else if(cbmi<25){
						outPutStr += "<font color=green><b>Normal</b></font>";
					}else if(cbmi<30){
						outPutStr += "<font color=#FDD790><b>Overweight</b></font>";
					}else if(cbmi<35){
						outPutStr += "<font color=#F69D92><b>Obese Class I</b></font>";
					}else if(cbmi<40){
						outPutStr += "<font color=#F05340><b>Obese Class II</b></font>";
					}else{
						outPutStr += "<font color=red><b>Obese Class III</b></font>";
					}
					outPutStr += ")";
					outPutStr += "<br />normal BMI range: 18.5 - 25 kg/m<sup>2</sup>";
					if (ctype=="standard"){
						outPutStr += "<br />normal weight range for the height: " + getTheWeight(18.5, cheightmeter, "lb") + " - " + getTheWeight(25, cheightmeter, "lb") + " lbs";
					}else{
						outPutStr += "<br />normal weight range for the height: " + getTheWeight(18.5, cheightmeter, "kg") + " - " + getTheWeight(25, cheightmeter, "kg") + " kgs";
					}

				}else{
					line5 = 0;
					line85 = 0;
					line95 = 0;
					if (ismale){
						line5 = boyA[cage][0];
						line85 = boyA[cage][1];
						line95 = boyA[cage][2];
					}else{
						line5 = girlA[cage][0];
						line85 = girlA[cage][1];
						line95 = girlA[cage][2];
					}

					if (cbmi<line5){
						outPutStr += "<font color=red><b>Underweight</b></font>";
					}else if(cbmi<line85){
						outPutStr += "<font color=green><b>Healthy weight</b></font>";
					}else if(cbmi<line95){
						outPutStr += "<font color=#F69D92>At risk of overweight</b></font>";
					}else{
						outPutStr += "<font color=red><b>Overweight</b></font>";
					}
					outPutStr += ")";
					outPutStr += "<br />normal BMI range: " + line5 + " - " + line85 + " kg/m<sup>2</sup>";

					if (ctype=="standard"){
						outPutStr += "<br />normal weight range for the height: " + getTheWeight(line5, cheightmeter, "lb") + " - " + getTheWeight(line85, cheightmeter, "lb") + " lbs";
					}else{
						outPutStr += "<br />normal weight range for the height: " + getTheWeight(line5, cheightmeter, "kg") + " - " + getTheWeight(line85, cheightmeter, "kg") + " kgs";
					}
				}
				outPutStr += "<br /><a href=\"http://www.calculator.net/bmi-calculator.html\" rel=\"nofollow\">more info &gt;&gt;</a>";
				showquickmsg(outPutStr, false);
			}
			</script>

			<!-- Edit the following to change the look and feel of this calculator -->
			<style>
				#calinputtable, #standardheightweight, #metricheightweight, #calinputtablesubmit{
					width:180px;
					border:0;
				}
				#calinputtable td, #standardheightweight td, #metricheightweight td, #calinputtablesubmit td{
					border:0;
					font-size:12px;
					padding:0px;
				}
				#calinputtable td input, #standardheightweight td input, #metricheightweight td input, #calinputtablesubmit td input{
					height: 25px;
					padding: 3px;
					margin: 0px;
					font-size:13px;
					display: inline-block;
				}
				#calinputtable td label{
					display: inline-block;
				}
				#calinputtableleft1{
					width:30px;
				}
				#calinputtablemid1{
					width:130px;
				}
				#calinputtableright1{
					width:20px;
				}
				#calinputtableleft2{
					width:45px;
				}
				#calinputtablemid2{
					width:115px;
				}
				#calinputtableright2{
					width:20px;
				}
				#calinputfooter{
					width:180px;
					text-align:right;
				}
			</style>
			<table id="calinputtable" cellpadding="0" cellspacing="3" border="1">
			<form>
			<tr>
				<td id="calinputtableleft1">unit</td>
				<td id="calinputtablemid1"><input type="radio" name="ctype" id="ctype1" value="standard" onclick="showCalc(1)" checked />US</label> &nbsp; <label for="ctype2"><input type="radio" name="ctype" id="ctype2" value="metric" onclick="showCalc(2)" />Metric</label></td>
				<td id="calinputtableright1">&nbsp;</td>
			</tr>
			<tr>
				<td>age</td>
				<td align="right"><input type="text" name="cage" size="6" id="cage" value="" style="text-align: right;"></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>sex</td>
				<td align="right"><label for="csex1"><input type="radio" name="csex" id="csex1" value="m" onclick="bmicalc()" checked />male</label> &nbsp; <label for="csex2"><input type="radio" name="csex" id="csex2" value="f" onclick="bmicalc()" />female</label></td>
				<td>&nbsp;</td>
			</tr>
			</table>
			<table id="standardheightweight">
			<tr>
				<td>height</td>
				<td align="right" colspan="2"><input type="text" name="cheightfeet" size="1" id="cheightfeet" value="" style="text-align: right;width:50px;"></td><td align="left">ft</td><td align="right"><input type="text" name="cheightinch" size="1" id="cheightinch" value="" style="text-align: right;width:50px;"></td><td align="left">in</td>
			</tr>
			<tr>
				<td id="calinputtableleft2">weight</td>
				<td align="right" id="calinputtablemid2" colspan="4"><input type="text" name="cpound" size="4" id="cpound" value="" style="text-align: right;"></td>
				<td id="calinputtableright2">lb</td>
			</tr>
			</table>
			<table id="metricheightweight">
			<tr>
				<td id="calinputtableleft2">height</td>
				<td align="right" id="calinputtablemid2"><input type="text" name="cheightmeter" size="4" id="cheightmeter" value="" style="text-align: right;"></td>
				<td id="calinputtableright2">cm</td>
			</tr>
			<tr id="metricweight">
				<td>weight</td>
				<td align="right"><input type="text" name="ckg" size="4" id="ckg" value="" style="text-align: right;"></td>
				<td>kg</td>
			</tr>
			</table>
			<table id="calinputtablesubmit">
			<tr>
				<td align="center"><input type="button" value="Calculate" onclick="bmicalc()"></td>
			</tr>
			</form>
			</table>
			<div id="bmicoutput"></div>
			<script type="text/javascript">
			showCalc(1);
			</script>
			<div id="calinputfooter">by <a href="http://www.calculator.net" rel="nofollow">calculator.net</a></div>
		</div>';
    	$output[] = $after_widget;
    	return join($output, "\n");
    }

	function calc_control() {
		$class_name = 'calculatornet_bmi_calculator';
		$calc_title = 'BMI Calculator';

	    $options = get_option($class_name);

		if (!is_array($options)) $options = array('title'=>$calc_title);

		if ($_POST[$class_name.'_submit']) {
			$options['title'] = strip_tags(stripslashes($_POST[$class_name.'_title']));
			update_option($class_name, $options);
		}

		$title = htmlspecialchars($options['title'], ENT_QUOTES);

		echo '<p>Title: <input style="width: 180px;" name="'.$class_name.'_title" type="text" value="'.$title.'" /></p>';
		echo '<input type="hidden" name="'.$class_name.'_submit" value="1" />';
	}

    function calc_shortcode($args, $content=null) {
        return calculatornet_bmi_calculator::calc_display(false, $args);
    }

    function calc_widget($args) {
        echo calculatornet_bmi_calculator::calc_display(true, $args);
    }
}

add_action('widgets_init', array('calculatornet_bmi_calculator', 'calc_init'));

?>