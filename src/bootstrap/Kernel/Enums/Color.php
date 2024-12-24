<?php
namespace OlympiaWorkout\bootstrap\Kernel\Enums;

enum Color: string {
    case RESET   = "\033[0m";     
    case GREEN   = "\033[32m";    
    case BLUE    = "\033[34m";     
    case YELLOW  = "\033[33m";    
    case RED     = "\033[31m";   
    case CYAN    = "\033[36m";    
    case MAGENTA = "\033[35m";     
    case WHITE   = "\033[37m";     
    case BOLD    = "\033[1m";      
}