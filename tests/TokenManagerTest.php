<?php
  use phpunit\framework\TestCase;
  include("./TokenManager.php");

  class TokenManagerTest extends TestCase
  {
    public function testGenerateFormToken() {
      $t = new TokenManager();
      $form = "testForm";
      $token = $t->generateFormToken($form);

      $this->assertTrue(isset($_SESSION[$form.'_token']));
      $this->assertEquals(64, strlen($token));
    }

    public function testVerifyToken()
    {
      $t = new TokenManager();
      $form = "testForm";

      // token not generated
      $this->assertFalse($t->verifyToken($form));

      // generate token
      $token = $t->generateFormToken($form);

      // bad post value
      $_POST['token'] = "AAA";
      $this->assertFalse($t->verifyToken($form));

      // good post value
      $_POST['token'] = $token;
      $this->assertTrue($t->verifyToken($form));

    }

  }
?>
