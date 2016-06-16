<?php

  use phpunit\framework\TestCase;
  include("./DoodleWorker.php");

  class DoodleWorkerTest extends TestCase
  {

    public function testDiffTests()
    {
      $d = new DoodleWorker($max=5, $avg=5);

      $this->assertTrue($d->diffTests([1,2,0,3]));
    }

    public function testTest1()
    {
      $d = new DoodleWorker($max=4);

      $this->assertTrue($d->test1([0,1,2,3]));
      $this->assertFalse($d->test1([0,1,2,3,4,5]));
    }

    public function testTest2()
    {
      $d = new DoodleWorker($max=4, $avg=5);

      $this->assertTrue($d->test2([2,3,4,5]));
      $this->assertFalse($d->test2([4,5,6,7]));
    }

    public function testGetDifferences()
    {
      $d = new DoodleWorker();

      $this->assertEquals([1,2,3], $d->getDifferences([[2,0],[3,0],[4,0]],[[1,0],[1,0],[1,0]]));
    }

    public function testCalCost()
    {
      $d = new DoodleWorker();

      $this->assertEquals(1, $d->calCost([1,0],[2,0]));
    }

  }

?>
