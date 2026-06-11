<?php

enum BatchStatus:string
{
    case OK = 'OK';
    case WARNING = 'WARNING';
    case CRITICAL = 'CRITICAL';
    case EXPIRED = 'EXPIRED';
    case RETURN_PROCESS = 'RETURN_PROCESS';
}