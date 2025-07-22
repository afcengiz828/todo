<?php
namespace App\Enums;

enum Status : string{
    case pending = "pending";
    case inprogress = "inprogress";
    case completed = "completed";
    case cancelled = "cancelled";
}

?>