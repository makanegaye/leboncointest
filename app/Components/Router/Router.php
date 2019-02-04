<?php
/**
 * Created by PhpStorm.
 * User: Makane GAYE
 * Date: 04/02/2019
 * Time: 19:07
 */

namespace App\Components\Router;

class Router
{
    /**
     * Redirection vers une url avec des options
     *
     * @param string $url
     * @param array $options
     */
    public function toRoute(string $url, array $options = array())
    {
        foreach($options as $key => $option) {
            if(is_int($key)) {
                header("$option");
            } else {
                header("$key: $option");
            }
        }
        header("Location: $url");
    }
}