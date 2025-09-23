<?php
// app/Enums/PerPage.php

namespace App\Enums;

enum PerPage: int
{
    case TEN = 10;
    case TWENTY = 20;
    case FIFTY = 50;
    case HUNDRED = 100;
}
