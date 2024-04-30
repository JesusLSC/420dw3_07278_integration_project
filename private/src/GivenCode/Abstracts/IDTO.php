<?php
declare(strict_types=1);

/*
 * 420DW3_07278_Project IDTO.php
 * 
 * @author Marc-Eric Boury (MEbou)
 * @since 2024-03-14
 * (c) Copyright 2024 Marc-Eric Boury 
 */

namespace Teacher\GivenCode\Abstracts;

/**
 * TODO: Interface documentation
 *
 * @author Marc-Eric Boury
 * @since  2024-03-14
 */
interface IDTO {
    
    /**
     * Returns the database primary key value of this DTO object.
     *
     * @return int
     *
     * @author Marc-Eric Boury
     * @since  2024-03-14
     */
    public function getPrimaryKeyValue() : int;
    
    /**
     * Returns the database column name for the primary key of this DTO object.
     *
     * @return string
     *
     * @author Marc-Eric Boury
     * @since  2024-03-14
     */
    public function getPrimaryKeyColumnName() : string;
    
    /**
     * Returns the database table name of this DTO object
     *
     * @return string
     *
     * @author Marc-Eric Boury
     * @since  2024-03-14
     */
    public function getDatabaseTableName() : string;
    
}