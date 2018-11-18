<?php

namespace Radkod\Xenforo2\XenforoBridge\Contracts;

interface Factory
{
    public function user();
    public function check();
    public function csrf();
    public function id();
    public function photo();
    public function logout();
}
