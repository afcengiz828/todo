<?php
namespace App\Enums;

enum Status : string{
    case pending = "pending";
    case inprogress = "in_progress";
    case completed = "completed";
    case cancelled = "cancelled";
}

?>