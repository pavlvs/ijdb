<?php
namespace Ijdb\Controllers;
use \Ninja\DatabaseTable;

class Register
{
   private $authorsTable;

   public function __construct(DatabaseTable $authorsTable)
   {
       $this->authorsTable = $authorsTable;
   }

   public function showForm()
   {
       echo '';
   }
}