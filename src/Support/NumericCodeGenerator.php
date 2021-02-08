<?php


namespace Support;


class NumericCodeGenerator
{

    public function execute(int $length = 6): int
    {
        return rand((int)str_pad(1, $length, '0', STR_PAD_RIGHT), (int)str_pad(9, $length, '9', STR_PAD_RIGHT));
    }
}
