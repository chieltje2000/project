<?php

function stripZerosFromDate( $markedString="" )
{
    // First remove marked zeros
    $noZeros = str_replace('*0','',$markedString);
    // Then remove any remaining marks
    $cleanedString = str_replace('*','',$noZeros);
    return $cleanedString;
}

function redirectTo($location=null)
{
    if (($location!=null))
    {
        header("Location: {$location}");
        exit;
    }
}

function outputMessage($message="")
{
    if(!empty($message))
    {
        return "<p class=\"message\">{$message}</p>";
    }
    else
    {
        return "";
    }
}

function __autoload($className)
{
    $className = strtolower($className);
    $path = LIB_PATH.DS."{$className}.php";
    if(file_exists($path))
    {
        require_once ($path);
    }
    else
    {
        die("The file {$className}.php could not be found.");
    }
}

function includeLayoutTemplate($template="")
{
    include (SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function logAction($action, $message="")
{
    $logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
    if($handle = fopen($logfile, 'a')) //append
    {
        $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
        $content = "{$timestamp} | {$action}: {$message}\n";
        fwrite($handle, $content);
        fclose($handle);
    }
    else
    {
        echo "Could not open log file for writting.";
    }
}

function datetimeToText($datetime="")
{
    $unixdatetime = strtotime($datetime);
    return strftime("%B %d, %Y at %H:%M", $unixdatetime);
}