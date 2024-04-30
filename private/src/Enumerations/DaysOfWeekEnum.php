<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project DaysOfWeekEnum.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\Examples\Enumerations;

use JetBrains\PhpStorm\Pure;

/**
 * TODO: Class documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
enum DaysOfWeekEnum: string {
    case SUNDAY = "sunday";
    case MONDAY = "monday";
    case TUESDAY = "tuesday";
    case WEDNESDAY = "wednesday";
    case THURSDAY = "thursday";
    case FRIDAY = "friday";
    case SATURDAY = "saturday";
    
    /**
     * TODO: Function documentation
     *
     * @param string $value
     * @return DaysOfWeekEnum
     *
     * @author Marc-Eric Boury
     * @since  2024-03-14
     */
    #[Pure] public static function getFromStringValue(string $value) : DaysOfWeekEnum {
        return DaysOfWeekEnum::from(strtolower($value));
    }
    
}
