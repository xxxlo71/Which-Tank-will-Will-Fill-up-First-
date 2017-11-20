<html>
<head>
<style type="text/css">
.tank {
	width:100;
	height:100;
	border:1px solid #000;
	border-top:none;
}
</style>
</head>
<body>
<a href="https://www.quora.com/%E2%80%9CWhich-Tank-will-Will-Fill-up-First-example-with-12-tanks-A-to-L-see-question-source-for-image" target="_blank">https://www.quora.com/%E2%80%9CWhich-Tank-will-Will-Fill-up-First-example-with-12-tanks-A-to-L-see-question-source-for-image</a>
<form name="frm">
<input type="hidden" id="water" name="water" value="<?echo ($_GET['water']) ? $_GET['water']:0;?>" />
<input type="button" value="drop one water" onclick="document.getElementById('water').value=parseInt(document.getElementById('water').value,10)+1;document.frm.submit();" />
</form>
<div style="text-align:right;position:relative;width:1000px;height:0px;">
<!--水槽-->
<canvas id="A_tank" class="tank" style="position:absolute;top:0;right:0;"></canvas>
<canvas id="B_tank" class="tank" style="position:absolute;top:70;right:140;"></canvas> 
<canvas id="C_tank" class="tank" style="position:absolute;top:130;right:280;"></canvas> 

<canvas id="D_tank" class="tank" style="position:absolute;top:210;right:140;"></canvas> 
<canvas id="E_tank" class="tank" style="position:absolute;top:210;right:0;"></canvas> 
<canvas id="J_tank" class="tank" style="position:absolute;top:180;right:420;"></canvas>
<canvas id="I_tank" class="tank" style="position:absolute;top:180;right:560;"></canvas> 

<canvas id="F_tank" class="tank" style="position:absolute;top:340;right:0;"></canvas> 
<canvas id="G_tank" class="tank" style="position:absolute;top:340;right:140;"></canvas> 
<canvas id="L_tank" class="tank" style="position:absolute;top:290;right:280;"></canvas>
<canvas id="K_tank" class="tank" style="position:absolute;top:310;right:420;"></canvas> 
<canvas id="H_tank" class="tank" style="position:absolute;top:380;right:560;"></canvas> 

<!--水管-->
<canvas id="AB_pipe" class="pipe" style="position:absolute;top:380;right:560;"></canvas> 

</div>
</body>

