<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    // protected function setUp(): void
    // {

    //     parent::setUp();

    //     $this->withoutExceptionHandling();
    // }
}
