<?php
class JModelLegacy
{
    public function __construct($config = []) {}
    public function getDbo() { return null; }
}

class JModelAdmin extends JModelLegacy {}
class JModelList  extends JModelLegacy {}
