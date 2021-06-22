<?php

/**
 * Interface IMySQLDriver
 */
interface IMySQLDriver{
    /**
     * @param $id
     * @return mixed
     */
    public function findProduct($id);
}