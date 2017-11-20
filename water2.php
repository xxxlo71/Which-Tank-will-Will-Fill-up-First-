<?php

class Path {
	private $bufferWater = [0,0,0,0,0];

	public $position;
	public $box;
	public $isBuffer = false;

	public function __construct($position,Box $box,$isBuffer = false) //$A->addOutput(new Path(1,$B));
	{
		$this->position = $position;//0,1,2,3,4
		$this->box = $box;//$B
		$this->isBuffer = $isBuffer;//false
	}

	public function fillBufferWater()
	{
		$isFull = true;
		for($i=0;$i<5;$i++) {
			if($this->bufferWater[$i] == 0) {
				$this->bufferWater[$i] = 1;
				$isFull = false;
				break;
			}
		}
		return $isFull;
	}
}
//水箱
class Box {
	private $outputs = [];
	private $water = [0,0,0,0,0];//五滴水

	public function addOutput(Path $path)
	{
		$this->outputs[] = $path;
	}

	public function isFull()
	{
		$isFull = true;
		//var_export($this->water);
		foreach ($this->water as $water){
			if($water == 0) {//陣列中其中一個是0,就沒滿,五滴水
				$isFull = false;
				break;
			}
		}
		return $isFull;
	}

	public function addWater()
	{
		$position = 0;
		$isFull = true;
		for($i=0;$i<5;$i++) {
			if($this->water[$i] == 0) {
				$position = $i;
				$isFull = false;
				break;
			}
		}
		if($isFull) $position = count($this->water) - 1;//滿了-1


		$hasOut = false;
		foreach($this->outputs as $output) {
			if($position>=$output->position) {
				
				if($output->isBuffer) {
					if($output->fillBufferWater()) {
						$hasOut = true;
						$output->box->addWater();
					} else {
						//WAIT
					}
				} else {
					$hasOut = true;
					$output->box->addWater();
				}
			}
		}

		if(!$hasOut) {
			$this->water[$position] = 1;
		}

	}

	public function checkWater()
	{
		return $this->water[0]+$this->water[1]+$this->water[2]+$this->water[3]+$this->water[4];
	}
}

//建立水槽
$A = new Box();
$B = new Box();
$C = new Box();
$J = new Box();
$I = new Box();
$L = new Box();
$K = new Box();
$F = new Box();

//水箱彼此關係
$A->addOutput(new Path(1,$B));//超過1滴 就流向$B
$B->addOutput(new Path(1,$C));
$C->addOutput(new Path(2,$J));
$I->addOutput(new Path(0,$K));
$J->addOutput(new Path(1,$I,true));//雙條件
$J->addOutput(new Path(3,$L));
$L->addOutput(new Path(0,$F));



//loop
$boxes = ['A','B','C','J','I','L','K','F'];
$dropwater=0;
while(true) {
	echo "<br />";
	echo "stage ".$dropwater."<br />";
	$haveFull = false;
	foreach($boxes as $box) {
		echo "BOX ".$box. " stay ".$$box->checkWater()." waterlevel. <br/>";
		if($$box->isFull()) {
			echo "BOX ".$box. " is fulled. <br/>";
			$haveFull = true;
		}
	}
	if($haveFull) break;
	$A->addWater();//注入水滴
	$dropwater++;
}
?>