<?php
/*
rule1: 一滴為基本單位、每次進水量為一滴直到丟出滿的水杯編號
rule2: 水杯五滴就算滿
rule3: 從底部 (0)-> A水杯(25)的高度為25滴
*/
ini_set('display_errors', 1); //顯示錯誤訊息
 
	/*
	1.A比B比C高
	2.I跟J一樣高,D跟E一樣高,G跟F一樣高
	3.H最低
	4.L比K高
	*/
	$tankHeight=array(	'A'=>'20',
						'B'=>'18','C'=>'15',
						'I'=>'11','J'=>'11',
						'D'=>'9','E'=>'9',
						'L'=>'5',
						'K'=>'4',
						'G'=>'3','F'=>'3',
						'H'=>'0');
    //class 容器
    class tank{
        private $showProcess = show;
        //目前此容器內的水量
        private $waterLevel = 0;
        private $tankName = '';
 
        function __construct($tankName){
            $this->waterLevel = 0;
            $this->tankName = $tankName;
        }
 
        function addWater($waterIncrease = 1){
            $this->waterLevel += $waterIncrease;
            if($this->showProcess)
                echo "tank {$this->tankName} add {$waterIncrease} drop, now waterLevel = {$this->waterLevel} <BR>";
            if($this->waterLevel >= 5 && !in_array($this->tankName, array('IJ','LHF'))){
                echo "tank {$this->tankName} already fill up <BR>";
				return;
            }
 
        }
 
        function subWater($waterDecrease = 1){
            $this->waterLevel -= $waterDecrease;
            if($this->showProcess)
                echo "tank {$this->tankName} sub {$waterDecrease} drop, now waterLevel = {$this->waterLevel} <BR>";
        }
 
        function checkWater(){
            return $this->waterLevel;
        }
    }
 
    $tankDef = array('A','B','C','D','E','F','G','H','I','J','K','L','IJ','LHF');
    foreach ($tankDef as $key) {
        $tank[$key] = new tank($key);
    }
 
    for ($i=0; $i < $_GET['water'] ; $i++) { 
        echo "<BR> {$i} times start <BR>";
 
        //水龍頭滴入 A 槽
        $tank['A']->addWater();
 
        //A槽流出B槽條件 : 水滴存量大於 2
        if($tank['A']->checkWater() >= 2){
            $tank['A']->subWater();
            $tank['B']->addWater();
        }
 
        //B槽流出C槽條件 : 水滴存量大於 1
        if($tank['B']->checkWater() >= 1){
            $tank['B']->subWater();
            $tank['C']->addWater();
        }
 
        //C槽流出D槽條件 : 水滴存量大於 1
        if($tank['C']->checkWater() >= 1){
            $tank['C']->subWater();
            $tank['D']->addWater();
 
            //D槽流出C槽條件 : 水滴存量大於 1
            if($tank['D']->checkWater() >= 1){
                $tank['D']->subWater();
                $tank['C']->addWater();
            }
        }
 
        //C槽流出J槽條件 : 水滴存量大於 3
        if($tank['C']->checkWater() >= 3){
            $tank['C']->subWater();
            $tank['J']->addWater();
        }
 
        //J槽流出IJ buffer槽條件 : 水滴存量大於 1, 但不 -1
        if($tank['J']->checkWater() >= 1){
            $tank['IJ']->addWater();
        }
 
        if($tank['J']->checkWater() >= 3){
            $tank['J']->subWater();
            $tank['IJ']->subWater();
            $tank['L']->addWater();
        }
 
        if($tank['IJ']->checkWater() >= 6){//水管buffer
            $tank['J']->subWater();
            $tank['I']->addWater();   
        }
 
		if($tank['I']->checkWater() >= 1){
            $tank['I']->subWater();
            $tank['K']->addWater(); 
        }
		
        if($tank['L']->checkWater() >= 1){
            $tank['L']->subWater();
            $tank['LHF']->addWater(); 
        }
 
        if($tank['LHF']->checkWater() >= 3){//水管buffer
            $tank['F']->addWater();
            if($tank['LHF']->checkWater() >= 4){
                $tank['L']->addWater();
            }
        }
    }
	$draw="	const tank = ['".$tank['A']->checkWater()."', '".$tank['B']->checkWater()."', '".$tank['C']->checkWater()."', '".$tank['D']->checkWater()."', '".$tank['E']->checkWater()."', '".$tank['F']->checkWater()."', '".$tank['G']->checkWater()."', '".$tank['H']->checkWater()."', '".$tank['I']->checkWater()."', '".$tank['J']->checkWater()."', '".$tank['K']->checkWater()."', '".$tank['L']->checkWater()."', '".$tank['IJ']->checkWater()."', '".$tank['LHF']->checkWater()."'];
			draw(tank);";
?>
<script type="text/javascript">
//init
var tank_arr = new Array('A_tank','B_tank','C_tank','D_tank','E_tank','F_tank','G_tank','H_tank','I_tank','J_tank','K_tank','L_tank');
var ctx;
for(x in tank_arr)
{
	ctx = document.getElementById(tank_arr[x]).getContext("2d");
	ctx.font = "60px Arial";
	ctx.strokeText(tank_arr[x].substring(0,1),100,100);
}

var rectXPos = 0;
var rectYPos = 0;
var rectWidth = 300;//max 300
var rectHeight = 0;//max 150
//draw
function draw(params)
{
	var thickness=1;
	for (var i=0; i<params.length; i++) {
		//alert(params[i]);//1->20% 5->100%
		//alert(tank_arr[i]);
		ctx = document.getElementById(tank_arr[i]).getContext("2d");
		ctx.fillStyle="#0000FF";
		
		switch(params[i])
		{
			case '0'://第零滴水 rectYPos = 120; rectHeight = 0;
				rectYPos = 120; rectHeight = 0;
			break;
			case '1'://第一滴水 rectYPos = 120; rectHeight = 30;
				rectYPos = 120; rectHeight = 30;
			break;
			case '2'://第二滴水 rectYPos = 90; rectHeight = 60;
				rectYPos = 90; rectHeight = 60;
			break;
			case '3'://第三滴水 rectYPos = 60; rectHeight = 90;
				rectYPos = 60; rectHeight = 90;
			break;
			case '4'://第四滴水 rectYPos = 30; rectHeight = 120;
				rectYPos = 30; rectHeight = 120;
			break;
			case '5'://第五滴水 rectYPos = 0; rectHeight = 150;
				rectYPos = 0; rectHeight = 150;
			break;
		}
		ctx.fillRect(rectXPos, rectYPos, rectWidth, rectHeight);
		ctx.font = "60px Arial";
		ctx.strokeText(tank_arr[i].substring(0,1),100,100);
	}
}
<? echo $draw;?>
</script>
</html>